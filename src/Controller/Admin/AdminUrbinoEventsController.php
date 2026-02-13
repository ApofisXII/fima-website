<?php

namespace App\Controller\Admin;

use App\DTO\Admin\DataTableRequestDTO;
use App\DTO\Admin\UrbinoEventRequestDTO;
use App\Repository\UrbinoEditionRepository;
use App\Repository\UrbinoEventRepository;
use App\Service\UrbinoEventService;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/admin/urbino/events')]
class AdminUrbinoEventsController extends AbstractController
{
    public function __construct(
        private readonly UrbinoEventRepository $urbinoEventRepository,
        private readonly UrbinoEditionRepository $urbinoEditionRepository,
        private readonly UrbinoEventService $urbinoEventService,
    ) {}

    #[Route(path: '', name: 'adminUrbinoEventsList')]
    public function adminUrbinoEventsList(): Response
    {
        return $this->render('admin/urbino-events-list.html.twig');
    }

    #[Route(path: '/json', name: 'adminUrbinoEventsListJson')]
    public function adminUrbinoEventsListJson(#[MapQueryString] DataTableRequestDTO $payload): Response
    {
        $qb = $this->urbinoEventRepository->createQueryBuilder("e")
            ->leftJoin("e.urbino_edition", "ed")
            ->orderBy("e.event_datetime", "desc");

        if ($payload->search["value"]) {
            $search = $payload->search["value"];
            $qb->andWhere("e.title LIKE :search OR e.subtitle_it LIKE :search OR e.subtitle_en LIKE :search OR ed.edition_name LIKE :search")
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
                "title" => $item->getTitle(),
                "editionName" => $item->getUrbinoEdition()?->getEditionName(),
                "eventDatetime" => $item->getEventDatetime()->format("d/m/Y H:i"),
                "isPublic" => $item->isPublic(),
                "createdAt" => $item->getCreatedAt()->format("d/m/Y \\a\\l\\l\\e H:i"),
                "eventDetailLink" => $this->generateUrl("adminUrbinoEventsDetail", ["eventId" => $item->getId()]),
            ];
        }, (array) $pagerfanta->getCurrentPageResults());

        return $this->json([
            "data" => $list,
            "draw" => $payload->draw,
            "recordsFiltered" => $pagerfanta->getNbResults(),
            "recordsTotal" => $pagerfanta->getNbResults(),
        ]);
    }

    #[Route(path: '/detail', name: 'adminUrbinoEventsDetail', methods: ['GET'])]
    public function adminUrbinoEventsDetail(Request $request): Response
    {
        $event = $this->urbinoEventRepository->findOneBy(["id" => $request->query->get("eventId")]);
        $editions = $this->urbinoEditionRepository->findAll();

        return $this->render('admin/urbino-events-detail.html.twig', [
            "event" => $event,
            "editions" => $editions,
        ]);
    }

    #[Route(path: '/detail', name: 'adminUrbinoEventsDetailSave', methods: ['POST'], format: 'json')]
    public function adminUrbinoEventsDetailSave(#[MapRequestPayload] UrbinoEventRequestDTO $payload): Response
    {
        $event = $this->urbinoEventRepository->findOneBy(["id" => $payload->eventId]);

        if (null === $event) {
            $event = $this->urbinoEventService->create($payload);
        } else {
            $event = $this->urbinoEventService->update($event, $payload);
        }

        return $this->json([
            "message" => "Dati salvati",
        ]);
    }

    #[Route(path: '/delete/{eventId}', name: 'adminUrbinoEventsDelete', methods: ['DELETE'], format: 'json')]
    public function adminUrbinoEventsDelete(int $eventId): Response
    {
        $event = $this->urbinoEventRepository->findOneBy(["id" => $eventId]);

        if (!$event) {
            return $this->json([
                "message" => "Evento non trovato",
            ], 404);
        }

        $this->urbinoEventService->softDelete($event);

        return $this->json([
            "message" => "Evento eliminato",
        ]);
    }
}
