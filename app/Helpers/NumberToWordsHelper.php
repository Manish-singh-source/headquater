<?php

// use NumberFormatter;
// use NumberToWords\NumberToWords;

// if (!function_exists('amountInWords')) {
//     function amountInWords($amount)
//     {
//         // Use PHP Intl NumberFormatter
//         $formatter = new NumberFormatter('en_IN', NumberFormatter::SPELLOUT);

//         $integerPart = floor($amount); // Rupees
//         $fractionPart = round(($amount - $integerPart) * 100); // Paise

//         $words = ucfirst($formatter->format($integerPart)) . ' rupees';

//         if ($fractionPart > 0) {
//             $words .= ' and ' . $formatter->format($fractionPart) . ' paise';
//         }

//         return $words . ' only';
//     }
// }

function numberToWords($number)
{
    $words = [
        0 => '', 1 => 'one', 2 => 'two', 3 => 'three', 4 => 'four',
        5 => 'five', 6 => 'six', 7 => 'seven', 8 => 'eight', 9 => 'nine',
        10 => 'ten', 11 => 'eleven', 12 => 'twelve', 13 => 'thirteen',
        14 => 'fourteen', 15 => 'fifteen', 16 => 'sixteen', 17 => 'seventeen',
        18 => 'eighteen', 19 => 'nineteen', 20 => 'twenty', 30 => 'thirty',
        40 => 'forty', 50 => 'fifty', 60 => 'sixty', 70 => 'seventy',
        80 => 'eighty', 90 => 'ninety'
    ];

    $units = ['', 'thousand', 'lakh', 'crore'];

    if ($number == 0) return 'zero';

    $numStr = str_pad($number, 9, "0", STR_PAD_LEFT); // pad to 9 digits
    $crore = (int)substr($numStr, 0, 2);
    $lakh  = (int)substr($numStr, 2, 2);
    $thousand = (int)substr($numStr, 4, 2);
    $hundred = (int)substr($numStr, 6, 1);
    $tenUnits = (int)substr($numStr, 7, 2);

    $result = '';

    if ($crore) {
        $result .= convertTwoDigits($crore, $words) . ' crore ';
    }
    if ($lakh) {
        $result .= convertTwoDigits($lakh, $words) . ' lakh ';
    }
    if ($thousand) {
        $result .= convertTwoDigits($thousand, $words) . ' thousand ';
    }
    if ($hundred) {
        $result .= $words[$hundred] . ' hundred ';
        if ($tenUnits) $result .= 'and ';
    }
    if ($tenUnits) {
        $result .= convertTwoDigits($tenUnits, $words);
    }

    return trim($result);
}

function convertTwoDigits($number, $words)
{
    if ($number < 21) return $words[$number];
    $tens = (int)($number / 10) * 10;
    $unit = $number % 10;
    return $words[$tens] . ($unit ? ' ' . $words[$unit] : '');
}
