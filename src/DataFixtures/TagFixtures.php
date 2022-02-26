<?php

namespace App\DataFixtures;

use App\Entity\Tag;
use Doctrine\Persistence\ObjectManager;

class TagFixtures extends BaseFixtures
{
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(Tag::class, 10, function (Tag $tag) {
            $tag->setName($this->faker->realText(10));
        });
    }
}
