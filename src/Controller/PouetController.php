<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Month;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;

class PouetController extends AbstractController
{
    //#[Route('/pouet',  methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Returns the rewards of an user')]

    public function __invoke(EntityManagerInterface $entityManager): Response
    {
//        $monthRepository = $entityManager->getRepository(Month::class);
//
//        /** @var Month $mounth */
//        $mounth = $monthRepository->find(4);
//
//
//        dd($mounth->getAdvices()[0]->getContent());


        return new Response("pouet pouet !");
    }
}
