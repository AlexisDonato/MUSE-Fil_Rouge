<?php

namespace App\Repository;

use App\Entity\Product;
use App\Data\SearchData;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Product>
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Product::class);
        $this->paginator = $paginator;
    }


    /**
     * Fetching products with a search filter
     *
     * @return PaginationInterface
     */
    public function findSearch(SearchData $search): PaginationInterface
    {
        $query = $this
            ->createQueryBuilder('p')
            ->select('c', 'p', 's')
            ->join('p.category', 'c')
            ->join('p.supplier', 's');

        // Here we look for :
        // what has been written in the search input
        if (!empty($search->q)) {
            $query = $query
                ->andWhere('p.name LIKE :q')
                ->orWhere('p.description LIKE :q')
                ->orWhere('c.name LIKE :q')
                ->orWhere('s.name LIKE :q')
                ->setParameter('q', "%{$search->q}%");
        }

        // what has been written in the min input
        if (!empty($search->min)) {
            $query = $query
                ->andWhere('p.price >= :min')
                ->setParameter('min', $search->min);
        }

        // what has been written in the max input
        if (!empty($search->max)) {
            $query = $query
                ->andWhere('p.price <= :max')
                ->setParameter('max', $search->max);
        }

        // if the discount checkbox has been checked
        if (!empty($search->discount)) {
            $query = $query
                ->andWhere('p.discountRate != 0');
        }

        // if some categories has been selected
        if (!empty($search->category)) {
            $query = $query
                ->andWhere('c.id IN (:category)')
                ->setParameter('category', $search->category);
        }

        // if some suppliers has been selected
        if (!empty($search->supplier)) {
            $query = $query
                ->andWhere('s.id IN (:supplier)')
                ->setParameter('supplier', $search->supplier);
        }

        // returns the query thanks to the paginator
        $query = $query->getQuery();
        return $this->paginator->paginate(
            $query,
            $search->page,
            9
        );
    }

    /**
     * Fetching products on discount
     *
     * @return PaginationInterface
     */
    public function findDiscount(SearchData $search): PaginationInterface
    {
        $search->discount;
        $query = $this
            ->createQueryBuilder('p')
            ->select('c', 'p')
            ->join('p.category', 'c')
            ->andWhere('p.discountRate != 0');

        $query = $query->getQuery();
        return $this->paginator->paginate(
            $query,
            $search->page,
            9
        );
    }

    // Products on discount
    public function findProductsDiscount()
    {
        return $this->createQueryBuilder('p')
                    ->where('p.discountRate != 0')
                    ->getQuery()
                    ->getResult();
    }

    // Accessories
    public function findAccessories()
    {
        return $this->createQueryBuilder('p')
                    ->select('c', 'p')
                    ->join('p.category', 'c')
                    ->andWhere('c.name LIKE :val')
                    ->setParameter('val', "%accessoires%")
                    ->getQuery()
                    ->getResult();
    }
}