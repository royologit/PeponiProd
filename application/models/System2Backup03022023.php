<?php
defined('BASEPATH') or exit('No direct script access allowed');

class System2 extends CI_Model
{
    public $ci;
    public function __construct()
    {
        parent::__construct();
        $this->ci =& get_instance();
        $this->load->model('Payment_method_model', 'paymentMethod');

        $this->load->model($this->config->item('admin_dir_model').'admin', 'admin');
        $this->load->library('pdf');
        $this->load->library('email');
        $this->load->helper(array('url','form'));
        if ($this->uri->segment(3) != "cron_job_peponi") {
            $this->custom->session_validation('admin_id');
            $this->custom->session_validation('admin_name');
        }
    }

    public function getInvoices($params=array())
    {
        if (isset($params["order_id"]) || isset($params["invoice_id"])) {
            if (isset($params["order_id"])) {
                $this->db->where("order_id", $params["order_id"]);
            }
            if (isset($params["status"])) {
                $this->db->where("In.status", $params["status"]);
            }
            if (isset($params["invoice_id"])) {
                $this->db->where("id", $params["invoice_id"]);
            }
            if (isset($params["invoice_type"])) {
                $this->db->where("invoice_type", $params["invoice_type"]);
            }
            if (isset($params["request_order_name"])) {
                $this->db->select("In.*, O.order_name");
                $this->db->join("order O", "O.order_id = In.order_id");
            }
            if (isset($params["order_by"])) {
                $this->db->order_by($params["order_by"]);
            } else {
                $this->db->order_by("In.id ASC");
            }
            if (isset($params["limit"])) {
                $this->db->limit($params["limit"]);
            }
            $res = $this->db->get('invoices In')->row();
        } else {
            if (isset($params["status"])) {
                $this->db->where("status", $params["status"]);
            }
            if (isset($params["ref_id"])) {
                $this->db->where("id", $params["ref_id"]);
            }
            $this->db->select("In.*, O.order_name");
            $this->db->join("order O", "O.order_id = In.order_id");
            if (isset($params["order_by"])) {
                $this->db->order_by($params["order_by"]);
            } else {
                $this->db->order_by("In.id DESC");
            }
            if (isset($params["limit"])) {
                $this->db->limit($params["limit"]);
            }
            $res = $this->db->get('invoices In')->result();
        }
        return $res;
    }

    public function updateInvoices($params, $id=null)
    {
        if ($id != null) {
            $this->db->where("id", $id);
            $res = $this->db->update('invoices', $params);
            return $this->db->affected_rows();
        } else {
            $res = $this->db->insert('invoices', $params);
            return $this->db->insert_id();
        }
    }

    public function deleteInvoices($id=null, $order_id=null)
    {
        if ($id != null) {
            $this->db->where("id", $id);
            $this->db->delete("invoices");
            return $this->db->affected_rows();
        } elseif ($order_id != null) {
            $this->db->where("order_id", $order_id);
            $this->db->delete("invoices");
            return $this->db->affected_rows();
        }
    }

