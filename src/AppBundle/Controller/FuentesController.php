<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Fuente;
use AppBundle\Entity\FuentesCodigos;

/**
	* @Route("fuentes")
	*/
	class FuentesController extends Controller
	{
	/**
	* @Route("/" , name="fuentes_index")
	*/
	public function indexAction(Request $request)
	{
		$em =$this->getDoctrine()->getManager();
		$fuentes = $em->getRepository('AppBundle:Fuente')->findAll();
		return $this->render('AppBundle:Fuentes:index.html.twig', array(
			'fuentes' => $fuentes
		));
	}

  /**
	* @Route("/new" , name="fuentes_new")
	*/
	public function newAction(Request $request)
	{
		$em =$this->getDoctrine()->getManager();
		if ($request->get('nombre')) {
			$fuente = new Fuente;
			$fuente->setNombre($request->get('nombre'));
			$em->persist($fuente);
			$em->flush();
			$this->addFlash(
  			'notice',
  			'Fuente creada correctamente'
  		);
			return $this->redirectToRoute('fuentes_index');
		}
		return $this->render('AppBundle:Fuentes:new.html.twig', array(
            // ...
		));
	}

  /**
	* @Route("/{id}/edit" , name="fuentes_edit")
	*/
	public function editAction(Fuente $fuente, Request $request)
	{   
		$em =$this->getDoctrine()->getManager();
		if ($request->get('nombre')) {
			$fuente->setNombre($request->get('nombre'));
			$em->flush();
			$this->addFlash(
  			'notice',
  			'Fuente editada correctamente'
  		);
			return $this->redirectToRoute('fuentes_index');
		}
		return $this->render('AppBundle:Fuentes:edit.html.twig', array(
			'fuente'=>$fuente
		));
	}

  /**
	* @Route("/{id}/del" , name="fuentes_del")
	*/
	public function delAction(Fuente $fuente, Request $request)
	{
		$em =$this->getDoctrine()->getManager();
		$em->remove($fuente);
		$this->addFlash(
  			'notice',
  			'Fuente eliminada correctamente'
  		);
		$em->flush();
		return $this->redirectToRoute('fuentes_index');
	}


	/**
	* @Route("/{id}/add_codigos" , name="add_codigos")
	*/
	public function showCodigosAction(Fuente $fuente, Request $request)
	{
		$em =$this->getDoctrine()->getManager();
		$codigos = $em->getRepository('AppBundle:Codigo')->findAll(); 
		$codigosFuente = $em->getRepository('AppBundle:FuentesCodigos')->findByFuente($fuente); 
		if ($request->get('codigo')) {
			$codigo = $em->getRepository('AppBundle:Codigo')->find($request->get('codigo'));
			$exist = $em->getRepository('AppBundle:FuentesCodigos')->findBy(['fuente'=>$fuente , 'codigo'=> $codigo]); 
			if (!$exist) {
				$fuentesCodigos = new FuentesCodigos;
				$fuentesCodigos->setCodigo($codigo);
				$fuentesCodigos->setFuente($fuente);
				$em->persist($fuentesCodigos);
				$em->flush();
				$codigosFuente = $em->getRepository('AppBundle:FuentesCodigos')->findByFuente($fuente); 
			}
		}
		return $this->render('AppBundle:Fuentes:addCodigos.html.twig',[
			'codigosFuente'=> $codigosFuente,
			'codigos'=> $codigos,
			'fuente'=> $fuente
		]);
	}


	/**
	* @Route("/{id}/del_codigos" , name="del_codigos")
	*/
	public function delCodigosAction(FuentesCodigos $codigos, Request $request)
	{
		$em =$this->getDoctrine()->getManager();
		$em->remove($codigos);
		$em->flush();
		$this->addFlash(
  			'notice',
  			'Codigo desasociado correctamente'
  		);
		return $this->redirectToRoute('add_codigos',['id'=>$codigos->getFuente()->getId()]);
	}

}
