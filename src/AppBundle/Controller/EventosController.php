<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
     * @Route("eventos")
     */
class EventosController extends Controller
{
    /**
     * @Route("/" , name="eventos_index")
     */
    public function indexAction()
    {
        $em =$this->getDoctrine()->getManager(); 
        $eventos = $em->getRepository('AppBundle:Evento')->findAll(); 
        return $this->render('AppBundle:Eventos:index.html.twig', array(
            'eventos' => $eventos
        ));
    }

    /**
     * @Route("/new" , name="eventos_new")
     */
    public function newAction()
    {
        return $this->render('AppBundle:Eventos:new.html.twig', array(
            // ...
        ));
    }

    /**
     * @Route("/edit" , name="eventos_edit")
     */
    public function editAction()
    {
        return $this->render('AppBundle:Eventos:edit.html.twig', array(
            // ...
        ));
    }

    /**
     * @Route("/del" , name="eventos_del")
     */
    public function delAction()
    {
        return $this->render('AppBundle:Eventos:del.html.twig', array(
            // ...
        ));
    }

}