    public function generateInvoice($orderId, $rest = false)
    {
        
        $this->load->library('pdf');
        $lib= new pdf();
        if ($rest) {
            
            $discount = 0;
            $orderData      = $this->getOrder($orderId);
            $orderDetail    = $this->getOrderDetail($orderId);
            $productName = ($orderData->product_name == null) ? $orderData->private_name : $orderData->product_name;
            $tripType = ($orderData->product_name == null) ? "Private Trip" : "Open Trip";
            $isPrivate = ($orderData->product_name == null) ? 1 :0;
            $productId = ($orderData->product_id == null) ? $orderData->private_id : $orderData->product_id;
            $separator = "\n";
            $dataInvoice = [
                "url"=>""
            ];
            $params = array( "is_private_trip"  => $isPrivate,
                             "order_date"       => date("Y-m-d", strtotime($orderData->created_at))
                           );
            if($orderData->product_id == null) {
                $tripPeriod = $this->getPrivateTripPeriod($params, $productId);
            } else {
                $tripPeriod = $this->getTripPeriod($params, $productId);
                $tripPeriod  = $tripPeriod[0];
            }

            $quantity = 0;
            foreach ($orderDetail as $eachDetail) {
                $quantity += intval($eachDetail->order_detail_quantity);
            }
            $remainingPrice = intval($orderData->order_price);
            
            if (isset($tripPeriod->period_json)) {
                $periodData = json_decode($tripPeriod->period_json);
                $remainingPrice -= $quantity * intval($tripPeriod->down_payment);
                // die("remaining " . $remainingPrice);
                foreach ($periodData as $index => $period) {
                    if ($remainingPrice > 0) {
                        $currentPrice = intval($period->price);
                        $currentLabel = $period->label;
                        if(isset($period->duedate)){
                            $currentDueDate = date("Y-m-d 00:00:01", strtotime($period->duedate));
                        }
                        else{
                            $dp_invoice = $this->getInvoices(["order_id"=>$orderId,"type"=>"down payment"]);
                            $currentDueDate = date("Y-m-d", strtotime($dp_invoice->due_date.'+'.$period->days." days"));
                        }
                        if (count($periodData)-1 == $index) {
                            $invoicePrice = $remainingPrice;
                            $totalPrice =  $quantity*intval($invoicePrice);
                            if ($orderData->voucher_id != null && $orderData->voucher_id != "") {
                                $voucher = $this->getVoucher(array("voucher_id" => $orderData->voucher_id));
                                $discount = $voucher->voucher_amount;
                                $invoicePrice += $voucher->voucher_amount;
                                $totalPrice += $voucher->voucher_amount;
                            }
                        } else {
                            $invoicePrice = $currentPrice;
                            $totalPrice = $quantity*intval($invoicePrice);
                            $remainingPrice -= $totalPrice;
                        }
                        $detail = $currentLabel . $separator . $productName . " ($tripType)". $separator;
                        
                        if($orderData->product_id == null) {
                            foreach ($orderDetail as $eachDetail) {
                                if(count($periodData)-1 == $index) {
                                    $endPeriodData = $periodData;
                                    $totalPeriod = 0;
                                    foreach ($endPeriodData as $index => $periodE) {
                                        $totalPeriod += $periodE->price;
                                    }
                                    $totalRemaining = $eachDetail->order_detail_price - ($totalPeriod + $tripPeriod->down_payment);
                                    $detail .= "• " . $eachDetail->order_detail_quantity . " " . $eachDetail->age_group_name . ' X ' . $this->currency($totalRemaining) . $separator;
                                } else {
                                    $detail .= "• " . $eachDetail->order_detail_quantity . " " . $eachDetail->age_group_name . ' X ' . $this->currency($period->price) . $separator;
                                }
                            }
                        } else {
                            foreach ($orderDetail as $eachDetail) {
                                $detail .= "• " . $eachDetail->order_detail_quantity . " " . $eachDetail->age_group_name . $separator;
                            }
                        }
                        
                       
                        $invoiceData = array("order_id" => $orderId,
                                        "title"    => "$productName [$currentLabel]",
                                        "quantity" => $quantity,
                                        "description" => $detail,
                                        "price"    => $invoicePrice,
                                        "discount" => $discount,
                                        "tax"      => 0,
                                        "total"    => $totalPrice,
                                        "invoice_type" => $currentLabel,
                                        "due_date" => $currentDueDate,
                                        "status"   => 0,
                                        "created_date" => date("Y-m-d H:m:s"),
                                        "external_id" => "",
                                        "invoice_url" => "",
                                    );
                       
                        $invoiceId = $this->updateInvoices($invoiceData);
                        $invoiceData["id"] = $invoiceId;
                        $invoiceData["order_name"] = $orderData->order_name;
                        // $filePdf = $lib->generate_pdf((object) $invoiceData);

                        // $this->send_email($invoiceData, $filePdf);
                    }
                }
                return base_url()."pembayaran/".$invoiceId;
            } else {
                error_log("Skip!! Have no payment period!");
                $this->email_report("System Error Report", "Payment Period for package {$productName} and date {$orderData->created_at} not found. <br>You must create and send email manually for client {$orderData->order_name} with order id {$orderData->order_id}.<br><br><br><br>Good luck for doing your job. <br>Please make better preparation for next time. :)");
            }
        } else {
            
            $orderData      = $this->getOrder($orderId);
            $orderDetail    = $this->getOrderDetail($orderId);
            $productName = ($orderData->product_name == null) ? $orderData->private_name : $orderData->product_name;
            $tripType = ($orderData->product_name == null) ? "Private Trip" : "Open Trip";
            $isPrivate = ($orderData->product_name == null) ? 1 :0;
            $productId = ($orderData->product_id == null) ? $orderData->private_id : $orderData->product_id;
            $separator = "\n";
            $quantity = 0;
            $detail = "Down Payment". $separator . $productName . " ($tripType)". $separator;
            
            if (intval($orderData->order_price) == 0) {
                $this->send_email($orderId);
            }

            $params = array( "is_private_trip"  => $isPrivate,
                             "order_date"       => date("Y-m-d", strtotime($orderData->created_at))
                           );

            if($orderData->product_id == null) {
                $tripPeriod = $this->getPrivateTripPeriod($params, $productId);
            } else {
                $tripPeriod = $this->getTripPeriod($params, $productId);
                $tripPeriod = $tripPeriod[0];
            }
            
            if (isset($tripPeriod->down_payment)) {
                
                if($orderData->product_id == null) {
                    foreach ($orderDetail as $eachDetail) {
                        $quantity += intval($eachDetail->order_detail_quantity);
                        $detail .= "• " .$eachDetail->order_detail_quantity . " " . $eachDetail->age_group_name . ' X ' . $this->currency($tripPeriod->down_payment) . $separator;
                    }
                } else {
                    foreach ($orderDetail as $eachDetail) {
                        $quantity += intval($eachDetail->order_detail_quantity);
                        $detail .= "• " .$eachDetail->order_detail_quantity . " " . $eachDetail->age_group_name . $separator;
                    }
                }
                $invoiceData = array("order_id" => $orderId,
                                "title"    => "$productName [Down Payment]",
                                "quantity" => $quantity,
                                "description" => $detail,
                                "price"    => intval($tripPeriod->down_payment),
                                "tax"      => 0,
                                "discount" => 0,
                                "total"    => intval($tripPeriod->down_payment)*$quantity,
                                "invoice_type" => "down payment",
                                "due_date" => date("Y-m-d H:i:s", strtotime("+3 hours")),
                                "status"   => 0,
                                "created_date" => date("Y-m-d H:i:s"),
                                "external_id" => "",
                                "invoice_url" => ""
                            );
                           
                error_log("down payment due date ". date("Y-m-d H:i:s", strtotime("+3 hours")));
                $invoiceId = $this->updateInvoices($invoiceData);
                $invoiceData["id"] = $invoiceId;
                $invoiceData["order_name"] = $orderData->order_name;
                $invoiceData["invoice_header"] ="INVOICE";
                $filePdf = $lib->generate_pdf((object) $invoiceData);
                
                $this->send_email($orderId, $invoiceId,base_url()."pembayaran/".$invoiceId, "reservation", $filePdf, "Invoice $invoiceId - " . $invoiceData['title'] . ".pdf");
                
                #$this->send_email($orderId, $invoiceId, "invoice generate", $filePdf, "Invoice $invoiceId - " . $invoiceData->title . ".pdf");
                return base_url()."pembayaran/".$invoiceId;
            } else {
                error_log("Skip!! Have no payment period!");
                $this->email_report("System Error Report", "Payment Period for package {$productName} and date {$orderData->created_at} not found. <br>You must create and send email manually for client {$orderData->order_name} with order id {$orderData->order_id}.<br><br><br><br>Good luck for doing your job. <br>Please make better preparation for next time. :)");
            }
            // echo $detail;
        }
    }

