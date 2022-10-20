<?php

namespace App\Builder;

use App\Builder\Contracts\BuilderInterface;
use App\Entity\Entry;

class EntryBuilder implements BuilderInterface
{
    private object $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * @return Entry
     */
    public function build(): Entry
    {
        $translations = [];
        $pronansiations = [];

        foreach ($this->data->results as $result) {
            foreach ($result->lexicalEntries as $lexicalEntry) {
                foreach ($lexicalEntry->entries as $entry) {
                    foreach ($entry->senses as $sense) {
                        if (isset($sense->translations)) {
                            $translations = array_unique(
                                array_merge(
                                    array_map(fn (object $translation): string => $translation->text, $sense->translations),
                                    $translations
                                )
                            );
                        }
                    }

                    if (isset($entry->pronunciations)) {
                        $pronansiations = array_unique(
                            array_merge(
                                array_map(fn (object $pronunciation): string => $pronunciation->audioFile, $entry->pronunciations),
                                $pronansiations
                            )
                        );
                    }
                }
            }
        }

        $entry = new Entry();
        $entry->setTranslations($translations);
        $entry->setPronansiations($pronansiations);

        return $entry;
    }
}
