<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');require_once dirname(__FILE__) . "/../libraries/Mollie/API/Autoloader.php";
class Sales extends MY_Controller {

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
    | WEBSITE:			http://tecdiary.com
    | -----------------------------------------------------
    |
    | MODULE: 			Sales
    | -----------------------------------------------------
    | This is sales module controller file.
    | -----------------------------------------------------
    */


    function __construct()
    {
        parent::__construct();

        if (!$this->sim->logged_in())
        {
            redirect('auth/login');
        }
        if($this->sim->in_group('customer')) {
            $this->session->set_flashdata('error', $this->lang->line("access_denied"));
            redirect('clients');
        }
        $this->load->library('form_validation');
        $this->load->model('sales_model');
        define('WORDS_LANG', 'en_US');
        $this->load->library('mywords');
        $this->mywords->load('Numbers/Words');

    }
    /* -------------------------------------------------------------------------------------------------------------------------------- */
//index or inventories page

    function index()
    {

        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));

        if($this->input->get('customer_id')){ $this->data['customer_id'] = $this->input->get('customer_id'); } else { $this->data['customer_id'] = NULL; }
        $user = $this->site->getUser();
        $this->data['from_name'] = $user->first_name." ".$user->last_name;
        $this->data['from_email'] = $user->email;

        $this->data['page_title'] = $this->lang->line("invoices");
        $this->page_construct('sales/index', $this->data);
    }

    function getdatatableajax()
    {

        if($this->Settings->restrict_sales && !$this->sim->in_group('admin')) { $check = TRUE; } else { $check = NULL; }
        $user_id = $this->session->userdata('user_id');

        $opt = "<center><div class='btn-group' style='margin:0;'>
		<a class=\"tip view_payment btn btn-success btn-xs\" title='".$this->lang->line("view_payments")."' href='".site_url('sales/view_payments/')."?id=$1' id='$1' data-customer='$2' data-company='$3' data-toggle='modal' data-target='#simModal'><i class=\"fa fa-dollar\"></i></a>
		<a class=\"tip add_payment btn btn-success btn-xs\" title='".$this->lang->line("add_payment")."' href='#' id='$1' data-customer='$2' data-company='$3'><i class=\"fa fa-briefcase\"></i></a>
		<a class=\"tip btn btn-primary btn-xs\" title='".$this->lang->line("view_invoice")."' href='#' onClick=\"MyWindow=window.open('".site_url('sales/view_invoice/')."?id=$1', 'MyWindow','toolbar=yes,location=no,directories=no,status=no,menubar=yes,scrollbars=yes,resizable=yes,width=1000,height=600'); return false;\"><i class=\"fa fa-file-text-o\"></i></a>
		<a class=\"tip btn btn-primary btn-xs\" title='".$this->lang->line("download_pdf")."' href='".site_url('sales/pdf/')."?id=$1'><i class=\"fa fa-download\"></i></a>
		<a class=\"tip email_inv btn btn-success btn-xs\" title='".$this->lang->line("email_invoice")."' href='#' id='$1' data-customer='$2' data-company='$3'><i class=\"fa fa-envelope\"></i></a>
		<a class=\"tip btn btn-warning btn-xs\" title='".$this->lang->line("edit_invoice")."' href='".site_url('sales/edit/')."?id=$1'><i class=\"fa fa-edit\"></i></a>
		<a class=\"tip btn btn-danger btn-xs\" title='".$this->lang->line("delete_invoice")."' href='".site_url('sales/delete/')."?id=$1' onClick=\"return confirm('". $this->lang->line('alert_x_invoice') ."')\"><i class=\"fa fa-trash-o\"></i></a></div></center>";


        if($this->input->get('customer_id')){ $customer_id = $this->input->get('customer_id'); } else { $customer_id = NULL; }

        $this->load->library('datatables');
        //(CASE WHEN users.first_name is null THEN sales.user ELSE CONCAT(users.first_name, ' ', users.last_name) END) as user
        $this->datatables
            ->select("sales.id as sid, sales.date as date, company.company, reference_no, CONCAT(users.first_name, ' ', users.last_name) as user, customer_name, total+COALESCE(shipping, 0) as total, COALESCE(sum(payment.amount), 0) as amount, (total+COALESCE(shipping, 0))-COALESCE(sum(payment.amount), 0) as balance, due_date, sales.status as status, sales.recurring, sales.customer_id as cid, sales.company as bid", FALSE)
            ->from('sales')
            ->join('company', 'company.id=sales.company', 'left')
            ->join('payment', 'payment.invoice_id=sales.id', 'left')
            ->join('users', 'users.id=sales.user', 'left')
            ->group_by('sales.id');
        if($customer_id) { $this->datatables->where('sales.customer_id', $customer_id); }
        if($check) { $this->datatables->where('sales.user', $user_id); }
        $this->datatables->edit_column('status', '$1-$2', 'status, sid')
            ->add_column("Actions", $opt, "sid, cid, bid")

            ->unset_column('cid')
            ->unset_column('bid');

        echo $this->datatables->generate();

    }


    function quotes()
    {

        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $this->data['message'] = $this->session->flashdata('message');

        $user = $this->site->getUser();
        $this->data['from_name'] = $user->first_name." ".$user->last_name;
        $this->data['from_email'] = $user->email;
        $this->data['page_title'] = $this->lang->line("quotes");
        $this->page_construct('sales/quotes', $this->data);
    }

    function getquotes()
    {

        if($this->Settings->restrict_sales && !$this->sim->in_group('admin')) { $check = TRUE; } else { $check = NULL; }
        $this->load->library('datatables');
        $this->datatables
            ->select("quotes.id as id, date, company.company, reference_no, CONCAT(users.first_name, ' ', users.last_name) as user, customer_name, inv_total, total_tax, COALESCE(shipping, 0) as shipping, COALESCE(total_discount, 0) as discount, (total+COALESCE(shipping, 0)) as total, quotes.status, quotes.customer_id as cid, quotes.company as bid", FALSE)
            ->from('quotes')
            ->join('company', 'company.id=quotes.company', 'left')
            ->join('users', 'users.id=quotes.user', 'left')
            ->group_by('quotes.id');
        if($check) { $this->datatables->where('user', $this->session->userdata('user_id')); }
        $this->datatables->add_column("Actions",
            "<center><div class='btn-group'><a class=\"tip btn btn-primary btn-xs\" title='".$this->lang->line("view_quote")."' href='#' onClick=\"MyWindow=window.open('".site_url('sales/view_quote/')."?id=$1', 'MyWindow','toolbar=yes,location=no,directories=no,status=no,menubar=yes,scrollbars=yes,resizable=yes,width=1000,height=600'); return false;\"><i class=\"fa fa-file-text-o\"></i></a>
		<a class=\"tip  btn btn-success btn-xs\" title='".$this->lang->line("quote_to_invoice")."' href='".site_url('sales/convert/')."?id=$1'><i class=\"fa fa-share\"></i></a> 
		<a class=\"tip  btn btn-primary btn-xs\" title='".$this->lang->line("download_pdf")."' href='".site_url('sales/pdf_quote/')."?id=$1'><i class=\"fa fa-download\"></i></a> 
		<a class=\"tip email_inv btn btn-success btn-xs\" title='".$this->lang->line("email_quote")."' href='#' id='$1' data-customer='$2' data-company='$3'><i class=\"fa fa-envelope\"></i></a>
		<a class=\"tip  btn btn-warning btn-xs\" title='".$this->lang->line("edit_quote")."' href='".site_url('sales/edit_quote/')."?id=$1'><i class=\"fa fa-edit\"></i></a>
		<a class=\"tip  btn btn-danger btn-xs\" title='".$this->lang->line("delete_quote")."' href='".site_url('sales/delete_quote/')."?id=$1' onClick=\"return confirm('". $this->lang->line('alert_x_quote') ."')\"><i class=\"fa fa-trash-o\"></i></a></div></center>", "id, cid, bid")

            ->unset_column('cid')
            ->unset_column('bid');

        echo $this->datatables->generate();

    }

    function getCE() {

        if($this->input->get('cid')){ $cid = $this->input->get('cid'); } else { $cid = NULL; die(); }
        if($this->input->get('bid')){ $bid = $this->input->get('bid'); } else { $bid = NULL; die(); }

        $cus = $this->sales_model->getCustomerByID($cid);
        $com = $this->sales_model->getCompanyByID($bid);

        echo json_encode(array('ce' => $cus->email, 'com' => ($com->company && $com->company != '-' ? $com->company : $com->name)));


    }

    function send_email() {
        if($this->input->post('id')){ $id = $this->input->post('id'); } else { $id = NULL; die(); }
        if($this->input->post('to')){ $to = $this->input->post('to'); } else { $to = NULL; die(); }
        if($this->input->post('subject')){ $subject = $this->input->post('subject'); } else { $subject = NULL; }
        if($this->input->post('note')){ $message = $this->input->post('note'); } else { $message = NULL; }
        if($this->input->post('cc')){ $cc = $this->input->post('cc'); } else { $cc = NULL; }
        if($this->input->post('bcc')){ $bcc = $this->input->post('bcc'); } else { $bcc = NULL; }


        $user = $this->site->getUser();
        $from_name = $user->first_name." ".$user->last_name;
        $from = $user->email;


        if ( $this->email($id, $to, $from_name, $from, $subject, $message, $cc, $bcc) )
        {
            echo $this->lang->line("sent");
        } else {
            echo $this->lang->line("x_sent");
        }

    }

    function send_quote() {
        if($this->input->post('id')){ $id = $this->input->post('id'); } else { $id = NULL; die(); }
        if($this->input->post('to')){ $to = $this->input->post('to'); } else { $to = NULL; die(); }
        if($this->input->post('subject')){ $subject = $this->input->post('subject'); } else { $subject = NULL; }
        if($this->input->post('note')){ $message = $this->input->post('note'); } else { $message = NULL; }
        if($this->input->post('cc')){ $cc = $this->input->post('cc'); } else { $cc = NULL; }
        if($this->input->post('bcc')){ $bcc = $this->input->post('bcc'); } else { $bcc = NULL; }

        $user = $this->site->getUser();
        $from_name = $user->first_name." ".$user->last_name;
        $from = $user->email;


        if ( $this->emailQ($id, $to, $from_name, $from, $subject, $message, $cc, $bcc) )
        {
            echo $this->lang->line("sent");
        } else {
            echo $this->lang->line("x_sent");
        }

    }

    /* ---------------------------------------------------------------------------------------------- */

    function view_invoice()
    {
        if($this->input->get('id')){ $sale_id = $this->input->get('id'); } else { $sale_id = NULL; }

        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $this->data['rows'] = $this->sales_model->getAllInvoiceItems($sale_id);

        $inv = $this->sales_model->getInvoiceBySaleID($sale_id);
        $customer_id = $inv->customer_id;
        $bc = $inv->company ? $inv->company : 1;
        $this->data['biller'] = $this->sales_model->getCompanyByID($bc);
        $this->data['customer'] = $this->sales_model->getCustomerByID($customer_id);
        $this->data['payment'] = $this->sales_model->getPaymentBySaleID($sale_id);
        $this->data['paid'] = $this->sales_model->getPaidAmount($sale_id);
        $this->data['inv'] = $inv;
        $this->data['sid'] = $sale_id;

        $this->data['page_title'] = $this->lang->line("invoice");

        $this->load->view($this->theme.'sales/view_invoice', $this->data);

    }

    function view_payments()
    {
        if($this->input->get('id')){ $sale_id = $this->input->get('id'); } else { $sale_id = NULL; }

        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $this->data['rows'] = $this->sales_model->getAllInvoiceItems($sale_id);
        $inv = $this->sales_model->getInvoiceBySaleID($sale_id);
        $customer_id = $inv->customer_id;
        $bc = $inv->company ? $inv->company : 1;
        $this->data['biller'] = $this->sales_model->getCompanyByID($bc);
        $this->data['customer'] = $this->sales_model->getCustomerByID($customer_id);
        $this->data['payment'] = $this->sales_model->getPaymentBySaleID($sale_id);
        $this->data['paid'] = $this->sales_model->getPaidAmount($sale_id);
        $this->data['inv'] = $inv;
        $this->data['sid'] = $sale_id;

        $this->data['page_title'] = $this->lang->line("payments");

        $this->load->view($this->theme.'sales/view_payments', $this->data);

    }

    function payment_note()
    {
        if($this->input->get('id')){ $id = $this->input->get('id'); } else { $id = NULL; }

        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));

        $payment = $this->sales_model->getPaymentByID($id);
        $inv = $this->sales_model->getInvoiceBySaleID($payment->invoice_id);
        $customer_id = $inv->customer_id;
        $bc = $inv->company ? $inv->company : 1;
        $this->data['biller'] = $this->sales_model->getCompanyByID($bc);
        $this->data['customer'] = $this->sales_model->getCustomerByID($customer_id);
        $this->data['inv'] = $inv;
        $this->data['payment'] = $payment;
        $this->data['page_title'] = $this->lang->line("payment_note");

        $this->load->view($this->theme.'sales/payment_note', $this->data);

    }

    function view_quote()
    {
        if($this->input->get('id')){ $quote_id = $this->input->get('id'); } else { $quote_id = NULL; }

        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $this->data['rows'] = $this->sales_model->getAllQuoteItems($quote_id);

        $inv = $this->sales_model->getQuoteByID($quote_id);
        $customer_id = $inv->customer_id;
        $bc = $inv->company ? $inv->company : 1;
        $this->data['biller'] = $this->sales_model->getCompanyByID($bc);
        $this->data['customer'] = $this->sales_model->getCustomerByID($customer_id);

        $this->data['inv'] = $inv;
        $this->data['sid'] = $quote_id;

        $this->data['page_title'] = $this->lang->line("invoice");


        $this->load->view($this->theme.'sales/view_quote', $this->data);

    }
    /* ------------------------------------------------------------------------------------------------- */


    function add()
    {

        $this->form_validation->set_message('is_natural_no_zero', $this->lang->line("no_zero_required"));
        $this->form_validation->set_rules('status', $this->lang->line("status"), 'required');
        $this->form_validation->set_rules('reference_no', $this->lang->line("reference_no"), 'required');
        $this->form_validation->set_rules('date', $this->lang->line("date"), 'required');
        $this->form_validation->set_rules('customer', $this->lang->line("customer"), 'required');
        $this->form_validation->set_rules('quantity1', $this->lang->line("quantity")." 1", 'required|numeric');
        $this->form_validation->set_rules('product1', $this->lang->line("product").' 1', 'required');
        $this->form_validation->set_rules('unit_price1', $this->lang->line("unit_price").' 1', 'required');
        if($this->input->post('customer') == 'new') {
            $this->form_validation->set_rules('name', $this->lang->line("customer")." ".$this->lang->line("name"), 'required');
            $this->form_validation->set_rules('email', $this->lang->line("customer")." ".$this->lang->line("email_address"), 'required|valid_email|is_unique[customers.email]');
            $this->form_validation->set_rules('phone', $this->lang->line("phone"), 'required|min_length[6]|max_length[16]');
        }

        $quantity = "quantity";
        $product = "product";
        $unit_price = "unit_price";
        $tax_rate = "tax_rate";

        if ($this->form_validation->run() == true)
        {
            $date = $this->sim->fsd($this->input->post('date'));
            $due_date = $this->input->post('due_date')? $this->sim->fsd($this->input->post('due_date')) : NULL;

            $reference_no = $this->input->post('reference_no');
            $billing_company = $this->input->post('billing_company');
            $status = $this->input->post('status');
            $shipping = $this->input->post('shipping');
            $recurring = $this->input->post('recurring');
            $discount = $this->input->post('discount') ? $this->input->post('discount') : 0;

            if($this->input->post('customer') == 'new') {

                $customer_name = $this->input->post('company') ? $this->input->post('company') : $this->input->post('name');
                $customer_data = array(
                    'name' => $this->input->post('name'),
                    'email' => $this->input->post('email'),
                    'phone' => $this->input->post('phone'),
                    'company' => $this->input->post('company'),
                    'address' => $this->input->post('address'),
                    'city' => $this->input->post('city'),
                    'postal_code' => $this->input->post('postal_code'),
                    'state' => $this->input->post('state'),
                    'country' => $this->input->post('country')
                );

            } else {
                $customer_id = $this->input->post('customer');
                $customer_details = $this->sales_model->getCustomerByID($customer_id);
                $customer_name = $customer_details->company ? $customer_details->company : $customer_details->name;
            }
            $note = $this->input->post('note');

            $inv_total_no_tax = 0;

            for($i=1; $i<=$this->Settings->total_rows; $i++){
                if( $this->input->post($quantity.$i) && $this->input->post($product.$i) && $this->input->post($unit_price.$i) ) {

                    $inv_quantity[] = $this->input->post($quantity.$i);
                    $inv_product_name[] = $this->input->post($product.$i);
                    $inv_unit_price[] = $this->input->post($unit_price.$i);
                    $inv_gross_total[] = $this->sim->formatDecimal(($this->input->post($quantity.$i)) * ($this->input->post($unit_price.$i)));

                    if($this->Settings->default_tax_rate) {
                        $tax_id = $this->input->post($tax_rate.$i);
                        $tax_details = $this->sales_model->getTaxRateByID($tax_id);
                        $taxRate = $tax_details->rate;
                        $taxType = $tax_details->type;
                        $tax_rate_id[] = $tax_id;

                        if($taxType == 1 && $taxType != 0) {
                            $val_tax[] = $this->sim->formatDecimal(($this->input->post($quantity.$i)) * ($this->input->post($unit_price.$i)) * $taxRate / 100);
                        } else {
                            $val_tax[] = $this->sim->formatDecimal($taxRate);
                        }

                        if($taxType == 1) { $tax[] = $taxRate."%"; } else { $tax[] = $taxRate;  }
                    } else {
                        $tax_rate_id[] = 0;
                        $val_tax[] = 0;
                        $tax[] = 0;
                    }

                    $inv_total_no_tax += $this->sim->formatDecimal(($this->input->post($quantity.$i)) * ($this->input->post($unit_price.$i)));

                }
            }

            $total_tax = array_sum($val_tax);
            $total = $this->sim->formatDecimal($inv_total_no_tax + $total_tax);
            $percentage = '%';
            $dpos = strpos($discount, $percentage);
            if ($dpos !== false) {
                $pds = explode("%", $discount);
                $total_discount = $this->sim->formatDecimal(($total* (Float)($pds[0]))/100);
            } else {
                $total_discount = $this->sim->formatDecimal($discount);
            }
            $total_discount = $this->roundnum($total_discount);

            $keys = array("product_name","tax_rate_id", "tax","quantity","unit_price", "gross_total", "val_tax");

            $items = array();
            foreach ( array_map(null, $inv_product_name, $tax_rate_id, $tax, $inv_quantity, $inv_unit_price, $inv_gross_total, $val_tax) as $key => $value ) {
                $items[] = array_combine($keys, $value);
            }
            if($this->input->post('customer') == 'new') {
                $saleDetails = array('reference_no' => $reference_no,
                    'company' => $billing_company,
                    'date' => $date,
                    'due_date' => $due_date,
                    'recurring' => $recurring,
                    'user' => $this->session->userdata('user_id'),
                    'customer_name' => $customer_name,
                    'note' => $note,
                    'inv_total' => $this->sim->formatDecimal($inv_total_no_tax),
                    'total_tax' => $this->sim->formatDecimal($total_tax),
                    'total' => $this->sim->formatDecimal($total - $total_discount),
                    'status' => $status,
                    'shipping' => $this->sim->formatDecimal($shipping),
                    'total_discount' => $this->sim->formatDecimal($total_discount),
                    'discount' => $discount,
                );
            } else {
                $saleDetails = array('reference_no' => $reference_no,
                    'company' => $billing_company,
                    'date' => $date,
                    'due_date' => $due_date,
                    'recurring' => $recurring,
                    'user' => $this->session->userdata('user_id'),
                    'customer_id' => $customer_id,
                    'customer_name' => $customer_name,
                    'note' => $note,
                    'inv_total' => $this->sim->formatDecimal($inv_total_no_tax),
                    'total_tax' => $this->sim->formatDecimal($total_tax),
                    'total' => $this->sim->formatDecimal($total - $total_discount),
                    'status' => $status,
                    'shipping' => $this->sim->formatDecimal($shipping),
                    'total_discount' => $this->sim->formatDecimal($total_discount),
                    'discount' => $discount,
                );
                $customer_data = array();
            }
            //echo '<pre />'; print_r($saleDetails); die();

        }


        if ( $this->form_validation->run() == true && $this->sales_model->addSale($saleDetails, $items, $customer_data) )
        {
            $this->session->set_flashdata('message', $this->lang->line("sale_added"));
            redirect("sales");

        }
        else
        {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));

            $this->data['reference_no'] = array('name' => 'reference_no',
                'id' => 'reference_no',
                'type' => 'text',
                'value' => $this->form_validation->set_value('reference_no'),
            );
            $this->data['date'] = array('name' => 'date',
                'id' => 'date',
                'type' => 'text',
                'value' => $this->form_validation->set_value('date'),
            );

            $this->data['customer'] = array('name' => 'customer',
                'id' => 'customer',
                'type' => 'select',
                'value' => $this->form_validation->set_select('customer'),
            );
            $this->data['note'] = array('name' => 'note',
                'id' => 'note',
                'type' => 'textarea',
                'value' => $this->form_validation->set_value('note'),
            );


            $this->data['customers'] = $this->sales_model->getAllCustomers();
            $this->data['products'] = $this->sales_model->getAllProducts();
            $this->data['tax_rates'] = $this->sales_model->getAllTaxRates();
            $this->data['companies'] = $this->sales_model->getAllCompanies();
            $this->data['page_title'] = $this->lang->line("add_sale");
            $this->page_construct('sales/add', $this->data);

        }
    }

