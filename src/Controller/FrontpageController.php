<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontpageController extends AbstractController
{
    /**
     * @Route("/", name="Index")
     */
    public function index(): Response
    {
        return $this->render('frontpage/index.html.twig');
    }
}
