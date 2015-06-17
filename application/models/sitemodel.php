<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sitemodel extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}

	function get_all()
	{
		$q = $this->db->select('*')->from('articles a')
		->join('prices p','p.price_article_id = a.article_id','left')
		->join('images i','i.image_article_id = a.article_id','left')
		->order_by('a.article_id','desc')
		->get();
		if($q->num_rows()>0)
		{
			foreach($q->result() as $row)
			{
				$data[] = $row;
			}
			return $data;
		}
	}
	
	
		function prix()
	{
		$q = $this->db->select('*')->from('articles a')
		->join('prices p','p.price_article_id = a.article_id','left')
		->join('images i','i.image_article_id = a.article_id','left')
		->order_by('a.qte','desc')
		->get();
		if($q->num_rows()>0)
		{
			foreach($q->result() as $row)
			{
				$data[] = $row;
			}
			return $data;
		}
	}

	function get_orders($user_id)
	{
		$q = $this->db->select('*')->from('orders o')
		->where('o.order_user_id',$user_id)
		->order_by('o.order_valid','asc')->order_by('o.order_id','desc')
		->get();
		if($q->num_rows()>0)
		{
			foreach($q->result() as $row)
			{
				$data[] = $row;
			}
			return $data;
		}
	}

	function update_sales_order($token,$data)
	{
		$this->db->where('sale_order_token',$token)->update('sales',$data);
		return true;
	}

	function get_sales_order($token)
	{
		$q = $this->db->get_where('sales',array('sale_order_token'=>$token));
		if($q->num_rows()>0)
		{
			foreach($q->result() as $row)
			{
				$data[] = $row;
			}
			return $data;
		}
	}

	function valid_order($token,$data)
	{
		$this->db->where('order_token',$token)->update('orders',$data);
		return true;
	}

	function get_order($token)
	{
		$q = $this->db->get_where('orders',array('order_token'=>$token));
		if($q->num_rows()>0)
		{
			return $q->row();
		}
	}

	function add_order($data)
	{
		$this->db->insert('orders',$data);
		return true;
	}

	function add_sale($data)
	{
		$this->db->insert('sales',$data);
		return true;
	}

	function get_one($article_id)
	{
		$q = $this->db->select('*')->from('articles a')
		->where('a.article_id',$article_id)
		->join('prices p','p.price_article_id = a.article_id','left')
		->join('images i','i.image_article_id = a.article_id','left')
		->order_by('a.article_id','desc')
		->get();
		if($q->num_rows()>0)
		{
			return $q->row();
		}
	}

	function signup($data)
	{
		$this->db->insert('users',$data);
		return true;
	}

	function  login($email,$password)
	{
		$q = $this->db->get_where('users',array('email'=>$email, 'password'=>sha1(md5($password))));
		if($q->num_rows()>0)
		{
			$row = $q->row();
			$session = array('lastname'=>$row->lastname,'logged'=>true);
			$this->session->set_userdata($session);
			return true;
		}
		return false;
	}

	function get_countries()
	{
		$q = $this->db->get('countries');
		if($q->num_rows()>0)
		{
			foreach($q->result() as $row)
			{
				$data[] = $row;
			}
			return $data;
		}
	}

	function get_user($param)
	{
		if(is_numeric($param))
		{
			$this->db->where('u.user_Id',$param);
		}else{
			$this->db->where('u.lastname',$param);
		}

		$q = $this->db->select('*')->from('users u')
		->join('countries c','u.user_country_id = c.country_id')
		->get();
		if($q->num_rows()>0)
		{
			return $q->row();
		}
	}

	function is_logged()
	{
		return $this->session->userdata('lastname') && $this->session->userdata('logged');
	}

}
