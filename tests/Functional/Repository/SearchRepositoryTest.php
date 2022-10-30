<?php

namespace App\Tests\Functional\Repository;

use App\Entity\Search;
use App\Repository\SearchRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class SearchRepositoryTest extends KernelTestCase
{
    private SearchRepository $searchRepository;

    protected function setUp(): void
    {
        $this->searchRepository = static::getContainer()->get(SearchRepository::class);
    }

    /**
     * @group functional
     */
    public function testRemoveSerchedWord(): void
    {
        /** @var Search $user*/
        $word = $this->searchRepository->findAll()[0];

        $this->searchRepository->remove($word, true);

        $removedWord = $this->searchRepository->findOneBy(['word' => $word->getWord()]);

        $this->assertNull($removedWord);
    }
}
