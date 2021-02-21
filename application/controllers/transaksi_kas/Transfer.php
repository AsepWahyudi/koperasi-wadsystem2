<?php defined('BASEPATH') or exit('No direct script access allowed');
 
class Transfer extends CI_Controller
{ 
	function __construct() {
		parent::__construct();
		$this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . 'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-chace');
        $this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");  
		 
		$this->load->database();  
		$this->load->model(array('dbasemodel', 'ModelVTransaksi', 'ModelSimpanan'));
		if (!is_logged_in()) {
			redirect('/auth_login');
		}
	} 
	public function index() { 
	
		$data['PAGE_TITLE'] = "Transaksi Transfer Kas";
		$data['page']       = "transaksikas/transfer";

		if (isset($_GET['tgl'])) {
			
			$cek       = date("d/m/y", strtotime($_GET['tgl']));
			$tgl       = date("Y-m-d", strtotime($cek));
			$wheretrgl = "AND DATE(TGL)='" . $tgl . "'";
			
		} else {
			
			$wheretrgl = "AND DATE(TGL)='" . date("Y-m-d") . "'";
			
		}
 
		if($this->session->userdata("wad_level") == "admin")
		{
			
			$sql = sprintf("SELECT A.*, B.NAMA_KAS NAMA_DARI_KAS, C.NAMA_KAS UNTUK_NAMA_AKUN,D.KODEPUSAT,D.KODECABANG 
			FROM
			transaksi_kas A
			LEFT JOIN
			jenis_kas B ON A.DARI_KAS_ID = B.IDAKUN
			LEFT JOIN
			jenis_kas C ON A.UNTUK_KAS_ID = C.IDAKUN
			LEFT JOIN
			m_user D ON A.USERNAME = D.USERNAME 
			WHERE A.AKUN = 'Transfer' $wheretrgl
			ORDER BY
			DATE(A.TGL) DESC, A.IDTRAN_KAS DESC ");

		}
		else
		{
			$sql = sprintf("SELECT A.*, B.NAMA_KAS NAMA_DARI_KAS, C.NAMA_KAS UNTUK_NAMA_AKUN,D.KODEPUSAT,D.KODECABANG 
			FROM
			transaksi_kas A
			LEFT JOIN
			jenis_kas B ON A.DARI_KAS_ID = B.IDAKUN
			LEFT JOIN
			jenis_kas C ON A.UNTUK_KAS_ID = C.IDAKUN
			LEFT JOIN
			m_user D ON A.USERNAME = D.USERNAME 
			WHERE A.AKUN = 'Transfer' AND D.KODECABANG='".$this->session->userdata('wad_kodecabang')."' $wheretrgl
			ORDER BY
			DATE(A.TGL) DESC, A.IDTRAN_KAS DESC ");

		}
		
		$data['data_source'] = $this->dbasemodel->loadsql($sql);

		$this->load->view('dashboard', $data);
	}
	public function transferanggota() { 
		$data['PAGE_TITLE'] = "Transaksi Transfer Antar Anggota";
		$data['page']       = "transaksikas/transferanggota";

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
			$sql = sprintf("SELECT A.*, B.NAMA_KAS NAMA_DARI_KAS, C.NAMA_KAS UNTUK_NAMA_AKUN,D.KODEPUSAT,D.KODECABANG 
			FROM
			transaksi_kas A
			LEFT JOIN
			jenis_kas B ON A.DARI_KAS_ID = B.IDAKUN
			LEFT JOIN
			jenis_kas C ON A.UNTUK_KAS_ID = C.IDAKUN
			LEFT JOIN
			m_user D ON A.USERNAME = D.USERNAME 
			WHERE A.AKUN = 'Transfer' AND A.DARI_ANGGOTA_ID !='0' AND A.UNTUK_ANGGOTA_ID !='0' $wheretrgl
			ORDER BY
			DATE(A.TGL) DESC, A.IDTRAN_KAS DESC ");

		}
		else
		{
			$sql = sprintf("SELECT A.*, B.NAMA_KAS NAMA_DARI_KAS, C.NAMA_KAS UNTUK_NAMA_AKUN,D.KODEPUSAT,D.KODECABANG 
			FROM
			transaksi_kas A
			LEFT JOIN
			jenis_kas B ON A.DARI_KAS_ID = B.IDAKUN
			LEFT JOIN
			jenis_kas C ON A.UNTUK_KAS_ID = C.IDAKUN
			LEFT JOIN
			m_user D ON A.USERNAME = D.USERNAME 
			WHERE A.AKUN = 'Transfer' AND A.DARI_ANGGOTA_ID !='0' AND A.UNTUK_ANGGOTA_ID !='0' AND D.KODECABANG='".$this->session->userdata('wad_kodecabang')."' $wheretrgl
			ORDER BY
			DATE(A.TGL) DESC, A.IDTRAN_KAS DESC");

		}
		$data['data_source'] = $this->dbasemodel->loadsql($sql);
		$data['sql'] = $sql;
		
		

		$this->load->view('dashboard', $data);
	}
	public function transferbank() { 
	
		$data['PAGE_TITLE'] = "Transaksi Transfer Antar Bank";
		$data['page']       = "transaksikas/transferbank";

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
			$sql = sprintf("SELECT A.*, B.NAMA_KAS NAMA_DARI_KAS, C.NAMA_KAS UNTUK_NAMA_AKUN,D.KODEPUSAT,D.KODECABANG 
			FROM
			transaksi_kas A
			LEFT JOIN
			jenis_kas B ON A.DARI_KAS_ID = B.IDAKUN
			LEFT JOIN
			jenis_kas C ON A.UNTUK_KAS_ID = C.IDAKUN
			LEFT JOIN
			m_user D ON A.USERNAME = D.USERNAME 
			WHERE A.AKUN = 'Transfer' AND A.DARI_BANK !='' AND A.UNTUK_BANK !='' $wheretrgl
			ORDER BY
			DATE(A.TGL) DESC, A.IDTRAN_KAS DESC ");

		}
		else
		{
			$sql = sprintf("SELECT A.*, B.NAMA_KAS NAMA_DARI_KAS, C.NAMA_KAS UNTUK_NAMA_AKUN,D.KODEPUSAT,D.KODECABANG 
			FROM
			transaksi_kas A
			LEFT JOIN
			jenis_kas B ON A.DARI_KAS_ID = B.IDAKUN
			LEFT JOIN
			jenis_kas C ON A.UNTUK_KAS_ID = C.IDAKUN 
			LEFT JOIN
			m_user D ON A.USERNAME = D.USERNAME 
			WHERE A.AKUN = 'Transfer' AND A.DARI_BANK !='' AND A.UNTUK_BANK !='' AND D.KODECABANG='".$this->session->userdata('wad_kodecabang')."' $wheretrgl
			ORDER BY
			DATE(A.TGL) DESC, A.IDTRAN_KAS DESC ");

		}
		$data['data_source'] = $this->dbasemodel->loadsql($sql);

		$this->load->view('dashboard', $data);
	} 
	public function formadd() {

		$data['PAGE_TITLE'] = "Tambah Transfer Antar Koprasi";
		$data['page'] = "transaksikas/add_transfer";
		
		if($this->session->userdata("wad_level") == "admin")
		{
			// $sql = sprintf("SELECT ID_JNS_KAS, NAMA_KAS FROM jenis_kas WHERE TMPL_TRANSVER = 'Y' AND AKTIF = 'Y' ");
			$sql = "SELECT ID_JNS_KAS,IDAKUN, NAMA_KAS FROM jenis_kas WHERE TMPL_TRANSVER = 'Y' AND AKTIF = 'Y'"; 
		}
		else
		{
			// $sql = sprintf("SELECT ID_JNS_KAS, NAMA_KAS FROM jenis_kas WHERE KODECABANG='".$this->session->userdata('wad_kodecabang')."' AND TMPL_TRANSVER = 'Y' AND AKTIF = 'Y' ");
			
			$sql = "SELECT ID_JNS_KAS,IDAKUN, NAMA_KAS FROM jenis_kas WHERE TMPL_TRANSVER = 'Y' AND AKTIF = 'Y' AND KODECABANG = '".$this->session->userdata('wad_kodecabang')."'";
		}
		
		$data['jenis_kas']  = $this->dbasemodel->loadsql($sql); 
		$data['js_to_load'] = array();

		$this->load->view('dashboard', $data);
	}
	public function formaddanggota() {

		$data['PAGE_TITLE'] = "Tambah Transfer Antar Anggota";
		$data['page'] = "transaksikas/add_transfer_anggota";
		
		if($this->session->userdata("wad_level") == "admin")
		{ 
			$sql = "SELECT ID_JNS_KAS,IDAKUN, NAMA_KAS FROM jenis_kas WHERE TMPL_TRANSVER = 'Y' AND AKTIF = 'Y'"; 
		}
		else
		{ 
			
			$sql = "SELECT ID_JNS_KAS,IDAKUN, NAMA_KAS FROM jenis_kas WHERE TMPL_TRANSVER = 'Y' AND AKTIF = 'Y' AND KODECABANG = '".$this->session->userdata('wad_kodecabang')."'";
		} 
		$data['jenis_kas']  = $this->dbasemodel->loadsql($sql); 
		
		$sqlbiaya               = "SELECT * FROM m_biaya_transfer";
		$data['biayatransfer']  = $this->dbasemodel->loadsql($sqlbiaya); 
		$data['js_to_load']     = array();

		$this->load->view('dashboard', $data);
	}
	public function formaddbank() {

		$data['PAGE_TITLE'] = "Tambah Transfer Antar Bank";
		$data['page'] = "transaksikas/add_transfer_bank";
		
		if($this->session->userdata("wad_level") == "admin")
		{
			// $sql = sprintf("SELECT ID_JNS_KAS, NAMA_KAS FROM jenis_kas WHERE TMPL_TRANSVER = 'Y' AND AKTIF = 'Y' ");
			$sql = "SELECT ID_JNS_KAS,IDAKUN, NAMA_KAS FROM jenis_kas WHERE TMPL_TRANSVER = 'Y' AND AKTIF = 'Y'"; 
		}
		else
		{
			// $sql = sprintf("SELECT ID_JNS_KAS, NAMA_KAS FROM jenis_kas WHERE KODECABANG='".$this->session->userdata('wad_kodecabang')."' AND TMPL_TRANSVER = 'Y' AND AKTIF = 'Y' ");
			
			$sql = "SELECT ID_JNS_KAS,IDAKUN, NAMA_KAS FROM jenis_kas WHERE TMPL_TRANSVER = 'Y' AND AKTIF = 'Y' AND KODECABANG = '".$this->session->userdata('wad_kodecabang')."'";
		}
		
		// $sqljnsakun = "SELECT * FROM jns_akun WHERE PARENT='50'";
		$sqljnsakun = "SELECT * FROM jns_akun WHERE PARENT='454'";
		$data['jns_akun']  = $this->dbasemodel->loadsql($sqljnsakun); 
		$data['jenis_kas']  = $this->dbasemodel->loadsql($sql); 
		$data['js_to_load'] = array();

		$this->load->view('dashboard', $data);
	}
	public function save() {
		
		$this->db->select('*');
		$this->db->from('m_biaya_transfer');
		$this->db->where('ID_BIAYA_TRF_KAS','3'); 
		$getbiaya = $this->db->get()->row();
		
		$cek = date("d/m/y", strtotime($this->input->post('tgl')));
		$tgl = date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('tgl'))));

		$_POST['tgl']             = $tgl . ' ' . date('H:i:s');
		$save                     =	$this->input->post();
		$save['dk']               = 'K';
		$save['akun']             = 'Transfer';
		$save['jenis_trans']      = '433';
		$save['UPDATE_DATA']      = date('Y-m-d H:i:s');
		$save['USERNAME']         = $this->session->userdata('wad_user'); 
		$save['ID_BIAYA_TRF_KAS'] = $getbiaya->ID_BIAYA_TRF_KAS;
		$save['BIAYA_TRF_KAS']    = $getbiaya->BIAYA_TRF;
		$save['NAMA_BIAYA_TRF']   = $getbiaya->NAMA_BIAYA;
		$save['STATUS_TRF']       = '1';

		if ($this->dbasemodel->insertData('transaksi_kas', $save)) {
			
			$this->db->select('*');
			$this->db->from('transaksi_kas');
			$this->db->order_by('IDTRAN_KAS','desc');
			$this->db->limit(1);
			$getkas       = $this->db->get()->row();
			$IDTRAN_KAS   = $getkas->IDTRAN_KAS; 
			$UNTUK_KAS_ID = $getkas->UNTUK_KAS_ID; 
			
			$datatransaksi = array('tgl' => $tgl, 'jumlah' => $this->input->post('jumlah'), 'idkasakun' => $UNTUK_KAS_ID, 'keterangan' => $this->input->post('keterangan'),'ket_dt' => "Transfer Dari Kas ".$this->input->post('dari_kas_id')." Untuk Kas ".$UNTUK_KAS_ID);
									 
			$this->ModelVTransaksi->insertVtransaksi($IDTRAN_KAS, $datatransaksi, 'KM', $UNTUK_KAS_ID, '433', 'KAS');
			
			$this->session->set_flashdata('ses_trx_kas', '11||Transaksi Transfer Kas Berhasil Disimpan.');
		} else {
			$this->session->set_flashdata('ses_trx_kas', '00||Transaksi Transfer Kas Gagal Dilakukan.');
		}
		redirect(base_url() . 'kas-transfer-add');
	}
	public function savetransferanggota() {
		
		// Array
		// (
		// [tgl]             => 02/12/2020
		// [jumlah]          => 1000000
		// [keterangan]      => tester 1
		// [id_anggota]      => 1383
		// [nama_penyetor]   => AAH SARIAH
		// [alamat]          => DUSUN PAHING RT.005/RW.002 KUNINGAN
		// [no_identitas]    => 3208074505730004
		// [KODECABANG]      => 19
		// [keid_anggota]    => 1383
		// [kenama_penyetor] => AAH SARIAH
		// [kealamat]        => DUSUN PAHING RT.005/RW.002 KUNINGAN
		// [keno_identitas]  => 3208074505730004
		// [KEKODECABANG]    => 19
		// )
		
		// Array
		// (
		// [tgl] => 22/12/2020
		// [jumlah] => 1000000
		// [keterangan] => tester transfer anggota
		// [id_anggota] => 411
		// [nama_penyetor] => KASDIAN
		// [alamat] => KALIPANCUR RT.002/RW.001 BATANG
		// [no_identitas] => 3325033112570018
		// [KODECABANG] => 27
		// [keid_anggota] => 2129
		// [kenama_penyetor] => MASDELINA
		// [kealamat] => PADURENAN RT03/05 TAPOS
		// [keno_identitas] => 3201014512740011
		// [KEKODECABANG] => 11
		// )
		// echo "<pre>";
		// echo print_r($_POST);
		// echo "</pre>";
		
		$this->db->select('*');
		$this->db->from('m_biaya_transfer');
		$this->db->where('ID_BIAYA_TRF_KAS','1'); 
		$getbiaya = $this->db->get()->row();
			
		$cek = date("d/m/y", strtotime($this->input->post('tgl')));
		$tgl = date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('tgl'))));

		$save['tgl']                 = $tgl . ' ' . date('H:i:s');
		// $save                     =	$this->input->post();
		$save['JUMLAH']              = $this->input->post('jumlah');
		$save['DK']                  = 'K';
		$save['AKUN']                = 'Transfer';
		$save['KETERANGAN']    		 = $this->input->post('keterangan');
		$save['DARI_ANGGOTA_ID']     = $this->input->post('id_anggota');
		$save['UNTUK_ANGGOTA_ID']    = $this->input->post('keid_anggota');
		$save['JENIS_TRANS']         = '433';
		$save['UPDATE_DATA']         = date('Y-m-d H:i:s');
		$save['USERNAME']            = $this->session->userdata('wad_user');
		$save['KODECABANG']          = $this->input->post('KEKODECABANG');
		$save['ID_BIAYA_TRF_KAS']    = $getbiaya->ID_BIAYA_TRF_KAS;
		$save['BIAYA_TRF_KAS']       = $getbiaya->BIAYA_TRF;
		$save['NAMA_BIAYA_TRF']      = $getbiaya->NAMA_BIAYA;
		$save['STATUS_TRF']          = '2';

		if ($this->dbasemodel->insertData('transaksi_kas', $save)) {
			
			$this->db->select('*');
			$this->db->from('transaksi_kas');
			$this->db->order_by('IDTRAN_KAS','desc');
			$this->db->limit(1);
			$getkas           = $this->db->get()->row();
			$IDTRAN_KAS       = $getkas->IDTRAN_KAS; 
			$DARI_ANGGOTA_ID = $getkas->DARI_ANGGOTA_ID; 
			$UNTUK_ANGGOTA_ID = $getkas->UNTUK_ANGGOTA_ID; 
			
			$this->db->select('*');
			$this->db->from('m_anggota');
			$this->db->where('IDANGGOTA',$UNTUK_ANGGOTA_ID); 
			$getanggota = $this->db->get()->row();
			$IDANGGOTA = $getanggota->IDANGGOTA;
			$KODECABANG = $getanggota->KODECABANG;
			
			$this->db->select('ID_JNS_KAS,IDAKUN, NAMA_KAS');
			$this->db->from('jenis_kas');
			$this->db->where('TMPL_SIMPAN','Y'); 
			$this->db->where('KODECABANG',$KODECABANG); 
			$getjeniskas = $this->db->get()->row();
			$IDAKUN = $getjeniskas->IDAKUN;
			
			$this->db->select('*');
			$this->db->from('m_cabang');
			$this->db->where('KODE',$KODECABANG); 
			$getcabang = $this->db->get()->row();
			$IDX = $this->session->userdata('wad_kodepusat').".".$getcabang->KODECABANG.".".$IDANGGOTA;
			
			$datatransaksi = array('tgl' => $tgl, 'jumlah' => $this->input->post('jumlah'), 'idkasakun' => $IDAKUN, 'keterangan' => $this->input->post('keterangan'),'ket_dt' => "Transfer Anggota Dari Anggota ".$IDX." Untuk Anggota ".$IDAKUN);
									 
			$this->ModelVTransaksi->insertVtransaksi($IDTRAN_KAS, $datatransaksi, 'KM', $UNTUK_KAS_ID, '433', 'KAS');
			
			$this->ModelSimpanan->updateSaldoAnggota('kurangi', $this->input->post('jumlah'),'180', $DARI_ANGGOTA_ID);
			
			$ceklst = $this->dbasemodel->loadsql("SELECT * FROM m_anggota_simp WHERE IDANGGOTA='".$UNTUK_ANGGOTA_ID."' AND IDJENIS_SIMP='180'");
			
			if($ceklst->num_rows()>0)
			{
				$rchek = $ceklst->row();
				$sql = sprintf("UPDATE m_anggota_simp SET SALDO = (SALDO + %s) WHERE ID_ANG_SIMP = %s ", $this->input->post('jumlah'), $rchek->ID_ANG_SIMP);
				$this->dbasemodel->loadSql($sql); 
			}
			else
			{
				$datacheclist = array("IDANGGOTA"	 => $key->ID_ANGGOTA,
									  "IDJENIS_SIMP" => $key->ID_JENIS,
									  "SALDO"        => $key->JUMLAH,
									  "TGLREG"		 => date("Y-m-d", strtotime($key->TGL_TRX)));
									  
				$this->dbasemodel->insertData("m_anggota_simp",$datacheclist);
			}
			
			
			
			// $_POST['id_jenis']		= '180';
			// $_POST['tgl_trx']		= date('Y-m-d', strtotime($tglTrx)) . ' ' . date('H:i:s');
			// $save					= $this->input->post();
			// $save['DK']				= 'D';
			// $save['AKUN']			= 'Setoran';
			// $save['keterangan']		= (trim($save['keterangan']) == "" ? "Setoran tunai (" . $save['nama_penyetor'] ."), sebesar rp " . toRp($save['jumlah']) : $save['keterangan']);
			// $save['USERNAME']		= $this->session->userdata('wad_user');
			// $save['KODEPUSAT']		= $this->session->userdata('wad_kodepusat'); 
			// $save['nama_penyetor']	= addslashes($save['nama_penyetor']);
			// $save['KOLEKTOR']		= 0; 
			// $save['ID_KASAKUN']		= $getIdKas->IDAKUN ;
			// $save['STATUS']			= strtolower($this->input->post('keterangan')) == 'transfer'  ? 1 : 0;
			// $this->dbasemodel->insertData('transaksi_simp', $save);
			
			
			$this->session->set_flashdata('ses_trx_kas', '11||Transaksi Transfer Kas Berhasil Disimpan.');
		} else {
			$this->session->set_flashdata('ses_trx_kas', '00||Transaksi Transfer Kas Gagal Dilakukan.');
		}
		redirect(base_url() . 'kas-transfer-add-anggota');
	}
	public function savetransferbank() {
		
		 // [tgl] => 02/12/2020
		// [jumlah] => 100000
		// [keterangan] => tester 1
		// [dari_bank] => 55
		// [untuk_bank] => 58
		// echo "<pre>";
		// echo print_r($_POST);
		// echo "</pre>";
		
		$this->db->select('*');
		$this->db->from('m_biaya_transfer');
		$this->db->where('ID_BIAYA_TRF_KAS','1'); 
		$getbiaya = $this->db->get()->row();
			
		$cek = date("d/m/y", strtotime($this->input->post('tgl')));
		$tgl = date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('tgl'))));

		$save['tgl'] = $tgl . ' ' . date('H:i:s');
		// $save                     =	$this->input->post();
		$save['JUMLAH']           = $this->input->post('jumlah');
		$save['DK']               = 'K';
		$save['AKUN']             = 'Transfer';
		$save['KETERANGAN']       = $this->input->post('keterangan');
		$save['DARI_BANK']        = $this->input->post('dari_bank');
		$save['UNTUK_BANK']       = $this->input->post('untuk_bank');
		$save['JENIS_TRANS']      = '433';
		$save['UPDATE_DATA']      = date('Y-m-d H:i:s');
		$save['USERNAME']         = $this->session->userdata('wad_user');
		$save['KODECABANG']       = $this->session->userdata('wad_kodecabang');
		$save['ID_BIAYA_TRF_KAS'] = $getbiaya->ID_BIAYA_TRF_KAS;
		$save['BIAYA_TRF_KAS']    = $getbiaya->BIAYA_TRF;
		$save['NAMA_BIAYA_TRF']   = $getbiaya->NAMA_BIAYA;
		$save['STATUS_TRF']       = '2';

		if ($this->dbasemodel->insertData('transaksi_kas', $save)) {
			
			$this->db->select('*');
			$this->db->from('transaksi_kas');
			$this->db->order_by('IDTRAN_KAS','desc');
			$this->db->limit(1);
			$getkas = $this->db->get()->row();
			$IDTRAN_KAS = $getkas->IDTRAN_KAS; 
			$UNTUK_BANK = $getkas->DARI_BANK; 
			$DARI_BANK = $getkas->UNTUK_BANK; 
			
			$this->db->select('*');
			$this->db->from('jns_akun');
			$this->db->where('IDAKUN',$this->input->post('dari_bank')); 
			$getdaribank = $this->db->get()->row();
			$DARIBANK = $getdaribank->JENIS_TRANSAKSI; 
			
			$this->db->select('*');
			$this->db->from('jns_akun');
			$this->db->where('IDAKUN',$this->input->post('untuk_bank')); 
			$getuntukbank = $this->db->get()->row();
			$UNTUKBANK = $getuntukbank->JENIS_TRANS; 
			 
			
			$datatransaksi = array('tgl' => $tgl, 'jumlah' => $this->input->post('jumlah'), 'idkasakun' => $this->input->post('untuk_bank'), 'keterangan' => $this->input->post('keterangan'),'ket_dt' => "Transfer BANK Dari BANK ".$DARIBANK." Untuk BANK ".$UNTUKBANK);
									 
			$this->ModelVTransaksi->insertVtransaksi($IDTRAN_KAS, $datatransaksi, 'KM', $UNTUK_KAS_ID, '433', 'KAS');
			 
			 
			$this->session->set_flashdata('ses_trx_kas', '11||Transaksi Transfer Kas Berhasil Disimpan.');
		} else {
			$this->session->set_flashdata('ses_trx_kas', '00||Transaksi Transfer Kas Gagal Dilakukan.');
		}
		redirect(base_url() . 'kas-transfer-add-bank');
	}
	public function get_anggota(){
		 
		$keyw = $this->input->get('para1');
		
		if($this->session->userdata("wad_level") == "admin")
		{
			
			$sql  = sprintf("SELECT IDANGGOTA id, NAMA text, ALAMAT alamat, NO_IDENTITAS identitas, KODECABANG
			FROM
			m_anggota
			WHERE 
			NAMA LIKE '%s' AND AKTIF = 'Y' 
			ORDER BY NAMA ASC",
			"%". $keyw ."%");
			
			
		}
		else
		{
			$sql  = sprintf("SELECT IDANGGOTA id, NAMA text, ALAMAT alamat, NO_IDENTITAS identitas, KODECABANG
			FROM
			m_anggota
			WHERE 
			NAMA LIKE '%s' AND AKTIF = 'Y'
			AND KODECABANG = '%s'
			ORDER BY NAMA ASC",
			"%". $keyw ."%",
			$this->session->userdata('wad_kodecabang'));

		}
		
		// echo $sql;
		$query  = $this->dbasemodel->loadsql($sql);
		$result = $query->result_array();
		echo json_encode($result);
	}
	public function get_anggotas(){
		 
		$keyw = $this->input->get('para1');
		
		if($this->session->userdata("wad_level") == "admin")
		{
			
			$sql  = sprintf("SELECT IDANGGOTA id, NAMA text, ALAMAT alamat, NO_IDENTITAS identitas, KODECABANG
			FROM
			m_anggota
			WHERE 
			NAMA LIKE '%s' AND AKTIF = 'Y' 
			ORDER BY NAMA ASC",
			"%". $keyw ."%");
			
			
		}
		else
		{
			$sql  = sprintf("SELECT IDANGGOTA id, NAMA text, ALAMAT alamat, NO_IDENTITAS identitas, KODECABANG
			FROM
			m_anggota
			WHERE 
			NAMA LIKE '%s' AND AKTIF = 'Y'
			AND KODECABANG = '%s'
			ORDER BY NAMA ASC",
			"%". $keyw ."%",
			$this->session->userdata('wad_kodecabang'));

		}
		
		// echo $sql;
		$query  = $this->dbasemodel->loadsql($sql);
		$result = $query->result_array();
		echo json_encode($result);
	}
	public function formedit() {

		$data['PAGE_TITLE'] = "Edit Transfer Kas";
		$data['page']       = "transaksikas/add_transfer";
		
		if($this->session->userdata("wad_level") == "admin")
		{
			$sqlk = sprintf("SELECT ID_JNS_KAS, NAMA_KAS FROM jenis_kas WHERE TMPL_TRANSVER = 'Y' AND AKTIF = 'Y' ");
			
			$sql = sprintf("SELECT * FROM transaksi_kas WHERE IDTRAN_KAS = %s", $this->input->get('id'));
		}
		else
		{
			$sqlk = sprintf("SELECT ID_JNS_KAS, NAMA_KAS FROM jenis_kas WHERE KODECABANG='".$this->session->userdata('wad_kodecabang')."' AND TMPL_TRANSVER = 'Y' AND AKTIF = 'Y' ");
			
			$sql = sprintf("SELECT * FROM transaksi_kas WHERE KODECABANG='".$this->session->userdata('wad_kodecabang')."' AND IDTRAN_KAS = %s", $this->input->get('id')); 
		}
	
		$data['jenis_kas'] = $this->dbasemodel->loadsql($sqlk);  
		$data['data_source'] = $this->dbasemodel->loadsql($sql);

		$this->load->view('dashboard', $data);
	} 
	public function update() {
		$id = $this->input->get('id');

		$_POST['tgl']        = date('Y-m-d', strtotime($this->input->post('tgl'))) . ' ' . date('H:i:s');
		$save                = $this->input->post();
		$save['UPDATE_DATA'] = date('Y-m-d H:i:s');

		if ($this->dbasemodel->updateData('transaksi_kas', $save, "IDTRAN_KAS = '" . $id . "' ")) {
			$this->session->set_flashdata('ses_trx_kas', '11||Update Transaksi Transfer Kas Berhasil Disimpan.');
		} else {
			$this->session->set_flashdata('ses_trx_kas', '00||Update Transaksi Transfer Kas Gagal Dilakukan.');
		}
		redirect(base_url() . 'kas-transfer');
	} 
	public function delete() {
		$id   = $this->input->get('id');
		$from = "transaksi_kas WHERE IDTRAN_KAS = " . $id . " ";
		$this->dbasemodel->hapus($from);
		$this->session->set_flashdata('ses_trx_kas', '11||Transaksi transfer kas telah dihapus.');
		redirect(base_url() . 'kas-transfer');
	}
}
