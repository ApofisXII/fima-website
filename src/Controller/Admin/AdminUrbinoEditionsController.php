<?php

namespace App\Controller\Admin;

use App\DTO\Admin\DataTableRequestDTO;
use App\DTO\Admin\UrbinoEditionRequestDTO;
use App\Repository\UrbinoEditionRepository;
use App\Service\UrbinoEditionService;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/admin/urbino-editions')]
class AdminUrbinoEditionsController extends AbstractController
{
    public function __construct(
        private readonly UrbinoEditionRepository $urbinoEditionRepository,
        private readonly UrbinoEditionService    $urbinoEditionService,
    ) {}

    #[Route(path: '', name: 'adminUrbinoEditionsList')]
    public function adminUrbinoEditionsList(): Response
    {
        return $this->render('admin/urbino-editions-list.html.twig');
    }

    #[Route(path: '/json', name: 'adminUrbinoEditionsListJson')]
    public function adminUrbinoEditionsListJson(#[MapQueryString] DataTableRequestDTO $payload): Response
    {
        $qb = $this->urbinoEditionRepository->createQueryBuilder("e")
            ->andWhere("e.is_deleted = false")
            ->orderBy("e.year", "desc");

        if ($payload->search["value"]) {
            $search = $payload->search["value"];
            $qb->andWhere("e.edition_name LIKE :search OR e.period_description LIKE :search OR e.year LIKE :search")
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
                "editionName" => $item->getEditionName(),
                "year" => $item->getYear(),
                "isPublicVisible" => $item->isPublicVisible(),
                "createdAt" => $item->getCreatedAt()->format("d/m/Y H:i"),
                "editionDetailLink" => $this->generateUrl("adminUrbinoEditionsDetail", ["editionId" => $item->getId()]),
            ];
        }, (array) $pagerfanta->getCurrentPageResults());

        return $this->json([
            "data" => $list,
            "draw" => $payload->draw,
            "recordsFiltered" => $pagerfanta->getNbResults(),
            "recordsTotal" => $pagerfanta->getNbResults(),
        ]);
    }

    #[Route(path: '/detail', name: 'adminUrbinoEditionsDetail', methods: ['GET'])]
    public function adminUrbinoEditionsDetail(Request $request): Response
    {
        $edition = $this->urbinoEditionRepository->findOneBy(["id" => $request->query->get("editionId")]);

        return $this->render('admin/urbino-editions-detail.html.twig', [
            "edition" => $edition,
        ]);
    }

    #[Route(path: '/detail', name: 'adminUrbinoEditionsDetailSave', methods: ['POST'], format: 'json')]
    public function adminUrbinoEditionsDetailSave(#[MapRequestPayload] UrbinoEditionRequestDTO $payload): Response
    {
        $edition = $this->urbinoEditionRepository->findOneBy(["id" => $payload->editionId]);

        if (null === $edition) {
            $this->urbinoEditionService->create($payload);
        } else {
            $this->urbinoEditionService->update($edition, $payload);
        }

        return $this->json([
            "message" => "Dati salvati",
        ]);
    }

}
