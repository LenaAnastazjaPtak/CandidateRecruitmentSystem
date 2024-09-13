<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class MainController extends AbstractController
{
    public function header(): JsonResponse|Response
    {
        return $this->render('header.html.twig', [
            'user' => $this->getUser()
        ]);
    }
}
