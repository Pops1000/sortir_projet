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

        for ($i = 1; $i <= 3; $i++) {
            $etat = new Etat();
            $etat->setLibelle("Etat $i");

            $manager->persist($etat);


        }
        $manager->flush();
    }}