//Add new quote

    function add_quote()
    {

        $this->form_validation->set_message('is_natural_no_zero', $this->lang->line("no_zero_required"));
        $this->form_validation->set_rules('reference_no', $this->lang->line("reference_no"), 'required');
        $this->form_validation->set_rules('date', $this->lang->line("date"), 'required');
        $this->form_validation->set_rules('customer', $this->lang->line("customer"), 'required');
        $this->form_validation->set_rules('quantity1', $this->lang->line("quantity")." 1", 'required|numeric');
        $this->form_validation->set_rules('product1', $this->lang->line("product").' 1', 'required');
        $this->form_validation->set_rules('unit_price1', $this->lang->line("unit_price").' 1', 'required');
        if($this->input->post('customer') == 'new') {
            $this->form_validation->set_rules('name', $this->lang->line("customer")." ".$this->lang->line("name"), 'required');
            $this->form_validation->set_rules('email', $this->lang->line("customer")." ".$this->lang->line("email_address"), 'required|valid_email|is_unique[customers.email]');
            $this->form_validation->set_rules('phone', $this->lang->line("phone"), 'required|min_length[6]|max_length[16]');
        }

        $quantity = "quantity";
        $product = "product";
        $unit_price = "unit_price";
        $tax_rate = "tax_rate";

        if ($this->form_validation->run() == true)
        {
            $date = $this->sim->fsd($this->input->post('date'));
            $exp_date = $this->input->post('expiry_date') ? $this->sim->fsd($this->input->post('expiry_date')) : NULL;


            $reference_no = $this->input->post('reference_no');
            $billing_company = $this->input->post('billing_company');

            if($this->input->post('customer') == 'new') {

                $customer_name = $this->input->post('company') ? $this->input->post('company') : $this->input->post('name');
                $customer_data = array(
                    'name' => $this->input->post('name'),
                    'email' => $this->input->post('email'),
                    'phone' => $this->input->post('phone'),
                    'company' => $this->input->post('company'),
                    'address' => $this->input->post('address'),
                    'city' => $this->input->post('city'),
                    'postal_code' => $this->input->post('postal_code'),
                    'state' => $this->input->post('state'),
                    'country' => $this->input->post('country')
                );

            } else {
                $customer_id = $this->input->post('customer');
                $customer_details = $this->sales_model->getCustomerByID($customer_id);
                $customer_name = $customer_details->company ? $customer_details->company : $customer_details->name;
            }
            $note = $this->input->post('note');
            $shipping = $this->input->post('shipping');
            $discount = $this->input->post('discount') ? $this->input->post('discount') : 0;
            $inv_total_no_tax = 0;

            for($i=1; $i<=$this->Settings->total_rows; $i++){
                if( $this->input->post($quantity.$i) && $this->input->post($product.$i) && $this->input->post($unit_price.$i) ) {

                    $inv_quantity[] = $this->input->post($quantity.$i);
                    $inv_product_name[] = $this->input->post($product.$i);
                    $inv_unit_price[] = $this->input->post($unit_price.$i);
                    $inv_gross_total[] = $this->sim->formatDecimal(($this->input->post($quantity.$i)) * ($this->input->post($unit_price.$i)));

                    if($this->Settings->default_tax_rate) {
                        $tax_id = $this->input->post($tax_rate.$i);
                        $tax_details = $this->sales_model->getTaxRateByID($tax_id);
                        $taxRate = $tax_details->rate;
                        $taxType = $tax_details->type;
                        $tax_rate_id[] = $tax_id;

                        if($taxType == 1 && $taxType != 0) {
                            $val_tax[] = $this->sim->formatDecimal(($this->input->post($quantity.$i)) * ($this->input->post($unit_price.$i)) * $taxRate / 100);
                        } else {
                            $val_tax[] = $this->sim->formatDecimal($taxRate);
                        }

                        if($taxType == 1) { $tax[] = $taxRate."%"; } else { $tax[] = $taxRate;  }
                    } else {
                        $tax_rate_id[] = 0;
                        $val_tax[] = 0;
                        $tax[] = 0;
                    }

                    $inv_total_no_tax += $this->sim->formatDecimal(($this->input->post($quantity.$i)) * ($this->input->post($unit_price.$i)));

                }
            }

            $total_tax = array_sum($val_tax);
            $total = $this->sim->formatDecimal($inv_total_no_tax + $total_tax);
            $percentage = '%';
            $dpos = strpos($discount, $percentage);
            if ($dpos !== false) {
                $pds = explode("%", $discount);
                $total_discount = $this->sim->formatDecimal(($total* (Float)($pds[0]))/100);
            } else {
                $total_discount = $this->sim->formatDecimal($discount);
            }
            $total_discount = $this->roundnum($total_discount);

            $keys = array("product_name","tax_rate_id", "tax","quantity","unit_price", "gross_total", "val_tax");

            $items = array();
            foreach ( array_map(null, $inv_product_name, $tax_rate_id, $tax, $inv_quantity, $inv_unit_price, $inv_gross_total, $val_tax) as $key => $value ) {
                $items[] = array_combine($keys, $value);
            }

            if($this->input->post('customer') == 'new') {
                $quoteDetails = array('reference_no' => $reference_no,
                    'company' => $billing_company,
                    'date' => $date,
                    'expiry_date' => $exp_date,
                    'user' => $this->session->userdata('user_id'),
                    'customer_name' => $customer_name,
                    'note' => $note,
                    'inv_total' => $this->sim->formatDecimal($inv_total_no_tax),
                    'total_tax' => $this->sim->formatDecimal($total_tax),
                    'total' => $this->sim->formatDecimal($total - $total_discount),
                    'shipping' => $this->sim->formatDecimal($shipping),
                    'total_discount' => $this->sim->formatDecimal($total_discount),
                    'discount' => $discount,
                    'status' => $this->input->post('status')
                );
            } else {
                $quoteDetails = array('reference_no' => $reference_no,
                    'company' => $billing_company,
                    'date' => $date,
                    'expiry_date' => $exp_date,
                    'user' => $this->session->userdata('user_id'),
                    'customer_id' => $customer_id,
                    'customer_name' => $customer_name,
                    'note' => $note,
                    'inv_total' => $this->sim->formatDecimal($inv_total_no_tax),
                    'total_tax' => $this->sim->formatDecimal($total_tax),
                    'total' => $this->sim->formatDecimal($total - $total_discount),
                    'shipping' => $this->sim->formatDecimal($shipping),
                    'total_discount' => $this->sim->formatDecimal($total_discount),
                    'discount' => $discount,
                    'status' => $this->input->post('status')
                );
                $customer_data = array();
            }
            // $this->sim->print_arrays($quoteDetails, $items);
        }


        if ( $this->form_validation->run() == true && $this->sales_model->addQuote($quoteDetails, $items, $customer_data) )
        {
            $this->session->set_flashdata('message', $this->lang->line("quote_added"));
            redirect("sales/quotes");

        }
        else
        {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));

            $this->data['reference_no'] = array('name' => 'reference_no',
                'id' => 'reference_no',
                'type' => 'text',
                'value' => $this->form_validation->set_value('reference_no'),
            );
            $this->data['date'] = array('name' => 'date',
                'id' => 'date',
                'type' => 'text',
                'value' => $this->form_validation->set_value('date'),
            );

            $this->data['customer'] = array('name' => 'customer',
                'id' => 'customer',
                'type' => 'select',
                'value' => $this->form_validation->set_select('customer'),
            );
            $this->data['note'] = array('name' => 'note',
                'id' => 'note',
                'type' => 'textarea',
                'value' => $this->form_validation->set_value('note'),
            );

            $this->data['customers'] = $this->sales_model->getAllCustomers();
            $this->data['tax_rates'] = $this->sales_model->getAllTaxRates();
            $this->data['products'] = $this->sales_model->getAllProducts();
            $this->data['companies'] = $this->sales_model->getAllCompanies();
            $this->data['page_title'] = $this->lang->line("new_quote");
            $this->page_construct('sales/add_quote', $this->data);

        }
    }

    //Add new quote

    function convert()
    {

        if($this->input->get('id')) { $id = $this->input->get('id'); } else { $id = NULL; }
        $inv = $this->sales_model->getQuoteByID($id);
        $this->sim->view_rights($inv->user_id);

        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));

        $this->data['customers'] = $this->sales_model->getAllCustomers();
        $this->data['products'] = $this->sales_model->getAllProducts();
        $this->data['tax_rates'] = $this->sales_model->getAllTaxRates();
        $this->data['inv'] = $inv;
        $this->data['inv_products'] =  $this->sales_model->getAllQuoteItems($id);
        $this->data['page_title'] = $this->lang->line("quote_to_invoice");
        $this->data['companies'] = $this->sales_model->getAllCompanies();
        $this->data['id'] = $id;

        $this->page_construct('sales/convert', $this->data);

    }

    /* -------------------------------------------------------------------------------------------------------------------------------- */
