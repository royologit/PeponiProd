<?php

class FooterSeeder extends Seeder {

  private $table = 'footer';

  public function run()
  {
    $this->db->truncate($this->table);

    //seed many records using faker
    $limit = 1;
    echo "seeding $limit in table ".$this->table;

    for($i=0;$i<$limit;$i++):
      echo ".";
      $data = array(
        'footer_image'       => 'images/product/cover.jpg',
      );
      $this->db->insert($this->table,$data);
    endfor;
  }
  //echo PHP_EOL;

}


?>
