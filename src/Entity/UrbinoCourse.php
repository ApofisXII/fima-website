<?php

namespace App\Entity;

use App\Repository\UrbinoCourseRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UrbinoCourseRepository::class)]
class UrbinoCourse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $teacher_full_name = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?UrbinoEdition $urbino_edition = null;

    #[ORM\Column]
    private ?bool $is_preselection_required = null;

    #[ORM\Column]
    private ?bool $is_sold_out = null;

    #[ORM\Column]
    private ?\DateTime $created_at = null;

    #[ORM\Column]
    private ?\DateTime $updated_at = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $subject_it = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $subject_en = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $program_description_it = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $program_description_en = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $bio_description_it = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $bio_description_en = null;

    #[ORM\Column(nullable: true)]
    private ?int $ordering = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\Column]
    private ?bool $is_image_uploaded = null;

    #[ORM\Column]
    private ?bool $is_afternoon_course = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTeacherFullName(): ?string
    {
        return $this->teacher_full_name;
    }

    public function setTeacherFullName(string $teacher_full_name): static
    {
        $this->teacher_full_name = $teacher_full_name;

        return $this;
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

    public function isPreselectionRequired(): ?bool
    {
        return $this->is_preselection_required;
    }

    public function setIsPreselectionRequired(bool $is_preselection_required): static
    {
        $this->is_preselection_required = $is_preselection_required;

        return $this;
    }

    public function isSoldOut(): ?bool
    {
        return $this->is_sold_out;
    }

    public function setIsSoldOut(bool $is_sold_out): static
    {
        $this->is_sold_out = $is_sold_out;

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

    public function getSubjectIt(): ?string
    {
        return $this->subject_it;
    }

    public function setSubjectIt(?string $subject_it): static
    {
        $this->subject_it = $subject_it;

        return $this;
    }

    public function getSubjectEn(): ?string
    {
        return $this->subject_en;
    }

    public function setSubjectEn(?string $subject_en): static
    {
        $this->subject_en = $subject_en;

        return $this;
    }

    public function getProgramDescriptionIt(): ?string
    {
        return $this->program_description_it;
    }

    public function setProgramDescriptionIt(?string $program_description_it): static
    {
        $this->program_description_it = $program_description_it;

        return $this;
    }

    public function getProgramDescriptionEn(): ?string
    {
        return $this->program_description_en;
    }

    public function setProgramDescriptionEn(?string $program_description_en): static
    {
        $this->program_description_en = $program_description_en;

        return $this;
    }

    public function getBioDescriptionIt(): ?string
    {
        return $this->bio_description_it;
    }

    public function setBioDescriptionIt(?string $bio_description_it): static
    {
        $this->bio_description_it = $bio_description_it;

        return $this;
    }

    public function getBioDescriptionEn(): ?string
    {
        return $this->bio_description_en;
    }

    public function setBioDescriptionEn(?string $bio_description_en): static
    {
        $this->bio_description_en = $bio_description_en;

        return $this;
    }

    public function getOrdering(): ?int
    {
        return $this->ordering;
    }

    public function setOrdering(?int $ordering): static
    {
        $this->ordering = $ordering;

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

    public function isImageUploaded(): ?bool
    {
        return $this->is_image_uploaded;
    }

    public function setIsImageUploaded(bool $is_image_uploaded): static
    {
        $this->is_image_uploaded = $is_image_uploaded;

        return $this;
    }

    public function isAfternoonCourse(): ?bool
    {
        return $this->is_afternoon_course;
    }

    public function setIsAfternoonCourse(bool $is_afternoon_course): static
    {
        $this->is_afternoon_course = $is_afternoon_course;

        return $this;
    }
}
