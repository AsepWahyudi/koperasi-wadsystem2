<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cabang extends CI_Controller {

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
		$data['PAGE_TITLE'] = "Data Cabang";
		$data['page']       = "masterdata/datacabang";
		  
		$data['query']      = $this->dbasemodel->loadsql("SELECT * FROM m_cabang A ORDER BY IDCABANG ASC"); 
		$data['sqlquery']   = "SELECT * FROM m_cabang ORDER BY IDCABANG ASC"; 
		$data['response']   = $this->session->flashdata('response');
        $this->load->view('dashboard',$data);
    }
	
	public function savecabang(){
		
		if($this->input->post())
        { 
			// echo "ada";
			
			print_r($_POST);
            $insert = array('NAMA' => $this->input->post('NAMA'),
                            'ALAMAT' => $this->input->post('ALAMAT'),
                            'KOTA' => $this->input->post('KOTA'),
                            'TELP' => $this->input->post('TELP'), 
                            'NAMAKSP' => $this->input->post('NAMAKSP'), 
                            'EMAIL' => $this->input->post('EMAIL'),
                            'WEB' => $this->input->post('WEB'),
                            'KODECABANG' => $this->input->post('KODECABANG'));
			  
			$IDCABANG = $this->input->post('IDCABANG');
			
			if($this->input->post('NAMA') == " " OR $this->input->post('NAMAKSP') == " ") 
			{ 
				echo $this->input->post('NAMA');
				$data['response'] = '<div class="alert alert-danger" role="alert"><strong>Gagal </strong>Harap periksa kembali inputan anda</div>';
			} 
			else 
			{ 
				echo "else";
				if($IDCABANG == "") 
				{
					echo "simpan";
					$this->dbasemodel->insertData("m_cabang",$insert);
					
					$get = $this->dbasemodel->loadsql("Select * FROM m_cabang ORDER BY IDCABANG DESC LIMIT 1")->row();
					 
					$insertupdate = array('KODE' => $get->IDCABANG);
							
					$this->dbasemodel->updateData("m_cabang", $insertupdate, "IDCABANG = '". $get->IDCABANG."' ");
					$response = '<div class="alert alert-info" role="alert"><strong>Sukses </strong>Insert Data Berhasil</div>';
					$this->session->set_flashdata('response',$response);
					redirect("cabang");
					
				} 
				else 
				{
					echo "update"; 
					$this->dbasemodel->updateData("m_cabang", $insert, "IDCABANG = '". $IDCABANG."'");
					$response = '<div class="alert alert-info" role="alert"><strong>Sukses </strong>Update Data Berhasil</div>';
					$this->session->set_flashdata('response',$response);
					redirect("cabang");
				} 
			}
        }else{
			// echo "Tidak ada";
			redirect("cabang");
		}
	}
	 
	public function get_edit(){
		if(!is_logged_in()){
			redirect('/auth_login');	
		}
		$id		= $this->input->get('id');
		$sql	= sprintf("SELECT * FROM m_cabang WHERE IDCABANG = '".$id."'");
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