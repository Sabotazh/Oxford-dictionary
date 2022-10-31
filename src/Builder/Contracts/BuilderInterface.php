<?php

namespace App\Builder\Contracts;

use App\Entity\Entry;

interface BuilderInterface
{
    /**
     * @param mixed $data
     * @return Entry
     */
    public function build(mixed $data): Entry;
}
