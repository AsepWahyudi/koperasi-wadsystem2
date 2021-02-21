<?php

require APPPATH.'third_party/vendor/autoload.php';    
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

defined('BASEPATH') OR exit('No direct script access allowed');

class Jurnal_transaksi extends CI_Controller {

	function __construct(){ 
        parent::__construct();
		$this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . 'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-chace');
        $this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");  
		 
		$this->load->database();
		 
		$this->load->model('dbasemodel');
		if(!is_logged_in()){
			redirect('/auth_login');	
		}
    }
	
	public function index(){ 
		
		$data['opt_data_entries'] = $this->load->view('_elements/data_entries', NULL, TRUE);
		$data['table_footer']     = $this->load->view('_elements/table_footer', NULL, TRUE);
		// $data['searchkey']     = $_POST;
        $data['PAGE_TITLE']       = "Jurnal Transaksi";
		$data['page']             = "laporan/jurnal_transaksi"; 
		
		$data['cabs']             = $this->dbasemodel->loadsql("SELECT NAMA,KODE FROM m_cabang ORDER BY NAMA ASC"); 
		$data['query']            = $this->dbasemodel->loadsql("SELECT A.* FROM jns_akun A ORDER BY IDAKUN ASC");
		
        $this->load->view('dashboard',$data);
    }
	
	public function data(){
 
		$this->load->model('ModelLaporan');
		$keyword     = null !== $this->input->post('keyword') ? $this->input->post('keyword') : "";
		$dataPerPage = $this->input->post('dataperpage');
		$page        = $this->input->post('page');
		$dataTable   = $this->ModelLaporan->getJurnalTrans($keyword, $dataPerPage, $page, $this->input->post());
		
		//array_unshift($dataTable, array('id' => ''));
		
		header('Content-Type: application/json');
		echo json_encode($dataTable);
		//die();
		
    }

    public function excel()
	{
		if(!is_logged_in()){
			redirect('/auth_login');	
		}

		if (isset($_GET['tgl'])) {
			$tglExp	=	explode('-', $_GET['tgl']);
			$tgl1 = $tglExp[0];
			$tgl2 = $tglExp[1];

			$tgl = " DATE(C.TANGGAL) BETWEEN '". date('Y-m-d', strtotime(trim($tgl1))) ."' AND '". date('Y-m-d', strtotime(trim($tgl2))) ."'";
		}else{
			$tgl = "1=1";
		}
		
		$spreadsheet = new Spreadsheet();
		$spreadsheet->getActiveSheet()->setTitle('Jurnal Transaksi');
		$sheet = $spreadsheet->getActiveSheet();
		
		$sheet->setCellValue('A1', 'JURNAL TRANSAKSI');
		$sheet->setCellValue('A2', 'Periode '.tgl_indo(date("m/d/Y", strtotime($tgl1))).' s/d '.tgl_indo(date("m/d/Y", strtotime($tgl2))));
		$sheet->setCellValue('A3', 'TANGGAL');
		$sheet->setCellValue('B3', 'NO. BUKTI');
		$sheet->setCellValue('C3', 'KODE PERKIRAAN');
		$sheet->setCellValue('D3', 'NAMA PERKIRAAN');
		$sheet->setCellValue('E3', 'URAIAN JURNAL');
		$sheet->setCellValue('F3', 'DEBET');
		$sheet->setCellValue('G3', 'KREDIT');
		
		foreach(range('A','G') as $columnID)
		{
			$sheet->getColumnDimension($columnID)->setAutoSize(true);
		}
		
		$sheet->getStyle('A1:G3')->applyFromArray(
		   array(
			  'font'  => array(
				  'bold'  =>  true
			  )
		   )
		);

		// $cond = ($this->session->userdata('wad_cabang')!="")? "C.KODECABANG = '". $this->session->userdata('wad_cabang') ."'": "1=1";
		if($this->session->userdata("wad_level") == "admin")
		{
			$cond = "";
		}
		else
		{
			$cond = " C.KODECABANG='".$this->session->userdata('wad_kodecabang')."'";
		}
		$where = $tgl." AND ".$cond;
		$cek = $this->dbasemodel->loadsql("SELECT A.IDVTRANSAKSI,
		DATE_FORMAT(C.TANGGAL, '%d/%m/%Y') TANGGAL, C.KODE_JURNAL, 
		C.ID_TRX_SIMP, C.ID_TRX_KAS, C.IDPINJ_D, C.REFERENSI, 
		B.KODE_AKTIVA, B.JENIS_TRANSAKSI, C.KETERANGAN, A.DEBET, A.KREDIT 
		FROM vtransaksi_dt A 
		LEFT JOIN jns_akun B ON A.IDAKUN = B.IDAKUN
		LEFT JOIN vtransaksi C ON A.IDVTRANSAKSI = C.IDVTRANSAKSI
		WHERE $where 
		ORDER BY A.IDVTRANSAKSI ASC");
								
		if($cek->num_rows()>0)
		{
			$row = 4;
			foreach($cek->result() as $item)
			{
				if ($item->KODE_JURNAL == 'ST' || $item->KODE_JURNAL == 'PT') {
			        $noBukti = "TAB.0".$item->ID_TRX_SIMP;
			      } elseif ($item->KODE_JURNAL == 'KM' || $item->KODE_JURNAL == 'KK') {
			        $noBukti = "KAS.0".$item->ID_TRX_KAS;
			      } elseif ($item->KODE_JURNAL == 'JT' && $item->ID_TRX_KAS != null) {
			        $noBukti = "KRE.0".$item->ID_TRX_KAS;
			      } elseif ($item->KODE_JURNAL == 'JT' && $item->IDPINJ_D != null) {
			        $noBukti = "KRE.0".$item->IDPINJ_D;
			      } elseif ($item->KODE_JURNAL == 'AK' || $item->KODE_JURNAL == 'KR' || $item->KODE_JURNAL == 'RT') {
			        $noBukti = "KRE.0".$item->IDPINJ_D;
			      } elseif ($item->ID_TRX_SIMP == null || $item->ID_TRX_KAS == null || $item->IDPINJ_D == null) {
			        $noBukti = $item->REFERENSI;
			      }

			      if ($item->DEBET != 0) {
			        $ket = $item->KETERANGAN;
			        $tgl = $item->TANGGAL;
			      }else{
			        $ket="";
			        $noBukti="";
			        $tgl = "";
			      }
				
				$sheet->setCellValue('A'.$row,$tgl);
				$sheet->setCellValue('B'.$row,$noBukti);
				$sheet->setCellValue('C'.$row,$item->KODE_AKTIVA);
				$sheet->setCellValue('D'.$row,$item->JENIS_TRANSAKSI);
				$sheet->setCellValue('E'.$row,$ket);
				$sheet->setCellValue('F'.$row,$item->DEBET);
				$sheet->getStyle('F'.$row)->getNumberFormat()->setFormatCode('#,##0');
				$sheet->setCellValue('G'.$row,$item->KREDIT);
				$sheet->getStyle('G'.$row)->getNumberFormat()->setFormatCode('#,##0');
				$row++;
			}
		}
		$writer = new Xlsx($spreadsheet);
		$file = "jurnaltransaksi_".date("ymdHis").".xlsx";
		$writer->save('export/'.$file);
		redirect(base_url().'export/'.$file);
								
								
	}
	public function cetak(){
		
		// plhcabang: 
		// idakun: 
		// tgl: 01/11/2020 - 25/11/2020
		$cabang = $this->input->post("plhcabang");
		$idakun = $this->input->post("idakun");
		$tgl = $this->input->post("tgl");
		$this->session->set_flashdata('cabang',$cabang);
		$this->session->set_flashdata('idakun',$idakun);
		$this->session->set_flashdata('tgl',$tgl);
	}  
	public function cetaklaporanjurnaltransaksi(){
		
	 
		$this->load->library('pdf');
		$this->load->library('terbilang');		
		
		$cabang = $this->session->flashdata('cabang');
		$idakun = $this->session->flashdata('idakun');
		$tgl = $this->session->flashdata('tgl'); 
		  
		$html_content = '';
		
		$data['cabang'] = $cabang;
		$data['datacabang'] = $this->dbasemodel->loadsql("SELECT * FROM m_cabang WHERE KODE ='".$cabang."'")->row(); 
		$data['idakun'] = $idakun;
		$data['tgl']    = $tgl;
		
		$html_content  = $this->load->view('laporan/cetakjurnaltransaksi',$data,true);
		
		//$html_content .= $html;
		
		$this->pdf->loadHtml($html_content,'UTF-8');
		$this->pdf->setPaper('A4');
		$this->pdf->render();
		$this->pdf->stream("Buku Besar.pdf", array("Attachment"=>0));
	}  
	
}