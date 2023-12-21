<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Package_model extends CI_Model
{
    public $table = 'package';

    public function pushPackage($packageId,$status)
    {
       return  $this->db->where('package_id', $packageId)->update($this->table,[
            "package_push"=>$status
        ]);
    }
}