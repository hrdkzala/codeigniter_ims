<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/*
| -----------------------------------------------------
| PRODUCT NAME: 	STOCK MANAGER ADVANCE 
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
| MODULE: 			Inventories
| -----------------------------------------------------
| This is inventories module's model file.
| -----------------------------------------------------
*/


class Sales_model extends CI_Model
{
	
	
	public function __construct()
	{
		parent::__construct();

	}
	
	public function getAllCustomers() 
	{
		$q = $this->db->get('customers');
		if($q->num_rows() > 0) {
			foreach (($q->result()) as $row) {
				$data[] = $row;
			}

			return $data;
		}
	}

	public function getAllCompanies() 	{
		$q = $this->db->get('company');
		if($q->num_rows() > 0) {
			foreach (($q->result()) as $row) {
				$data[] = $row;
			}
			return $data;
		}
	}

	public function getCompanyByID($id) 	{
		$q = $this->db->get_where('company', array('id' => $id), 1); 
		if( $q->num_rows() > 0 )
		{
			return $q->row();
		} 
		
		return FALSE;

	}
	
	public function getAllProducts() 
	{
		$q = $this->db->get('products');
		if($q->num_rows() > 0) {
			foreach (($q->result()) as $row) {
				$data[] = $row;
			}

			return $data;
		}
	}
	
	public function getCustomerByID($id)
	{

		$q = $this->db->get_where('customers', array('id' => $id), 1); 
		if( $q->num_rows() > 0 )
		{
			return $q->row();
		} 
		
		return FALSE;

	}
	
	public function getAllTaxRates() 
	{
		$q = $this->db->get('tax_rates');
		if($q->num_rows() > 0) {
			foreach (($q->result()) as $row) {
				$data[] = $row;
			}

			return $data;
		}
	}
	
	public function getTaxRateByID($id) 
	{

		$q = $this->db->get_where('tax_rates', array('id' => $id), 1); 
		if( $q->num_rows() > 0 )
		{
			return $q->row();
		} 
		
		return FALSE;

	}
	
	
	public function getAllInvoiceTypes() 
	{
		$q = $this->db->get('invoice_types');
		if($q->num_rows() > 0) {
			foreach (($q->result()) as $row) {
				$data[] = $row;
			}

			return $data;
		}
	}
	
	public function getInvoiceTypeByID($id) 
	{

		$q = $this->db->get_where('invoice_types', array('id' => $id), 1); 
		if( $q->num_rows() > 0 )
		{
			return $q->row();
		} 
		
		return FALSE;

	}
	
	
	public function getItemByID($id)
	{

		$q = $this->db->get_where('sale_items', array('id' => $id), 1); 
		if( $q->num_rows() > 0 )
		{
			return $q->row();
		} 
		
		return FALSE;

	}
	
	public function getAllSales() 
	{
		$q = $this->db->get('sales');
		if($q->num_rows() > 0) {
			foreach (($q->result()) as $row) {
				$data[] = $row;
			}
			return $data;
		}
	}

	public function getAllQuotes() 
	{
		$q = $this->db->get('quotes');
		if($q->num_rows() > 0) {
			foreach (($q->result()) as $row) {
				$data[] = $row;
			}
			return $data;
		}
	}
	
	
	public function getmonthlySales() 
	{
		$myQuery = "SELECT date_format( date, '%b' ) as month, SUM( total ) as sales FROM sales WHERE in_type = 'real' AND date >= date_sub( now( ) , INTERVAL 12 MONTH ) GROUP BY date_format( date, '%b' ) ORDER BY date_format( date, '%m' ) ASC";
		$q = $this->db->query($myQuery);
		if($q->num_rows() > 0) {
			foreach (($q->result()) as $row) {
				$data[] = $row;
			}

			return $data;
		}
	}
	
	public function getAllInvoiceItems($sale_id) 
	{
		$this->db->order_by('id');
		$q = $this->db->get_where('sale_items', array('sale_id' => $sale_id));
		if($q->num_rows() > 0) {
			foreach (($q->result()) as $row) {
				$data[] = $row;
			}

			return $data;
		}
	}
	public function getInvoiceByID($id)
	{

		$q = $this->db->get_where('sales', array('id' => $id), 1); 
		if( $q->num_rows() > 0 )
		{
			return $q->row();
		} 
		
		return FALSE;

	}

	
	public function getInvoiceBySaleID($sale_id)
	{

		$q = $this->db->get_where('sales', array('id' => $sale_id), 1); 
		if( $q->num_rows() > 0 )
		{
			return $q->row();
		} 
		
		return FALSE;

	}
	