    public function generateReceipt($invoiceData)
    {
        $this->load->library('pdf');
        $lib= new pdf();
        $invoiceData->invoice_header ="RECEIPT";
        
        $filePdf = $lib->generate_pdf((object) $invoiceData, false);
        
        $this->send_email($invoiceData->order_id, $invoiceData->id,"", "invoice receipt", $filePdf, "#".$invoiceData->id." - Receipt.pdf");
    }

    public function send_email($orderId, $invoiceId=null, $invoice_url=null,$emailType="reservation", $filePdf=null, $fileName=null, $alert = "")
    {
        // $this->load->library('email');
        // $email = new email();
        
        if ($alert != "") {
            $alert .= " ";
        }
        $orderData      = $this->getOrder($orderId);
        $orderDetail    = $this->getOrderDetail($orderId);
        // die("Invoice id " . $invoiceId);
        if ($invoiceId) {
            $invoiceData    = $this->getInvoices(array("invoice_id" => $invoiceId));
        } else {
            $invoiceData = null;
        }
        $isPrivate = ($orderData->product_name == null) ? 1 :0;
        $emailViewData = [
            'orderData'         => $orderData,
            'orderDetail'       => $orderDetail,
            'invoiceData'       => $invoiceData,
            'url_invoice'       => $invoice_url
        ];

        $emailParams = ["invoice_id" => $invoiceId, "email_type" => $emailType, "email" => $orderData->order_email];
        $checkEmailHistory = $this->getEmailLog($emailParams);

        if (count($checkEmailHistory) <= 0) {
            if ($emailType == "reservation") {
                if ($orderData->order_price) {
                    $customerEmailTitle = "Terima kasih atas Reservasi Anda";
                    $adminEmailTitle = "Reservasi baru - " . $orderData->order_name . " - " . $orderData->order_phone;
                    $customerEmailView   = $this->ci->load->view('email/v2/reservation_with_detail', $emailViewData, true);
                } else {
                    $customerEmailTitle = "Rincian biaya paket liburan Anda akan segera kami kirim";
                    $adminEmailTitle = "Permintaan Private Trip - " . $orderData->order_name . " - " . $orderData->order_phone;
                    $customerEmailView = $this->ci->load->view('email/v2/reservation_without_detail', $emailViewData, true);
                }
            } elseif ($emailType == "invoice generate") {
                $title = "#{$invoiceId} - Ini Rincian Tagihan Anda, Mohon Segera Lengkapi Pembayaran";
                $customerEmailTitle = $title;
                $adminEmailTitle = "Invoice sent H-7 Forward - Client " . $orderData->order_name . " - " . $title;
                $customerEmailView   = $this->ci->load->view('email/v2/invoice_detail', $emailViewData, true);
            } elseif ($emailType == "invoice receipt") {
                $title = "#{$invoiceId} - Terimakasih, Pembayaran Anda telah Berhasil";
                $customerEmailTitle = $title;
                $adminEmailTitle = "Pengiriman bukti pembayaran " . $orderData->order_name . " - " . $title;
                $customerEmailView   = $this->ci->load->view('email/v2/invoice_receipt', $emailViewData, true);
            } elseif ($emailType == "friendly reminder") {
                $title = "#{$invoiceId} - Tagihan Anda akan jatuh tempo dalam 3 hari kedepan";
                $customerEmailTitle = $title;
                $adminEmailTitle = "Friendly Reminder H-3 Forward - Client " . $orderData->order_name . " - " . $title;
                $customerEmailView   = $this->ci->load->view('email/v2/invoice_detail', $emailViewData, true);
            } elseif ($emailType == "due date") {
                $title = "#{$invoiceId} - Tagihan Anda telah jatuh tempo";
                $customerEmailTitle = $title;
                $adminEmailTitle = "Due Date Notification Forward - Client " . $orderData->order_name . " - " . $title;
                $customerEmailView   = $this->ci->load->view('email/v2/invoice_detail', $emailViewData, true);
            } elseif ($emailType == "first reminder") {
                $title = "#{$invoiceId} - Tagihan Anda telah jatuh tempo [Peringatan Pertama]";
                $customerEmailTitle = $title;
                $adminEmailTitle = "First Reminder H+3 Forward - Client " . $orderData->order_name . " - " . $title;
                $customerEmailView   = $this->ci->load->view('email/v2/invoice_detail', $emailViewData, true);
            } elseif ($emailType == "second reminder") {
                $title = "#{$invoiceId} - Tagihan Anda telah jatuh tempo [Peringatan Kedua]";
                $customerEmailTitle = $title;
                $adminEmailTitle = "Se`con`d Reminder H+7 Forward - Client " . $orderData->order_name . " - " . $title;
                $customerEmailView   = $this->ci->load->view('email/v2/invoice_detail', $emailViewData, true);
            } elseif ($emailType == "last reminder") {
                $title = "#{$invoiceId} - Tagihan Anda telah jatuh tempo [Peringatan Terakhir]";
                $customerEmailTitle = $title;
                $adminEmailTitle = "Last Reminder H+10 Forward - Client " . $orderData->order_name . " - " . $title;
                $customerEmailView   = $this->ci->load->view('email/v2/invoice_detail', $emailViewData, true);
            }
        } else {
            return false;
        }
            $this->email->to($orderData->order_email);
            $this->email->from("no-reply@peponitravel.com", "Peponitravel");
            $this->email->subject($customerEmailTitle);
            $this->email->message($customerEmailView);
            if ($filePdf != null) {
                $attachResult=$this->email->attach($filePdf, 'attachment', $fileName);
            }
            $this->email->send();
      
        $emailParams["sent_date"] = date("Y-m-d H:m:s");
        $this->insertEmailLog($emailParams);
        $this->email->clear(true);
        //
        $this->email->to("financecontrol.peponitravel@gmail.com");
        $this->email->from("no-reply@peponitravel.com", "Peponitravel");
        $this->email->subject($adminEmailTitle);
        $this->email->message($customerEmailView);
        if ($filePdf != null) {
            $attachResult=$this->email->attach($filePdf, 'attachment', $fileName);
        }
        $this->email->send();
        $this->email->clear(true);

        $this->email->to("denny1@mhs.stts.edu");
        $this->email->from("no-reply@peponitravel.com", "Peponitravel");
        $this->email->subject($adminEmailTitle);
        $this->email->message($customerEmailView);
        if ($filePdf != null) {
            $attachResult=$this->email->attach($filePdf, 'attachment', $fileName);
        }
        $this->email->send();
        
        $this->email->clear(true);

        //
        // $this->email->to("darianchristiandinata@gmail.com");
        // $this->email->from("no-reply@peponitravel.com", "Peponitravel");
        // $this->email->subject($adminEmailTitle);
        // $this->email->message($customerEmailView);
        // if ($filePdf != null) {
        //     $attachResult=$this->email->attach($filePdf, 'attachment', $fileName);
        // }
        // $this->email->send();
    }

