<?php

namespace App\Entity;

use App\Repository\BulletinRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BulletinRepository::class)]
class Bulletin
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $number = null;

    #[ORM\Column]
    private ?int $year = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $editor = null;

    #[ORM\Column(length: 500, nullable: true)]
    private ?string $authors = null;

    #[ORM\Column]
    private bool $has_cover = false;

    #[ORM\Column]
    private ?bool $is_public = null;

    #[ORM\Column]
    private ?bool $is_deleted = null;

    #[ORM\Column]
    private ?\DateTime $created_at = null;

    #[ORM\Column]
    private ?\DateTime $updated_at = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): static
    {
        $this->number = $number;

        return $this;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(int $year): static
    {
        $this->year = $year;

        return $this;
    }

    public function getEditor(): ?string
    {
        return $this->editor;
    }

    public function setEditor(?string $editor): static
    {
        $this->editor = $editor;

        return $this;
    }

    public function getAuthors(): ?string
    {
        return $this->authors;
    }

    public function setAuthors(?string $authors): static
    {
        $this->authors = $authors;

        return $this;
    }

    public function hasCover(): bool
    {
        return $this->has_cover;
    }

    public function setHasCover(bool $has_cover): static
    {
        $this->has_cover = $has_cover;

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
}
