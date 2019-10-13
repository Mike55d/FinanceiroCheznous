<?php

namespace AppBundle\Repository;

/**
 * SolicitudRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class SolicitudRepository extends \Doctrine\ORM\EntityRepository
{
	public function findUser($cajas){
		$em = $this->getEntityManager(); 
		$qb = $em->createQueryBuilder();
		$qb->select('c')
		->from('AppBundle:Solicitud', 'c')
		->where('c.cajaSolicita IN (:cajas) OR c.cajaSolicitada IN (:cajas)')
		->setParameter('cajas', $cajas);
		$query = $qb->getQuery();
		return $query->getResult();
	}
	public function findUserStatus($cajas,$status){
		$em = $this->getEntityManager(); 
		$qb = $em->createQueryBuilder();
		$qb->select('c')
		->from('AppBundle:Solicitud', 'c')
		->where('(c.cajaSolicita IN (:cajas) OR c.cajaSolicitada IN (:cajas)) 
			AND c.status = :status')
		->setParameter('cajas', $cajas)
		->setParameter('status', $status);
		$query = $qb->getQuery();
		return $query->getResult();
	}
}