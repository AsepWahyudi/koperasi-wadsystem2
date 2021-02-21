<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jatuh_tempo extends CI_Controller {

	function __construct(){ 
        parent::__construct();
		$this->load->helper(array('form','url', 'xml','text_helper','date','inflector'));
		$this->load->database();
		$this->load->library(array('Pagination','user_agent','session','form_validation','session'));
		$this->load->model('dbasemodel');
		ini_set("memory_limit", "-1");
		set_time_limit(0);
		if(!is_logged_in()){
			redirect('/auth_login');	
		}
    }
	
	public function index(){
		
		$data['opt_data_entries'] = $this->load->view('_elements/data_entries', NULL, TRUE);
		$data['table_footer']     = $this->load->view('_elements/table_footer', NULL, TRUE);
        $data['PAGE_TITLE']       = "Laporan Jatuh Tempo";
		$data['page']             = "laporan/jatuh_tempo";

        $this->load->view('dashboard',$data);
    }
	
	public function data(){
		 
		$this->load->model('ModelLaporan');
		$keyword     = null !== $this->input->post('keyword') ? $this->input->post('keyword') : "";
		$dataPerPage = $this->input->post('dataperpage');
		$page        = $this->input->post('page');
		$dataTable   = $this->ModelLaporan->getDataJatuhTempo($keyword, $dataPerPage, $page, $this->input->post());

        header('Content-Type: application/json');
		echo json_encode($dataTable);
		die();
		
    }

    
	
}