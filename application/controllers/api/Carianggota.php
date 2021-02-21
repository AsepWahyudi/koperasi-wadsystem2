<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Carianggota extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
		header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
		$this->load->helper(array('form', 'url', 'xml', 'text_helper', 'date', 'inflector'));
		$this->load->database();
		$this->load->library(array('Pagination', 'user_agent', 'session', 'form_validation', 'session'));
		$this->load->model('dbasemodel');
		//@session_start();
	}

	public function index()
	{

		if ($this->input->post()) {
			$kopus  = $this->input->post("kopus");
			$kocab  = $this->input->post("kocab");
			$cari  = $this->input->post("cari");
			$search = (is_numeric($cari)) ? substr($cari, -4) : $cari;
			
			
			if($this->session->userdata("wad_level") == "admin")
			{
				$kodecabang ="";
			}
			else
			{
				$kodecabang =" KODEPUSAT='$kopus' AND KODECABANG='$kocab' AND ";
			}
			$sql = "SELECT * from m_anggota WHERE $kodecabang AKTIF='Y' AND NAMA LIKE '%$search%' OR NO_ANGGOTA LIKE'%$search%' LIMIT 20"; //AND NAMA LIKE '%$cari%' OR NO_ANGGOTA LIKE'%$cari%'
			$cek = $this->dbasemodel->loadsql($sql);
			$arr = array();
			//var_dump($_POST);
			if ($cek->num_rows() > 0) 
			{
				foreach ($cek->result() as $key) 
				{
					array_push($arr, array(
						"IDANGGOTA"   => $key->IDANGGOTA,
						"NAMA"        => $key->NAMA,
						"KODEANGGOTA" => $key->KODEPUSAT . "." . $key->KODECABANG . "." . $key->NO_ANGGOTA,
						"KOTA"        => $key->KOTA,
						"IMG"         => base_url() . 'uploads/identitas/' . $key->FILE_PIC
					));
				}
				$array = array(
					"code" => "200",
					"msg"  => "",
					"data" => $arr
				);
				echo json_encode($array);
			} 
			else 
			{
				$array = array(
					"code" => "404",
					"msg"  => "Data titak ditemukan",
					"data" => ""
				);
				echo json_encode($array);
			}
		}
	}

	function login()
	{
		if ($this->input->post()) 
		{
			
			$post     = file_get_contents("php://input");
			$postData = json_decode($post);
			$usra     = trim($postData->email);
			$pwda     = md5($postData->pin_password);
			$arr      = array();
			
			$sql = "SELECT a.*, 
				b.name as `provinsi`, 
				c.name as `kab_kota`, 
				d.name as `kelurahan`, 
				e.name as `kecamatan`, 
				f.name as `posisi` 
				FROM `m_anggota` a 
				LEFT JOIN lokasi_provinces b on a.idprovinsi = b.id_provinsi 
				LEFT JOIN lokasi_kota c  on a.idkota = c.id_kota 
				LEFT JOIN lokasi_kelurahan d on a.idkelurahan = d.id_kelurahan 
				LEFT JOIN lokasi_kecamatan e on a.idkecamatan = e.id_kecamatan 
				LEFT JOIN m_jabatan f on a.jabatan = f.id  
				WHERE a.EMAIL='$usra' AND a.PIN='$pwda'";
				
			$cek = $this->dbasemodel->loadsql($sql);
			
			if ($cek->num_rows() > 0) 
			{
				foreach ($cek->result() as $key) {
					array_push($arr, array(
						"idAnggota"      => $key->IDANGGOTA,
						"nama"           => $key->NAMA,
						"noAnggota"      => $key->NO_ANGGOTA,
						"kodeAnggota"    => $key->KODEPUSAT . "." . $key->KODECABANG . "." . $key->NO_ANGGOTA,
						"kodePusat"      => $key->KODEPUSAT,
						"kodeCabang"     => $key->KODECABANG,
						"tempatLahir"    => $key->TMP_LAHIR,
						"tglLahir"       => $key->TGL_LAHIR,
						"alamat"         => $key->ALAMAT,
						"alamatDomisili" => $key->ALAMAT_DOMISILI,
						"provinsi"       => $key->provinsi,
						"kota"           => $key->kab_kota,
						"kecamatan"      => $key->kecamatan,
						"kelurahan"      => $key->kelurahan,
						"jenisKelamin"   => $key->JK,
						"telp"           => $key->TELP,
						"email"          => $key->EMAIL,
						"status"         => $key->STATUS,
						"jabatan"        => $key->posisi,
						"tglBergabung"   => $key->TGL_DAFTAR,
						"image"          => base_url() . 'uploads/identitas/' . $key->FILE_PIC,
						"aktif"          => $key->AKTIF,
						"pin"            => $key->PIN,
						"newPin"         => "1234"
					));
				}
				$array = array(
					"code" => 200,
					"msg"  => "",
					"data" => $arr[0]
				);
				echo json_encode($array);
			} 
			else 
			{
				$array = array(
					"code" => 404,
					"msg"  => "Anggota Tidak Terdaftar"
				);
				echo json_encode($array);
			}
		}
	}
	
	//Register Anggota Baru via Nasabah Mobile APP
	
	function register()
	{
		if ($this->input->post()) 
		{
			
			$post     = file_get_contents("php://input");
			$postData = json_decode($post);
			
			$nama = trim($postData->nama); 
			$noktp = trim($postData->ktp); 
			$tempat = trim($postData->tempat);
			$tanggal = trim($postData->tanggal);
			$email = trim($postData->email);
			$password = trim($postData->pswd);
			$ibu = trim($postData->ibu);
			$saudara = trim($postData->saudara);
			$alamat = trim($postData->alamat);
			$provinsi_id = trim($postData->provinsi_id);
			$provinsi_nama = trim($postData->provinsi_nama);
			$kota_id = trim($postData->kota_id);
			$kota_nama = trim($postData->kota_nama);
			$kecamatan_id = trim($postData->kecamatan_id);
			$kecamatan_nama = trim($postData->kecamatan_nama);
			$kode_unik = trim($postData->unique_code);
			
			$anggota_id= trim($postData->anggota_id);
			$phone = trim($postData->phone);
			
			$usra     = trim($postData->email);
			$pwda     = md5($postData->pswd);
			
			$strKota = str_replace("KOTA ","",$kota_nama);
			$strKota = str_replace("KABUPATEN ","",$strKota);
			
			$sql =	"SELECT * FROM m_cabang A WHERE A.NAMA LIKE '%$strKota%' OR A.NAMA LIKE '%$provinsi_nama%' OR A.NAMA LIKE '%$kecamatan_nama%'";
			$cekcabang = $this->dbasemodel->loadsql($sql);
			
		    if ($cekcabang->num_rows() > 0)
		    {
		        $cabang	= $cekcabang->row();	
    			$nom = $this->dbasemodel->loadsql("SELECT COALESCE(MAX(NO_ANGGOTA), 0)+1 AS NOMER FROM m_anggota WHERE KODEPUSAT='0001' AND KODECABANG='.$cabang->KODE.'");
    		 
    		    $rnom  = $nom->row();
                $invID = str_pad($rnom->NOMER, 4, '0', STR_PAD_LEFT);
        		$noAgt = "0001-".$cabang->KODE."-".$invID;
                $pass = md5($password);
                $tgllhrString = date('Y-m-d', strtotime(str_replace('/', '-', $tanggal)));
    	        $tglregString = date('Y-m-d H:i:s');
    
                $arrInsert = array(
					"NAMA"             => $nama,
					"IDENTITAS"        => "ktp",
					"KODEPUSAT"        => "0001",
					"KODECABANG"       => $cabang->KODE,
					"NO_ANGGOTA"       => $invID,
					"NO_IDENTITAS"     => $noktp,
					"TMP_LAHIR"        => $tempat,
					"TGL_LAHIR"        => $tgllhrString,
					"IBU_KANDUNG"      => $ibu,
					"USER"             => 'mobile-app',
					"ALAMAT"           => $alamat,
					"ALAMAT_DOMISILI"  => $alamat,
					"NAMA_SAUDARA"     => $saudara,
					"PIN"              => $pass,
					"IDPROVINSI"       => $provinsi_id,
					"IDKOTA"           => $kota_id,
					"IDKECAMATAN"      => $kecamatan_id
				);
    						
    								 
    		    $where = "IDANGGOTA=".$anggota_id;
        		//var_dump($_POST);
        		$insertProc = $this->dbasemodel->updateData('m_anggota', $arrInsert,$where);
    		    
    			if ($insertProc) 
    			{
    			    $nomertrx = date("ymdHis");
            		$tgl = date("Y-m-d H:i:s");
            		$expiredDate = date("Y-m-d H:i:s", strtotime("+1 hour"));
            		$total_bayar = "100".$kode_unik;
            		
            		$sql =	"SELECT * FROM m_rekening_transfer WHERE status = 1";
            		$otherdb = $this->load->database('otherdb', TRUE);
            		$otherdb->query($sql);
        			$ceknorek = $otherdb->query($sql);
			        if ($ceknorek->num_rows() > 0) 
			        {
			            $norek	= $ceknorek->row();
			            $nama_rek = $norek->ATAS_NAMA;
			            $nama_bank = $norek->NAMA_BANK;
			            $no_rek = $norek->NO_REK;
			            $msg = "Silakan lakukan transfer biaya aktifasi sebesar $total_bayar sebelum $expiredDate. ke $no_rek ($nama_bank) a/n $nama_rek";
        			
            			$sql = "INSERT INTO m_trx(KODE_TRX, KODE_ANGGOTA, TGL, NOTRX, PAYMENT_VIA, KODE_UNIK, TOTAL_BAYAR, STATUS_BAYAR, EXPIRED_DATE, PROSES, STATUS, MSG, IDH2H, ID_JENIS_SIMPANAN) 
                        				VALUES (5, '$anggota_id', '$tgl', '$nomertrx', 'transfer', '$kode_unik', '$total_bayar', 0, '$expiredDate', 
                        				0, 0, '$msg', 0, '180')";
                		$otherdb = $this->load->database('otherdb', TRUE);
                		$otherdb->query($sql);
        			    
        			    
        			    $sql = "SELECT a.* FROM `m_anggota` a
            				WHERE EMAIL='$email' AND PIN='$pass'";
        				
        			    $cek = $this->dbasemodel->loadsql($sql);
        			
        			    if ($cek->num_rows() > 0)
        			    {
        			        $key	= $cek->row();
    			            $arr = array(
        						"idAnggota"      => $key->IDANGGOTA,
        						"nama"           => $key->NAMA,
        						"noAnggota"      => $key->NO_ANGGOTA,
        						"kodeAnggota"    => $key->KODEPUSAT . "." . $key->KODECABANG . "." . $key->NO_ANGGOTA,
        						"kodePusat"      => $key->KODEPUSAT,
        						"kodeCabang"     => $key->KODECABANG,
        						"tempatLahir"    => $key->TMP_LAHIR,
        						"tglLahir"       => $key->TGL_LAHIR,
        						"alamat"         => $key->ALAMAT,
        						"alamatDomisili" => $key->ALAMAT_DOMISILI,
        						"provinsi"       => $key->provinsi,
        						"kota"           => $key->kab_kota,
        						"kecamatan"      => $key->kecamatan,
        						"kelurahan"      => $key->kelurahan,
        						"jenisKelamin"   => $key->JK,
        						"telp"           => $key->TELP,
        						"email"          => $key->EMAIL,
        						"status"         => $key->STATUS,
        						"jabatan"        => $key->posisi,
        						"tglBergabung"   => $key->TGL_DAFTAR,
        						"image"          => base_url() . 'uploads/identitas/' . $key->FILE_PIC,
        						"aktif"         => $key->AKTIF,
        						"newPin"         => "1234",
        						"pin"         => $key->PIN
        					);
        					$array = array(
            					"code" => 200,
            					"msg"  => "Silakan lakukan transfer biaya aktifasi sebesar $total_bayar sebelum $expiredDate. ke ",
            					"data" => $arr
            				);
            				echo json_encode($array);
        			    }
        			    else {
        			        $array = array(
            					"code" => 404,
            					"msg"  => "Gagal, data sudah ada sebelumnya. Harap masukkan data lain"
            				);
            				echo json_encode($array);
        			    }
			        }
			        else
			        {
			            $array = array(
            				"code" => 404,
            				"msg"  => "Maaf Rekening Transfer tidak ditemukan, silahkan hubungi kami untuk info lebih lanjut"
            			);
            			echo json_encode($array);
			        }
    				
    			} 
    			else 
    			{
    				$array = array(
    					"code" => 404,
    					"msg"  => "Gagal, data sudah ada sebelumnya. Harap masukkan data lain"
    				);
    				echo json_encode($array);
    			}
		    }
		    else
		    {
		        $array = array(
    				"code" => 404,
    				"msg"  => "Maaf belum tersedia cabang di daerah anda, silahkan hubungi kami untuk info lebih lanjut"
    			);
    			echo json_encode($array);
		    }
		}
	}

	function caripinjaman()
	{
		if ($this->input->post()) {
			$kopus  = $this->input->post("kopus");
			$kocab  = $this->input->post("kocab");
			$cari  = $this->input->post("cari");
			$search = (is_numeric($cari)) ? substr($cari, -4) : $cari;
			
			if($this->session->userdata("wad_level") == "admin")
			{
				$kodecabang ="";
			}
			else
			{
				$kodecabang =" B.KODEPUSAT='$kopus' AND B.KODECABANG='$kocab' AND ";
			}
			
			$sql = "SELECT A.IDPINJM_H,
						B.IDANGGOTA,
						B.NAMA,
						B.NO_ANGGOTA,
						B.KODEPUSAT,
						B.KODECABANG,
						B.KOTA,
						B.FILE_PIC
						FROM tbl_pinjaman_h A
						LEFT JOIN m_anggota B ON A.ANGGOTA_ID=B.IDANGGOTA
						WHERE $kodecabang
						A.LUNAS='Belum'  
						AND AKTIF='Y' 
						AND B.NAMA LIKE '%$search%' OR B.NO_ANGGOTA LIKE'%$search%'
						LIMIT 20"; //AND NAMA LIKE '%$cari%' OR NO_ANGGOTA LIKE'%$cari%'
			$cek  		= $this->dbasemodel->loadsql($sql);
			$arr = array();
			//var_dump($_POST);
			if ($cek->num_rows() > 0) {
				foreach ($cek->result() as $key) {
					array_push($arr, array(
						"IDANGGOTA"   => $key->IDANGGOTA,
						"NAMA"        => $key->NAMA,
						"IDPINJAMAN"  => $key->IDPINJM_H,
						"KODEANGGOTA" => $key->KODEPUSAT . "." . $key->KODECABANG . "." . $key->NO_ANGGOTA,
						"KOTA"        => $key->KOTA,
						"IMG"         => base_url() . 'uploads/identitas/' . $key->FILE_PIC
					));
				}
				$array = array(
					"code" => "200",
					"msg"  => "",
					"data" => $arr
				);
				echo json_encode($array);
			} else {
				$array = array(
					"code" => "404",
					"msg"  => "Data titak ditemukan",
					"data" => ""
				);
				echo json_encode($array);
			}
		}
	}

	function detailanggota()
	{
		//sleep(20);
		//var_dump($_POST);
		if ($this->input->post()) 
		{
			$kopus  = $this->input->post("kopus");
			$kocab  = $this->input->post("kocab");
			$cari  = $this->input->post("cari");
			
			if($this->session->userdata("wad_level") == "admin")
			{
				$kodecabang ="";
			}
			else
			{
				$kodecabang =" KODEPUSAT='$kopus' AND KODECABANG='$kocab' AND ";
			}
			
			$sql = "SELECT * from m_anggota WHERE $kodecabang AKTIF='Y' AND IDANGGOTA='$cari'"; //AND NAMA LIKE '%$cari%' OR NO_ANGGOTA LIKE'%$cari%'
			$cek = $this->dbasemodel->loadsql($sql);
			$arr = array();
			//var_dump($_POST);
			if ($cek->num_rows() > 0) 
			{
				foreach ($cek->result() as $key) {
					array_push($arr, array(
						"IDANGGOTA"   => $key->IDANGGOTA,
						"NAMA"        => $key->NAMA,
						"ALAMAT"      => $key->ALAMAT . "," . $key->KOTA,
						"KODEANGGOTA" => $key->KODEPUSAT . "." . $key->KODECABANG . "." . $key->NO_ANGGOTA,
						"IMG"         => base_url() . 'uploads/identitas/' . $key->FILE_PIC
					));
				}

				$arrjenis = array();
				$jns  		= $this->dbasemodel->loadsql("SELECT * FROM jns_simpan");
				foreach ($jns->result() as $key) {
					array_push($arrjenis, array(
						"IDJENIS" => $key->IDJENIS_SIMP,
						"JENIS"   => $key->JNS_SIMP,
						"JUMLAH"  => $key->JUMLAH
					));
				}

				$array = array(
					"code" => "200",
					"msg"  => "",
					"data" => $arr, "jenis" => $arrjenis
				);
				echo json_encode($array);
			} else {
				$array = array(
					"code" => "404",
					"msg"  => "Data titak ditemukan",
					"data" => ""
				);
				echo json_encode($array);
			}
		}
	}
	
	function reset()
	{
		if ($this->input->post()) 
		{
			
			$post     = file_get_contents("php://input");
			$postData = json_decode($post);
			$id = trim($postData->idAnggota);
			$usra     = trim($postData->email);
			$nama     = $postData->nama;
			$pwda     = $postData->pin_password;
			$reset     = $postData->reset;
			$new_pwda     = md5($postData->new_pin_password);
			
			if ($reset) {
                $pin = $new_pwda;
            }
            else
            {
                $pin = $pwda;
            }

            $where  = "IDANGGOTA = '".$id."' ";
			$dataUpdate = array("EMAIL"=>$usra, "NAMA" => $nama, "PIN" => $pin);
			$this->dbasemodel->updateData("m_anggota", $dataUpdate, $where);
			$sql = "SELECT a.* FROM `m_anggota` a
    				WHERE IDANGGOTA='$id'";
				
		    $cek = $this->dbasemodel->loadsql($sql);
		
		    if ($cek->num_rows() > 0)
		    {
		        $key	= $cek->row();
		        
		        $arr = array(
					"idAnggota"      => $key->IDANGGOTA,
					"nama"           => $key->NAMA,
					"noAnggota"      => $key->NO_ANGGOTA,
					"kodeAnggota"    => $key->KODEPUSAT . "." . $key->KODECABANG . "." . $key->NO_ANGGOTA,
					"kodePusat"      => $key->KODEPUSAT,
					"kodeCabang"     => $key->KODECABANG,
					"tempatLahir"    => $key->TMP_LAHIR,
					"tglLahir"       => $key->TGL_LAHIR,
					"alamat"         => $key->ALAMAT,
					"alamatDomisili" => $key->ALAMAT_DOMISILI,
					"provinsi"       => $key->provinsi,
					"kota"           => $key->kab_kota,
					"kecamatan"      => $key->kecamatan,
					"kelurahan"      => $key->kelurahan,
					"jenisKelamin"   => $key->JK,
					"telp"           => $key->TELP,
					"email"          => $key->EMAIL,
					"status"         => $key->STATUS,
					"jabatan"        => $key->posisi,
					"tglBergabung"   => $key->TGL_DAFTAR,
					"image"          => base_url() . 'uploads/identitas/' . $key->FILE_PIC,
					"aktif"         => $key->AKTIF,
					"newPin"         => "1234",
					"pin"         => $key->PIN
				);
				$array = array(
					"code" => 200,
					"msg"  => "",
					"data" => $arr
				);
				echo json_encode($array);
		    }
		    else 
			{
				$array = array(
					"code" => 404,
					"msg"  => "Gagal, data sudah ada sebelumnya. Harap masukkan data lain"
				);
				echo json_encode($array);
			}
		}
	}
	
	function getCabang()
	{
			$sql = "SELECT a.* FROM `m_cabang` a";
				
		    $cek = $this->dbasemodel->loadsql($sql);
		
		    if ($cek->num_rows() > 0)
		    {
		        $json = array();
		        foreach($cek->result() as $row){
                    $json[] = array(
                        'id'=>$row->IDCABANG,
                        'nama'=> strtoupper($row->NAMA)
                    );
                }
				echo json_encode($json);
		    }
		    else 
			{
				$array = array(
					"code" => 404,
					"msg"  => "Gagal, ambil data"
				);
				echo json_encode($array);
			}
	}
	
	function GenerateOTP()
	{     
	    $post     = file_get_contents("php://input");
		$postData = json_decode($post);
		
		$phone = trim($postData->phone); 
		$email = trim($postData->email); 
		
		$sql = "SELECT *
			FROM `m_anggota` WHERE EMAIL='$email'";
			
		$cek = $this->dbasemodel->loadsql($sql);
		
		if ($cek->num_rows() > 0)
		{
		    $array = array(
					"code" => 404,
					"msg"  => "Email anda sudah terdaftar sebagai nasabah"
				);
				echo json_encode($array);
		}
	    else 
	    {
	        $userkey = '0b218589e1b7'; //userkey lihat di zenziva
    		$passkey = 'd49631bcbf125442b0407d66'; // set passkey di zenziva
    
    		$hariini = date('Y-m-d');
            $random 		= $this->generateRandomString(6);
    		$pesan 		= "Kode verifikasi bersifat RAHASIA. Jangan informasikan ke siapa pun, termasuk pihak Koperasi Wahyu Arta Sejahtera. Gunakan kode berikut untuk melanjutkan registrasi akun Nasbahmu : " . $random;
    		$telepon 	= $phone;
    
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
    		    'userkey' 	=> $userkey,
    		    'passkey' 	=> $passkey,
    		    'nohp' 		=> $telepon,
    		    'pesan' 	=> $pesan
    		));
    		$results = json_decode(curl_exec($curlHandle), true);
    		curl_close($curlHandle);
    
    		//$XMLdata = new SimpleXMLElement($results);
    		if ($results['status'] == '1'){ 
    			$status = 'Terkirim';
    			
		        $expiredDate = date("Y-m-d H:i:s", strtotime("+10 minutes"));
    			
    			$saveOtp =	array(
        			'EMAIL'		    =>	$email,
        		  	'OTP'		    =>  $random,
        		  	'TELP' 	        =>	$telepon,
        		  	'EXPIRED_DATE'  => $expiredDate
    			);
    			$this->dbasemodel->insertData('tbl_pre_register', $saveOtp);
    		}else{
    			$status = 'Gagal';
    		}
    		$text = $results['text'];
    
	        $save =	array(
    			'PESAN'		=>	$pesan,
    		  	'KIRIM'		=>  1,
    		  	'TANGGAL' 	=>	date('Y-m-d H:i:s'),
    		  	'STATUS'	=>	$status,
    		  	'TEXT'		=>	$text,
    		  	'JENIS'		=>	'PEMBERITAHUAN ANGSURAN'
			);
    
    		if($this->dbasemodel->insertData('t_outbox', $save)) {
    			$array = array(
					"code" => 200,
					"msg"  => "Berhasil"
				);
				echo json_encode($array); 
    		}
	    }
		
	}
	
	function generateRandomString($length = 10) {
		$characters = '0123456789';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}
	
	function GetOTP()
	{     
	    $post     = file_get_contents("php://input");
		$postData = json_decode($post);
		
		$otp = trim($postData->otp); 
		$tlp = trim($postData->phone); 
		$email = trim($postData->email); 
		$tglnow = date('Y-m-d H:i:s');
		$sql = "SELECT *
			FROM `tbl_pre_register` WHERE EMAIL='$email' AND OTP = '$otp' AND EXPIRED_DATE >= '$tglnow'";
			
		$cek = $this->dbasemodel->loadsql($sql);
		
		if ($cek->num_rows() > 0)
		{
		    $pwda     = md5("123456");
		    
		    $arrInsert = array(
				"EMAIL"             => $email,
				"TELP"              => $tlp,
				"PIN"               => $pwda,
				"AKTIF"             => "N"
			);
						
								 
		    $insertProc = $this->dbasemodel->insertDataProc('m_anggota', $arrInsert);
		    
			if ($insertProc) 
			{
			    
			    $sql = "SELECT a.* FROM `m_anggota` a
    				WHERE EMAIL='$email' AND PIN='$pwda'";
				
			    $cek = $this->dbasemodel->loadsql($sql);
			
			    if ($cek->num_rows() > 0)
			    {
			        $key	= $cek->row();
			        
			        $arr = array(
						"idAnggota"      => $key->IDANGGOTA,
						"nama"           => $key->NAMA,
						"noAnggota"      => $key->NO_ANGGOTA,
						"kodeAnggota"    => $key->KODEPUSAT . "." . $key->KODECABANG . "." . $key->NO_ANGGOTA,
						"kodePusat"      => $key->KODEPUSAT,
						"kodeCabang"     => $key->KODECABANG,
						"tempatLahir"    => $key->TMP_LAHIR,
						"tglLahir"       => $key->TGL_LAHIR,
						"alamat"         => $key->ALAMAT,
						"alamatDomisili" => $key->ALAMAT_DOMISILI,
						"provinsi"       => $key->provinsi,
						"kota"           => $key->kab_kota,
						"kecamatan"      => $key->kecamatan,
						"kelurahan"      => $key->kelurahan,
						"jenisKelamin"   => $key->JK,
						"telp"           => $key->TELP,
						"email"          => $key->EMAIL,
						"status"         => $key->STATUS,
						"jabatan"        => $key->posisi,
						"tglBergabung"   => $key->TGL_DAFTAR,
						"image"          => base_url() . 'uploads/identitas/' . $key->FILE_PIC,
						"aktif"         => $key->AKTIF,
						"newPin"         => "1234",
						"pin"         => $key->PIN
					);
					$array = array(
    					"code" => 200,
    					"msg"  => "",
    					"data" => $arr
    				);
    				echo json_encode($array);
			    }
			    else {
			        $array = array(
        				"code" => 401,
        				"msg"  => "Gagal, data sudah ada sebelumnya. Harap masukkan data lain"
        			);
        			echo json_encode($array);
			    }
				
				
			} 
			else 
			{
			    $array = array(
    				"code" => 404,
    				"msg"  => "Gagal, data sudah ada sebelumnya. Harap masukkan data lain"
    			);
    			echo json_encode($array);
			}
		}
	    else 
	    {
	       $array = array(
				"code" => 404,
				"msg"  => "OTP tidak valid"
			);
			echo json_encode($array);
	    }
		
	}
	
	function GetProvinsi()
	{
			$sql = "SELECT a.* FROM `lokasi_provinces` a";
				
		    $cek = $this->dbasemodel->loadsql($sql);
		
		    if ($cek->num_rows() > 0)
		    {
		        $json = array();
		        foreach($cek->result() as $row){
                    $json[] = array(
                        'id'=>$row->id_provinsi,
                        'nama'=>$row->name
                    );
                }
				echo json_encode($json);
		    }
		    else 
			{
				$array = array(
					"code" => 404,
					"msg"  => "Gagal, ambil data"
				);
				echo json_encode($array);
			}
	}
	
	function GetKota()
	{
	    $post     = file_get_contents("php://input");
		$postData = json_decode($post);
		
		$provinsi_id = trim($postData->provinsi_id); 
		$sql = "SELECT * FROM `lokasi_kota` where id_provinsi = '$provinsi_id'";
			
	    $cek = $this->dbasemodel->loadsql($sql);
	
	    if ($cek->num_rows() > 0)
	    {
	        $json = array();
	        foreach($cek->result() as $row){
                $json[] = array(
                    'id'=>$row->id_kota,
                    'nama'=>$row->name
                );
            }
			echo json_encode($json);
	    }
	    else 
		{
			$array = array(
				"code" => 404,
				"msg"  => "Gagal, ambil data"
			);
			echo json_encode($array);
		}
	}
	
	function GetKecamatan()
	{
	    $post     = file_get_contents("php://input");
		$postData = json_decode($post);
		
		$id_kota = trim($postData->id_kota); 
		$sql = "SELECT * FROM `lokasi_kecamatan` WHERE `id_kota` = '$id_kota'";
			
	    $cek = $this->dbasemodel->loadsql($sql);
	
	    if ($cek->num_rows() > 0)
	    {
	        $json = array();
	        foreach($cek->result() as $row){
                $json[] = array(
                    'id'=>$row->id_kecamatan,
                    'nama'=>$row->name
                );
            }
			echo json_encode($json);
	    }
	    else 
		{
			$array = array(
				"code" => 404,
				"msg"  => "Gagal, ambil data"
			);
			echo json_encode($array);
		}
	}
	
}
