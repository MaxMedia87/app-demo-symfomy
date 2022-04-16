<?php

namespace App\Service;

class RegistrationSpamFilter
{
    protected const SUPPORTED_ZONES = ['ru', 'com', 'org'];

    /**
     * @param string|null $email
     *
     * @return bool
     */
    public function filter(?string $email): bool
    {
        if (false === filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return true;
        }

        preg_match('/@([\da-z\.-]+)\.([a-z\.]{2,6})$/', $email, $matches);

        $domainZone = $matches[2];

        if (false === in_array($domainZone, self::SUPPORTED_ZONES)) {
            return true;
        }

        return false;
    }
}
