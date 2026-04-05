<?php

namespace App\Helpers;

class EmptyColumns
{
    public static function check($record, $requiredColumns)
    {
        foreach ($requiredColumns as $column) {
            if (! isset($record[$column]) || empty($record[$column])) {
                return false;
            }
        }

        return true;
    }
}
