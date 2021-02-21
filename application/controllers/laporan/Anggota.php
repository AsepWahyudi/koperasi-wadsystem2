<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Anggota extends CI_Controller {

	function __construct(){ 
        parent::__construct();
		$this->load->helper(array('form','url', 'xml','text_helper','date','inflector'));
		$this->load->database();
		$this->load->library(array('Pagination','user_agent','session','form_validation','session'));
		$this->load->model('dbasemodel');
		//@session_start();
		if(!is_logged_in()){
			redirect('/auth_login');	
		}
    }
	
	public function index(){
		
		$data['opt_data_entries'] = $this->load->view('_elements/data_entries', NULL, TRUE);
		$data['table_footer']     = $this->load->view('_elements/table_footer', NULL, TRUE);
        $data['PAGE_TITLE']       = "Data Anggota";
		$data['page']             = "laporan/anggota";
		
		if($this->session->userdata("wad_level") == "admin")
		{
			$kodecabang = "";
		}
		else
		{
			$kodecabang = " WHERE KODECABANG='".$this->session->userdata('wad_kodecabang')."'";
		}
		$data['query'] = $this->dbasemodel->loadsql("SELECT FILE_PIC, IDANGGOTA, NOREK, NAMA, '' AS NAMABANK, JK,
		DATE_FORMAT(TGL_LAHIR, '%d/%m/%Y') AS TGL_LAHIR,
		TIMESTAMPDIFF(YEAR, TGL_LAHIR, CURDATE()) AS USIA,
		ALAMAT, DATE_FORMAT(TGL_DAFTAR, '%d/%m/%Y') AS TGL_DAFTAR, AKTIF
		FROM m_anggota $kodecabang");

        $this->load->view('dashboard',$data);
    }
	
	public function dataanggota(){
		 
		$this->load->model('ModelLaporan');
		$keyword		=	null !== $this->input->post('keyword') ? $this->input->post('keyword') : "";
		$dataPerPage	=	$this->input->post('dataperpage');
		$page			=	$this->input->post('page');
		$dataTable		=	$this->ModelLaporan->getDataTable($keyword, $dataPerPage, $page);

        header('Content-Type: application/json');
		echo json_encode($dataTable);
		die();
		
    }

    
	
}