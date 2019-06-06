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
		$this->db->where('count >', 2);

        return $this->db->get('t_fm802')->result_array();
	}
}
