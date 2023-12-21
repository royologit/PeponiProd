<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AuthController extends CI_Controller {

	function __construct()
 	{
 		parent::__construct();
		$this->load->model($this->config->item('admin_dir_model').'admin','admin');
		$this->load->model('admin');
 	}

	function login()
	{
			$this->form_validation->set_rules('username','Username','required');
			$this->form_validation->set_rules('password','Password','required|callback_check_user');

			if($this->form_validation->run() == FALSE)
			{
				$admin_dir_view = $this->config->item('admin_dir_view');
				$this->load->view('/'.$admin_dir_view.'login');
			}
			else
			{
				$admin_controller			= 'AdminController';
				$admin_dir_controller = $this->config->item('admin_dir_controller');

				redirect('/'.$this->config->item('admin_softlink').'Admin_Management');
				//redirect('/'.$admin_dir_controller.$admin_controller.'/page/Admin_Management');
			}
	}

	function check_user()
	{
		$username 					= $this->input->post('username');
		$password 					= $this->input->post('password');
		$this->form_validation->set_message('check_user', 'username and password is not registered in our database');

		$table							=		'admin';
		$condition = array(
				"username"			=> $username,
				"password"			=> md5($password),
		);
		$result 						=	$this->admin->select($table,'',$condition);

		if($result->num_rows() == 1):
			$navbar = array(
					'admin_id' 				=> $result->row()->admin_id,
					'admin_name'			=> $result->row()->username,
				);
			$this->session->set_userdata($navbar);
			return true;
		else:
			return false;
		endif;
	}

	function logout()
	{
		$this->session->sess_destroy();
		redirect($this->config->item('admin_softlink').'login');
	}


}
