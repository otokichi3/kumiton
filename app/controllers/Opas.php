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
		// $this->_login(date('Ym'));
        $this->_get_list();
    }

	private function _get_list()
	{
		require_once("phpQuery-onefile.php");
		$html = read_file('opas_reservation.txt');
		// dump($html);
		// $this->_delete_two_rows('opas_reservation.txt');

		$doc  = phpQuery::newDocument($html);

		// $test = $doc->find('#mmaincolumn')->text();
		// $test = $doc->find('#mmaincolumn')->html();
		$test = $doc->find('#mmaincolumn')->find("table:eq(2)")->html();
		// $test = $test->find('table');
        $test = mb_convert_encoding($test,'UTF-8','sjis');
		echo($test);
		die;
		$domDocument = new DOMDocument();
		$domDocument->loadHTML($html);
		$xmlString = $domDocument->saveXML();
		$xmlObject = simplexml_load_string($xmlString);
		$array = json_decode(json_encode($xmlObject), TRUE);
        // dump($array['body']['div']['form'][1]['div'][2]['div']['table'][1]['tr'][1]['th']);
        // unset($array['body']['div']['form'][1]['div'][2]['div']['table'][1]['tr'][0]);
        // unset($array['body']['div']['form'][1]['div'][2]['div']['table'][1]['tr'][1]);
        // $tr = $array['body']['div']['form'][1]['div'][2]['div']['table'][1]['tr'];
        // $array = mb_convert_encoding($array,'UTF-8','sjis');
        // $tr = $array['body']['div']['form'][1]['div'][2]['div'];

        // dump($tr);
        // foreach ($tr as $key => $val)
        // {
        //     dump(preg_replace('/(\t|\r\n|\r|\n)/s', '', $val['td'][1]));
        //     dump(preg_replace('/(\t|\r\n|\r|\n)/s', '', $val['td'][2]));
        // }
	}


	/**
	 * ファイルの先頭二行を削除する
	 *
	 * @param string $filename
	 * @return void
	 */
	private function _delete_two_rows(string $filename)
	{

		// 配列として取得
		$arr = file($filename);

		if (count($arr) == 0) {
			return;
		}

		// 配列の先頭二行を削除
		array_shift($arr);
		array_shift($arr);

		// 上書き書き込み
		write_file($filename, implode($arr));
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
        // $html = mb_convert_encoding($html, 'utf-8', 'sjis');

        // ライブラリ
        // require_once 'simple_html_dom.php';
        // $dom = file_get_html($html);

        curl_close($ch);
		if ( ! write_file('opas_reservation.txt', $html))
		{
				echo 'ファイルに書き込めません';
		}
		else
		{
				echo 'ファイルが書き込まれました！';
		}
        dump($html);
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
