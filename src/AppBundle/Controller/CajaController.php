<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Caja;
use AppBundle\Entity\AsociacionesCaja;
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
    $cajas  = $em->getRepository('AppBundle:Caja')->findBy([],['id'=>'DESC']); 
    return $this->render('AppBundle:Caja:index.html.twig', array(
     'cajas'=> $cajas,
   ));
  }


  /**
   * @Route("/misCajas" , name="misCajas")
   */
  public function misCajasAction(Request $request)
  {
    $em =$this->getDoctrine()->getManager(); 
    $user = $this->get('security.token_storage')
    ->getToken()->getUser(); 
    $cajasResponsable  = $em->getRepository('AppBundle:responsablesCaja')->findBy(['user'=>$user]);
    // buscar cajas activas e inactivas
    // $misCajas = [];
    // foreach ($cajasResponsable as $responsable) {
    //   if ($responsable->getCaja()->getActiva()) {
    //     $misCajas[] = $responsable;
    //   }
    // }
    return $this->render('AppBundle:Caja:indexUser.html.twig', array(
     'cajas'=> $cajasResponsable,
   ));
  }

  /**
   * @Route("/new" , name="cajas_new")
   */
  public function newAction(Request $request)
  {
    $em =$this->getDoctrine()->getManager(); 
    $codigos = $em->getRepository('AppBundle:Codigo')->findAll(); 
    $asociaciones = $em->getRepository('AppBundle:Asociacion')->findAll();
    if ($request->get('nombre')) {
      $caja = new Caja;
      $caja->setNombre($request->get('nombre'));
      $caja->setDireccion($request->get('direccion'));
      $caja->setOrden($request->get('orden'));
      $caja->setCp($request->get('cp'));
      $caja->setCe($request->get('ce'));
      $caja->setSaldo($request->get('saldo'));
      $caja->setTipo($request->get('tipo'));
      $caja->setImage('default_box.png');
      $caja->setActiva(1);
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
      foreach ($request->get('asociaciones') as $asociacionId) {
        $asociacionesCaja = new AsociacionesCaja;
        $asociacion = $em->getRepository('AppBundle:Asociacion')->find($asociacionId); 
        $asociacionesCaja->setCaja($caja);
        $asociacionesCaja->setAsociacion($asociacion);
        $em->persist($asociacionesCaja);
        $em->flush();
      }
      $this->addFlash(
        'notice',
        'Caja '.$caja->getNombre().' creada satisfactoriamente'
      );
      return $this->redirectToRoute('cajas_index');
    }
    return $this->render('AppBundle:Caja:new.html.twig', array(
      'codigos' => $codigos,
      'asociaciones' => $asociaciones,
    ));
  }

  /**
   * @Route("/{id}/edit" , name="cajas_edit")
   */
  public function editAction(Request $request , Caja $caja)
  {
    $em =$this->getDoctrine()->getManager(); 
    $users = $em->getRepository('AppBundle:User')->findAll(); 
    $asociaciones = $em->getRepository('AppBundle:Asociacion')->findAll();
    $asociacionesCaja = $em->getRepository('AppBundle:AsociacionesCaja')->findByCaja($caja); 
    $formatAsociacionesCaja = [];
    foreach ($asociacionesCaja as $asociacionCaja) {
      $formatAsociacionesCaja[] = $asociacionCaja->getAsociacion();
    }
    if ($request->get('nombre')) {
      $caja->setNombre($request->get('nombre'));
      $caja->setDireccion($request->get('direccion'));
      $caja->setOrden($request->get('orden'));
      $caja->setTipo($request->get('tipo'));
      $caja->setCp($request->get('cp'));
      $caja->setCe($request->get('ce'));

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
      $em->flush();
      foreach ($request->get('asociaciones') as $asociacionId) {
        $asociacion = $em->getRepository('AppBundle:Asociacion')->find($asociacionId);
        if (!in_array($asociacion,$formatAsociacionesCaja)) {
          $asociacionesCaja = new AsociacionesCaja;
          $asociacionesCaja->setCaja($caja);
          $asociacionesCaja->setAsociacion($asociacion);
          $em->persist($asociacionesCaja);
          $em->flush();
        }
      }
      foreach ($formatAsociacionesCaja as $formatAsociacion) {
        if (!in_array($formatAsociacion->getId(),$request->get('asociaciones'))){
          $eliminar = $em->getRepository('AppBundle:AsociacionesCaja')
          ->findOneBy(['caja'=> $caja , 'asociacion'=> $formatAsociacion]); 
          $em->remove($eliminar);
          $em->flush();
        }
      }
      $this->addFlash(
        'notice',
        'Caja '.$caja->getNombre().' actualizada'
      );
      // return $this->redirectToRoute('cajas_index');
    }
    return $this->render('AppBundle:Caja:edit.html.twig', array(
      'caja' => $caja,
      'asociaciones' => $asociaciones,
      'asociacionesCaja' => $formatAsociacionesCaja
    ));
  }

  /**
   * @Route("/{id}/del" , name="cajas_del")
   */
  public function delAction(Caja $caja,Request $request)
  {
    $em =$this->getDoctrine()->getManager(); 
    $this->addFlash(
      'notice',
      'Caja '.$caja->getnombre().' eliminada'
    );
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
      $this->addFlash(
        'notice',
        $request->get('saldo').' R$ añadidos a '.$caja->getNombre()
      );
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

  /**
  * @Route("/active" , name="cajas_active")
  */
  public function changeAction(Request $request , \Swift_Mailer $mailer)
  {
    $em =$this->getDoctrine()->getManager(); 
    $caja = $em->getRepository('AppBundle:Caja')
    ->find($request->get('id')); 
    if ($caja->getActiva()) {
      $caja->setActiva(0);
    }else{
      // $message = (new \Swift_Message('Notificacion'))
      // ->setSubject('Notificaciones')
      // ->setFrom('info@financeirocheznous.org')
      // ->setTo($caja->getEmail())
      // ->setBody(
      //     $this->renderView(
      //         'AppBundle:Email:confirm.html.twig',
      //         array('caja' => $caja)),'text/html');

      // $mailer->send($message);
      $caja->setActiva(1);
    }
    $em->flush();
    return new JsonResponse(1);
  }

  /**
  * @Route("/negativo" , name="cajas_negativo")
  */
  public function saldoNegativoAction(Request $request , \Swift_Mailer $mailer)
  {
    $em =$this->getDoctrine()->getManager(); 
    $caja = $em->getRepository('AppBundle:Caja')
    ->find($request->get('id')); 
    if ($caja->getNegativo()) {
      $caja->setNegativo(0);
    }else{
      $caja->setNegativo(1);
      // $message = (new \Swift_Message('Notificacion'))
      // ->setSubject('Notificaciones')
      // ->setFrom('info@financeirocheznous.org')
      // ->setTo($caja->getEmail())
      // ->setBody(
      //     $this->renderView(
      //         'AppBundle:Email:confirm.html.twig',
      //         array('caja' => $caja)),'text/html');

      // $mailer->send($message);
    }
    $em->flush();
    return new JsonResponse(1);
  }

}
