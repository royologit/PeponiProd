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
        $this->load->model('Product_period_model', 'productPeriod');
        $this->load->model('Voucher_model', 'voucher');
        $this->load->model('Payment_method_model', 'paymentMethod');
      //  $this->load->model($this->config->item('admin_dir_model') . 'admin', 'admin');
        $this->load->library('session');
        $this->load->library('email');
        $this->load->library('template');
        $this->load->library('form_validation');

        $this->load->helper('string');

        $this->data = [
            "contacts" => $this->admin->select('contact'),
            "socmeds"  => $this->admin->select('media'),
            "footer"   => $this->admin->select('footer')
        ];
       // $navbar = array(
         //   'admin_id' 				=> 1,
        //    'admin_name'			=> "test",
       // );
 //   $this->session->set_userdata($navbar);
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
                    'email'         => $this->input->post('email', true),
                    'voucher'       => $this->input->post('voucher', true) ?: null,
                    'age'           => $this->input->post('age', true) ?: null,
                    'gender'        => $this->input->post('gender',true)
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

    public function cvoucher($str,$pid)
    {
        $tpax = $this->input->post('tpax');
        if($str==''){
            return TRUE;
        }
        
        $voucher    = $this->voucher->getFromCodeForProduct($str, $pid);
        $voucherId  = $voucher ? $voucher->voucher_id : null;

        $sisavoucher = $this->voucher->getVoucher($voucherId);

        if($sisavoucher->voucher_quota < $tpax){
            $this->form_validation->set_message('cvoucher', 'Kode Voucher Sudah Habis / Kurang');
            return FALSE;
        }else{
            return TRUE;
        }
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
        $productPeriod = $this->productPeriod->getProductPeriod($product->product_id);
        if(!empty($productPeriod)) {
            $downPayment = $productPeriod->down_payment; 
        } else {
            $downPayment = 1000000*count($inputData->fullname); 
        }

        foreach ($productPrices as $productPrice) {
            $inputName = 'ageGroup' . $productPrice->age_group_id;

            if (property_exists($inputData, $inputName) && ($quantity = $inputData->$inputName)) {
                $totalPax   += $quantity;
                $totalPrice += ($productPrice->product_price * $quantity);
            }
        }
        
        $voucherCode = $inputData->voucher;
       
        if ($voucherCode!=null) {
            $voucher = $this->voucher->getFromCodeForProduct($voucherCode, $product->product_id);
            if ($voucher) {
                $totalPrice -= $voucher->voucher_amount*count($inputData->fullname);
            }
        }
        
      
        if ($this->input->post()) {
     
                 
            $this->form_validation->set_rules('voucher', 'voucher', 'callback_cvoucher['.$product->product_id.']');
            
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
                    $totalPrice -= $voucher->voucher_amount*count($inputData->fullname);
                }


                
                $orderId = null;
                $ada = -1;
                $fn = json_decode($this->input->post('fullname', true),true);
                $ph = json_decode($this->input->post('phone', true),true);
                $em = json_decode($this->input->post('email', true),true);
                $gender = json_decode($this->input->post('gender', true),true);

              
                for ($i=0; $i < count($fn); $i++) { 
                    $temp = [
                        'order_name'        => $gender[$i].' '.ucwords(strtolower($fn[$i])),
                        'order_phone'       => $ph[$i],
                        'order_email'       => $i==0?$em[$i]:"",
                        'payment_method_id' => 1,
                        'order_price'       => $totalPrice,
                        'voucher_id'        => $voucherId,
                        'product_id'        => $product->product_id,
                        'order_type_id'     => 1,
                        'order_leader'      => $i==0?1:0
                    ];

                    try {
                        $result = $this->order->insertOrder($temp, $orderDetailData,$i);
                        $orderId = $result["order_id"];
                        //if($i==0){
                            //Email Reservasi OLD
                            //$this->send_order_notification_email($orderId);
                        //}
                        $ada=1;
                    } catch (\Exception $e) {
                        redirect(lastPage());
                    }
                }
                if ($ada==1) {
                   // $invoiceData = $this->system2->getInvoices(array("invoice_id"=>$this->input->post("invoice_id"),"request_order_name"=>true));
                    
                    redirect('/package/' . $productId . '/order-open-trip/finish');
                } else {
                    redirect(lastPage());
                }
            }
        }
        



        foreach ($inputData as $key => $value) {
            $this->data[$key] = $value;
        }
        $productDurations = explode(" - ", $product->product_duration);
       
        if (count($productDurations) > 1) {
            if(stripos($productDurations[1]," (")>=0){
                $detail = explode(" (", $productDurations[1]);
                $productDurations[1] = str_replace(" ","",$detail[0]);
            }
            $productDurations[0] = str_replace(" ","",$productDurations[0]);
            $productStartDate = Carbon::parse($productDurations[0])->format('d F Y');
            $productEndDate = Carbon::parse($productDurations[1])->format('d F Y');
            
            $product->product_date = $productStartDate . " - " . $productEndDate;
        } else {
            $product->product_date = $product->product_duration;
        }

        $this->data['paymentMethods'] = "1";
        $this->data['productPrices']  = $productPrices;
        $this->data['product']        = $product;
        $this->data['totalPrice']     = $totalPrice;
        $this->data['totalPax']       = $totalPax;
        $this->data['pricePerPax']    = $downPayment;
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
            
                $dates = explode(' - ', $this->input->post('date-range'));

                $startDate = Carbon::parse($dates[0])->toDateTimeString();
                $endDate   = Carbon::parse($dates[1])->toDateTimeString();

                $voucherCode = $this->input->post('voucher', true);

                $voucherId = null;
                if ($voucherCode) {
                    $voucher    = $this->voucher->getFromCodeForProduct($voucherCode, $product->product_id);
                    $voucherId  = $voucher ? $voucher->voucher_id : null;
                }

                $orderId = null;
                $ada = -1;
                $fn = $this->input->post('fullname', true);
                $ph = $this->input->post('phone', true);
                $em = $this->input->post('email', true);
                
               
                for ($i=0; $i < count($fn); $i++) { 
                    $orderDetailData = [];
                    $orderData = [
                        'order_name'        => $fn[$i],
                        'order_phone'       => $ph[$i],
                        'order_email'       => $em[$i],
                        'order_start_date' => $startDate,
                        'order_end_date'   => $endDate,
                        'voucher_id'       => $voucherId,
                        'product_id'       => $product->product_id,
                        'order_type_id'    => 2,
                        'order_leader'      => $i==0?1:0
                    ];
                    
                    foreach ($productPrices as $productPrice) {
                        $inputName = 'ageGroup' . $productPrice->age_group_id;
    
                        if ($quantity = $this->input->post($inputName, true)) {
                            $orderDetailData[] = [
                                'age_group_id'  => $productPrice->age_group_id,
                                'order_detail_quantity'      => $quantity
                            ];
                        }
                    }
                    try {
                        $orderId = $this->order->insertOrder($orderData, $orderDetailData,$i);

                        if($i==0){
                            $this->send_order_private_notification_email($orderId->order_id);
                        }
                        $ada=1;
                    } catch (\Exception $e) {
                        
                        redirect(lastPage());
                    }
                }
                
                if ($ada==1) {
                   // $invoiceData = $this->system2->getInvoices(array("invoice_id"=>$this->input->post("invoice_id"),"request_order_name"=>true));
                    redirect('/package/' . $productId . '/order-open-trip/finish');
                } else {
                    redirect(lastPage());
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
        $this->data["url"] = $this->session->userData("invoice")["url"];
        $this->load->view('order/order_finish', $this->data);
    }

    private function send_order_notification_email($orderId)
    {
        $order = $this->order->getOrder($orderId);
        $orderDetail = $this->order->getOrderDetail($orderId);
        $downPayment = 1000000;
        
        $productPeriod = $this->productPeriod->getProductPeriod($order->product_id);
        if(!empty($productPeriod)) {
            $downPayment = $productPeriod->down_payment; 
        } else {
            $downPayment = 1000000; 
        }
        

        if ($order && $orderDetail) {
            $order->expired_at = Carbon::parse($order->created_at)->addHours(3)->format('l, d F Y \J\a\m H:i T');

            $paxCount = 0;
            foreach($orderDetail as $detail) {
                $paxCount += $detail->order_detail_quantity;
            }

            $order->down_payment = $paxCount * $downPayment;

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
    
    private function send_order_private_notification_email($orderId)
    {
        $order = $this->order->getOrder($orderId);
        $orderDetail = $this->order->getOrderDetail($orderId);
        $downPayment = 1000000;
        if(!empty($order->private_id)) {
            $productPeriod = $this->productPeriod->getPrivatePeriod($order->private_id);
            if(!empty($productPeriod)) {
                $downPayment = $productPeriod->down_payment; 
            } else {
                $downPayment = 1000000; 
            }
        } 

        if ($order && $orderDetail) {
            $order->expired_at = Carbon::parse($order->created_at)->addHours(3)->format('l, d F Y \J\a\m H:i T');

            $paxCount = 0;
            foreach($orderDetail as $detail) {
                $paxCount += $detail->order_detail_quantity;
            }

            $order->down_payment = $paxCount * $downPayment;

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
