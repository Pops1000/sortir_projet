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

#[Route('/profile', name: 'app_profile')]
class ProfileController extends AbstractController
{
    #[Route('', name: '')]
    public function index(EntityManagerInterface $entityManager, Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {

        $participant = $this->getUser();
        $profileForm = $this->createForm(ProfileType::class, $participant);

        $profileForm->handleRequest($request);

        if ($profileForm->isSubmitted() && $profileForm->isValid()) {
//Je remplace le mdp en clair par le mdp cripté avant de le mettre en bdd si le champ mdp a bien été modifié

            $newPassword = $profileForm->get('newPassword')->getData();
            if (null != $newPassword) {
                // hasher le nouveau mot de passe et l'utiliser dans le setter
                $mdpCripte=$passwordHasher->hashPassword( $participant,$newPassword);

                $participant->setMotPasse($mdpCripte);

            }

            $entityManager->persist($participant);
            $entityManager->flush();

            return $this->redirectToRoute('app_main');

        }

        return $this->render('profile/profile.html.twig', [
            'profileForm' => $profileForm->createView()
        ]);
    }
}
