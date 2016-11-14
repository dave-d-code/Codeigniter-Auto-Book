<?php 

/**
* model for loading the calender data, and 
* taking bookings
*/
class Booking_m extends CI_Model
{
	protected  $booked_days = array();
	protected  $booked_days_next_m = array();
	protected  $cal_config = array( // this is how to color code Calendar cells in CI
		'day_type' => 'short',
		'start_day' => 'monday',
		'show_other_days' => true,
		'template' => array(
			'table_open' => '<table class="table table-bordered mytable">',
			'cal_cell_start' => '<td class="',
			'cal_cell_no_content' => '">{day}',
			'cal_cell_content' => '{content}">{day}',
			'cal_cell_blank' => '">&nbsp;',
			'cal_cell_start_today' => '<td class="',
			'cal_cell_content_today' => '{content}">{day}',
			'cal_cell_no_content_today' => 'currday">{day}',
			'cal_cell_start_other' => '<td class="text-muted">',
			'week_row_start' => '<tr class="row_colour">',
			),
		'time' => '',
		);

	public $form_rules1 = array(
		array(
			'field' => 'start_date',
			'label' => 'Start Date',
			'rules' => 'required|trim|alpha_dash|exact_length[10]|callback_date_check',
			),
		array(
			'field' => 'end_date',
			'label' => 'End Date',
			'rules' => 'required|trim|alpha_dash|exact_length[10]|callback_date_check|callback_date_check2',
			),
		);

	public $form_rules2 = array(
		array(
			'field' => 'first_name',
			'label' => 'First Name',
			'rules' => 'required|trim|max_length[50]',
			),
		array(
			'field' => 'last_name',
			'label' => 'Last Name',
			'rules' => 'required|trim|max_length[50]',
			),
		array(
			'field' => 'email',
			'label' => 'Email',
			'rules' => 'required|trim|max_length[255]|valid_email',
			),
		array(
			'field' => 'comments',
			'label' => 'Comments',
			'rules' => 'trim|max_length[2000]',
			),
		);


	
	// to display the caldender with links
	public function cal_display()
	{	
		$this->load->model('frontpage_m');
		$data = $this->frontpage_m->calendar_page();
		$this->load->library('calendar', $this->cal_config);
		$times = $this->calculate_times();
		// set the session
		$this->session->times = $times;

		// get booked_days for each month
		$this->booked_days = $this->get_cal_dates_2($times['current_month']);
		$this->booked_days_next_m = $this->get_cal_dates_2($times['next_m']);


		$data->cal_data = $this->calendar->generate($times['current_year'], $times['current_month'], $this->booked_days);
		$data->cal_data_next = $this->calendar->generate($times['next_y'], $times['next_m'], $this->booked_days_next_m);
		$data->next_y_link = $times['next_y_link'];
		$data->next_m_link = $times['next_m_link'];
		$data->prev_y_link = $times['prev_y_link'];
		$data->prev_m_link = $times['prev_m_link'];

		return $data;
	}

	protected function calculate_times() 
	{
		$times = array();
		// see if anything is in the url first or session

		 if ($this->uri->segment(5)) {
			// set current values for cal 1
			$times['current_year'] = $this->uri->segment(4);
			$times['current_month'] = $this->uri->segment(5);

			// work out next values for the next calender
			$next_data = $this->time_jumper($times['current_month'], $times['current_year'], 1);
			$times['next_y'] = $next_data['year'];
			$times['next_m'] = $next_data['month'];

			// values for the next and previous links
			$next_data_link = $this->time_jumper($times['current_month'], $times['current_year'], 2);
			$times['next_y_link'] = $next_data_link['year'];
			$times['next_m_link'] = $next_data_link['month'];
			$prev_data_link = $this->time_jumper($times['current_month'], $times['current_year'], -2);
			$times['prev_y_link'] = $prev_data_link['year'];
			$times['prev_m_link'] = $prev_data_link['month'];
			
			return $times;
		
		} elseif ($this->session->has_userdata('times')) {
			$times = $this->session->times;

		} else {
			$times['current_year'] = date('Y');
			$times['current_month'] = date('m');
			$times['next_y'] = date('Y', strtotime("+1 month"));
			$times['next_m'] = date('m', strtotime("+1 month"));
			$times['next_y_link'] = date('Y', strtotime("+2 month"));
			$times['next_m_link'] = date('m', strtotime("+2 month"));
			$times['prev_y_link'] = false;
			$times['prev_m_link'] = false;
		}
		return $times;


	}

	protected function time_jumper($month, $year, $jump) 
	{
		$jump1 = $month + $jump;
		return $this->calendar->adjust_date($jump1, $year);
	}

	// function to get array of days for a current month from two dates and push it into an array

	protected  function the_booked_days($start_day, $end_day, $class, $current_month) 
	{
		$dates_array = array();
		$begin = new DateTime($start_day);
		$end = new DateTime($end_day);
		$end = $end->modify( '+1 day' ); 

		$interval = new DateInterval('P1D');
		$daterange = new DatePeriod($begin, $interval ,$end);

			foreach($daterange as $date){
				$date_month = (int)$date->format("m");
				if($current_month == $date_month) {
					$dates_array[(int) $date->format("d")] = $class;		
				}
			}
		return $dates_array;
	}

	// to join two arrays

	protected  function my_join_arrays($array1, $array2)
	{
		return $array1 + $array2;	
	}

