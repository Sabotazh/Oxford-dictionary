<?php

namespace App\Client;

use App\Client\Contracts\ClientInterface;

class DictionaryClient implements ClientInterface
{
    private string $appId;
    private string $appKey;

    public function __construct(string $appId, string $appKey)
    {
        $this->appId = $appId;
        $this->appKey = $appKey;
    }

    public function getData(string $url): object
    {
        return json_decode(file_get_contents(
            $url,
            false,
            stream_context_create($this->headerOptions()))
        );
    }

    private function headerOptions(): array
    {
        return [
            "http" => [
                "header" => "Accept: application/json\r\n" . "app_id: $this->appId\r\n" . "app_key: $this->appKey\r\n",
            ],
        ];
    }
}
