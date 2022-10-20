<?php

namespace App\Service;

use App\Builder\EntryBuilder;
use App\Client\Contracts\ClientInterface;
use App\Entity\Entry;
use App\Type\LangType;

class DictionaryService
{
    private ClientInterface $client;
    private string $baseUrl = 'https://od-api.oxforddictionaries.com/api/v2';

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @param string $lang
     * @param string $word
     * @return Entry[]
     */
    public function getEnteries(string $lang, string $word): array
    {
        $lang = $this->parsLang($lang);
        $url = sprintf('%s/translations/%s/%s/%s',
            $this->baseUrl,
            $lang->sourceLang,
            $lang->targetLang,
            $word
        );
        $data = $this->client->getData($url);

        return [
            (new EntryBuilder($data))->build()
        ];
    }

    /**
     * @param string $lang
     * @return LangType
     */
    private function parsLang(string $lang): LangType
    {
        return new LangType($lang);
    }
}
