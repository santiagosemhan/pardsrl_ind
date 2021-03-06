<?php

namespace AppBundle\Repository;

/**
 * TipoRecursoRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TipoRecursoRepository extends \Doctrine\ORM\EntityRepository
{
	public function getQb()
	{
		return $this->createQueryBuilder('tr');
	}


	public function getBySlug($slug){

		$qb = $this->getQb()->where('tr.slug = :slug');

		$qb->setParameter('slug',$slug);

		$qb->setMaxResults(1);

		return $qb;

	}
}
