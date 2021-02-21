<?php
require APPPATH.'third_party/vendor/autoload.php';    
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

defined('BASEPATH') OR exit('No direct script access allowed');

class Pinjaman extends CI_Controller {

	function __construct(){ 
        parent::__construct();
		$this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . 'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-chace');
        $this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");   
		 
		$this->load->database(); 
		$this->load->model('dbasemodel');
		$this->load->model('ModelSimpanan');
		$this->load->model(array('dbasemodel', 'ModelVTransaksi'));
		//@session_start();
		if(!is_logged_in()){
			redirect('/auth_login');	
		}
    } 
	public function index(){
		 
		$data['opt_data_entries'] = $this->load->view('_elements/data_entries', NULL, TRUE);
		$data['table_footer']     = $this->load->view('_elements/table_footer', NULL, TRUE);
        $data['PAGE_TITLE']       = "Transaksi Pinjaman/Pembiayaan";
        $data['page']             = "pinjaman/pinjaman";
        $this->load->view('dashboard',$data);
    
	} 
	public function datapinjaman(){
		 
		/*if($this->input->post('tgl'))
		{
			$tgl = date("Y-m-d", strtotime($this->input->post('tgl')));
			$wheretrgl = "AND DATE(A.TGL_PINJ)='".$tgl."'";
		}else{
			$wheretrgl = "AND DATE(A.TGL_PINJ)='".date("Y-m-d")."'";
		}*/
		$wheretrgl = "AND 1=1";
		
		if($this->session->userdata("wad_level") == "admin")
		{
			
			$koncabang = "";
		}
		else
		{
			$koncabang = " AND A.KODECABANG='".$this->session->userdata('wad_kodecabang')."'";
		}
		
		
		$this->load->model('ModelPinjaman');
		$keyword		=	null !== $this->input->post('keyword') ? $this->input->post('keyword') : "";
		$dataPerPage	=	$this->input->post('dataperpage');
		$page			=	$this->input->post('page');
		$dataTable		=	$this->ModelPinjaman->getDataTable($keyword, $dataPerPage, $page,$koncabang,$wheretrgl);

        header('Content-Type: application/json');
		echo json_encode($dataTable);
		die();
	} 
	public function get_jenispinjam() {
		
	    $id 	= $this->input->post('data');
		$sql	= sprintf("SELECT BAGIHASIL, BIAYAADMIN, REKOM_PINJ, ASURANSI FROM jns_pinjm WHERE IDJNS_PINJ = %s ", $id);
	    $query	= $this->dbasemodel->loadsql($sql)->result_array();
		$row	= $query[0];
		
		if($this->session->userdata("wad_level") == "admin")
		{
			$sql = sprintf("SELECT IDPINJM_H FROM tbl_pinjaman_h WHERE ANGGOTA_ID = '%s' AND LUNAS LIKE 'Lunas' ", $this->input->post('idanggota'));
		}
		else
		{
			$sql = sprintf("SELECT IDPINJM_H FROM tbl_pinjaman_h WHERE KODECABANG='".$this->session->userdata('wad_kodecabang')."' AND ANGGOTA_ID = '%s' AND LUNAS LIKE 'Lunas' ", $this->input->post('idanggota'));
		}
		
		$query	= $this->dbasemodel->loadsql($sql)->num_rows();
		$row['jml_pinjam']	=	$query;
	    echo json_encode($row);
	} 
	public function get_pinjaman() {
	    $id 	= $this->input->post('data');
		$sql	= sprintf("SELECT ISCREDIT FROM m_anggota WHERE IDANGGOTA = %s ", $id);
	    $query	= $this->dbasemodel->loadsql($sql);
		$row	= $query->row();
	    echo $row->ISCREDIT;
	} 
	public function get_datasaudara() {
	    $id    = $this->input->post('data');
		$sql   = sprintf("SELECT NAMA_SAUDARA,TELP_SAUDARA,HUB_SAUDARA,ALMT_SAUDARA FROM m_anggota WHERE IDANGGOTA = %s ", $id);
	    $query = $this->dbasemodel->loadsql($sql);
		$row   = $query->row();
	    echo json_encode($row);
	} 
    public function formadd(){
		
		error_reporting(-1);
		ini_set('display_errors', 1);
		 
        $data['PAGE_TITLE'] = "Tambah Pinjaman";
        $data['page']       = "pinjaman/add_pinjaman";
		
		$sql = "SELECT IDJNS_PINJ, JNS_PINJ, HARGA, IDAKUN FROM jns_pinjm ORDER BY JNS_PINJ";
		
		$data['jenis_pembiayaan'] = $this->dbasemodel->loadsql($sql);
		 
		if($this->session->userdata("wad_level") == "admin")
		{
			$sqls = "SELECT IDAKUN, NAMA_KAS FROM jenis_kas WHERE TMPL_SIMPAN = 'Y'";
		}
		else
		{
			$sqls = "SELECT IDAKUN, NAMA_KAS FROM jenis_kas WHERE TMPL_SIMPAN = 'Y' AND KODECABANG = '".$this->session->userdata('wad_kodecabang')."'";
		}
		
		$data['sqljeniskas'] = $sqls;
		$data['jenis_kas']   = $this->dbasemodel->loadsql($sqls);
		
		$sql                 = "SELECT IDANGSURAN, KETERANGAN FROM jns_angsuran WHERE AKTIF = 'Y' ORDER BY KETERANGAN ASC";
		$data['jenis_ags']   = $this->dbasemodel->loadsql($sql);

		$sql                 = "SELECT IDJAMINAN, NAMAJAMINAN FROM jns_jaminan ORDER BY NAMAJAMINAN ASC";
		$data['jaminan']     = $this->dbasemodel->loadsql($sql);
		
        $data['js_to_load']  = array();

        $this->load->view('dashboard',$data); 
    }
 
	public function pembulatan($uang){
		$ratusan = substr($uang, -2);
		$akhir = $uang + (100-$ratusan);
		return $akhir; 
	}
	public function save(){
		  
		/* [tgl_pinj] => 22/11/2020
		[id_anggota] => 2441
		[nama_penyetor] => tester anggota 1
		[alamat] => bandung
		[no_identitas] => 1231313123131231
		[KODECABANG] => 11
		[idjenis_pinjam] => 99
		[nilai] => 1,000,000
		[saldo_tabungan] => 100,000
		[topup] => 0
		[kacab] => 0
		[nominal_kacab] => 0
		[jumlah] => 1,000,000
		[lama_angsuran] => 3
		[bunga_rp_txt] => 50,000
		[bunga_persen_txt] => 5
		[biaya_adm] => 
		[biaya_adm_txt] => 20,000
		[bunga_admin_txt] => 2
		[biaya_asuransi] => NaN
		[biaya_asuransi_txt] => 20,000
		[bunga_asuransi_txt] => 2
		[id_kas] => 13
		[ket] => Keterangan Pembiayaan
		[no_jaminan] => 2423424
		[jenis_jaminan] => 8
		[nama_saudara] => Saudara Yang Dapat Dihubungi
		[hubungan_saudara] => Hubungan Saudara
		[telp_saudara] => 232323
		[alamat_saudara] => Alamat Saudara  */
	
		// echo "<pre>";
		// echo print_r($_POST);
		// echo "</pre>";
		 
		
		$topup 			= $this->_integer($this->input->post('topup'));
		$saldo_tabungan = $this->_integer($this->input->post('saldo_tabungan'));
		$basil_total    = $this->_integer($this->input->post('bunga_rp_txt'));
		$lama_angsuran  = $this->_integer($this->input->post('lama_angsuran'));
		$basil_dasar    = ROUND($basil_total / $lama_angsuran);

		if($topup > 0)
		{
		    $saldo_tabungan = $topup;
		} 
		else 
		{
		    $saldo_tabungan = 0;
		}

		if ($this->input->post('bunga_persen_txt') === null) 
		{
			// $bunga = 0.00;
			$bunga = 0;
		} 
		else 
		{
			$bunga = $this->input->post('bunga_persen_txt');
		}
		
		$idtrx = $this->dbasemodel->get_id('IDPINJM_H', 'tbl_pinjaman_h');
		 
		// $jumlah       = $this->_integer($this->input->post('jumlah'));
		// $lamaangsuran = $this->input->post('lama_angsuran');
		
		// $ang_dasar = ROUND((int)$jumlah /(int)$lamaangsuran);
		// $bas_dasar = ROUND((((int)$jumlah * $bunga) / 100) / (int)$lamaangsuran);

		// $angdasar = $this->pembulatan($ang_dasar); 
		// $basdasar = $this->pembulatan($bas_dasar);
		
		
		// echo "<pre>";
		// echo "angdasar ".print_r($ang_dasar); 
		// echo "</pre>";
		
		// echo "<pre>";
		// echo "basil ".print_r($angdasar); 
		// echo "</pre>";
		
		// $tambahangbas = (int)$angdasar+(int)$basdasar;
		// echo "<pre>";
		// echo "tambahangbas ".print_r($tambahangbas); 
		// echo "</pre>";
		// $tot_jml_ang = $tambahangbas*(int)$lamaangsuran;
			// echo "<pre>";
		// echo "tot_jml_ang ".print_r($tot_jml_ang); 
		// echo "</pre>";			
		
		$save =	array(
					  'IDPINJM_H'		 => $idtrx, 
					  'REKENING'		 => getRekpinj($this->session->userdata('wad_kodepusat'),$this->input->post('KODECABANG')),
					  'TGL_PINJ' 		 => date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('tgl_pinj')))) . ' ' . date('H:i:s'),
					  'ANGGOTA_ID'		 => $this->input->post('id_anggota'),
					  'BARANG_ID'		 => $this->input->post('idjenis_pinjam'),
					  'LAMA_ANGSURAN'	 => $this->input->post('lama_angsuran'),
					  'JUMLAH'			 => $this->_integer($this->input->post('jumlah')),
					  'BUNGA'			 => $bunga,
					  'BIAYA_ADMIN'		 => $this->_integer($this->input->post('biaya_adm_txt')),
					  'BUNGAADMIN'		 => $this->input->post('bunga_admin_txt'),
					  'BIAYA_ASURANSI'	 => $this->_integer($this->input->post('biaya_asuransi_txt')),
					  'BUNGAASURANSI'    => $this->input->post('bunga_asuransi_txt'),
					  'JAMINAN_TABUNGAN' => $saldo_tabungan,
					  'LUNAS'			 => 'Belum',
					  'DK'				 => 'K',
					  'KAS_ID'			 => $this->input->post('id_kas'),
					  'JNS_TRANS'		 => $this->input->post('idjenis_pinjam'),
					  'NAMA_SDR'		 => $this->input->post('nama_saudara'),
					  'HUB_SDR'			 => $this->input->post('hubungan_saudara'),
					  'TELP_SDR'		 => $this->input->post('telp_saudara'),
					  'ALAMAT_SDR'		 => $this->input->post('alamat_saudara'),
					  'USERNAME'		 => $this->session->userdata('wad_user'),
					  'KODEPUSAT'		 => $this->session->userdata('wad_kodepusat'), 
					  'KODECABANG'		 => $this->input->post('KODECABANG'),
					  'KETERANGAN'		 => $this->input->post('ket'), 
					  'NO_JAMINAN'		 => $this->input->post('no_jaminan'),
					  'JENIS_JAMINAN'	 => $this->input->post('jenis_jaminan'),
					  // 'PINJ_RP_ANGSURAN' => $tot_jml_ang,
					  'PINJ_BASIL_TOTAL' =>	$basil_total,
					  'PINJ_BASIL_DASAR' =>	$this->pembulatan($basil_dasar),
					  'PINJ_TOTAL'		 =>	$this->_integer($this->input->post('jumlah')) + $basil_total,
					  'PINJ_DIBAYAR'	 =>	0,
				);
		// echo "<pre>";
		// echo print_r($save); 
		// echo "</pre>";
		if($this->dbasemodel->insertData('tbl_pinjaman_h', $save)) {
			
			$insertsimp4 = array(
								'IDANGGOTA'  => $this->input->post('id_anggota'),
								'IDUSER'     => $this->session->userdata('wad_id'),
								'JUMLAH'     => $this->_integer($this->input->post('biaya_asuransi_txt')),
								'TGL'        => date("Y-m-d H:i:s"),
								'JENIS'      => "2",
								'KODEPUSAT'  => $this->session->userdata('wad_kodepusat'), 
								'KODECABANG' => $this->input->post('KODECABANG')
							); 
			$this->dbasemodel->insertData('m_asuransi', $insertsimp4);
			
			$this->session->set_flashdata('ses_trx_pinj', '11||Transaksi Pinjaman Berhasil Disimpan.');
			
		} else {
			$this->session->set_flashdata('ses_trx_pinj', '00||Transaksi Pinjaman Gagal Dilakukan.');
		}
		
		echo true;
	} 
	public function detail(){
		 
		$id  = $this->input->get('id');
		$sql = sprintf("SELECT A.IDANGGOTA, A.NAMA, A.NO_ANGGOTA, A.ALAMAT,
						A.KOTA, A.TMP_LAHIR, A.TGL_LAHIR, A.FILE_PIC,
						B.IDPINJM_H, B.TGL_PINJ, B.LAMA_ANGSURAN, B.JUMLAH, 
						B.IS_RESET, B.BUNGA, B.BIAYA_ADMIN,
						B.NO_JAMINAN, B.JENIS_JAMINAN,
						B.BIAYA_ASURANSI, B.JAMINAN_TABUNGAN, B.LUNAS,
						B.PINJ_POKOK_SISA, B.PINJ_BASIL_DASAR, 
						B.PINJ_BASIL_TOTAL, B.PINJ_BASIL_BAYAR,
						B.PINJ_DIBAYAR, B.PINJ_TOTAL,B.PINJ_RP_ANGSURAN
						FROM 
							m_anggota A
						LEFT JOIN
							tbl_pinjaman_h B ON A.IDANGGOTA = B.ANGGOTA_ID 
						WHERE 
							B.IDPINJM_H = %s ",
							$id
						);
		$data['data_source'] = $this->dbasemodel->loadSql($sql);
		$sql = sprintf("SELECT IDPINJ_D, TGL_BAYAR, ANGSURAN_KE, BAYAR_SALDO, BASILBAYAR, POKOKBAYAR,
						JUMLAH_BAYAR, DENDA_RP, TERLAMBAT, KET_BAYAR, DK, KAS_ID, 
						JENIS_TRANS, UPDATE_DATA, USERNAME, KETERANGAN,BIAYA_KOLEKTOR
						FROM 
							tbl_pinjaman_d A
						WHERE 
							A.IDPINJAM = %s ", $id );
		$data['data_angsuran'] = $this->dbasemodel->loadSql($sql);
		
		$sql = sprintf("SELECT ID, TANGGAL, JUMLAH, ANGSURAN_KE, LUNAS, JENIS
						FROM 
							tbl_reset A
						WHERE 
							A.IDPINJAMAN = %s AND JENIS = 0 ", $id );
		$data['data_reset']	= $this->dbasemodel->loadSql($sql);
		
		$sql = sprintf("SELECT
						ID, TANGGAL, JUMLAH, LUNAS, JENIS
						FROM 
							tbl_reset A
						WHERE 
							A.IDPINJAMAN = %s AND JENIS = 1 ", $id );
		$data['data_kolektor'] = $this->dbasemodel->loadSql($sql);
		
        $data['PAGE_TITLE'] = "Transaksi Pinjaman/Pembiayaan";
        $data['page']       = "pinjaman/detail";
        $data['idpinjaman'] = $id;
        $this->load->view('dashboard', $data);
	} 
	// UNTUK UMUM
	public function formAngsuran(){
		 
		$id            = $this->input->get('idpj');
		$data_angsuran = $this->_data_angsuran($id);
		$data_pinjaman = $this->_data_pinjaman($id);
		
		// $total_tagihan = $data_pinjaman['JUMLAH'] + (($data_pinjaman['BUNGA'] * $data_pinjaman['JUMLAH']) / 100);
		$total_tagihan = $data_pinjaman['PINJ_RP_ANGSURAN'] + (($data_pinjaman['BUNGA'] * $data_pinjaman['PINJ_RP_ANGSURAN']) / 100);
 
		$result['idagt']              = $this->input->get('idagt');
		$result['bunga']              = $data_pinjaman['BUNGA'];
		$result['idpinjam']           = $data_pinjaman['IDPINJM_H'];
		$result['PINJ_RP_ANGSURAN']   = $data_pinjaman['PINJ_RP_ANGSURAN'];
		$result['TOTAL_BAYAR']        = $data_angsuran['TOTAL_BAYAR'];
		// $result['sisatagihan']        = torp($data_pinjaman['PINJ_RP_ANGSURAN']-$data_angsuran['TOTAL_BAYAR']);
		$result['sisatagihan']        = torp($data_pinjaman['PINJ_SISA']);
		$result['angsuranperbulan']   = toRp($data_pinjaman['ANGSURAN_DASAR']+$data_pinjaman['PINJ_BASIL_DASAR']);
		// $result['jmlangsuran']        = $data_pinjaman['ANGSURAN_DASAR']+$data_pinjaman['PINJ_BASIL_DASAR'];
		$result['bayarpokok']         = $data_pinjaman['ANGSURAN_DASAR'];
		$result['bayarbasil']         = $data_pinjaman['PINJ_BASIL_DASAR'];
		
		$result['ags_ke']             = ($data_angsuran['jml_ags'] + 1);
		$result['sisa_ags']           = ($data_pinjaman['LAMA_ANGSURAN'] - $data_angsuran['jml_ags'] - 1);
		$result['sisa_tag']           = toRp($total_tagihan - $data_angsuran['total_bayar']);
		
		
		$result['ags_perbulan']	      = toRp($total_tagihan / $data_pinjaman['LAMA_ANGSURAN']);
		
		$result['pinj_basil_dasar']   = toRp($data_pinjaman['PINJ_BASIL_DASAR']);
		$result['pinj_pokok_sisa']    = toRp($data_pinjaman['PINJ_POKOK_SISA']);
		$result['pinj_basil_total']   = toRp($data_pinjaman['PINJ_BASIL_TOTAL']);
		$result['pinj_basil_dibayar'] = toRp($data_pinjaman['PINJ_BASIL_BAYAR']);
		
		$result['denda']              = toRp(sukubunga('denda'));
		$result['simpan_kas']         = $this->_jenis_kas();
		
		$result['biayareset']         = toRp($this->_biaya_reset($data_pinjaman['IDPINJM_H'], 0));
		$result['kolektor']           = toRp($this->_biaya_reset($data_pinjaman['IDPINJM_H'], 1));
		 
		echo json_encode($result);
	}
	protected function _data_pinjaman($idpinjam) {
		
		$sql = "SELECT A.IDPINJM_H, A.TGL_PINJ, A.LAMA_ANGSURAN, A.JUMLAH, A.PINJ_RP_ANGSURAN,A.PINJ_BASIL_DASAR,A.PINJ_SISA, A.ANGSURAN_DASAR, A.IS_RESET, A.BUNGA, A.BIAYA_ADMIN, A.BIAYA_ASURANSI, A.JAMINAN_TABUNGAN, A.LUNAS, A.PINJ_POKOK_SISA, A.PINJ_BASIL_DASAR, A.IS_RESET, A.PINJ_BASIL_TOTAL, A.PINJ_BASIL_BAYAR FROM tbl_pinjaman_h A WHERE A.IDPINJM_H = '".$idpinjam."'";
		
		$query = $this->dbasemodel->loadSql($sql);		
		
		if($query->num_rows() > 0) 
		{
			$result	= $query->result_array();
			return $result[0];
		}
		return false;
	}
	protected function _data_angsuran($idpinjam) {
		
		$sql	= "SELECT COUNT(IDPINJ_D) JML_AGS, SUM(JUMLAH_BAYAR) TOTAL_BAYAR FROM tbl_pinjaman_d WHERE IDPINJAM = '".$idpinjam."'";
		$query	= $this->dbasemodel->loadSql($sql);
		
		if($query->num_rows() > 0) 
		{
			$row = $query->row();
			return array('jml_ags' => $row->JML_AGS, 'total_bayar' => $row->TOTAL_BAYAR);
		}
		return false;
	}
	protected function _biaya_reset($idpinjam, $jenis) {
		
		$sql = sprintf("SELECT (SUM(JUMLAH) - SUM(DIBAYAR)) TOTAL_BIAYA FROM tbl_reset WHERE JENIS = %s AND LUNAS = 0 AND IDPINJAMAN = %s ", $jenis, $idpinjam);
		$query	=	$this->dbasemodel->loadsql($sql);
		if($query->num_rows() > 0) {
			$row	=	$query->row();
			return $row->TOTAL_BIAYA;
		}
		return 0;
	} 
	public function save_angsuran(){
		
		/* error_reporting(1);
		[chk_bayar_saldo] => false
		[tgl_trx] => 10/10/2020
		[bayar_saldo] => 0
		[jml_angsuran] => 1240440
		[bayar_pokok] => 333400
		[bayar_basil] => 33400
		[biaya_reset] => 110040
		[biaya_kolektor] => 30000
		[simpan_ke] => 13
		[keterangan] => Keterangan 
		[idanggota] => 1
		[idpinjam] => 1
		[ags_ke] => 1
		echo "<pre>";
		echo print_r($_POST);
		echo "</pre>"; */
		$angsuran		= $this->input->post('jml_angsuran');
		$bayar_saldo	= 0;
		$biaya_reset	= $this->input->post('biaya_reset') > 0 ? $this->input->post('biaya_reset') : 0;
		$biaya_kolektor	= $this->input->post('biaya_kolektor') > 0 ? $this->input->post('biaya_kolektor') : 0;
		
		$tgl_trx		= date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('tgl_trx')))) . date(' H:i:s');
		
		if($this->input->post('chk_bayar_saldo') == true) 
		{
			$bayar_saldo = $this->input->post('bayar_saldo');
			$bayar_saldo = ($bayar_saldo > $angsuran) ? $angsuran : $bayar_saldo;
			
			if($bayar_saldo > 0) 
			{
				$this->create_penarikan(array('tgl_trx' => $tgl_trx, 'idanggota' => $this->input->post('idanggota'), 'bayar_saldo' => $bayar_saldo, 'kas_id' => $this->input->post('simpan_ke'), 'idpinjam' => $this->input->post('idpinjam') ));
			}
		} 
		
		$this->db->select('*');
		$this->db->from('m_anggota');
		$this->db->where('IDANGGOTA',$this->input->post('idanggota'));
		$getanggota = $this->db->get()->row();
		$KODECABANG = $getanggota->KODECABANG;
		
		$save = array(  'TGL_BAYAR'		=> $tgl_trx,
						'IDPINJAM'		=> $this->input->post('idpinjam'),
						'ANGSURAN_KE'	=> $this->input->post('ags_ke'),
						'BAYAR_SALDO'	=> $bayar_saldo,
						'JUMLAH_BAYAR'	=> $angsuran,
						'BASILBAYAR'	=> $this->_integer($this->input->post('bayar_basil')),
						'POKOKBAYAR'	=> $this->_integer($this->input->post('bayar_pokok')), 
						'DENDA_RP'		=> $this->_integer($biaya_reset),
						'BIAYA_KOLEKTOR'=> $this->_integer($biaya_kolektor),  
						'KET_BAYAR'		=> 'Angsuran',
						'KAS_ID'		=> $this->input->post('simpan_ke'),
						'JENIS_TRANS'	=> sukubunga('angsuran_pembiayaan'),
						// 'KODECABANG'	=> $this->session->userdata('wad_kodecabang'),
						'KODECABANG'	=> $KODECABANG,
						'KETERANGAN'	=> $this->input->post('keterangan'),
						'USERNAME'		=> $this->session->userdata('wad_user'));
						
		$this->dbasemodel->insertData('tbl_pinjaman_d', $save);

		// $ceklst = $this->dbasemodel->loadsql("Select * FROM checklist_teller WHERE TGL_AWAL='".date("Y-m-d")."' AND KODEPUSAT='".$this->session->userdata('wad_kodepusat')."' AND KODECABANG='".$KODECABANG."'"); //AND Jenis='Angsuran' $this->session->userdata('wad_kodecabang')
		$ceklst = $this->dbasemodel->loadsql("Select * FROM checklist_teller WHERE TGL_AWAL='".date("Y-m-d")."' AND KODECABANG='".$KODECABANG."'"); //AND Jenis='Angsuran' $this->session->userdata('wad_kodecabang')
		
		if($ceklst->num_rows()>0)
		{
			
			$rchek	= $ceklst->row();
			$nom 	= $rchek->NOMINAL_PINJ + $angsuran;
			$where  = "IDCEKTELLER = '". $rchek->IDCEKTELLER."' ";
			$datacheclist = array("NOMINAL_PINJ"=>$nom, "APPROVAL" => '', "STATUS" => 0);
			$this->dbasemodel->updateData("checklist_teller", $datacheclist, $where);
			
		}
		else
		{
			$datacheclist = array("TGL_AWAL"   => date("Y-m-d", strtotime($tgl_trx)),
								"NOMINAL_PINJ" => $angsuran,
								"KODEPUSAT"    => $this->session->userdata('wad_kodepusat'),
								"KODECABANG"   => $KODECABANG);//"JENIS"=>"Angsuran"
								
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

			$saveOutbox = array(
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
		
		$sql = sprintf("SELECT A.IDAKUN FROM jenis_kas A WHERE A.KODECABANG = '%s' AND A.NAMA_KAS LIKE 'kas reset' LIMIT 1", $kodecabang );
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
		$idtrx = $this->dbasemodel->get_id('ID_TRX_SIMP', 'transaksi_simp');
		$this->db->select('IDAKUN');
	    $this->db->where('auto_debet',1);
	    $jenis_id = $this->db->get('jns_simpan')->row()->IDAKUN;
		
		$this->db->select('*');
		$this->db->from('jenis_kas');
	    $this->db->where('IDAKUN',$data['kas_id']);
	    $getjeniskas = $this->db->get()->row();
		$ID_JNS_KAS = $getjeniskas->ID_JNS_KAS;
		$IDAKUN = $getjeniskas->IDAKUN;
		
		$save['ID_TRX_SIMP'] = $idtrx;
		$save['TGL_TRX']     = $data['tgl_trx'];
		$save['ID_ANGGOTA']  = $data['idanggota'];
		$save['ID_JENIS']    = $jenis_id;
		$save['JUMLAH']      = $data['bayar_saldo'];
		$save['KETERANGAN']  = 'Pembayaran Angsuran';
		$save['AKUN']        = 'Penarikan';
		$save['DK']          = 'K';
		$save['ID_KAS']      = $ID_JNS_KAS;
		$save['ID_KASAKUN']  = $IDAKUN;
		$save['UPDATE_DATA'] = date('Y-m-d H:i:s');
		$save['USERNAME']    = $this->session->userdata('wad_user');
		$save['KOLEKTOR']    = 0;
		$save['STATUS']      = 0;
			
		if($this->dbasemodel->insertData('transaksi_simp', $save)) {
			
			$this->ModelSimpanan->updateSaldoAnggota('kurangi', $data['bayar_saldo'], $jenis_id, $data['idanggota']);
			
			$this->db->select('*');
			$this->db->from('m_anggota');
			$this->db->where('IDANGGOTA',$data['idanggota']);
			$getanggota = $this->db->get()->row();
			$KODECABANG = $getanggota->KODECABANG;
			
			/* $hasil = $key->JUMLAH_BAYAR-$key->BASILBAYAR;
			$datatransaksi = array('tgl'        => $key->TGL_BAYAR, 
										   'jumlah'     => $hasil, 
										   'keterangan' => 'Angsuran ke '.$key->ANGSURAN_KE.' '. $key->JENIS_TRANSAKSI .', No Rek : '. $key->REKENING . '('. $key->NAMA_AGT .')', 
										   'user'       => $key->USERNAME,
										   'kodecabang' => $key->KODECABANG,
										   'idkasakun'  => $key->KAS_ID,
										   'ket_dt'     => 'angsuran '. $key->JENIS_TRANSAKSI);
										    
			$this->ModelVTransaksi->insertVtransaksi($key->IDPINJ_D, $datatransaksi, 'AR', $key->KAS_ID, $key->JENIS_TRANS, 'PINJ');			 */				   
		
			$datatransaksi	= array('tgl' => $data['tgl_trx'], 
									'jumlah' => $data['bayar_saldo'], 
									'kodecabang' => $KODECABANG,  
									'idkasakun' => $IDAKUN, 
									'keterangan' => 'Pembayaran Angsuran',
									'ket_dt'     => 'angsuran bayar saldo');
			
			$this->ModelVTransaksi->insertVtransaksi($idtrx, $datatransaksi, 'PT', $jenis_id, $IDAKUN, 'SIMP');			
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
			
			$data = array('TGL' 		=>	$data['tgl_trx'],
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
		
		if($this->session->userdata("wad_level") == "admin")
		{
			$sql = sprintf("SELECT IDAKUN, NAMA_KAS
			FROM
			jenis_kas
			WHERE 
			TMPL_BAYAR = 'Y'"
			);
		}
		else
		{
			$sql = sprintf("SELECT IDAKUN, NAMA_KAS
			FROM
			jenis_kas
			WHERE 
			TMPL_BAYAR = 'Y'
			AND KODECABANG = '%s'",
			$this->session->userdata('wad_kodecabang')
			);
		}
		 
		$query = $this->dbasemodel->loadsql($sql);
		
		//$result	=	$this->tree->result_tree('PARENT', 'IDAKUN', $query->result_array());
							
		return $query->result_array();
		
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
		
		if($this->session->userdata("wad_level") == "admin")
		{
			$koncabang = ""; 
		}
		else
		{
			$koncabang = " AND A.KODECABANG='".$this->session->userdata('wad_cabang')."'";
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
	function pinjaman_excel() {
 
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
								jns_pinjm C ON A.BARANG_ID = C.IDAKUN
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
	public function importpinjaman(){
		    
		$this->load->library('upload');
		$data['judul_browser'] = 'Import Data';
		$data['judul_utama'] = 'Import Data';
		$data['judul_sub'] = 'Anggota <a href="'.site_url('pinjaman-data').'" class="btn btn-sm btn-success">Kembali</a>';

		$this->load->helper(array('form'));

		
		if($this->input->post('submit')) {
			
			$config['upload_path']   = FCPATH . 'uploads/temp/';
			$config['allowed_types'] = 'xls|xlsx'; 
			$this->upload->initialize($config);

			if ( ! $this->upload->do_upload('import_pinjaman')) 
			{ 
				$data['error'] = $this->upload->display_errors(); 
			} 
			else 
			{
				// ok uploaded
				$file                = $this->upload->data();
				$data['file']        = $file; 
				$data['lokasi_file'] = $file['full_path'];

				$this->load->library('excel');

				// baca excel
				$objPHPExcel = PHPExcel_IOFactory::load($file['full_path']);
				$no_sheet    = 1;
				$header      = array();
				$data_list_x = array();
				$data_list   = array();
				foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
					
					if($no_sheet == 1) 
					{   
						$no_sheet++;
						$worksheetTitle = $worksheet->getTitle();
						$highestRow = $worksheet->getHighestRow();  
						$highestColumn = $worksheet->getHighestColumn();  
						$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);

						$nrColumns = ord($highestColumn) - 64;
					  
						$data_jml_arr = array(); 
						for ($row = 1; $row <= $highestRow; ++$row) {
				 
							for ($col = 0; $col < $highestColumnIndex; ++$col) {
								$cell = $worksheet->getCellByColumnAndRow($col, $row);
								$val = $cell->getValue();
								$kolom = PHPExcel_Cell::stringFromColumnIndex($col);
								if($row === 1) { 
									$header[$kolom] = $val;
								} else { 
									$data_list_x[$row][$kolom] = $val;   
								}
							}
						}
					}
				}

				$no = 1;
				foreach ($data_list_x as $data_kolom) {
					if((@$data_kolom['A'] == NULL || trim(@$data_kolom['A'] == '')) ) { continue; }
					foreach ($data_kolom as $kolom => $val) {
						if(in_array($kolom, array('E', 'K', 'L')) ) {
							
							$val = ltrim($val, "'");
						}
						$data_list[$no][$kolom] = $val;
					}
					$no++;
				} 
				$data['header'] = $header;
				$data['values'] = $data_list;
				  
			}
		}
  
		$data['opt_data_entries'] = $this->load->view('_elements/data_entries', NULL, TRUE);
		$data['table_footer']     = $this->load->view('_elements/table_footer', NULL, TRUE);
        $data['PAGE_TITLE']       = "Import Data Pinjaman Anggota";
		$data['page']             = "pinjaman/importpinjaman";
		$this->load->view('dashboard',$data); 
		 
    }
	public function prosesimport() {
        
		require_once APPPATH."/libraries/phpexcel/PHPExcel.php"; 
		 
		$config['upload_path']   = FCPATH . 'uploads/temp/';
        $config['allowed_types'] = 'xlsx|xls|csv';
        $config['max_size']      = '10000';
        $config['encrypt_name']  = true;

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload()) 
		{  
            $this->session->set_flashdata('notif', '<div class="alert alert-danger"><b>PROSES IMPORT GAGAL!</b> '.$this->upload->display_errors().'</div>'); 
            redirect('pinjaman/importpinjaman'); 
        } 
		else 
		{

            $data_upload = $this->upload->data();

            $excelreader = new PHPExcel_Reader_Excel2007();
            $loadexcel   = $excelreader->load('uploads/temp/'.$data_upload['file_name']); // Load file yang telah diupload ke folder excel
            $sheet       = $loadexcel->getActiveSheet()->toArray(null, true, true ,true);

            $data = array();

            $numrow = 1;
			
            foreach($sheet as $row){
			 
				if($numrow > 1){
					  
					$getnoanggota = explode(".",$row['A']); // NO ANGGOTA
					$KODEPUSAT    = $getnoanggota[0]; // KOPUSAT
					$KODECABANG   = $getnoanggota[1]; // KODECABANG
					$NO_ANGGOTA   = $getnoanggota[2]; // ANGGOTA
					
					$NOPINJAMAN        = $row['B'] ; // NO PINJAMAN
					
					$gettgldaftar      = trim(strip_tags($row['C'])) ; // TANGGAL PINJAM
					$pec               = explode("/",$gettgldaftar);
					
					$settgldaftar      = date_create($pec[2]."-".$pec[1]."-".$pec[0]);
					$TGL_PINJAM        = date_format($settgldaftar,"Y-m-d");
					 
					$NAMA              = trim(strip_tags(addslashes($row['D']))); // NAMA ANGGOTA
					$JENISPEMBIAYAAN   = trim(strip_tags(addslashes($row['E']))); // JENIS PEMBIAYAAN
					$NILAI_PEMBIAYAAN  = $this->_integer(trim(strip_tags(addslashes($row['F'])))); // NILAI PEMBIAYAAN
					$SALDO             = trim(strip_tags(addslashes($row['G']))); // SALDO
					$NOMINAL_KEBIJAKAN = trim(strip_tags(addslashes($row['H']))); // NOMINAL KEBIJAKAN
					$TOTAL_PEMBIAYAAN  = $this->_integer(trim(strip_tags(addslashes($row['I'])))); // TOTAL PEMBIAYAAN
					
					$getjangka = explode(" ",trim(strip_tags(addslashes($row['J']))));
					$LAMA_ANGSURAN     = $getjangka[0]; // LAMA ANGSURAN
					  
					$BAGIHASIL         = $this->_integer(trim(strip_tags(addslashes($row['K'])))); // BAGI HASIL
					$PERSENBAGIHASIL   = trim(strip_tags(addslashes(str_replace("%","",$row['L'])))); // PERSEN BAGI HASIL
					$BIAYAADMIN        = $this->_integer(trim(strip_tags(addslashes($row['M'])))); // BIAYA ADMIN
					$PERSENADMIN       = trim(strip_tags(addslashes(str_replace("%","",$row['N'])))); // PERSEN ADMIN
					$BIAAYAASURANSI    = $this->_integer(trim(strip_tags(addslashes($row['O'])))); // BIAYA ASURANSI
					$PERSENASURANSI    = trim(strip_tags(addslashes(str_replace("%","",$row['P'])))); // PERSEN ASURANSI 
					$AMBILDARIKAS      = trim(strip_tags(addslashes($row['Q']))); // AMBIL DARI KAS
					$KETERANGAN        = trim(strip_tags(addslashes($row['R']))); // KETERANGAN
					$JENISJAMINAN      = trim(strip_tags(addslashes($row['S']))); // JENIS JAMINAN
					$NOJAMINAN         = trim(strip_tags(addslashes($row['T']))); // NO JAMINAN
					
					
					$this->db->select('m_anggota.IDANGGOTA, m_anggota.KODEPUSAT, m_anggota.KODECABANG AS KODECABANGANGGOTA, m_anggota.NAMA,
					m_anggota.NO_ANGGOTA,m_cabang.NAMA AS NAMACABANG, m_cabang.KODE, m_cabang.KODECABANG, m_anggota.NAMA_SAUDARA,m_anggota.HUB_SAUDARA,m_anggota.TELP_SAUDARA,m_anggota.ALMT_SAUDARA');
					$this->db->from('m_anggota'); 
					$this->db->join('m_cabang','m_anggota.KODECABANG = m_cabang.KODE');
					$this->db->where('m_anggota.NO_ANGGOTA',$NO_ANGGOTA); 
					$this->db->where('m_cabang.KODECABANG',$KODECABANG); 
					$getanggota = $this->db->get()->row();
		
					$idtrx = $this->dbasemodel->get_id('IDPINJM_H', 'tbl_pinjaman_h');
					
					$basil_dasar = ROUND($BAGIHASIL / $LAMA_ANGSURAN);
					
					$save =	array(
						  'IDPINJM_H'		 => $idtrx, 
						  'REKENING'		 => getRekpinj($KODEPUSAT,$getanggota->KODECABANGANGGOTA),
						  'TGL_PINJ' 		 => $TGL_PINJAM . ' 00:00:00' ,
						  'ANGGOTA_ID'		 => $NO_ANGGOTA,
						  'BARANG_ID'		 => $JENISPEMBIAYAAN,
						  'LAMA_ANGSURAN'	 => $LAMA_ANGSURAN,
						  'JUMLAH'			 => $TOTAL_PEMBIAYAAN,
						  'BUNGA'			 => $PERSENBAGIHASIL,
						  'BIAYA_ADMIN'		 => $BIAYAADMIN,
						  'BUNGAADMIN'		 => $PERSENADMIN,
						  'BIAYA_ASURANSI'	 => $BIAAYAASURANSI,
						  'BUNGAASURANSI'    => $PERSENASURANSI,
						  'JAMINAN_TABUNGAN' => $SALDO,
						  'LUNAS'			 => 'Belum',
						  'DK'				 => 'K',
						  'KAS_ID'			 => $AMBILDARIKAS,
						  'JNS_TRANS'		 => $JENISPEMBIAYAAN,
						  'NAMA_SDR'		 => $getanggota->NAMA_SAUDARA,
						  'HUB_SDR'			 => $getanggota->HUB_SAUDARA,
						  'TELP_SDR'		 => $getanggota->TELP_SAUDARA,
						  'ALAMAT_SDR'		 => $getanggota->ALMT_SAUDARA,
						  'USERNAME'		 => $this->session->userdata('wad_user'),
						  'KODEPUSAT'		 => $this->session->userdata('wad_kodepusat'), 
						  'KODECABANG'		 => $getanggota->KODECABANGANGGOTA, 
						  'KETERANGAN'		 => $KETERANGAN, 
						  'NO_JAMINAN'		 => $NOJAMINAN,
						  'JENIS_JAMINAN'	 => $JENISJAMINAN, 
						  'PINJ_BASIL_TOTAL' =>	$BAGIHASIL,
						  'PINJ_BASIL_DASAR' =>	$this->pembulatan($basil_dasar),
						  'PINJ_TOTAL'		 =>	(int)$TOTAL_PEMBIAYAAN + (int)$BAGIHASIL,
						  'PINJ_DIBAYAR'	 =>	0,
						  'NOPINJAMAN'	     =>	$NOPINJAMAN,
						  'ISAPPROVE'	     =>	'1',
						  'APPROVAL'	     =>	$this->session->userdata('wad_user'),
					);
					
					$this->dbasemodel->insertData('tbl_pinjaman_h', $save);
					
					$insertsimp4 = array(
								'IDANGGOTA'  => $NO_ANGGOTA,
								'IDUSER'     => $this->session->userdata('wad_id'),
								'JUMLAH'     => $BIAAYAASURANSI,
								'TGL'        => date("Y-m-d H:i:s"),
								'JENIS'      => "2",
								'KODEPUSAT'  => $this->session->userdata('wad_kodepusat'), 
								'KODECABANG' => $getanggota->KODECABANGANGGOTA
							); 
					$this->dbasemodel->insertData('m_asuransi', $insertsimp4);
					  
				}
				$numrow++; 
			} 
			  
            $this->session->set_flashdata('notif', '<div class="alert alert-success"><b>PROSES IMPORT BERHASIL!</b> Data berhasil diimport!</div>');
            
            redirect('pinjaman/importpinjaman');

        }
    }
}