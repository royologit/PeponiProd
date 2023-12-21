<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_About extends CI_Migration {

    public function up()
    {
      $this->dbforge->add_field(array(
          'about_id'                => array(
              'type'                => 'INT',
              'constraint'          => 11,
              'auto_increment'      => TRUE
          ),
          'about_description'       => array(
              'type'                => 'TEXT',
          ),
          'about_image'             => array(
              'type'                => 'VARCHAR',
              'constraint'          => '100',
          ),
      ));
      $this->dbforge->add_key('about_id', TRUE);
      $this->dbforge->create_table('about');
    }

    public function down()
    {
      $this->dbforge->drop_table('about');
    }
}
