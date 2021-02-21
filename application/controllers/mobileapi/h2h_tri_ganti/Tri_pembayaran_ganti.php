<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tri_pembayaran extends CI_Controller {

	function __construct(){ 
        parent::__construct();
		header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
		$this->load->helper(array('form','url', 'xml','text_helper','date','inflector','app'));
		$this->load->database();
		$this->load->library(array('Pagination','user_agent','session','form_validation','session'));
		$this->load->model('mobileapi/dbasemodel');
		//@session_start();
    }
	public function index()
	{
		
		$noangggota 	= $this->input->post('user');
		$idpel 			= $this->input->post('idpelanggan');
		$nomertrx		= date("ymdHis");
		$tgl			= date("Y-m-d H:i:s");
		$idprd 			= $this->input->post('idproduk');
		$kode 			= $this->input->post('kode');
		$hp				= "081333387700";
		//$via 			= $this->input->post('via');
		//var_dump($_POST);
	
		
		$datainsert = array('IDUSER'=>$noangggota,
					'TGL'=>$tgl,
					'NOTRX'=>$nomertrx,
					'IDPEL'=>$idpel,
					'NOHP'=>$hp,
					'IDPRODUK'=>$idprd,
					'PRODUK'=>$kode);
		$idtrx = $this->dbasemodel->insertTrx("m_log_inq",$datainsert);
		
		$key = APIKEY_TRIPAY;
		$url = 'https://tripay.co.id/api/v2/pembayaran/cek-tagihan';

		$header = array(
		   'Accept: application/json',
		   "Authorization: Bearer yY4DpFzEQXAmmvvFKU9PlkTiyVKoS94r", // Ganti [apikey] dengan API KEY Anda
		);
		
		$data = array(
			'product' => $kode, // Masukkan ID Produk (exp : PLN)
			'phone' => $hp, // Masukkan No.hp Anda
			'no_pelanggan' => $idpel, // Masukkan ID Pelanggan (exp: no.meteran/ id pembayaran)
			'api_trxid' => $nomertrx, // ID transaksi dari server Anda. (tidak wajib, maks. 25 karakter)
			'pin' => '1825', // pin member
		);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		//curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		$result = curl_exec($ch);
		
		if(curl_errno($ch)){
		   return 'Request Error:' . curl_error($ch);
		}
		echo $result;
		$json 		= json_decode($result);
		if($json->success){
			echo $json
		}else{
			$array = array("code"=>"404",
									"msg"=>$json->message,
									"data"=>"");
			echo json_encode($array);
		}
	}
}