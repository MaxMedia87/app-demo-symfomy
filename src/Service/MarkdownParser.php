<?php

declare(strict_types=1);

namespace App\Service;

use Demontpx\ParsedownBundle\Parsedown;
use Psr\Log\LoggerInterface;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\Security\Core\Security;

class MarkdownParser
{
    /**
     * @var Parsedown
     */
    private $markDownParser;

    /**
     * @var AdapterInterface
     */
    private $cacheStorage;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var bool
     */
    private $isDebug;

    /**
     * @var Security
     */
    private $security;

    /**
     * @param Parsedown $markDownParser
     * @param AdapterInterface $cacheStorage
     * @param LoggerInterface $logger
     * @param Security $security
     * @param bool $isDebug
     */
    public function __construct(
        Parsedown $markDownParser,
        AdapterInterface $cacheStorage,
        LoggerInterface $logger,
        Security $security,
        bool $isDebug
    ) {
        $this->markDownParser = $markDownParser;
        $this->cacheStorage = $cacheStorage;
        $this->logger = $logger;
        $this->isDebug = $isDebug;
        $this->security = $security;
    }

    public function parse(string $source): string
    {
        if (false !== stripos($source, 'Нуф')) {
            $this->logger->info(
                'Данный текст содержит запись о поросенке Нуф-Нуф',
                [
                    'user' => $this->security->getUser()
                ]
            );
        }

        if (true === $this->isDebug) {
            return $this->markDownParser->text($source);
        }

        return $this->cacheStorage->get(
            'markdown_' . md5($source),
            function() use ($source) {
                return $this->markDownParser->text($source);
            });
    }
}
