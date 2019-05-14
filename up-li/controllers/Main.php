<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Main extends CI_Controller
{
    public $view_data = [];

    function __construct()
    {
        parent::__construct();
        $this->load->model('t_member');
    }

    public function index()
    {
        if ($this->input->post('add_member_name')) {
            $this->add_member();
        }

        $selected_member = $this->input->post('selected_member');

        $all_member = $this->t_member->get_all_member();

        $this->view_data = [
            'all_member'      => $all_member,
            'selected_member' => $selected_member,
        ];
        $this->load->view('member_select', $this->view_data);
    }

    private function add_member()
    {
        $add_member       = [];
        $add_member_name  = $this->input->post('add_member_name');
        $add_member_sex   = $this->input->post('add_member_sex');
        $add_member_level = $this->input->post('add_member_level');

        // 空を削除しつつ移し替え
        foreach ($add_member_name as $key => $value) {
            if ( ! $add_member_name[$key] OR ! $add_member_sex[$key] OR ! $add_member_level[$key]) {
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

    public function show_game()
    {
        $selected_member = $this->input->post('selected_member');
        $all_member = $this->t_member->get_all_member();

        if ( ! is_array($selected_member)  || count($selected_member) < 4) {
            die('参加者は４人以上必要です。');
        }
        
        // 参加者
        $all_member_name = array_keys($all_member);
        $sanka = array_intersect($all_member_name, $selected_member);
        
        // 参加者（名前ーレベル形式）
        $sanka_member = $this->get_member($selected_member, $all_member, TRUE);
        
        // 全ペア算出
        $all_pairs = $this->get_all_pairs($sanka_member);
        
        // 全ペアのレベル合計値算出
        $sum_list = $this->get_all_sum($all_pairs);
        asort($sum_list);
        $sum_numbers = array_unique(array_values($sum_list));
        $pairs_by_level = $this->get_pairs_by_level($all_pairs, $sum_list, $sum_numbers);
        
        $kumis_by_level = [];
        foreach ($pairs_by_level as $level => $pairs) {
            $kumis = $this->get_kumis_by_level($pairs);
            if (count($kumis) === 0) {
                continue;
            } else {
                foreach ($kumis as $key => $val) {
                    foreach ($val as $key2 => $val2) {
                        $kumis_by_level[$level][] = $val2;
                    }
                }
            }
        }

        $this->view_data = [
            'sanka_member'      => $sanka_member,
            'selected_member' => $selected_member,
            'kumis_by_level'  => $kumis_by_level,
        ];
        $this->load->view('main.php', $this->view_data);
    }

    public function todo()
    {
        $this->load->view('todo');
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
    private function get_all_sum(array $all_pairs)
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
    private function get_all_pairs(array $members)
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
