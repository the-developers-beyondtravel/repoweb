<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Entity\Remboursement;
use App\Entity\User;
use App\Form\ReclamationType;
use App\Repository\ReclamationRepository;
use App\Repository\RemboursementRepository;
use App\Repository\UserRepository;
use App\Services\QrcodeService;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/reclamation")
 */

class ReclamationController extends AbstractController
{
    /**
     * @Route("/", name="app_reclamation_index")
     */
    public function index(): Response
    {
        $reclamations=$this->getDoctrine()->getRepository(Reclamation::class)->findAll();
        $remboursements=$this->getDoctrine()->getRepository(Remboursement::class)->findAll();
        return $this->render('reclamation/index.html.twig', [
            'reclamations' => $reclamations,
            'remboursements' => $remboursements,
        ]);
    }
    /**
     * @Route("/Admin", name="app_reclamation_index_admin")
     */
    public function indexAdmin(): Response
    {
        $reclamations=$this->getDoctrine()->getRepository(Reclamation::class)->findAll();
        $remboursements=$this->getDoctrine()->getRepository(Remboursement::class)->findAll();

        $totalRefu=0;
        $totalEnCours=0;
        $totalApprouve=0;

        foreach($reclamations as $tmp)
        {

            if($tmp->getEtat() == "Refusé")
            {
                $totalRefu=$totalRefu+1;
            }
            elseif ($tmp->getEtat() == "Approuvée")
            {
                $totalApprouve=$totalApprouve+1;
            }
            if($tmp->getEtat() == "En Cours")
            {
                $totalEnCours=$totalEnCours+1;
            }
        }

        return $this->render('reclamation/indexAdmin.html.twig', [
            'reclamations' => $reclamations,
            'remboursements' => $remboursements,
            'totalrefu'=> $totalRefu,
            'totalapprouve'=> $totalApprouve,
            'totalencours'=> $totalEnCours,
        ]);
    }

    /**
     * @Route("/new", name="app_reclamation_new", methods={"GET", "POST"})
     */
    public function new(Request $request, ReclamationRepository $reclamationRepository, UserRepository  $userRepository,QrcodeService $qrcodeService): Response
    {
        $reclamation = new Reclamation();
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        $qrCode=null;

        if ($form->isSubmitted() && $form->isValid()) {
            $user=new User();
            $file = $form->get('image')->getData();
            if($file)
            {
                $fileName = md5(uniqid()).'.'.$file->guessExtension();
                try {
                    $file->move(
                        $this->getParameter('images_directory'),
                        $fileName
                    );
                } catch (FileException $e){

                }
                $reclamation->setImage($fileName);
            }
            else
            {
                $reclamation->setImage("NoImage.png");
            }

            $reclamation->setUser($userRepository->find(40));
            $reclamation->setEtat("En Cours");
            $qrCode=$qrcodeService->qrCode($reclamation->getSujet());

            $reclamationRepository->add($reclamation);
            $this->addFlash(
                'info',
                'Added Successfully'
            );

            return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reclamation/add.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/edit/{id}", name="app_reclamation_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Reclamation $reclamation, ReclamationRepository $reclamationRepository): Response
    {
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('image')->getData();
            if($file)
            {
                $fileName = md5(uniqid()).'.'.$file->guessExtension();
                try {
                    $file->move(
                        $this->getParameter('images_directory'),
                        $fileName
                    );
                } catch (FileException $e){

                }
                $reclamation->setImage($fileName);
            }

            $reclamationRepository->add($reclamation);
            $this->addFlash(
                'info-edit',
                'Updated Successfully'
            );

            return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reclamation/edit.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/delete/{id}", name="app_reclamation_delete")
     * method=({"DELETE"})
     */
    public function delete(Request $request, $id)
    {
        $reclamation = $this->getDoctrine()->getRepository(Reclamation::class)->find($id);

        $entityyManager = $this->getDoctrine()->getManager();
        $entityyManager->remove($reclamation);
        $entityyManager->flush();

        $response = new Response();
        $response->send();
        $this->addFlash(
            'info-delete',
            'Deleted Successfully'
        );

        return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
    }


    /**
     * @Route("/Admin/Approuver/{id}", name="Approuver")
     */
    function ApprouverAction(Request $request,$id,RemboursementRepository $remboursementRepository){
        $reclamation = new Reclamation();

        $reclamation=$this->getDoctrine()->getRepository(Reclamation::class)->find($id);
        $reclamation->setEtat("Approuvée");
        $remboursement = new Remboursement();
        $remboursement->setEtat("Approuvée");
        $remboursement->setReclamation($reclamation);
        $remboursement->setUser($reclamation->getUser());
        $remboursement->setPrixtotal($reclamation->getMontant());
        $remboursementRepository->add($remboursement);

        $em = $this->getDoctrine()->getManager();
        $em->flush();

        return $this->redirectToRoute('app_reclamation_index_admin');
    }

    /**
     * @Route("/Admin/Refuser/{id}", name="Refuser")
     */
    function RefuserAction(Request $request,$id){
        $reclamation=$this->getDoctrine()->getRepository(Reclamation::class)->find($id);
        $reclamation->setEtat("Refusé");

        $em = $this->getDoctrine()->getManager();
        $em->flush();

        return $this->redirectToRoute('app_reclamation_index_admin');
    }

    /**
     * @Route("/excel/export",  name="export")
     */
    public function export()
    {
        $spreadsheet = new Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setTitle('Reclamation List');

        $sheet->getCell('A1')->setValue('type');
        $sheet->getCell('B1')->setValue('description');
        $sheet->getCell('C1')->setValue('sujet');
        $sheet->getCell('D1')->setValue('etat');
        $sheet->getCell('E1')->setValue('montant');

        // Increase row cursor after header write
        $sheet->fromArray($this->getData(), null, 'A2', true);
        $writer = new Xlsx($spreadsheet);
        // $writer->save('ss.xlsx');
        $writer->save('Reclamation' . date('m-d-Y_his') . '.xlsx');
        return $this->redirectToRoute('app_reclamation_index_admin');
    }

    public function getData()
    {
        /**
         * @var $reclamation typ[]
         */
        $list = [];
        $typerec = $this->getDoctrine()->getRepository(Reclamation::class)->findAll();

        foreach ($typerec as $typ) {
            $list[] = [
                $typ->getType(),
                $typ->getDescription(),
                $typ->getSujet(),
                $typ->getEtat(),
                $typ->getMontant(),

            ];
        }
        return $list;
    }

}
