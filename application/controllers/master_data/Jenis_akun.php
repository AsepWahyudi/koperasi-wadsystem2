<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jenis_akun extends CI_Controller {

	function __construct(){ 
        parent::__construct();
		$this->load->helper(array('form','url', 'xml','text_helper','date','inflector','app'));
		$this->load->database();
		$this->load->library(array('Pagination','user_agent','session','form_validation','session', 'tree'));
		$this->load->model('dbasemodel');
		//@session_start();
    }
	
	public function index()
	{
		if(!is_logged_in()){
			redirect('/auth_login');	
		}
        $data['PAGE_TITLE']     = "Jenis Akun Transaksi";
		$data['page']           = "masterdata/jenis_akun";
		$data['query']			= $this->dbasemodel->loadsql("SELECT A.*  FROM jns_akun A ORDER BY IDAKUN ASC");
		
		$data['header']			= $this->dbasemodel->loadsql("SELECT * FROM jns_akun WHERE HEADER = 1 ");
        $this->load->view('dashboard',$data);
    }
	
	public function save(){
		if($this->input->post())
        {
			$id			= $this->input->post('idtrx');
            $insert 	= $this->input->post();
			unset($insert['idtrx']);
			if($this->check_kode($this->input->post('kode_aktiva'), $id) == true) {
				$this->session->set_flashdata('ses_trx_akun', '00||Kode Aktiva sudah digunakan, harap gunakan kode yang lain.');
			} else {
				if($id == "") {
					$this->dbasemodel->insertData("jns_akun",$insert);
					$this->session->set_flashdata('ses_trx_akun', '11||Input Data Berhasil.');
				} else {
					$this->dbasemodel->updateData("jns_akun", $insert, "IDAKUN = '". $id."' ");
					$this->session->set_flashdata('ses_trx_akun', '11||Update Data Berhasil.');
				}
			}
        }
		redirect(base_url() . 'jenis-akun');
	}
	
	protected function check_kode($kode, $id = ""){
		$_where	=	($id == "" ? "0=0" : "IDAKUN <> '". $id ."'");
		$sql	=	sprintf("SELECT IDAKUN FROM jns_akun WHERE %s AND KODE_AKTIVA LIKE '%s' ", $_where, $kode);
		$query	=	$this->dbasemodel->loadsql($sql);
		if($query->num_rows() > 0) {
			return true;
		}
		return false;
	}
	
	public function get_edit(){
		if(!is_logged_in()){
			redirect('/auth_login');	
		}
		$id		=	$this->input->get('id');
		$sql	=	sprintf("SELECT * FROM jns_akun WHERE IDAKUN = '%s' ", $id);
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
		$from	=	"jns_akun WHERE IDAKUN = ". $id ." ";
		$this->dbasemodel->hapus($from);
		$this->session->set_flashdata('ses_trx_akun', '11||Data telah dihapus.');
		redirect(base_url() . 'jenis-akun');
	}
}