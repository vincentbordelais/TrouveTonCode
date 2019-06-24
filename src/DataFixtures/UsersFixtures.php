<?php


namespace App\DataFixtures;


use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class UsersFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->setUsername('user'.$i);
            $user->setRoles('user'.$i);
            $user->setPassword(password_hash('user'.$i, PASSWORD_DEFAULT));
            $user->setEmail('user'.$i.'@fake.com');

            $this->addReference('user'.$i, $user);

            $manager->persist($user);
        }
        $manager->flush();
    }
}