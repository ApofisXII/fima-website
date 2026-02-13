<?php

namespace App\Service;

use App\DTO\Admin\UrbinoEventRequestDTO;
use App\Entity\UrbinoEvent;
use App\Repository\UrbinoEditionRepository;
use App\Repository\UrbinoEventRepository;

class UrbinoEventService
{
    public function __construct(
        private readonly UrbinoEventRepository $urbinoEventRepository,
        private readonly UrbinoEditionRepository $urbinoEditionRepository,
    ) {}

    public function create(UrbinoEventRequestDTO $dto): UrbinoEvent
    {
        $event = new UrbinoEvent();
        $event->setCreatedAt(new \DateTime());
        return $this->update($event, $dto);
    }

    public function update(UrbinoEvent $event, UrbinoEventRequestDTO $dto): UrbinoEvent
    {
        $edition = $this->urbinoEditionRepository->find($dto->urbinoEditionId);
        if (!$edition) {
            throw new \Exception("Edizione non trovata");
        }

        $event->setUrbinoEdition($edition);
        $event->setEventDatetime(new \DateTime($dto->eventDatetime));
        $event->setTitle($dto->title);
        $event->setSubtitleIt($dto->subtitleIt);
        $event->setSubtitleEn($dto->subtitleEn);
        $event->setLocationShort($dto->locationShort);
        $event->setLocationLong($dto->locationLong);
        $event->setTicketLink($dto->ticketLink);
        $event->setDescriptionIt($dto->descriptionIt);
        $event->setDescriptionEn($dto->descriptionEn);
        $event->setIsPublic($dto->isPublic ?? false);
        $event->setIsDeleted($dto->isDeleted ?? false);
        $event->setUpdatedAt(new \DateTime());

        return $this->urbinoEventRepository->save($event);
    }

    public function delete(UrbinoEvent $event): UrbinoEvent
    {
        $event->setIsDeleted(true);
        $event->setUpdatedAt(new \DateTime());

        return $this->urbinoEventRepository->save($event);
    }
}
