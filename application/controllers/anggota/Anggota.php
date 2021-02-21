<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'third_party/vendor/autoload.php';    
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
  
class Anggota extends CI_Controller {

	function __construct(){ 
        parent::__construct();
		$this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . 'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-chace');
        $this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");   
		 
		$this->load->database(); 
		$this->load->model('dbasemodel');
		//@session_start();
		if(!is_logged_in()){
			redirect('/auth_login');	
		}
    }
	
	public function index(){
		  
		$data['opt_data_entries'] = $this->load->view('_elements/data_entries', NULL, TRUE);
		$data['table_footer']     = $this->load->view('_elements/table_footer', NULL, TRUE);
        $data['PAGE_TITLE']       = "Data Anggota Aktif";
		$data['page']             = "anggota/anggota";
		
		if($this->session->userdata("wad_level") == "admin")
		{
			$kodecabang = "";
		}
		else
		{
			$kodecabang =" WHERE KODECABANG='".$this->session->userdata('wad_kodecabang')."'";
		}
		
		$data['query'] = $this->dbasemodel->loadsql("SELECT FILE_PIC, IDANGGOTA, NOREK, NAMA, '' AS NAMABANK, JK,
		DATE_FORMAT(TGL_LAHIR, '%d/%m/%Y') AS TGL_LAHIR,
		TIMESTAMPDIFF(YEAR, TGL_LAHIR, CURDATE()) AS USIA,
		ALAMAT, DATE_FORMAT(TGL_DAFTAR, '%d/%m/%Y') AS TGL_DAFTAR, AKTIF
		FROM m_anggota $kodecabang"); 
        
		$this->load->view('dashboard',$data);
    } 
	
	public function dataanggota(){
		 
		$this->load->model('ModelLaporan');
		
		$keyword     = null !== $this->input->post('keyword') ? $this->input->post('keyword') : "";
		$dataPerPage = $this->input->post('dataperpage');
		$page        = $this->input->post('page');
		$dataTable   = $this->ModelLaporan->getDataTableAktif($keyword, $dataPerPage, $page);

        header('Content-Type: application/json');
		echo json_encode($dataTable);
		die();
		 
    }

    public function nonaktif(){
		 
		//echo $_GET['active'];
		$data['opt_data_entries'] = $this->load->view('_elements/data_entries', NULL, TRUE);
		$data['table_footer']     = $this->load->view('_elements/table_footer', NULL, TRUE);
        $data['PAGE_TITLE']       = "Data Anggota Non Aktif";
		$data['page']             = "anggota/anggota_nonaktif";
		
		if($this->session->userdata("wad_level") == "admin")
		{
			$kodecabang = "";
		}
		else
		{
			$kodecabang =" WHERE KODECABANG='".$this->session->userdata('wad_kodecabang')."'";
		}
		
		$data['query'] = $this->dbasemodel->loadsql("SELECT FILE_PIC, IDANGGOTA, NOREK, NAMA, '' AS NAMABANK, JK,
													 DATE_FORMAT(TGL_LAHIR, '%d/%m/%Y') AS TGL_LAHIR,
												     TIMESTAMPDIFF(YEAR, TGL_LAHIR, CURDATE()) AS USIA,
													 ALAMAT, DATE_FORMAT(TGL_DAFTAR, '%d/%m/%Y') AS TGL_DAFTAR, AKTIF
													 FROM m_anggota $kodecabang");
			
        $this->load->view('dashboard',$data);
    } 
	
	public function dataanggotanonaktif(){
	 
		$this->load->model('ModelLaporan');
		$keyword		=	null !== $this->input->post('keyword') ? $this->input->post('keyword') : "";
		$dataPerPage	=	$this->input->post('dataperpage');
		$page			=	$this->input->post('page');
		$dataTable		=	$this->ModelLaporan->getDataTableNonAktif($keyword, $dataPerPage, $page);

        header('Content-Type: application/json');
		echo json_encode($dataTable);
		die();
		
    }

    public function nonanggota(){
		
		 
		//echo $_GET['active'];
		$data['opt_data_entries'] = $this->load->view('_elements/data_entries', NULL, TRUE);
		$data['table_footer']     = $this->load->view('_elements/table_footer', NULL, TRUE);
        $data['PAGE_TITLE']       = "Data Non Anggota";
		$data['page']             = "anggota/non_anggota";
		
		if($this->session->userdata("wad_level") == "admin")
		{
			$kodecabang = "";
		}
		else
		{
			$kodecabang ="WHERE KODECABANG='".$this->session->userdata('wad_kodecabang')."'";
		}
	
		$data['query'] = $this->dbasemodel->loadsql("SELECT FILE_PIC, IDANGGOTA, NOREK, NAMA, '' AS NAMABANK, JK,
		DATE_FORMAT(TGL_LAHIR, '%d/%m/%Y') AS TGL_LAHIR,
		TIMESTAMPDIFF(YEAR, TGL_LAHIR, CURDATE()) AS USIA,
		ALAMAT, EMAIL, DATE_FORMAT(TGL_DAFTAR, '%d/%m/%Y') AS TGL_DAFTAR, AKTIF
		FROM m_anggota $kodecabang");
		
        $this->load->view('dashboard',$data);
    } 

    public function datanonanggota(){
		 
		$this->load->model('ModelLaporan');
		$keyword     = null !== $this->input->post('keyword') ? $this->input->post('keyword') : "";
		$dataPerPage = $this->input->post('dataperpage');
		$page        = $this->input->post('page');
		$dataTable   = $this->ModelLaporan->getDataTableNonAnggota($keyword, $dataPerPage, $page);

        header('Content-Type: application/json');
		echo json_encode($dataTable);
		die();
		
    }

    public function addanggota(){
		 
        $data['PAGE_TITLE'] = "Tambah Anggota";
        $data['page']       = "anggota/editor_anggota";
		$data['action']     = "save-anggota";
		$data['provinsi']   = $this->dbasemodel->loadsql("SELECT * FROM lokasi_provinces ORDER BY name ");
		$data['kota']       = $this->dbasemodel->loadsql("SELECT id_kota id, id_provinsi p, name n FROM lokasi_kota");
		$data['banks']      = $this->dbasemodel->loadsql("Select * FROM t_bank ORDER BY NAMA_BANK ASC");
        $data['js_to_load'] = 'Master/anggota.js';

        $this->load->view('dashboard',$data);
    }
	
	public function kecamatan(){
		$sql = sprintf("SELECT id_kecamatan id, id_kota k, name n FROM lokasi_kecamatan where id_kota = '%s' order by name", $this->input->post('id'));
		$kecamatan = $this->dbasemodel->loadsql($sql);
		echo json_encode($kecamatan->result_array());
	}
	
	public function kelurahan(){
		$sql = sprintf("SELECT id_kelurahan id, id_kecamatan kl, name n FROM lokasi_kelurahan where id_kecamatan = '%s' order by name", $this->input->post('id'));
		$kecamatan = $this->dbasemodel->loadsql($sql);
		echo json_encode($kecamatan->result_array());
	}
	
	public function saveanggota(){
		
		if ($this->input->post('auth') != 'Api#wadsyatem123456') {
			if(!is_logged_in()){
				redirect('/auth_login');	
			}
		}
		 
		$tgllhrString = date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('tgllhr'))));
		$tglregString = date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('tglreg'))));			
		
		if($this->input->post("bank") !="")
		{
			$pbank =  explode("-",$this->input->post("bank"));
			$namabank = $pbank[0];
			$kodebank = $pbank[1];
		}
		else
		{
			$namabank = "";
			$kodebank = "";
		}
		
		$nom = $this->dbasemodel->loadsql("SELECT COALESCE(MAX(NO_ANGGOTA), 0)+1 AS NOMER FROM m_anggota WHERE KODEPUSAT='".$this->session->userdata('wad_kodepusat')."' AND KODECABANG='".$this->session->userdata('wad_kodecabang')."'");
		 
		$rnom  = $nom->row();
		$invID = str_pad($rnom->NOMER, 4, '0', STR_PAD_LEFT);
		$noAgt = $this->session->userdata('wad_kodepusat')."-".$this->session->userdata('wad_kodecabang')."-".$invID;
		// "AKTIF"			=>	$this->input->post('statusaktif'),
		$arrInsert = array(
							"NAMA"             => $this->input->post('nama'),
							"IDENTITAS"        => $this->input->post('identitas'),
							"KODEPUSAT"        => $this->session->userdata('wad_kodepusat'),
							"KODECABANG"       => $this->session->userdata('wad_kodecabang'),
							"NO_ANGGOTA"       => $invID,
							"NO_IDENTITAS"     => $this->input->post('noidentitas'),
							"JK"               => $this->input->post('jk'),
							"TMP_LAHIR"        => $this->input->post('tmptlhr'),
							"TGL_LAHIR"        => $tgllhrString,
							"IBU_KANDUNG"      => $this->input->post('ibu'),
							"USER"             => $this->session->userdata('wad_user'),
							"STATUS"           => $this->input->post('status'),
							"PEKERJAAN"        => $this->input->post('pekerjaan'),
							"NAMA_PEKERJAAN"   => $this->input->post('nama_pekerjaan'),
							"ALAMAT_PEKERJAAN" => $this->input->post('alamat_pekerjaan'),
							"ALAMAT"           => $this->input->post('alamatktp'),
							"ALAMAT_DOMISILI"  => ($this->input->post('gunakanalamatktp') == "" ? $this->input->post('alamatdom') : $this->input->post('alamatktp')),
							"lat"              => $this->input->post('lat'),
							"lng"              => $this->input->post('lng'),
							"AGAMA"            => $this->input->post('agama'),
							"IDPROVINSI"       => $this->input->post('provinsi'),
							"IDKOTA"           => $this->input->post('idkota'),
							"IDKECAMATAN"      => $this->input->post('kecamatan'),
							"IDKELURAHAN"      => $this->input->post('kelurahan'),
							"TELP"             => $this->input->post('telp'),
							"EMAIL"            => $this->input->post('email'),
							"NAMA_SAUDARA"     => $this->input->post('saudara'),
							"TELP_SAUDARA"     => $this->input->post('telpsaudara'),
							"HUB_SAUDARA"      => $this->input->post('hubsaudara'),
							"ALMT_SAUDARA"     => $this->input->post('alamatsaudara'),
							"TGL_DAFTAR"       => $tglregString,//date("Y-m-d"),
							"NOREK"            => $this->input->post('norek'),
							"NOKARTU"          => $this->input->post('nokartu'),
							"KODEBANK"         => $kodebank,
							"NAMA_BANK"        => $namabank,
							"JABATAN"          => $this->input->post('jabatan'),
							"FILE_PIC"         => $this->input->post('filefotowajah'),
							"FILE_NPWP"        => $this->input->post('filefotonpwp'),
							"FILE_KK"          => $this->input->post('filefotokk'),
							"FILE_BK_NKH"      => $this->input->post('filefotobn'),
							"FILE_KTP"         => $this->input->post('filefotoktp')
						);
								 
		$insertProc = $this->dbasemodel->insertDataProc('m_anggota', $arrInsert);
		
		if($insertProc !="")
		{
			$has =  true;
		}
		else
		{
			$has =  false;
		}
		
		if($insertProc)
		{
			$responseArr = array("status"=>200, "msg"=>"");
			
			$getIdKas = $this->dbasemodel->loadsql("SELECT * FROM jenis_kas WHERE TMPL_SIMPAN = 'Y' AND KODECABANG='".$this->session->userdata('wad_kodecabang')."' LIMIT 1")->row();
			  
			$insertsimp = array(
								'ID_ANGGOTA'    => $insertProc,
								'TGL_TRX'       => date("Y-m-d H:i:s"),
								'ID_JENIS'      => "258",
								'JUMLAH'        => "30000",
								'KET_BAYAR'     => "Tabungan",
								'AKUN'          => "Setoran",
								'DK'            => "D",
								// 'ID_KAS'        => kasteller($this->session->userdata('wad_kodecabang')),
								'ID_KAS'        => $getIdKas->ID_JNS_KAS, 
								'ID_KASAKUN'    => $getIdKas->IDAKUN, 
								'USERNAME'      => $this->session->userdata('wad_user'),
								'NAMA_PENYETOR' => $this->input->post('nama'),
								'NO_IDENTITAS'  => $this->input->post('noidentitas'),
								'ALAMAT'        => $this->input->post('alamatktp'),
								'KODEPUSAT'     => $this->session->userdata('wad_kodepusat'),
								'KODECABANG'    => $this->session->userdata('wad_kodecabang'),
								'KOLEKTOR'      => "0",
								'STATUS'        => "0",
								'KETERANGAN'    => 'Setoran awal simpanan pokok '.$this->input->post('nama').' '.$noAgt
						    );
			
			$this->dbasemodel->insertData('transaksi_simp', $insertsimp);
			
			$insertsimp2 = array(
								'ID_ANGGOTA'    => $insertProc,
								'TGL_TRX'       => date("Y-m-d H:i:s"),
								'ID_JENIS'      => "180",
								'JUMLAH'        => "50000",
								'KET_BAYAR'	    => "Tabungan",
								'AKUN'          => "Setoran",
								'DK'            => "D",
								// 'ID_KAS'        => kasteller($this->session->userdata('wad_kodecabang')),
								'ID_KAS'        => $getIdKas->ID_JNS_KAS,
								'ID_KASAKUN'    => $getIdKas->IDAKUN, 
								'USERNAME'      => $this->session->userdata('wad_user'),
								'NAMA_PENYETOR' => $this->input->post('nama'),
								'NO_IDENTITAS'  => $this->input->post('noidentitas'),
								'ALAMAT'        => $this->input->post('alamatktp'),
								'KODEPUSAT'     => $this->session->userdata('wad_kodepusat'),
								'KODECABANG'    => $this->session->userdata('wad_kodecabang'),
								'KOLEKTOR'      => "0",
								'STATUS'        => "0",
								'KETERANGAN'    => 'Setoran awal simpanan mudharabah '.$this->input->post('nama').' '.$noAgt
							);
			$this->dbasemodel->insertData('transaksi_simp', $insertsimp2);
			
			$insertsimp3 = array(
								'ID_ANGGOTA'    => $insertProc,
								'TGL_TRX'       => date("Y-m-d H:i:s"),
								'ID_JENIS'      => "259",
								'JUMLAH'        => "20000",
								'KET_BAYAR'     => "Tabungan",
								'AKUN'          => "Setoran",
								'DK'            => "D",
								// 'ID_KAS'        => kasteller($this->session->userdata('wad_kodecabang')),
								'ID_KAS'        => $getIdKas->ID_JNS_KAS,
								'ID_KASAKUN'    => $getIdKas->IDAKUN, 
								'USERNAME'      => $this->session->userdata('wad_user'),
								'NAMA_PENYETOR' => $this->input->post('nama'),
								'NO_IDENTITAS'  => $this->input->post('noidentitas'),
								'ALAMAT'        => $this->input->post('alamatktp'),
								'KODEPUSAT'     => $this->session->userdata('wad_kodepusat'),
								'KODECABANG'    => $this->session->userdata('wad_kodecabang'),
								'KOLEKTOR'      => "0",
								'STATUS'        => "0",
								'KETERANGAN'    => 'Setoran awal simpanan wajib '.$this->input->post('nama').' '.$noAgt
						    ); 
			$this->dbasemodel->insertData('transaksi_simp', $insertsimp3);
			
			$ceklst = $this->dbasemodel->loadsql("Select * FROM checklist_teller WHERE TGL_AWAL='".date("Y-m-d")."' AND KODEPUSAT='".$this->session->userdata('wad_kodepusat')."' AND KODECABANG='".$this->session->userdata('wad_kodecabang')."'");
			
			if($ceklst->num_rows()>0)
			{
				
				$rchek	= $ceklst->row();
				$nom 	= $rchek->NOMINAL_SIMP+100000;
				$where  = "IDCEKTELLER = '". $rchek->IDCEKTELLER."' ";
				$datacheclist = array("NOMINAL_SIMP"=>$nom, "STATUS" => 0, "APPROVAL" => '');
				$this->dbasemodel->updateData("checklist_teller", $datacheclist, $where);
				
			}
			else
			{
				$datacheclist = array(
									"TGL_AWAL"     => date("Y-m-d"),
									"NOMINAL_SIMP" => "100000",
									"KODEPUSAT"    => $this->session->userdata('wad_kodepusat'),
									"KODECABANG"   => $this->session->userdata('wad_kodecabang')
								);
				$this->dbasemodel->insertData("checklist_teller",$datacheclist);
			}
			
		} 
		else 
		{
			$responseArr = array("status"=>103, "msg"=>"Gagal, data sudah ada sebelumnya. Harap masukkan data lain");
		}
				
		header('Content-Type: application/json');
		echo json_encode($responseArr);
		die();
		
	}

	public function anggotaedit(){
		 
		//var_dump($_POST);
		//die();
		$tgllhrString = date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('tgllhr'))));
		$tglregString = date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('tglreg'))));

		if($this->input->post("bank") !="")
		{
			$pbank    = explode("-",$this->input->post("bank"));
			$namabank = $pbank[0];
			$kodebank = $pbank[1];
		}
		else
		{
			$namabank = "";
			$kodebank = "";
		}

		$arrInsert = array(
						"NAMA"            => $this->input->post('nama'),
						"IDENTITAS"       => $this->input->post('identitas'),
						"NO_IDENTITAS"    => $this->input->post('noidentitas'),
						"JK"              => $this->input->post('jk'),
						"TMP_LAHIR"       => $this->input->post('tmptlhr'),
						"TGL_LAHIR"       => $tgllhrString,
						"IBU_KANDUNG"     => $this->input->post('ibu'),
						"STATUS"          => $this->input->post('status'),
						"PEKERJAAN"       => $this->input->post('pekerjaan'),
						"ALAMAT"          => $this->input->post('alamatktp'),
						"ALAMAT_DOMISILI" => $this->input->post('alamatdom'),
						"lat"             => $this->input->post('lat'),
						"lng"             => $this->input->post('lng'),
						"AGAMA"           => $this->input->post('agama'),
						"IDPROVINSI"      => $this->input->post('provinsi'),
						"IDKOTA"          => $this->input->post('idkota'),
						"IDKECAMATAN"     => $this->input->post('kecamatan'),
						"IDKELURAHAN"     => $this->input->post('kelurahan'),
						"TELP"            => $this->input->post('telp'),
						"EMAIL"           => $this->input->post('email'),
						"NAMA_SAUDARA"    => $this->input->post('saudara'),
						"TELP_SAUDARA"    => $this->input->post('telpsaudara'),
						"HUB_SAUDARA"     => $this->input->post('hubsaudara'),
						"ALMT_SAUDARA"    => $this->input->post('alamatsaudara'),
						"TGL_DAFTAR"      => $tglregString,
						"NOREK"           => $this->input->post('norek'),
						"NOKARTU"         => $this->input->post('nokartu'),
						"KODEBANK"        => $kodebank,
						"NAMA_BANK"       => $namabank,
						"JABATAN"         => $this->input->post('jabatan'),
						"FILE_PIC"        => $this->input->post('filefotowajah'),
						"FILE_NPWP"       => $this->input->post('filefotonpwp'),
						"FILE_KK"         => $this->input->post('filefotokk'),
						"FILE_BK_NKH"     => $this->input->post('filefotobn'),
						"FILE_KTP"        => $this->input->post('filefotoktp')
					);

		$where = "IDANGGOTA=".$this->input->post('idanggota');
		//var_dump($_POST);
		$insertProc = $this->dbasemodel->updateData('m_anggota', $arrInsert,$where);
		if($insertProc)
		{
			$responseArr = array("status"=>200, "msg"=>""); 
		} 
		else 
		{ 
			$responseArr = array("status"=>103, "msg"=>"Gagal, data sudah ada sebelumnya. Harap masukkan data lain");
		}
				
		header('Content-Type: application/json');
		echo json_encode($responseArr);
		die();
	}

    public function editanggota($idanggota){
		if(!is_logged_in()){
			redirect('/auth_login');	
		}
		
		$this->load->model('ModelAnggota');
		$dataDetail					=	$this->ModelAnggota->getDetailAnggota($idanggota);

        $data['PAGE_TITLE']     	= "Ubah Anggota";
		$data['page']           	= "anggota/editor_anggota";
		$data['action']           	= "anggotaedit";
		$data['provinsi']			= $this->dbasemodel->loadsql("SELECT * FROM lokasi_provinces ORDER BY name ");
		$data['kota']				= $this->dbasemodel->loadsql("SELECT id_kota id, id_provinsi p, name n FROM lokasi_kota");
		$data['banks']				= $this->dbasemodel->loadsql("Select * FROM t_bank ORDER BY NAMA_BANK ASC");
        $data['detail']           	= $dataDetail;

        $this->load->view('dashboard',$data);
	}
	
	public function cekKtp(){
		
		$nomer = $this->input->post('nomer');
		$cek = $this->dbasemodel->loadsql("SELECT * FROM m_anggota WHERE NO_IDENTITAS='$nomer'");
		if($cek->num_rows()>0){
			echo "error";
		}else{
			echo "ok";
		}
	}
	
	public function cekHp(){
		$nomer = $this->input->post('nomer');
		$cek = $this->dbasemodel->loadsql("SELECT * FROM m_anggota WHERE TELP='$nomer'");
		if($cek->num_rows()>0){
			echo "error";
		}else{
			echo "ok";
		}
	}
	 
	public function uploadktp(){
		$this->uploadimages();
	}
	
	public function uploadwajah(){
		$this->uploadimages();
	}
	
	public function uploadkk(){
		$this->uploadimages();
	}
	
	public function uploadbn(){
		$this->uploadimages();
	}
	
	public function uploadnpwp(){
		$this->uploadimages();
	}
	
	public function uploadlok(){
		$this->uploadimages();
	}

	public function uploadimages(){
		 
		$mydir = $this->session->userdata('wad_kodepusat')."_".$this->session->userdata('wad_kodecabang')."_".date("Ymd");
		$path  = './uploads/identitas/'.$mydir;
		
		if (!file_exists($path)) {
			mkdir($path, 0755, true);
		}
		
		$config['encrypt_name']  = TRUE;
		$config['upload_path']   = $path."/";
        $config['allowed_types'] = 'gif|jpg|png|jpeg';
		$config['max_size']      = 2000;
		$new_name                = time()."_".$_FILES["music"]['name'];
		$config['file_name']     = $new_name;

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('music')) 
		{
            //$error = array('error' => $this->upload->display_errors());
            echo "99|".$this->upload->display_errors();
        } 
		else 
		{
			$data = array('upload_data' => $this->upload->data());
            echo "00|".$mydir."/".$data['upload_data']['file_name'];
        } 
	}
	
	public function anggota_excel($kodecabang){
		 
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
		 
		// $koncabang = ($this->session->userdata('wad_cabang')!="")? " AND KODECABANG='".$this->session->userdata('wad_cabang')."'":"";
		/* if($this->session->userdata("wad_level") == "admin")
		{
			
			$koncabang = "";
		}
		else
		{
			// $koncabang = " AND A.KODECABANG='".$this->sesssion->userdata('wad_kodecabang')."'";
			
		} */
		$koncabang = " AND KODECABANG='".$kodecabang."'";
		$cek = $this->dbasemodel->loadsql("SELECT IDANGGOTA, NOREK, NAMA,ALAMAT,TMP_LAHIR,KOTA,AGAMA,JK,TELP,IDENTITAS,NO_IDENTITAS,IBU_KANDUNG,
		DATE_FORMAT(TGL_LAHIR, '%d/%m/%Y') AS TGL_LAHIR, TIMESTAMPDIFF(YEAR, TGL_LAHIR, CURDATE()) AS USIA, ALAMAT, DATE_FORMAT(TGL_DAFTAR, '%d/%m/%Y') AS TGL_DAFTAR, AKTIF,KODEPUSAT,(SELECT CONVERT(KODECABANG, CHAR) FROM m_cabang WHERE m_cabang.KODE = m_anggota.KODECABANG) KODECABANGANGGOTA,NO_ANGGOTA FROM m_anggota WHERE 1=1 $koncabang ORDER BY IDANGGOTA");
		
		// echo "SELECT IDANGGOTA, NOREK, NAMA,ALAMAT,TMP_LAHIR,KOTA,AGAMA,JK,TELP,IDENTITAS,NO_IDENTITAS,IBU_KANDUNG,
		// DATE_FORMAT(TGL_LAHIR, '%d/%m/%Y') AS TGL_LAHIR, TIMESTAMPDIFF(YEAR, TGL_LAHIR, CURDATE()) AS USIA, ALAMAT, DATE_FORMAT(TGL_DAFTAR, '%d/%m/%Y') AS TGL_DAFTAR, AKTIF,KODEPUSAT,(SELECT CONVERT(KODECABANG, CHAR) FROM m_cabang WHERE m_cabang.KODE = m_anggota.KODECABANG) KODECABANGANGGOTA,NO_ANGGOTA FROM m_anggota WHERE 1=1 $koncabang ORDER BY IDANGGOTA";
		if($cek->num_rows()>0)
		{
			$row = 2;
			foreach($cek->result() as $item)
			{
				$sheet->setCellValue('A'.$row,$item->KODEPUSAT.".".$item->KODECABANGANGGOTA.".".$item->NO_ANGGOTA);
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