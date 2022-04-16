<?php

namespace App\Form\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class IsNotSpamBot extends Constraint
{
    public $message = 'Ботам здесь не место';

    public function validatedBy(): string
    {
        return static::class . 'Validator';
    }

    public function getTargets()
    {
        return self::PROPERTY_CONSTRAINT;
    }
}
