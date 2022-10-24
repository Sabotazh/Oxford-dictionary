<?php

namespace App\Client;

use App\Client\Contracts\ClientInterface;
use App\Type\JsonType;
use GuzzleHttp\Client as Guzzle;

class OxfordClient implements ClientInterface
{
    private string $baseUrl;
    private string $appId;
    private string $appKey;
    private Guzzle $client;

    public function __construct(
        string $baseUrl,
        string $appId,
        string $appKey,
        Guzzle $client
    )
    {
        $this->baseUrl = $baseUrl;
        $this->appId = $appId;
        $this->appKey = $appKey;
        $this->client = $client;
    }

    /**
     * @param string $endPoint
     * @return JsonType
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getData(string $endPoint): JsonType
    {
        $url = sprintf('%s%s', $this->baseUrl, $endPoint);
        $resourse = $this->client->request('GET', $url, ['headers' => $this->getHeaderOptions()]);

        return new JsonType($resourse->getBody()->getContents());
    }

    /**
     * @return array
     */
    private function getHeaderOptions(): array
    {
        return [
            'Accept'    => 'application/json',
            'app_id'    => $this->appId,
            'app_key'   => $this->appKey,
        ];;
    }
}
