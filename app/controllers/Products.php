<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Products extends MY_Controller {

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
| MODULE: 			Products
| -----------------------------------------------------
| This is products module controller file.
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
	}		if(!file_exists("uploads/"))
	{
		@mkdir("uploads/",0777);
	}
	else
	{
		@chmod("uploads/",0777);
	}
		
	if(!file_exists("uploads/products/"))
	{
		@mkdir("uploads/products/",0777);
	}
	else
	{
		@chmod("uploads/products/",0777);
	}
	
	$this->load->library('form_validation');
	$this->load->model('products_model');	$this->load->model('category_model');

}

function index(){	$this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
	$this->data['page_title'] = $this->lang->line("products");
	$this->page_construct('products/index', $this->data);
}

function getdatatableajax(){
	$this->load->library('datatables');
	$this->datatables
	//->select("products.id, products.name as pname, category.name as cname, price, tax_rates.name")	->select("products.id, products.name as pname, category.name as cname, products.price, products.qty, products.description")
	->from("products")
	->join('category', 'category.id=products.category_id', 'left')	//->join('tax_rates', 'tax_rates.id=products.tax_rate', 'left')
	->group_by('products.id')
	->add_column("Actions", 
		"<center><div class='btn-group'><a class=\"tip btn btn-primary btn-xs\" title='".$this->lang->line("edit_product")."' href='".site_url('products/edit')."?id=$1'><i class=\"fa fa-edit\"></i></a> <a class=\"tip btn btn-danger btn-xs\" title='".$this->lang->line("delete_product")."' href='".site_url('products/delete')."?id=$1' onClick=\"return confirm('". $this->lang->line('alert_x_product') ."')\"><i class=\"fa fa-trash-o\"></i></a></div></center>", "products.id")
	->unset_column('id');
	
	echo $this->datatables->generate();

}