	public function getQuoteByID($id)
	{

		$q = $this->db->get_where('quotes', array('id' => $id), 1); 
		if( $q->num_rows() > 0 )
		{
			return $q->row();
		} 
		
		return FALSE;

	}

	public function getAllQuoteItems($quote_id) 
	{
		$this->db->order_by('id');
		$q = $this->db->get_where('quote_items', array('quote_id' => $quote_id));
		if($q->num_rows() > 0) {
			foreach (($q->result()) as $row) {
				$data[] = $row;
			}

			return $data;
		}
	}
	

	public function addSale($saleDetails = array(), $items = array(), $customerData = array())
	{
		if(!empty($customerData)) {
			
			if($this->db->insert('customers', $customerData)) {
				$customer_id = $this->db->insert_id();
			}
			// sale data
			$saleData = array(
				'reference_no'			=> $saleDetails['reference_no'],
				'company'				=> $saleDetails['company'],
				'customer_id'			=> $customer_id,
				'customer_name'			=> $saleDetails['customer_name'],
				'recurring'				=> $saleDetails['recurring'],
				'recur_date'			=> $saleDetails['date'],
				'date'					=> $saleDetails['date'],
				'due_date'				=> $saleDetails['due_date'],
				'user'					=> $saleDetails['user'],
				'note'	  	 			=> $saleDetails['note'],
				'inv_total'				=> $saleDetails['inv_total'],
				'total_tax'				=> $saleDetails['total_tax'],
				'total'					=> $saleDetails['total'],
				'status'				=> $saleDetails['status'],
				'user_id'				=> $this->session->userdata('user_id'),
				'shipping'				=> $saleDetails['shipping'],
				'discount'				=> $saleDetails['discount'],
				'total_discount'		=> $saleDetails['total_discount'],
				);

		} else {
			// sale data
			$saleData = array(
				'reference_no'			=> $saleDetails['reference_no'],
				'company'				=> $saleDetails['company'],
				'customer_id'			=> $saleDetails['customer_id'],
				'customer_name'			=> $saleDetails['customer_name'],
				'recurring'				=> $saleDetails['recurring'],
				'recur_date'			=> $saleDetails['date'],
				'date'					=> $saleDetails['date'],
				'due_date'				=> $saleDetails['due_date'],
				'user'					=> $saleDetails['user'],
				'note'	  	 			=> $saleDetails['note'],
				'inv_total'				=> $saleDetails['inv_total'],
				'total_tax'				=> $saleDetails['total_tax'],
				'total'					=> $saleDetails['total'],
				'status'				=> $saleDetails['status'],
				'user_id'				=> $this->session->userdata('user_id'),
				'shipping'				=> $saleDetails['shipping'],
				'discount'				=> $saleDetails['discount'],
				'total_discount'		=> $saleDetails['total_discount'],
				);
		}

		if($this->db->insert('sales', $saleData)) {
			$sale_id = $this->db->insert_id();
			
			$addOn = array('sale_id' => $sale_id);
			end($addOn);
			foreach ( $items as &$var ) {
				$var = array_merge($addOn, $var);
			}
			
			if($saleDetails['status'] == $this->lang->line('paid') || $saleDetails['status'] == 'paid') {
				$adata = array(
					'date' => $saleDetails['date'],
					'invoice_id' => $sale_id,
					'customer_id' => $saleDetails['customer_id'],
					'amount' => ($saleDetails['total']+$saleDetails['shipping']),
					'note' => $this->lang->line('paid_nett'),
					'user' => $this->session->userdata('user_id')
					);
				$this->db->insert('payment', $adata);
				$this->db->update('sales', array('paid' => ($saleDetails['total']+$saleDetails['shipping'])), array('id' => $sale_id));
			}

			if($this->db->insert_batch('sale_items', $items)) {
				return true;
			}
		}
		
		return false;
	}
	
