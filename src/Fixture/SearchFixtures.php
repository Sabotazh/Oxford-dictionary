<?php

namespace App\Fixture;

use App\Entity\Search;
use App\Repository\SearchRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as Faker;
use Faker\Generator;

class SearchFixtures extends Fixture
{
    private SearchRepository $repository;
    private Generator $faker;

    public function __construct(SearchRepository $repository, Faker $faker)
    {
        $this->repository = $repository;
        $this->faker = $faker::create();
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 50; ++$i) {
            $search = new Search();
            $search
                ->setWord($this->faker->unique()->word())
                ->setCount(mt_rand(1, 10));

            $this->repository->save($search, true);
        }
    }
}
