<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Order_model extends CI_Model
{
    public $table = 'order';
    function __construct()
    {
 		parent::__construct();
        $this->load->model(array('system2'));
    }

    public function getOrder($orderId = null)
    {
        $this->db->join('order_type', 'order_type.order_type_id = order.order_type_id');
        $this->db->join('payment_method', 'payment_method.payment_method_id = order.payment_method_id', 'left');
        $this->db->join('product', 'product.product_id = order.product_id');
        $this->db->join('voucher', 'voucher.voucher_id = order.voucher_id', 'left');

        if ($orderId) {
            $this->db->where('order.order_id', $orderId);
        }

        $this->db->order_by('order.created_at', 'desc');
        $this->db->select('*, product.product_duration, order.created_at as created_at', false);

        $query = $this->db->get($this->table);

        if ($orderId) {
            return $query->row();
        } else {
            return $query->result();
        }
    }

    public function getOrderDetail($orderId)
    {
        $this->db->join('age_group', 'age_group.age_group_id = order_detail.age_group_id', 'join');
        $this->db->where('order_detail.order_id', $orderId);

        $query = $this->db->get('order_detail');

        return $query->result();
    }

    public function insertOrder($orderData, $orderDetailData,$index)
    {
      //  var_dump($orderData);die;
        $this->load->model('Voucher_model', 'voucher');

        $this->db->trans_start();

        $this->db->insert($this->table, $orderData);

        $orderId = $this->db->insert_id();

        if ($orderData['voucher_id'] !== null) {
            $this->voucher->substractVoucherQuota($orderData['voucher_id']);
        }

        foreach ($orderDetailData as $data) {
            $data['order_id'] = $orderId;
            
            $this->db->insert('order_detail', $data);
        }

        $this->db->trans_complete();

        if ($orderData["order_type_id"] == 1 && $index==0) {
            $url = $this->system2->generateInvoice($orderId,false);
        }
        
        return array(
            "order_id"=>$orderId,
            "url" =>$url
        );
    }
}
