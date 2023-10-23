<?php

namespace App\DataFixtures;

use App\Entity\Ville;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class VilleFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 10; $i++) {
            $ville = new Ville();
            $ville->setNom("Ville $i");
            $ville->setCodePostal( random_int(1000, 9999));
            $manager->persist($ville);
        }
        $manager->flush();

    }

}
