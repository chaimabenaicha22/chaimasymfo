<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AdType;
use App\Entity\Image;
use App\Form\Ad2Type;
use http\Env\Response;
use App\Repository\AdRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class AdController extends AbstractController
{
    /**
     * @Route("/ads", name="ads_index")
     */
    public function index(AdRepository $repo)
    {

        $ads = $repo->findAll();

        return $this->render('ad/index.html.twig', [
            'controller_name' => 'AdController',
            'ads' => $ads
        ]);
    }

    /**
     * permet de creer une annonce
     * @Route("ads/new" , name="ads_create")
     * @Security("is_granted('Role_User') and user === ad.getAuthor()")
     */
    public function create(Request $request)
    {
        $ad = new Ad();

        $form = $this->createForm(AdType::class, $ad);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $ad->setAuthor($this->getUser());
            $manager->persist($ad);
            $manager->flush();
        }
        return $this->render('ad/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     *permet de creer une annonce
     * @Route("ads/new2" , name="ads_create2")
     *
     */
    public function create2(Request $request)
    {
        $ad = new Ad();
        $form = $this->createForm(Ad2Type::class, $ad);
        $form->handleRequest($request);
        $manager = $this->getDoctrine()->getManager();
        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($ad->getImages() as $image) {
                $image->setAd($ad);
                $manager->persist($image);
            }

            $manager->persist($ad);
            $manager->flush();
            $this->addFlash('success', "l'annonce<strong>{{ad.title}}</strong>a bien été enregistrer!!!");

            return $this->redirectToRoute('ads_show', [
                'slug' => $ad->getSlug()
            ]);
        }
        return $this->render('ad/new2.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * permet de modifer le formulaire
     * @Route("/ads/{slug}/edit", name="ads_edit")
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function edit(Ad $ad, Request $request)
    {


        $form = $this->createForm(Ad2Type::class, $ad);
        $form->handleRequest($request);
        $manager = $this->getDoctrine()->getManager();
        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($ad->getImages() as $image) {
                $image->setAd($ad);
                $manager->persist($image);
            }

            $manager->persist($ad);
            $manager->flush();
            $this->addFlash('success', "Modification bien enregistrer!!");

            return $this->redirectToRoute('ads_show', [
                'slug' => $ad->getSlug()
            ]);
        }

        return  $this->render('ad/edit.html.twig', [
            'form' => $form->createView(),
            'ad' => $ad
        ]);
    }


    /**
     *Permet d'afficher une seule annonce
     * @Route("/ads/{slug}" ,name ="ads_show")
     *
     * @return Response
     */

    public function show(Ad $ad)
    {

        return $this->render('ad/show.html.twig', [
            'ad' => $ad
        ]);
    }
}
