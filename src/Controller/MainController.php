<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'main')]
    public function index(): Response
    {
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
        ]);


    }

    #[Route('/main2', name: 'main2')]
    public function index2(): Response
    {
        return $this->render('main/index2.html.twig', [
            'controller_name' => 'MainController',
        ]);


    }

    #[Route('/main3', name: 'main3')]
    public function index3(): Response
    {
        return $this->render('main/index3.html.twig', [
            'controller_name' => 'MainController',
        ]);


    }


}
