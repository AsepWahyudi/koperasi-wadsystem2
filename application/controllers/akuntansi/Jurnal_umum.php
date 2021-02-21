<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jurnal_umum extends CI_Controller {

	function __construct(){ 
        parent::__construct();
		$this->load->helper(array('form','url', 'xml','text_helper','date','inflector','app'));
		$this->load->database();
		$this->load->library(array('Pagination','user_agent','session','form_validation','session', 'tree'));
		$this->load->model(array('dbasemodel', 'ModelVTransaksi'));
		if(!is_logged_in()){
			redirect('/auth_login');	
		}
    }
	
	public function index()
	{
		
        $data['PAGE_TITLE'] = "Pencatatan Jurnal Umum";
		$data['page']       = "akuntansi/jurnal_umum";
		$data['query']      = $this->dbasemodel->loadsql("SELECT A.*  FROM jns_akun A ORDER BY IDAKUN ASC"); 
		$data['header']     = $this->dbasemodel->loadsql("SELECT * FROM jns_akun WHERE HEADER = 1 ");
        $this->load->view('dashboard',$data);
    }
	public function datajurnal(){
		 
		$this->load->model('ModelAkuntansi');
		$keyword     = null !== $this->input->post('keyword') ? $this->input->post('keyword') : "";
		$dataPerPage = $this->input->post('dataperpage');
		$page        = $this->input->post('page');
		$dataTable   = $this->ModelAkuntansi->getJurnalUmum($keyword, $dataPerPage, $page, $this->input->post());

        header('Content-Type: application/json');
		echo json_encode($dataTable);
		die();
		
    }
	public function form_add(){
		 
		$data['PAGE_TITLE'] = "Pencatatan Jurnal Umum";
        $data['page']       = "akuntansi/jurnal_add";
		$data['action']     = "akuntansi/jurnal_umum/save";
		
        $this->load->view('dashboard',$data);
    }
	
	public function save(){
		 
		$content = file_get_contents("php://input");
		$data    = json_decode($content, true);
		
		$save = array( 
					   'KODE_JURNAL' => $data['kode_jurnal'],
					   'KODEPUSAT'   => $this->session->userdata('wad_kodepusat'),
					   'KODECABANG'  => $data['kantor'],
					   'REFERENSI'   => $data['referensi'],
					   'KETERANGAN'  => $data['keterangan'],
					   'JUMLAH'      => $data['total_debet'],
					   'TANGGAL'     => date('Y-m-d', strtotime(str_replace('/', '-', $data['tanggal']))) . date(' H:i:s'),
					);
		$id = $this->dbasemodel->insertDataProc("jurnal_umum", $save);
		
		$saveVTransaksi			= $save;
		$saveVTransaksi['USER']	= $this->session->userdata('wad_user');
		$idVTransaksi			= $this->dbasemodel->insertDataProc("vtransaksi", $saveVTransaksi);
		
		foreach($data['data_jurnal'] as $key) {
			
			$save = array(
							'IDJURNAL'   =>	$id,
							'IDAKUN'     =>	$key[0],
							'DEBET'      =>	$key[3],
							'KREDIT'     =>	$key[4],
							'KETERANGAN' =>	$key[5]
						);
			$this->dbasemodel->insertData("jurnal_umum_dt", $save);
			
			$saveVTransaksidt = array(
										'IDVTRANSAKSI' => $idVTransaksi,
										'IDAKUN'       =>	$key[0],
										'DEBET'        => $key[3],
										'KREDIT'       => $key[4],
										'KETERANGAN'   =>	$key[5]
									);
			$this->dbasemodel->insertData("vtransaksi_dt", $saveVTransaksidt);
		}
		
		echo true;
	}
	
	
	public function get_kodeakun(){
		 
        $perkiraan = $this->input->get('param');
		
		$sql = sprintf("SELECT IDAKUN id, KODE_AKTIVA text, JENIS_TRANSAKSI perkiraan FROM jns_akun WHERE HEADER = 0 AND (KODE_AKTIVA LIKE '%s' OR JENIS_TRANSAKSI LIKE '%s') ", $perkiraan ."%", "%". $perkiraan ."%");
		
        $query	= $this->dbasemodel->loadSql($sql);
		$result	= $query->result_array();
		echo json_encode($result);
    }
}