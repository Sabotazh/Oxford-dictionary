<?php

namespace App\Tests\Unit\Service;

use App\Entity\Search;
use App\Repository\SearchRepository;
use App\Service\SearchService;
use PHPUnit\Framework\TestCase;

class SearchServiceTest extends TestCase
{
    /**
     * @group unit
     * @dataProvider dataProvider
     */
    public function testCheckExistentWord(string $word): void
    {
        $searchRepository = \Mockery::mock(SearchRepository::class);
        $searchRepository
            ->shouldReceive('findOneBy')
            ->with(['word' => $word])
            ->andReturn(new Search());
        $searchRepository
            ->shouldReceive('save');

        $service = new SearchService($searchRepository);
        $result = $service->checkWord($word);

        $this->assertNull($result);
    }

    /**
     * @group unit
     * @dataProvider dataProvider
     */
    public function testCheckNonExistentWord(string $word): void
    {
        $searchRepository = \Mockery::mock(SearchRepository::class);
        $searchRepository
            ->shouldReceive('findOneBy')
            ->with(['word' => $word])
            ->andReturn(null);
        $searchRepository
            ->shouldReceive('save');

        $service = new SearchService($searchRepository);
        $result = $service->checkWord($word);

        $this->assertNull($result);
    }

    public function dataProvider(): array
    {
        return [
            ['test'],
        ];
    }
}
