<?php

namespace App\Controller\Admin;

use App\DTO\Admin\DataTableRequestDTO;
use App\DTO\Admin\NewsCategoryRequestDTO;
use App\Repository\NewsCategoryRepository;
use App\Service\NewsCategoryService;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/admin/news-categories')]
class AdminNewsCategoriesController extends AbstractController
{
    public function __construct(
        private readonly NewsCategoryRepository $newsCategoryRepository,
        private readonly NewsCategoryService $newsCategoryService,
    ) {}

    #[Route(path: '', name: 'adminNewsCategoriesList')]
    public function adminNewsCategoriesList(): Response
    {
        return $this->render('admin/news-categories-list.html.twig');
    }

    #[Route(path: '/json', name: 'adminNewsCategoriesListJson')]
    public function adminNewsCategoriesListJson(#[MapQueryString] DataTableRequestDTO $payload): Response
    {
        $qb = $this->newsCategoryRepository->createQueryBuilder("c")
            ->andWhere("c.is_deleted = false")
            ->orderBy("c.name_it", "asc");

        if ($payload->search["value"]) {
            $search = $payload->search["value"];
            $qb->andWhere("c.name_it LIKE :search OR c.name_en LIKE :search")
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
                "nameIt" => $item->getNameIt(),
                "nameEn" => $item->getNameEn(),
                "createdAt" => $item->getCreatedAt()->format("d/m/Y \\a\\l\\l\\e H:i"),
                "categoryDetailLink" => $this->generateUrl("adminNewsCategoriesDetail", ["categoryId" => $item->getId()]),
            ];
        }, (array) $pagerfanta->getCurrentPageResults());

        return $this->json([
            "data" => $list,
            "draw" => $payload->draw,
            "recordsFiltered" => $pagerfanta->getNbResults(),
            "recordsTotal" => $pagerfanta->getNbResults(),
        ]);
    }

    #[Route(path: '/detail', name: 'adminNewsCategoriesDetail', methods: ['GET'])]
    public function adminNewsCategoriesDetail(Request $request): Response
    {
        $category = $this->newsCategoryRepository->findOneBy(["id" => $request->query->get("categoryId")]);

        return $this->render('admin/news-categories-detail.html.twig', [
            "category" => $category,
        ]);
    }

    #[Route(path: '/detail', name: 'adminNewsCategoriesDetailSave', methods: ['POST'], format: 'json')]
    public function adminNewsCategoriesDetailSave(#[MapRequestPayload] NewsCategoryRequestDTO $payload): Response
    {
        try {
            $category = $this->newsCategoryRepository->findOneBy(["id" => $payload->categoryId]);

            if (null === $category) {
                $this->newsCategoryService->create($payload);
            } else {
                $this->newsCategoryService->update($category, $payload);
            }

            return $this->json([
                "message" => "Categoria salvata",
            ]);
        } catch (\Exception $e) {
            return $this->json([
                "message" => $e->getMessage(),
            ], 400);
        }
    }

    #[Route(path: '/delete/{categoryId}', name: 'adminNewsCategoriesDelete', methods: ['DELETE'], format: 'json')]
    public function adminNewsCategoriesDelete(int $categoryId): Response
    {
        $category = $this->newsCategoryRepository->find($categoryId);

        $this->newsCategoryService->delete($category);

        return $this->json([
            "message" => "Categoria eliminata",
        ]);
    }
}
