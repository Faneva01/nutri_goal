<?php

if (!function_exists('assets_url')) {

    function assets_url($path = '')
    {
        return base_url('assets/' . ltrim($path, '/'));
    }
}

if (!function_exists('img_url')) {

    function img_url($path = '')
    {
        return base_url('assets/images/' . ltrim($path, '/'));
    }
}

if (!function_exists('css_url')) {

    function css_url($path = '')
    {
        return base_url('assets/css/' . ltrim($path, '/'));
    }
}

if (!function_exists('js_url')) {

    function js_url($path = '')
    {
        return base_url('assets/js/' . ltrim($path, '/'));
    }
}

if (!function_exists('format_currency_smart')) {
    /**
     * Formate un montant en Ar de manière intelligente (10K, 1M, etc.)
     * @param float|int $amount
     * @return string
     */
    function format_currency_smart($amount)
    {
        if ($amount >= 1000000) {
            return number_format($amount / 1000000, 2, '.', ' ') . 'M Ar';
        } elseif ($amount >= 10000) {
            return number_format($amount / 1000, 2, '.', ' ') . 'K Ar';
        }
        return number_format($amount, 0, '.', ' ') . ' Ar';
    }
}