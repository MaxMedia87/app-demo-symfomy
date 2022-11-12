<?php

namespace App\DataFixtures;

use App\Entity\ApiToken;
use App\Entity\Tag;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends BaseFixtures
{
    /**
     * @var UserPasswordHasherInterface
     */
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function loadData(ObjectManager $manager): void
    {
        $this->create(User::class, function (User $user) use ($manager) {
            $user
                ->setEmail('api@symfony.skillbox')
                ->setFirstName('Гена')
                ->setPassword($this->passwordHasher->hashPassword($user, '123456'))
                ->setIsActive(true)
                ->setRoles(['ROLE_API']);

            for ($i = 0; $i < 3; $i++) {
                $manager->persist(new ApiToken($user));
            }
        });

        $this->create(User::class, function (User $user) use ($manager) {
            $user
                ->setEmail('admin@catcascar.ru')
                ->setFirstName('Администратор')
                ->setPassword($this->passwordHasher->hashPassword($user, '123456'))
                ->setIsActive(true)
                ->setRoles(['ROLE_ADMIN'])
                ->setSubscribeToNewsletter(true);

            $manager->persist(new ApiToken($user));
        });

        $this->createMany(User::class, 10, function (User $user) use ($manager) {
            $user
                ->setEmail($this->faker->email)
                ->setFirstName($this->faker->firstName())
                ->setPassword($this->passwordHasher->hashPassword($user, '123456'))
                ->setIsActive(true);

            if ($this->faker->boolean(30)) {
                $user
                    ->setIsActive(false)
                    ->setSubscribeToNewsletter(true);
            }

            $manager->persist(new ApiToken($user));
        });
    }
}
