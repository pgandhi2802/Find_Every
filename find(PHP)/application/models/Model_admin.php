<?php

class Model_admin extends CI_Model{

	public function admin_log_in()
	{
		$this->db->where('admin_user',$this->input->post('admin_id'));
		$this->db->where('admin_password',md5($this->input->post('password')));
		$query = $this->db->get('admin_detail');
		if($query->num_rows()==1)
			return true;
		else
			return false;
	}

	public function add_cat($data,$data1)
	{
		$cat_data['parent_cat']=$data1;
		$cat_data['cat_name']=$data;
		if($this->db->insert('category',$cat_data))
			return true;
		else
			return false;
	}

	public function show_cat()
	{
		$query = $this->db->get('category');
		return $query->result();
	}

	public function del_cat($row)
	{
		$this->db->where("cat_id",$row);
		if($this->db->delete("category"))
			return true;
		else
			return false;
	}

	public function get_new_entry($count,$block_size)
	{
		$this->db->where('shop_approved',0);
		$this->db->limit($block_size,(($count-1)*$block_size));
		$query=$this->db->get('shops');
		return $query->result();
	}

	public function get_edit_new_entry($count,$block_size)
	{
		$this->db->where('edit_shop_approved',1);
		$this->db->limit($block_size,(($count-1)*$block_size));
		$query=$this->db->get('shops');
		return $query->result();
	}

	public function get_count_new_entry()
	{
		$this->db->where('shop_approved',0);
		return $this->db->count_all_results('shops');
	}

	public function get_count_edit_new_entry()
	{
		$this->db->where('edit_shop_approved',1);
		return $this->db->count_all_results('shops');
	}

	public function check_entry($value)
	{
		$this->db->where('shop_id',$value);
		$query = $this->db->get('shops');
		if($query->num_rows()==1)
			return true;
		else
			return false;
	}

	public function check_edit_entry($value)
	{
		$this->db->where('shop_id',$value);
		$query = $this->db->get('shops');
		if($query->num_rows()==1)
			return true;
		else
			return false;
	}
	
	public function approve_new($value)
	{
		$data['shop_approved']=1;
		$this->db->where("shop_id",$value);
		$this->db->update("shops",$data);
	}

	public function check_big_cat($value){
		$this->db->where('shop_id',$value);
		$this->db->where('shop_cat','1');
		$query = $this->db->get('shops');
		if($query->num_rows()==1)
			return true;
		else
			return false;
	}

	public function get_small_cat_no($value){
		$this->db->where("shop_id",$value);
		$this->db->select('shop_category');
		$query=$this->db->get('shops');
		foreach ($query->result() as $row)
			return $row->shop_category;
	}

	public function get_small_cat_name($value){
		$this->db->where("cat_id",$value);
		$this->db->select('cat_name');
		$query=$this->db->get('category');
		foreach ($query->result() as $row)
			return $row->cat_name;
	}

	public function get_user_id_for_notification($category_no){
		$this->db->where('cat_id',$category_no);
		$this->db->distinct();
		$this->db->select('user_id');
		$query=$this->db->get('user_history');
		return $query->result();
	}

	public function get_regId($value){
		$this->db->where("user_id",$value);
		$this->db->select('app_regId');
		$query=$this->db->get('User');
		foreach ($query->result() as $row)
			return $row->app_regId;
	}

	public function approve_edit_new($value)
	{
		$this->db->where('shop_id',$value);
		$query=$this->db->get('edit_shops');
		$data=array();
		foreach ($query->result() as $row)
		{
			$data['shop_start_date']=$row->shop_start_date;
			$data['shop_end_date']=$row->shop_end_date;
			$data['shop_lat']=$row->shop_lat;
			$data['shop_lng']=$row->shop_lng;
			$data['shop_city']=$row->shop_city;
			$data['shop_pincode']=$row->shop_pincode;
			$data['shop_add_line_1']=$row->shop_add_line_1;
			$data['shop_add_line_2']=$row->shop_add_line_2;
			$data['shop_descr']=$row->shop_descr;
		}
		$data['edit_shop_approved']=2;
		$this->db->where("shop_id",$value);
		if($this->db->update("shops",$data))
		{
			$this->db->where("shop_id",$value);
			$this->db->delete('edit_shops');
		}
	}

	public function delete_approve_new($value)
	{
		$this->db->where("shop_id",$value);
		$this->db->delete("shops");
	}

	public function show_approve_new($value)
	{
		$this->db->where('shop_id',$value);
		$query=$this->db->get("shops");
		return $query->result();
	}

	public function show_approve_edit_new($value)
	{
		$this->db->where('shop_id',$value);
		$query=$this->db->get("edit_shops");
		return $query->result();
	}
}