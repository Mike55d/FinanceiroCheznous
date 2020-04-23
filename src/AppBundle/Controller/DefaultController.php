<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Caja;

class DefaultController extends Controller
{
	/**
	* @Route("/dashboard", name="homepage")
	*/
	public function indexAction(Request $request)
	{
		$em =$this->getDoctrine()->getManager(); 
		$google = new \Google_Client();
		$google->setApplicationname('google sheets and php');
		$google->setScopes([\Google_Service_Sheets::SPREADSHEETS]);
		$google->setAccessType('offline');
		$google->setAuthConfig(__DIR__.'/../../../web/google/credentials.json');
		$service = new \Google_Service_Sheets($google);
		$spreadSheetId = "1l274qdHVj3_beytyT-s-ZbE8pSuwao6qnUq7DgIhnIY";
		$range = "Hoja 1!A2:I";
		$response = $service->spreadsheets_values->get($spreadSheetId,$range);
		$values = $response->getValues();
		/*
		foreach ($values as $val) {
			$caja  = new Caja;
			$caja->setCodigo($val[0]);
			$caja->setNombre($val[1]);
			$caja->setLocalidad($val[2]);
			$caja->setCalle($val[3]);
			$caja->setNCalle($val[4]);
			$caja->setBarrio($val[5]);
			$caja->setCiudad($val[6]);
			$caja->setUf($val[7]);
			$caja->setCe($val[8]);
			$caja->setDireccion('ninguna');
			$caja->setOrden('I');
			$caja->setCp(null);
			$caja->setSaldo(0);
			$caja->setImage('default_box.png');
			$caja->setDescripcion('ninguna');
			$em->persist($caja);
			$em->flush();
		}
		*/
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
			$cajasResponsable = [];
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
			'pendientes'=>$pendientes,
			'values'=> $values,
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
