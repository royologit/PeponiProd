<?php defined('BASEPATH') OR exit('No direct script access allowed');
use Xendit\Xendit;

class Payment_method_model extends CI_Model
{
    public $table = 'payment_method';

    public function getAllPaymentMethod()
    {
       // return $this->createInvoice(5000,"test",86400,[]);

    }

    public function createInvoice($amount,$description,$exp,$dataOrder)
    {
        $date = new DateTime();
       
        $ex  = "INV-".$date->getTimestamp();
        $data = array(
            "external_id"=> $ex,
            "description"=> $description,
            "amount"=> intval($amount),
            "invoice_duration"=>$exp,
            'customer' => [
                'given_names' => $dataOrder->given_names,
                'email' => $dataOrder->email,
                'mobile_number' => $dataOrder->mobile_number
            ],
            "payment_methods"=> array($dataOrder->payment)
        );
        
        Xendit::setApiKey("xnd_production_TqlOJyWca7oSJQCrmfdj343LWF1vrdAVkYQy1HECvYd6rxhfZVmUxTzexHwRZK3");
       //Xendit::setApiKey("xnd_development_vi5X5NLCL9oXBYp0USwGtoTAQsl2uUlp6WcoPzInAH0br2GKVsgfPuyO7OFyM35");
        try {
            $inv = \Xendit\Invoice::create($data);
            $response = [
                "url"=>$inv["invoice_url"],
                "external_id"=>$ex
            ];
            
            return $response;
        } catch (\Exception $e) {
            return $e;
        }

    }
}