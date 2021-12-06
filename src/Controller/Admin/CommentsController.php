<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentsController extends AbstractController
{
    /**
     * @Route("/admin/comments", name="app_admin_comments")
     */
    public function index(): Response
    {
        $comments = [
            [
                'articleTitle' => 'Есть ли жизнь после девятой жизни?',
                'comment' => 'Pess persuadere, tanquam ferox bursa. Credere aliquando ducunt ad camerarius detrius. Ecce. Ignigenas crescere, tanquam albus nuclear vexatum iacere.',
                'createdAt' => new \DateTime('-1 hours'),
                'authorName' => 'Сметанка',
            ],
            [
                'articleTitle' => 'Когда в машинах поставят лоток?',
                'comment' => 'As i have shaped you, so you must hurt one another.',
                'createdAt' => new \DateTime('-1 days'),
                'authorName' => 'Рыжий Бесстыжий',
            ],
            [
                'articleTitle' => 'Есть ли жизнь после девятой жизни?',
                'comment' => 'Big passions lead to the life. Placidus ignigena sapienter falleres competition est. A falsis, tumultumque audax armarium.',
                'createdAt' => new \DateTime('-11 days'),
                'authorName' => 'Барон Сосискин',
            ],
            [
                'articleTitle' => 'В погоне за красной точкой',
                'comment' => 'The sea-dog waves adventure like a swashbuckling jack.',
                'createdAt' => new \DateTime('-35 days'),
                'authorName' => 'Сметанка',
            ],
        ];

        return $this->render('admin/comments/index.html.twig', [
            'comments' => $comments,
        ]);
    }
}
