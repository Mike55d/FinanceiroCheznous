<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Codigo;

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
            $codigo->setDescripcion($request->get('descripcion'));
            $em->persist($codigo);
            $em->flush();
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
            $codigo->setDescripcion($request->get('descripcion'));
            $em->flush();
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
        $em->flush();
        return $this->redirectToRoute('codigos_index');
    }

}
