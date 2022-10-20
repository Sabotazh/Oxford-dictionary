<?php

namespace App\Builder\Contracts;

use App\Entity\Entry;

interface BuilderInterface
{
    /**
     * @param $data
     * @return Entry
     */
    public function build($data): Entry;
}
