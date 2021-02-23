<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AdType;
use App\Repository\AdRepository;
use App\Service\PaginationService;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;

class AdminAdController extends AbstractController
{
    /**
     * @Route("/admin/ads/{page<\d+>?1}", name="admin_ads_index")
     */
    public function index(AdRepository $repo, $page, PaginationService $pagination)
    {

        $pagination->setEntityClass(Ad::class)
            ->setPage($page);

        return $this->render('admin/ad/index.html.twig', [
            'pagination' => $pagination
        ]);
        /*  //Méthode find: permet de trouver un enregistrement par son identifiant
        $ad = $repo->find(213);
        //recherche par critére
        //critére multiples 
        $ad = $repo->findOneBy([
            'title' => "Quam ex repudiandae aperiam molestias distinctio id non eos.",
            'id' => 222
        ]);

        //Méthode findby() permet de retrouver plusieurs enregistrement grace à des criteres 
        //elle prend 4 Arguments possible:1)critére 2)Orders 3)limite(nbre) 4)Offset(début)

        $ads = $repo->findby([], [], 5, 0);

        dump($ads);*/
    }


    /**
     * permet de modifer le formulaire d'édition
     * 
     * @Route("admin/ads/{id}/edit", name="admin_ads_edit")
     * 
     * @param Ad $ad
     * @return Response
     */

    public function edit(Ad $ad, Request $request, EntityManagerInterface $manager)
    {
        $form = $this->createForm(AdType::class, $ad);

        $form->handleRequest($request);



        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($ad);
            $manager->flush();

            $this->addFlash(
                'success',
                " l'annonce <strong>{$ad->getTitle()} </strong> a bien été enregistrer ! "
            );
        }



        return  $this->render('admin/ad/edit.html.twig', [
            'form' => $form->createView(),
            'ad' => $ad
        ]);
    }
    /**
     * permet de supprimer une annoce 
     * 
     * @Route("admin/ads/{id}/delete" , name="admin_ads_delete")
     *
     * @param Ad $ad
     * @param EntityManagerInterface $manager
     * @return Response 
     */
    public function delete(Ad $ad, EntityManagerInterface $manager)
    {
        if (count($ad->getBookings()) > 0) {
            $this->addFlash(
                'warning',
                "vous ne povez pas supprimer l'annoce <strong> {$ad->getTitle()} </strong> car elle possede déja des réservations  "
            );
        } else {
            $manager->remove($ad);
            $manager->flush();
            $this->addFlash(
                'success',
                "l'annonce <strong> {$ad->getTitle()}</strong> a bien été supprimée !"
            );
        }
        return $this->redirectToRoute("admin_ads_index");
    }
}
