<?php

namespace App\Controller\Admin;

use App\DTO\Admin\DataTableRequestDTO;
use App\DTO\Admin\UrbinoCourseRequestDTO;
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
        private readonly UrbinoCourseService $urbinoCourseService,
    ) {}

    #[Route(path: '', name: 'adminUrbinoCoursesList')]
    public function adminUrbinoCoursesList(): Response
    {
        return $this->render('admin/urbino-courses-list.html.twig');
    }

    #[Route(path: '/json', name: 'adminUrbinoCoursesListJson')]
    public function adminUrbinoCoursesListJson(#[MapQueryString] DataTableRequestDTO $payload): Response
    {
        $qb = $this->urbinoCourseRepository->createQueryBuilder("c")
            ->leftJoin("c.urbino_edition", "e")
            ->orderBy("c.ordering", "asc")
            ->addOrderBy("c.id", "desc");

        if ($payload->search["value"]) {
            $search = $payload->search["value"];
            $qb->andWhere("c.teacher_full_name LIKE :search OR c.subject_it LIKE :search OR c.subject_en LIKE :search OR e.edition_name LIKE :search")
                ->setParameter("search", "%".$search."%");
        }

        $adapter = new QueryAdapter($qb->getQuery());
        $pagerfanta = Pagerfanta::createForCurrentPageWithMaxPerPage(
            adapter: $adapter,
            currentPage: floor($payload->start / $payload->length) + 1,
            maxPerPage: $payload->length,
        );

        $list = array_map(function ($item) {
            return [
                "id" => $item->getId(),
                "teacherFullName" => $item->getTeacherFullName(),
                "subjectIt" => $item->getSubjectIt(),
                "editionName" => $item->getUrbinoEdition()?->getEditionName(),
                "isSoldOut" => $item->isSoldOut(),
                "createdAt" => $item->getCreatedAt()->format("d/m/Y H:i"),
                "courseDetailLink" => $this->generateUrl("adminUrbinoCoursesDetail", ["courseId" => $item->getId()]),
            ];
        }, (array) $pagerfanta->getCurrentPageResults());

        return $this->json([
            "data" => $list,
            "draw" => $payload->draw,
            "recordsFiltered" => $pagerfanta->getNbResults(),
            "recordsTotal" => $pagerfanta->getNbResults(),
        ]);
    }

    #[Route(path: '/detail', name: 'adminUrbinoCoursesDetail', methods: ['GET'])]
    public function adminUrbinoCoursesDetail(Request $request): Response
    {
        $course = $this->urbinoCourseRepository->findOneBy(["id" => $request->query->get("courseId")]);
        $editions = $this->urbinoEditionRepository->findAll();

        return $this->render('admin/urbino-courses-detail.html.twig', [
            "course" => $course,
            "editions" => $editions,
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
        $orderedIds = json_decode($request->getContent(), true)['orderedIds'] ?? [];
        $this->urbinoCourseService->updateOrdering($orderedIds);

        return $this->json([
            "message" => "Ordinamento salvato",
        ]);
    }
}
