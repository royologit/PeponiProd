<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Product_price_model extends CI_Model
{
    public $table = 'product_price';

    public function getProductPrice($productId, $isActive = true)
    {
        $this->db->join('age_group', 'age_group.age_group_id = product_price.age_group_id', 'join');
        $this->db->where('product_id', $productId);

        if ($isActive) {
            $this->db->where('age_group_deactivated_at', null);
        }

        $query = $this->db->get($this->table);

        return $query->result();
    }

    public function updateProductPrice($productId, $productPrices)
    {
        $this->db->trans_start();

        $this->db->where('product_id', $productId);
        $this->db->delete($this->table);

        foreach ($productPrices as $ageGroupId => $productPrice) {
            $this->db->insert($this->table, [
                'product_id'    => $productId,
                'age_group_id'  => $ageGroupId,
                'product_price' => $productPrice,
            ]);
        }

        $this->db->trans_complete();
    }
}