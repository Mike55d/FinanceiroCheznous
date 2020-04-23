<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Asociacion;

/**
* @Route("asociaciones")
*/
class AsociaciacionesController extends Controller
{
  /**
   * @Route("/" , name="asociaciones_index")
   */
  public function indexAction()
  {
  	$em =$this->getDoctrine()->getManager(); 
  	$asociaciones = $em->getRepository('AppBundle:Asociacion')->findAll(); 
  	return $this->render('AppBundle:Asociaciaciones:index.html.twig', array(
  		'asociaciones' =>$asociaciones
  	));
  }


  /**
   * @Route("/new" , name="asociaciones_new")
   */
  public function newAction(Request $request)
  {
  	$em =$this->getDoctrine()->getManager(); 
  	if ($request->get('nombre')) {
  		$asociacion = new Asociacion;
  		$asociacion->setNombre($request->get('nombre'));
  		$asociacion->setCodigo($request->get('codigo'));
  		$em->persist($asociacion);
  		$em->flush();
  		$this->addFlash(
  			'notice',
  			'Asociacion creada correctamente'
  		);
  		return $this->redirectToRoute('asociaciones_index');
  	}
  	return $this->render('AppBundle:Asociaciaciones:new.html.twig', array(
  	));
  }

  /**
   * @Route("{id}/edit" , name="asociaciones_edit")
   */
  public function editAction(Asociacion $asociacion ,Request $request)
  {
  	$em =$this->getDoctrine()->getManager(); 
  	if ($request->get('nombre')) {
  		$asociacion->setNombre($request->get('nombre'));
  		$asociacion->setCodigo($request->get('codigo'));
  		$em->flush();
  		$this->addFlash(
  			'notice',
  			'Asociacion editada correctamente'
  		);
  		return $this->redirectToRoute('asociaciones_index');
  	} 
  	return $this->render('AppBundle:Asociaciaciones:edit.html.twig', array(
  		'asociacion' =>$asociacion
  	));
  }

   /**
   * @Route("{id}/del" , name="asociaciones_del")
   */
   public function delAction(Asociacion $asociacion)
   {
   	$em = $this->getDoctrine()->getManager();
   	$em->remove($user);
   	$em->flush();
   	$this->addFlash(
   		'notice',
   		'Asociacion eliminada correctamente'
   	);
   	return $this->redirectToRoute('asociaciones_index');
   }



 }
