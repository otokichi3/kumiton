<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Opas extends CI_Controller

{

    public function __construct()
    {
        parent::__construct();
		$this->load->helper('file');
    }

    public function index()
    {
        $this->_login(date('Ym'));
    }

    /**
     * OPAS にログインする
     *
     * @return string
     */
	private function _login(string $month = NULL)
	{
        // 月は未選択なら当月
        $month = $month ?? date('Ym');

        // Cookie情報を保存する一時ファイルディレクトリにファイルを作成します
        $tmp_path =  tempnam(sys_get_temp_dir(), 'cookie_');

        // OPAS にログインする
		$url       = OPAS_LOGIN_URL;
		$id_name   = 'txtRiyoshaCode';
		$pass_name = 'txtPassWord';

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
			'User-Agent: ' . USER_AGENT,
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

        // return $tmp_path;

        // 予約画面を開く
        $post_data = [
            'action'          => 'Enter',
            'txtProcId'       => '/menu/Menu',
            'txtFunctionCode' => 'YoyakuQuery',
        ];
		
        $post_data = http_build_query($post_data);
        
		$headers = [
			'Content-Type: application/x-www-form-urlencoded',
			'Content-Length: ' . strlen($post_data),
			'User-Agent: ' . USER_AGENT,
		];
        
		$url = OPAS_MENU_URL;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url); 
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $tmp_path);
        
        $html = curl_exec($ch);
		$html = mb_convert_encoding($html, 'utf-8', 'sjis');
        curl_close($ch);
        
        $post_data = [
            'action'                     => 'Setup',
            'txtProcId'                  => '/yoyaku/RiyoshaYoyakuList',
            'txtFunctionCode'            => 'YoyakuQuery',
            'selectedYoyakuUniqKey'      => '',
            'hiddenCorrectRiyoShinseiYM' => '',
            'hiddenCollectDisplayNum'    => '5',
            'pageIndex'                  => '1',
            'printedFlg'                 => '',
            'riyoShinseiYM'              => '201908',
            'reqDisplayInfoNum'          => '50',
        ];
		
        $post_data = http_build_query($post_data);
        
		$headers = [
			'Content-Type: application/x-www-form-urlencoded',
			'Content-Length: ' . strlen($post_data),
			'User-Agent: ' . USER_AGENT,
		];
        
		$url = OPAS_RES_URL;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url); 
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $tmp_path);
        
        $html = curl_exec($ch);
        $html = mb_convert_encoding($html, 'utf-8', 'sjis');

        // ライブラリ
        require_once 'simple_html_dom.php';
        $dom = file_get_html($html);

        curl_close($ch);
        dump($dom);
        die;
    }
    
    /**
     * 該当月の予約一覧を返す
     *
     * @param string $month
     * @return string
     */
    private function _get_yoyaku_list(string $month = NULL, string $path = NULL): string
    {
        // 月は未選択なら当月
        $month = $month ?? date('Ym');

        // 予約画面を開く
        $post_data = [
            'action'          => 'Enter',
            'txtProcId'       => '/menu/Menu',
            'txtFunctionCode' => 'YoyakuQuery',
        ];
		
        $post_data = http_build_query($post_data);
        
		$headers = [
			'Content-Type: application/x-www-form-urlencoded',
			'Content-Length: ' . strlen($post_data),
			'User-Agent: ' . USER_AGENT,
		];
        
		$url = OPAS_MENU_URL;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url); 
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $path);
        
        $html = curl_exec($ch);
		$html = mb_convert_encoding($html, 'utf-8', 'sjis');
        curl_close($ch);
        
        $post_data = [
            'action'                     => 'Setup',
            'txtProcId'                  => '/yoyaku/RiyoshaYoyakuList',
            'txtFunctionCode'            => 'YoyakuQuery',
            'selectedYoyakuUniqKey'      => '',
            'hiddenCorrectRiyoShinseiYM' => '',
            'hiddenCollectDisplayNum'    => '5',
            'pageIndex'                  => '1',
            'printedFlg'                 => '',
            'riyoShinseiYM'              => '201908',
            'reqDisplayInfoNum'          => '50',
        ];
		
        $post_data = http_build_query($post_data);
        
		$headers = [
			'Content-Type: application/x-www-form-urlencoded',
			'Content-Length: ' . strlen($post_data),
			'User-Agent: ' . USER_AGENT,
		];
        
		$url = OPAS_RES_URL;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url); 
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $tmp_path);
        // curl_setopt($ch, CURLOPT_COOKIEJAR, $tmp_path);
        
        $html = curl_exec($ch);
		$html = mb_convert_encoding($html, 'utf-8', 'sjis');
		dump($html);
        curl_close($ch);
        die;

        // return $tmp_path;
    }

}
