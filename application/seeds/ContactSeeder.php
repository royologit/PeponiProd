<?php

class ContactSeeder extends Seeder {

  private $table = 'contact';

  public function run()
  {
    $this->db->truncate($this->table);

    //seed many records using faker
    $limit = 5;
    echo "seeding $limit in table ".$this->table;

    for($i=0;$i<$limit;$i++):
      echo ".";
      $data = array(
        'contact_name'        => $this->faker->email,
        'contact_image'       => 'images/product/cover.jpg',
      );
      $this->db->insert($this->table,$data);
    endfor;
  }
  //echo PHP_EOL;

}


?>
