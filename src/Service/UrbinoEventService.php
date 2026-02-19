<?php

namespace App\Service;

use App\DTO\Admin\UrbinoEventRequestDTO;
use App\Entity\UrbinoEvent;
use App\Repository\UrbinoEditionRepository;
use App\Repository\UrbinoEventRepository;
use App\Utils\ImageUtils;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final readonly class UrbinoEventService
{
    public function __construct(
        private UrbinoEventRepository $urbinoEventRepository,
        private UrbinoEditionRepository $urbinoEditionRepository,
        private ParameterBagInterface $parameterBag,
        private Filesystem $filesystem,
        private ImageUtils $imageUtils,
    ) {}

    public function create(UrbinoEventRequestDTO $dto): UrbinoEvent
    {
        $event = new UrbinoEvent();
        $event->setCreatedAt(new \DateTime());
        $event->setHasCoverImage(false);
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

    public function saveCoverImage(UrbinoEvent $event, UploadedFile $uploadedFile): UrbinoEvent
    {
        $serverPath = $this->parameterBag->get('kernel.project_dir') . '/public/uploads-uma-event/';
        $imageName = $event->getId() . '.webp';
        $imagePath = $serverPath . $imageName;

        $uploadedFile->move($serverPath, $imageName);
        $this->imageUtils->compressImage($imagePath);

        $event->setHasCoverImage(true);
        $event->setUpdatedAt(new \DateTime());
        $this->urbinoEventRepository->save($event);

        return $event;
    }

    public function deleteCoverImage(UrbinoEvent $event): UrbinoEvent
    {
        $serverPath = $this->parameterBag->get('kernel.project_dir') . '/public/uploads-uma-event/';
        $imageName = $event->getId() . '.webp';
        $imagePath = $serverPath . $imageName;

        if ($this->filesystem->exists($imagePath)) {
            $this->filesystem->remove($imagePath);
        }

        $event->setHasCoverImage(false);
        $event->setUpdatedAt(new \DateTime());
        $this->urbinoEventRepository->save($event);

        return $event;
    }
}
