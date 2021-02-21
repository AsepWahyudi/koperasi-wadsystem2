<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class mycallback extends CI_Controller {

	function __construct(){ 
        parent::__construct();
		header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
		$this->load->helper(array('form','url', 'xml','text_helper','date','inflector','app','tri'));
		$this->load->database();
		$this->load->library(array('Pagination','user_agent','session','form_validation','session'));
		$this->load->model('mobileapi/dbasemodel');
		//@session_start();
    }
	
	public function index()
	{
		$json = file_get_contents("php://input");
		
		
		$arrdata = json_decode($json);
		
		if(is_array($arrdata)){
			
			
			//echo $json;
			//var_dump($json);
			//var_dump(json_decode($json, true));
			//echo $json;
			$myFile = "testfile.txt";
			file_put_contents($myFile,$json);
			
			foreach($arrdata as $key){
			//echo $key->trxid;
				$sqls 		= $this->dbasemodel->loadsql("SELECT * FROM m_trx WHERE TRXID='".$key->trxid."'");
				if($sqls->num_rows()>0){
					$row = $sqls->row();
					$trxproses 	= array('STATUS'=>$key->status,
									'TOKEN'=>$key->token,
									'MSG'=>$key->note,
									'NOTE'=>$key->note,
									'CALLBACK'=>$json);
					$wheredepo	= "TRXID='".$key->trxid."'";
					$this->dbasemodel->updateData("m_trx",$trxproses,$wheredepo);
					ekseskusi($row->KODE_ANGGOTA,$row->HARGA_JUAL);
				}
				
			}
		}
		
		
	}
	
}