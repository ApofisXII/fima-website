<?php

namespace App\Service;

use App\DTO\Admin\UrbinoCourseRequestDTO;
use App\Entity\UrbinoCourse;
use App\Repository\UrbinoCourseRepository;
use App\Repository\UrbinoEditionRepository;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class UrbinoCourseService
{
    public function __construct(
        private readonly UrbinoCourseRepository $urbinoCourseRepository,
        private readonly UrbinoEditionRepository $urbinoEditionRepository,
        private readonly SluggerInterface $slugger,
        private readonly ParameterBagInterface $parameterBag,
        private readonly Filesystem $filesystem,
    ) {}

    public function create(UrbinoCourseRequestDTO $dto): UrbinoCourse
    {
        $course = new UrbinoCourse();
        $course->setCreatedAt(new \DateTime());
        $course->setIsImageUploaded(false);
        return $this->update($course, $dto);
    }

    public function update(UrbinoCourse $course, UrbinoCourseRequestDTO $dto): UrbinoCourse
    {
        $edition = $this->urbinoEditionRepository->find($dto->urbinoEditionId);
        if (!$edition) {
            throw new \Exception("Edizione non trovata");
        }

        $course->setTeacherFullName($dto->teacherFullName);
        $course->setUrbinoEdition($edition);
        $course->setSubjectIt($dto->subjectIt);
        $course->setSubjectEn($dto->subjectEn);
        $course->setProgramDescriptionIt($dto->programDescriptionIt);
        $course->setProgramDescriptionEn($dto->programDescriptionEn);
        $course->setBioDescriptionIt($dto->bioDescriptionIt);
        $course->setBioDescriptionEn($dto->bioDescriptionEn);
        $course->setIsPreselectionRequired($dto->isPreselectionRequired ?? false);
        $course->setIsSoldOut($dto->isSoldOut ?? false);

        if (!$course->getSlug()) {
            $slugText = $dto->teacherFullName;
            if ($dto->subjectIt) {
                $slugText .= ' ' . $dto->subjectIt;
            }
            $course->setSlug($this->slugger->slug(strtolower($slugText)));
        }

        if ($course->getOrdering() === null) {
            $maxOrdering = $this->urbinoCourseRepository->createQueryBuilder('c')
                ->select('MAX(c.ordering)')
                ->getQuery()
                ->getSingleScalarResult();
            $course->setOrdering(($maxOrdering ?? 0) + 1);
        }

        $course->setUpdatedAt(new \DateTime());

        return $this->urbinoCourseRepository->save($course);
    }

    public function updateOrdering(array $orderedIds): void
    {
        foreach ($orderedIds as $position => $id) {
            $course = $this->urbinoCourseRepository->find($id);
            if ($course) {
                $course->setOrdering($position);
                $course->setUpdatedAt(new \DateTime());
                $this->urbinoCourseRepository->save($course);
            }
        }
    }

    public function saveTeacherImage(UrbinoCourse $course, UploadedFile $uploadedFile): UrbinoCourse
    {
        $serverPath = $this->parameterBag->get('kernel.project_dir') . '/public/uploads-urbino-courses/';
        $imageName = $course->getId() . '.webp';

        if (!$this->filesystem->exists($serverPath)) {
            $this->filesystem->mkdir($serverPath, 0755);
        }

        $uploadedFile->move($serverPath, $imageName);

        $course->setIsImageUploaded(true);
        $this->urbinoCourseRepository->save($course);

        return $course;
    }
}
