<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Opas extends CI_Controller
{
	public $view_data   = [];

    public function __construct()
    {
        parent::__construct();
		$this->load->helper('file');
        $this->load->model('Gym_model');
        $this->_basic_auth();
    }

    public function index()
    {
		$this->title      = 'OPASログイン';
        $this->title_lead = '利用者番号とパスワードでOPASにログインし予約情報を取得します';

		$this->view_data = [
			'title'        => $this->title,
			'title_lead'   => $this->title_lead,
        ];

        $this->load->view('header');
		$this->load->view('opas/index', $this->view_data);
        $this->load->view('footer');
    }

    public function login()
    {
        $id       = $this->input->post('id');
        $password = $this->input->post('password');
        $month    = $this->input->post('month');

		$this->_opas_login($id, $password, $month);
        $this->_get_list($id);
    }

	private function _get_list(string $id)
	{
		require_once("phpQuery-onefile.php");

		$html = read_file(GYM_TXT);
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

        $list = array_chunk($tds, 5, FALSE);

		$gym_list = [];
		// 配列を加工する
		foreach ($list as $key => &$gyms)
		{
			array_splice($gyms, 3);
			$names = explode('第', $gyms[1]);
            $times = explode('〜', $gyms[2]);
            $canceled = (bool)(strpos($names[0], '【取消済み】') !== FALSE);

			$gym_list[$key]['date']      = $this->normalizeDate($gyms[0]);
			$gym_list[$key]['name']      = str_replace('【取消済み】', '', $names[0]);
			$gym_list[$key]['place']     = sprintf('第%s', $names[1]);
			$gym_list[$key]['time_from'] = $times[0];
			$gym_list[$key]['time_to']   = $times[1];
			$gym_list[$key]['canceled']  = $canceled;
		}

		foreach ($gym_list as $key => $gym)
		{
			// todo:同じものがあれば更新しない
			$params = [
				'opas_id'   => $id,
				'date'      => $gym['date'],
				'name'      => $gym['name'],
				'place'     => $gym['place'],
				'time_from' => $gym['time_from'],
				'time_to'   => $gym['time_to'],
				'canceled'  => $gym['canceled'],
            ];
			$this->db->insert('t_gym_reservation', $params);
		}

        echo '<table border="1" style="text-align: center;">';
        echo '<tr style="background: lightgray;">';
        echo '<th>日にち</th>';
        echo '<th>場所</th>';
        echo '<th>体育場</th>';
        echo '<th>開始時間</th>';
        echo '<th>終了時間</th>';
        echo '<th>取り消し</th>';
        echo '</tr>';
        foreach ($gym_list as $idx => $gym)
        {
            echo '<tr>';
            foreach ($gym as $key => $val)
            {
                if ($key === 'canceled')
                {
                    echo sprintf('<td>%s</td>', $val ? 'yes' : 'no');
                }
                else
                {
                    echo '<td>' . $val . '</td>';
                }
            }
            echo '</tr>';
        }
        echo '</table>';
    }

    public function reservation_txt()
    {
        $week = array( "日", "月", "火", "水", "木", "金", "土" );

        $sql = 'SELECT * FROM t_gym_reservation WHERE canceled = FALSE;';
        $query = $this->db->query($sql);
        foreach ($query->result() as $key => $val)
        {
            $time      = strtotime($val->date);
            $time_from = date('H:i', strtotime($val->time_from));
            $time_to   = date('H:i', strtotime($val->time_to));
            $capacity  = strpos($val->place, '１') !== FALSE ? 24 : 18;
            $court     = strpos($val->place, '１') !== FALSE ? 4 : 3;
            echo sprintf('日時：%s(%s) %s～%s<br>', date('n月j日', $time), $week[date('w', $time)], $time_from, $time_to);
            echo sprintf('場所：%s（%s）<br>', $val->name, $val->place);
            echo sprintf('コート数：%s面<br>', $court);
            echo sprintf('定員：%s名<br>', $capacity);
            echo sprintf('参加費：700～800円(人数で変動)<br>');
            echo sprintf('最寄り駅：<br><br>');
        }
        die;
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

	public function normalizeDate($inStr)
	{
        // 年月日の各パーツを分割する
        preg_match( "/([0-9]*)年([0-9]*)月([0-9]*)日/", $inStr, $data );
        if ( Count( $data ) != 4 ) {
            return $inStr;
        }

        // 先頭0埋めでYYYY-MM-DD形式の日付文字列に変換する
        $outStr = sprintf("%04.4d-%02.2d-%02.2d", $data[1], $data[2], $data[3]);

        return $outStr;
    }


    /**
     * OPAS にログインする
     *
     * @return string
     */
	private function _opas_login($id, $password, string $month = NULL)
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
			// $id_name    => OPAS_ID,
			// $pass_name  => OPAS_PW,
			$id_name    => $id,
			$pass_name  => $password,
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

        curl_close($ch);
		if ( ! write_file(GYM_TXT, $html))
		{
				echo 'ファイルに書き込めません';
				die;
		}
		$this->_delete_two_rows(GYM_TXT);
    }

    private function _basic_auth()
    {
        switch (TRUE)
        {
            case ! isset($_SERVER['PHP_AUTH_USER']):
            case $_SERVER['PHP_AUTH_USER'] !== 'opas':
                header('WWW-Authenticate: Basic realm="Enter username."');
                header('Content-Type: text/plain; charset=utf-8');
                die('このページを見るにはログインが必要です');
        }
    }

}