	public function addQuote($quoteDetails = array(), $items = array(), $customerData = array())
	{
		
		if(!empty($customerData)) {
			
			if($this->db->insert('customers', $customerData)) {
				$customer_id = $this->db->insert_id();
			}
			// Quote data
			$quoteData = array(
				'reference_no'			=> $quoteDetails['reference_no'],
				'company'				=> $quoteDetails['company'],
				'customer_id'			=> $customer_id,
				'customer_name'			=> $quoteDetails['customer_name'],
				'date'					=> $quoteDetails['date'],
				'expiry_date'			=> $quoteDetails['expiry_date'],
				'user'					=> $quoteDetails['user'],
				'note'	  	 			=> $quoteDetails['note'],
				'inv_total'				=> $quoteDetails['inv_total'],
				'total_tax'				=> $quoteDetails['total_tax'],
				'total'					=> $quoteDetails['total'],
				'user_id'				=> $this->session->userdata('user_id'),
				'shipping'				=> $quoteDetails['shipping'],
				'discount'				=> $quoteDetails['discount'],
				'total_discount'		=> $quoteDetails['total_discount'],
				'status'				=> $quoteDetails['status'],
				);

		} else {
			// Quote data
			$quoteData = array(
				'reference_no'			=> $quoteDetails['reference_no'],
				'company'				=> $quoteDetails['company'],
				'customer_id'			=> $quoteDetails['customer_id'],
				'customer_name'			=> $quoteDetails['customer_name'],
				'date'					=> $quoteDetails['date'],
				'expiry_date'			=> $quoteDetails['expiry_date'],
				'user'					=> $quoteDetails['user'],
				'note'	  	 			=> $quoteDetails['note'],
				'inv_total'				=> $quoteDetails['inv_total'],
				'total_tax'				=> $quoteDetails['total_tax'],
				'total'					=> $quoteDetails['total'],
				'user_id'				=> $this->session->userdata('user_id'),
				'shipping'				=> $quoteDetails['shipping'],
				'discount'				=> $quoteDetails['discount'],
				'total_discount'		=> $quoteDetails['total_discount'],
				'status'				=> $quoteDetails['status'],
				);
		}

		if($this->db->insert('quotes', $quoteData)) {
			$quote_id = $this->db->insert_id();
			
			$addOn = array('quote_id' => $quote_id);
			end($addOn);
			foreach ( $items as &$var ) {
				$var = array_merge($addOn, $var);
			}

			if($this->db->insert_batch('quote_items', $items)) {
				return true;
			}
		}

		return false;
	}
	
	public function updateSale($id, $saleDetails, $items = array())
	{
		
		
			// sale data
		$saleData = array(
			'reference_no'			=> $saleDetails['reference_no'],
			'company'				=> $saleDetails['company'],
			'customer_id'			=> $saleDetails['customer_id'],
			'customer_name'			=> $saleDetails['customer_name'],
			'recurring'				=> $saleDetails['recurring'],
			'recur_date'			=> $saleDetails['date'],
			'date'					=> $saleDetails['date'],
			'due_date'				=> $saleDetails['due_date'],
			'note'	  	 			=> $saleDetails['note'],
			'inv_total'				=> $saleDetails['inv_total'],
			'total_tax'				=> $saleDetails['total_tax'],
			'total'					=> $saleDetails['total'],
			'status'				=> $saleDetails['status'],
			'shipping'				=> $saleDetails['shipping'],
			'discount'				=> $saleDetails['discount'],
			'total_discount'		=> $saleDetails['total_discount'],
			);

		$this->db->where('id', $id);
		if($this->db->update('sales', $saleData) && $this->db->delete('sale_items', array('sale_id' => $id))) {

			
			if($this->db->insert_batch('sale_items', $items)) {
				return true;
			}

		}
		return false;
	}
	
	public function updateQuote($id, $quoteDetails, $items = array())
	{
		
		
			// quote data
		$quoteData = array(
			'reference_no'			=> $quoteDetails['reference_no'],
			'company'				=> $quoteDetails['company'],
			'customer_id'			=> $quoteDetails['customer_id'],
			'customer_name'			=> $quoteDetails['customer_name'],
			'date'					=> $quoteDetails['date'],
			'expiry_date'			=> $quoteDetails['expiry_date'],
			'note'	  	 			=> $quoteDetails['note'],
			'inv_total'				=> $quoteDetails['inv_total'],
			'total_tax'				=> $quoteDetails['total_tax'],
			'total'					=> $quoteDetails['total'],
			'shipping'				=> $quoteDetails['shipping'],
			'discount'				=> $quoteDetails['discount'],
			'total_discount'		=> $quoteDetails['total_discount'],
			'status'				=> $quoteDetails['status'],
			);

		$this->db->where('id', $id);
		if($this->db->update('quotes', $quoteData) && $this->db->delete('quote_items', array('quote_id' => $id))) {

			
			if($this->db->insert_batch('quote_items', $items)) {
				return true;
			}


		}
		return false;
	}

	public function updateQuoteStatus($id)
	{

		if($this->db->update('quotes', array('status' => 'sent'), array('id' => $id))) {
			return true;
		}
		return false;
	}
	
	public function getCompanyDetails() 
	{

		$q = $this->db->get_where('company', array('id' => 1), 1); 
		if( $q->num_rows() > 0 )
		{
			return $q->row();
		} 
		
		return FALSE;

	}
	
	public function deleteInvoice($id)
	{

		if($this->db->delete('sale_items', array('sale_id' => $id)) && $this->db->delete('sales', array('id' => $id))) {
			return true;
		}
		return FALSE;
	}
	
