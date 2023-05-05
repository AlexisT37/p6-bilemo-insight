<?php

namespace App\DataFixtures;

use App\Entity\Phone;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        //creates 10 phones with random data according to the Phone entity
        for ($i = 0; $i < 10; ++$i) {
            $phone = new Phone();
            $phone->setName('Phone '.$i);
            $phone->setQuantity(random_int(0, 100));
            $manager->persist($phone);
        }


        $manager->flush();
    }
}
