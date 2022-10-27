<?php

namespace App\Fixture;

use App\Entity\Tag;
use App\Repository\TagRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as Faker;
use Faker\Generator;

class TagFixtures extends Fixture
{
    private TagRepository $repository;
    private Generator $faker;

    public function __construct(TagRepository $repository, Faker $faker)
    {
        $this->repository = $repository;
        $this->faker = $faker::create();
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 50; ++$i) {
            $tag = new Tag();
            $tag->setTitle($this->faker->unique()->word());

            $this->repository->save($tag, true);
        }
    }
}