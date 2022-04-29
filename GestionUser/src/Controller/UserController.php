<?php

namespace App\Controller;

use Knp\Component\Pager\PaginatorInterface;
use Knp\Bundle\PaginatorBundle\Pagination\SlidingPaginationInterface ;
use App\Entity\User;
use App\Form\registerType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\LoginType;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Form\UpdateType;
use App\Entity\Urlizer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Repository\loginTimeRepository;

class UserController extends AbstractController
{
    private $session;


    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }



   

    /**
     * @Route("/index", name="index")
     */
    public function index(): Response
    {
        return $this->render('front/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }
     /**
     * @Route("/dash", name="dash")
     */
    public function loginadmin(loginTimeRepository $a): Response
    {
        
        $values=$a->countingtotalvisits();
        $var=$a->countingtotalvisitsbyuser();
        
        
        return $this->render('user/dash.html.twig', [
            'values' =>$values,'var'=>$var,
        ]);
    
    }
    /**
     * @Route("/error", name="error")
     */
    public function error(): Response
    {
        return $this->render('user/error.html.twig',
        );
    }
     /**
     * @Route("/users", name="list")
     */
    public function liste(Request $request,PaginatorInterface $paginator): Response
    {
        $rep=$this->getDoctrine()->getRepository(User::class);
        $data=$rep->findby([]);
        $user = $paginator->paginate(
            $data,
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            6 // Nombre de résultats par page


        );


        return $this->render('user/userList.html.twig', [
            'user' => $user,
        ]);
    } 


    /**
     * @Route("/settings", name="update")
     */
 public function update(Request $request): Response
 {

       $rep=$this->getDoctrine()->getRepository(User::class);
     
        $user=$rep->find($this->getUser()->getId());
        $form=$this->createForm(UpdateType::class,$user);
        $form=$form->handleRequest($request);
        if ($form->isSubmitted())
        {
                   /** @var UploadedFile $uploadedFile */
                   $uploadedFile = $form['img_name']->getData();
                   $destination = $this->getParameter('kernel.project_dir').'/public/uploads/images/user';
                   $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
                   $newFilename = Urlizer::urlize($originalFilename).'-'.uniqid().'.'.$uploadedFile->guessExtension();
                   $uploadedFile->move(
                       $destination,
                       $newFilename
                   );
                   $user->setImgName($newFilename);
                   $user=$form->getData();
        $em=$this->getDoctrine()->getManager();
        $em->flush();
         return $this->redirectToRoute('index');
         
        }
    
           return $this->render('user/account.front.html.twig', [
               'f' => $form->createView(),'h'=> $user
           ]);


 
 }      
/**
     * @Route("/searchusers", name="searshusers")
     */

    public function searchAction(Request $request,UserRepository $a)
    {
        $em = $this->getDoctrine()->getManager();
        $requestString = $request->get('q');
        $users =  $a->findEntitiesByString($requestString);
        
        
        if(!$users) {
            $result['$users']['error'] = "Hotel introuvable 🙁 ";
        } else {
            $result['$users'] = $this->getRealEntities($users);
        }
        return new Response(json_encode($result));
        
    }
    public function getRealEntities($users){
        foreach ($users as $users){
            $realEntities[$users->getId()] = [$users->getImgName(),$users->getEmail(),$users->getRoles(),$users->getFirstname(),$users->getLastname(),$users->getDateDN(),$users->getSexe(),$users->getTelNum(),$users->getAdresse(),$users->getId()];

        }
        return $realEntities;
    }

  /**
     * @Route("/disable/{id}", name="disable")
     */
    public function disable(Request $request,$id){

        $rep = $this->getDoctrine()->getManager();
        $user = $rep->getRepository(User::class)->find($id);
        $user->setdisable(true);
        $rep->flush();
        return $this->redirectToRoute('list');
}
  /**
     * @Route("/enable/{id}", name="enable")
     */
    public function enable($id){

        $rep = $this->getDoctrine()->getManager();
        $user = $rep->getRepository(User::class)->find($id);
        $user->setdisable(false);
        $rep->flush();
        return $this->redirectToRoute('list');
}
  /**
     * @Route("/upgrade/{id}", name="upgrade")
     */
    public function makeadmin($id){

        $rep = $this->getDoctrine()->getManager();
        $user = $rep->getRepository(User::class)->find($id);
        $roles[] = 'ROLE_CLIENT';
        $roles[] = 'ROLE_ADMIN';
        $user->setRoles($roles);
        $rep->flush();
        return $this->redirectToRoute('list');
}
  /**
     * @Route("/dwonupgrade/{id}", name="downupgrade")
     */
    public function unmakeadmin($id){

        $rep = $this->getDoctrine()->getManager();
        $user = $rep->getRepository(User::class)->find($id);
        $roles[] = 'ROLE_CLIENT';
        $user->setRoles($roles);
        $rep->flush();
        return $this->redirectToRoute('list');
}

    /**
 * @Route("/delete/{id}", name="delete")
 */
public function delete($id): Response
{
    $rep = $this->getDoctrine()->getRepository(User::class);
    $em = $this->getDoctrine()->getManager();
    $user = $rep->find($id);
    $em->remove($user);
    $em->flush();
    return $this->redirectToRoute('list');
}
}
    