function add(){
	$this->form_validation->set_rules('name', $this->lang->line("name"), 'required');
	$this->form_validation->set_rules('price', $this->lang->line("price"), 'required');
	if ($this->form_validation->run() == true)	{		$productImage = "";		if(!empty($_FILES["productImage"]["name"]) && count($_FILES["productImage"]["name"])>0)		{			foreach($_FILES["productImage"]["name"] as $key=>$value)			{				if($value!="")				{					$imageExtension = pathinfo($value, PATHINFO_EXTENSION);										if($imageExtension=="jpg" || $imageExtension=="jpeg" || $imageExtension=="png" || $imageExtension=="bmp" || $imageExtension=="gif")					{						//$fileName = date('YmdHis').".".$imageExtension;												$productImage .= $value.',';								move_uploaded_file($_FILES["productImage"]["tmp_name"][$key], "uploads/products/".$value);					}				}			}			$productImage = rtrim($productImage,",");		}
		$data = array('name' => $this->input->post('name'),			'category_id' =>$this->input->post('category_id'),			'qty' =>$this->input->post('qty'),			'description' =>$this->input->post('description'),
			'price' => $this->input->post('price'),			'tax_rate' => $this->input->post('tax_rate'),			'product_image' => $productImage		);				/* echo '<pre>';
		print_r($_POST);
		print_r($_FILES);
		exit; */	}
	if ($this->form_validation->run() == true && $this->products_model->addProduct($data)) { 
			$this->session->set_flashdata('message', $this->lang->line("product_added"));
			redirect("products");
		} else { 
			$this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
			$this->data['tax_rates'] = $this->products_model->getAllTaxRates();						$arrOfCategories = array();
			$arrOfCategories = $this->category_model->fetchCategoryTree();
				
			$this->data['categories'] = $arrOfCategories;
			$this->data['page_title'] = $this->lang->line("new_product");
			$this->page_construct('products/add', $this->data);
			
		}
	}
	
	function edit($id = NULL)
	{
		if($this->input->get('id')) { $id = $this->input->get('id'); }

		$this->form_validation->set_rules('name', $this->lang->line("name"), 'required');
		$this->form_validation->set_rules('price', $this->lang->line("price"), 'required');
		
		if ($this->form_validation->run() == true) {			$objProduct = $this->products_model->getProductByID($id);						$productImage = "";			if($objProduct->product_image!="")			{				$productImage = $objProduct->product_image.",";			}			
			if(!empty($_FILES["productImage"]["name"]) && count($_FILES["productImage"]["name"])>0)
			{
				foreach($_FILES["productImage"]["name"] as $key=>$value)
				{
					if($value!="")
					{
						$imageExtension = pathinfo($value, PATHINFO_EXTENSION);												if($imageExtension=="jpg" || $imageExtension=="jpeg" || $imageExtension=="png" || $imageExtension=="bmp" || $imageExtension=="gif")
						{
							//$fileName = date('YmdHis').".".$imageExtension;
								
							$productImage .= $value.',';
				
							move_uploaded_file($_FILES["productImage"]["tmp_name"][$key], "uploads/products/".$value);						}
					}
				}
				$productImage = rtrim($productImage,",");
			}
						if($productImage!="")			{
				$data = array('name' => $this->input->post('name'),
						'category_id' =>$this->input->post('category_id'),
						'qty' =>$this->input->post('qty'),
						'description' =>$this->input->post('description'),
						'price' => $this->input->post('price'),
						'tax_rate' => $this->input->post('tax_rate'),
						'product_image' => $productImage
				);			}			else			{				$data = array('name' => $this->input->post('name'),
						'category_id' =>$this->input->post('category_id'),
						'qty' =>$this->input->post('qty'),
						'description' =>$this->input->post('description'),
						'price' => $this->input->post('price'),
						'tax_rate' => $this->input->post('tax_rate')
				);			}		}
		if ( $this->form_validation->run() == true && $this->products_model->updateProduct($id, $data))
		{  
			$this->session->set_flashdata('message', $this->lang->line("product_updated"));
			redirect("products");
		} else {  						$arrOfCategories = array();
			$arrOfCategories = $this->category_model->fetchCategoryTree();
			
			$this->data['categories'] = $arrOfCategories;
			$this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
			$this->data['product'] = $this->products_model->getProductByID($id);
			$this->data['tax_rates'] = $this->products_model->getAllTaxRates();
			$this->data['id'] = $id;
			$this->data['page_title'] = $this->lang->line("update_product");
			$this->page_construct('products/edit', $this->data);
			
		}
	}
	
	function import()
	{
		if (!$this->sim->in_group('admin')) {
			$this->session->set_flashdata('error', $this->lang->line("access_denied"));
			redirect('products');
		}
		$this->load->helper('security');
		$this->form_validation->set_rules('userfile', $this->lang->line("upload_file"), 'xss_clean');
		
		if ($this->form_validation->run() == true) {
			
			if (DEMO) {
				$this->session->set_flashdata('error', $this->lang->line("disabled_in_demo"));
				redirect('home');
			}
			
			$category = $this->input->post('category');			
			if ( isset($_FILES["userfile"])) /*if($_FILES['userfile']['size'] > 0)*/
			{
				
				$this->load->library('upload');

				$config['upload_path'] = 'uploads/'; 
				$config['allowed_types'] = 'csv'; 
				$config['max_size'] = '200';
				$config['overwrite'] = TRUE; 
				
				$this->upload->initialize($config);
				
				if( ! $this->upload->do_upload()){
					
					$error = $this->upload->display_errors();
					$this->session->set_flashdata('error', $error);
					redirect("products/import");
				} 
				
				$csv = $this->upload->file_name;
				
				$arrResult = array();
				$handle = fopen("uploads/".$csv, "r");
				if( $handle ) {
					while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
						$arrResult[] = $row;
					}
					fclose($handle);
				}
				$titles = array_shift($arrResult);
				
				$keys = array('name', 'price', 'tax');
				
				$final = array();
				
				foreach ( $arrResult as $key => $value ) {
					$final[] = array_combine($keys, $value);
				}
				$rw=2;
				foreach($final as $csv_pr) {
					if($this->products_model->getProductByName($csv_pr['name'])) {
						$this->session->set_flashdata('error', $this->lang->line("check_product_name")." (".$csv_pr['name']."). ".$this->lang->line("product_name_already_exist")." ".$this->lang->line("line_no")." ".$rw);
						redirect("products/import");
					}
					if( $taxd = $this->products_model->getTaxRateByName($csv_pr['tax'])) {
						$pr_name[] = $csv_pr['name'];
						$pr_tax[] = $taxd->id;
						$pr_price[] = $csv_pr['price'];
					} else {
						$this->session->set_flashdata('error', $this->lang->line("check_tax_rate")." (".$csv_pr['tax']."). ".$this->lang->line("tax_x_exist")." ".$this->lang->line("line_no")." ".$rw);
						redirect("products/import");
					}
					
					$rw++;	
				}
			} 

			$ikeys = array('name', 'price', 'tax_rate');
			
			$items = array();
			foreach ( array_map(null, $pr_name, $pr_price, $pr_tax) as $ikey => $value ) {
				$items[] = array_combine($ikeys, $value);
			}
			
			$final = $this->sim->escape_str($items);
		}
		
		if ( $this->form_validation->run() == true && $this->products_model->add_products($final))
		{ 
			$this->session->set_flashdata('message', $this->lang->line("products_added"));
			redirect('products');
		}
		else
		{  
			
			$this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
			
			$this->data['userfile'] = array('name' => 'userfile',
				'id' => 'userfile',
				'type' => 'text',
				'value' => $this->form_validation->set_value('userfile')
				);

			$this->data['page_title'] = $this->lang->line("csv_add_products");
			$this->page_construct('products/upload_csv', $this->data);
			
		}
	}

	function delete($id = NULL)
	{
		if (DEMO) {
			$this->session->set_flashdata('error', $this->lang->line("disabled_in_demo"));
			redirect('home');
		}

		if($this->input->get('id')) { $id = $this->input->get('id'); }
		if (!$this->sim->in_group('admin')) {
			$this->session->set_flashdata('error', $this->lang->line("access_denied"));
			redirect('home');
		}

		if ( $this->products_model->deleteProduct($id) )
		{  
			$this->session->set_flashdata('message', $this->lang->line("product_deleted"));
			redirect("products");
		}
		
	}
		function deleteimage()	{		$getProductId = $this->input->get('id');		$getProductImage = $this->input->get('prdImage');				$objProduct = $this->products_model->getProductByID($getProductId);				/* echo '<pre>';		print_R($objProduct);		exit; */				$productImg = $objProduct->product_image;		$explodeProductImage = explode(",",$productImg);				$arrImg = array();		foreach($explodeProductImage as $image)		{			if($image != $getProductImage)			{				$arrImg[] = $image;			}		}				$productImg = "";		if(!empty($arrImg) && count($arrImg)>0)		{			$productImg = implode(",",$arrImg);		}				$update = $this->products_model->updateProduct($getProductId, array('product_image'=>$productImg));				if($update)		{			$this->session->set_flashdata('message', $this->lang->line("product_image_deleted_suc"));		}		else		{			$this->session->set_flashdata('error', $this->lang->line("product_image_deleted_err"));		}				redirect("products/edit?id=".$getProductId,"refresh");	}}