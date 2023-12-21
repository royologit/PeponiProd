<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class GeneralModel extends CI_Model {

  function __construct()
  {
    parent::__construct();
    $this->load->database();
  }

  function selectCarousel()
  {
    $query = $this->db->get('carousel');
        return $query->result();
  }
  public function FunctionName($external_id)
  {
      $this->db->where('external_id',$external_id)->get("invoices")->result();
  }
}
