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

class GetAdviceChoosenMonthController extends AbstractController
{
    #[Route('/api/advice/{id}',  methods: ['GET'])]
    #[Security(name: 'Bearer')]
    #[OA\Tag(name: 'Advice')]
    #[OA\Parameter(
        name: "id",
        description: "ID of the mounth",
        in: "path",
        required: true,
        schema: new OA\Schema(type: "integer", example: 1)
    )]
    #[OA\Response(
        response: 200,
        description: 'Returns all advices linked to the month id in the path')]
    #[OA\Response(
        response: 401,
        description: 'Unauthorized: user is not logged in')]
    #[OA\Response(
        response: 400,
        description: 'Bad-request: mounth id doesn\'t exist')]

    public function __invoke(EntityManagerInterface $entityManager): Response
    {

        return new JsonResponse("Page de conseil avec id du mois dans le path", response::HTTP_OK);
    }
}
