<?php

namespace App\Controller;
use App\Entity\Evenement;
use App\Entity\Participation;
use App\Form\EventType;
use App\Repository\EventRepository;
use App\Repository\ParticipationRespository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Dompdf\Dompdf;
use Dompdf\Options;


use Knp\Component\Pager\PaginatorInterface; // Nous appelons le bundle KNP Paginator
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;

use MercurySeries\FlashyBundle\FlashyNotifier;



class EventController extends AbstractController
{
   
    /**
     * @Route("/event", name="app_event")
     */
    public function index(ParticipationRespository $a , FlashyNotifier $flashy): Response
    {
    /*    return $this->render('base.back.html.twig', [
            'controller_name' => 'EventController',
        ]);*/
        $rep=$this->getDoctrine()->getRepository(Evenement::class);
        
        $event=$rep->findAll();

        

        $flashy->muted('Can you see me?', 'http://your-awesome-link.com');

    
        $check=$a->selectbyevent(12);//$this->getUser()->getId()
        
        $session=false;
        if($session==false){
            return $this->render('event/eventF.html.twig', [
                'event' =>$event,'check' => $check,
            ]);
        }else{

            return $this->render('event/eventF.html.twig', [
                'event' =>$event,

                
            ]);
        }

      

    }
     /**
     * @Route("/eventlist", name="lista")
     */
    public function liste(Request $request, PaginatorInterface $paginator , FlashyNotifier $flashy): Response
    {
        $rep=$this->getDoctrine()->getRepository(Evenement::class);
        $event=$rep->findAll();
        $event = $paginator->paginate(
            $event, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            3/*limit per page*/
        );


        
         $flashy->error('Uh Oh', 'http://your-awesome-link.com');

        return $this->render('event/eventList.html.twig', [
            'events' => $event,
        ]);
    }   
    
    /**
 * @Route("/addevent", name="addevent")
*/
public function addevent(Request $request ): Response
{
    $evenement=new Evenement();
    $form=$this->createForm(EventType::class,$evenement);
    $form=$form->handleRequest($request);
    if($form->isSubmitted() && $form->isValid())  {
        $evenement=$form->getData();
        $em=$this->getDoctrine()->getManager();
        $em->persist($evenement);
        $em->flush();



        return $this->redirectToRoute('lista');

   
    }
    return $this->render('event/addevent.html.twig', [
        'f' => $form->createView(),

    ]);
}


/**
     * @Route("/imprimreservationm", name="imprimreservationm")
     */
    public function imprimEvent(): Response

    {

        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);


        $event = $this->getDoctrine()->getManager()->getRepository(Evenement::class)->findAll();

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('event/imprimerEvent.html.twig', [
            'events' => $event,
                ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (inline view)
        $dompdf->stream("listes des evenements.pdf", [
            "Attachment" => true
        ]);
    }















    /**
     * @Route("/{id}", name="update")
     */

    public function update($id,Request $request): Response

    {
   
          $rep=$this->getDoctrine()->getRepository(Evenement::class);
        
           $event=$rep->find($id);
           $form=$this->createForm(EventType::class,$event);
           $form=$form->handleRequest($request);
           if($form->isSubmitted() && $form->isValid())
            {

            $em=$this->getDoctrine()->getManager();

           $em->flush();
            return $this->redirectToRoute('lista');
            
           }
       
              return $this->render('event/addevent.html.twig', [
                  'f' => $form->createView(), 'ev' => $event,
              ]);
   
   
    
    }     
    /**
 * @Route("/delete/{id}", name="delete_events")
 */
public function deleteEvent($id): Response
{
    $rep = $this->getDoctrine()->getRepository(Evenement::class);
    $em = $this->getDoctrine()->getManager();
    $evenement = $rep->find($id);
    $em->remove($evenement);
    $em->flush();
    return $this->redirectToRoute('lista');
}
    
     /**
 * @Route("/participer/{id}", name="participer")
*/
public function participer($id,Request $request,EventRepository $a): Response
{
      $participation = new Participation();
      $participation->setIdevenement($id);
      //$this->getUser()->getId()
      $participation->setIduser(12);
      $em=$this->getDoctrine()->getManager();
      $em->persist($participation);
      $em->flush();
      $a->capaciteDOWNbyONE($id);
    return $this->redirectToRoute('app_event');
    


  
}   
     /**
 * @Route("/imparticiper/{id}", name="imparticiper")
*/
public function imparticiper($id,ParticipationRespository $b,EventRepository $a): Response
{
    //$this->getUser()->getId()
     $b->delete($id,12);
      $a->capaciteUPbyONE($id);
    return $this->redirectToRoute('app_event');
}









/**
     * @Route("/trievent", name="trievent")
     */
    public function Tri(Request $request)
    {
        $em = $this->getDoctrine()->getManager();


        $query = $em->createQuery(
            'SELECT p FROM App\Entity\Evenement p
            ORDER BY p.prix '
        );

        $event = $query->getResult();



        return $this->render('eventList/eventlist.html.twig',
            array('c' => $event));

    }








/**
     * @Route("/statevent", name="statevent")
     */
    public function stat()
    {

        $repository = $this->getDoctrine()->getRepository(Voyage::class);
        $voyage = $repository->findAll();

        $em = $this->getDoctrine()->getManager();


        $pr1 = 0;
        $pr2 = 0;


        foreach ($ev as $ev) {
            if ($event->getPrix() == "900")  :

                $pr1 += 1;
            else:

                $pr2 += 1;

            endif;

        }

        $pieChart = new PieChart();
        $pieChart->getData()->setArrayToDataTable(
            [['prix', 'nom'],
                ['900', $pr1],
                ['1200', $pr2],
            ]
        );
        $pieChart->getOptions()->setTitle('Prix des events');
        $pieChart->getOptions()->setHeight(500);
        $pieChart->getOptions()->setWidth(900);
        $pieChart->getOptions()->getTitleTextStyle()->setBold(true);
        $pieChart->getOptions()->getTitleTextStyle()->setColor('#009900');
        $pieChart->getOptions()->getTitleTextStyle()->setItalic(true);
        $pieChart->getOptions()->getTitleTextStyle()->setFontName('Arial');
        $pieChart->getOptions()->getTitleTextStyle()->setFontSize(20);

        return $this->render('event/statEvent.html.twig', array('piechart' => $pieChart));
    }


    /**
     * @Route("/Recherche", name="Recherche")
     */
   public function rechercheByName(Request $request)
   {
       $em=$this->getDoctrine()->getManager();
       $event=$em->getRepository(evenement::class)->findAll();
       if($request->isMethod("POST"))
       {
           $nom = $request->get('nom');
           $event=$em->getRepository(evenement::class)->findBy(array('nom'=>$nom));
       }
       return $this->render('event/eventlist.html.twig', array('events' => $event));


   }







}

