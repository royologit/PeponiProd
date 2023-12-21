<?php

use Carbon\Carbon;

class Order extends CI_Controller
{
    private $data;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Product_model', 'product');
        $this->load->model('Order_model', 'order');
        $this->load->model('Order_type_model', 'orderType');
        $this->load->model('Product_price_model', 'productPrice');
        $this->load->model('Voucher_model', 'voucher');
        $this->load->model('Payment_method_model', 'paymentMethod');
        $this->load->model($this->config->item('admin_dir_model') . 'admin', 'admin');

        $this->load->library('email');
        $this->load->library('template');
        $this->load->library('form_validation');

        $this->load->helper('string');

        $this->data = [
            "contacts" => $this->admin->select('contact'),
            "socmeds"  => $this->admin->select('media'),
            "footer"   => $this->admin->select('footer')
        ];
    }

    public function order_open_step_1($productId)
    {
        $product = $this->product->getProduct($productId);

        if (
            !$product ||
            !$this->orderType->hasOrderTypeFromProductOrderType($product->product_order_type, 1)
        ) {
            redirect(lastPage());
        }

        $productPrices = $this->productPrice->getProductPrice($product->product_id);

        if ($this->input->post()) {
            $this->form_validation->set_error_delimiters('<label class="error">', '</label>');

            $formRules = [
                [
                    'field' => 'fullname',
                    'label' => 'Full name',
                    'rules' => 'required|min_length[5]'
                ],
                [
                    'field' => 'phone',
                    'label' => 'Phone number',
                    'rules' => 'required|numeric|min_length[3]'
                ],
                [
                    'field' => 'email',
                    'label' => 'Email',
                    'rules' => 'required|valid_email'
                ],
                [
                    'field' => 'voucher',
                    'label' => 'Voucher code',
                    'rules' => 'callback_voucher_check['.$product->product_id.']'
                ]
            ];

            foreach ($productPrices as $productPrice) {
                $formRules[] = [
                    'field' => 'ageGroup' . $productPrice->age_group_id,
                    'label' => $productPrice->age_group_name,
                    'rules' => 'greater_than_equal_to[0]|less_than_equal_to[100]|callback_participant_check['.$product->product_id.']'
                ];
            }

            $this->form_validation->set_rules($formRules);

            if ($this->form_validation->run() === true) {
                $inputData = [
                    'fullname'      => $this->input->post('fullname', true),
                    'phone'         => $this->input->post('phone', true),
                    'lineID'        => $this->input->post('lineID', true) ?: null,
                    'email'         => $this->input->post('email', true),
                    'voucher'       => $this->input->post('voucher', true) ?: null,
                ];

                foreach ($productPrices as $productPrice) {
                    $inputName = 'ageGroup' . $productPrice->age_group_id;

                    if ($quantity = $this->input->post($inputName, true)) {
                        $inputData[$inputName] = $quantity;
                    }
                }

                $sessionKey = $this->input->get('order', true) ?: random_string('alnum', 8);

                $this->session->set_userdata($sessionKey, json_encode([
                    'product_id'        => base64_encode($productId),
                    'input_data'        => $inputData,
                ]));

                redirect('/package/' . $productId . '/order-open-trip/review?order=' . $sessionKey);
            }
        }

        $sessionKey  = $this->input->get('order', true);
        $sessionData = $this->session->userdata($sessionKey);
        $inputData   = null;

        if ($sessionKey && $sessionData) {
            $sessionData = json_decode($sessionData);

            if (base64_decode($sessionData->product_id) == $productId) {
                $inputData   = $sessionData->input_data;
            }
        }

        $this->data['orderType']     = 'open';
        $this->data['productPrices'] = $productPrices;
        $this->data['productId']     = $product->product_id;
        $this->data['inputData']     = $inputData;

        $this->load->view('order/order_step_1', $this->data);
    }

    public function order_open_step_2($productId)
    {
        $product    = $this->product->getProduct($productId);
        $sessionKey = $this->input->get('order', true);

        if (
            !$product ||
            !$this->orderType->hasOrderTypeFromProductOrderType($product->product_order_type, 1)
        ) {
            redirect(lastPage());
        }

        $sessionData = $this->session->userdata($sessionKey);
        if (!$sessionKey || !$sessionData) {
            redirect('/package/' . $productId . '/order-open-trip');
        }

        $sessionData = json_decode($sessionData);
        if (!$sessionData->input_data ||
            !$sessionData->product_id ||
            base64_decode($sessionData->product_id) != $productId)
        {
            redirect('/');
        }


        $inputData = $sessionData->input_data;

        $totalPrice    = 0;
        $totalPax      = 0;
        $productPrices = $this->productPrice->getProductPrice($product->product_id);

        foreach ($productPrices as $productPrice) {
            $inputName = 'ageGroup' . $productPrice->age_group_id;

            if (property_exists($inputData, $inputName) && ($quantity = $inputData->$inputName)) {
                $totalPax   += $quantity;
                $totalPrice += ($productPrice->product_price * $quantity);
            }
        }

        if ($voucherCode = $inputData->voucher) {
            $voucher = $this->voucher->getFromCodeForProduct($voucherCode, $product->product_id);

            if ($voucher) {
                $totalPrice -= $voucher->voucher_amount;
            }
        }

        $paymentMethods = $this->paymentMethod->getAllPaymentMethod();

        if ($this->input->post()) {
            $formRules = [
                [
                    'field' => 'fullname',
                    'label' => 'Full name',
                    'rules' => 'required|min_length[5]'
                ],
                [
                    'field' => 'phone',
                    'label' => 'Phone number',
                    'rules' => 'required|numeric|min_length[3]'
                ],
                [
                    'field' => 'email',
                    'label' => 'Email',
                    'rules' => 'required|valid_email'
                ],
                [
                    'field' => 'voucher',
                    'label' => 'Voucher code',
                    'rules' => 'callback_voucher_check['.$product->product_id.']'
                ],
                [
                    'field' => 'paymentMethod',
                    'label' => 'Payment method',
                    'rules' => 'required|numeric'
                ]
            ];

            foreach ($productPrices as $productPrice) {
                $formRules[] = [
                    'field' => 'ageGroup' . $productPrice->age_group_id,
                    'label' => $productPrice->age_group_name,
                    'rules' => 'greater_than_equal_to[0]|less_than_equal_to[100]|callback_participant_check['.$product->product_id.']'
                ];
            }

            $this->form_validation->set_rules($formRules);

            if ($this->form_validation->run() === true) {
                $voucherCode = $this->input->post('voucher', true);

                $voucher   = null;
                $voucherId = null;
                if ($voucherCode) {
                    $voucher    = $this->voucher->getFromCodeForProduct($voucherCode, $product->product_id);
                    $voucherId  = $voucher ? $voucher->voucher_id : null;
                }

                $totalPrice      = 0;
                $orderDetailData = [];
                foreach ($productPrices as $productPrice) {
                    $inputName = 'ageGroup' . $productPrice->age_group_id;

                    if ($quantity = $this->input->post($inputName, true)) {
                        $price = $productPrice->product_price * $quantity;
                        $orderDetailData[] = [
                            'age_group_id'               => $productPrice->age_group_id,
                            'order_detail_quantity'      => $quantity,
                            'order_detail_price'         => $price
                        ];

                        $totalPrice += $price;
                    }
                }

                if ($voucher) {
                    $totalPrice -= $voucher->voucher_amount;
                }

                $orderData = [
                    'order_name'        => $this->input->post('fullname', true),
                    'order_phone'       => $this->input->post('phone', true),
                    'order_line_id'     => $this->input->post('lineID', true) ?: null,
                    'order_email'       => $this->input->post('email', true),
                    'payment_method_id' => $this->input->post('paymentMethod'),
                    'order_price'       => $totalPrice,
                    'voucher_id'        => $voucherId,
                    'product_id'        => $product->product_id,
                    'order_type_id'     => 1
                ];

                $orderId = null;
                try {
                    $orderId = $this->order->insertOrder($orderData, $orderDetailData);
                } catch (\Exception $e) {
                    redirect(lastPage());
                }

                if ($orderId) {
                    #$this->send_order_notification_email($orderId);

                    $this->session->unset_userdata($sessionKey);
                    redirect('/package/' . $productId . '/order-open-trip/finish');
                } else {
                    redirect(lastPage());
                }
            } else {
                redirect('/package/'. $product->product_id .'/order-open-trip?order=' . $sessionKey);
            }
        }

        foreach ($inputData as $key => $value) {
            $this->data[$key] = $value;
        }

        $productDurations = explode(" ", $product->product_duration);

        if (count($productDurations) > 2) {
            $productStartDate = Carbon::parse($productDurations[0])->format('d F Y');
            $productEndDate = Carbon::parse($productDurations[2])->format('d F Y');

            $product->product_date = $productStartDate . " - " . $productEndDate;
        } else {
            $product->product_date = $product->product_duration;
        }

        $this->data['paymentMethods'] = $paymentMethods;
        $this->data['productPrices']  = $productPrices;
        $this->data['product']        = $product;
        $this->data['totalPrice']     = $totalPrice;
        $this->data['totalPax']       = $totalPax;
        $this->data['pricePerPax']    = 1000000;
        $this->data['backUrl']        = base_url('/package/'. $product->product_id .'/order-open-trip?order=' . $sessionKey);

        $this->load->view('order/order_step_2', $this->data);
    }

    public function order_private($productId)
    {
        $product = $this->product->getProduct($productId);

        if (
            !$product ||
            !$this->orderType->hasOrderTypeFromProductOrderType($product->product_order_type, 2)
        ) {
            redirect(lastPage());
        }

        $productPrices = $this->productPrice->getProductPrice($product->product_id);

        if ($this->input->post()) {
            $this->form_validation->set_error_delimiters('<label class="error">', '</label>');

            $formRules = [
                [
                    'field' => 'fullname',
                    'label' => 'Full name',
                    'rules' => 'required|min_length[5]'
                ],
                [
                    'field' => 'phone',
                    'label' => 'Phone number',
                    'rules' => 'required|numeric|min_length[3]'
                ],
                [
                    'field' => 'email',
                    'label' => 'Email',
                    'rules' => 'required|valid_email'
                ],
                [
                    'field' => 'voucher',
                    'label' => 'Voucher code',
                    'rules' => 'callback_voucher_check['.$product->product_id.']'
                ],
                [
                    'field' => 'date-range',
                    'label' => 'Date range',
                    'rules' => 'required|callback_date_range_check'
                ]
            ];

            foreach ($productPrices as $productPrice) {
                $formRules[] = [
                    'field' => 'ageGroup' . $productPrice->age_group_id,
                    'label' => $productPrice->age_group_name,
                    'rules' => 'greater_than_equal_to[0]|less_than_equal_to[100]|callback_private_participant_check['.$product->product_id.']'
                ];
            }

            $this->form_validation->set_rules($formRules);

            if ($this->form_validation->run() === true) {
                $dates = explode(' - ', $this->input->post('date-range'));

                $startDate = Carbon::parse($dates[0])->toDateTimeString();
                $endDate   = Carbon::parse($dates[1])->toDateTimeString();

                $voucherCode = $this->input->post('voucher', true);

                $voucherId = null;
                if ($voucherCode) {
                    $voucher    = $this->voucher->getFromCodeForProduct($voucherCode, $product->product_id);
                    $voucherId  = $voucher ? $voucher->voucher_id : null;
                }

                $orderData = [
                    'order_name'       => $this->input->post('fullname', true),
                    'order_phone'      => $this->input->post('phone', true),
                    'order_line_id'    => $this->input->post('lineID', true) ?: null,
                    'order_email'      => $this->input->post('email', true),
                    'order_note'       => $this->input->post('note', true) ?: null,
                    'order_start_date' => $startDate,
                    'order_end_date'   => $endDate,
                    'voucher_id'       => $voucherId,
                    'product_id'       => $product->product_id,
                    'order_type_id'    => 2
                ];

                $orderDetailData = [];
                foreach ($productPrices as $productPrice) {
                    $inputName = 'ageGroup' . $productPrice->age_group_id;

                    if ($quantity = $this->input->post($inputName, true)) {
                        $orderDetailData[] = [
                            'age_group_id'  => $productPrice->age_group_id,
                            'order_detail_quantity'      => $quantity
                        ];
                    }
                }

                $orderId = null;
                try {
                    $orderId = $this->order->insertOrder($orderData, $orderDetailData);
                } catch (\Exception $e) {
                    redirect(lastPage());
                }

                if ($orderId) {
                    $this->send_order_notification_email($orderId);
                    redirect('/package/' . $productId . '/order-private-trip/finish');
                } else {
                    redirect(lastPage());
                }
            }
        }

        $this->data['orderType']     = 'private';
        $this->data['productPrices'] = $productPrices;
        $this->data['productId']     = $product->product_id;
        $this->data['inputData']     = null;

        $this->load->view('order/order_step_1', $this->data);
    }

    public function voucher_check($voucherCode, $productId)
    {
        if (!$voucherCode) {
            return true;
        }

        $voucher = $this->voucher->getFromCodeForProduct($voucherCode, $productId);

        if ($voucher) {
            return true;
        }

        $this->form_validation->set_message('voucher_check', '{field} is invalid');
        return false;
    }

    public function date_range_check($dateRange)
    {
        $this->form_validation->set_message('date_range_check', '{field} is invalid');

        $dates = explode(' - ', $dateRange);

        if (count($dates) != 2) {
            return false;
        }

        foreach ($dates as $date) {
            if (!Carbon::parse($date)->isFuture()) {
                return false;
            };
        }

        return true;
    }

    public function participant_check($quantity, $productId)
    {
        if ($quantity) {
            return true;
        }

        $this->form_validation->set_message('participant_check', 'At least 1 participant required');

        $productPrices = $this->productPrice->getProductPrice($productId);

        foreach ($productPrices as $productPrice) {
            $inputName = 'ageGroup' . $productPrice->age_group_id;

            if ($this->input->post($inputName, true)) {
                return true;
            }
        }

        return false;
    }

    public function private_participant_check($quantity, $productId)
    {
        $this->form_validation->set_message('private_participant_check', 'At least 6 participant required');

        $productPrices = $this->productPrice->getProductPrice($productId);

        $totalParticipant = 0;
        foreach ($productPrices as $productPrice) {
            $inputName = 'ageGroup' . $productPrice->age_group_id;

            if ($value = $this->input->post($inputName, true)) {
                $totalParticipant += $value;
            }

            if ($totalParticipant >= 6) {
                return true;
            }
        }

        return false;
    }

    public function order_finish($orderTypeId)
    {
        switch ($orderTypeId) {
            case 1:
                $this->data['orderType'] = 'open';
                break;
            case 2:
                $this->data['orderType'] = 'private';
                break;
            default:
                $this->data['orderType'] = 'open';
                break;
        }

        $this->load->view('order/order_finish', $this->data);
    }

    private function send_order_notification_email($orderId)
    {
        $order = $this->order->getOrder($orderId);
        $orderDetail = $this->order->getOrderDetail($orderId);

        if ($order && $orderDetail) {
            $order->expired_at = Carbon::parse($order->created_at)->addHours(3)->format('l, d F Y \J\a\m H:i T');

            $paxCount = 0;
            foreach($orderDetail as $detail) {
                $paxCount += $detail->order_detail_quantity;
            }

            $order->down_payment = $paxCount * 1000000;

            $emailViewData = [
                'order'         => $order,
                'orderDetails'  => $orderDetail
            ];

            $adminEmailTitle    = 'Pemesanan baru telah masuk';
            $customerEmailTitle = 'Terima kasih atas pemesanan anda';

            $adminEmailView    = $this->template->load('email/base', 'email/admin_new_order', array_merge($emailViewData, [
                'title' => $adminEmailTitle
            ]), true);
            $customerEmailView = $this->template->load('email/base', 'email/customer_new_order', array_merge($emailViewData, [
                'title' => $customerEmailTitle
            ]), true);

            $this->email->to($order->order_email);
            $this->email->from("no-reply@peponitravel.com", "Peponitravel");
            $this->email->subject("Berikut detail order Anda - Peponitravel");
            $this->email->message($customerEmailView);
            $this->email->send();

            $this->email->clear();

            $this->email->to("cs@peponitravel.com");
            $this->email->from("no-reply@peponitravel.com", "Peponitravel");
            $this->email->subject("Order baru telah masuk - Peponitravel");
            $this->email->message($adminEmailView);
            $this->email->send();
            
            $this->email->clear();
            
            $this->email->to("financecontrol.peponitravel@gmail.com");
            $this->email->from("no-reply@peponitravel.com", "Peponitravel");
            $this->email->subject("Order baru telah masuk - Peponitravel");
            $this->email->message($adminEmailView);
            $this->email->send();
            
            $this->email->clear();


            $this->email->to("williamgunawan22@gmail.com");
            $this->email->from("no-reply@peponitravel.com", "Peponitravel");
            $this->email->subject("Order baru telah masuk - Peponitravel");
            $this->email->message($adminEmailView);
            $this->email->send();
        }
    }
}