    public function email_report($title, $message)
    {
        $mail_list = ["financecontrol.peponitravel@gmail.com", "williamgunawan22@gmail.com","darianchristiandinata@gmail.com","williamtrv1@gmail.com"];
        foreach ($mail_list as $email) {
            $this->email->to($email);
            $this->email->from("no-reply@peponitravel.com", "Peponitravel");
            $this->email->subject($title);
            $this->email->message($message);
            $this->email->send();

            $this->email->clear(true);
        }
    }

    public function insertEmailLog($params)
    {
        $res = $this->db->insert('email_sent_log', $params);
        return $this->db->insert_id();
    }

    public function getEmailLog($params)
    {
        if (isset($params["invoice_id"])) {
            $this->db->where("invoice_id", $params["invoice_id"]);
        }
        if (isset($params["email_type"])) {
            $this->db->where("email_type", $params["email_type"]);
        }
        $res = $this->db->get("email_sent_log")->result();
        return $res;
    }
    public function getTripPeriod($params= array(), $id=null)
    {   
        if ($id != null) {
            $this->db->select("TP.*, P.product_name, Pv.name private_name");
            $this->db->where("TP.product_id", $id);
            $this->db->where("TP.is_private_trip", $params["is_private_trip"]);
            $this->db->where("TP.order_date_start <=",  date($params["order_date"]));
            $this->db->where("TP.order_date_end >=", date($params["order_date"]));
            $this->db->join("product P", "P.product_id = TP.product_id", "left");
            $this->db->join("private_trip Pv", "Pv.id = TP.product_id", "left");
            $this->db->limit("1");
            $res = $this->db->get('trip_period TP')->result();
        // echo $this->db->last_query();
        } else {
            $this->db->select("TP.*, P.product_name");
            $this->db->join("product P", "P.product_id = TP.product_id");
            $res = $this->db->get('trip_period TP')->result();
        }
        return $res;
    }