//Edit sale

    function edit()
    {
        if($this->input->get('id')) { $id = $this->input->get('id'); } else { $id = NULL; }
        $inv = $this->sales_model->getInvoiceByID($id);
        $this->sim->view_rights($inv->user_id);

        $this->form_validation->set_rules('reference_no', $this->lang->line("reference_no"), 'required');
        $this->form_validation->set_rules('status', $this->lang->line("status"), 'required');
        $this->form_validation->set_rules('date', $this->lang->line("date"), 'required');
        $this->form_validation->set_rules('customer', $this->lang->line("customer"), 'required');
        $this->form_validation->set_rules('quantity1', $this->lang->line("quantity").' 1', 'required|numeric');
        $this->form_validation->set_rules('product1', $this->lang->line("products").' 1', 'required');
        $this->form_validation->set_rules('unit_price1', $this->lang->line("unit_price").' 1', 'required');

        $quantity = "quantity";
        $product = "product";
        $unit_price = "unit_price";
        $tax_rate = "tax_rate";

        if ($this->form_validation->run() == true)
        {
            $date = $this->sim->fsd($this->input->post('date'));
            $due_date = $this->input->post('due_date') ? $this->sim->fsd($this->input->post('due_date')) : NULL;

            $reference_no = $this->input->post('reference_no');
            $recurring = $this->input->post('recurring');
            $billing_company = $this->input->post('billing_company');
            $status = $this->input->post('status');

            $customer_id = $this->input->post('customer');
            $customer_details = $this->sales_model->getCustomerByID($customer_id);
            $customer_name = $customer_details->company ? $customer_details->company : $customer_details->name;

            $note = $this->input->post('note');
            $shipping = $this->input->post('shipping');
            $discount = $this->input->post('discount') ? $this->input->post('discount') : 0;

            $inv_total_no_tax = 0;

            for($i=1; $i<=$this->Settings->total_rows; $i++){
                if( $this->input->post($quantity.$i) && $this->input->post($product.$i) && $this->input->post($unit_price.$i) ) {

                    $inv_quantity[] = $this->input->post($quantity.$i);
                    $inv_product_name[] = $this->input->post($product.$i);
                    $inv_unit_price[] = $this->input->post($unit_price.$i);
                    $inv_gross_total[] = $this->sim->formatDecimal(($this->input->post($quantity.$i)) * ($this->input->post($unit_price.$i)));
                    $sid[] = $id;
                    if($this->Settings->default_tax_rate) {
                        $tax_id = $this->input->post($tax_rate.$i);
                        $tax_details = $this->sales_model->getTaxRateByID($tax_id);
                        $taxRate = $tax_details->rate;
                        $taxType = $tax_details->type;
                        $tax_rate_id[] = $tax_id;

                        if($taxType == 1 && $taxType != 0) {
                            $val_tax[] = $this->sim->formatDecimal(($this->input->post($quantity.$i)) * ($this->input->post($unit_price.$i)) * $taxRate / 100);
                        } else {
                            $val_tax[] = $this->sim->formatDecimal($taxRate);
                        }

                        if($taxType == 1) { $tax[] = $taxRate."%"; } else { $tax[] = $taxRate;  }
                    } else {
                        $tax_rate_id[] = 0;
                        $val_tax[] = 0;
                        $tax[] = 0;
                    }

                    $inv_total_no_tax += $this->sim->formatDecimal(($this->input->post($quantity.$i)) * ($this->input->post($unit_price.$i)));

                }
            }

            $total_tax = array_sum($val_tax);
            $total = $this->sim->formatDecimal($inv_total_no_tax + $total_tax);

            $percentage = '%';
            $dpos = strpos($discount, $percentage);
            if ($dpos !== false) {
                $pds = explode("%", $discount);
                $total_discount = $this->sim->formatDecimal(($total* (Float)($pds[0]))/100);
            } else {
                $total_discount = $this->sim->formatDecimal($discount);
            }
            $total_discount = $this->roundnum($total_discount);

            $keys = array("sale_id", "product_name","tax_rate_id", "tax","quantity","unit_price", "gross_total", "val_tax");

            $items = array();
            foreach ( array_map(null, $sid, $inv_product_name, $tax_rate_id, $tax, $inv_quantity, $inv_unit_price, $inv_gross_total, $val_tax) as $key => $value ) {
                $items[] = array_combine($keys, $value);
            }

            $saleDetails = array('reference_no' => $reference_no,
                'company' => $billing_company,
                'date' => $date,
                'due_date' => $due_date,
                'recurring' => $recurring,
                'user' => $this->session->userdata('user_id'),
                'customer_id' => $customer_id,
                'customer_name' => $customer_name,
                'note' => $note,
                'inv_total' => $this->sim->formatDecimal($inv_total_no_tax),
                'total_tax' => $this->sim->formatDecimal($total_tax),
                'total' => $this->sim->formatDecimal($total - $total_discount),
                'status' => $status,
                'shipping' => $this->sim->formatDecimal($shipping),
                'total_discount' => $this->sim->formatDecimal($total_discount),
                'discount' => $discount,
            );
            $customer_data = array();

            // echo '<pre />'; print_r($saleDetails); print_r($items); die();
        }

        if ( $this->form_validation->run() == true && $this->sales_model->updateSale($id, $saleDetails, $items) )
        {
            $this->session->set_flashdata('message', $this->lang->line("sale_updated"));
            redirect("sales");

        } else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));

            $this->data['customers'] = $this->sales_model->getAllCustomers();

            $this->data['tax_rates'] = $this->sales_model->getAllTaxRates();
            $this->data['products'] = $this->sales_model->getAllProducts();

            $this->data['inv'] = $inv;
            $this->data['inv_products'] =  $this->sales_model->getAllInvoiceItems($id);
            $this->data['id'] = $id;
            $this->data['page_title'] = $this->lang->line("update_sale");
            $this->data['companies'] = $this->sales_model->getAllCompanies();
            $this->page_construct('sales/edit', $this->data);

        }
    }

    //Edit quote

    function edit_quote()
    {
        if($this->input->get('id')) { $id = $this->input->get('id'); } else { $id = NULL; }
        $inv = $this->sales_model->getQuoteByID($id);
        $this->sim->view_rights($inv->user_id);

        $this->form_validation->set_rules('reference_no', $this->lang->line("reference_no"), 'required');
        $this->form_validation->set_rules('date', $this->lang->line("date"), 'required');
        $this->form_validation->set_rules('customer', $this->lang->line("customer"), 'required');
        $this->form_validation->set_rules('quantity1', $this->lang->line("quantity").' 1', 'required|numeric');
        $this->form_validation->set_rules('product1', $this->lang->line("products").' 1', 'required');
        $this->form_validation->set_rules('unit_price1', $this->lang->line("unit_price").' 1', 'required');
        $quantity = "quantity";
        $product = "product";
        $unit_price = "unit_price";
        $tax_rate = "tax_rate";

        if ($this->form_validation->run() == true)
        {
            $date = $this->sim->fsd($this->input->post('date'));
            $exp_date = $this->input->post('expiry_date') ? $this->sim->fsd($this->input->post('expiry_date')) : NULL;

            $reference_no = $this->input->post('reference_no');
            $billing_company = $this->input->post('billing_company');

            $customer_id = $this->input->post('customer');
            $customer_details = $this->sales_model->getCustomerByID($customer_id);
            $customer_name = $customer_details->company ? $customer_details->company : $customer_details->name;

            $note = $this->input->post('note');
            $shipping = $this->input->post('shipping');
            $discount = $this->input->post('discount') ? $this->input->post('discount') : 0;
            $inv_total_no_tax = 0;

            for($i=1; $i<=$this->Settings->total_rows; $i++){
                if( $this->input->post($quantity.$i) && $this->input->post($product.$i) && $this->input->post($unit_price.$i) ) {

                    $inv_quantity[] = $this->input->post($quantity.$i);
                    $inv_product_name[] = $this->input->post($product.$i);
                    $inv_unit_price[] = $this->input->post($unit_price.$i);
                    $inv_gross_total[] = $this->sim->formatDecimal(($this->input->post($quantity.$i)) * ($this->input->post($unit_price.$i)));
                    $qid[] = $id;
                    if($this->Settings->default_tax_rate) {
                        $tax_id = $this->input->post($tax_rate.$i);
                        $tax_details = $this->sales_model->getTaxRateByID($tax_id);
                        $taxRate = $tax_details->rate;
                        $taxType = $tax_details->type;
                        $tax_rate_id[] = $tax_id;

                        if($taxType == 1 && $taxType != 0) {
                            $val_tax[] = $this->sim->formatDecimal(($this->input->post($quantity.$i)) * ($this->input->post($unit_price.$i)) * $taxRate / 100);
                        } else {
                            $val_tax[] = $this->sim->formatDecimal($taxRate);
                        }

                        if($taxType == 1) { $tax[] = $taxRate."%"; } else { $tax[] = $taxRate;  }
                    } else {
                        $tax_rate_id[] = 0;
                        $val_tax[] = 0;
                        $tax[] = 0;
                    }

                    $inv_total_no_tax += $this->sim->formatDecimal(($this->input->post($quantity.$i)) * ($this->input->post($unit_price.$i)));

                }
            }

            $total_tax = array_sum($val_tax);
            $total = $this->sim->formatDecimal($inv_total_no_tax + $total_tax);
            $percentage = '%';
            $dpos = strpos($discount, $percentage);
            if ($dpos !== false) {
                $pds = explode("%", $discount);
                $total_discount = $this->sim->formatDecimal(($total* (Float)($pds[0]))/100);
            } else {
                $total_discount = $this->sim->formatDecimal($discount);
            }
            $total_discount = $this->roundnum($total_discount);

            $keys = array("quote_id", "product_name","tax_rate_id", "tax","quantity","unit_price", "gross_total", "val_tax");

            $items = array();
            foreach ( array_map(null, $qid, $inv_product_name, $tax_rate_id, $tax, $inv_quantity, $inv_unit_price, $inv_gross_total, $val_tax) as $key => $value ) {
                $items[] = array_combine($keys, $value);
            }

            $quoteDetails = array('reference_no' => $reference_no,
                'company' => $billing_company,
                'date' => $date,
                'expiry_date' => $exp_date,
                'user' => $this->session->userdata('user_id'),
                'customer_id' => $customer_id,
                'customer_name' => $customer_name,
                'note' => $note,
                'inv_total' => $this->sim->formatDecimal($inv_total_no_tax),
                'total_tax' => $this->sim->formatDecimal($total_tax),
                'total' => $this->sim->formatDecimal($total - $total_discount),
                'shipping' => $this->sim->formatDecimal($shipping),
                'total_discount' => $this->sim->formatDecimal($total_discount),
                'discount' => $discount,
                'status' => $this->input->post('status')
            );

        }

        if ( $this->form_validation->run() == true && $this->sales_model->updateQuote($id, $quoteDetails, $items) ) {

            $this->session->set_flashdata('message', $this->lang->line("quote_updated"));
            redirect("sales/quotes");

        } else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));

            $this->data['customers'] = $this->sales_model->getAllCustomers();
            $this->data['products'] = $this->sales_model->getAllProducts();
            $this->data['tax_rates'] = $this->sales_model->getAllTaxRates();


            $this->data['inv'] = $this->sales_model->getQuoteByID($id);
            $this->data['inv_products'] =  $this->sales_model->getAllQuoteItems($id);
            $this->data['id'] = $id;
            $meta['page_title'] = $this->lang->line("update_quote");
            $this->data['page_title'] = $this->lang->line("update_quote");
            $this->data['companies'] = $this->sales_model->getAllCompanies();
            $this->page_construct('sales/edit_quote', $this->data);

        }
    }
    /*-------------------------------*/
    function delete($id = NULL)
    {
        if (DEMO) {
            $this->session->set_flashdata('error', $this->lang->line("disabled_in_demo"));
            redirect('home');
        }

        if($this->input->get('id')){ $id = $this->input->get('id'); } else { $id = NULL; }

        if (!$this->sim->in_group('admin')) {
            $this->session->set_flashdata('error', $this->lang->line("access_denied"));
            redirect('sales');
        }

        if ( $this->sales_model->deleteInvoice($id) ) {
            $this->session->set_flashdata('message', $this->lang->line("invoice_deleted"));
            redirect('sales');
        }

    }

    function delete_quote($id = NULL)
    {
        if (DEMO) {
            $this->session->set_flashdata('error', $this->lang->line("disabled_in_demo"));
            redirect('home');
        }

        if($this->input->get('id')){ $id = $this->input->get('id'); } else { $id = NULL; }

        if (!$this->sim->in_group('admin')) {
            $this->session->set_flashdata('error', $this->lang->line("access_denied"));
            redirect('sales');
        }

        if ( $this->sales_model->deleteQuote($id) ) {
            $this->session->set_flashdata('message', $this->lang->line("quote_deleted"));
            redirect('sales/quotes');
        }

    }
    /* -------------------------------------------------------------------------------------------------------------------------------- */
