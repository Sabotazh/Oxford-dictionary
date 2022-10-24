<?php

namespace App\Builder;

use App\Builder\Contracts\BuilderInterface;
use App\Entity\Entry;

class OxfordEntryBuilder implements BuilderInterface
{
    /**
     * @param $data
     * @return Entry
     */
    public function build($data): Entry
    {
        $entity = new Entry();

        foreach ($data->results as $result) {
            foreach ($result->lexicalEntries as $lexicalEntry) {
                foreach ($lexicalEntry->entries as $entry) {
                    if (isset($entry->pronunciations)) {
                        foreach ($entry->pronunciations as $pronunciation) {
                            $entity->addPronansiations($pronunciation->audioFile);
                        }
                    }

                    foreach ($entry->senses as $sense) {
                        if (isset($sense->definitions)) {
                            foreach ($sense->definitions as $definition) {
                                $entity->addDefinitions($definition);
                            }
                        }
                    }
                }
            }
        }

        return $entity;
    }
}
