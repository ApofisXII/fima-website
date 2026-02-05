<?php

namespace App\Service;

use App\DTO\Admin\NewsRequestDTO;
use App\Entity\News;
use App\Repository\NewsRepository;
use Symfony\Component\String\Slugger\SluggerInterface;

final readonly class NewsService
{

    public function __construct(
        private NewsRepository $newsRepository,
        private SluggerInterface $slugger,
    ) {}

    public function create(NewsRequestDTO $payload): News
    {
        $news = (new News())
            ->setTitleIt($payload->titleIt)
            ->setTitleEn($payload->titleEn)
            ->setBodyIt($payload->bodyIt)
            ->setBodyEn($payload->bodyEn)
            ->setCreatedAt(new \DateTime())
            ->setSlug($this->slugger->slug($payload->titleIt))
            ->setHasCoverImage(false)
            ->setIsEvent(false);

        return $this->newsRepository->save($news);
    }

    public function update(News $news, NewsRequestDTO $payload): News
    {
        $news
            ->setTitleIt($payload->titleIt)
            ->setTitleEn($payload->titleEn)
            ->setBodyIt($payload->bodyIt)
            ->setBodyEn($payload->bodyEn)
            ->setSlug($this->slugger->slug($payload->titleIt));

        return $this->newsRepository->save($news);
    }

}
