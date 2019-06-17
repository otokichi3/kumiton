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

    /**
     * 一覧取得.
     *
     * @param array  $params
     * @param string $select
     * @param string $table
     * @param array  $limit
     * @return array $result
     */
    public function get_list(array $params = [], string $select = NULL, string $table = 't_fm802', array $limit = NULL)
    {
        try {
			if (strlen($select))
			{
                $this->db->select($select);
			}

			foreach ($params as $key => $value)
			{
				if (is_array($value) && ! empty($value))
				{
					$this->db->where_in($key, $value);
				}
				elseif ( ! is_array($value) && strlen($value) > 0)
				{
					$this->db->where($key, $value);
				}
				break;
            }

			// LIMIT
            if (isset($limit['limit']) && strlen($limit['limit'])) {
                if (isset($limit['offset']) && strlen($limit['offset'])) {
                    $this->db->limit($limit['limit'], $limit['offset']);
                } else {
                    $this->db->limit($limit['limit']);
                }
            }
            return $this->db->get($table)->result();
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
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
