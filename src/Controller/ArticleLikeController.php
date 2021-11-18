<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ArticleLikeController extends AbstractController
{
    /**
     * @Route("/articles/{id<\d+>}/like/{type<like|dislike>}", methods={"POST"}, name="app_article_like")
     * @param int $id
     * @param string $type
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function like(int $id, string $type)
    {
        $likes = rand(0, 119);

        if ('like' === $type) {
            $likes = rand(120, 200);
        }

        return $this->json(['likes' => $likes]);
    }
}
