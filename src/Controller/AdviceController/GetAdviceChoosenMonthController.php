<?php

declare(strict_types=1);

namespace App\Controller\AdviceController;

use App\Entity\Month;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Id;
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

    public function __invoke(EntityManagerInterface $entityManager, int $id): Response
    {
        try {


        $monthRepository = $entityManager->getRepository(Month::class);
        /** @var Month $selectedMonth */
        $selectedMonth = $monthRepository->find($id);

        if($selectedMonth === null) {
            return new JsonResponse("Month not found", Response::HTTP_BAD_REQUEST);
        }

        $monthAdvices = $selectedMonth->getAdvices();

        $advicesArray = [];
        foreach ($monthAdvices as $advice) {
            $advicesArray[] = [
                'id' => $advice->getId(),
                'content' => $advice->getContent(),
            ];
        }

        return new JsonResponse($advicesArray, response::HTTP_OK);
        } catch (\Exception $exception) {
            return new JsonResponse($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
