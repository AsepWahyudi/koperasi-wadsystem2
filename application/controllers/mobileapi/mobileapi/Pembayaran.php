<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pembayaran extends CI_Controller {

	function __construct(){ 
		parent::__construct();
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
		header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
		$this->load->helper(array('form','url', 'xml','text_helper','date','inflector','app','tri'));
		$this->load->database();
		$this->load->library(array('Pagination','user_agent','session','form_validation','session'));
		$this->load->model('mobileapi/dbasemodel');
		//@session_start();
    }
	
	public function index()
	{
		//$id =  $this->uri->segment(2);
		$arr = array();
		$id =  $this->input->post('produk');
		$cek = $this->dbasemodel->loadsql("SELECT * FROM m_product WHERE KATEGORI='$id' ORDER BY IDPRODUK ASC");
		if($cek->num_rows()>0){
			
			foreach($cek->result() as $key)
			{
				array_push($arr, array("id"=>$key->IDPRODUK,
										"kode"=>$key->KODE,
										"provider"=>$key->NAMA,
										"adminview"=>toRp($key->ADMIN),
										"admin"=>$key->ADMIN));
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
	
	function cektagihan()
	{
		$noangggota 	= $this->input->post('user');
		$idpel 			= $this->input->post('idpelanggan');
		$nomertrx		= date("ymdHis");
		$tgl			= date("Y-m-d H:i:s");
		$idprd 			= $this->input->post('idproduk');
		$kode 			= $this->input->post('kode');
		$hp 			= $this->input->post('hp');
		$via 			= $this->input->post('via');
		//var_dump($_POST);
		$ctgl			= date("Y-m-d");
		$cek = $this->dbasemodel->loadsql("SELECT * FROM m_log_inq WHERE IDPEL='$idpel'AND DATE(TGL)='$ctgl'");
		if($cek->num_rows()>0){
			$res = $cek->row();
			//echo $res->LOGINQ;
			if($res->STATUS=="1"){
				$result = $res->LOGINQ;
		
				$json 		= json_decode($result);
				
				$array = array("code"=>"200",
								"msg"=>$res->message,
								"htmldata"=>"Idpel : <b>".$json->data->no_pelanggan."</b>"
								."<br>Nama : <b>".$json->data->nama."</b>"
								."<br>Periode : <b>".$json->data->periode."</b>"
								."<br>Jumlah : <b>Rp.".toRp($json->data->jumlah_tagihan)."</b>"
								,
								"data"=>array("produk"=>$json->data->product_name,
											"nama"=>$json->data->nama,
											"periode"=>$json->data->periode,
											"jumlah"=>$json->data->jumlah_tagihan,
											"admin"=>"2000",
											"idrequest"=>$json->data->id));
				echo json_encode($array);
				
			}else{
				$array = array("code"=>"404",
									"msg"=>$res->MSG,
									"data"=>"");
				echo json_encode($array);
			}
			
			
		}else{
			$datainsert = array('IDUSER'=>$noangggota,
						'TGL'=>$tgl,
						'NOTRX'=>$nomertrx,
						'IDPEL'=>$idpel,
						'NOHP'=>$hp,
						'VIA'=>$via,
						'IDPRODUK'=>$idprd,
						'PRODUK'=>$kode);
			$idtrx = $this->dbasemodel->insertTrx("m_log_inq",$datainsert);
			cektag($idtrx);
		}
		
			
	}
	
	function pelunasan()
	{
		$idrequest 	= $this->input->post('idrequest');
		$cek = $this->dbasemodel->loadsql("SELECT * FROM m_log_inq WHERE IDREQUEST='$idrequest'");
		if($cek->num_rows()>0){
			$res = $cek->row();
			
			$datainsert = array('KODE_ANGGOTA'=>$res->IDUSER,
								'TGL'=>date("Y-m-d H:i:s"),
								'NOTRX'=>$nomertrx,
								'NOHP'=>$res->NOHP,
								'IDPRODUK'=>$res->IDPRODUK,
								'IDPEL'=>$res->IDPEL,
								'IDINQ'=>$idrequest,
								'PRODUK'=>$res->PRODUK,
								'HARGA_BELI'=>$res->JUMLAH_TAGIHAN,
								'HARGA_JUAL'=>($res->JUMLAH_TAGIHAN+$res->ADMIN) ,
								'PAYMENT_VIA'=>$res->VIA);
			$idtrx = $this->dbasemodel->insertTrx("m_trx",$datainsert);
			lunasi($idtrx,$idrequest);
		}
	}
	
	function tesdata()
	{
		$result = '{"success":true,"message":"Pembayaran tersedia.","data":{"tagihan_id":"1694488724","code":"PLNPASCH","product_name":"PLN Pasca Bayar","type":"PLN","phone":"081333387700","no_pelanggan":"513140277732","nama":"KAMSUN","periode":"202002","jumlah_tagihan":258949,"admin":"700","jumlah_bayar":259649,"user_id":7071,"via":"API","status":0,"expired":1,"api_trxid":"200214095251","updated_at":"2020-02-14 09:52:55","created_at":"2020-02-14 09:52:55","id":5066315}}';
		
		$json 		= json_decode($result);
		$array = array("code"=>"200",
							"msg"=>$json->message,
							"htmldata"=>"Idpel : <b>".$json->data->no_pelanggan."</b>"
							."<br>Nama : <b>".$json->data->nama."</b>"
							."<br>Periode : <b>".$json->data->periode."</b>"
							."<br>Jumlah : <b>Rp.".toRp($json->data->jumlah_tagihan)."</b>"
							,
							"data"=>array("produk"=>$json->data->product_name,
										"nama"=>$json->data->nama,
										"periode"=>$json->data->periode,
										"jumlah"=>$json->data->jumlah_tagihan,
										"admin"=>"2000",
										"idrequest"=>$json->data->id));
		echo json_encode($array);
	}
	
	
}