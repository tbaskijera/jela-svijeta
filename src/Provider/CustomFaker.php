<?php

namespace App\Provider;

use Faker\Provider\Base;

class CustomFaker extends Base
{
    private static $categories = [
        'Appetizers' => ['en' => 'Appetizers', 'es' => 'Aperitivos'],
        'Burgers' => ['en' => 'Burgers', 'es' => 'Hamburgesas'],
        'Pizza' => ['en' => 'Pizza', 'es' => 'Pizza'],
        'Pasta' => ['en' => 'Pasta', 'es' => 'Pasta'],
        'Seafood' => ['en' => 'Seafood', 'es' => 'Marisco'],
        'Steak' => ['en' => 'Steak', 'es' => 'Bistec'],
        'Salads' => ['en' => 'Salads', 'es' => 'Ensaladas'],
        'Desserts' => ['en' => 'Desserts', 'es' => 'Postres']
    ];

    private static $tags = [
        'Spicy' => ['en' => 'Spicy', 'es' => 'Picante'],
        'Vegetarian' => ['en' => 'Vegetarian', 'es' => 'Vegetariano'],
        'Gluten-free' => ['en' => 'Gluten-free', 'es' => 'Sin gluten'],
        'Low-carb' => ['en' => 'Low-carb', 'es' => 'Bajo en carbohidratos'],
        'Healthy' => ['en' => 'Healthy', 'es' => 'Saludable'],
        'Comfort food' => ['en' => 'Comfort food', 'es' => 'Comida reconfortante'],
        'Gourmet' => ['en' => 'Gourmet', 'es' => 'Gourmet'],
        'International' => ['en' => 'International', 'es' => 'Internacional']
    ];

    private static $ingredients = [
        'Cheese' => ['en' => 'Cheese', 'es' => 'Queso'],
        'Tomatoes' => ['en' => 'Tomatoes', 'es' => 'Tomates'],
        'Mushrooms' => ['en' => 'Mushrooms', 'es' => 'Hongos'],
        'Onions' => ['en' => 'Onions', 'es' => 'Cebollas'],
        'Peppers' => ['en' => 'Peppers', 'es' => 'Pimientos'],
        'Bacon' => ['en' => 'Bacon', 'es' => 'Tocino'],
        'Chicken' => ['en' => 'Chicken', 'es' => 'Pollo'],
        'Beef' => ['en' => 'Beef', 'es' => 'Vaca'],
        'Pork' => ['en' => 'Pork', 'es' => 'Cerdo'],
        'Shrimp' => ['en' => 'Shrimp', 'es' => 'Gambas'],
        'Salmon' => ['en' => 'Salmon', 'es' => 'Salmón'],
        'Tuna' => ['en' => 'Tuna', 'es' => 'Atún'],
        'Pineapple' => ['en' => 'Pineapple', 'es' => 'Piña'],
        'Olives' => ['en' => 'Olives', 'es' => 'Aceitunas'],
        'Garlic' => ['en' => 'Garlic', 'es' => 'Ajo'],
        'Basil' => ['en' => 'Basil', 'es' => 'Albahaca'],
        'Oregano' => ['en' => 'Oregano', 'es' => 'Orégano'],
        'Thyme' => ['en' => 'Thyme', 'es' => 'Tomillo'],
        'Parsley' => ['en' => 'Parsley', 'es' => 'Perejil'],
        'Rosemary' => ['en' => 'Rosemary', 'es' => 'Romero']
    ];


    private static $meals = [
        'Fish and Chips' => ['en' => 'Fish and Chips', 'es' => 'Pescado con patatas fritas'],
        'Spaghetti Carbonara' => ['en' => 'Spaghetti Carbonara', 'es' => 'Espaguetis a la carbonara'],
        'Caesar Salad' => ['en' => 'Caesar Salad', 'es' => 'Ensalada César'],
        'Chicken Parmesan' => ['en' => 'Chicken Parmesan', 'es' => 'Pollo parmesano'],
        'Grilled Cheese Sandwich' => ['en' => 'Grilled Cheese Sandwich', 'es' => 'Sándwich de queso a la parrilla'],
        'Beef Stroganoff' => ['en' => 'Beef Stroganoff', 'es' => 'Estofado de ternera'],
        'Lobster Bisque' => ['en' => 'Lobster Bisque', 'es' => 'Sopa de langosta'],
        'Philly Cheesesteak' => ['en' => 'Philly Cheesesteak', 'es' => 'Philly Cheesesteak'],
        'Chicken Alfredo' => ['en' => 'Chicken Alfredo', 'es' => 'Pollo Alfredo'],
        'BBQ Ribs' => ['en' => 'BBQ Ribs', 'es' => 'Costillas a la barbacoa']
    ];


    public function getCategory(string $lang): string
    {
        $category = static::randomElement(static::$categories);
        return $category[$lang];
    }

    public function getTag(string $lang): string
    {
        $tag = static::randomElement(static::$tags);
        return $tag[$lang];
    }

    public function getIngredient(string $lang): string
    {
        $ingredient = static::randomElement(static::$ingredients);
        return $ingredient[$lang];
    }

    public function getMeal(string $lang): string
    {
        $meal = static::randomElement(static::$meals);
        return $meal[$lang];
    }

    public function getTranslation($original, $lang): string
    {
        foreach (static::$categories as $category) {
            if (in_array($original, $category)) {
                return $category[$lang];
            }
        }

        foreach (static::$tags as $tag) {
            if (in_array($original, $tag)) {
                return $tag[$lang];
            }
        }

        foreach (static::$ingredients as $ingredient) {
            if (in_array($original, $ingredient)) {
                return $ingredient[$lang];
            }
        }

        foreach (static::$meals as $meal) {
            if (in_array($original, $meal)) {
                return $meal[$lang];
            }
        }

        return $original;

    }
}
