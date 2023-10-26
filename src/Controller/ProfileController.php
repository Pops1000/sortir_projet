<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\ProfileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[Route(path:'/profile', name: 'app_')]
class ProfileController extends AbstractController
{
    #[Route(path:'', name: 'profile')]
    public function index(EntityManagerInterface $entityManager, Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {

        $participant = $this->getUser();
// Si le compte est désactivé, déconnecte aussitot et TODO redirige vers une page d'alerte
        if($participant->isIsActif()==false){
            return $this->redirectToRoute('app_logout');

           // return $this->render('profile/desactive.html.twig');
        }

        $profileForm = $this->createForm(ProfileType::class, $participant);

        $profileForm->handleRequest($request);

        if ($profileForm->isSubmitted() && $profileForm->isValid()) {
// je verifie que le pseudo n'existe pas déja
            $existingParticipant = $entityManager->getRepository(Participant::class)->findOneBy(['pseudo' => $profileForm->get('pseudo')->getData()]);

            if ($existingParticipant !== null && $existingParticipant !== $participant) {
// Le pseudo existe déjà pour un autre utilisateur j'ajoute un message d'erreur

                $this->addFlash('error', 'Le pseudo est déjà utilisé par un autre utilisateur.');

                return $this->redirectToRoute('app_profile');
            }
//Je remplace le mdp en clair par le mdp cripté avant de le mettre en bdd si le champ mdp a bien été modifié

            $newPassword = $profileForm->get('newPassword')->getData();
            if (null != $newPassword) {
                // hasher le nouveau mot de passe et l'utiliser dans le setter
                $mdpCripte=$passwordHasher->hashPassword($participant,$newPassword);

                $participant->setMotPasse($mdpCripte);


            }

            $entityManager->persist($participant);
            $entityManager->flush();

            $this->addFlash('success','profile modifié!');
            return $this->redirectToRoute('app_sorties');

        }

        return $this->render('profile/profile.html.twig', [
            'profileForm' => $profileForm->createView()
        ]);
    }
    #[Route(path:'/{id}', name: 'profile_display', requirements: ['id' => '\d+'])]
    public function displayProfile(Participant $participant): Response
    {
        return $this->render('profile/display.html.twig', [
            'participant' => $participant
        ]);
    }
}
