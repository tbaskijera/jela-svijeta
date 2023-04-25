<?php

namespace App\Service;

use App\Entity\MealTranslation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Translation\Loader\LoaderInterface;
use Symfony\Component\Translation\MessageCatalogue;

class MealTranslationLoader implements LoaderInterface
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

    }

    public function load($resource, $locale, $domain = 'meals'): MessageCatalogue
    {

        if($domain == 'meals') {
            $mealTranslations = $this->entityManager->getRepository(MealTranslation::class)->findBy(['locale' => $locale]);
            $catalogue = new MessageCatalogue($locale);

            foreach ($mealTranslations as $mealTranslation) {
                $catalogue->set($mealTranslation->getTitle(), $domain);
            }

            return $catalogue;
        }
    }
}
