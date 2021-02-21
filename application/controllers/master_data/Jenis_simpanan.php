<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jenis_simpanan extends CI_Controller {

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
		if($this->input->post())
		{
			$jnis	= $this->input->post('jns_simpadd');
			$jumlah	= $this->input->post('jumlahadd');
			$auto	= $this->input->post('auto_debetadd');
			$tmpl	= $this->input->post('tampiladd');
			$idakun = $this->input->post('idakun');
			
			$cek = $this->dbasemodel->loadsql("SELECT * FROM jns_simpan WHERE IDAKUN='$idakun'");
			if($cek->num_rows()>0){
				$this->session->set_flashdata('ses_trx_simp', '00||Akun telah di gunakan produk lain.');
			}else{
				$datainsert = array('JNS_SIMP'=>$jnis,
									'JUMLAH'=>$jumlah,
									'AUTO_DEBET'=>$auto,
									'TAMPIL'=>$tmpl,
									'IDAKUN'=>$idakun,
									);
				
				$this->dbasemodel->insertData("jns_simpan",$datainsert);
				$this->session->set_flashdata('ses_trx_simp', '11||Input Data Berhasil');
			}
			
			
			
		}
		$data['akunz']			= $this->dbasemodel->loadsql("SELECT * FROM jns_akun 
															WHERE jenis_transaksi LIKE 'simpanan%' AND header = 0 
															ORDER BY AKUN");
        $data['PAGE_TITLE']     = "Jenis Simpanan";
		$data['page']           = "masterdata/jenis_simpanan";
		$data['query']			= $this->dbasemodel->loadsql("SELECT * FROM jns_simpan");

        $this->load->view('dashboard',$data);
    }
	
	public function save(){
		if($this->input->post('simpanedit'))
        {
			$id			= $this->input->post('idtrx');
            //$insert 	= $this->input->post();
			unset($insert['idtrx']);
			
			$jnis	= $this->input->post('jns_simp');
			$jumlah	= $this->input->post('jumlah');
			$auto	= $this->input->post('auto_debet');
			$tmpl	= $this->input->post('tampil');
			$idakun = $this->input->post('idakun');
			
			$dataupdate = array('JNS_SIMP'=>$jnis,
									'JUMLAH'=>$jumlah,
									'AUTO_DEBET'=>$auto,
									'TAMPIL'=>$tmpl,
									'IDAKUN'=>$idakun,
									);
			
			$this->dbasemodel->updateData("jns_simpan", $dataupdate, "IDJENIS_SIMP = '". $id."' ");
			$this->session->set_flashdata('ses_trx_simp', '11||Update Data Berhasil.');
			
        }
		redirect(base_url() . 'jenis-simpanan');
	}
	
	public function get_edit(){
		if(!is_logged_in()){
			redirect('/auth_login');	
		}
		$id		=	$this->input->get('id');
		$sql	=	sprintf("SELECT * FROM jns_simpan WHERE IDJENIS_SIMP = '%s' ", $id);
		$query	=	$this->dbasemodel->loadsql($sql);
		if($query->num_rows() > 0) {
			$result	=	$query->result_array();
			echo json_encode ($result[0]);
		}
		echo null;
	}
	/*public function delete(){
		if(!is_logged_in()){
			redirect('/auth_login');	
		}
		
		$id		=	$this->uri->segment(4);
		$from	=	"jns_simpan WHERE IDJENIS_SIMP = ". $id ." ";
		$this->dbasemodel->hapus($from);
		$this->session->set_flashdata('ses_trx_simp', '11||Data telah dihapus.');
		redirect(base_url() . 'jenis-simpanan');
	}*/
}