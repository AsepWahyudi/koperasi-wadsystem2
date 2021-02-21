<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Historytrx extends CI_Controller {

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
		/*if(!is_logged_in()){
			redirect('/auth-user');	
		}*/
		if($this->input->post()){
			$noanggota = $this->input->post('user');
			$cek = $this->dbasemodel->loadsql("SELECT A.TGL,
													A.NOTRX,
													A.HARGA_JUAL,
													A.PAYMENT_VIA,
													A.NOTE,
													A.IDPEL,
													A.STATUS,
													A.MSG,
													B.NAMA,
													B.PRDINQ
												FROM m_trx A
												LEFT JOIN m_product B ON A.IDPRODUK=B.IDPRODUK
												WHERE A.KODE_ANGGOTA='$noanggota' 
												ORDER BY A.IDTRX DESC LIMIT 20");
			if($cek->num_rows()>0){
				$arr = array();
				foreach($cek->result() as $key)
				{
					if($key->STATUS=="1")
					{
						if($key->IDPEL !="")
						{
							$msg = $key->NOTE;
						}else{
							$msg = "Transaksi Berhasil";
						}
						
					}else{
						$msg = $key->MSG;
					}
					
					$status		= ($key->STATUS=="1")?"Berhasil":"Gagal";
					
					array_push($arr, array("notrx"=>$key->NOTRX,
											"tgl"=>date('d M Y H:i', strtotime($key->TGL)),
											"via"=>$key->PAYMENT_VIA,
											"produk"=>$key->NAMA,
											"idpel"=>$key->IDPEL,
											"status"=>$status,
											"status_code"=>$key->STATUS,
											"msg"=>$msg,
											"harga"=>toRp($key->HARGA_JUAL)));
				}
				
				$array = array("code"=>"200",
									"msg"=>"",
									"data"=>$arr);
				echo json_encode($array);
			}else{
				$array = array("code"=>"404",
									"msg"=>"Transaksi tidak ditemukan",
									"data"=>"");
				echo json_encode($array);
			}
		}
		
	}
}