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
| MODULE: 			Products
| -----------------------------------------------------
| This is products module model file.
| -----------------------------------------------------
*/


class Settings_model extends CI_Model
{
	
	
	public function __construct()
	{
		parent::__construct();

	}
	
	public function updateLogo($photo)
	{

			$logo = array(
				'logo'	     			=> $photo
			);
			
		if($this->db->update('settings', $logo)) {
			return true;
		} else {
			return false;
		}
	}
	
	public function updateInvoiceLogo($photo)
	{

			$logo = array(
				'invoice_logo'	     			=> $photo
			);
			
		if($this->db->update('settings', $logo)) {
			return true;
		} else {
			return false;
		}
	}
	
	public function getSettings() 
	{
				
		$q = $this->db->get('settings'); 
		  if( $q->num_rows() > 0 )
		  {
			return $q->row();
		  } 
		
		  return FALSE;

	}
	
	public function getDateFormats() 
	{
		$q = $this->db->get('date_format');
		if($q->num_rows() > 0) {
			foreach (($q->result()) as $row) {
				$data[] = $row;
			}
				
			return $data;
		}
	}
	
	public function updateSetting($data)
	{
		
		$this->db->where('setting_id', '1');
		if($this->db->update('settings', $data)) {
			return true;
		} else {
			return false;
		}
	}
	
	public function addTaxRate($data)
	{

			$taxData = array(
				'name'	     	=> $data['name'],
				'rate' 			=> $data['rate'],
				'type' 			=> $data['type']
			);

		if($this->db->insert('tax_rates', $taxData)) {
			return true;
		} else {
			return false;
		}
	}
	
	public function updateTaxRate($id, $data = array())
	{
		
		$taxData = array(
				'name'	     	=> $data['name'],
				'rate' 			=> $data['rate'],
				'type' 			=> $data['type']
			);
			
		$this->db->where('id', $id);
		if($this->db->update('tax_rates', $taxData)) {
			return true;
		} else {
			return false;
		}
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

	public function addCompany($data = array())
	{

		if($this->db->insert('company', $data)) {
			return true;
		} else {
			return false;
		}
	}
	
	public function updateCompany($id, $data = array())
	{

		$this->db->where('id', $id);
		if($this->db->update('company', $data)) {
			return true;
		} else {
			return false;
		}
	}
	
	public function getCompanyByID($id) 
	{

		$q = $this->db->get_where('company', array('id' => $id), 1); 
		  if( $q->num_rows() > 0 )
		  {
			return $q->row();
		  } 
		
		  return FALSE;

	}

	public function deleteCompany($id) 
	{
		if($this->db->delete('company', array('id' => $id))) {
			return true;
		}
	return FALSE;
	}

	
	public function deleteTaxRate($id) 
	{
		if($this->db->delete('tax_rates', array('id' => $id))) {
			return true;
		}
	return FALSE;
	}

	public function getAllCompanies() 
	{
		$q = $this->db->get('company');
		if($q->num_rows() > 0) {
			foreach (($q->result()) as $row) {
				$data[] = $row;
			}
				
			return $data;
		}
	}

	public function getPaypalSettings() {        
        $q = $this->db->get('paypal'); 
        if( $q->num_rows() > 0 ) {
            return $q->row();
        } 
        return FALSE;
    }

    public function updatePaypal($data) {
        $this->db->where('id', '1');
        if($this->db->update('paypal', $data)) {
            return true;
        } 
        return FALSE;
    }

    public function getSkrillSettings() {        
        $q = $this->db->get('skrill'); 
        if( $q->num_rows() > 0 ) {
            return $q->row();
        } 
        return FALSE;
    }        public function getMollieSettings() {
    
    	$q = $this->db->get('mollie');
    
    	if( $q->num_rows() > 0 ) {
    
    		return $q->row();
    
    	}
    
    	return FALSE;
    
    }

    public function updateSkrill($data) {
        $this->db->where('id', '1');
        if($this->db->update('skrill', $data)) {
            return true;
        } 
        return FALSE;
    }        public function updateMollie($data) {
    
    	$this->db->where('id', '1');
    
    	if($this->db->update('mollie', $data)) {
    
    		return true;
    
    	}
    
    	return FALSE;
    
    }

    /* ------------------------ */

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
	
	public function getUserByFirstName($fn)
	{
		$q = $this->db->get_where('users', array('first_name' => $fn), 1); 
		if( $q->num_rows() > 0 )
		{
			return $q->row();
		} 
		return FALSE;

	}

	public function updateSalesUser()
	{
		$sales = $this->getAllSales();
		foreach ($sales as $sale) {
			if($user = $this->getUserByFirstName($sale->user)) {
				$this->db->update('sales', array('user' => $user->id), array('id' => $sale->id));
			}
		}
		return TRUE;
	}

	public function updateQuotesUser()
	{
		$quotes = $this->getAllQuotes();
		foreach ($quotes as $quote) {
			if($user = $this->getUserByFirstName($quote->user)) {
				$this->db->update('quotes', array('user' => $user->id), array('id' => $quote->id));
			}
		}
		return TRUE;
	}
	

}
