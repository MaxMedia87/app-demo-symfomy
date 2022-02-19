<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentsController extends AbstractController
{
    /**
     * @Route("/admin/comments", name="app_admin_comments")
     * @param Request $request
     * @param CommentRepository $commentRepository
     *
     * @return Response
     */
    public function index(Request $request, CommentRepository $commentRepository): Response
    {
        $comments = $commentRepository->findAllWithSearch($request->query->get('q'));

        return $this->render('admin/comments/index.html.twig', [
            'comments' => $comments,
        ]);
    }
}
