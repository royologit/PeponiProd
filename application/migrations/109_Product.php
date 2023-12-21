<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Product extends CI_Migration {

    public function up()
    {
      $this->dbforge->add_field(array(
          'product_id'                    => array(
              'type'                      => 'INT',
              'constraint'                => 11,
              'auto_increment'            => TRUE
          ),
          'package_id'                    => array(
              'type'                      => 'INT',
              'constraint'                => 11,
          ),
          'product_name'                  => array(
              'type'                      => 'VARCHAR',
              'constraint'                => '50',
          ),
          'product_price'                 => array(
              'type'                      => 'INT',
              'constraint'                => '11',
          ),
          'product_duration'              => array(
              'type'                      => 'VARCHAR',
              'constraint'                => '100',
          ),
          'product_airlines'              => array(
              'type'                      => 'VARCHAR',
              'constraint'                => '100',
          ),
          'product_include'               => array(
              'type'                      => 'VARCHAR',
              'constraint'                => '100',
          ),
          'product_exclude'               => array(
              'type'                      => 'VARCHAR',
              'constraint'                => '100',
          ),
          'product_rundown_tour'          => array(
              'type'                      => 'TEXT',
          ),
          'product_cover_image'           => array(
              'type'                      => 'VARCHAR',
              'constraint'                => '100',
          ),
          'product_registration_date'     => array(
              'type'                      => 'TIMESTAMP',
              'extra'                     => '',
          ),
      ));
      $this->dbforge->add_key('product_id', TRUE);
      $this->dbforge->create_table('product');
    }

    public function down()
    {
      $this->dbforge->drop_table('product');
    }
}
