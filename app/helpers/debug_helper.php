<?php  if (! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('dump')) {
    /**
     * var_export を pre タグで囲んで出力するラッパー
     *
     * @param [type] $arg
     * @return void
     */
    function dump($arg)
    {
        echo '<pre>';
        var_export($arg);
        echo '</pre>';
    }
}
