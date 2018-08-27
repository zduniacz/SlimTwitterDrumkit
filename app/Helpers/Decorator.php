<?php
namespace App\Helpers;

class Decorator
{
    public function replaceStringContent($string, $replacement, $startIndex, $length)
    {
        $startString = mb_substr($string, 0, $startIndex, "UTF-8");
        $endString = mb_substr($string, $startIndex + $length, mb_strlen($string), "UTF-8");
        $out = $startString . $replacement . $endString;
        return $out;
    }

    public function humanDate($date = '', $format = "d/m/Y")
    {
        return date($format, strtotime($date));
    }
}