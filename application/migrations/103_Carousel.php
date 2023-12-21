<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Carousel extends CI_Migration {

    public function up()
    {
      $this->dbforge->add_field(array(
          'carousel_id'             => array(
              'type'                => 'INT',
              'constraint'          => 11,
              'auto_increment'      => TRUE
          ),
          'carousel_name'           => array(
              'type'                => 'VARCHAR',
              'constraint'          => '50',
          ),
          'carousel_description'    => array(
              'type'                => 'VARCHAR',
              'constraint'          => '200',
          ),
          'carousel_layout'         => array(
              'type'                => 'VARCHAR',
              'constraint'          => '50',
          ),
          'carousel_image'          => array(
              'type'                => 'VARCHAR',
              'constraint'          => '100',
          ),
      ));
      $this->dbforge->add_key('carousel_id', TRUE);
      $this->dbforge->create_table('carousel');
    }

    public function down()
    {
      $this->dbforge->drop_table('carousel');
    }
}