//generate pdf and force to download  

    function pdf($sale_id = NULL, $save_bufffer = NULL) {
        if($this->input->get('id')){ $sale_id = $this->input->get('id'); }

        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $this->data['rows'] = $this->sales_model->getAllInvoiceItems($sale_id);

        $inv = $this->sales_model->getInvoiceBySaleID($sale_id);
        $customer_id = $inv->customer_id;
        $bc = $inv->company ? $inv->company : 1;
        $this->data['biller'] = $this->sales_model->getCompanyByID($bc);
        $this->data['customer'] = $this->sales_model->getCustomerByID($customer_id);
        $this->data['payment'] = $this->sales_model->getPaymentBySaleID($sale_id);
        $this->data['paid'] = $this->sales_model->getPaidAmount($sale_id);
        $this->data['inv'] = $inv;
        $this->data['sid'] = $sale_id;

        $this->data['page_title'] = $this->lang->line("invoice");

        $html =  $this->load->view($this->theme.'sales/view_invoice', $this->data, TRUE);
        $name = $this->lang->line("invoice")." ".$this->lang->line("no")." ".$inv->id.".pdf";

        $search = array("<div id=\"wrap\">", "<div class=\"row-fluid\">", "<div class=\"span6\">", "<div class=\"span2\">", "<div class=\"span10\">", "<div class=\"span4\">", "<div class=\"span4 offset3\">", "<div class=\"span4 pull-left\">", "<div class=\"span4 pull-right\">");
        $replace = array("<div style='padding:0;'>", "<div style='width: 100%;'>", "<div style='width: 48%; float: left;'>", "<div style='width: 18%; float: left;'>", "<div style='width: 78%; float: left;'>", "<div style='width: 40%; float: left;'>", "<div style='width: 40%; float: right;'>", "<div style='width: 40%; float: left;'>", "<div style='width: 40%; float: right;'>");

        $html = str_replace($search, $replace, $html);

        if($save_bufffer) {
            return $this->sim->generate_pdf($html, $name, $save_bufffer);
        } else {
            $this->sim->generate_pdf($html, $name);
        }

    }

    function pdf_quote($quote_id = NULL, $save_bufffer = NULL)
    {
        if($this->input->get('id')){ $quote_id = $this->input->get('id'); }

        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $this->data['rows'] = $this->sales_model->getAllQuoteItems($quote_id);

        $inv = $this->sales_model->getQuoteByID($quote_id);
        $customer_id = $inv->customer_id;
        $bc = $inv->company ? $inv->company : 1;
        $this->data['biller'] = $this->sales_model->getCompanyByID($bc);
        $this->data['customer'] = $this->sales_model->getCustomerByID($customer_id);

        $this->data['inv'] = $inv;

        $this->data['page_title'] = $this->lang->line("quote");


        $html =  $this->load->view($this->theme.'sales/view_quote', $this->data, TRUE);
        $name = $this->lang->line("quote")." ".$this->lang->line("no")." ".$inv->id.".pdf";

        $search = array("<div class=\"row-fluid\">", "<div class=\"span6\">", "<div class=\"span2\">", "<div class=\"span10\">", "<div class=\"span4\">", "<div class=\"span4 offset3\">", "<div class=\"span4 pull-left\">", "<div class=\"span4 pull-right\">");
        $replace = array("<div style='width: 100%;'>", "<div style='width: 48%; float: left;'>", "<div style='width: 18%; float: left;'>", "<div style='width: 78%; float: left;'>", "<div style='width: 40%; float: left;'>", "<div style='width: 40%; float: right;'>", "<div style='width: 40%; float: left;'>", "<div style='width: 40%; float: right;'>");

        $html = str_replace($search, $replace, $html);

        if($save_bufffer) {
            return $this->sim->generate_pdf($html, $name, $save_bufffer);
        } else {
            $this->sim->generate_pdf($html, $name);
        }

    }
    /* --------------------------------------------------------------------------------------------- */


    function email($sale_id = NULL, $to = NULL, $from_name = NULL, $from = NULL, $subject = NULL, $note = NULL, $cc = NULL, $bcc = NULL)
    {

        if($this->input->get('id')){ $sale_id = $this->input->get('id'); }

        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $this->data['rows'] = $this->sales_model->getAllInvoiceItems($sale_id);

        $inv = $this->sales_model->getInvoiceBySaleID($sale_id);
        $customer_id = $inv->customer_id;
        $bc = $inv->company ? $inv->company : 1;
        $biller = $this->sales_model->getCompanyByID($bc);
        $customer = $this->sales_model->getCustomerByID($customer_id);
        $payment = $this->sales_model->getPaymentBySaleID($sale_id);
        $paid = $this->sales_model->getPaidAmount($sale_id);
        $this->data['inv'] = $inv;
        $this->data['sid'] = $sale_id;
        $this->data['biller'] = $biller;
        $this->data['customer'] = $customer;
        $this->data['payment'] = $payment;
        $this->data['paid'] = $paid;

        if(!$to) { $to = $this->data['customer']->email; }
        if(!$subject) { $subject = $this->lang->line('invoice_from').' '.$this->data['biller']->company; }
        if(!$note) { $note = $this->lang->line("find_attached_invoice"); }

        $this->data['page_title'] = $this->lang->line("invoice");

        $html =  $this->load->view($this->theme.'sales/view_invoice', $this->data, TRUE);
        $name = $this->lang->line("invoice")." ".$this->lang->line("no")." ".$inv->id.".pdf";

        $search = array("<div id=\"wrap\">", "<div class=\"row-fluid\">", "<div class=\"span6\">", "<div class=\"span2\">", "<div class=\"span10\">", "<div class=\"span4\">", "<div class=\"span4 offset3\">", "<div class=\"span4 pull-left\">", "<div class=\"span4 pull-right\">");
        $replace = array("<div style='padding:0;'>", "<div style='width: 100%;'>", "<div style='width: 48%; float: left;'>", "<div style='width: 18%; float: left;'>", "<div style='width: 78%; float: left;'>", "<div style='width: 40%; float: left;'>", "<div style='width: 40%; float: right;'>", "<div style='width: 40%; float: left;'>", "<div style='width: 40%; float: right;'>");

        $html = str_replace($search, $replace, $html);

        $email_data = $this->load->view($this->theme.'sales/view_invoice', $this->data, TRUE);
        $email_data = str_replace($search, $replace, $email_data);
        $grand_total = ($inv->total - $paid) + $inv->shipping;
        $paypal = $this->sales_model->getPaypalSettings();
        $skrill = $this->sales_model->getSkrillSettings();                $mollie = $this->sales_model->getMollieSettings();
        $btn_code = '<br><br><div id="payment_buttons" class="text-center margin010">';
        if($paypal->active == "1" && $grand_total != "0.00") {
            if(trim(strtolower($customer->country)) == $biller->country) {
                $paypal_fee = $paypal->fixed_charges+($grand_total*$paypal->extra_charges_my/100);
            } else {
                $paypal_fee = $paypal->fixed_charges+($grand_total*$paypal->extra_charges_other/100);
            }
            $btn_code .=  '<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&business='.$paypal->account_email.'&item_name='.$inv->reference_no.'&item_number='.$inv->id.'&image_url='.base_url() . 'uploads/' . $this->Settings->logo.'&amount='.(($grand_total-$inv->paid)+$paypal_fee).'&no_shipping=1&no_note=1&currency_code='.$this->Settings->currency_prefix.'&bn=FC-BuyNow&rm=2&return='.site_url('clients/sales').'&cancel_return='.site_url('clients/sales').'&notify_url='.site_url('payments/paypalipn').'&custom='.$inv->reference_no.'__'.($grand_total-$inv->paid).'__'.$paypal_fee.'"><img src="'.base_url('uploads/btn-paypal.png').'" alt="Pay by PayPal"></a> ';
        }
        if($skrill->active == "1" && $grand_total != "0.00") {
            if(trim(strtolower($customer->country)) == $biller->country) {
                $skrill_fee = $skrill->fixed_charges+($grand_total*$skrill->extra_charges_my/100);
            } else {
                $skrill_fee = $skrill->fixed_charges+($grand_total*$skrill->extra_charges_other/100);
            }
            $btn_code .= ' <a href="https://www.moneybookers.com/app/payment.pl?method=get&pay_to_email='.$skrill->account_email.'&language=EN&merchant_fields=item_name,item_number&item_name='.$inv->reference_no.'&item_number='.$inv->id.'&logo_url='.base_url() . 'uploads/' . $this->Settings->logo.'&amount='.(($grand_total-$inv->paid)+$skrill_fee).'&return_url='.site_url('clients/sales').'&cancel_url='.site_url('clients/sales').'&detail1_description='.$inv->reference_no.'&detail1_text=Payment for the sale invoice '.$inv->reference_no . ': '.$grand_total.'(+ fee: '.$skrill_fee.') = '.$this->sim->formatDecimal($grand_total+$skrill_fee).'&currency='.$this->Settings->currency_prefix.'&status_url='.site_url('payments/skrillipn').'"><img src="'.base_url('uploads/btn-skrill.png').'" alt="Pay by Skrill"></a>';
        }    	if($mollie->active == "1" && $grand_total != "0.00") {    		    		$objMollie = new Mollie_API_Client;    		$objMollie->setApiKey($mollie->api_key);    		    		if(trim(strtolower($customer->country)) == $biller->country) {
    		
    			$mollie_fee = $mollie->fixed_charges+($grand_total*$mollie->extra_charges_my/100);
    		
    		} else {
    			$mollie_fee = $mollie->fixed_charges+($grand_total*$mollie->extra_charges_other/100);
    		}    		    		$payment = $objMollie->payments->create(array(
    				"amount"       => (($grand_total-$inv->paid)+$mollie_fee),
    				"description"  => "Invoice#: $inv->id",
    				"redirectUrl"  => site_url('clients/sales?invId='.$inv->id.'&amount='.(($grand_total-$inv->paid)+$mollie_fee)),
    				"metadata"     => array(
	    				"order_id" => $inv->id
	    			),
    		));    		$mollielink = $payment->getPaymentUrl();    		    		/* echo '<br>===>'.$mollielink;    		exit; */    		            $btn_code .= ' <a href="'.$mollielink.'"><img src="'.base_url('uploads/btn-mollie.jpg').'" alt="Pay by Mollie"></a>';        }

        $btn_code .= '<div class="clearfix"></div></div>';
        $note = $note.$btn_code;
        if($this->Settings->email_html) {
            if($note) { $message = $note."<br /><hr>".$email_data; } else { $message = $email_data; }
        } else {
            $message = $note;
        }

        $attachment = $this->pdf($sale_id, 'S');
        if($this->sim->send_email($to, $subject, $message, NULL, NULL, $attachment, $cc, $bcc)) {
            delete_files($attachment);
            return TRUE;
        } else {
            return FALSE;
        }

    }

    function emailQ($id, $to, $from_name, $from, $subject, $note, $cc = NULL, $bcc = NULL)
    {


        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $this->data['rows'] = $this->sales_model->getAllQuoteItems($id);

        $inv = $this->sales_model->getQuoteByID($id);
        $customer_id = $inv->customer_id;
        $bc = $inv->company ? $inv->company : 1;
        $this->data['biller'] = $this->sales_model->getCompanyByID($bc);
        $this->data['customer'] = $this->sales_model->getCustomerByID($customer_id);

        $this->data['inv'] = $inv;


        $this->data['page_title'] = $this->lang->line("quote");

        $html1 =  $this->load->view($this->theme.'sales/view_quote', $this->data, TRUE);
        $name = $this->lang->line("quote")." ".$this->lang->line("no")." ".$inv->id.".pdf";

        $search = array("<div class=\"row-fluid\">", "<div class=\"span6\">", "<div class=\"span2\">", "<div class=\"span10\">", "<div class=\"span4\">", "<div class=\"span4 offset3\">", "<div class=\"span4 pull-left\">", "<div class=\"span4 pull-right\">");
        $replace = array("<div style='width: 100%;'>", "<div style='width: 48%; float: left;'>", "<div style='width: 18%; float: left;'>", "<div style='width: 78%; float: left;'>", "<div style='width: 40%; float: left;'>", "<div style='width: 40%; float: right;'>", "<div style='width: 40%; float: left;'>", "<div style='width: 40%; float: right;'>");

        $html1 = str_replace($search, $replace, $html1);

        $html =  $this->load->view($this->theme.'sales/view_quote', $this->data, TRUE);
        $html = str_replace($search, $replace, $html);

        if($this->Settings->email_html) {
            if($note) { $message = $note."<br /><hr>".$html; } else { $message = $html; }
        } else {
            $message = $note;
        }
        $attachment = $this->pdf_quote($id, 'S');
        if($this->sim->send_email($to, $subject, $message, NULL, NULL, $attachment, $cc, $bcc)) {
            delete_files($attachment);
            return TRUE;
        } else {
            return FALSE;
        }


    }

    function email_invoice($id = NULL)
    {
        if($this->input->get('id')){ $id = $this->input->get('id'); }

        $this->form_validation->set_rules('to', $this->lang->line("to")." ".$this->lang->line("email"), 'required|valid_email');
        $this->form_validation->set_rules('subject', $this->lang->line("subject"), 'required');
        $this->form_validation->set_rules('note', $this->lang->line("message"), 'trim');

        if ($this->form_validation->run() == true) {
            $to = $this->input->post('to');
            $cc = $this->input->post('cc');
            $bcc = $this->input->post('bcc');
            $subject= $this->input->post('subject');
            $message = $this->input->post('note');
        }

        if ( $this->form_validation->run() == true && $this->email($id, $to, $subject, $message, $cc, $bcc) ) {

            $this->session->set_flashdata('message', $this->lang->line("sent"));
            redirect("sales");

        } else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));

            $this->data['to'] = array('name' => 'to',
                'id' => 'to',
                'type' => 'text',
                'value' => $this->form_validation->set_value('to'),
            );
            $this->data['subject'] = array('name' => 'subject',
                'id' => 'subject',
                'type' => 'text',
                'value' => $this->form_validation->set_value('subject'),
            );
            $this->data['note'] = array('name' => 'note',
                'id' => 'note',
                'type' => 'text',
                'value' => $this->form_validation->set_value('note'),
            );


            $inv = $this->sales_model->getInvoiceByID($id);
            $customer_id = $inv->customer_id;
            $this->data['cus'] = $this->sales_model->getCustomerByID($customer_id);
            $this->data['id'] = $id;
            $this->data['quote_id'] = NULL;
            $meta['page_title'] = $this->lang->line("email"). " " . $this->lang->line("invoice");
            $this->data['page_title'] = $this->lang->line("email"). " " . $this->lang->line("invoice");

            $this->page_construct('sales/email', $this->data);

        }
    }

    function email_quote($id = NULL)
    {
        if($this->input->get('id')){ $id = $this->input->get('id'); }

        $this->form_validation->set_rules('to', $this->lang->line("to")." ".$this->lang->line("email"), 'required|valid_email');
        $this->form_validation->set_rules('subject', $this->lang->line("subject"), 'required');
        $this->form_validation->set_rules('note', $this->lang->line("message"), 'trim');

        if ($this->form_validation->run() == true)
        {
            $to = $this->input->post('to');
            $cc = $this->input->post('cc');
            $bcc = $this->input->post('bcc');
            $subject= $this->input->post('subject');
            $message = $this->input->post('note');

        }

        if ( $this->form_validation->run() == true && $this->emailQ($id, $to, $subject, $message, $cc, $bcc) ) {

            $this->session->set_flashdata('message', $this->lang->line("sent"));
            redirect("sales/quotes");

        } else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));

            $this->data['to'] = array('name' => 'to',
                'id' => 'to',
                'type' => 'text',
                'value' => $this->form_validation->set_value('to'),
            );
            $this->data['subject'] = array('name' => 'subject',
                'id' => 'subject',
                'type' => 'text',
                'value' => $this->form_validation->set_value('subject'),
            );
            $this->data['note'] = array('name' => 'note',
                'id' => 'note',
                'type' => 'text',
                'value' => $this->form_validation->set_value('note'),
            );

            $inv = $this->sales_model->getQuoteByID($id);
            $customer_id = $inv->customer_id;
            $this->data['cus'] = $this->sales_model->getCustomerByID($customer_id);
            $this->data['id'] = $id;
            $this->data['quote_id'] = NULL;
            $this->data['page_title'] = $this->lang->line("email"). " " . $this->lang->line("quote");

            $this->page_construct('sales/email_quote', $this->data);

        }
    }
    /*----------------------------------------------------------------------------------------------------------------------------------*/
    function update_status()
    {
        if($this->input->post('id')){ $id = $this->input->post('id'); } else { $id = NULL; die(); }
        if($this->input->post('status')){ $status = $this->input->post('status'); } else { $status = NULL; die(); }
        if($id && $status) {
            if($this->sales_model->updateStatus($id, $status)){
                $this->session->set_flashdata('message', $this->lang->line("status_updated"));
                redirect("sales");
            }
        }

        return false;
    }

    function add_payment()
    {
        if($this->input->post('invoice_id')){ $invoice_id = $this->input->post('invoice_id'); } else { $invoice_id = NULL; die(); }
        if($this->input->post('customer_id')){ $customer_id = $this->input->post('customer_id'); } else { $customer_id = NULL; die(); }
        if($this->input->post('note')){ $note = $this->input->post('note'); } else { $note = NULL; }
        if($this->input->post('amount')){ $amount = $this->input->post('amount'); } else { $amount = NULL; die(); }
        if($invoice_id && $customer_id && $amount) {
            if($this->input->post('date')) {
                $date = $this->sim->fsd($this->input->post('date'));
            } else {
                $date = date('Y-m-d');
            }
            if($this->sales_model->addPayment($invoice_id, $customer_id, $amount, $note, $date)){
                $this->session->set_flashdata('message', $this->lang->line("amount_added"));
                redirect("sales");
            }
        }

        return false;
    }

    function pr_details()
    {
        if($this->input->get('name')) { $name = $this->input->get('name', TRUE); }

        if($item = $this->sales_model->getProductByName($name)) {

            $price = $item->price;
            $tax_rate = $item->tax_rate;

            $product = array('price' => $price, 'tax_rate' => $tax_rate);

        }

        echo json_encode($product);

    }

    function roundnum($num, $nearest = 0.05){
        return round($num / $nearest) * $nearest;
    }

    function edit_payment($id = NULL) {
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        $this->form_validation->set_rules('amount', $this->lang->line("amount"), 'required');

        if ($this->form_validation->run() == true) {
            $data = array(
                'date' => $this->sim->fsd($this->input->post('date')),
                'amount' => $this->input->post('amount'),
                'note' => $this->input->post('note'),
            );
        }

        if ($this->form_validation->run() == true && $this->sales_model->updatePayment($id, $data)) {

            $this->session->set_flashdata('message', $this->lang->line("payment_updated"));
            redirect("sales");

        } else {
            $this->data['payment'] = $this->sales_model->getPaymentByID($id);
            $this->data['page_title'] = $this->lang->line("edit_payment");
            $this->load->view($this->theme.'sales/edit_payment', $this->data);
        }

    }

    function delete_payment($id = NULL) {
        if (!$this->sim->in_group('admin')) {
            $this->session->set_flashdata('error', $this->lang->line("access_denied"));
            redirect('sales');
        }

        if($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        if($this->sales_model->deletePayment($id)) {
            //echo lang("payment_deleted");
            $this->session->set_flashdata('message', lang("payment_deleted"));
            redirect($_SERVER["HTTP_REFERER"]);
        }
    }

}