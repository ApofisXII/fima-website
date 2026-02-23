<?php

namespace App\Controller\Public;

use App\Entity\UrbinoEvent;
use App\Repository\UrbinoEditionRepository;
use App\Repository\UrbinoEventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/{_locale}/urbino-musica-antica')]
final class UrbinoEventController extends AbstractController
{

    public function __construct(
        private readonly UrbinoEditionRepository $urbinoEditionRepository,
        private readonly UrbinoEventRepository $urbinoEventRepository,
    ) {}

    #[Route('/events-festival', name: 'urbinoEventsFestival')]
    public function urbinoEventsFestival(): Response
    {
        $currentEdition = $this->urbinoEditionRepository->findCurrentEdition();

        $events = $this->urbinoEventRepository->findBy([
            "urbino_edition" => $currentEdition,
            "category" => UrbinoEvent::EVENT_CATEGORY_FESTIVAL,
            "is_deleted" => false,
        ]);

        return $this->render('public/urbino-events-festival.html.twig', [
            "events" => $events,
            "currentEdition" => $currentEdition,
        ]);
    }

    #[Route('/events-exhibitions', name: 'urbinoEventsExhibitions')]
    public function urbinoEventsExhibitions(): Response
    {
        $currentEdition = $this->urbinoEditionRepository->findCurrentEdition();

        $events = $this->urbinoEventRepository->findBy([
            "urbino_edition" => $currentEdition,
            "category" => UrbinoEvent::EVENT_CATEGORY_EXHIBITION,
            "is_deleted" => false,
        ]);

        return $this->render('public/urbino-events-exhibitions.html.twig', [
            "events" => $events,
            "currentEdition" => $currentEdition,
        ]);
    }

    #[Route('/events-conferences', name: 'urbinoEventsConferences')]
    public function urbinoEventsConferences(): Response
    {
        $currentEdition = $this->urbinoEditionRepository->findCurrentEdition();

        $events = $this->urbinoEventRepository->findBy([
            "urbino_edition" => $currentEdition,
            "category" => UrbinoEvent::EVENT_CATEGORY_CONFERENCE,
            "is_deleted" => false,
        ]);

        return $this->render('public/urbino-events-conferences.html.twig', [
            "events" => $events,
            "currentEdition" => $currentEdition,
        ]);
    }

    #[Route('/events/{event}/{slug}', name: 'urbinoEventDetail')]
    public function urbinoEventDetail(UrbinoEvent $event, string $slug): Response
    {
        if ($event->isDeleted()) {
            throw $this->createNotFoundException();
        }

        // TODO Gestire lo slug, se è diverso da quello attuale reindirizzare alla pagina corretta

        return $this->render('public/urbino-event-detail.html.twig', [
            "event" => $event,
        ]);
    }

}
