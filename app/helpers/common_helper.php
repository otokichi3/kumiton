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

if ( ! function_exists('to_normal_date'))
{
    /**
     * 年月日表記に変換する
     *
     * @param [type] $str
     * @return void
     */
	function to_normal_date($str)
	{
        // 年月日の各パーツを分割する
        preg_match('/([0-9]*)年([0-9]*)月([0-9]*)日/', $str, $data);

        if (count($data) !== 4)
        {
            return $str;
        }

        // 先頭0埋めでYYYY-MM-DD形式の日付文字列に変換する
        $res = sprintf('%04.4d-%02.2d-%02.2d', $data[1], $data[2], $data[3]);

        return $res;
    }
}

if ( ! function_exists('delete_two_rows'))
{
    /**
     * ファイルの先頭二行を削除する
     *
     * @param string $filename
     * @return void
     */
    function delete_two_rows(string $filename)
    {
        // 配列として取得
        $arr = file($filename);

        if (count($arr) === 0) {
            return;
        }

        // 配列の先頭二行を削除
        array_shift($arr);
        array_shift($arr);

        // 上書き書き込み
        write_file($filename, implode($arr));
    }
}
