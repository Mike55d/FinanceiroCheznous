<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\User;
use AppBundle\Entity\Evento;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Entity\responsablesCaja;
use Symfony\Component\HttpFoundation\File\File;

class UsersController extends Controller
{
    /**
     * @Route("/users/" , name="users_index")
     */
    public function indexAction()
    {
        $em =$this->getDoctrine()->getManager(); 
        $user = $this->get('security.token_storage')
        ->getToken()->getUser(); 
        $users = $em->getRepository('AppBundle:User')->findUsers(); 
        return $this->render('AppBundle:Users:index.html.twig', array(
            'users'=>$users
        ));
    }

    /**
     * @Route("/users/new" , name="users_new")
     */
    public function newAction(Request $request)
    {
        $em =$this->getDoctrine()->getManager(); 
        if ($request->get('username')) {
            $user = new User;
            $user->setUsername($request->get('username'));
            $user->setName($request->get('nombre'));
            $user->setLastName($request->get('apellido'));
            $user->setEmail($request->get('email'));
            $user->setPhone($request->get('telefono'));
            $user->setActive(1);
            $user->setRoles($request->get('role'));
            $user->setImage('default_user.png');
            if ($request->get('role') == 'ROLE_USER') $user->setRola('USER');
            if ($request->get('role') == 'ROLE_ADMIN') $user->setRola('ADMIN');
            if ($request->get('role') == 'ROLE_SUPER_ADMIN') $user->setRola('SUPER ADMIN'); 
            $user->setNombreSede($request->get('nombreSede'));
            $user->setCiudadSede($request->get('ciudadSede'));
            $user->setPaisSede($request->get('paisSede'));
            $password = $this->get('security.password_encoder')
            ->encodePassword($user,$request->get('pw'));
            $user->setPassword($password);
            $em->persist($user);
            $em->flush();
            $this->addFlash(
                'notice',
                'Usuario '.$user->getUsername().' creado correctamente'
            );
            return $this->redirectToRoute('users_index');
        }
        return $this->render('AppBundle:Users:new.html.twig', array(
        ));
    }

    /**
     * @Route("/users/{id}/edit" , name="users_edit")
     */
    public function editAction(Request $request , User $user)
    {
        $em =$this->getDoctrine()->getManager(); 
        if ($request->get('nombre')) {
            $user->setName($request->get('nombre'));
            $user->setLastName($request->get('apellido'));
            $user->setEmail($request->get('email'));
            $user->setPhone($request->get('telefono'));
            $user->setActive(1);
            $user->setRoles($request->get('role'));
            if ($request->get('role') == 'ROLE_USER') $user->setRola('USER');
            if ($request->get('role') == 'ROLE_ADMIN') $user->setRola('ADMIN');
            if ($request->get('role') == 'ROLE_SUPER_ADMIN') $user->setRola('SUPER ADMIN');
            $user->setNombreSede($request->get('nombreSede'));
            $user->setCiudadSede($request->get('ciudadSede'));
            $user->setPaisSede($request->get('paisSede'));
            $evento = new Evento;
            $evento->setEvento('El usuario '.strtoupper($user->getUsername()).' ha sido modificado');
            $evento->setFecha(new \Datetime());
            $em->persist($evento);
            $em->flush();
            $this->addFlash(
                'notice',
                'Usuario '.$user->getName().' actualizado correctamente'
            );
            return $this->redirectToRoute('users_index');
        }
        return $this->render('AppBundle:Users:edit.html.twig', array(
            'user' =>$user
        ));
    }

    /**
     * @Route("/users/{id}/del" , name="users_del")
     */
    public function delAction(User $user , Request $request)
    {
        $em =$this->getDoctrine()->getManager(); 
        $this->addFlash(
                'notice',
                'Usuario '.$user->getName().' eliminado correctamente'
            );
        $em->remove($user);
        $em->flush();
        return $this->redirectToRoute('users_index');
    }

