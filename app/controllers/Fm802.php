<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Fm802 extends CI_Controller
{
	public $view_data = [];
	private $title = '';
	private $title_lead = '';

    function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        require_once("phpQuery-onefile.php");
		$this->title = 'FM802';
		$this->title_lead = 'FM802で再生された曲のアーティスト別回数を表示します。';

		$url  = 'https://funky802.com/service/OnairList/today';
		$html = file_get_contents($url);
		$html = mb_convert_encoding($html, "utf-8", "sjis");
		$html = str_replace(array("\r\n", "\r", "\n"), "\n", $html);
		$doc  = phpQuery::newDocument($html);

		$song_name   = $doc->find(".song-name")->text();
		$artist_name = $doc->find(".artist-name")->text();

		$song_name_list   = explode("\n", $song_name);
		$artist_name_list = explode("\n", $artist_name);

		$song_name_cnt   = array_count_values($song_name_list);
        $artist_name_cnt = array_count_values($artist_name_list);
        foreach ($artist_name_cnt as $name =>$val) {
            if ($val < 3) {
                unset($artist_name_cnt[$name]);
            }
        }
		arsort($song_name_cnt);
		arsort($artist_name_cnt);

		$this->view_data = [
			'title'            => $this->title,
			'title_lead'       => $this->title_lead,
			'song_name_cnt'    => $song_name_cnt,
			'artist_name_cnt'  => $artist_name_cnt,
			'song_name_list'   => $song_name_list,
			'artist_name_list' => $artist_name_list,
		];
        $this->load->view('header');
		$this->load->view('fm802', $this->view_data);
        $this->load->view('footer');
    }

	private function _get_time_list($doc)
	{
		$ret = [];

		$timeD =  $doc->find(".time-D")->text();
		$timeD =  explode("\n", $timeD);
		if (count($timeD)) {
			$ret += $timeD;
		}

		$timeC =  $doc->find(".time-C")->text();
		$timeC =  explode("\n", $timeC);
		if (count($timeC)) {
			$ret += $timeC;
		}
		$timeB =  $doc->find(".time-B")->text();
		$timeB =  explode("\n", $timeB);
		if (count($timeB)) {
			$ret += $timeB;
		}
		$timeA =  $doc->find(".time-A")->text();
		$timeA =  explode("\n", $timeA);
		if (count($timeA)) {
			$ret += $timeA;
		}
		
		return $ret;

	}
}

