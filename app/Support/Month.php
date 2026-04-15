<?php

namespace App\Support;

final class Month
{
    /**
     * Normalize a month string to YYYY-MM (e.g. "2026-04").
     * Falls back to the current month when invalid.
     *
     * @return array{0:string,1:int,2:int} [$month, $year, $monthNum]
     */
    public static function normalize(string|null $value): array
    {
        $month = is_string($value) ? trim($value) : '';

        if (! preg_match('/^\d{4}-\d{2}$/', $month)) {
            $month = date('Y-m');
        }

        [$year, $monthNum] = array_map('intval', explode('-', $month, 2));

        if (! checkdate($monthNum, 1, $year)) {
            $month = date('Y-m');
            [$year, $monthNum] = array_map('intval', explode('-', $month, 2));
        }

        return [$month, $year, $monthNum];
    }
}

