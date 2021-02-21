<?php
require APPPATH.'third_party/vendor/autoload.php';    
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

defined('BASEPATH') OR exit('No direct script access allowed');

class Riwayat extends CI_Controller {

	function __construct(){ 
        parent::__construct();
		$this->load->helper(array('app', 'form','url', 'xml','text_helper','date','inflector','app'));
		$this->load->database();
		$this->load->library(array('Pagination','user_agent','session','form_validation','session', 'table', 'tree'));
		$this->load->model('dbasemodel');
		if(!is_logged_in()){
			redirect('/auth_login');	
		}
    }
	
	public function index(){
		
		 
		$data['opt_data_entries']	=	$this->load->view('_elements/data_entries', NULL, TRUE);
		$data['table_footer']		=	$this->load->view('_elements/table_footer', NULL, TRUE);
        $data['PAGE_TITLE']     	= 	"Riwayat Pinjaman";
        $data['page']           	= 	"pinjaman/riwayat";
        $this->load->view('dashboard',$data);
    }
	
	
	public function detail(){
		 
		$id = $this->input->post('idanggota');
		
		$sql = sprintf("SELECT 
		A.IDPINJM_H,
		A.REKENING, 
		DATE_FORMAT(A.TGL_PINJ, '%s') AS TGL_PINJ,
		A.LAMA_ANGSURAN,
		A.JUMLAH,
		A.LUNAS,
		A.IS_RESET,
		A.STATUS,
		CONCAT_WS('', A.KODEPUSAT, '.', A.KODECABANG, '.', A.REKENING, '') REKENING,
		CONCAT_WS('', A.KODEPUSAT, '.', A.KODECABANG, '.', B.NO_ANGGOTA, '') KODE_ANGGOTA,
		B.NAMA 
		FROM 
		tbl_pinjaman_h A
		LEFT JOIN
		m_anggota B ON B.IDANGGOTA = A.ANGGOTA_ID 
		WHERE 
		B.IDANGGOTA = '%s' 
		ORDER BY
		TGL_PINJ DESC",
		'%d/%m/%Y', $id
		);
		
		$query	=	$this->dbasemodel->loadSql($sql);
		echo json_encode($query->result_array());
	}
	
	
	public function get_jenispinjam() {
	    $id 	= $this->input->post('data');
		$sql	= sprintf("SELECT BAGIHASIL, BIAYAADMIN, REKOM_PINJ, ASURANSI FROM jns_pinjm WHERE IDAKUN = %s ", $id);
	    $query	= $this->dbasemodel->loadsql($sql)->result_array();
		$row	= $query[0];
		
		$sql	= sprintf("SELECT IDPINJM_H FROM tbl_pinjaman_h WHERE ANGGOTA_ID = '%s' AND LUNAS LIKE 'Lunas' ", $this->input->post('idanggota'));
		$query	= $this->dbasemodel->loadsql($sql)->num_rows();
		$row['jml_pinjam']	=	$query;
	    echo json_encode($row);
	}
	
	
	public function get_pinjaman() {
	    $id 	=	$this->input->post('data');
		$sql	=	sprintf("SELECT ISCREDIT FROM m_anggota WHERE IDANGGOTA = %s ", $id);
	    $query	=	$this->dbasemodel->loadsql($sql);
		$row	=	$query->row();
	    echo $row->ISCREDIT;
	}
	
