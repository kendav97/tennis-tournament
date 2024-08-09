<?php

namespace App\Http\Helpers;

class NumericHelper
{
    public static function isPowerOfTwo(int $number): bool
    {
        return ($number & ($number - 1)) === 0;
    }

    public static function generatePowersOfTwo(int $quantity): array
    {
        $pows = [];
        for ($i = 1; $i <= $quantity; $i++) {
            $pows[] = pow(2, $i);
        }

        return $pows;
    }
}
