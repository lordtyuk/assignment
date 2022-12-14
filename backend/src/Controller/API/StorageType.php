<?php

declare(strict_types=1);

namespace App\Controller\API;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/api/v1/storage-type")
 */
class StorageType extends AbstractAPIController
{
    public function __construct(SerializerInterface $serializer, EntityManagerInterface $entityManager)
    {
        parent::__construct($serializer, $entityManager->getRepository(\App\Entity\StorageType::class));
    }
}
