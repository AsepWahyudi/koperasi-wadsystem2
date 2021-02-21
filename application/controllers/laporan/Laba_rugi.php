<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laba_rugi extends CI_Controller {

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
		
        $data['PAGE_TITLE'] = "Laporan Laba Rugi";
		$data['page']       = "laporan/laba_rugi";
		$data['cabs']       = $this->dbasemodel->loadsql("SELECT NAMA,KODE FROM m_cabang ORDER BY NAMA ASC"); 
		$data['query']      = $this->dbasemodel->loadsql("SELECT A.* FROM jns_akun A ORDER BY IDAKUN ASC");
		 
        $this->load->view('dashboard',$data);
    }
	public function data(){
		 
		$keyword           = null !== $this->input->post('keyword') ? $this->input->post('keyword') : "";
		$dataPerPage       = 10000;
		$page              = $this->input->post('page');
		$dataTable         = $this->ModelLaporan->getLabaRugi($keyword, $dataPerPage, $page, $this->input->post());
		$dataTable         = json_decode(json_encode($dataTable), true);
		                  
		$array_sum         = array('DEBET', 'KREDIT');
		$result            = $this->tree->result_tree('PARENT', 'IDAKUN', $dataTable['data'], $array_sum);
		
		$dataTable['data'] = $result['return'];
        header('Content-Type: application/json');
		echo json_encode($dataTable);
		die(); 
    } 
	public function cetak(){
		
		// plhcabang: 
		// idakun: 
		// tgl: 01/11/2020 - 25/11/2020
		$cabang = $this->input->post("plhcabang"); 
		$this->session->set_flashdata('cabang',$cabang); 
		
		$tgl = $this->input->post("tgl");
		$this->session->set_flashdata('tgl',$tgl);
		
		$keyword		= null !== $this->input->post('keyword') ? $this->input->post('keyword') : "";
		$dataPerPage	= $this->input->post('dataperpage');
		$page			= $this->input->post('page');
		
		$this->session->set_flashdata('keyword',$keyword); 
		$this->session->set_flashdata('dataPerPage',$dataPerPage); 
		$this->session->set_flashdata('page',$page);
		
	}  
	public function cetaklaplabarugi(){
		
		ini_set('memory_limit', '512');      // DIDN'T WORK
		ini_set('memory_limit', '512MB');    // DIDN'T WORK
		ini_set('memory_limit', '512M');     // OK - 512MB
		ini_set('memory_limit', 512000000);  // OK - 512MB
		$this->load->model('ModelLaporan');
		$this->load->library('pdf'); 	
		
		$cabang      = $this->session->flashdata('cabang'); 
		$tgl         = $this->session->flashdata('tgl');  
		$dataPerPage = $this->session->flashdata('dataPerPage'); 
		$page        = $this->session->flashdata('page'); 
		  
		$html_content = '';
		 
		$post['tgl']         = $tgl;
		$post['plhcabang']   = $cabang;
		$post['dataPerPage'] = $dataPerPage;
		$post['page']        = $page;
		$post['keyword']     = $keyword;
		
		$dataTable['cabang']     = $cabang;
		$dataTable['tanggal']    = $tgl; 
		$dataTable['datacabang'] = $this->dbasemodel->loadsql("SELECT * FROM m_cabang WHERE KODE ='".$cabang."'")->row();  
		
		$dataTable = $this->ModelLaporan->getLabaRugi($keyword, 10000, $page, $post);
		$dataTable = json_decode(json_encode($dataTable), true);
		                  
		$array_sum = array('DEBET', 'KREDIT');
		 
		 
		$result                  = $this->tree->result_tree('PARENT', 'IDAKUN', $dataTable['data'], $array_sum); 
		$dataTable['data']       = $result['return'];
		 
	    $html_content = $this->load->view('laporan/cetaklabarugi',$dataTable,true);
		 
		$this->pdf->loadHtml($html_content,'UTF-8');
		$this->pdf->setPaper('A4');
		$this->pdf->render();
		$this->pdf->stream("Laba Rugi.pdf", array("Attachment"=>0));
	}  
}