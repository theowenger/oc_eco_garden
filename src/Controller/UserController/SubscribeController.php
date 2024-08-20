<?php

declare(strict_types=1);

namespace App\Controller\UserController;

use App\Entity\User;
use App\Tools\ValidationTool;
use Doctrine\ORM\EntityManagerInterface;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SubscribeController extends AbstractController
{
    /**
     * @throws \JsonException
     */
    #[Route('/user', methods: ['POST'])]
    #[OA\Tag(name: 'User')]
    #[OA\RequestBody(
        description: "User Subscribe credentials",
        required: true,
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: "username", type: "string", example: "user_0@example.com"),
                new OA\Property(property: "password", type: "string", example: "Secret123!"),
                new OA\Property(property: "password-verification", type: "string", example: "Secret123!"),
                new OA\Property(property: "city", type: "string", example: "Montpellier")

            ],
            type: "object"
        )
    )]
    #[OA\Response(
        response: 201,
        description: 'Returns account created successfully')]
    #[OA\Response(
        response: 400,
        description: 'invalid credentials')]
    #[OA\Response(
        response: 409,
        description: 'Conflict: the credentials already exists')]
    public function __invoke(
        EntityManagerInterface $entityManager,
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        ValidatorInterface $validator,
        ValidationTool $validationTool
    ): Response
    {

        try {
            $jsonContent = $request->getContent();
            $data = json_decode($jsonContent, true, 512, JSON_THROW_ON_ERROR);

            $username = $data['username'] ?? null;
            $password = $data['password'] ?? null;
            $passwordVerification = $data['password-verification'] ?? null;
            $city = $data['city'] ?? null;

            if ($password !== $passwordVerification) {
                return new JsonResponse("passwords do not match", Response::HTTP_BAD_REQUEST);
            }


            $user = new User();

            $user
                ->setEmail($username)
                ->setCity($city)
                ->setPassword($password);

            $validationResponse = $validationTool::validateEntity($user, $validator);
            if ($validationResponse !== null) {
                return $validationResponse;
            }

            $encodedPassword = $passwordHasher->hashPassword($user, $password);
            $user->setPassword($encodedPassword);

            $entityManager->persist($user);
            $entityManager->flush();

            return new JsonResponse("Account created successfully", Response::HTTP_CREATED);
        } catch (\Exception $exception) {
            return new JsonResponse($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
}
