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

class DeleteAdviceController extends AbstractController
{
    #[Route('/api/advice/{id}',  methods: ['DELETE'])]
    #[IsGranted("ROLE_ADMIN")]
    #[Security(name: 'Bearer')]
    #[OA\Tag(name: 'Admin_Advice')]
    #[OA\Parameter(
        name: "id",
        description: "ID of the advice",
        in: "path",
        required: true,
        schema: new OA\Schema(type: "integer", example: 1)
    )]
    #[OA\Response(
        response: 204,
        description: 'No-content: Returns advice deleted.',)]
    #[OA\Response(
        response: 401,
        description: 'Unauthorized: user is not logged in')]
    #[OA\Response(
        response: 403,
        description: 'Forbidden: user doesn\'t have permissions to remove advice')]
    #[OA\Response(
        response: 404,
        description: 'Not found: advice id doesn\'t exist')]

    public function __invoke(EntityManagerInterface $entityManager): Response
    {

        return new JsonResponse("supprimer un conseil role admin", Response::HTTP_NO_CONTENT);
    }
}
