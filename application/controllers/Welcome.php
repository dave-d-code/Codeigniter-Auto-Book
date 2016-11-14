<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends MY_Controller 
{

	function __construct() 
	{
		parent::__construct();
		$this->load->model('frontpage_m');
	}

	public function index()
	{	
		$data = $this->frontpage_m->page_one();
		$this->load->view('templates/header', $data);
		$this->load->view('frontpage', $data);
		$this->load->view('templates/footer', $data);
	}

	public function information()
	{
		$data = $this->frontpage_m->info_page();
		$this->load->view('templates/header', $data);
		$this->load->view('info_page_v', $data);
		$this->load->view('templates/footer', $data);
	}

	public function interests()
	{
		$data = $this->frontpage_m->interests_page();
		$this->load->view('templates/header', $data);
		$this->load->view('interests_v', $data);
		$this->load->view('templates/footer', $data);
	}


} // end of class
