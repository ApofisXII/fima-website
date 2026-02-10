<?php

namespace App\Controller\Admin;

use App\Repository\NewsRepository;
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
        private readonly UrbinoEditionRepository $urbinoEditionRepository,
        private readonly UrbinoCourseRepository $urbinoCourseRepository,
        private readonly UrbinoEventRepository $urbinoEventRepository,
    ) {}

    #[Route(path: '', name: 'adminDashboard')]
    public function adminDashboard(): Response
    {
        $newsCount = $this->newsRepository->count([]);
        $editionsCount = $this->urbinoEditionRepository->count([]);
        $coursesCount = $this->urbinoCourseRepository->count([]);
        $eventsCount = $this->urbinoEventRepository->count([]);

        return $this->render('admin/index.html.twig', [
            'newsCount' => $newsCount,
            'editionsCount' => $editionsCount,
            'coursesCount' => $coursesCount,
            'eventsCount' => $eventsCount,
        ]);
    }

}
