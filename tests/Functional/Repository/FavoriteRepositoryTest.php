<?php

namespace App\Tests\Functional\Repository;

use App\Entity\Favorite;
use App\Repository\FavoriteRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Doctrine\Persistence\ManagerRegistry;

final class FavoriteRepositoryTest extends KernelTestCase
{
    private FavoriteRepository $favoriteRepository;

    protected function setUp(): void
    {
        $this->favoriteRepository = static::getContainer()->get(FavoriteRepository::class);
    }

    /**
     * @group functional
     */
    public function testRemoveFavorite(): void
    {
        /** @var Favorite $favorite*/
        $favorite = $this->favoriteRepository->findAll()[0];

        $this->favoriteRepository->remove($favorite, true);

        $removedWord = $this->favoriteRepository->findOneBy(['id' => $favorite->getId()]);

        $this->assertNull($removedWord);
    }
}
