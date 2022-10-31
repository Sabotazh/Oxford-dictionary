<?php

namespace App\Fixture;

use App\Entity\Favorite;
use App\Entity\Search;
use App\Entity\User;
use App\Repository\FavoriteRepository;
use App\Repository\SearchRepository;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class FavoriteFixtures extends Fixture implements DependentFixtureInterface
{
    private FavoriteRepository $favoriteRepository;
    private UserRepository $userRepository;
    private SearchRepository $searcheRepository;

    public function __construct(
        FavoriteRepository $favoriteRepository,
        UserRepository     $userRepository,
        SearchRepository   $searcheRepository,
    )
    {
        $this->favoriteRepository = $favoriteRepository;
        $this->userRepository = $userRepository;
        $this->searcheRepository = $searcheRepository;
    }

    public function load(ObjectManager $manager)
    {
        $usersIds = array_map(function (User $user): int {
            return $user->getId();
        }, $this->userRepository->findAll());

        $searchesIds = array_map(function (Search $searche): int {
            return $searche->getId();
        }, $this->searcheRepository->findAll());

        for ($i = 0; $i < 100; ++$i) {
            if (empty($searchesIds)) break;

            $userId = $usersIds[array_rand($usersIds)];

            $searcheArrRandomIndex = array_rand($searchesIds);
            $searcheId = $searchesIds[$searcheArrRandomIndex];
            array_splice($searchesIds, $searcheArrRandomIndex, 1);

            $favorite = new Favorite();
            $favorite->setUserId($userId)
                ->setWordId($searcheId)
                ->setCreatedAt()
                ->setUpdatedAt();

            $this->favoriteRepository->save($favorite, true);
        }
    }

    public function getDependencies(): array
    {
        return [
            'App\Fixture\UserFixtures',
            'App\Fixture\SearchFixtures',
        ];
    }
}
