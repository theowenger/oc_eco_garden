<?php

declare(strict_types=1);

namespace App\Controller\AdviceController;

use App\Entity\Month;
use Doctrine\ORM\EntityManagerInterface;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CreateAdviceController extends AbstractController
{
    #[Route('/api/advice',  methods: ['POST'])]
    #[OA\Tag(name: 'Admin_Advice')]
    #[OA\Response(
        response: 201,
        description: 'Return advice added.')]
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
        $monthRepository = $entityManager->getRepository(Month::class);

        /** @var Month $mounth */
        $mounth = $monthRepository->find(4);


        dd($mounth->getAdvices()[0]->getContent());


        return new Response("creer un conseil role admin");
    }
}
