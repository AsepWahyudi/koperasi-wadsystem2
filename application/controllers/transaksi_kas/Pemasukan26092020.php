<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pemasukan extends CI_Controller {

	function __construct(){ 
        parent::__construct();
		$this->load->helper(array('app', 'form','url', 'xml','text_helper','date','inflector'));
		$this->load->database();
		$this->load->library(array('Pagination','user_agent','session','form_validation','session', 'tree'));
		$this->load->model(array('dbasemodel', 'ModelVTransaksi'));
		//@session_start();
		
		if(!is_logged_in()){
			redirect('/auth_login');	
		}
		
    }
	
	public function index(){
		
		
        $data['PAGE_TITLE'] = "Transaksi Pemasukan Kas Tunai";
        $data['page']       = "transaksikas/pemasukan";
		
		if(isset($_GET['tgl']))
		{
			$cek = date("d/m/y", strtotime($_GET['tgl']));
			$tgl = date("Y-m-d", strtotime($cek));
			$wheretrgl = "AND DATE(TGL)='".$tgl."'";
		}else{
			$wheretrgl = "AND DATE(TGL)='".date("Y-m-d")."'";
		}
		
		if($this->session->userdata("wad_level") == "admin")
		{
			
			$sql = sprintf("SELECT A.*, B.JENIS_TRANSAKSI NAMA_KAS, C.JENIS_TRANSAKSI, D.KODEPUSAT,D.KODECABANG,E.NAMA AS NAMACABANG
			FROM
			transaksi_kas A
			LEFT JOIN
			jns_akun B ON A.UNTUK_KAS_ID = B.IDAKUN
			LEFT JOIN
			jns_akun C ON A.JENIS_TRANS = C.IDAKUN
			LEFT JOIN
			m_user D ON A.USERNAME = D.USERNAME
			LEFT JOIN
			m_cabang E ON D.KODECABANG = E.KODE
			WHERE A.AKUN = 'Pemasukan' $wheretrgl
			ORDER BY
			A.TGL DESC ");
		}
		else
		{
			$sql = sprintf("SELECT A.*, B.JENIS_TRANSAKSI NAMA_KAS, C.JENIS_TRANSAKSI, D.KODEPUSAT,D.KODECABANG,E.NAMA AS NAMACABANG
			FROM
			transaksi_kas A
			LEFT JOIN
			jns_akun B ON A.UNTUK_KAS_ID = B.IDAKUN
			LEFT JOIN
			jns_akun C ON A.JENIS_TRANS = C.IDAKUN
			LEFT JOIN
			m_user D ON A.USERNAME = D.USERNAME
			LEFT JOIN
			m_cabang E ON D.KODECABANG = E.KODE
			WHERE A.AKUN = 'Pemasukan' AND D.KODECABANG='".$this->session->userdata('wad_kodecabang')."' $wheretrgl
			ORDER BY
			A.TGL DESC ");
		}
		// $koncabang = ($this->session->userdata('wad_cabang')!="")? " AND D.KODECABANG='".$this->session->userdata('wad_cabang')."'":"";
		
       
		//echo  $sql;
		$data['data_source'] = $this->dbasemodel->loadsql($sql);

        $this->load->view('dashboard',$data);
    }

    public function formadd(){
		 
        $data['PAGE_TITLE'] = "Tambah Pemasukan";
        $data['page']       = "transaksikas/add_pemasukan";
		
		$sql = sprintf("SELECT IDAKUN, JENIS_TRANSAKSI FROM jns_akun"); 
		
		$data['dari_akun'] = $this->dbasemodel->loadsql($sql);
		
		if($this->session->userdata("wad_level") == "admin")
		{
			// $sql = sprintf("SELECT IDAKUN, NAMA_KAS FROM jenis_kas WHERE TMPL_PEMASUKAN = 'Y'" );
			$sql = "SELECT ID_JNS_KAS,IDAKUN, NAMA_KAS FROM jenis_kas WHERE TMPL_PEMASUKAN = 'Y'"; 
		}
		else
		{
			/* $sql = sprintf("SELECT IDAKUN, NAMA_KAS
						FROM
						jenis_kas
						WHERE 
						TMPL_PEMASUKAN = 'Y'
						AND KODECABANG = '%s' ",
						$this->session->userdata('wad_kodecabang')
						); */
			$sql = "SELECT ID_JNS_KAS,IDAKUN, NAMA_KAS FROM jenis_kas WHERE TMPL_PEMASUKAN = 'Y' AND KODECABANG = '".$this->session->userdata('wad_kodecabang')."'";
		}
		 
		$data['untuk_kas'] = $this->dbasemodel->loadsql($sql); 
        $data['js_to_load'] = array(); 
        $this->load->view('dashboard',$data);
    }
	
	public function save(){
		  
		$cek = date("d/m/y", strtotime($this->input->post('tgl')));
		$tgl = date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('tgl'))));

		$_POST['IDTRAN_KAS'] = $this->dbasemodel->get_id('IDTRAN_KAS', 'transaksi_kas'); 
		$_POST['tgl']        = $tgl . ' ' . date('H:i:s');
		$save                = $this->input->post();
		$save['dk']          = 'D';
		$save['akun']        = 'Pemasukan';
		$save['UPDATE_DATA'] = date('Y-m-d H:i:s');
		$save['USERNAME']    = $this->session->userdata('wad_user');
		$save['KODEPUSAT']   = $this->session->userdata('wad_kodepusat');
		
		if($this->dbasemodel->insertData('transaksi_kas', $save)) {
			/* $insert2 = array('TGL'	=> date("Y-m-d H:i:s"),
						'DEBET'		=> $this->input->post('jumlah'),
						'KREDIT'	=> "0",
						'UNTUK_KAS' => $this->input->post('untuk_kas_id'),
						'TRANSAKSI'	=> $this->input->post('jenis_trans'),
						'USER'		=> $this->session->userdata('wad_user'),
						'KET'		=> 'Pemasukan',
						'KODEPUSAT'		=> $this->session->userdata('wad_kodepusat'),
						'KODECABANG'	=> $this->session->userdata('wad_kodecabang')); 
			$this->dbasemodel->insertData('v_transaksi', $insert2);*/
			
			$this->ModelVTransaksi->insertVtransaksi($_POST['IDTRAN_KAS'], 
													$this->input->post(), 'KM', 
													$this->input->post('untuk_kas_id'), 
													$this->input->post('jenis_trans'), 'KAS');
			
			$this->session->set_flashdata('ses_trx_kas', '11||Transaksi Pemasukan Kas Berhasil Disimpan.');
		} else {
			$this->session->set_flashdata('ses_trx_kas', '00||Transaksi Pemasukan Kas Gagal Dilakukan.');
		}
		redirect(base_url() . 'kas-pemasukan-add');
	}
	 
	public function formedit(){
		 
        $data['PAGE_TITLE'] = "Edit Pemasukan";
        $data['page']       = "transaksikas/add_pemasukan";
		// KODECABANG='".$this->session->userdata('wad_kodecabang')."'
		
		$sql = sprintf("SELECT IDAKUN, JENIS_TRANSAKSI FROM jns_akun WHERE PEMASUKAN = 'Y' ");
		$data['dari_akun'] = $this->dbasemodel->loadsql($sql);
		
		$sql = sprintf("SELECT IDAKUN, JENIS_TRANSAKSI FROM jns_akun ");
		$data['untuk_kas'] = $this->dbasemodel->loadsql($sql);
		
		if($this->session->userdata("wad_level") == "admin")
		{
			$sqlkas = sprintf("SELECT * FROM transaksi_kas WHERE IDTRAN_KAS = %s", $this->input->get('id'));
		}
		else
		{
			$sqlkas = sprintf("SELECT * FROM transaksi_kas WHERE KODECABANG='".$this->session->userdata('wad_kodecabang')."' AND IDTRAN_KAS = %s", $this->input->get('id'));
		}
		
        $data['data_source'] = $this->dbasemodel->loadsql($sqlkas);

        $this->load->view('dashboard',$data);
    }
	
	public function update(){
		 
		$id = $this->input->get('id');
		
		$_POST['tgl']        = date('Y-m-d', strtotime($this->input->post('tgl'))) . ' ' . date('H:i:s');
		$save                = $this->input->post();
		$save['UPDATE_DATA'] = date('Y-m-d H:i:s');
		
		if($this->dbasemodel->updateData('transaksi_kas', $save, "IDTRAN_KAS = '". $id ."' ")) {
			/*$this->ModelVTransaksi->insertVtransaksi($_POST['IDTRAN_KAS'], 
													$this->input->post(), 'KM', 
													$this->input->post('untuk_kas_id'), 
													$this->input->post('jenis_trans'), 'KAS'); */
													
			$this->session->set_flashdata('ses_trx_kas', '11||Update Transaksi Pemasukan Kas Berhasil Disimpan.');
		} else {
			$this->session->set_flashdata('ses_trx_kas', '00||Update Transaksi Pemasukan Kas Gagal Dilakukan.');
		}
		redirect(base_url() . 'kas-pemasukan');
	}
	
	public function delete(){
		if(!is_logged_in()){
			redirect('/auth_login');	
		}
		$id		=	$this->input->get('id');
		$from	=	"transaksi_kas WHERE IDTRAN_KAS = ". $id ." ";
		$this->dbasemodel->hapus($from);
		$this->session->set_flashdata('ses_trx_kas', '11||Transaksi kas masuk telah dihapus.');
		redirect(base_url() . 'kas-pemasukan');
	}
}