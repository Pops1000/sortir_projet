<?php

namespace App\Controller;

use App\Entity\Sortie;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\CreationSortieType;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SortieController extends AbstractController
{
    #[Route(path: '/sortie', name: 'app_sortie')]
    public function createSortie(Request $request): Response
    {
        $sortie = new Sortie();
        $creationSortie = $this->createForm(CreationSortieType::class, $sortie);

        $creationSortie->handleRequest($request);

        if ($creationSortie->isSubmitted() && $creationSortie->isValid()) {
            $lieu = $sortie->getLieu();
            $ville = $lieu->getVille();
            $em = $this->getDoctrine()->getManager();

            if ($ville->getId() === null) {
                $em->persist($ville);
            }


            if ($lieu->getId() === null) {
                $em->persist($lieu);
            }

            $organisateur = $this->getUser();
            $sortie->setOrganisateur($organisateur);

            $lieu->setVille($ville);
            $em->persist($lieu);

            $em->persist($sortie);
            $em->flush();

            return $this->redirectToRoute(route: '/');
        }


        return $this->render('sortie/index.html.twig', [

            'creationSortie' => $creationSortie->createView(),
        ]);
    }
    #[Route(path: '/sorties', name: 'app_sorties')]
    public function sorties(EntityManagerInterface $em): Response
    {
        $sorties = $em->getRepository(Sortie::class)->findAll();
        return $this->render('sorties/index.html.twig', [
            'controller_name' => 'SortieController',
            'sorties' => $sorties,
        ]);}}


