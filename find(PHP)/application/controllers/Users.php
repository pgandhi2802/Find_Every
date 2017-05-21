<?php
defined("BASEPATH") OR exit("No direct script access allowed");

class Users extends CI_Controller{

	public function index()
	{
		redirect("shop/","refresh");
	}

	public function get_cat(){
		$this->load->model("model_users");
		header('Content-type: application/json');
		$response=array();
		$response['success']=1;
		$response['category']=array();
		$parent_cat=$this->input->post('parent_cat');
		$response['category']=$this->model_users->get_cat($parent_cat);
		echo json_encode($response);
	}

	public function get_city(){
		$this->load->model("model_users");
		header('Content-type: application/json');
		$response=array();
		$response['success']=1;
		$response['city']=array();
		$response['city']=$this->model_users->get_city();
		echo json_encode($response);
	}

	public function login(){
		$this->load->model("model_users");
		header("Content-type: application/json");
		$response = array();
		if($this->model_users->can_log_in())
		{
			$response['success']=1;
			$response['user']=array();
			$response['user']=$this->model_users->get_mini_detail();
		}
		else
			$response['success']=0;
		echo json_encode($response);
	}

	public function register(){
		$this->load->model("model_users");
		header("Content-type: application/json");
		$response=array();
		$response['success']=0;
		$response['error']="problem while signing Up";
		$this->load->library('form_validation');
		$this->form_validation->set_rules('userEmail','Shop Name/Event Name','is_unique[User.email]');
		$this->form_validation->set_rules('userPhone','Shop Name/Event Name','is_unique[User.phone]');
		if($this->form_validation->run())
		{
			if($this->model_users->register())
			{
				$response['success']=1;
			}
		}
		else
		{

		}
		echo json_encode($response);
	}

	public function get_shop_location(){
		$this->load->model("model_users");
		header('Content-type: application/json');
		$response=array();
		$response['success']=1;
		if($_SERVER['REQUEST_METHOD']=="POST")
		{
			$response['shops']=array();
			$cat=$this->input->post("user_small_cat");
			switch ($this->input->post("user_big_cat")) {
				case 1:
		// 			// SHOP
					$response['big_cat']=$this->input->post("user_big_cat");
					if($this->input->post("user_city_selected")==1)
					{
						$response['city']=$this->model_users->getCityName($this->input->post("user_city"));
						$city=$this->model_users->getCityName($this->input->post("user_city"));
						// $response['query']=$this->model_users->get_location_shop_city($cat,$city);
						$response['shops']=$this->model_users->get_location_shop_city($cat,$city);
					}
					else
					{
						$lat=$this->input->post("user_lat");
						$lng=$this->input->post("user_lng");
						$radius=$this->input->post("user_radius");
						$response['shops']=$this->model_users->get_location_shop_location($cat,$lat,$lng,$radius);
					}
					break;
				case 2:
		// 			// EVENT
					$day=$this->input->post("user_day");
					$month=$this->input->post("user_month");
					$year=$this->input->post("user_year");
					$response['big_cat']=$this->input->post("user_big_cat");

					if($this->input->post("user_city_selected")==1)
					{
						$response['city']=$this->model_users->getCityName($this->input->post("user_city"));
						$city=$this->model_users->getCityName($this->input->post("user_city"));
						$response['shops']=$this->model_users->get_location_event_city($cat,$city,$day,$month,$year);
					}
					else
					{
						$lat=$this->input->post("user_lat");
						$lng=$this->input->post("user_lng");
						$radius=$this->input->post("user_radius");
						$response['shops']=$this->model_users->get_location_event_location($cat,$day,$month,$year,$lng,$lat,$radius);
					}

					break;
				default:
					$response['success']=0;
					$response['Error']="Something Went Wrong";
			}
		}
		else
		{
			$response['success']=0;
			$response['Error']="Something Went Wrong";
		}

		echo json_encode($response);
	}

	public function get_shop_details(){
		$shop_id=$this->input->post("shop_id");
		header('Content-type: application/json');
		$this->load->model("model_users");
		$response=array();
		$response['success']=1;
		$response['shop_details']=array();
		$response['shop_details']=$this->model_users->get_shop_detail($shop_id);
		if(!empty($this->input->post("user_id")))
		{
			$user_id=$this->input->post("user_id");
			$response['shop_user_rating']=$this->model_users->get_shop_user_rating($shop_id,$user_id);
		}
		else
			$response['shop_user_rating']=0;
		$response['shop_avg_rating']=$this->model_users->get_shop_avg_rating($shop_id);
		$response['shop_review']=array();
		$response['shop_review']=$this->model_users->getReview($shop_id);
		echo json_encode($response);
	}


	public function getCityName($city){
		$this->load->model('model_users');
		echo $this->model_users->getCityName($city);
	}


	public function rate_shop(){
		header('Content-type: application/json');
		$this->load->model("model_users");
		$response=array();
		if($this->model_users->give_rate())
			$response['success']=1;
		else
			$response['success']=0;
		echo json_encode($response);
	}

	public function review_shop(){
		header('Content-type: application/json');
		$this->load->model("model_users");
		$response=array();
		if($this->model_users->give_review())
			$response['success']=1;
		else
			$response['success']=0;
		echo json_encode($response);
	}

	public function add_user_cat(){
		header('Content-type: application/json');
		$this->load->model("model_users");
		$response=array();
		if($this->model_users->add_user_cat())
			$response['success']=1;
		else
			$response['success']=0;
		echo json_encode($response);
	}

	
}

?>