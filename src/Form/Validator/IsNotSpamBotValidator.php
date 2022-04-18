<?php

namespace App\Form\Validator;

use App\Service\RegistrationSpamFilter;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class IsNotSpamBotValidator extends ConstraintValidator
{
    /**
     * @var RegistrationSpamFilter
     */
    private $registrationSpamFilter;

    public function __construct(RegistrationSpamFilter $registrationSpamFilter)
    {
        $this->registrationSpamFilter = $registrationSpamFilter;
    }

    /**
     * @inheritDoc
     */
    public function validate($value, Constraint $constraint)
    {
        if (false === $constraint instanceof IsNotSpamBot) {
            throw new UnexpectedTypeException($constraint, IsNotSpamBot::class);
        }

        if (true === $this->registrationSpamFilter->filter($value)) {
            $this
                ->context
                ->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
