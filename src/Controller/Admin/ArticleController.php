<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Entity\User;
use App\Form\ArticleFormType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @method User|null getUser()
 */
class ArticleController extends AbstractController
{
    /**
     * @IsGranted("ROLE_ADMIN_ARTICLE")
     * @Route("/admin/articles", name="app_admin_articles")
     *
     * @param Request $request
     * @param ArticleRepository $articleRepository
     * @param PaginatorInterface $paginator
     *
     * @return Response
     */
    public function index(
        Request $request,
        ArticleRepository $articleRepository,
        PaginatorInterface $paginator
    ): Response {
        $defaultPerPage = 10;
        $perPage = 0;

        if (true === $request->query->has('perPage')
            && false === empty($request->query->get('perPage'))
        ) {
            $perPage = (int) $request->query->get('perPage');
        }

        $query = $request->query->get('q');

        $pagination = $paginator->paginate(
            $articleRepository->findAllWithSearchQueryBuilder($query),
            $request->query->getInt('page', 1),
            0 === $perPage ? $defaultPerPage : $perPage
        );

        return $this->render('admin/article/index.html.twig', [
            'pagination' => $pagination,
            'perPage' => [10, 50, 100]
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN_ARTICLE")
     * @Route("/admin/articles/create", name="app_admin_articles_create")
     *
     * @param EntityManagerInterface $em
     * @param Request $request
     * @return Response
     */
    public function create(EntityManagerInterface $em, Request $request): Response
    {
        $form = $this->createForm(ArticleFormType::class);

        $form->handleRequest($request);

        if (true === $form->isSubmitted() && true === $form->isValid()) {
            /** @var Article $article */
            $article = $form->getData();

            $em->persist($article);
            $em->flush();

            $this->addFlash('flash_message', 'Статья успешно создана.');

            return $this->redirectToRoute('app_admin_articles');
        }

        return $this->render('admin/article/create.html.twig', [
            'articleForm' => $form->createView(),
            'showError' => $form->isSubmitted()
        ]);
    }

    /**
     * @IsGranted("MANAGE", subject="article")
     * @Route("/admin/articles/{id}/edit", name="app_admin_articles_edit")
     *
     * @param Article $article
     * @param EntityManagerInterface $em
     * @param Request $request
     * @param SluggerInterface $slugger
     * @return Response
     */
    public function edit(
        Article $article,
        EntityManagerInterface $em,
        Request $request,
        SluggerInterface $slugger
    ): Response {
        $form = $this->createForm(ArticleFormType::class, $article, [
            'enabled_published_at' => true
        ]);

        $form->handleRequest($request);

        if (true === $form->isSubmitted() && true === $form->isValid()) {
            /** @var Article $article */
            $article = $form->getData();

            /** @var UploadedFile|null $image */
            $image = $form->get('image')->getData();
            $newFile = $this->uploadImage($image, $slugger);

            $article->setImageFileName($newFile->getFilename());
            $em->persist($article);
            $em->flush();

            $this->addFlash('flash_message', 'Статья успешно изменена.');

            return $this->redirectToRoute('app_admin_articles_edit', ['id' => $article->getId()]);
        }

        return $this->render('admin/article/edit.html.twig', [
            'articleForm' => $form->createView(),
            'showError' => $form->isSubmitted()
        ]);
    }

    protected function uploadImage(UploadedFile $image, SluggerInterface $slugger): File
    {
        $originalName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
        $fileName = $slugger
            ->slug($originalName)
            ->append('-', uniqid())
            ->append('.', $image->guessClientExtension())
            ->toString();

        $uploadDir = $this->getParameter('article_uploads_dir');

        return $image->move($uploadDir, $fileName);
    }
}
