<?php

declare(strict_types=1);

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Model")
 *
 */
class Model
{
    /**
     * @Groups({"api"})
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTime $updatedAt;

    /**
     * @Groups({"api"})
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private string $name = '';

    /**
     * @Groups({"api"})
     * @ORM\Column(type="integer")
     */
    private int $ramCount = 0;

    /**
     * @Groups({"api"})
     * @ORM\ManyToOne(targetEntity="RamType")
     */
    private ?RamType $ramType = null;

    /**
     * @Groups({"api"})
     * @ORM\Column(type="integer")
     */
    private int $storageCount = 0;

    /**
     * @Groups({"api"})
     * @ORM\Column(type="integer")
     */
    private int $storageSizeGB = 0;

    /**
     * @Groups({"api"})
     * @ORM\ManyToOne(targetEntity="StorageType")
     */
    private ?StorageType $storageType = null;

    /**
     * @Groups({"api"})
     * @ORM\ManyToOne(targetEntity="Location")
     */
    private ?Location $location = null;

    /**
     * @Groups({"api"})
     * @ORM\ManyToOne(targetEntity="Currency")
     */
    private ?Currency $currency = null;

    /**
     * @Groups({"api"})
     * @ORM\Column(type="decimal")
     */
    private float $price;

    /**
     * @Groups({"api"})
     * @ORM\Column(type="decimal")
     */
    private float $usdPrice;

    public function __construct() {
        $this->updatedAt = new DateTime('now');
    }


    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getRamCount(): int
    {
        return $this->ramCount;
    }

    /**
     * @param int $ramCount
     */
    public function setRamCount(int $ramCount): void
    {
        $this->ramCount = $ramCount;
    }

    /**
     * @return RamType|null
     */
    public function getRamType(): ?RamType
    {
        return $this->ramType;
    }

    /**
     * @param RamType|null $ramType
     */
    public function setRamType(?RamType $ramType): void
    {
        $this->ramType = $ramType;
    }

    /**
     * @return int
     */
    public function getStorageCount(): int
    {
        return $this->storageCount;
    }

    /**
     * @param int $storageCount
     */
    public function setStorageCount(int $storageCount): void
    {
        $this->storageCount = $storageCount;
    }

    /**
     * @return int
     */
    public function getStorageSizeGB(): int
    {
        return $this->storageSizeGB;
    }

    /**
     * @param int storageSizeGB
     */
    public function setStorageSizeGB(int $storageSizeGB): void
    {
        $this->storageSizeGB = $storageSizeGB;
    }

    /**
     * @return StorageType|null
     */
    public function getStorageType(): ?StorageType
    {
        return $this->storageType;
    }

    /**
     * @param StorageType|null $storageType
     */
    public function setStorageType(?StorageType $storageType): void
    {
        $this->storageType = $storageType;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @param float $price
     */
    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    /**
     * @return Location|null
     */
    public function getLocation(): ?Location
    {
        return $this->location;
    }

    /**
     * @param Location|null $location
     */
    public function setLocation(?Location $location): void
    {
        $this->location = $location;
    }

    /**
     * @return Currency|null
     */
    public function getCurrency(): ?Currency
    {
        return $this->currency;
    }

    /**
     * @param Currency|null $currency
     */
    public function setCurrency(?Currency $currency): void
    {
        $this->currency = $currency;
    }

    /**
     * @return float
     */
    public function getUsdPrice(): float
    {
        return $this->usdPrice;
    }

    /**
     * @param float $usdPrice
     */
    public function setUsdPrice(float $usdPrice): void
    {
        $this->usdPrice = $usdPrice;
    }

    /**
     * @return DateTime
     */
    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param DateTime $updatedAt
     */
    public function setUpdatedAt(DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}

