<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class produktri extends CI_Controller {

	function __construct(){ 
        parent::__construct();
		header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
		$this->load->helper(array('form','url', 'xml','text_helper','date','inflector'));
		$this->load->database();
		$this->load->library(array('Pagination','user_agent','session','form_validation','session'));
		$this->load->model('mobileapi/dbasemodel');
		//@session_start();
    }
	public function index()
	{
		/*$url = 'https://tripay.co.id/api/v2/pembayaran/produk/';
		$key = APIKEY_TRIPAY;

		$header = array(
		   'Accept: application/json',
		   "Authorization: Bearer $key", // Ganti [apikey] dengan API KEY Anda
		);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		//curl_setopt($ch, CURLOPT_POST, 1);
		$result = curl_exec($ch);

		if(curl_errno($ch)){
		   return 'Request Error:' . curl_error($ch);
		}
		echo $result;
		$json 		= json_decode($result);
		foreach($json->data as $key)
		{
			echo $key->product_name."<br>";
			$datainsert = array('NAMA'=>$key->product_name,
								'KODE'=>$key->code,
								'ADMIN'=>$key->biaya_admin,
								'PEMBAYARAN'=>"1",
								'KATEGORI'=>$key->pembayarankategori_id);
			//$idtrx = $this->dbasemodel->insertTrx("tri_produk",$datainsert);
			
			
			
		}*/
		
		
	}
	
	function proses()
	{
		/*
			SELECT A.*,
        B.OPERATOR,
        C.KATEGORI
        FROM tri_produk A
        LEFT JOIN tri_operator B ON A.OPERATOR=B.IDOPERATOR
        LEFT JOIN tri_kategori C ON A.KATEGORI=C.IDTRI
        WHERE A.OPERATOR='69' 
		*/
		$cek = $this->dbasemodel->loadsql("SELECT * FROM tri_produk WHERE OPERATOR='69' AND PROSES='0' ORDER BY NAMA ASC");
		if($cek->num_rows()>0){
			foreach($cek->result() as $key)
			{
				echo $key->NAMA."<br>";
				
				$datainsert = array('KODE'=>$key->KODE,
								'NAMA'=>$key->NAMA,
								'OPERATOR'=>$key->OPERATOR,
								'KATEGORI'=>"47",
								'HARGA_BELI'=>$key->HARGA);
				$idtrx = $this->dbasemodel->insertData("m_product",$datainsert);
				
				$trxproses 	= array('PROSES'=>"1");
				$wheredepo	= "IDB='".$key->IDB."'";
				$this->dbasemodel->updateData("tri_produk",$trxproses,$wheredepo);
			}
		}
	}
	
	function prosespembayaran()
	{
		/*
			SELECT A.*,
        B.OPERATOR,
        C.KATEGORI
        FROM tri_produk A
        LEFT JOIN tri_operator B ON A.OPERATOR=B.IDOPERATOR
        LEFT JOIN tri_kategori C ON A.KATEGORI=C.IDTRI
        WHERE A.OPERATOR='69' 
		*/
		$cek = $this->dbasemodel->loadsql("SELECT * FROM tri_produk WHERE KATEGORI='37' AND PROSES='0' ORDER BY NAMA ASC");
		if($cek->num_rows()>0){
			foreach($cek->result() as $key)
			{
				echo $key->NAMA."<br>";
				
				$datainsert = array('KODE'=>$key->KODE,
								'NAMA'=>$key->NAMA,
								'OPERATOR'=>$key->OPERATOR,
								'KATEGORI'=>"7",
								'PRDINQ'=>"1",
								'ADMIN'=>$key->ADMIN);
				$idtrx = $this->dbasemodel->insertData("m_product",$datainsert);
				
				$trxproses 	= array('PROSES'=>"1");
				$wheredepo	= "IDB='".$key->IDB."'";
				$this->dbasemodel->updateData("tri_produk",$trxproses,$wheredepo);
			}
		}
	}
}