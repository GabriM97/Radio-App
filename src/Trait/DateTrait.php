<?php

namespace App\Trait;

use DateTimeImmutable;

trait DateTrait
{
    protected function getDatesRange(int $lastDays, ?string $fromDate, ?string $toDate): array
    {
        switch (true) {
            case $fromDate && $toDate:
                // ignore "$lastDays"
                $fromDate = new DateTimeImmutable($fromDate);
                $toDate = new DateTimeImmutable($toDate);
                break;
            
            case $fromDate && !$toDate:
                $fromDate = new DateTimeImmutable($fromDate);
                $toDate = $fromDate->modify('+' . $lastDays . ' days');
                break;
            
            case !$fromDate && $toDate:
                $toDate = new DateTimeImmutable($toDate);
                $fromDate = $toDate->modify((-1 * $lastDays) . ' days');
                break;

            default:    // !$fromDate && !$toDate
                $fromDate = new DateTimeImmutable((-1 * $lastDays) . ' days');
                $toDate = new DateTimeImmutable();
        }

        return [$fromDate, $toDate];
    }

    protected function groupDataByDay(array $data): array
    {
        $groupedDates = [];
        foreach ($data as $element) {
            if (empty($element['datetime'])) {
                continue;
            }
            
            $day = $element['datetime']->format('Y-m-d');
            if (!isset($groupedDates[$day])) {
                $groupedDates[$day] = [];
            }

            $groupedDates[$day][] = $element;
        }

        return $groupedDates;
    }
}