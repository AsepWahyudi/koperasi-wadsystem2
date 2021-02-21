<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ovo extends CI_Controller {
	function __construct(){ 
        parent::__construct();
		$this->load->helper(array('form','url', 'xml','text_helper','date','inflector','app'));
		$this->load->database();
		$this->load->library(array('Pagination','user_agent','session','form_validation'));
		$this->load->model('dbasemodel');
		//@session_start();
    }

    public function index()
	{
		$this->load->library('Cekmutasi/cekmutasi');
		$list = json_encode($this->cekmutasi->ovo()->list());
		$data['list']      		= $list;

		$data['PAGE_TITLE']     = "Payment Gateway - Mutasi OVO";
		$data['page']           = "payment/ovo"; 

		$this->load->view('dashboard', $data);
	} 

}