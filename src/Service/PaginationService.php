<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Environment;

class PaginationService
{
    private $entityClass;
    private $limit = 10;
    private $currentPage  = 1;
    private $manager;
    private $twig;
    private $route;

    public function __construct(EntityManagerInterface $manager, Environment $twig, RequestStack $request)
    {
        //  $this->route = $request->getCurrentRequest()->attributes->get('_route');
        $this->manager = $manager;
        $this->twig = $twig;
    }
    public function setRoute($route)
    {

        $this->route = $route;
        return $this;
    }
    public function getRoute($route)
    {
        return $this->route;
    }
    /* public function display()
    {
        $this->twig->display('admin/partials/pagination.html.twig', [
            'page' => $this->currentPage,
            'pages' => $this->getPages(),
            'route' =>  $this->route
        ]);
    }*/

    public function getPages()
    {
        //1)connaitre le total des enregistrement de la table 
        $repo = $this->manager->getRepository($this->entityClass);
        $total = count($repo->findAll());
        // utilise la method php ceil eq arrondi 3.4=>4 faire la div , l'arondi et le renvoyer 
        $pages = ceil($total / $this->limit);
        return $pages;
    }


    public function getData()
    {
        //1)Calcul offset 
        $offset = $this->currentPage * $this->limit - $this->limit;
        //2) demander au repository de trouver les elemts
        $repo = $this->manager->getRepository($this->entityClass);
        $data = $repo->findBy([], [], $this->limit, $offset);
        //3) Renvoyer les elements en question 
        return $data;
    }

    public function setPage($page)
    {
        $this->currentPage = $page;
        return $this;
    }
    public function getPage()
    {
        return $this->currentPage;
    }

    public function setLimit($limit)
    {
        $this->limit = $limit;
        return $this;
    }
    public function getLimit()
    {
        return $this->limit;
    }
    public function setEntityClass($entityClass)
    {
        $this->entityClass = $entityClass;
        return $this;
    }
    public function getEntityClass()
    {
        return $this->entityClass;
    }
}
