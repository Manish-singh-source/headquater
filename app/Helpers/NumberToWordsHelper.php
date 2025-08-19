<?php

use NumberFormatter;
use NumberToWords\NumberToWords;

if (!function_exists('amountInWords')) {
    function amountInWords($amount)
    {
        // Use PHP Intl NumberFormatter
        $formatter = new NumberFormatter('en_IN', NumberFormatter::SPELLOUT);

        $integerPart = floor($amount); // Rupees
        $fractionPart = round(($amount - $integerPart) * 100); // Paise

        $words = ucfirst($formatter->format($integerPart)) . ' rupees';

        if ($fractionPart > 0) {
            $words .= ' and ' . $formatter->format($fractionPart) . ' paise';
        }

        return $words . ' only';
    }
}
