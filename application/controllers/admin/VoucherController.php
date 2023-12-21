<?php
use Carbon\Carbon;

defined('BASEPATH') OR exit('No direct script access allowed');

class VoucherController extends CI_Controller
{
    private $data;

    public function __construct()
    {
        parent::__construct();
        $this->custom->session_validation('admin_id');
        $this->custom->session_validation('admin_name');

        $this->load->model('Voucher_model', 'voucher');
        $this->load->model('Product_model', 'product');

        $this->data = [
            'admin_route' => $this->config->item('admin_dir_controller'),
            'title'       => $this->uri->segment(2),
            'content_managements'   => $this->config->item('content_management')
        ];
    }

    public function add()
    {
        if ($this->input->post()) {
            $this->form_validation->set_error_delimiters('<span class="help-block">', '</span>');

            $formRules = [
                [
                    'field' => 'voucher_code',
                    'label' => 'Voucher Code',
                    'rules' => 'required|is_unique[voucher.voucher_code]'
                ],
                [
                    'field' => 'voucher_amount',
                    'label' => 'Voucher amount',
                    'rules' => 'required|numeric'
                ],
                [
                    'field' => 'voucher_quota',
                    'label' => 'Voucher quota',
                    'rules' => 'numeric'
                ],
                [
                    'field' => 'voucher_expiration_date',
                    'label' => 'Voucher expiration date',
                    'rules' => 'callback_date_check'
                ],
                [
                    'field' => 'voucher_active',
                    'label' => 'Voucher active',
                    'rules' => 'required|numeric'
                ],
                [
                    'field' => 'voucher_product[]',
                    'label' => 'Voucher product',
                    'rules' => 'required|numeric'
                ]
            ];

            $this->form_validation->set_rules($formRules);

            if ($this->form_validation->run() === true) {
                $quota          = $this->input->post('voucher_quota', true);
                $isActive       = $this->input->post('voucher_active', true);
                $expirationDate = $this->input->post('voucher_expiration_date', true);

                $voucherData = [
                    'voucher_code'            => $this->input->post('voucher_code', true),
                    'voucher_amount'          => $this->input->post('voucher_amount', true),
                    'voucher_quota'           => $quota ?: NULL,
                    'voucher_expiration_date' => $expirationDate ?: NULL,
                    'voucher_deactivated_at'  => $isActive ? NULL : Carbon::now()->toDateTimeString(),
                ];

                $voucherProductIds = $this->input->post('voucher_product');

                $this->voucher->insertVoucher($voucherData, $voucherProductIds);

                redirect($this->config->item('admin_softlink') . '/Voucher_Management');
            }
        }

        $products = $this->product->getProduct();

        $this->data['method']          = 'add';
        $this->data['products']        = $products;

        $this->load->view('admin/update_voucher', $this->data);
    }

    public function edit($voucherId)
    {
        $voucher = $this->voucher->getVoucher($voucherId);

        if (!$voucher) {
            redirect(lastPage());
        }

        if ($this->input->post()) {
            $this->form_validation->set_error_delimiters('<span class="help-block">', '</span>');

            $formRules = [
                [
                    'field' => 'voucher_code',
                    'label' => 'Voucher Code',
                    'rules' => 'required'
                ],
                [
                    'field' => 'voucher_amount',
                    'label' => 'Voucher amount',
                    'rules' => 'required|numeric'
                ],
                [
                    'field' => 'voucher_quota',
                    'label' => 'Voucher quota',
                    'rules' => 'numeric'
                ],
                [
                    'field' => 'voucher_expiration_date',
                    'label' => 'Voucher expiration date',
                    'rules' => 'callback_date_check'
                ],
                [
                    'field' => 'voucher_active',
                    'label' => 'Voucher active',
                    'rules' => 'required|numeric'
                ],
                [
                    'field' => 'voucher_product[]',
                    'label' => 'Voucher product',
                    'rules' => 'required|numeric'
                ]
            ];

            $this->form_validation->set_rules($formRules);

            if ($this->form_validation->run() === true) {
                $quota          = $this->input->post('voucher_quota', true);
                $isActive       = $this->input->post('voucher_active', true);
                $expirationDate = $this->input->post('voucher_expiration_date', true);

                $voucherData = [
                    'voucher_code'            => $this->input->post('voucher_code', true),
                    'voucher_amount'          => $this->input->post('voucher_amount', true),
                    'voucher_quota'           => $quota ?: NULL,
                    'voucher_expiration_date' => $expirationDate ?: NULL,
                    'voucher_deactivated_at'  => $isActive ? NULL : Carbon::now()->toDateTimeString(),
                ];

                $voucherProductIds = $this->input->post('voucher_product');

                $this->voucher->updateVoucher($voucherId, $voucherData, $voucherProductIds);

                redirect($this->config->item('admin_softlink') . '/Voucher_Management');
            }
        }

        $voucherDetails = $this->voucher->getVoucherDetail($voucherId);
        
        $products = $this->product->getProduct();

        $this->data['method']          = 'edit';
        $this->data['voucher']         = $voucher;
        $this->data['voucherDetails']  = $voucherDetails;
        $this->data['products']        = $products;

        $this->load->view('admin/update_voucher', $this->data);
    }

    public function date_check($date)
    {
        if (!$date) {
            return true;
        }

        $this->form_validation->set_message('date_check', '{field} is not valid date');

        try {
            Carbon::createFromFormat('Y-m-d', $date);
            return true;
        } catch (\Exception $e) {
            try {
                Carbon::createFromFormat('Y-m-d H:i:s', $date);
                return true;
            } catch (\Exception $e) {
                return false;
            }
        }
    }
}