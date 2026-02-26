<?php

namespace App\Entity;

use App\Repository\UrbinoEditionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UrbinoEditionRepository::class)]
class UrbinoEdition
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $edition_name = null;

    #[ORM\Column]
    private ?\DateTime $date_start = null;

    #[ORM\Column]
    private ?\DateTime $date_end = null;

    #[ORM\Column]
    private ?\DateTime $created_at = null;

    #[ORM\Column]
    private ?\DateTime $updated_at = null;

    #[ORM\Column]
    private ?bool $is_deleted = null;

    #[ORM\Column]
    private ?bool $is_public_visible = null;

    #[ORM\Column]
    private bool $is_programme_pdf_uploaded = false;

    #[ORM\Column(length: 2048, nullable: true)]
    private ?string $enrollment_link = null;

    #[ORM\Column(nullable: true)]
    private ?int $enrollment_price_cents = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $enrollment_deadline = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $enrollment_info_it = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $enrollment_info_en = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEditionName(): ?string
    {
        return $this->edition_name;
    }

    public function setEditionName(string $edition_name): static
    {
        $this->edition_name = $edition_name;

        return $this;
    }

    public function getDateStart(): ?\DateTime
    {
        return $this->date_start;
    }

    public function setDateStart(\DateTime $date_start): static
    {
        $this->date_start = $date_start;

        return $this;
    }

    public function getDateEnd(): ?\DateTime
    {
        return $this->date_end;
    }

    public function setDateEnd(\DateTime $date_end): static
    {
        $this->date_end = $date_end;

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

    public function isPublicVisible(): ?bool
    {
        return $this->is_public_visible;
    }

    public function setIsPublicVisible(bool $is_public_visible): static
    {
        $this->is_public_visible = $is_public_visible;

        return $this;
    }

    public function getEnrollmentLink(): ?string
    {
        return $this->enrollment_link;
    }

    public function setEnrollmentLink(?string $enrollment_link): static
    {
        $this->enrollment_link = $enrollment_link;

        return $this;
    }

    public function getEnrollmentPriceCents(): ?int
    {
        return $this->enrollment_price_cents;
    }

    public function setEnrollmentPriceCents(?int $enrollment_price_cents): static
    {
        $this->enrollment_price_cents = $enrollment_price_cents;

        return $this;
    }

    public function getEnrollmentPriceFormatted(): ?string
    {
        if ($this->enrollment_price_cents === null) {
            return null;
        }

        return number_format($this->enrollment_price_cents / 100, 2, ',', '.') . ' €';
    }

    public function getEnrollmentDeadline(): ?\DateTime
    {
        return $this->enrollment_deadline;
    }

    public function setEnrollmentDeadline(?\DateTime $enrollment_deadline): static
    {
        $this->enrollment_deadline = $enrollment_deadline;

        return $this;
    }

    public function getEnrollmentInfoIt(): ?string
    {
        return $this->enrollment_info_it;
    }

    public function setEnrollmentInfoIt(string $enrollment_info_it): static
    {
        $this->enrollment_info_it = $enrollment_info_it;

        return $this;
    }

    public function getEnrollmentInfoEn(): ?string
    {
        return $this->enrollment_info_en;
    }

    public function setEnrollmentInfoEn(string $enrollment_info_en): static
    {
        $this->enrollment_info_en = $enrollment_info_en;

        return $this;
    }

    public function isProgrammePdfUploaded(): bool
    {
        return $this->is_programme_pdf_uploaded;
    }

    public function setIsProgrammePdfUploaded(bool $is_programme_pdf_uploaded): static
    {
        $this->is_programme_pdf_uploaded = $is_programme_pdf_uploaded;

        return $this;
    }

}
