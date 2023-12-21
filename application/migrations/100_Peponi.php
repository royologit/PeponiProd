<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Peponi extends CI_Migration {

    public function up()
    {
      // TABLE ABOUT
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

      //TABLE ADMIN
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

      //TABLE CAROUSEL
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

      //TABLE CONTACT
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

      //TABLE EXPERIENCE
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

      //TABLE FOOTER
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

      // TABLE MEDIA
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

      //TABLE PACKAGE
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

      //TABLE PRDUCT
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

      //TABLE TR PRODUCT IMAGE
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
      $this->dbforge->drop_table('about');
      $this->dbforge->drop_table('admin');
      $this->dbforge->drop_table('carousel');
      $this->dbforge->drop_table('contact');
      $this->dbforge->drop_table('experience');
      $this->dbforge->drop_table('footer');
      $this->dbforge->drop_table('media');
      $this->dbforge->drop_table('package');
      $this->dbforge->drop_table('product');
      $this->dbforge->drop_table('tr_product_image');
    }
}
