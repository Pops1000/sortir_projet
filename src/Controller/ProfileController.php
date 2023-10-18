<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\ProfileType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/profile', name: 'app_profile')]
class ProfileController extends AbstractController
{
    #[Route('', name: '')]
    public function index(Request $request): Response
    {

        $participant = new Participant();
        $profileForm= $this->createForm(ProfileType::class,$participant);

        return $this->render('profile/profile.html.twig', [
            'profileForm' => $profileForm->createView()
        ]);
    }

    #[Route('/update', name:'_update')]
public function update( Request $request)
    {

//TODO gerer les updates

    }

}
