<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\Booking;
use App\Form\BookingType;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class BookingController extends AbstractController
{
    /**
     * @Route("/ads/{slug}/book", name="booking_create")
     * @IsGranted("ROLE_USER")
     */
    public function book(Ad $ad, Request $request, EntityManagerInterface $manager)
    {
        $booking = new Booking();
        $form = $this->createForm(BookingType::class, $booking);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $booking->setBooker($user)
                ->setAd($ad);
            //si les dates ne sont pas disponible msg d'erreur 

            if (!$booking->isBookableDates()) {
                $this->addFlash(
                    'waring',
                    "les dates qui vous avez choisis ne peuvent pass etre réservé : elles sont déja prise "
                );
            } else {
                //si nn enregistrement et redirection 

                $manager = $this->getDoctrine()->getManager();
                $manager->persist($booking);
                $manager->flush();
                return $this->redirectToRoute('booking_show', ['id' => $booking->getId(), 'WithAlert' => true]);
            }
        }


        return $this->render('booking/book.html.twig', [
            'ad' => $ad,
            'form' => $form->createView()
        ]);
    }
    /**
     *permet d'afficher la page d'une reservation 
     *
     * @Route("/booking/{id}", name="booking_show")
     * 
     * @param Booking $booking
     * 
     * @return void  
     */
    public function show(Booking $booking)
    {
        return $this->render('booking/show.html.twig', [
            'booking' => $booking
        ]);
    }
}
