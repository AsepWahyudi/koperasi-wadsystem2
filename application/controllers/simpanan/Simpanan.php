<?php
require APPPATH.'third_party/vendor/autoload.php';    
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

defined('BASEPATH') OR exit('No direct script access allowed');

class Simpanan extends CI_Controller {

	function __construct(){ 
        parent::__construct();
		$this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . 'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-chace');
        $this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");  
		
		// $this->load->helper(array('app', 'form','url', 'xml','text_helper','date','inflector'));
		$this->load->database();
		// $this->load->library(array('Pagination','user_agent','session','form_validation','session', 'tree'));
		$this->load->model('dbasemodel');
		$this->load->library('terbilang');
		if(!is_logged_in()){
			redirect('/auth_login');	
		} 
    }
	
	public function index(){
		 
		$data['opt_data_entries'] =	$this->load->view('_elements/data_entries', NULL, TRUE);
		$data['table_footer']     =	$this->load->view('_elements/table_footer', NULL, TRUE);
        $data['PAGE_TITLE']       = "Transaksi Setoran Tunai";
        $data['page']             = "simpanan/simpanan";
        $this->load->view('dashboard',$data);
    }
	
	public function datasetoran(){
		  
		$tgl    = $this->input->post('tgl') != "" ? $this->input->post('tgl') : date('01/m/Y') . ' - ' . date('d/m/Y');
		$tgl    = explode('-', $tgl);
		$tgl[0] = trim($tgl[0]);
		$tgl[1] = trim($tgl[1]);
		$tgl[0] = str_replace('/', '-', $tgl[0]);
		$tgl[1] = str_replace('/', '-', $tgl[1]);
		$tgl[0] = date("Y-m-d", strtotime($tgl[0]));
		$tgl[1] = date("Y-m-d", strtotime($tgl[1]));
		
		$wheretrgl = " AND DATE(TGL_TRX) BETWEEN '". $tgl[0] ."' AND '". $tgl[1] ."'";
		
		// if($this->session->userdata("wad_level") == "admin")
		// {
			// $koncabang = "";
		// }
		// else
		// {
			// $koncabang = " AND D.KODECABANG='".$this->session->userdata('wad_cabang')."'";
		// }
		if($this->session->userdata("wad_level") == "admin")
		{
			$koncabang = "";
			
			if($this->session->userdata('wad_cabang') === true OR $this->session->userdata('wad_cabang') !="" ){
				
				$koncabang =" AND A.KODECABANG = '" .$this->session->userdata('wad_cabang'). "' ";
				
			}else{
				$koncabang = "";
			}
		}
		else
		{
			 
			if($this->session->userdata('wad_cabang') === true OR $this->session->userdata('wad_cabang') !="" ){
				
				$koncabang =" AND A.KODECABANG = '" .$this->session->userdata('wad_cabang'). "' ";
				
			}else{
				
				$koncabang =" AND A.KODECABANG = '" .$this->session->userdata('wad_kodecabang'). "' ";
			}
		} 
		$this->load->model('ModelSimpanan');
		$keyword     = null !== $this->input->post('keyword') ? $this->input->post('keyword') : "";
		$dataPerPage = $this->input->post('dataperpage');
		$page        = $this->input->post('page');
		$dataTable   = $this->ModelSimpanan->getDataTable($keyword, $dataPerPage, $page, $koncabang, $wheretrgl);
		
		// $sql = sprintf("SELECT A.ID_TRX_SIMP, DATE_FORMAT(A.TGL_TRX, '%s') TGL_TRX, A.NAMA_PENYETOR, A.ALAMAT, FORMAT(A.JUMLAH, 0) JUMLAH, A.USERNAME, A.NO_IDENTITAS, B.JNS_SIMP,D.KODECABANG,E.NAMA AS NAMACABANG,A.KETERANGAN FROM transaksi_simp A LEFT JOIN jns_simpan B ON A.ID_JENIS = B.IDAKUN LEFT JOIN m_user D ON A.USERNAME = D.USERNAME LEFT JOIN m_cabang E ON D.KODECABANG = E.KODE WHERE STATUS = 1 AND UPDATE_DATA <> '0000-00-00 00:00:00' AND DK = 'D' AND %s %s %s ORDER BY DATE(A.TGL_TRX) DESC, A.ID_TRX_SIMP DESC", '%d/%m/%Y', $koncabang, $koncabang, $wheretrgl);
		
		// echo $sql;
        header('Content-Type: application/json');
		echo json_encode($dataTable);
		die();
	}
	
    public function formadd(){
		 
        $data['PAGE_TITLE'] = "Tambah Setoran Tunai";
        $data['page'] = "simpanan/add_simpanan";
		
		if($this->session->userdata("wad_level") == "admin")
		{
			$sqlanggota = "SELECT IDANGGOTA, NAMA, ALAMAT, NO_IDENTITAS FROM m_anggota ORDER BY NAMA ASC";
			
			$sqlkas = "SELECT ID_JNS_KAS,IDAKUN, NAMA_KAS FROM jenis_kas WHERE TMPL_SIMPAN = 'Y'";
			
		}
		else
		{
			$sqlanggota = "SELECT IDANGGOTA, NAMA, ALAMAT, NO_IDENTITAS FROM m_anggota WHERE KODECABANG ='".$this->session->userdata('wad_kodecabang')."' ORDER BY NAMA ASC";
			
			$sqlkas = "SELECT ID_JNS_KAS,IDAKUN, NAMA_KAS FROM jenis_kas WHERE TMPL_SIMPAN = 'Y' AND KODECABANG = '".$this->session->userdata('wad_kodecabang')."'";
			
		}
		$data['anggota'] = $this->dbasemodel->loadsql($sqlanggota);
		
		$sqls = "SELECT IDJENIS_SIMP, JNS_SIMP, JUMLAH, IDAKUN FROM jns_simpan WHERE TAMPIL = 'Y'";
		$data['jenis_simpanan']	= $this->dbasemodel->loadsql($sqls); 
		$data['jenis_kas']      = $this->dbasemodel->loadsql($sqlkas); 
		$data['sqlkas']         = $sqlkas; 
        $data['js_to_load']     = array();

        $this->load->view('dashboard',$data);
    }
	public function formedit($id){
		 
		// echo $id;
        $data['PAGE_TITLE'] = "Edit Setoran Tunai";
        $data['page']       = "simpanan/edit_simpanan";
		
		if($this->session->userdata("wad_level") == "admin")
		{
			$sqlanggota = sprintf("SELECT IDANGGOTA, NAMA, ALAMAT, NO_IDENTITAS FROM m_anggota ORDER BY NAMA ASC");
			
			$sqlkas = sprintf("SELECT IDAKUN, NAMA_KAS FROM jenis_kas WHERE TMPL_SIMPAN = 'Y'");
			
			$this->db->select('*');
			$this->db->from('transaksi_simp');
			$this->db->where('ID_TRX_SIMP',$id); 
			$output = $this->db->get()->row(); 
		}
		else
		{
			$sqlanggota = sprintf("SELECT IDANGGOTA, NAMA, ALAMAT, NO_IDENTITAS FROM m_anggota WHERE KODECABANG='".$this->session->userdata('wad_kodecabang')."' ORDER BY NAMA ASC");
			
			$sqlkas = sprintf("SELECT IDAKUN, NAMA_KAS FROM jenis_kas WHERE TMPL_SIMPAN = 'Y' AND KODECABANG = '%s' ", $this->session->userdata('wad_kodecabang'));
			
			$this->db->select('*');
			$this->db->from('transaksi_simp');
			$this->db->where('ID_TRX_SIMP',$id); 
			$this->db->where('KODECABANG',$this->session->userdata('wad_kodecabang')); 
			$output = $this->db->get()->row(); 
		}
		 
		$data['anggota'] = $this->dbasemodel->loadsql($sqlanggota);
		
		$sql = sprintf("SELECT IDJENIS_SIMP, JNS_SIMP, JUMLAH, IDAKUN FROM jns_simpan WHERE TAMPIL = 'Y' ");
		$data['jenis_simpanan']	= $this->dbasemodel->loadsql($sql); 
		$data['jenis_kas']      = $this->dbasemodel->loadsql($sqlkas); 
        $data['js_to_load']     = array(); 
		$data['datasetoran']    = $output;
        $this->load->view('dashboard',$data);
    }
	
	public function save(){
		
		// echo print_r($_POST);
		$this->load->model('ModelSimpanan');
		//$idtrx =	$this->dbasemodel->get_id('ID_TRX_SIMP', 'transaksi_simp');

		$tgl = str_replace('/', '-', $this->input->post('tgl_trx'));
		$tglTrx = date("Y-m-d", strtotime($tgl));
		
		$getIdKas = $this->dbasemodel->loadsql("SELECT * FROM jenis_kas WHERE ID_JNS_KAS = '".$this->input->post('id_kas')."'LIMIT 1")->row();
		
		$id_jenis				= explode("|", $this->input->post('id_jenis'));
		$_POST['id_jenis']		= $id_jenis[0];
		$_POST['tgl_trx']		= date('Y-m-d', strtotime($tglTrx)) . ' ' . date('H:i:s');
		$save					= $this->input->post();
		$save['DK']				= 'D';
		$save['AKUN']			= 'Setoran';
		$save['keterangan']		= (trim($save['keterangan']) == "" ? "Setoran tunai (" . $save['nama_penyetor'] ."), sebesar rp " . toRp($save['jumlah']) : $save['keterangan']);
		$save['USERNAME']		= $this->session->userdata('wad_user');
		$save['KODEPUSAT']		= $this->session->userdata('wad_kodepusat');
		// $save['KODECABANG']		= $this->session->userdata('wad_kodecabang');
		$save['nama_penyetor']	= addslashes($save['nama_penyetor']);
		$save['KOLEKTOR']		= 0; 
		$save['ID_KASAKUN']		= $getIdKas->IDAKUN ;
		$save['STATUS']			= strtolower($this->input->post('keterangan')) == 'transfer'  ? 1 : 0;
			
		if($this->dbasemodel->insertData('transaksi_simp', $save)) 
		{
			
			if($this->session->userdata("wad_level") == "admin")
			{
				$ceklst = $this->dbasemodel->loadsql("Select * FROM checklist_teller WHERE TGL_AWAL='".date("Y-m-d")."'");//AND Jenis='Tabungan'
			
			}
			else
			{
				// $ceklst = $this->dbasemodel->loadsql("Select * FROM checklist_teller WHERE TGL_AWAL='".date("Y-m-d")."' AND KODEPUSAT='".$this->session->userdata('wad_kodepusat')."' AND KODECABANG ='".$this->session->userdata('wad_kodecabang')."'");//AND Jenis='Tabungan'	
				$ceklst = $this->dbasemodel->loadsql("Select * FROM checklist_teller WHERE TGL_AWAL='".date("Y-m-d")."' AND KODECABANG ='".$this->session->userdata('wad_kodecabang')."'");//AND Jenis='Tabungan'	
			}
			 
			if($ceklst->num_rows()>0)
			{
				
				$rchek	      = $ceklst->row();
				$nom 	      = (int)$rchek->NOMINAL_SIMP+(int)str_replace(",","",$this->input->post('jumlah'));
				$where        = "IDCEKTELLER = '". $rchek->IDCEKTELLER."' ";
				$datacheclist = array("NOMINAL_SIMP"=>$nom, "APPROVAL" => '', "STATUS" => 0);
				$this->dbasemodel->updateData("checklist_teller", $datacheclist, $where);
				
			}
			else
			{
				$datacheclist = array("TGL_AWAL"	    => date("Y-m-d"),
									  "NOMINAL_SIMP"	=> $this->input->post('jumlah'),
									  "KODEPUSAT"		=> $this->session->userdata('wad_kodepusat'),
									  "BUKTI"		    => "",
									  // "KODECABANG"	=> $this->session->userdata('wad_kodecabang'));//"JENIS"=>"Tabungan"
									  "KODECABANG"	    => $this->input->post('KODECABANG'));//"JENIS"=>"Tabungan"
				$this->dbasemodel->insertData("checklist_teller", $datacheclist);
			}
			
			$this->session->set_flashdata('ses_trx_simp', '11||Transaksi Setoran Tunai Berhasil Disimpan.');
		} 
		else 
		{
			$this->session->set_flashdata('ses_trx_simp', '00||Transaksi Setoran Tunai Gagal Dilakukan.');
		}
		echo true;
	}
	public function edit(){
		 
		$this->load->model('ModelSimpanan'); 
		$tgl                   = str_replace('/', '-', $this->input->post('tgl_trx'));
		$tglTrx                = date("Y-m-d", strtotime($tgl));
		
		$ID_TRX_SIMP           = $this->input->post('ID_TRX_SIMP');
		$id_jenis              = explode("|", $this->input->post('id_jenis'));
		$_POST['id_jenis']     = $id_jenis[0];
		$_POST['tgl_trx']      = date('Y-m-d', strtotime($tglTrx)) . ' ' . date('H:i:s');
		$save                  = $this->input->post();
		$save['DK']            = 'D';
		$save['AKUN']          = 'Setoran';
		$save['JUMLAH']        = str_replace(',','',$save['jumlah']);
		$save['keterangan']    = (trim($save['keterangan']) == "" ? "Setoran tunai (" . $save['nama_penyetor'] ."), sebesar rp " . str_replace(',','',$save['jumlah']) : $save['keterangan']);
		$save['USERNAME']      = $this->session->userdata('wad_user');
		$save['KODEPUSAT']     = $this->session->userdata('wad_kodepusat');
		$save['KODECABANG']    = $this->session->userdata('wad_kodecabang');
		$save['nama_penyetor'] = addslashes($save['nama_penyetor']);
		$save['KOLEKTOR']      = 0;
		$save['STATUS']        = strtolower($this->input->post('keterangan')) == 'transfer'  ? 1 : 0;
		 
		// var_dump($save);
		$where = "ID_TRX_SIMP = $ID_TRX_SIMP";
		if($this->dbasemodel->updateData('transaksi_simp', $save,$where)) {
			
			if($this->session->userdata("wad_level") == "admin")
			{
				$this->db->select('SUM(JUMLAH) as totaljumlah');
				$this->db->from('transaksi_simp'); 
				$this->db->like('TGL_TRX',$tglTrx); 
				$output = $this->db->get()->row(); 
			}
			else
			{
				$this->db->select('SUM(JUMLAH) as totaljumlah');
				$this->db->from('transaksi_simp'); 
				$this->db->where('KODECABANG',$this->session->userdata('wad_kodecabang')); 
				$this->db->like('TGL_TRX',$tglTrx); 
				$output = $this->db->get()->row(); 
			}
			
			 
			$wheres = "TGL_AWAL LIKE '%$tglTrx%'";
			$update['NOMINAL_SIMP'] = $output->totaljumlah;
			$this->dbasemodel->updateData('checklist_teller', $update,$wheres);
			
			$this->session->set_flashdata('ses_trx_simp', '11||Transaksi Setoran Tunai Berhasil Disimpan.');
		} else {
			$this->session->set_flashdata('ses_trx_simp', '00||Transaksi Setoran Tunai Gagal Dilakukan.');
		}
		echo true;
	}
	
	public function get_anggota(){
		 
		$keyw = $this->input->get('para1');
		
		if($this->session->userdata("wad_level") == "admin")
		{
			
			$sql  = sprintf("SELECT IDANGGOTA id, NAMA text, ALAMAT alamat, NO_IDENTITAS identitas, KODECABANG
			FROM
			m_anggota
			WHERE 
			NAMA LIKE '%s' AND AKTIF = 'Y' 
			ORDER BY NAMA ASC",
			"%". $keyw ."%");

		}
		else
		{
			$sql  = sprintf("SELECT IDANGGOTA id, NAMA text, ALAMAT alamat, NO_IDENTITAS identitas, KODECABANG
			FROM
			m_anggota
			WHERE 
			NAMA LIKE '%s' AND AKTIF = 'Y'
			AND KODECABANG = '%s'
			ORDER BY NAMA ASC",
			"%". $keyw ."%",
			$this->session->userdata('wad_kodecabang'));

		}
		$query  = $this->dbasemodel->loadsql($sql);
		$result = $query->result_array();
		echo json_encode($result);
	}
	
	public function delete(){
		 
		$this->load->model('ModelSimpanan');
		$id = $this->input->get('id');
		
		$this->ModelSimpanan->updateSaldo('kurangi', $id);
		//$this->dbasemodel->hapus("v_transaksi WHERE ID_TRX_SIMP = ". $id ." ");
		$this->dbasemodel->hapus("transaksi_simp WHERE ID_TRX_SIMP = ". $id ." ");
		
		$this->session->set_flashdata('ses_trx_simp', '11||Transaksi setoran tunai telah dihapus.');
		redirect(base_url() . 'setoran-tunai');
	}
	public function deletes($id){
		 
		$this->load->model('ModelSimpanan');  
		 
		if($this->session->userdata("wad_level") == "admin")
		{
			$this->db->select('*');
			$this->db->from('transaksi_simp'); 
			$this->db->like('ID_TRX_SIMP',$id); 
			$output = $this->db->get()->row(); 
			
			$this->db->select('SUM(NOMINAL_SIMP) as total');
			$this->db->from('checklist_teller'); 
			$this->db->like('TGL_AWAL',$TGL_TRX); 
			$outputs = $this->db->get()->row(); 
		}
		else
		{
			$this->db->select('*');
			$this->db->from('transaksi_simp'); 
			$this->db->where('KODECABANG',$this->session->userdata('wad_kodecabang')); 
			$this->db->like('ID_TRX_SIMP',$id); 
			$output = $this->db->get()->row(); 
			
			$this->db->select('SUM(NOMINAL_SIMP) as total');
			$this->db->from('checklist_teller'); 
			$this->db->where('KODECABANG',$this->session->userdata('wad_kodecabang')); 
			$this->db->like('TGL_AWAL',$TGL_TRX); 
			$outputs = $this->db->get()->row(); 
			
		}
		 
		$TGL_TRX = date_create($output->TGL_TRX);
		$TGL_TRX = date_format("Y-m-d",$output->TGL_TRX);
		 
		$wheres = "TGL_AWAL LIKE '%$TGL_TRX%'";
		$update['NOMINAL_SIMP'] = $outputs->total-$output->JUMLAH;
		$this->dbasemodel->updateData('checklist_teller', $update,$wheres);
		
		$this->ModelSimpanan->updateSaldo('kurangi', $id); 
		$this->dbasemodel->hapus("transaksi_simp WHERE ID_TRX_SIMP = ". $id ." ");
		  
		$this->session->set_flashdata('ses_trx_simp', '11||Transaksi setoran tunai telah dihapus.');
		redirect(base_url() . 'cheklist-teller');
	}
	public function setoran_excel(){
		$tgl1 = str_replace("/","-",$this->input->get('tgl1'));
		$tgl2 = str_replace("/","-",$this->input->get('tgl2'));
		//echo $tgl2;
		//echo date('Y-m-d', strtotime($tgl2));
		
		$spreadsheet = new Spreadsheet();
		$spreadsheet->getActiveSheet()->setTitle('Setoran Tunai');
		$sheet = $spreadsheet->getActiveSheet();
		
		$sheet->setCellValue('A1', 'TANGGAL');
		$sheet->setCellValue('B1', 'NAMA');
		$sheet->setCellValue('C1', 'ALAMAT');
		$sheet->setCellValue('D1', 'JENIS SIMPANAN');
		$sheet->setCellValue('E1', 'JUMLAH');
		
		
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
		
		$wheretrgl = " AND DATE(TGL_TRX) BETWEEN '". date('Y-m-d', strtotime(str_replace('/', '-', trim($tgl1)))) ."' AND '". date('Y-m-d', strtotime(str_replace('/', '-', trim($tgl2)))) ."'";
		// $koncabang = ($this->session->userdata('wad_cabang')!="")? " AND D.KODECABANG='".$this->session->userdata('wad_cabang')."'":"";
		
		if($this->session->userdata("wad_level") == "admin")
		{
			$koncabang = "";
			$sql = sprintf("SELECT 
			A.ID_TRX_SIMP, DATE_FORMAT(A.TGL_TRX, '%s') TGL_TRX,
			A.NAMA_PENYETOR, A.ALAMAT,
			A.JUMLAH, A.USERNAME, A.NO_IDENTITAS,
			B.JNS_SIMP,D.KODECABANG,E.NAMA AS NAMACABANG
			FROM
			transaksi_simp A
			LEFT JOIN
			jns_simpan B ON A.ID_JENIS = B.IDAKUN
			LEFT JOIN
			m_user D ON A.USERNAME = D.USERNAME
			LEFT JOIN
			m_cabang E ON D.KODECABANG = E.KODE
			WHERE 
			STATUS = 1 AND UPDATE_DATA <> '0000-00-00 00:00:00' 
			AND DK = 'D' %s %s
			ORDER BY
			DATE(A.TGL_TRX) DESC, A.ID_TRX_SIMP DESC",
			'%d/%m/%Y', $koncabang, $wheretrgl);

		}
		else
		{
			
			$koncabang =" AND D.KODECABANG='".$this->session->userdata('wad_cabang')."'";
			$sql = sprintf("SELECT 
			A.ID_TRX_SIMP, DATE_FORMAT(A.TGL_TRX, '%s') TGL_TRX,
			A.NAMA_PENYETOR, A.ALAMAT,
			A.JUMLAH, A.USERNAME, A.NO_IDENTITAS,
			B.JNS_SIMP,D.KODECABANG,E.NAMA AS NAMACABANG
			FROM
			transaksi_simp A
			LEFT JOIN
			jns_simpan B ON A.ID_JENIS = B.IDAKUN
			LEFT JOIN
			m_user D ON A.USERNAME = D.USERNAME
			LEFT JOIN
			m_cabang E ON D.KODECABANG = E.KODE
			WHERE 
			STATUS = 1 AND UPDATE_DATA <> '0000-00-00 00:00:00' 
			AND DK = 'D' %s %s
			ORDER BY
			DATE(A.TGL_TRX) DESC, A.ID_TRX_SIMP DESC",
			'%d/%m/%Y', $koncabang, $wheretrgl);

		}
		//echo $sql;
		$cek = $this->dbasemodel->loadsql($sql);
		
		if($cek->num_rows()>0)
		{
			$row = 2;
			foreach($cek->result() as $item)
			{
				
				$sheet->setCellValue('A'.$row,$item->TGL_TRX);
				$sheet->setCellValue('B'.$row,$item->NAMA_PENYETOR);
				$sheet->setCellValue('C'.$row,$item->ALAMAT);
				$sheet->setCellValue('D'.$row,$item->JNS_SIMP);
				$sheet->setCellValue('E'.$row,$item->JUMLAH);
				$sheet->getStyle('E'.$row)->getNumberFormat()->setFormatCode('#,##0');
				$row++;
			}
		}
		
		$writer = new Xlsx($spreadsheet);
		$file = "setorantunai_".date("ymdHis").".xlsx";
		$writer->save('export/'.$file);
		redirect(base_url().'export/'.$file);
		 
	}

	public function struk() {
		   
		$this->load->model('ModelSimpanan');
		$id = $this->uri->segment(2);
		$simpanan = $this->ModelSimpanan->lap_data_simpanan($id);

		$opsi_val_arr = $this->ModelSimpanan->get_key_val();
				
		foreach ($opsi_val_arr as $key => $value){
			$out[$key] = $value;
		}
		
		$this->db->select('*');
		$this->db->from('transaksi_simp');
		$this->db->where('ID_TRX_SIMP',$id);
		$qs = $this->db->get()->row();
		
		
		// var_dump($out);
		// exit;
		$output = $this->ModelSimpanan->getCabangh($qs->KODECABANG);
		
		// var_dump($output);
		// exit;
		$this->load->library('Struk');
		$pdf = new Struk('P', 'mm', 'A4', true, 'UTF-8', false);
		$pdf->set_nsi_header(false);
		$resolution = array(210, 80);
		$pdf->AddPage('L', $resolution);
		$html = '
		<style>
			.h_tengah {text-align: center;}
			.h_kiri {text-align: left;}
			.h_kanan {text-align: right;}
			.txt_judul {font-size: 12pt; font-weight: bold; padding-bottom: 12px;}
			.header_kolom {background-color: #cccccc; text-align: center; font-weight: bold;}
			.txt_content {font-size: 7pt; text-align: center;}
		</style>';
		$html .= ''.$pdf->nsi_box($text =' <table width="100%">
			<tr>
				<td colspan="2" class="h_kanan"><strong>'.$output->NAMA.'</strong></td>
			</tr>
			<tr>
				<td width="20%"><strong>BUKTI SETORAN TUNAI</strong>
					<hr width="100%">
				</td>
				<td class="h_kanan" width="80%">'.$output->ALAMAT.'</td>
			</tr>
		</table>', $width = '100%', $spacing = '0', $padding = '1', $border = '0', $align = 'left').'';
		$no =1;
		
		// echo $html;
		// exit;
		foreach ($simpanan as $row) {
			
			$anggota = $this->ModelSimpanan->get_data_anggota($row->ID_ANGGOTA);
			$jns_simpan = $this->ModelSimpanan->get_jenis_simpan($row->ID_JENIS);

			$tgl_bayar = explode(' ', $row->TGL_TRX);
			$txt_tanggal = jin_date_ina($tgl_bayar[0]);
			$txt_tanggal .= ' / ' . substr($tgl_bayar[1], 0, 5);

			if($row->NAMA_PENYETOR ==''){
				$penyetor = '-';
			}else{
				$penyetor = $row->NAMA_PENYETOR;
			}

			if($row->ALAMAT ==''){
				$alamat = '-';
			} else {
				$alamat = $row->ALAMAT;
			}

        //'.'AG'.sprintf('%04d', $row->anggota_id).'
			$html .='<table width="100%">
			<tr>
				<td width="20%"> Tanggal Transaksi </td>
				<td width="2%">:</td>
				<td width="35%" class="h_kiri">'.$txt_tanggal.'</td>

				<td> Tanggal Cetak </td>
				<td width="2%">:</td>
				<td width="25%" class="h_kiri">'.jin_date_ina(date('Y-m-d')).' / '.date('H:i').'</td>
			</tr>
			<tr>
				<td> Nomor Transaksi </td>
				<td>:</td>
				<td>'.'TRD'.sprintf('%05d', $row->ID_TRX_SIMP).'</td>

				<td> User Akun </td>
				<td width="2%">:</td>
				<td class="h_kiri">'.$row->USERNAME.'</td>
			</tr>
			<tr>
				<td> ID Anggota </td>
				<td>:</td>
				<td>'.$anggota->KODEPUSAT.".".$anggota->KODECABANG.".".$anggota->NO_ANGGOTA.'</td>
			
				<td> Status </td>
				<td width="2%">:</td>
				<td class="h_kiri">SUKSES</td>
			</tr>
			<tr>
				<td> Nama Anggota </td>
				<td>:</td>
				<td>'.strtoupper($anggota->NAMA).'</td>
			</tr>
			<tr>
				<td> Dept </td>
				<td>:</td>
				<td>'.$anggota->NAMACABANG.'</td>
			</tr>			
			<tr>
				<td> Nama Penyetor </td>
				<td>:</td>
				<td>'.$penyetor.'</td>

				<td></td>
				<td width="2%"></td>
				<td class="h_kiri">Paraf, </td>
			</tr>
			<tr>
				<td> Alamat </td>
				<td>:</td>
				<td>'.$alamat.'</td>
			</tr>
			<tr>
				<td> Jenis Akun </td>
				<td>:</td>
				<td>'.$jns_simpan->JENIS_TRANSAKSI.'</td>
			</tr>
			<tr>
				<td> Jumlah Setoran </td>
				<td>:</td>
				<td>Rp. '.number_format($row->JUMLAH).'</td>

				<td></td>
				<td width="2%"></td>
				<td class="h_kiri">____________ </td>
			</tr>
			<tr>
				<td> Terbilang </td> 
				<td>:</td>
				<td colspan="3">'.$this->terbilang->eja($row->JUMLAH).' RUPIAH </td>
			</tr>';
		}
		$html .= '</table> 
		<p class="txt_content"></p>

		<p class="txt_content">Ref. '.date('Ymd_His').'<br> 
			Informasi Hubungi Call Center : '.$output->TELP.'
			<br>
			atau dapat diakses melalui : '.$output->WEB.'
		</p>';
		// var_dump($html);
		// exit;
		$pdf->nsi_html($html);
		$pdf->Output(date('Ymd_His') . '.pdf', 'I');
	} 
	public function struksetoran(){
		$this->load->model('ModelSimpanan'); 
		$this->load->library('pdf');
		$this->load->library('terbilang');

		/* $id = $this->uri->segment(2);
		$cekPembayaran 	= $this->dbasemodel->loadsql("SELECT * FROM `tbl_pinjaman_d` WHERE IDPINJ_D = '$id'");
		$key = $cekPembayaran->row();

		$koncabang = ($this->session->userdata('wad_cabang')!="")? " WHERE KODE='".$this->session->userdata('wad_cabang')."'":"";
		$sql = "SELECT * FROM m_cabang $koncabang";
		$cabs = $this->dbasemodel->loadsql($sql);
		$cab  = $cabs->row(); */
		 
		$id = $this->uri->segment(2);
		$simpanan = $this->ModelSimpanan->lap_data_simpananrow($id);

		$opsi_val_arr = $this->ModelSimpanan->get_key_val();
				
		foreach ($opsi_val_arr as $key => $value){
			$out[$key] = $value;
		}
		
		$this->db->select('*');
		$this->db->from('transaksi_simp');
		$this->db->where('ID_TRX_SIMP',$id); 
		$qs = $this->db->get()->row();
		 
		$output = $this->ModelSimpanan->getCabangh($qs->KODECABANG);
		  
		$anggota    = $this->ModelSimpanan->get_data_anggota($simpanan->ID_ANGGOTA);
		$jns_simpan = $this->ModelSimpanan->get_jenis_simpan($simpanan->ID_JENIS);
		
		$this->db->select('*');
		$this->db->from('transaksi_simp');
		$this->db->where('ID_TRX_SIMP',$id); 
		$qs = $this->db->get()->row();
		
		$data['output']     = $output;
		$data['simpanan']   = $simpanan;
		$data['anggota']    = $anggota;
		$data['jns_simpan'] = $jns_simpan;
		$html_content = $this->load->view("simpanan/struksetoran.php",$data,true); 
		 
		$this->pdf->loadHtml($html_content,'UTF-8');
		$this->pdf->set_paper(array(0,0,180,330)); 
		// $customPaper = array(0,0,360,360);
		// $dompdf->set_paper($customPaper);
		$this->pdf->render();
		$this->pdf->stream("Pinjaman_".$key->IDPINJAM.".pdf", array("Attachment"=>0));
		 
	}
	public function bukutabungan(){
		 
        $data['PAGE_TITLE'] = "Cetak Buku Tabungan Anggota";
        $data['page'] = "simpanan/bukutabungan";
		
		if($this->session->userdata("wad_level") == "admin")
		{
			$sqlanggota = "SELECT IDANGGOTA, NAMA, ALAMAT, NO_IDENTITAS FROM m_anggota ORDER BY NAMA ASC";
			
			$sqlkas = "SELECT ID_JNS_KAS,IDAKUN, NAMA_KAS FROM jenis_kas WHERE TMPL_SIMPAN = 'Y'";
			
		}
		else
		{
			$sqlanggota = "SELECT IDANGGOTA, NAMA, ALAMAT, NO_IDENTITAS FROM m_anggota WHERE KODECABANG ='".$this->session->userdata('wad_kodecabang')."' ORDER BY NAMA ASC";
			
			$sqlkas = "SELECT ID_JNS_KAS,IDAKUN, NAMA_KAS FROM jenis_kas WHERE TMPL_SIMPAN = 'Y' AND KODECABANG = '".$this->session->userdata('wad_kodecabang')."'";
			
		}
		$data['anggota'] = $this->dbasemodel->loadsql($sqlanggota);
		
		$sqls = "SELECT IDJENIS_SIMP, JNS_SIMP, JUMLAH, IDAKUN FROM jns_simpan WHERE TAMPIL = 'Y'";
		$data['jenis_simpanan']	= $this->dbasemodel->loadsql($sqls); 
		$data['jenis_kas']      = $this->dbasemodel->loadsql($sqlkas); 
		$data['sqlkas']         = $sqlkas; 
        $data['js_to_load']     = array();

        $this->load->view('dashboard',$data);
    }
	public function rekeningkoran(){
		 
        $data['PAGE_TITLE'] = "Cetak Rekening Koran Anggota";
        $data['page'] = "simpanan/rekeningkoran";
		
		if($this->session->userdata("wad_level") == "admin")
		{
			$sqlanggota = "SELECT IDANGGOTA, NAMA, ALAMAT, NO_IDENTITAS FROM m_anggota ORDER BY NAMA ASC";
			
			$sqlkas = "SELECT ID_JNS_KAS,IDAKUN, NAMA_KAS FROM jenis_kas WHERE TMPL_SIMPAN = 'Y'";
			
		}
		else
		{
			$sqlanggota = "SELECT IDANGGOTA, NAMA, ALAMAT, NO_IDENTITAS FROM m_anggota WHERE KODECABANG ='".$this->session->userdata('wad_kodecabang')."' ORDER BY NAMA ASC";
			
			$sqlkas = "SELECT ID_JNS_KAS,IDAKUN, NAMA_KAS FROM jenis_kas WHERE TMPL_SIMPAN = 'Y' AND KODECABANG = '".$this->session->userdata('wad_kodecabang')."'";
			
		}
		$data['anggota'] = $this->dbasemodel->loadsql($sqlanggota);
		
		$sqls = "SELECT IDJENIS_SIMP, JNS_SIMP, JUMLAH, IDAKUN FROM jns_simpan WHERE TAMPIL = 'Y'";
		$data['jenis_simpanan']	= $this->dbasemodel->loadsql($sqls); 
		$data['jenis_kas']      = $this->dbasemodel->loadsql($sqlkas); 
		$data['sqlkas']         = $sqlkas; 
        $data['js_to_load']     = array();

        $this->load->view('dashboard',$data);
    }
	public function cetakbukutabungan(){
		
		/* [id_anggota] => 304
		[nama_penyetor] => NUR HIDAYAH
		[alamat] => DK TOSO 01/01 BATANG
		[no_identitas] => 3325025012650002
		[KODECABANG] => 27
		[tgl_awal] => 03/01/2021
		[tgl_akhir] => 03/01/2021  */
		// echo "<pre>";
		// echo print_r($_POST);
		
		$idanggota     = $this->input->post("id_anggota",true);
		// $nama_penyetor = $this->input->post("nama_penyetor",true);
		// $alamat        = $this->input->post("alamat",true);
		// $no_identitas  = $this->input->post("no_identitas",true);
		$KODECABANG    = $this->input->post("KODECABANG",true);
		$tgl_awal      = $this->input->post("tgl_awal",true);
		$tgl_akhir     = $this->input->post("tgl_akhir",true);
		
		
		$this->db->select("*");
		$this->db->from("m_anggota");
		$this->db->where("IDANGGOTA",$idanggota);
		$getanggota = $this->db->get()->row(); 
		
		$this->db->select("*");
		$this->db->from("m_cabang");
		$this->db->where("KODE",$KODECABANG);
		$getcabang = $this->db->get()->row(); 
		
		$norek = $getanggota->KODEPUSAT .".". $getcabang->KODECABANG . "." . $getanggota->IDANGGOTA;
		
		$this->db->select('transaksi_simp.ID_TRX_SIMP,DATE(transaksi_simp.TGL_TRX) tgltranssp,m_anggota.IDANGGOTA, m_anggota.NAMA,SUM(transaksi_simp.JUMLAH) as totalsimpan');
		$this->db->from('transaksi_simp');
		$this->db->join('m_anggota','transaksi_simp.ID_ANGGOTA = m_anggota.IDANGGOTA');
		$this->db->where('transaksi_simp.DK','D');
		$this->db->where('m_anggota.IDANGGOTA',$idanggota);
		$this->db->where('DATE(transaksi_simp.TGL_TRX)  >=', tgl_awal);
		$this->db->where('DATE(transaksi_simp.TGL_TRX)  <=', tgl_akhir);
		$this->db->group_by(array("tgltranssp", "m_anggota.NAMA"));
		$this->db->order_by('tgltranssp', 'DESC');
		$querysimpanan = $this->db->get()->result();
		
		$this->db->select('transaksi_simp.ID_TRX_SIMP,DATE(transaksi_simp.TGL_TRX) tgltranssp,m_anggota.IDANGGOTA, m_anggota.NAMA,SUM(transaksi_simp.JUMLAH) as totalsimpan');
		$this->db->from('transaksi_simp');
		$this->db->join('m_anggota','transaksi_simp.ID_ANGGOTA = m_anggota.IDANGGOTA');
		$this->db->where('transaksi_simp.DK','K');
		$this->db->where('m_anggota.IDANGGOTA',$idanggota);
		$this->db->where('DATE(transaksi_simp.TGL_TRX)  >=', tgl_awal);
		$this->db->where('DATE(transaksi_simp.TGL_TRX)  <=', tgl_akhir);
		$this->db->group_by(array("tgltranssp", "m_anggota.NAMA"));
		$this->db->order_by('tgltranssp', 'DESC');
		$querypenarikan = $this->db->get()->result();
     
		$debit = $this->db->query("SELECT T.* FROM(SELECT transaksi_simp.ID_TRX_SIMP,DATE(transaksi_simp.TGL_TRX) tgltranssp,m_anggota.IDANGGOTA, m_anggota.NAMA,SUM(transaksi_simp.JUMLAH) as totalsimpan,transaksi_simp.DK FROM transaksi_simp JOIN m_anggota ON m_anggota.IDANGGOTA = transaksi_simp.ID_ANGGOTA WHERE transaksi_simp.DK='D' AND m_anggota.IDANGGOTA = '".$idanggota."' AND DATE(transaksi_simp.TGL_TRX) BETWEEN '".$tgl_awal."' AND '".$tgl_akhir."' GROUP BY tgltranssp, NAMA UNION ALL SELECT transaksi_simp.ID_TRX_SIMP,DATE(transaksi_simp.TGL_TRX) tgltranssp,m_anggota.IDANGGOTA, m_anggota.NAMA,SUM(transaksi_simp.JUMLAH) as totalsimpan,transaksi_simp.DK FROM transaksi_simp JOIN m_anggota ON m_anggota.IDANGGOTA = transaksi_simp.ID_ANGGOTA WHERE transaksi_simp.DK='K' AND m_anggota.IDANGGOTA = '".$idanggota."' AND DATE(transaksi_simp.TGL_TRX) BETWEEN '".$tgl_awal."' AND '".$tgl_akhir."' GROUP BY tgltranssp, m_anggota.NAMA ) AS T ORDER BY T.tgltranssp,T.DK asc")->result(); 
		 
		 
		$this->load->library('Pdftc'); 
		$pdf = new Pdftc();
		$pdf->set_nsi_headers(TRUE, $norek,$getanggota->NAMA,$getanggota->ALAMAT,$KODECABANG);
	 
		$pdf->SetMargins(0, 0, 0, 0);
		$pdf->setCellPaddings(0,0,0,0); 
		$html = '';
		 
		$pdf->AddPage('L',array(90,140), true);
		
		$html .= '
		<style>
			.h_tengah {text-align: center;}
			.h_kiri {text-align: left;}
			.h_kanan {text-align: right;}
			.txt_judul {font-size: 15pt; font-weight: bold; padding-bottom: 12px;}
			.header_kolom {color:#fff;  background-color: #42c7d4; text-align: center; font-weight: bold;} 
		</style> 
		 
		<table width="100%" cellspacing="0" cellpadding="3" border="1"> 
			<tr class="header_kolom" pagebreak="false">
				<th style="width:20mm;font-size:8px;">Tanggal</th>
				<th style="width:30mm;font-size:8px;">Transaksi</th>
				<th style="width:30mm;font-size:8px;">Debit</th>
				<th style="width:30mm;font-size:8px;">Kredit</th>
				<th style="width:30mm;font-size:8px;">Saldo</th> 
			</tr>';
			
			$saldo = 0;
			foreach($debit as $rows){
				  
				$debits = ($rows->dk == "K") ? $rows->totalsimpan : 0;
				$kredits = ($rows->dk == "D") ? $rows->totalsimpan : 0;
				
				$transaksi = ($rows->dk == "K") ? "Tarik Tunai" : "Setor Tunai";
				
				$getsaldo = (int)$kredits-(int)$debits;
				
				$saldo = $saldo-$getsaldo;
		 
				$html .= '
					<tr class="bordernone;" pagebreak="false">
						<th style="width:20mm;font-size:8px;">'.$rows->tgltranssp.'</th>
						<th style="background-color:#baf7ff;width:30mm;font-size:8px;">'.$transaksi.'</th>
						<th style="width:30mm;font-size:8px; text-align:center;">'.number_format($debits).'</th>
						<th style="background-color:#baf7ff;width:30mm;font-size:8px; text-align:center;">'.number_format($kredits).'</th>
						<th style="width:30mm;font-size:8px; text-align:center;">'.number_format($saldo).'</th> 
					</tr>
				';
				
			}
			  
			
		$html .= '</table>';

		$pdf->nsi_html($html);
		$pdf->Output('pinjam'.date('Ymd_His') . '.pdf', 'I'); 
		 
	}
	public function cetakrekeningkoran(){
		  
		$idanggota     = $this->input->post("id_anggota",true); 
		$KODECABANG    = $this->input->post("KODECABANG",true);
		$tgl_awal      = $this->input->post("tgl_awal",true);
		$tgl_akhir     = $this->input->post("tgl_akhir",true);
		 
		$this->db->select("*");
		$this->db->from("m_anggota");
		$this->db->where("IDANGGOTA",$idanggota);
		$getanggota = $this->db->get()->row(); 
		
		$this->db->select("*");
		$this->db->from("m_cabang");
		$this->db->where("KODE",$KODECABANG);
		$getcabang = $this->db->get()->row(); 
		
		$norek = $getanggota->KODEPUSAT .".". $getcabang->KODECABANG . "." . $getanggota->IDANGGOTA;
		 
		$debit = $this->db->query("SELECT T.* FROM(SELECT concat('simpan') as ket,transaksi_simp.ID_TRX_SIMP,DATE(transaksi_simp.TGL_TRX) tgltranssp,m_anggota.IDANGGOTA, m_anggota.NAMA,transaksi_simp.JUMLAH as totalsimpan,transaksi_simp.DK,transaksi_simp.KETERANGAN FROM transaksi_simp JOIN m_anggota ON m_anggota.IDANGGOTA = transaksi_simp.ID_ANGGOTA WHERE transaksi_simp.DK='D' AND m_anggota.IDANGGOTA = '".$idanggota."' AND DATE(transaksi_simp.TGL_TRX) BETWEEN '".$tgl_awal."' AND '".$tgl_akhir."'
		UNION ALL 
		SELECT  concat('pinjam') as ket,tbl_pinjaman_h.IDPINJM_H,DATE(tbl_pinjaman_h.TGL_PINJ) tgltranssp,m_anggota.IDANGGOTA, m_anggota.NAMA,tbl_pinjaman_h.JUMLAH as totalsimpan,tbl_pinjaman_h.DK,tbl_pinjaman_h.KETERANGAN FROM tbl_pinjaman_h JOIN m_anggota ON m_anggota.IDANGGOTA = tbl_pinjaman_h.ANGGOTA_ID WHERE tbl_pinjaman_h.DK='K' AND m_anggota.IDANGGOTA = '".$idanggota."' AND DATE(tbl_pinjaman_h.TGL_PINJ) BETWEEN '".$tgl_awal."' AND '".$tgl_akhir."'
		UNION ALL
		SELECT  concat('angsur') as ket,tbl_pinjaman_d.IDPINJ_D,DATE(tbl_pinjaman_d.TGL_BAYAR) tgltranssp,tbl_pinjaman_h.ANGGOTA_ID idanggota, m_anggota.NAMA,tbl_pinjaman_d.JUMLAH_BAYAR as totalsimpan,tbl_pinjaman_d.DK,tbl_pinjaman_d.KETERANGAN FROM tbl_pinjaman_d JOIN tbl_pinjaman_h ON tbl_pinjaman_d.IDPINJAM = tbl_pinjaman_h.IDPINJM_H JOIN m_anggota ON m_anggota.IDANGGOTA = tbl_pinjaman_h.ANGGOTA_ID WHERE tbl_pinjaman_h.ANGGOTA_ID = '".$idanggota."' AND DATE(tbl_pinjaman_d.TGL_BAYAR) BETWEEN '".$tgl_awal."' AND '".$tgl_akhir."'
		UNION ALL
		SELECT  concat('tarik') as ket,transaksi_simp.ID_TRX_SIMP,DATE(transaksi_simp.TGL_TRX) tgltranssp,m_anggota.IDANGGOTA, m_anggota.NAMA,transaksi_simp.JUMLAH as totalsimpan,transaksi_simp.DK,transaksi_simp.KETERANGAN FROM transaksi_simp JOIN m_anggota ON m_anggota.IDANGGOTA = transaksi_simp.ID_ANGGOTA WHERE dk='K' AND m_anggota.IDANGGOTA = '".$idanggota."' AND DATE(transaksi_simp.TGL_TRX) BETWEEN '".$tgl_awal."' AND '".$tgl_akhir."') AS T ORDER BY T.tgltranssp,T.DK asc")->result(); 
		 
		$periode = $tgl_awal." s.d ".$tgl_akhir;
		$tertanggal = date("Y-m-d");
		$this->load->library('Pdftc'); 
		$pdf = new Pdftc();
		// $pdf->set_nsi_headers(TRUE, $norek,$getanggota->NAMA,$getanggota->ALAMAT,$KODECABANG);
		$pdf->set_nsi_headerss(TRUE, $norek,$getanggota->NAMA,$periode,$tertanggal,$KODECABANG);
	 
		$pdf->SetMargins(0, 0, 0, 0);
		$pdf->setCellPaddings(0,0,0,0); 
		$html = '';
		 
		$pdf->AddPage('L',array(90,140), true);
		
		$html .= '
		<style>
			.h_tengah {text-align: center;}
			.h_kiri {text-align: left;}
			.h_kanan {text-align: right;}
			.txt_judul {font-size: 15pt; font-weight: bold; padding-bottom: 12px;}
			.header_kolom {color:#fff;  background-color: #42c7d4; text-align: center; font-weight: bold;} 
		</style> 
		 
		<table width="100%" cellspacing="0" cellpadding="3" border="1"> 
			<tr class="header_kolom" pagebreak="false">
				<th style="width:20mm;font-size:8px;">Tanggal</th>
				<th style="width:60mm;font-size:8px;">Transaksi</th>
				<th style="width:20mm;font-size:8px;">Debit</th>
				<th style="width:20mm;font-size:8px;">Kredit</th> 
				<th style="width:20mm;font-size:8px;">Saldo</th> 
			</tr>';
			
			$saldo = 0;
			foreach($debit as $rows){
				
				// K = TARIK TUNAI
				// D = SIMPAN
				// select @k:=if(kode="k",nominal,0) as Kredit,@d:=if(kode="d",nominal,0) as
				// debet, @s:=@s+@k-@d as saldo from trx;
				
				$debits = ($rows->dk == "K") ? $rows->totalsimpan : 0;
				$kredits = ($rows->dk == "D") ? $rows->totalsimpan : 0;
				
				
				$transaksi ="";
				if($rows->ket == "simpan"){
					
					$transaksi = "Setor Tunai, ".$rows->keterangan;
				}
				elseif($rows->ket == "pinjam"){
					$transaksi = "Pinjaman, ".$rows->keterangan;
					
				}elseif($rows->ket == "angsur"){
					
					$transaksi = "Angsuran, ".$rows->keterangan;
				}elseif($rows->ket == "tarik"){
					$transaksi = "Tarik Tunai, ".$rows->keterangan;
					
				}
				// $transaksi = ($rows->dk == "K") ? "Tarik Tunai, ".$rows->keterangan : "Setor Tunai. ".$rows->keterangan;
				
				$getsaldo = (int)$kredits-(int)$debits;
				
				$saldo = $saldo-$getsaldo;
				// $transaksi = ($rows->dk == "D") ? $rows->totalsimpan : 0;
				// if()
				$html .= '
					<tr class="bordernone;" pagebreak="false">
						<th style="width:20mm;font-size:8px;">'.$rows->tgltranssp.'</th>
						<th style="background-color:#baf7ff;width:60mm;font-size:8px;">'.$transaksi.'</th>
						<th style="width:20mm;font-size:8px; text-align:center;">'.number_format($debits).'</th>
						<th style="background-color:#baf7ff;width:20mm;font-size:8px; text-align:center;">'.number_format($kredits).'</th> 
						<th style="background-color:#baf7ff;width:20mm;font-size:8px; text-align:center;">'.number_format($saldo).'</th> 
					</tr>
				';
				
			}
			  
			
		$html .= '</table>';

		$pdf->nsi_html($html);
		$pdf->Output('pinjam'.date('Ymd_His') . '.pdf', 'I'); 
		
		
	}
}