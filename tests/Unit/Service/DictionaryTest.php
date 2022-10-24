<?php

namespace App\Tests\Unit\Service;

use App\Exception\DictionaryException;
use App\Factory\BuilderFactory;
use App\Factory\ClientFactory;
use App\Factory\Contracts\FactoryInterface;
use App\Service\Dictionary;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class DictionaryTest extends KernelTestCase
{
    private FactoryInterface $clientFactory;
    private FactoryInterface $builderFactory;

    protected function setUp(): void
    {
        self::bootKernel();

        $this->clientFactory = static::getContainer()->get(ClientFactory::class);
        $this->builderFactory = static::getContainer()->get(BuilderFactory::class);
    }

    /**
     * @dataProvider positiveDataProvider
     */
    public function testGetDefinitionsAndPronunciations($input): void
    {
        [$provider, $lang, $word] = $input;

        $client = $this->clientFactory->create($provider);
        $entityBuilder = $this->builderFactory->create($provider);

        $dictionary = new Dictionary($client, $entityBuilder);
        $result = $dictionary->getEnteries($lang, $word);

        $this->assertNotEmpty($result->getDefinitions());
        $this->assertNotEmpty($result->getPronansiations());
    }

    /**
     * @dataProvider negativeDataProvider
     */
    public function testGetNoResult($input): void
    {
        [$provider, $lang, $word] = $input;

        $client = $this->clientFactory->create($provider);
        $entityBuilder = $this->builderFactory->create($provider);

        $dictionary = new Dictionary($client, $entityBuilder);

        $this->expectException(DictionaryException::class);
        $dictionary->getEnteries($lang, $word);
    }

    public function positiveDataProvider(): array
    {
        return [
            [
                ['oxford', 'en-gb', 'test'],
                ['oxford', 'es', 'prueba'],
                ['oxford', 'fr', 'examen'],
            ],
        ];
    }

    public function negativeDataProvider(): array
    {
        return [
            [
                ['oxford', 'en-gb', 'kjshkwe'],
                ['oxford', 'es', 'wefwfwefw'],
                ['oxford', 'fr', 'wefwfewe'],
            ],
        ];
    }
}
