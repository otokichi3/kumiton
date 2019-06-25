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

    private function basic_auth()
    {
        switch (TRUE)
        {
            case !isset($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']):
            case $_SERVER['PHP_AUTH_USER'] !== 'fm802':
            case $_SERVER['PHP_AUTH_PW']   !== 'fm802':
                header('WWW-Authenticate: Basic realm="Enter username and password."');
                header('Content-Type: text/plain; charset=utf-8');
                die('このページを見るにはログインが必要です');
        }
    }

    public function index(): void
    {
        $this->basic_auth();

		require_once("phpQuery-onefile.php");

		$this->title      = 'FM802';
		$this->title_lead = 'FM802で再生された曲のアーティスト別回数を表示します。';

        $fm802 = [
            'url'    => 'https://funky802.com/service/OnairList/today',
            'song'   =>'.song-name',
            'artist' =>'.artist-name',
        ];
        list($song_list, $artist_list, $song_cnt, $artist_cnt)
            = $this->_get_radio_onair_info($fm802['url'], $fm802['song'], $fm802['artist']);

        $artist_info     = $this->fm802_model->get_artist_info();

        $ranking = $this->fm802_model->get_rank(1); // -1 week

		$this->view_data = [
			'title'        => $this->title,
			'title_lead'   => $this->title_lead,
			'artist_info'  => $artist_info,
			'song_list'    => $song_list,
			'artist_list'  => $artist_list,
			'song_cnt'     => $song_cnt,
			'artist_cnt'   => $artist_cnt,
			'ranking'      => $ranking,
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
    
    public function get_rank(): void
    {
		$type = $this->input->post('type') ?? 1;
		$rank = $this->fm802_model->get_rank($type);

		header('Content-Type: application/json');
		echo json_encode($rank);
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
}

