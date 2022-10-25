<?php

namespace App\Tests\Unit\Service\Data;

use App\Tests\Unit\Service\Data\Type\OxfordResultType;

class OxfordResponseData
{
    public array $results;

    public function __construct()
    {
        $this->results = [new OxfordResultType()];
    }

    public function withoutPronansiations(): self
    {
        foreach ($this->results as $result) {
            foreach ($result as $lexicalEntries) {
                foreach ($lexicalEntries as $entries) {
                    foreach ($entries as $entry) {
                        foreach ($entry as $data) {
                            $data->pronunciations = [];
                        }
                    }
                }
            }
        }

        return $this;
    }

    public function withoutDefinitions(): self
    {
        foreach ($this->results as $result) {
            foreach ($result as $lexicalEntries) {
                foreach ($lexicalEntries as $entries) {
                    foreach ($entries as $entry) {
                        foreach ($entry as $data) {
                            foreach ($data->senses as $sens) {
                                $sens->definitions = [];
                            }
                        }
                    }
                }
            }
        }

        return $this;
    }
}
