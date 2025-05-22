<?php

namespace App\DataFixtures;

use App\Entity\Ingredient;
use App\Entity\Recipe;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Generator;
use Faker\Factory;

class AppFixtures extends Fixture
{
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create(locale: "fr_FR");
    }

    public function load(ObjectManager $manager): void
    {
        $ingredients = [];
        for ($i = 1; $i <= 50; $i++) {
            $ingredient = new Ingredient();
            $ingredient->setName($this->faker->word())->setPrice(mt_rand(1, 199));

            $ingredients[] = $ingredient;
            $manager->persist($ingredient);
        }

        for ($j = 1; $j <= 25; $j++) {
            $recipe = new Recipe();
            $recipe
                ->setName($this->faker->word())
                ->setTime(mt_rand(1, 1440))
                ->setNbPersons(mt_rand(1, 50))
                ->setDifficulty(mt_rand(1, 5))
                ->setDescription($this->faker->paragraph(2))
                ->setPrice(mt_rand(1, 1000))
                ->setIsFavorite(mt_rand(0, 1) == 1 ? true : false);

            for ($k = 0; $k < mt_rand(5, 15); $k++) {
                $recipe->addIngredient($ingredients[mt_rand(0, count($ingredients) - 1)]);
            }

            $manager->persist($recipe);
        }

        $manager->flush();
    }
}
