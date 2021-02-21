<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jenis_kas extends CI_Controller {

	function __construct(){ 
        parent::__construct();
		$this->load->helper(array('form','url', 'xml','text_helper','date','inflector','app'));
		$this->load->database();
		$this->load->library(array('Pagination','user_agent','session','form_validation','session'));
		$this->load->model('dbasemodel');
		if(!is_logged_in())
		{
			redirect('/auth_login');	
		}
    }
	
	public function index(){
	
        $data['PAGE_TITLE'] = "Jenis Kas";
		$data['page']       = "masterdata/jenis_kas";
		$data['query']      = $this->dbasemodel->loadsql("SELECT * FROM jenis_kas");
		$data['akn']        = $this->dbasemodel->loadsql("SELECT * FROM jns_akun");
		$data['cbg']        = $this->dbasemodel->loadsql("SELECT * FROM jns_akun WHERE PARENT ='3' AND HEADER='1'");
        $this->load->view('dashboard',$data);
    } 
	public function save(){
		
		if($this->input->post())
        {
			$id     = $this->input->post('idtrx');
            $insert = $this->input->post();
			
			$idakun = $this->input->post('kodecabang'); // IDAKUN dari table jns_akun 
			$cekdata = $this->dbasemodel->loadsql("SELECT * FROM m_cabang WHERE KODE='".$idakun."'")->num_rows();
					
			if($cekdata == 0){
				
				$getdataakun = $this->dbasemodel->loadsql("SELECT * FROM jns_akun WHERE IDAKUN='".$idakun."'")->row();
				
				$insertcabang = array('NAMA'     => $getdataakun->JENIS_TRANSAKSI,
									  'KODE'     => $idakun, 
									  'KODEAKUN' => $getdataakun->KODE_AKTIVA);
					
				$this->dbasemodel->insertData("m_cabang",$insertcabang);	
			} 
			unset($insert['idtrx']);
			
				if($id == "") {
					$this->dbasemodel->insertData("jenis_kas",$insert);
					$this->session->set_flashdata('ses_trx_kas', '11||Input Data Berhasil.');
				} else { 
					$this->dbasemodel->updateData("jenis_kas", $insert, "ID_JNS_KAS = '". $id."' ");
					$this->session->set_flashdata('ses_trx_kas', '11||Update Data Berhasil.');
				}
			
        }
		redirect(base_url() . 'jenis-kas');
	}
	public function get_edit(){
		if(!is_logged_in()){
			redirect('/auth_login');	
		}
		$id		= $this->input->get('id');
		$sql	= "SELECT * FROM jenis_kas WHERE ID_JNS_KAS = '".$id."'";
		$query	= $this->dbasemodel->loadsql($sql);
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
		$from	=	"jenis_kas WHERE ID_JNS_KAS = ". $id ." ";
		$this->dbasemodel->hapus($from);
		$this->session->set_flashdata('ses_trx_kas', '11||Data telah dihapus.');
		redirect(base_url() . 'jenis-kas');
	}
}