<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	function __construct(){ 
        parent::__construct();
		$this->load->helper(array('form', 'url', 'xml','text_helper','date','inflector'));
		$this->load->database();
		$this->load->library(array('Pagination','user_agent','session','form_validation'));
		$this->load->model('dbasemodel');
		//@session_start();
    }
	
	public function index()
	{
		//$this->load->view('welcome_message');
		redirect('/dashboard');
	}
	
	function login()
	{
		$data['response']	= "";
		if($this->input->post())
		{
			$usra = trim($this->input->post('username'));
			$pwda = md5($this->input->post('password'));
			$cek  = $this->dbasemodel->loadsql("SELECT * FROM m_user WHERE USERNAME='$usra' AND PASSWORD='$pwda'");
			
			if($cek->num_rows()>0){

				$res = $cek->row();
				$wadcab = ($res->LEVEL=="admin")? "":$res->KODECABANG;
				$newdata = array(
					'wad_cabang'     => $wadcab,
					'wad_user'       => $res->USERNAME,
					'wad_pass'       => $res->PASSWORD,
					'wad_level'      => $res->LEVEL,
					'wad_approval'   => $res->APPROVAL,
					'wad_kodepusat'  => $res->KODEPUSAT,
					'wad_kodecabang' => $res->KODECABANG,
					'wad_id'         => $res->IDUSER
				);
				$this->session->set_userdata($newdata);
				redirect('/dashboard');	
			}else{
				$data['response'] = 'login gagal';
			}
		}
		$this->load->view('login',$data);
	}
	
	function logout()
	{
		session_destroy();
		redirect(base_url());
	}
	
	function ganticabang()
	{
		$cabang = $this->input->post("cabang");
		$newdata = array('wad_cabang' =>$cabang);
		$this->session->set_userdata($newdata);
		echo "ok";
	}
}