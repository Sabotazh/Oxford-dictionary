<?php

namespace App\Factory\Contracts;

interface FactoryInterface
{
    public function create(string $provider): mixed;
}
