<?php

namespace App\Entity;

class Entry
{
    private array $definitions;
    private array $pronansiations;

    public function __construct()
    {
        $this->definitions = [];
        $this->pronansiations = [];
    }

    /**
     * @return array
     */
    public function getDefinitions(): array
    {
        return $this->definitions;
    }

    /**
     * @param string $definition
     */
    public function addDefinitions(string $definition): void
    {
        if (!in_array($definition, $this->definitions)) {
            $this->definitions[] = $definition;
        }
    }

    /**
     * @return array
     */
    public function getPronansiations(): array
    {
        return $this->pronansiations;
    }

    /**
     * @param string $pronansiation
     */
    public function addPronansiations(string $pronansiation): void
    {
        if (!in_array($pronansiation, $this->pronansiations)) {
            $this->pronansiations[] = $pronansiation;
        }
    }
}
