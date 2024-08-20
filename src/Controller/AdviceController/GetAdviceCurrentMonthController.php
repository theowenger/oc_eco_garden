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

class GetAdviceCurrentMonthController extends AbstractController
{
    #[Route('/api/advice/', methods: ['GET'])]
    #[Security(name: 'Bearer')]
    #[OA\Tag(name: 'Advice')]
    #[OA\Response(
        response: 200,
        description: 'Returns all advices of the current month')]
    #[OA\Response(
        response: 401,
        description: 'Unauthorized: user is not logged in')]
    public function __invoke(EntityManagerInterface $entityManager): Response
    {

        try {
            $currentMonth = date('m');

            $monthRepository = $entityManager->getRepository(Month::class);
            /** @var Month $selectedMonth */
            $selectedMonth = $monthRepository->find($currentMonth);
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
