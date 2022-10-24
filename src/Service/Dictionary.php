<?php

namespace App\Service;

use App\Builder\Contracts\BuilderInterface;
use App\Client\Contracts\ClientInterface;
use App\Entity\Entry;
use App\Exception\ApiExecutionException;
use App\Exception\DictionaryException;
use App\Type\LangType;
use GuzzleHttp\Exception\GuzzleException;

class Dictionary
{
    private ClientInterface $client;
    private BuilderInterface $builder;

    public function __construct(ClientInterface $client, BuilderInterface $builder)
    {
        $this->client = $client;
        $this->builder = $builder;
    }

    public function getEnteries(string $lang, string $word): Entry
    {
        try {
            $endPoint = sprintf('/entries/%s/%s', $lang, $word);
            $data = $this->client->getData($endPoint);
        } catch (GuzzleException $exception) {
            if ($exception->getCode() === 404) {
                throw new DictionaryException('No entry found matching supplied source language, word and provided filters.');
            }

            throw new ApiExecutionException('Api request execution error.');
        }

        return $this->builder->build($data->toObject());
    }
}
