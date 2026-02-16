<?php

namespace App\Controller\Public;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class GeneralController extends AbstractController
{
    #[Route('/', name: 'indexRedirectLocalize')]
    public function indexRedirectLocalize(Request $request): Response
    {
        if (str_contains(strtolower($request->getPreferredLanguage()), "it")) {
            return $this->redirectToRoute("indexLocalized", ["_locale" => "it"]);
        }

        return $this->redirectToRoute('indexLocalized', ['_locale' => 'en']);
    }

    #[Route('/{_locale}', name: 'indexLocalized')]
    public function indexLocalized(): Response
    {
        return $this->render('public/index.html.twig');
    }

    #[Route('/{_locale}/donate', name: 'donate')]
    public function donate(): Response
    {
        return $this->render('public/donate.html.twig');
    }
}
