<?php

namespace App\DataFixtures;

use App\Entity\ArticlePicture;
use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Color;
use App\Entity\Sizes;
use App\Entity\Stock;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');



        $categories = [];

        for ($i = 0; $i < 3 ; $i++) {
            $categories[$i] = new Category();
            $categories[$i]->setCategoryName($faker->word);
            $manager->persist($categories[$i]);
        }

        $articles=[];

        for ($i = 0; $i < 10 ; $i++) {
            $articles[$i] = new Article();
            $articles[$i]->setArticleTitle($faker->text(15));
            $articles[$i]->setArticleDescription($faker->paragraph(3));
            $articles[$i]->setSellingPrice($faker->randomFloat(2,10, 500));
            $articles[$i]->setSellingPricePromo(0);
            $articles[$i]->setCategory($categories[1]);

            $manager->persist($articles[$i]);
        }

        $colors = [];

        $colors[0]= new Color();
        $colors[0]->setColorLabel("Green");

        $colors[1]= new Color();
        $colors[1]->setColorLabel("Purple");

        $colors[2]= new Color();
        $colors[2]->setColorLabel("Yellow");

        foreach ($colors as $color){
            $manager->persist($color);
        }

        $j = 0;

        for($i=0; $i<10; $i++){
            $pictures[$j] = new ArticlePicture();
            $pictures[$j]->setPictureLink("https://picsum.photos/".rand(1000, 2000).'/'.rand(2000, 1000).'/');
            $pictures[$j]->setColor($colors[rand(0, 2)]);
            $pictures[$j]->setArticle($articles[$i]);

            $manager->persist($pictures[$j]);

            $j++;

            $pictures[$j] = new ArticlePicture();
            $pictures[$j]->setPictureLink("https://picsum.photos/".rand(1000, 2000).'/'.rand(2000, 1000).'/');
            $pictures[$j]->setColor($colors[rand(0, 2)]);
            $pictures[$j]->setArticle($articles[$i]);

            $manager->persist($pictures[$j]);

            $j++;
        }

        $sizes = [];

        for($i=0; $i<5; $i++){
            $sizes[$i] = new Sizes();
            $sizes[$i]->setSizeLabel($faker->word);

            $manager->persist($sizes[$i]);
        }

        $stock = [];
        $h=0;

        for($i=0; $i<10;$i++){
            for($j=0; $j<5; $j++){
                $stock[$h] = new Stock();
                $stock[$h]->addArticle($articles[$i]);
                $stock[$h]->setSize($sizes[$j]);
                $stock[$h]->setAmount(random_int(0, 3));

                $manager->persist($stock[$h]);
                $h++;
            }
        }


        $manager->flush();
    }
}
