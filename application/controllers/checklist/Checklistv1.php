<?php
require APPPATH.'third_party/vendor/autoload.php';    
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

defined('BASEPATH') OR exit('No direct script access allowed');

class Checklist extends CI_Controller {

	function __construct(){ 
        parent::__construct();
		$this->load->helper(array('form','url', 'xml','text_helper','date','inflector','app'));
		$this->load->database();
		$this->load->library(array('Pagination','user_agent','session','form_validation','session'));
		$this->load->model(array('dbasemodel', 'ModelVTransaksi'));
		ini_set("memory_limit", "-1");
		set_time_limit(0);
    }
	
	public function index()
	{
		if(!is_logged_in()){
			redirect('/auth_login');	
		}
        $data['PAGE_TITLE']     = "Checklist Teller";
        $data['page']           = "checklist/checklist_teller";
        $data['response']       = '';
		
		if(isset($_GET['tgl']))
		{
			$tgl = date("Y-m-d", strtotime($_GET['tgl']));
			$wheretrgl = "AND DATE(A.TGL_AWAL)='".$tgl."'";
		}else{
			$wheretrgl = "AND DATE(A.TGL_AWAL)='".date("Y-m-d")."'";
		}
		
		$koncabang = ($this->session->userdata('wad_cabang')!="")? " AND A.KODECABANG='".$this->session->userdata('wad_cabang')."'":"";

		$data['query']			= $this->dbasemodel->loadsql("SELECT A.IDCEKTELLER,
																  DATE_FORMAT(A.TGL_AWAL,'%d/%m/%Y') as TGL_AWAL,
																  A.NOMINAL_SIMP,
																  A.NOMINAL_PINJ,
																  A.BUKTI,
																  B.NAMA AS CABANG,
																  A.KODECABANG
																  FROM checklist_teller A
																  LEFT JOIN m_cabang B ON A.KODECABANG=B.KODE
															 WHERE A.STATUS='0' $koncabang $wheretrgl ORDER BY TGL_AWAL ASC ");

        $this->load->view('dashboard',$data);
    }
	
	function uploadteller()
	{
		if(!is_logged_in()){
			redirect('/auth_login');	
		}
		if($this->input->post())
		{
			//var_dump($_POST);
			$mydir	= $this->session->userdata('wad_kodepusat')."_".$this->session->userdata('wad_kodecabang')."_".date("Ymd");
			$path 	= './uploads/bukti/'.$mydir;
			if (!file_exists($path)) {
				mkdir($path, 0755, true);
			}
			$id 						= $this->input->post("id");
			//$config['encrypt_name'] 	= TRUE;
			$config['upload_path'] 		= $path.'/';
			$config['allowed_types'] 	= 'gif|jpg|png|jpeg';
			$config['max_size'] 		= 2000;
			$new_name 					= time()."_".$_FILES["buktiteller".$id]['name'];
			$config['file_name'] 		= $new_name;

			$this->load->library('upload', $config);

			if (!$this->upload->do_upload("buktiteller".$id)) {
				//$error = array('error' => $this->upload->display_errors());
				echo "99|".$this->upload->display_errors();
			} else {
				$data = array('upload_data' => $this->upload->data());
				
				$where  = "IDCEKTELLER = '". $id."' ";
				$datacheclist = array("BUKTI"=>$mydir."/".$data['upload_data']['file_name']);
				$this->dbasemodel->updateData("checklist_teller", $datacheclist, $where);
				
				echo "00|".$mydir."/".$data['upload_data']['file_name'];
			}
			
		}
		
	}
	
	function confirmteller()
	{
		if(!is_logged_in()){
			redirect('/auth_login');	
		}
		$id = $this->input->post('id');
		$cek = $this->dbasemodel->loadsql("SELECT * FROM checklist_teller WHERE IDCEKTELLER='$id'");
		if($cek->num_rows()>0)
		{
			$res =  $cek->row();
			$cus = $this->dbasemodel->loadsql("SELECT * FROM transaksi_simp WHERE UPDATE_DATA='0000-00-00 00:00:00' 
													AND DATE(TGL_TRX)='".$res->TGL_AWAL."' 
													AND STATUS='0' 
													AND KODEPUSAT='".$res->KODEPUSAT."' 
													AND KODECABANG='".$res->KODECABANG."'");
			if($cus->num_rows()>0){
				foreach($cus->result() as $key){
					
					/* Insert data transaksi simpanan ke jurnal transaksi(table vtransaksi) */
					$datatransaksi	=	array( 'tgl' => $key->TGL_TRX,  'jumlah' => $key->JUMLAH, 'keterangan' => $key->KETERANGAN, 'user' => $key->USERNAME );
					$this->ModelVTransaksi->insertVtransaksi($key->ID_TRX_SIMP, $datatransaksi, 'ST', $key->ID_KAS, $key->ID_JENIS, 'SIMP');
					
					$ceklst			=	$this->dbasemodel->loadsql("SELECT * FROM m_anggota_simp WHERE IDANGGOTA='".$key->ID_ANGGOTA."' AND IDJENIS_SIMP='".$key->ID_JENIS."'");
					if($ceklst->num_rows()>0)
					{
						$rchek	= $ceklst->row();
						$sql	= sprintf("UPDATE m_anggota_simp SET SALDO = (SALDO + %s) WHERE ID_ANG_SIMP = %s ", $key->JUMLAH, $rchek->ID_ANG_SIMP);
						$this->dbasemodel->loadSql($sql);
						
					}else{
						$datacheclist = array("IDANGGOTA"	=> $key->ID_ANGGOTA,
											"IDJENIS_SIMP"	=> $key->ID_JENIS,
											"SALDO"			=> $key->JUMLAH,
											"TGLREG"		=> date("Y-m-d", strtotime($key->TGL_TRX)) );
						$this->dbasemodel->insertData("m_anggota_simp",$datacheclist);
					}
					
					$wheresimp  = "ID_TRX_SIMP = '". $key->ID_TRX_SIMP."'";
					$updatesimp = array("UPDATE_DATA"=>date("Y-m-d H:i:s"), "STATUS"=>"1");
					$this->dbasemodel->updateData("transaksi_simp", $updatesimp, $wheresimp);
					
					$wanggota  = "IDANGGOTA = '". $key->ID_ANGGOTA."'";
					$uanggota = array("AKTIF"=>"Y");
					$this->dbasemodel->updateData("m_anggota", $uanggota, $wanggota);	
				}
			}
			# ANGSURAN
			$sql	=	"SELECT A.*, 
							B.ANGGOTA_ID, B.PINJ_SISA, B.LUNAS, B.REKENING, B.KODECABANG, 
							C.NAMA NAMA_AGT,
							D.JENIS_TRANSAKSI
						 FROM tbl_pinjaman_d A 
						 LEFT JOIN
						 	tbl_pinjaman_h B ON A.IDPINJAM = B.IDPINJM_H
						 LEFT JOIN
						 	m_anggota C ON B.ANGGOTA_ID = C.IDANGGOTA
						 LEFT JOIN
						 	jns_akun D ON B.JNS_TRANS = D.IDAKUN
						 WHERE A.UPDATE_DATA = '0000-00-00 00:00:00' 
							 AND DATE(A.TGL_BAYAR)='".$res->TGL_AWAL."' 
							 AND A.STATUS='0' 
							 AND B.KODEPUSAT='".$res->KODEPUSAT."'  ";
			$query	=	$this->dbasemodel->loadsql($sql);
			
			if($query->num_rows() > 0){
				foreach($query->result() as $key) {
					
					/* Insert pembayaran/angsuran pokok ke jurnal transaksi(table vtransaksi) */
					$datatransaksi	=	array('tgl' 		=> $key->TGL_BAYAR,
											'jumlah' 		=> $key->POKOKBAYAR, 
											'keterangan' 	=> 'Angsuran ke '.$key->ANGSURAN_KE.' '. $key->JENIS_TRANSAKSI .', No Rek : '. $key->REKENING . '('. $key->NAMA_AGT .')', 
											'user' 			=> $key->USERNAME,
											'kodecabang' 	=> $key->KODECABANG,
											'ket_dt'		=> 'angsuran '. $key->JENIS_TRANSAKSI);
					if($key->POKOKBAYAR != 0) {
						$this->ModelVTransaksi->insertVtransaksi($key->IDPINJ_D, $datatransaksi, 'JT', $key->KAS_ID, $key->JENIS_TRANS, 'PINJ');
					}
					
					/* Insert pembayaran/angsuran pokok ke jurnal transaksi(table vtransaksi) */
					$jenisAkun		=	namaAkun(sukubunga('pendapatan_mudharabah'));
					$datatransaksi	=	array('tgl' 		=> $key->TGL_BAYAR,
											'jumlah' 		=> $key->BASILBAYAR, 
											'keterangan' 	=> $jenisAkun['JENIS_TRANSAKSI'] .', No Rek : '. $key->REKENING . '('. $key->NAMA_AGT .')', 
											'user' 			=> $key->USERNAME,
											'kodecabang' 	=> $key->KODECABANG,
											'ket_dt'		=> $jenisAkun['JENIS_TRANSAKSI']);
					if($key->BASILBAYAR != 0) {
						$this->ModelVTransaksi->insertVtransaksi($key->IDPINJ_D, $datatransaksi, 'JT', $key->KAS_ID, $jenisAkun['IDAKUN'], 'PINJ');
					}
					
					/* Insert Biaya reset ke jurnal transaksi(table vtransaksi) */
					$idAkunReset	=	$this->akunKasReset($key->KODECABANG);
					$datatransaksi	=	array('tgl' 		=> $key->TGL_BAYAR,
											'jumlah' 		=> $key->DENDA_RP, 
											'keterangan' 	=> 'Pendapatan Reset '. $key->JENIS_TRANSAKSI .', No Rek: '.$key->REKENING . '('. $key->NAMA_AGT .')', 
											'user' 			=> $key->USERNAME,
											'kodecabang' 	=> $key->KODECABANG);
					if($key->DENDA_RP != 0) {
						$this->ModelVTransaksi->insertVtransaksi($key->IDPINJ_D, $datatransaksi, 'KR', $idAkunReset, sukubunga('admin_pembiayaan'), 'PINJ');
						$this->updateTabelReset($key->IDPINJAM, $key->DENDA_RP, 0);
					}
					
					/* Insert Biaya admin kolektor ke jurnal transaksi(table vtransaksi) */
					$datatransaksi	=	array('tgl' 		=> $key->TGL_BAYAR,
											'jumlah' 		=> $key->BIAYA_KOLEKTOR, 
											'keterangan' 	=> 'Pendapatan Admin Kolektor No Rek: '.$key->REKENING . '('. $key->NAMA_AGT .')', 
											'user' 			=> $key->USERNAME,
											'kodecabang' 	=> $key->KODECABANG);
					if($key->BIAYA_KOLEKTOR != 0) {
						$this->ModelVTransaksi->insertVtransaksi($key->IDPINJ_D, $datatransaksi, 'RT', kasteller($key->KODECABANG), sukubunga('admin_kolektor'), 'PINJ');
						$this->updateTabelReset($key->IDPINJAM, $key->BIAYA_KOLEKTOR, 1);
					}
					
					$status_lunas	=	($key->PINJ_SISA - $key->JUMLAH_BAYAR) <= 0 ? 'Lunas' : 'Belum';
					
					$sql	=	sprintf("UPDATE tbl_pinjaman_h 
										 SET 
											PINJ_DIBAYAR = (PINJ_DIBAYAR + %s),
											PINJ_SISA = (PINJ_SISA - %s),
											PINJ_POKOK_DIBAYAR = (PINJ_POKOK_DIBAYAR + %s),
											PINJ_POKOK_SISA = (PINJ_POKOK_SISA - %s),
											UPDATE_DATA = NOW(),
											LUNAS = '%s',
											PINJ_BASIL_BAYAR = (PINJ_BASIL_BAYAR + %s)
										 WHERE 
											IDPINJM_H = %s ", 
										 ($key->JUMLAH_BAYAR - $key->BIAYA_KOLEKTOR),
										 ($key->JUMLAH_BAYAR - $key->BIAYA_KOLEKTOR),
										 $key->POKOKBAYAR,
										 $key->POKOKBAYAR,
										 $status_lunas,
										 $key->BASILBAYAR,
										 $key->IDPINJAM
									);
					$this->dbasemodel->loadSql($sql);
					
					$sql	=	sprintf("UPDATE tbl_pinjaman_d 
										 SET 
											UPDATE_DATA = NOW(), 
											STATUS = 1
										 WHERE IDPINJ_D = %s ", 
										 $key->IDPINJ_D
										 );
					$this->dbasemodel->loadsql($sql);
					
					//if($key->LUNAS === 'Belum') {
					$sql	=	sprintf("UPDATE m_anggota 
										 SET 
											PINJ_DIBAYAR = (PINJ_DIBAYAR + %s),
											PINJ_SISA = (PINJ_SISA - %s),
											ISCREDIT = '%s',
											PINJ_POKOK = %s,
											PINJ_TOTAL = %s,
											PINJ_RP_ANGSURAN = %s,
											PINJ_BASIL_DASAR = %s,
											PINJ_BASIL_BAYAR = (PINJ_BASIL_BAYAR + %s),
											PINJ_POKOK_DIBAYAR = (PINJ_POKOK_DIBAYAR + %s),
											PINJ_POKOK_SISA = (PINJ_POKOK_SISA - %s)
										 WHERE 
											IDANGGOTA = %s ", 
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
					//}
					/* $sql	=	sprintf("UPDATE m_anggota SET ISCREDIT = '%s' WHERE IDANGGOTA = %s ", ($status_lunas == 'Lunas' ? 0 : 1), $key->ANGGOTA_ID);
					$this->dbasemodel->loadsql($sql); */
				}
			}
			echo "ok";
			
			$sql	=	sprintf("UPDATE 
									checklist_teller 
								 SET
									AKUMULASI_SIMP = (AKUMULASI_SIMP + NOMINAL_SIMP),
									AKUMULASI_PINJ = (AKUMULASI_PINJ + NOMINAL_PINJ),
									NOMINAL_SIMP = 0, NOMINAL_PINJ = 0, APPROVAL = '%s', STATUS = 1
								 WHERE
									IDCEKTELLER = '%s' ", $this->session->userdata('wad_id'), $id);
			$this->dbasemodel->loadSql($sql);
					
			/*$wherecls  	= "IDCEKTELLER = '".$id."'";
			$cls 		= array("NOMINAL_SIMP"	=>	0,
								"NOMINAL_PINJ"	=>	0,
								"APPROVAL"		=>	0, #$this->session->userdata('wad_id'),
								"STATUS"		=>	0);
			$this->dbasemodel->updateData("checklist_teller", $cls, $wherecls);*/
			
		}else{
			echo "error";
		}
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
	
	protected function akunKasReset($kodecabang){
		$sql			=	sprintf("SELECT
										A.IDAKUN
									FROM
										jenis_kas A
									WHERE
										A.KODECABANG = '%s'
										AND A.NAMA_KAS LIKE 'kas reset'
									LIMIT 1",
									$kodecabang
								);
		$query			=	$this->dbasemodel->loadSql($sql);
		if($query->num_rows() > 0) {
			$row	=	$query->row();
			return $row->IDAKUN;
		}
		return 0;
	}
	
	
	function detailceklis()
	{
		if(!is_logged_in()){
			redirect('/auth_login');	
		}
		
		$cek = $this->dbasemodel->loadsql("SELECT * FROM checklist_teller WHERE IDCEKTELLER='".$this->uri->segment(2)."'");
		if($cek->num_rows()>0){
			$res =  $cek->row();
		
			$data['opt_data_entries']	=	$this->load->view('_elements/data_entries', NULL, TRUE);
			$data['table_footer']		=	$this->load->view('_elements/table_footer', NULL, TRUE);
			$data['PAGE_TITLE']     = "Checklist Setoran";
			$data['page']           = "checklist/checklist_detail";
			$data['tgl']     		= $res->TGL_AWAL;
			$this->load->view('dashboard',$data);
		}else{
			redirect('/cheklist-teller');
		}
		
	}
	
	function detaildata()
	{
		if(!is_logged_in()){
			redirect('/auth_login');	
		}
		/*if($this->input->post('tgl'))
		{
			$tgl = date("Y-m-d", strtotime($this->input->post('tgl')));
			$wheretrgl = "AND DATE(TGL_TRX)='".$tgl."'";
		}else{
			$wheretrgl = "AND DATE(TGL_TRX)='".date("Y-m-d")."'";
		}*/
		 
		
		$koncabang = ($this->session->userdata('wad_cabang')!="")? " AND A.KODECABANG='".$this->session->userdata('wad_cabang')."'":"";
		
		$this->load->model('ModelChecklist');
		$keyword		=	null !== $this->uri->segment(4) ? $this->uri->segment(4) : "";
		$dataPerPage	=	$this->input->post('dataperpage');
		$page			=	$this->input->post('page');
		$dataTable		=	$this->ModelChecklist->getDatasimpanan($keyword, $dataPerPage, $page,$koncabang,$this->input->post('tgl'));

        header('Content-Type: application/json');
		echo json_encode($dataTable);
		die();
	}
	
	
	public function migrasi_simpanan()
	{
		if(!is_logged_in()){
			redirect('/auth_login');	
		}
		$simpanan = 1;
		if($simpanan > 0)
		{
			$cus = $this->dbasemodel->loadsql("SELECT * FROM transaksi_simp 
												# WHERE UPDATE_DATA = '0000-00-00 00:00:00'");
			if($cus->num_rows()>0){
				foreach($cus->result() as $key){
					
					/* Insert data transaksi simpanan ke jurnal transaksi(table vtransaksi) */
					$datatransaksi	=	array( 
											'tgl' 			=> $key->TGL_TRX,  
											'jumlah' 		=> $key->JUMLAH, 
											'keterangan' 	=> $key->KETERANGAN == '' ? 'Setoran tunai ('. $key->NAMA_PENYETOR.')' : $key->KETERANGAN, 
											'user' 			=> $key->USERNAME,
											'kodecabang' 	=> $key->KODECABANG,
											'ket_dt' 		=> 'setoran tunai' );
					$this->ModelVTransaksi->insertVtransaksi($key->ID_TRX_SIMP, $datatransaksi, 'ST', $key->ID_KAS, $key->ID_JENIS, 'SIMP');
					
					$ceklst			=	$this->dbasemodel->loadsql("SELECT * FROM m_anggota_simp WHERE IDANGGOTA='".$key->ID_ANGGOTA."' AND IDJENIS_SIMP='".$key->ID_JENIS."'");
					if($ceklst->num_rows()>0)
					{
						$rchek	= $ceklst->row();
						$sql	= sprintf("UPDATE m_anggota_simp SET SALDO = (SALDO + %s) WHERE ID_ANG_SIMP = %s ", $key->JUMLAH, $rchek->ID_ANG_SIMP);
						//$this->dbasemodel->loadSql($sql);
						
					}else{
						$datacheclist = array("IDANGGOTA"	=> $key->ID_ANGGOTA,
											"IDJENIS_SIMP"	=> $key->ID_JENIS,
											"SALDO"			=> $key->JUMLAH,
											"TGLREG"		=> date("Y-m-d", strtotime($key->TGL_TRX)) );
						//$this->dbasemodel->insertData("m_anggota_simp",$datacheclist);
					}
					
					$wheresimp  = "ID_TRX_SIMP = '". $key->ID_TRX_SIMP."'";
					$updatesimp = array("UPDATE_DATA"=>date("Y-m-d H:i:s"), "STATUS"=>"1");
					//$this->dbasemodel->updateData("transaksi_simp", $updatesimp, $wheresimp);
					
					$wanggota  = "IDANGGOTA = '". $key->ID_ANGGOTA."'";
					$uanggota = array("AKTIF"=>"Y");
					//$this->dbasemodel->updateData("m_anggota", $uanggota, $wanggota);	
				}
			}
			
			echo "ok: simpanan";
		}else{
			echo "error";
		}
	}
	
	public function migrasi_angsuran()
	{
		if(!is_logged_in()){
			redirect('/auth_login');	
		}
		$angsuran = 1;
		if($angsuran > 0)
		{
			# ANGSURAN
			$sql	=	"SELECT A.*, B.ANGGOTA_ID, B.PINJ_SISA, B.LUNAS, B.REKENING, B.KODECABANG, C.NAMA NAMA_AGT
						 FROM tbl_pinjaman_d A 
						 LEFT JOIN
						 	tbl_pinjaman_h B ON A.IDPINJAM = B.IDPINJM_H
						 LEFT JOIN
						 	m_anggota C ON B.ANGGOTA_ID = C.IDANGGOTA
						 # WHERE UPDATE_DATA = '0000-00-00 00:00:00' ";
			$query	=	$this->dbasemodel->loadsql($sql);
			
			if($query->num_rows() > 0){
				foreach($query->result() as $key) {
					
					/* Insert pembayaran/angsuran pokok ke jurnal transaksi(table vtransaksi) */
					$datatransaksi	=	array('tgl' 		=> $key->TGL_BAYAR,
											'jumlah' 		=> $key->POKOKBAYAR, 
											'keterangan' 	=> 'Angsuran ke '.$key->ANGSURAN_KE.' '. $key->JENIS_TRANSAKSI .', No Rek : '. $key->REKENING . '('. $key->NAMA_AGT .')', 
											'user' 			=> $key->USERNAME,
											'kodecabang' 	=> $key->KODECABANG,
											'ket_dt' 		=> 'angsuran '. $key->JENIS_TRANSAKSI);
					if($key->POKOKBAYAR != 0) {
						$this->ModelVTransaksi->insertVtransaksi($key->IDPINJ_D, $datatransaksi, 'JT', $key->KAS_ID, $key->JENIS_TRANS, 'PINJ');
					}
					
					/* Insert pembayaran/angsuran pokok ke jurnal transaksi(table vtransaksi) */
					$jenisAkun		=	namaAkun(sukubunga('pendapatan_mudharabah'));
					$datatransaksi	=	array('tgl' 		=> $key->TGL_BAYAR,
											'jumlah' 		=> $key->BASILBAYAR, 
											'keterangan' 	=> $jenisAkun['JENIS_TRANSAKSI'] .', No Rek : '. $key->REKENING . '('. $key->NAMA_AGT .')', 
											'user' 			=> $key->USERNAME,
											'kodecabang' 	=> $key->KODECABANG,
											'ket_dt' 		=> $jenisAkun['JENIS_TRANSAKSI'] .'');
					if($key->BASILBAYAR != 0) {
						$this->ModelVTransaksi->insertVtransaksi($key->IDPINJ_D, $datatransaksi, 'JT', $key->KAS_ID, $jenisAkun['IDAKUN'], 'PINJ');
					}
					
					$status_lunas	=	($key->PINJ_SISA - $key->JUMLAH_BAYAR) <= 0 ? 'Lunas' : 'Belum';
					
					$sql	=	sprintf("UPDATE tbl_pinjaman_h 
										 SET 
											PINJ_DIBAYAR = (PINJ_DIBAYAR + %s),
											PINJ_SISA = (PINJ_SISA - %s),
											PINJ_POKOK_DIBAYAR = (PINJ_POKOK_DIBAYAR + %s),
											PINJ_POKOK_SISA = (PINJ_POKOK_SISA - %s),
											UPDATE_DATA = NOW(),
											LUNAS = '%s',
											PINJ_BASIL_BAYAR = (PINJ_BASIL_BAYAR + %s)
										 WHERE 
											IDPINJM_H = %s ", 
										 $key->JUMLAH_BAYAR,
										 $key->JUMLAH_BAYAR,
										 $key->POKOKBAYAR,
										 $key->POKOKBAYAR,
										 $status_lunas,
										 $key->BASILBAYAR,
										 $key->IDPINJAM
									);
					//$this->dbasemodel->loadSql($sql);
					
								 
					$sql	=	sprintf("UPDATE tbl_pinjaman_d 
										 SET 
											UPDATE_DATA = NOW(), 
											STATUS = 1
										 WHERE IDPINJ_D = %s ", 
										 $key->IDPINJ_D
										 );
					//$this->dbasemodel->loadsql($sql);
					
					if($key->LUNAS === 'Belum') {
						$sql	=	sprintf("UPDATE m_anggota 
											 SET 
												PINJ_DIBAYAR = (PINJ_DIBAYAR + %s),
												PINJ_SISA = (PINJ_SISA - %s),
												ISCREDIT = '%s',
												PINJ_POKOK = %s,
												PINJ_TOTAL = %s,
												PINJ_RP_ANGSURAN = %s,
												PINJ_BASIL_DASAR = %s,
												PINJ_BASIL_BAYAR = (PINJ_BASIL_BAYAR + %s),
												PINJ_POKOK_DIBAYAR = (PINJ_POKOK_DIBAYAR + %s),
												PINJ_POKOK_SISA = (PINJ_POKOK_SISA - %s)
											 WHERE 
												IDANGGOTA = %s ", 
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
						//$this->dbasemodel->loadSql($sql);
					}
					
				}
			}
			echo "ok: angsuran";
			
			
		}else{
			echo "error";
		}
	}
	
	public function angsuranke()
	{
		if(!is_logged_in()){
			redirect('/auth_login');	
		}
		$cek = 1;
		if($cek > 0)
		{
			
			# ANGSURAN
			
			$sql	=	"SELECT A.*, B.ANGGOTA_ID, B.PINJ_SISA, B.LUNAS
						 FROM tbl_pinjaman_d A 
						 LEFT JOIN
						 	tbl_pinjaman_h B ON A.IDPINJAM = B.IDPINJM_H
						 LEFT JOIN
						 	m_anggota C ON B.ANGGOTA_ID = C.IDANGGOTA
						 WHERE A.UPDATE_DATA = '0000-00-00 00:00:00's";
			$query	=	$this->dbasemodel->loadsql($sql);
			
			if($query->num_rows() > 0){
				foreach($query->result() as $key) {
					$sql	=	sprintf("SELECT ANGSURAN_KE 
										 FROM tbl_pinjaman_d
										 WHERE IDPINJAM = %s
										 ORDER BY ANGSURAN_KE DESC
										 LIMIT 1", $key->IDPINJAM);
					$query	=	$this->dbasemodel->loadsql($sql);
					$angsuranke	=	1;
					if($query->num_rows() > 0) {
						$row	=	$query->row();
						$angsuranke	=	($row->ANGSURAN_KE + 1);
					}
										 
					$sql	=	sprintf("UPDATE tbl_pinjaman_d 
										 SET 
											ANGSURAN_KE = %s
										 WHERE IDPINJ_D = %s ", 
										 $angsuranke,
										 $key->IDPINJ_D
										 );
					$this->dbasemodel->loadsql($sql);
					
				}
			}
			echo "ok";
		}else{
			echo "error";
		}
	}
	
	public function updatesimp()
	{
		if(!is_logged_in()){
			redirect('/auth_login');	
		}
		$cek = 1;
		if($cek > 0)
		{
			$sql	=	"SELECT A.*
						 FROM transaksi_simp A  ";
			$query	=	$this->dbasemodel->loadsql($sql);
			
			if($query->num_rows() > 0){
				foreach($query->result() as $key) {
					$idkas		=	kasteller($key->KODECABANG);
					
					$sql	=	sprintf("UPDATE transaksi_simp 
										 SET 
											ID_KAS = %s
										 WHERE ID_TRX_SIMP = %s ", 
										 $idkas,
										 $key->ID_TRX_SIMP
										 );
					$this->dbasemodel->loadsql($sql);
					
				}
			}
			echo "ok";
		}else{
			echo "error";
		}
	}

	function excel()
	{
		if(!is_logged_in()){
			redirect('/auth_login');	
		}
		
		$spreadsheet = new Spreadsheet();
		$spreadsheet->getActiveSheet()->setTitle('Checklist Teller');
		$sheet = $spreadsheet->getActiveSheet();
		
		$sheet->setCellValue('A1', 'TANGGAL');
		$sheet->setCellValue('B1', 'NOMINAL SIMPANAN');
		$sheet->setCellValue('C1', 'NOMINAL PINJAMAN');
		$sheet->setCellValue('D1', 'CABANG');
		$sheet->setCellValue('E1', 'BUKTI');
		
		foreach(range('A','E') as $columnID)
		{
			$sheet->getColumnDimension($columnID)->setAutoSize(true);
		}
		
		$sheet->getStyle('A1:E1')->applyFromArray(
		   array(
			  'font'  => array(
				  'bold'  =>  true
			  )
		   )
		);

		if(isset($_GET['tgl']))
		{
			$tgl = date("Y-m-d", strtotime($_GET['tgl']));
			$wheretrgl = "AND DATE(A.TGL_AWAL)='".$tgl."'";
		}else{
			$wheretrgl = "AND DATE(A.TGL_AWAL)='".date("Y-m-d")."'";
		}
		
		$koncabang = ($this->session->userdata('wad_cabang')!="")? " AND A.KODECABANG='".$this->session->userdata('wad_cabang')."'":"";

		$cek		= $this->dbasemodel->loadsql("SELECT A.IDCEKTELLER,
													  DATE_FORMAT(A.TGL_AWAL,'%d/%m/%Y') as TGL_AWAL,
													  A.NOMINAL_SIMP,
													  A.NOMINAL_PINJ,
													  A.BUKTI,
													  B.NAMA AS CABANG
													  FROM checklist_teller A
													  LEFT JOIN m_cabang B ON A.KODECABANG=B.KODE
												 WHERE A.STATUS='0' $koncabang $wheretrgl ORDER BY TGL_AWAL ASC ");
								
		$row = 2;
		if($cek->num_rows() > 0){ $n = 1;
		
			foreach($cek->result() as $item){ 
				$sheet->setCellValue('A'.$row,$item->TGL_AWAL);
				$sheet->setCellValue('B'.$row,$item->NOMINAL_SIMP);
				$sheet->getStyle('B'.$row)->getNumberFormat()->setFormatCode('#,##0');
				$sheet->setCellValue('C'.$row,$item->NOMINAL_PINJ);
				$sheet->getStyle('C'.$row)->getNumberFormat()->setFormatCode('#,##0');
				$sheet->setCellValue('D'.$row,$item->CABANG);
				$sheet->setCellValue('E'.$row,$item->BUKTI);
				$row++;
			} 
			
		}
		
		$writer = new Xlsx($spreadsheet);
		$file = "checklist_teller_".date("ymdHis").".xlsx";
		$writer->save('export/'.$file);
		redirect(base_url().'export/'.$file);
		
	}
}