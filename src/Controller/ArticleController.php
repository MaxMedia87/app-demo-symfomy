<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Service\SlackClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    /**
     * @Route("/", name="app_homepage")
     * @param ArticleRepository $articleRepository
     * @return Response
     */
    public function homepage(ArticleRepository $articleRepository): Response
    {
        $articles = $articleRepository->findLatestPublished();

        return $this->render('homepage.html.twig', ['articles' => $articles]);
    }

    /**
     * @Route ("/articles/{slug}", name="app_article_show")
     * @param Article $article
     *
     * @return Response
     */
    public function show(Article $article): Response
    {
        return $this->render(
            'articles/show.html.twig',
            [
                'article' => $article
            ]
        );
    }

    /**
     * @Route ("/notify/slack", name="app_notify_slack")
     * @param SlackClient $slackClient
     * @return Response
     */
    public function notifySlack(SlackClient $slackClient): Response
    {
        $slackClient->send('Привет, это важное сообщение с сайта!', 'Администратор сайта');

        return $this->json('Ok');
    }
}
