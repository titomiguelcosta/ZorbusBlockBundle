<?php

namespace Zorbus\BlockBundle\Entity;

use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityRepository;

class BlockRepository extends EntityRepository
{
    public function getEnabledUnassociatedBlocks(array $associatedBlocks)
    {
        return $this
            ->createQueryBuilder('b')
            ->where('b.id not in (:ids)')
            ->andWhere('b.enabled = 1')
            ->setParameter('ids', $associatedBlocks)
            ->getQuery()
            ->getResult();
    }

    public function getEnabledCategories()
    {
        $categories = $this
            ->createQueryBuilder('b')
            ->select('b.category')
            ->where('b.enabled = 1')
            ->groupBy('b.category')
            ->getQuery()
            ->getResult(AbstractQuery::HYDRATE_ARRAY);

        return $categories;
    }
}
