<?php defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    function __construct() 
    {
        parent::__construct();

        define("DEMO", 0);
        $this->Settings = $this->site->get_setting();
        if($sim_language = $this->input->cookie('sim_language', TRUE)) {
            $this->Settings->language = $sim_language;
        }
        $this->config->set_item('language', $this->Settings->language);
        $this->theme = $this->Settings->theme.'/views/';
        $this->data['assets'] = base_url() . 'themes/'.$this->Settings->theme.'/assets/';
        $this->lang->load('sim', $this->Settings->language);
        $this->data['Settings'] = $this->Settings;
        $this->loggedIn = $this->sim->logged_in();

        if($sd = $this->site->getDateFormat($this->Settings->dateformat)) {
            $dateFormats = array(
                'js_sdate' => $sd->js,
                'php_sdate' => $sd->php,
                'mysq_sdate' => $sd->sql,
                'js_ldate' => $sd->js . ' hh:ii',
                'php_ldate' => $sd->php . ' H:i',
                'mysql_ldate' => $sd->sql . ' %H:%i'
                );
        } else {
            $dateFormats = array(
                'js_sdate' => 'mm-dd-yy',
                'php_sdate' => 'm-d-Y',
                'mysq_sdate' => '%m-%d-%Y',
                'js_ldate' => 'mm-dd-yy hh:ii:ss',
                'php_ldate' => 'm-d-Y H:i:s',
                'mysql_ldate' => '%m-%d-%Y %T'
                );
        }
        $this->dateFormats = $dateFormats;
        $this->data['dateFormats'] = $dateFormats;
        $this->Admin = $this->sim->in_group('admin') ? TRUE : NULL;
        $this->data['Admin'] = $this->Admin;
            
    }

    function page_construct($page, $data = array(), $meta = array()) {
        $meta['message'] = isset($data['message']) ? $data['message'] : $this->session->flashdata('message');
        $meta['error'] = isset($data['error']) ? $data['error'] : $this->session->flashdata('error');
        $meta['warning'] = isset($data['warning']) ? $data['warning'] : $this->session->flashdata('warning');
        $meta['Settings'] = $data['Settings'];
        $meta['assets'] = $data['assets'];
        $meta['dateFormats'] = $this->dateFormats;
        $meta['page_title'] = $data['page_title'];
        $meta['events'] = $this->site->getUpcomingEvents();
        $this->load->view($this->theme . 'header', $meta);
        $this->load->view($this->theme . $page, $data);
        $this->load->view($this->theme . 'footer');
    }
    
}
