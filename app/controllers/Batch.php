<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Batch extends CI_Controller
{
    public function __construct()
    {
		parent::__construct();

        if ($this->input->method(TRUE) !== 'POST') {
			return FALSE;
		}
        $this->load->model('fm802_model');
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

		$msg = '';
		foreach ($artist_info[$yesterday] as $key => $val) {
			$msg .= sprintf('%s%s: %s', PHP_EOL, $key, $val);
		}
		send_line(LINE_TOKEN1, $msg);

    }
}