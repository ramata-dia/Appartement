<?php

namespace App\DataFixtures;

use DateTime;
use Faker\Factory;
use App\Entity\Image;
use App\Entity\Annonce;
use App\Entity\Comment;
use Cocur\Slugify\Slugify;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        $slugger = new Slugify();
        for ($i = 0; $i < 5; $i++) {
            $annonce = new Annonce();
            $annonce

                ->setTitle($faker->sentence(3, false))
                ->setIntroduction($faker->sentence())
                ->setSlug($slugger->Slugify($annonce->getTitle()))
                ->setDesription($faker->text(200))
                ->setPrice(mt_rand(30000, 60000))
                ->setAddress($faker->address())
                ->setCoverImage("0e3f5e6b2503ab7dbd4281affd60394b.jpg")
                ->setRooms(mt_rand(0, 5))
                ->setIsAvailable(mt_rand(0, 1))
                ->setCreatedAt($faker->datetimeBetween('-3 month', 'now'));

            for ($j = 0; $j < mt_rand(0, 5); $j++) {
                $comment = new Comment();
                $comment
                    ->setAuthor($faker->name())
                    ->setMail($faker->email())
                    ->setContent($faker->text(200))
                    ->setCreatedAt($faker->datetimeBetween('-3 month', 'now'))

                    ->setAnnonce($annonce);
                $annonce->AddComment($comment);
                $manager->persist($comment);

                for ($k = 0; $k < mt_rand(0, 4); $k++) {
                    $image = new Image();
                    $image
                        ->setImageUrl(
                            'https://picsum.photos/350/300?random=' .
                                mt_rand(1, 5000)
                        )
                        ->setDescription($faker->sentence())
                        ->setAnnonce($annonce);
                    $manager->persist($image);
                    $annonce->AddImage($image);
                }

                $manager->persist($annonce);
            }

            $manager->flush();
        }
    }
}
