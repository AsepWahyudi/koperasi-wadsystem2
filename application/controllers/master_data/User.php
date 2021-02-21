<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

	function __construct(){ 
        parent::__construct();
		$this->load->helper(array('form','url', 'xml','text_helper','date','inflector','app'));
		$this->load->database();
		$this->load->library(array('Pagination','user_agent','session','form_validation','session'));
		$this->load->model('dbasemodel');
		//@session_start();
		if(!is_logged_in()){
			redirect('/auth_login');	
		}
    } 
	public function index() {
		
        $data['PAGE_TITLE'] = "Data User";
        $data['page']       = "masterdata/data_user";
        $data['response']   = '';

        if($this->input->post())
        {
			$iduser	= $this->input->post('iduser');
            $insert = array('NAMA'		 => $this->input->post('nama'),
                            'USERNAME'	 => $this->input->post('uname'),
                            'PASSWORD'	 => md5($this->input->post('passwd')),
                            'AKTIF'		 => $this->input->post('aktif'),
							'KODEPUSAT'  => $this->session->userdata('wad_kodepusat'),
							'KODECABANG' => $this->input->post('kodecabang'),
                            'LEVEL'		 => $this->input->post('level'),
                            'APPROVAL'	 => $this->input->post('approval'));
			
			if($this->check_username($this->input->post('uname'), $iduser) == true) 
			{
				$data['response'] = '<div class="alert alert-danger" role="alert"><strong>Gagal </strong>Harap gunakan username yang lain</div>';
			}
			else 
			{
				if($iduser == "") 
				{
					
					$idakun = $this->input->post('kodecabang'); // IDAKUN dari table jns_akun
					
					$cekdata = $this->dbasemodel->loadsql("SELECT * FROM m_cabang WHERE KODE='".$idakun."'")->num_rows();
					
					if($cekdata == 0){
						
						$getdataakun = $this->dbasemodel->loadsql("SELECT * FROM jns_akun WHERE IDAKUN='".$idakun."'")->row();
						
						$insertcabang = array('NAMA'     => $getdataakun->JENIS_TRANSAKSI,
										      'KODE'     => $idakun, 
											  'KODEAKUN' => $getdataakun->KODE_AKTIVA);
							
						$this->dbasemodel->insertData("m_cabang",$insertcabang);	
					} 
					$this->dbasemodel->insertData("m_user",$insert);
					$data['response'] = '<div class="alert alert-info" role="alert"><strong>Sukses </strong>Input Data Berhasil</div>';
				}
				else 
				{
					
					if(($this->input->post('passwd') == "") or ($this->input->post('passwd') == "poasdfj24skd"))
					{
						unset($insert['PASSWORD']);
					}
					
					$this->dbasemodel->updateData("m_user", $insert, "IDUSER = '". $iduser."' ");
					
					$idakun = $this->input->post('kodecabang'); // IDAKUN dari table jns_akun
					
					$cekdata = $this->dbasemodel->loadsql("SELECT * FROM m_cabang WHERE KODE='".$idakun."'")->num_rows();
					
					if($cekdata == 0){
						
						$getdataakun = $this->dbasemodel->loadsql("SELECT * FROM jns_akun WHERE IDAKUN='".$idakun."'")->row();
						
						$insertcabang = array('NAMA'     => $getdataakun->JENIS_TRANSAKSI,
										      'KODE'     => $idakun, 
											  'KODEAKUN' => $getdataakun->KODE_AKTIVA);
							
						$this->dbasemodel->insertData("m_cabang",$insertcabang);	
					} 
					
					$data['response'] = '<div class="alert alert-info" role="alert"><strong>Sukses </strong>Update Data Berhasil</div>';
				}
			}
        }

		$data['query'] = $this->dbasemodel->loadsql("SELECT * FROM m_user");
		$data['cbg']   = $this->dbasemodel->loadsql("SELECT * FROM m_cabang");
		$data['akn']   = $this->dbasemodel->loadsql("SELECT * FROM jns_akun WHERE PARENT ='3' AND HEADER='1'");
        $this->load->view('dashboard',$data);
    } 
	protected function check_username($username, $id = ""){
		$_where	=	($id == "" ? "0=0" : "IDUSER <> '". $id ."'");
		$sql	=	sprintf("SELECT IDUSER FROM m_user WHERE %s AND USERNAME LIKE '%s' ", $_where, $username);
		$query	=	$this->dbasemodel->loadsql($sql);
		if($query->num_rows() > 0) {
			return true;
		}
		return false;
	} 
	public function get_edit(){
		 
		$id    = $this->input->get('id');
		$sql   = "SELECT IDUSER, NAMA, USERNAME, 'poasdfj24skd' AS PASSWORD, AKTIF, LEVEL, APPROVAL, KODECABANG FROM m_user WHERE IDUSER = '".$id."'";
		$query = $this->dbasemodel->loadsql($sql);
		
		if($query->num_rows() > 0) 
		{
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