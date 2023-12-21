<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Tr_Product_Image extends CI_Migration {

    public function up()
    {
      $this->dbforge->add_field(array(
          'tr_product_image_id'        => array(
              'type'                   => 'INT',
              'constraint'             => 11,
              'auto_increment'         => TRUE
          ),
          'product_id'                 => array(
              'type'                   => 'INT',
              'constraint'             => 11,
          ),
          'product_image'           => array(
              'type'                   => 'VARCHAR',
              'constraint'             => '100',
          )
      ));
      $this->dbforge->add_key('tr_product_image_id', TRUE);
      $this->dbforge->create_table('tr_product_image');
    }

    public function down()
    {
      $this->dbforge->drop_table('tr_product_image');
    }
}
