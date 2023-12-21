<?php

class Seed extends CI_Controller
{
    public function index($name)
    {
      $this->load->library('seeder');
      $this->faker = Faker\Factory::create('id_ID');
      $this->seeder->call($name);
    }

    public function all()
    {
      $this->load->library('seeder');
      $this->faker = Faker\Factory::create('id_ID');
      $item = array('AboutSeeder','AdminSeeder','CarouselSeeder',
                    'ContactSeeder','ExperienceSeeder','FooterSeeder',
                    'MediaSeeder','PackageSeeder','ProductSeeder',
                    'TrProductImageSeeder');
      foreach ($item as $name ) :
        $this->seeder->call($name);
        echo "<br/>";
      endforeach;
    }
}
