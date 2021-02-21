<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kategori extends CI_Controller {

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
		if($this->input->post('submitadd'))
		{
				$datainsert = array('KATEGORI'=>strip_tags($this->input->post('kategori')),
									'PARENT'=>$this->input->post('tipe'));
				$this->dbasemodel->insertData("m_kat_prod",$datainsert);
				$this->session->set_flashdata('messagebox', '<div class="alert alert-success"><strong>Success!</strong> tambah data berhasil.</div>');
		}
		
		if($this->input->post('submitedit'))
		{
				
				
				$dataupdate 	= array('KATEGORI'=>$this->input->post('katedit'));
				$whereupdate	= "IDKAT='".$this->input->post('idedit')."'";
				$this->dbasemodel->updateData("m_kat_prod",$dataupdate,$whereupdate);
				$this->session->set_flashdata('messagebox', '<div class="alert alert-success"><strong>Success!</strong> update data berhasil.</div>');
		}
		
		$data['page']			= "kategori";
		
		$hit					= $this->dbasemodel->countData("m_kat_prod",'IDKAT');
		$trow 					= $hit->row();
		$config["per_page"] 	= 20;
		$config["total_rows"] 	= $trow->TOTAL;
		$config["base_url"] 	= base_url()."kategori/";
		$config["uri_segment"] 	= 2;
		$config['use_page_numbers'] = TRUE;
		
		$this->pagination->initialize($config);
		
		//$page 					= (intval($this->uri->segment(2)))? intval($this->uri->segment(2)) : 0;
		$page_num 				= $this->uri->segment(2);
		$page 					= ($page_num  == NULL) ? 0 : ($page_num * $config['per_page']) - $config['per_page'];
		
		$data['result']			= $this->dbasemodel->getCategori();
		
		/*$data['result'] 	= $this->dbasemodel->loadsql("SELECT A.*,
														  B.KATEGORI AS TIPE
														  FROM m_kat_prod A
														  LEFT JOIN m_kat_prod B ON A.PARENT=B.IDKAT
														  WHERE A.PARENT !='0'");*/
		$data['tipe'] 	= $this->dbasemodel->loadsql("SELECT * FROM m_kat_prod WHERE PARENT ='0'");
		$data["links"] 	= $this->pagination->create_links();

		
		$this->load->view('mobileapi/dashboard',$data);
	}
	
	function hapuskat()
	{
		if($this->uri->segment('2')=="delete")
		{
			$id =  $this->uri->segment(3);
			
			$dataupdate 	= array('HAPUS'=>'1');
			$whereupdate	= "IDKAT='".$id."'";
			$this->dbasemodel->updateData("m_kat_prod",$dataupdate,$whereupdate);
			
			$this->session->set_flashdata('messagebox', '<div class="alert alert-success"><strong>Success!</strong> Hapus data berhasil.</div>');
			redirect('/kategori');	
		}
	}
    
    function build_edit($id){
        
        if($id){
            
            if($this->input->post('submitedit'))
    		{
    				$dataupdate 	= array(
                                            'KATEGORI'=>$this->input->post('kategori'), 
                                            'PARENT'=>$this->input->post('tipe'),
                                      ); 
    				$whereupdate	= "IDKAT='".$id."' limit 1";
    				$this->dbasemodel->updateData("m_kat_prod",$dataupdate,$whereupdate);
                    $this->session->set_flashdata('messagebox', '<div class="alert alert-success"><strong>Success!</strong> update data berhasil.</div>');
                    redirect('/kategori');
    		}
            
            $dataquery = $this->dbasemodel->loadsql("SELECT * FROM m_kat_prod WHERE IDKAT ='$id'");
            $dataall   = $dataquery->result();
            
            $data['headtitle']	= "Edit Kategori";
    		$data['page']		= "editkategori";
    		$data['response']	= "";
            $data['detail'] 	= $this->dbasemodel->loadsql("SELECT * FROM m_kat_prod WHERE IDKAT ='$id'");
            $data['tipe'] 	    = $this->dbasemodel->loadsql("SELECT * FROM m_kat_prod WHERE PARENT ='0'");
            return $this->load->view('mobileapi/dashboard',$data);
            exit;
        }
        
        redirect('/kategori');	
    }
}