<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\AdminCommentType;
use App\Repository\CommentRepository;
use App\Service\PaginationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminCommentController extends AbstractController
{
    /**
     * @Route("/admin/comments/{page<\d+>?1}", name="admin_comment_index")
     */
    public function index(CommentRepository $repo, $page, PaginationService $pagination)
    {
        $pagination->setEntityClass(comment::class)
            ->setLimit(5)
            ->setPage($page);
        // $repo = $this->getDoctrine()->getRepository(Comment::class);
        // $comments = $repo->findAll();

        return $this->render('admin/comment/index.html.twig', [
            // 'comments' => $comments,
            'pagination' => $pagination
        ]);
    }

    /**
     * permet demodifier un commentaire 
     *
     * @Route("/admin/comments/{id}/edit" , name="admin_comment_edit")
     * 
     * @return Response 
     */
    public function edit(Comment $comment, Request $request, EntityManagerInterface $manager)
    {

        $form = $this->createForm(AdminCommentType::class, $comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($comment);
            $manager->flush();

            $this->addFlash(
                'success',
                "Le commmentaire num {$comment->getId()} a bien été modifié !"
            );
        }

        return  $this->render('admin/comment/edit.html.twig', [
            'comment' => $comment,
            'form' => $form->createView()
        ]);
    }

    /**
     * peremt de supprimer un commentaire 
     *
     * @Route("/admin/comments/{id}/delete" , name="admin_comment_delete")
     * @param Comment $comment
     * @param EntityManagerInterface $manager
     * @return Response 
     */
    public function delete(Comment $comment, EntityManagerInterface $manager)
    {
        $manager->remove($comment);
        $manager->flush(); //pour que la requete parte au BD 

        $this->addFlash(
            'success',
            " le commentaire de {$comment->getAuthor()->getfullName()} a bien été supprimer "
        );
        return $this->redirectToRoute('admin_comment_index');
    }
}
