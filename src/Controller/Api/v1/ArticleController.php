<?php

namespace App\Controller\Api\v1;

use App\Entity\Article;
use App\Service\ApiLogger;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    /**
     * @Route("/api/v1/article_content", name="app_api_v1_article_content")
     * @IsGranted("ROLE_API")
     *
     * @param ApiLogger $logger
     * @return Response
     */
    public function articleContent(ApiLogger $logger): Response
    {
        if (false === $this->isGranted('ROLE_API')) {
            $logger->warning('Вход на страницу api', [$this->getUser()->getUserIdentifier()]);

        }

        return new JsonResponse([]);
    }

    /**
     * @IsGranted("MANAGE_API", subject="article")
     * @Route("/api/v1/artices/{id}", name="app_api_v1_article")
     *
     * @param Article $article
     * @return Response
     */
    public function show(Article $article): Response
    {
        return $this->json($article, 200, [], [
            'groups' => ['show_article']
        ]);
    }
}
