<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends MY_Controller 
{
	// These 2 urls/controllers allowed for non logged in users
	private $exceptions = array('ad/admin/authenticate', 'ad/admin/login');

	function __construct() 
	{
		parent::__construct();
		$this->load->model('admin_m');
		if (!$this->session->has_userdata('gsl_logg')) {
			if (in_array(uri_string(), $this->exceptions) == false) {
				redirect('ad/admin/login');
			}
		}
	}

	// display the calender showing all dates booked
	public function calendar()
	{
		$data = $this->admin_m->calendar_page();
		$this->load->view('adadmin/header_v', $data);
		$this->load->view('booking_v', $data);
		$this->load->view('adadmin/footer_v', $data);
	}

	// ADMIN Booking only. Allows ADMIN to book directly w/o going through the site
	public function book()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules($this->admin_m->form_rules);
		if ($this->form_validation->run() == false) {
			$this->calendar();
		} else {
			$this->admin_m->book();
			$this->calendar();
		}
	}

	// once logged in, ADMIN shown the calendar
	public function index()
	{
		$this->calendar();
	}

	// login section for ADMIN control
	public function login()
	{
		$data = $this->admin_m->login();
		$this->load->helper('form');
		$this->load->view('adadmin/header_wo_v', $data);
		$this->load->view('adadmin/login_v', $data);
		$this->load->view('adadmin/footer_v', $data);		
	}

	// logging out
	public function logout()
	{
		$this->session->unset_userdata('gsl_logg');
		redirect('ad/admin/login');
	}

	// checking logging in details
	public function authenticate()
	{
		$this->load->library('form_validation', $this->admin_m->login_rules);
		if ($this->form_validation->run() == FALSE) {
			$this->login();
		} else {
			if ($this->admin_m->authenticate()) {
				$this->session->set_userdata('gsl_logg', true);
				redirect('ad/admin/calendar');
			} else {
				$this->login();
			}
		}

		
	}

	// to check tables of requested dates, and also to view each one individually
	public function requests($id=false)
	{
		if ($id) {
			$data = $this->admin_m->requests($id);
			$this->load->view('adadmin/header_v', $data);
			$this->load->view('adadmin/open_v', $data);
			$this->load->view('adadmin/footer_v', $data);
		} else {
			$data = $this->admin_m->requests();
			$this->load->view('adadmin/header_v', $data);
			$this->load->view('adadmin/table_v', $data);
			$this->load->view('adadmin/footer_v', $data);
		}	
	}

	public function requests_decision($id, $decision)
	{
		$this->admin_m->request_decision($id, $decision);
		redirect('ad/admin/requests');
	}

	public function messages($id=false)
	{
		if ($id) {
			$data = $this->admin_m->questions($id);
			$this->load->view('adadmin/header_v', $data);
			$this->load->view('adadmin/open_v_2', $data);
			$this->load->view('adadmin/footer_v', $data);
		} else {
			$data = $this->admin_m->questions();
			$this->load->view('adadmin/header_v', $data);
			$this->load->view('adadmin/table_v', $data);
			$this->load->view('adadmin/footer_v', $data);
		}
	}

	public function actioned($id)
	{
		$this->admin_m->actioned($id);
		redirect('ad/admin/messages');
	}

	public function live($id=false)
	{
		if ($id) {
		$data = $this->admin_m->live_booking($id);
		$this->load->view('adadmin/header_v', $data);
		$this->load->view('adadmin/open_v', $data);
		$this->load->view('adadmin/footer_v', $data);
		} else {
		$data = $this->admin_m->live_booking();
		$this->load->view('adadmin/header_v', $data);
		$this->load->view('adadmin/table_v', $data);
		$this->load->view('adadmin/footer_v', $data);
	}
	}

	public function cancel_booking($id)
	{
		$this->admin_m->cancel_booking($id);
		redirect('ad/admin/live');
	}







	


} // end of class