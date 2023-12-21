<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */

	function __construct()
 	{
 		parent::__construct();
		$this->load->model('admin');
 		$this->custom->text_editor('300px');
 	}

	public function content()
	{
		$language = array(
				"id"		=>	"indonesian",
		);
		$this->custom->selected_language($language);
		//$this->config->load('content_en');
		//$this->config->load('content_id');
		echo $this->config->item('about_us'); exit();
	}

	public function content2()
	{
		//$this->config->unload('content_en');
		$this->config->load('content_id');
		echo $this->config->item('about_us'); exit();
	}

	public function language()
	{
		//$this->config->set_item('language','english');
		$this->form_validation->set_rules('full_name','Full Name','required');
		if($this->form_validation->run() == FALSE)
		{
				$this->index();
		}

	}

	public function index($offset='')
	{
		//echo $this->ckeditor->editor("community_description",html_entity_decode(set_value('community_description')));
		/*
		if($offset == ""):
			$offset 				= 0;
		endif;

		$link 						= $this->config->item('admin_dir')."Welcome/index/";
		$segment 					= 3;
		$model						= 'admin';
		$model_function		= 'select';
		$table						= 'admin';
		$join_table				= '';

		if($this->input->post('category') != NULL):$this->session->set_userdata('category',$this->input->post('category')); endif;
		if($this->input->post('search') 	!= NULL):$this->session->set_userdata('search',$this->input->post('search')); 		endif;

		if($this->input->post('category') != NULL):
			$this->session->set_userdata('search',$this->input->post('search'));
			$category   		= $this->session->userdata('category');
			$key_search 		= $this->session->userdata('search');
		else:
			$category   		= $this->session->userdata('category');
			$key_search 		= $this->session->userdata('search');
		endif;

		$category					= 'username';
		$key_search				= 'a';

		$like = array(
			$category				=> $key_search,
		);

		$result 					= $this->custom->pagination_link($offset,$segment,$link,$model,$model_function,$table,$join_table,$like);
		$data['link']			= $result['link'];
		$data['resource']	= $result['resource'];
		$this->load->view('welcome_message',$data);*/
		$this->load->view('welcome_message');
	}

	public function upload()
	{
		// UPLOAD IMAGE
		$name 					= 'product_image';

		$upload_dir   	= $this->config->item('upload_dir');
		$this->custom->folder_exist($upload_dir);

		$folder 				= 'product';
		$folder_dir   	= $upload_dir.'/'.$folder;
		$this->custom->folder_exist($folder_dir);

		$destination  	= $folder_dir;
		$encrypt				= 'false';
		$col_image			= 'tr_product_image';
		$model					= 'admin';
		$model_function =	'add';
		$data						= array( 'product_id' => 1 );
		$table					= 'tr_product_image';

		$result_image 	= $this->custom->upload_image($name,$destination,$encrypt,$col_image,$model,$model_function,$data,$table);

		//RESIZE IMAGE
		if($result_image['file_name'] != "")
		{
			$file_name 				= $result_image['file_name'];
			$file_dir					= $destination.'/'.$file_name;
			$master_dim				= 'height';
			$size							= '100';

			$upload_dir   		= $this->config->item('upload_dir');
			$this->custom->folder_exist($upload_dir);

			$folder 					= 'product';
			$folder_dir   		= $upload_dir.'/'.$folder;
			$this->custom->folder_exist($folder_dir);

			$folder 					= 'thumbnail';
			$folder_dir   		= $folder_dir.'/'.$folder;
			$this->custom->folder_exist($folder_dir);

			$new_destination  = $folder_dir;

			$result_resize		= $this->custom->resize_crop($file_name,$file_dir,$master_dim,$size,$new_destination);
			//print_r($result_resize);
		}

	}

	public function login($username,$password)
	{
		$table							=		'admin';
		$condition = array(
				"username"			=> $username,
				"password"			=> md5($password),
		);
		$result 					=		$this->admin->select($table,'',$condition);


		echo "<table>";
		foreach($result->result() as $res):
			echo "<tr>";
			echo "<td>".$res->username."</td>";
			echo "<td>".$res->password."</td>";
			echo "<td>".$res->last_login."</td>";
			echo "</tr>";
		endforeach;
		echo "</table>";
	}

	public function select()
	{
		$table						=		'admin';
		$result 					=		$this->admin->select($table);
		echo "<table>";
		foreach($result->result() as $res):
			echo "<tr>";
			echo "<td>".$res->username."</td>";
			echo "<td>".$res->password."</td>";
			echo "<td>".$res->last_login."</td>";
			echo "</tr>";
		endforeach;
		echo "</table>";
	}

	public function select_product()
	{
		$table						=		'product';
		$join							= 	 array(
			"package"				=>	 array(
				"package_id"	=>	 'left join',
				),
			);

		$result 					=		$this->admin->select($table,$join);
		echo "<table>";
		foreach($result->result() as $res):
			echo "<tr>";
			echo "<td>".$res->product_name."</td>";
			echo "<td>".$res->package_name."</td>";
			echo "</tr>";
		endforeach;
		echo "</table>";
	}

	public function add()
	{
		$table 						= 	'admin';
		$data  = array(
				"username"	 	=>	"Fandy",
		 		"password"	 	=>	md5("Fandy"),
		);
		$this->admin->add($table,$data);
	}

	public function edit()
	{
		$table 						= 	'admin';
		$data  = array(
				"username"	 	=>	"FandyLimardi",
		 		"password"	 	=>	md5("FandyLimardi"),
		);
		$condition = array(
				"admin_id"			=> 5,
		);
		$this->admin->edit($table,$data,$condition);
	}

	public function delete()
	{
		$table 						= 	'admin';
		$condition = array(
				"admin_id"			=> 5,
		);
		$this->admin->delete($table,$condition);
	}


}
