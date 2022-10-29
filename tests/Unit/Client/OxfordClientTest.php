<?php

namespace App\Tests\Unit\Client;

use App\Client\OxfordClient;
use App\Tests\Unit\Service\Data\OxfordResponseData;
use App\Type\JsonType;
use PHPUnit\Framework\TestCase;

class OxfordClientTest extends TestCase
{
    /**
     * @group unit
     * @dataProvider dataProvider
     */
    public function testCheckReturnTypeFromOxfordAPIClient(array $input): void
    {
        [$lang, $word] = $input;

        $endPoint = sprintf('/entries/%s/%s', $lang, $word);
        $response = new OxfordResponseData();

        $client = \Mockery::mock(OxfordClient::class);
        $client
            ->shouldReceive('getData')
            ->with($endPoint)
            ->andReturn(new JsonType(json_encode($response)));

        $result = $client->getData($endPoint);

        $this->assertInstanceOf(JsonType::class, $result);
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
