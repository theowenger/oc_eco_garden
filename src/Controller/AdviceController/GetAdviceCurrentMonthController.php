<?php

declare(strict_types=1);

namespace App\Controller\AdviceController;

use App\Entity\Month;
use Doctrine\ORM\EntityManagerInterface;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class GetAdviceCurrentMonthController extends AbstractController
{
    #[Route('/api/advice/',  methods: ['GET'])]
    #[OA\Tag(name: 'Advice')]
    #[OA\Response(
        response: 200,
        description: 'Returns all advices of the current month')]
    #[OA\Response(
        response: 401,
        description: 'Unauthorized: user is not logged in')]

    public function __invoke(EntityManagerInterface $entityManager): Response
    {
        $monthRepository = $entityManager->getRepository(Month::class);

        /** @var Month $mounth */
        $mounth = $monthRepository->find(4);


        dd($mounth->getAdvices()[0]->getContent());


        return new Response("Page de conseil du mois en cours");
    }
}
