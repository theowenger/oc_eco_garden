<?php

declare(strict_types=1);

namespace App\Controller\WeatherController;

use App\Entity\Month;
use Doctrine\ORM\EntityManagerInterface;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class GetWeatherCurrentCityController extends AbstractController
{
    #[Route('/api/weather/',  methods: ['GET'])]
    #[OA\Tag(name: 'Weather')]
    #[OA\Response(
        response: 200,
        description: 'Returns weather report for the default city of user')]
    #[OA\Response(
        response: 401,
        description: 'Unauthorized: user is not logged in')]

    public function __invoke(EntityManagerInterface $entityManager): Response
    {
        $monthRepository = $entityManager->getRepository(Month::class);

        /** @var Month $mounth */
        $mounth = $monthRepository->find(4);


        dd($mounth->getAdvices()[0]->getContent());


        return new Response("meteo avec ville par defaut du compte user");
    }
}
