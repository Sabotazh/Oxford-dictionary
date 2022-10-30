<?php

namespace App\Tests\Functional\Client;

use App\Client\OxfordClient;
use App\Type\JsonType;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class OxfordClientTest extends KernelTestCase
{
    private OxfordClient $client;

    protected function setUp(): void
    {
        $this->client = static::getContainer()->get(OxfordClient::class);
    }

    /**
     * @group functional
     * @dataProvider dataProvider
     */
    public function testGetDataFromOxfordAPIClient(array $input): void
    {
        [$lang, $word] = $input;

        $endPoint = sprintf('/entries/%s/%s', $lang, $word);

        $result = $this->client->getData($endPoint);

        $this->assertInstanceOf(JsonType::class, $result);
        $this->assertNotEmpty($result->toObject());
    }

    public function dataProvider(): array
    {
        return [
            [
                ['en-gb', 'test'],
            ],
        ];
    }
}
