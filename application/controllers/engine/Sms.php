<?
defined('BASEPATH') OR exit('No direct script access allowed');

class Sms extends CI_Controller {
	
	function __construct(){ 
        parent::__construct();
		$this->load->helper(array('form','url', 'xml','text_helper','date','inflector','app'));
		$this->load->database();
		$this->load->library(array('Pagination','user_agent','session','form_validation','session'));
		$this->load->model('dbasemodel');
		//@session_start();
    }
	
	public function index(){
		if($this->input->post())
		{
			$save	=	array(
							'NOMER'		=>	$this->input->post('nomer'),
							'PESAN'		=>	$this->input->post('pesan'),
							'TANGGAL'	=>	date("Y-m-d H:i:s")
   						);
			$this->dbasemodel->insertData('t_outbox', $save);
		}
		echo true;
	}
	public function getsms()
	{
		$arr =  array();
		$cek  = $this->dbasemodel->loadSql("SELECT * FROM t_outbox WHERE KIRIM='0'");
		if($cek->num_rows()>0)
		{
			foreach($cek->result() as $key)
			{
				array_push($arr,array("NOMER"=>$key->NOMER,"PESAN"=>$key->PESAN,"IDSERVER"=>$key->IDOUTBOX));
				
				$where  = "IDOUTBOX = '". $key->IDOUTBOX."' ";
				$datacheclist = array("KIRIM" => 1);
				$this->dbasemodel->updateData("t_outbox", $datacheclist, $where);
			}
			echo json_encode($arr);
		}
	}
}
