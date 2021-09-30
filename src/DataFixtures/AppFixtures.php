<?php

namespace App\DataFixtures;

use Faker;
use App\Entity\Beer;
use App\Entity\Category;
use App\Entity\Country;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class AppFixtures extends Fixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
        $countriesName = ['Belgium','French','English','Germany'];
        for ($i = 0; $i < count($countriesName); $i++) {
            $country = new Country();
            $country->setName($countriesName[$i]);
            $country->setAddress($faker->address());
            $country->setEmail($faker->email());
            $manager->persist($country);
        }
        // catégories normals
        $categoriesNormals = ['blonde', 'brune', 'blanche'];
        foreach ($categoriesNormals as $normal) {
            $category = new Category();
            $category->setName($normal);
            $category->setTerm("Normal");
            $category->setDescription($faker->text(rand(200, 500)));
            $manager->persist($category);
        }
        
        // catégories specials
        $categoriesSpecials = ['houblon', 'rose', 'menthe', 'grenadine', 'réglisse', 'marron', 'whisky', 'bio'] ;
        foreach ($categoriesSpecials as $special) {
            $category = new Category();
            $category->setName($special);
            $category->setTerm("Special");
            $category->setDescription($faker->text(rand(200, 500)));
            $manager->persist($category);
        }
        $manager->flush();
        for ($i = 0; $i < 20; $i++) {
            
            $beer = new Beer();
            $beer->setName($faker->word());
            $beer->setPrice($faker->randomFloat(2,4,15));

            $countries = $manager->getRepository(Country::class)->findAll();
            shuffle($countries);
            $country = array_slice($countries, 0, 1);
            $beer->setCountry($country[0]);

            $beer->setDescription($faker->text(rand(200, 500)));
            $beer->setPublishedAt($faker->dateTime());

            //$countries = $manager->getRepository(Category::class)->findByTerm("Normal");
            $categoriesNormal = $manager->getRepository(Category::class)->findBy(['term' => 'Normal']);
            shuffle($categoriesNormal);
            $categoryNormal = array_slice($categoriesNormal, 0, 1);
            $beer->addCategory($categoryNormal[0]);

            $rand=rand(1,2);
            $categoriesSpecial = $manager->getRepository(Category::class)->findBy(['term' => 'Special']);
            shuffle($categoriesSpecial);
            $categorySpecial = array_slice($categoriesSpecial, 0, $rand);
            foreach ($categorySpecial as $spe) {
                $beer->addCategory($spe);
            }

            $manager->persist($beer);
        }
        
       
        $manager->flush();
    }
    public function getOrder()
    {
        return 2;
    }
}