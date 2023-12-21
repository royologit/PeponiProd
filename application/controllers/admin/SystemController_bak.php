<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SystemController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        // echo $this->session->userdata("admin_id");
        // echo "<br>" .$this->session->userdata("admin_name");
        $this->load->model($this->config->item('admin_dir_model').'admin', 'admin');
        $this->load->model(array('admin','system2'));
        $this->load->library('pdf');
        $this->load->library('email');
        $this->load->helper(array('url','form'));
        if ($this->uri->segment(3) != "cron_job_peponi") {
            $this->custom->session_validation('admin_id');
            $this->custom->session_validation('admin_name');
        }
    }

    public function myexcel()
    {
        $this->load->library('phpexcel');
        // Create new PHPExcel object
        // echo date('H:i:s') , " Create new PHPExcel object" , EOL;
        //	Change these values to select the Rendering library that you wish to use
        //		and its directory location on your server
        // $rendererName = PHPExcel_Settings::PDF_RENDERER_MPDF;
        // $rendererLibrary = 'mpdf_2/';
        // $rendererName = PHPExcel_Settings::PDF_RENDERER_TCPDF;
        // $rendererLibrary = 'tcpdf';
        $rendererName = PHPExcel_Settings::PDF_RENDERER_DOMPDF;
        $rendererLibrary = 'dompdf';

        $rendererLibraryPath = FCPATH.'/application/libraries/' . $rendererLibrary;

        // Load Excel
        $objPHPExcel = PHPExcel_IOFactory::load(FCPATH . "asset/excel/template.xlsx");
        // $objPHPExcel = PHPExcel_IOFactory::load(FCPATH . "asset/excel/new_data.xlsx");

        // var_dump($objPHPExcel);
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);

        if (!PHPExcel_Settings::setPdfRenderer(
                $rendererName,
                $rendererLibraryPath
            )) {
            die(
                'NOTICE: Please set the $rendererName and $rendererLibraryPath values' .
                '<br />' .
                'at the top of this script as appropriate for your directory structure'
            );
        }

        // Redirect output to a client’s web browser (PDF)
        try {
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'PDF');
        } catch (Exception $err) {
            echo "<pre>" . var_export($err, true) . "</pre>";
        }
        // header('Content-Type: application/pdf');
        // header('Content-Disposition: attachment;filename="peponi.pdf"');
        // header('Cache-Control: max-age=0');

        // $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        // header('Content-Type: application/Excel2007');
        // header('Content-Disposition: attachment;filename="peponi.xlsx"');
        // header('Cache-Control: max-age=0');
        try {
            $objWriter->save('php://output');
        } catch (Exception $err) {
            echo "<pre>" . var_export($err, true) . "</pre>";
        }
        exit;
    }

    public function dompdf()
    {
        require_once(FCPATH.'/application/libraries/dompdf/autoload.inc.php');
        $dompdf = new Dompdf();
    }

    public function generate()
    {
        $invoiceData = $this->system2->generateInvoice("6", false);
        // WL_DUMP($invoiceData);
        // $this->pdf->generate_pdf((object) $invoiceData);
    }

    public function generate_discount()
    {
        $invoiceData = $this->system2->generateInvoice("13");
        $invoiceData["discount"] = 300000;
        // WL_DUMP($invoiceData);
        $this->pdf->generate_pdf((object) $invoiceData);
    }

    public function cron_job_peponi()
    {
        $pattern = [    "-7"    => "invoice generate",
                        "-3"    => "friendly reminder",
                        "0"     => "due date",
                        "3"     => "first reminder",
                        "7"     => "second reminder",
                        "10"    => "last reminder"];
        $params = [ "status" => 0 ];
        $invoiceData = $this->system2->getInvoices($params);
        foreach ($invoiceData as $invoice) {
            $days = GET_DIFF_DAYS($invoice->due_date);
            foreach ($pattern as $remind_days => $email_type) {
                if ($days == intval($remind_days)) {
                    echo "Invoice no $invoice->id, Days $days Remind Days $remind_days <br>";
                    $invoice->invoice_header = "INVOICE";
                    $filePdf = $this->pdf->generate_pdf($invoice);
                    $this->system2->send_email($invoice->order_id, $invoice->id, $email_type, $filePdf, "Invoice $invoice->id - " . $invoice->title . ".pdf");
                } else {
                    echo "Invoice no $invoice->id, Due date $invoice->due_date. Diff Days $days. Pattern $remind_days not match. Skip!<br>";
                }
            }
        }
    }

    public function Download_Invoice()
    {
        $params = $this->input->get();
        $invoiceData = $this->system2->getInvoices(
            array("invoice_id" => $params["invoice_id"],
                                                         "request_order_name" => true)
                                                   );
        $this->load->library('pdf');
        try {
            if($invoiceData->status=="1"){
                $invoiceData->invoice_header = "RECEIPT";
            }
            else{
                $invoiceData->invoice_header = "INVOICE";
            }
            $this->pdf->generate_pdf($invoiceData, true);
        } catch (Exception $err) {
            echo "<pre>" . var_export($err, true) . "</pre>";
        }
        WL_DUMP($invoiceData);
        // $this->pdf->generate_pdf((object) $invoiceData);
    }

    public function open_email()
    {
        $this->load->view("email/v2/open");
    }

    public function private_email()
    {
        $this->load->view("email/v2/private");
    }

    public function mypdf()
    {
        //
        // use Dompdf\Dompdf;
        // // instantiate and use the dompdf class
        // $dompdf = new Dompdf();
        // $dompdf->loadHtml('hello world');
        //
        // // (Optional) Setup the paper size and orientation
        // $dompdf->setPaper('A4', 'landscape');
        //
        // // Render the HTML as PDF
        // $dompdf->render();
        //
        // // Output the generated PDF to Browser
        // $dompdf->stream();
        define("DOMPDF_ENABLE_REMOTE", true);
        $this->load->library('pdf');
        //
        // echo "work!";
        $this->pdf->set_paper(DEFAULT_PDF_PAPER_SIZE, 'A4');
        $this->pdf->load_view('admin/Admin_Management/v2/mypdf');
        $this->pdf->render();
        //
        $output = $this->pdf->output();

        // $this->load->view('admin/Admin_Management/v2/mypdf',array("bg_url" => base_url()));
        $this->pdf->stream("welcome.pdf");

        // file_put_contents("/path/to/file.pdf", $output);
    }

    public function pdf()
    {
        $this->load->library('pdf');
        $invoice_data = array("code"     => "1002",
                         "status"   => "UNPAID",
                         "duedate"  => "22 Maret 2018",
                         "name"     => "Ms. Caroline Christine",
                         "quantity" => "3",
                         "detail"   => "Pembayaran Tahap Kedua Japan Private Land TripPembayaran Tahap Kedua Japan Private Land TripPembayaran Tahap Kedua Japan Private Land Trip\nPembayaran Tahap Kedua Japan Private Land Trip\nPembayaran Tahap Kedua Japan Private Land Trip\nPembayaran Tahap Kedua Japan Private Land Trip",
                         "unit"     => "5,000,000",
                         "total"    => "15,000,000",
                         "tax"      => "0",
                         "net"      => "14,450,000",
                         "discount" => "50,000");
        try {
            $this->pdf->generate_pdf($invoice_data);
        } catch (Exception $err) {
            echo "<pre>" . var_export($err, true) . "</pre>";
        }
    }

    public function Get_Product_Price()
    {
        $params = $this->input->post();
        if (isset($params["type"]) and $params["type"] == "open_trip") {
            die(json_encode($this->system2->getProductPrice($params["id"])));
        } elseif (isset($params["type"]) and $params["type"] == "private_trip") {
            die(json_encode($this->system2->getProductPrice(null, $params["id"])));
        }
        return [];
    }

    public function Order_Management()
    {
        $this->system2->validateAccess("order_list");
        $data['content_managements'] = $this->config->item('content_management');
        $data['admin_route']         = $this->config->item('admin_dir_controller');
        $data['title']               = "v2/Order_Management";
        $orders = $this->system2->getOrder();
        foreach ($orders as $order) {
            $orderDetails = $this->system2->getOrderDetail($order->order_id);

            $orderParticipant = [];
            foreach ($orderDetails as $orderDetail) {
                $orderParticipant[] = $orderDetail->age_group_name . ' : ' . $orderDetail->order_detail_quantity;
            }
            $order->order_detail = json_encode($orderDetails);
            $order->order_participant = implode('<br>', $orderParticipant);

            // $order->order_price = $order->order_price ? currency_format($order->order_price, false) : null;
        }
        $content["private_trip"] = $this->system2->getPrivateTrip();
        $content["products"] = $this->system2->getProduct();
        $content["age_group"] = $this->system2->getAgeGroup();
        $content["payment_method"] = $this->system2->getPaymentMethod();
        $content["package_list"] = $this->system2->getPackage();
        $content["result"] = $orders;
        // echo "<pre>" . var_export($orders, true) . "</pre>";
        // die();
        $this->load->view("admin/layouts/v2/header", $data);
        $this->load->view("admin/Admin_Management/v2/order", $content);
        $this->load->view("admin/layouts/v2/footer");
    }


    public function Create_Order()
    {
        $this->system2->validateAccess("order_create", true);
        $res = 0;
        $params = $this->input->post();
        if ($params["product_type"] == "private_trip") {
            if ($params["private_trip"]["id"] == "custom") {
                $params["private_trip"]["created_date"] = date("Y-m-d H:m:s");
                unset($params["private_trip"]["id"]);
                $params["private_trip"]["id"] = $this->system2->updatePrivateTrip($params["private_trip"]);

                $period_data = array();
                if (is_array($params["period"]["period_json"]["label"])) {
                    foreach ($params["period"]["period_json"]["label"] as $index => $value) {
                        $period_data[] = array("label"   => $value,
                                               "duedate" => $params["period"]["period_json"]["duedate"][$index],
                                               "price"   => $params["period"]["period_json"]["price"][$index]);
                    }
                    $params["period"]["period_json"] = json_encode($period_data);
                }
                $params["period"]["product_id"] = $params["private_trip"]["id"];
                $params["period"]["is_private_trip"] = 1;
                $params["period"]["created_date"] = date("Y-m-d H:m:s");
                $this->system2->updateTripPeriod($params["period"]);
            }
            $params["order"]["private_id"] = $params["private_trip"]["id"];
            $params["order"]["order_type_id"] = 2; // Private Trip
            $params["order"]["created_at"] = date("Y-m-d H:m:s");
            $params["order"]["order_start_date"] = $private_trip->start_date;
            $params["order"]["order_end_date"]   = $private_trip->end_date;
            $res = $order_id = $this->system2->updateOrder($params["order"]);
            $product_price = $this->system2->getProductPrice(null, $params["private_trip"]["id"]);
            if (count($product_price) > 0) {
                foreach ($product_price as $age) {
                    if ($params["age"][$age->age_group_id] != 0) {
                        $ageData = array("order_id" => $order_id,
                                         "age_group_id" => $age->age_group_id,
                                         "order_detail_quantity" => $params["age"][$age->age_group_id],
                                         "order_detail_price" => $age->product_price,
                                         "created_at"  => date("Y-m-d H:m:s")
                                   );
                        $this->system2->updateOrderDetail($ageData);
                    }
                }
            } elseif (is_array($params["age"])) {
                foreach ($params["age"] as $index => $val) {
                    if ($val != 0) {
                        $ageData = array("order_id" => $order_id,
                                         "age_group_id" => $index,
                                         "order_detail_quantity" => $val,
                                         "order_detail_price" => "0",
                                         "created_at"  => date("Y-m-d H:m:s")
                                   );
                        $this->system2->updateOrderDetail($ageData);
                    }
                }
            }
        } else {
            $params["order"]["order_type_id"] = 1; // Open Trip
            $params["order"]["created_at"] = date("Y-m-d H:m:s");
            $res = $order_id = $this->system2->updateOrder($params["order"]);
            $product_price = $this->system2->getProductPrice($params["order"]["product_id"]);
            if (count($product_price) > 0) {
                foreach ($product_price as $age) {
                    if ($params["age"][$age->age_group_id] != 0) {
                        $ageData = array("order_id" => $order_id,
                                         "age_group_id" => $age->age_group_id,
                                         "order_detail_quantity" => $params["age"][$age->age_group_id],
                                         "order_detail_price" => $age->product_price,
                                         "created_at"  => date("Y-m-d H:i:s")
                                   );
                        $this->system2->updateOrderDetail($ageData);
                    }
                }
            } elseif (is_array($params["age"])) {
                foreach ($params["age"] as $index => $val) {
                    if ($val != 0) {
                        $ageData = array("order_id" => $order_id,
                                         "age_group_id" => $index,
                                         "order_detail_quantity" => $val,
                                         "order_detail_price" => "0",
                                         "created_at"  => date("Y-m-d H:i:s")
                                   );
                        $this->system2->updateOrderDetail($ageData);
                    }
                }
            }
        }
        // echo "<pre>" . var_export($params,true) . "</pre>";
        // die();
        // $res = $this->system2->updateOrder($params);

        if ($res > 0) {
            $this->system2->generateInvoice($res);
            $data = array(  "title"     => "Add Order Success",
                            "message"   => "Insert order with id " .$res,
                            "status"    => "1",
                        );
        } else {
            $data = array(  "title"     => "Add Order Fail",
                            "message"   => "Please try again later. If problem still happen in a few minute, please contact your developer.",
                            "status"    => "0",
                        );
        }

        die(json_encode($data));
    }

    public function Update_Order()
    {
        $this->system2->validateAccess("order_update", true);
        $res = 0;
        $params = $this->input->post();
        if ($params["product_type"] == "private_trip") {
            if ($params["private_trip"]["id"] == "custom") {
                $params["private_trip"]["created_date"] = date("Y-m-d H:m:s");
                unset($params["private_trip"]["id"]);
                $params["private_trip"]["id"] = $this->system2->updatePrivateTrip($params["private_trip"]);
                $params["period"]["period_json"] = json_encode($params["period"]["period_json"]);
                $params["period"]["product_id"] = $params["private_trip"]["id"];
                $params["period"]["is_private_trip"] = 1;
                $params["period"]["created_date"] = date("Y-m-d H:m:s");
                $this->system2->updateTripPeriod($params["period"]);
            }
            $params["order"]["private_id"] = $params["private_trip"]["id"];
            $params["order"]["order_type_id"] = 2; // Private Trip
            $params["order"]["created_at"] = date("Y-m-d H:m:s");
            $params["order"]["order_start_date"] = $private_trip->start_date;
            $params["order"]["order_end_date"]   = $private_trip->end_date;
            // echo "<pre>" . var_export($params,true) . "</pre>";
            // die();
            $order_id = $params["order"]["order_id"];
            // unset($params["order"]["order_id"]);
            $res = $this->system2->updateOrder($params["order"], $order_id);
            if (is_array($params["age"])) {
                foreach ($params["age"] as $index => $age_value) {
                    if ($age_value["val"] != 0) {
                        if ($age_value["id"] == "" || $age_value["id"] == null) {
                            $ageData = array("order_id" => $order_id,
                                             "age_group_id" => $index,
                                             "order_detail_quantity" => $age_value["val"],
                                             "order_detail_price" => "0",
                                             "created_at"  => date("Y-m-d H:m:s")
                                       );
                            $this->system2->updateOrderDetail($ageData);
                        } else {
                            $ageData = array("order_detail_quantity" => $age_value["val"],
                                             "updated_at"  => date("Y-m-d H:m:s")
                                            );
                            $this->system2->updateOrderDetail($ageData, $age_value["id"]);
                        }
                    }
                }
            }
        } else {
            $params["order"]["order_type_id"] = 1; // Open Trip
            $params["order"]["created_at"] = date("Y-m-d H:m:s");
            $order_id = $params["order"]["order_id"];
            // unset($params["order"]["order_id"]);
            $res = $this->system2->updateOrder($params["order"], $order_id);
            if (is_array($params["age"])) {
                foreach ($params["age"] as $index => $age_value) {
                    if ($age_value["val"] != 0) {
                        if ($age_value["id"] == "" || $age_value["id"] == null) {
                            $ageData = array("order_id" => $order_id,
                                             "age_group_id" => $index,
                                             "order_detail_quantity" => $age_value["val"],
                                             "order_detail_price" => "0",
                                             "created_at"  => date("Y-m-d H:m:s")
                                       );
                            $this->system2->updateOrderDetail($ageData);
                        } else {
                            $ageData = array("order_detail_quantity" => $age_value["val"],
                                             "updated_at"  => date("Y-m-d H:m:s")
                                            );
                            $this->system2->updateOrderDetail($ageData, $age_value["id"]);
                        }
                    }
                }
            }
        }
        // echo "<pre>" . var_export($params,true) . "</pre>";
        // die();
        // $res = $this->system2->updateOrder($params);
        if ($res > 0) {
            $data = array(  "title"     => "Update Invoice Success",
                            "message"   => "Updated " .$res . " row",
                            "status"    => "1",
                        );
        } else {
            $data = array(  "title"     => "Update Invoice Fail",
                            "message"   => "Please try again later. If problem still happen in a few minute, please contact your developer.",
                            "status"    => "0",
                        );
        }

        die(json_encode($data));
    }

    public function Delete_Order()
    {
        $this->system2->validateAccess("order_delete", true);
        $order_id = $this->input->post("order_id");
        $res = $this->system2->deleteOrder($order_id);

        if ($res > 0) {
            $this->system2->deleteInvoices(null, $order_id);
            $data = array(  "title"     => "Remove Order Success",
                            "message"   => "Removed " .$res . " row",
                            "status"    => "1",
                        );
        } else {
            $data = array(  "title"     => "Remove Order Fail",
                            "message"   => "Please try again later. If problem still happen in a few minute, please contact your developer.",
                            "status"    => "0",
                        );
        }

        die(json_encode($data));
    }

    public function Invoice_Management()
    {
        $this->system2->validateAccess("invoice_list");
        $data['content_managements'] = $this->config->item('content_management');
        $data['admin_route']         = $this->config->item('admin_dir_controller');
        $data['title']               = "v2/Invoice_Management";
        // echo "<pre>" . var_export($this->system2->getInvoices(), true) . "</pre>";
        $params = [];
        if ($this->input->get("invoice_id")) {
            $params["ref_id"] = $this->input->get("invoice_id");
        }
        $content["result"] = $this->system2->getInvoices($params);
        $content["order_list"] = $this->system2->getOrder();
        $this->load->view("admin/layouts/v2/header", $data);
        $this->load->view("admin/Admin_Management/v2/invoice", $content);
        $this->load->view("admin/layouts/v2/footer");
    }

    public function Invoice_Create()
    {
        $this->system2->validateAccess("invoice_create", true);
        $params = $this->input->post();
        $params["created_date"] = date("Y-m-d H:m:s");
        $res = $this->system2->updateInvoices($params);
        if ($res > 0) {
            $data = array(  "title"     => "Add Invoice Success",
                            "message"   => "Insert invoice with id " .$res,
                            "status"    => "1"
                        );
        } else {
            $data = array(  "title"     => "Insert Invoice Fail",
                            "message"   => "Please try again later. If problem still happen in a few minute, please contact your developer.",
                            "status"    => "0",
                        );
        }

        die(json_encode($data));
    }

    public function Update_Invoice()
    {
        $this->system2->validateAccess("invoice_update", true);
        $params = $this->input->post();
        $invoice_id = $params["id"];
        unset($params["id"]);
        $params["updated_date"] = date("Y-m-d H:m:s");
        $res = $this->system2->updateInvoices($params, $invoice_id);
        if ($res > 0) {
            $data = array(  "title"     => "Update Invoice Success",
                            "message"   => "Updated " .$res . " row",
                            "status"    => "1",
                        );
        } else {
            $data = array(  "title"     => "Update Invoice Fail",
                            "message"   => "Please try again later. If problem still happen in a few minute, please contact your developer.",
                            "status"    => "0",
                        );
        }

        die(json_encode($data));
    }

    public function Delete_Invoice()
    {
        $this->system2->validateAccess("invoice_delete", true);
        $invoice_id = $this->input->post("invoice_id");
        $res = $this->system2->deleteInvoices($invoice_id);
        if ($res > 0) {
            $data = array(  "title"     => "Remove Invoice Success",
                            "message"   => "Removed " .$res . " row",
                            "status"    => "1"
                        );
        } else {
            $data = array(  "title"     => "Remove Invoice Fail",
                            "message"   => "Please try again later. If problem still happen in a few minute, please contact your developer.",
                            "status"    => "0"
                        );
        }
        die(json_encode($data));
    }

    public function Private_Trip_Management()
    {
        $this->system2->validateAccess("privatetrip_list");
        // echo "<pre>" . var_export($res, true) . "</pre>";
        $data['content_managements'] = $this->config->item('content_management');
        $data['admin_route']         = $this->config->item('admin_dir_controller');
        $data['title']               = "v2/Private_Trip_Management";
        // echo "<pre>" . var_export($this->system2->getInvoices(), true) . "</pre>";
        $content["result"] = $this->system2->getPrivateTrip();
        $content["package_list"] = $this->system2->getPackage();
        $content["name"] = "Private Trip";
        $content["age_groups"] = $this->system2->getAgeGroup();
        $this->load->view("admin/layouts/v2/header", $data);
        $this->load->view("admin/Admin_Management/v2/private_trip", $content);
        $this->load->view("admin/layouts/v2/footer");
    }

    public function Create_Private_Trip()
    {
        $this->system2->validateAccess("privatetrip_create", true);
        $params = $this->input->post();
        $params["created_date"] = date("Y-m-d H:m:s");
        $res = $this->system2->updatePrivateTrip($params);
        if ($res > 0) {
            $data = array(  "title"     => "Add Private Trip Success",
                            "message"   => "Insert private trip with id " .$res,
                            "status"    => "1",
                        );
        } else {
            $data = array(  "title"     => "Insert Private Trip Fail",
                            "message"   => "Please try again later. If problem still happen in a few minute, please contact your developer.",
                            "status"    => "0",
                        );
        }

        die(json_encode($data));
    }

    public function Update_Private_Trip()
    {
        $this->system2->validateAccess("privatetrip_update", true);
        $params = $this->input->post();
        $private_trip_id = $params["id"];
        unset($params["id"]);
        $params["updated_date"] = date("Y-m-d H:m:s");
        $res = $this->system2->updatePrivateTrip($params, $private_trip_id);
        if ($res > 0) {
            $data = array(  "title"     => "Update Private Trip Success",
                            "message"   => "Updated " .$res . " row",
                            "status"    => "1",
                        );
        } else {
            $data = array(  "title"     => "Update Private Trip Fail",
                            "message"   => "Please try again later. If problem still happen in a few minute, please contact your developer.",
                            "status"    => "0",
                        );
        }

        die(json_encode($data));
    }

    public function Delete_Private_Trip()
    {
        $this->system2->validateAccess("privatetrip_delete", true);
        $private_trip_id = $this->input->post("private_trip_id");
        $res = $this->system2->deletePrivateTrip($private_trip_id);
        if ($res > 0) {
            $data = array(  "title"     => "Remove Private Trip Success",
                            "message"   => "Removed " .$res . " row",
                            "status"    => "1",
                        );
        } else {
            $data = array(  "title"     => "Remove Private Trip Fail",
                            "message"   => "Please try again later. If problem still happen in a few minute, please contact your developer.",
                            "status"    => "0",
                        );
        }

        die(json_encode($data));
    }

    public function Period_Management()
    {
        $this->system2->validateAccess("period_list");
        $data['content_managements'] = $this->config->item('content_management');
        $data['admin_route']         = $this->config->item('admin_dir_controller');
        $data['title']               = "v2/Period_Management";
        // echo "<pre>" . var_export($this->system2->getInvoices(), true) . "</pre>";
        $content["result"]     = $this->system2->getTripPeriod();
        $content["name"]       = "Period";
        $content["product_list"] = $this->system2->getProduct();
        $this->load->view("admin/layouts/v2/header", $data);
        $this->load->view("admin/Admin_Management/v2/period", $content);
        $this->load->view("admin/layouts/v2/footer");
    }

    public function Create_Period()
    {
        $this->system2->validateAccess("period_create", true);
        $params = $this->input->post();
        $params["created_date"] = date("Y-m-d H:m:s");
        $period_data = array();
        if (is_array($params["label"])) {
            foreach ($params["label"] as $index => $value) {
                $period_data[] = array("label"   => $value,
                                       "duedate" => $params["duedate"][$index],
                                       "price"   => $params["price"][$index]);
            }
            $params["period_json"] = json_encode($period_data);
        }
        unset($params["label"]);
        unset($params["duedate"]);
        unset($params["price"]);
        $res = $this->system2->updateTripPeriod($params);
        if ($res > 0) {
            $data = array(  "title"     => "Add Trip Period Success",
                            "message"   => "Insert period with id " .$res,
                            "status"    => "1",
                        );
        } else {
            $data = array(  "title"     => "Insert Trip Period Fail",
                            "message"   => "Please try again later. If problem still happen in a few minute, please contact your developer.",
                            "status"    => "0",
                        );
        }

        die(json_encode($data));
    }

    public function Update_Period($period_id)
    {
        $this->system2->validateAccess("period_update", true);
        $params = $this->input->post();
        $period_id = $params["id"];
        unset($params["id"]);
        $params["updated_date"] = date("Y-m-d H:m:s");
        if (is_array($params["label"])) {
            foreach ($params["label"] as $index => $value) {
                $period_data[] = array("label"   => $value,
                                       "duedate" => $params["duedate"][$index],
                                       "price"   => $params["price"][$index]);
            }
            $params["period_json"] = json_encode($period_data);
        }
        unset($params["label"]);
        unset($params["duedate"]);
        unset($params["price"]);
        $res = $this->system2->updateTripPeriod($params, $period_id);
        if ($res > 0) {
            $data = array(  "title"     => "Update Trip Period Success",
                            "message"   => "Updated " .$res . " row",
                            "status"    => "1"
                        );
        } else {
            $data = array(  "title"     => "Update Trip Period Fail",
                            "message"   => "Please try again later. If problem still happen in a few minute, please contact your developer.",
                            "status"    => "0"
                        );
        }

        die(json_encode($data));
    }

    public function Delete_Period()
    {
        $this->system2->validateAccess("period_delete", true);
        $period_id = $this->input->post("period_id");
        $res = $this->system2->deleteTripPeriod($period_id);
        if ($res > 0) {
            $data = array(  "title"     => "Remove Trip Period Success",
                            "message"   => "Removed " .$res . " row",
                            "status"    => "1",
                        );
        } else {
            $data = array(  "title"     => "Remove Trip Period Fail",
                            "message"   => "Please try again later. If problem still happen in a few minute, please contact your developer.",
                            "status"    => "0",
                        );
        }

        die(json_encode($data));
    }


    public function Trip_Management()
    {
        $this->system2->validateAccess("trip_list");
        $data['content_managements'] = $this->config->item('content_management');
        $data['admin_route']         = $this->config->item('admin_dir_controller');
        $data['title']               = "v2/Trip_Management";
        // echo "<pre>" . var_export($this->system2->getInvoices(), true) . "</pre>";
        $content["result"] = $this->system2->getTrip();
        $content["name"] = "Trip";
        $this->load->view("admin/layouts/v2/header", $data);
        $this->load->view("admin/Admin_Management/v2/trip", $content);
        $this->load->view("admin/layouts/v2/footer");
    }

    public function Trip_Participant()
    {
        $res = $this->system2->getParticipantInformation($this->input->post("product_id"));
        die(json_encode($res));
    }

    public function Participant_Invoice()
    {
        $res = $this->system2->getParticipantInvoices($this->input->post("order_id"));
        die(json_encode($res));
    }

    public function Add_Payment()
    {
        
        $this->system2->validateAccess("add_payment", true);
        $res = $this->system2->updateInvoices(array("status"=>1), $this->input->post("invoice_id"));
        if ($res > 0) {
            $data = array(  "title"     => "Add Payment Success",
                            "message"   => "Updated " .$res . " row",
                            "status"    => "1",
                        );
            $invoiceData = $this->system2->getInvoices(array("invoice_id"=>$this->input->post("invoice_id"),"request_order_name"=>true));
            if ($invoiceData->invoice_type == "down payment") {
                $this->system2->generateInvoice($invoiceData->order_id, true);
            }
            $this->system2->generateReceipt($invoiceData);
            $nextInvoice = $this->system2->getInvoices([
                    "order_id"  => $invoiceData->order_id,
                    "status"    => "0",
                    "order_by"  => "In.due_date ASC",
                    "limit"     => "1"
                ]);
            error_log("Count unpaid invoice ".count($nextInvoice) . ". order id $invoiceData->order_id");
            error_log("unpaid invoice data " . json_encode($nextInvoice));
            if (isset($nextInvoice->id)) {
                $invoice = $nextInvoice;
                error_log("invoice id $invoice->id");
                $invoice->invoice_header = "INVOICE";
                $filePdf = $this->pdf->generate_pdf($invoice);
                $this->system2->send_email($invoice->order_id, $invoice->id, "invoice generate", $filePdf, "Invoice $invoice->id - " . $invoice->title . ".pdf");
            }
        } else {
            $data = array(  "title"     => "Add Payment Fail",
                            "message"   => "Please try again later. If problem still happen in a few minute, please contact your developer.",
                            "status"    => "0",
                        );
        }
        die(json_encode($data));
    }
}
