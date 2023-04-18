<?php

namespace App\Constraints;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class RequestConstraints
{
    public static function getRequestConstraints()
    {
        return new Assert\Collection([
            'per_page' => new Assert\Optional([
                new Assert\Type('integer'),
                new Assert\Positive(),
            ]),
            'page' => new Assert\Optional([
                new Assert\Type('integer'),
                new Assert\Positive(),
            ]),
            'category' => new Assert\Optional([
                new Assert\Callback(function ($value, $context) {
                    if (!is_int($value) && $value !== 'NULL' && $value !== '!NULL') {
                        throw new UnexpectedValueException(
                            $value,
                            'positive integer or string ["NULL" or "!NULL"]'
                        );
                    }
                })
            ]),
            'tags' => new Assert\Optional([
                new Assert\Type('array'),
                new Assert\All([
                    new Assert\Type('integer'),
                ]),
            ]),
            'with' => new Assert\Optional([
                new Assert\Type('array'),
                new Assert\All([
                    new Assert\Choice(['ingredients', 'category', 'tags']),
                ]),
            ]),
            'lang' => new Assert\NotBlank(),
            'diff_time' => new Assert\Optional([
                new Assert\Type('integer'),
                new Assert\Positive(),
            ]),
        ]);
    }
}
