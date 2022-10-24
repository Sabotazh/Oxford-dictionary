<?php

namespace App\Factory;

use App\Builder\Contracts\BuilderInterface;
use App\Builder\OxfordEntryBuilder;
use App\Exception\BuilderNotFoundException;
use App\Factory\Contracts\FactoryInterface;

class BuilderFactory implements FactoryInterface
{
    /**
     * @param string $provider
     * @return BuilderInterface
     * @throws BuilderNotFoundException
     */
    public function create(string $provider): BuilderInterface
    {
        switch ($provider) {
            case 'oxford':
                return new OxfordEntryBuilder();
            default:
                throw new BuilderNotFoundException('Data builder not set!');
        }
    }
}
