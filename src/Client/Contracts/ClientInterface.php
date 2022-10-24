<?php

namespace App\Client\Contracts;

interface ClientInterface
{
    public function getData(string $endPoint);
}
