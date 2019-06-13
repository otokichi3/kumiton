<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Fm802 extends CI_Controller
{
	public $view_data   = [];

	private $title 	    = '';

	private $title_lead = '';

    function __construct()
    {
		parent::__construct();
		$this->load->model('fm802_model');
    }

    public function index(): void
    {
		require_once 'phpQuery-onefile.php';

		$this->title      = 'FM802';
		$this->title_lead = 'FM802で再生された曲のアーティスト別回数を表示します。';

        $fm802 = [
            'url'    => 'https://funky802.com/service/OnairList/today',
            'song'   =>'.song-name',
            'artist' =>'.artist-name',
        ];
        list($song_list, $artist_list, $song_cnt, $artist_cnt)
            = $this->_get_radio_onair_info($fm802['url'], $fm802['song'], $fm802['artist']);

        // $kissfm = [
        //     'url'    => 'http://noa-www-lb-148752130.ap-northeast-1.elb.amazonaws.com/search/view/iv/',
        //     'song'   => '.entryTxt > a',
        //     'artist' => '.entryArtist',
        // ];
        // list($song_list2, $artist_list2, $song_cnt2, $artist_cnt2)
        //     = $this->_get_radio_onair_info($kissfm['url'], $kissfm['song'], $kissfm['artist'], FALSE);

		$artist_info     = $this->fm802_model->get_artist_info();
		$this->view_data = [
			'title'        => $this->title,
			'title_lead'   => $this->title_lead,
			'artist_info'  => $artist_info,
			'song_list'    => $song_list,
			'artist_list'  => $artist_list,
			'song_cnt'     => $song_cnt,
			'artist_cnt'   => $artist_cnt,
			'song_list2'   => $song_list2,
			'artist_list2' => $artist_list2,
			'song_cnt2'    => $song_cnt2,
			'artist_cntw\2'  => $artist_cnt2,
        ];

        $this->load->view('header');
		$this->load->view('fm802', $this->view_data);
        $this->load->view('footer');
    }

	public function get_artist_info(): void
	{
		$onair_date  = $this->input->post('onair_date');
		$artist_info = $this->fm802_model->get_artist_info($onair_date);

		header('Content-Type: application/json');
		echo json_encode($artist_info);
		die;
	}

	private function _get_radio_onair_info(string $url = NULL, $song_class = NULL, $artist_class = NULL, bool $enc = TRUE)
	{
        $html = file_get_contents($url);

        if ($enc === TRUE)
        {
            $html = mb_convert_encoding($html, 'utf-8', 'sjis');
        }


		$html = str_replace(["\r\n", "\r", "\n"], "\n", $html);
		$doc  = phpQuery::newDocument($html);

		$song_name   = $doc->find($song_class)->text();
        $artist_name = $doc->find($artist_class)->text();

        //文字列の中にある半角空白と全角空白をすべて削除・除去する
        $song_name   = str_replace([' ', '　'], "", $song_name);
        $artist_name = str_replace([' ', '　'], "", $artist_name);

		$song_list   = explode("\n", $song_name);
        $artist_list = explode("\n", $artist_name);

        // foreach ($song_list as $key => $val) {
        //     if (strlen($val) === 0)
        //     {
        //         unset($song_list[$key]);
        //     }
        //     else
        //     {
        //         $song_list[$key] = mb_convert_kana($val, 'a');
        //     }
        // }
        // foreach ($artist_list as $key => $val) {
        //     if (strlen($val) === 0) {
        //         unset($artist_list[$key]);
        //     }
        //     else
        //     {
        //         $artist_list[$key] = mb_convert_kana($val, 'a');
        //     }
        // }
        // $song_list   = array_values($song_list);
        // $artist_list = array_values($artist_list);
        // dump($song_list);
        // dump($artist_list);
        // die;

		$song_cnt   = array_count_values($song_list);
        $artist_cnt = array_count_values($artist_list);

        foreach ($artist_cnt as $name =>$val)
        {
            if ($val < 2)
            {
                unset($artist_cnt[$name]);
            }
        }
		arsort($song_cnt);
		arsort($artist_cnt);

		return [$song_list, $artist_list, $song_cnt, $artist_cnt];
	}

	private function _get_time_list($doc)
	{
		$ret = [];

		// $timeD =  $doc->find(".time-D")->text();
		// $timeD =  explode("\n", $timeD);
		// if (count($timeD)) {
		// 	$ret += $timeD;
		// }

		// $timeC =  $doc->find(".time-C")->text();
		// $timeC =  explode("\n", $timeC);
		// if (count($timeC)) {
		// 	$ret += $timeC;
		// }
		// $timeB =  $doc->find(".time-B")->text();
		// $timeB =  explode("\n", $timeB);
		// if (count($timeB)) {
		// 	$ret += $timeB;
		// }
		// $timeA =  $doc->find(".time-A")->text();
		// $timeA =  explode("\n", $timeA);
		// if (count($timeA)) {
		// 	$ret += $timeA;
		// }

		$time = $doc->find('.time-*')->text();
		$ret  = explode("\n", $time);

		return $ret;
	}
}

