<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Product_model extends CI_Model
{
    public $table = 'product';

    public function getProduct($productId = null, $isActive = true)
    {
        if ($productId) {
            $this->db->where('product_id', $productId);
        }

        if ($isActive) {
            $this->db->where('product_deactivated_at', null);
        }

        $query = $this->db->get($this->table);

        if ($productId) {
            return $query->row();
        } else {
            return $query->result();
        }
    }
    public function pushProduct($productId,$status)
    {
       return  $this->db->where('product_id', $productId)->update($this->table,[
            "product_push"=>$status
        ]);
    }
}
