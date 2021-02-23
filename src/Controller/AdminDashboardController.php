<?php

namespace App\Controller;

use App\Service\StatsService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Expr\Select;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminDashboardController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_dashboard")
     */
    public function index(EntityManagerInterface $manager, StatsService $statsService)
    {
        //   $users = $statsService->getUsersCount();
        //  $ads = $statsService->getAdsCount();
        //  $bookings = $statsService->getBookingsCount();
        //  $comments = $statsService->getCommentsCount();
        $stats = $statsService->getStats();

        $bestAds = $statsService->getBestAds();

        $worstAds = $statsService->getWorstAds();

        return $this->render('admin/dashboard/index.html.twig', [
            'stats' => $stats,
            'bestAds' => $bestAds,
            'worstAds' => $worstAds

        ]);
    }
}
