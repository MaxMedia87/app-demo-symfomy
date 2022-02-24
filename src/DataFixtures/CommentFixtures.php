<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Comment;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CommentFixtures extends BaseFixtures implements DependentFixtureInterface
{
    public function loadData(ObjectManager $manager)
    {
        $this->createMany(Comment::class, 100, function (Comment $comment) {

            /** @var Article $article */
            $article = $this->getRandomReference(Article::class);

            $comment
                ->setAuthorName($this->faker->name())
                ->setContent($this->faker->paragraph())
                ->setCreatedAt($this->faker->dateTimeBetween('- 100 days', '-1 day'))
                ->setArticle($article);

            if ($this->faker->boolean()) {
                $comment->setDeletedAt($this->faker->dateTimeThisMonth());
            }
        });
    }

    /**
     * @return array
     */
    public function getDependencies(): array
    {
        return [
            ArticleFixtures::class
        ];
    }
}
