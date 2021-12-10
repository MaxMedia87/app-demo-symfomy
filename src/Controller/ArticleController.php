<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\SlackClient;
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
     * @return Response
     */
    public function show($slug): Response
    {
        $comments = [
            'Crescere etiam ducunt ad teres fraticinida.',
            'Boil six chocolates, sauerkraut, and butterscotch in a large plastic bag over medium heat, cook for two minutes and season with some steak.',
            'Boil six chocolates, sauerkraut, and buttersc.',
            'God theres nothing like the scurvy endurance growing on the woodchuck.'
        ];

        $slug = str_replace('-', ' ', $slug);

        $articleContent = <<<EOF
**Жили-были на свете три поросенка**. [Три брата](/). Все одинакового роста, кругленькие, розовые, 
с одинаковыми веселыми хвостиками. Даже имена у них были похожи. Звали поросят: Ниф-Ниф, Нуф-Нуф и Наф-Наф.
Все лето поросята кувыркались в зеленой траве, грелись на солнышке, нежились в лужах. Но вот наступила осень.
* Пора нам подумать о зиме, — сказал как-то Наф-Наф своим братьям, проснувшись рано утром. 

* Я весь дрожу от холода. Давайте построим дом и будем зимовать вместе под одной теплой крышей.

#####Но его братья не хотели браться за работу.

* Успеется! До зимы еще далеко. Мы еще погуляем, — сказал Ниф-Ниф и перекувырнулся через голову.

* Когда нужно будет, я сам построю себе дом, — сказал Нуф-Нуф и лег в лужу.

* Я тоже, — добавил Ниф-Ниф.
EOF;

        return $this->render(
            'articles/show.html.twig',
            [
                'article' => ucfirst($slug),
                'comments' => $comments,
                'articleContent' => $articleContent
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
