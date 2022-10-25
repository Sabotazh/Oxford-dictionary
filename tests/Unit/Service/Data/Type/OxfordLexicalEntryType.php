<?php

namespace App\Tests\Unit\Service\Data\Type;

class OxfordLexicalEntryType
{
    public array $entries;

    public function __construct()
    {
        $this->entries = [new OxfordEntryType()];
    }
}
