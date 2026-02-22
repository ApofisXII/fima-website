<?php

namespace App\Controller\Admin;

use App\DTO\Admin\DataTableRequestDTO;
use App\DTO\Admin\UrbinoEditionRequestDTO;
use App\Repository\UrbinoEditionRepository;
use App\Service\UrbinoEditionService;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Attribute\MapUploadedFile;
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
            ->orderBy("e.date_start", "desc");

        if ($payload->search["value"]) {
            $search = $payload->search["value"];
            $qb->andWhere("e.edition_name LIKE :search")
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
                "year" => $item->getDateStart()->format('Y'),
                "dateRange" => 'Dal ' . $item->getDateStart()->format('d/m') . ' al ' . $item->getDateEnd()->format('d/m'),
                "isPublicVisible" => $item->isPublicVisible(),
                "createdAt" => $item->getCreatedAt()->format("d/m/y \\a\\l\\l\\e H:i"),
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

    #[Route(path: '/delete/{editionId}', name: 'adminUrbinoEditionsDelete', methods: ['DELETE'], format: 'json')]
    public function adminUrbinoEditionsDelete(int $editionId): Response
    {
        $edition = $this->urbinoEditionRepository->findOneBy(["id" => $editionId]);

        if (!$edition) {
            return $this->json([
                "message" => "Edizione non trovata",
            ], 404);
        }

        $this->urbinoEditionService->delete($edition);

        return $this->json([
            "message" => "Edizione eliminata",
        ]);
    }

    #[Route(path: '/upload-programme-pdf/{editionId}', name: 'adminUrbinoEditionsUploadProgrammePdf', methods: ['POST'], format: 'json')]
    public function adminUrbinoEditionsUploadProgrammePdf(int $editionId, #[MapUploadedFile] ?UploadedFile $programmePdf): Response
    {
        $edition = $this->urbinoEditionRepository->find($editionId);

        if (!$programmePdf) {
            return $this->json(["message" => "Nessun file selezionato"], 400);
        }

        $this->urbinoEditionService->saveProgrammePdf($edition, $programmePdf);

        return $this->json(["message" => "PDF caricato"]);
    }

    #[Route(path: '/delete-programme-pdf/{editionId}', name: 'adminUrbinoEditionsDeleteProgrammePdf', methods: ['DELETE'], format: 'json')]
    public function adminUrbinoEditionsDeleteProgrammePdf(int $editionId): Response
    {
        $edition = $this->urbinoEditionRepository->find($editionId);

        $this->urbinoEditionService->deleteProgrammePdf($edition);

        return $this->json(["message" => "PDF eliminato"]);
    }

}
