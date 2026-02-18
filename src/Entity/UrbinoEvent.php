<?php

namespace App\Entity;

use App\Repository\UrbinoEventRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UrbinoEventRepository::class)]
class UrbinoEvent
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?UrbinoEdition $urbino_edition = null;

    #[ORM\Column]
    private ?\DateTime $event_datetime = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $subtitle_it = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $subtitle_en = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $location_short = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $location_long = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ticket_link = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description_it = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description_en = null;

    #[ORM\Column]
    private ?\DateTime $created_at = null;

    #[ORM\Column]
    private ?\DateTime $updated_at = null;

    #[ORM\Column]
    private ?bool $is_deleted = null;

    #[ORM\Column]
    private ?bool $is_public = null;

    #[ORM\Column]
    private ?bool $has_cover_image = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrbinoEdition(): ?UrbinoEdition
    {
        return $this->urbino_edition;
    }

    public function setUrbinoEdition(?UrbinoEdition $urbino_edition): static
    {
        $this->urbino_edition = $urbino_edition;

        return $this;
    }

    public function getEventDatetime(): ?\DateTime
    {
        return $this->event_datetime;
    }

    public function setEventDatetime(\DateTime $event_datetime): static
    {
        $this->event_datetime = $event_datetime;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getSubtitleIt(): ?string
    {
        return $this->subtitle_it;
    }

    public function setSubtitleIt(string $subtitle_it): static
    {
        $this->subtitle_it = $subtitle_it;

        return $this;
    }

    public function getSubtitleEn(): ?string
    {
        return $this->subtitle_en;
    }

    public function setSubtitleEn(?string $subtitle_en): static
    {
        $this->subtitle_en = $subtitle_en;

        return $this;
    }

    public function getLocationShort(): ?string
    {
        return $this->location_short;
    }

    public function setLocationShort(?string $location_short): static
    {
        $this->location_short = $location_short;

        return $this;
    }

    public function getLocationLong(): ?string
    {
        return $this->location_long;
    }

    public function setLocationLong(?string $location_long): static
    {
        $this->location_long = $location_long;

        return $this;
    }

    public function getTicketLink(): ?string
    {
        return $this->ticket_link;
    }

    public function setTicketLink(?string $ticket_link): static
    {
        $this->ticket_link = $ticket_link;

        return $this;
    }

    public function getDescriptionIt(): ?string
    {
        return $this->description_it;
    }

    public function setDescriptionIt(?string $description_it): static
    {
        $this->description_it = $description_it;

        return $this;
    }

    public function getDescriptionEn(): ?string
    {
        return $this->description_en;
    }

    public function setDescriptionEn(?string $description_en): static
    {
        $this->description_en = $description_en;

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

    public function isDeleted(): ?bool
    {
        return $this->is_deleted;
    }

    public function setIsDeleted(bool $is_deleted): static
    {
        $this->is_deleted = $is_deleted;

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

    public function hasCoverImage(): ?bool
    {
        return $this->has_cover_image;
    }

    public function setHasCoverImage(bool $has_cover_image): static
    {
        $this->has_cover_image = $has_cover_image;

        return $this;
    }
}
