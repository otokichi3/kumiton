<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Opas extends CI_Controller

{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $cookie_path = $this->_login();
        // $this->_yoyaku($cookie_path);
    }

    /**
     * OPAS にログインする
     *
     * @return string
     */
	private function _login(): string
	{
        // Cookie情報を保存する一時ファイルディレクトリにファイルを作成します
        $tmp_path =  tempnam(sys_get_temp_dir(), 'CKI');

		$url       = OPAS_LOGIN_URL;
		$id_name   = 'txtRiyoshaCode';
		$pass_name = 'txtPassWord';
        $ua        = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/69.0.3497.100';

		$post_data = [
			'action'    => 'Enter',
			'txtProcId' => '/menu/Login',
			$id_name    => OPAS_ID,
			$pass_name  => OPAS_PW,
        ];
        $post_data = http_build_query($post_data);
        
		$headers = [
			'Content-Type: application/x-www-form-urlencoded',
			'Content-Length: ' . strlen($post_data),
			'User-Agent: ' . $ua,
		];
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url); 
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $tmp_path);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $tmp_path);
        
        $html = curl_exec($ch);
        curl_close($ch);
        // dump($html);

		$post_data = [
			'action'          => 'Enter',
			'txtProcId'       => '/menu/Menu',
			'txtFunctionCode' => 'Yoyaku',
        ];
        $post_data = http_build_query($post_data);
        
		$headers = [
			'Content-Type: application/x-www-form-urlencoded',
			'Content-Length: ' . strlen($post_data),
			'User-Agent: ' . $ua,
		];
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url); 
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $tmp_path);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $tmp_path);
        
        $html = curl_exec($ch);
		$html = mb_convert_encoding($html, 'utf-8', 'sjis');
        curl_close($ch);
        dump($html);

        return $tmp_path;
    }

    /**
     * 「空き紹介・予約」に遷移する
     *
     * @return void
     */
	private function _yoyaku(string $path)
	{
        $this->load->helper('file');
        $string = read_file($path);
        dump($string);
        die;
		$url       = OPAS_LOGIN_URL;
		$id_name   = 'txtRiyoshaCode';
		$pass_name = 'txtPassWord';
		$ua        = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/69.0.3497.100';
		$data = [
			'action'    => 'Enter',
			'txtProcId' => '/menu/Login',
			$id_name    => OPAS_ID,
			$pass_name  => OPAS_PW,
		];
		$data = http_build_query($data, '', '&');

		$header = [
			'Content-Type: application/x-www-form-urlencoded',
			'Content-Length: ' . strlen($data),
			'User-Agent: ' . $ua,
		];

		$context = [
			'http' => [
				'method'  => 'POST',
				'header'  => implode('\r\n', $header),
				'content' => $data,
			],
		];
		
		$html = file_get_contents($url, FALSE, stream_context_create($context));
		$html = mb_convert_encoding($html, 'utf-8', 'sjis');
		echo $html;
	}


}
