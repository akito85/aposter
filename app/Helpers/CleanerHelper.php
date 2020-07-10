<?php

namespace App\Helpers;

class CleanerHelper
{
    public function removeWhiteSpace($text)
    {
        $text = preg_replace('/[\t\n\r\0\x0B]/', '', $text);
        $text = preg_replace('/([\s])\1+/', ' ', $text);
        $text = trim($text);

        return $text;
    }
}