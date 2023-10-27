<?php

namespace App\DataFixtures;

use App\Entity\Lieu;
use App\Entity\Ville;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class LieuFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $ville = $manager->getRepository(Ville::class)->findAll();

        $faker = \Faker\Factory::create();
        for ($i = 1; $i <= 10; $i++) {
            $lieu = new Lieu();
            $lieu->setNom("lieu $i");
            $lieu->setRue($faker->streetName());
            $lieu->setLatitude(random_int(1000, 9999));
            $lieu->setLongitude(random_int(1000, 9999));
            $randomVille = $ville[array_rand($ville)];
            $lieu->setVille($randomVille);


            $manager->persist($lieu);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            EtatFixture::class,
            VilleFixtures::class,
            CampusFixtures::class,


        ];
    }
}
