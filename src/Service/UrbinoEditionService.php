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
            ->setDateStart(new \DateTime($payload->dateStart))
            ->setDateEnd(new \DateTime($payload->dateEnd))
            ->setIsPublicVisible($payload->isPublicVisible ?? false)
            ->setEnrollmentInfoIt($payload->enrollmentInfoIt)
            ->setEnrollmentInfoEn($payload->enrollmentInfoEn)
            ->setIsDeleted(false)
            ->setCreatedAt(new \DateTime())
            ->setUpdatedAt(new \DateTime());

        return $this->urbinoEditionRepository->save($edition);
    }

    public function update(UrbinoEdition $edition, UrbinoEditionRequestDTO $payload): UrbinoEdition
    {
        $edition
            ->setEditionName($payload->editionName)
            ->setDateStart(new \DateTime($payload->dateStart))
            ->setDateEnd(new \DateTime($payload->dateEnd))
            ->setIsPublicVisible($payload->isPublicVisible ?? false)
            ->setEnrollmentInfoIt($payload->enrollmentInfoIt)
            ->setEnrollmentInfoEn($payload->enrollmentInfoEn)
            ->setUpdatedAt(new \DateTime());

        return $this->urbinoEditionRepository->save($edition);
    }

    public function delete(UrbinoEdition $edition): UrbinoEdition
    {
        $edition->setIsDeleted(true);
        $edition->setUpdatedAt(new \DateTime());

        return $this->urbinoEditionRepository->save($edition);
    }

}
