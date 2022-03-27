<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ArticleLikeController extends AbstractController
{
    /**
     * @Route("/articles/{slug}/like/{type<like|dislike>}", methods={"POST"}, name="app_article_like")
     * @param Article $article
     * @param string $type
     * @param EntityManagerInterface $em
     *
     * @return JsonResponse
     */
    public function like(Article $article, string $type, EntityManagerInterface $em)
    {
        if ('like' === $type) {
            $article->like();
        } else {
            $article->dislike();
        }

        $em->flush();

        return $this->json(['likes' => $article->getLikeCount()]);
    }
}
