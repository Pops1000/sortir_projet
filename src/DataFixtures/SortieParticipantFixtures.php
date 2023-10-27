<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Participant;
use App\Entity\Sortie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SortieParticipantFixtures extends Fixture implements DependentFixtureInterface
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $participants = [];
        $campus = $manager->getRepository(Campus::class)->findAll();
        $etat = $manager->getRepository(Etat::class)->findAll();
        $lieu = $manager->getRepository(Lieu::class)->findAll();
        for ($i = 1; $i <= 5; $i++) {
            $participant = new Participant();

            $participant->setMail("participant$i@example.com");
            $participant->setNom("Nom$i");
            $participant->setPrenom("Prenom$i");
            $participant->setTelephone("Telephone$i");
            $participant->setPseudo("Pseudo$i");
            $participant->setMotPasse("mdp");


            $isAdmin = (bool)random_int(0, 1);
            $participant->setIsAdministrateur($isAdmin);
            $isActif = (bool)random_int(0, 1);
            $participant->setActif($isActif);
            $randomCampus = $campus[array_rand($campus)];
            $participant->setCampus($randomCampus);
            $motdepasse = $participant->getMotPasse();

            $hashedPassword = $this->passwordHasher->hashPassword($participant, $motdepasse);
            $participant->setMotPasse($hashedPassword);
            $participants[] = $participant;
            $manager->persist($participant);
        }

        $manager->flush();


        for ($i = 1; $i <= 50; $i++) {
            $sortie = new Sortie();
            $sortie->setNom("Sortie$i");
            $dateHeureDebut = new \DateTime();
            $dateHeureDebut->modify("+$i days");
            $sortie->setDateHeureDebut($dateHeureDebut);
            $dateLimiteInscription = new \DateTime();
            $dateLimiteInscription->modify("+$i days");
            $sortie->setDateLimiteInscription($dateLimiteInscription);
            $sortie->setDuree($i + 2);
            $sortie->setNbInscriptionsMax($i + 10);
            $sortie->setInfosSortie("info$i");
            $randomCampus = $campus[array_rand($campus)];
            $sortie->setCampus($randomCampus);
            $randomEtat = $etat[array_rand($etat)];
            $sortie->setEtat($randomEtat);
            $randomLieu = $lieu[array_rand($lieu)];
            $sortie->setLieu($randomLieu);

            $organisateur = $participants[array_rand($participants)];
            $sortie->setOrganisateur($organisateur);
            $manager->persist($sortie);
            $participantCount = mt_rand(1, count($participants));
            shuffle($participants);
            $participantsToJoin = array_slice($participants, 0, $participantCount);


            foreach ($participantsToJoin as $participantIndex) {

                $sortie->addParticipant($participant);
            }

            $manager->flush();
        }
    }

    public function getDependencies()
    {
        return [
            EtatFixture::class,
            VilleFixtures::class,
            CampusFixtures::class,
            LieuFixtures::class,


        ];
    }
}
