<?php 

/**
* for admin fundtions only
*/
class Admin_m extends CI_Model
{

	public $form_rules;
	public $login_rules = array(
				array(
					'field' => 'username',
					'label' => 'username',
					'rules' => 'trim|required|max_length[30]',
					),
				array(
					'field' => 'password',
					'label' => 'password',
					'rules' => 'trim|required|max_length[30]',
					),
		);

	protected $table_config = array('table_open' => '<table class="owntable table table-striped table-bordered text-center">',
									'heading_cell_start' => '<th class="text-center">',
			);

	protected $requests_select = 'bookings.id, start_date, end_date';
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('frontpage_m');
		$this->load->model('booking_m');
		$this->form_rules = $this->booking_m->form_rules1;
	}

	// display the caldender using new parameters


	public function calendar_page()
	{
		$raw_data = $this->booking_m->cal_display();
		$data = $this->clean_up($raw_data);
		$data->next_tabs = 'ad/Admin/calendar/';
		$data->form_address = 'ad/Admin/book';
		$data->form_action = 'Auto Book and Confirm your own dates';
		return $data;
	}

	// book confirm dates using admin = true in the booking_m

	public function book()
	{
		$this->booking_m->insert_dates($admin=true);
		$this->session->set_flashdata('msg', 'Your dates have been booked and confirmed by you.');
		$this->session->mark_as_flash('msg');
	}


	// to get table of all unanswered date requests and put them into a table
	public function requests($id=false)
	{	
		$raw_data = $this->frontpage_m->page_template();
		$data = $this->clean_up($raw_data);
		$data->choices = 'adadmin/choices/requests';

		if ($id) {
			$data->page_title = 'Booking Request Details';
			$query = $this->get_requests_data($id);
			$data = $this->request_view($data, $query);
		} else {
			$data->page_title = 'Requested Bookings that await your viewing and approval';		
			$data->table = $this->request_table($this->get_requests_data());	
		}
		return $data;				
	}

	// to get questions info
	public function questions($id=false)
	{
		$raw_data = $this->frontpage_m->page_template();
		$data = $this->clean_up($raw_data);

		if ($id) {
			$data->page_title = 'Individual Question Form Details';
			$query = $this->get_questions_data($id);
			$data = $this->question_view($data, $query);
		} else {
			$data->page_title = 'Questions that await your response';		
			$data->table = $this->question_table($this->get_questions_data());	
		}
		return $data;
	}

	// database queries to get all or one date requests

	public function get_requests_data($id=false)
	{
		$this->db->select('*, bookings.id AS my_id');
		$this->db->join('clients', 'bookings.id = clients.booking_id');
		if ($id) {
			$this->db->where('bookings.id', $id);
		} else {
			$this->db->where('pending', 1);
			$this->db->where('rejected !=', 1);
			$this->db->where('end_date >=', 'DATE(NOW())', false);
			$this->db->order_by('start_date', 'asc');
		}
		$query = $this->db->get('bookings');
		
		if ($id) {
			return $query->first_row();
		} else {
			return $query->result();
		}
	}

	// to accept or reject a booked holiday date and show client as actioned

	public function request_decision($id, $decision)
	{
		if ($decision == 'accept') {
			$data_insert = array(
				'pending' => 0,
				'confirmed' => 1,
				);
		$this->session->set_flashdata('msg', 'This holiday date request has been shown as confirmed in the database');	
		} elseif($decision == 'reject') {
			$data_insert = array(
				'pending' => 0,
				'rejected' => 1,
				);
		$this->session->set_flashdata('msg', 'This holiday date request has been rejected by you.');
		}

		$this->db->where('id', $id);
		$this->db->update('bookings', $data_insert);

		$this->db->reset_query();
		$data_insert2 = array('actioned' => 1);
		$this->db->where('booking_id', $id);
		$this->db->update('clients', $data_insert2);
		$this->session->mark_as_flash('msg'); 		
	}

	// to show question as actioned
	public function actioned($id)
	{
		$this->session->set_flashdata('msg', 'The message has been shown as actioned!!');
		$data_insert = array('actioned' => 1);
		$this->db->where('id', $id);
		$this->db->update('clients', $data_insert);
		$this->session->mark_as_flash('msg');
	}

	// to view live bookings and cancel a booking

	public function live_booking($id=false)
	{
		$raw_data = $this->frontpage_m->page_template();
		$data = $this->clean_up($raw_data);
		$data->choices = 'adadmin/choices/live';

		if ($id) {
			$data->page_title = 'Confirmed Booking Details';
			$query = $this->live_booking_data($id);
			$data = $this->request_view($data, $query);
		} else {
			$data->page_title = 'Live Confirmed Bookings';		
			$data->table = $this->request_table($this->live_booking_data(), false);	
		}
		return $data;
	}

	// to cancel a confirmed booking
	public function cancel_booking($id)
	{
		$this->session->set_flashdata('msg', 'The booking has been cancelled!!');
		$data_insert = array('rejected' => 1);
		$this->db->where('id', $id);
		$this->db->update('bookings', $data_insert);
		$this->session->mark_as_flash('msg');
	}

	// just get data for the login variables
	public function login()
	{
		$raw_data = $this->frontpage_m->page_template();
		$data = $this->clean_up($raw_data);
		return $data;
	}

	public function authenticate()
	{
		$username = $this->input->post('username', true);
		$raw_password = $this->input->post('password', true);
		$password = $this->hash($raw_password);
		$this->db->where('username', $username);
		$this->db->where('password', $password);
		$query = $this->db->get('loggins');
		if (count($query->result())) {
			return true;
		} else {
			$this->session->set_flashdata('msg', 'The username or password is invalid');
			$this->session->mark_as_flash('msg');
			return false;
		}
	}


	// confirmed booking data from the db
	protected function live_booking_data($id=false)
	{
		$this->db->select('*, bookings.id AS my_id');
		$this->db->join('clients', 'bookings.id = clients.booking_id', 'left');
		if ($id) {
			$this->db->where('bookings.id', $id);
		} else {
			$this->db->where('confirmed', 1);
			$this->db->where('rejected !=', 1);
			$this->db->where('end_date >=', 'DATE(NOW())', false);
			$this->db->order_by('start_date', 'asc');
		}
		$query = $this->db->get('bookings');
		
		if ($id) {
			return $query->first_row();
		} else {
			return $query->result();
		}
	}


	// generate the table of all date requests..
	protected function request_table($data, $request=true)
	{
		$this->load->library('table');
		$this->table->set_template($this->table_config);
		if ($request) {
			$anchor_str = 'ad/admin/requests/';
		} else {
			$anchor_str = 'ad/admin/live/';
		}
		$table_data = array(array('Start Date', 'End Date', 'Time Booked', 'First Name', 'Last Name', 'Action'));
		foreach ($data as $row) {
			$feed = array();
			$feed[] = $this->format_time_booked($row->start_date, false);
			$feed[] = $this->format_time_booked($row->end_date, false);
			$feed[] = $this->format_time_booked($row->time_booked);
			if ($row->by_admin) {
				$feed[] = 'ADMIN';
				$feed[] = 'ADMIN';
			} else {
				$feed[] = $row->first_name;
				$feed[] = $row->last_name;
			}
			
			$feed[] = anchor($anchor_str . $row->my_id, 'View This', 'class="btn btn-sm btn-warning"');

			$table_data[] = $feed;
		}
		return $this->table->generate($table_data);
	}


	// generate the page view of the date requests
	protected function request_view($data, $query)
	{
		$data->start_date = $this->format_time_booked($query->start_date, false);
		$data->end_date = $this->format_time_booked($query->end_date, false);
		$data->time_booked = $this->format_time_booked($query->time_booked);
		if ($query->by_admin) {
			$data->first_name = 'Admin Booking';
			$data->last_name = 'Admin Booking';
			$data->email = 'Admin Booking';
		} else {
			$data->first_name = htmlentities($query->first_name);
			$data->last_name = htmlentities($query->last_name);
			$data->email = htmlentities($query->email);
		}
		
		if (empty($query->comments)) {
			$data->comments = 'No Comments were provided!!!!';
		} else {
			$data->comments = htmlentities($query->comments);
		}
		$data->my_id = $query->my_id;
		return $data;
	}


	// function to look at the questions only and put it in a table
	protected function question_table($data)
	{
		$this->load->library('table', $this->table_config);
		$this->load->helper('text');
		$table_data = array(array('First Name', 'Last Name', 'Email', 'Question', 'Action'));

		foreach ($data as $row) {
			$feed = array();
			$feed[] = htmlentities($row->first_name);
			$feed[] = htmlentities($row->last_name);
			$feed[] = character_limiter(htmlentities($row->email), 30);
			$feed[] = word_limiter(htmlentities($row->comments), 4);
			$feed[] = anchor('ad/admin/messages/' . $row->id, 'View This', 'Class="btn btn-sm btn-warning"');

			$table_data[] = $feed;
		}
		return $this->table->generate($table_data);
	}

	// to get each question in a view
	protected function question_view($data, $query)
	{
		$data->first_name = htmlentities($query->first_name);
		$data->last_name = htmlentities($query->last_name);
		$data->email = htmlentities($query->email);
		if (empty($query->comments)) {
			$data->comments = 'No Comments were provided!!!!';
		} else {
			$data->comments = htmlentities($query->comments);
		}
		$data->id = $query->id;
		return $data;
	}

	// to get all question data from the db
	protected function get_questions_data($id=false)
	{
		if ($id) {
			$this->db->where('id', $id);
		} else {
			$this->db->where('question_only', 1);
			$this->db->where('actioned !=', 1);
		}
		$query = $this->db->get('clients');

		if ($id) {
			return $query->first_row();
		} else {
			return $query->result();
		}
	}

	// to rearrange and present better time data using CI helper function

	protected function format_time_booked($timestr, $timestamp=true)
	{
		$this->load->helper('date');
		if ($timestamp) {
			return nice_date($timestr, 'd-m-Y h:i a');
		} else {
			return nice_date($timestr, 'd-m-Y');
		}	
	}


	// to clean frontpage_m data

	protected function clean_up($data)
	{
		$data->meta_robots = '<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">';
		$data->title = 'Green St Lucia Admin Pages';
		return $data;
	}

	// to hash the password
	private function hash($string)
	{
		return hash('sha512', $string . config_item('encryption_key'));
	}





} // end of class





 ?>