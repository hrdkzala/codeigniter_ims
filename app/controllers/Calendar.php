<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Calendar extends MY_Controller {

/*
| -----------------------------------------------------
| PRODUCT NAME: 	SIMPLE INVOICE MANAGER
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
| This is calendar module controller file.
| -----------------------------------------------------
*/


	function __construct()
	{
		parent::__construct();

		if (!$this->sim->logged_in()) {
			redirect('auth/login');
		}
		if($this->sim->in_group('customer')) {
			$this->session->set_flashdata('message', $this->lang->line("access_denied"));
			redirect('clients');
		}
		$this->load->model('calendar_model');
	}


	function index($year = NULL, $month = NULL)
	{
		if($this->input->get('year')){ $year = $this->input->get('year'); }
		if($this->input->get('month')){ $month = $this->input->get('month'); }
		if(!$year) { $year = date('Y'); }
		if(!$month) { $month = date('m'); }
		if ($day = $this->input->post('day')) {
			if(!$this->input->post('data')) {
				$this->calendar_model->deleteEvent("$year-$month-$day");
			} else {
				$this->calendar_model->add_calendar_data("$year-$month-$day", $this->input->post('data'));
			}
		}

		$this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');

		$config = array (
			'show_next_prev'  => TRUE,
			'next_prev_url'   => site_url('calendar'),
			'month_type'   => 'long',
			'day_type'     => 'long'
			);

		$config['template'] = '

		{table_open}<table border="0" cellpadding="0" cellspacing="0" class="table table-bordered" style="min-width:522px;">{/table_open}

		{heading_row_start}<tr>{/heading_row_start}

		{heading_previous_cell}<th><a href="{previous_url}">&lt;&lt;</a></th>{/heading_previous_cell}
		{heading_title_cell}<th colspan="{colspan}" id="month_year">{heading}</th>{/heading_title_cell}
		{heading_next_cell}<th><a href="{next_url}">&gt;&gt;</a></th>{/heading_next_cell}

		{heading_row_end}</tr>{/heading_row_end}

		{week_row_start}<tr>{/week_row_start}
		{week_day_cell}<td class="cl_wday">{week_day}</td>{/week_day_cell}
		{week_row_end}</tr>{/week_row_end}

		{cal_row_start}<tr class="days">{/cal_row_start}
		{cal_cell_start}<td class="day">{/cal_cell_start}

		{cal_cell_content}
		<div class="day_num">{day}</div>
		<div class="content">{content}</div>
		{/cal_cell_content}
		{cal_cell_content_today}
		<div class="day_num highlight">{day}</div>
		<div class="content">{content}</div>
		{/cal_cell_content_today}

		{cal_cell_no_content}<div class="day_num">{day}</div>{/cal_cell_no_content}
		{cal_cell_no_content_today}<div class="day_num highlight">{day}</div>{/cal_cell_no_content_today}

		{cal_cell_blank}&nbsp;{/cal_cell_blank}

		{cal_cell_end}</td>{/cal_cell_end}
		{cal_row_end}</tr>{/cal_row_end}

		{table_close}</table>{/table_close}
		';


		$this->load->library('calendar', $config);


		$cal_data = $this->calendar_model->get_calendar_data($year, $month);
		$this->data['calender'] = $this->calendar->generate($year, $month, $cal_data);

		$this->data['page_title'] = $this->lang->line("calendar");

		$this->page_construct('calendar', $this->data);

	}

	public function get_event($date)
	{
		$this->data['event'] = $this->calendar_model->get_event_data($date);
		$this->data['date'] = $date;
		$this->data['page_title'] = $this->lang->line("day_event");
		$this->load->view($this->theme.'event', $this->data);
	}

}
