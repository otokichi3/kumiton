<?php if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Match extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_match()
    {
        $this->db->select('server1, server2, receiver1, receiver2');
        $this->db->where('play_flg', FLG_OFF);
        $this->db->order_by("RAND()");
        $this->db->limit('4');

        $result_array = $this->db->get('t_match')->result_array();
        return $result_array;
    }

    public function get_negotiation_list($buyer_id, $id=null, $sort=null, $count=false, $limit=null)
    {
        $this->db->select('
            t_offer.id,
            t_booking.id AS booking_id
        ');
        $this->db->join('t_offer_spec', 't_offer_spec.t_offer_id = t_offer.id AND t_offer_spec.del_flg=0');
        $this->db->join('t_vehicle_base', 't_vehicle_base.id = t_offer_spec.t_vehicle_base_id');
        $this->db->join('m_as_maker', 'm_as_maker.id = t_vehicle_base.m_as_maker_id', 'left outer');
        $this->db->join('m_as_model', 'm_as_model.id = t_vehicle_base.m_as_model_id', 'left outer');
        $this->db->join('m_agent', 'm_agent.id = t_offer.m_agent_id');
        $this->db->join('t_invoice', 't_invoice.t_offer_id = t_offer.id', 'left outer');
        $this->db->join('t_proforma_invoice', 't_proforma_invoice.t_offer_id = t_offer.id AND t_proforma_invoice.del_flg=0', 'left outer');
        $this->db->join('m_buyer_user', 'm_buyer_user.id = t_offer.m_buyer_user_id');
        $this->db->join('t_booking', 't_booking.t_offer_id = t_offer.id AND t_booking.del_flg=0', 'left outer');
        $this->db->where('t_offer.del_flg', 0);
        $this->db->where('t_offer.m_buyer_user_id', $buyer_id);
        $this->db->group_by('t_offer.id');

        if (strlen($id)) {
            $this->db->where('t_offer.id', $id);
            $row_array = $this->db->get('t_offer')->row_array();
            return $row_array;
        } else {
            if ($count === true) {
                return $this->db->count_all_results('t_offer');
            }

            if ($limit) {
                $this->db->limit($limit['to'], $limit['from']);
            }
            $this->_set_order_by($sort);

            $result_array = $this->db->get('t_offer')->result_array();
            return $result_array;
        }
    }

    // public function update_read_flg($buyer_id, $offer_id)
    // {
    //     $this->db->set('newest_buyer_read_flg', 1);
    //     $this->db->where('del_flg', 0);
    //     $this->db->where('m_buyer_user_id', $buyer_id);
    //     $this->db->where('id', $offer_id);
    //     $this->db->update('t_offer');
    // }


    // public function get_status_cd($buyer_id, $offer_id)
    // {
    //     $this->db->select('status_cd');
    //     $this->db->where('del_flg', 0);
    //     $this->db->where('m_buyer_user_id', $buyer_id);
    //     $this->db->where('id', $offer_id);

    //     $row_array = $this->db->get('t_offer')->row_array();
    //     return $row_array['status_cd'];
    // }
}
