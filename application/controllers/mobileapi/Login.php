<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

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
		if($this->input->post()){
			$kopus  		= substr($this->input->post("user"),0,4);
			$kocab  		= substr($this->input->post("user"),4,2);
			$noanggota  = substr($this->input->post("user"),-4);//$this->input->post("user");
			$pin		= md5($this->input->post('pin'));
			$otherdb 	= $this->load->database('otherdb', TRUE);
			$sql = "SELECT * from m_anggota 
			WHERE NO_ANGGOTA='$noanggota' AND KODEPUSAT='$kopus' AND KODECABANG='$kocab' AND PIN='$pin'";
			//echo $sql;
			$cek  		= $otherdb->query($sql);
			if($cek->num_rows()>0)
			{
				//$random 		= $this->generateRandomString(6);
				$res =  $cek->row();
				
				/*$pesan = $random." adalah kode OTP pertanyaan keamanan anda. - KSP. Wahyu arta sejahtera";
				
				$otherdb->query("UPDATE m_anggota SET OTPCODE='$random' WHERE IDANGGOTA='".$res->IDANGGOTA."'");
				$otherdb->query("INSERT INTO t_outbox (NOMER,PESAN,TANGGAL) VALUES ('".$res->TELP."','".$pesan."','".date("Y-m-d H:i:s")."')");*/
				
				$array = array("code"=>"200",
								"msg"=>"",
								"data"=>array("idanggota"=>$res->IDANGGOTA,
												"kopus"=>$res->KODEPUSAT,
												"kocab"=>$res->KODECABANG,
												"nama"=>$res->NAMA));
				echo json_encode($array);
			}else{
				$array = array("code"=>"404",
								"msg"=>"No Anggota Tidak Terdaftar",
								"data"=>"");
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

}