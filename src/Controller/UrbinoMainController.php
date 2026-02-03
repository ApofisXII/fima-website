<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/{_locale}/urbino-musica-antica')]
final class UrbinoMainController extends AbstractController
{

    #[Route('/welcome', name: 'urbinoWelcome')]
    public function urbinoWelcome(): Response
    {
        return $this->render('public/urbino-welcome.html.twig');
    }

    #[Route('/courses', name: 'urbinoCoursesList')]
    public function urbinoCoursesList(): Response
    {
        $courses = []; // Todo: fetch from database
        return $this->render('public/urbino-courses.html.twig', [
            "courses" => $courses,
        ]);
    }

}
