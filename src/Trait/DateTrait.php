<?php

namespace App\Trait;

use DateTimeImmutable;
use DateTimeInterface;

trait DateTrait
{
    /**
     * Resolve the range of dates based on the passed parameters.
     * 
     * @param int $lastDays
     * @param null|string $fromDate
     * @param null|string $toDate
     * 
     * @return array
     */
    protected function getDatesRange(int $lastDays, ?string $fromDate, ?string $toDate): array
    {
        switch (true) {
            // both dates are set, we can ignore the $lastDays parameter
            case $fromDate && $toDate:
                $fromDate = new DateTimeImmutable($fromDate);
                $toDate = new DateTimeImmutable($toDate);
                break;
            
            // only $fromDate is set, $toDate will be calculated based on $lastDays
            case $fromDate && !$toDate:
                $fromDate = new DateTimeImmutable($fromDate);
                $toDate = $fromDate->modify('+' . $lastDays . ' days');
                break;
            
            // only $toDate is set, $fromDate will be calculated based on $lastDays
            case !$fromDate && $toDate:
                $toDate = new DateTimeImmutable($toDate);
                $fromDate = $toDate->modify((-1 * $lastDays) . ' days');
                break;

            // both $fromDate and $toDate are not set, the dates will be calculated based on $lastDays only
            default:
                $fromDate = new DateTimeImmutable((-1 * $lastDays) . ' days');
                $toDate = new DateTimeImmutable();
        }

        return [$fromDate, $toDate];
    }

    /**
     * Groups a set of data by day. The elements must contain a valid 'datetime' field to be grouped.
     * 
     * @param iterable $data
     * 
     * @return array
     */
    protected function groupDataByDay(iterable $data): array
    {
        $groupedDates = [];
        foreach ($data as $element) {
            // skip the elements not containing a valid datetime
            if (empty($element['datetime']) || !($element['datetime'] instanceof DateTimeInterface)) {
                continue;
            }
            
            // get the day and group the element
            $day = $element['datetime']->format('Y-m-d');
            if (!isset($groupedDates[$day])) {
                $groupedDates[$day] = [];
            }
            
            $groupedDates[$day][] = $element;
        }

        return $groupedDates;
    }
}