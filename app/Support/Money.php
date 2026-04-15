<?php

namespace App\Support;

final class Money
{
    public static function roundDivToCents(int $numeratorCents, int $denominatorUnits): int
    {
        if ($denominatorUnits <= 0) {
            return 0;
        }

        $q = intdiv($numeratorCents, $denominatorUnits);
        $r = $numeratorCents % $denominatorUnits;

        return ($r * 2 >= $denominatorUnits) ? $q + 1 : $q;
    }

    public static function centsToString(int $cents): string
    {
        $sign = $cents < 0 ? '-' : '';
        $abs = abs($cents);
        $whole = intdiv($abs, 100);
        $fraction = str_pad((string) ($abs % 100), 2, '0', STR_PAD_LEFT);

        return $sign.$whole.'.'.$fraction;
    }
}

