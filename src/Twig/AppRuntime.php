<?php

declare(strict_types=1);

namespace App\Twig;

use App\Service\MarkdownParser;
use Twig\Extension\RuntimeExtensionInterface;

class AppRuntime implements RuntimeExtensionInterface
{
    /**
     * @var MarkdownParser
     */
    private $markdownParser;

    public function __construct(MarkdownParser $markdownParser)
    {
        $this->markdownParser = $markdownParser;
    }

    public function parseMarkdown(string $content): string
    {
       return $this->markdownParser->parse($content);
    }

    public function declensionWords(int $number, array $words): string
    {
        $num = ($n = abs($number) % 100) > 19 ? $n % 10 : $n;

        if ($num === 1) {
            return $words[0];
        }

        if (1 < $num && $num < 5) {
            return $words[1];
        }

        return $words[2];
    }
}
