<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Footer extends CI_Migration {

    public function up()
    {
      $this->dbforge->add_field(array(
          'footer_id'               => array(
              'type'                => 'INT',
              'constraint'          => 11,
              'auto_increment'      => TRUE
          ),
          'footer_image'            => array(
              'type'                => 'VARCHAR',
              'constraint'          => '100',
          )
      ));
      $this->dbforge->add_key('footer_id', TRUE);
      $this->dbforge->create_table('footer');
    }

    public function down()
    {
      $this->dbforge->drop_table('footer');
    }
}
