<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {

    public function index()
    {
        $view_data = [];

        $sanka_data = file_get_contents('json/'.SANKA_FILE);
        $sanka_member = json_decode($sanka_data, true);

        // メンバー追加
        $add_member = [];
        // $add_member_name = filter_input(INPUT_POST, 'add_member_name', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        $add_member_name = $this->input->post('add_member_name');
        // $add_member_level = filter_input(INPUT_POST, 'add_member_level', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        $add_member_level = $this->input->post('add_member_level');
        if ( ! is_null($add_member_name) && ! is_null($add_member_level)) {
            foreach ($add_member_name as $key => $value) {
                if ( ! $value) {
                    unset($add_member_name[$key]);
                    unset($add_member_level[$key]);
                }
            }
            $add_member = array_combine($add_member_name, $add_member_level);
            $sanka_member += $add_member;
            if (file_put_contents($sanka_file, json_encode($sanka_member)) === FALSE) {
                echo 'Failed to write.';
            }
        }

        $husanka_data = file_get_contents('json/'.HUSANKA_FILE);
        $husanka_member = json_decode($husanka_data, true);

        $all_member = $sanka_member + $husanka_member;
        asort($all_member);


        // $selected_member = filter_input(INPUT_POST, 'selected_member', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        $selected_member = $this->input->post('selected_member');
        // $test = $this->db->get('t_member')->result();
        // $this->dump($test);

        $view_data = [
            'all_member'      => $all_member,
            'selected_member' => $selected_member,
        ];
        $this->load->view('member_select', $view_data);
    }

    /**
     * メンバーリストから参加者 or 不参加者を取得する
     *
     * @param array $sanka
     * @param array $all_member
     * @param bool  $is_sanka
     * @return array
     */
    function get_member(array $sanka, array $all_member, bool $is_sanka) : array
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
    function get_kumis_by_level(array $pairs_by_level) : array
    {
        $kumis = [];
        $cnt   = count($pairs_by_level);
        $j     = 0;

        try {
            foreach ($pairs_by_level as $key => $pairs) {
                ++$j;
                for ($i = $j; $i < $cnt; $i++) {
                    $arr = array_slice($pairs_by_level, $i, 1);
                    if (is_duplicate($pairs, $arr[0])) {
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
    function is_duplicate(array $pair1, array $pair2) : bool
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
    function get_pairs_by_level(array $all_pairs, array $sum_list, array $sum_numbers) : array
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
    function get_all_sum(array $all_pairs)
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
    function get_all_pairs(array $members)
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

    /**
     * var_export を pre タグで囲んで出力するラッパー
     *
     * @param [type] $arg
     * @return void
     */
    function dump($arg)
    {
        echo '<pre>';
        var_export($arg);
        echo '</pre>';
    }

    }
