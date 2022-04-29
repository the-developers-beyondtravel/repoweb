<?php

namespace App\Controller;

use App\Entity\Hotels;
use App\Form\HotelsType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;



class HotelsController extends AbstractController
{
    /**
     * @Route("/hotels", name="display_hotels")
     */
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        $hotels = $this->getDoctrine()->getManager()->getRepository(Hotels::class)->findAll();
        $hotels = $paginator->paginate(
            $hotels, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            6 // Nombre de résultats par page
        );
        return $this->render('hotels/index.html.twig', [
            'h'=>$hotels
        ]);
    }

    /**
     * @Route("/searchHotel", name="searchHotel")
     */

    public function searchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $requestString = $request->get('q');
        $hotels =  $em->getRepository(Hotels::class)->findEntitiesByString($requestString);
        
        
        if(!$hotels) {
            $result['hotels']['error'] = "Hotel introuvable :( ";
        } else {
            $result['hotels'] = $this->getRealEntities($hotels);
        }
        return new Response(json_encode($result));
        
    }
    public function getRealEntities($hotels){
        foreach ($hotels as $hotels){
            $realEntities[$hotels->getId()] = [$hotels->getImage(),$hotels->getNom(),$hotels->getAdresse(),$hotels->getNbetoiles(),$hotels->getPointfort(),$hotels->getId()];

        }
        return $realEntities;
    }

    /**
     * @Route("/hotelfront", name="hotels_front")
     */
    public function indexfront(Request $request, PaginatorInterface $paginator): Response
    {
        $hotels = $this->getDoctrine()->getManager()->getRepository(Hotels::class)->findAll();
        $hotels = $paginator->paginate(
            $hotels, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            6 // Nombre de résultats par page
        );
        return $this->render('hotels/hotelsfront.html.twig', [
            'h'=>$hotels
        ]);
    }

     /**
     * @Route("/addHotels", name="addHotels")
     */
    public function addHotels(Request $request): Response
    {
        $hotels = new Hotels();
        $form = $this->createForm(HotelsType::class,$hotels);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            
            $file = $form->get('image')->getData();
            $filename = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move($this->getParameter('image_directory'), $filename);
            $hotels->setImage($filename);

            $em = $this->getDoctrine()->getManager();
            $em->persist($hotels);
            $em->flush();

            return $this->redirectToRoute('display_hotels');
    }
        return $this->render('hotels/createHotels.html.twig',['f'=>$form->createView()]);
    }

     
    /**
     * @Route("/hotelfront{id}", name="Hotels_singel", methods={"GET"})
     */
    public function show(Hotels $hotels): Response
    {
        return $this->render('hotels/show.html.twig', [
            'hotels' => $hotels,
        ]);
    }
    


   
    /**
     * @Route("/removeHotels/{id}", name="supp_Hotels")
     */
    public function suppressionHotels(Hotels  $hotels): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($hotels);
        $em->flush();

        return $this->redirectToRoute('display_hotels');


    }


    /**
     * @Route("/modifhotels/{id}", name="modifhotels")
     */
    public function modifhotels(Request $request,$id): Response
    {
        $hotels = $this->getDoctrine()->getManager()->getRepository(hotels::class)->find($id);

        $form = $this->createForm(HotelsType::class,$hotels);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirectToRoute('display_hotels');
        }
        return $this->render('hotels/updateHotels.html.twig',['f'=>$form->createView()]);




    }

   

    
}
