<?php

namespace App\Repository;

use App\Entity\Advisor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Advisor|null find($id, $lockMode = null, $lockVersion = null)
 * @method Advisor|null findOneBy(array $criteria, array $orderBy = null)
 * @method Advisor[]    findAll()
 */
class AdvisorRepository extends ServiceEntityRepository
{
    /**
     * AdvisorRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Advisor::class);
    }

    /**
     * @param array $criteria
     * @param array|null $orderBy
     * @param null $limit
     * @param null $offset
     * @return object[]|void
     */
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        $newCriteria = [];
        if (isset($criteria['name'])) {
            $newCriteria['name'] = $criteria['name'];
        }
        if (isset($criteria['language'])) {

            $crt = Criteria::create();
            $crt->where(Criteria::expr()->isNull("JSON_SEARCH(languages_6, 'one', " . $criteria['language'] . ")"));
            $newCriteria['languages'] = $crt;
        }
        return parent::findBy($newCriteria, $orderBy = null, $limit = null, $offset = null);
    }
}
