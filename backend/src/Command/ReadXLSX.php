<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\Currency;
use App\Entity\Location;
use App\Entity\Model;
use App\Entity\RamType;
use App\Entity\StorageType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Money\Converter;
use Money\Currencies\ISOCurrencies;
use Money\Exchange\SwapExchange;
use Money\Parser\IntlMoneyParser;
use NumberFormatter;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Row;
use Swap\Builder;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\Psr16Cache;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 */
class ReadXLSX extends Command
{
    private EntityManagerInterface $entityManager;

    private OutputInterface $output;

    private IntlMoneyParser $moneyParser;
    private Converter $moneyConverter;

    private DateTime $updatedAt;

    private array $entityCache = [];

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        EntityManagerInterface $entityManager
    )
    {
        $this->entityManager = $entityManager;
        $this->updatedAt = new DateTime();

        //TODO: move api_keys to config
        $swap = (new Builder(['cache_ttl' => 1440, 'cache_key_prefix' => 'curr-']))
            // Use the Fixer service as first level provider
            ->add('apilayer_fixer', ['api_key' => 'vFJ6Z6EaeAxV75Mrx45viMuYhHDOS8X8'])

            // Use the Currency Data service as first fallback
            ->add('apilayer_currency_data', ['api_key' => 'vFJ6Z6EaeAxV75Mrx45viMuYhHDOS8X8'])
            ->add('apilayer_exchange_rates_data', ['api_key' => 'vFJ6Z6EaeAxV75Mrx45viMuYhHDOS8X8'])
            ->useSimpleCache(new Psr16Cache(new FilesystemAdapter()))
            ->build();
        $currencies = new ISOCurrencies();

        $exchange = new SwapExchange($swap);

        $this->moneyConverter = new Converter(new ISOCurrencies(), $exchange);

        $numberFormatter = new NumberFormatter('en_US', \NumberFormatter::CURRENCY);
        $this->moneyParser = new IntlMoneyParser($numberFormatter, $currencies);

        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('app:read-xlsx')
            ->setDescription('Processing xlsx')
            ->addArgument('path', InputArgument::REQUIRED);
    }

    /**
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->output = $output;

        $reader = new Xlsx();
        $reader->setReadDataOnly(true);

        //$spreadsheet = $reader->load($input->getArgument('path'));

        //FIXME: load file based on argument
        $spreadsheet = $reader->load(__DIR__ . '/../../files/LeaseWeb_servers_filters_assignment .xlsx');

        $rows = $spreadsheet->getActiveSheet()->getRowIterator();
        $rows->next();
        while ($row = $rows->valid()) {
            $this->processRow($rows->current());
            $rows->next();
        }

        $this->entityManager->flush();
        $this->deleteMissingModels();

        return 0;
    }

    private function getEntityByUniqueField(string $entityClass, string $field, string $value)
    {
        if (!isset($this->entityCache[$entityClass])) {
            $this->entityCache[$entityClass] = [];
        }
        if (!isset($this->entityCache[$entityClass][$value])) {
            $entity = $this->entityManager->getRepository($entityClass)->findOneBy([$field => $value]);

            if (!$entity) {
                $entity = new $entityClass($value);
                //$entity->{'set'.ucfirst($field)}($value);

                $this->entityManager->persist($entity);
            }

            $this->entityCache[$entityClass][$value] = $entity;
        }

        return $this->entityCache[$entityClass][$value];
    }

    /**
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws Exception
     */
    private function processRow(Row $row)
    {
        $iterator = $row->getCellIterator('A', 'E');

        $name = $iterator->seek('A')->current()->getValue();
        $ramData = $iterator->seek('B')->current()->getValue();
        $storageData = $iterator->seek('C')->current()->getValue();
        $locationData = $iterator->seek('D')->current()->getValue();
        $priceData = $iterator->seek('E')->current()->getValue();

        $locationCode = substr($locationData, -6);
        $locationName = substr($locationData, 0, -6);

        //FIXME: workaround to handle indonesian dollar
        $priceData = str_replace("S$", "SGD", $priceData);

        $money = $this->moneyParser->parse($priceData);
        $location = $this->getEntityByUniqueField(Location::class, 'code', $locationCode);
        /* @var Location $location */
        $location->setName($locationName);

        $currency = $this->getEntityByUniqueField(Currency::class, 'code', $money->getCurrency()->getCode());

        $ramParts = explode('GB', $ramData);
        if (count($ramParts) !== 2) {
            throw new Exception('Ram parsing error');
        }
        $ramCount = (int)$ramParts[0];
        $ramType = $this->getEntityByUniqueField(RamType::class, 'name', $ramParts[1]);

        preg_match('/(?P<count>[\d]+)x(?P<size>[\d]+)(?P<sizeType>(GB|TB))(?P<type>[a-zA-Z]+)/', $storageData, $storageParts);
        if (!(
            isset($storageParts['count']) &&
            isset($storageParts['size']) &&
            isset($storageParts['sizeType']) &&
            isset($storageParts['type'])
        )) {
            throw new Exception('Storage parsing error');
        }
        $storageCount = (int)$storageParts['count'];
        $storageType = $this->getEntityByUniqueField(StorageType::class, 'name', $storageParts['type']);
        $storageSize = (int)$storageParts['size'] * ($storageParts['sizeType'] === 'GB' ? 1 : 1024);

        $model = $this->getEntityByUniqueField(Model::class, 'name', $name);
        $model->setLocation($location);
        $model->setCurrency($currency);
        $model->setName($name);
        $model->setUsdPrice((float)$this->moneyConverter->convert($money, new \Money\Currency('USD'))->getAmount());

        $model->setRamCount($ramCount);
        $model->setRamType($ramType);

        $model->setStorageCount($storageCount);
        $model->setStorageSizeGB($storageSize);
        $model->setStorageType($storageType);

        $model->setPrice((float)$money->getAmount());
        $model->setUpdatedAt($this->updatedAt);
    }

    private function deleteMissingModels()
    {
        $this->entityManager->createQueryBuilder()
            ->delete(Model::class, 'm')
            ->where('m.updatedAt < :updatedAt')
            ->setParameter('updatedAt', $this->updatedAt)
            ->getQuery()->execute();
    }
}
