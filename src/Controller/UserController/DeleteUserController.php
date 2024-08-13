<?php

declare(strict_types=1);

namespace App\Controller\UserController;

use App\Entity\Month;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DeleteUserController extends AbstractController
{
    #[Route('/api/user/{id}',  methods: ['DELETE'])]
    #[OA\Tag(name: 'Admin_User')]
    #[OA\Response(
        response: 204,
        description: 'No-content: Returns removed user successfully')]
    #[OA\Response(
        response: 401,
        description: 'Unauthorized: user is not logged in')]
    #[OA\Response(
        response: 403,
        description: 'Forbidden: user doesn\'t have permissions to deleter user')]
    #[OA\Response(
        response: 404,
        description: 'Not found: user id doesn\'t exist')]

    public function __invoke(EntityManagerInterface $entityManager): Response
    {
        $monthRepository = $entityManager->getRepository(Month::class);

        /** @var Month $mounth */
        $mounth = $monthRepository->find(4);


        dd($mounth->getAdvices()[0]->getContent());


        return new Response("Suppression of user account for admin only");
    }
}
