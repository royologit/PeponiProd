<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Experience extends CI_Migration {

    public function up()
    {
      $this->dbforge->add_field(array(
          'experience_id'              => array(
              'type'                   => 'INT',
              'constraint'             => 11,
              'auto_increment'         => TRUE
          ),
          'experience_name'            => array(
              'type'                   => 'VARCHAR',
              'constraint'             => '100',
          ),
          'experience_description'     => array(
              'type'                   => 'TEXT',
          ),
          'experience_image'           => array(
              'type'                   => 'VARCHAR',
              'constraint'             => '100',
          )
      ));
      $this->dbforge->add_key('experience_id', TRUE);
      $this->dbforge->create_table('experience');
    }

    public function down()
    {
      $this->dbforge->drop_table('experience');
    }
}