    public function getPrivateTripPeriod($params=array(), $id=null){
        if ($id != null) {
            $this->db->select("TP.*, P.product_name, Pv.name private_name");
            $this->db->where("TP.private_id", $id);
            $this->db->where("TP.product_id", 0);
            $this->db->where("TP.is_private_trip", $params["is_private_trip"]);
            $this->db->where("TP.order_date_start <=", $params["order_date"]);
            $this->db->where("TP.order_date_end >=", $params["order_date"]);
            $this->db->join("product P", "P.product_id = TP.product_id", "left");
            $this->db->join("private_trip Pv", "Pv.id = TP.product_id", "left");
            $this->db->limit("1");
            $this->db->order_by("TP.order_date_end DESC");
            $res = $this->db->get('trip_period TP')->row();
        // echo $this->db->last_query();
        } else {
            $this->db->select("TP.*, P.name");
            $this->db->where("TP.product_id", 0);
            $this->db->join("private_trip P", "P.id = TP.private_id");
            $res = $this->db->get('trip_period TP')->result();
        }
        return $res;
    }

    public function updateTripPeriod($params, $id=null)
    {
        if ($id != null) {
            $this->db->where("id", $id);
            $res = $this->db->update('trip_period', $params);
            return $this->db->affected_rows();
        } else {
            $res = $this->db->insert('trip_period', $params);
            return $this->db->insert_id();
        }
    }

    public function deleteTripPeriod($id=null)
    {
        if ($id != null) {
            $this->db->where("id", $id);
            $this->db->delete("trip_period");
            return $this->db->affected_rows();
        }
    }

