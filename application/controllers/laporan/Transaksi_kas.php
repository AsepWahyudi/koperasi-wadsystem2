<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaksi_kas extends CI_Controller {

	function __construct(){ 
        parent::__construct();
		$this->load->helper(array('form','url', 'xml','text_helper','date','inflector'));
		$this->load->database();
		$this->load->library(array('Pagination','user_agent','session','form_validation','session'));
		$this->load->model('dbasemodel');
		if(!is_logged_in()){
			redirect('/auth_login');	
		}
    }
	
	public function index(){
		
		$data['opt_data_entries'] = $this->load->view('_elements/data_entries', NULL, TRUE);
		$data['table_footer']     = $this->load->view('_elements/table_footer', NULL, TRUE);
        $data['PAGE_TITLE']       = "Laporan Transaksi Kas";
		$data['page']             = "laporan/transaksi_kas";

        $this->load->view('dashboard',$data);
    }
	
	public function data(){
	 
		$this->load->model('ModelLaporan');
		$keyword     = null !== $this->input->post('keyword') ? $this->input->post('keyword') : "";
		$dataPerPage = $this->input->post('dataperpage');
		$page        = $this->input->post('page');
		$dataTable   = $this->ModelLaporan->getTransaksiKas($keyword, $dataPerPage, $page, $this->input->post());
		
		//array_unshift($dataTable, array('id' => ''));
		
		header('Content-Type: application/json');
		echo json_encode($dataTable);
		die();
		
    }

    
	
}