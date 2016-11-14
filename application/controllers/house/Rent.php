<?php 

/**
* Controller for displaying calender and for taking bookings
*/
class Rent extends MY_Controller
{
	
	function __construct()
	{
		parent::__construct();
	}

	// to show the calender first off
	public function calendar()
	{
		$data = $this->booking_m->cal_display();
		$this->load->view('templates/header', $data);
		$this->load->view('booking_v', $data);
		$this->load->view('templates/footer', $data);

	}


	// stage 1 booking with dates places dates into the session for stage 2
	// retrieve from the model
	public function book()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules($this->booking_m->form_rules1);
		if ($this->form_validation->run() == false) {
			$this->calendar();
		} else {
			$data = $this->booking_m->contact_1();
			$this->load->view('templates/header', $data);
			$this->load->view('contact1_v', $data);
			$this->load->view('templates/footer', $data);
		}
	}

	public function confirm($question=false)
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules($this->booking_m->form_rules2);
		if ($this->form_validation->run() == false) {
			
			if (!$question) {
				$data = $this->booking_m->contact_1();
				$this->load->view('templates/header', $data);
				$this->load->view('contact1_v', $data);
				$this->load->view('templates/footer', $data);
			} else {
				$this->contact();
			}
		} else {
			$data = $this->booking_m->response($question);
			$this->load->view('templates/header', $data);
			$this->load->view('response_v', $data);
			$this->load->view('templates/footer', $data);
		}
	}

	public function contact()
	{
		$data = $this->booking_m->ask_me_form();
		$this->load->view('templates/header', $data);
		$this->load->view('contact2_v', $data);
		$this->load->view('templates/footer', $data);	
		
	}

	public function ask_me()
	{
		$this->confirm($question=true);
	}







} // end of class


 ?>