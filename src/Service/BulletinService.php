<?php

namespace App\Service;

use App\DTO\Admin\BulletinRequestDTO;
use App\Entity\Bulletin;
use App\Repository\BulletinRepository;
use App\Utils\ImageUtils;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final readonly class BulletinService
{
    public function __construct(
        private BulletinRepository    $bulletinRepository,
        private ParameterBagInterface $parameterBag,
        private Filesystem            $filesystem,
        private ImageUtils            $imageUtils,
    ) {}

    public function create(BulletinRequestDTO $payload): Bulletin
    {
        $bulletin = (new Bulletin())
            ->setNumber($payload->number)
            ->setYear($payload->year)
            ->setEditor($payload->editor)
            ->setAuthors($payload->authors)
            ->setIsPublic($payload->isPublic ?? false)
            ->setHasCover(false)
            ->setIsDeleted(false)
            ->setCreatedAt(new \DateTime())
            ->setUpdatedAt(new \DateTime());

        return $this->bulletinRepository->save($bulletin);
    }

    public function update(Bulletin $bulletin, BulletinRequestDTO $payload): Bulletin
    {
        $bulletin
            ->setNumber($payload->number)
            ->setYear($payload->year)
            ->setEditor($payload->editor)
            ->setAuthors($payload->authors)
            ->setIsPublic($payload->isPublic ?? false);

        return $this->bulletinRepository->save($bulletin);
    }

    public function delete(Bulletin $bulletin): Bulletin
    {
        foreach ([$this->coverPath($bulletin), $this->pdfPath($bulletin)] as $path) {
            if ($this->filesystem->exists($path)) {
                $this->filesystem->remove($path);
            }
        }

        $bulletin->setIsDeleted(true);

        return $this->bulletinRepository->save($bulletin);
    }

    public function saveCover(Bulletin $bulletin, UploadedFile $uploadedFile): Bulletin
    {
        $uploadedFile->move($this->coversDir(), $bulletin->getId() . '.webp');
        $this->imageUtils->compressImage($this->coverPath($bulletin));

        $bulletin->setHasCover(true);

        return $this->bulletinRepository->save($bulletin);
    }

    public function deleteCover(Bulletin $bulletin): Bulletin
    {
        if ($this->filesystem->exists($this->coverPath($bulletin))) {
            $this->filesystem->remove($this->coverPath($bulletin));
        }

        $bulletin->setHasCover(false);

        return $this->bulletinRepository->save($bulletin);
    }

    public function savePdf(Bulletin $bulletin, UploadedFile $uploadedFile): Bulletin
    {
        $uploadedFile->move($this->pdfsDir(), $bulletin->getId() . '.pdf');

        return $this->bulletinRepository->save($bulletin);
    }

    public function deletePdf(Bulletin $bulletin): Bulletin
    {
        if ($this->filesystem->exists($this->pdfPath($bulletin))) {
            $this->filesystem->remove($this->pdfPath($bulletin));
        }

        return $this->bulletinRepository->save($bulletin);
    }

    private function coversDir(): string
    {
        return $this->parameterBag->get('kernel.project_dir') . '/public/uploads-bulletins/covers/';
    }

    private function pdfsDir(): string
    {
        return $this->parameterBag->get('kernel.project_dir') . '/public/uploads-bulletins/pdfs/';
    }

    private function coverPath(Bulletin $bulletin): string
    {
        return $this->coversDir() . $bulletin->getId() . '.webp';
    }

    private function pdfPath(Bulletin $bulletin): string
    {
        return $this->pdfsDir() . $bulletin->getId() . '.pdf';
    }
}
