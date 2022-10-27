<?php

namespace App\Service;

use App\Entity\Search;
use App\Repository\SearchRepository;

class SearchService
{
    private SearchRepository $repository;

    public function __construct(SearchRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param string $word
     */
    public function checkWord(string $word): void
    {
        if ($history = $this->repository->findOneBy(['word' => $word])) {
            $count = $history->getCount();
            $history->setCount(++$count);
        } else {
            $history = new Search();
            $history->setWord($word);
        }

        $this->repository->save($history, true);
    }
}
