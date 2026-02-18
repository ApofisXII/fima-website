<?php

namespace App\Controller\Admin;

use App\DTO\Admin\DataTableRequestDTO;
use App\DTO\Admin\UrbinoCourseCategoryRequestDTO;
use App\Repository\UrbinoCourseCategoryRepository;
use App\Service\UrbinoCourseCategoryService;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/admin/urbino-categories')]
class AdminUrbinoCategoriesController extends AbstractController
{
    public function __construct(
        private readonly UrbinoCourseCategoryRepository $urbinoCourseCategoryRepository,
        private readonly UrbinoCourseCategoryService $urbinoCourseCategoryService,
    ) {}

    #[Route(path: '', name: 'adminUrbinoCategoriesList')]
    public function adminUrbinoCategoriesList(): Response
    {
        return $this->render('admin/urbino-categories-list.html.twig');
    }

    #[Route(path: '/json', name: 'adminUrbinoCategoriesListJson')]
    public function adminUrbinoCategoriesListJson(#[MapQueryString] DataTableRequestDTO $payload): Response
    {
        $qb = $this->urbinoCourseCategoryRepository->createQueryBuilder("c")
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
                "categoryDetailLink" => $this->generateUrl("adminUrbinoCategoriesDetail", ["categoryId" => $item->getId()]),
            ];
        }, (array) $pagerfanta->getCurrentPageResults());

        return $this->json([
            "data" => $list,
            "draw" => $payload->draw,
            "recordsFiltered" => $pagerfanta->getNbResults(),
            "recordsTotal" => $pagerfanta->getNbResults(),
        ]);
    }

    #[Route(path: '/detail', name: 'adminUrbinoCategoriesDetail', methods: ['GET'])]
    public function adminUrbinoCategoriesDetail(Request $request): Response
    {
        $category = $this->urbinoCourseCategoryRepository->findOneBy(["id" => $request->query->get("categoryId")]);

        return $this->render('admin/urbino-categories-detail.html.twig', [
            "category" => $category,
        ]);
    }

    #[Route(path: '/detail', name: 'adminUrbinoCategoriesDetailSave', methods: ['POST'], format: 'json')]
    public function adminUrbinoCategoriesDetailSave(#[MapRequestPayload] UrbinoCourseCategoryRequestDTO $payload): Response
    {
        try {
            $category = $this->urbinoCourseCategoryRepository->findOneBy(["id" => $payload->categoryId]);

            if (null === $category) {
                $this->urbinoCourseCategoryService->create($payload);
            } else {
                $this->urbinoCourseCategoryService->update($category, $payload);
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

    #[Route(path: '/delete/{categoryId}', name: 'adminUrbinoCategoriesDelete', methods: ['DELETE'], format: 'json')]
    public function adminUrbinoCategoriesDelete(int $categoryId): Response
    {
        $category = $this->urbinoCourseCategoryRepository->findOneBy(["id" => $categoryId]);

        if (!$category) {
            return $this->json([
                "message" => "Categoria non trovata",
            ], 404);
        }

        $this->urbinoCourseCategoryService->delete($category);

        return $this->json([
            "message" => "Categoria eliminata",
        ]);
    }

    #[Route(path: '/reorder', name: 'adminUrbinoCategoriesReorder', methods: ['POST'], format: 'json')]
    public function adminUrbinoCategoriesReorder(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $diffCategories = $data['diff_categories'] ?? [];

        $this->urbinoCourseCategoryService->updateOrdering($diffCategories);

        return $this->json([
            "message" => "Ordinamento salvato",
        ]);
    }
}
