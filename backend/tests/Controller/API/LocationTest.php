<?php

namespace App\Tests\Controller\API;

use App\Entity\Location;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LocationTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();

        $client->request('GET', '/api/v1/location');

        $items = json_decode($client->getResponse()->getContent());

        $this->assertResponseIsSuccessful();
        $this->assertCount(2, $items);
    }
}
