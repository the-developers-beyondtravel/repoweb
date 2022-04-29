<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\registerType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\LoginType;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
class UserController extends AbstractController
{
    private $session;


    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }





    /**
     * @Route("/usr", name="index")
     */
    public function index(): Response
    {
        return $this->render('front/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

/**
 * @Route("/register", name="adduser")
*/
    public function adduser(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(registerType::class,$user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
                if(str_contains($user->getEmail(),'@beyond-travel.tn')){
                    $user->setRole("admin");
                } else {
                   $user->setRole("client");}
         $em = $this->getDoctrine()->getManager();
           $em->persist($user);//Add
            $em->flush();
            return $this->redirectToRoute('index');
        }
        return $this->render('user/register.html.twig',['f'=>$form->createView()]);
    
    
    
    } 
    

/**
 * @Route("/login", name="login")
 */
public function login(Request $request,UserRepository $repository): Response
{
   
    if($this->session->get('role'))
    {
        return $this->redirectToRoute('index');
    }else
    $user = new User();
    $form = $this->createForm(LoginType::class,$user);
    $form->handleRequest($request);
    if($form->isSubmitted() && $form->isValid()) {
        $userCheck = $repository->findOneBy(['email' => $user->getEmail(), 'password' => $user->getPassword()]);
        if($userCheck)
        {
            if($userCheck->getRole())
            {

                $this->session->set('id',$userCheck->getId());
            /*    $this->session->set('nom',$userCheck->getNom());
                $this->session->set('prenom',$userCheck->getPrenom());
                $this->session->set('mail',$userCheck->getMail());*/
                $this->session->set('role',$userCheck->getRole());
                $role = $this->session->get('role');
                if($role == 'client')
                return $this->redirectToRoute('index');

            }
            else {
                return $this->render('user/login.html.twig', [
                    'f' => $form->createView(),
                ]);
            }
    }
}
return $this->render('user/login.html.twig', [
    'f' => $form->createView(),
]);




} 
    /**
     * @Route("/logout", name="logout")
     */
 public function logout(Request $request,UserRepository $repository)
{
    $role = $this->session->get('role');
    $this->session->clear();

   if($role == 'client')
   return $this->redirectToRoute('index');
   else return $this->redirectToRoute('login');//lil admin

}    
    
      /**
     * @Route("/settings", name="update")
     */
 public function update(Request $request,UserRepository $repository)
 {
    if($this->session->get('role'))
    {
    return $this->render('user/account.front.html.twig');
    }else{
        return $this->redirectToRoute('index');
    }
 
 }      
    
}
