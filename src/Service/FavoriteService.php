<?php

namespace App\Service;

use App\Exception\FavoriteException;
use App\Repository\FavoriteRepository;
use App\Repository\WordRepository;

class FavoriteService
{

    private FavoriteRepository $repository;

    public function __construct(FavoriteRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param int $userId
     * @param int $wordId
     * @throws FavoriteException
     */
    public function isExists(int $userId, int $wordId): void
    {
        if ($this->repository->findOneBy(['user_id' => $userId, 'word_id' => $wordId])) {
            throw new FavoriteException('This word has already been added to your favorites.');
        }
    }
}
