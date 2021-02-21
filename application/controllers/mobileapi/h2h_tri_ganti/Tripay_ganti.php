<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tripay extends CI_Controller {

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
		//echo "pulsa";
		$arr = array();
		$cek = $this->dbasemodel->loadsql("SELECT * FROM m_kat_prod WHERE PARENT='3' ORDER BY IDKAT ASC");
		if($cek->num_rows()>0){
			foreach($cek->result() as $key)
			{
				array_push($arr, array("id"=>$key->IDKAT,
										"provider"=>$key->KATEGORI,
										"images"=>base_url()."assets/pulsa/".$key->GAMBAR));
			}
			
			$array = array("code"=>"200",
									"msg"=>"",
									"data"=>$arr);
			echo json_encode($array);
		}else{
			$array = array("code"=>"404",
									"msg"=>"Produk tidak ditemukan",
									"data"=>"");
			echo json_encode($array);
		}
	}
	
	function detailpulsa()
	{
		$id =  $this->uri->segment(2);
		$arr = array();
		$cek = $this->dbasemodel->loadsql("SELECT * FROM m_product WHERE KATEGORI='$id' ORDER BY IDPRODUK ASC");
		if($cek->num_rows()>0){
			
			foreach($cek->result() as $key)
			{
				array_push($arr, array("id"=>$key->IDPRODUK,
										"kode"=>$key->KODE,
										"provider"=>$key->NAMA,
										"priceview"=>toRp($key->HARGA_JUAL),
										"price"=>$key->HARGA_JUAL));
			}
			$array = array("code"=>"200",
									"msg"=>"",
									"data"=>$arr);
			echo json_encode($array);
			
		}else{
			$array = array("code"=>"404",
									"msg"=>"Produk tidak ditemukan",
									"data"=>"");
			echo json_encode($array);
		}
		
	}
	
	function trx()
	{
		$noangggota 	= $this->input->post('user');
		$hp 			= $this->input->post('hp');
		$nomertrx		= date("ymdHis");
		$tgl			= date("Y-m-d H:i:s");
		$idprd 			= $this->input->post('idprd');
		$via 			= $this->input->post('via');
		
		//var_dump($_POST);
		
		$cek 		= $this->dbasemodel->loadsql("SELECT * FROM m_product WHERE IDPRODUK='$idprd'");
		if($cek->num_rows()>0){
			$prod 		= $cek->row();
			$kode 		= $prod->KODE;
			$hbeli 		= $prod->HARGA_BELI;
			$hjual 		= $prod->HARGA_JUAL;
			
			$sqls 		= $this->dbasemodel->loadsql("SELECT * FROM m_trx WHERE DATE(TGL) = CURDATE() AND HP='$hp' AND PRODUK='$kode'");
			if($sqls->num_rows()>0){
				
				$array = array("code"=>"404",
									"msg"=>"Transaksi dengan nominal dan nomer yang sama sudah pernah dilakukan, silahkan coba dengan nominal lain",
									"data"=>"");
				echo json_encode($array);
			}else{
				
				$tbtrx	= $sqls->row();
				if($tbtrx->PRDINQ=="0"){
					$datainsert = array('KODE_ANGGOTA'=>$noangggota,
								'TGL'=>$tgl,
								'NOTRX'=>$nomertrx,
								'NOHP'=>$hp,
								'IDPRODUK'=>$idprd,
								'PRODUK'=>$kode,
								'HARGA_BELI'=>$hbeli,
								'HARGA_JUAL'=>$hjual ,
								'PAYMENT_VIA'=>$via);
					$idtrx = $this->dbasemodel->insertTrx("m_trx",$datainsert);
					
					$array = array("code"=>"200",
											"msg"=>"Transaksi Berhasil Di Proses",
											"data"=>"");
					echo json_encode($array);
					$this->pembelian($idtrx);
				}else{
					$datainsert = array('KODE_ANGGOTA'=>$noangggota,
								'TGL'=>$tgl,
								'NOTRX'=>$nomertrx,
								'NOHP'=>$hp,
								'IDPRODUK'=>$idprd,
								'PRODUK'=>$kode,
								'HARGA_BELI'=>$hbeli,
								'PRDINQ'=>"1",
								'HARGA_JUAL'=>$hjual ,
								'PAYMENT_VIA'=>$via);
					$idtrx = $this->dbasemodel->insertTrx("m_trx",$datainsert);
					inqpembayaran($idtrx);
				}
				
				
			}
			
			
		}else{
			$array = array("code"=>"404",
									"msg"=>"Produk tidak ditemukan",
									"data"=>"");
			echo json_encode($array);
		}
		
	}
	
	function pembelian($idtrx)
	{
		$cek 		= $this->dbasemodel->loadsql("SELECT * FROM m_trx WHERE IDTRX='$idtrx'");
		if($cek->num_rows()>0){
			$res 		=  $cek->row();
			
			$key = APIKEY_TRIPAY;
			$url = 'https://tripay.co.id/api/v2/transaksi/pembelian';

			$header = array(
			   'Accept: application/json',
			   "Authorization: Bearer yY4DpFzEQXAmmvvFKU9PlkTiyVKoS94r", // Ganti [apikey] dengan API KEY Anda
			);
			
			$data = array(
			'inquiry' => 'I', // 'PLN' untuk pembelian PLN Prabayar, atau 'I' (i besar) untuk produk lainnya
			'code' => $res->PRODUK, // kode produk
			'phone' => $res->NOHP, // nohp pembeli
			//'no_meter_pln' => '1234567890', // khusus untuk pembelian token PLN prabayar
			'api_trxid' => $res->NOTRX, // ID transaksi dari server Anda. (tidak wajib, maks. 25 karakter)
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
			//echo $result;
			$json 		= json_decode($result);
			
			$success 	= ($json->success=="0")? "3":$json->success;
			$trxid 		= $json->trxid;
			$message 	= $json->message;
			
			$trxproses 	= array('STATUS'=>$success,
								'TRXID'=>$trxid,
								'PROSES'=>"1",
								'LOG'=>$result,
								'MSG'=>$message);
			$wheredepo	= "IDTRX='".$res->IDTRX."'";
			$this->dbasemodel->updateData("m_trx",$trxproses,$wheredepo);
			
		}
	}
		
	function inqpembayaran($idtrx)
	{
		$cek 		= $this->dbasemodel->loadsql("SELECT * FROM m_trx WHERE IDTRX='$idtrx'");
		if($cek->num_rows()>0){
			$res 		=  $cek->row();
			
			$key = APIKEY_TRIPAY;
			$url = 'https://tripay.co.id/api/v2/pembayaran/cek-tagihan';

			$header = array(
			   'Accept: application/json',
			   "Authorization: Bearer yY4DpFzEQXAmmvvFKU9PlkTiyVKoS94r", // Ganti [apikey] dengan API KEY Anda
			);
			
			$data = array(
				'inquiry' => 'I', // 'PLN' untuk pembelian PLN Prabayar, atau 'I' (i besar) untuk produk lainnya
				'code' => $res->PRODUK, // kode produk
				'phone' => $res->NOHP, // nohp pembeli
				'no_pelanggan' => $res->IDPEL, // Masukkan ID Pelanggan (exp: no.meteran/ id pembayaran)
				'api_trxid' => $res->NOTRX, // ID transaksi dari server Anda. (tidak wajib, maks. 25 karakter)
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
			//echo $result;
			$json 		= json_decode($result);
			
			//$success 	= ($json->success=="0")? "3":$json->success;
			$idinq 		= $json->id;
			
			
			$trxproses 	= array('IDINQ'=>$idinq,
								'PROSESINQ'=>"1",
								'LOGINQ'=>$result);
			$wheredepo	= "IDTRX='".$res->IDTRX."'";
			$this->dbasemodel->updateData("m_trx",$trxproses,$wheredepo);
			
			$arrinq = array("NAMA"=>$json->nama,
							"IDPEL"=>$json->no_pelanggan,
							"PERIODE"=>$json->periode,
							"TAGIHAN"=>$json->jumlah_bayar);
			$array = array("code"=>"200",
									"msg"=>"",
									"data"=>$arrinq);
			echo json_encode($array);
			
		}
		
		
	}
}