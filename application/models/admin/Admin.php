<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Model {

  function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

  function select($table,$join='',$condition='',$like='',$limit='',$order_by='')
  {
    if($join  != ''):
    foreach($join as $join_table => $sub_array):
      foreach($sub_array as $key => $type):
        $this->db->join($join_table,$table.'.'.$key.' = '.$join_table.'.'.$key,$type);
      endforeach;
    endforeach;
    endif;

    if($condition  != ''):
    foreach($condition as $column => $value):
      $this->db->where($column,$value);
    endforeach;
    endif;

    if ($table == 'product') {
        $this->db->where('product_deactivated_at', null);
        $this->db->order_by('product_push', "desc");
    } elseif ($table == 'age_group') {
        $this->db->where('age_group_deactivated_at', null);
    } elseif ($table == 'payment_method') {
        $this->db->where('payment_method_deactivated_at', null);
    } elseif ($table == 'package') {
        $this->db->order_by('package_push', "desc");
    }

    if($like  != ''):
    foreach($like as $column => $value):
      $this->db->like($column,$value);
    endforeach;
    endif;

    if($limit  != ''):
    foreach($limit as $column => $value):
      $this->db->limit($column,$value);
    endforeach;
    endif;

    if($order_by != ''):
    foreach($order_by as $column => $value):
      $this->db->order_by($column,$value);
    endforeach;
    endif;

    $query = $this->db->get($table);
    return $query;
  }

  function add($table,$data)
  {
    $this->db->insert($table,$data);
    return $this->db->insert_id();
  }

  function edit($table,$data,$condition)
  {
    foreach($data as $column => $value):
      $this->db->set($column,$value);
    endforeach;

    foreach($condition as $column => $value):
      $this->db->where($column,$value);
    endforeach;
      $this->db->update($table);
    
    }

  function delete($table,$condition)
  {
    foreach($condition as $column => $value):
      $this->db->where($column,$value);
    endforeach;
      $this->db->delete($table);
  }

}
