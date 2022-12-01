<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\User;
use App\Entity\Product;
use App\Entity\Category;
use App\Entity\Supplier;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class All extends Fixture
{
private UserPasswordHasherInterface $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // for ($i = 0; $i < 10; $i++) {
        //     $category = new Category();
        //     $category->setName('product ' . $i);
        //     $category->setParentCategory($category);
        //     $manager->persist($category);
        // }


        // for ($i = 0; $i < 20; $i++) {
        //     $product = new Product();
        //     $product->setName('product ' . $i);
        //     $product->setCategory(mt_rand(0, 9));
        //     $manager->persist($product);
        // }


        // for ($i = 0; $i < 10; $i++) {
        //     $user = new User();
        //     $user->setName($this->faker->name())
        //         ->setPseudo(mt_rand(0, 1) === 1 ? $this->faker->firstName() : null)
        //         ->setEmail($this->faker->email())
        //         ->setRoles(['ROLE_USER'])
        //         ->setPlainPassword('password');
        // }


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



        // *** CATEGORIES *** //

        $c1 = new Category();
            $c1->setName("Guitares")
               ->setParentCategory(null);
        $manager->persist($c1);

            $c11 = new Category();
                $c11->setName("Guitares Electriques")
                ->setParentCategory($c1);
            $manager->persist($c11);

            $c12 = new Category();
                $c12->setName("Guitares accoustiques")
                ->setParentCategory($c1);
            $manager->persist($c12);

        $c2 = new Category();
            $c2->setName("Guitares basses")
               ->setParentCategory(null);
        $manager->persist($c2);

            $c21 = new Category();
                $c21->setName("Basses accoustiques")
                ->setParentCategory($c2);
            $manager->persist($c21);

            $c22 = new Category();
                $c22->setName("Basses électriques")
                ->setParentCategory($c2);
            $manager->persist($c22);


        $c3 = new Category();
            $c3->setName("Batteries & Percussions")
            ->setParentCategory(null);
        $manager->persist($c3);

                $c31 = new Category();
                    $c31->setName("Batteries")
                    ->setParentCategory($c3);
                $manager->persist($c31);

                $c32 = new Category();
                    $c32->setName("Percussions")
                    ->setParentCategory($c3);
                $manager->persist($c32);

        $c4 = new Category();
            $c4->setName("Pianos & Claviers")
            ->setParentCategory(null);
        $manager->persist($c4);

                $c41 = new Category();
                    $c41->setName("Claviers")
                    ->setParentCategory($c4);
                $manager->persist($c41);


                $c42 = new Category();
                    $c42->setName("Pianos")
                    ->setParentCategory($c4);
                $manager->persist($c42);

        $c5 = new Category();
            $c5->setName("Instruments à vent")
            ->setParentCategory(null);
        $manager->persist($c5);

        $c6 = new Category();
            $c6->setName("Instruments trad.")
            ->setParentCategory(null);
        $manager->persist($c6);

        $c7 = new Category();
            $c7->setName("Matériel DJ")
            ->setParentCategory(null);
        $manager->persist($c7);

        $c8 = new Category();
            $c8->setName("Microphones")
            ->setParentCategory(null);
        $manager->persist($c8);

        $c9 = new Category();
            $c9->setName("Sonorisation")
            ->setParentCategory(null);
        $manager->persist($c9);


        // *** SUPPLIERS *** //

        // $s1 = new Supplier();
        //     $s1->setName("Fender");
        // $manager->persist($s1);

        // $s2 = new Supplier();
        //     $s2->setName("Ibanez");
        // $manager->persist($s2);

        // $s3 = new Supplier();
        //     $s3->setName("P.R.S.");
        // $manager->persist($s3);

        // $s4 = new Supplier();
        //     $s4->setName("L.T.D.");
        // $manager->persist($s4);

        // $s5 = new Supplier();
        //     $s5->setName("Cordoba");
        // $manager->persist($s5);


        // *** PRODUCTS *** //

        // for ($i = 0; $i < 15; $i++) {
        //     $product = new Product();
        //         $product->setName('Elec ' . $i)
        //         ->setSupplier($s1)
        //         ->setCategory($c11)
        //         ->setImage('http://picsum.photos/id/'.mt_rand(100, 230).'/100/150')
        //         ->setImage1('http://picsum.photos/id/'.mt_rand(200, 330).'/100/150')
        //         ->setImage2('http://picsum.photos/id/'.mt_rand(300, 430).'/100/150')
        //         ->setPrice(mt_rand(25, 2750))
        //         ->setDescription(mt_rand(0, 10).' chance(s) sur 10 de devenir sourd')
        //         ->setContent('1 instrument')
        //         ->setDiscount(false)
        //         ->setDiscountRate('0')
        //         ->setQuantity(mt_rand(300, 430));
        //     $manager->persist($product);
        // }

        // for ($i = 0; $i < 20; $i++) {
        //     $product = new Product();
        //         $product->setName('Accoustique ' . $i)
        //         ->setSupplier($s2)
        //         ->setCategory($c12)
        //         ->setImage('http://picsum.photos/id/'.mt_rand(100, 230).'/100/150')
        //         ->setImage1('http://picsum.photos/id/'.mt_rand(200, 330).'/100/150')
        //         ->setImage2('http://picsum.photos/id/'.mt_rand(300, 430).'/100/150')
        //         ->setPrice(mt_rand(25, 2750))
        //         ->setDescription(mt_rand(0, 10).' chance(s) sur 10 de devenir sourd')
        //         ->setContent('1 instrument')
        //         ->setDiscount(false)
        //         ->setDiscountRate('0')
        //         ->setQuantity(mt_rand(1, 100));
        //     $manager->persist($product);
        // }

        // for ($i = 0; $i < 25; $i++) {
        //     $product = new Product();
        //         $product->setName('Basse accoustique ' . $i)
        //         ->setSupplier($s3)
        //         ->setCategory($c21)
        //         ->setImage('http://picsum.photos/id/'.mt_rand(100, 230).'/100/150')
        //         ->setImage1('http://picsum.photos/id/'.mt_rand(200, 330).'/100/150')
        //         ->setImage2('http://picsum.photos/id/'.mt_rand(300, 430).'/100/150')
        //         ->setPrice(mt_rand(25, 2750))
        //         ->setDescription(mt_rand(0, 10).' chance(s) sur 10 de devenir sourd')
        //         ->setContent('1 instrument')
        //         ->setDiscount(false)
        //         ->setDiscountRate('0')
        //         ->setQuantity(mt_rand(1, 100));
        //     $manager->persist($product);
        // }

        // for ($i = 0; $i < 30; $i++) {
        //     $product = new Product();
        //         $product->setName('Basse ' . $i)
        //         ->setSupplier($s4)
        //         ->setCategory($c22)
        //         ->setImage('http://picsum.photos/id/'.mt_rand(100, 230).'/100/150')
        //         ->setImage1('http://picsum.photos/id/'.mt_rand(200, 330).'/100/150')
        //         ->setImage2('http://picsum.photos/id/'.mt_rand(300, 430).'/100/150')
        //         ->setPrice(mt_rand(25, 2750))
        //         ->setDescription(mt_rand(0, 10).' chance(s) sur 10 de devenir sourd')
        //         ->setContent('1 instrument')
        //         ->setDiscount(false)
        //         ->setDiscountRate('0.05')
        //         ->setQuantity(mt_rand(1, 100));
        //     $manager->persist($product);
        // }


        $manager->flush();
    }
}
