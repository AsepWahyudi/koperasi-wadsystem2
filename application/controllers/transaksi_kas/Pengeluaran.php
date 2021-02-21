<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pengeluaran extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->helper(array('app', 'form', 'url', 'xml', 'text_helper', 'date', 'inflector'));
		$this->load->database();
		$this->load->library(array('Pagination', 'user_agent', 'session', 'form_validation', 'session'));
		$this->load->model(array('dbasemodel', 'ModelVTransaksi'));
		//@session_start();
		if (!is_logged_in()) {
			redirect('/auth_login');
		}
	}

	public function index()
	{
		 
		$data['PAGE_TITLE'] = "Transaksi Pengeluaran Kas Tunai";
		$data['page']       = "transaksikas/pengeluaran";

		if (isset($_GET['tgl'])) {
			$cek = date("d/m/y", strtotime($_GET['tgl']));
			$tgl = date("Y-m-d", strtotime($cek));
			$wheretrgl = "AND DATE(TGL)='" . $tgl . "'";
		} else {
			$wheretrgl = "AND DATE(TGL)='" . date("Y-m-d") . "'";
		}

		// $koncabang = ($this->session->userdata('wad_cabang') != "") ? " AND D.KODECABANG='" . $this->session->userdata('wad_cabang') . "'" : "";

		if($this->session->userdata("wad_level") == "admin")
		{
			$sql = sprintf("SELECT A.*, B.JENIS_TRANSAKSI UNTUK_NAMA_AKUN, C.JENIS_TRANSAKSI NAMA_DARI_KAS,D.KODEPUSAT,D.KODECABANG,E.NAMA AS NAMACABANG
			FROM
			transaksi_kas A
			LEFT JOIN
			jns_akun B ON A.JENIS_TRANS = B.IDAKUN
			LEFT JOIN
			jns_akun C ON A.DARI_KAS_ID = C.IDAKUN
			LEFT JOIN
			m_user D ON A.USERNAME = D.USERNAME
			LEFT JOIN
			m_cabang E ON D.KODECABANG = E.KODE
			WHERE A.AKUN = 'Pengeluaran' $wheretrgl
			ORDER BY
			A.TGL DESC ");
		}
		else
		{
			$sql = sprintf("SELECT A.*, B.JENIS_TRANSAKSI UNTUK_NAMA_AKUN, C.JENIS_TRANSAKSI NAMA_DARI_KAS,D.KODEPUSAT,D.KODECABANG,E.NAMA AS NAMACABANG
			FROM
			transaksi_kas A
			LEFT JOIN
			jns_akun B ON A.JENIS_TRANS = B.IDAKUN
			LEFT JOIN
			jns_akun C ON A.DARI_KAS_ID = C.IDAKUN
			LEFT JOIN
			m_user D ON A.USERNAME = D.USERNAME
			LEFT JOIN
			m_cabang E ON D.KODECABANG = E.KODE
			WHERE A.AKUN = 'Pengeluaran' AND D.KODECABANG='".$this->session->userdata('wad_kodecabang')."' $wheretrgl
			ORDER BY
			A.TGL DESC ");
			
		}
		
		$data['data_source'] = $this->dbasemodel->loadsql($sql);

		$this->load->view('dashboard', $data);
	}

	public function formadd()
	{
		 
		$data['PAGE_TITLE'] = "Tambah Pengeluaran";
		$data['page']       = "transaksikas/add_pengeluaran";

		$sql = sprintf("SELECT IDAKUN, JENIS_TRANSAKSI FROM jns_akun ");
		$data['untuk_akun'] = $this->dbasemodel->loadsql($sql);
		
		if($this->session->userdata("wad_level") == "admin")
		{
			
			// $sql = sprintf("SELECT IDAKUN, NAMA_KAS
			// FROM
			// jenis_kas
			// WHERE 
			// TMPL_PENGELUARAN = 'Y'"
			// );
			$sql = "SELECT ID_JNS_KAS,IDAKUN, NAMA_KAS FROM jenis_kas WHERE TMPL_PENGELUARAN = 'Y'"; 
		}
		else
		{
			// $sql = sprintf("SELECT IDAKUN, NAMA_KAS
			// FROM
			// jenis_kas
			// WHERE 
			// TMPL_PENGELUARAN = 'Y'
			// AND KODECABANG = '%s' ",
			// $this->session->userdata('wad_kodecabang')
			// );
			$sql = "SELECT ID_JNS_KAS,IDAKUN, NAMA_KAS FROM jenis_kas WHERE TMPL_PENGELUARAN = 'Y' AND KODECABANG = '".$this->session->userdata('wad_kodecabang')."'";
		}
		
		$data['dari_kas'] =	$this->dbasemodel->loadsql($sql); 
		$data['js_to_load'] = array(); 
		$this->load->view('dashboard', $data);
	}

	public function save()
	{
		 
		$cek = date("d/m/y", strtotime($this->input->post('tgl')));
		$tgl = date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('tgl'))));

		$_POST['IDTRAN_KAS'] = $this->dbasemodel->get_id('IDTRAN_KAS', 'transaksi_kas');
		$_POST['tgl']        = $tgl . ' ' . date('H:i:s');
		$save                = $this->input->post();
		$save['dk']          = 'K';
		$save['akun']        = 'Pengeluaran';
		//$save['UPDATE_DATA']	=	date('Y-m-d H:i:s');
		$save['USERNAME'] = $this->session->userdata('wad_user');
		//print_r($save);die;
		if ($this->dbasemodel->insertData('transaksi_kas', $save)) {
			/* $insert2 = array('TGL'	=> $_POST['tgl'],
						'DEBET'		=> 0,
						'KREDIT'	=> $this->input->post('jumlah'),
						'DARI_KAS' => $this->input->post('dari_kas_id'),
						'TRANSAKSI'	=> $this->input->post('jenis_trans'),
						'USER'		=> $this->session->userdata('wad_user'),
						'KET'		=> 'Pengeluaran',
						'KODEPUSAT'		=> $this->session->userdata('wad_kodepusat'),
						'KODECABANG'	=> $this->session->userdata('wad_kodecabang')); 
			$this->dbasemodel->insertData('v_transaksi', $insert2); */
			
			$datatransaksi = array('tgl' => $tgl, 'jumlah' => $this->input->post('jumlah'), 'idkasakun' => $this->input->post('dari_kas_id'), 
								   'keterangan' => $save['keterangan']);
								   
			$this->ModelVTransaksi->insertVtransaksi($_POST['IDTRAN_KAS'], $datatransaksi,'KK',$this->input->post('jenis_trans'),$this->input->post('dari_kas_id'),'KAS');

			$this->session->set_flashdata('ses_trx_kas', '11||Transaksi Pengeluaran Kas Berhasil Disimpan.');
		} else {
			$this->session->set_flashdata('ses_trx_kas', '00||Transaksi Pengeluaran Kas Gagal Dilakukan.');
		}
		redirect(base_url() . 'kas-pengeluaran-add');
	}

	public function formedit()
	{
		 
		$data['PAGE_TITLE'] = "Edit Pengeluaran";
		$data['page']       = "transaksikas/add_pengeluaran";

		$sql = sprintf("SELECT IDAKUN, JENIS_TRANSAKSI FROM jns_akun WHERE PENGELUARAN = 'Y' ");
		$data['untuk_akun'] = $this->dbasemodel->loadsql($sql);

	
		
		if($this->session->userdata("wad_level") == "admin")
		{
			$sqlj = sprintf("SELECT ID_JNS_KAS, NAMA_KAS
						FROM
						jenis_kas
						WHERE TMPL_PENGELUARAN = 'Y' AND AKTIF = 'Y' ");
						
			$sqls = sprintf("SELECT * FROM transaksi_kas WHERE IDTRAN_KAS = %s", $this->input->get('id'));
		}
		else
		{
			$sqlj = sprintf("SELECT ID_JNS_KAS, NAMA_KAS
						FROM
						jenis_kas
						WHERE KODECABANG='".$this->session->userdata('wad_kodecabang')."' AND TMPL_PENGELUARAN = 'Y' AND AKTIF = 'Y' ");
						
			$sqls = sprintf("SELECT * FROM transaksi_kas WHERE KODECABANG='".$this->session->userdata('wad_kodecabang')."' AND IDTRAN_KAS = %s", $this->input->get('id'));
		}
			
		$data['dari_kas'] = $this->dbasemodel->loadsql($sqlj);
		$data['data_source'] = $this->dbasemodel->loadsql($sqls);

		$this->load->view('dashboard', $data);
	}

	public function update()
	{
		 
		$id = $this->input->get('id');

		$_POST['tgl']        = date('Y-m-d', strtotime($this->input->post('tgl'))) . ' ' . date('H:i:s');
		$save                = $this->input->post();
		$save['UPDATE_DATA'] = date('Y-m-d H:i:s');

		if ($this->dbasemodel->updateData('transaksi_kas', $save, "IDTRAN_KAS = '" . $id . "' ")) {
			$this->session->set_flashdata('ses_trx_kas', '11||Update Transaksi Pengeluaran Kas Berhasil Disimpan.');
		} else {
			$this->session->set_flashdata('ses_trx_kas', '00||Update Transaksi Pengeluaran Kas Gagal Dilakukan.');
		}
		redirect(base_url() . 'kas-pengeluaran');
	}

	public function delete()
	{
		 
		$id   = $this->input->get('id');
		$from = "transaksi_kas WHERE IDTRAN_KAS = " . $id . " ";
		$this->dbasemodel->hapus($from);
		$this->session->set_flashdata('ses_trx_kas', '11||Transaksi pengeluaran kas telah dihapus.');
		redirect(base_url() . 'kas-pengeluaran');
	}
}
