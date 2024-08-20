<?php

declare(strict_types=1);

namespace App\Controller\UserController;

use App\Entity\User;
use App\Tools\ValidationTool;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Throwable;

class EditUserController extends AbstractController
{
    #[Route('/api/user/{id}', methods: ['PUT'])]
    #[IsGranted("ROLE_ADMIN")]
    #[Security(name: 'Bearer')]
    #[OA\Tag(name: 'Admin_User')]
    #[OA\Parameter(
        name: "id",
        description: "ID of the user",
        in: "path",
        required: true,
        schema: new OA\Schema(type: "integer", example: 1)
    )]
    #[OA\RequestBody(
        description: "User credentials to set",
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
        response: 200,
        description: 'Returns editing user successfully.',)]
    #[OA\Response(
        response: 401,
        description: 'Unauthorized: user is not logged in')]
    #[OA\Response(
        response: 403,
        description: 'Forbidden: user doesn\'t have permissions to deleter user')]
    #[OA\Response(
        response: 404,
        description: 'Not found: user id doesn\'t exist')]
    public function __invoke(
        EntityManagerInterface $entityManager,
        ValidationTool         $validationTool,
        ValidatorInterface     $validator,
        Request                $request,
        UserPasswordHasherInterface $passwordHasher,
        int                    $id
    ): Response
    {
        try {
            $jsonContent = $request->getContent();
            $data = json_decode($jsonContent, true, 512, JSON_THROW_ON_ERROR);

            $username = $data['username'] ?? null;
            $password = $data['password'] ?? null;
            $passwordVerification = $data['password-verification'] ?? null;
            $city = $data['city'] ?? null;
            dump($city);

            if ($password !== $passwordVerification) {
                return new JsonResponse("passwords do not match", Response::HTTP_BAD_REQUEST);
            }

            $userRepository = $entityManager->getRepository(User::class);
            /** @var User $user */
            $user = $userRepository->find($id);

            if ($user === null) {
                return new JsonResponse("user not found", Response::HTTP_NOT_FOUND);
            }

            if ($username && $username !== $user->getEmail()) {
                $user->setEmail($username);
            }
            if ($city && $city !== $user->getCity()) {
                $user->setCity($city);
            }
            if ($password) {
                $user->setPassword($password);
                $validationResponse = $validationTool::validateEntity($user, $validator);
                if ($validationResponse !== null) {
                    return $validationResponse;
                }
                $encodedPassword = $passwordHasher->hashPassword($user, $password);
                $user->setPassword($encodedPassword);
            }


            dump($user); // Avant persist
            $entityManager->persist($user);
            $entityManager->flush();
            dump('Changes flushed to database');


            return new JsonResponse("User account edited", Response::HTTP_OK);
        } catch (Throwable $exception) {
            return new JsonResponse($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }
}
