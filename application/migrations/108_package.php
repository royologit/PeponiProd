<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Package extends CI_Migration {

    public function up()
    {
      $this->dbforge->add_field(array(
          'package_id'                 => array(
              'type'                   => 'INT',
              'constraint'             => 11,
              'auto_increment'         => TRUE
          ),
          'package_name'               => array(
              'type'                   => 'VARCHAR',
              'constraint'             => '50',
          ),
          'package_description'        => array(
              'type'                   => 'VARCHAR',
              'constraint'             => '200',
          ),
          'package_image'              => array(
              'type'                   => 'VARCHAR',
              'constraint'             => '100',
          )
      ));
      $this->dbforge->add_key('package_id', TRUE);
      $this->dbforge->create_table('package');
    }

    public function down()
    {
      $this->dbforge->drop_table('package');
    }
}
