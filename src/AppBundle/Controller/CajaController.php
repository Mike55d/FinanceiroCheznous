<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Caja;
use AppBundle\Entity\Codigo;
use AppBundle\Entity\responsablesCaja;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
     * @Route("cajas")
     */
class CajaController extends Controller
{
    /**
     * @Route("/" , name="cajas_index")
     */
    public function indexAction(Request $request)
    {
        $em =$this->getDoctrine()->getManager(); 
        $cajas  = $em->getRepository('AppBundle:Caja')->findALl(); 
        return $this->render('AppBundle:Caja:index.html.twig', array(
         'cajas'=> $cajas,
     ));
    }

    /**
     * @Route("/new" , name="cajas_new")
     */
    public function newAction(Request $request)
    {
        $em =$this->getDoctrine()->getManager(); 
        $codigos = $em->getRepository('AppBundle:Codigo')->findAll(); 
        if ($request->get('nombre')) {
            $caja = new Caja;
            $caja->setNombre($request->get('nombre'));
            $caja->setDireccion($request->get('direccion'));
            $caja->setOrden($request->get('orden'));
            $caja->setCp($request->get('cp'));
            $caja->setCe($request->get('ce'));
            $caja->setSaldo($request->get('saldo'));
            $caja->setImage('default_box.png');
            $caja->setCodigo($request->get('codigo'));
            $caja->setDescripcion($request->get('descripcion'));
                //insertar imagen
            if ($request->get('image')) {
               $img = $request->get('image');
               $img = str_replace('data:image/jpeg;base64,', '', $img);
               $img = str_replace(' ', '+', $img);
               $data = base64_decode($img);
               $file = 'image.jpeg';
               $success = file_put_contents($file, $data);
               $file = new File($file);
               $fileName = md5(uniqid()).'.'.$file->guessExtension();
               $file->move($this->getParameter('images'),$fileName);
               $caja->setImage($fileName);
           }
            $em->persist($caja);
            $em->flush();
            return $this->redirectToRoute('cajas_index');
        }
        return $this->render('AppBundle:Caja:new.html.twig', array(
            'codigos' => $codigos
        ));
    }

    /**
     * @Route("/{id}/edit" , name="cajas_edit")
     */
    public function editAction(Request $request , Caja $caja)
    {
        $em =$this->getDoctrine()->getManager(); 
        $users = $em->getRepository('AppBundle:User')->findAll(); 
        $codigos = $em->getRepository('AppBundle:Codigo')->findAll(); 
        /*
        $responsables = $em->getRepository('AppBundle:responsablesCaja')
        ->findByCaja($caja);
        $responsablesCaja = [];
        foreach ($responsables as $responsable) {
            $responsablesCaja[] = $responsable->getUser()->getId();
        } 
        */
        if ($request->get('nombre')) {
            $caja->setNombre($request->get('nombre'));
            $caja->setDireccion($request->get('direccion'));
            $caja->setOrden($request->get('orden'));
            $caja->setCp($request->get('cp'));
            $caja->setCe($request->get('ce'));
            $codigo = $em->getRepository('AppBundle:Codigo')
            ->find($request->get('codigo')); 
            $caja->setCodigo($codigo);
            /*
            foreach ($responsablesCaja as $respCaja) {
                if (!in_array($respCaja, $request->get('responsables'))) {
                    $responsableCaja = $em->getRepository('AppBundle:responsablesCaja')
                    ->findOneByUser($respCaja); 
                    $em->remove($responsableCaja);
                    $em->flush();
                };
            }
            foreach ($request->get('responsables') as $respForm) {
                if (!in_array($respForm, $responsablesCaja)) {
                    $respCajaNew = new responsablesCaja;
                    $respCajaNew->setCaja($caja);
                    $user = $em->getRepository('AppBundle:User')->find($respForm); 
                    $respCajaNew->setUser($user);
                    $em->persist($respCajaNew);
                    $em->flush();

                };
            }
            */
                //insertar imagen
            if ($request->get('image')) {
               $img = $request->get('image');
               $img = str_replace('data:image/jpeg;base64,', '', $img);
               $img = str_replace(' ', '+', $img);
               $data = base64_decode($img);
               $file = 'image.jpeg';
               $success = file_put_contents($file, $data);
               $file = new File($file);
               $fileName = md5(uniqid()).'.'.$file->guessExtension();
               $file->move($this->getParameter('images'),$fileName);
               $caja->setImage($fileName);
           }
            $em->persist($caja);
            $em->flush();
            return $this->redirectToRoute('cajas_index');
        }
        return $this->render('AppBundle:Caja:edit.html.twig', array(
            'codigos' => $codigos,
            'caja' => $caja,
            /*
            'responsables'=> $responsablesCaja,
            'users'=> $users
            */
        ));
    }

    /**
     * @Route("/{id}/del" , name="cajas_del")
     */
    public function delAction(Caja $caja,Request $request)
    {
        $em =$this->getDoctrine()->getManager(); 
        $em->remove($caja);
        $em->flush();
        return $this->redirectToRoute('cajas_index');
    }


    /**
     * @Route("/{id}/addSaldo" , name="addSaldo")
     */
    public function addSaldoAction(Request $request , Caja $caja)
    {
        $em = $this->getDoctrine()->getManager();
        if ($request->get('saldo')) {
            $caja->setSaldo($caja->getSaldo() + $request->get('saldo'));
            $em->flush();
            return $this->redirectToRoute('cajas_index');
         } 
        return $this->render('AppBundle:Caja:ingresarDinero.html.twig', array(
            'caja' => $caja
        ));
    }

    /**
     * @Route("/{id}/show" , name="cajas_show")
     */
    public function showAction(Request $request , Caja $caja)
    {
        $em = $this->getDoctrine()->getManager();
        $responsables = $em->getRepository('AppBundle:responsablesCaja')
        ->findBy(['caja' => $caja->getid() , 'responsable' => 1 ]);
        $noResponsables = $em->getRepository('AppBundle:responsablesCaja')
        ->findBy(['caja' => $caja->getid() , 'responsable' => null]);
        $users = $em->getRepository('AppBundle:responsablesCaja')->findBy(['caja'=> $caja->getid()]);  
        return $this->render('AppBundle:Caja:show.html.twig', array(
            'caja' => $caja,
            'responsables' => $responsables,
            'noResponsables' => $noResponsables,
            'users' => $users
        ));
    }

    /**
     * @Route("/valid" , name="cajas_valid")
     */
    public function validCajaAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager(); 
        $caja = $em->getRepository('AppBundle:Caja')
        ->findByCodigo($request->get('codigo')); 
        if ($caja) {
            $res = false;
        }else{
            $res = true;
        }
        return new JsonResponse($res);
    }

}
