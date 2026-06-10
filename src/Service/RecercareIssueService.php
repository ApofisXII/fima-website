<?php

namespace App\Service;

use App\DTO\Admin\RecercareIssueRequestDTO;
use App\Entity\RecercareIssue;
use App\Repository\RecercareIssueRepository;
use App\Utils\ImageUtils;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final readonly class RecercareIssueService
{
    public function __construct(
        private RecercareIssueRepository $recercareIssueRepository,
        private ParameterBagInterface    $parameterBag,
        private Filesystem               $filesystem,
        private ImageUtils               $imageUtils,
    ) {}

    public function create(RecercareIssueRequestDTO $payload): RecercareIssue
    {
        $issue = (new RecercareIssue())
            ->setVolume($payload->volume)
            ->setYear($payload->year)
            ->setIsbn($payload->isbn)
            ->setNote($payload->note)
            ->setIsPublic($payload->isPublic ?? false)
            ->setHasSummary($payload->hasSummary ?? false)
            ->setHasCover(false)
            ->setHasIndexIt(false)
            ->setHasIndexEn(false)
            ->setIsDeleted(false)
            ->setCreatedAt(new \DateTime())
            ->setUpdatedAt(new \DateTime());

        return $this->recercareIssueRepository->save($issue);
    }

    public function update(RecercareIssue $issue, RecercareIssueRequestDTO $payload): RecercareIssue
    {
        $issue
            ->setVolume($payload->volume)
            ->setYear($payload->year)
            ->setIsbn($payload->isbn)
            ->setNote($payload->note)
            ->setIsPublic($payload->isPublic ?? false)
            ->setHasSummary($payload->hasSummary ?? false);

        return $this->recercareIssueRepository->save($issue);
    }

    public function delete(RecercareIssue $issue): RecercareIssue
    {
        $coversPath = $this->parameterBag->get('kernel.project_dir') . '/public/uploads-recercare/covers/';
        $indexesPath = $this->parameterBag->get('kernel.project_dir') . '/public/uploads-recercare/indexes/';

        foreach ([
            $coversPath . $issue->getId() . '.webp',
            $indexesPath . $issue->getId() . '_it.pdf',
            $indexesPath . $issue->getId() . '_en.pdf',
        ] as $path) {
            if ($this->filesystem->exists($path)) {
                $this->filesystem->remove($path);
            }
        }

        $issue->setIsDeleted(true);

        return $this->recercareIssueRepository->save($issue);
    }

    public function saveCover(RecercareIssue $issue, UploadedFile $uploadedFile): RecercareIssue
    {
        $serverPath = $this->parameterBag->get('kernel.project_dir') . '/public/uploads-recercare/covers/';
        $imageName = $issue->getId() . '.webp';
        $imagePath = $serverPath . $imageName;

        $uploadedFile->move($serverPath, $imageName);
        $this->imageUtils->compressImage($imagePath);

        $issue->setHasCover(true);

        return $this->recercareIssueRepository->save($issue);
    }

    public function deleteCover(RecercareIssue $issue): RecercareIssue
    {
        $serverPath = $this->parameterBag->get('kernel.project_dir') . '/public/uploads-recercare/covers/';
        $imagePath = $serverPath . $issue->getId() . '.webp';

        if ($this->filesystem->exists($imagePath)) {
            $this->filesystem->remove($imagePath);
        }

        $issue->setHasCover(false);

        return $this->recercareIssueRepository->save($issue);
    }

    public function saveIndexIt(RecercareIssue $issue, UploadedFile $uploadedFile): RecercareIssue
    {
        $serverPath = $this->parameterBag->get('kernel.project_dir') . '/public/uploads-recercare/indexes/';
        $fileName = $issue->getId() . '_it.pdf';

        $uploadedFile->move($serverPath, $fileName);

        $issue->setHasIndexIt(true);

        return $this->recercareIssueRepository->save($issue);
    }

    public function deleteIndexIt(RecercareIssue $issue): RecercareIssue
    {
        $serverPath = $this->parameterBag->get('kernel.project_dir') . '/public/uploads-recercare/indexes/';
        $filePath = $serverPath . $issue->getId() . '_it.pdf';

        if ($this->filesystem->exists($filePath)) {
            $this->filesystem->remove($filePath);
        }

        $issue->setHasIndexIt(false);

        return $this->recercareIssueRepository->save($issue);
    }

    public function saveIndexEn(RecercareIssue $issue, UploadedFile $uploadedFile): RecercareIssue
    {
        $serverPath = $this->parameterBag->get('kernel.project_dir') . '/public/uploads-recercare/indexes/';
        $fileName = $issue->getId() . '_en.pdf';

        $uploadedFile->move($serverPath, $fileName);

        $issue->setHasIndexEn(true);

        return $this->recercareIssueRepository->save($issue);
    }

    public function deleteIndexEn(RecercareIssue $issue): RecercareIssue
    {
        $serverPath = $this->parameterBag->get('kernel.project_dir') . '/public/uploads-recercare/indexes/';
        $filePath = $serverPath . $issue->getId() . '_en.pdf';

        if ($this->filesystem->exists($filePath)) {
            $this->filesystem->remove($filePath);
        }

        $issue->setHasIndexEn(false);

        return $this->recercareIssueRepository->save($issue);
    }
}
