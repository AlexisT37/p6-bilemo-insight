<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Phone;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $userPasswordHasher;
    
    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function load(ObjectManager $manager): void
    {

        // Création d'un user "normal"
        $user = new User();
        $user->setEmail("margesimpson@bilemo.com");
        $user->setRoles(["ROLE_USER"]);
        $user->setPassword($this->userPasswordHasher->hashPassword($user, "MargeSimpson3224#"));
        $manager->persist($user);
        
        // Création d'un user admin
        $userAdmin = new User();
        $userAdmin->setEmail("admin@bilemo.com");
        $userAdmin->setRoles(["ROLE_ADMIN"]);
        $userAdmin->setPassword($this->userPasswordHasher->hashPassword($userAdmin, "Gandalf234@7"));
        $manager->persist($userAdmin);

        //creates 10 phones with random data according to the Phone entity
        for ($i = 0; $i < 10; ++$i) {
            $phone = new Phone();
            $phone->setName('Phone '.$i);
            $phone->setQuantity(random_int(0, 100));
            // sets brand and model randomly
            $brand = ['Apple', 'Samsung', 'Huawei', 'Xiaomi', 'Oppo', 'Vivo'];
            $model = ['A', 'B', 'C', 'D', 'E', 'F'];
            $phone->setBrand($brand[random_int(0, 5)]);
            $phone->setModel($model[random_int(0, 5)]);
            $manager->persist($phone);
        }


        $manager->flush();
    }
}
