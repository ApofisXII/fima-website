<?php

declare(strict_types=1);

namespace App\Twig;

use IntlDateFormatter;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class DateExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('locale_date', $this->localeDate(...)),
        ];
    }

    public function localeDate(\DateTimeInterface $date, string $pattern, string $locale): string
    {
        $formatter = new IntlDateFormatter(
            $locale,
            IntlDateFormatter::NONE,
            IntlDateFormatter::NONE,
            $date->getTimezone(),
            null,
            $pattern,
        );

        return $formatter->format($date) ?: '';
    }
}
