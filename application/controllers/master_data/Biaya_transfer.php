<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Biaya_transfer extends CI_Controller {

	function __construct(){ 
        parent::__construct();
		$this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . 'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-chace');
        $this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");  
		 
		$this->load->database(); 
		$this->load->model('dbasemodel');
		//@session_start();
    }
	
	public function index()
	{
		$data['PAGE_TITLE'] = "Data Biaya Transfer Kas";
		$data['page']       = "masterdata/biayatransferkas";
		  
		$data['query']      = $this->dbasemodel->loadsql("SELECT * FROM m_biaya_transfer ORDER BY ID_BIAYA_TRF_KAS ASC");   
		$data['response']   = $this->session->flashdata('response');
        $this->load->view('dashboard',$data);
    }
	
	public function savebiayatransfer(){
		// Array ( [NAMA_BIAYA] => tester nama biaya transfer [BIAYA_TRF] => 2000 )
		// echo "<pre>".print_r($_POST)."</pre>";
		
		if($this->input->post())
        {   
			if($this->input->post('NAMA_BIAYA') == " " OR $this->input->post('BIAYA_TRF') == " ") 
			{  
				$response = '<div class="alert alert-danger" role="alert"><strong>Gagal </strong>Harap periksa kembali inputan anda</div>';
				$this->session->set_flashdata('response',$response);
				redirect("biaya-transfer");
			} 
			else 
			{  
		
				$ID_BIAYA_TRF_KAS = $this->input->post('ID_BIAYA_TRF_KAS');
				
				if($ID_BIAYA_TRF_KAS  == ""){
					
					$insert["NAMA_BIAYA"] = $this->input->post('NAMA_BIAYA');
					$insert["BIAYA_TRF"] = $this->input->post('BIAYA_TRF');
					
					$this->dbasemodel->insertData("m_biaya_transfer",$insert);
						  
					$response = '<div class="alert alert-info" role="alert"><strong>Sukses </strong>Insert Data Berhasil</div>';
					$this->session->set_flashdata('response',$response);
					redirect("biaya-transfer");
				}else{
					
					$ID_BIAYA_TRF_KAS = $this->input->post('ID_BIAYA_TRF_KAS');
					  
					$update["NAMA_BIAYA"] = $this->input->post('NAMA_BIAYA');
					$update["BIAYA_TRF"] = $this->input->post('BIAYA_TRF');

					if ($this->dbasemodel->updateData('m_biaya_transfer', $update, "ID_BIAYA_TRF_KAS = '" . $ID_BIAYA_TRF_KAS . "' ")) {
					 
						
						$response = '<div class="alert alert-info" role="alert"><strong>Sukses </strong>Update Berhasil Disimpan</div>';
						$this->session->set_flashdata('response',$response);
						redirect("biaya-transfer");
						
					} else { 
						$response = '<div class="alert alert-danger" role="alert"><strong>Gagal </strong>Update Gagal Dilakukan</div>';
						$this->session->set_flashdata('response',$response);
						redirect("biaya-transfer");
					} 
				} 
			}
        }
		else
		{ 
			$response = '<div class="alert alert-danger" role="alert"><strong>Gagal </strong>Harap periksa kembali inputan anda</div>';
			$this->session->set_flashdata('response',$response);
			redirect("biaya-transfer");
		}
	}
	 
	 
	public function get_edit(){
		if(!is_logged_in()){
			redirect('/auth_login');	
		}
		$id		= $this->input->get('id');
		$sql	= sprintf("SELECT * FROM m_biaya_transfer WHERE ID_BIAYA_TRF_KAS = '".$id."'");
		$query	= $this->dbasemodel->loadsql($sql);
		if($query->num_rows() > 0) {
			$result	= $query->result_array();
			echo json_encode ($result[0]);
		}
		echo null;
	}
	public function delete(){
		if(!is_logged_in()){
			redirect('/auth_login');	
		}
		
		$id		=	$this->uri->segment(4);
		$from	=	"m_user WHERE IDUSER = ". $id ." ";
		$this->dbasemodel->hapus($from);
		$this->session->set_flashdata('ses_trx_user', '11||Data user telah dihapus.');
		redirect(base_url() . 'user');
	}
}