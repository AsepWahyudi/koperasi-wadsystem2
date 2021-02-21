<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Smsgateway extends CI_Controller {
	function __construct(){ 
        parent::__construct();
		$this->load->helper(array('form','url', 'xml','text_helper','date','inflector','app'));
		$this->load->database();
		$this->load->library(array('Pagination','user_agent','session','form_validation'));
		$this->load->model('dbasemodel');
		//@session_start();
    }
	
	public function index()
	{
		$userkey = '0b218589e1b7'; //userkey lihat di zenziva
		$passkey = 'd49631bcbf125442b0407d66'; // set passkey di zenziva

		$url = "https://alpha.zenziva.net/apps/getbalance.php?";
				$curlHandle = curl_init();
				curl_setopt($curlHandle, CURLOPT_URL, $url);
				curl_setopt($curlHandle, CURLOPT_POSTFIELDS, 'userkey='.$userkey.'&passkey='.$passkey);
				curl_setopt($curlHandle, CURLOPT_HEADER, 0);
				curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($curlHandle, CURLOPT_SSL_VERIFYHOST, 2);
				curl_setopt($curlHandle, CURLOPT_SSL_VERIFYPEER, 0);
				curl_setopt($curlHandle, CURLOPT_TIMEOUT,30);
				curl_setopt($curlHandle, CURLOPT_POST, 1);
				$results = curl_exec($curlHandle);
				curl_close($curlHandle);

		$koncabang = ($this->session->userdata('wad_cabang')!="")? " AND KODECABANG='".$this->session->userdata('wad_cabang')."'":"";

		$sql = "SELECT * from m_anggota WHERE AKTIF='Y' $koncabang"; //AND NAMA LIKE '%$cari%' OR NO_ANGGOTA LIKE'%$cari%'
		$cek  		= $this->dbasemodel->loadsql($sql);
		$jmlanggota = $cek->num_rows();

		$hariini = date('Y-m-d');
		$koncabang2 = ($this->session->userdata('wad_cabang')!="")? " AND m_anggota.KODECABANG='".$this->session->userdata('wad_cabang')."'":"";
		$hariini = date('Y-m-d');
		$sql2 = "SELECT * from m_anggota, tbl_pinjaman_h WHERE m_anggota.AKTIF='Y' AND m_anggota.IDANGGOTA = tbl_pinjaman_h.ANGGOTA_ID AND tbl_pinjaman_h.LUNAS = 'Belum' AND DATE(tbl_pinjaman_h.TGL_PINJ) = SUBDATE(SUBDATE('2020-05-13', INTERVAL tbl_pinjaman_h.PERIODE_ANGSURAN MONTH), 3) $koncabang2";

		$cek2  		= $this->dbasemodel->loadsql($sql2);
		$jmlanggota2 = $cek2->num_rows();

		$bulanini = date('m');
		$sql3 = "SELECT * from m_anggota, tbl_pinjaman_h WHERE m_anggota.AKTIF='Y' AND m_anggota.IDANGGOTA = tbl_pinjaman_h.ANGGOTA_ID AND tbl_pinjaman_h.LUNAS = 'Belum' AND MONTH(tbl_pinjaman_h.TGL_PINJ) = ($bulanini - tbl_pinjaman_h.LAMA_ANGSURAN) $koncabang2";

		$cekrisert  		= $this->dbasemodel->loadsql($sql3);
		$agtrisert = $cekrisert->num_rows();

		$data['PAGE_TITLE']     = "SMSGateway";
		$data['page']           = "sms/smsgateway";
		$data['credit']         = $results;
		$data['anggota']        = $jmlanggota;
		$data['tempoh3']        = $jmlanggota2;
		$data['agtrisert']      = $agtrisert;
		$data['outbox']			= $this->dbasemodel->loadsql("SELECT * FROM t_outbox");

		$this->load->view('dashboard', $data);
	} 

	public function kirimsms()
	{     
		$pesanConf3 = "kredit # tgl # total saldo anda Rp. # #mudahkan belanja anda di WAD MART berbagai produk unggulan#";
		
		$pesan = $_POST['pesan'];

		$userkey = '0b218589e1b7'; //userkey lihat di zenziva
		$passkey = 'd49631bcbf125442b0407d66'; // set passkey di zenziva

		$koncabang = ($this->session->userdata('wad_cabang')!="")? " AND KODECABANG='".$this->session->userdata('wad_cabang')."'":"";
		
		$sql = "SELECT * from m_anggota WHERE AKTIF='Y' $koncabang"; //AND NAMA LIKE '%$cari%' OR NO_ANGGOTA LIKE'%$cari%'
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
				    'pesan' => $pesan
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
			echo "<script>alert($status)</script>";

			$save		=	array(
								'PESAN'		=>	$pesan,
							  	'KIRIM'		=>  $cek->num_rows(),
							  	'TANGGAL' 	=>	date('Y-m-d H:i:s'),
							  	'STATUS'	=>	$status,
							  	'TEXT'		=>	$text,
							  	'JENIS'		=>	'PEMBERITAHUAN'
						);
			if($this->dbasemodel->insertData('t_outbox', $save)) {
				redirect('/smsgateway'); 
			}
		}
	} 

	public function kirimsmsh3()
	{     
		$userkey = '0b218589e1b7'; //userkey lihat di zenziva
		$passkey = 'd49631bcbf125442b0407d66'; // set passkey di zenziva

		$koncabang = ($this->session->userdata('wad_cabang')!="")? " AND m_anggota.KODECABANG='".$this->session->userdata('wad_cabang')."'":"";
		$hariini = date('Y-m-d');
		
		$sql = "SELECT * from m_anggota, tbl_pinjaman_h WHERE m_anggota.AKTIF='Y' AND m_anggota.IDANGGOTA = tbl_pinjaman_h.ANGGOTA_ID AND tbl_pinjaman_h.LUNAS = 'Belum' AND DATE(tbl_pinjaman_h.TGL_PINJ) = SUBDATE(SUBDATE('2020-05-13', INTERVAL tbl_pinjaman_h.PERIODE_ANGSURAN MONTH), 3) $koncabang"; //AND NAMA LIKE '%$cari%' OR NO_ANGGOTA LIKE'%$cari%'
		$cek  		= $this->dbasemodel->loadsql($sql);
		$arr = array();
		//var_dump($_POST);
		if($cek->num_rows()>0)
		{
			foreach($cek->result() as $key)
			{
				$pesanConf1 = "H-3 segera lakukan pembayaran angsuran anda sebesar Rp. ".toRp($key->PINJ_RP_ANGSURAN)." melalui cash/transfer";
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
				    'pesan' => $pesanConf1
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
			echo "<script>alert($status)</script>";

			$save		=	array(
								'PESAN'		=>	'H-3 segera lakukan pembayaran angsuran anda sebesar [Rp. ...] melalui cash/transfer',
							  	'KIRIM'		=>  $cek->num_rows(),
							  	'TANGGAL' 	=>	date('Y-m-d H:i:s'),
							  	'STATUS'	=>	$status,
							  	'TEXT'		=>	$text,
							  	'JENIS'		=>	'PEMBERITAHUAN H-3'
						);
			if($this->dbasemodel->insertData('t_outbox', $save)) {
				redirect('/smsgateway'); 
			}
		}
	}

	public function kirimsmsrisert()
	{     
		$userkey = '0b218589e1b7'; //userkey lihat di zenziva
		$passkey = 'd49631bcbf125442b0407d66'; // set passkey di zenziva

		$koncabang = ($this->session->userdata('wad_cabang')!="")? " AND m_anggota.KODECABANG='".$this->session->userdata('wad_cabang')."'":"";
		$hariini = date('Y-m-d');
		
		$sql = "SELECT * from m_anggota, tbl_pinjaman_h WHERE m_anggota.AKTIF='Y' AND m_anggota.IDANGGOTA = tbl_pinjaman_h.ANGGOTA_ID AND tbl_pinjaman_h.LUNAS = 'Belum' AND MONTH(tbl_pinjaman_h.TGL_PINJ) = ($bulanini - tbl_pinjaman_h.LAMA_ANGSURAN) $koncabang"; //AND NAMA LIKE '%$cari%' OR NO_ANGGOTA LIKE'%$cari%'
		$cek  		= $this->dbasemodel->loadsql($sql);
		$arr = array();
		//var_dump($_POST);
		if($cek->num_rows()>0)
		{
			foreach($cek->result() as $key)
			{
				$pesanConf4 = "Segera lakukan pembayaran angsuran anda sebelum jatuh tempo akad risert";
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
				    'userkey' 	=> $userkey,
				    'passkey' 	=> $passkey,
				    'nohp' 		=> $telepon,
				    'pesan' 	=> $pesanConf4
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
			echo "<script>alert($status)</script>";

			$save		=	array(
								'PESAN'		=>	'Segera lakukan pembayaran angsuran anda sebelum jatuh tempo akad risert',
							  	'KIRIM'		=>  $cek->num_rows(),
							  	'TANGGAL' 	=>	date('Y-m-d H:i:s'),
							  	'STATUS'	=>	$status,
							  	'TEXT'		=>	$text,
							  	'JENIS'		=>	'PEMBERITAHUAN RISERT'
						);
			if($this->dbasemodel->insertData('t_outbox', $save)) {
				redirect('/smsgateway'); 
			}
		}
	}

	public function smskirim()
	{     
		$userkey = '0b218589e1b7'; //userkey lihat di zenziva
		$passkey = 'd49631bcbf125442b0407d66'; // set passkey di zenziva

		$hariini = date('Y-m-d');

		$pesan 		= $this->input->post('pesan');
		$telepon 	= $this->input->post('nomer');

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
		}else{
			$status = 'Gagal';
		}
		$text = $results['text'];

		$save		=	array(
							'PESAN'		=>	$pesan,
						  	'KIRIM'		=>  1,
						  	'TANGGAL' 	=>	date('Y-m-d H:i:s'),
						  	'STATUS'	=>	$status,
						  	'TEXT'		=>	$text,
						  	'JENIS'		=>	'PEMBERITAHUAN ANGSURAN'
					);

		if($this->dbasemodel->insertData('t_outbox', $save)) {
			echo $status; 
		}
	}

	public function reset()
	{
		if($this->dbasemodel->hapus('t_outbox')) {
			redirect('/smsgateway'); 
		}
	}
} 