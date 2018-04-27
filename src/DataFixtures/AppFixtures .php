<?php
namespace src\DataFixtures;

use ApiBundle\Entity\Entreprise;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // create 20 products! Bam!
        for ($i = 0; $i < 2; $i++) {
            $entreprise = new Entreprise();
            $entreprise->setName('product '.$i);
            $entreprise->setAdresse(mt_rand(10, 100));
            $entreprise->setPobox(mt_rand(10, 100));
            $entreprise->setPhone(mt_rand(10, 100));
            $entreprise->setDescription('entreprise '.$i);
            $entreprise->setStatus(true);

                $manager->persist($entreprise);
        }

        $manager->flush();
    }
}