<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    /**
     * @Route("/", name="app_homepage")
     */
    public function homepage(): Response
    {
        return $this->render('homepage.html.twig');
    }

    /**
     * @Route ("/articles/{slug}", name="app_article_show")
     * @param $slug
     * @return Response
     */
    public function show($slug): Response
    {
        $comments = [
            'Crescere etiam ducunt ad teres fraticinida.',
            'Boil six chocolates, sauerkraut, and butterscotch in a large plastic bag over medium heat, cook for two minutes and season with some steak.',
            'Boil six chocolates, sauerkraut, and buttersc.',
            'God theres nothing like the scurvy endurance growing on the woodchuck.'
        ];

        $slug = str_replace('-', ' ', $slug);

        return $this->render(
            'articles/show.html.twig',
            [
                'article' => ucfirst($slug),
                'comments' => $comments
            ]
        );
    }
}
