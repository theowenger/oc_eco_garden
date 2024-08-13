<?php

declare(strict_types=1);

namespace App\Controller\UserController;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class LoginController extends AbstractController
{
    /**
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws \JsonException
     */
    #[Route('/auth', methods: ['POST'])]
    #[OA\Tag(name: 'User')]
    #[OA\RequestBody(
        description: "User login credentials",
        required: true,
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: "username", type: "string", example: "user_0@example.com"),
                new OA\Property(property: "password", type: "string", example: "secret")
            ],
            type: "object"
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Returns user logged in successfully.',)]
    #[OA\Response(
        response: 400,
        description: 'bad-request : incorrect or missing credentials.',)]
    #[OA\Response(
        response: 401,
        description: 'unauthorized : incorrect username or password.',)]
    public function __invoke(EntityManagerInterface $entityManager, Request $request): Response
    {
        $jsonContent = $request->getContent();
        $data = json_decode($jsonContent, true, 512, JSON_THROW_ON_ERROR);

        $username = $data['username'] ?? null;
        $password = $data['password'] ?? null;

        if (!$username || !$password) {
            return new JsonResponse(['error' => 'Missing username or password'], Response::HTTP_BAD_REQUEST);
        }
        // Crée une instance du client HTTP
        $client = HttpClient::create();

        // Fait la requête POST vers la route /api/login_check
        $response = $client->request('POST', 'http://eco_garden_nginx/api/login_check', [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'body' => json_encode(['username' => $username, 'password' => $password], JSON_THROW_ON_ERROR),
        ]);

        if($response->getStatusCode() !== Response::HTTP_OK) {
            return new JsonResponse(['error' => "incorrect username or password"], Response::HTTP_UNAUTHORIZED);
        }

        return new Response($response->getContent(), $response->getStatusCode(), $response->getHeaders());
    }
}
