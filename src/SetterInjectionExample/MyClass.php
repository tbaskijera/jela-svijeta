<?php

namespace App\SetterInjectionExample;

class MyClass
{
    private $adder;

    public function setService(Adder $adder)
    {
        $this->adder = $adder;
    }

    public function printAction()
    {
        $a = 5;
        $b = 3;

        echo $this->adder->add($a, $b);
    }
}
