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
            $transferencias = $em->getRepository('AppBundle:Transaccion')->findAll(); 
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
        if ($user->getRola() != 'SUPER ADMIN') {
            $misCajas = $em->getRepository('AppBundle:responsablesCaja')
            ->findBy(['user'=>$user->getId() , 'responsable'=> 1]);
            foreach ($misCajas as $miCaja) {
                $newCaja[] = ['id'=>$miCaja->getCaja()->getId(),
                'image'=>$miCaja->getCaja()->getImage(),
                'text'=>$miCaja->getCaja()->getNombre(),
                'saldo'=>$miCaja->getCaja()->getSaldo()]; 
            }
        }else{
            $misCajas = $em->getRepository('AppBundle:Caja')->findAll(); 
            foreach ($misCajas as $miCaja) {
                $newCaja[] = ['id'=>$miCaja->getId(),
                'image'=>$miCaja->getImage(),
                'text'=>$miCaja->getNombre(),
                'saldo'=>$miCaja->getSaldo()]; 
            }
        }
        
        $newCajas = $serializer->serialize(
            $newCaja,
            'json');
        $newCaja2 = [];
        foreach ($cajas as $miCaja2) {
            $newCaja2[] = ['id'=>$miCaja2->getId(),
            'image'=>$miCaja2->getImage(),
            'text'=>$miCaja2->getNombre(),
            'saldo'=>$miCaja2->getSaldo()]; 
        }
        $newCajas2 = $serializer->serialize(
            $newCaja2,
            'json');
        if ($request->get('monto')) {
            $transaccion = new Transaccion;
            $cajaEnvia = $em->getRepository('AppBundle:Caja')
            ->find($request->get('envia')); 
            $transaccion->setCajaEnvia($cajaEnvia);
            $cajaEnvia->setSaldo($cajaEnvia->getSaldo() - 
                $request->get('monto'));
            $cajaRecibe = $em->getRepository('AppBundle:Caja')
            ->find($request->get('recibe')); 
            $transaccion->setCajaRecibe($cajaRecibe);
            $cajaRecibe->setSaldo($cajaRecibe->getSaldo() + 
                $request->get('monto'));
            $transaccion->setUserEnvia($user);
            $transaccion->setMonto($request->get('monto'));
            $transaccion->setTipo('transferencia');
            $transaccion->setFecha(new \Datetime());
            if ($cajaEnvia->getSaldo() >= 0) {
                $em->persist($transaccion);
                $em->flush();
            }
            return $this->redirectToRoute('transferencias');
        }
        return $this->render('AppBundle:Transferencias:transferir.html.twig', array(
            'misCajas' => $misCajas,
            'cajas' =>$cajas,
            'newCajas'=>$newCajas,
            'newCajas2'=>$newCajas2
        ));
    }

    /**
     * @Route("/solicitar" , name="solicitar")
     */
    public function solicitarAction(Request $request)
    {
        return $this->render('AppBundle:Transferencias:solicitar.html.twig', array(
            // ...
        ));
    }


}
