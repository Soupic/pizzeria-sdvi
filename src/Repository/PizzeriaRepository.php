<?php

declare(strict_types = 1);

namespace App\Repository;

use App\Entity\Pizzeria;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class PizzeriaRepository
 * @package App\Repository
 */
class PizzeriaRepository extends ServiceEntityRepository
{
    /**
     * PizzeriaRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Pizzeria::class);
    }

    /**
     * @return array
     */
    public function findAll(): array
    {
        // exécution de la requête
        return parent::findAll();
    }

    /**
     * @param int $pizzeriaId
     * @return Pizzeria
     */
    public function findCartePizzeria(int $pizzeriaId): Pizzeria
    {
        $qb = $this->createQueryBuilder("p");

        $qb
            ->addSelect(["piz", "qte", "ing"])
            ->innerJoin( "p.pizzas", "piz")
            ->innerJoin( "piz.quantiteIngredients", "qte")
            ->innerJoin( "qte.ingredient", "ing")
            ->where( "p.id = :idPizzeria")
            ->setParameter("idPizzeria", $pizzeriaId)
        ;

        return $qb->getQuery()->getSingleResult();
    }
}
