<?php  if ( ! defined('BASEPATH')) exit("No direct script access allowed");

class Migrate extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->input->is_cli_request()
        or exit("Execute via command line: php index.php migrate");

        $this->load->library('migration');
    }

    public function index()
    {
        if ($this->migration->latest() === FALSE)
        {
            show_error($this->migration->error_string());
        }
    }

    public function version($version)
    {
        if ($this->migration->version($version) === FALSE)
        {
            show_error($this->migration->error_string());
        }
    }
}