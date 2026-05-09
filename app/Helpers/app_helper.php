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