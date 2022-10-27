<?php

namespace App\Fixture;

use App\Entity\History;
use App\Repository\HistoryRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as Faker;
use Faker\Generator;

class HistoryFixtures extends Fixture
{
    private HistoryRepository $repository;
    private Generator $faker;

    public function __construct(HistoryRepository $repository, Faker $faker)
    {
        $this->repository = $repository;
        $this->faker = $faker::create();
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 50; ++$i) {
            $word = new History();
            $word->setValue($this->faker->unique()->word());
            $word->setCount(mt_rand(1, 10));

            $this->repository->save($word, true);
        }
    }
}
