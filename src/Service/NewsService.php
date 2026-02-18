<?php

namespace App\Service;

use App\DTO\Admin\NewsRequestDTO;
use App\Entity\News;
use App\Repository\NewsRepository;
use App\Utils\ImageUtils;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

final readonly class NewsService
{

    public function __construct(
        private NewsRepository $newsRepository,
        private SluggerInterface $slugger,
        private ParameterBagInterface $parameterBag,
        private Filesystem $filesystem,
        private ImageUtils $imageUtils,
    ) {}

    public function create(NewsRequestDTO $payload): News
    {
        $news = (new News())
            ->setTitleIt($payload->titleIt)
            ->setTitleEn($payload->titleEn)
            ->setBodyIt($payload->bodyIt)
            ->setBodyEn($payload->bodyEn)
            ->setCreatedAt(new \DateTime())
            ->setSlug($this->slugger->slug(strtolower($payload->titleIt)))
            ->setHasCoverImage(false)
            ->setIsPublic($payload->isPublic ?? false)
            ->setIsEvent($payload->isEvent ?? false)
            ->setEventDatetime($payload->eventDatetime ? new \DateTime($payload->eventDatetime) : null);

        return $this->newsRepository->save($news);
    }

    public function update(News $news, NewsRequestDTO $payload): News
    {
        $news
            ->setTitleIt($payload->titleIt)
            ->setTitleEn($payload->titleEn)
            ->setBodyIt($payload->bodyIt)
            ->setBodyEn($payload->bodyEn)
            ->setSlug($this->slugger->slug(strtolower($payload->titleIt)))
            ->setIsPublic($payload->isPublic ?? false)
            ->setIsEvent($payload->isEvent ?? false)
            ->setEventDatetime($payload->eventDatetime ? new \DateTime($payload->eventDatetime) : null);

        return $this->newsRepository->save($news);
    }

    public function saveCoverImage(News $news, UploadedFile $uploadedFile): News
    {
        $serverPath = $this->parameterBag->get('kernel.project_dir') . '/public/uploads-news/';
        $imageName = $news->getId() . '.webp';
        $imagePath = $serverPath . $imageName;

        $uploadedFile->move($serverPath, $imageName);
        $this->imageUtils->compressImage($imagePath);

        $news->setHasCoverImage(true);
        $this->newsRepository->save($news);

        return $news;
    }

    public function deleteCoverImage(News $news): News
    {
        $serverPath = $this->parameterBag->get('kernel.project_dir') . '/public/uploads-news/';
        $imageName = $news->getId() . '.webp';
        $imagePath = $serverPath . $imageName;

        if ($this->filesystem->exists($imagePath)) {
            $this->filesystem->remove($imagePath);
        }

        $news->setHasCoverImage(false);
        $this->newsRepository->save($news);

        return $news;
    }

}
