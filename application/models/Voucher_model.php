<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Voucher_model extends CI_Model
{
    public $table = 'voucher';

    public function getVoucher($voucherId)
    {
        $this->db->where('voucher.voucher_id', $voucherId);

        $query = $this->db->get($this->table);
        return $query->row();
    }

    public function getVoucherDetail($voucherId)
    {
        $this->db->where('voucher_id', $voucherId);
        $this->db->join('product', 'product.product_id = voucher_detail.product_id', 'join');

        $query = $this->db->get('voucher_detail');
        return $query->result();
    }

    public function insertVoucher($voucherData, $voucherProductIds)
    {
        $this->db->trans_start();
        $this->db->insert($this->table, $voucherData);

        $voucherId = $this->db->insert_id();
        foreach ($voucherProductIds as $productId) {
            $this->db->insert('voucher_detail', [
                'voucher_id' => $voucherId,
                'product_id' => $productId,
            ]);
        }
        $this->db->trans_complete();
    }

    public function updateVoucher($voucherId, $voucherData, $voucherProductIds)
    {
        $this->db->trans_start();

        foreach ($voucherData as $column => $value) {
            $this->db->set($column, $value);
        }

        $this->db->where('voucher_id', $voucherId);
        $this->db->update($this->table);

        $this->db->where('voucher_id', $voucherId);
        $this->db->delete('voucher_detail');

        foreach ($voucherProductIds as $productId) {
            $this->db->insert('voucher_detail', [
                'voucher_id' => $voucherId,
                'product_id' => $productId,
            ]);
        }

        $this->db->trans_complete();
    }

    public function substractVoucherQuota($voucherId)
    {
        $this->db->where('voucher.voucher_id', $voucherId);
        $this->isActive();

        $voucher = $this->db->get($this->table)->row();

        if (!$voucher) {
            throw new \ErrorException();
        }

        if ($voucher->voucher_quota !== null) {
            $this->db->set('voucher_quota', ($voucher->voucher_quota - 1));
            $this->db->where('voucher.voucher_id', $voucherId);
            $this->db->update($this->table);
        }
    }

    public function getFromCodeForProduct($code, $productId)
    {
        $this->db->join('voucher_detail', 'voucher_detail.voucher_id = voucher.voucher_id', 'join');
        $this->db->where('voucher.voucher_code', $code);
        $this->db->where('voucher_detail.product_id', $productId);
        $this->isActive();

        $query = $this->db->get($this->table);

        return $query->row();
    }

    private function isActive()
    {
        $this->db->where('voucher.voucher_deactivated_at IS NULL', null, false);
        $this->db->where('(voucher.voucher_expiration_date IS NULL', null, false);
        $this->db->or_where("voucher.voucher_expiration_date > NOW())", null, false);
        $this->db->where('(voucher.voucher_quota IS NULL', null, false);
        $this->db->or_where("voucher.voucher_quota > 0)", null, false);
    }
}