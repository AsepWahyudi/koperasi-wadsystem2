<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Paymentgateway extends CI_Controller {
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
		  
		$this->load->library('Cekmutasi/Cekmutasi');
		 
		$list = json_encode($this->cekmutasi->bank()->app_list());
		$data['list']      		= $list;

		$data['PAGE_TITLE']     = "Payment Gateway - Mutasi Bank";
		$data['page']           = "payment/paymentgateway"; 		 

		$this->load->view('dashboard', $data);
	
	 // $this->load->library('Cekmutasi/Cekmutasi');

		// $mutasi = $this->cekmutasi->bank()->mutation([
			// 'date'		=> [
				// 'from'	=> date('Y-m-d') . ' 00:00:00',
				// 'to'	=> date('Y-m-d') . ' 23:59:59'
			// ]
		// ]);

		// print_r($mutasi);
	} 
 }