<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class Users extends Fixture implements FixtureGroupInterface
{
    private UserPasswordHasherInterface $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }
    public function load(ObjectManager $manager): void
    {
        // *** USERS *** //

        $user1 = new User();
        $user1
            ->setUserName('admin')
            ->setUserLastName('admin')
            ->setBirthdate(new DateTime('2022-12-12'))
            ->setPhoneNumber('0999999999')
            ->setRegisterDate(new \DateTime('2022-12-12'))
            ->setVat('0.10')
            ->setEmail('admin@muse.com')
            ->setRoles(["ROLE_ADMIN","ROLE_SALES","ROLE_SHIP","ROLE_PRO","ROLE_CLIENT","ROLE_USER"])
            ->setPassword($this->userPasswordHasher->hashPassword($user1, '123456'))
            ->setIsVerified(true);
        $manager->persist($user1);

        $user2 = new User();
        $user2
            ->setUserName('sales')
            ->setUserLastName('sales')
            ->setBirthdate(new DateTime('2022-12-12'))
            ->setPhoneNumber('0999999999')
            ->setRegisterDate(new \DateTime('2022-12-12'))
            ->setVat('0.10')
            ->setEmail('sales@muse.com')
            ->setRoles(["ROLE_SALES","ROLE_SHIP","ROLE_PRO","ROLE_CLIENT","ROLE_USER"])
            ->setPassword($this->userPasswordHasher->hashPassword($user2, '123456'))
            ->setIsVerified(true);
        $manager->persist($user2);

        $user3 = new User();
        $user3
            ->setUserName('ship')
            ->setUserLastName('ship')
            ->setBirthdate(new DateTime('2022-12-12'))
            ->setPhoneNumber('0999999999')
            ->setRegisterDate(new \DateTime('2022-12-12'))
            ->setVat('0.10')
            ->setEmail('ship@muse.com')
            ->setRoles(["ROLE_SHIP","ROLE_PRO","ROLE_CLIENT","ROLE_USER"])
            ->setPassword($this->userPasswordHasher->hashPassword($user3, '123456'))
            ->setIsVerified(true);
        $manager->persist($user3);

        $user4 = new User();
        $user4
            ->setUserName('pro')
            ->setUserLastName('pro')
            ->setBirthdate(new DateTime('2022-12-12'))
            ->setPhoneNumber('0999999999')
            ->setRegisterDate(new \DateTime('2022-12-12'))
            ->setVat('0.10')
            ->setEmail('pro@muse.com')
            ->setRoles(["ROLE_PRO","ROLE_CLIENT","ROLE_USER"])
            ->setPassword($this->userPasswordHasher->hashPassword($user4, '123456'))
            ->setIsVerified(true);
        $manager->persist($user4);

        $user5 = new User();
        $user5
            ->setUserName('client')
            ->setUserLastName('client')
            ->setBirthdate(new DateTime('2022-12-12'))
            ->setPhoneNumber('0999999999')
            ->setRegisterDate(new \DateTime('2022-12-12'))
            ->setVat('0.20')
            ->setEmail('client@muse.com')
            ->setRoles(["ROLE_CLIENT","ROLE_USER"])
            ->setPassword($this->userPasswordHasher->hashPassword($user5, '123456'))
            ->setIsVerified(true);
        $manager->persist($user5);

        $manager->flush();
    }

    public static function getGroups(): array
     {
         return ['Users'];
     }
}
