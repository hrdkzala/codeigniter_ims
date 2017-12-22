<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/*
| -----------------------------------------------------
| PRODUCT NAME: 	SCHOOL MANAGER
| -----------------------------------------------------
| AUTHER:			MIAN SALEEM
| -----------------------------------------------------
| EMAIL:			saleem@tecdiary.com
| -----------------------------------------------------
| COPYRIGHTS:		RESERVED BY TECDIARY IT SOLUTIONS
| -----------------------------------------------------
| WEBSITE:			http://tecdiary.net
| -----------------------------------------------------
|
| MODULE: 			Calendar
| -----------------------------------------------------
| This is calendar module model file.
| -----------------------------------------------------
*/


class Calendar_model extends CI_Model
{


	public function __construct()
	{
		parent::__construct();
	}

	public function get_calendar_data($year, $month) {

		if($this->Settings->calendar) {
			$query = $this->db->select('date, data')->like('date', "$year-$month", 'after')
			->get_where('calendar', array('user_id' => $this->session->userdata('user_id')));
		} else {
			$query = $this->db->select('date, data')->like('date', "$year-$month", 'after')
			->get('calendar');
		}

		$cal_data = array();

		foreach ($query->result() as $row) {
			$day = (int)substr($row->date,8,2);
			$cal_data[$day] = str_replace("|", "<br>", html_entity_decode($row->data));
		}
		return $cal_data;

	}

	public function add_calendar_data($date, $data) {

		$data = htmlentities(strip_tags($data, '<br><br /><br/>'));
		if(empty($data)){
			$this->deleteEvent($date);
		} else {
			if ($this->db->select('date')->from('calendar')
					->where('date', $date)->count_all_results()) {

				$this->db->where('date', $date)
					->update('calendar', array(
					'date' => $date,
					'data' => $data,
					'user_id' => $this->session->userdata('user_id')
				));

			} else {

				$this->db->insert('calendar', array(
					'date' => $date,
					'data' => $data,
					'user_id' => $this->session->userdata('user_id')
				));
			}
		}

	}

	public function deleteEvent($date) {

		if($this->db->delete('calendar', array('date' => $date))) {
			return true;
		}
		return FALSE;
	}

	public function get_event_data($date) {

		if($this->Settings->calendar) {
			$q = $this->db->select('date, data')->where('date', $date)
			->get_where('calendar', array('user_id' => $this->session->userdata('user_id')));
		} else {
			$q = $this->db->select('date, data')->where('date', $date)
			->get('calendar');
		}

		if( $q->num_rows() > 0 ) {
			return $q->row();
		}

		return false;

	}

}

/* End of file calendar_model.php */
/* Location: ./sma/modules/calendar/models/calendar_model.php */
