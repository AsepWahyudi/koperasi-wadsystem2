<?php
require APPPATH.'third_party/vendor/autoload.php';    
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
defined('BASEPATH') OR exit('No direct script access allowed');

class Perkiraan extends CI_Controller {

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
		
        $data['PAGE_TITLE'] = "Daftar Perkiraan(COA)";
		$data['page']       = "akuntansi/perkiraan";
		$data['query']      = $this->dbasemodel->loadsql("SELECT A.*  FROM jns_akun A ORDER BY IDAKUN ASC"); 
		$data['header']     = $this->dbasemodel->loadsql("SELECT * FROM jns_akun WHERE HEADER = 1 ");
        $this->load->view('dashboard',$data);
    }
	
	public function save(){
		
		if($this->input->post())
        {
			$id			= $this->input->post('idtrx');
            $insert 	= $this->input->post();
			
			unset($insert['idtrx']);
			
			if($this->check_kode($this->input->post('kode_aktiva'), $id) == true) 
			{
				$this->session->set_flashdata('ses_trx_akun', '00||Kode Aktiva sudah digunakan, harap gunakan kode yang lain.');
			} 
			else 
			{
				if($id == "") 
				{
					$this->dbasemodel->insertData("jns_akun",$insert);
					$this->session->set_flashdata('ses_trx_akun', '11||Input Data Berhasil.');
				} 
				else 
				{
					$this->dbasemodel->updateData("jns_akun", $insert, "IDAKUN = '". $id."' ");
					$this->session->set_flashdata('ses_trx_akun', '11||Update Data Berhasil.');
				}
			}
        }
		redirect(base_url() . 'jenis-akun');
	}
	
	protected function check_kode($kode, $id = ""){
		
		$_where	= ($id == "" ? "0=0" : "IDAKUN <> '". $id ."'");
		$sql	= sprintf("SELECT IDAKUN FROM jns_akun WHERE %s AND KODE_AKTIVA LIKE '%s' ", $_where, $kode);
		$query	= $this->dbasemodel->loadsql($sql);
		
		if($query->num_rows() > 0)
		{
			return true;
		}
		return false;
	}
	
	public function get_edit(){
		
		 
		$id		= $this->input->get('id');
		$sql	= sprintf("SELECT * FROM jns_akun WHERE IDAKUN = '%s' ", $id);
		$query	= $this->dbasemodel->loadsql($sql);
		
		if($query->num_rows() > 0) 
		{
			$result	=	$query->result_array();
			echo json_encode ($result[0]);
		}
		echo null;
	}
	public function delete(){
	  
		$id		= $this->uri->segment(4);
		$from	= "jns_akun WHERE IDAKUN = ". $id ." ";
		$this->dbasemodel->hapus($from);
		$this->session->set_flashdata('ses_trx_akun', '11||Data telah dihapus.');
		redirect(base_url() . 'jenis-akun');
	}
	public function perkiraan_excel()
	{
		 
		$spreadsheet = new Spreadsheet();
		$spreadsheet->getActiveSheet()->setTitle('Kode Perkiraan');
		$sheet = $spreadsheet->getActiveSheet();
		
		$sheet->setCellValue('A1', 'KODE');
		$sheet->setCellValue('B1', 'PERKIRAAN');
		$sheet->setCellValue('C1', 'AKUN');
		$sheet->setCellValue('D1', 'AKTIF');
		$sheet->setCellValue('E1', 'JENIS');
		
		foreach(range('A','E') as $columnID)
		{
			$sheet->getColumnDimension($columnID)->setAutoSize(true);
		}
		
		$sheet->getStyle('A1:E1')->applyFromArray(
		   array(
			  'font'  => array(
				  'bold'  =>  true
			  )
		   )
		);

		
		$query  = $this->dbasemodel->loadsql("SELECT A.*  FROM jns_akun A ORDER BY IDAKUN ASC");
		$header = $this->dbasemodel->loadsql("SELECT * FROM jns_akun WHERE HEADER = 1 ");
		$row = 2;
		
		if($query->num_rows() > 0)
		{ 
			$n = 1;
			$data_source = $query->result_array();
			$data_source = $this->tree->result_tree('PARENT', 'IDAKUN', $data_source);
			$result      = $data_source['return'];
			foreach($result as $key=>$item)
			{ 
				$no = $n++; 
				$sheet->setCellValue('A'.$row,$item['KODE_AKTIVA']);
				$sheet->setCellValue('B'.$row,str_replace("&nbsp;"," ",strip_tags($this->tree->level($item['_level'], $item['_header'], $item['JENIS_TRANSAKSI']))));
				$sheet->setCellValue('C'.$row,$item['AKUN']);
				$sheet->setCellValue('D'.$row,$item['AKTIF']);
				$sheet->setCellValue('E'.$row,$item['TIPE']);
				$row++;
			} 
			
		}  
		
		$writer = new Xlsx($spreadsheet);
		$file = "kodeperkiraan_".date("ymdHis").".xlsx";
		$writer->save('export/'.$file);
		redirect(base_url().'export/'.$file);
	
	}
}