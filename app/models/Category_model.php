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
| This is products module's model file.
| -----------------------------------------------------
*/


class Category_model extends CI_Model{
	public function __construct()	{		parent::__construct();	}		public function fetchCategoryTree($parent = 0, $spacing = '', $user_tree_array = '')	{		if (!is_array($user_tree_array))		{
			$user_tree_array[""] = "";		}
		$q = $this->db->get_where('category', array('parent_id' => $parent));		if($q->num_rows() > 0)		{			foreach ($q->result() as $row)			{
				//$user_tree_array[] = array("id" => $row->id, "name" => $spacing . $row->name);				$user_tree_array[$row->id] = $spacing . $row->name;
				$user_tree_array = $this->fetchCategoryTree($row->id, $spacing . '&nbsp;&nbsp;&nbsp;', $user_tree_array);
			}
		}
		return $user_tree_array;
	}

	public function getAllCategory()	{		$q = $this->db->get('category');
		if($q->num_rows() > 0) {
			foreach (($q->result()) as $row) {
				$data[] = $row;
			}
			return $data;
		}
	}
	public function getCategoryByID($id) 	{
		$q = $this->db->get_where('category', array('id' => $id), 1); 
		  if( $q->num_rows() > 0 )
		  {
			return $q->row();
		  } 		  return FALSE;	}
	public function addCategory($data = array())	{		if($this->db->insert('category', $data)) {
			return true;
		} else {
			return false;
		}
	}
	public function updateCategory($id, $data = array())	{		$this->db->where('id', $id);
		if($this->db->update('category', $data)) {
			return true;
		} else {
			return false;
		}
	}
	public function deleteCategory($id) 	{		$objCat = $this->getCategoryByID($id);				$this->deleteCategoryTree($objCat->id,$objCat->parent_id);				return true;				/*if($this->db->delete('category', array('id' => $id)))		{			return true;		}		return FALSE;*/	}
	public function add_category($data = array())	{		if($this->db->insert_batch('category', $data)) {			return true;		} else {			return false;		}	}
	public function getCategoryByName($name) 	{		$q = $this->db->get_where('category', array('name' => $name), 1); 
		  if( $q->num_rows() > 0 )		  {			return $q->row();		  } 		  return FALSE;	}		function deleteCategoryTree($catId,$parent_id)
	{		$this->db->delete('category', array('id' => $catId));				$q = $this->db->get_where('category', array('parent_id'=>$catId));		if($q->num_rows() > 0)
		{
			foreach ($q->result() as $row)
			{				$id = $row->id;				$parentId = $row->parent_id;
				$this->db->delete('category', array('parent_id' => $row->id));				
				$this->deleteCategoryTree($id, $parentId);
			}
		}	}}