<?php

namespace App\Controller\Public;

use App\Entity\News;
use App\Repository\NewsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/{_locale}/news')]
final class NewsController extends AbstractController
{

    public function __construct(
        private readonly NewsRepository $newsRepository,
    ) {}

    #[Route('', name: 'newsList')]
    public function newsList(): Response
    {
        $newsList = $this->newsRepository->findBy([
            "is_deleted" => false,
        ], ["created_at" => "desc"]);

        return $this->render('public/news-list.html.twig', [
            'newsList' => $newsList,
        ]);
    }

    #[Route('/{news}/{slug}', name: 'newsDetail')]
    public function newsDetail(News $news, string $slug): Response
    {
        // If slug changed, handle graceful redirect to the new URL
        if ($slug !== $news->getSlug()) {
            return $this->redirectToRoute('newsDetail', [
                'news' => $news->getId(),
                'slug' => $news->getSlug(),
            ], Response::HTTP_MOVED_PERMANENTLY);
        }

        return $this->render('public/news-detail.html.twig', [
            'news' => $news,
        ]);
    }

}
