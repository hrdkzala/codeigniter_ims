<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Category extends MY_Controller {

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
| MODULE: 			Category
| -----------------------------------------------------
| This is products module controller file.
| -----------------------------------------------------
*/
	function __construct()	{	
		parent::__construct();	
		
		if (!$this->sim->logged_in())	
		{	
			redirect('auth/login');	
		}	
		if($this->sim->in_group('customer')) {	
			$this->session->set_flashdata('error', $this->lang->line("access_denied"));	
			redirect('clients');		}				if(!file_exists("uploads/"))
		{
			@mkdir("uploads/",0777);
		}
		else
		{
			@chmod("uploads/",0777);
		}
			
		if(!file_exists("uploads/category/"))
		{
			@mkdir("uploads/category/",0777);
		}
		else
		{
			@chmod("uploads/category/",0777);
		}	
		$this->load->helper('file');	
		$this->load->library('form_validation');	
		$this->load->model('category_model');	}
	function index()	{			/* $arr = $this->category_model->getAllCategory();		echo '<pre>';		print_r($arr);		exit; */	
		$this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');				$arrOfCategories = array();
		$arrOfCategories = $this->category_model->fetchCategoryTree();
		
		$this->data['categories'] = $arrOfCategories;
		
		$this->data['page_title'] = $this->lang->line("category");	
		$this->page_construct('category/index', $this->data);	
	}
	function getdatatableajax()	{	
		$this->load->library('datatables');	
		$this->datatables	
		->select("category.id as id, category.name as cname")	
		->from("category")	
		->add_column("Actions", 	
			"<center><div class='btn-group'><a class=\"tip btn btn-primary btn-xs\" title='".$this->lang->line("edit_category")."' href='".site_url('category/edit')."?id=$1'><i class=\"fa fa-edit\"></i></a> <a class=\"tip btn btn-danger btn-xs\" title='".$this->lang->line("delete_category")."' href='".site_url('category/delete')."?id=$1' onClick=\"return confirm('". $this->lang->line('alert_x_category') ."')\"><i class=\"fa fa-trash-o\"></i></a></div></center>", "id")	
		->unset_column('id');	
		echo $this->datatables->generate();	}	function add()
	{
	
		$this->form_validation->set_rules('name', $this->lang->line("name"), 'required');				if ($this->form_validation->run() == true)		{			$this->load->library('upload');						$config['upload_path'] = 'uploads/category/';
			
			$config['allowed_types'] = 'gif|jpg|png|bmp|jpeg';
			
			$config['overwrite'] = FALSE;						$this->upload->initialize($config);						if( ! $this->upload->do_upload('flCategory')){
			
				$error = $this->upload->display_errors();
			
				$this->session->set_flashdata('error', $error);
			
				redirect("category");
			
			}			else			{				/* echo '<pre>';				print_r($this->upload->data());				exit; */								$arrOfImgData = $this->upload->data();								$data = array('name' => $this->input->post('name'),
						'parent_id' => $this->input->post('parent_id'),
						'description' => $this->input->post('description'),						'image' => $arrOfImgData["file_name"],
				);			}		}
	
		if ($this->form_validation->run() == true && $this->category_model->addCategory($data)) {
	
			$this->session->set_flashdata('message', $this->lang->line("category_added"));
	
			redirect("category");
	
		} else {
	
			$this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));						$arrOfCategories = array();			$arrOfCategories = $this->category_model->fetchCategoryTree();						$this->data['parent_category'] = $arrOfCategories;
	
			$this->data['page_title'] = $this->lang->line("new_category");
	
			$this->page_construct('category/add', $this->data);
	
		}	}		function edit($id = NULL)
	{
	
		if($this->input->get('id')) { $id = $this->input->get('id'); }
	
		$this->form_validation->set_rules('name', $this->lang->line("name"), 'required');	
		if ($this->form_validation->run() == true)		{			$this->load->library('upload');															
				
			$config['upload_path'] = 'uploads/category/';
				
			$config['allowed_types'] = 'gif|jpg|png|bmp|jpeg';
				
			$config['overwrite'] = FALSE;
				
			$this->upload->initialize($config);						/* echo '<pre>';			print_r($_FILES);			exit; */						if($_FILES["flCategory"]["name"]!="")			{
				if( ! $this->upload->do_upload('flCategory')){
						
					$error = $this->upload->display_errors();
						
					$this->session->set_flashdata('error', $error);
						
					redirect("category");						
				}
				else
				{										$arrOfImgData = $this->upload->data();						$data = array('name' => $this->input->post('name'),							'parent_id' => $this->input->post('parent_id'),							'description' => $this->input->post('description'),							'image' => $arrOfImgData["file_name"],					);				}			}			else			{				$data = array('name' => $this->input->post('name'),
						'parent_id' => $this->input->post('parent_id'),
						'description' => $this->input->post('description'),
				);			}
	
		}
	
		if ( $this->form_validation->run() == true && $this->category_model->updateCategory($id, $data))
		{
	
			$this->session->set_flashdata('message', $this->lang->line("category_updated"));
	
			redirect("category");
	
		} else {
	
			$this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
	
			$this->data['category'] = $this->category_model->getCategoryByID($id);
	
			$arrOfCategories = $this->category_model->fetchCategoryTree();
				
			$this->data['parent_category'] = $arrOfCategories;
	
			$this->data['id'] = $id;
	
			$this->data['page_title'] = $this->lang->line("update_category");
	
			$this->page_construct('category/edit', $this->data);
	
		}
	
	}		function createCategoryDropdown()	{		$data = array();		$arrOfCategories = $this->category_model->getCategories();								if(!empty($arrOfCategories) && count($arrOfCategories)>0)		{			foreach($arrOfCategories as $parentCat)			{				$data[$parentCat->id] = $parentCat->name;			}		}		return $data;	}		function delete($id = NULL)
	{
		if (DEMO) {
			$this->session->set_flashdata('error', $this->lang->line("disabled_in_demo"));
			redirect('home');
		}

		if($this->input->get('id')) {			$id = $this->input->get('id');		}

		if (!$this->sim->in_group('admin')) {

			$this->session->set_flashdata('error', $this->lang->line("access_denied"));

			redirect('home');
		}

		if ( $this->category_model->deleteCategory($id) )
		{
			$this->session->set_flashdata('message', $this->lang->line("category_deleted"));
			redirect("category");
		}
	}

}