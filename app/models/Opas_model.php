<?php

if ( !defined('BASEPATH'))
{
    exit('No direct script access allowed');
}

class Opas_model extends CI_Model {
    public function __construct()
    {
        parent::__construct();
    }

	public function get_gym_list(string $month = NULL, $show_canceled = TRUE)
	{
		$month = $month ?? date('2019-m', strtotime('+1 month'));

		$this->db->select('t_gym_reservation.id');
		$this->db->select("IFNULL(m_opas_user.name, 'unknown') as user_name", FALSE);
		$this->db->select('t_gym_reservation.date');
		$this->db->select('t_gym_reservation.name');
		$this->db->select('t_gym_reservation.place');
		$this->db->select('t_gym_reservation.station');
		$this->db->select('CONCAT(LEFT(t_gym_reservation.time_from, 2), "-", LEFT(t_gym_reservation.time_to, 2)) AS time', FALSE);
		$this->db->select('t_gym_reservation.time_from');
		$this->db->select('t_gym_reservation.time_to');
		$this->db->select('t_gym_reservation.canceled');

		if ($show_canceled)
		{
			$this->db->where('t_gym_reservation.canceled', 0);
		}
		$this->db->where("DATE_FORMAT(t_gym_reservation.date, '%Y-%m') = ", $month);
        $this->db->join('m_opas_user', 'm_opas_user.opas_id = t_gym_reservation.opas_id', 'LEFT');
        $this->db->order_by('t_gym_reservation.date', 'ASC');
		$query = $this->db->get('t_gym_reservation');

		return $query->result_array();
	}

	public function get_data(int $id)
	{
		$this->db->select("IFNULL(m_opas_user.name, 'unknown') as user_name", FALSE);
		$this->db->select('t_gym_reservation.date');
		$this->db->select('t_gym_reservation.name');
		$this->db->select('t_gym_reservation.place');
		$this->db->select('t_gym_reservation.station');
		$this->db->select('CONCAT(LEFT(t_gym_reservation.time_from, 2), "-", LEFT(t_gym_reservation.time_to, 2)) AS time', FALSE);
		$this->db->select('t_gym_reservation.time_from');
		$this->db->select('t_gym_reservation.time_to');
		$this->db->select('t_gym_reservation.canceled');
		$this->db->where('t_gym_reservation.id', $id);
        $this->db->join('m_opas_user', 'm_opas_user.opas_id = t_gym_reservation.opas_id', 'LEFT');
		$query = $this->db->get('t_gym_reservation');

		return $query->row_array();
	}

	public function save(string $id, array $gym_list, string $month)
    {
		// OPAS ID と 年月が同じものをいったんクリア
		$where = [
			'opas_id' => $id,
			"DATE_FORMAT(date, '%Y%m') = " => sprintf('2019%02s', $month),
		];
		$this->db->delete('t_gym_reservation', $where);

		foreach ($gym_list as $gym)
		{
			$params = [
				'opas_id'   => $id,
				'date'      => $gym['date'],
				'name'      => $gym['name'],
				'place'     => $gym['place'],
				'station'   => $gym['station'],
				'time_from' => $gym['time_from'],
				'time_to'   => $gym['time_to'],
				'canceled'  => $gym['canceled'],
			];
			$this->db->insert('t_gym_reservation', $params);
        }
    }

}
