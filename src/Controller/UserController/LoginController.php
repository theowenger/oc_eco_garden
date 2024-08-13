<?php

declare(strict_types=1);

namespace App\Controller\UserController;

use App\Entity\Month;
use Doctrine\ORM\EntityManagerInterface;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LoginController extends AbstractController
{
    #[Route('/auth',  methods: ['POST'])]
    #[OA\Tag(name: 'User')]
    #[OA\Response(
        response: 200,
        description: 'Returns user logged in successfully.',)]
    #[OA\Response(
        response: 401,
        description: 'unauthorized : incorrect username or password.',)]

    public function __invoke(EntityManagerInterface $entityManager): Response
    {
        $monthRepository = $entityManager->getRepository(Month::class);

        /** @var Month $mounth */
        $mounth = $monthRepository->find(4);


        dd($mounth->getAdvices()[0]->getContent());


        return new Response("Vous etes connectés");
    }
}
