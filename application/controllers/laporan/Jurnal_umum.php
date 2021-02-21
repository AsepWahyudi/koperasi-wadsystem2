<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jurnal_umum extends CI_Controller {

	function __construct(){ 
        parent::__construct();
		$this->load->helper(array('form','url', 'xml','text_helper','date','inflector','app'));
		$this->load->database();
		$this->load->library(array('Pagination','user_agent','session','form_validation','session', 'tree'));
		$this->load->model(array('dbasemodel', 'ModelLaporan'));
		if(!is_logged_in()){
			redirect('/auth_login');	
		}
    }
	
	public function index()
	{
        $data['PAGE_TITLE'] = "Laporan Jurnal Umum (Transaksi)";
		$data['page']       = "laporan/jurnal_umum";
		
        $this->load->view('dashboard',$data);
    }
	public function data(){
	  
		$keyword     = null !== $this->input->post('keyword') ? $this->input->post('keyword') : "";
		$dataPerPage = 10000;
		$page        = $this->input->post('page');
		$dataTable   = $this->ModelLaporan->getJurnal($keyword, $dataPerPage, $page, $this->input->post());

        header('Content-Type: application/json');
		echo json_encode($dataTable);
		die();
		
    }
}