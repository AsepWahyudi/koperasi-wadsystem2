<?php

require APPPATH.'third_party/vendor/autoload.php';    
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;


defined('BASEPATH') OR exit('No direct script access allowed');

class Sertifikat extends CI_Controller {

	function __construct(){ 
        parent::__construct();
		$this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . 'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-chace');
        $this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");   
		
		
		$this->load->database(); 
		$this->load->model('dbasemodel');
		$this->load->model('modelSertifikat');
		if(!is_logged_in()){
			redirect('/auth_login');	
		}
		//@session_start();
    }
	
	public function index(){
		 
		$data['opt_data_entries']	=	$this->load->view('_elements/data_entries', NULL, TRUE);
		$data['table_footer']		=	$this->load->view('_elements/table_footer', NULL, TRUE);
        $data['PAGE_TITLE']     	=	"Data Jaminan Pinjaman";
		$data['page']           	=	"sertifikat/sertifikat";
		
		
		// $data['query'] =	$this->dbasemodel->loadsql("SELECT FILE_PIC, NOSE, NAMA, '' AS JALUR, LUAS,
		// DATE_FORMAT(TGL_LAHIR, '%d/%m/%Y') AS TGL_LAHIR,
		// TIMESTAMPDIFF(YEAR, TGL_LAHIR, CURDATE()) AS USIA,
		// TAKSIR, DATE_FORMAT(TGL_LAHIR, '%d/%m/%Y') AS TGL_LAHIR, LUAS
		// FROM m_sertifikat");

        $this->load->view('dashboard',$data);
    }
	
	public function datajaminan(){
		 
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
		
		// $active = (@$_GET['active']=="Y")? "AKTIF='Y'":"AKTIF is NULL";
		// $wheretrgl ="AND 1=1 AND $active";
		
		// $koncabang = ($this->session->userdata('wad_cabang')!="")? " AND KODECABANG='".$this->session->userdata('wad_cabang')."'":"";
		$wheretrgl ="";
		$this->load->model('modelSertifikat');
		$keyword		=	null !== $this->input->post('keyword') ? $this->input->post('keyword') : "";
		$dataPerPage	=	$this->input->post('dataperpage');
		$page			=	$this->input->post('page');
		$dataTable		=	$this->modelSertifikat->getDataTable($keyword, $dataPerPage, $page,$koncabang,$wheretrgl);
 
        header('Content-Type: application/json');
		echo json_encode($dataTable);
		die();
		
    }

    function addsertifikat(){
		if(!is_logged_in()){
			redirect('/auth_login');	
		}
        $data['PAGE_TITLE']     	= "Tambah Data";
        $data['page']           	= "sertifikat/editor_sertifikat";
		$data['action']           	= "save-sertifikat";
		$data['provinsi']			= $this->dbasemodel->loadsql("SELECT * FROM lokasi_provinces ORDER BY name ");
		$data['kota']				= $this->dbasemodel->loadsql("SELECT id_kota id, id_provinsi p, name n FROM lokasi_kota");
		
        $data['js_to_load']     	= array('Master/sertifikat.js');

        $this->load->view('dashboard',$data);
    }
	function kecamatan(){
		$sql		=	sprintf("SELECT id_kecamatan id, id_kota k, name n FROM lokasi_kecamatan where id_kota = '%s' order by name", $this->input->post('id'));
		$kecamatan	= $this->dbasemodel->loadsql($sql);
		echo json_encode($kecamatan->result_array());
	}
	function kelurahan(){
		$sql		=	sprintf("SELECT id_kelurahan id, id_kecamatan kl, name n FROM lokasi_kelurahan where id_kecamatan = '%s' order by name", $this->input->post('id'));
		$kecamatan	= 	$this->dbasemodel->loadsql($sql);
		echo json_encode($kecamatan->result_array());
	}
	
	function savesertifikat(){
		  
		$tgllhrString = date("Y-m-d", strtotime($this->input->post('tgllhr')));
		
		
		if($this->input->post("bank") !=""){
			$pbank =  explode("-",$this->input->post("bank"));
			$namabank = $pbank[0];
			$kodebank = $pbank[1];
		}else{
			$namabank = "";
			$kodebank = "";
		}
		$nom = $this->dbasemodel->loadsql("SELECT COALESCE(MAX(NAMA), 0)+1 AS NOMER FROM m_sertifikat WHERE KODEPUSAT='".$this->session->userdata('wad_kodepusat')."' AND KODECABANG='".$this->session->userdata('wad_kodecabang')."'");
		$rnom = $nom->row();
		$invID = str_pad($rnom->NOMER, 4, '0', STR_PAD_LEFT);
		
		// "AKTIF"			=>	$this->input->post('statusaktif'),
		$arrInsert = array("NAMA" => $this->input->post('nama'),
		"NOSE" => $this->input->post('nose'),
		"KODEPUSAT" => $this->session->userdata('wad_kodepusat'),
		"KODECABANG" => $this->session->userdata('wad_kodecabang'), 
		"JALUR" => $this->input->post('jalur'), 
		"TGL_LAHIR" => $tgllhrString,
		"TAKSIR"	=> $this->input->post('taksir'), 
		"LUAS" => $this->input->post('luas'),
		"IDPROVINSI" => $this->input->post('provinsi'),
		"IDKOTA" => $this->input->post('idkota'),
		"IDKECAMATAN" => $this->input->post('kecamatan'),
		"IDKELURAHAN" => $this->input->post('kelurahan'), 
		"FILE_PIC" => $this->input->post('filefotowajah'),                    
		);
								 
		$insertProc = $this->dbasemodel->insertDataProc('m_sertifikat', $arrInsert);
		if($insertProc !=""){
			$has =  true;
		}else{
			$has =  false;
		}
		if($insertProc){
			$responseArr	=	array("status"=>200, "msg"=>"");
			
			$insertsimp = array('ID_ANGGOTA'=> $insertProc,
						'TGL_TRX'	=> date("Y-m-d H:i:s"),
                        'ID_JENIS'	=> "258",
						'JUMLAH'	=> "30000",
						'KET_BAYAR'	=> "Tabungan",
						'AKUN' 		=> "Setoran",
						'DK'		=> "D",
						'ID_KAS'		=> kasteller($this->session->userdata('wad_kodecabang')),
						'USERNAME'		=> $this->session->userdata('wad_user'),
						'NAMA_PENYETOR'		=> $this->input->post('nama'),
						'NO_IDENTITAS'		=> $this->input->post('noidentitas'),
						'ALAMAT'		=> $this->input->post('alamatktp'),
						'KODEPUSAT'		=> $this->session->userdata('wad_kodepusat'),
						'KODECABANG'		=> $this->session->userdata('wad_kodecabang'),
						'KOLEKTOR'		=> "0",
						'STATUS'		=> "0",
						'KETERANGAN'	=> 'Setoran awal simpanan pokok('. $this->input->post('nama') .')');
			$this->dbasemodel->insertData('transaksi_simp', $insertsimp);
			
			$insertsimp2 = array('ID_ANGGOTA'=> $insertProc,
						'TGL_TRX'	=> date("Y-m-d H:i:s"),
						'ID_JENIS'	=> "180",
						'JUMLAH'	=> "50000",
						'KET_BAYAR'	=> "Tabungan",
						'AKUN' 		=> "Setoran",
						'DK'		=> "D",
						'ID_KAS'		=> kasteller($this->session->userdata('wad_kodecabang')),
						'USERNAME'		=> $this->session->userdata('wad_user'),
						'NAMA_PENYETOR'		=> $this->input->post('nama'),
						'NO_IDENTITAS'		=> $this->input->post('noidentitas'),
						'ALAMAT'		=> $this->input->post('alamatktp'),
						'KODEPUSAT'		=> $this->session->userdata('wad_kodepusat'),
						'KODECABANG'		=> $this->session->userdata('wad_kodecabang'),
						'KOLEKTOR'		=> "0",
						'STATUS'		=> "0",
						'KETERANGAN'	=> 'Setoran awal simpanan mudharabah('. $this->input->post('nama') .')');
			$this->dbasemodel->insertData('transaksi_simp', $insertsimp2);
			
			$insertsimp3 = array('ID_ANGGOTA'=> $insertProc,
						'TGL_TRX'	=> date("Y-m-d H:i:s"),
						'ID_JENIS'	=> "259",
						'JUMLAH'	=> "20000",
						'KET_BAYAR'	=> "Tabungan",
						'AKUN' 		=> "Setoran",
						'DK'		=> "D",
						'ID_KAS'		=> kasteller($this->session->userdata('wad_kodecabang')),
						'USERNAME'		=> $this->session->userdata('wad_user'),
						'NAMA_PENYETOR'		=> $this->input->post('nama'),
						'NO_IDENTITAS'		=> $this->input->post('noidentitas'),
						'ALAMAT'		=> $this->input->post('alamatktp'),
						'KODEPUSAT'		=> $this->session->userdata('wad_kodepusat'),
						'KODECABANG'		=> $this->session->userdata('wad_kodecabang'),
						'KOLEKTOR'		=> "0",
						'STATUS'		=> "0",
						'KETERANGAN'	=> 'Setoran awal simpanan wajib('. $this->input->post('nama') .')'); 
			$this->dbasemodel->insertData('transaksi_simp', $insertsimp3);
			
			$ceklst			=	$this->dbasemodel->loadsql("Select * FROM checklist_teller WHERE TGL_AWAL='".date("Y-m-d")."' 
												AND KODEPUSAT='".$this->session->userdata('wad_kodepusat')."'
												AND KODECABANG='".$this->session->userdata('wad_kodecabang')."'");
			if($ceklst->num_rows()>0)
			{
				
				$rchek	= $ceklst->row();
				$nom 	= $rchek->NOMINAL_SIMP+100000;
				$where  = "IDCEKTELLER = '". $rchek->IDCEKTELLER."' ";
				$datacheclist = array("NOMINAL_SIMP"=>$nom, "STATUS" => 0, "APPROVAL" => '', 'BUKTI' => '');
				$this->dbasemodel->updateData("checklist_teller", $datacheclist, $where);
				
			}else{
				$datacheclist = array("TGL_AWAL"=>date("Y-m-d"),
									"NOMINAL_SIMP"=>"100000",
									"KODEPUSAT"=>$this->session->userdata('wad_kodepusat'),
									"KODECABANG"=>$this->session->userdata('wad_kodecabang'));
				$this->dbasemodel->insertData("checklist_teller",$datacheclist);
			}
			
		} else {
			$responseArr	=	array("status"=>103, "msg"=>"Gagal, data sudah ada sebelumnya. Harap masukkan data lain");
		}
				
		header('Content-Type: application/json');
		echo json_encode($responseArr);
		die();
		
	}

	function sertifikatedit() {
		 
		//var_dump($_POST);
		//die();
		$tgllhrString	=	date("Y-m-d", strtotime($this->input->post('tgllhr')));
		

		if($this->input->post("bank") !=""){
			$pbank =  explode("-",$this->input->post("bank"));
			$namabank = $pbank[0];
			$kodebank = $pbank[1];
		}else{
			$namabank = "";
			$kodebank = "";
		}

		$arrInsert		=	array("NAMA"			=>	$this->input->post('nama'),
								   "NOSE"		=>	$this->input->post('nose'),
								   "KODEPUSAT"		=>	$this->session->userdata('wad_kodepusat'),
								   "KODECABANG"		=>	$this->session->userdata('wad_kodecabang'),
								  
								   
								   "JALUR"				=>	$this->input->post('jalur'),
								  
								   "TGL_LAHIR"		=>	$tgllhrString,
								   "TAKSIR"	=>	$this->input->post('taksir'),
								   
								   "LUAS"			=>	$this->input->post('luas'),
								   "IDPROVINSI"		=>	$this->input->post('provinsi'),
								   "IDKOTA"			=>	$this->input->post('idkota'),
								   "IDKECAMATAN"	=>	$this->input->post('kecamatan'),
								   "IDKELURAHAN"	=>	$this->input->post('kelurahan'),
								   
								  
								   
								   
								   "FILE_PIC"			=>	$this->input->post('filefotowajah'),
                                                                   
								 );

		$where 				= 	"NOSE=".$this->input->post('nose');
		//var_dump($_POST);
		$insertProc			=	$this->dbasemodel->updateData('m_sertifikat', $arrInsert,$where);
		if($insertProc){
			$responseArr	=	array("status"=>200, "msg"=>"");
		} else {
			$responseArr	=	array("status"=>103, "msg"=>"Gagal, data sudah ada sebelumnya. Harap masukkan data lain");
		}
				
		header('Content-Type: application/json');
		echo json_encode($responseArr);
		die();
	}
	
	function editjaminanpinjaman($idanggunan){
		  
	  
	  echo $idanggunan;
        $data['PAGE_TITLE'] = "Ubah Data Anggunan";
		$data['page']       = "sertifikat/editor_anggunan";
		$data['action']     = "anggunanedit";
 
        $data['dataanggunan'] = $this->dbasemodel->loadsql("Select * FROM m_anggunan WHERE IDANGGUNAN ='".$idanggunan."'")->row();
        
		$dataanggunan = $this->dbasemodel->loadsql("Select * FROM m_anggunan WHERE IDANGGUNAN ='".$idanggunan."'")->row();
		
		$data['datapinjaman'] = $this->dbasemodel->loadsql("Select * FROM tbl_pinjaman_h WHERE IDPINJM_H ='".$dataanggunan->IDPINJM_H."'")->row();
		
		$datapinjaman = $this->dbasemodel->loadsql("Select * FROM tbl_pinjaman_h WHERE IDPINJM_H ='".$dataanggunan->IDPINJM_H."'")->row();
		$data['datajaminan'] = $this->dbasemodel->loadsql("Select * FROM jns_jaminan WHERE IDJAMINAN ='".$datapinjaman->JENIS_JAMINAN."'")->row();
		
		$data['selectjaminan'] = $this->dbasemodel->loadsql("Select * FROM jns_jaminan")->result();
		$data['dataanggota'] = $this->dbasemodel->loadsql("Select * FROM m_anggota WHERE IDANGGOTA ='".$datapinjaman->ANGGOTA_ID."'")->row();
		
		

        $this->load->view('dashboard',$data);
	}
	
    function editsertifikat($idsertifikat){
		 
		$this->load->model('modelSertifikat');
		$dataDetail					=	$this->ModelSetifikat->getDetailSetifikat($idsertifikat);

        $data['PAGE_TITLE']     	= "Ubah Anggota";
		$data['page']           	= "sertifikat/editor_sertifikat";
		$data['action']           	= "sertifikatedit";
		$data['provinsi']			= $this->dbasemodel->loadsql("SELECT * FROM lokasi_provinces ORDER BY name ");
		$data['kota']				= $this->dbasemodel->loadsql("SELECT id_kota id, id_provinsi p, name n FROM lokasi_kota");
		$data['jalur']				= $this->dbasemodel->loadsql("Select * FROM t_jalur ORDER BY NAMA_JALUR ASC");
        $data['detail']           	= $dataDetail;

        $this->load->view('dashboard',$data);
	}
	
	public function save() {
		
		// echo print_r($_POST);
		// Array ( [IDANGGUNAN] => 1 [IDPINJM_H] => 1 [tgl_trx] => 24/11/2020 [id_jenis] => 1 [no_jaminan] => 2423424 [status] => Sudah Masuk Berkas ) 1 
		if($this->input->post())
		{
			//var_dump($_POST);
			$mydir	= $this->session->userdata('wad_kodepusat')."_".$this->session->userdata('wad_kodecabang')."_".date("Ymd");
			$path 	= './uploads/bukti/'.$mydir;
			if (!file_exists($path)) {
				mkdir($path, 0755, true);
			}
			$id 						= $this->input->post("IDANGGUNAN").$this->input->post("IDPINJM_H");
			//$config['encrypt_name'] 	= TRUE;
			$config['upload_path'] 		= $path.'/';
			$config['allowed_types'] 	= '*';
			$config['max_size'] 		= 2000;
			$new_name 					= time()."_".$_FILES["file"]['name'];
			$config['file_name'] 		= $new_name;

			$this->load->library('upload', $config);

			if (!$this->upload->do_upload("file")) {
				$error = array('error' => $this->upload->display_errors());
				// echo "99|".$this->upload->display_errors();
				redirect('sertifikat');
			} else {
				$data = array('upload_data' => $this->upload->data());
				// 24/11/2020 [
				$gettgl_trx = explode("/",$this->input->post("tgl_trx"));
				$tgl_trx = $gettgl_trx[2].'-'.$gettgl_trx[1].'-'.$gettgl_trx[0].' '.date('H:i:s');
				
				$where  = "IDANGGUNAN = '".$this->input->post("IDANGGUNAN")."' ";
				$dataanggunan = array("FILE"=>$mydir."/".$data['upload_data']['file_name'], "DATE_UPDATE" => $tgl_trx, "STATUS" => $this->input->post("status"), 'USR_INPT_BRKS' => $this->session->userdata('wad_user'));
				$this->dbasemodel->updateData("m_anggunan", $dataanggunan, $where);
				 
				$wherep  = "IDPINJM_H = '".$this->input->post("IDPINJM_H")."' ";
				$datapinjaman = array( "JENIS_JAMINAN" => $this->input->post("id_jenis"), "NO_JAMINAN" => $this->input->post("no_jaminan"));
				$this->dbasemodel->updateData("tbl_pinjaman_h", $datapinjaman, $wherep);
				
				redirect('sertifikat');
			} 
		} 
		
	}
	public function downloadanggunan($idanggunan){		
		$this->load->helper(array('url','download'));	
		
		$data = $this->dbasemodel->loadsql("Select * FROM m_anggunan WHERE IDANGGUNAN ='".$idanggunan."'")->row();
		
		force_download('uploads/bukti/'.$data->FILE, NULL);
	}	
	function cekKtp() {
		$nomer = $this->input->post('nomer');
		$cek = $this->dbasemodel->loadsql("SELECT * FROM m_anggota WHERE NO_IDENTITAS='$nomer'");
		if($cek->num_rows()>0){
			echo "error";
		}else{
			echo "ok";
		}
	}
	
	function cekHp() {
		$nomer = $this->input->post('nomer');
		$cek = $this->dbasemodel->loadsql("SELECT * FROM m_anggota WHERE TELP='$nomer'");
		if($cek->num_rows()>0){
			echo "error";
		}else{
			echo "ok";
		}
	} 
	function uploadktp() {
		$this->uploadimages();
	}
	function uploadwajah() {
		$this->uploadimages();
	}
	function uploadkk() {
		$this->uploadimages();
	}
	function uploadbn() {
		$this->uploadimages();
	}
	function uploadnpwp() {
		$this->uploadimages();
	}
	function uploadln() {
		$this->uploadimages();
	} 
	function uploadimages() {
		if(!is_logged_in()){
			redirect('/auth_login');	
		}
		
		$mydir	= $this->session->userdata('wad_kodepusat')."_".$this->session->userdata('wad_kodecabang')."_".date("Ymd");
		$path 	= './uploads/identitas/'.$mydir;
		if (!file_exists($path)) {
			mkdir($path, 0755, true);
		}
		
		$config['encrypt_name'] = TRUE;
		$config['upload_path'] = $path."/";
        $config['allowed_types'] = 'gif|jpg|png|jpeg';
		$config['max_size'] = 2000;
		$new_name = time()."_".$_FILES["music"]['name'];
		$config['file_name'] = $new_name;

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('music')) {
            //$error = array('error' => $this->upload->display_errors());
            echo "99|".$this->upload->display_errors();
        } else {
			$data = array('upload_data' => $this->upload->data());
            echo "00|".$mydir."/".$data['upload_data']['file_name'];
        }

	} 
	public function anggota_excel() {
		if(!is_logged_in()){
			redirect('/auth_login');	
		}
		
		$spreadsheet = new Spreadsheet();
		$spreadsheet->getActiveSheet()->setTitle('Data Anggota');
		$sheet = $spreadsheet->getActiveSheet();
		
		$sheet->setCellValue('A1', 'NO ANGGOTA');
		$sheet->setCellValue('B1', 'NAMA');
		$sheet->setCellValue('C1', 'ALAMAT');
		$sheet->setCellValue('D1', 'KOTA');
		$sheet->setCellValue('E1', 'TEMPAT LAHIR');
		$sheet->setCellValue('F1', 'TANGGAL LAHIR');
		$sheet->setCellValue('G1', 'JENIS KELAMIN');
		$sheet->setCellValue('H1', 'AGAMA');
		$sheet->setCellValue('I1', 'IDENTITAS');
		$sheet->setCellValue('J1', 'NO IDENTITAS');
		$sheet->setCellValue('K1', 'IBU KANDUNG');
		$sheet->setCellValue('L1', 'TANGGAL DAFTAR');
		
		foreach(range('A','L') as $columnID)
		{
			$sheet->getColumnDimension($columnID)->setAutoSize(true);
		}
		
		$sheet->getStyle('A1:L1')->applyFromArray(
		   array(
			  'font'  => array(
				  'bold'  =>  true
			  )
		   )
		); 
		$koncabang = ($this->session->userdata('wad_cabang')!="")? " AND KODECABANG='".$this->session->userdata('wad_cabang')."'":"";
		$cek = $this->dbasemodel->loadsql("SELECT IDANGGOTA, NOREK, NAMA,ALAMAT,TMP_LAHIR,KOTA,AGAMA,JK,TELP,IDENTITAS,NO_IDENTITAS,IBU_KANDUNG,
										DATE_FORMAT(TGL_LAHIR, '%d/%m/%Y') AS TGL_LAHIR,
										TIMESTAMPDIFF(YEAR, TGL_LAHIR, CURDATE()) AS USIA,
										ALAMAT, DATE_FORMAT(TGL_DAFTAR, '%d/%m/%Y') AS TGL_DAFTAR, AKTIF,KODEPUSAT,KODECABANG,NO_ANGGOTA
								 FROM m_sertifkat
								 WHERE 1=1 $koncabang
								 ORDER BY IDANGGOTA");
								 
		if($cek->num_rows()>0)
		{
			$row = 2;
			foreach($cek->result() as $item)
			{
				$sheet->setCellValue('A'.$row,$item->KODEPUSAT.".".$item->KODECABANG.".".$item->NO_ANGGOTA);
				$sheet->setCellValue('B'.$row,$item->NAMA);
				$sheet->setCellValue('C'.$row,$item->ALAMAT);
				$sheet->setCellValue('D'.$row,$item->KOTA);
				$sheet->setCellValue('E'.$row,$item->TMP_LAHIR);
				$sheet->setCellValue('F'.$row,$item->TGL_LAHIR);
				$sheet->setCellValue('G'.$row,$item->JK);
				$sheet->setCellValue('H'.$row,$item->AGAMA);
				$sheet->setCellValue('I'.$row,$item->IDENTITAS);
				$sheet->setCellValue('J'.$row,$item->NO_IDENTITAS);
				$sheet->getStyle('J'.$row)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER);
				$sheet->setCellValue('K'.$row,$item->IBU_KANDUNG);
				$sheet->setCellValue('L'.$row,$item->TGL_DAFTAR);
				$row++;
			}
		}
		
		$writer = new Xlsx($spreadsheet);
		$file = "dataanggota_".date("ymdHis").".xlsx";
		$writer->save('export/'.$file);
		redirect(base_url().'export/'.$file);
	}
	 
}