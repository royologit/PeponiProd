<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('create_index_query'))
{
    function create_index_query($table_name, $index_name, array $index_fields, $index_type = null, $add_suffix = true) {
        $CI =& get_instance();
        $db_name = $CI->db->database;

        $index_type = strtoupper($index_type);
        if ($add_suffix) {
            $index_name = strpos($index_name, '_INDEX')
                ? $index_name
                : $index_name.'_INDEX';
        }

        array_walk($index_fields, function(&$index) {
            if(!strpos(strtolower($index), 'ASC') && !strpos(strtolower($index), 'DESC')) {
                $index = $index.' ASC';
            }
        });

        return $index_type
            ? "ALTER TABLE `$db_name`.`$table_name` ADD $index_type INDEX $index_name (".implode(', ', $index_fields)."), ALGORITHM=INPLACE, LOCK=NONE;"
            : "ALTER TABLE `$db_name`.`$table_name` ADD INDEX $index_name (".implode(', ', $index_fields)."), ALGORITHM=INPLACE, LOCK=NONE;";
    }
}

if (!function_exists('lastPage')) {
    function lastPage()
    {
        $CI       =& get_instance();
        $lastPage = $CI->input->server("HTTP_REFERER");

        if (parse_url($lastPage, PHP_URL_HOST) != parse_url(site_url(''), PHP_URL_HOST)) {
            return site_url('/');
        } else {
            return $lastPage;
        }
    }
}

if (!function_exists('current_full_url')) {
    function current_full_url($additional_get_params = [], $get_params_to_be_removed = [])
    {
        $CI =& get_instance();

        parse_str($_SERVER['QUERY_STRING'], $query);

        foreach ($additional_get_params as $key => $value) {
            $query[$key] = $value;
        }

        foreach ($get_params_to_be_removed as $key) {
            if (isset($query[$key])) {
                unset($query[$key]);
            }
        }

        $query = http_build_query($query, '', '&');

        $url = $CI->config->site_url($CI->uri->uri_string());

        return $query ? $url.'?'.$query : $url;
    }
}

if (!function_exists('currency_format')) {
    function currency_format($value, $withRp = true)
    {
        $formattedValue = number_format($value, 0, ',', '.');

        if ($withRp) {
            return "Rp. " . $formattedValue;
        } else {
            return $formattedValue;
        }
    }
}