<?php

namespace App\Controller\Admin;

use App\DTO\Admin\DataTableRequestDTO;
use App\DTO\Admin\NewsRequestDTO;
use App\Repository\NewsCategoryRepository;
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
        private readonly NewsCategoryRepository $newsCategoryRepository,
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
        $columnMap = [
            0 => 'n.title_it',
            1 => 'c.name_it',
            2 => 'n.event_datetime',
            4 => 'n.created_at',
        ];

        $orderColumnIndex = (int) ($payload->order[0]['column'] ?? 0);
        $orderDir = strtolower($payload->order[0]['dir'] ?? 'desc') === 'asc' ? 'asc' : 'desc';
        $orderColumn = $columnMap[$orderColumnIndex] ?? 'n.title_it';

        $qb = $this->newsRepository->createQueryBuilder("n")
            ->leftJoin("n.news_category", "c")
            ->orderBy($orderColumn, $orderDir);

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
                "categoryName" => $item->getNewsCategory()?->getNameIt(),
                "isEvent" => $item->getEventDatetime() !== null,
                "isPublic" => $item->isPublic(),
                "createdAt" => $item->getCreatedAt()->format("d/m/y \\a\\l\\l\\e H:i"),
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
        $categories = $this->newsCategoryRepository->findBy(["is_deleted" => false], ["name_it" => "asc"]);

        return $this->render('admin/news-detail.html.twig', [
            "news" => $news,
            "categories" => $categories,
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

    #[Route(path: '/upload-image/{newsId}', name: 'adminNewsUploadImage', methods: ['POST'], format: 'json')]
    public function adminNewsUploadImage(Request $request, int $newsId): Response
    {
        $news = $this->newsRepository->find($newsId);

        $uploadedFile = $request->files->get('coverImage');

        if (!$uploadedFile) {
            return $this->json([
                "message" => "Nessun file caricato",
            ], 400);
        }

        if ($uploadedFile->getError() !== UPLOAD_ERR_OK) {
            return $this->json([
                "message" => "Il file è troppo grande",
            ], 400);
        }

        $this->newsService->saveCoverImage($news, $uploadedFile);

        return $this->json([
            "message" => "Immagine caricata",
        ]);
    }

    #[Route(path: '/delete-image/{newsId}', name: 'adminNewsDeleteImage', methods: ['DELETE'], format: 'json')]
    public function adminNewsDeleteImage(int $newsId): Response
    {
        $news = $this->newsRepository->find($newsId);

        $this->newsService->deleteCoverImage($news);

        return $this->json([
            "message" => "Immagine eliminata",
        ]);
    }

}
