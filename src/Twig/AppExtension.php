<?php

declare(strict_types=1);

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('cachedMarkdownParser', [AppRuntime::class, 'parseMarkdown'], ['is_safe' => ['html']]),
            new TwigFilter('declension', [AppRuntime::class, 'declensionWords']),
        ];
    }
}
