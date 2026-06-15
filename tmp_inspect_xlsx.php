<?php

require __DIR__ . '/vendor/autoload.php';

use Spatie\SimpleExcel\SimpleExcelReader;

$path = 'C:/Users/Manish-Technofra/Downloads/Packaging-Products (47).xlsx';

$reader = SimpleExcelReader::create($path);

$count = 0;
foreach ($reader->getRows() as $row) {
    echo "ROW " . ($count + 1) . PHP_EOL;
    print_r($row);
    echo PHP_EOL;
    $count++;
    if ($count >= 3) {
        break;
    }
}
