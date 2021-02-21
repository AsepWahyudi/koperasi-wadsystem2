<?php
require APPPATH.'third_party/vendor/autoload.php';    
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

defined('BASEPATH') OR exit('No direct script access allowed');

class Checklist extends CI_Controller {

	function __construct(){ 
        parent::__construct();
		$this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . 'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-chace');
        $this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");   
		
		$this->load->database(); 
		$this->load->model(array('dbasemodel', 'ModelVTransaksi'));
		ini_set("memory_limit", "-1");
		set_time_limit(0);
		if(!is_logged_in()){
			redirect('/auth_login');	
		}
    }
	
	public function index(){ 
        $data['PAGE_TITLE'] = "Checklist Teller";
        $data['page']       = "checklist/checklist_teller";
        $data['response']   = '';
		if(isset($_GET['tgl']))
		{
			// $tgl       = date("Y-m-d", strtotime(urldecode($_GET['tgl'])));
			$gettanggal = urldecode($_GET['tgl']);
			$tgl        = explode("/",$gettanggal);
			$tanggal    = $tgl[2]."-".$tgl[1]."-".$tgl[0]; 
			$wheretrgl  = "AND DATE(A.TGL_AWAL)='".$tanggal."'";
		}
		else
		{
			$wheretrgl = "AND DATE(A.TGL_AWAL)='".date("Y-m-d")."'";
		} 
		/* $koncabang = ($this->session->userdata('wad_cabang')!="")? " AND A.KODECABANG='".$this->session->userdata('wad_cabang')."'":"";
		if($this->session->userdata("wad_level") == "admin")
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
		
		$data['query'] = $this->dbasemodel->loadsql("SELECT A.IDCEKTELLER, DATE_FORMAT(A.TGL_AWAL,'%d/%m/%Y') as TGL_AWAL, A.NOMINAL_SIMP, A.NOMINAL_PINJ, A.BUKTI, B.NAMA AS CABANG, A.KODECABANG, (SELECT COUNT(*) FROM transaksi_simp WHERE UPDATE_DATA ='0000-00-00 00:00:00' AND STATUS ='0' AND KODECABANG= A.KODECABANG) JMLSIMPANAN , (SELECT COUNT(*) FROM tbl_pinjaman_d WHERE UPDATE_DATA ='0000-00-00 00:00:00' AND STATUS ='0' AND KODECABANG= A.KODECABANG) JMLPINJAMAN FROM checklist_teller A LEFT JOIN m_cabang B ON A.KODECABANG=B.KODE WHERE A.STATUS='0' $koncabang $wheretrgl ORDER BY TGL_AWAL ASC ");
		
		$data['sqlquery'] = "SELECT A.IDCEKTELLER, DATE_FORMAT(A.TGL_AWAL,'%d/%m/%Y') as TGL_AWAL, A.NOMINAL_SIMP,
							 A.NOMINAL_PINJ, A.BUKTI, B.NAMA AS CABANG, A.KODECABANG, (SELECT COUNT(*) FROM transaksi_simp WHERE UPDATE_DATA ='0000-00-00 00:00:00' AND STATUS ='0' AND KODECABANG= A.KODECABANG) JMLSIMPANAN 
							 FROM checklist_teller A
							 LEFT JOIN m_cabang B ON A.KODECABANG=B.KODE
							 WHERE A.STATUS='0' $koncabang $wheretrgl ORDER BY TGL_AWAL ASC "; 
							 
        $this->load->view('dashboard',$data);
    }
	
	public function uploadteller(){
		 
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
	public function uploadtellersetoran(){
		 
		if($this->input->post())
		{
			// var_dump($_POST);
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
			$new_name 					= time()."_".$_FILES["buktitellersetoran".$id]['name'];
			$config['file_name'] 		= $new_name;

			$this->load->library('upload', $config);

			if (!$this->upload->do_upload("buktitellersetoran".$id)) {
				//$error = array('error' => $this->upload->display_errors());
				echo "99|".$this->upload->display_errors();
			} else {
				$data = array('upload_data' => $this->upload->data());
				
				$where  = "ID_TRX_SIMP = '". $id."' ";
				$datacheclist = array("BUKTI"=>$mydir."/".$data['upload_data']['file_name']);
				$this->dbasemodel->updateData("transaksi_simp", $datacheclist, $where);
				
				echo "00|".$mydir."/".$data['upload_data']['file_name'];
			} 
		} 
	}
	public function uploadtellerangsuran(){
		 
		if($this->input->post())
		{
			// var_dump($_POST);
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
			$new_name 					= time()."_".$_FILES["buktitellerangsuran".$id]['name'];
			$config['file_name'] 		= $new_name;

			$this->load->library('upload', $config);

			if (!$this->upload->do_upload("buktitellerangsuran".$id)) {
				//$error = array('error' => $this->upload->display_errors());
				echo "99|".$this->upload->display_errors();
			} else {
				$data = array('upload_data' => $this->upload->data());
				
				$where  = "IDPINJ_D = '". $id."' ";
				$datacheclist = array("BUKTI"=>$mydir."/".$data['upload_data']['file_name']);
				$this->dbasemodel->updateData("tbl_pinjaman_d", $datacheclist, $where);
				
				echo "00|".$mydir."/".$data['upload_data']['file_name'];
			} 
		} 
	}
	
	public function confirmteller(){
		  
		$id  = $this->input->post('id');
		$cek = $this->dbasemodel->loadsql("SELECT * FROM checklist_teller WHERE IDCEKTELLER='$id'");
		if($cek->num_rows()>0)
		{
			$res = $cek->row();
			
			// $sqlcus = "SELECT * FROM transaksi_simp WHERE UPDATE_DATA='0000-00-00 00:00:00' AND DATE(TGL_TRX)='".$res->TGL_AWAL."' AND STATUS='0' AND KODEPUSAT='".$res->KODEPUSAT."' AND KODECABANG='".$res->KODECABANG."'";
			
			$sqlcus = "SELECT * FROM transaksi_simp WHERE UPDATE_DATA='0000-00-00 00:00:00' AND DATE(TGL_TRX)='".$res->TGL_AWAL."' AND STATUS='0' AND KODECABANG='".$res->KODECABANG."'";
			
			$cus = $this->dbasemodel->loadsql($sqlcus);
			
			if($cus->num_rows()>0)
			{
				foreach($cus->result() as $key)
				{ 
					/* Insert data transaksi simpanan ke jurnal transaksi(table vtransaksi) */
					$datatransaksi = array( 'tgl' => $key->TGL_TRX, 'jumlah' => $key->JUMLAH, 'keterangan' => $key->KETERANGAN,
											'user' => $key->USERNAME, 'kodecabang' => $key->KODECABANG, 'idkasakun' => $key->ID_KASAKUN);
											
					$this->ModelVTransaksi->insertVtransaksi($key->ID_TRX_SIMP, $datatransaksi, 'ST', $key->ID_KAS, $key->ID_JENIS, 'SIMP');
					
					$ceklst = $this->dbasemodel->loadsql("SELECT * FROM m_anggota_simp WHERE IDANGGOTA='".$key->ID_ANGGOTA."' AND IDJENIS_SIMP='".$key->ID_JENIS."'");
					
					if($ceklst->num_rows()>0)
					{
						$rchek = $ceklst->row();
						$sql = sprintf("UPDATE m_anggota_simp SET SALDO = (SALDO + %s) WHERE ID_ANG_SIMP = %s ", $key->JUMLAH, $rchek->ID_ANG_SIMP);
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
					
					$wheresimp  = "ID_TRX_SIMP = '". $key->ID_TRX_SIMP."'";
					$updatesimp = array("UPDATE_DATA"=>date("Y-m-d H:i:s"), "STATUS"=>"1");
					$this->dbasemodel->updateData("transaksi_simp", $updatesimp, $wheresimp);
					
					$wanggota = "IDANGGOTA = '". $key->ID_ANGGOTA."'";
					$uanggota = array("AKTIF"=>"Y");
					
					$this->dbasemodel->updateData("m_anggota", $uanggota, $wanggota);	
				}
			}
			# ANGSURAN  
			// $sql = "SELECT A.*, B.KAS_ID, B.JUMLAH, B.PINJ_RP_ANGSURAN, B.PINJ_TOTAL, B.ANGGOTA_ID, B.PINJ_SISA, 
					// B.LUNAS, B.REKENING, B.KODECABANG, C.NAMA NAMA_AGT, D.JENIS_TRANSAKSI
				    // FROM tbl_pinjaman_d A 
				    // LEFT JOIN tbl_pinjaman_h B ON A.IDPINJAM = B.IDPINJM_H
				    // LEFT JOIN m_anggota C ON B.ANGGOTA_ID = C.IDANGGOTA
				    // LEFT JOIN jns_akun D ON B.JNS_TRANS = D.IDAKUN
				    // WHERE A.UPDATE_DATA = '0000-00-00 00:00:00' 
				    // AND DATE(A.TGL_BAYAR)='".$res->TGL_AWAL."' 
				    // AND A.STATUS='0' 
				    // AND B.KODEPUSAT='".$res->KODEPUSAT."'";
					
			$sql = "SELECT A.*, B.KAS_ID, B.JUMLAH, B.PINJ_RP_ANGSURAN, B.PINJ_TOTAL, B.ANGGOTA_ID, B.PINJ_SISA, 
					B.LUNAS, B.REKENING, B.KODECABANG, C.NAMA NAMA_AGT, D.JENIS_TRANSAKSI
				    FROM tbl_pinjaman_d A 
				    LEFT JOIN tbl_pinjaman_h B ON A.IDPINJAM = B.IDPINJM_H
				    LEFT JOIN m_anggota C ON B.ANGGOTA_ID = C.IDANGGOTA
				    LEFT JOIN jns_akun D ON B.JNS_TRANS = D.IDAKUN
				    WHERE A.UPDATE_DATA = '0000-00-00 00:00:00' 
				    AND DATE(A.TGL_BAYAR)='".$res->TGL_AWAL."' 
				    AND A.STATUS='0'";
					
			$query = $this->dbasemodel->loadsql($sql);
			
			// echo $sql;
			
			if($query->num_rows() > 0)
			{
				foreach($query->result() as $key) 
				{ 
					// Insert pembayaran/angsuran pokok ke jurnal transaksi(table vtransaksi)
					
					$hasil = $key->JUMLAH_BAYAR-$key->BASILBAYAR;
					$datatransaksi = array('tgl'        => $key->TGL_BAYAR,
										// 'jumlah'     => $key->JUMLAH_BAYAR, 
										   'jumlah'     => $hasil, 
										   'keterangan' => 'Angsuran ke '.$key->ANGSURAN_KE.' '. $key->JENIS_TRANSAKSI .', No Rek : '. $key->REKENING . '('. $key->NAMA_AGT .')', 
										   'user'       => $key->USERNAME,
										   'kodecabang' => $key->KODECABANG,
										   'idkasakun'  => $key->KAS_ID,
										   'ket_dt'     => 'angsuran '. $key->JENIS_TRANSAKSI);
										   
					if($key->JUMLAH_BAYAR != 0) 
					{
						// $this->ModelVTransaksi->insertVtransaksi($key->IDPINJ_D, $datatransaksi, 'JT', $key->KAS_ID, $key->JENIS_TRANS, 'PINJ');
						$this->ModelVTransaksi->insertVtransaksi($key->IDPINJ_D, $datatransaksi, 'AR', $key->KAS_ID, $key->JENIS_TRANS, 'PINJ');
					}
					
					// Insert pembayaran/angsuran pokok ke jurnal transaksi(table vtransaksi)
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
					
					// Insert Biaya reset ke jurnal transaksi(table vtransaksi)
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
					
					// Insert Biaya admin kolektor ke jurnal transaksi(table vtransaksi)
					$datatransaksi = array('tgl' 		 => $key->TGL_BAYAR,
											'jumlah'     => $key->BIAYA_KOLEKTOR, 
											'keterangan' => 'Pendapatan Admin Kolektor No Rek: '.$key->REKENING . '('. $key->NAMA_AGT .')', 
											'user'       => $key->USERNAME,
											'idkasakun' => $key->KAS_ID,
											'kodecabang' => $key->KODECABANG);
											
					if($key->BIAYA_KOLEKTOR != 0) 
					{
						
						$idKolektor = $this->akunKasReset($key->KODECABANG);
						
						// $this->ModelVTransaksi->insertVtransaksi($key->IDPINJ_D, $datatransaksi, 'RT', kasteller($key->KODECABANG), sukubunga('admin_kolektor'), 'PINJ');
						$this->ModelVTransaksi->insertVtransaksi($key->IDPINJ_D, $datatransaksi, 'RT', $idKolektor, sukubunga('admin_kolektor'), 'PINJ');
						$this->updateTabelReset($key->IDPINJAM, $key->BIAYA_KOLEKTOR, 1);
					}
					
					$status_lunas = ($key->PINJ_SISA - $key->JUMLAH_BAYAR) <= 0 ? 'Lunas' : 'Belum';
					
					// PINJ_RP_ANGSURAN - PINJ_TOTAL
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
					
					
					$PINJ_DIBAYAR       = ($key->JUMLAH_BAYAR - $key->BIAYA_KOLEKTOR); // MENAMBAHKAN PEMBAYARAN ANGSURAN DI TABLE tbl_pinjaman_h
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
			echo "ok";
			 
			$this->dbasemodel->hapus("checklist_teller WHERE IDCEKTELLER = '". $id ."'");
	 
		}
		else
		{
			echo "error";
		}
	}
	public function confirmtellersetoran(){
		  
		$id  = $this->input->post('id');
		
		$pecah = explode("#",$id);
		$IDCEKTELLER = $pecah[0];
		$ID_TRX_SIMP = $pecah[1];
		
		$cek = $this->dbasemodel->loadsql("SELECT * FROM checklist_teller WHERE IDCEKTELLER='".$IDCEKTELLER."'");
		 
		if($cek->num_rows()>0)
		{
			$res = $cek->row();
			 
			$sqlcus = "SELECT * FROM transaksi_simp WHERE UPDATE_DATA='0000-00-00 00:00:00' AND DATE(TGL_TRX)='".$res->TGL_AWAL."' AND STATUS='0' AND ID_TRX_SIMP='".$ID_TRX_SIMP."'";
			
			// echo $sqlcus;
			
			$cus = $this->dbasemodel->loadsql($sqlcus);
			
			if($cus->num_rows()>0)
			{
				foreach($cus->result() as $key)
				{  
					$datatransaksi = array( 'tgl' => $key->TGL_TRX, 'jumlah' => $key->JUMLAH, 'keterangan' => $key->KETERANGAN,
											'user' => $key->USERNAME, 'kodecabang' => $key->KODECABANG, 'idkasakun' => $key->ID_KASAKUN);
											
					$this->ModelVTransaksi->insertVtransaksi($key->ID_TRX_SIMP, $datatransaksi, 'ST', $key->ID_KAS, $key->ID_JENIS, 'SIMP');
					
					$ceklst = $this->dbasemodel->loadsql("SELECT * FROM m_anggota_simp WHERE IDANGGOTA='".$key->ID_ANGGOTA."' AND IDJENIS_SIMP='".$key->ID_JENIS."'");
					
					if($ceklst->num_rows()>0)
					{
						$rchek = $ceklst->row();
						$sql = sprintf("UPDATE m_anggota_simp SET SALDO = (SALDO + %s) WHERE ID_ANG_SIMP = %s ", $key->JUMLAH, $rchek->ID_ANG_SIMP);
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
					
					$wheresimp  = "ID_TRX_SIMP = '". $key->ID_TRX_SIMP."'";
					$updatesimp = array("UPDATE_DATA"=>date("Y-m-d H:i:s"), "STATUS"=>"1");
					$this->dbasemodel->updateData("transaksi_simp", $updatesimp, $wheresimp);
					
					$wanggota = "IDANGGOTA = '". $key->ID_ANGGOTA."'";
					$uanggota = array("AKTIF"=>"Y");
					
					$this->dbasemodel->updateData("m_anggota", $uanggota, $wanggota);	
				}
			}
			# ANGSURAN   
			/* $sql = "SELECT A.*, B.KAS_ID, B.JUMLAH, B.PINJ_RP_ANGSURAN, B.PINJ_TOTAL, B.ANGGOTA_ID, B.PINJ_SISA, 
					B.LUNAS, B.REKENING, B.KODECABANG, C.NAMA NAMA_AGT, D.JENIS_TRANSAKSI
				    FROM tbl_pinjaman_d A 
				    LEFT JOIN tbl_pinjaman_h B ON A.IDPINJAM = B.IDPINJM_H
				    LEFT JOIN m_anggota C ON B.ANGGOTA_ID = C.IDANGGOTA
				    LEFT JOIN jns_akun D ON B.JNS_TRANS = D.IDAKUN
				    WHERE A.UPDATE_DATA = '0000-00-00 00:00:00' 
				    AND DATE(A.TGL_BAYAR)='".$res->TGL_AWAL."' 
				    AND A.STATUS='0'";
					
			$query = $this->dbasemodel->loadsql($sql);
			  
			if($query->num_rows() > 0)
			{
				foreach($query->result() as $key) 
				{  
					$hasil = $key->JUMLAH_BAYAR-$key->BASILBAYAR;
					$datatransaksi = array('tgl'        => $key->TGL_BAYAR,
										// 'jumlah'     => $key->JUMLAH_BAYAR, 
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
					 
					$datatransaksi = array('tgl' 		 => $key->TGL_BAYAR,
											'jumlah'     => $key->BIAYA_KOLEKTOR, 
											'keterangan' => 'Pendapatan Admin Kolektor No Rek: '.$key->REKENING . '('. $key->NAMA_AGT .')', 
											'user'       => $key->USERNAME,
											'idkasakun' => $key->KAS_ID,
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
					
					
					$PINJ_DIBAYAR       = ($key->JUMLAH_BAYAR - $key->BIAYA_KOLEKTOR); // MENAMBAHKAN PEMBAYARAN ANGSURAN DI TABLE tbl_pinjaman_h
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
			} */
			echo "ok";
			 
			// $this->dbasemodel->hapus("checklist_teller WHERE IDCEKTELLER = '". $id ."'");
	 
		}
		else
		{
			echo "error";
		}
	}
	public function confirmtellerangsuran(){
		  
		$id  = $this->input->post('id');
		
		$pecah = explode("#",$id);
		$IDCEKTELLER = $pecah[0];
		$IDPINJ_D = $pecah[1];
		
		$cek = $this->dbasemodel->loadsql("SELECT * FROM checklist_teller WHERE IDCEKTELLER='".$IDCEKTELLER."'");
		 
		if($cek->num_rows()>0)
		{
			$res = $cek->row();
			    
			# ANGSURAN   
			$sql = "SELECT A.*, B.KAS_ID, B.JUMLAH, B.PINJ_RP_ANGSURAN, B.PINJ_TOTAL, B.ANGGOTA_ID, B.PINJ_SISA, 
					B.LUNAS, B.REKENING, B.KODECABANG, C.NAMA NAMA_AGT, D.JENIS_TRANSAKSI
				    FROM tbl_pinjaman_d A 
				    LEFT JOIN tbl_pinjaman_h B ON A.IDPINJAM = B.IDPINJM_H
				    LEFT JOIN m_anggota C ON B.ANGGOTA_ID = C.IDANGGOTA
				    LEFT JOIN jns_akun D ON B.JNS_TRANS = D.IDAKUN
				    WHERE A.UPDATE_DATA = '0000-00-00 00:00:00' 
				    AND DATE(A.TGL_BAYAR)='".$res->TGL_AWAL."' 
				    AND A.STATUS='0' AND A.IDPINJ_D ='".$IDPINJ_D."'";
					
			$query = $this->dbasemodel->loadsql($sql);
			  
			if($query->num_rows() > 0)
			{
				foreach($query->result() as $key) 
				{  
					$hasil = $key->JUMLAH_BAYAR-$key->BASILBAYAR;
					$datatransaksi = array('tgl'        => $key->TGL_BAYAR,
										// 'jumlah'     => $key->JUMLAH_BAYAR, 
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
					 
					$datatransaksi = array('tgl' 		 => $key->TGL_BAYAR,
											'jumlah'     => $key->BIAYA_KOLEKTOR, 
											'keterangan' => 'Pendapatan Admin Kolektor No Rek: '.$key->REKENING . '('. $key->NAMA_AGT .')', 
											'user'       => $key->USERNAME,
											'idkasakun' => $key->KAS_ID,
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
					
					
					$PINJ_DIBAYAR       = ($key->JUMLAH_BAYAR - $key->BIAYA_KOLEKTOR); // MENAMBAHKAN PEMBAYARAN ANGSURAN DI TABLE tbl_pinjaman_h
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
			echo "ok";
			 
			// $this->dbasemodel->hapus("checklist_teller WHERE IDCEKTELLER = '". $id ."'");
	 
		}
		else
		{
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
		
		// $sql =	sprintf("SELECT A.IDAKUN FROM jenis_kas A WHERE A.KODECABANG = '%s' AND A.NAMA_KAS LIKE 'kas reset' LIMIT 1", $kodecabang );
		$sql = "SELECT A.IDAKUN FROM jenis_kas A WHERE A.KODECABANG = '".$kodecabang."' AND A.TMPL_SIMPAN ='Y' LIMIT 1";
		
		$query = $this->dbasemodel->loadSql($sql);
		
		if($query->num_rows() > 0) 
		{
			$row = $query->row();
			return $row->IDAKUN;
		}
		return 0;
	}
	 
	public function detailceklis(){
		  
		$cek = $this->dbasemodel->loadsql("SELECT * FROM checklist_teller WHERE IDCEKTELLER='".$this->uri->segment(2)."'");
		
		if($cek->num_rows()>0)
		{
			$res                      = $cek->row(); 
			$data['opt_data_entries'] =	$this->load->view('_elements/data_entries', NULL, TRUE);
			$data['table_footer']     = $this->load->view('_elements/table_footer', NULL, TRUE);
			$data['PAGE_TITLE']       = "Checklist Setoran";
			$data['page']             = "checklist/checklist_detail";
			$data['tgl']              = $res->TGL_AWAL;
			$data['KODECABANG']       = $res->KODECABANG;
			$data['IDCEKTELLER']      = $this->uri->segment(2);
			  
			$basequery = "SELECT A.ID_TRX_SIMP,DATE_FORMAT(A.TGL_TRX, '%d/%m/%Y')AS TGL, A.JUMLAH, A.NAMA_PENYETOR, B.JENIS_TRANSAKSI, C.NAMA AS NAMACABANG, A.KETERANGAN, A.BUKTI, A.STATUS FROM transaksi_simp A LEFT JOIN jns_akun B ON A.ID_JENIS=B.IDAKUN LEFT JOIN m_cabang C ON A.KODECABANG=C.KODE WHERE A.STATUS='0' AND A.KOLEKTOR='0' AND A.DK='D' AND DATE(A.TGL_TRX)='".$res->TGL_AWAL."' AND A.KODECABANG='".$res->KODECABANG."' ORDER BY A.ID_TRX_SIMP ASC" ;	

			$data['datadetail'] = $this->db->query($basequery)->result();	
			$data['basequery'] = $basequery;	
		
			$this->load->view('dashboard',$data);
		}
		else
		{
			redirect('/cheklist-teller');
		}
		
	}
	public function detailceklisangsuran(){
		  
		$cek = $this->dbasemodel->loadsql("SELECT * FROM checklist_teller WHERE IDCEKTELLER='".$this->uri->segment(2)."'");
		
		if($cek->num_rows()>0)
		{
			$res                      = $cek->row(); 
			$data['opt_data_entries'] =	$this->load->view('_elements/data_entries', NULL, TRUE);
			$data['table_footer']     = $this->load->view('_elements/table_footer', NULL, TRUE);
			$data['PAGE_TITLE']       = "Checklist Angsuran";
			$data['page']             = "checklist/checklist_detailangsuran";
			$data['tgl']              = $res->TGL_AWAL;
			$data['KODECABANG']       = $res->KODECABANG;
			$data['IDCEKTELLER']      = $this->uri->segment(2);
			  
			// $basequery = "SELECT A.ID_TRX_SIMP,DATE_FORMAT(A.TGL_TRX, '%d/%m/%Y')AS TGL, A.JUMLAH, A.NAMA_PENYETOR, B.JENIS_TRANSAKSI, C.NAMA AS NAMACABANG, A.KETERANGAN, A.BUKTI, A.STATUS FROM transaksi_simp A LEFT JOIN jns_akun B ON A.ID_JENIS=B.IDAKUN LEFT JOIN m_cabang C ON A.KODECABANG=C.KODE WHERE A.STATUS='0' AND A.KOLEKTOR='0' AND A.DK='D' AND DATE(A.TGL_TRX)='".$res->TGL_AWAL."' AND A.KODECABANG='".$res->KODECABANG."' ORDER BY A.ID_TRX_SIMP ASC" ;	
			
			$basequery = "SELECT A.IDPINJM_H,D.IDPINJ_D,D.BUKTI,D.STATUS,DATE_FORMAT(A.TGL_PINJ, '%d/%m/%Y') TGL_PINJ, DATE_FORMAT(D.TGL_BAYAR, '%d/%m/%Y') TGL_BAYAR, B.NAMA NAMA_ANGGOTA, FORMAT(A.PINJ_TOTAL,0) TOTAL_TAGIHAN, FORMAT(A.PINJ_RP_ANGSURAN,0) JML_ANGSURANS, FORMAT(D.JUMLAH_BAYAR,0) JML_ANGSURAN, (SELECT FORMAT(((A.PINJ_TOTAL) - (SUM(PD.JUMLAH_BAYAR))), 0) FROM tbl_pinjaman_d PD WHERE A.IDPINJM_H = PD.IDPINJAM AND PD.ANGSURAN_KE <= D.ANGSURAN_KE ) AS SISA_TAGIHAN, D.ANGSURAN_KE, A.LAMA_ANGSURAN, (A.LAMA_ANGSURAN - D.ANGSURAN_KE) SISA_ANGSURAN, E.KODECABANG,F.NAMA AS NAMACABANG FROM tbl_pinjaman_d D LEFT JOIN tbl_pinjaman_h A ON A.IDPINJM_H = D.IDPINJAM LEFT JOIN m_anggota B ON A.ANGGOTA_ID = B.IDANGGOTA LEFT JOIN m_user E ON A.USERNAME = E.USERNAME LEFT JOIN m_cabang F ON A.KODECABANG = F.KODE WHERE 1=1 AND A.KODECABANG = '11' AND DATE(D.TGL_BAYAR)='".$res->TGL_AWAL."' AND A.KODECABANG='".$res->KODECABANG."' AND D.STATUS ='0' ORDER BY D.TGL_BAYAR DESC, A.IDPINJM_H DESC";
			
			$data['datadetail'] = $this->db->query($basequery)->result();	
			$data['basequery'] = $basequery;	
		
			$this->load->view('dashboard',$data);
		}
		else
		{
			redirect('/cheklist-teller');
		}
		
	}
	
	public function detaildata(){
	 
		/*if($this->input->post('tgl'))
		{
			$tgl = date("Y-m-d", strtotime($this->input->post('tgl')));
			$wheretrgl = "AND DATE(TGL_TRX)='".$tgl."'";
		}else{
			$wheretrgl = "AND DATE(TGL_TRX)='".date("Y-m-d")."'";
		}*/
		  
		// $koncabang = ($this->session->userdata('wad_cabang')!="")? " AND A.KODECABANG='".$this->session->userdata('wad_cabang')."'":""; 
		// if($this->session->userdata("wad_level") == "admin")
		// {
			// $koncabang = "";
		// }
		// else
		// {
			// $koncabang = " AND A.KODECABANG='".$this->session->userdata('wad_kodecabang')."'";
		// }
		
		$koncabang = " AND A.KODECABANG='".$this->input->post('kodecabang')."'";
		
		$this->load->model('ModelChecklist');
		// $keyword     = null !== $this->uri->segment(2) ? $this->uri->segment(4) : "";
		$dataPerPage = $this->input->post('dataperpage');
		$page        = $this->input->post('page');
		$dataTable   = $this->ModelChecklist->getDatasimpanan($this->input->post('keyword'), $dataPerPage, $page,$koncabang,$this->input->post('tgl'));

		$data['data'] = $dataTable['data'];
		$data['query'] =$dataTable['query'];
        header('Content-Type: application/json');
		echo json_encode($data);
		die();
	}
	 
	public function migrasi_simpanan(){
		 
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
	
	public function migrasi_angsuran(){
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
	
	public function angsuranke(){
	 
		$cek = 1;
		if($cek > 0)
		{
			
			# ANGSURAN 
			$sql = "SELECT A.*, B.ANGGOTA_ID, B.PINJ_SISA, B.LUNAS
			FROM tbl_pinjaman_d A 
			LEFT JOIN
			tbl_pinjaman_h B ON A.IDPINJAM = B.IDPINJM_H
			LEFT JOIN
			m_anggota C ON B.ANGGOTA_ID = C.IDANGGOTA
			WHERE A.UPDATE_DATA = '0000-00-00 00:00:00's";
			$query = $this->dbasemodel->loadsql($sql);
			
			if($query->num_rows() > 0)
			{
				foreach($query->result() as $key) 
				{
					$sql = sprintf("SELECT ANGSURAN_KE 
					FROM tbl_pinjaman_d
					WHERE IDPINJAM = %s
					ORDER BY ANGSURAN_KE DESC
					LIMIT 1", $key->IDPINJAM);
					
					$query = $this->dbasemodel->loadsql($sql);
					$angsuranke	=	1;
					
					if($query->num_rows() > 0) 
					{
						$row	=	$query->row();
						$angsuranke	=	($row->ANGSURAN_KE + 1);
					}
										 
					$sql = sprintf("UPDATE tbl_pinjaman_d 
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
	
	public function updatesimp(){
	 
		$cek = 1;
		if($cek > 0)
		{
			$sql = "SELECT A.* FROM transaksi_simp A";
			$query = $this->dbasemodel->loadsql($sql);
			
			if($query->num_rows() > 0)
			{
				foreach($query->result() as $key) 
				{
					$idkas = kasteller($key->KODECABANG);
					
					$sql = sprintf("UPDATE transaksi_simp 
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

	function excel(){
		  
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
		
		// $koncabang = ($this->session->userdata('wad_cabang')!="")? " AND A.KODECABANG='".$this->session->userdata('wad_cabang')."'":"";
		if($this->session->userdata("wad_level") == "admin")
		{
			$koncabang = "";
		}
		else
		{
			$koncabang = " AND A.KODECABANG='".$this->session->userdata('wad_kodecabang')."'";
		}
		$cek = $this->dbasemodel->loadsql("SELECT A.IDCEKTELLER,
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