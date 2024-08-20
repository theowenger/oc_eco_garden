<?php

declare(strict_types=1);

namespace App\Controller\AdviceController;

use App\Entity\Advice;
use App\Entity\Month;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Throwable;

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

    public function __invoke(EntityManagerInterface $entityManager, int $id): Response
    {

        try {
            $adviceRepository = $entityManager->getRepository(Advice::class);
            $advice = $adviceRepository->find($id);

            if($advice === null) {
                return new JsonResponse("advice not found", Response::HTTP_NOT_FOUND);
            }

            $entityManager->remove($advice);
            $entityManager->flush();

                return new JsonResponse("advice deleted", Response::HTTP_NO_CONTENT);

        } catch (Throwable $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
