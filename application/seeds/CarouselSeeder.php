<?php

class CarouselSeeder extends Seeder {

  private $table = 'carousel';

  public function run()
  {
    $this->db->truncate($this->table);

    //seed many records using faker
    $limit = 5;
    echo "seeding $limit in table ".$this->table;

    for($i=0;$i<$limit;$i++):
      echo ".";
      $data = array(
        'carousel_name'          => $this->faker->sentence(6),
        'carousel_description'   => $this->faker->paragraph(1),
        'carousel_layout'        => $this->faker->word,
        'carousel_image'         => 'images/product/cover.jpg',
      );
      $this->db->insert($this->table,$data);
    endfor;
  }
  //echo PHP_EOL;

}


?>
