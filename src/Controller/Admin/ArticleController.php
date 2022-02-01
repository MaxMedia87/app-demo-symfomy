<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    /**
     * @Route("/admin/articles/create", name="app_admin_articles_create")
     *
     * @param EntityManagerInterface $em
     * @return Response
     * @throws Exception
     */
    public function create(EntityManagerInterface $em): Response
    {
        $article = new Article();
        $article
            ->setTitle('Есть ли жизнь после девятой жизни?')
            ->setSlug('is-there-life-after-the-ninth-life-' . rand(100, 999))
            ->setBody(<<<EOF
**Жили-были на свете три поросенка**. [Три брата](/). Все одинакового роста, кругленькие, розовые, 
с одинаковыми веселыми хвостиками. Даже имена у них были похожи. Звали поросят: Ниф-Ниф, Нуф-Нуф и Наф-Наф.
Все лето поросята кувыркались в зеленой траве, грелись на солнышке, нежились в лужах. Но вот наступила осень.
* Пора нам подумать о зиме, — сказал как-то Наф-Наф своим братьям, проснувшись рано утром. 

* Я весь дрожу от холода. Давайте построим дом и будем зимовать вместе под одной теплой крышей.

#####Но его братья не хотели браться за работу.

* Успеется! До зимы еще далеко. Мы еще погуляем, — сказал Ниф-Ниф и перекувырнулся через голову.

* Когда нужно будет, я сам построю себе дом, — сказал Нуф-Нуф и лег в лужу.

* Я тоже, — добавил Ниф-Ниф.
EOF
);
        if (rand(1, 10) > 4) {
            $article->setPublishedAt(new \DateTimeImmutable(sprintf('-%d days', rand(0, 50))));
        }

        $em->persist($article);
        $em->flush();

        return new Response(
            sprintf(
                'Создана статья с id: %s символьный код: %s',
                $article->getId(),
                $article->getSlug()
            )
        );
    }
}
