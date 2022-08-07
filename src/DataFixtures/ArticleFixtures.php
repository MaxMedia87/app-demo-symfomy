<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Tag;
use App\Entity\User;
use App\Service\FileUploader;
use DateTimeImmutable;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;

class ArticleFixtures extends BaseFixtures implements DependentFixtureInterface
{
    private static $titles = [
        'Есть ли жизнь после девятой жизни?',
        'Когда в машинах поставят лоток?',
        'В погоне за красной точкой',
        'В чем смысл жизни сосисок',
    ];

    private static $images = [
        'car1.jpg',
        'car2.jpg',
        'car3.jpg'
    ];

    /**
     * @var FileUploader
     */
    private $articleFileUploader;

    public function __construct(FileUploader $articleFileUploader)
    {
        $this->articleFileUploader = $articleFileUploader;
    }

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

            /** @var User $author */
            $author = $this->getRandomReference(User::class);

            $fileName = $this->faker->randomElement(self::$images);
            $originalFile = dirname(dirname(__DIR__)) . '/public/images/' . $fileName;

            $article
                ->setAuthor($author)
                ->setLikeCount($this->faker->numberBetween(0, 10))
                ->setImageFileName(
                    $this->articleFileUploader->uploadFile(
                        new File($originalFile)
                    )
                );

            /** @var Tag[] $tags */
            $tags = [];

            for ($i = 0; $i < $this->faker->numberBetween(0, 5); $i++) {
                $tags[] = $this->getRandomReference(Tag::class);
            }

            foreach ($tags as $tag) {
                $article->addTag($tag);
            }
        });
    }

    public function getDependencies(): array
    {
        return [
            TagFixtures::class,
            UserFixtures::class
        ];
    }
}
