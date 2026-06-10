<?php

namespace App\Controller\Admin;

use App\DTO\Admin\DataTableRequestDTO;
use App\DTO\Admin\RecercareIssueRequestDTO;
use App\Repository\RecercareIssueRepository;
use App\Service\RecercareIssueService;
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

#[Route(path: '/admin/recercare')]
class AdminRecercareIssuesController extends AbstractController
{
    public function __construct(
        private readonly RecercareIssueRepository $recercareIssueRepository,
        private readonly RecercareIssueService    $recercareIssueService,
    ) {}

    #[Route(path: '', name: 'adminRecercareIssuesList')]
    public function adminRecercareIssuesList(): Response
    {
        return $this->render('admin/recercare-list.html.twig');
    }

    #[Route(path: '/json', name: 'adminRecercareIssuesListJson')]
    public function adminRecercareIssuesListJson(#[MapQueryString] DataTableRequestDTO $payload): Response
    {
        $qb = $this->recercareIssueRepository->createQueryBuilder('r')
            ->andWhere('r.is_deleted = false')
            ->orderBy('r.volume', 'desc');

        if ($payload->search['value']) {
            $search = $payload->search['value'];
            $qb->andWhere('CAST(r.volume AS TEXT) LIKE :search OR CAST(r.year AS TEXT) LIKE :search OR r.isbn LIKE :search')
                ->setParameter('search', '%'.$search.'%');
        }

        $adapter = new QueryAdapter($qb->getQuery());
        $pagerfanta = Pagerfanta::createForCurrentPageWithMaxPerPage(
            adapter: $adapter,
            currentPage: floor($payload->start / $payload->length) + 1,
            maxPerPage: $payload->length,
        );

        $list = array_map(function ($item) {
            return [
                'volume'          => $item->getVolume(),
                'year'            => $item->getYear(),
                'isbn'            => $item->getIsbn() ?? '—',
                'isPublic'        => $item->isPublic(),
                'createdAt'       => $item->getCreatedAt()->format('d/m/y \\a\\l\\l\\e H:i'),
                'issueDetailLink' => $this->generateUrl('adminRecercareIssuesDetail', ['issueId' => $item->getId()]),
            ];
        }, (array) $pagerfanta->getCurrentPageResults());

        return $this->json([
            'data'            => $list,
            'draw'            => $payload->draw,
            'recordsFiltered' => $pagerfanta->getNbResults(),
            'recordsTotal'    => $pagerfanta->getNbResults(),
        ]);
    }

    #[Route(path: '/detail', name: 'adminRecercareIssuesDetail', methods: ['GET'])]
    public function adminRecercareIssuesDetail(Request $request): Response
    {
        $issue = $this->recercareIssueRepository->findOneBy(['id' => $request->query->get('issueId')]);

        return $this->render('admin/recercare-detail.html.twig', [
            'issue' => $issue,
        ]);
    }

    #[Route(path: '/detail', name: 'adminRecercareIssuesDetailSave', methods: ['POST'], format: 'json')]
    public function adminRecercareIssuesDetailSave(#[MapRequestPayload] RecercareIssueRequestDTO $payload): Response
    {
        $issue = $this->recercareIssueRepository->findOneBy(['id' => $payload->issueId]);

        if (null === $issue) {
            $saved = $this->recercareIssueService->create($payload);

            return $this->json(['message' => 'Dati salvati', 'issueId' => $saved->getId()]);
        }

        $this->recercareIssueService->update($issue, $payload);

        return $this->json(['message' => 'Dati salvati']);
    }

    #[Route(path: '/delete/{issueId}', name: 'adminRecercareIssuesDelete', methods: ['DELETE'], format: 'json')]
    public function adminRecercareIssuesDelete(int $issueId): Response
    {
        $issue = $this->recercareIssueRepository->find($issueId);

        $this->recercareIssueService->delete($issue);

        return $this->json(['message' => 'Numero eliminato']);
    }

    #[Route(path: '/upload-cover/{issueId}', name: 'adminRecercareIssuesUploadCover', methods: ['POST'], format: 'json')]
    public function adminRecercareIssuesUploadCover(int $issueId, #[MapUploadedFile] ?UploadedFile $cover): Response
    {
        if (!$cover) {
            return $this->json(['message' => 'Nessun file selezionato'], 400);
        }

        $issue = $this->recercareIssueRepository->find($issueId);
        $this->recercareIssueService->saveCover($issue, $cover);

        return $this->json(['message' => 'Copertina caricata']);
    }

    #[Route(path: '/delete-cover/{issueId}', name: 'adminRecercareIssuesDeleteCover', methods: ['DELETE'], format: 'json')]
    public function adminRecercareIssuesDeleteCover(int $issueId): Response
    {
        $issue = $this->recercareIssueRepository->find($issueId);
        $this->recercareIssueService->deleteCover($issue);

        return $this->json(['message' => 'Copertina eliminata']);
    }

    #[Route(path: '/upload-index-it/{issueId}', name: 'adminRecercareIssuesUploadIndexIt', methods: ['POST'], format: 'json')]
    public function adminRecercareIssuesUploadIndexIt(int $issueId, #[MapUploadedFile] ?UploadedFile $indexIt): Response
    {
        if (!$indexIt) {
            return $this->json(['message' => 'Nessun file selezionato'], 400);
        }

        $issue = $this->recercareIssueRepository->find($issueId);
        $this->recercareIssueService->saveIndexIt($issue, $indexIt);

        return $this->json(['message' => 'Indice italiano caricato']);
    }

    #[Route(path: '/delete-index-it/{issueId}', name: 'adminRecercareIssuesDeleteIndexIt', methods: ['DELETE'], format: 'json')]
    public function adminRecercareIssuesDeleteIndexIt(int $issueId): Response
    {
        $issue = $this->recercareIssueRepository->find($issueId);
        $this->recercareIssueService->deleteIndexIt($issue);

        return $this->json(['message' => 'Indice italiano eliminato']);
    }

    #[Route(path: '/upload-index-en/{issueId}', name: 'adminRecercareIssuesUploadIndexEn', methods: ['POST'], format: 'json')]
    public function adminRecercareIssuesUploadIndexEn(int $issueId, #[MapUploadedFile] ?UploadedFile $indexEn): Response
    {
        if (!$indexEn) {
            return $this->json(['message' => 'Nessun file selezionato'], 400);
        }

        $issue = $this->recercareIssueRepository->find($issueId);
        $this->recercareIssueService->saveIndexEn($issue, $indexEn);

        return $this->json(['message' => 'Indice inglese caricato']);
    }

    #[Route(path: '/delete-index-en/{issueId}', name: 'adminRecercareIssuesDeleteIndexEn', methods: ['DELETE'], format: 'json')]
    public function adminRecercareIssuesDeleteIndexEn(int $issueId): Response
    {
        $issue = $this->recercareIssueRepository->find($issueId);
        $this->recercareIssueService->deleteIndexEn($issue);

        return $this->json(['message' => 'Indice inglese eliminato']);
    }
}
