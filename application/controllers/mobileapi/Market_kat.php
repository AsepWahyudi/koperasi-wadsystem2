<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Market_kat extends CI_Controller {

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
		
        $data['response']	= "";
        $data['title']  	= "Data Market Kategori";
        $data['page']		= "datamarket_kat";
		$data['result'] 	= $this->dbasemodel->loadsql("SELECT * FROM m_market_kat");
        
		if($this->input->post())
		{
				$datainsert = array('KATMARKET'=>$this->input->post('kat_market'));
				$this->dbasemodel->insertData("m_market_kat",$datainsert);
                redirect('/market_kat');
    			$this->session->set_flashdata('messagebox', '<div class="alert alert-success"><strong>Success!</strong> data berhasil ditambahkan.</div>');
                                      
		}
																  
		$this->load->view('mobileapi/dashboard',$data);
	}
    
    function build_edit($id){
        
        if($id){
            
            if($this->input->post('submitedit'))
    		{
				
                $dataupdate 	= array('KATMARKET'=>$this->input->post('katmarket'));
                                  
				$whereupdate	= "IDMARKETKAT='".$id."' limit 1";
				$this->dbasemodel->updateData("m_market_kat",$dataupdate,$whereupdate);
                $this->session->set_flashdata('messagebox', '<div class="alert alert-success"><strong>Success!</strong> update data berhasil.</div>');
                redirect('/market_kat');
    		}
            
            $data['headtitle']	= "Edit Kategori Market";
    		$data['page']		= "editmarket_kat";
    		$data['response']	= "";
            $data['detail'] 	= $this->dbasemodel->loadsql("SELECT * FROM m_market_kat WHERE IDMARKETKAT ='$id'");            
            return $this->load->view('mobileapi/dashboard',$data);
            exit;
        }
        
        redirect('/market_kat');	
    }
    
    function hapuskat()
	{
		if($this->uri->segment('2')=="delete")
		{
			$id =  $this->uri->segment(3);
            if($id){
			 $this->dbasemodel->hapus("m_market_kat where IDMARKETKAT = '".$id."' limit 1");
            }
			
			$this->session->set_flashdata('messagebox', '<div class="alert alert-success"><strong>Success!</strong> Hapus data berhasil.</div>');
			redirect('/market_kat');	
		}
	}
}