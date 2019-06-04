<?php if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Member extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_all_member()
    {
        $this->db->select('name, level');
        $this->db->where('deleted', 0);

        $result_array = $this->db->get('t_member')->result_array();
        $ret = [];
        foreach ($result_array as $key => $value) {
            $ret[$value['name']] = (float)$value['level'];
        }
        asort($ret);
        return $ret;
	}

    public function get_all_member_info()
    {
        $this->db->select('name, nickname, sex, join_cnt, level');
        $this->db->where('deleted', 0);

        $result_array = $this->db->get('t_member')->result_array();
        return $result_array;
    }
}
