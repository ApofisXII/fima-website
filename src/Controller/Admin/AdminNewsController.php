<?php

namespace App\Controller\Admin;

use App\DTO\Admin\NewsRequestDTO;
use App\Repository\NewsRepository;
use App\Service\NewsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Attribute\MapUploadedFile;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/admin/news')]
class AdminNewsController extends AbstractController
{
    public function __construct(
        private readonly NewsRepository $newsRepository,
        private readonly NewsService    $newsService,
    ) {}

    #[Route(path: '', name: 'adminNewsList')]
    public function adminNewsList(): Response
    {
        return $this->render('admin/news-list.html.twig');
    }

    #[Route(path: '/detail', name: 'adminNewsDetail', methods: ['GET'])]
    public function adminNewsDetail(Request $request): Response
    {
        $news = $this->newsRepository->findOneBy(["id" => $request->request->get("newsId")]);

        return $this->render('admin/news-detail.html.twig', [
            "news" => $news,
        ]);
    }

    #[Route(path: '/detail', name: 'adminNewsDetailSave', methods: ['POST'], format: 'json')]
    public function adminNewsDetailSave(#[MapRequestPayload] NewsRequestDTO $payload, #[MapUploadedFile] ?UploadedFile $coverImage): Response
    {
        $news = $this->newsRepository->findOneBy(["id" => $payload->newsId]);

        if (null === $news) {
            $news = $this->newsService->create($payload);
        } else {
            $news = $this->newsService->update($news, $payload);
        }

        return $this->json([
            "message" => "Dati salvati",
        ]);
    }

}
