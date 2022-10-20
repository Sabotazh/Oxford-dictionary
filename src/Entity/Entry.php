<?php

namespace App\Entity;

class Entry
{
    private array $translations;
    private array $pronansiations;

    /**
     * @return array
     */
    public function getTranslations(): array
    {
        return $this->translations;
    }

    /**
     * @param array $translations
     */
    public function setTranslations(array $translations): void
    {
        $this->translations = $translations;
    }

    /**
     * @return array
     */
    public function getPronansiations(): array
    {
        return $this->pronansiations;
    }

    /**
     * @param array $pronansiations
     */
    public function setPronansiations(array $pronansiations): void
    {
        $this->pronansiations = $pronansiations;
    }
}