    public function getPrivateTrip($params=array(), $id=null)
    {
        if ($id != null) {
            $this->db->select("PT.id, P.package_name, PT.name, PT.description, CONCAT(\"[\",GROUP_CONCAT(DISTINCT CONCAT(\"[\",CONCAT('\"',AG.age_group_name,'\",'),CONCAT('\"',PP.age_group_id,'\",'),CONCAT('\"',PP.product_price,'\"'),\"]\")),\"]\") age_price, PT.start_date, PT.end_date, PT.created_date, PT.updated_date", false);
            $this->db->join("package P", "P.package_id = PT.package_id");
            $this->db->join("product_price PP", "PP.private_id = PT.id", 'LEFT');
            $this->db->join("age_group AG", "PP.age_group_id = AG.age_group_id", 'LEFT');
            $this->db->group_by("P.package_name, PT.name, PT.description, PT.start_date, PT.end_date, PT.created_date, PT.updated_date");
            $this->db->where("PT.id", $id);
            $res = $this->db->get('private_trip PT')->row();
        } else {
            $this->db->select("TP.id as period_id, TP.order_date_start, TP.order_date_end, TP.down_payment, TP.period_json, TP.is_private_trip, TP.created_date, TP.updated_date,
            PT.id, P.package_id, P.package_name, PT.name, PT.description, CONCAT(\"[\",GROUP_CONCAT(DISTINCT CONCAT(\"[\",CONCAT('\"',AG.age_group_name,'\",'),CONCAT('\"',PP.age_group_id,'\",'),CONCAT('\"',PP.product_price,'\"'),\"]\")),\"]\") age_price, PT.start_date, PT.end_date, PT.created_date, PT.updated_date", false);
            $this->db->join("package P", "P.package_id = PT.package_id");
            $this->db->join("trip_period TP", "PT.id = TP.private_id", 'LEFT');
            $this->db->join("product_price PP", "PP.private_id = PT.id", 'LEFT');
            $this->db->join("age_group AG", "PP.age_group_id = AG.age_group_id", 'LEFT');
            $this->db->group_by("P.package_name, PT.name, PT.description, PT.start_date, PT.end_date, PT.created_date, PT.updated_date");
            $this->db->order_by('PT.id', 'DESC');
            $res = $this->db->get('private_trip PT')->result();
        }
        return $res;
    }

    public function updatePrivateTrip($params, $id=null)
    {
        if ($id != null) {
            $this->db->where("id", $id);
            $age = $params['age'];
            unset($params['age']);
            $res = $this->db->update('private_trip', $params);
            $affected_row = $this->db->affected_rows();
            $private_trip_id = $id;
            if ($private_trip_id !== null) {
                foreach ($age as $age_group_id => $product_price) {
                    $this->db->where("age_group_id", $age_group_id);
                    $this->db->where("private_id", $private_trip_id);
                    $product_price = array("product_price"  => $product_price);
                    $this->db->update('product_price', $product_price);
                }
            }
            return $affected_row;
        } else {
            $age = $params['age'];
            unset($params['age']);
            $res = $this->db->insert('private_trip', $params);
            $private_trip_id = $this->db->insert_id();
            if ($private_trip_id !== null) {
                foreach ($age as $age_group_id => $product_price) {
                    $product_price = array("private_id"     => $private_trip_id,
                                           "age_group_id"   => $age_group_id,
                                           "product_price"  => $product_price);
                    $this->db->insert('product_price', $product_price);
                }
            }
            return $private_trip_id;
        }
    }

    public function deletePrivateTrip($id=null)
    {
        if ($id != null) {
            $this->db->where("id", $id);
            $this->db->delete("private_trip");
            return $this->db->affected_rows();
        }
    }

    public function getProduct($params= array(), $id=null)
    {
        $this->db->order_by("product_registration_date DESC");
        $this->db->where("product_deactivated_at", null);
        $res = $this->db->get("product")->result();
        return $res;
    }

    public function getTrip($params= array(), $id=null)
    {
        $this->db->select("Pr.product_id, Pr.product_name, Pr.product_price, Pr.product_duration, Pr.product_airlines, Pkg.package_name");
        $this->db->where("Pr.product_deactivated_at", null);
        $this->db->join("package Pkg", "Pkg.package_id = Pr.package_id");
        $this->db->join("order Or", "Pr.product_id = Or.product_id", "left");
        $this->db->group_by("Pr.product_id, Pr.product_name, Pr.product_price, Pr.product_duration, Pr.product_airlines, Pkg.package_name");
        $this->db->order_by("Pr.product_registration_date DESC");
        $res = $this->db->get("product Pr")->result();
        foreach ($res as $key => $r) {
            $r->participant = count($this->getParticipantInformation($r->product_id));
        }
        // $this->db->join("product Pr", "Pr.product_id = Or.product_id");
        // $this->db->join("payment_method Py", "Py.payment_method_id = Or.payment_method_id");
        // $this->db->group_by("Or.product_id, Or.order_name, Or.order_phone, Or.order_line_id, Or.order_email, Or.order_start_date, Or.order_end_date, Py.payment_method_name, Or.order_note");
        // $res = $this->db->get('order Or')->result();

        return $res;
    }

    public function getParticipantInformation($product_id)
    {
        $this->db->select("O.order_id, O.order_name, O.order_phone, O.order_line_id, O.order_email, O.order_start_date, O.order_end_date, O.order_price, O.order_name, V.voucher_code,O.order_leader, Sum(Case When In.status = 1
         Then In.total Else 0 End) total_payment, Sum(In.total) total_invoice");
        $this->db->where("product_id", $product_id);
        $this->db->join("invoices In", "In.order_id = O.order_id", "left");
        $this->db->join("voucher V", "O.voucher_id = V.voucher_id", "left");
        $this->db->group_by("O.order_name, O.order_phone, O.order_line_id, O.order_email, O.order_start_date, O.order_end_date, O.order_price, O.order_name, V.voucher_code");
        $res = $this->db->get("order O")->result();
        return $res;
    }

    public function getParticipantInvoices($order_id)
    {
        $this->db->select("In.*");
        $this->db->where("O.order_id", $order_id);
        $this->db->join("invoices In", "O.order_id = In.order_id");
        $res = $this->db->get("order O")->result();
        return $res;
    }
    public function delete_Participant($order_id)
    {
        
        $this->db->where("order_id", intval($order_id));
        $res = $this->db->delete("order");
        $this->db->where("order_id", intval($order_id));
        $res = $this->db->where('status','0')->delete("invoices");
        return $this->db->affected_rows();
    }

    public function getPackage($params= array(), $id=null)
    {
        $res = $this->db->get("package")->result();
        return $res;
    }

    public function getOrder($orderId = null)
    {
        $this->db->join('order_type', 'order_type.order_type_id = order.order_type_id');
        $this->db->join('payment_method', 'payment_method.payment_method_id = order.payment_method_id', 'left');
        $this->db->join('product', 'product.product_id = order.product_id', "left");
        $this->db->join('private_trip', 'order.private_id = private_trip.id', "left");
        $this->db->join('package', 'package.package_id = private_trip.package_id OR package.package_id = product.package_id', "left");
        $this->db->join('voucher', 'voucher.voucher_id = order.voucher_id', 'left');

        if ($orderId) {
            $this->db->where('order.order_id', $orderId);
        }

        $this->db->order_by('order.created_at', 'desc');
        $this->db->select('order.*, product.product_name as product_name, product.product_duration trip_schedule, payment_method.payment_method_name, voucher.voucher_code, private_trip.name as private_name, package.package_name as package_name, order_type.order_type_name, order.created_at as created_at');

        $query = $this->db->get("order");

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

    public function updateOrderDetail($params, $id=null)
    {
        if ($id != null) {
            $this->db->where("order_detail_id", $id);
            $res = $this->db->update('order_detail', $params);
            return $this->db->affected_rows();
        } else {
            $res = $this->db->insert('order_detail', $params);
            return $this->db->insert_id();
        }
    }

    public function insertOrder($orderData, $orderDetailData)
    {
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

        return $orderId;
    }


    public function updateOrder($params, $id=null)
    {
        if ($id != null) {
            $this->db->where("order_id", $id);
            $res = $this->db->update('order', $params);
            return $this->db->affected_rows();
        } else {
            $res = $this->db->insert('order', $params);
            $order_id = $this->db->insert_id();
            return $order_id;
        }
    }

    public function deleteOrder($id=null)
    {
        if ($id != null) {
            $this->db->where("order_id", $id);
            $this->db->delete("order");
            return $this->db->affected_rows();
        }
    }

    public function getAgeGroup()
    {
        $res = $this->db->get('age_group')->result();
        return $res;
    }
    public function detail_Participant($id)
    {
        $this->db->query('select * from order_detail')->result();
    }
    public function getProductPrice($id, $private_id=null)
    {
        $this->db->select("age_group_id, product_price");
        if ($id) {
            $this->db->where("product_id", $id);
        }
        if ($private_id) {
            $this->db->where("private_id", $private_id);
        }
        
        $res = $this->db->get("product_price")->result();
        return $res;
    }

    public function getPaymentMethod()
    {
        $res = $this->db->get('payment_method')->result();
        return $res;
    }

    public function getVoucher($params)
    {
        if (isset($params["voucher_id"])) {
            $this->db->where("voucher_id", $params["voucher_id"]);
            return $this->db->get("voucher")->row();
        }
    }

    public function validateAccess($page, $isAjax = false)
    {
        $restrict = [ "editor.peponi" => [ 
                                    "order" => true,
                                    "trip" => true,
                                    "invoice" => true,
                                    "privatetrip" => true,
                                    "period" => true,
                                    "admin" => true,
                                    "voucher" => true,
                                    // "age" => true
                                   ]];

        $allow = [ "finance.peponi" => [   "order" => true,
                                    "trip" => true,
                                    "invoice" => true,
                                    "privatetrip" => true,
                                    "period" => true,
                                    "add_payment" => true
                                ],
                   "editor" => [],
                   "peponi" => []];

        $username = $this->session->userdata("admin_name");
        $raw_page = explode("_", $page);

        if (!empty($restrict[$username]) && (!empty($restrict[$username][$page]) || !empty($restrict[$username][$raw_page[0]]))) {
            $permission = false;
        } elseif (!empty($restrict[$username]) ) {
            $permission = true;
        }
        if (isset($allow[$username]) && (count($allow[$username]) == 0 || !empty($allow[$username][$page]) || !empty($allow[$username][$raw_page[0]]) ) ) {
            $permission = true;
        } elseif (!empty($allow[$username])) {
            $permission= false;
        }

        if ( $permission == false ) {
            if (!$isAjax) {
                $data['content_managements'] = $this->ci->config->item('content_management');
                $data['admin_route']         = $this->ci->config->item('admin_dir_controller');
                $data['title']               = "v2/Order_Management";
                echo $this->ci->load->view("admin/layouts/v2/header", $data, true);
                echo $this->ci->load->view("admin/Admin_Management/v2/blank", null, true);
                echo $this->ci->load->view("admin/layouts/v2/footer", null, true);
                die();
            } else {
                $data = array(  "title"     => "Access Limited",
                                "message"   => "You have no access!",
                                "status"    => "0",
                            );
                die(json_encode($data));
            }
        }        
    }

    function currency($number) {
        $formatted = number_format($number,0);
        $withRp = 'Rp '.$formatted;
        return $withRp;
    }
    public function getOrderID($id)
    {
       $this->db->where('id',$id);
       $data = $this->db->get("invoices")->result();
       return $data[0];
    }


    public function Add_Payment($id)
    {
        
        $res = $this->updateInvoices(array("status"=>1,"due_date"=>date("Y-m-d H:i:s")), $id);
        
        if ($res > 0) {
            
            $data = array(  "title"     => "Add Payment Success",
                            "message"   => "Updated " .$res . " row",
                            "status"    => "1",
                        );
            $invoiceData = $this->getInvoices(array("invoice_id"=>$id));
           // $invoiceData = $invoiceData[0];
            if ($invoiceData->invoice_type == "down payment") {
                $this->generateInvoice($invoiceData->order_id, true);
            }
            $od = $this->getOrder($invoiceData->order_id);
            $invoiceData->order_name = $od->order_name;
            $this->generateReceipt((object)$invoiceData);
            $nextInvoice = $this->getInvoices([
                    "order_id"  => $invoiceData->order_id,
                    "status"    => "0",
                    "order_by"  => "In.due_date ASC",
                    "limit"     => "1"
                ]);
            
            if (isset($nextInvoice->id)) {
                $invoice = $nextInvoice;
                $invoice->invoice_header ="INVOICE";
                $invoice->order_name = $od->order_name;
                $filePdf = $this->pdf->generate_pdf($invoice);
                $this->send_email($invoice->order_id, $invoice->id,base_url()."pembayaran/".$invoice->id, "invoice generate", $filePdf, "Invoice $invoice->id - " . $invoice->title . ".pdf");
            }
        } else {
            $data = array(  "title"     => "Add Payment Fail",
                            "message"   => "Please try again later. If problem still happen in a few minute, please contact your developer.",
                            "status"    => "0",
                        );
        }
        die(json_encode($data));
    }


    //
}