	public function get_datasaudara() {
	    $id 	=	$this->input->post('data');
		$sql	=	sprintf("SELECT NAMA_SAUDARA,TELP_SAUDARA,HUB_SAUDARA,ALMT_SAUDARA FROM m_anggota WHERE IDANGGOTA = %s ", $id);
	    $query	=	$this->dbasemodel->loadsql($sql);
		$row	=	$query->row();
	    echo json_encode($row);
	}
	
	
    public function formadd(){
		error_reporting(-1);
		ini_set('display_errors', 1);
		
		if(!is_logged_in()){
			redirect('/auth_login');	
		}
		
        $data['PAGE_TITLE'] = "Tambah Pinjaman";
        $data['page'] = "pinjaman/add_pinjaman";
		
		$sql = sprintf("SELECT IDJNS_PINJ, JNS_PINJ, HARGA, IDAKUN
		FROM
		jns_pinjm 
		ORDER BY JNS_PINJ");
		$data['jenis_pembiayaan'] = $this->dbasemodel->loadsql($sql);
		
		if($this->session->userdata("wad_level") == "admin")
		{
			$sqlk = sprintf("SELECT IDAKUN, NAMA_KAS
			FROM
			jenis_kas
			WHERE 
			TMPL_SIMPAN = 'Y'"
			);
		}
		else
		{
			$sqlk = sprintf("SELECT IDAKUN, NAMA_KAS
			FROM
			jenis_kas
			WHERE 
			TMPL_SIMPAN = 'Y'
			AND KODECABANG = '%s' ",
			$this->session->userdata('wad_kodecabang')
			);
		}
		 
		$data['jenis_kas'] = $this->dbasemodel->loadsql($sqlk);
		
		$sql = sprintf("SELECT IDANGSURAN, KETERANGAN
		FROM
		jns_angsuran
		WHERE AKTIF = 'Y' 
		ORDER BY KETERANGAN ASC");
		$data['jenis_ags'] = $this->dbasemodel->loadsql($sql);
		
        $data['js_to_load'] = array();

        $this->load->view('dashboard',$data);
    }
	
	public function save(){
		if(!is_logged_in()){
			redirect('/auth_login');	
		}
		
		$topup 			= $this->_integer($this->input->post('topup'));
		$saldo_tabungan = $this->_integer($this->input->post('saldo_tabungan'));
		if($topup > 0) {
		    $saldo_tabungan = $topup;
		} else {
		    $saldo_tabungan = 0;
		}
		
		$idtrx		=	$this->dbasemodel->get_id('IDPINJM_H', 'tbl_pinjaman_h');
		$save		=	array('IDPINJM_H'		=>	$idtrx,
							  'REKENING'		=>  getRekpinj($this->session->userdata('wad_kodepusat'),$this->session->userdata('wad_kodecabang')),
							  'TGL_PINJ' 		=>	date('Y-m-d', strtotime($this->input->post('tgl_pinj'))) . ' ' . date('H:i:s'),
							  'ANGGOTA_ID'		=>	$this->input->post('id_anggota'),
							  'BARANG_ID'		=>	$this->input->post('idjenis_pinjam'),
							  'LAMA_ANGSURAN'	=>	$this->input->post('lama_angsuran'),
							  'JUMLAH'			=>	$this->_integer($this->input->post('jumlah')),
							  'BUNGA'			=>	$this->input->post('bunga'),
							  'BIAYA_ADMIN'		=>	$this->_integer($this->input->post('biaya_adm')),
							  'BIAYA_ASURANSI'	=>	$this->_integer($this->input->post('biaya_asuransi')),
							  'JAMINAN_TABUNGAN'=>	$saldo_tabungan,
							  'LUNAS'			=>	'Belum',
							  'DK'				=>	'K',
							  'KAS_ID'			=>	$this->input->post('id_kas'),
							  'JNS_TRANS'		=>	$this->input->post('idjenis_pinjam'),
							  'NAMA_SDR'		=>	$this->input->post('nama_saudara'),
							  'HUB_SDR'			=>	$this->input->post('hubungan_saudara'),
							  'TELP_SDR'		=>	$this->input->post('telp_saudara'),
							  'ALAMAT_SDR'		=>	$this->input->post('alamat_saudara'),
							  'USERNAME'		=>	$this->session->userdata('wad_user'),
							  'KODEPUSAT'		=>	$this->session->userdata('wad_kodepusat'),
							  'KODECABANG'		=>	$this->session->userdata('wad_kodecabang'),
							  'KETERANGAN'		=>	$this->input->post('ket'),
							  'NO_JAMINAN'		=>	$this->session->userdata('no_jaminan'),
							  'JENIS_JAMINAN'	=>	$this->input->post('jenis_jaminan')
							  /* 'PINJ_TOTAL'		=>	($save['JUMLAH'] + (($save['BUNGA'] * $save['JUMLAH']) / 100)),
							  'PINJ_DIBAYAR'	=>	0,
							  'PINJ_SISA'		=>	,
							  'PINJ_RP_ANGSURAN'=>	,
							  'PINJ_BASIL_DASAR'=>	((($save['BUNGA'] * $save['JUMLAH']) / 100) / $save['LAMA_ANGSURAN'])*/
						);
		if($this->dbasemodel->insertData('tbl_pinjaman_h', $save)) {
			
			$insertsimp4 = array('IDANGGOTA'=> $this->input->post('id_anggota'),
						'IDUSER'		=> $this->session->userdata('wad_id'),
						'JUMLAH'		=> $this->_integer($this->input->post('biaya_asuransi')),
						'TGL'			=> date("Y-m-d H:i:s"),
						'JENIS'			=> "2",
						'KODEPUSAT'		=> $this->session->userdata('wad_kodepusat'),
						'KODECABANG'	=> $this->session->userdata('wad_kodecabang'));
			$this->dbasemodel->insertData('m_asuransi', $insertsimp4);
			
			/*$sql	=	sprintf("UPDATE jns_pinjm SET JML_BARANG = (JML_BARANG - 1) WHERE IDJNS_PINJ = %s AND TIPE <> 'uang' ", $this->input->post('barang_id'));
			$this->dbasemodel->loadSql($sql);
			
			$update	=	array('PINJ_POKOK' => $save['JUMLAH'], 
							  'PINJ_TOTAL' => ($save['JUMLAH'] + (($save['BUNGA'] * $save['JUMLAH']) / 100)),
							  'PINJ_RP_ANGSURAN' => (($save['JUMLAH'] + (($save['BUNGA'] * $save['JUMLAH']) / 100)) / $save['LAMA_ANGSURAN']),
							  'PINJ_BASIL_DASAR' => ((($save['BUNGA'] * $save['JUMLAH']) / 100) / $save['LAMA_ANGSURAN']) );
			$this->dbasemodel->updateData('m_anggota', $update, "IDANGGOTA = '". $save['ANGGOTA_ID'] ."' ");*/
			
			$this->session->set_flashdata('ses_trx_pinj', '11||Transaksi Pinjaman Berhasil Disimpan.');
			
		} else {
			$this->session->set_flashdata('ses_trx_pinj', '00||Transaksi Pinjaman Gagal Dilakukan.');
		}
		
		echo true;
	}
	
	
	
	
	// UNTUK UMUM
	public function formAngsuran(){
		if(!is_logged_in()){
			redirect('/auth_login');	
		}
		$id		=	$this->input->get('idpj');
		$data_angsuran	=	$this->_data_angsuran($id);
		$data_pinjaman	=	$this->_data_pinjaman($id);
		
		$total_tagihan	=	$data_pinjaman['JUMLAH'] + (($data_pinjaman['BUNGA'] * $data_pinjaman['JUMLAH']) / 100);
		
		$result['idagt']		=	$this->input->get('idagt');
		$result['idpinjam']		=	$data_pinjaman['IDPINJM_H'];
		$result['ags_ke']		=	($data_angsuran['jml_ags'] + 1);
		$result['sisa_ags']		=	($data_pinjaman['LAMA_ANGSURAN'] - $data_angsuran['jml_ags'] - 1);
		$result['sisa_tag']		=	toRp($total_tagihan - $data_angsuran['total_bayar']);
		$result['ags_perbulan']	=	toRp($total_tagihan / $data_pinjaman['LAMA_ANGSURAN']);
		$result['pinj_basil_dasar']		=	toRp($data_pinjaman['PINJ_BASIL_DASAR']);
		$result['pinj_pokok_sisa']		=	toRp($data_pinjaman['PINJ_POKOK_SISA']);
		$result['pinj_basil_total']		=	toRp($data_pinjaman['PINJ_BASIL_TOTAL']);
		$result['pinj_basil_dibayar']	=	toRp($data_pinjaman['PINJ_BASIL_BAYAR']);
		$result['denda']				=	toRp(sukubunga('denda'));
		$result['simpan_kas']			=	$this->_jenis_kas();
		$result['biayareset']			=	toRp($this->_biaya_reset($data_pinjaman['IDPINJM_H'], 0));
		$result['kolektor']				=	toRp($this->_biaya_reset($data_pinjaman['IDPINJM_H'], 1));
		
		echo json_encode($result);
	}
	
	protected function _biaya_reset($idpinjam, $jenis) {
		
		if($this->session->userdata("wad_level") == "admin")
		{
			$sql = sprintf("SELECT (SUM(JUMLAH) - SUM(DIBAYAR)) TOTAL_BIAYA FROM tbl_reset WHERE JENIS = %s AND LUNAS = 0 AND IDPINJAMAN = %s ", $jenis, $idpinjam);
		}
		else
		{
			$sql = sprintf("SELECT (SUM(JUMLAH) - SUM(DIBAYAR)) TOTAL_BIAYA FROM tbl_reset WHERE KODECABANG ='".$this->session->userdata('wad_kodecabang')."' AND JENIS = %s AND LUNAS = 0 AND IDPINJAMAN = %s ", $jenis, $idpinjam);
		}
		 
		$query	=	$this->dbasemodel->loadsql($sql);
		if($query->num_rows() > 0) {
			$row	=	$query->row();
			return $row->TOTAL_BIAYA;
		}
		return 0;
	}
	
	public function save_angsuran(){
		
		$angsuran		=	$this->input->post('jml_angsuran');
		$bayar_saldo	=	0;
		$biaya_reset	=	$this->input->post('biaya_reset') > 0 ? $this->input->post('biaya_reset') : 0;
		$biaya_kolektor	=	$this->input->post('biaya_kolektor') > 0 ? $this->input->post('biaya_kolektor') : 0;
		$tgl_trx		=	date('Y-m-d', strtotime($this->input->post('tgl_trx'))) . date(' H:i:s');
		if($this->input->post('chk_bayar_saldo') == true) {
			$bayar_saldo	=	$this->input->post('bayar_saldo');
			$bayar_saldo	=	($bayar_saldo > $angsuran) ? $angsuran : $bayar_saldo;
			if($bayar_saldo > 0) {
				$this->create_penarikan(array('tgl_trx' => $tgl_trx, 'idanggota' => $this->input->post('idanggota'), 'bayar_saldo' => $bayar_saldo, 'kas_id' => $this->input->post('simpan_ke') ));
			}
		}
		
		$save = array(  'TGL_BAYAR'		=>	$tgl_trx,
						'IDPINJAM'		=>	$this->input->post('idpinjam'),
						'ANGSURAN_KE'	=>	$this->input->post('ags_ke'),
						'BAYAR_SALDO'	=>	$bayar_saldo,
						'JUMLAH_BAYAR'	=>	$angsuran,
						'BASILBAYAR'	=>	$this->input->post('bayar_basil'),
						'POKOKBAYAR'	=>	$this->input->post('bayar_pokok'),
						'DENDA_RP'		=>	$biaya_reset,
						'BIAYA_KOLEKTOR'=>	$biaya_kolektor,
						'KET_BAYAR'		=>	'Angsuran',
						'KAS_ID'		=>	$this->input->post('simpan_ke'),
						'JENIS_TRANS'	=>	sukubunga('angsuran_pembiayaan'),
						'KETERANGAN'	=>	$this->input->post('keterangan'),
						'USERNAME'		=>	$this->session->userdata('wad_user'));
		$this->dbasemodel->insertData('tbl_pinjaman_d', $save);
		
		
		$ceklst			=	$this->dbasemodel->loadsql("Select * FROM checklist_teller WHERE TGL_AWAL='".date("Y-m-d")."' AND KODEPUSAT='".$this->session->userdata('wad_kodepusat')."' AND KODECABANG='".$this->session->userdata('wad_kodecabang')."'"); //AND Jenis='Angsuran'
		if($ceklst->num_rows()>0)
		{
			
			$rchek	= $ceklst->row();
			$nom 	= $rchek->NOMINAL_PINJ + $angsuran;
			$where  = "IDCEKTELLER = '". $rchek->IDCEKTELLER."' ";
			$datacheclist = array("NOMINAL_PINJ"=>$nom, "APPROVAL" => '', "STATUS" => 0);
			$this->dbasemodel->updateData("checklist_teller", $datacheclist, $where);
			
		}else{
			$datacheclist = array("TGL_AWAL"=>date("Y-m-d", strtotime($tgl_trx)),
								"NOMINAL_PINJ"=>$angsuran,
								"KODEPUSAT"=>$this->session->userdata('wad_kodepusat'),
								"KODECABANG"=>$this->session->userdata('wad_kodecabang'));//"JENIS"=>"Angsuran"
			$this->dbasemodel->insertData("checklist_teller", $datacheclist);
		}

		//kirim SMS
		$pesanConf2 = "Terimakasih anda sudah melakukan angsuran pinjaman sebesar Rp. ".toRp($angsuran)." #mudahkan belanja anda di WAD MART berbagai produk unggulan#";

		$userkey = '0b218589e1b7'; //userkey lihat di zenziva
		$passkey = 'd49631bcbf125442b0407d66'; // set passkey di zenziva
		
		$idpnjam = $this->input->post('idpinjam');

		$sql = "SELECT m_anggota.TELP as TELP from m_anggota, tbl_pinjaman_h WHERE tbl_pinjaman_h.IDPINJM_H = '$idpnjam' AND tbl_pinjaman_h.ANGGOTA_ID = m_anggota.IDANGGOTA"; //AND NAMA LIKE '%$cari%' OR NO_ANGGOTA LIKE'%$cari%'
		$cek  		= $this->dbasemodel->loadsql($sql);
		$arr = array();
		//var_dump($_POST);
		if($cek->num_rows()>0)
		{
			foreach($cek->result() as $key)
			{
				$telepon = $key->TELP;
				$url = "https://masking.zenziva.net/api/sendsms/"; //SMS API V2
				$curlHandle = curl_init();
				curl_setopt($curlHandle, CURLOPT_URL, $url);
				curl_setopt($curlHandle, CURLOPT_HEADER, 0);
				curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($curlHandle, CURLOPT_SSL_VERIFYHOST, 2);
				curl_setopt($curlHandle, CURLOPT_SSL_VERIFYPEER, 0);
				curl_setopt($curlHandle, CURLOPT_TIMEOUT,30);
				curl_setopt($curlHandle, CURLOPT_POST, 1);
				curl_setopt($curlHandle, CURLOPT_POSTFIELDS, array(
				    'userkey' => $userkey,
				    'passkey' => $passkey,
				    'nohp' => $telepon,
				    'pesan' => $pesanConf2
				));
				$results = json_decode(curl_exec($curlHandle), true);
				curl_close($curlHandle);
			}

			//$XMLdata = new SimpleXMLElement($results);
			if ($results['status'] == '1'){ 
				$status = 'Terkirim';
			}else{
				$status = 'Gagal';
			}
			$text = $results['text'];

			$saveOutbox		=	array(
								'PESAN'		=>	$pesanConf2,
							  	'KIRIM'		=>  1,
							  	'TANGGAL' 	=>	date('Y-m-d H:i:s'),
							  	'STATUS'	=>	$status,
							  	'TEXT'		=>	$text,
							  	'JENIS'		=>	'NOTIFIKASI'
						);
			$this->dbasemodel->insertData('t_outbox', $saveOutbox);
		}
			
		$this->session->set_flashdata('ses_trx_ags', '11||Transaksi pembayaran angsuran telah disimpan.');
		echo true;
	}
	
	protected function akunKasReset($kodecabang){
		
		$sql = sprintf("SELECT
		A.IDAKUN
		FROM
		jenis_kas A
		WHERE
		A.KODECABANG = '%s'
		AND A.NAMA_KAS LIKE 'kas reset'
		LIMIT 1",
		$kodecabang
		);
		$query = $this->dbasemodel->loadSql($sql);
		if($query->num_rows() > 0) {
			$row	=	$query->row();
			return $row->IDAKUN;
		}
		return 0;
	}
	
	protected function create_penarikan($data) {
		/*$idtrx					=	$this->dbasemodel->get_id('ID_TRX_SIMP', 'transaksi_simp');
		$this->db->select('ID_JNS_KAS');
	    $this->db->where('AUTO_DEBET',1);
	    $kas_id 	= $this->db->get('jenis_kas')->row()->ID_JNS_KAS;*/
		
		$this->db->select('IDAKUN');
	    $this->db->where('auto_debet',1);
	    $jenis_id = $this->db->get('jns_simpan')->row()->IDAKUN;
		
		$save['TGL_TRX']		=	$data['tgl_trx'];
		$save['ID_KAS']			=	$data['$kas_id'];
		$save['ID_TRX_SIMP']	=	$idtrx;
		$save['ID_ANGGOTA']		=	$data['idanggota'];
		$save['ID_JENIS']		=	$jenis_id;
		$save['JUMLAH']			=	$data['bayar_saldo'];
		$save['KETERANGAN']		=	'Pembayaran Angsuran';
		$save['DK']				=	'K';
		$save['AKUN']			=	'Penarikan';
		$save['UPDATE_DATA']	=	date('Y-m-d H:i:s');
		$save['USERNAME']		=	$this->session->userdata('wad_user');
		$save['KOLEKTOR']		=	0;
		$save['STATUS']			=	0;
			
		if($this->dbasemodel->insertData('transaksi_simp', $save)) {
			
			/* $ceklst			=	$this->dbasemodel->loadsql("Select * FROM checklist_teller WHERE TGL_AWAL='".date("Y-m-d")."' 
											AND Jenis='Angsuran'
											AND KODEPUSAT='".$this->session->userdata('wad_kodepusat')."'
											AND KODECABANG='".$this->session->userdata('wad_kodecabang')."'");
			if($ceklst->num_rows()>0)
			{
				
				$rchek	= $ceklst->row();
				$nom 	= $rchek->NOMINAL + $angsuran;
				$where  = "IDCEKTELLER = '". $rchek->IDCEKTELLER."' ";
				$datacheclist = array("NOMINAL"=>$nom);
				$this->dbasemodel->updateData("checklist_teller", $datacheclist, $where);
				
			}else{
				$datacheclist = array("TGL_AWAL"=>date("Y-m-d", strtotime($tgl_trx)),
									"NOMINAL"=>$angsuran,
									"KODEPUSAT"=>$this->session->userdata('wad_kodepusat'),
									"KODECABANG"=>$this->session->userdata('wad_kodecabang'),
									"JENIS"=>"Angsuran");
				$this->dbasemodel->insertData("checklist_teller", $datacheclist);
			}*/
			
			$data	=	array('TGL' 		=>	$data['tgl_trx'],
							  'KREDIT'		=>	$data['bayar_saldo'],
							  'DARI_KAS'	=>	$kas_id,
							  'ID_TRX_SIMP'	=>	$idtrx,
							  'TRANSAKSI'	=>	$jenis_id,
							  'KET'			=>	'Pembayaran Angsuran',
							  'USER'		=>	$this->session->userdata('wad_user')
						);
			//$this->dbasemodel->insertData('v_transaksi', $data);
		}
		return true;
	}
	
	protected function _jenis_kas() {
		$sql	=	sprintf("SELECT IDAKUN, NAMA_KAS
							 FROM
							 	jenis_kas
							 WHERE 
							 	TMPL_BAYAR = 'Y'
								AND KODECABANG = '%s' ",
							 $this->session->userdata('wad_kodecabang')
						);
		$query	=	$this->dbasemodel->loadsql($sql);
		
		//$result	=	$this->tree->result_tree('PARENT', 'IDAKUN', $query->result_array());
							
		return $query->result_array();
	}
	
	protected function _data_pinjaman($idpinjam) {
		
		$sql = sprintf("SELECT A.IDPINJM_H, A.TGL_PINJ, A.LAMA_ANGSURAN, A.JUMLAH, 
		A.IS_RESET, A.BUNGA, A.BIAYA_ADMIN,
		A.BIAYA_ASURANSI, A.JAMINAN_TABUNGAN, A.LUNAS,
		A.PINJ_POKOK_SISA, A.PINJ_BASIL_DASAR, A.IS_RESET,
		A.PINJ_BASIL_TOTAL, A.PINJ_BASIL_BAYAR
		FROM 
		tbl_pinjaman_h A
		WHERE 
		A.IDPINJM_H = %s ", $idpinjam );
		
		$query = $this->dbasemodel->loadSql($sql);					
		if($query->num_rows() > 0) {
			$result	=	$query->result_array();
			return $result[0];
		}
		return false;
	}
	protected function _data_angsuran($idpinjam) {
		$sql = sprintf("SELECT COUNT(IDPINJ_D) JML_AGS, SUM(JUMLAH_BAYAR) TOTAL_BAYAR FROM tbl_pinjaman_d WHERE IDPINJAM = %s ", $idpinjam);
		$query = $this->dbasemodel->loadSql($sql);
		if($query->num_rows() > 0) {
			$row = $query->row();
			return array('jml_ags' => $row->JML_AGS, 'total_bayar' => $row->TOTAL_BAYAR);
		}
		return false;
	}
	
	
	public function lunas(){
		 
		$data['opt_data_entries'] = $this->load->view('_elements/data_entries', NULL, TRUE);
		$data['table_footer']     = $this->load->view('_elements/table_footer', NULL, TRUE);
        $data['PAGE_TITLE']       = "Pelunasan Pinjaman";
        $data['page']             = "pinjaman/pinjaman_lunas";
        $this->load->view('dashboard',$data);
    }
	
	public function datalunas(){
		 
		if($this->input->post('tgl'))
		{
			$tgl = date("Y-m-d", strtotime($this->input->post('tgl')));
			$wheretrgl = "AND DATE(A.TGL_PINJ)='".$tgl."'";
		}else{
			$wheretrgl = "AND DATE(A.TGL_PINJ)='".date("Y-m-d")."'";
		}
		
		$wheretrgl = " AND 1=1 ";
		
		// $koncabang = ($this->session->userdata('wad_cabang')!="")? " AND A.KODECABANG='".$this->session->userdata('wad_cabang')."'":"";
		if($this->session->userdata("wad_level") == "admin")
		{
			$koncabang = "";
		}
		else
		{
			$koncabang = " AND A.KODECABANG='".$this->session->userdata('wad_kodecabang')."'";
		}
		$this->load->model('ModelPinjaman');
		$keyword     = null !== $this->input->post('keyword') ? $this->input->post('keyword') : "";
		$dataPerPage = $this->input->post('dataperpage');
		$page        = $this->input->post('page');
		$dataTable   = $this->ModelPinjaman->getDataTableLunas($keyword, $dataPerPage, $page,$koncabang,$wheretrgl);

        header('Content-Type: application/json');
		echo json_encode($dataTable);
		die();
	}
	
	protected function _integer($data){
		return str_replace(array(',', '.'), '', $data);
	}
	
	function pinjaman_excel()
	{
		 
		$spreadsheet = new Spreadsheet();
		$spreadsheet->getActiveSheet()->setTitle('Data Pinjaman');
		$sheet = $spreadsheet->getActiveSheet();
		
		$sheet->setCellValue('A1', 'TANGGAL PINJAM');
		$sheet->setCellValue('B1', 'NAMA');
		$sheet->setCellValue('C1', 'ALAMAT');
		$sheet->setCellValue('D1', 'JENIS');
		$sheet->setCellValue('E1', 'JUMLAH');
		$sheet->setCellValue('F1', 'BIAYA ADMIN');
		$sheet->setCellValue('G1', 'ASURANSI');
		$sheet->setCellValue('H1', 'LAMA ANGSURAN');
		$sheet->setCellValue('I1', 'BAGI HASIL');
		$sheet->setCellValue('J1', 'JUMLAH ANGSURAN');
		$sheet->setCellValue('K1', 'JUMLAH DENDA');
		$sheet->setCellValue('L1', 'JUMLAH TAGIHAN');
		$sheet->setCellValue('M1', 'SUDAH DIBAYAR');
		$sheet->setCellValue('N1', 'SISA ANGSURAN');
		$sheet->setCellValue('O1', 'SISA TAGIHAN');
		$sheet->setCellValue('P1', 'STATUS');
		
		foreach(range('A','P') as $columnID)
		{
			$sheet->getColumnDimension($columnID)->setAutoSize(true);
		}
		
		$sheet->getStyle('A1:P1')->applyFromArray(
		   array(
			  'font'  => array(
				  'bold'  =>  true
			  )
		   )
		);

		
		// $koncabang = ($this->session->userdata('wad_cabang')!="")? " AND A.KODECABANG='".$this->session->userdata('wad_cabang')."'":"";
		
		if($this->session->userdata("wad_level") == "admin")
		{
			$koncabang = "";
		}
		else
		{
			$koncabang = " AND A.KODECABANG='".$this->session->userdata('wad_kodecabang')."'";
		}
		$cek = $this->dbasemodel->loadSql("SELECT 
								A.IDPINJM_H, DATE_FORMAT(A.TGL_PINJ, '%d/%m/%Y') TGL_PINJ,
								A.LUNAS, A.BUNGA, 
								A.PINJ_RP_ANGSURAN AS JML_ANGSURAN,
								IF(ISNULL(SUM(D.DENDA_RP)), 0, SUM(D.DENDA_RP))AS  JML_DENDA,
								A.PINJ_TOTAL AS TOTAL_TAGIHAN,
								A.PINJ_DIBAYAR AS SUDAH_DIBAYAR,
								(A.LAMA_ANGSURAN - COUNT(D.IDPINJ_D)) AS SISA_ANGSURAN,
								A.PINJ_SISA AS SISA_TAGIHAN,
								A.USERNAME,
								B.NAMA NAMA_ANGGOTA, B.ALAMAT,
								C.JNS_PINJ, A.JUMLAH AS JUMLAH, A.BIAYA_ADMIN AS BIAYA_ADMIN,
								A.BIAYA_ASURANSI as BIAYA_ASURANSI, A.LAMA_ANGSURAN, 
								(A.JUMLAH/A.LAMA_ANGSURAN) as ANGSURAN_DASAR,
								A.PINJ_BASIL_DASAR AS BASIL_DASAR,
								E.KODECABANG,F.NAMA AS NAMACABANG
							 FROM
							 	tbl_pinjaman_h A
							 LEFT JOIN
							 	m_anggota B ON A.ANGGOTA_ID = B.IDANGGOTA
							 LEFT JOIN
								jns_pinjm C ON A.BARANG_ID = C.IDJNS_PINJ
							 LEFT JOIN
								tbl_pinjaman_d D ON A.IDPINJM_H = D.IDPINJAM
							 LEFT JOIN
							 	m_user E ON A.USERNAME = E.USERNAME
							 LEFT JOIN
							 	m_cabang F ON E.KODECABANG = F.KODE
							 WHERE 1=1 $koncabang AND A.ISAPPROVE = 1 
							 GROUP BY
							 	A.IDPINJM_H
							 ORDER BY
							 	DATE(A.TGL_PINJ) DESC, A.IDPINJM_H DESC");
								
		$row = 2;
		if($cek->num_rows() > 0){ $n = 1;
		
			foreach($cek->result() as $item){ 
				$sheet->setCellValue('A'.$row,$item->TGL_PINJ);
				$sheet->setCellValue('B'.$row,$item->NAMA_ANGGOTA);
				$sheet->setCellValue('C'.$row,$item->ALAMAT);
				$sheet->setCellValue('D'.$row,$item->JNS_PINJ);
				$sheet->setCellValue('E'.$row,$item->JUMLAH);
				$sheet->getStyle('E'.$row)->getNumberFormat()->setFormatCode('#,##0');
				$sheet->setCellValue('F'.$row,$item->BIAYA_ADMIN);
				$sheet->getStyle('F'.$row)->getNumberFormat()->setFormatCode('#,##0');
				$sheet->setCellValue('G'.$row,$item->BIAYA_ASURANSI);
				$sheet->getStyle('G'.$row)->getNumberFormat()->setFormatCode('#,##0');
				$sheet->setCellValue('H'.$row,$item->LAMA_ANGSURAN);
				$sheet->setCellValue('I'.$row,$item->BASIL_DASAR);
				$sheet->getStyle('I'.$row)->getNumberFormat()->setFormatCode('#,##0');
				$sheet->setCellValue('J'.$row,$item->JML_ANGSURAN);
				$sheet->getStyle('J'.$row)->getNumberFormat()->setFormatCode('#,##0');
				$sheet->setCellValue('K'.$row,$item->JML_DENDA);
				$sheet->getStyle('K'.$row)->getNumberFormat()->setFormatCode('#,##0');
				$sheet->setCellValue('L'.$row,$item->TOTAL_TAGIHAN);
				$sheet->getStyle('L'.$row)->getNumberFormat()->setFormatCode('#,##0');
				$sheet->setCellValue('M'.$row,$item->SUDAH_DIBAYAR);
				$sheet->getStyle('M'.$row)->getNumberFormat()->setFormatCode('#,##0');
				$sheet->setCellValue('N'.$row,$item->SISA_ANGSURAN);
				$sheet->getStyle('N'.$row)->getNumberFormat()->setFormatCode('#,##0');
				$sheet->setCellValue('O'.$row,$item->SISA_TAGIHAN);
				$sheet->getStyle('O'.$row)->getNumberFormat()->setFormatCode('#,##0');
				$sheet->setCellValue('P'.$row,$item->LUNAS);
				$row++;
			} 
			
		}
		
		$writer = new Xlsx($spreadsheet);
		$file = "pinjaman_".date("ymdHis").".xlsx";
		$writer->save('export/'.$file);
		redirect(base_url().'export/'.$file);
		
	}
}