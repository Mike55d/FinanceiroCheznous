<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Transaccion;
use Symfony\Component\Serializer\SerializerInterface;


/**
* @Route("transferencias")
*/
class TransferenciasController extends Controller
{
  /**
  * @Route("/all" , name="transferencias")
  */
  public function indexAction(Request $request)
  {
    $em =$this->getDoctrine()->getManager();
    $user = $this->get('security.token_storage')
    ->getToken()->getUser();
    $cajasResponsable = $em->getRepository('AppBundle:responsablesCaja')
    ->findBy(['user'=>$user->getId(),'responsable'=> 1]);
    $cajas = [];
    foreach ($cajasResponsable as $caja) {
      if ($caja->getCaja()->getActiva()) {
        $cajas[]= $caja->getCaja()->getId();
      }
    }
    if ($user->getRola() != 'SUPER ADMIN') {
      $transferencias = $em->getRepository('AppBundle:Transaccion')->findUser($cajas); 
    }else{
      $transferencias = $em->getRepository('AppBundle:Transaccion')->findAll(['id'=>'DESC']); 
    }
    return $this->render('AppBundle:Transferencias:index.html.twig', array(
      'transferencias'=>$transferencias
    ));
  }

  /**
  * @Route("/transferir" , name="transferir")
  */
  public function transferirAction(Request $request ,SerializerInterface $serializer)
  {
    $em =$this->getDoctrine()->getManager();
    $user = $this->get('security.token_storage')
    ->getToken()->getUser(); 
    $cajas = $em->getRepository('AppBundle:Caja')->findBy(['activa'=>true]); 
    $codigos = $em->getRepository('AppBundle:Codigo')->findAll();
    $newCodigo = [];
    $newCaja = [];
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

    if ($request->get('monto')) {
      $transaccion = new Transaccion;
      $cajaEnvia = $em->getRepository('AppBundle:Caja')->find($request->get('envia')); 
      $cajaRecibe = $em->getRepository('AppBundle:Caja')->find($request->get('recibe')); 
      $transaccion->setCajaEnvia($cajaEnvia);
      $cajaEnvia->setSaldo($cajaEnvia->getSaldo() - $request->get('monto'));
      $transaccion->setCajaRecibe($cajaRecibe);
      $cajaRecibe->setSaldo($cajaRecibe->getSaldo() + $request->get('monto'));
      $transaccion->setUserEnvia($user);
      $transaccion->setMonto($request->get('monto'));
      $transaccion->setTipo('transferencia');
      $transaccion->setFecha(new \Datetime());
      $asociacion = $em->getRepository('AppBundle:Asociacion')->find($request->get('asociacion')); 
      $codigo = $em->getRepository('AppBundle:Codigo')->find($request->get('codigo')); 
      $fuente = $em->getRepository('AppBundle:Fuente')->find($request->get('fuente'));
      $transaccion->setAsociacion($asociacion);
      $transaccion->setCodigo($codigo);
      $transaccion->setFuente($fuente);
      
      if ($cajaEnvia->getSaldo() >= 0 || $cajaEnvia->getNegativo()) {
        $em->persist($transaccion);
        $em->flush();
      }
      $this->addFlash(
        'notice',
        'Transferencia hecha a '.$cajaRecibe->getNombre().' por '.$request->get('monto').' R$'
      );
      return $this->redirectToRoute('transferencias');
    }
    return $this->render('AppBundle:Transferencias:transferir.html.twig', array(
      'misCajas' => $misCajas,
      'cajas' =>$cajas,
      'newCajas'=>$newCajas,
      'newCodigos'=> $newCodigos,
    ));
  }

}
