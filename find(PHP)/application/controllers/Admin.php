<?php
defined("BASEPATH") OR exit("No direct script access allowed");

class Admin extends CI_Controller{

	public function index()
	{
		switch($this->session->userdata("admin_logged_in"))
		{
			case 1:
				$this->load->view('Welcome_admin');
				break;
			default:
				if($_SERVER['REQUEST_METHOD']=='POST')
				{
					if($this->login_validation())
					{
						$data=array(
							'admin_logged_in'=>1
						);
						$this->session->set_userdata($data);
						$this->load->view('Welcome_admin');
					}
					else
					{
						$data['admin_login_error']=1;
						$this->load->view('Login_admin',$data);
					}
				}
				else
				{
					$data['admin_login_error']=0;
					$this->load->view('Login_admin',$data);
				}
		}
	}

	private function login_validation()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('admin_id','Admin ID','required|trim|callback_check_login_credentials');
		$this->form_validation->set_rules('password','Password','required|md5|trim');
		if($this->form_validation->run())
			return true;
		else
			return false;
	}

	public function check_login_credentials()
	{
		$this->load->model('model_admin');
		if($this->model_admin->admin_log_in())
			return true;
		else
		{
			$this->form_validation->set_message('check_login_credentials',"Incorrect Admin ID or Password");
			return false;
		}
	}

	public function logout()
	{
		$this->session->sess_destroy();
		redirect('admin/');
	}

	public function show_cat()
	{
		if($this->session->userdata('admin_logged_in')==1)
		{
			$this->load->model("model_admin");
			$data['category']=$this->model_admin->show_cat();
			$this->load->view('Show_category',$data);
		}
		else
			redirect('admin/','refresh');
	}

	public function delete_cat($del_cat=null)
	{
		if($this->session->userdata('admin_logged_in')==1){
			if(isset($del_cat))
			{
				$this->load->model("model_admin");
				$this->model_admin->del_cat($del_cat);
				redirect('admin/show_cat','refresh');
			}
			else
			{
				if($_SERVER["REQUEST_METHOD"]=="POST")
				{
					$this->load->library('form_validation');
					$this->form_validation->set_rules('del_cat','Delete Category',"callback_check_del_cat_set");
					if($this->form_validation->run())
					{
						$this->load->model("model_admin");
						foreach($_POST["del_cat"] as $row)
						{
							$this->model_admin->del_cat($row);
						}
						$data['category']=$this->model_admin->show_cat();
						$this->load->view('Show_category',$data);
					}
					else
					{
						$this->load->model("model_admin");
						$data['category']=$this->model_admin->show_cat();
						$this->load->view('Delete_category',$data);
					}
				}
				else
				{
					redirect('admin/show_cat','refresh');
				}
			}
		}
		else
			redirect('admin/','refresh');
	}

	public function check_del_cat_set($str)
	{
		if(empty($_POST["del_cat"])){
			$this->form_validation->set_message('check_del_cat_set','Please select a Category');
			return false;
		}
		else
			return true;
	}

	public function add_cat()
	{
		if($this->session->userdata("admin_logged_in"))
		{
			if($_SERVER["REQUEST_METHOD"]=="POST")
			{
				$this->load->library('form_validation');
				$this->form_validation->set_rules('add_cat','Category','required|trim|callback_check_add_cat');
				if($this->form_validation->run())
				{
					redirect('admin/show_cat','refresh');
				}
				else
				{
					$data['add_cat_error']=1;
					$this->load->view('Add_cat',$data);	
				}
			}
			else
			{
				$data['add_cat_error']=0;
				$this->load->view('Add_cat',$data);
			}
		}
		else
		{
			$this->load->view("Welcome_admin");
		}
	}

	public function check_add_cat($str)
	{
		$this->load->model("model_admin");
		if($this->model_admin->add_cat($str,$this->input->post("big_cat")))
			return true;
		else
		{
			$this->set_message("add_cat","Something went wrong");
			return false;
		}
	}

	public function approve_new($page_no=null,$value=null)
	{
		if($this->session->userdata('admin_logged_in')==1){
			switch($page_no){
				case "view" :
				case "View" :
					$this->load->model("model_admin");
					if($this->model_admin->check_entry($value))
					{
						$data['profile']=$this->model_admin->show_approve_new($value);
						$this->load->model('model_shop');
						$data["category"]=$this->model_shop->get_fix_cat($data["profile"][0]->shop_category);
						$this->load->view('Admin_show_new',$data);
					}
					else
						redirect('admin/approve_new','refresh');
					break;
					break;
				case "delete" :
				case "Delete" :
					$this->load->model("model_admin");
					if($this->model_admin->check_entry($value))
					{
						$this->model_admin->delete_approve_new($value);
						redirect('admin/approve_new','refresh');
					}
					else
						redirect('admin/approve_new','refresh');
					break;
				case "approve" :
				case "Approve" :
					$this->load->model("model_admin");
					if($this->model_admin->check_entry($value))
					{
						$this->model_admin->approve_new($value);
						if($this->model_admin->check_big_cat($value))
							$this->sendNotification($value);
						redirect('admin/approve_new','refresh');
					}
					else
						redirect('admin/approve_new','refresh');
					break;
				default :
					if(!isset($page_no))
						$page_no=1;
					else
					{
						if($page_no<0)
							redirect('admin/logout','refresh');
						else
						{
							if($page_no==0)
								redirect('admin/approve_new','refresh');
						}
					}
					$this->load->model("model_admin");
					$data=array();
					if($data['count_entry']=$this->model_admin->get_count_new_entry())
					{
						$block_size=2;
						if((($page_no*$block_size)-$data['count_entry'])>=$block_size)
							redirect('admin/logout','refresh');
						$data['block_size']=$block_size;
						$data['content']=$this->model_admin->get_new_entry($page_no,$block_size);
						$data['page_no']=$page_no;
						$data['has_any']=true;
					}
					else
					{
						$data['has_any']=false;
					}
					$this->load->view("Approve_new",$data);
					break;
			}
		}
		else
			redirect('admin/','refresh');
	}

	private function sendNotification($value){
		$this->load->model("model_admin");
		$category_no=$this->model_admin->get_small_cat_no($value);
		$category_name=$this->model_admin->get_small_cat_name($category_no);
		$shop_id=$value;

		$messagetxt="A ".$category_name." is going to be organised";

		$user_ids=array();
		$user_ids=$this->model_admin->get_user_id_for_notification($category_no);
		foreach($user_ids as $row){
			echo $this->model_admin->get_regId($row->user_id);
			$registatoin_ids = array($this->model_admin->get_regId($row->user_id));
	    	$message = array(
	    		"message" => $messagetxt,
	    		'shop_id' => $value,
	    		'category_name' =>$category_name);

			$url = 'https://android.googleapis.com/gcm/send';
	 
	        $fields = array(
	            'registration_ids' => $registatoin_ids,
	            'data' => $message
	        );
	 
	        $headers = array(
	            'Authorization: key=AIzaSyARFUPcovwauRmRRG7HtFt1RnWGKreLGqc',
	            'Content-Type: application/json'
	        );
	        // Open connection
	        $ch = curl_init();
	 
	        // Set the url, number of POST vars, POST data
	        curl_setopt($ch, CURLOPT_URL, $url);
	 
	        curl_setopt($ch, CURLOPT_POST, true);
	        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	 
	        // disable SSL certificate support
	        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	 
	        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
	 
	        // execute post
	        $result = curl_exec($ch);
	        if ($result === FALSE) {
	            die('Curl failed: ' . curl_error($ch));
	        }
	        curl_close($ch);
        }
	}

	public function approve_edit($page_no=null,$value=null)
	{
		if($this->session->userdata('admin_logged_in')==1){
			switch($page_no){
				case "view" :
				case "View" :
					$this->load->model("model_admin");
					if($this->model_admin->check_edit_entry($value))
					{
						$data['profile']=$this->model_admin->show_approve_edit_new($value);
						$this->load->model('model_shop');
						$data["category"]=$this->model_shop->get_fix_cat($data["profile"][0]->shop_category);
						$this->load->view('Show_edit_new',$data);
					}
					else
						redirect('admin/approve_edit','refresh');
					break;
					break;
				case "delete" :
				case "Delete" :
					$this->load->model("model_admin");
					if($this->model_admin->check_entry($value))
					{
						$this->model_admin->delete_approve_new($value);
						redirect('admin/approve_edit','refresh');
					}
					else
						redirect('admin/approve_edit','refresh');
					break;
				case "approve" :
				case "Approve" :
					$this->load->model("model_admin");
					if($this->model_admin->check_entry($value))
					{
						$this->model_admin->approve_edit_new($value);
						redirect('admin/approve_edit','refresh');
					}
					else
						redirect('admin/approve_edit','refresh');
					break;
				default :
					if(!isset($page_no))
						$page_no=1;
					else
					{
						if($page_no<0)
							redirect('admin/logout','refresh');
						else
						{
							if($page_no==0)
								redirect('admin/approve_edit','refresh');
						}
					}
					$this->load->model("model_admin");
					$data=array();
					if($this->model_admin->get_count_edit_new_entry()>0)
					{
						echo $data['count_entry']=$this->model_admin->get_count_edit_new_entry();
						$block_size=2;
						if((($page_no*$block_size)-$data['count_entry'])>=$block_size)
							redirect('admin/logout','refresh');
						$data['block_size']=$block_size;
						$data['content']=$this->model_admin->get_edit_new_entry($page_no,$block_size);
						$data['page_no']=$page_no;
						$data['has_any']=true;
					}
					else
					{
						$data['has_any']=false;
					}
					$this->load->view("Approve_edit",$data);
					break;
			}
		}
		else
			redirect('admin/','refresh');
	}

}