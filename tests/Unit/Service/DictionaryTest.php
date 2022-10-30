<?php

namespace App\Tests\Unit\Service;

use App\Builder\OxfordEntryBuilder;
use App\Client\OxfordClient;
use App\Exception\ApiExecutionException;
use App\Exception\DictionaryException;
use App\Service\Dictionary;
use App\Tests\Unit\Service\Data\OxfordResponseData;
use App\Type\JsonType;
use GuzzleHttp\Exception\TransferException;
use PHPUnit\Framework\TestCase;

final class DictionaryTest extends TestCase
{
    /**
     * @group unit
     * @dataProvider dataProvider
     */
    public function testGetDefinitionsAndPronunciations(array $input): void
    {
        [$lang, $word] = $input;

        $endPoint = sprintf('/entries/%s/%s', $lang, $word);
        $response = new OxfordResponseData();

        $client = \Mockery::mock(OxfordClient::class);
        $client
            ->shouldReceive('getData')
            ->with($endPoint)
            ->andReturn(new JsonType(json_encode($response)));

        $entityBuilder = new OxfordEntryBuilder();

        $dictionary = new Dictionary($client, $entityBuilder);
        $result = $dictionary->getEnteries($lang, $word);

        $this->assertNotEmpty($result->getDefinitions());
        $this->assertNotEmpty($result->getPronansiations());
    }

    /**
     * @group unit
     * @dataProvider dataProvider
     */
    public function testGetOnlyDefinitions(array $input): void
    {
        [$lang, $word] = $input;

        $endPoint = sprintf('/entries/%s/%s', $lang, $word);
        $response = new OxfordResponseData();

        $client = \Mockery::mock(OxfordClient::class);
        $client
            ->shouldReceive('getData')
            ->with($endPoint)
            ->andReturn(new JsonType(json_encode($response->withoutPronansiations())));

        $entityBuilder = new OxfordEntryBuilder();

        $dictionary = new Dictionary($client, $entityBuilder);
        $result = $dictionary->getEnteries($lang, $word);

        $this->assertNotEmpty($result->getDefinitions());
        $this->assertEmpty($result->getPronansiations());
    }

    /**
     * @group unit
     * @dataProvider dataProvider
     */
    public function testGetNoResults(array $input): void
    {
        [$lang, $word] = $input;

        $endPoint = sprintf('/entries/%s/%s', $lang, $word);
        $response = new OxfordResponseData();

        $client = \Mockery::mock(OxfordClient::class);
        $client
            ->shouldReceive('getData')
            ->with($endPoint)
            ->andReturn(new JsonType(json_encode($response->withoutPronansiations()->withoutDefinitions())));

        $entityBuilder = new OxfordEntryBuilder();

        $dictionary = new Dictionary($client, $entityBuilder);
        $result = $dictionary->getEnteries($lang, $word);

        $this->assertEmpty($result->getDefinitions());
        $this->assertEmpty($result->getPronansiations());
    }

    /**
     * @group unit
     * @dataProvider dataProvider
     */
    public function testThrowsDictionaryException(array $input): void
    {
        [$lang, $word] = $input;

        $endPoint = sprintf('/entries/%s/%s', $lang, $word);

        $client = \Mockery::mock(OxfordClient::class);
        $client
            ->shouldReceive('getData')
            ->with($endPoint)
            ->andThrowExceptions([new TransferException('', 404)]);

        $entityBuilder = new OxfordEntryBuilder();
        $dictionary = new Dictionary($client, $entityBuilder);

        $this->expectException(DictionaryException::class);
        $dictionary->getEnteries($lang, $word);
    }

    /**
     * @group unit
     * @dataProvider dataProvider
     */
    public function testThrowsApiExecutionException(array $input): void
    {
        [$lang, $word] = $input;

        $endPoint = sprintf('/entries/%s/%s', $lang, $word);

        $client = \Mockery::mock(OxfordClient::class);
        $client
            ->shouldReceive('getData')
            ->with($endPoint)
            ->andThrowExceptions([new TransferException()]);

        $entityBuilder = new OxfordEntryBuilder();
        $dictionary = new Dictionary($client, $entityBuilder);

        $this->expectException(ApiExecutionException::class);
        $dictionary->getEnteries($lang, $word);
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
