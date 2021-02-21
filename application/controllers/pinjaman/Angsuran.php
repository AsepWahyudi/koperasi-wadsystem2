<?php
require APPPATH.'third_party/vendor/autoload.php';    
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

defined('BASEPATH') OR exit('No direct script access allowed');

class Angsuran extends CI_Controller {

	function __construct(){ 
        parent::__construct();
		$this->load->helper(array('app', 'form','url', 'xml','text_helper','date','inflector','app'));
		$this->load->database();
		$this->load->library(array('Pagination','user_agent','session','form_validation','session', 'table', 'tree'));
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
        $data['PAGE_TITLE']       = "Transaksi Pembayaran Angsuran";
        $data['page']             = "pinjaman/angsuran";
        $this->load->view('dashboard',$data);
    }
	public function dataangsuran(){
		 
		if($this->input->post('tgl'))
		{
			$tgl = date("Y-m-d", strtotime($this->input->post('tgl')));
			$wheretrgl = "AND DATE(A.TGL_PINJ)='".$tgl."'";
		}else{
			//$wheretrgl = "AND DATE(A.TGL_PINJ)='".date("Y-m-d")."'";
			$wheretrgl = "AND 0=0 "; 
		}
		
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
		$keyword		=	null !== $this->input->post('keyword') ? $this->input->post('keyword') : "";
		$dataPerPage	=	$this->input->post('dataperpage');
		$page			=	$this->input->post('page');
		$dataTable		=	$this->ModelPinjaman->getDataTableAngsuran($keyword, $dataPerPage, $page,$koncabang, $wheretrgl);
		
        header('Content-Type: application/json');
		echo json_encode($dataTable);
		die();
	}
	
