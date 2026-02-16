<?php

namespace App\Service;

use App\DTO\Admin\UrbinoCourseRequestDTO;
use App\Entity\UrbinoCourse;
use App\Repository\UrbinoCourseCategoryRepository;
use App\Repository\UrbinoCourseRepository;
use App\Repository\UrbinoEditionRepository;
use App\Utils\ImageUtils;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class UrbinoCourseService
{
    public function __construct(
        private readonly UrbinoCourseRepository $urbinoCourseRepository,
        private readonly UrbinoEditionRepository $urbinoEditionRepository,
        private readonly UrbinoCourseCategoryRepository $urbinoCourseCategoryRepository,
        private readonly SluggerInterface $slugger,
        private readonly ParameterBagInterface $parameterBag,
        private readonly Filesystem $filesystem,
        private readonly ImageUtils $imageUtils,
    ) {}

    public function create(UrbinoCourseRequestDTO $dto): UrbinoCourse
    {
        $course = new UrbinoCourse();
        $course->setCreatedAt(new \DateTime());
        $course->setIsImageUploaded(false);
        $course->setScheduleType(UrbinoCourse::SCHEDULE_TYPE_MAIN);
        $course->setIsDeleted(false);
        return $this->update($course, $dto);
    }

    public function update(UrbinoCourse $course, UrbinoCourseRequestDTO $dto): UrbinoCourse
    {
        $edition = $this->urbinoEditionRepository->find($dto->urbinoEditionId);
        $category = $this->urbinoCourseCategoryRepository->find($dto->urbinoCategoryId);

        $course->setTeacherFullName($dto->teacherFullName);
        $course->setUrbinoEdition($edition);
        $course->setUrbinoCourseCategory($category);
        $course->setProgramDescriptionIt($dto->programDescriptionIt);
        $course->setProgramDescriptionEn($dto->programDescriptionEn);
        $course->setBioDescriptionIt($dto->bioDescriptionIt);
        $course->setBioDescriptionEn($dto->bioDescriptionEn);
        $course->setIsPreselectionRequired($dto->isPreselectionRequired ?? false);
        $course->setIsSoldOut($dto->isSoldOut ?? false);
        $course->setScheduleType($dto->scheduleType ?? UrbinoCourse::SCHEDULE_TYPE_MAIN);

        if ($dto->priceEuros !== null) {
            $course->setPriceCents((int) round($dto->priceEuros * 100));
        } else {
            $course->setPriceCents(null);
        }

        if (!$course->getSlug()) {
            $slugText = $dto->teacherFullName;
            if ($category && $category->getNameIt()) {
                $slugText .= ' ' . $category->getNameIt();
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

    public function updateOrdering(array $diffCourses): void
    {
        $em = $this->urbinoCourseRepository->getEntityManager();

        foreach ($diffCourses as $diff) {
            $course = $this->urbinoCourseRepository->find($diff['id']);
            $course->setOrdering($diff['new_position'] + 1);
            $course->setUpdatedAt(new \DateTime());
        }

        $em->flush();

        $allCourses = $this->urbinoCourseRepository->findBy([], ['ordering' => 'ASC']);

        $position = 1;
        foreach ($allCourses as $course) {
            $course->setOrdering($position);
            $position++;
        }

        $em->flush();
    }

    public function saveTeacherImage(UrbinoCourse $course, UploadedFile $uploadedFile): UrbinoCourse
    {
        $serverPath = $this->parameterBag->get('kernel.project_dir') . '/public/uploads-uma-courses/';
        $imageName = $course->getId() . '.webp';
        $imagePath = $serverPath . $imageName;

        $uploadedFile->move($serverPath, $imageName);
        $this->imageUtils->compressImage($imagePath);

        $course->setIsImageUploaded(true);
        $this->urbinoCourseRepository->save($course);

        return $course;
    }

    public function deleteTeacherImage(UrbinoCourse $course): UrbinoCourse
    {
        $serverPath = $this->parameterBag->get('kernel.project_dir') . '/public/uploads-uma-courses/';
        $imageName = $course->getId() . '.webp';
        $imagePath = $serverPath . $imageName;

        if ($this->filesystem->exists($imagePath)) {
            $this->filesystem->remove($imagePath);
        }

        $course->setIsImageUploaded(false);
        $course->setUpdatedAt(new \DateTime());
        $this->urbinoCourseRepository->save($course);

        return $course;
    }

    public function delete(UrbinoCourse $course): UrbinoCourse
    {
        $course->setIsDeleted(true);
        $course->setUpdatedAt(new \DateTime());

        return $this->urbinoCourseRepository->save($course);
    }
}
