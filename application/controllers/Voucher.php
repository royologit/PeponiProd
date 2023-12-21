<?php

class Voucher extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Voucher_model', 'voucher');
    }

    public function xmlhttp_check_voucher_code()
    {
        if ($this->input->is_ajax_request()) {
            $voucherCode = $this->input->post('voucher_code');
            $productId   = $this->input->post('product_id');

            if(!$voucherCode || !$productId) {
                return $this->jsonResponse([
                    'valid'     => false,
                    'voucher'   => null
                ]);
            }

            $voucher = $this->voucher->getFromCodeForProduct($voucherCode, $productId);

            if ($voucher && $voucher->voucher_code !== $voucherCode) {
                $voucher = null;
            }

            return $this->jsonResponse([
                'valid'     => $voucher ? true : false,
                'voucher'   => $voucher
            ]);
        } else {
            show_404();
        }
    }
}