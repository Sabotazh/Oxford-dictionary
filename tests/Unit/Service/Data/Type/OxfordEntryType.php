<?php

namespace App\Tests\Unit\Service\Data\Type;

class OxfordEntryType
{
    public array $pronunciations;
    public array $senses;

    public function __construct(bool $hasPronunciation = true)
    {
        $this->pronunciations = [new OxfordPronunciationType()];

        $this->senses = [new OxfordSenseType()];
    }
}
