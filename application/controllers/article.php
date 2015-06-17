<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Article extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->user = ($this->sitemodel->is_logged()) ? $this->sitemodel->get_user($this->session->userdata('lastname')) : false;
		$this->picture_path = base_url().'pictures/';
		$this->view_folder = strtolower(__CLASS__).'/';
	}

	public function index()
	{
		$data = array(
			'title' => 'Bienvenu sur la boutique',
			'articles'=>$this->sitemodel->get_all(),
			'content'=> $this->view_folder.__FUNCTION__
		);
		$this->load->view('template/content',$data);
	}

	public function payer()
	{
		if(!$this->sitemodel->is_logged()){
			redirect('user/login');exit;
		}

		if(!$this->cart->contents()){
			redirect();exit;
		}

		$this->load->library('paypal');
		$params = array(
			'RETURNURL'=>site_url('article/retour'),
			'CANCELURL'=>site_url('article/cancel')
		);

		$items = array();

		$i = 0;
		foreach($this->cart->contents() as $cart)
		{
			$items['L_PAYMENTREQUEST_0_NAME'.$i]  = $cart['name'];
			$items['L_PAYMENTREQUEST_0_NUMBER'.$i] = $cart['id'];
			$items['L_PAYMENTREQUEST_0_DESC'.$i] = $cart['name'];
			$items['L_PAYMENTREQUEST_0_AMT'.$i] = $cart['price'];
			$items['L_PAYMENTREQUEST_0_QTY'.$i] = $cart['qty'];
			$i++;
		}

		$items['PAYMENTREQUEST_0_AMT'] = $this->cart->total();
		$items['PAYMENTREQUEST_0_CURRENCYCODE'] = 'EUR';

		$params += $items;
		$paypal = new Paypal();
		$response = $paypal->request('SetExpressCheckout',$params);

		if(!empty($response['TOKEN']) && $response['ACK']=='Success')
		{
			$token = htmlentities($response['TOKEN']);

			$order = array(
				'order_token'=>$token,
				'order_user_id'=>$this->user->user_id,
				'order_amt'=>$this->cart->total(),
				'order_total_items'=>$this->cart->total_items(),
				'order_paypal_infos'=>false,
				'order_valid'=>false
			);

			if($this->sitemodel->add_order($order))
			{
				foreach($this->cart->contents() as $cart)
				{
					$sale = array(
						'sale_user_id'=>$this->user->user_id,
						'sale_article_id'=>$cart['id'],
						'sale_qty'=>$cart['qty'],
						'sale_amt'=>$cart['price'],
						'sale_order_token'=>$token,
						'sale_valid'=>false
					);
					$this->sitemodel->add_sale($sale);
				}

				header('Location: https://www.sandbox.paypal.com/webscr?cmd=_express-checkout&token='.urlencode($token).'&useraction=commit');
			}
		}
		else{
			echo 'Une erreur s\'est produite : <br> '.$response['L_LONGMESSAGE0'];
		}

	}

	function retour()
	{
		if(empty($_GET)){redirect();exit;}

		$this->load->library('paypal');

		if(!empty($_GET['token']))
		{
			$params = array('TOKEN'=>htmlentities($_GET['token'], ENT_QUOTES));

			$paypal = new Paypal();
			$response = $paypal->request('GetExpressCheckoutDetails',$params);
			if(is_array($response) && $response['ACK']=='Success')
			{
				$token = htmlentities($response['TOKEN']);
				$user = $this->sitemodel->get_user($this->sitemodel->get_order($token)->order_user_id);

				$payment_params = array(
					'PAYMENTREQUEST_0_PAYMENTACTIO'=>'Sale',
					'PayerID'=>htmlentities($_GET['PayerID'], ENT_QUOTES),
					'PAYMENTREQUEST_0_AMT'=>$response['AMT'],
					'PAYMENTREQUEST_0_CURRENCYCODE'=> 'EUR'
				);

				$params += $payment_params;
				$paypal = new Paypal();
				$response = $paypal->request('DoExpressCheckoutPayment',$params);

				if(is_array($response) && $response['ACK']=='Success')
				{
					$token = htmlentities($response['TOKEN']);
					$order = array(
						'order_paypal_infos'=>serialize($response),
						'order_valid'=>true
						);
					if($this->sitemodel->valid_order($token,$order))
					{
						$sales = $this->sitemodel->get_sales_order($token);
						foreach($sales as $s)
						{
							$data = array('sale_valid'=>true);
							$this->sitemodel->update_sales_order($token,$data);
						}

						$amount = htmlentities($response['PAYMENTINFO_0_AMT']);

						$this->email->from('nettutoriel@gmail.com','Shop');
						$this->email->to($user->email);
						$this->email->subject('Vos achats sur Shop');
						$this->email->message('<h2>Bonjour '.$user->firstname.', </h2>
							<div>Commande n° <strong>'.$token.'</strong></div>
							<div>Montant de la commande :<strong>'.$amount.'</strong></div>
							<p>Votre commande sera expédiée rapidement bla bla bla<br>
							Vous pouvez consulter '.anchor('user','la liste de vos achats').' dans votre epace personnel et imprimer la facture.</p>');

						$this->email->send();

						$this->cart->destroy();
						redirect('user');
					}
				}
			}
		}
	}

	public function cancel()
	{
		echo 'Paiement annulé';
	}

	public function panier()
	{
		$data = array(
			'title' => 'Mon panier',
			'cart'=>$this->cart->contents(),
			'total'=>$this->cart->total(),
			'total_articles'=>$this->cart->total_items(),
			'content'=> $this->view_folder.__FUNCTION__
		);
		$this->load->view('template/content',$data);
	}

	public function add($article_id=null)
	{
		if(!$article_id || !$this->sitemodel->get_one($article_id)){
			redirect();exit;
		}
		$article = $this->sitemodel->get_one($article_id);

		$data = array(
			'id'=>$article->article_id,
			'qty'=>1,
			'price'=>$article->price_amount,
			'name'=>$article->title
		);
		$this->cart->insert($data);
		redirect('article/panier');exit;
	}

	public function update($rowid=null)
	{
		if(!$rowid || !$this->input->post('qty') || !is_numeric($this->input->post('qty'))){
			redirect('article/panier');exit;
		}
		$data = array(
			'rowid'=>$rowid,
			'qty'=>$this->input->post('qty')
		);
		$this->cart->update($data);

		if($this->input->is_ajax_request()){
			$response = array(
				'success'=>true,
				'nb_article'=>$this->cart->total_items(),
				'total'=>number_format($this->cart->total(), 2, ',', ' '),
				'total_for_item'=>number_format($this->input->post('qty') * $this->input->post('price'), 2, ',', ' ')
			);
			echo json_encode($response);exit;
		}

		redirect('article/panier');
	}

	public function delete($rowid=null)
	{
		if(!$rowid){redirect();exit;}
		$data = array(
			'rowid'=>$rowid,
			'qty'=>0
		);

		$this->cart->update($data);

		if($this->input->is_ajax_request()){
			$response = array(
				'success'=>true,
				'nb_article'=>$this->cart->total_items(),
				'total'=>number_format($this->cart->total(), 2, ',', ' ')
			);
			echo json_encode($response);exit;
		}

		redirect('article/panier');
	}

	function show($article_id=null)
	{
		if(!$article_id || !$this->sitemodel->get_one($article_id)){
			redirect();exit;
		}
		$article = $this->sitemodel->get_one($article_id);
		$data = array(
			'title' => $article->title,
			'article'=>$article,
			'content'=> $this->view_folder.__FUNCTION__
		);
		$this->load->view('template/content',$data);
	}
}
