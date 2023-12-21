<?php

class ProductSeeder extends Seeder {

  private $table = 'product';

  public function run()
  {
    $this->db->truncate($this->table);

    //seed many records using faker
    $limit = 5;
    echo "seeding $limit in table ".$this->table;

    for($i=0;$i<$limit;$i++):
      echo ".";
      $data = array(
        'package_id'                  => $this->faker->numberBetween(1,3),
        'product_name'                => $this->faker->word(1),
        'product_price'               => $this->faker->numberBetween(3000000,10000000),
        'product_duration'            => $this->faker->dateTimeBetween($startDate='+2 weeks',$endDate='+4 weeks')->format('Y-m-d H:i:s')." - ".$this->faker->dateTimeBetween($startDate='+2 weeks',$endDate='+4 weeks')->format('Y-m-d H:i:s'),
        'product_airlines'            => $this->faker->word(3),
        'product_include'             => $this->faker->sentence(2),
        'product_exclude'             => $this->faker->sentence(2),
        'product_rundown_tour'        => $this->faker->text(200),
        'product_cover_image'         => 'images/product/cover.jpg',
        'product_registration_date'   => $this->faker->dateTimeBetween($startDate='-1 weeks',$endDate='+1 weeks')->format('Y-m-d H:i:s'),
      );
      $this->db->insert($this->table,$data);
    endfor;
  }
  //echo PHP_EOL;

}


?>
