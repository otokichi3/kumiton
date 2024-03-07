<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {
	protected $twig;

	protected $opas_txt;

    public function __construct()
    {
        parent::__construct();
        $this->opas_txt = tempnam(sys_get_temp_dir(), 'opas_');

        // テンプレートファイルの場所
        // $loader     = new Twig_Loader_Filesystem(VIEW_PATH);
        // $this->twig = new Twig_Environment($loader, ['cache' => APPPATH . '/cache/twig', 'debug' => TRUE]);
    }

    private function _basic_auth()
    {
        switch (TRUE)
        {
            case ! isset($_SERVER['PHP_AUTH_USER']):
            case $_SERVER['PHP_AUTH_USER'] !== 'kumiton':
                header('WWW-Authenticate: Basic realm="Enter username and password."');
                header('Content-Type: text/plain; charset=utf-8');
                die("You can't use kumiton without login!");
        }
	}

	/**
	 * OPAS にログインする
	 *
	 * @param mixed       $id
	 * @param mixed       $password
	 * @param null|string $month
	 * @return string
	 */
	public function opas_login($id, $password, string $month = NULL)
	{
        // 月は未選択なら当月
        $month = $month ?? date('Ym');

        // Cookie情報を保存する一時ファイルディレクトリにファイルを作成します
        $tmp_path =  tempnam(sys_get_temp_dir(), 'cookie_');

        // OPAS にログインする
		$id_name   = 'txtRiyoshaCode';
		$pass_name = 'txtPassWord';

		$post_data = [
			'action'    => 'Enter',
			'txtProcId' => '/menu/Login',
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
        curl_setopt($ch, CURLOPT_URL, OPAS_LOGIN_URL); 
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

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, OPAS_MENU_URL); 
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
            'riyoShinseiYM'              => $month,
            'reqDisplayInfoNum'          => '50',
        ];

        $post_data = http_build_query($post_data);

		$headers = [
			'Content-Type: application/x-www-form-urlencoded',
			'Content-Length: ' . strlen($post_data),
			'User-Agent: ' . USER_AGENT,
		];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, OPAS_RES_URL); 
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $tmp_path);

        $html = curl_exec($ch);

        curl_close($ch);

		if ( ! write_file($this->opas_txt, $html))
		{
            log_message('error', 'ファイルの書き込みに失敗');
		}
		delete_two_rows($this->opas_txt);
    }

	public function get_list(): array
	{
		require_once 'phpQuery-onefile.php';

		$html = read_file($this->opas_txt);
		$doc  = phpQuery::newDocument($html);

        $table = $doc->find('#mmaincolumn')->find('table:eq(2)')->find("[onmouseover='reportOver(this);']");
        $tds   = $table->find('td')->text();
        $tds   = preg_replace('/(\t)|(\r\n)|( )/s', '', $tds);
        $tds   = explode("\n", $tds);

        foreach ($tds as $key => $val)
        {
            if (strlen(trim($val)) === 0)
            {
                unset($tds[$key]);
            }
        }

        $list = array_chunk($tds, 5, FALSE);

        // 最寄り駅マスタ取得
        $station_list = $this->db->select('keyword, station')
            ->get('m_nearest_station')
			->result_array();

		$gym_list = [];
		// 配列を加工する
		foreach ($list as $key => $gym)
		{
			array_splice($gym, 3);
			$names    = explode('第', $gym[1]);
			$times    = explode('〜', $gym[2]);
			$canceled = (bool) (strpos($names[0], '【取消済み】') !== FALSE);

			$gym_list[$key]['date']      = to_normal_date($gym[0]);
			$gym_list[$key]['name']      = str_replace('【取消済み】', '', $names[0]);
			$gym_list[$key]['place']     = sprintf('第%s', $names[1]);
			$gym_list[$key]['time_from'] = $times[0];
			$gym_list[$key]['time_to']   = $times[1];
			$gym_list[$key]['canceled']  = $canceled;

			// 最寄り駅検索
			$gym_list[$key]['station'] = 'Not found';
			foreach ($station_list as $val)
			{
				if (mb_strpos($gym_list[$key]['name'], $val['keyword'], 0, 'UTF-8') !== FALSE)
				{
					$gym_list[$key]['station'] = $val['station'];
					break;
				}
			}
        }

        return $gym_list;
    }

}
