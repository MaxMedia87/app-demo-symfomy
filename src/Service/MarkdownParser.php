<?php

declare(strict_types=1);

namespace App\Service;

use Demontpx\ParsedownBundle\Parsedown;
use Psr\Log\LoggerInterface;
use Symfony\Component\Cache\Adapter\AdapterInterface;

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
     * @param Parsedown $markDownParser
     * @param AdapterInterface $cacheStorage
     * @param LoggerInterface $markdownLogger
     */
    public function __construct(Parsedown $markDownParser, AdapterInterface $cacheStorage, LoggerInterface $markdownLogger)
    {
        $this->markDownParser = $markDownParser;
        $this->cacheStorage = $cacheStorage;
        $this->logger = $markdownLogger;
    }

    public function parse(string $source): string
    {
        if (false !== stripos($source, 'Нуф')) {
            $this->logger->info('Данный текст содержит запись о поросенке Нуф-Нуф');
        }

        return $this->cacheStorage->get(
            'markdown_' . md5($source),
            function() use ($source) {
                return $this->markDownParser->text($source);
            });
    }
}