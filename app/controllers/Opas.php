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
		$doc  = phpQuery::newDocument($html);

        $table = $doc->find('#mmaincolumn')->find("table:eq(2)")->find("[onmouseover='reportOver(this);']");
        $tds = $table->find('td')->text();
        $tds = preg_replace('/(\t)|(\r\n)|( )/s', '', $tds);
        $tds = explode("\n", $tds);

        // remove trash
        foreach ($tds as $key => $val)
        {
            if (strlen(trim($val)) === 0)
            {
                unset($tds[$key]);
            }
        }

        $goal = array_chunk($tds, 5, FALSE);

        echo '<table border="1" style="text-align: center;">';
        echo '<tr style="background: lightgray;">';
        echo '<th>日にち</th>';
        echo '<th>場所</th>';
        echo '<th>体育場</th>';
        echo '<th>開始時間</th>';
        echo '<th>終了時間</th>';
        echo '</tr>';
        foreach ($goal as $key => $val)
        {
            echo '<tr>';
            foreach ($val as $key2 => $val2)
            {
                if ($key2 < 3)
                {
                    if ($key2 == 0)
                    {
                        echo "<td>" . $this->normalizeDate($val2) . "</td>";
                    }
                    elseif ($key2 == 1)
                    {
                        $place = explode('第', $val2);
                        echo "<td>{$place[0]}</td>";
                        echo "<td>第{$place[1]}</td>";
                    }
                    else
                    {
                        $time = explode('〜', $val2);
                        echo "<td>{$time[0]}</td>";
                        echo "<td>{$time[1]}</td>";
                    }
                }
            }
            echo '</tr>';
        }
        echo '</table>';
    }

    public function normalizeDate( $inStr ) {
        // 年月日の各パーツを分割する
        preg_match( "/([0-9]*)年([0-9]*)月([0-9]*)日/", $inStr, $data );
        if ( Count( $data ) != 4 ) {
            return $inStr;
        }

        // 先頭0埋めでYYYY-MM-DD形式の日付文字列に変換する
        $outStr = sprintf( "%04.4d-%02.2d-%02.2d", $data[1], $data[2], $data[3] );

        return $outStr;
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
