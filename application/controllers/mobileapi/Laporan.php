<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan extends CI_Controller {

	function __construct(){ 
        parent::__construct();
		header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
		$this->load->helper(array('form','url', 'xml','text_helper','date','inflector','app'));
		$this->load->database();
		$this->load->library(array('Pagination','user_agent','session','form_validation','session'));
		$this->load->model('mobileapi/dbasemodel');
		//@session_start();
    }
	public function index()
	{
	    if(!is_logged_in()){
			redirect('/auth/sign-in');	
		}
		$data['page']		= "laporan";
		$query             	= "SELECT * FROM m_trx ORDER BY IDTRX DESC";
        $data['result'] 	= array();
        $data["links"]      = '';
        
		if((($this->input->post('datestart')) && ($this->input->post('dateend'))) or ($this->uri->segment(2)) ){
		    
            $datestart     = str_replace('/', '-', $this->input->post('datestart'));
            $newDatestart  = date("Y-d-m", strtotime($datestart));
            
            $newDateend    = date("Y-m-d", strtotime($this->input->post('dateend')));
            $newDateendpag = date("d-m-Y", strtotime($newDateend));
            
            $data['pagdatestart']  = $this->input->post('datestart');
            $data['pagdateend']    = $this->input->post('dateend');
                          
            $hit					= $this->dbasemodel->countData("m_trx",'IDTRX');
    		$trow 					= $hit->row();
    		$config["per_page"] 	= 5;
    		$config["total_rows"] 	= $trow->TOTAL;
    		$config["base_url"] 	= base_url()."laporan/pag/".$newDatestart."/".$newDateend;
    		$config["uri_segment"] 	= 2;
    		$config['use_page_numbers'] = TRUE;
    		
    		$this->pagination->initialize($config);
            
            //$page 				= (intval($this->uri->segment(2)))? intval($this->uri->segment(2)) : 0;
    		$page_num 				= $this->uri->segment(2);
    		$page 					= ($page_num  == NULL) ? 0 : ($page_num * $config['per_page']) - $config['per_page'];
    		
    		//$data['result'] 	= $this->dbasemodel->loadsql("SELECT * FROM m_trx where tgl BETWEEN '$newDatestart' and '$newDateend' ORDER BY IDTRX DESC");
           	$data['result']			= $this->dbasemodel->getLaporan($config["per_page"], $page, $newDatestart, $newDateend);
            if(count($data['result']->result()) > 0){
                $data["links"] 	        = $this->pagination->create_links();
            }
        }
        $this->load->view('mobileapi/dashboard',$data);
	}
    
    public function pag($startdate, $endstart, $pages)
	{
	    $data['page']		= "laporan"; 
        
        if($startdate && $endstart){
		    
            $datestart     = str_replace('/', '-', $this->input->post('datestart'));
            $newDatestart  = $startdate;
            
            $newDateend    = $endstart;
            
            $data['pagdatestart']  = $this->input->post('datestart');
            $data['pagdateend']    = $this->input->post('dateend');
                          
            $hit					= $this->dbasemodel->countData("m_trx",'IDTRX');
    		$trow 					= $hit->row();
    		$config["per_page"] 	= 5;
    		$config["total_rows"] 	= $trow->TOTAL;
    		$config["base_url"] 	= base_url()."laporan/pag/".$startdate."/".$endstart;
    		$config["uri_segment"] 	= 5;
    		$config['use_page_numbers'] = TRUE;
    		//print_r($config["uri_segment"] ); die;
    		$this->pagination->initialize($config);
            
            //$page 				= (intval($this->uri->segment(2)))? intval($this->uri->segment(2)) : 0;
    		$page_num 				= $pages;
    		$page 					= ($page_num  == NULL) ? 0 : ($page_num * $config['per_page']) - $config['per_page'];
    		
    		//$data['result'] 	= $this->dbasemodel->loadsql("SELECT * FROM m_trx where tgl BETWEEN '$newDatestart' and '$newDateend' ORDER BY IDTRX DESC");
           	$data['result']			= $this->dbasemodel->getLaporan($config["per_page"], $page, $newDatestart, $newDateend);
            $data["links"] 	        = $this->pagination->create_links();
        }
        $this->load->view('mobileapi/dashboard',$data);     
	}
	
	
	function trxdetail()
	{
		if(!is_logged_in()){
			redirect('/auth/sign-in');	
		}
		$id 				=  $this->uri->segment(2);
		$data['page']		= "detailtrx";
		$data['result'] 	= $this->dbasemodel->loadsql("SELECT * FROM m_trx WHERE IDTRX='$id'");
														  
		$this->load->view('mobileapi/dashboard',$data);
	}
}