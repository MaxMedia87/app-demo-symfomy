<?php

namespace App\Form\Model;

use App\Form\Validator\EmailRequirements;
use Symfony\Component\Validator\Constraints as Assert;

class UserRegistrationFormModel
{
    /**
     * @Assert\NotBlank()
     * @Assert\Email()
     * @EmailRequirements()
     */
    public $email;

    public $firstName;

    /**
     * @Assert\Length(min="6", minMessage="Пароль должен быть не менее 6 символов.")
     * @Assert\NotBlank(message="Пароль не указан.")
     */
    public $plainPassword;

    /**
     * @Assert\IsTrue(message="Подтвердите согласие на обработку")
     */
    public $agreeTerms;
}
