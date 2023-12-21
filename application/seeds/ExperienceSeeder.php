<?php

class ExperienceSeeder extends Seeder {

  private $table = 'experience';

  public function run()
  {
    $this->db->truncate($this->table);

    //seed many records using faker
    $limit = 5;
    echo "seeding $limit in table ".$this->table;

    for($i=0;$i<$limit;$i++):
      echo ".";
      $data = array(
        'experience_name'        => $this->faker->sentence,
        'experience_description' => $this->faker->text(400),
        'experience_image'       => 'images/product/cover.jpg',
      );
      $this->db->insert($this->table,$data);
    endfor;
  }
  //echo PHP_EOL;

}


?>
