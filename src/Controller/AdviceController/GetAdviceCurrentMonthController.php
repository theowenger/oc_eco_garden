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
use Symfony\Component\Serializer\SerializerInterface;

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
    public function __invoke(EntityManagerInterface $entityManager, SerializerInterface $serializer): Response
    {

        try {
            $currentMonth = date('m');

            $monthRepository = $entityManager->getRepository(Month::class);

            /** @var Month $selectedMonth */
            $selectedMonth = $monthRepository->find($currentMonth);
            $monthAdvices = $selectedMonth->getAdvices();

            $monthAdvices = $serializer->normalize($monthAdvices, 'json', ["groups" => "advice"]);


            return new JsonResponse($monthAdvices, response::HTTP_OK);
        } catch (\Exception $exception) {
            return new JsonResponse($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
