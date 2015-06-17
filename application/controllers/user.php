<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->user = ($this->sitemodel->is_logged()) ? $this->sitemodel->get_user($this->session->userdata('lastname')) : false;
		$this->picture_path = base_url().'pictures/';
		$this->view_folder = strtolower(__CLASS__).'/';
	}

	public function index()
	{
		if(!$this->sitemodel->is_logged()){
			redirect('user/login');exit;
		}

		$data = array(
			'title'=>'Mes achats',
			'user'=>$this->user,
			'orders'=>$this->sitemodel->get_orders($this->user->user_id),
			'content'=>$this->view_folder.__FUNCTION__
		);
		$this->load->view('template/content',$data);
	}

	public function commande($token=null)
	{
		if(!$token){redirect();exit;}
		$sales = $this->sitemodel->get_sales_order($token);
		if(!$sales){redirect();exit;}
		$data = array(
			'title'=>'Mes commandes',
			'sales'=>$sales,
			'order'=>$this->sitemodel->get_order($token),
			'content'=>$this->view_folder.__FUNCTION__
		);

		$this->load->view('template/content',$data);
	}

	public function facture($token=null)
	{
		if(!$this->sitemodel->is_logged()){redirect();exit;}
		if(!$token){redirect();exit;}

		$order = $this->sitemodel->get_order($token);
		$sales = $this->sitemodel->get_sales_order($token);

		$data = array(
			'sales'=>$sales,
			'order'=>$order
		);

		$this->load->view($this->view_folder.__FUNCTION__,$data);
		$html = $this->output->get_output();
		$this->load->library('mpdf');
		$mpdf = new Pdf();
		$mpdf->WriteHTML($html);
		$mpdf->Output();
	}

	public function login()
	{
		if($this->sitemodel->is_logged()){
			redirect('user');exit;
		}

		$this->form_validation->set_rules('email','Email','trim|required|valid_email');
		$this->form_validation->set_rules('password','Mot de passe','trim|required');

		if($this->form_validation->run())
		{
			if($this->sitemodel->login($this->input->post('email'),$this->input->post('password')))
			{
				redirect('user');exit;
			}
			else
			{
				$this->session->set_flashdata('error','Mauvais identifiants');
				redirect(current_url());exit;
			}
		}

		$data = array(
			'title'=>'Connexion',
			'content'=>$this->view_folder.__FUNCTION__
		);
		$this->load->view('template/content',$data);
	}

	public function logout()
	{
		$this->session->sess_destroy();
		redirect();exit;
	}

	public function signup()
	{
		if($this->sitemodel->is_logged()){
			redirect('user');exit;
		}

		$this->form_validation->set_rules('email','Email','required|valid_email|is_unique[users.email]');
		$this->form_validation->set_rules('password','Mot de passe','trim|required|min_length[5]');
		$this->form_validation->set_rules('lastname','Nom','trim|required');
		$this->form_validation->set_rules('firstname','Prénom','trim|required');
		$this->form_validation->set_rules('address','Adresse','trim|required');
		$this->form_validation->set_rules('city','Ville','trim|required');
		$this->form_validation->set_rules('cp','Code postal','trim|required');
		$this->form_validation->set_rules('country','Pays','trim|required|is_natural_no_zero');
		$this->form_validation->set_rules('phone','Téléphone','trim|required|integer');

		if($this->form_validation->run())
		{
			$user = array(
				'email'=>$this->input->post('email'),
				'firstname'=>$this->input->post('firstname'),
				'lastname'=>$this->input->post('lastname'),
				'address'=>$this->input->post('address'),
				'postal'=>$this->input->post('cp'),
				'user_country_id'=>$this->input->post('country'),
				'city'=>$this->input->post('city'),
				'phone'=>$this->input->post('phone'),
				'password'=>sha1(md5($this->input->post('password')))
			);

			if($this->sitemodel->signup($user))
			{
				$this->session->set_flashdata('success','Inscription réussie');
				redirect(current_url());exit;
			}else{
				throw new Exception('Une erreur est survenue, réessayez svp');

			}
		}

		$data = array(
			'title'=>'Inscription',
			'countries'=>$this->sitemodel->get_countries(),
			'content'=>$this->view_folder.__FUNCTION__
		);

		$this->load->view('template/content',$data);
	}


}
