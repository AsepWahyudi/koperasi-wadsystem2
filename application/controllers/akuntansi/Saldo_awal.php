<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Saldo_awal extends CI_Controller {

	function __construct(){ 
        parent::__construct();
		$this->load->helper(array('form','url', 'xml','text_helper','date','inflector','app'));
		$this->load->database();
		$this->load->library(array('Pagination','user_agent','session','form_validation','session', 'tree'));
		$this->load->model('dbasemodel');
		if(!is_logged_in()){
			redirect('/auth_login');	
		}
    }
	
	public function index()
	{
		
        $data['PAGE_TITLE'] = "Saldo Awal Perkiraan";
		$data['page']       = "akuntansi/saldo_awal";
		$data['query']      = $this->dbasemodel->loadsql("SELECT A.*  FROM jns_akun A ORDER BY IDAKUN ASC"); 
		$data['header']     = $this->dbasemodel->loadsql("SELECT * FROM jns_akun WHERE HEADER = 1 ");
        $this->load->view('dashboard',$data);
    }
	
	public function save(){
		 
		if($this->input->post())
        {
			$tanggal    = $this->input->post('tgl');
			$idakun     = $this->input->post('idakun');
            $saldo_awal = $this->input->post('saldo_awal');
			
			for($i=0; $i<count($idakun); $i++)
			{
				$save = array('SALDO_AWAL' => str_replace(",", "", $saldo_awal[$i]), 'TANGGAL' => date('Y-m-d', strtotime($tanggal)));
				$this->dbasemodel->updateData("jns_akun", $save, "IDAKUN = '". $idakun[$i]."' ");
			}
        }
		echo json_encode(array('status' => 201, 'msg' => 'Berhasil mengupdate saldo awal perkiraan'));
	}
}