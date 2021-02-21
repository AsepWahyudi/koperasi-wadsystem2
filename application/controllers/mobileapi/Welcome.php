<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	function __construct(){ 
        parent::__construct();
		header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
		$this->load->helper(array('form','url', 'xml','text_helper','date','inflector'));
		$this->load->database();
		$this->load->library(array('Pagination','user_agent','session','form_validation','session'));
		$this->load->model('mobileapi/dbasemodel');
		//@session_start();
    }
	public function index()
	{
		session_destroy();
		redirect('/auth/sign-in');
	}
	
	function intro()
	{
		/*$array = array("code"=>"200",
					"iklan"=>array(base_url()."assets/iklan/iklan4.png",base_url()."assets/iklan/iklan7.png"),
					"home"=>array(base_url()."assets/iklan/home.png",base_url()."assets/iklan/undian.png"), 
					"info"=>"ini info");
		echo json_encode($array);*/
	}
}
