<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jenis_jaminan extends CI_Controller {

	function __construct(){ 
        parent::__construct();
		$this->load->helper(array('form','url', 'xml','text_helper','date','inflector','app'));
		$this->load->database();
		$this->load->library(array('Pagination','user_agent','session','form_validation','session'));
		$this->load->model('dbasemodel');
		//@session_start();
    }
	
	public function index()
	{
		if(!is_logged_in()){
			redirect('/auth_login');	
		}
        $data['PAGE_TITLE']     = "Data Jenis Jaminan";
        
        $data['response']       = '';

        if($this->input->post())
        {
			
            $insert = array('NAMAJAMINAN'		=> $this->input->post('nama')
                            );
            $id = $this->input->post('idtrx');                
            if($id=="") {
                $this->dbasemodel->insertData("jns_jaminan",$insert);
                $data['response']    = '<div class="alert alert-info" role="alert"><strong>Sukses </strong>Input Data Berhasil</div>';
            } else {
               
                $this->dbasemodel->updateData("jns_jaminan", $insert, "IDJAMINAN = '". $id."' ");
                $data['response']    = '<div class="alert alert-info" role="alert"><strong>Sukses </strong>Update Data Berhasil</div>';
            }
		
        }
		$data['page']           = "masterdata/jenis_jaminan";
		$data['query']			= $this->dbasemodel->loadsql("SELECT * FROM jns_jaminan")->result();
        $this->load->view('dashboard',$data);
    }
	
	
	
	public function get_edit(){
		if(!is_logged_in()){
			redirect('/auth_login');	
		}
		$id		=	$this->input->get('id');
		$sql	=	sprintf("SELECT * FROM jns_jaminan WHERE IDJAMINAN = '%s' ", $id);
		$query	=	$this->dbasemodel->loadsql($sql);
		if($query->num_rows() > 0) {
			$result	=	$query->result_array();
			echo json_encode ($result[0]);
		}
		echo null;
	}
	public function delete(){
		if(!is_logged_in()){
			redirect('/auth_login');	
		}
		
		$id		=	$this->uri->segment(4);
		$from	=	"jns_jaminan WHERE IDJAMINAN = ". $id ." ";
		$this->dbasemodel->hapus($from);
		$this->session->set_flashdata('response', 'Data telah berhasil dihapus.');
		redirect(base_url() . 'jenis-jaminan');
	}
}