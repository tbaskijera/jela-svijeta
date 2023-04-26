<?php

namespace App\SetterInjectionExample;

class Adder
{
    public function add(int $a, int $b): int
    {
        return $a+$b;
    }
}
