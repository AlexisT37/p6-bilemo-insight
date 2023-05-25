<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Phone;
use App\Entity\Customer;
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

        // Add 2 customers to this user, they should have an email and a password, and should be linked to the user
        // the first customer has chou@gmail.com as an email, and Boubou345 as a password
        // the second customer has a kiwi83@gmail.com as an email, and Banana321 as a password

        $customer1 = new Customer();
        $customer1->setEmail("chou@gmail.com");
        $customer1->setPassword($this->userPasswordHasher->hashPassword($user, "Boubou345"));
        $customer1->setClient($user);
        $manager->persist($customer1);

        $customer2 = new Customer();
        $customer2->setEmail("kiwi83@gmail.com");
        $customer2->setPassword($this->userPasswordHasher->hashPassword($user, "Banana321"));
        $customer2->setClient($user);
        $manager->persist($customer2);
        
        // Création d'un user admin
        $userAdmin = new User();
        $userAdmin->setEmail("admin@bilemo.com");
        $userAdmin->setRoles(["ROLE_ADMIN"]);
        $userAdmin->setPassword($this->userPasswordHasher->hashPassword($userAdmin, "Gandalf234@7"));
        $manager->persist($userAdmin);

        // Add 3 customers to this user, they should have an email and a password, and should be linked to the user
        // the first customer has baragouin@gmail.com as an email, and Squalala345 as a password
        // the second customer has a karaoke@gmail.com as an email, and Jeronimo782 as a password
        // the third customer has a limbo@gmail.com as an email, and Kamehameha942 as a password

        $customer3 = new Customer();
        $customer3->setEmail("baragouin@gmail.com");
        $customer3->setPassword($this->userPasswordHasher->hashPassword($userAdmin, "Squalala345"));
        $customer3->setClient($userAdmin);
        $manager->persist($customer3);
        
        $customer4 = new Customer();
        $customer4->setEmail("karaoke@gmail.com");
        $customer4->setPassword($this->userPasswordHasher->hashPassword($userAdmin, "Jeronimo782"));
        $customer4->setClient($userAdmin);
        $manager->persist($customer4);
        
        $customer5 = new Customer();
        $customer5->setEmail("limbo@gmail.com");
        $customer5->setPassword($this->userPasswordHasher->hashPassword($userAdmin, "Kamehameha942"));
        $customer5->setClient($userAdmin);
        $manager->persist($customer5);

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
