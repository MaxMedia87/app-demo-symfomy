<?php

namespace App\Form\Validator;

use Symfony\Component\Validator\Constraints\Compound;

/**
 * @Annotation
 */
class EmailRequirements extends Compound
{
    protected function getConstraints(array $options): array
    {
        return [
            new IsNotSpamBot(),
            new UniqueUser()
        ];
    }
}
