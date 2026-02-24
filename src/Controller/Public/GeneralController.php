<?php

namespace App\Controller\Public;

use App\Repository\NewsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class GeneralController extends AbstractController
{
    public function __construct(
        private readonly NewsRepository $newsRepository,
    ) {}

    #[Route('/', name: 'indexRedirectLocalize')]
    public function indexRedirectLocalize(Request $request): Response
    {
        return new Response("Lavori in corso, il sito sarà disponibile a breve.");

        if (str_contains(strtolower($request->getPreferredLanguage()), "it")) {
            return $this->redirectToRoute("indexLocalized", ["_locale" => "it"]);
        }

        return $this->redirectToRoute('indexLocalized', ['_locale' => 'en']);
    }

    #[Route('/{_locale}', name: 'indexLocalized')]
    public function indexLocalized(): Response
    {
        $newsList = $this->newsRepository->findBy([
            "is_deleted" => false,
        ], ["created_at" => "desc"], 3);

        return $this->render('public/index.html.twig', [
            "newsList" => $newsList,
        ]);
    }

    #[Route('/{_locale}/donate', name: 'donate')]
    public function donate(): Response
    {
        return $this->render('public/donate.html.twig');
    }

    #[Route('/{_locale}/contacts', name: 'contacts')]
    public function contacts(): Response
    {
        return $this->render('public/contacts.html.twig');
    }

    #[Route('/{_locale}/library', name: 'library')]
    public function library(): Response
    {
        return $this->render('public/library.html.twig');
    }

    #[Route('/{_locale}/transparent-administration', name: 'transparentAdministration')]
    public function transparentAdministration(): Response
    {
        return $this->render('public/transparent-administration.html.twig');
    }

    #[Route('/404', name: 'error404preview')]
    public function error404preview(): Response
    {
        return $this->render('@Twig/Exception/error404.html.twig');
    }
}
