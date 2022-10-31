<?php

namespace App\Client\Contracts;

use App\Type\JsonType;

interface ClientInterface
{
    public function getData(string $endPoint): JsonType;
}
