<?php

namespace App\Type;

class LangType
{
    public string $sourceLang;
    public string $targetLang;

    public function __construct(string $lang)
    {
        $langs = explode('-', $lang);
        $this->sourceLang = $langs[0];
        $this->targetLang = $langs[1];
    }
}
