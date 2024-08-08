<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PouetController extends AbstractController
{
    #[Route('/pouet')]
    public function index(): Response
    {
        return new Response("pouet pouet !");
    }
}
