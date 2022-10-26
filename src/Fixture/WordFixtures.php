<?php

namespace App\Fixture;

use App\Entity\Word;
use App\Repository\WordRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as Faker;
use Faker\Generator;

class WordFixtures extends Fixture
{
    private WordRepository $repository;
    private Generator $faker;

    public function __construct(WordRepository $repository, Faker $faker)
    {
        $this->repository = $repository;
        $this->faker = $faker::create();
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 50; ++$i) {
            $word = new Word();
            $word->setName($this->faker->unique()->word());

            $this->repository->save($word, true);
        }
    }
}