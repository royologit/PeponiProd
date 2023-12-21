<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Media extends CI_Migration {

    public function up()
    {
      $this->dbforge->add_field(array(
          'media_id'                => array(
              'type'                => 'INT',
              'constraint'          => 11,
              'auto_increment'      => TRUE
          ),
          'media_link'              => array(
              'type'                => 'VARCHAR',
              'constraint'          => '200',
          ),
          'media_image'             => array(
              'type'                => 'VARCHAR',
              'constraint'          => '100',
          ),
      ));
      $this->dbforge->add_key('media_id', TRUE);
      $this->dbforge->create_table('media');
    }

    public function down()
    {
      $this->dbforge->drop_table('media');
    }
}
