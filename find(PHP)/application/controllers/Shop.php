<?php
defined("BASEPATH") OR exit("No direct script access allowed");

class Shop extends CI_Controller{

	/*
		session validation check (shop_logged_in)
		1 => shop confirmation session is active
		2 => shop login session is active
	*/

	public function index()
	{
		switch($this->session->userdata("shop_logged_in"))
		{
			case 1:
				redirect("shop/fill_details","refresh");
				break;
			case 2:
				redirect("shop/profile","refresh");
				break;
			default:
				$this->load->view('Welcome_shop');
		}
	}

	public function register()
	{
		switch($this->session->userdata("shop_logged_in"))
		{
			case 1:
				redirect("shop/fill_details","refresh");
				break;
			case 2:
				redirect("shop/profile","refresh");
				break;
			default:
				if($_SERVER['REQUEST_METHOD']=="POST")
				{
					if($this->signup_validation())
					{
						// store in database
						$key=md5(uniqid());
						$this->load->model('model_shop');
						if($this->model_shop->add_temp_shop($key))
						{
							//send mail
							if($this->signup_sendmail($key))
							{
								$data["title"]="Mail Send";
								$message="<p>the email has been sent</p><p>Please Check your Inbox</p>";
								$data["display_msg"]=$message;
								$this->load->view('Error_message',$data);
							}
							else
							{
								$data["title"]="Mail Send";
								$message="<p>Thank you for signup with us</p>";
								$message.="<p><a href='".base_url()."shop/confirm_register/?q=".$this->input->post('registration_id')."&en_q=".$key."'>Click Here </a>&nbsp; to confirm your registration</p>";
								$data["display_msg"]=$message;
								$this->load->view("Error_message",$data);
								// $data['title']="Mail Sending Error";
								// $data['display_msg']="problem ocuured while sending Mail<br />Please try after siometime";
								// $this->load->view('Error_message',$data);
							}
						}
						else
						{
							$data['title']="Signup Error";
							$data['display_msg']="problem ocuured while signing up";
							$this->load->view('Error_message',$data);
						}
					}
					else
					{
						$data["signup_error"]=true;
						$this->load->view("Register_shop",$data);
					}
				}
				else
				{
					$data["signup_error"]=false;
					$this->load->view('Register_shop',$data);
				}
		}
	}

