<?php

namespace App\Entity;

use App\Repository\NewsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NewsRepository::class)]
class News
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title_it = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $title_en = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $body_it = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $body_en = null;

    #[ORM\Column]
    private ?\DateTime $created_at = null;

    #[ORM\Column]
    private ?\DateTime $updated_at = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\Column]
    private ?bool $has_cover_image = null;

    #[ORM\Column]
    private bool $has_poster = false;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $event_datetime = null;

    #[ORM\Column]
    private ?bool $is_public = null;

    #[ORM\Column]
    private ?bool $is_deleted = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: true)]
    private ?NewsCategory $news_category = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitleIt(): ?string
    {
        return $this->title_it;
    }

    public function setTitleIt(string $title_it): static
    {
        $this->title_it = $title_it;

        return $this;
    }

    public function getTitleEn(): ?string
    {
        return $this->title_en;
    }

    public function setTitleEn(?string $title_en): static
    {
        $this->title_en = $title_en;

        return $this;
    }

    public function getBodyIt(): ?string
    {
        return $this->body_it;
    }

    public function setBodyIt(string $body_it): static
    {
        $this->body_it = $body_it;

        return $this;
    }

    public function getBodyEn(): ?string
    {
        return $this->body_en;
    }

    public function setBodyEn(?string $body_en): static
    {
        $this->body_en = $body_en;

        return $this;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTime $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTime $updated_at): static
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function hasCoverImage(): ?bool
    {
        return $this->has_cover_image;
    }

    public function setHasCoverImage(bool $has_cover_image): static
    {
        $this->has_cover_image = $has_cover_image;

        return $this;
    }

    public function hasPoster(): bool
    {
        return $this->has_poster;
    }

    public function setHasPoster(bool $has_poster): static
    {
        $this->has_poster = $has_poster;

        return $this;
    }

    public function getEventDatetime(): ?\DateTime
    {
        return $this->event_datetime;
    }

    public function setEventDatetime(?\DateTime $event_datetime): static
    {
        $this->event_datetime = $event_datetime;

        return $this;
    }

    public function isPublic(): ?bool
    {
        return $this->is_public;
    }

    public function setIsPublic(bool $is_public): static
    {
        $this->is_public = $is_public;

        return $this;
    }

    public function isDeleted(): ?bool
    {
        return $this->is_deleted;
    }

    public function setIsDeleted(bool $is_deleted): static
    {
        $this->is_deleted = $is_deleted;

        return $this;
    }

    public function getNewsCategory(): ?NewsCategory
    {
        return $this->news_category;
    }

    public function setNewsCategory(?NewsCategory $news_category): static
    {
        $this->news_category = $news_category;

        return $this;
    }
}