    /**
     * @Route("/valid" , name="users_valid")
     */
    public function validUsernameAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager(); 
        $user = $em->getRepository('AppBundle:User')
        ->findByUsername($request->get('username')); 
        if ($user) {
            $res = false;
        }else{
            $res = true;
        }
        return new JsonResponse($res);
    }

    /**
     * @Route("/register" , name="users_register")
     */
    public function registerAction(Request $request)
    {
        $em =$this->getDoctrine()->getManager(); 
        if ($request->get('username')) {
            $user = new User;
            $user->setUsername($request->get('username'));
            $user->setName($request->get('nombre'));
            $user->setLastName($request->get('apellido'));
            $user->setEmail($request->get('email'));
            $user->setPhone($request->get('telefono'));
            $user->setActive(0);
            $user->setRoles("ROLE_USER");
            $user->setRola("USER");
            $user->setNombreSede($request->get('nombreSede'));
            $user->setCiudadSede($request->get('ciudadSede'));
            $user->setPaisSede($request->get('paisSede'));
            $user->setImage('default_user.png');
            $time = new \Datetime();
            $pw = substr(md5($time->format('Y-m-d H:i:s')),0,8); 
            $user->setPw($pw);
            $password = $this->get('security.password_encoder')
            ->encodePassword($user,$pw);
            $user->setPassword($password);
            $evento = new Evento;
            $evento->setEvento('El usuario '.strtoupper($user->getUsername()).' se ah registrado');
            $evento->setFecha(new \Datetime());
            $em->persist($evento);
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('thanks');
        }
        return $this->render('AppBundle:Users:register.html.twig', array(
        ));
    }

    /**
     * @Route("/info" , name="thanks")
     */
    public function thanksAction(Request $request)
    {
        return $this->render('AppBundle:Users:thanks.html.twig', array(
        ));
    }

    /**
     * @Route("/editUser" , name="edit_user")
     */
    public function editUserAction(Request $request)
    {
        $em =$this->getDoctrine()->getManager(); 
        $user = $this->get('security.token_storage')
        ->getToken()->getUser(); 
        if ($request->get('nombre')) {
            $user->setName($request->get('nombre'));
            $user->setLastName($request->get('apellido'));
            $user->setPhone($request->get('telefono'));
            $user->setNombreSede($request->get('nombreSede'));
            $user->setCiudadSede($request->get('ciudadSede'));
            $user->setPaisSede($request->get('paisSede'));
            $evento = new Evento;
            $evento->setEvento('El usuario '.strtoupper($user->getUsername()).' ha sido modificado');
            $evento->setFecha(new \Datetime());
            $em->persist($evento);
            $em->flush();
            $this->addFlash(
                'notice',
                'Tus datos han sido actualizados correctamente'
            );
            return $this->redirectToRoute('homepage');
        }
        return $this->render('AppBundle:Users:editUser.html.twig', array(
            'user'=>$user
        ));
    }

    /**
     * @Route("/pwEditUser" , name="edit_pw_user")
     */
    public function pwEditUserAction(Request $request)
    {
        $em =$this->getDoctrine()->getManager(); 
        $user = $this->get('security.token_storage')
        ->getToken()->getUser();
        if ($request->get('pw')) {
            $password = $this->get('security.password_encoder')
            ->encodePassword($user,$request->get('pw'));
            $user->setPassword($password);
            $em->flush();
            return $this->redirectToRoute('homepage');
        }
        return $this->render('AppBundle:Users:changePw.html.twig', array(
        ));
    }

    /**
     * @Route("/cambiar_imagen" , name="cambiar_imagen")
     */
    public function changeImageAction(Request $request)
    {
        $em =$this->getDoctrine()->getManager(); 
        $user = $this->get('security.token_storage')
        ->getToken()->getUser();
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
           $user->setImage($fileName);
           $em->flush();
           $this->addFlash(
                'notice',
                'Tu imagen de perfil ah sido actualizada correctamente'
            );
           return $this->redirectToRoute('homepage');
       }
       return $this->render('AppBundle:Users:changeImage.html.twig', array(
       ));
   }

     /**
     * @Route("/users/active" , name="users_active")
     */
     public function changeAction(Request $request , \Swift_Mailer $mailer)
     {
        $em =$this->getDoctrine()->getManager(); 
        $user = $em->getRepository('AppBundle:User')
        ->find($request->get('id')); 
        if ($user->getActive()) {
            $user->setActive(0);
        }else{
            $message = (new \Swift_Message('Notificacion'))
            ->setSubject('Notificaciones')
            ->setFrom('info@financeirocheznous.org')
            ->setTo($user->getEmail())
            ->setBody(
                $this->renderView(
                    'AppBundle:Email:confirm.html.twig',
                    array('user' => $user)),'text/html');

            $mailer->send($message);
            $user->setActive(1);
        }
        $em->flush();
        return new JsonResponse(1);
    }

    /**
     * @Route("/lostPw" , name="users_lostPw")
     */
    public function lostPwAction(Request $request,\Swift_Mailer $mailer){
        $em =$this->getDoctrine()->getManager(); 
        $user = $em->getRepository('AppBundle:User')
        ->findOneByEmail($request->get('email')); 
        if ($user) {
            $message = (new \Swift_Message('Notificacion'))
            ->setSubject('Notificaciones')
            ->setFrom('info@financeirocheznous.org')
            ->setTo($user->getEmail())
            ->setBody(
                $this->renderView(
                    'AppBundle:Email:resetPw.html.twig',
                    array('user' => $user)),'text/html');
            $mailer->send($message);
            $r = 1;
        }else{
            $r = 0;
        }
        return new JsonResponse($r);
    }

    /**
     * @Route("/users/{id}/show" , name="users_show")
     */
    public function showAction(User $user , Request $request){
        $em =$this->getDoctrine()->getManager(); 
        $responsables = $em->getRepository('AppBundle:responsablesCaja')
        ->findBy(['user'=>$user->getId(),'responsable'=> 1]);
        $noResponsables = $em->getRepository('AppBundle:responsablesCaja')
        ->findBy(['user'=>$user->getId()]);
        return $this->render('AppBundle:Users:show.html.twig',[
            'user' => $user,
            'responsables' => $responsables,
            'noResponsables' => $noResponsables,
        ]);
    }


}
