<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Importanggota extends CI_Controller {

	function __construct(){ 
        parent::__construct();
		$this->load->helper(array('form','url', 'xml','text_helper','date','inflector','app'));
		$this->load->database();
		$this->load->library(array('Pagination','user_agent','session','form_validation','session'));
		$this->load->model('dbasemodel');
		$this->load->model(array('ModelVTransaksi'));
		//@session_start();
		if(!is_logged_in()){
			redirect('/auth_login');	
		}
    }
	
	public function index(){
		    
		$this->load->library('upload');
		$data['judul_browser'] = 'Import Data';
		$data['judul_utama'] = 'Import Data';
		$data['judul_sub'] = 'Anggota <a href="'.site_url('anggota').'" class="btn btn-sm btn-success">Kembali</a>';

		$this->load->helper(array('form'));

		
		if($this->input->post('submit')) {
			
			$config['upload_path']   = FCPATH . 'uploads/temp/';
			$config['allowed_types'] = 'xls|xlsx'; 
			$this->upload->initialize($config);

			if ( ! $this->upload->do_upload('import_anggota')) 
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
        $data['PAGE_TITLE']       = "Import Data Anggota";
		$data['page']             = "masterdata/importanggota";
		$this->load->view('dashboard',$data); 
		
		 
    } 
	function import_db() {
		ini_set('max_execution_time', 123456);
		ini_set("memory_limit","1256M");
		ini_set('max_input_vars', 10000);
		
		$submit = $this->input->post('import');
		$data = $this->input->post('val_arr');
		echo "<pre>";
		echo print_r(print_r($data));
		echo "</pre>";
		if($submit){
			
			$data = $this->input->post("val_arr");
		 
			echo "<pre>";
			echo print_r(print_r($data));
			echo "</pre>";
			 
			
			echo "ada";
		}else{
			echo "tidak";
			 
		}
			
			// $getdata = array();  
			// $setdata = array(); 
			// foreach ($data_import as $rows) { 
				// foreach ($rows as $key => $val) {
					 
					// if($key == 'A') { $setdata['NO_ANGGOTA'] = $val;} 
					// if($key == 'B') { $setdata['TGL_DAFTAR'] = $val; }
					// if($key == 'C') { $setdata['CABANG'] = $val; }
					// if($key == 'D') { $setdata['NAMA'] = $val; }
					// if($key == 'E') { $setdata['TEMPAT_LAHIR'] = $val; }
					// if($key == 'F') { $setdata['TGL_LAHIR'] = $val; }
					// if($key == 'G') { $setdata['ALAMAT_DOMISILI'] = $val; }
					// if($key == 'H') { $setdata['PROVINSI'] = $val; }
					// if($key == 'I') { $setdata['KOTA'] = $val; }
					// if($key == 'J') { $setdata['KECAMATAN'] = $val; }
					// if($key == 'K') { $setdata['KELURAHAN'] = $val; }
					// if($key == 'L') { $setdata['AGAMA'] = $val; }
					// if($key == 'N') { $setdata['JENIS_KLAMIN'] = $val; } 
					// if($key == 'O') { $setdata['TELP'] = $val; }
					// if($key == 'Q') { $setdata['STATUS'] = $val; }
					// if($key == 'R') { $setdata['IDENTITAS'] = $val; }
					// if($key == 'S') { $setdata['NO_IDENTITAS'] = $val; }
					// if($key == 'X') { $setdata['IBU_KANDUNG'] = $val; }
				  
				// }   
				// $getdata[] = $setdata;	
			// }	 
			 
	}
	public function prosesimport()
    { 
        // Load plugin PHPExcel nya
        // include APPPATH.'third_party/PHPExcel/PHPExcel.php';
		require_once APPPATH."/libraries/phpexcel/PHPExcel.php"; 
		 
		$config['upload_path']   = FCPATH . 'uploads/temp/';
        $config['allowed_types'] = 'xlsx|xls|csv';
        $config['max_size']      = '10000';
        $config['encrypt_name']  = true;

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload()) 
		{

            //upload gagal
            $this->session->set_flashdata('notif', '<div class="alert alert-danger"><b>PROSES IMPORT GAGAL!</b> '.$this->upload->display_errors().'</div>');
            //redirect halaman
            redirect('importanggota');

        } 
		else 
		{

            $data_upload = $this->upload->data();

            $excelreader = new PHPExcel_Reader_Excel2007();
            $loadexcel = $excelreader->load('uploads/temp/'.$data_upload['file_name']); // Load file yang telah diupload ke folder excel
            $sheet = $loadexcel->getActiveSheet()->toArray(null, true, true ,true);

            $data = array();

            $numrow = 1;
            foreach($sheet as $row){
			 
				if($numrow > 1){
					 // trim(strip_tags(addslashes(strtoupper($this->input->post("suku", TRUE)))))
					// $NO_ANGGOTA = $row['A'] ; // NO ANGGOTA
					
					$gettgldaftar    = trim(strip_tags($row['B'])) ; // TANGGAL DAFTAR
					$pec = explode("/",$gettgldaftar);
					
					$settgldaftar    = date_create($pec[2]."-".$pec[1]."-".$pec[0]);
					$TGL_DAFTAR      = date_format($settgldaftar,"Y-m-d");
					 
					// $TGL_DAFTAR      = trim(strip_tags($row['B'])) ; 
					$CABANG          = trim(strip_tags(addslashes($row['C']))) ; // CABANG
					$NAMA            = trim(strip_tags(addslashes($row['D']))) ; // NAMA ANGGOTA
					$TMP_LAHIR       = trim(strip_tags(addslashes($row['E']))) ;  // TEMPAT LAHIR
					
					$gettgllahir     = trim(strip_tags($row['F'])) ; // TGL LAHIR
					$pech = explode("/",$gettgllahir);
					
					$ctgllahir       = date_create($pech[2]."-".$pech[1]."-".$pech[0]);
					$TGL_LAHIR       = date_format($ctgllahir,"Y-m-d");
					 
					// $TGL_LAHIR       = trim(strip_tags($row['F'])) ;
					$ALAMAT_DOMISILI = trim(strip_tags(addslashes($row['G']))) ; // ALAMAT DOMISILI
					$IDPROVINSI      = trim(strip_tags(addslashes($row['H']))) ; // PROVINSI
					$IDKOTA          = trim(strip_tags(addslashes($row['I']))) ; // KOTA
					$IDKECAMATAN     = trim(strip_tags(addslashes($row['J']))) ; // KECAMATAN
					$IDKELURAHAN     = trim(strip_tags(addslashes($row['K']))) ; // KELURAHAN
					$AGAMA           = trim(strip_tags(addslashes($row['L']))) ; // AGAMA
					$JK              = trim(strip_tags(addslashes($row['M']))) ; // JENIS_KLAMIN
					$TELP            = trim(strip_tags(addslashes($row['N']))) ; // TELP
					$STATUS          = trim(strip_tags(addslashes($row['O']))) ; // STATUS
					$IDENTITAS       = trim(strip_tags(addslashes($row['P']))) ; // IDENTITAS 
					$NO_IDENTITAS    = trim(strip_tags(addslashes($row['Q']))) ; // NO IDENTITAS
					$IBU_KANDUNG     = trim(strip_tags(addslashes($row['R']))) ; // IBU KANDUNG
					 
					 
					// echo $TGL_DAFTAR."</br>";
					$nom = $this->dbasemodel->loadsql("SELECT COALESCE(MAX(NO_ANGGOTA), 0)+1 AS NOMER FROM m_anggota WHERE KODEPUSAT='".$this->session->userdata('wad_kodepusat')."' AND KODECABANG='".$CABANG."'");

					$rnom  = $nom->row();
					$invID = str_pad($rnom->NOMER, 4, '0', STR_PAD_LEFT);
					
					$getKodecabang = $this->dbasemodel->loadsql("SELECT * FROM m_cabang WHERE KODE='".$CABANG."'")->row();
					
					$noAgt = $this->session->userdata('wad_kodepusat')."-".$getKodecabang->KODECABANG."-".$invID;
					
					$arrInsert = array(
							"NAMA"             => $NAMA,
							"IDENTITAS"        => $IDENTITAS,
							"KODEPUSAT"        => $this->session->userdata('wad_kodepusat'),
							"KODECABANG"       => $CABANG,
							"NO_ANGGOTA"       => $invID,
							"NO_IDENTITAS"     => $NO_IDENTITAS,
							"JK"               => $JK,
							"TMP_LAHIR"        => $TMP_LAHIR,
							"TGL_LAHIR"        => $TGL_LAHIR,
							"IBU_KANDUNG"      => $IBU_KANDUNG,
							"USER"             => $this->session->userdata('wad_user'),
							"STATUS"           => $STATUS, 
							"ALAMAT"           => $ALAMAT_DOMISILI,
							"ALAMAT_DOMISILI"  => $ALAMAT_DOMISILI, 
							"AGAMA"            => $AGAMA,
							"IDPROVINSI"       => $IDPROVINSI,
							"IDKOTA"           => $IDKOTA,
							"IDKECAMATAN"      => $IDKECAMATAN,
							"IDKELURAHAN"      => $IDKELURAHAN,
							"TELP"             => $TELP, 
							"TGL_DAFTAR"       => $TGL_DAFTAR, //date("Y-m-d"), 
							"JABATAN"          => '2', //date("Y-m-d"), 
							"AKTIF"            => 'Y' //date("Y-m-d"), 
						);
								 
					$insertProc = $this->dbasemodel->insertDataProc('m_anggota', $arrInsert);
						
					$getIdKas = $this->dbasemodel->loadsql("SELECT * FROM jenis_kas WHERE TMPL_SIMPAN = 'Y' AND KODECABANG='".$CABANG."' LIMIT 1")->row();
						
					$insertsimp = array(
						'ID_ANGGOTA'    => $insertProc,
						'TGL_TRX'       => date("Y-m-d H:i:s"),
						'ID_JENIS'      => "258",
						'JUMLAH'        => "30000",
						'KET_BAYAR'     => "Tabungan",
						'AKUN'          => "Setoran",
						'DK'            => "D", 
						'ID_KAS'        => $getIdKas->ID_JNS_KAS, 
						'ID_KASAKUN'    => $getIdKas->IDAKUN, 
						'USERNAME'      => $this->session->userdata('wad_user'),
						'NAMA_PENYETOR' => $NAMA,
						'NO_IDENTITAS'  => $NO_IDENTITAS,
						'ALAMAT'        => $ALAMAT_DOMISILI,
						'KODEPUSAT'     => $this->session->userdata('wad_kodepusat'),
						'KODECABANG'    => $CABANG,
						'KOLEKTOR'      => "0",
						'STATUS'        => "0",
						'KETERANGAN'    => 'Setoran awal simpanan pokok '.$NAMA.' '.$noAgt
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
						'ID_KAS'        => $getIdKas->ID_JNS_KAS,
						'ID_KASAKUN'    => $getIdKas->IDAKUN, 
						'USERNAME'      => $this->session->userdata('wad_user'),
						'NAMA_PENYETOR' => $NAMA,
						'NO_IDENTITAS'  => $NO_IDENTITAS,
						'ALAMAT'        => $ALAMAT_DOMISILI,
						'KODEPUSAT'     => $this->session->userdata('wad_kodepusat'),
						'KODECABANG'    => $CABANG,
						'KOLEKTOR'      => "0",
						'STATUS'        => "0",
						'KETERANGAN'    => 'Setoran awal simpanan mudharabah '.$NAMA.' '.$noAgt
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
						'ID_KAS'        => $getIdKas->ID_JNS_KAS,
						'ID_KASAKUN'    => $getIdKas->IDAKUN, 
						'USERNAME'      => $this->session->userdata('wad_user'),
						'NAMA_PENYETOR' => $NAMA,
						'NO_IDENTITAS'  => $NO_IDENTITAS,
						'ALAMAT'        => $ALAMAT_DOMISILI,
						'KODEPUSAT'     => $this->session->userdata('wad_kodepusat'),
						'KODECABANG'    => $CABANG,
						'KOLEKTOR'      => "0",
						'STATUS'        => "0",
						'KETERANGAN'    => 'Setoran awal simpanan wajib '.$NAMA.' '.$noAgt
					); 
					$this->dbasemodel->insertData('transaksi_simp', $insertsimp3);
				 
					
					$sqlcus = "SELECT * FROM transaksi_simp WHERE UPDATE_DATA='0000-00-00 00:00:00' AND DATE(TGL_TRX)='".date("Y-m-d")."' AND STATUS='0' AND KODECABANG='".$CABANG."'";
			
					$cus = $this->dbasemodel->loadsql($sqlcus);
					
					if($cus->num_rows()>0)
					{
						foreach($cus->result() as $key)
						{ 
							// Insert data transaksi simpanan ke jurnal transaksi(table vtransaksi)  
							$datatransaksi = array( 'tgl' => $key->TGL_TRX, 'jumlah' => $key->JUMLAH, 'keterangan' => $key->KETERANGAN, 'ket_dt' => $key->KETERANGAN, 'user' => $key->USERNAME, 'kodecabang' => $key->KODECABANG, 'idkasakun' => $key->ID_KASAKUN);
													
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
				}
				$numrow++;
            
			} 
            unlink(realpath('uploads/temp/'.$data_upload['file_name']));
 
            $this->session->set_flashdata('notif', '<div class="alert alert-success"><b>PROSES IMPORT BERHASIL!</b> Data berhasil diimport!</div>');
            
            redirect('importanggota');

        }
    }
	function import_batal() {
		//hapus semua file di temp
		$files = glob('uploads/temp/*');
		foreach($files as $file){ 
			if(is_file($file)) {
				@unlink($file);
			}
		}
		$this->session->set_flashdata('import', 'BATAL');
		redirect('importanggota');
	}
	 
	
}