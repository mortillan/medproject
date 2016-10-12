<?php

namespace App\Libraries\Repositories\Core\Doctrine;

use App\Libraries\Repositories\Core\Contracts\InterfaceDepartmentRepository;
use App\Libraries\Common\ValueObjects\SearchCriteria;
use App\Libraries\Entities\Core\Department;
use Doctrine\ORM\EntityManagerInterface;

class DepartmentRepository implements InterfaceDepartmentRepository {

    protected $repo;
    protected $em;

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    public function save(Department $department) {
        $this->em->transactional(function($em) use($department) {
            $em->persist($department);
            $em->flush();
        });
    }

    public function findByCode($code) {
        return $this->em->getRepository(Department::class)->findOneBy([
                    'code' => $code
        ]);
    }

    public function findAll(SearchCriteria $search) {
        $builder = $this->em->createQueryBuilder();

        $builder->select('t0')
                ->from(Department::class, 't0');

        foreach ($search->getColumns() as $col) {
            $builder->orWhere("t0.{$col} LIKE :{$col}")
                    ->setParameter($col, $search->getKeyword());
        }

        $builder->orderBy("t0.{$search->getSortBy()}", $search->getOrder());

        $builder->setFirstResult($search->getOffset());
        $builder->setMaxResults($search->getLimit());

        $result = $builder->getQuery()->getResult();

        return collect($result);
    }

    public function count() {
        return $this->em->createQueryBuilder()
                        ->select('COUNT(t0.code)')
                        ->from(Department::class, 't0')
                        ->getQuery()
                        ->getSingleScalarResult();
    }

    public function delete($code, $defer = false) {
        $entity = $this->em->getReference(Department::class, $code);

        $this->em->transactional(function($em) use ($entity) {
            $em->remove($entity);
        });

        if ($defer == false) {
            $this->em->flush();
        }
    }

}