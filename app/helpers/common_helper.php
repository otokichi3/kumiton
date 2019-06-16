<?php
if ( ! defined('BASEPATH'))
{
	exit('No direct script access allowed');
}

if ( ! function_exists('dump'))
{
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

if ( ! function_exists('send_line'))
{
    /**
     * LINE にメッセージを送る
     *
     * @param [type] $token
     * @param [type] $msg
     * @return void
     */
    function send_line($token, $msg)
    {
        // リクエストヘッダの作成
        $query = http_build_query(['message' => $msg]);
        $header = [
            'Content-Type: application/x-www-form-urlencoded',
            'Authorization: Bearer ' . $token,
            'Content-Length: ' . strlen($query)
        ];

        $ch = curl_init(LINE_NOTIFY_URL);
        $options = [
            CURLOPT_RETURNTRANSFER  => TRUE,
            CURLOPT_POST            => TRUE,
            CURLOPT_HTTPHEADER      => $header,
            CURLOPT_POSTFIELDS      => $query
        ];

        curl_setopt_array($ch, $options);
        curl_exec($ch);
        curl_close($ch);
    }
}