	// db fun to get calender dates
	protected function get_calendar_dates_db($month, $status)
	{	
		$this->db->where('rejected !=', 1);	
		$this->db->where("$month BETWEEN MONTH(start_date) AND MONTH(end_date)");
		$this->db->where($status, 1);
		$query = $this->db->get('bookings');
		return $query->result();
	}

	// get the data array to put in the calender
	protected function get_cal_dates_2($current_month)
	{
		// for pending.
		$dates_range = array();
		$query = $this->get_calendar_dates_db($current_month, 'pending');
		foreach ($query as $row) {
			$dates_range = $this->my_join_arrays($dates_range, $this->the_booked_days($row->start_date, $row->end_date, 'pending', $current_month));
		}
		// for confirmed..
		$query2 = $this->get_calendar_dates_db($current_month, 'confirmed');
		foreach ($query2 as $row2) {
			$dates_range = $this->my_join_arrays($dates_range, $this->the_booked_days($row2->start_date, $row2->end_date, 'taken', $current_month));
		}

		return $dates_range;


	}

	// validation function to check the dates dont conflict with a booking
	// used in MY_Model as part of validation callback
	// ditto with date_conflict and date_conflict2

	public function date_checker($start_date, $end_date)
	{
		if ($this->date_conflict($start_date)) {
			return true;
		} elseif ($this->date_conflict($end_date)) {
			return true;
		} elseif ($this->date_conflict2($start_date, $end_date)) {
			return true;
		} else {
			return false;
		}
	}

	// see above date_checker
	protected function date_conflict($date)
	{
		$sql_date = $this->convert_date($date);		
		$this->db->where("('$sql_date' BETWEEN start_date AND end_date)");
		$this->db->where('rejected !=', 1);		
		$query = $this->db->get('bookings');
		if (count($query->result())) {
			return true;
		} else {
			return false;
		}
	}

	// see above date_checker
	protected function date_conflict2($start_date, $end_date)
	{
		$sql_start_date = $this->convert_date($start_date);
		$sql_end_date = $this->convert_date($end_date);
		$this->db->where('start_date > ', $sql_start_date);
		$this->db->where('end_date < ', $sql_end_date);
		$this->db->where('rejected !=', 1);
		$query = $this->db->get('bookings');
		if (count($query->result())) {
			return true;
		} else {
			return false;
		}
	}


	// convert form dates to sql version
	protected function convert_date($date)
	{
		$date1 = strtotime($date);
		return $sql_date = date('Y-m-d', $date1);
	}

	// put dates from input into the session for use later.
	// will abandon this function for now.. see below.
	public function dates_to_session()
	{
		$user_dates = array();
		$user_dates['start_date'] = $this->input->post('start_date', true);
		$user_dates['end_date'] = $this->input->post('end_date', true);
		$this->session->user_dates = $user_dates;
	}

	// run through front_page_m to get data for rent controller for 1st contact page.

	public function contact_1()
	{
		$this->load->model('frontpage_m');
		$data = $this->frontpage_m->contact_1();
		$data->start_date = $this->input->post('start_date', true);
		$data->end_date = $this->input->post('end_date', true);
		return $data;
	}

	// put dates in the db. if admin is true, it is confirmed
	// false admin from user will be pending only
	// get back the insert id for the user

	public function insert_dates($admin=false)
	{
		$sql_start_date = $this->convert_date($this->input->post('start_date', true));
		$sql_end_date = $this->convert_date($this->input->post('end_date', true));
		$data_str = array(
				'start_date' => $sql_start_date,
				'end_date' => $sql_end_date,);
		if ($admin) {
			$data_str['confirmed'] = 1;
			$data_str['by_admin'] = 1;
		} else {
			$data_str['pending'] = 1;
		}

		// insert into database and return the insert_id
		$this->db->set('time_booked', 'NOW()', false);
		$this->db->insert('bookings', $data_str);
		return $this->db->insert_id();	
	}

	// to put all data into the db, will call the above function
	// to save the controller doing it.. for use by user side only
	// also will work for the questions form as well.

	public function insert_client($question=false)
	{
		$data_str = array(
			'first_name' => $this->input->post('first_name', true),
			'last_name' => $this->input->post('last_name', true),
			'email' => $this->input->post('email', true),
			'comments' => $this->input->post('comments', true),
			);
		if (!$question) {
			$data_str['booking_id'] = $this->insert_dates();
		} else {
			$data_str['question_only'] = 1;
		}

		$this->db->insert('clients', $data_str);
		if ($this->db->affected_rows()) {
			return true;
		} else {
			return false;
		}
	}

	// func to handle the response from filling in the form

	public function response($question=false)
	{
		$this->load->model('frontpage_m');
		if ($this->insert_client($question)) {
			$data = $this->frontpage_m->success();
			$data->first_name = $this->input->post('first_name', true);
			$data->last_name = $this->input->post('last_name', true);
			$data->modal_text = '<p class="lead">We will be in contact with you shortly, ';
			$data->modal_text .= $data->first_name;
			$data->modal_text .= '. Please click below to return to the Home Page, or check out the local sites ACME</p>';
		} else {
			$data = $this->frontpage_m->failure();
		}
		
		return $data;
	}

	// to simply channel data for the ask me question form

	public function ask_me_form()
	{
		$this->load->model('frontpage_m');
		return $data = $this->frontpage_m->contact_2();
	}













} // end of class









 ?>