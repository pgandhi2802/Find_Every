<?php

class Model_users extends CI_Model{

	public function get_cat($parent_cat){
		$this->db->where("parent_cat",$parent_cat);
		$this->db->select('cat_id,cat_name');
		$query=$this->db->get('category');
		return $query->result();
	}

	public function get_city(){
		$this->db->select('city_id,city_name');
		$query=$this->db->get('city');
		return $query->result();
	}

	public function can_log_in()
	{
		$this->db->where('email',$this->input->post('user_name'));
		$this->db->where('password',md5($this->input->post('password')));
		$query = $this->db->get('User');
		if($query->num_rows()==1)
			return true;
		else
			return false;
	}

	public function get_mini_detail(){
		$this->db->where('email',$this->input->post('user_name'));
		$this->db->select('user_id,email');
		$query = $this->db->get('User');
		return $query->result();

	}

	public function register(){
		$data = array(
			'first_name' => $this->input->post('userFirstName'),
			'last_name' => $this->input->post('userLastName'),
			'email' => $this->input->post('userEmail'),
			'phone'=>$this->input->post('userPhone'),
			'app_regId' => $this->input->post('userRegID'),
			'password'=>md5($this->input->post('userPassword')));
			if($this->db->insert('User',$data))
				return true;
			else
				return false;
	}

	public function add_user_cat(){
		$data = array(
			'user_id' => $this->input->post("user_id"),
			'cat_id' => $this->input->post("cat_id"));
		if($this->db->insert('user_history',$data))
			return true;
		else
			return false;
	}

	public function getCityName($city){
		$this->db->where('city_id',$city);
		$this->db->select('city_name');
		$query=$this->db->get('city');
		foreach ($query->result() as $row)
			return $row->city_name;
	}

	public function get_location_shop_city($cat,$city){
		$sql="select * from `shops` where `shop_category`='".$cat."' and `shop_city`='".$city."' and `shop_approved`='1'";
		
		$result=$this->db->query($sql);
		return $result->result();
		
	}

	public function get_location_shop_location($cat,$lat,$lng,$radius){
		$sql="select `shop_id`,`shop_name`,`shop_lat`,`shop_lng`,(DEGREES(acos(sin(RADIANS(".$lat.")) * sin(RADIANS(`shop_lat`)) + cos(RADIANS(".$lat.")) * cos(RADIANS(`shop_lat`)) * cos(RADIANS(".$lng."-`shop_lng`))))* 60 * 1.1515* 1.609344) as distance from shops where (DEGREES(acos(sin(RADIANS(".$lat.")) * sin(RADIANS(`shop_lat`)) + cos(RADIANS(".$lat.")) * cos(RADIANS(`shop_lat`)) * cos(RADIANS(".$lng."-`shop_lng`))))* 60 * 1.1515* 1.609344) < ".$radius." and `shop_category`=".$cat." and `shop_approved`=1";
		$result=$this->db->query($sql);
		return $result->result();
	}
	public function get_location_event_city($cat,$city,$day,$month,$year){
		$sql="select `shop_id`,`shop_name`,`shop_lat`,`shop_lng` from shops where `shop_start_date` <= '".$year."-".$month."-".$day."' AND `shop_end_date` >='".$year."-".$month."-".$day."' AND `shop_city`= '".$city."' AND `shop_category` = ".$cat." AND `shop_approved`=1";
		$query=$this->db->query($sql);
		return $query->result();
		return $sql;
	}
	public function get_location_event_location($cat,$day,$month,$year,$lng,$lat,$radius){
		$sql="select `shop_id`,`shop_name`,`shop_lat`,`shop_lng`,(DEGREES(acos(sin(RADIANS(".$lat.")) * sin(RADIANS(`shop_lat`)) + cos(RADIANS(".$lat.")) * cos(RADIANS(`shop_lat`)) * cos(RADIANS(".$lng."-`shop_lng`))))* 60 * 1.1515* 1.609344) as distance from shops where (DEGREES(acos(sin(RADIANS(".$lat.")) * sin(RADIANS(`shop_lat`)) + cos(RADIANS(".$lat.")) * cos(RADIANS(`shop_lat`)) * cos(RADIANS(".$lng."-`shop_lng`))))* 60 * 1.1515* 1.609344) < ".$radius." and `shop_category` =".$cat." and `shop_approved`=1 AND `shop_start_date` <= '".$year."-".$month."-".$day."' AND `shop_end_date` >='".$year."-".$month."-".$day."'";
		$query=$this->db->query($sql);
		return $query->result();	
	}


	
	public function get_shop_detail($id){
		$this->db->select('shop_id,shop_name,shop_lat,shop_lng,shop_email,shop_city,shop_pincode,shop_contact_no,shop_add_line_1,,shop_add_line_2,shop_descr');
		$this->db->where('shop_id',$id);
		return $this->db->get('shops')->result();
	}

	public function get_shop_user_rating($shop,$user){
		$this->db->where('shop_id',$shop);
		$this->db->where('user_id',$user);
		$query=$this->db->get('shop_rating');
		foreach($query->result() as $row)
			return $row->rating;
	}

	public function get_shop_avg_rating($shop){
		$this->db->where('shop_id',$shop);
		$this->db->select("AVG(rating) avg_rating");
		$query=$this->db->get('shop_rating');
		foreach($query->result() as $row)
			return $row->avg_rating;
	}

	public function getReview($shop){
		$this->db->where("shop_id",$shop);
		$this->db->order_by("date_time","asc");
		$this->db->limit(5,0);
		$query=$this->db->get('shop_review');
		return $query->result();
	}

	public function give_rate(){
		$this->db->where('shop_id',$this->input->post("shop_id"));
		$this->db->where('user_id',$this->input->post("user_id"));
		$query=$this->db->get('shop_rating');
		if($query->num_rows()==1)
		{
			$data['rating']=$this->input->post("rating");
			$this->db->where('shop_id',$this->input->post("shop_id"));
			$this->db->where('user_id',$this->input->post("user_id"));
			$this->db->update('shop_rating',$data);
			return true;
		}
		else
		{
			$data = array(
				'shop_id' => $this->input->post("shop_id") ,
				'user_id' => $this->input->post("user_id"),
				'rating' => $this->input->post("rating") );
			$this->db->insert("shop_rating",$data);
			return true;
		}
		return false;
	}
	public function give_review(){
		$data = array(
			'shop_id' => $this->input->post("shop_id"),
			'user_id' => $this->input->post("user_id"),
			'review' => $this->input->post("review"));
		$this->db->insert("shop_review",$data);
		return true;
	}
}

?>