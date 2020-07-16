<?php

namespace App\Helpers;

use App\Helpers\CleanerHelper;

class StandardDateTimeHelper
{
    public const MONTH = [
        'Januari' => 1,
        'Februari' => 2,
        'Maret' => 3,
        'April' => 4,
        'Mei' => 5,
        'Juni' => 6,
        'Juli' => 7,
        'Agustus' => 8,
        'September' => 9,
        'Oktober' => 10,
        'November' => 11,
        'Desember' => 12
    ];

    public function standardized($time)
    {
        $cleaner = new CleanerHelper();

        $dt = explode(',', $time);
        $rawdt = explode(' ', $cleaner->removeWhiteSpace($dt[1]));

        $day = $rawdt[0];
        $month = $rawdt[1];
        $year = $rawdt[2];

        if(array_key_exists($month, $this::MONTH))
        {
            return $year . '-' . $this::MONTH[$month] . '-' . $day;
        }
    }
}