<?php

namespace App\Repository;

use App\Entity\Favorite;
use App\Entity\Search;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Favorite>
 *
 * @method Favorite|null find($id, $lockMode = null, $lockVersion = null)
 * @method Favorite|null findOneBy(array $criteria, array $orderBy = null)
 * @method Favorite[]    findAll()
 * @method Favorite[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FavoriteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Favorite::class);
    }

    public function save(Favorite $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Favorite $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByUser(int $id): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "
            SELECT uf.id, uf.user_id, uf.word_id, s.word
            FROM user_favorites AS uf
            LEFT JOIN searches AS s ON uf.word_id = s.id
            WHERE uf.user_id = $id
            GROUP BY uf.id, uf.word_id
            ";
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();

        return array_map(function (array $el) {
            $favorite = new Favorite();
            $favorite
                ->setId($el['id'])
                ->setUserId($el['user_id'])
                ->setWordId($el['word_id'])
                ->setWord($el['word']);

            return $favorite;
        }, $resultSet->fetchAllAssociative());
    }
}
