<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
	/**
	* @Route("/dashboard", name="homepage")
	*/
	public function indexAction(Request $request)
	{
		$em =$this->getDoctrine()->getManager(); 
		$user = $this->get('security.token_storage')
    ->getToken()->getUser(); 
		$misCajas = $em->getRepository('AppBundle:responsablesCaja')
		->findByUser($user->getId());
		$total = 0;
		$cajas = $em->getRepository('AppBundle:Caja')->findAll(); 
		$pendientes = $em->getRepository('AppBundle:Solicitud')
		->findByStatus('pendiente'); 
		if ($user->getRola() != 'SUPER ADMIN') {
			$cajas = $em->getRepository('AppBundle:responsablesCaja')
			->findBy(['user'=>$user]);
			$cajasResp = $em->getRepository('AppBundle:responsablesCaja')
			->findBy(['user'=>$user,'responsable'=>1]);
			foreach ($cajasResp as $caja) {
				$cajasResponsable[]= $caja->getCaja()->getId();
			}
			$pendientes = $em->getRepository('AppBundle:Solicitud')
			->findBy(['cajaSolicitada'=> $cajasResponsable , 'status'=>'pendiente']);  
		}
		$users = $em->getRepository('AppBundle:User')->findAll(); 
		foreach ($misCajas as $caja) {
			$total += $caja->getCaja()->getSaldo();
		}
		$transacciones = $em->getRepository('AppBundle:Transaccion')
		->lastTransaccion(); 
		
		return $this->render('AppBundle:Home:index.html.twig',[
			'misCajas' => $misCajas,
			'total' => $total,
			'transacciones' => $transacciones,
			'cajas'=>$cajas,
			'users'=>$users,
			'pendientes'=>$pendientes
		]);
	}

	/**
	* @Route("/", name="homepage_index")
	*/
	public function indexHomeAction(Request $request)
	{
		return $this->redirectToRoute('homepage',[
		]);
	}
}
