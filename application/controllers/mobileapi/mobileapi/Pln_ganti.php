<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pln_ganti extends CI_Controller {

	function __construct(){ 
		parent::__construct();
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
		header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
		$this->load->helper(array('form','url', 'xml','text_helper','date','inflector','app'));
		$this->load->database();
		$this->load->library(array('Pagination','user_agent','session','form_validation','session'));
		$this->load->model('mobileapi/dbasemodel');
		//@session_start();
    }
	
	public function index()
	{
		$id =  $this->uri->segment(2);
		$arr = array();
		$cek = $this->dbasemodel->loadsql("SELECT * FROM m_product WHERE KATEGORI='7' ORDER BY IDPRODUK ASC");
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
	
	
}