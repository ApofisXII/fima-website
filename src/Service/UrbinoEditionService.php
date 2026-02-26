<?php

namespace App\Service;

use App\DTO\Admin\UrbinoEditionRequestDTO;
use App\Entity\UrbinoEdition;
use App\Repository\UrbinoEditionRepository;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final readonly class UrbinoEditionService
{

    public function __construct(
        private UrbinoEditionRepository $urbinoEditionRepository,
        private ParameterBagInterface   $parameterBag,
        private Filesystem              $filesystem,
    ) {}

    public function create(UrbinoEditionRequestDTO $payload): UrbinoEdition
    {
        $edition = new UrbinoEdition()
            ->setEditionName($payload->editionName)
            ->setDateStart(new \DateTime($payload->dateStart))
            ->setDateEnd(new \DateTime($payload->dateEnd))
            ->setIsPublicVisible($payload->isPublicVisible ?? false)
            ->setEnrollmentInfoIt($payload->enrollmentInfoIt)
            ->setEnrollmentInfoEn($payload->enrollmentInfoEn)
            ->setEnrollmentLink($payload->enrollmentLink ?: null)
            ->setEnrollmentDeadline($payload->enrollmentDeadline ? new \DateTime($payload->enrollmentDeadline) : null)
            ->setIsProgrammePdfUploaded(false)
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
            ->setEnrollmentLink($payload->enrollmentLink ?: null)
            ->setEnrollmentDeadline($payload->enrollmentDeadline ? new \DateTime($payload->enrollmentDeadline) : null)
            ->setUpdatedAt(new \DateTime());

        return $this->urbinoEditionRepository->save($edition);
    }

    public function delete(UrbinoEdition $edition): UrbinoEdition
    {
        $edition->setIsDeleted(true);
        $edition->setUpdatedAt(new \DateTime());

        return $this->urbinoEditionRepository->save($edition);
    }

    public function saveProgrammePdf(UrbinoEdition $edition, UploadedFile $uploadedFile): UrbinoEdition
    {
        $serverPath = $this->parameterBag->get('kernel.project_dir') . '/public/uploads-uma-edition/';
        $fileName = $edition->getId() . '.pdf';

        $uploadedFile->move($serverPath, $fileName);

        $edition->setIsProgrammePdfUploaded(true);
        $edition->setUpdatedAt(new \DateTime());

        return $this->urbinoEditionRepository->save($edition);
    }

    public function deleteProgrammePdf(UrbinoEdition $edition): UrbinoEdition
    {
        $serverPath = $this->parameterBag->get('kernel.project_dir') . '/public/uploads-uma-edition/';
        $filePath = $serverPath . $edition->getId() . '.pdf';

        if ($this->filesystem->exists($filePath)) {
            $this->filesystem->remove($filePath);
        }

        $edition->setIsProgrammePdfUploaded(false);
        $edition->setUpdatedAt(new \DateTime());

        return $this->urbinoEditionRepository->save($edition);
    }

}
