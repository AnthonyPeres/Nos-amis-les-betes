<?php

namespace App\Repository;

use App\Entity\Animal;
use App\Entity\Adresse;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

/**
 * @method Animal|null find($id, $lockMode = null, $lockVersion = null)
 * @method Animal|null findOneBy(array $criteria, array $orderBy = null)
 * @method Animal[]    findAll()
 * @method Animal[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnimalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Animal::class);
    }

    public function getMoyenneAge(Adresse $adresse): float 
    {
        $qb = $this->createQueryBuilder('a');

        // AVG : average (moyenne)
        // innerJoin est une jointure, une intersection 
        $qb
            ->select('AVG(a.age)')
            ->innerJoin('a.personnes', 'p', 'WITH', 'p.adresse = :adresse')
            ->setParameter('adresse', $adresse->getId());


        // getSingleScalarResult -> obtenir un resultat scalaire à 2 chiffres apres la virgule
        try {
            return round($qb->getQuery()->getSingleScalarResult(), 2);
        } catch (NoResultException $e) {

        } catch (NonUniqueResultException $e) {

        }

    }
}
