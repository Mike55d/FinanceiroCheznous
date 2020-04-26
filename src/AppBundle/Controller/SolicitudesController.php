<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Solicitud;
use Symfony\Component\Serializer\SerializerInterface;
use AppBundle\Entity\Transaccion;


  /**
   * @Route("solicitudes")
   */

  class SolicitudesController extends Controller
  {

  /**
  * @Route("/{status}" , name="solicitudes_index")
  */
  public function indexAction($status = null)
  {
    $cajas = [];
    $parameters = $status ? ['status'=> $status] : [];
    $em =$this->getDoctrine()->getManager();   
    $user = $this->get('security.token_storage')->getToken()->getUser();
    $misCajas = $em->getRepository('AppBundle:responsablesCaja')
    ->findBy(['user'=>$user->getid(),'responsable'=>1]); 
    foreach ($misCajas as $caja) $cajas[]= $caja->getCaja()->getId();
    if ($user->getRola() != 'SUPER ADMIN') {
      $parameters = array_merge($parameters, ['userSolicita' => $user]);
    }
    $solicitudes = $em->getRepository('AppBundle:Solicitud')->findBy($parameters);
    return $this->render('AppBundle:Solicitudes:index.html.twig', array(
      'solicitudes' => $solicitudes,
      'misCajas'=>$cajas
    ));
  }

  /**
  * @Route("/new" , name="solicitudes_new")
  */
  public function newAction(Request $request ,SerializerInterface $serializer)
  {
    $newCodigo = [];
    $newCaja = [];
    $em =$this->getDoctrine()->getManager();
    $user = $this->get('security.token_storage')
    ->getToken()->getUser(); 
    $cajas = $em->getRepository('AppBundle:Caja')->findBy(['activa'=>true]); 
    $codigos = $em->getRepository('AppBundle:Codigo')->findAll();
    if ($user->getRola() != 'SUPER ADMIN') {
      $misCajas = [];
      $cajasResponsable = $em->getRepository('AppBundle:responsablesCaja')
      ->findBy(['user'=>$user->getId(),'responsable'=> 1]);
      foreach ($cajasResponsable as $caja) {
        if ($caja->getCaja()->getActiva()) {
          $misCajas[]= $caja->getCaja();
        }
      }
    }else{
      $misCajas = $em->getRepository('AppBundle:Caja')->findBy(['activa'=>true]); 
    }
    // recorrer mis cajas
    foreach ($misCajas as $miCaja) {
      $asociacionesMiCaja = $em->getRepository('AppBundle:AsociacionesCaja')->findByCaja($miCaja); 
      $cajasTransferir =[];
      foreach ($cajas as $caja) {
        $asociacionesCaja = $em->getRepository('AppBundle:AsociacionesCaja')->findByCaja($caja);
        $comunAsociaciones = array_values(array_intersect($asociacionesMiCaja, $asociacionesCaja));
        if ($comunAsociaciones && $miCaja != $caja) {
          $cajasTransferir[] = [
            'id'=>$caja->getId(),
            'image'=>$caja->getImage(),
            'text'=>$caja->getNombre(),
            'saldo'=>$caja->getSaldo()
            , 'asociaciones' => $comunAsociaciones
          ];
        }
      }
      $newCaja[] = [
        'id'=>$miCaja->getId(),
        'image'=>$miCaja->getImage(),
        'text'=>$miCaja->getNombre(),
        'saldo'=>$miCaja->getSaldo(),
        'cajasTransferir'=> $cajasTransferir
      ]; 
    }

    foreach ($codigos as $codigo) {
      $fuentesArr = [];
      $fuentes = $em->getRepository('AppBundle:FuentesCodigos')->findByCodigo($codigo);
      foreach ($fuentes as $fuente) {
        $fuentesArr[]=[
          'id'=>$fuente->getFuente()->getId(),
          'text'=>$fuente->getFuente()->getNombre(),
        ];
      }
      $newCodigo[]=[
        'id'=>$codigo->getId(),
        'text'=>$codigo->getNro().'  '.$codigo->getIniciales(),
        'fuentes'=> $fuentesArr
      ];
    }

    $newCajas = $serializer->serialize($newCaja,'json');
    $newCodigos = $serializer->serialize($newCodigo,'json');

    if ($request->get('monto') > 0) {
      $solicitud = new Solicitud;
      $cajaSolicita = $em->getRepository('AppBundle:Caja')
      ->find($request->get('recibe'));
      $solicitud->setCajaSolicita($cajaSolicita);
      $cajaSolicitada = $em->getRepository('AppBundle:Caja')
      ->find($request->get('envia'));
      $solicitud->setCajaSolicitada($cajaSolicitada);
      $solicitud->setUserSolicita($user);
      $solicitud->setMonto($request->get('monto'));
      $solicitud->setFecha( new \Datetime());
      $solicitud->setStatus('pendiente');
      $asociacion = $em->getRepository('AppBundle:Asociacion')->find($request->get('asociacion')); 
      $codigo = $em->getRepository('AppBundle:Codigo')->find($request->get('codigo')); 
      $fuente = $em->getRepository('AppBundle:Fuente')->find($request->get('fuente'));
      $solicitud->setAsociacion($asociacion);
      $solicitud->setCodigo($codigo);
      $solicitud->setFuente($fuente);
      $em->persist($solicitud);
      $em->flush();
      $this->addFlash(
        'notice',
        'Solicitud enviada a '.$cajaSolicitada->getNombre().' por '.$request->get('monto').' R$'
      );
      return $this->redirectToRoute('solicitudes_index');
    }


    return $this->render('AppBundle:Solicitudes:new.html.twig', array(
      'misCajas'=>$misCajas,
      'cajas'=>$cajas,
      'newCajas'=>$newCajas,
      'newCodigos'=> $newCodigos,
      
    ));
  }

  /**
     * @Route("/edit" , name="solicitudes_edit")
     */
  public function editAction()
  {
    return $this->render('AppBundle:Solicitudes:edit.html.twig', array(
            // ...
    ));
  }

  /**
     * @Route("/{id}/aprobar" , name="solicitudes_aprobar")
     */
  public function aprovarAction(Solicitud $solicitud ,Request $request)
  {
    $em =$this->getDoctrine()->getManager(); 
    $user = $this->get('security.token_storage')
    ->getToken()->getUser(); 
    $solicitud->setStatus('aprobada');
    $solicitud->setUserAprueba($user);
    $transaccion = new Transaccion;
    $transaccion->setCajaRecibe($solicitud->getCajaSolicita());
    $transaccion->setCajaEnvia($solicitud->getCajaSolicitada());
    $transaccion->setUserAprueba($user);
    $transaccion->setUserSolicita($solicitud->getUserSolicita());
    $transaccion->setFecha(new \Datetime());
    $transaccion->setMonto($solicitud->getMonto());
    $transaccion->setTipo('solicitud');
    $cajaA = $solicitud->getCajaSolicitada();
    $cajaB = $solicitud->getCajaSolicita();
    $cajaA->setSaldo($cajaA->getSaldo() - $solicitud->getMonto());
    $cajaB->setSaldo($cajaB->getSaldo() + $solicitud->getMonto());
    if ($cajaA->getSaldo() >= 0 || $cajaEnvia->getNegativo()) {
      $em->persist($transaccion);
      $em->flush();
    }
    $this->addFlash(
      'notice',
      'Solicitud aprobada'
    );
    return $this->redirectToRoute('solicitudes_pendientes');
  }

  /**
     * @Route("/{id}/rechazar" , name="solicitudes_rechazar")
     */
  public function rechazarAction(Solicitud $solicitud ,Request $request)
  {
    $em =$this->getDoctrine()->getManager(); 
    $solicitud->setStatus('rechazada');
    $em->flush();
    $this->addFlash(
      'notice',
      'Solicitud rechazada'
    );
    return $this->redirectToRoute('solicitudes_pendientes');
  }

  /**
     * @Route("/del" , name="solicitudes_del")
     */
  public function delAction()
  {
    return $this->render('AppBundle:Solicitudes:del.html.twig', array(
            // ...
    ));
  }

}
