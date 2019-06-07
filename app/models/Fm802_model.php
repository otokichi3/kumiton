<?php if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Fm802_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
	}

    public function get_artist_info()
    {
		$this->db->select('artist, count, date');
		$this->db->where('count >', 1);
		$this->db->where('date', '2019-06-06');
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
