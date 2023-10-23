<?php

namespace App\Controller;

use App\Entity\Sortie;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_main')]
    public function index(): Response
    {

        $entityManager = $this->getDoctrine()->getManager();
        $participants= $entityManager->getRepository(Sortie::class)->findAll();
        return $this->redirectToRoute('app_sorties');


    }

    #[Route('/test', name: 'app_test')]
    public function test()
    {
        return $this->render('main/test.html.twig', [
            'controller_name' => 'MainController'
        ]);
    }
}
