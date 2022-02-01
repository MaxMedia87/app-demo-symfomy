<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Article;
use App\Service\SlackClient;
use Doctrine\ORM\EntityManagerInterface;
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
     * @param EntityManagerInterface $em
     *
     * @return Response
     */
    public function show($slug, EntityManagerInterface $em): Response
    {
        $repository = $em->getRepository(Article::class);
        $article = $repository->findOneBy(['slug' => $slug]);

        if (null === $article) {
            throw $this->createNotFoundException(
                sprintf('Статья %s не найдена', $slug)
            );
        }

        $comments = [
            'Crescere etiam ducunt ad teres fraticinida.',
            'Boil six chocolates, sauerkraut, and butterscotch in a large plastic bag over medium heat, cook for two minutes and season with some steak.',
            'Boil six chocolates, sauerkraut, and buttersc.',
            'God theres nothing like the scurvy endurance growing on the woodchuck.'
        ];

        return $this->render(
            'articles/show.html.twig',
            [
                'article' => $article,
                'comments' => $comments,
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
