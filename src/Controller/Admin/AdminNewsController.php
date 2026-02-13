<?php

namespace App\Controller\Admin;

use App\DTO\Admin\DataTableRequestDTO;
use App\DTO\Admin\NewsRequestDTO;
use App\Repository\NewsRepository;
use App\Service\NewsService;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
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

    #[Route(path: '/json', name: 'adminNewsListJson')]
    public function adminNewsListJson(#[MapQueryString] DataTableRequestDTO $payload): Response
    {
        $qb = $this->newsRepository->createQueryBuilder("n")
            ->orderBy("n.id", "desc");

        if ($payload->search["value"]) {
            $search = $payload->search["value"];
            $qb->andWhere("n.title_it LIKE :search OR n.title_en LIKE :search OR n.body_it LIKE :search OR n.body_en LIKE :search")
                ->setParameter("search", "%".$search."%");
        }

        $adapter = new QueryAdapter($qb->getQuery());
        $pagerfanta = Pagerfanta::createForCurrentPageWithMaxPerPage(
            adapter: $adapter,
            currentPage: floor($payload->start / $payload->length) + 1,
            maxPerPage: $payload->length,
        );

        $list = array_map(function ($item) {
            return [
                "titleIt" => $item->getTitleIt(),
                "isEvent" => $item->isEvent(),
                "isPublic" => $item->isPublic(),
                "createdAt" => $item->getCreatedAt()->format("d/m/Y \\a\\l\\l\\e H:i"),
                "newsDetailLink" => $this->generateUrl("adminNewsDetail", ["newsId" => $item->getId()]),
            ];
        }, (array) $pagerfanta->getCurrentPageResults());

        return $this->json([
            "data" => $list,
            "draw" => $payload->draw,
            "recordsFiltered" => $pagerfanta->getNbResults(),
            "recordsTotal" => $pagerfanta->getNbResults(),
        ]);
    }

    #[Route(path: '/detail', name: 'adminNewsDetail', methods: ['GET'])]
    public function adminNewsDetail(Request $request): Response
    {
        $news = $this->newsRepository->findOneBy(["id" => $request->query->get("newsId")]);

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

        if ($coverImage) {
            $this->newsService->saveCoverImage($news, $coverImage);
        }

        return $this->json([
            "message" => "Dati salvati",
        ]);
    }

}
