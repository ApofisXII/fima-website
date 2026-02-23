<?php

namespace App\Controller\Public;

use App\DTO\Website\CoursesInCategoryDTO;
use App\Entity\UrbinoCourse;
use App\Repository\UrbinoCourseCategoryRepository;
use App\Repository\UrbinoCourseRepository;
use App\Repository\UrbinoEditionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/{_locale}/urbino-musica-antica')]
final class UrbinoCourseController extends AbstractController
{

    public function __construct(
        private readonly UrbinoCourseRepository         $urbinoCourseRepository,
        private readonly UrbinoEditionRepository        $urbinoEditionRepository,
        private readonly UrbinoCourseCategoryRepository $urbinoCourseCategoryRepository,
    ) {}

    #[Route('/courses', name: 'urbinoCoursesList')]
    public function urbinoCoursesList(): Response
    {
        $currentEdition = $this->urbinoEditionRepository->findCurrentEdition();

        $categories = $this->urbinoCourseCategoryRepository->findBy([
            "is_deleted" => false,
        ], ["ordering" => "asc"]);

        $coursesInCategories = [];
        foreach ($categories as $category) {
            $courses = $this->urbinoCourseRepository->findBy([
                "urbino_edition" => $currentEdition,
                "urbino_course_category" => $category,
                "is_deleted" => false,
            ], ["ordering" => "asc"]);

            if (count($courses) > 0) {
                $coursesInCategories[] = new CoursesInCategoryDTO(
                    category: $category,
                    courses: $courses,
                );
            }
        }

        return $this->render('public/urbino-courses.html.twig', [
            "coursesInCategories" => $coursesInCategories,
        ]);
    }

    #[Route('/courses/{course}/{slug}', name: 'urbinoCourseDetail')]
    public function urbinoCourseDetail(UrbinoCourse $course, string $slug): Response
    {
        if ($course->getSlug() !== $slug) {
            return $this->redirectToRoute("urbinoCourseDetail", [
                "course" => $course->getId(),
                "slug" => $course->getSlug(),
            ]);
        }

        return $this->render('public/urbino-course-detail.html.twig', [
            "course" => $course,
        ]);
    }

}
