<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Admin extends CI_Migration {

    public function up()
    {
      $this->dbforge->add_field(array(
          'admin_id'            => array(
              'type'            => 'INT',
              'constraint'      => 11,
              'auto_increment'  => TRUE
          ),
          'username'            => array(
              'type'            => 'VARCHAR',
              'constraint'      => '100',
          ),
          'password'            => array(
              'type'            => 'CHAR',
              'constraint'      => '32',
          ),
          'last_login'          => array(
              'type'            => 'TIMESTAMP',
          ),
      ));
      $this->dbforge->add_key('admin_id', TRUE);
      $this->dbforge->create_table('admin');
    }

    public function down()
    {
      $this->dbforge->drop_table('admin');
    }
}
