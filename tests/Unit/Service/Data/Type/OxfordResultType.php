<?php

namespace App\Tests\Unit\Service\Data\Type;

class OxfordResultType
{
    public array $lexicalEntries;

    public function __construct()
    {
        $this->lexicalEntries = [new OxfordLexicalEntryType()];
    }
}
