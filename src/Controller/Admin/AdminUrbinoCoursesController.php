<?php

namespace App\Controller\Admin;

use App\DTO\Admin\DataTableRequestDTO;
use App\DTO\Admin\UrbinoCourseRequestDTO;
use App\Repository\UrbinoCourseCategoryRepository;
use App\Repository\UrbinoCourseRepository;
use App\Repository\UrbinoEditionRepository;
use App\Service\UrbinoCourseService;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Attribute\MapUploadedFile;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/admin/urbino/courses')]
class AdminUrbinoCoursesController extends AbstractController
{
    public function __construct(
        private readonly UrbinoCourseRepository $urbinoCourseRepository,
        private readonly UrbinoEditionRepository $urbinoEditionRepository,
        private readonly UrbinoCourseCategoryRepository $urbinoCourseCategoryRepository,
        private readonly UrbinoCourseService $urbinoCourseService,
    ) {}

    #[Route(path: '', name: 'adminUrbinoCoursesList')]
    public function adminUrbinoCoursesList(): Response
    {
        return $this->render('admin/urbino-courses-list.html.twig');
    }

    #[Route(path: '/json', name: 'adminUrbinoCoursesListJson')]
    public function adminUrbinoCoursesListJson(): Response
    {
        // Get all categories ordered
        $categories = $this->urbinoCourseCategoryRepository->createQueryBuilder("cat")
            ->andWhere("cat.is_deleted = false")
            ->orderBy("cat.ordering", "ASC")
            ->addOrderBy("cat.id", "ASC")
            ->getQuery()
            ->getResult();

        $categoriesData = [];

        foreach ($categories as $category) {
            // Get courses for this category
            $courses = $this->urbinoCourseRepository->createQueryBuilder("c")
                ->leftJoin("c.urbino_edition", "e")
                ->andWhere("c.urbino_course_category = :categoryId")
                ->andWhere("c.is_deleted = false")
                ->setParameter("categoryId", $category->getId())
                ->orderBy("c.ordering", "ASC")
                ->addOrderBy("c.id", "DESC")
                ->getQuery()
                ->getResult();

            $coursesData = array_map(function ($item) {
                return [
                    "id" => $item->getId(),
                    "teacherFullName" => $item->getTeacherFullName(),
                    "editionName" => $item->getUrbinoEdition()?->getEditionName(),
                    "scheduleType" => $item->getScheduleType(),
                    "isSoldOut" => $item->isSoldOut(),
                    "createdAt" => $item->getCreatedAt()->format("d/m/Y \\a\\l\\l\\e H:i"),
                    "courseDetailLink" => $this->generateUrl("adminUrbinoCoursesDetail", ["courseId" => $item->getId()]),
                ];
            }, $courses);

            $categoriesData[] = [
                "id" => $category->getId(),
                "nameIt" => $category->getNameIt(),
                "nameEn" => $category->getNameEn(),
                "courses" => $coursesData,
            ];
        }

        return $this->json([
            "categories" => $categoriesData,
        ]);
    }

    #[Route(path: '/detail', name: 'adminUrbinoCoursesDetail', methods: ['GET'])]
    public function adminUrbinoCoursesDetail(Request $request): Response
    {
        $course = $this->urbinoCourseRepository->findOneBy(["id" => $request->query->get("courseId")]);
        $editions = $this->urbinoEditionRepository->findAll();
        $categories = $this->urbinoCourseCategoryRepository->createQueryBuilder("c")
            ->andWhere("c.is_deleted = false")
            ->orderBy("c.name_it", "asc")
            ->getQuery()
            ->getResult();

        return $this->render('admin/urbino-courses-detail.html.twig', [
            "course" => $course,
            "editions" => $editions,
            "categories" => $categories,
        ]);
    }

    #[Route(path: '/detail', name: 'adminUrbinoCoursesDetailSave', methods: ['POST'], format: 'json')]
    public function adminUrbinoCoursesDetailSave(#[MapRequestPayload] UrbinoCourseRequestDTO $payload, #[MapUploadedFile] ?UploadedFile $teacherImage): Response
    {
        $course = $this->urbinoCourseRepository->findOneBy(["id" => $payload->courseId]);

        if (null === $course) {
            $course = $this->urbinoCourseService->create($payload);
        } else {
            $course = $this->urbinoCourseService->update($course, $payload);
        }

        if ($teacherImage) {
            $this->urbinoCourseService->saveTeacherImage($course, $teacherImage);
        }

        return $this->json([
            "message" => "Dati salvati",
        ]);
    }

    #[Route(path: '/reorder', name: 'adminUrbinoCoursesReorder', methods: ['POST'], format: 'json')]
    public function adminUrbinoCoursesReorder(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $diffCourses = $data['diff_courses'] ?? [];

        $this->urbinoCourseService->updateOrdering($diffCourses);

        return $this->json([
            "updated" => true,
        ]);
    }

    #[Route(path: '/delete/{courseId}', name: 'adminUrbinoCoursesDelete', methods: ['DELETE'], format: 'json')]
    public function adminUrbinoCoursesDelete(int $courseId): Response
    {
        $course = $this->urbinoCourseRepository->findOneBy(["id" => $courseId]);

        if (!$course) {
            return $this->json([
                "message" => "Corso non trovato",
            ], 404);
        }

        $this->urbinoCourseService->delete($course);

        return $this->json([
            "message" => "Corso eliminato",
        ]);
    }

    #[Route(path: '/upload-image/{courseId}', name: 'adminUrbinoCoursesUploadImage', methods: ['POST'], format: 'json')]
    public function adminUrbinoCoursesUploadImage(int $courseId, #[MapUploadedFile] ?UploadedFile $teacherImage): Response
    {
        $course = $this->urbinoCourseRepository->findOneBy(["id" => $courseId]);

        if (!$course) {
            return $this->json([
                "message" => "Corso non trovato",
            ], 404);
        }

        if (!$teacherImage) {
            return $this->json([
                "message" => "Nessuna immagine selezionata",
            ], 400);
        }

        $this->urbinoCourseService->saveTeacherImage($course, $teacherImage);

        return $this->json([
            "message" => "Immagine salvata",
        ]);
    }

    #[Route(path: '/delete-image/{courseId}', name: 'adminUrbinoCoursesDeleteImage', methods: ['DELETE'], format: 'json')]
    public function adminUrbinoCoursesDeleteImage(int $courseId): Response
    {
        $course = $this->urbinoCourseRepository->findOneBy(["id" => $courseId]);

        if (!$course) {
            return $this->json([
                "message" => "Corso non trovato",
            ], 404);
        }

        $this->urbinoCourseService->deleteTeacherImage($course);

        return $this->json([
            "message" => "Immagine eliminata",
        ]);
    }
}
