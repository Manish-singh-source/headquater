<?php

if (!function_exists('numberToWords')) {

    function numberToWords($num)
    {
        $ones = [
            "", "One", "Two", "Three", "Four", "Five", "Six",
            "Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve",
            "Thirteen", "Fourteen", "Fifteen", "Sixteen",
            "Seventeen", "Eighteen", "Nineteen"
        ];

        $tens = [
            "", "", "Twenty", "Thirty", "Forty", "Fifty",
            "Sixty", "Seventy", "Eighty", "Ninety"
        ];

        $levels = [
            "", "Thousand", "Lakh", "Crore"
        ];

        if ($num == 0) {
            return "Zero";
        }

        $result = "";

        // Break number into Indian format chunks
        $counter = 0;

        while ($num > 0) {

            if ($counter == 0) {
                $chunk = $num % 1000; // first chunk 3 digits
                $num = intval($num / 1000);
            } else {
                $chunk = $num % 100; // next chunks 2 digits
                $num = intval($num / 100);
            }

            if ($chunk != 0) {
                $chunkWords = "";

                if ($chunk > 99) {
                    $chunkWords .= $ones[intval($chunk / 100)] . " Hundred ";
                    $chunk = $chunk % 100;
                }

                if ($chunk > 19) {
                    $chunkWords .= $tens[intval($chunk / 10)] . " ";
                    if ($chunk % 10 != 0) {
                        $chunkWords .= $ones[$chunk % 10] . " ";
                    }
                } elseif ($chunk > 0) {
                    $chunkWords .= $ones[$chunk] . " ";
                }

                $result = $chunkWords . $levels[$counter] . " " . $result;
            }

            $counter++;
        }

        return trim($result);
    }
}
