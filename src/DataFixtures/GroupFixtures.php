<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class GroupFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {

        $manager->flush();
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