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


class Category_model extends CI_Model
	public function __construct()
			$user_tree_array[""] = "";
		$q = $this->db->get_where('category', array('parent_id' => $parent));
				//$user_tree_array[] = array("id" => $row->id, "name" => $spacing . $row->name);
				$user_tree_array = $this->fetchCategoryTree($row->id, $spacing . '&nbsp;&nbsp;&nbsp;', $user_tree_array);
			}
		}
		return $user_tree_array;
	}

	public function getAllCategory()
		if($q->num_rows() > 0) {
			foreach (($q->result()) as $row) {
				$data[] = $row;
			}
			return $data;
		}
	}
	public function getCategoryByID($id) 
		$q = $this->db->get_where('category', array('id' => $id), 1); 
		  if( $q->num_rows() > 0 )
		  {
			return $q->row();
		  } 
	public function addCategory($data = array())
			return true;
		} else {
			return false;
		}
	}
	public function updateCategory($id, $data = array())
		if($this->db->update('category', $data)) {
			return true;
		} else {
			return false;
		}
	}
	public function deleteCategory($id) 
	public function add_category($data = array())
	public function getCategoryByName($name) 
		  if( $q->num_rows() > 0 )
	{
		{
			foreach ($q->result() as $row)
			{
				$this->db->delete('category', array('parent_id' => $row->id));
				$this->deleteCategoryTree($id, $parentId);
			}
		}