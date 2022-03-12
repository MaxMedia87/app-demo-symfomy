<?php

namespace App\DataFixtures;

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
        $this->create(User::class, function (User $user) {
            $user
                ->setEmail('admin@catcascar.ru')
                ->setFirstName('Администратор')
                ->setPassword($this->passwordHasher->hashPassword($user, '123456'))
                ->setIsActive(true)
                ->setRoles(['ROLE_ADMIN']);
        });

        $this->createMany(User::class, 10, function (User $user) {
            $user
                ->setEmail($this->faker->email)
                ->setFirstName($this->faker->firstName())
                ->setPassword($this->passwordHasher->hashPassword($user, '123456'))
                ->setIsActive(true);

            if ($this->faker->boolean(30)) {
                $user->setIsActive(false);
            }
        });
    }
}
