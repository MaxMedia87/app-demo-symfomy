<?php

declare(strict_types=1);

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction('uploaded_asset', [AppUploadedAsset::class, 'asset'])
        ];
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('cachedMarkdownParser', [AppRuntime::class, 'parseMarkdown'], ['is_safe' => ['html']]),
            new TwigFilter('declension', [AppRuntime::class, 'declensionWords']),
        ];
    }
}
