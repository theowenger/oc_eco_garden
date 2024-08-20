<?php

declare(strict_types=1);

namespace App\Controller\AdviceController;

use App\Entity\Advice;
use App\Entity\Month;
use App\Tools\ValidationTool;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreateAdviceController extends AbstractController
{
    #[Route('/api/advice', methods: ['POST'])]
    #[IsGranted("ROLE_ADMIN")]
    #[Security(name: 'Bearer')]
    #[OA\Tag(name: 'Admin_Advice')]
    #[OA\RequestBody(
        description: "Create an advice",
        required: true,
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: "content", type: "string", example: "Après la pluie vient le beau temps"),
                new OA\Property(
                    property: "months",
                    type: "array",
                    items: new OA\Items(type: "integer"),
                    example: [1, 3, 5, 9]
                ),
            ],
            type: "object"
        )
    )]
    #[OA\Response(
        response: 201,
        description: 'Return advice created.')]
    #[OA\Response(
        response: 400,
        description: 'Bad-request: invalid request.')]
    #[OA\Response(
        response: 401,
        description: 'Unauthorized: user is not logged in')]
    #[OA\Response(
        response: 403,
        description: 'Forbidden: user doesn\'t have permissions to create advice')]
    public function __invoke(
        EntityManagerInterface $entityManager,
        Request                $request,
        ValidatorInterface     $validator,
        ValidationTool         $validationTool
    ): Response
    {
        try {
            $monthRepository = $entityManager->getRepository(Month::class);

            $jsonContent = $request->getContent();
            $data = json_decode($jsonContent, true, 512, JSON_THROW_ON_ERROR);

            $adviceContent = $data["content"] ?? null;
            $adviceMonths = $data["months"] ?? [];

            if ($adviceContent === null || $adviceContent === "") {
                return new JsonResponse("content must be fill", Response::HTTP_BAD_REQUEST);
            }

            if ($adviceMonths === []) {
                return new JsonResponse("advices Month must be greater than 0", Response::HTTP_BAD_REQUEST);
            }

            $advice = new Advice();

            $advice->setContent($adviceContent);


            $validationResponse = $validationTool::validateEntity($advice, $validator);
            if ($validationResponse !== null) {
                return $validationResponse;
            }


            foreach ($adviceMonths as $monthId) {
                /** @var Month $month */
                $month = $monthRepository->find($monthId);

                if ($month === null) {
                    return new JsonResponse("month $monthId not found", Response::HTTP_BAD_REQUEST);
                }

                $advice->addMonth($month);
            }
            $entityManager->persist($advice);
            $entityManager->flush();

            return new JsonResponse($advice, Response::HTTP_CREATED);
        } catch (\Exception $exception) {
            return new JsonResponse($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
}
