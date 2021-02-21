<?
defined('BASEPATH') OR exit('No direct script access allowed');

class Rekening extends CI_Controller {
	
	function __construct(){ 
        parent::__construct();
		$this->load->helper(array('form','url', 'xml','text_helper','date','inflector','app'));
		$this->load->database();
		$this->load->library(array('Pagination','user_agent','session','form_validation','session'));
		$this->load->model('dbasemodel');
		//@session_start();
    }
	
	public function index(){
		
		$cek = $this->dbasemodel->loadsql("SELECT * FROM tbl_pinjaman_h WHERE REKENING IS NULL LIMIT 100");
		if($cek->num_rows()>0)
		{
			foreach($cek->result() as $key)
			{
				//$key->IDPINJM_H
				echo $key->IDPINJM_H."<br>";
				$rekenings =  getRekpinj($key->KODEPUSAT,$key->KODECABANG);
				
				$where  = "IDPINJM_H = '". $key->IDPINJM_H."' ";
				$datacheclist = array("REKENING"=>$rekenings);
				$this->dbasemodel->updateData("tbl_pinjaman_h", $datacheclist, $where);
			}
		}else{
			echo "nodata";
		}
		
	}
	
}
