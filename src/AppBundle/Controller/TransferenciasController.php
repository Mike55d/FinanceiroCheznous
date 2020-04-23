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
    $misCajas = $em->getRepository('AppBundle:responsablesCaja')
    ->findBy(['user'=>$user->getid(),'responsable'=>1]); 
    foreach ($misCajas as $caja) {
      $cajas[]= $caja->getCaja()->getId();
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
    $cajas = $em->getRepository('AppBundle:Caja')->findAll(); 
    $newCaja = [];
    $newCaja2 = [];
    if ($user->getRola() != 'SUPER ADMIN') {
      $misCajas = $em->getRepository('AppBundle:responsablesCaja')
      ->findBy(['user'=>$user->getId(),'responsable'=> 1]);
    }else{
      $misCajas = $em->getRepository('AppBundle:Caja')->findAll(); 
    }


    // recorrer mis cajas
    foreach ($misCajas as $miCaja) {
      $newCaja[] = [
        'id'=>$miCaja->getId(),
        'image'=>$miCaja->getImage(),
        'text'=>$miCaja->getNombre(),
        'saldo'=>$miCaja->getSaldo()
      ]; 
    }
    // recorrer todas las cajas
    foreach ($cajas as $miCaja2) {
      $newCaja2[] = [
        'id'=>$miCaja2->getId(),
        'image'=>$miCaja2->getImage(),
        'text'=>$miCaja2->getNombre(),
        'saldo'=>$miCaja2->getSaldo()
      ]; 
    }


    $blue = [];
    // recorrer mis cajas
    foreach ($misCajas as $miCaja) {
        $asociacionesMiCaja = $em->getRepository('AppBundle:AsociacionesCaja')->findByCaja($miCaja); 
        $cajasTransferir =[];
      foreach ($cajas as $caja) {
        $asociacionesCaja = $em->getRepository('AppBundle:AsociacionesCaja')->findByCaja($caja);
        $comunAsociaciones = array_intersect($asociacionesMiCaja, $asociacionesCaja);
        if ($comunAsociaciones && $miCaja != $caja) {
          $cajasTransferir[] = ['caja'=> $caja , 'asociaciones' => $comunAsociaciones];
        }
      }
      $blue[] = [
        'id'=>$miCaja->getId(),
        'image'=>$miCaja->getImage(),
        'text'=>$miCaja->getNombre(),
        'saldo'=>$miCaja->getSaldo(),
        'cajasTransferir'=> $cajasTransferir
      ]; 
    }



    $newCajas2 = $serializer->serialize($newCaja2,'json');
    $newCajas = $serializer->serialize($newCaja,'json');

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
      if ($cajaEnvia->getSaldo() >= 0) {
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
      'newCajas2'=>$newCajas2,
      'blue'=> $blue,
    ));
  }

}
