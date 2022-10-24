<?php

namespace App\Factory;

use App\Client\Contracts\ClientInterface;
use App\Client\OxfordClient;
use App\Exception\ClientNotFoundException;
use App\Factory\Contracts\FactoryInterface;

class ClientFactory implements FactoryInterface
{
    private OxfordClient $oxfordClient;

    public function __construct(OxfordClient $oxfordClient)
    {
        $this->oxfordClient = $oxfordClient;
    }

    /**
     * @param string $provider
     * @return ClientInterface
     * @throws ClientNotFoundException
     */
    public function create(string $provider): ClientInterface
    {
        switch ($provider) {
            case 'oxford':
                return $this->oxfordClient;
            default:
                throw new ClientNotFoundException('Resource not found!');
        }
    }
}
