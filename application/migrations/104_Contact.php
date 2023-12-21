<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Contact extends CI_Migration {

    public function up()
    {
      $this->dbforge->add_field(array(
          'contact_id'              => array(
              'type'                => 'INT',
              'constraint'          => 11,
              'auto_increment'      => TRUE
          ),
          'contact_name'            => array(
              'type'                => 'VARCHAR',
              'constraint'          => '100',
          ),
          'contact_image'           => array(
              'type'                => 'VARCHAR',
              'constraint'          => '100',
          )
      ));
      $this->dbforge->add_key('contact_id', TRUE);
      $this->dbforge->create_table('contact');
    }

    public function down()
    {
      $this->dbforge->drop_table('contact');
    }
}
