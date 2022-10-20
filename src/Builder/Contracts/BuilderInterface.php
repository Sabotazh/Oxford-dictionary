<?php

namespace App\Builder\Contracts;

use App\Entity\Entry;

interface BuilderInterface
{
    public function build(): Entry;
}
