<?php

namespace App\Service;

use Symfony\Contracts\Translation\TranslatorInterface;

class MealTranslator
{
    public function __construct(private TranslatorInterface $translator)
    {
    }

    public function translateMealData($data, string $locale)
    {
        foreach ($data as $key => $value) {

            if (is_string($value)) {
                $data->$key = $this->translator->trans($value, [], domain: 'meals', locale: $locale);
            }

            if (is_object($value) || is_array(($value))) {
                $this->translateMealData($value, $locale);
            }
        }

        return $data;
    }

}
