<?php

namespace App\Controller\Public;

use App\Repository\BulletinRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class BulletinsController extends AbstractController
{
    public function __construct(
        private readonly BulletinRepository $bulletinRepository,
    ) {}

    #[Route('/{_locale}/bulletins', name: 'bulletins')]
    public function bulletins(): Response
    {
        $bulletins = $this->bulletinRepository->findBy(
            ['is_public' => true, 'is_deleted' => false],
            ['year' => 'desc', 'number' => 'desc'],
        );

        return $this->render('public/bulletins.html.twig', [
            "bulletins" => $bulletins,
        ]);
    }

}
