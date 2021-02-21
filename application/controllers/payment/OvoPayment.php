<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class OvoPayment extends CI_Controller {
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

		$data['PAGE_TITLE']     = "Payment Gateway - OVO Payment";
		$data['page']           = "payment/ovoPayment"; 

		$this->load->view('dashboard', $data);
	} 

}