	public function deleteQuote($id)
	{

		if($this->db->delete('quote_items', array('quote_id' => $id)) && $this->db->delete('quotes', array('id' => $id))) {
			return true;
		}
		return FALSE;
	} 
	
	public function updateStatus($id, $status)
	{
		

		$this->db->where('id', $id);
		if($this->db->update('sales', array('status' => $status))) {
			return true;
		}
		return false;
	} 
	
	
	public function getPaymentBySaleID($sale_id) 
	{
		$this->db->order_by('id');
		$q = $this->db->get_where('payment', array('invoice_id' => $sale_id));
		if($q->num_rows() > 0) {
			foreach (($q->result()) as $row) {
				$data[] = $row;
			}

			return $data;
		}
	}
	
	public function addPayment($invoice_id, $customer_id, $amount, $note = NULL, $date = NULL)
	{
		if(!$date) { $date = date('Y-m-d'); }
		$inv = $this->getInvoiceByID($invoice_id);
		$total = $inv->total+$inv->shipping;
		$adata = array(
			'date' => $date,
			'invoice_id' => $invoice_id,
			'customer_id' => $customer_id,
			'amount' => $amount,
			'note' => $note,
			'user' => $this->session->userdata('user_id')
			);
		if($this->db->insert('payment', $adata)) {
			$paid = $this->getPaidAmount($invoice_id);
			
			if($paid >= $total) {
				$this->db->update('sales', array('status' => 'paid', 'paid' => $paid), array('id' => $invoice_id));
				return true;
			} else {
				$this->db->update('sales', array('status' => 'partial', 'paid' => $paid), array('id' => $invoice_id));
				return true;
			}
		}
		return false;
	}

    public function updatePayment($id, $data)
    {
        $payment = $this->getPaymentByID($id);
        $paid = $this->getPaidAmount($payment->invoice_id);
        $paid = $paid - $payment->amount + $data['amount'];
        if($this->db->update('payment', $data, array('id' => $id))) {
            $inv = $this->getInvoiceByID($payment->invoice_id);
            $total = $inv->total+$inv->shipping;
            if($paid >= $total) {
                $this->db->update('sales', array('status' => 'paid', 'paid' => $paid), array('id' => $payment->invoice_id));
                return true;
            } else {
                $this->db->update('sales', array('status' => 'partial', 'paid' => $paid), array('id' => $payment->invoice_id));
                return true;
            }
        }
        return false;
    }

    public function deletePayment($id)
    {
        $payment = $this->getPaymentByID($id);
        $paid = $this->getPaidAmount($payment->invoice_id);
        $paid = $paid - $payment->amount;
        if($this->db->delete('payment', array('id' => $id))) {
            $inv = $this->getInvoiceByID($payment->invoice_id);
            $total = $inv->total+$inv->shipping;
            if($paid >= $total) {
                $this->db->update('sales', array('status' => 'paid', 'paid' => $paid), array('id' => $payment->invoice_id));
                return true;
            } elseif($paid > 0) {
                $this->db->update('sales', array('status' => 'partial', 'paid' => $paid), array('id' => $payment->invoice_id));
                return true;
            } else {
                $this->db->update('sales', array('status' => 'overdue', 'paid' => $paid), array('id' => $payment->invoice_id));
                return true;
            }
        }
        return false;
    }

	public function getPaidAmount($invoice_id) 
	{
		$this->db->select('COALESCE(sum(payment.amount), 0) as amount', FALSE);
		$q = $this->db->get_where('payment', array('invoice_id' => $invoice_id)); 
		if( $q->num_rows() > 0 )
		{
			$da = $q->row();
			return $da->amount;
		} 
		
		return FALSE;

	}    
	
	public function getProductByName($name)
	{

		$q = $this->db->get_where('products', array('name' => $name), 1); 
		if( $q->num_rows() > 0 )
		{
			return $q->row();
		} 
		
		return FALSE;

	} 

	public function getPaypalSettings() {        
		$q = $this->db->get('paypal'); 
		if( $q->num_rows() > 0 ) {
			return $q->row();
		} 
		return FALSE;
	}

	public function getSkrillSettings() {        
		$q = $this->db->get('skrill'); 
		if( $q->num_rows() > 0 ) {
			return $q->row();
		} 
		return FALSE;
	}		public function getMollieSettings() {
	
		$q = $this->db->get('mollie');
	
		if( $q->num_rows() > 0 ) {
	
			return $q->row();
	
		}
	
		return FALSE;
	
	}
	
	public function getPaymentByID($id) 
	{

		$q = $this->db->get_where('payment', array('id' => $id), 1); 
		if( $q->num_rows() > 0 )
		{
			return $q->row();
		} 
		
		return FALSE;

	}
	
}
