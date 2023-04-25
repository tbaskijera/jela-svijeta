<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\CategoryTranslation;
use App\Entity\Ingredient;
use App\Entity\IngredientTranslation;
use App\Entity\Language;
use App\Entity\Meal;
use App\Entity\MealTranslation;
use App\Entity\Tag;
use App\Entity\TagTranslation;
use App\Provider\CustomFaker;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class AppFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $generator = new Generator();
        $faker_en = Factory::create('en_US');
        $faker = new CustomFaker($generator);

        // Create some categories
        $categories = [];
        for ($i = 1; $i <= 7; $i++) {
            $category = new Category();
            $category->setTitle($faker->getCategory('en'));
            $category->setSlug($faker_en->word());
            $manager->persist($category);
            $categories[] = $category;
        }

        // Create some tags
        $tags = [];
        for ($i = 1; $i <= 8; $i++) {
            $tag = new Tag();
            $tag->setTitle($faker->getTag('en'));
            $tag->setSlug($faker_en->word());
            $manager->persist($tag);
            $tags[] = $tag;
        }

        // Create some ingredients
        $ingredients = [];
        for ($i = 1; $i <= 15; $i++) {
            $ingredient = new Ingredient();
            $ingredient->setTitle($faker->getIngredient('en'));
            $ingredient->setSlug($faker_en->word());
            $manager->persist($ingredient);
            $ingredients[] = $ingredient;
        }

        // Create some languages
        $languages = [
            'en' => 'English',
            'es' => 'Spanish',
        ];

        foreach ($languages as $code => $name) {
            $language = new Language();
            $language->setCode($code);
            $language->setName($name);
            $manager->persist($language);

            // Create some category translations for this language
            foreach ($categories as $category) {
                $categoryTranslation = new CategoryTranslation();
                $categoryTranslation->setCategory($category);
                $categoryTranslation->setLanguage($language);
                $categoryTranslation->setLocale($code);
                $categoryTranslation->setTitle($faker->getTranslation($category->getTitle(), $code));
                $manager->persist($categoryTranslation);
            }

            // Create some tag translations for this language
            foreach ($tags as $tag) {
                $tagTranslation = new TagTranslation();
                $tagTranslation->setTag($tag);
                $tagTranslation->setLanguage($language);
                $tagTranslation->setLocale($code);
                $tagTranslation->setTitle($faker->getTranslation($tag->getTitle(), $code));
                $manager->persist($tagTranslation);
            }

            // Create some ingredient translations for this language
            foreach ($ingredients as $ingredient) {
                $ingredientTranslation = new IngredientTranslation();
                $ingredientTranslation->setIngredient($ingredient);
                $ingredientTranslation->setLanguage($language);
                $ingredientTranslation->setLocale($code);
                $ingredientTranslation->setTitle($faker->getTranslation($ingredient->getTitle(), $code));
                $manager->persist($ingredientTranslation);
            }
        }

        // Create some meals
        for ($i = 1; $i <= 10; $i++) {
            $meal = new Meal();
            $meal->setTitle($faker->getMeal('en'));
            $meal->setDescription('Description of meal '.$i.' '.'English');

            $num_categories = $faker->numberBetween(0, 1);
            $selected_category = $categories[($i - 1) % count($categories)];
            if ($num_categories == 1) {
                $meal->setCategory($selected_category);
            }

            $num_ingredients = $faker->numberBetween(1, count($ingredients));
            for ($j = 0; $j < $num_ingredients; $j++) {
                $meal->addIngredient($ingredients[($i - 1 + $j) % count($ingredients)]);
            }

            $num_tags = $faker->numberBetween(1, count($tags));
            $selected_tags = $faker->randomElements($tags, $num_tags);
            foreach ($selected_tags as $tag) {
                $meal->addTag($tag);
            }


            // Create meal translations for each language
            foreach ($languages as $code => $name) {
                $mealTranslation = new MealTranslation();
                $mealTranslation->setMeal($meal);
                $mealTranslation->setLanguage($language);
                $mealTranslation->setLocale($code);
                $mealTranslation->setTitle($faker->getTranslation($meal->getTitle(), $code));
                $mealTranslation->setDescription('Description of meal '.$i.' '.$languages[$code]);
                $manager->persist($mealTranslation);
            }

            $manager->persist($meal);
        }

        // Flush changes to database
        $manager->flush();
    }
}
