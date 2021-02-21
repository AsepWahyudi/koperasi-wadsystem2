<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bpkb extends CI_Controller {

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
        $data['PAGE_TITLE']     = "Data BPKB";
        $data['page']           = "masterdata/data_bpkb";
        $data['response']       = '';

        if($this->input->post())
        {
			$idbpkb	= $this->input->post('idbpkb');
            $insert = array('NAMAG'			=> $this->input->post('namag'),
                            'NOS'			=> $this->input->post('nos'),
                            'PROV'			=> $this->input->post('prov'),
                            'KOT'			=> $this->input->post('kot'),
							'KEL' 			=> $this->input->post('kel'),
							'LUAS' 			=> '',
							'NAMAP' 		=> $this->input->post('namap'),
							'TAKSIR' 		=> $this->input->post('taksir'),
							'KODECABANG'	=> $this->input->post('kodecabang'),
							'STNK'			=> $this->input->post('stnk'),
							'NOPOL'			=> $this->input->post('nopol'),
							'MERK'			=> $this->input->post('merk'),
							'TIPE'			=> $this->input->post('tipe'),
							'MASA_PAJAK'	=> date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('masa_pajak'))))
						);
                            
			
		if($this->check_namag($this->input->post('namag'), $idbpkb) == true) {
				$data['response']    = '<div class="alert alert-danger" role="alert"><strong>Gagal </strong>Harap gunakan username yang lain</div>';
			} else {
				if($idbpkb == "") {
					$this->dbasemodel->insertData("m_bpkb",$insert);
					$data['response']    = '<div class="alert alert-info" role="alert"><strong>Sukses </strong>Input Data Berhasil</div>';
				} else {
					if(($this->input->post('passwd') == "") or ($this->input->post('passwd') == "poasdfj24skd")) {
						unset($insert['PASSWORD']);
					}
					$this->dbasemodel->updateData("m_bpkb", $insert, "IDBPKB = '". $idbpkb."' ");
					$data['response']    = '<div class="alert alert-info" role="alert"><strong>Sukses </strong>Update Data Berhasil</div>';
				}
			}
        }

		$data['query']			= $this->dbasemodel->loadsql("SELECT * FROM m_bpkb");
		$data['cbg']			= $this->dbasemodel->loadsql("SELECT * FROM m_cabang");
        $this->load->view('dashboard',$data);
    }
	
	protected function check_namag($namag, $id = ""){
		$_where	=	($id == "" ? "0=0" : "IDBPKB <> '". $id ."'");
		$sql	=	sprintf("SELECT IDBPKB FROM m_bpkb WHERE %s AND NAMAG LIKE '%s' ", $_where, $namag);
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
		$sql	=	sprintf("SELECT IDUSER,NAMA,USERNAME, 'poasdfj24skd' AS PASSWORD,AKTIF,LEVEL,APPROVAL FROM m_user WHERE IDUSER = '%s' ", $id);
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
		$from	=	"m_user WHERE IDUSER = ". $id ." ";
		$this->dbasemodel->hapus($from);
		$this->session->set_flashdata('ses_trx_bpkb', '11||Data user telah dihapus.');
		redirect(base_url() . 'bpkb');
	}
}