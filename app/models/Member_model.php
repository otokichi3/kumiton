<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Member_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function update_by_id($params): void
    {
        $upd_data = [
            'name'     => $params['name'],
            'nickname' => $params['nickname'],
            'sex'      => $params['sex'],
            'level'    => $params['level'],
        ];
        $this->db->where('id', $params['id']);
        $this->db->update('t_member', $upd_data);
    }

    public function get_data($id)
    {
        $this->db->select('*');
        $this->db->where('deleted', 0);
        $this->db->where('id', $id);

        return $this->db->get('t_member')->row_array();
    }

    public function get_all_member()
    {
        $this->db->select('name, level');
        $this->db->where('deleted', 0);

        $result_array = $this->db->get('t_member')->result_array();
        $ret          = [];

        foreach ($result_array as $key => $value) {
            $ret[$value['name']] = (float) $value['level'];
        }
        asort($ret);

        return $ret;
	}

    public function get_all_member_info()
    {
        $this->db->select('id, name, nickname, sex, join_cnt, level');
        $this->db->where('deleted', 0);

        $result_array = $this->db->get('t_member')->result_array();

        return $result_array;
    }
}
