<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    /**
     * @IsGranted("ROLE_ADMIN_ARTICLE")
     * @Route("/admin/articles/create", name="app_admin_articles_create")
     *
     * @param EntityManagerInterface $em
     * @return Response
     * @throws Exception
     */
    public function create(EntityManagerInterface $em): Response
    {
        return new Response('Здесь будет страница по созданию статьи');
    }

    /**
     * @Route("/admin/articles/{id}/edit", name="app_admin_articles_edit")
     *
     * @param Article $article
     * @return Response
     */
    public function edit(Article $article): Response
    {
        $this->denyAccessUnlessGranted('MANAGE', $article);

        return new Response('Страница редактирования статьи: ' . $article->getTitle());
    }
}
