<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Batch extends MY_Controller {

    public function __construct()
    {
		parent::__construct();

        if ($this->input->method(TRUE) !== 'POST')
        {
			return FALSE;
		}
        $this->load->model('Fm802_model');
		$this->load->model('Opas_model');
		$this->load->helper('file');
    }

    public function Fm802()
    {
		require_once("phpQuery-onefile.php");

		$url  = 'https://funky802.com/service/OnairList/today';
		$html = file_get_contents($url);
		$html = mb_convert_encoding($html, "utf-8", "sjis");
		$html = str_replace(array("\r\n",  "\r",    "\n"), "\n", $html);
		$doc  = phpQuery::newDocument($html);

		$artist_name      = $doc->find(".artist-name")->text();
		$artist_name_list = explode("\n", $artist_name);
		$artist_name_cnt  = array_count_values($artist_name_list);
		foreach ($artist_name_cnt as $name =>$val) {
			if ( ! trim($name)) {
				unset($artist_name_cnt[$name]);
			}
		}

		$today = date('Y-m-d H:i:s');
		foreach ($artist_name_cnt as $name => $cnt) {
			$params = [
				'artist' => $name,
				'count'  => $cnt,
				'date'   => $today,
			];
			$this->db->insert('t_fm802', $params);
		}

		return TRUE;
	}

    public function send_rank()
    {
		$yesterday   = date('Y-m-d', strtotime('-1 day', time()));
        $artist_info = $this->fm802_model->get_artist_info($yesterday, 5);

		$msg = sprintf('%s%s のトップ５%s', PHP_EOL, $yesterday, PHP_EOL);
		foreach ($artist_info[$yesterday] as $key => $val) {
			$msg .= sprintf('%s%s: %s', PHP_EOL, $key, $val);
		}
		send_line(LINE_TOKEN1, $msg);
	}

	/**
	 * 色々な残日数を LINE に通知する
	 *
	 * @return void
	 */
    public function notify_remain_day()
    {
		$today          = new DateTime(date('Y-m-d'));
		$wedding        = new DateTime('2019-08-11');
		$honeymoon      = new DateTime('2019-08-15');
		$new_job        = new DateTime('2019-09-02');
		$ojt_finish     = new DateTime('2019-11-30');
		$osaka_marathon = new DateTime('2019-12-01');
		$tomo_birthday  = new DateTime('2019-12-08');

		$remains = [
			'topic1'     => $today->diff($wedding),
			'topic2'    => $today->diff($honeymoon),
			'topic3'   => $today->diff($new_job),
			'topic4'   => $today->diff($ojt_finish),
			'topic5'  => $today->diff($osaka_marathon),
			'topic6'  => $today->diff($tomo_birthday),
		];

		$msg = '';
		foreach ($remains as $name => $day)
		{
			if ($day->invert === 0) // 残日数が正の数
			{
				$msg .= sprintf('%s まであと %s日%s', $name, $day->days, PHP_EOL);
			}
		}

		send_line(LINE_TOKEN1, $msg);
	}
	
	/**
	 * OPAS の情報を更新する
	 *
	 * @return void
	 */
	public function update_opas_info()
	{
		$today = date('Ymd');

		// 当月分（末日-3日～は不要）
		$next_first_day = date('Ym01', strtotime('next month'));
		$due_date = date('Ymd', strtotime($next_first_day . '-3 days'));

		if ($today < $due_date)
		{
			$month = substr($today, 0, 4);
			foreach (ACCOUNT_LIST as $id => $pw)
			{
				$this->opas_login($id, $pw, $month);
				$gym_list = $this->get_list();
				$this->Opas_model->save($id, $gym_list, $month);
			}
		}

		// 来月分
		$month = date('Ym', strtotime($today . '+1 month'));
		foreach (ACCOUNT_LIST as $id => $pw)
		{
			$this->opas_login($id, $pw, $month);
			$gym_list = $this->get_list();
			$this->Opas_model->save($id, $gym_list, $month);

		}

		// 再来月分（抽選結果がくるのが10日のため、それ以降）
		if ($today >= date('Ym10'))
		{
			$month = date('Ym', strtotime('+2 month'));
			foreach (ACCOUNT_LIST as $id => $pw)
			{
				$this->opas_login($id, $pw, $month);
				$gym_list = $this->get_list();
				$this->Opas_model->save($id, $gym_list, $month);
			}
		}
	}
}