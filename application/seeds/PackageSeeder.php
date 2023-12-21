<?php

class PackageSeeder extends Seeder {

  private $table = 'package';

  public function run()
  {
    $this->db->truncate($this->table);

    //seed many records using faker
    $limit = 3;
    echo "seeding $limit in table ".$this->table;

    for($i=0;$i<$limit;$i++):
      echo ".";
      $data = array(
        'package_name'          => $this->faker->word(),
        'package_description'   => $this->faker->sentence(2),
        'package_image'         => 'images/product/cover.jpg',
      );
      $this->db->insert($this->table,$data);
    endfor;
  }
  //echo PHP_EOL;

}


?>