	private function signup_validation()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('shop_name','Shop Name/Event Name','required|trim|callback_fullname_check');
		$this->form_validation->set_rules('registration_id','Registration ID/Email','required|trim|valid_email|is_unique[shops.shop_email]|is_unique[temp_shops.shop_email]');
		$this->form_validation->set_rules('password','Password','required|trim');
		$this->form_validation->set_rules('confirm_password','Confirm Password','required|trim|matches[password]');
		$this->form_validation->set_message('is_unique',"That email you have entered already exist in our database");
		$this->form_validation->set_message('matches',"Passwords do not match");
		if($this->form_validation->run())
			return true;
		else
			return false;
	}

	public function fullname_check($str)
	{
		if(!preg_match("/^([a-z ])+$/i", $str))
		{
			$this->form_validation->set_message('fullname_check', '%s field can have only alphabetical and space');
			return false;
		}
		else
			return true;
	}

	private function signup_sendmail($key)
	{
		$this->load->library('email',array('mailtype' => 'html'));
		$this->email->from('p.gandhi2802@gmail.com','Piyush Gandhi');
		$this->email->to($this->input->post('shop_email'));
		$this->email->subject("confirm ypu subscription");
		$message="<p>Thank you for signup with us</p>";
		$message.="<p><a href='".base_url()."shop/confirm_register/?q=".$this->input->post('registration_id')."&en_q=".$key."'>Click Here </a>&nbsp; to confirm your registration</p>";
		$this->email->message($message);
		if($this->email->send())
			return true;
		else
			return false;
	}

	public function confirm_register()
	{
		switch ($this->session->userdata("shop_logged_in"))
		{
			case 1:
				redirect("shop/fill_details","refresh");
				break;
			case 2:
				redirect("shop/profile","refresh");
				break;
			default:
				if($_SERVER["REQUEST_METHOD"]=="GET")
				{
					if((isset($_GET["q"]))&&(isset($_GET['en_q'])))
					{
						$email=$this->input->get('q');
						$key=$this->input->get('en_q');
						$this->load->model('model_shop');
						$success_code=$this->model_shop->confirm_register($email,$key);
						/*
							Success==0 => wrong email confirmation
							Success==1 => email confirmation successfull
							Success==2 => already registered
						*/
						if($success_code==0)
						{
							$data["title"]="Confirmation Error";
							$message="<h3>Invalid key or email.</h3><p>please,check your email or <a href='".base_url()."shop/register'>Click here to register</a></p>";
							$data['display_msg']=$message;
							$this->load->view('Error_message',$data);
						}
						else if($success_code==1)
						{
							$this->load->model('model_shop');
							/*1-shop 2-event*/
							$shop_cat_no=$this->model_shop->get_shop_cat($email);
							$shop_name=$this->model_shop->get_shop_name($email);
							if($shop_cat_no==1)
								$shop_cat="Shop";
							else
								$shop_cat="Event";
							$se_data=array(
								'shop_cat_no'=>$shop_cat_no,
								'shop_cat'=>$shop_cat,
								'shop_name'=>$shop_name,
								'shop_email'=>$email,
								'shop_logged_in'=>1,
								'key'=>$key
							);
							$this->session->set_userdata($se_data);
							$data["title"]="Email Confirm";
							$message="<h3>You have verified your email.</h3><p><a href='".base_url()."shop/fill_details'>Click here to fill Details</a></p>";
							$data['display_msg']=$message;
							$this->load->view('Error_message',$data);
						}
						else if($success_code==2)
						{
							$message="<h3>You have already registered with Us.</h3><p><a href='".base_url()."shop/login'>Click here to Login</a></p>";
							$data['display_msg']=$message;
							$this->load->view('Error_message',$data);
						}
					}
					else
						redirect("shop/register","refresh");
				}
				else
					redirect("shop/register","refresh");
		}
	}

	public function fill_details()
	{
		switch ($this->session->userdata("shop_logged_in")) {
			case 1:
				if($_SERVER["REQUEST_METHOD"]=="POST")
				{
					$this->load->model("model_shop");
					if($this->register_complete())
					{
						if($this->model_shop->city_reg($this->input->post('shop_city')))
						{
							$data['shop_cat']=$this->session->userdata('shop_cat_no');
							$data['shop_category']=$this->input->post('shop_sub_cat');
							$data["shop_email"]=$this->session->userdata('shop_email');
							$data['shop_lat']=$this->input->post('shop_lat');
							$data['shop_lng']=$this->input->post('shop_lng');
							$data['shop_city']=$this->input->post('shop_city');
							$data['shop_pincode']=$this->input->post('shop_pincode');
							$data['Shop_contact_no']=$this->input->post('shop_contact_no');
							$data['shop_add_line_1']=$this->input->post('shop_add_line_1');
							$data['shop_add_line_2']=$this->input->post('shop_add_line_2');
							$data['shop_descr']=$this->input->post('shop_descr');
							if($this->session->userdata('shop_cat_no')==2)
							{
								$data['shop_start_date']=date('Y-m-d', strtotime($this->input->post('start_date')));
								$data['shop_end_date']=date('Y-m-d', strtotime($this->input->post('end_date')));
							}
							$data['shop_approved']=0;
							$data['edit_shop_approved']=0;
							if($this->model_shop->register_complete($data))
							{
								$this->session->sess_destroy();
								$se_data=array(
									'shop_logged_in'=>2,
									'shop_is'=>$this->model_shop->get_shop_id($this->input->post('registration_id')),
									'shop_email'=>$this->input->post('registration_id'),
								);
								$this->session->set_userdata($se_data);
								redirect('shop/profile', 'refresh');
							}
							else
							{
								$data['display_msg']="<h4>Some error has been occcured</h4>Try After some time";
								$this->load->view('Error_message',$data);
							}
						}
						else
						{
							$data['display_msg']="<h4>Some error has been occcured</h4>Try After some time";
							$this->load->view('Error_message',$data);
						}

					}
					else
					{
						$data["fill_details_error"]=true;
						$data['shop_cat_no']=$this->session->userdata('shop_cat_no');
						$data["shop_cat"]=$this->session->userdata('shop_cat');
						$data["shop_name"]=$this->session->userdata('shop_name');
						$data["shop_email"]=$this->session->userdata('shop_email');
						$data["cat_list"]=array();
						$data["cat_list"]=$this->model_shop->get_cat($data['shop_cat_no']);
						$this->load->view('Fill_details',$data);
					}

				}
				else
				{
					$this->load->model('model_shop');
					$data["fill_details_error"]=false;
					$data['shop_cat_no']=$this->session->userdata('shop_cat_no');
					$data["shop_cat"]=$this->session->userdata('shop_cat');
					$data["shop_name"]=$this->session->userdata('shop_name');
					$data["shop_email"]=$this->session->userdata('shop_email');
					$data["cat_list"]=array();
					$data["cat_list"]=$this->model_shop->get_cat($data['shop_cat_no']);
					$this->load->view('Fill_details',$data);
				}
				break;
			case 2:
				redirect("shop/profile","refresh");
				break;
			default:
				redirect("shop/register","refersh");
		}
	}

	private function register_complete()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('shop_sub_cat','Category','required|trim|callback_check_category');
		$this->form_validation->set_rules('shop_city','City','required|trim|callback_fullname_check');
		$this->form_validation->set_rules('shop_pincode','Pincode','required|trim|is_natural_no_zero|exact_length[6]');
		$this->form_validation->set_rules('shop_contact_no','Contact Number','required|trim|is_natural_no_zero|exact_length[10]');
		$this->form_validation->set_rules('shop_add_line_1','Address Line 1','required|trim|callback_fullname_check');
		$this->form_validation->set_rules('shop_add_line_2','Address Line 2','required|trim|callback_fullname_check');
		if($this->session->userdata('shop_cat_no')==2)
		{
			$this->form_validation->set_rules('start_date','Start Date','required|trim|callback_check_date_validation');
			$this->form_validation->set_rules('end_date','End Date','required|trim|callback_check_date_validation');
		}
		$this->form_validation->set_rules('shop_descr','Description','required|trim|callback_fullname_check|max_length[150]');
		$this->form_validation->set_rules('shop_lat','Latitude','required|trim');
		$this->form_validation->set_rules('shop_lng','Longitude','required|trim');
		if($this->form_validation->run())
			return true;
		else
			return false;
	}

	public function check_category($str)
	{
		$this->load->model('model_shop');
		if($this->model_shop->check_cat_exist($str))
			return true;
		else
		{
			$this->form_validation->set_message('check_category','Please Select Appropriate category');
			return false;
		}
	}

	public function login()
	{
		switch ($this->session->userdata("shop_logged_in")) {
			case 1:
				redirect("fill_details","refersh");
				break;
			case 2:
				redirect("shop/profile","refersh");
				break;
			default:
				if($_SERVER['REQUEST_METHOD']=="POST")
				{
					if($this->login_validation())
					{
						$this->load->model('model_shop');
						$se_data=array(
							'shop_logged_in'=>2,
							'shop_id'=>$this->model_shop->get_shop_id($this->input->post('registration_id')),
							'shop_email'=>$this->input->post('registration_id'),
						);
						$this->session->set_userdata($se_data);
						redirect('shop/profile/', 'refresh');
					}
					else
					{
						$data['login_error']=true;
						$this->load->view('Login_shop',$data);
					}
				}
				else
				{
					$data['login_error']=false;
					$this->load->view('Login_shop',$data);
				}
		}
	}

	private function login_validation()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('registration_id','Registration ID/Email ID','required|trim|callback_check_login_credentials');
		$this->form_validation->set_rules('password','Password','required|md5|trim');
		if($this->form_validation->run())
			return true;
		else
			return false;
	}

	public function check_login_credentials()
	{
		$this->load->model('model_shop');
		if($this->model_shop->can_log_in())
			return true;
		else
		{
			$this->form_validation->set_message('check_login_credentials',"Incorrect Registration ID/Email id or Password");
			return false;
		}
	}

	public function profile()
	{
		switch ($this->session->userdata("shop_logged_in")) {
			case 1:
				redirect("shop/fill_details","refresh");
			case 2:
				$email=$this->session->userdata('shop_email');
				$this->load->model("model_shop");
				$data["profile"]=array();
				$data["profile"]=$this->model_shop->get_profile($email);
				$data["category"]=$this->model_shop->get_fix_cat($data["profile"][0]->shop_category);
				$data["avg_rating"]=$this->model_shop->get_avg_rating($this->session->userdata('shop_id'));
				$data["reviews"]=array();
				$data["reviews"]=$this->model_shop->get_reviews($this->session->userdata('shop_id'));
				$data["shop_id"]=$this->session->userdata('shop_id');
				$this->load->view("Shop_profile",$data);
				break;
			default:
				redirect("shop/login","refresh");

		}
	}

	public function edit_profile()
	{
		switch ($this->session->userdata("shop_logged_in")) {
			case 1:
				redirect("shop/fill_details","refresh");
				break;
			case 2:
				if($_SERVER["REQUEST_METHOD"]=="POST")
					{
						if($this->edit_validation_profile())
						{
							$this->load->model("model_shop");
							$data["shop_id"]=$this->session->userdata('shop_id');
							$data['shop_lat']=$this->input->post('shop_lat');
							$data['shop_lng']=$this->input->post('shop_lng');
							$data['shop_city']=$this->input->post('shop_city');
							$data['shop_pincode']=$this->input->post('shop_pincode');
							$data['Shop_contact_no']=$this->input->post('shop_contact_no');
							$data['shop_add_line_1']=$this->input->post('shop_add_line_1');
							$data['shop_add_line_2']=$this->input->post('shop_add_line_2');
							echo $this->model_shop->get_shop_type($this->session->userdata('shop_email'));
							if($this->model_shop->get_shop_type($this->session->userdata('shop_email'))==2)
							{
								$data['shop_start_date']=date('Y-m-d', strtotime($this->input->post('start_date')));
								 	$data['shop_end_date']=date('Y-m-d', strtotime($this->input->post('end_date')));
							}
							$data['shop_descr']=$this->input->post('shop_descr');
							echo $this->session->userdata('shop_id');
							if($this->model_shop->edit_profile_complete($data,$this->session->userdata('shop_id')))
							{
								redirect('shop/profile','refresh');
							}
							else
							{
								$data['display_msg']="<h4>Some error has been occcured</h4>Try After some time";
								$this->load->view('Error_message',$data);
							}
						}
						else
						{
							$data["edit_profile_error"]=true;
							$email=$this->session->userdata('shop_email');
							$this->load->model("model_shop");
							$data["profile"]=array();
							$data["profile"]=$this->model_shop->get_profile($email);
							$data["category"]=$this->model_shop->get_fix_cat($data["profile"][0]->shop_category);
							$this->load->view("Edit_profile",$data);
						}	
					}
					else
					{
						$data["edit_profile_error"]=false;
						$email=$this->session->userdata('shop_email');
						$this->load->model("model_shop");
						$data["profile"]=array();
						$data["profile"]=$this->model_shop->get_profile($email);
						$data["category"]=$this->model_shop->get_fix_cat($data["profile"][0]->shop_category);
						$this->load->view("Edit_profile",$data);
					}
				break;
			default:
				redirect('shop/login/', 'refresh');
		}
	}

	private function edit_validation_profile()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('shop_city','City','required|trim|callback_fullname_check');
		$this->form_validation->set_rules('shop_pincode','Pincode','required|trim|is_natural_no_zero|exact_length[6]');
		$this->form_validation->set_rules('shop_contact_no','Contact Number','required|trim|is_natural_no_zero|exact_length[10]');
		$this->form_validation->set_rules('shop_add_line_1','Address Line 1','required|trim|callback_fullname_check');
		$this->form_validation->set_rules('shop_add_line_2','Address Line 2','required|trim|callback_fullname_check');
		$this->load->model('model_shop');
		if($this->model_shop->get_shop_type($this->session->userdata('shop_email'))==2)
		{
			$this->form_validation->set_rules('start_date','Start Date','required|trim|callback_check_date_validation');
			$this->form_validation->set_rules('end_date','End Date','required|trim|callback_check_date_validation');
		}
		$this->form_validation->set_rules('shop_descr','Description','required|trim|callback_fullname_check|max_length[150]');
		$this->form_validation->set_rules('shop_lat','Latitude','required|trim');
		$this->form_validation->set_rules('shop_lng','Longitude','required|trim');
		if($this->form_validation->run())
			return true;
		else
			return false;
	}

	public function logout()
	{
		$this->session->sess_destroy();
		redirect('shop/login');
	}

	public function check_date_validation($pt_st)
	{
		$dt=date('Y-m-d');
		$pt=date("Y-m-d", strtotime($pt_st));
		if($pt>$dt)
			return true;
		else
		{
			$this->form_validation->set_message('check_date_validation','Please Select Future Date');
			return false;
		}
	}
}

?>