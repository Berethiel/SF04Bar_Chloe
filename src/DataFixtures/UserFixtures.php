<?php

namespace App\DataFixtures;

use App\Entity\Client;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{

    private $passwordHasher;
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }
    public function load(ObjectManager $manager): void
    {
        $client = new Client();
        $client->setEmail('chloe.balmat@gmail.com');
        $client->setWeight(60);
        $client->setName('Chloe');
        $client->setAge(25);
        $client->setNumberBeer(0);
        $manager->persist($client);
        $manager->flush();

        $user = new User();
        $user->setEmail($client->getEmail());
        $user->setRoles(['ROLE_VISITEUR','ROLE_ADMIN']);
        $user->setPassword($this->passwordHasher->hashPassword(
            $user,
            'boudin'
        ));
        $user->setClient($client);
        $manager->persist($user);
        $manager->flush();
    }
}
