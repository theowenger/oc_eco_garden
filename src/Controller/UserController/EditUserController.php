<?php

declare(strict_types=1);

namespace App\Controller\UserController;

use App\Entity\Month;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class EditUserController extends AbstractController
{
    #[Route('/api/user/{id}',  methods: ['PUT'])]
    #[IsGranted("ROLE_ADMIN")]
    #[Security(name: 'Bearer')]
    #[OA\Tag(name: 'Admin_User')]
    #[OA\Parameter(
        name: "id",
        description: "ID of the user",
        in: "path",
        required: true,
        schema: new OA\Schema(type: "integer", example: 1)
    )]
    #[OA\Response(
        response: 200,
        description: 'Returns editing user successfully.',)]
    #[OA\Response(
        response: 401,
        description: 'Unauthorized: user is not logged in')]
    #[OA\Response(
        response: 403,
        description: 'Forbidden: user doesn\'t have permissions to deleter user')]
    #[OA\Response(
        response: 404,
        description: 'Not found: user id doesn\'t exist')]

    public function __invoke(EntityManagerInterface $entityManager): Response
    {

        return new JsonResponse("Edit user account for admin only", Response::HTTP_OK);
    }
}