	public function data(){
		 
		$data['opt_data_entries'] = $this->load->view('_elements/data_entries', NULL, TRUE);
		$data['table_footer']     = $this->load->view('_elements/table_footer', NULL, TRUE);
        $data['PAGE_TITLE']       = "Rekap Angsuran Pinjaman";
        $data['page']             = "pinjaman/angsuran_rekap";
        $this->load->view('dashboard',$data);
    }
	public function datarekapangsuran(){
		 
		if($this->input->post('tgl'))
		{
			$tgl = date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('tgl'))));
			$wheretrgl = "AND DATE(D.TGL_BAYAR)='".$tgl."'";
		}else{
			$wheretrgl = "AND DATE(D.TGL_BAYAR)='".date("Y-m-d")."'";
		}
		
		// $koncabang = ($this->session->userdata('wad_cabang')!="")? " AND A.KODECABANG='".$this->session->userdata('wad_cabang')."'":"";
		/* if($this->session->userdata("wad_level") == "admin")
		{
			$koncabang = ""; 
		}
		else
		{
			$koncabang = " AND A.KODECABANG='".$this->session->userdata('wad_kodecabang')."'";
		} */
		if($this->session->userdata("wad_level") == "admin")
		{
			$koncabang = "";
			
			if($this->session->userdata('wad_cabang') === true OR $this->session->userdata('wad_cabang') !="" ){
				
				$koncabang =" AND A.KODECABANG = '" .$this->session->userdata('wad_cabang'). "' ";
				
			}else{
				$koncabang = "";
			}
		}
		else
		{
			 
			if($this->session->userdata('wad_cabang') === true OR $this->session->userdata('wad_cabang') !="" ){
				
				$koncabang =" AND A.KODECABANG = '" .$this->session->userdata('wad_cabang'). "' ";
				
			}else{
				
				$koncabang =" AND A.KODECABANG = '" .$this->session->userdata('wad_kodecabang'). "' ";
			}
		} 
		
		$this->load->model('ModelPinjaman');
		$keyword		=	null !== $this->input->post('keyword') ? $this->input->post('keyword') : "";
		$dataPerPage	=	$this->input->post('dataperpage');
		$page			=	$this->input->post('page');
		$dataTable		=	$this->ModelPinjaman->getDataTableRekapAngsuran($keyword, $dataPerPage, $page,$koncabang,$wheretrgl);

        header('Content-Type: application/json');
		echo json_encode($dataTable);
		die();
	}
	public function importangsuran(){
		    
		$this->load->library('upload');
		$data['judul_browser'] = 'Import Data';
		$data['judul_utama'] = 'Import Data';
		$data['judul_sub'] = 'Anggota <a href="'.site_url('bayar-angsuran').'" class="btn btn-sm btn-success">Kembali</a>';
 
		$data['opt_data_entries'] = $this->load->view('_elements/data_entries', NULL, TRUE);
		$data['table_footer']     = $this->load->view('_elements/table_footer', NULL, TRUE);
        $data['PAGE_TITLE']       = "Import Data Pinjaman Anggota";
		$data['page']             = "pinjaman/importangsuran";
		$this->load->view('dashboard',$data); 
		 
    }
	public function prosesimport() {
        
		ini_set('max_execution_time', 123456);
		ini_set("memory_limit","1256M");
			 
		require_once APPPATH."/libraries/phpexcel/PHPExcel.php"; 
		 
		$config['upload_path']   = FCPATH . 'uploads/temp/';
        $config['allowed_types'] = 'xlsx|xls|csv';
        $config['max_size']      = '10000';
        $config['encrypt_name']  = true;

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload()) 
		{  
            $this->session->set_flashdata('notif', '<div class="alert alert-danger"><b>PROSES IMPORT GAGAL!</b> '.$this->upload->display_errors().'</div>'); 
            redirect('angsuran/importangsuran'); 
        } 
		else 
		{

            $data_upload = $this->upload->data();

            $excelreader = new PHPExcel_Reader_Excel2007();
            $loadexcel   = $excelreader->load('uploads/temp/'.$data_upload['file_name']);  
            $sheet       = $loadexcel->getActiveSheet()->toArray(null, true, true ,true);

            $data = array();

            $numrow = 1;
			
            foreach($sheet as $row){
			 
				if($numrow > 1){ 

					/* [NOPINJAMAN] => 002.121.0000262
					[TGL_ANGSURAN] => 2019-08-26  16:13:28
					[STATUSANGSURAN] => Belum Lunas
					[ANGSURANKE] => 1
					[SISAANGSURAN] => -667
					[SISATAGIHAN] => 746000
					[ANGSURANPERBULAN] => 373333
					[BAYARDENGANSALDO] => BAYAR DENGAN SALDO
					[JUMLAHBAYAR] => 374000
					[BAYARPOKOK] => 334000
					[BAYARBAGIHASIL] => 40000
					[BIAYARESET] => 0
					[BIAYAKOLEKTOR] => 0
					[KETERANGAN] => KETERANGAN
					[SIMPANKAS] => 29 */
			
					$getnoanggota = explode(".",$row['A']); // NO ANGSURAN
					$KODEPUSAT    = $getnoanggota[0]; // KOPUSAT
					$KODECABANGS  = $getnoanggota[1]; // KODECABANG
					$NO_ANGGOTA   = $getnoanggota[2]; // ANGGOTA
					
					$NOPINJAMAN   = $row['B'] ; // NO PINJAMAN
					
					$gettgldaftar = trim(strip_tags($row['C'])) ; // TANGGAL ANGSURAN
					$pec          = explode("/",$gettgldaftar);
					
					$settgldaftar = date_create($pec[2]."-".$pec[1]."-".$pec[0]);
					$TGL_ANGSURAN = date_format($settgldaftar,"Y-m-d"). date(' H:i:s');
					 
					$STATUSANGSURAN = trim(strip_tags(addslashes($row['D']))); // STATUS ANGSURAN 
					$ANGSURANKE     = trim(strip_tags(addslashes($row['E']))); // ANGSURAN KE
					
					$SISAANGSURAN      = $this->_integer(trim(strip_tags(addslashes($row['F'])))); // SISA ANGSURAN
					$SISATAGIHAN       = $this->_integer(trim(strip_tags(addslashes($row['G'])))); // SISA TAGIHAN
					$ANGSURANPERBULAN  = $this->_integer(trim(strip_tags(addslashes($row['H'])))); // ANGSURAN PERBULAN
					$BAYARSALDO        = $this->_integer(trim(strip_tags(addslashes($row['I'])))); // BAYAR DENGAN SALDO
					 
					$JMLBAYAR       = $this->_integer(trim(strip_tags(addslashes($row['J'])))); // JUMLAH BAYAR
					$BAYARPOKOK     = $this->_integer(trim(strip_tags(addslashes($row['K'])))); // BAYAR POKOK
					$BAYARBAGIHASIL = $this->_integer(trim(strip_tags(addslashes($row['L'])))); // BAYAR BAGI HASIL 
					$BIAYARESET     = $this->_integer(trim(strip_tags(addslashes($row['M'])))); // BIAYA RESET 
					$BIAYAKOLEKTOR  = $this->_integer(trim(strip_tags(addslashes($row['N'])))); // BIAYA KOLEKTOR 
					$KETERANGAN     = trim(strip_tags(addslashes($row['O']))); // KETERANGAN
					$SIMPANKEKAS    = trim(strip_tags(addslashes($row['P']))); // SIMPAN KE KAS
					
					
					/* $angsuran["NO_ANGGOTA"]       = $getnoanggota;
					$angsuran["NOPINJAMAN"]       = $NOPINJAMAN;
					$angsuran["TGL_ANGSURAN"]     = $TGL_ANGSURAN;
					$angsuran["STATUSANGSURAN"]   = $STATUSANGSURAN;
					$angsuran["ANGSURANKE"]       = $ANGSURANKE;
					$angsuran["SISAANGSURAN"]     = $SISAANGSURAN;
					$angsuran["SISATAGIHAN"]      = $SISATAGIHAN;
					$angsuran["ANGSURANPERBULAN"] = $ANGSURANPERBULAN;
					$angsuran["BAYARDENGANSALDO"] = $BAYARSALDO;
					$angsuran["JUMLAHBAYAR"]      = $JMLBAYAR;
					$angsuran["BAYARPOKOK"]       = $BAYARPOKOK;
					$angsuran["BAYARBAGIHASIL"]   = $BAYARBAGIHASIL;
					$angsuran["BIAYARESET"]       = $BIAYARESET;
					$angsuran["BIAYAKOLEKTOR"]    = $BIAYAKOLEKTOR;
					$angsuran["KETERANGAN"]       = $KETERANGAN;
					$angsuran["SIMPANKAS"]        = $SIMPANKEKAS; */
					
					// $getdataangsuran[] = $angsuran; 
					
					$this->db->select('*');
					$this->db->from('tbl_pinjaman_h');
					$this->db->where('NOPINJAMAN',$NOPINJAMAN);
					$getpinjamans = $this->db->get();
					
					
					$IDPINJM_H = "";
					if($getpinjamans->num_rows() > 0){
						
						$this->db->select('*');
						$this->db->from('tbl_pinjaman_h');
						$this->db->where('NOPINJAMAN',$NOPINJAMAN);
						$getidpinjam = $this->db->get()->row();
						
						$IDPINJM_H = $getidpinjam->IDPINJM_H;
						
						if($BAYARSALDO == "BAYAR DENGAN SALDO") 
						{
							$bayar_saldo = $JMLBAYAR;
							
							if($bayar_saldo > 0) 
							{
								$this->create_penarikan(array('tgl_trx' => $TGL_ANGSURAN, 'idanggota' => $NO_ANGGOTA, 'bayar_saldo' => $bayar_saldo, 'kas_id' => $SIMPANKEKAS, 'idpinjam' => $IDPINJM_H ));
							}
						} 
						
						$this->db->select('*');
						$this->db->from('m_anggota');
						$this->db->where('IDANGGOTA',$NO_ANGGOTA);
						$getanggota = $this->db->get()->row();
						$IDANGGOTA = $getanggota->IDANGGOTA;
						$KODECABANG = $getanggota->KODECABANG;
						
						$save = array( 'TGL_BAYAR'     => $TGL_ANGSURAN,
									   'IDPINJAM'      => $IDPINJM_H,
									   'ANGSURAN_KE'   => $ANGSURANKE,
									   'BAYAR_SALDO'   => $bayar_saldo,
									   'JUMLAH_BAYAR'  => $JMLBAYAR,
									   'BASILBAYAR'	   => $BAYARBAGIHASIL,
									   'POKOKBAYAR'	   => $BAYARPOKOK, 
									   'DENDA_RP'      => $BIAYARESET,
									   'BIAYA_KOLEKTOR'=> $BIAYAKOLEKTOR,  
									   'KET_BAYAR'     => 'Angsuran',
									   'KAS_ID'        => $SIMPANKEKAS,
									   'JENIS_TRANS'   => sukubunga('angsuran_pembiayaan'), 
									   'KODECABANG'    => $KODECABANG,
									   'KETERANGAN'	   => $KETERANGAN,
									   'USERNAME'      => $this->session->userdata('wad_user'));
							
						$this->dbasemodel->insertData('tbl_pinjaman_d', $save);
						
						/* $sql = "SELECT A.*, B.KAS_ID, B.JUMLAH, B.PINJ_RP_ANGSURAN, B.PINJ_TOTAL, B.ANGGOTA_ID, B.PINJ_SISA, 
						B.LUNAS, B.REKENING, B.KODECABANG, C.NAMA NAMA_AGT, D.JENIS_TRANSAKSI
						FROM tbl_pinjaman_d A 
						LEFT JOIN tbl_pinjaman_h B ON A.IDPINJAM = B.IDPINJM_H
						LEFT JOIN m_anggota C ON B.ANGGOTA_ID = C.IDANGGOTA
						LEFT JOIN jns_akun D ON B.JNS_TRANS = D.IDAKUN
						WHERE B.NOPINJAMAN = '".$NOPINJAMAN."'"; */
						
						$sql = "SELECT A.*, B.KAS_ID, B.JUMLAH, B.PINJ_RP_ANGSURAN, B.PINJ_TOTAL, B.ANGGOTA_ID, B.PINJ_SISA, 
						B.LUNAS, B.REKENING, B.KODECABANG, C.NAMA NAMA_AGT, D.JENIS_TRANSAKSI
						FROM tbl_pinjaman_d A 
						LEFT JOIN tbl_pinjaman_h B ON A.IDPINJAM = B.IDPINJM_H
						LEFT JOIN m_anggota C ON B.ANGGOTA_ID = C.IDANGGOTA
						LEFT JOIN jns_akun D ON B.JNS_TRANS = D.IDAKUN
						WHERE A.UPDATE_DATA = '0000-00-00 00:00:00' AND A.STATUS='0'";  
					   
						$getPinjaman = $this->dbasemodel->loadsql($sql);
						
						foreach($getPinjaman->result() as $key) 
						{ 
							
							$hasil = $key->JUMLAH_BAYAR-$key->BASILBAYAR;
							$datatransaksi = array('tgl'        => $key->TGL_BAYAR, 
												   'jumlah'     => $hasil, 
												   'keterangan' => 'Angsuran ke '.$key->ANGSURAN_KE.' '. $key->JENIS_TRANSAKSI .', No Rek : '. $key->REKENING . '('. $key->NAMA_AGT .')', 
												   'user'       => $key->USERNAME,
												   'kodecabang' => $key->KODECABANG,
												   'idkasakun'  => $key->KAS_ID,
												   'ket_dt'     => 'angsuran '. $key->JENIS_TRANSAKSI);
												   
							if($key->JUMLAH_BAYAR != 0) 
							{ 
								$this->ModelVTransaksi->insertVtransaksi($key->IDPINJ_D, $datatransaksi, 'AR', $key->KAS_ID, $key->JENIS_TRANS, 'PINJ');
							}
							
							$jenisAkun     = namaAkun(sukubunga('pendapatan_mudharabah'));
							$datatransaksi = array('tgl'        => $key->TGL_BAYAR,
												   'jumlah'     => $key->BASILBAYAR, 
												   'keterangan' => $jenisAkun['JENIS_TRANSAKSI'] .', No Rek : '. $key->REKENING . '('. $key->NAMA_AGT .')', 
												   'user'       => $key->USERNAME,
												   'kodecabang' => $key->KODECABANG,
												   'idkasakun'  => $key->KAS_ID,
												   'ket_dt'		=> $jenisAkun['JENIS_TRANSAKSI']);
												   
							if($key->BASILBAYAR != 0) 
							{
								$this->ModelVTransaksi->insertVtransaksi($key->IDPINJ_D, $datatransaksi, 'AR', $key->KAS_ID, $jenisAkun['IDAKUN'], 'PINJ');
							}
							
							$idAkunReset = $this->akunKasReset($key->KODECABANG);
						
							$datatransaksi = array('tgl'        => $key->TGL_BAYAR,
												   'jumlah'     => $key->DENDA_RP, 
												   'keterangan' => 'Pendapatan Reset '. $key->JENIS_TRANSAKSI .', No Rek: '.$key->REKENING . '('. $key->NAMA_AGT .')', 
												   'user'       => $key->USERNAME,
												   'idkasakun' => $key->KAS_ID,
												   'kodecabang' => $key->KODECABANG);
												   
							if($key->DENDA_RP != 0) 
							{
								$this->ModelVTransaksi->insertVtransaksi($key->IDPINJ_D, $datatransaksi, 'KR', $idAkunReset, sukubunga('admin_pembiayaan'), 'PINJ');
								$this->updateTabelReset($key->IDPINJAM, $key->DENDA_RP, 0);
							}
							
							$datatransaksi = array('tgl'     => $key->TGL_BAYAR,
												'jumlah'     => $key->BIAYA_KOLEKTOR, 
												'keterangan' => 'Pendapatan Admin Kolektor No Rek: '.$key->REKENING . '('. $key->NAMA_AGT .')', 
												'user'       => $key->USERNAME,
												'idkasakun'  => $key->KAS_ID,
												'kodecabang' => $key->KODECABANG);
												
							if($key->BIAYA_KOLEKTOR != 0) 
							{
								$idKolektor = $this->akunKasReset($key->KODECABANG); 
								$this->ModelVTransaksi->insertVtransaksi($key->IDPINJ_D, $datatransaksi, 'RT', $idKolektor, sukubunga('admin_kolektor'), 'PINJ');
								$this->updateTabelReset($key->IDPINJAM, $key->BIAYA_KOLEKTOR, 1);
							}
							
							$status_lunas = ($key->PINJ_SISA - $key->JUMLAH_BAYAR) <= 0 ? 'Lunas' : 'Belum';
							  
							$selisihpembulatan = $key->PINJ_RP_ANGSURAN-$key->PINJ_TOTAL;
							
							$datatransaksipembulatan = array('tgl'        => $key->TGL_BAYAR, 
															 'jumlah'     => $selisihpembulatan,  
															 'keterangan' => 'Pembulatan Selisih', 
															 'user'       => $key->USERNAME,
															 'kodecabang' => $key->KODECABANG,
															 'idkasakun'  => $key->KAS_ID,
															 'ket_dt'     => 'pembulatan Selisih');
													
							if($status_lunas == "Lunas"){
								
								$this->ModelVTransaksi->insertVtransaksi($key->IDPINJ_D, $datatransaksipembulatan, 'PL', $key->KAS_ID, '434', 'PINJ');
							}
							
							
							$PINJ_DIBAYAR       = ($key->JUMLAH_BAYAR - $key->BIAYA_KOLEKTOR);  
							$PINJ_POKOK_DIBAYAR = $key->POKOKBAYAR;
							$PINJ_BASIL_BAYAR   = $key->BASILBAYAR;
							
							$sql = "UPDATE tbl_pinjaman_h 
									SET PINJ_DIBAYAR = (PINJ_DIBAYAR + '".$PINJ_DIBAYAR."'),
									PINJ_SISA = (PINJ_SISA - '".$PINJ_DIBAYAR."'), 
									PINJ_POKOK_DIBAYAR = (PINJ_POKOK_DIBAYAR + '".$PINJ_POKOK_DIBAYAR."'),
									PINJ_POKOK_SISA = (PINJ_POKOK_SISA - '".$PINJ_POKOK_DIBAYAR."'), 
									UPDATE_DATA = NOW(),
									LUNAS = '".$status_lunas."', 
									PINJ_BASIL_BAYAR = (PINJ_BASIL_BAYAR + '".$PINJ_BASIL_BAYAR."')
									WHERE IDPINJM_H = '".$key->IDPINJAM."'";
									 
							$this->dbasemodel->loadSql($sql); 
							
							$sql = "UPDATE tbl_pinjaman_d SET UPDATE_DATA = NOW(), STATUS = 1 WHERE IDPINJ_D = '".$key->IDPINJ_D."'";
							$this->dbasemodel->loadsql($sql);
							 
							$sql = sprintf("UPDATE m_anggota SET PINJ_DIBAYAR = (PINJ_DIBAYAR + %s),
											PINJ_SISA = (PINJ_SISA - %s), ISCREDIT = '%s', PINJ_POKOK = %s,
											PINJ_TOTAL = %s, PINJ_RP_ANGSURAN = %s,
											PINJ_BASIL_DASAR = %s, PINJ_BASIL_BAYAR = (PINJ_BASIL_BAYAR + %s),
											PINJ_POKOK_DIBAYAR = (PINJ_POKOK_DIBAYAR + %s),
											PINJ_POKOK_SISA = (PINJ_POKOK_SISA - %s)
											WHERE IDANGGOTA = %s ", 
											$key->JUMLAH_BAYAR,
											$key->JUMLAH_BAYAR,
											($status_lunas == 'Lunas' ? 0 : 1), 
											($status_lunas == 'Lunas' ? 0 : 'PINJ_POKOK'),
											($status_lunas == 'Lunas' ? 0 : 'PINJ_TOTAL'),
											($status_lunas == 'Lunas' ? 0 : 'PINJ_RP_ANGSURAN'),
											($status_lunas == 'Lunas' ? 0 : 'PINJ_BASIL_DASAR'),
											$key->BASILBAYAR,
											$key->POKOKBAYAR,
											$key->POKOKBAYAR,
											$key->ANGGOTA_ID
										);
										
							$this->dbasemodel->loadSql($sql);
						}
					}
					
					/* else{
						
						$this->db->select('*');
						$this->db->from('tbl_pinjaman_h');
						$this->db->where('ANGGOTA_ID',$NO_ANGGOTA);
						$getidpinjams = $this->db->get()->row();
						
						$IDPINJM_H = $getidpinjams->IDPINJM_H;
					} */
					
					  
					
				 
				}
				$numrow++; 
			} 
			 
			// echo "<pre>";
			// echo print_r($getdataangsuran);
			// echo "</pre>";
		
			$this->session->set_flashdata('notif', '<div class="alert alert-success"><b>PROSES IMPORT BERHASIL!</b> Data berhasil diimport!</div>');
            
            redirect('angsuran/importangsuran');

        }
    }
	protected function create_penarikan($data) {
		 
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
			
			
			$datatransaksi	= array('tgl' => $data['tgl_trx'], 
									'jumlah' => $data['bayar_saldo'], 
									'kodecabang' => $KODECABANG,  
									'idkasakun' => $IDAKUN, 
									'keterangan' => 'Pembayaran Angsuran',
									'ket_dt'     => 'angsuran bayar saldo');
			
			$this->ModelVTransaksi->insertVtransaksi($idtrx, $datatransaksi, 'PT', $jenis_id, $IDAKUN, 'SIMP');			
			
			$data = array('TGL' 		=>	$data['tgl_trx'],
						  'KREDIT'		=>	$data['bayar_saldo'],
						  'DARI_KAS'	=>	$kas_id,
						  'ID_TRX_SIMP'	=>	$idtrx,
						  'TRANSAKSI'	=>	$jenis_id,
						  'KET'			=>	'Pembayaran Angsuran',
						  'USER'		=>	$this->session->userdata('wad_user')
					);
		}
		return true;
	} 
	protected function _integer($data){
		return str_replace(array(',', '.'), '', $data);
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
	protected function updateTabelReset($idpinjam, $jumlah, $jenis = 0) {
		$sql	=	sprintf("SELECT * FROM tbl_reset WHERE IDPINJAMAN = %s AND JENIS = %s AND LUNAS = 0 ORDER BY TANGGAL", $idpinjam, $jenis);
		$query	=	$this->dbasemodel->loadsql($sql);
		if($query->num_rows() > 0) {
			foreach($query->result() as $res) {
				if($jumlah > 0 ) {
					$sisa	=	($res->JUMLAH - $res->DIBAYAR);
					$bayar	=	$jumlah >= $sisa ? $sisa : $jumlah;
					$lunas	=	($sisa == $bayar ? '1' : '0');
					$sql	=	sprintf("UPDATE tbl_reset SET DIBAYAR = (DIBAYAR + %s), LUNAS = %s WHERE ID = %s ", $bayar, $lunas, $res->ID);
					$this->dbasemodel->loadsql($sql);
					$jumlah	=	$jumlah - $bayar;
				}
			}
		}
	}
}