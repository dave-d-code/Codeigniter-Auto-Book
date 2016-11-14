<?php 

/**
* to hold variables and data model for the admin class
*/
class Frontpage_m extends CI_Model
{

	public function page_one()
	{
		$data = $this->page_template();
		$data->title = 'Holiday House Rent ACME';
		$data->customcss = '<link href="' . base_url('assets/css/half-slider.css') .'" rel="stylesheet">';
		$data->meta_keywords = '<meta name="keywords" content="ACME">';
		$data->meta_desc = '<meta name="description" content="Holiday Home, ACME.">';
		$data->footer_runscript = '<script>$(".carousel").carousel({interval: 5000})</script>';
		return $data;
	}

	public function calendar_page()
	{
		$data = $this->page_template();
		$data->title = $this->set_calender_title();
		$data->next_tabs = 'house/Rent/calendar/';
		$data->form_address = 'house/Rent/book';
		$data->form_action = 'Choose dates and proceed with booking';
		$data->meta_keywords = '<meta name="keywords" content="ACME">';
		$data->meta_desc = '<meta name="description" content="Online Calendar showing availibility of ACME.">';
		$data->footer_jqueryui_link = '<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>';
		$data->footer_jqueryui_theme = '<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">';
		$data->footer_runscript = $this->jquery_assembler();
		return $data;
	}

	public function info_page()
	{
		$data = $this->page_template();
		$data->title = 'Holiday House ACME';
		$data->meta_keywords = '<meta name="keywords" content="ACME">';
		$data->meta_desc = '<meta name="description" content="More pictures and infomation about ACME">';
		return $data;
	}

	public function interests_page()
	{
		$data = $this->page_template();
		$data->title = 'ACME offers plenty of things to do';
		$data->meta_desc = '<meta name="description" content="What to do in ACME">';
		return $data;
	}

	public function contact_1()
	{
		$data = $this->page_template();
		$data->title = 'Complete the contact form for ACME.';
		$data->meta_desc = '<meta name="description" content="Contact form for ACME">';
		return $data;
	}

	public function success()
	{
		$data = $this->page_template();
		$data->title = 'Thank you for contacting ACME';
		$data->meta_desc = '<meta name="description" content="You have completed the contact form for ACME">';
		$data->modal_title = 'Thank you for contacting us';
		$data->modal_btn1 = anchor('', 'Go back to the Home Page', 'class="btn btn-primary btn-lg"');
		$data->modal_btn2 = anchor('Welcome/interests', 'More about ACME', 'class="btn btn-warning btn-lg"');
		$data->footer_runscript = '<script>$(document).ready(function () {
    		$("#myModal").modal("show").on("shown.bs.modal", function () {
        	$(".modal").css("display", "block");})});</script>';

		return $data;
	}

	public function failure()
	{
		$data = $this->page_template();
		$data->title = 'Something went wrong with contacting ACME';
		$data->meta_desc = '<meta name="description" content="Something went wrong with the contact form for ACME">';
		$data->modal_title = 'Thank you for contacting us';
		$data->modal_text = '<p class="lead">Something went wrong with our database. Please try again later</p>';
		$data->modal_btn1 = anchor('', 'Go back to the Home Page', 'class="btn btn-primary btn-lg"');
		$data->modal_btn2 = '';
		return $data;
	}

	public function contact_2()
	{
		$data = $this->page_template();
		$data->title = 'Complete the contact form to ask us questions about ACME.';
		$data->meta_desc = '<meta name="description" content="Contact form for asking questions about ACME">';
		return $data;
	}



	public function page_template()
	{
		$data = new stdClass();
		$data->title = '';
		$data->customcss = '<link href="' . base_url('assets/css/mystyle.css') .'" rel="stylesheet">';
		$data->meta_keywords = '<meta name="keywords" content="ACME">';
		$data->meta_robots = '<meta name="robots" content="index, follow">';
		$data->meta_desc = '';
		$data->start_date = '';
		$data->end_date = '';
		$data->footer_jqueryui_link = '';
		$data->footer_jqueryui_theme = '';
		$data->footer_jqueryui_script = '';
		$data->footer_runscript = '';
		return $data;
	}

	// to set the date for jquery

	protected function set_date() 
	{
		if ($this->uri->segment(5)) {
			$current_month = $this->uri->segment(5);
			$current_year = $this->uri->segment(4);
			$str = $current_month . '/01/' . $current_year;
		} elseif ($this->session->has_userdata('times')) {
			$times = $this->session->times;
			$str = $times['current_month'] . '/01/' . $times['current_year'];
		} else {
			$str = 'today';
		}

		return $str;
	}

	// to set the current date in jquery datepicker according to the current month.

	protected function jquery_assembler()
	{
		$date = $this->set_date();
		$str = '<script>$(function() {$( ".datepicker" ).datepicker();$( ".datepicker" ).datepicker("setDate", "';
		$str .= $date;
		$str .= '");$( ".datepicker" ).datepicker({dateFormat: "dd-mm-yy"});var dateFormat = $( ".datepicker" ).datepicker( "option", "dateFormat" );
		  $( ".datepicker" ).datepicker( "option", "dateFormat", "dd-mm-yy" );});</script>';
		return $str;
	}

	// to set dynamic title for the calender pages.

	protected function set_calender_title() 
	{
		if ($this->uri->segment(5)) {
			$current_month = $this->month_to_name($this->uri->segment(5));
			$current_year = $this->uri->segment(4);
			$str = 'Calendar dates for ACME from ' . $current_month . ' ' . $current_year;
		} elseif ($this->session->has_userdata('times')) {
			$times = $this->session->times;
			$str = 'Calendar dates for ACME from ' . $this->month_to_name($times['current_month']) . ' ' . $times['current_year'];
		} else {
			$str = 'Book your ACME here';
		}

		return $str;
		
	}


	protected function month_to_name($month)
	{
		$dateObj = DateTime::createFromFormat('!m', $month);
		return $dateObj->format('F');
	}

	

} // end of class







 ?>