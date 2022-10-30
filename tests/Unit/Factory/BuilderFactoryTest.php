<?php

namespace App\Tests\Unit\Factory;

use App\Builder\OxfordEntryBuilder;
use App\Exception\BuilderNotFoundException;
use App\Factory\BuilderFactory;
use PHPUnit\Framework\TestCase;

final class BuilderFactoryTest extends TestCase
{
    /**
     * @group unit
     * @dataProvider dataProvider
     */
    public function testCreateOxfordEntryBuilder(string $provider): void
    {
        $factory = new BuilderFactory();
        $builder = $factory->create($provider);

        $this->assertInstanceOf(OxfordEntryBuilder::class, $builder);
    }

    /**
     * @group unit
     * @dataProvider negativeDataProvider
     */
    public function testThrowExceptionIfProviderNotExist(string $wrongProvider): void
    {
        $factory = new BuilderFactory();

        $this->expectException(BuilderNotFoundException::class);
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
