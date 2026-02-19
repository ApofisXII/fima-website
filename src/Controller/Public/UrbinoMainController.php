<?php

namespace App\Controller\Public;

use App\Repository\UrbinoEditionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/{_locale}/urbino-musica-antica')]
final class UrbinoMainController extends AbstractController
{

    public function __construct(
        private readonly UrbinoEditionRepository $urbinoEditionRepository,
    ) {}

    #[Route('/welcome', name: 'urbinoWelcome')]
    public function urbinoWelcome(): Response
    {
        $currentEdition = $this->urbinoEditionRepository->findCurrentEdition();

        return $this->render('public/urbino-welcome.html.twig', [
            "currentEdition" => $currentEdition,
        ]);
    }

    #[Route('/courses', name: 'urbinoCoursesList')]
    public function urbinoCoursesList(): Response
    {
        $courses = []; // Todo: fetch from database
        return $this->render('public/urbino-courses.html.twig', [
            "courses" => $courses,
        ]);
    }

    #[Route('/info', name: 'urbinoInformations')]
    public function urbinoInformations(): Response
    {
        $currentEdition = $this->urbinoEditionRepository->findCurrentEdition();

        return $this->render('public/urbino-informations.html.twig', [
            "currentEdition" => $currentEdition,
        ]);
    }

}
