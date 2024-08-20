<?php

declare(strict_types=1);

namespace App\Controller\UserController;

use App\Entity\Month;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Throwable;

class DeleteUserController extends AbstractController
{
    #[Route('/api/user/{id}',  methods: ['DELETE'])]
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
        response: 204,
        description: 'No-content: Returns removed user successfully')]
    #[OA\Response(
        response: 401,
        description: 'Unauthorized: user is not logged in')]
    #[OA\Response(
        response: 403,
        description: 'Forbidden: user doesn\'t have permissions to deleter user')]
    #[OA\Response(
        response: 404,
        description: 'Not found: user id doesn\'t exist')]

    public function __invoke(EntityManagerInterface $entityManager, int $id): Response
    {

        try{

            $userRepository = $entityManager->getRepository(User::class);
            $user = $userRepository->find($id);

            if($user === null){
                return new JsonResponse("User not found", Response::HTTP_NOT_FOUND);
            }

            $entityManager->remove($user);
            $entityManager->flush();

            return new JsonResponse("user deleted", response::HTTP_NO_CONTENT);
        } catch (Throwable $e){
            return new JsonResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
