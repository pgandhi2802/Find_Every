<?php

class Model_shop extends CI_Model{

	public function can_log_in()
	{
		$this->db->where('shop_email',$this->input->post('registration_id'));
		$this->db->where('shop_password',md5($this->input->post('password')));
		$query = $this->db->get('shops');
		if($query->num_rows()==1)
			return true;
		else
			return false;
	}

	public function add_temp_shop($key)
	{
		$data=array(
			'shop_cat'=>$this->input->post('shop_cat'),
			'shop_name'=> $this->input->post('shop_name'),
			'shop_email'=> $this->input->post('registration_id'),
			'shop_password'=>md5($this->input->post('password')),
			'key'=>$key
		);
		$query = $this->db->insert('temp_shops',$data);
		if($query)
			return true;
		else
			return false;
	}

	public function city_reg($data)
	{
		$city['city_name']=$data;
		if($this->db->insert('city',$city))
			return true;
		else
			return false;
	}

	public function register_complete($data)
	{
		$this->db->where('shop_email',$data['shop_email']);
		$temp_users=$this->db->get('temp_shops');
		if($temp_users->num_rows())
		{
			$row=$temp_users->row();
			$pass=$row->shop_password;
			$name=$row->shop_name;
			$add_data=array(
				'shop_name'=>$name,
				'shop_password'=>$pass
			);
			$data=$add_data+$data;
			$did_add_user=$this->db->insert('shops',$data);
			// print_r($data);
			if($did_add_user)
			{
				$this->db->where('shop_email',$data['shop_email']);
				$this->db->delete('temp_shops');
				return true;
			}
			else
				return false;
		}
		else
			return false;
	}

	public function confirm_register($email,$key)
	{
		$this->db->where('key',$key);
		$this->db->where('shop_email',$email);
		$query=$this->db->get('temp_shops');
		if($query->num_rows()==1)
			return 1;
		else
		{
			$this->db->where('shop_email',$email);
			$query=$this->db->get('shops');
			if($query->num_rows()==1)
				return 2;
			else
				return 0;
		}
	}

	public function get_shop_cat($email)
	{
		$this->db->where('shop_email',$email);
		$query=$this->db->get('temp_shops');
		foreach ($query->result() as $row)
			return $row->shop_cat;
	}

	public function get_cat($big_cat)
	{
		$this->db->where("parent_cat",$big_cat);
		$query = $this->db->get('category');
		return $query->result();
	}

	public function get_fix_cat($cat)
	{
		$this->db->where("cat_id",$cat);
		$query = $this->db->get('category');
		return $query->result();
	}

	public function get_shop_name($email)
	{
		$this->db->where('shop_email',$email);
		$query=$this->db->get('temp_shops');
		foreach ($query->result() as $row)
			return $row->shop_name;
	}

	public function check_cat_exist($str)
	{
		$this->db->where('cat_id',$str);
		$query = $this->db->get('category');
		if($query->num_rows()== 1)
			return true;
		else
			return false;
	}

	public function get_profile($email)
	{
		$this->db->where('shop_email',$email);
		$query = $this->db->get('shops');
		return $query->result();
	}

	public function get_avg_rating($shop){
		$this->db->where('shop_id',$shop);
		$this->db->select("AVG(rating) avg_rating");
		$query=$this->db->get('shop_rating');
		foreach($query->result() as $row)
			return $row->avg_rating;
	}
	public function get_reviews($shop){
		$this->db->where("shop_id",$shop);
		$this->db->order_by("date_time","asc");
		$this->db->limit(5,0);
		$query=$this->db->get('shop_review');
		return $query->result();
	}

	public function get_shop_id($data)
	{
		$this->db->where('shop_email',$data);
		$query=$this->db->get('shops');
		foreach ($query->result() as $row)
			return $row->shop_id;
	}

	public function get_shop_type($data)
	{
		$this->db->where('shop_email',$data);
		$query=$this->db->get('shops');
		foreach ($query->result() as $row)
			return $row->shop_cat;
	}

	public function edit_profile_complete($data,$shop_id)
	{
		$this->db->where('shop_id',$shop_id);
		$query=$this->db->get('edit_shops');
		if($query->num_rows()==1)
		{
			$this->db->where('shop_id',$shop_id);
			if($this->db->update('edit_shops',$data))
			{
				if($this->edit_shop_table($shop_id))
					return true;
				else
					return false;
			}
			else
				return false;
		}
		else
		{
			if($this->db->insert('edit_shops',$data))
			{
				if($this->edit_shop_table($shop_id))
					return true;
				else
					return false;
			}
			else
				return false;
		}
	}

	public function edit_shop_table($shop_id)
	{
		$data['edit_shop_approved']=1;
		$this->db->where('shop_id',$shop_id);
		if($this->db->update('shops',$data))
			return true;
		else
			return false;
	}

}

?>