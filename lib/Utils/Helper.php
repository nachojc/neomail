<?php
namespace NeoMail\Utils;
if(!defined('ABSPATH')) exit;

class Helper{
    static function isDateFormat($dte){
        $date = explode(' ',$dte);
        if (sizeof($date)==2){
            list($y, $m, $d) = explode('-', $date[0]);
            $h = explode(':', $date[1]);
            return sizeof($h)==3 && is_numeric($h[0]) && is_numeric($h[1]) && is_numeric($h[2])
                    && is_numeric($y) && is_numeric($m) && is_numeric($d);
        }
        return false;
    }

}