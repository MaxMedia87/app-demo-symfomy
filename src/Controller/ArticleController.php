<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController
{
    /**
     * @Route("/")
     */
    public function homepage(): Response
    {
        return new Response('Главная страница');
    }

    /**
     * @Route ("/articles/{slug}")
     * @param $slug
     */
    public function show($slug): Response
    {
        return new Response(
            sprintf('Это страница %s', $slug)
        );
    }
}
