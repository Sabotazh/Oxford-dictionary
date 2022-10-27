<?php

namespace App\Fixture;

use App\Entity\Favorite;
use App\Entity\User;
use App\Entity\Word;
use App\Repository\FavoriteRepository;
use App\Repository\UserRepository;
use App\Repository\WordRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as Faker;

class FavoriteFixtures extends Fixture implements DependentFixtureInterface
{

    private FavoriteRepository $favoriteRepository;
    private UserRepository $userRepository;
    private WordRepository $wordRepository;
    private Faker $faker;

    public function __construct(
        FavoriteRepository $favoriteRepository,
        UserRepository $userRepository,
        WordRepository $wordRepository,
        Faker $faker
    )
    {
        $this->favoriteRepository = $favoriteRepository;
        $this->userRepository = $userRepository;
        $this->wordRepository = $wordRepository;
        $this->faker = $faker;
    }

    public function load(ObjectManager $manager)
    {
        $usersIds = array_map(function (User $user): int {
            return $user->getId();
        }, $this->userRepository->findAll());

        $wordsIds = array_map(function (Word $word): int {
            return $word->getId();
        }, $this->wordRepository->findAll());

        for ($i = 0; $i < 100; ++$i) {
            $userId = $usersIds[array_rand($usersIds)];

            if (empty($wordsIds)) {
                break;
            }

            $wordArrRandomIndex = array_rand($wordsIds);
            $wordId = $wordsIds[$wordArrRandomIndex];
            array_splice($wordsIds, $wordArrRandomIndex, 1);

            $favorite = new Favorite();
            $favorite->setUserId($userId)
                ->setWordId($wordId)
                ->setCount(0)
                ->setCreatedAt()
                ->setUpdatedAt();

            $this->favoriteRepository->save($favorite, true);
        }
    }

    public function getDependencies(): array
    {
        return [
            'App\Fixture\UserFixtures',
            'App\Fixture\WordFixtures',
        ];
    }
}
