<?php

if ( ! defined('BASEPATH'))
{
    exit('No direct script access allowed');
}

class Fm802_model extends CI_Model {
    public function __construct()
    {
        parent::__construct();
    }

    public function get_rank(int $type = 1): array
    {
        $type_list = [
            1 => '-1 week',
            2 => '-2 week',
            3 => '-1 month',
            4 => '-2 month',
        ];

        $start_date = date("Y-m-d", strtotime($type_list[$type]));

        $this->db->select('artist, SUM(count) as count');

        $this->db->where('date >=', $start_date);
        $this->db->group_by('artist');
        $this->db->having('SUM(count) >= ', 10);

        $this->db->order_by('count', 'DESC');
        $this->db->limit(10);

        $res = $this->db->get('t_fm802')->result_array();
        return $res;
    }

    public function get_artist_info(string $date = '', int $limit = 7): array
    {
        // なければ前日
        $date = $date ?? date('Y-m-d', strtotime('-1 day', time()));

        $this->db->select('artist, count, DATE(date) as date');
        $this->db->where('count >', 1);
        $this->db->where('DATE(date)', $date);
        $this->db->order_by('count', 'DESC');
        $this->db->limit($limit);

        $res = $this->db->get('t_fm802')->result_array();

        $ret = [];

        foreach ($res as $key => $val) {
            $ret[$val['date']][$val['artist']] = $val['count'];
        }

        return $ret;
    }
}
