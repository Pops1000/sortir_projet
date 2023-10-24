<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Etat;
use App\Entity\Sortie;


class EtatFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {


        $etat = new Etat();
        $etat->setLibelle("Crée");

        $manager->persist($etat);
        $etat = new Etat();
        $etat->setLibelle("Ouverte");
        $manager->persist($etat);
        $etat = new Etat();
        $etat->setLibelle("Clôturée");
        $manager->persist($etat);
        $etat = new Etat();
        $etat->setLibelle("Activité en cours");
        $manager->persist($etat);
        $etat = new Etat();
        $etat->setLibelle("Passée");
        $manager->persist($etat);
        $etat = new Etat();
        $etat->setLibelle("Annulée");
        $manager->persist($etat);
        $etat = new Etat();
        $etat->setLibelle("Archivée");


        $manager->persist($etat);
        $manager->flush();
    }
}
