<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/{_locale}/news')]
final class NewsController extends AbstractController
{

    #[Route('', name: 'newsList')]
    public function newsList(Request $request): Response
    {
        $newsList = []; // TODO: Fetch via Repository

        return $this->render('public/news-list.html.twig', [
            'newsList' => $newsList,
        ]);
    }

}
