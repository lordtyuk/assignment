<?php

declare(strict_types=1);

namespace App\Controller\API;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

abstract class AbstractAPIController
{

    /* @var EntityRepository|null */
    protected ?EntityRepository $repository = null;

    /* @var null|SerializerInterface */
    protected ?SerializerInterface $serializer = null;

    protected ?string $className = null;


    public function __construct(
        SerializerInterface $serializer,
        EntityRepository $repository
    )
    {
        $this->serializer = $serializer;
        $this->repository = $repository;
    }

    /**
     * @Route("", methods={"GET"})
     *
     * @return Response
     */
    public function getAll(): Response
    {
        $items = $this->repository->findAll();

        return new Response($this->serializer->serialize($items, JsonEncoder::FORMAT, ['groups' => ['api'], AbstractObjectNormalizer::ENABLE_MAX_DEPTH => true, AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function () {
            return null;
        }]), Response::HTTP_OK, [
            'X-Total-Count' => count($items)
        ]);
    }
}
