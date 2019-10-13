<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Entity\responsablesCaja;
use Symfony\Component\Serializer\SerializerInterface;
use AppBundle\Entity\Caja;
use AppBundle\Entity\User;



/**
     * @Route("responsables")
     */
class ResponsablesController extends Controller
{
    /**
     * @Route("/{id}/list" , name="responsables_index")
     */
    public function indexAction(Request $request , Caja $caja ,SerializerInterface $serializer)
    {
        $em =$this->getDoctrine()->getManager();
        $usersAll = $em->getRepository('AppBundle:User')->findAll();  
        $users = [];
        foreach ($usersAll as $user) {
            $users[] = ['id'=>$user->getId(),
            'image'=>$user->getImage(),
            'text'=>$user->getName()]; 
        }
        $usersJson = $serializer->serialize(
            $users,
            'json');
        $responsables = $em->getRepository('AppBundle:responsablesCaja')
        ->findByCaja($caja->getId()); 
        return $this->render('AppBundle:Responsables:index.html.twig', array(
            'responsables' => $responsables,
            'caja' => $caja,
            'users' =>$usersJson,

        ));
    }

    /**
     * @Route("/{id}/listUser" , name="responsables_index_user")
     */
    public function indexUserAction(Request $request , User $user,SerializerInterface $serializer)
    {
        $em =$this->getDoctrine()->getManager(); 
        $cajas = $em->getRepository('AppBundle:Caja')->findAll(); 
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
        $responsables = $em->getRepository('AppBundle:responsablesCaja')
        ->findByUser($user->getId()); 
        return $this->render('AppBundle:Responsables:indexUser.html.twig', array(
            'responsables' => $responsables,
            'cajas' => $cajas,
            'user' =>$user,
            'newCajas' => $newCajas2,
        ));
    }

    /**
     * @Route("/{id}/new" , name="responsables_new")
     */
    public function newAction(Request $request , Caja $caja)
    {
        $em =$this->getDoctrine()->getManager();
        $exist = $em->getRepository('AppBundle:responsablesCaja')
        ->findBy(['caja'=>$caja->getId(), 'user'=>$request->get('user')]);
        if (!$exist) {
            $responsable = new responsablesCaja ;
            $user= $em->getRepository('AppBundle:User')
            ->find($request->get('user')); 
            $responsable->setCaja($caja);
            $responsable->setUser($user);
            $responsable->setResponsable($request->get('responsable'));
            $em->persist($responsable);
            $em->flush();
         }
        return $this->redirectToRoute('responsables_index',['id'=>$caja->getId()]);
    }

    /**
     * @Route("/{id}/newUser" , name="responsables_new_user")
     */
    public function newUserAction(Request $request , User $user)
    {
        $em =$this->getDoctrine()->getManager();
        $exist = $em->getRepository('AppBundle:responsablesCaja')
        ->findBy(['user'=>$user->getId(), 'caja'=>$request->get('caja')]);
        if (!$exist) {
            $responsable = new responsablesCaja ;
            $caja= $em->getRepository('AppBundle:Caja')
            ->find($request->get('caja')); 
            $responsable->setCaja($caja);
            $responsable->setUser($user);
            $responsable->setResponsable($request->get('responsable'));
            $em->persist($responsable);
            $em->flush();
         }
        return $this->redirectToRoute('responsables_index_user',['id'=>$user->getId()]);
    }

    /**
     * @Route("/edit" , name="responsables_edit")
     */
    public function editAction(Request $request)
    {
        $em =$this->getDoctrine()->getManager(); 
        return $this->render('AppBundle:Responsables:edit.html.twig', array(
            // ...
        ));
    }

    /**
     * @Route("/{id}/del" , name="responsables_del")
     */
    public function delAction(Request $request , responsablesCaja $resp)
    {
        $em =$this->getDoctrine()->getManager(); 
        $caja = $resp->getCaja()->getId();
        $em->remove($resp);
        $em->flush();
        return $this->redirectToRoute('responsables_index',['id'=>$caja]);
    }

    /**
     * @Route("/{id}/delUser" , name="responsables_del_user")
     */
    public function delUserAction(Request $request , responsablesCaja $resp)
    {
        $em =$this->getDoctrine()->getManager(); 
        $user = $resp->getUser()->getId();
        $em->remove($resp);
        $em->flush();
        return $this->redirectToRoute('responsables_index_user',['id'=>$user]);
    }

    /**
     * @Route("/change" , name="responsables_change")
     */
    public function changeAction(Request $request)
    {
        $em =$this->getDoctrine()->getManager(); 
        $responsablesCaja = $em->getRepository('AppBundle:responsablesCaja')
        ->find($request->get('id')); 
        if ($responsablesCaja->getResponsable()) {
            $responsablesCaja->setResponsable(null);
        }else{
            $responsablesCaja->setResponsable(1);
        }
        $em->flush();
        return new JsonResponse(1);
    }

}
