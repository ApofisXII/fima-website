<?php

namespace App\Service;

use App\DTO\Admin\UrbinoEditionRequestDTO;
use App\Entity\UrbinoEdition;
use App\Repository\UrbinoEditionRepository;

final readonly class UrbinoEditionService
{

    public function __construct(
        private UrbinoEditionRepository $urbinoEditionRepository,
    ) {}

    public function create(UrbinoEditionRequestDTO $payload): UrbinoEdition
    {
        $edition = (new UrbinoEdition())
            ->setEditionName($payload->editionName)
            ->setYear($payload->year)
            ->setPeriodDescription($payload->periodDescription)
            ->setIsPublicVisible($payload->isPublicVisible ?? false)
            ->setIsDeleted(false)
            ->setCreatedAt(new \DateTime())
            ->setUpdatedAt(new \DateTime());

        return $this->urbinoEditionRepository->save($edition);
    }

    public function update(UrbinoEdition $edition, UrbinoEditionRequestDTO $payload): UrbinoEdition
    {
        $edition
            ->setEditionName($payload->editionName)
            ->setYear($payload->year)
            ->setPeriodDescription($payload->periodDescription)
            ->setIsPublicVisible($payload->isPublicVisible ?? false)
            ->setUpdatedAt(new \DateTime());

        return $this->urbinoEditionRepository->save($edition);
    }

    public function softDelete(UrbinoEdition $edition): UrbinoEdition
    {
        $edition->setIsDeleted(true);
        $edition->setUpdatedAt(new \DateTime());

        return $this->urbinoEditionRepository->save($edition);
    }

}
