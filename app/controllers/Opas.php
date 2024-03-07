<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Opas extends MY_Controller {

    private $view_data = [];

    public function __construct()
    {
        parent::__construct();

		$this->load->helper('file');
		$this->load->model('Opas_model');
    }

    public function index()
    {
		$this->title      = 'OPAS予約';
        $this->title_lead = 'OPASの体育館予約情報を表示します。';

        $next_month = date('Y-m', strtotime('+1 month'));

        $params = [
            'gym_list' => $this->Opas_model->get_gym_list($next_month),
        ];
		$this->view_data = [
			'title'      => $this->title,
			'title_lead' => $this->title_lead,
			'table_view' => $this->load->view('opas/table', $params, TRUE),
			'month'      => date('n', strtotime('+1 month')),
        ];

        $this->load->view('header');
		$this->load->view('opas/index', $this->view_data);
        $this->load->view('footer');

        //テンプレート読み込み先指定
        // $template = $this->twig->loadTemplate('header');       
        // echo $twig->render('index.html', ['products' => $products] );

        // $this->output->set_output($template->render($view_data));
    }

    public function login()
    {
        $id       = $this->input->post('id');
        $password = $this->input->post('password');
        $month    = $this->input->post('month');

		$this->opas_login($id, $password, $month);
        $gym_list = $this->get_list();
        $this->Opas_model->save($id, $gym_list, $month);
		redirect('opas');
    }

    public function get_table_view()
    {
		$month         = $this->input->post('month');
		$show_canceled = $this->input->post('show_canceled');

		$month = sprintf('2019-%02s', $month);

        $params = [
            'gym_list' => $this->Opas_model->get_gym_list($month, $show_canceled),
        ];
		$table_view = $this->load->view('opas/table', $params, TRUE);

		header('Content-Type: application/json');
		echo json_encode($table_view);
		die;
	}

    public function get_gym_txt()
    {
		$id = $this->input->post('id');
		$gym = $this->Opas_model->get_data($id);

		$time      = strtotime($gym['date']);
		$time_from = date('H:i', strtotime($gym['time_from']));
		$time_to   = date('H:i', strtotime($gym['time_to']));
		$capacity  = strpos($gym['place'], '１') !== FALSE ? 24 : 18;
		$court     = strpos($gym['place'], '１') !== FALSE ? 4 : 3;

		$txt = '';
		$txt .= sprintf('日時：%s(%s) %s～%s<br>', date('n月j日', $time), WEEKDAY[date('w', $time)], $time_from, $time_to);
		$txt .= sprintf('場所：%s（%s）<br>', $gym['name'], $gym['place']);
		$txt .= sprintf('コート数：%s面<br>', $court);
		$txt .= sprintf('定員：%s名<br>', $capacity);
		$txt .= sprintf('参加費：700～800円(人数で変動)<br>');
		$txt .= sprintf('最寄り駅：%s<br><br>', $gym['station']);

		header('Content-Type: application/json');
		echo json_encode($txt);
		die;
	}


	/**
	 * 全体公開用のテキストを取得する
	 *
	 * @return void
	 */
    public function get_txt()
    {
		$month = $this->input->post('month') ?? date('m', strtotime('+1 month'));
		$type = $this->input->post('type') ?? TXT_MAIN;

		$date = sprintf('2019-%02s', $month);

		$gym_list = $this->Opas_model->get_gym_list($date, FALSE);

		$txt = $this->_to_txt($gym_list, $type, $month);

		header('Content-Type: application/json');
		echo json_encode(nl2br($txt));
		die;
	}

	/**
	 * 予約情報の配列を文字列形式に変換する
	 *
	 * @param array $list
	 * @return string
	 */
	private function _to_txt(array $list, int $type = TXT_MAIN, string $month = NULL): string
	{
		if ($type === TXT_MAIN)
		{
			$txt = '';
			foreach ($list as $val)
			{
				$time      = strtotime($val['date']);
				$time_from = date('H:i', strtotime($val['time_from']));
				$time_to   = date('H:i', strtotime($val['time_to']));
				$capacity  = strpos($val['place'], '１') !== FALSE ? 24 : 18;
				$court     = strpos($val['place'], '１') !== FALSE ? 4 : 3;

				$txt .= sprintf('日時：%s(%s) %s～%s<br>', date('n月j日', $time), WEEKDAY[date('w', $time)], $time_from, $time_to);
				$txt .= sprintf('場所：%s（%s）<br>', $val['name'], $val['place']);
				$txt .= sprintf('コート数：%s面<br>', $court);
				$txt .= sprintf('定員：%s名<br>', $capacity);
				$txt .= sprintf('参加費：700～800円(人数で変動)<br>');
				$txt .= sprintf('最寄り駅：%s<br><br>', $val['station']);
			}
		}
		elseif ($type === TXT_SUB)
		{
			$txt = sprintf('%s月の予約状況%s%s', $month, PHP_EOL, PHP_EOL);
			foreach ($list as $val)
			{
				$name      = str_replace('スポーツセンター', 'SC', $val['name']);
				$place     = (bool) (strpos($val['place'], '１') !== FALSE) ? '①' : '②';
				$time      = strtotime($val['date']);
				$time_from = date('H:i', strtotime($val['time_from']));
				$time_to   = date('H:i', strtotime($val['time_to']));
				$court     = strpos($val['place'], '１') !== FALSE ? 4 : 3;

				$txt .= sprintf('%s(%s) ', date('m/d', $time), WEEKDAY[date('w', $time)]);
				$txt .= sprintf('%s～%s ', $time_from, $time_to);
				$txt .= sprintf('%s%s ', $name, $place);
				$txt .= sprintf('%s面 (%s)%s', $court, $val['user_name'], PHP_EOL);
			}
		}

		return $txt;
	}
}
