<?php

require APPPATH.'third_party/vendor/autoload.php';    
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

defined('BASEPATH') OR exit('No direct script access allowed');

class Pembayaran extends CI_Controller {

	function __construct(){ 
        parent::__construct();
		$this->load->helper(array('form','url', 'xml','text_helper','date','inflector','app'));
		$this->load->database();
		$this->load->library(array('Pagination','user_agent','session','form_validation','session'));
		$this->load->model('dbasemodel');
		if(!is_logged_in()){
			redirect('/auth_login');	
		}
    }
	
	public function index(){
		
		$data['opt_data_entries'] = $this->load->view('_elements/data_entries', NULL, TRUE);
		$data['table_footer']     = $this->load->view('_elements/table_footer', NULL, TRUE);
        $data['PAGE_TITLE']       = "Data Pembayaran Lancar" . $this->input->get('q');
		$data['page']             = "pinjaman/lancar";

        $this->load->view('dashboard',$data);
    }
	
	public function data(){
		 
		$this->load->model('ModelLaporan');
		$keyword     = null !== $this->input->post('keyword') ? $this->input->post('keyword') : "";
		$dataPerPage = $this->input->post('dataperpage');
		$page        = $this->input->post('page');
		
		$dataTable = $this->ModelLaporan->getKreditPinjaman($keyword, $dataPerPage, $page, $this->input->post());

        header('Content-Type: application/json');
		echo json_encode($dataTable);
		die();
		
    }

    public function lancar(){
		 
		$data['opt_data_entries'] = $this->load->view('_elements/data_entries', NULL, TRUE);
		$data['table_footer']     = $this->load->view('_elements/table_footer', NULL, TRUE);
        $data['PAGE_TITLE']       = "Data Pembayaran Lancar" . $this->input->get('q');
		$data['page']             = "pinjaman/lancar";

        $this->load->view('dashboard',$data);
    }

    public function meragukan(){
		 
		$data['opt_data_entries'] = $this->load->view('_elements/data_entries', NULL, TRUE);
		$data['table_footer']     = $this->load->view('_elements/table_footer', NULL, TRUE);
        $data['PAGE_TITLE']       = "Data Pembayaran Meragukan" . $this->input->get('q');
		$data['page']             = "pinjaman/meragukan";

        $this->load->view('dashboard',$data);
    }

    public function buruk(){
	 
		$data['opt_data_entries'] = $this->load->view('_elements/data_entries', NULL, TRUE);
		$data['table_footer']     = $this->load->view('_elements/table_footer', NULL, TRUE);
        $data['PAGE_TITLE']       = "Data Pembayaran Buruk" . $this->input->get('q');
		$data['page']             = "pinjaman/buruk";

        $this->load->view('dashboard',$data);
    }

    public function macet(){
		 
		$data['opt_data_entries'] = $this->load->view('_elements/data_entries', NULL, TRUE);
		$data['table_footer']     = $this->load->view('_elements/table_footer', NULL, TRUE);
        $data['PAGE_TITLE']       = "Data Pembayaran Macet" . $this->input->get('q');
		$data['page']             = "pinjaman/macet";

        $this->load->view('dashboard',$data);
    }
	
	public function dataLancar(){
		 
		$this->load->model('ModelLaporan');
		$keyword     = null !== $this->input->post('keyword') ? $this->input->post('keyword') : "";
		$dataPerPage = $this->input->post('dataperpage');
		$page        = $this->input->post('page');
		
		$dataTable   = $this->ModelLaporan->getKreditLancar($keyword, $dataPerPage, $page, $this->input->post());

        header('Content-Type: application/json');
		echo json_encode($dataTable);
		die();
		
	}
	
	
	public function dataMeragukan(){
		 
		$this->load->model('ModelLaporan');
		$keyword		= null !== $this->input->post('keyword') ? $this->input->post('keyword') : "";
		$dataPerPage	= $this->input->post('dataperpage');
		$page			= $this->input->post('page');
		
		$dataTable		= $this->ModelLaporan->getKreditMeragukan($keyword, $dataPerPage, $page, $this->input->post());

        header('Content-Type: application/json');
		echo json_encode($dataTable);
		die();
    }
	
	public function dataBuruk(){
		if(!is_logged_in()){
			redirect('/auth_login');	
		}
		$this->load->model('ModelLaporan');
		$keyword		= null !== $this->input->post('keyword') ? $this->input->post('keyword') : "";
		$dataPerPage	= $this->input->post('dataperpage');
		$page			= $this->input->post('page');
		
		$dataTable		= $this->ModelLaporan->getKreditBuruk($keyword, $dataPerPage, $page, $this->input->post());

        header('Content-Type: application/json');
		echo json_encode($dataTable);
		die();
    }
	
