<?php

namespace App\Controller\Public;

use App\Repository\RecercareIssueRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class RecercareController extends AbstractController
{
    public function __construct(
        private readonly RecercareIssueRepository $recercareIssueRepository,
    ) {}

    #[Route('/{_locale}/recercare', name: 'recercare')]
    public function recercare(): Response
    {
        $issues = $this->recercareIssueRepository->findBy(
            ['is_public' => true, 'is_deleted' => false],
            ['year' => 'desc'],
        );

        return $this->render('public/recercare.html.twig', [
            "issues" => $issues,
        ]);
    }

}
