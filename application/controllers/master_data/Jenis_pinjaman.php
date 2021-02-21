<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jenis_pinjaman extends CI_Controller {

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
        $data['PAGE_TITLE']     = "Jenis Pinjaman";
		$data['page']           = "masterdata/jenis_pinjaman";
		$data['query']			= $this->dbasemodel->loadsql("SELECT * FROM jns_pinjm");
		$data['akunz']			= $this->dbasemodel->loadsql("SELECT * FROM jns_akun 
															WHERE (jenis_transaksi LIKE '%pyd%' or jenis_transaksi LIKE '%pinjaman%') AND header = 0 
															ORDER BY AKUN");
		

        $this->load->view('dashboard',$data);
    }
	
	public function save(){
		if($this->input->post())
        {
			$id			= $this->input->post('idtrx');
            $insert 	= $this->input->post();
			unset($insert['idtrx']);
			if($id == "") {
				$this->dbasemodel->insertData("jns_pinjm", $insert);
				$this->session->set_flashdata('ses_trx_pinj', '11||Input Data Berhasil.');
			} else {
				$this->dbasemodel->updateData("jns_pinjm", $insert, "IDJNS_PINJ = '". $id."' ");
				$this->session->set_flashdata('ses_trx_pinj', '11||Update Data Berhasil.');
			}
        }
		redirect(base_url() . 'jenis-pinjaman');
	}
	
	public function get_edit(){
		if(!is_logged_in()){
			redirect('/auth_login');	
		}
		$id		=	$this->input->get('id');
		$sql	=	sprintf("SELECT * FROM jns_pinjm WHERE IDJNS_PINJ = '%s' ", $id);
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
		$from	=	"jns_pinjm WHERE IDJNS_PINJ = ". $id ." ";
		$this->dbasemodel->hapus($from);
		$this->session->set_flashdata('ses_trx_pinj', '11||Data telah dihapus.');
		redirect(base_url() . 'jenis-pinjaman');
	}
}