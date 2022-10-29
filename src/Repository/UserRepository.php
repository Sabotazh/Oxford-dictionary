<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function save(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByRole(string $role): array
    {
        return $this->createQueryBuilder('users')
            ->andWhere('JSON_CONTAINS(users.roles, :role) = 1')
            ->setParameter('role', '"ROLE_' . mb_strtoupper($role) . '"')
            ->getQuery()
            ->getResult();
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);

        $this->save($user, true);
    }

    /**
     * @return array
     * @throws \Doctrine\DBAL\Exception
     */
    public function countRegisteredUsersByMonth(): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "
            SELECT MONTH(users.created_at) as 'month', COUNT(users.id) as 'total' 
            FROM users 
            GROUP BY MONTH(users.created_at)
            ";
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();

        return $resultSet->fetchAllKeyValue();
    }

    /**
     * @param int $page
     * @param int $max
     * @return \Doctrine\ORM\Tools\Pagination\Paginator
     */
    public function pagination(int $page = 1, int $max = 10): Paginator
    {
        $dql = $this->createQueryBuilder('user');
        $dql->orderBy('user.name');

        $firstResult = ($page - 1) * $max;

        $query = $dql->getQuery();
        $query->setFirstResult($firstResult);
        $query->setMaxResults($max);

        $paginator = new Paginator($query);

        if (($paginator->count() <=  $firstResult) && $page !== 1) {
            throw new NotFoundHttpException('Page not found');
        }

        return $paginator;
    }
}
