<?php

namespace App\Form\Validator;

use App\Repository\UserRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueUserValidator extends ConstraintValidator
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function validate($value, Constraint $constraint)
    {
        if (false === $constraint instanceof UniqueUser) {
            throw new UnexpectedTypeException($constraint, UniqueUser::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (null === $this->userRepository->findOneBy(['email' => $value])) {
            return;
        }

        $this->context->buildViolation($constraint->message)
            ->setParameter('{{ value }}', $value)
            ->addViolation();
    }
}
