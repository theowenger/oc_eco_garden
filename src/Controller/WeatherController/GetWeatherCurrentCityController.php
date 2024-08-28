<?php

declare(strict_types=1);

namespace App\Controller\WeatherController;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security as SecurityBundle;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Throwable;

class GetWeatherCurrentCityController extends AbstractController
{
    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     * @throws \JsonException
     */
    #[Route('/api/weather/', methods: ['GET'])]
    #[OA\Tag(name: 'Weather')]
    #[Security(name: 'Bearer')]
    #[OA\Response(
        response: 200,
        description: 'Returns weather report for the default city of user')]
    #[OA\Response(
        response: 401,
        description: 'Unauthorized: user is not logged in')]
    public function __invoke(
        EntityManagerInterface $entityManager,
        SecurityBundle         $security,
        Request                $request
    ): Response
    {

        try {


            /** @var User $user */
            $user = $security->getUser();
            $city = $user->getCity();

            $apiKey = $_ENV['WEATHER_API_KEY'];
            $apiAdress = $_ENV['WEATHER_API_URL'];
            $url = "{$apiAdress}?key={$apiKey}&q={$city}";

            $client = HttpClient::create();
            $response = $client->request("GET", $url);

            $statusCode = $response->getStatusCode();
            $content = $response->getContent();

            $data = json_decode($content, true, 512, JSON_THROW_ON_ERROR);

            return new JsonResponse($data, $statusCode);
        } catch (ClientExceptionInterface $e) {
            if ($e->getCode() === 400) {
                return new JsonResponse("city doesn't exist", Response::HTTP_NOT_FOUND);
            }

            return new JsonResponse($e->getMessage(), Response::HTTP_BAD_REQUEST);
        } catch (Throwable $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
