<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Main extends CI_Controller
{
    public $view_data = [];
    private $title = '';
    private $title_lead = '';

    public function __construct()
    {
        parent::__construct();
        $this->load->model('member_model');
        $this->load->model('match_model');
    }

    public function index()
    {
        $this->title = 'メンバー選択';
        $this->title_lead = '参加するメンバーを選択してください。';

        if ($this->input->post('add_member_name')) {
            $this->_add_member();
        }
        $this->view_data = $this->_get_view_data();
        $this->load->view('header');
        $this->load->view('member_select', $this->view_data);
        $this->load->view('footer');
    }

    private function _get_view_data()
    {
        $selected_member = $this->input->post('selected_member');
        $all_member      = $this->member_model->get_all_member();
        $all_member_info = $this->member_model->get_all_member_info();

        $data = [
            'title'           => $this->title,
            'title_lead'      => $this->title_lead,
            'all_member'      => $all_member,
            'selected_member' => $selected_member,
            'all_member_info' => $all_member_info,
        ];

        return $data;
    }

    private function _add_member()
    {
        $add_member       = [];
        $add_member_name  = $this->input->post('add_member_name');
        $add_member_sex   = $this->input->post('add_member_sex');
        $add_member_level = $this->input->post('add_member_level');

        // 空を削除しつつ移し替え
        foreach ($add_member_name as $key => $value) {
            if (! $add_member_name[$key] or ! $add_member_sex[$key] or ! $add_member_level[$key]) {
                unset($add_member_name[$key]);
                unset($add_member_sex[$key]);
                unset($add_member_level[$key]);
            } else {
                $add_member[$key]['name']  = $add_member_name[$key];
                $add_member[$key]['sex']   = $add_member_sex[$key];
                $add_member[$key]['level'] = $add_member_level[$key];
            }
        }

        // DB に格納
        foreach ($add_member as $val) {
            $params = [
                'name'  => $val['name'],
                'sex'   => $val['sex'],
                'level' => $val['level'],
            ];
            $this->db->insert('t_member', $params);
        }
    }

    public function line_notify()
    {
        $message = $this->input->post('message');
        // リクエストヘッダの作成
        $query = http_build_query(['message' => $message]);
        $token = 'sJv0RyOTrXI3FPG7KDYoKRM8YbvHdROIoljC98jwq22';
        $header = [
            'Content-Type: application/x-www-form-urlencoded',
            'Authorization: Bearer ' . $token,
            'Content-Length: ' . strlen($query)
        ];

        $ch = curl_init('https://notify-api.line.me/api/notify');
        $options = [
            CURLOPT_RETURNTRANSFER  => TRUE,
            CURLOPT_POST            => TRUE,
            CURLOPT_HTTPHEADER      => $header,
            CURLOPT_POSTFIELDS      => $query
        ];

        curl_setopt_array($ch, $options);
        curl_exec($ch);
        curl_close($ch);

        header('Content-Type: application/json');
        echo json_encode('succeed');
        die;
    }

    public function show_game()
    {
        $selected_list = $this->input->post('selected_member');
        $member_list   = $this->member_model->get_all_member();

        if (! $selected_list or count($selected_list) < 4) {
            $this->load->view('header');
            $this->load->view('member_select', $this->_get_view_data());
            $this->load->view('footer');
            return;
        }
        
        list($sanka_list, $match_list) = $this->_get_all_match($member_list, $selected_list);

        $this->db->truncate('t_match');

        // t_match
        foreach ($match_list as $match) {
            foreach ($match as $pair) {
                $params = [
                    'server1'   => $pair[0][0],
                    'server2'   => $pair[0][1],
                    'receiver1' => $pair[1][0],
                    'receiver2' => $pair[1][1],
                    'play_flg'  => FLG_OFF,
                ];
                $this->db->insert('t_match', $params);
            }
        }

        $this->show_match($sanka_list, $selected_list);
    }

    /**
     * 試合を表示する
     *
     * @return void
     */
    public function show_match(array $sanka_list = [], array $selected_list = [])
    {
        $this->title = '試合';
        $this->title_lead = '試合を表示します。';

        $match = $this->get_match(NUMBER_OF_COURTS);

        if (is_array($match)) {
            foreach ($match as $pair) {
                $params = [
                    'server1'   => $pair['server1'],
                    'server2'   => $pair['server2'],
                    'receiver1' => $pair['receiver1'],
                    'receiver2' => $pair['receiver2'],
                ];
                $this->db->insert('t_match_history', $params);
            }
        }

        $this->view_data = [
            'title'         => $this->title,
            'title_lead'    => $this->title_lead,
            'sanka_list'    => $sanka_list,
            'selected_list' => $selected_list,
            'court_view'    => $this->load->view('court', ['match' => $match], true),
        ];

        $this->load->view('header');
        $this->load->view('main', $this->view_data);
        $this->load->view('footer');
    }

    /**
     * 試合を取得する
     *
     * @param integer $num
     * @return array
     */
    public function get_match(int $num = 0) : array
    {
        $num_of_courts = $this->input->post('num') ?: $num;
        $match = $this->match_model->get_match($num_of_courts);

        if ($this->input->is_ajax_request()) {
            $court_view = $this->load->view('court', ['match' => $match], true);
            header('Content-Type: application/json');
            echo json_encode($court_view);
            die;
        }

        return $match;
    }

    public function manage_member()
    {
        $this->title = 'メンバー追加';
        $this->title_lead = 'メンバーの追加を行えます。';

        if ($this->input->post('add_member_name')) {
            $this->_add_member();
        }
        $this->view_data = $this->_get_view_data();
        $this->load->view('header');
        $this->load->view('manage_member', $this->view_data);
        $this->load->view('footer');
    }

    /**
     * すべての試合を取得する
     *
     * @param array $member_list
     * @param array $selected_list
     * @return void
     */
    private function _get_all_match(array $member_list, array $selected_list)
    {
        // 参加者（名前ーレベル形式）
        $sanka_list = $this->get_member($selected_list, $member_list, true);
        $sanka_list = $this->_summarize_level($sanka_list, 2);

        // 全ペア算出
        $all_pairs = $this->get_all_pairs($sanka_list);
        
        // 全ペアのレベル合計値算出
        $sum_list = $this->get_all_sum($all_pairs);
        asort($sum_list);
        $sum_numbers = array_unique(array_values($sum_list));
        $pairs_by_level = $this->get_pairs_by_level($all_pairs, $sum_list, $sum_numbers);
        
        $match_list = [];
        foreach ($pairs_by_level as $level => $pairs) {
            $kumis = $this->get_kumis_by_level($pairs);
            if (count($kumis) === 0) {
                continue;
            } else {
                foreach ($kumis as $key => $val) {
                    foreach ($val as $key2 => $val2) {
                        $match_list[$level][] = $val2;
                    }
                }
            }
        }

        return [$sanka_list, $match_list];
    }

    /**
     * レベルの粒度を低くする（要約する）
     *
     * @param array $list
     * @return array
     */
    private function _summarize_level(array $list, int $num_of_level)
    {
        foreach ($list as $key => $val) {
            $list[$key] = ceil($val / $num_of_level);
        }
        return $list;
    }

    public function todo()
    {
        $this->load->view('header');
        $this->load->view('todo');
        $this->load->view('footer');
    }

    /**
     * メンバーリストから参加者 or 不参加者を取得する
     *
     * @param array $sanka
     * @param array $all_member
     * @param bool  $is_sanka
     * @return array
     */
    private function get_member(array $sanka, array $all_member, bool $is_sanka) : array
    {
        $list = [];

        foreach ($sanka as $val) {
            if (isset($all_member[$val])) {
                if ($is_sanka) {
                    $list[$val] = $all_member[$val];
                } else {
                    unset($all_member[$val]);
                }
            }
        }
        return $is_sanka ? $list : $all_member;
    }

    /**
     * レベルごとの試合のすべての組み合わせを返却する
     *
     * @param array $pairs_by_level
     * @return array
     */
    private function get_kumis_by_level(array $pairs_by_level) : array
    {
        $kumis = [];
        $cnt   = count($pairs_by_level);
        $j     = 0;

        try {
            foreach ($pairs_by_level as $key => $pairs) {
                ++$j;
                for ($i = $j; $i < $cnt; $i++) {
                    $arr = array_slice($pairs_by_level, $i, 1);
                    if ($this->is_duplicate($pairs, $arr[0])) {
                        continue;
                    }
                    $kumis[$key][$i][0] = $pairs;
                    $kumis[$key][$i][1] = $arr[0];
                }
            }
        } catch (Exception $e) {
            return $e->getMessage();
        } finally {
            return $kumis;
        }
    }

    /**
     * ２ペアの中に同一人物がいるかどうかを判定する
     *
     * @param array $pairs
     * @param array $arr
     * @return boolean
     */
    private function is_duplicate(array $pair1, array $pair2) : bool
    {
        $gattai = array_merge($pair1, $pair2);
        $org_cnt = count($gattai);
        $unq_cnt = count(array_unique($gattai, SORT_STRING));
        return $org_cnt !== $unq_cnt;
    }

    /**
     * レベルごとのペアのすべての組み合わせを返却する
     *
     * @param array $all_pairs
     * @param array $sum_list
     * @param array $sum_numbers
     * @return array
     */
    private function get_pairs_by_level(array $all_pairs, array $sum_list, array $sum_numbers) : array
    {
        $ret = [];

        foreach ($sum_numbers as $sum_number) {
            foreach ($sum_list as $key2 => $val2) {
                if ($val2 === $sum_number) {
                    $ret[$sum_number][] = array_keys($all_pairs[$key2]);
                }
            }
        }
        return $ret;
    }

    /**
     * ペアそれぞれのレベル合計値を算出し配列を返却する
     *
     * @param array $all_pairs
     * @return void
     */
    private function get_all_sum(array $all_pairs) : array
    {
        $sum_list = [];

        foreach ($all_pairs as $key => $pair) {
            $sum_list[] = array_sum($pair);
        }

        return $sum_list;
    }

    /**
     * 参加者からすべての組み合わせを作成し、返却する
     *
     * @param array $members
     * @return void
     */
    private function get_all_pairs(array $members) : array
    {
        $pair= [];
        $cnt  = count($members);
        $j    = 0;

        try {
            foreach ($members as $key => $member) {
                ++$j;
                for ($i = $j; $i < $cnt; $i++) {
                    $arr = array_slice($members, $i, 1);
                    $pair[] = [$key => $member] + [key($arr) => current($arr)];
                }
            }
        } catch (Exception $e) {
            return $e->getMessage();
        } finally {
            return $pair;
        }
    }
}
