<?php

class AdminSeeder extends Seeder {

  private $table = 'admin';

  public function run()
  {
    $this->db->truncate($this->table);

    //seed many records using faker
    $limit = 5;
    echo "seeding $limit in table ".$this->table;

    for($i=0;$i<$limit;$i++):
      echo ".";
      $data = array(
        'username'          => $this->faker->userName,
        'password'          => md5('123321'),
        'last_login'        => $this->faker->dateTimeBetween($startDate='-15 years',$endDate='now')->format('Y-m-d H:i:s'),
      );
      $this->db->insert($this->table,$data);
    endfor;
  }
  //echo PHP_EOL;

}


?>
