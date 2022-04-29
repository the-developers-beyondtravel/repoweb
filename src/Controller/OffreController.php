<?php

namespace App\Controller;

use App\Entity\Offre;
use App\Entity\Reservation;
use App\Form\OffreType;
use App\Repository\OffreRepository;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
     * @Route("/offre")
 */

class OffreController extends AbstractController
{
    /**
     * @Route("/", name="app_offre_index")
     */
    public function index(): Response
    {
        $offres=$this->getDoctrine()->getRepository(Offre::class)->findAll();

        return $this->render('offre/index.html.twig', [
            'offres' => $offres,
        ]);
    }
    /**
     * @Route("/Admin", name="app_offre_index_admin")
     */
    public function indexAdmin(): Response
    {
        $offres=$this->getDoctrine()->getRepository(Offre::class)->findAll();

        $pieChart = new PieChart();
        $data= array();
        $stat=['Les Types', '%'];
        array_push($data,$stat);

        foreach($offres as $tmp)
        {
            $stat=array();
            $cmp = new Reservation();
            $cmp = $this->getDoctrine()->getManager()->getRepository(Reservation::class)->findBy([
                'id_offre' => $tmp
            ]);

            $total = count($cmp);
            $stat=[$tmp->getDestination(),$total];
            array_push($data,$stat);
        }

        $pieChart->getData()->setArrayToDataTable(
            $data
        );

        $pieChart->getOptions()->setTitle('Les Reservations des offres');
        $pieChart->getOptions()->setHeight(500);
        $pieChart->getOptions()->setWidth(900);
        $pieChart->getOptions()->getTitleTextStyle()->setBold(true);
        $pieChart->getOptions()->getTitleTextStyle()->setColor('#009900');
        $pieChart->getOptions()->getTitleTextStyle()->setItalic(true);
        $pieChart->getOptions()->getTitleTextStyle()->setFontName('Arial');
        $pieChart->getOptions()->getTitleTextStyle()->setFontSize(20);

        return $this->render('offre/indexAdmin.html.twig', [
            'offres' => $offres,
            'piechart' => $pieChart
        ]);
    }

    /**
     * @Route("/Admin/new", name="app_offre_new", methods={"GET", "POST"})
     */
    public function new(Request $request, OffreRepository $offreRepository): Response
    {
        $offre = new Offre();
        $form = $this->createForm(OffreType::class, $offre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $offreRepository->add($offre);
            return $this->redirectToRoute('app_offre_index_admin', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('offre/add.html.twig', [
            'offre' => $offre,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/Admin/edit/{id}", name="app_offre_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Offre $offre, OffreRepository $offreRepository): Response
    {
        $form = $this->createForm(OffreType::class, $offre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $offreRepository->add($offre);
            return $this->redirectToRoute('app_offre_index_admin', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('offre/edit.html.twig', [
            'offre' => $offre,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/Admin/delete/{id}", name="app_offre_delete")
     * method=({"DELETE"})
     */
    public function delete(Request $request, $id)
    {
        $offre = $this->getDoctrine()->getRepository(Offre::class)->find($id);

        $entityyManager = $this->getDoctrine()->getManager();
        $entityyManager->remove($offre);
        $entityyManager->flush();

        $response = new Response();
        $response->send();

        return $this->redirectToRoute('app_offre_index_admin', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/r/search_recc", name="search_recc", methods={"GET"})
     */
    public function search_rec(Request $request, NormalizerInterface $Normalizer, OffreRepository $offreRepository): Response
    {

        $requestString = $request->get('searchValue');
        $requestString3 = $request->get('orderid');

        $offre = $offreRepository->findOffre($requestString, $requestString3);
        $jsoncontentc = $Normalizer->normalize($offre, 'json', ['groups' => 'posts:read']);
        $jsonc = json_encode($jsoncontentc);
        if ($jsonc == "[]") {
            return new Response(null);
        } else {
            return new Response($jsonc);
        }
    }

        /**
         * @Route("/pdf/{id}", name="offre_pdf")
         */
        public function PDF(int $id)
    {
        //on definit les option du pdf
        $pdfOptions = new Options();
        //police par defaut
        $pdfOptions->set('defaultFont', 'Arial');
        $pdfOptions->setIsRemoteEnabled(true);

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        $offre = $this->getDoctrine()->getRepository(Offre::class)->find($id);



        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('offre/pdf.html.twig', [
            'offre' => $offre
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);



        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A3', 'paysage');

        // Render the HTML as PDF
        $dompdf->render();



        // Output the generated PDF to Browser (inline view)
        $dompdf->stream("offer.pdf", [
            "Attachment" => false
        ]);
        return new Response();
    }


    }
