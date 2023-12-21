<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	http://codeigniter.com/user_guide/general/hooks.html
|
*/

function WL_DUMP($data) {
    echo "<pre>" . var_export ( $data,true ) . "</pre>";
}

function CONVERT_TO_CURRENCY($number) {
    return number_format($number,0);
}

function GET_DIFF_DAYS($date) {
    #$current_time = strtotime("2019-03-08 00:00:00");
    $current_time = time();
    return floor(($current_time - strtotime($date))/86400);
}
