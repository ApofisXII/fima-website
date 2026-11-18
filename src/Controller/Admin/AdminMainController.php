<?php

namespace App\Controller\Admin;

use App\Repository\BulletinRepository;
use App\Repository\NewsRepository;
use App\Repository\RecercareIssueRepository;
use App\Repository\UrbinoCourseRepository;
use App\Repository\UrbinoEditionRepository;
use App\Repository\UrbinoEventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/admin')]
class AdminMainController extends AbstractController
{
    public function __construct(
        private readonly NewsRepository $newsRepository,
        private readonly RecercareIssueRepository $recercareIssueRepository,
        private readonly BulletinRepository $bulletinRepository,
        private readonly UrbinoEditionRepository $urbinoEditionRepository,
        private readonly UrbinoCourseRepository $urbinoCourseRepository,
        private readonly UrbinoEventRepository $urbinoEventRepository,
    ) {}

    #[Route(path: '', name: 'adminDashboard')]
    public function adminDashboard(): Response
    {
        $newsCount = $this->newsRepository->count(['is_deleted' => false]);
        $recercareCount = $this->recercareIssueRepository->count(['is_deleted' => false]);
        $bulletinsCount = $this->bulletinRepository->count(['is_deleted' => false]);
        $editionsCount = $this->urbinoEditionRepository->count(['is_deleted' => false]);
        $coursesCount = $this->urbinoCourseRepository->count(['is_deleted' => false]);
        $eventsCount = $this->urbinoEventRepository->count(['is_deleted' => false]);

        return $this->render('admin/index.html.twig', [
            'newsCount' => $newsCount,
            'recercareCount' => $recercareCount,
            'bulletinsCount' => $bulletinsCount,
            'editionsCount' => $editionsCount,
            'coursesCount' => $coursesCount,
            'eventsCount' => $eventsCount,
        ]);
    }

}
