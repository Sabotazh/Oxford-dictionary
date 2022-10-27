<?php

namespace App\Service;

use App\Entity\History;
use App\Repository\HistoryRepository;

class HistoryService
{
    private HistoryRepository $repository;

    public function __construct(HistoryRepository $repository)
    {
        $this->repository = $repository;
    }

    public function checkWord(string $word)
    {
        if ($history = $this->isOld($word)) {
            $currentCount = $history->getCount();
            $history->setCount(++$currentCount);
        } else {
            $history = new History();
            $history->setValue($word);
        }

        $this->repository->save($history, true);
    }

    private function isOld(string $word): ?History
    {
        return $this->repository->findOneBy(['value' => $word]);
    }
}
