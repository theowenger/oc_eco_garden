<?php

declare(strict_types=1);

namespace App\Controller\WeatherController;

use App\Entity\Month;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Throwable;

class GetWeatherChoosenCityController extends AbstractController
{
    #[Route('/api/weather/{city}',  methods: ['GET'])]
    #[OA\Tag(name: 'Weather')]
    #[Security(name: 'Bearer')]
    #[OA\Parameter(
        name: "city",
        description: "name of the city",
        in: "path",
        required: true,
        schema: new OA\Schema(type: "string", example: "Nîmes")
    )]
    #[OA\Response(
        response: 200,
        description: 'Returns weather report of the city in the path')]
    #[OA\Response(
        response: 401,
        description: 'Unauthorized: user is not logged in')]
    #[OA\Response(
        response: 404,
        description: 'Not found: city in path doesn\'t exist')]

    public function __invoke(
        EntityManagerInterface $entityManager,
        Request $request,
        string $city,
    ): Response
    {
        try{
            $apiKey = $_ENV['WEATHER_API_KEY'];
            $apiAdress = $_ENV['WEATHER_API_URL'];
            $url = "{$apiAdress}?key={$apiKey}&q={$city}";

            $client = HttpClient::create();
            $response = $client->request("GET", $url);

            $statusCode = $response->getStatusCode();
            $content = $response->getContent();

            $data = json_decode($content, true, 512, JSON_THROW_ON_ERROR);

            return new JsonResponse($data, $statusCode);
        }catch (ClientExceptionInterface $e) {
            if ($e->getCode() === 400) {
                return new JsonResponse("city doesn't exist", Response::HTTP_NOT_FOUND);
            }

            return new JsonResponse($e->getMessage(), Response::HTTP_BAD_REQUEST);
        } catch (Throwable $e){
            return new JsonResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }
}
