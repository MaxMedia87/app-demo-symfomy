<?php

namespace App\Service;

use Psr\Log\LoggerInterface;

class ApiLogger
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function warning($message, array $context = array()): void
    {
        $this->logger->warning($message, $context);
    }
}
