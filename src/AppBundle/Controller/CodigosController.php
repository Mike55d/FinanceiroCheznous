<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Codigo;
use AppBundle\Entity\FuentesCodigos;


/**
* @Route("codigos")
*/
class CodigosController extends Controller
{
  /**
   * @Route("/" , name="codigos_index")
   */
  public function indexAction(Request $request)
  {
    $em =$this->getDoctrine()->getManager(); 
    $codigos = $em->getRepository('AppBundle:Codigo')->findAll(); 
    return $this->render('AppBundle:Codigos:index.html.twig', array(
      'codigos' => $codigos
    ));
  }

  /**
   * @Route("/new" , name="codigos_new")
   */
  public function newAction(Request $request)
  {
    $em =$this->getDoctrine()->getManager(); 
    if ($request->get('descripcion')) {
      $codigo = new Codigo;
      $codigo->setNro($request->get('nro'));
      $codigo->setDescripcion($request->get('descripcion'));
      $codigo->setIniciales($request->get('iniciales'));
      $em->persist($codigo);
      $em->flush();
      $this->addFlash(
        'notice',
        'Codigo creado correctamente'
      );
      return $this->redirectToRoute('codigos_index');
    }
    return $this->render('AppBundle:Codigos:new.html.twig', array(
            // ...
    ));
  }

  /**
   * @Route("/{id}/edit" , name="codigos_edit")
   */
  public function editAction( Codigo $codigo, Request $request)
  {   
    $em =$this->getDoctrine()->getManager(); 
    if ($request->get('descripcion')) {
      $codigo->setNro($request->get('nro'));
      $codigo->setDescripcion($request->get('descripcion'));
      $codigo->setIniciales($request->get('iniciales'));
      $em->flush();
      $this->addFlash(
        'notice',
        'Codigo editado correctamente'
      );
      return $this->redirectToRoute('codigos_index');
    }
    return $this->render('AppBundle:Codigos:edit.html.twig', array(
      'codigo'=>$codigo
    ));
  }

  /**
   * @Route("/{id}/del" , name="codigos_del")
   */
  public function delAction(Codigo $codigo, Request $request)
  {
    $em =$this->getDoctrine()->getManager(); 
    $em->remove($codigo);
    $this->addFlash(
      'notice',
      'Codigo eliminado correctamente'
    );
    $em->flush();
    return $this->redirectToRoute('codigos_index');
  }

  /**
  * @Route("/{id}/add_fuentes" , name="add_fuentes")
  */
  public function showCodigosAction(Codigo $codigo, Request $request)
  {
    $em =$this->getDoctrine()->getManager();
    $fuentes = $em->getRepository('AppBundle:Fuente')->findAll(); 
    $codigosFuente = $em->getRepository('AppBundle:FuentesCodigos')->findByCodigo($codigo);
    if ($request->get('fuente')) {
      $fuente = $em->getRepository('AppBundle:Fuente')->find($request->get('fuente'));
      $exist = $em->getRepository('AppBundle:FuentesCodigos')->findBy(['fuente'=>$fuente , 'codigo'=> $codigo]); 
      if (!$exist) {
        $fuentesCodigos = new FuentesCodigos;
        $fuentesCodigos->setCodigo($codigo);
        $fuentesCodigos->setFuente($fuente);
        $em->persist($fuentesCodigos);
        $em->flush();
        $codigosFuente = $em->getRepository('AppBundle:FuentesCodigos')->findByCodigo($codigo);
      }
    }
    return $this->render('AppBundle:Codigos:addFuentes.html.twig',[
      'codigosFuente'=> $codigosFuente,
      'fuentes'=> $fuentes,
      'codigo'=> $codigo
    ]);
  }


  /**
  * @Route("/{id}/del_fuentes" , name="del_fuentes")
  */
  public function delFuentesAction(FuentesCodigos $fuentes, Request $request)
  {
    $em =$this->getDoctrine()->getManager();
    $em->remove($fuentes);
    $em->flush();
    $this->addFlash(
      'notice',
      'Fuente desasociada correctamente'
    );
    return $this->redirectToRoute('add_fuentes',['id'=>$fuentes->getCodigo()->getId()]);
  }

}
