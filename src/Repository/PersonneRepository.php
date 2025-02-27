<?php

namespace App\Repository;

use App\Entity\Personne;
use App\Entity\Espece;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Personne|null find($id, $lockMode = null, $lockVersion = null)
 * @method Personne|null findOneBy(array $criteria, array $orderBy = null)
 * @method Personne[]    findAll()
 * @method Personne[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PersonneRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Personne::class);
    }

    public function getPariteEspece(Espece $espece)
    {
        $qb = $this->createQueryBuilder('p');

        $qb 
            ->innerJoin('p.animal', 'a', 'WITH', 'a.espece = :espece')
            ->andWhere('p.sexe = :sexe')
            ->setParameter('espece', $espece->getId());

        $data['hommes'] = count($qb->setParameter('sexe', 'H')->getQuery()->getResult());
        $data['femmes'] = count($qb->setParameter('sexe', 'F')->getQuery()->getResult());
        $data['total'] = $data['hommes'] + $data['femmes'];

        return $data;
    }
}
