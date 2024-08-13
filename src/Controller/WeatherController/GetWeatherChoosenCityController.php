<?php

declare(strict_types=1);

namespace App\Controller\WeatherController;

use App\Entity\Month;
use Doctrine\ORM\EntityManagerInterface;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class GetWeatherChoosenCityController extends AbstractController
{
    #[Route('/api/weather/{city}',  methods: ['GET'])]
    #[OA\Tag(name: 'Weather')]
    #[OA\Response(
        response: 200,
        description: 'Returns weather report of the city in the path')]
    #[OA\Response(
        response: 401,
        description: 'Unauthorized: user is not logged in')]
    #[OA\Response(
        response: 404,
        description: 'Not found: city in path doesn\'t exist')]

    public function __invoke(EntityManagerInterface $entityManager): Response
    {
        $monthRepository = $entityManager->getRepository(Month::class);

        /** @var Month $mounth */
        $mounth = $monthRepository->find(4);


        dd($mounth->getAdvices()[0]->getContent());


        return new Response("meteo avec une ville donnée");
    }
}
