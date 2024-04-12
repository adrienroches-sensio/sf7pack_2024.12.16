<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_main_index')]
    public function index(Request $request): Response
    {
        $name = $request->query->get('name', 'World');

        return $this->render('main/index.html.twig', [
            'name' => $name,
        ]);
    }

    #[Route('/contact', name: 'app_main_contact')]
    public function contact(): Response
    {
        return new Response('<html><body><h1>Contact</h1></body>');
    }
}