	public function dataMacet(){
		 
		$this->load->model('ModelLaporan');
		$keyword		= null !== $this->input->post('keyword') ? $this->input->post('keyword') : "";
		$dataPerPage	= $this->input->post('dataperpage');
		$page			= $this->input->post('page');
		
		$dataTable		= $this->ModelLaporan->getKreditMacet($keyword, $dataPerPage, $page, $this->input->post());

        header('Content-Type: application/json');
		echo json_encode($dataTable);
		die();
    }

	public function kreditmacet_excel()
	{
		 
		$spreadsheet = new Spreadsheet();
		$spreadsheet->getActiveSheet()->setTitle('Kredit Macet');
		$sheet = $spreadsheet->getActiveSheet();
		
		$sheet->setCellValue('A1', 'NAMA');
		$sheet->setCellValue('B1', 'TANGGAL PINJAM');
		$sheet->setCellValue('C1', 'JATUH TEMPO');
		$sheet->setCellValue('D1', 'LAMA PINJAM');
		$sheet->setCellValue('E1', 'JUMLAH TAGIHAN');
		$sheet->setCellValue('F1', 'DIBAYAR');
		$sheet->setCellValue('G1', 'SISA');
		$sheet->setCellValue('H1', 'KETERLAMBATAN');
		
		foreach(range('A','H') as $columnID)
		{
			$sheet->getColumnDimension($columnID)->setAutoSize(true);
		}
		
		$sheet->getStyle('A1:H1')->applyFromArray(
		   array(
			  'font'  => array(
				  'bold'  =>  true
			  )
		   )
		);
		// $cond =  ($this->session->userdata('wad_cabang')!="")? "AND A.KODECABANG = '". $this->session->userdata('wad_cabang') ."'":"";
		
		if($this->session->userdata("wad_level") == "admin")
		{
			$cond = "";
		}
		else
		{
			$cond = "AND A.KODECABANG = '". $this->session->userdata('wad_kodecabang') ."'";
		}
		$cek = $this->dbasemodel->loadsql("SELECT 
									A.IDPINJM_H, A.NOREK, A.LAMA_ANGSURAN,       
									   DATE_FORMAT(A.TGL_PINJ, '%d/%m/%Y') TGL_PINJ,
									   DATE_FORMAT(DATE_ADD(A.TGL_PINJ, INTERVAL A.LAMA_ANGSURAN MONTH), '%d/%m/%Y') JATUH_TEMPO,   
									   A.PINJ_TOTAL, A.PINJ_DIBAYAR, A.PINJ_SISA,
									   B.IDANGGOTA, B.NAMA,        
									   CONCAT_WS('', B.KODEPUSAT, '.', B.KODECABANG, '.', B.NO_ANGGOTA, '') KODE_ANGGOTA,       
									   A.LUNAS,        
									   DATEDIFF(DATE(NOW()), DATE(DATE_ADD(A.TGL_PINJ, INTERVAL A.LAMA_ANGSURAN MONTH))) LAMA_MACET
								
								FROM tbl_pinjaman_h A
								LEFT JOIN
									 m_anggota B ON B.IDANGGOTA = A.ANGGOTA_ID
								WHERE 1=1 $cond 
									AND A.LUNAS = 'Belum'
									AND DATE(DATE_ADD(A.TGL_PINJ, INTERVAL (A.LAMA_ANGSURAN + 3) MONTH)) < DATE(NOW())
								ORDER BY 
									DATE_ADD(A.TGL_PINJ, INTERVAL A.LAMA_ANGSURAN MONTH) ");
								
		if($cek->num_rows()>0)
		{
			$row = 2;
			foreach($cek->result() as $item)
			{
				$sheet->setCellValue('A'.$row,$item->NAMA);
				$sheet->setCellValue('B'.$row,$item->TGL_PINJ);
				$sheet->setCellValue('C'.$row,$item->JATUH_TEMPO);
				$sheet->setCellValue('D'.$row,$item->LAMA_ANGSURAN);
				$sheet->setCellValue('E'.$row,$item->PINJ_TOTAL);
				$sheet->getStyle('E'.$row)->getNumberFormat()->setFormatCode('#,##0');
				$sheet->setCellValue('F'.$row,$item->PINJ_DIBAYAR);
				$sheet->getStyle('F'.$row)->getNumberFormat()->setFormatCode('#,##0');
				$sheet->setCellValue('G'.$row,$item->PINJ_SISA);
				$sheet->getStyle('G'.$row)->getNumberFormat()->setFormatCode('#,##0');
				$sheet->setCellValue('H'.$row,$item->LAMA_MACET." Hari");
				$row++;
			}
		}
		$writer = new Xlsx($spreadsheet);
		$file = "laporanmacet_".date("ymdHis").".xlsx";
		$writer->save('export/'.$file);
		redirect(base_url().'export/'.$file);
								
								
	}
    
	
}