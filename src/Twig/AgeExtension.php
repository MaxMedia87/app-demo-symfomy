<?php

declare(strict_types=1);

namespace App\Twig;

use Carbon\Carbon;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AgeExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('ago', [$this, 'getDiff']),
        ];
    }

    public function getDiff($value): string
    {
        return Carbon::make($value)->locale('ru')->diffForHumans();
    }
}
