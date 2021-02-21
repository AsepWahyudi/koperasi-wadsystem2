<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Intromobile extends CI_Controller {

	function __construct(){ 
		parent::__construct();
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
		header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
		$this->load->helper(array('form','url', 'xml','text_helper','date','inflector','app'));
		$this->load->database();
		$this->load->library(array('Pagination','user_agent','session','form_validation','session'));
		$this->load->model('mobileapi/dbasemodel');
		//@session_start();
    }
	
	public function index()
	{
		$id =  $this->input->post('idkat'); 
		$arr = array();
		$cek = $this->dbasemodel->loadsql("SELECT * FROM m_iklan ORDER BY IDIKLAN ASC");
			$arriklan = array();
			$arrhome = array();
			foreach($cek->result() as $key)
			{
				if($key->POSISI=="0"){
					array_push($arrhome, base_url()."assets/iklan/".$key->GAMBAR);
				}else{
					array_push($arriklan, base_url()."assets/iklan/".$key->GAMBAR);
				}
				
			}
			$array = array("code"=>"200",
					"iklan"=>$arriklan,
					"home"=>$arrhome, 
					"info"=>"ini info");
		echo json_encode($array);
			
		
	}
}