<?php

namespace App\DataFixtures;

use App\Entity\Currency;
use App\Entity\Location;
use App\Entity\RamType;
use App\Entity\StorageType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $objects = [
            RamType::class => [],
            StorageType::class => [],
            Currency::class => [],
            Location::class => []
        ];

        $locationData = ['ABC-12', 'CDE-13', 'EFG-14'];
        foreach ($locationData as $code) {
            $location = new Location($code);
            $location->setName('NAME-' . $code);

            $manager->persist($location);
            $objects[Location::class][] = $location;
        }

        $namedData = [];
        $namedData[RamType::class] = ['DDR', 'DDR2', 'DDR3'];
        $namedData[StorageType::class] = ['SATA', 'SSD', 'SAS'];
        $namedData[Currency::class] = ['USD', 'EUR'];


        foreach ($namedData as $class => $items) {
            foreach ($items as $name) {
                $item = new $class($name);
                $manager->persist($item);
                $objects[$class][] = $item;
            }
        }

        $manager->flush();
    }
}
