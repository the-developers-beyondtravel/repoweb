<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Entity\Remboursement;
use App\Form\RemboursementType;
use App\Repository\RemboursementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/remboursement")
 */

class RemboursementController extends AbstractController
{
    /**
     * @Route("/{id}", name="app_remboursement_index")
     */
    public function index($id): Response
    {

        $remboursements=$this->getDoctrine()->getRepository(Remboursement::class)->findBy([
            'reclamation' => $id
        ]);
        return $this->render('remboursement/index.html.twig', [
            'remboursements' => $remboursements,
        ]);
    }
    /**
     * @Route("/Admin/{id}", name="app_remboursement_index_admin")
     */
    public function indexAdmin($id): Response
    {

        $remboursements=$this->getDoctrine()->getRepository(Remboursement::class)->findBy([
            'reclamation' => $id
        ]);
        return $this->render('remboursement/indexAdmin.html.twig', [
            'remboursements' => $remboursements,
        ]);
    }

    /**
     * @Route("/Admin/Traiter/{id}", name="app_remboursement_new", methods={"GET", "POST"})
     */
    public function new(Request $request, RemboursementRepository $remboursementRepository,$id): Response
    {
            $remboursement = new Remboursement();
        $reclamation=$this->getDoctrine()->getRepository(Reclamation::class)->find($id);

        $form = $this->createForm(RemboursementType::class, $remboursement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $remboursement->setEtat("En Cours");
            $remboursement->setReclamation($reclamation);
            $remboursement->setUser($reclamation->getUser());
            $remboursementRepository->add($remboursement);

            return $this->redirectToRoute('app_reclamation_index_admin', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('remboursement/add.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/Approuver/{id}", name="Approuver_client")
     */
    function ApprouverAction(Request $request,$id,RemboursementRepository $remboursementRepository){
        $remboursement=$this->getDoctrine()->getRepository(Remboursement::class)->find($id);
        $reclamation=$this->getDoctrine()->getRepository(Reclamation::class)->find($remboursement->getReclamation()->getId());
        $remboursement->setEtat("Approuvée");
        $reclamation->setEtat("Approuvée");

        $em = $this->getDoctrine()->getManager();
        $em->flush();
        return $this->redirectToRoute('app_remboursement_index', ['id'=> $remboursement->getReclamation()->getId() ], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/Refuser/{id}", name="Refuser_client")
     */
    function RefuserAction(Request $request,$id){
        $remboursement=$this->getDoctrine()->getRepository(Remboursement::class)->find($id);
        $reclamation=$this->getDoctrine()->getRepository(Reclamation::class)->find($remboursement->getReclamation()->getId());
        $remboursement->setEtat("Refusé");
        $reclamation->setEtat("Refusé");

        $em = $this->getDoctrine()->getManager();
        $em->flush();

        return $this->redirectToRoute('app_remboursement_index', ['id'=> $remboursement->getReclamation()->getId() ], Response::HTTP_SEE_OTHER);
    }


}
