<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Comment;
use DateTimeImmutable;
use Doctrine\Persistence\ObjectManager;

class ArticleFixtures extends BaseFixtures
{
    private static $titles = [
        'Есть ли жизнь после девятой жизни?',
        'Когда в машинах поставят лоток?',
        'В погоне за красной точкой',
        'В чем смысл жизни сосисок',
    ];


    private static $authors = [
        'Кексик',
        'Матроскин',
        'Фунтик',
        'Колбаска',
        'Беляш'
    ];

    private static $images = [
        'car1.jpg',
        'car2.jpg',
        'car3.jpg'
    ];

    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(Article::class, 10, function (Article $article) use ($manager) {
            $article
                ->setTitle($this->faker->randomElement(self::$titles))
                ->setBody(
                    '**Жили-были на свете три поросенка**. [Три брата](/).' .
                    'Все одинакового роста, кругленькие, розовые, с одинаковыми веселыми хвостиками. ' .
                    'Даже имена у них были похожи. Звали поросят: Ниф-Ниф, Нуф-Нуф и Наф-Наф. ' .
                    $this->faker->paragraphs($this->faker->numberBetween(2, 5), true)
                );
            if ($this->faker->boolean(60)) {
                $article->setPublishedAt(
                    new DateTimeImmutable(
                        $this->faker->dateTimeBetween('-100 days')->format('Y-m-d h:i:s')
                    )
                );
            }

            $article
                ->setAuthor($this->faker->randomElement(self::$authors))
                ->setLikeCount($this->faker->numberBetween(0, 10))
                ->setImageFileName($this->faker->randomElement(self::$images));

            for ($i = 0; $i < $this->faker->numberBetween(2, 10); $i++) {
                $this->addComment($article, $manager);
            }
        });
    }

    /**
     * @param Article $article
     * @param ObjectManager $manager
     */
    public function addComment(Article $article, ObjectManager $manager): void
    {
        $comment = new Comment();
        $comment
            ->setAuthorName('Усатый-полосатый')
            ->setContent($this->faker->paragraph())
            ->setCreatedAt($this->faker->dateTimeBetween('- 100 days', '-1 day'))
            ->setArticle($article);

        if ($this->faker->boolean()) {
            $comment->setDeletedAt($this->faker->dateTimeThisMonth());
        }

        $manager->persist($comment);
    }
}
