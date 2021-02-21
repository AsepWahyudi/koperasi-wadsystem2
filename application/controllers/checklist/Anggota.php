<?php
require APPPATH.'third_party/vendor/autoload.php';    
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

defined('BASEPATH') OR exit('No direct script access allowed');

class Anggota extends CI_Controller {

	function __construct(){ 
        parent::__construct(); 
		$this->load->database();
		$this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . 'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-chace');
        $this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");  
		 
		$this->load->model('dbasemodel');
		error_reporting(-1);
		ini_set('display_errors', 1);
		
		if(!is_logged_in()){
			redirect('/auth_login');	
		}
    }
	
	public function index(){
		
		$data['opt_data_entries'] = $this->load->view('_elements/data_entries', NULL, TRUE);
		$data['table_footer']     = $this->load->view('_elements/table_footer', NULL, TRUE);
        $data['PAGE_TITLE']       = "Data Anggota";
		$data['page']             = "checklist/anggota";
		 
        $this->load->view('dashboard',$data);
    }
	
	public function dataanggota(){
		 
		$this->load->model('ModelChecklist');
		
		$keyword     = null !== $this->input->post('keyword') ? $this->input->post('keyword') : "";
		$dataPerPage = $this->input->post('dataperpage');
		$page        = $this->input->post('page');
		$dataTable   = $this->ModelChecklist->getDataTable($keyword, $dataPerPage, $page);

        header('Content-Type: application/json');
		echo json_encode($dataTable);
		die();
		
    }

	public function detail(){
		 
		$this->load->model('ModelChecklist');
		
		$idanggota           = $this->uri->segment(2);
		$data['PAGE_TITLE']  = "Data Anggota";
		$data['data_source'] = $this->ModelChecklist->getDetailAnggota($idanggota);
		$data['page']        = "checklist/anggota_detail";
		
        $this->load->view('dashboard',$data);
    }
	
	public function approve(){
		 
		$idanggota = $this->uri->segment(2);
		$sql = sprintf("UPDATE m_anggota SET AKTIF = 'Y' WHERE IDANGGOTA = '%s' ", $idanggota);
		$this->dbasemodel->loadsql($sql);
		$this->session->set_flashdata('ses_checklist', '11||Berhasil menyimpan data.');
		redirect(base_url() . 'list-anggota-baru');
    }
	
	public function tolak(){
		 
		$idanggota = $this->uri->segment(2);
		$sql       = sprintf("UPDATE m_anggota SET AKTIF = 'N' WHERE IDANGGOTA = '%s' ", $idanggota);
		$this->dbasemodel->loadsql($sql);
		$this->session->set_flashdata('ses_checklist', '11||Anda telah menolak anggota baru.');
		redirect(base_url() . 'list-anggota-baru');
    }

    public function nonaktif(){
		 
		$idanggota = $this->uri->segment(2);
		$sql = sprintf("UPDATE m_anggota SET AKTIF = 'N' WHERE IDANGGOTA = '%s' ", $idanggota);
		$this->dbasemodel->loadsql($sql);
		$this->session->set_flashdata('ses_checklist', '11||Anda telah Non Aktifkan anggota.');
		redirect(base_url() . 'anggota');
    }

    public function aktif(){
		 
		$idanggota = $this->uri->segment(2);
		$sql = sprintf("UPDATE m_anggota SET AKTIF = 'Y' WHERE IDANGGOTA = '%s' ", $idanggota);
		$this->dbasemodel->loadsql($sql);
		$this->session->set_flashdata('ses_checklist', '11||Anda Aktifkan anggota.');
		redirect(base_url() . 'anggota-nonaktif');
    }

    function excel(){
		 
		$spreadsheet = new Spreadsheet();
		$spreadsheet->getActiveSheet()->setTitle('Data Anggota Baru');
		$sheet = $spreadsheet->getActiveSheet();
		
		$sheet->setCellValue('A1', 'NAMA LENGKAP');
		$sheet->setCellValue('B1', 'JENIS KELAMIN');
		$sheet->setCellValue('C1', 'TANGGAL LAHIR');
		$sheet->setCellValue('D1', 'USIA');
		$sheet->setCellValue('E1', 'ALAMAT');
		$sheet->setCellValue('F1', 'TANGGAL REGISTRASI');
		
		foreach(range('A','F') as $columnID)
		{
			$sheet->getColumnDimension($columnID)->setAutoSize(true);
		}
		
		$sheet->getStyle('A1:F1')->applyFromArray(
		   array(
			  'font'  => array(
				  'bold'  =>  true
			  )
		   )
		);

		
		// $koncabang = ($this->session->userdata('wad_cabang')!="")? " AND A.KODECABANG='".$this->session->userdata('wad_cabang')."'":"";
		
		if($this->session->userdata("wad_level") == "admin")
		{
			$koncabang = "";
		}
		else
		{
			$koncabang = " AND A.KODECABANG='".$this->session->userdata('wad_kodecabang')."' AND ";
		}
		
		$cek = $this->dbasemodel->loadSql("SELECT FILE_PIC, IDANGGOTA, NOREK, NAMA, '' AS NAMABANK, JK,
		DATE_FORMAT(TGL_LAHIR, '%d/%m/%Y') AS TGL_LAHIR,
		TIMESTAMPDIFF(YEAR, TGL_LAHIR, CURDATE()) AS USIA,
		ALAMAT, DATE_FORMAT(TGL_DAFTAR, '%d/%m/%Y') AS TGL_DAFTAR, AKTIF
		FROM m_anggota
		WHERE $koncabang AKTIF = '' 
		ORDER BY IDANGGOTA");
								
		$row = 2;
		if($cek->num_rows() > 0){ $n = 1;
		
			foreach($cek->result() as $item){ 
				$sheet->setCellValue('A'.$row,$item->NAMA);
				$sheet->setCellValue('B'.$row,$item->JK);
				$sheet->setCellValue('C'.$row,$item->TGL_LAHIR);
				$sheet->setCellValue('D'.$row,$item->USIA);
				$sheet->setCellValue('E'.$row,$item->ALAMAT);
				$sheet->setCellValue('F'.$row,$item->TGL_DAFTAR);
				$row++;
			} 
			
		}
		
		$writer = new Xlsx($spreadsheet);
		$file = "anggota_baru_".date("ymdHis").".xlsx";
		$writer->save('export/'.$file);
		redirect(base_url().'export/'.$file);
		
	}
	
}