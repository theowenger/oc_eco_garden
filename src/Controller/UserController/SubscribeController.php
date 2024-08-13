<?php

declare(strict_types=1);

namespace App\Controller\UserController;

use App\Entity\Month;
use Doctrine\ORM\EntityManagerInterface;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SubscribeController extends AbstractController
{
    #[Route('/user',  methods: ['POST'])]
    #[OA\Tag(name: 'User')]
    #[OA\Response(
        response: 201,
        description: 'Returns account created successfully')]
    #[OA\Response(
        response: 400,
        description: 'invalid credentials')]
    #[OA\Response(
        response: 409,
        description: 'Conflict: the credentials already exists')]

    public function __invoke(EntityManagerInterface $entityManager): Response
    {

        return new JsonResponse("Vous etes inscrit !", Response::HTTP_CREATED);
    }
}
