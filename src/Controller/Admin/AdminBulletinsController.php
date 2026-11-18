<?php

namespace App\Controller\Admin;

use App\DTO\Admin\BulletinRequestDTO;
use App\DTO\Admin\DataTableRequestDTO;
use App\Entity\Bulletin;
use App\Repository\BulletinRepository;
use App\Service\BulletinService;
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

#[Route(path: '/admin/bulletins')]
class AdminBulletinsController extends AbstractController
{
    public function __construct(
        private readonly BulletinRepository $bulletinRepository,
        private readonly BulletinService    $bulletinService,
    ) {}

    #[Route(path: '', name: 'adminBulletinsList')]
    public function adminBulletinsList(): Response
    {
        return $this->render('admin/bulletins-list.html.twig');
    }

    #[Route(path: '/json', name: 'adminBulletinsListJson')]
    public function adminBulletinsListJson(#[MapQueryString] DataTableRequestDTO $payload): Response
    {
        $qb = $this->bulletinRepository->createQueryBuilder('b')
            ->andWhere('b.is_deleted = false')
            ->orderBy('b.published_at', 'desc');

        if ($payload->search['value']) {
            $search = $payload->search['value'];
            $qb->andWhere('b.title LIKE :search OR b.author LIKE :search')
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
                'title'              => $item->getTitle(),
                'author'             => $item->getAuthor(),
                'publishedAt'        => $item->getPublishedAt()->format('d/m/Y'),
                'isPublic'           => $item->isPublic(),
                'createdAt'          => $item->getCreatedAt()->format('d/m/y \\a\\l\\l\\e H:i'),
                'bulletinDetailLink' => $this->generateUrl('adminBulletinsDetail', ['bulletinId' => $item->getId()]),
            ];
        }, (array) $pagerfanta->getCurrentPageResults());

        return $this->json([
            'data'            => $list,
            'draw'            => $payload->draw,
            'recordsFiltered' => $pagerfanta->getNbResults(),
            'recordsTotal'    => $pagerfanta->getNbResults(),
        ]);
    }

    #[Route(path: '/detail', name: 'adminBulletinsDetail', methods: ['GET'])]
    public function adminBulletinsDetail(Request $request): Response
    {
        $bulletin = $this->bulletinRepository->findOneBy(['id' => $request->query->get('bulletinId')]);

        return $this->render('admin/bulletins-detail.html.twig', [
            'bulletin' => $bulletin,
        ]);
    }

    #[Route(path: '/detail', name: 'adminBulletinsDetailSave', methods: ['POST'], format: 'json')]
    public function adminBulletinsDetailSave(#[MapRequestPayload] BulletinRequestDTO $payload): Response
    {
        $bulletin = $this->bulletinRepository->findOneBy(['id' => $payload->bulletinId]);

        if (null === $bulletin) {
            $saved = $this->bulletinService->create($payload);

            return $this->json(['message' => 'Dati salvati', 'bulletinId' => $saved->getId()]);
        }

        $this->bulletinService->update($bulletin, $payload);

        return $this->json(['message' => 'Dati salvati']);
    }

    #[Route(path: '/delete/{bulletin}', name: 'adminBulletinsDelete', methods: ['DELETE'], format: 'json')]
    public function adminBulletinsDelete(
        Bulletin $bulletin,
    ): Response {
        $this->bulletinService->delete($bulletin);

        return $this->json(['message' => 'Bollettino eliminato']);
    }

    #[Route(path: '/upload-cover/{bulletin}', name: 'adminBulletinsUploadCover', methods: ['POST'], format: 'json')]
    public function adminBulletinsUploadCover(
        Bulletin $bulletin,
        #[MapUploadedFile] ?UploadedFile $cover,
    ): Response {
        if (!$cover) {
            return $this->json(['message' => 'Nessun file selezionato'], 400);
        }

        $this->bulletinService->saveCover($bulletin, $cover);

        return $this->json(['message' => 'Copertina caricata']);
    }

    #[Route(path: '/delete-cover/{bulletin}', name: 'adminBulletinsDeleteCover', methods: ['DELETE'], format: 'json')]
    public function adminBulletinsDeleteCover(
        Bulletin $bulletin,
    ): Response {
        $this->bulletinService->deleteCover($bulletin);

        return $this->json(['message' => 'Copertina eliminata']);
    }

    #[Route(path: '/upload-pdf/{bulletin}', name: 'adminBulletinsUploadPdf', methods: ['POST'], format: 'json')]
    public function adminBulletinsUploadPdf(
        Bulletin $bulletin,
        #[MapUploadedFile] ?UploadedFile $pdf,
    ): Response {
        if (!$pdf) {
            return $this->json(['message' => 'Nessun file selezionato'], 400);
        }

        $this->bulletinService->savePdf($bulletin, $pdf);

        return $this->json(['message' => 'PDF caricato']);
    }

    #[Route(path: '/delete-pdf/{bulletin}', name: 'adminBulletinsDeletePdf', methods: ['DELETE'], format: 'json')]
    public function adminBulletinsDeletePdf(
        Bulletin $bulletin,
    ): Response {
        $this->bulletinService->deletePdf($bulletin);

        return $this->json(['message' => 'PDF eliminato']);
    }
}
