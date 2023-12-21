<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Product_period_model extends CI_Model
{
    public $table = 'trip_period';

    public function getProductPeriod($productId)
    {
        $this->db->where('product_id', $productId);
        $query = $this->db->get($this->table);
        return $query->row();
    }

    public function getPrivatePeriod($privateId)
    {
        $this->db->where('private_id', $privateId);
        $query = $this->db->get($this->table);
        return $query->row();
    }
}