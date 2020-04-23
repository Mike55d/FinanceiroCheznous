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
     * @Route("/todas" , name="solicitudes_index")
     */
    public function indexAction()
    {
        $em =$this->getDoctrine()->getManager();   
        $user = $this->get('security.token_storage')
        ->getToken()->getUser();
        $misCajas = $em->getRepository('AppBundle:responsablesCaja')
        ->findBy(['user'=>$user->getid(),'responsable'=>1]); 
        $cajas = [];
        foreach ($misCajas as $caja) {
            $cajas[]= $caja->getCaja()->getId();
        }
        if ($user->getRola() != 'SUPER ADMIN') {
            $solicitudes = $em->getRepository('AppBundle:Solicitud')
            ->findUser($cajas); 
        }else{
            $solicitudes = $em->getRepository('AppBundle:Solicitud')->findAll();
        }
        return $this->render('AppBundle:Solicitudes:index.html.twig', array(
            'solicitudes' => $solicitudes,
            'misCajas'=>$cajas
        ));
    }

    /**
     * @Route("/pendientes" , name="solicitudes_pendientes")
     */
    public function pendientesAction()
    {
        $em =$this->getDoctrine()->getManager(); 
        $user = $this->get('security.token_storage')
        ->getToken()->getUser();
        $misCajas = $em->getRepository('AppBundle:responsablesCaja')
        ->findBy(['user'=>$user->getid(),'responsable'=>1]); 
        $cajas = [];
        foreach ($misCajas as $caja) {
            $cajas[]= $caja->getCaja()->getId();
        }
        if ($user->getRola() != 'SUPER ADMIN') {
            $solicitudes = $em->getRepository('AppBundle:Solicitud')
            ->findUserStatus($cajas,'pendiente'); 
        }else{
            $solicitudes = $em->getRepository('AppBundle:Solicitud')
            ->findByStatus('pendiente');
        }
        return $this->render('AppBundle:Solicitudes:index.html.twig', array(
            'solicitudes' => $solicitudes,
            'misCajas'=>$cajas
        ));
    }

    /**
     * @Route("/rechazadas" , name="solicitudes_rechazadas")
     */
    public function rechazadasAction()
    {
        $em =$this->getDoctrine()->getManager(); 
        $user = $this->get('security.token_storage')
        ->getToken()->getUser();
        $misCajas = $em->getRepository('AppBundle:responsablesCaja')
        ->findBy(['user'=>$user->getid(),'responsable'=>1]); 
        $cajas = [];
        foreach ($misCajas as $caja) {
            $cajas[]= $caja->getCaja()->getId();
        }
        if ($user->getRola() != 'SUPER ADMIN') {
            $solicitudes = $em->getRepository('AppBundle:Solicitud')
            ->findUserStatus($cajas,'rechazada'); 
        }else{
            $solicitudes = $em->getRepository('AppBundle:Solicitud')
            ->findByStatus('rechazada');
        }
        return $this->render('AppBundle:Solicitudes:index.html.twig', array(
            'solicitudes' => $solicitudes,
            'misCajas'=>$cajas
        ));
    }

    /**
     * @Route("/aprobadas" , name="solicitudes_aprobadas")
     */
    public function aprobadasAction()
    {
        $em =$this->getDoctrine()->getManager(); 
        $user = $this->get('security.token_storage')
        ->getToken()->getUser();
        $misCajas = $em->getRepository('AppBundle:responsablesCaja')
        ->findBy(['user'=>$user->getid(),'responsable'=>1]); 
        $cajas = [];
        foreach ($misCajas as $caja) {
            $cajas[]= $caja->getCaja()->getId();
        }
        if ($user->getRola() != 'SUPER ADMIN') {
            $solicitudes = $em->getRepository('AppBundle:Solicitud')
            ->findUserStatus($cajas,'aprobada');  
        }else{
            $solicitudes = $em->getRepository('AppBundle:Solicitud')
            ->findByStatus('aprobada');
        }
        return $this->render('AppBundle:Solicitudes:index.html.twig', array(
            'solicitudes' => $solicitudes,
            'misCajas' => $cajas
        ));
    }

    /**
     * @Route("/new" , name="solicitudes_new")
     */
    public function newAction(Request $request ,SerializerInterface $serializer)
    {
        $em =$this->getDoctrine()->getManager(); 
        $cajas = $em->getRepository('AppBundle:Caja')->findAll(); 
        $user = $this->get('security.token_storage')
        ->getToken()->getUser(); 
        $misCajas = $em->getRepository('AppBundle:responsablesCaja')
        ->findBy(['user'=>$user->getId(), 'responsable' => 1]); 
        $newCaja = [];
        foreach ($misCajas as $miCaja) {
            $newCaja[] = ['id'=>$miCaja->getCaja()->getId(),
            'image'=>$miCaja->getCaja()->getImage(),
            'text'=>$miCaja->getCaja()->getNombre(),
            'saldo'=>$miCaja->getCaja()->getSaldo()]; 
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
            $em->persist($solicitud);
            $em->flush();
            $this->addFlash(
                'notice',
                'Solicitud enviada a '.$cajaSolicitada->getNombre().' por '.$request->get('monto').' R$'
            );
            return $this->redirectToRoute('solicitudes_index');
        }
        return $this->render('AppBundle:Solicitudes:new.html.twig', array(
            'cajas'=>$cajas,
            'misCajas'=>$misCajas,
            'newCajas'=>$newCajas,
            'newCajas2'=>$newCajas2
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
        $em->persist($transaccion);
        $em->flush();
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
