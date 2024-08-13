<?php

declare(strict_types=1);

namespace App\Controller\AdviceController;

use App\Entity\Month;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class CreateAdviceController extends AbstractController
{
    #[Route('/api/advice',  methods: ['POST'])]
    #[IsGranted("ROLE_ADMIN")]
    #[Security(name: 'Bearer')]
    #[OA\Tag(name: 'Admin_Advice')]
    #[OA\Response(
        response: 201,
        description: 'Return advice created.')]
    #[OA\Response(
        response: 400,
        description: 'Bad-request: invalid request.')]
    #[OA\Response(
        response: 401,
        description: 'Unauthorized: user is not logged in')]
    #[OA\Response(
        response: 403,
        description: 'Forbidden: user doesn\'t have permissions to create advice')]

    public function __invoke(EntityManagerInterface $entityManager): Response
    {

        return new JsonResponse("creer un conseil role admin", Response::HTTP_CREATED);
    }
}
