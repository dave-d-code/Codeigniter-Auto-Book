<?php 

/**
*  base controller to share some of the validation functions
*/
class MY_Controller extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('booking_m');
		$this->load->helper('form');
	}

	// common validation functions
	// method to check if the start date is not before today

		public function date_check($str)
	{
		$today = strtotime('today');
		$test_str = strtotime($str);
		if ($test_str < $today) {
			$this->form_validation->set_message('date_check', 'The %s field cannot be in the past');
			return false;
		} else {
			return true;
		}
	}

	// method to check if the end date is not before the start date.
	// also to check if no bookings are anywhere within the period.

	public function date_check2($str)
	{
		$start_date = $this->input->post('start_date', true);
		$end_date = $this->input->post('end_date', true);
		if (strtotime($start_date) > strtotime($end_date)) {
			$this->form_validation->set_message('date_check2', 'The End date cannot be before the start date');
			return false;
		} elseif($this->booking_m->date_checker($start_date, $end_date)) {
			$this->form_validation->set_message('date_check2', 'The dates conflict with a booking');
			return false;
		} else {
			return true;
		}
	}











} // end of class








 ?>