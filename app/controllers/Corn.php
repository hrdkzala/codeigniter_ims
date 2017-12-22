<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Corn extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
    }

    function index()
    {
        show_404();
    }

    function reset_demo() {
        $this->db->truncate('calendar');
        $this->db->truncate('company');
        $this->db->truncate('customers');
        $this->db->truncate('login_attempts');
        $this->db->truncate('payment');
        $this->db->truncate('products');
        $this->db->truncate('quotes');
        $this->db->truncate('quote_items');
        $this->db->truncate('sales');
        $this->db->truncate('sale_items');
        $this->db->truncate('sessions');
        $this->db->truncate('settings');
        $this->db->truncate('tax_rates');
        $this->db->truncate('users');
        $this->db->truncate('users_groups');

        $file = file_get_contents('./dump/demo.sql');
        $this->db->conn_id->multi_query($file);
        $this->db->conn_id->close();
        $this->load->dbutil();
        $this->dbutil->optimize_database();

        redirect('login');
    }

}
