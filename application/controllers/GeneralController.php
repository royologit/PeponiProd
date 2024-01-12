<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class GeneralController extends CI_Controller {

	function __construct()
 	{
 		 parent::__construct();
		 $this->load->model($this->config->item('admin_dir_model').'admin','admin');
		 $this->load->model('System2','system');
		 $this->load->database();
		 $this->load->library('pdf');
		 $this->load->library('email');
		 $this->load->model('Payment_method_model', 'paymentMethod');
 	}

	function index()
	{
			//Fetch Data
			$data = array(
				"carousels" 					=> $this->admin->select('carousel'), 		//list slider
				"packages"						=> $this->admin->select('package'),//list paket icon
				"products"						=> $this->admin->select('product','','','','',array ('product'."_id" => "desc")),			//list produk2nya
				"abouts"							=> $this->admin->select('about'),				//list abouts
				"experiences"					=> $this->admin->select('experience','','','','',array ('experience'."_id" => "desc")),	//list experience
				"contacts"						=> $this->admin->select('contact'),			//list contact
				"socmeds"							=> $this->admin->select('media'),				//list socmed
				"footer"							=> $this->admin->select('footer')
			);

      $this->load->view('index',$data);
	}

	function filter($filter)
	{

			//Condition Product
			$condition = array(
					"package_id"					=> $filter,
			);

			//Fetch Data
			$data = array(
				"filter_title"				=> $this->admin->select('package','',$condition),
				"carousels" 					=> $this->admin->select('carousel'), 		//list slider
				"packages"						=> $this->admin->select('package','','','',array ('package'."_id" => "desc")),  		//list paket icon
				"products"						=> $this->admin->select('product','',$condition,'','',array ('product'."_id" => "desc")),//list produk2nya
				"abouts"							=> $this->admin->select('about'),				//list abouts
				"experiences"					=> $this->admin->select('experience'),	//list experience
				"contacts"						=> $this->admin->select('contact'),			//list contact
				"socmeds"							=> $this->admin->select('media'),				//list socmed
				"footer"							=> $this->admin->select('footer')
			);

      $this->load->view('filter',$data);
	}
	function package($productByID = 1)
	{
	    $this->load->model('Order_type_model', 'orderType');
		//Product Join Package Table
		$product_join							= array(
			"package"								=> array(
				"package_id"					=> 'left join',
			)
		);

		//Condition Product
		$condition = array(
				"product_id"					=> $productByID,
		);

        $product = $this->admin->select('product',$product_join,$condition);

        $productEntity = $product->row();

        $orderTypes = [];
        if ($productEntity) {
            $orderTypes = $this->orderType->getOrderTypeFromProductOrderType($productEntity->product_order_type);
        }

		//Fetch Data
		$data = array(
			"products"							=> $product,
																 //list product
			"product_images"				=> $this->admin->select('tr_product_image','',$condition),
																 //list product images
            "order_types"                   => $orderTypes,
			"contacts"						=> $this->admin->select('contact'),			//list contact
			"socmeds"							=> $this->admin->select('media'),				//list socmed
			"footer"							=> $this->admin->select('footer')
		);

		$this->load->view('details',$data);
	}

	function packageSearch(){
		$id = $_POST['id'];
		redirect('package/'.$id);
	}

	function tnc() {
        //Fetch Data
        $data = array(
            "tnc"                           => $this->admin->select('tnc')->row(),
            "contacts"						=> $this->admin->select('contact'),			//list contact
            "socmeds"							=> $this->admin->select('media'),				//list socmed
            "footer"							=> $this->admin->select('footer')
        );

        $this->load->view('tnc', $data);
    }
	public function verifikasi()
	{
		$data =json_decode(file_get_contents("php://input"));
		$query = $this->db->where('external_id',$data->external_id)->get("invoices")->result();
		if(count($query)>0){
			if($data->status=="PAID"){
				$this->db->where('external_id',$data->external_id)->update("invoices",["status"=>"1","due_date"=>date("Y-m-d H:i:s")]);
				if($query[0]->invoice_type=="down payment"){
					$this->system->generateInvoice($query[0]->order_id, true);
				}
				$od = $this->system->getOrder($query[0]->order_id);
            	$query[0]->order_name = $od->order_name;
				$this->system->generateReceipt((object)$query[0]);
				// $nextInvoice = $this->system->getInvoices([
                //     "order_id"  => $query[0]->order_id,
                //     "status"    => "0",
                //     "order_by"  => "In.due_date ASC",
                //     "limit"     => "1"
                // ]);
				// if (isset($nextInvoice->id)) {
				// 	$invoice = $nextInvoice;
				// 	$invoice->invoice_header = "INVOICE";
				// 	$invoice->order_name = $od->order_name;
				// 	$filePdf = $this->pdf->generate_pdf($invoice);
				// 	$this->system->send_email($invoice->order_id, $invoice->id,base_url()."pembayaran/".$invoice->id, "invoice generate", $filePdf, "Invoice $invoice->id - " . $invoice->title . ".pdf");
				// }
			}
			else{
				if($query[0]->invoice_type=="down payment"){
					$this->db->where('order_id',$query[0]->order_id)->delete("order");
					$this->db->where('order_id',$query[0]->order_id)->delete("invoices");
				}
			}
		}
		http_response_code(200);
	}
	public function getAllPaymentMethod()
	{
		echo date("Y-m-d", strtotime("2022-02-10".'+2 days'));
		//return $this->paymentMethod->getAllPaymentMethod();
	}
	public function testingEmail()
	{
		// echo (1000000*4)+(7000000*4)+(5000000*4)+(5160000*4);
		// echo '<br>';
		// echo 19840000/4;
		//$invoiceid = $emailViewData['Tagihan_Berikutnya']-;
		//$emailViewData['Tagihan_Berikutnya'] = $nextInvoice;
		$query = $this->db->where('external_id','INV-1704183543')->get("invoices")->result();
		$orderData      = $this->system->getOrder($query[0]->order_id);
        $orderDetail    = $this->system->getOrderDetail($query[0]->order_id);
		$invoiceData    = $this->system->getInvoices(array("invoice_id" => $query[0]->id));
		$nextInvoice = $this->system->getInvoices([
			"order_id"  => $query[0]->order_id,
			"status"    => "0",
			"order_by"  => "In.due_date ASC",
			"limit"     => "1"
		]);
		if($nextInvoice!=null){
			$nextInvoice->url_invoice = 'pembayaran/'.$nextInvoice->id;
		}
		//$invoiceData    = $this->system->getInvoices(array("invoice_id" => $nextInvoice->id));
		$emailViewData = [
            'orderData'         => $orderData,
			'orderDetail'       => $orderDetail,
			'invoiceData'       => $invoiceData,
			'url_invoice'       => 'pembayaran/'.$invoiceData->id
		];

		$emailViewData['next_invoice'] = $nextInvoice;
	
		//var_dump($query);
		//var_dump($emailViewData);


		// check cron
		// $pattern = [    "-7"    => "invoice generate",
		// "-3"    => "friendly reminder",
		// "0"     => "due date",
		// "3"     => "first reminder",
		// "7"     => "second reminder",
		// "10"    => "last reminder"];
		// $params = [ "status" => 0 ];
		// $invoiceData2 = $this->system->getInvoices($params);
		// foreach ($invoiceData2 as $invoice) {
		// $days = GET_DIFF_DAYS($invoice->due_date);
		// foreach ($pattern as $remind_days => $email_type) {
		// 		if ($days == intval($remind_days)) {
		// 			echo "Invoice no $invoice->id, Days $days Remind Days $remind_days <br>";
		// 			$invoice->invoice_header = "INVOICE";
		// 			$filePdf = $this->pdf->generate_pdf($invoice);
		// 			//$this->system2->send_email($invoice->order_id, $invoice->id,base_url()."pembayaran/".$invoice->id, $email_type, $filePdf, "Invoice $invoice->id - " . $invoice->title . ".pdf");
		// 		} else {
		// 			//echo "Invoice no $invoice->id, Due date $invoice->due_date. Diff Days $days. Pattern $remind_days not match. Skip!<br>";
		// 		}
		// 	}
		// }
		// echo $days;
		// var_dump($invoiceData2);
		//die();

		$this->load->view('email/v2/reservation_with_detail',$emailViewData);
	}
	public function test_send_email(){
		$this->system->test_send_email();
	}
	public function checkout($invoice_id)
	{
		$invoice = $this->system->getInvoices([
			"invoice_id"  => $invoice_id
		]);
		$param["invoice"] = $invoice;
		if($invoice->status==0)$this->load->view('pembayaran',$param);
		else $this->load->view('succes_pembayaran',$param);
		//$this->load->view('footer');
	}
	public function proses_pembayaran()
	{
		
		$invoice_id = $this->input->post("invoice_id");
		
		$grand = $this->input->post("grandtotal");
		$method = $this->input->post("method");
		$invoice = $this->system->getInvoices([
			"invoice_id"  => $invoice_id
		]);
		
		$order = $this->system->getOrder($invoice->order_id);
		$exp = 86400;
		if($invoice->invoice_type=="down payment"){
			$exp = 31536000;
		}
		$data = [
			"given_names"=>$order->order_name,
			"email"=>$order->order_email,
			//"email"=>'roybagaskara123@gmail.com',
			"mobile_number"=>$order->order_phone,
			"payment"=>$method
		];
		
// 		echo '<pre>';
// 		var_dump($data);
// 		var_dump($order);
// 		var_dump($invoice);
// 		die();
		
		$result = $this->paymentMethod->createInvoice($grand,$invoice->description,$exp,(object)$data);
		$invoice->invoice_url =$result["url"];
		$invoice->external_id =$result["external_id"];
		$this->system->updateInvoices((array)$invoice,$invoice_id);
		return redirect($result["url"]);
	}
}
