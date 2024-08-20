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
use Throwable;

class EditAdviceController extends AbstractController
{
    #[Route('/api/advice/{id}', methods: ['PUT'])]
    #[IsGranted("ROLE_ADMIN")]
    #[Security(name: 'Bearer')]
    #[OA\Tag(name: 'Admin_Advice')]
    #[OA\Parameter(
        name: "id",
        description: "ID of the advice",
        in: "path",
        required: true,
        schema: new OA\Schema(type: "integer", example: 1)
    )]
    #[OA\RequestBody(
        description: "Advice to edit",
        required: true,
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: "content", type: "string", example: "Il faut de l'eau dans le pastis"),
                new OA\Property(
                    property: "months",
                    type: "array",
                    items: new OA\Items(type: "integer"),
                    example: [1, 8, 12]
                ),
            ],
            type: "object"
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Returns advice edited successfully',)]
    #[OA\Response(
        response: 401,
        description: 'Unauthorized: user is not logged in')]
    #[OA\Response(
        response: 403,
        description: 'Forbidden: user doesn\'t have permissions to edit advice')]
    #[OA\Response(
        response: 404,
        description: 'Not found: advice id doesn\'t exist')]
    public function __invoke(
        EntityManagerInterface $entityManager,
        Request $request,
        ValidatorInterface $validator,
        int $id,
        ValidationTool $validationTool
    ): Response
    {
        try {
            $adviceRepository = $entityManager->getRepository(Advice::class);
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

        /** @var Advice $advice */
            $advice = $adviceRepository->find($id);

            if($advice === null){
                return new JsonResponse("advice not found", Response::HTTP_BAD_REQUEST);
            }

            if($advice->getContent() !== $adviceContent){
                $advice->setContent($adviceContent);
            }

            foreach ($advice->getMonths() as $month) {
                $advice->removeMonth($month);
            }

            foreach ($adviceMonths as $adviceMonth) {
                /** @var Month $monthToInsert */
                $monthToInsert = $monthRepository->find($adviceMonth);
                if($monthToInsert !== null){
                    $advice->addMonth($monthToInsert);
                }
            }


            $validationResponse = $validationTool::validateEntity($advice, $validator);
            if ($validationResponse !== null) {
                return $validationResponse;
            }

            $entityManager->persist($advice);
            $entityManager->flush();

            return new JsonResponse($advice, Response::HTTP_OK);
        } catch (Throwable $exception) {
            return new JsonResponse($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
