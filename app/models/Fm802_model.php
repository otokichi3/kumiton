<?php if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Fm802_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
	}

    public function get_artist_info(string $date = '')
    {

		// なければ前日
		$date = $date ?? date('Y-m-d', strtotime('-1 day', time()));

		$this->db->select('artist, count, date');
		$this->db->where('count >', 1);
		$this->db->where('date', $date);
		$this->db->order_by('count', 'DESC');
		$this->db->limit(7);

		$res = $this->db->get('t_fm802')->result_array();
		
		$ret = [];
		foreach ($res as $key => $val) {
			$ret[$val['date']][$val['artist']] = $val['count'];
		}

		return $ret;
	}
}
