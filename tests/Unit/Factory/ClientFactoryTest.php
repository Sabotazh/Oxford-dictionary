<?php

namespace App\Tests\Unit\Factory;

use App\Client\OxfordClient;
use App\Exception\ClientNotFoundException;
use App\Factory\ClientFactory;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class ClientFactoryTest extends KernelTestCase
{
    /**
     * @group unit
     * @dataProvider dataProvider
     */
    public function testCreateOxfordClient(string $provider): void
    {
        $factory = new ClientFactory(static::getContainer()->get(OxfordClient::class));
        $client = $factory->create($provider);

        $this->assertInstanceOf(OxfordClient::class, $client);
    }

    /**
     * @group unit
     * @dataProvider negativeDataProvider
     */
    public function testThrowExceptionIfProviderNotExist(string $wrongProvider): void
    {
        $factory = new ClientFactory(static::getContainer()->get(OxfordClient::class));

        $this->expectException(ClientNotFoundException::class);
        $factory->create($wrongProvider);
    }

    public function dataProvider(): array
    {
        return [
            ['oxford'],
        ];
    }

    public function negativeDataProvider(): array
    {
        return [
            ['not_exist'],
        ];
    }
}
