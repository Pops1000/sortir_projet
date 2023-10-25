<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Entity\Etat;
use App\Entity\Sortie;
use App\Form\FilterSortiesType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\CreationSortieType;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SortieController extends AbstractController
{
    #[Route(path: '/sortie', name: 'app_sortie')]
    public function createSortie(Request $request,EntityManagerInterface $em): Response
    {
        $sortie = new Sortie();
        $sortie->setOrganisateur($this->getUser());
        $sortie->setEtat($em->getRepository(Etat::class)->find(1));
        $sortie->setCampus(campus: $this->getUser()->getCampus());
        $sortie->setDateHeureDebut(new \DateTime());
        $sortie->setDateLimiteInscription(new \DateTime());
        $sortie->addParticipant($this.getUser());

        $creationSortie = $this->createForm(CreationSortieType::class, $sortie);
        $creationSortie->handleRequest($request);

        if ($creationSortie->isSubmitted() && $creationSortie->isValid()) {
            $lieu = $sortie->getLieu();
            $ville = $lieu->getVille();


            if ($ville->getId() === null) {
                $em->persist($ville);
            }


            if ($lieu->getId() === null) {
                $em->persist($lieu);
            }


            $em->persist($lieu);

            $em->persist($sortie);
            $em->flush();

            return $this->redirectToRoute(route: 'app_sorties');
        }


        return $this->render('sortie/index.html.twig', [

            'creationSortie' => $creationSortie->createView(),
        ]);
    }

    #[Route(path: '/sorties', name: 'app_sorties')]
    public function sorties(EntityManagerInterface $em, Request $request): Response
    {
        $data = new SearchData();

        $form = $this->createForm(FilterSortiesType::class, $data);
        $form->handleRequest($request);

        $sorties = $em->getRepository(Sortie::class)->findByFilter($data, $this->getUser());
        return $this->render('sorties/index.html.twig', [
            'controller_name' => 'SortieController',
            'searchForm' => $form->createView(),
            'sorties' => $sorties,
        ]);
    }

    #[Route(path: '/inscription/{id}', name: "inscription_sortie")]
    public function inscriptionSortie(Sortie $sortie): Response
    {
        $participant = $this->getUser();

        if (!$sortie->getParticipants()->contains($participant)) {
            $sortie->addParticipant($participant);

            $em = $this->getDoctrine()->getManager();
            $em->flush();;

            return $this->redirectToRoute('app_main');


        }
        return $this->redirectToRoute('app_main');

    }

    #[Route("/sortie/{id}", name: "sortie_detail")]
    public function sortieDetails(Sortie $sortie): Response
    {
        return $this->render('sortie/detail.html.twig', [
            'sortie' => $sortie,
        ]);

    }

    #[Route(path: "sortie/modifier/{id}", name: "sortie_modifier")]
    public function modifierSortie(Sortie $sortie, Request $request): Response
    {

        $sortieForm = $this->createForm(CreationSortieType::class, $sortie);
        $sortieForm->handleRequest($request);

        if ($sortieForm->isSubmitted() && $sortieForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $lieu=$sortie->getLieu();
            $ville = $lieu->getVille();
            if ($ville->getId() === null) {
                $em->persist($ville);
            }

            if ($lieu->getId() === null) {
                $em->persist($lieu);
            }

            $em->persist($sortie);
            $em->flush();

            $this->addFlash('success', 'Sortie modifiée avec succès!');

            return $this->redirectToRoute('sortie_detail', ['id' => $sortie->getId()]);
        }
        return $this->render('sortie/modifier.html.twig', [
            'sortieForm' => $sortieForm->createView(),
            'sortie' => $sortie,
        ]);
    }


}


