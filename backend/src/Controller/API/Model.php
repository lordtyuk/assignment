<?php

declare(strict_types=1);

namespace App\Controller\API;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/api/v1/model")
 */
class Model extends AbstractAPIController
{
    private RequestStack $requestStack;

    public function __construct(SerializerInterface $serializer, EntityManagerInterface $entityManager, RequestStack $requestStack)
    {
        parent::__construct($serializer, $entityManager->getRepository(\App\Entity\Model::class));
        $this->requestStack = $requestStack;
    }

    /**
     * @Route("", methods={"GET"})
     *
     * @return Response
     */
    public function getAll(): Response
    {
        $items = $this->repository->getByFilters(
            $this->requestStack->getCurrentRequest()->get('name') ?? '',
            $this->requestStack->getCurrentRequest()->get('ram') ?? [],
            $this->requestStack->getCurrentRequest()->get('storage_type') ?? [],
            $this->requestStack->getCurrentRequest()->get('storage_size_min') ?? 0,
            $this->requestStack->getCurrentRequest()->get('storage_size_max') ?? 0,
            $this->requestStack->getCurrentRequest()->get('location') ?? '',
        );

        return new Response($this->serializer->serialize($items, JsonEncoder::FORMAT, ['groups' => ['api'], AbstractObjectNormalizer::ENABLE_MAX_DEPTH => true, AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function () {
            return null;
        }]), Response::HTTP_OK, [
            'X-Total-Count' => count($items)
        ]);
    }
}
