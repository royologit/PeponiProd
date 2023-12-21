<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Age_group_model extends CI_Model
{
    public $table = 'age_group';

    public function getAgeGroup()
    {
        $query = $this->db->get_where($this->table, ['age_group_deactivated_at' => null]);

        return $query->result();
    }
}