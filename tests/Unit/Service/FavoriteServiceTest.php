<?php

namespace App\Tests\Unit\Service;

use App\Entity\Favorite;
use App\Exception\FavoriteException;
use App\Repository\FavoriteRepository;
use App\Service\FavoriteService;
use PHPUnit\Framework\TestCase;

class FavoriteServiceTest extends TestCase
{
    /**
     * @group unit
     * @dataProvider dataProvider
     */
    public function testIsExist(array $input): void
    {
        [$userId, $wordId] = $input;
        $criteria = ['user_id' => $userId, 'word_id' => $wordId];

        $favoriteRepository = \Mockery::mock(FavoriteRepository::class);
        $favoriteRepository
            ->shouldReceive('findOneBy')
            ->with($criteria)
            ->andReturn(new Favorite());


        $service = new FavoriteService($favoriteRepository);

        $this->expectException(FavoriteException::class);
        $service->isExists($userId, $wordId);
    }

    /**
     * @group unit
     * @dataProvider dataProvider
     */
    public function testIsNotExist(array $input): void
    {
        [$userId, $wordId] = $input;
        $criteria = ['user_id' => $userId, 'word_id' => $wordId];

        $favoriteRepository = \Mockery::mock(FavoriteRepository::class);
        $favoriteRepository
            ->shouldReceive('findOneBy')
            ->with($criteria)
            ->andReturn(null);


        $service = new FavoriteService($favoriteRepository);
        $result = $service->isExists($userId, $wordId);

        $this->assertNull($result);
    }

    public function dataProvider(): array
    {
        return [
            [
                [1, 1],
            ],
        ];
    }
}
