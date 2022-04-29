<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Repository\ReservationRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/reservation")
 */

class ReservationController extends AbstractController
{
    /**
     * @Route("/", name="app_reservation_index")
     */
    public function index(): Response
    {
        $reservations=$this->getDoctrine()->getRepository(Reservation::class)->findAll();

        return $this->render('reservation/index.html.twig', [
            'reservations' => $reservations,
        ]);
    }
    /**
     * @Route("/Admin", name="app_reservation_index_Admin")
     */
    public function indexAdmin(): Response
    {

        $reservations=$this->getDoctrine()->getRepository(Reservation::class)->findAll();

        foreach ($reservations as $reservation){
            $datec[] = [
                'id' => $reservation->getId(),
                'start' => $reservation->getDate()->format('Y-m-d H:i:s'),
                'title' => $reservation->getIdOffre()->getDestination(),
            ];
        }

        $data = json_encode($datec);

        return $this->render('reservation/indexAdmin.html.twig', [
            'reservations' => $reservations,
            'data' => $data,

        ]);
    }

    /**
     * @Route("/new", name="app_reservation_new", methods={"GET", "POST"})
     */
    public function new(Request $request, ReservationRepository $reservationRepository, UserRepository  $userRepository): Response
    {
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reservation->setIdUser($userRepository->find(40));
            $reservationRepository->add($reservation);
            return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reservation/add.html.twig', [
            'reservation' => $reservation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/edit/{id}", name="app_reservation_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Reservation $reservation, ReservationRepository $reservationRepository): Response
    {
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $reservationRepository->add($reservation);
            return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reservation/edit.html.twig', [
            'reservation' => $reservation,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/delete/{id}", name="app_reservation_delete")
     * method=({"DELETE"})
     */
    public function delete(Request $request, $id)
    {
        $reservation = $this->getDoctrine()->getRepository(Reservation::class)->find($id);

        $entityyManager = $this->getDoctrine()->getManager();
        $entityyManager->remove($reservation);
        $entityyManager->flush();

        $response = new Response();
        $response->send();

        return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
    }

}
