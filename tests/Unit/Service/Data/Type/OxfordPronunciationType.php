<?php

namespace App\Tests\Unit\Service\Data\Type;

class OxfordPronunciationType
{
    public string $audioFile;

    public function __construct()
    {
        $this->audioFile = 'https://audio.oxforddictionaries.com/en/mp3/test__gb_1.mp3';
    }
}
