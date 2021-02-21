<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Neraca extends CI_Controller {

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
	
	public function index(){
		
		$data['opt_data_entries'] = $this->load->view('_elements/data_entries', NULL, TRUE);
		$data['table_footer']     = $this->load->view('_elements/table_footer', NULL, TRUE);
        $data['PAGE_TITLE']       = "Laporan Neraca Saldo";
		$data['page']             = "laporan/neraca";
		$data['cabs']             = $this->dbasemodel->loadsql("SELECT NAMA,KODE FROM m_cabang ORDER BY NAMA ASC"); 
		$data['query']            = $this->dbasemodel->loadsql("SELECT A.* FROM jns_akun A ORDER BY IDAKUN ASC");
        $this->load->view('dashboard',$data);
    }
	
	public function data(){
		 
		$this->load->model('ModelLaporan');
		$keyword		= null !== $this->input->post('keyword') ? $this->input->post('keyword') : "";
		$dataPerPage	= $this->input->post('dataperpage');
		$page			= $this->input->post('page');
		
		$dataTable		= $this->ModelLaporan->getNeraca($keyword, 10000, $page, $this->input->post());
		$dataTable 		= json_decode(json_encode($dataTable), true);
		
		$totalKMacet	                  = $this->sumKreditMacet($this->input->post());
		$dataTable['data'][175]['KREDIT'] =	$totalKMacet;
		  
		$array_sum         = array('DEBET', 'KREDIT');
		$result            = $this->tree->result_tree('PARENT', 'IDAKUN', $dataTable['data'], $array_sum);
		 
		$dataTable['data'] = $result['return'];
		
		header('Content-Type: application/json');
		echo json_encode($dataTable);
		die();	
    }
	public function cetak(){
		
		// plhcabang: 
		// idakun: 
		// tgl: 01/11/2020 - 25/11/2020
		$cabang = $this->input->post("plhcabang"); 
		$this->session->set_flashdata('cabang',$cabang); 
		
		$tgl = $this->input->post("tgl");
		$this->session->set_flashdata('tgl',$tgl);
		
		$keyword		= null !== $this->input->post('keyword') ? $this->input->post('keyword') : "";
		$dataPerPage	= $this->input->post('dataperpage');
		$page			= $this->input->post('page');
		
		$this->session->set_flashdata('keyword',$keyword); 
		$this->session->set_flashdata('dataPerPage',$dataPerPage); 
		$this->session->set_flashdata('page',$page);
		
	}  
	public function cetaklapneraca(){
		ini_set('memory_limit', '512');      // DIDN'T WORK
		ini_set('memory_limit', '512MB');    // DIDN'T WORK
		ini_set('memory_limit', '512M');     // OK - 512MB
		ini_set('memory_limit', 512000000);  // OK - 512MB
		$this->load->model('ModelLaporan');
		$this->load->library('pdf'); 	
		
		$cabang      = $this->session->flashdata('cabang'); 
		$tgl         = $this->session->flashdata('tgl');  
		$dataPerPage = $this->session->flashdata('dataPerPage'); 
		$page        = $this->session->flashdata('page'); 
		  
		$html_content = '';
		 
		$post['tgl']         = $tgl;
		$post['plhcabang']   = $cabang;
		$post['dataPerPage'] = $dataPerPage;
		$post['page']        = $page;
		$post['keyword']     = $keyword;
		  
		$dataTable = $this->ModelLaporan->getNeraca($keyword , 10000, $page , $post);
		$dataTable = json_decode(json_encode($dataTable), true);
		 
		$totalKMacet	                  = $this->sumKreditMacet($post);
		$dataTable['data'][175]['KREDIT'] =	$totalKMacet;
		  
		$array_sum = array('DEBET', 'KREDIT');
		
		$dataTable['cabang']  = $cabang;
		$dataTable['tanggal'] = $tgl;
		// $data['plhcabangs'] = $cabang;
		$dataTable['datacabang'] = $this->dbasemodel->loadsql("SELECT * FROM m_cabang WHERE KODE ='".$cabang."'")->row();  
		$result = $this->tree->result_tree('PARENT', 'IDAKUN', $dataTable['data'], $array_sum);
		 
		$dataTable['data'] = $result['return'];
		// header('Content-Type: application/json');
		// echo json_encode($dataTable);
		// die();	
		
		// $html_content  = $this->load->view('laporan/cetakneracasaldo',$dataTable,true);
	   $html_content = $this->load->view('laporan/cetakneracasaldo',$dataTable,true);
		 
		$this->pdf->loadHtml($html_content,'UTF-8');
		$this->pdf->setPaper('A4');
		$this->pdf->render();
		$this->pdf->stream("Neraca Saldo.pdf", array("Attachment"=>0));
	}  
	protected function sumKreditMacet($post) {
		// $tgl = explode('-', $post['tgl']);
		// $_where = $this->session->userdata('wad_cabang') != "" ? " A.KODECABANG = '". $this->session->userdata('wad_cabang') ."' " : " 1=1 ";
		// $_having = ""; #" HAVING DATE(TGL_JTH_TEMPO) BETWEEN '". date('Y-m-d', strtotime(trim($tgl[0]))) ."' AND '". date('Y-m-d', strtotime(trim($tgl[1]))) ."'";
		
		// if($this->session->userdata("wad_level") == "admin")
		// {
			// $_where = " 1=1 ";
		// }
		// else
		// {
			// $_where = " A.KODECABANG = '". $this->session->userdata('wad_kodecabang') ."' ";
		// } 
		
		$_having  = !empty($post['tgl']) ? " AND DATE(A.TGL_PINJ) BETWEEN '" . date('Y-m-d', strtotime(str_replace('/', '-', trim($tgl[0])))) . "' AND '" . date('Y-m-d', strtotime(str_replace('/', '-', trim($tgl[1])))) . "'" : ""; 
		
		if($post['plhcabang'] == "")
		{
			if($this->session->userdata("wad_level") == "admin")
			{
				$con_keyword = "1=1"; 
			}
			else
			{ 
				$con_keyword =" A.KODECABANG = '" .$this->session->userdata('wad_cabang'). "' ";
			} 
		}
		else
		{
			if($post['plhcabang'] != "")
			{
				$con_keyword =" A.KODECABANG = '" .$post['plhcabang']. "'";
			}
			else
			{
				$con_keyword = "1=1"; 
			} 
		}
		 
		$sql = sprintf("SELECT 
		SUM(A.PINJ_SISA) TOTAL
		FROM tbl_pinjaman_h A
		LEFT JOIN
		m_anggota B ON B.IDANGGOTA = A.ANGGOTA_ID
		WHERE %s 
		AND A.LUNAS = 'Belum'
		AND DATE(DATE_ADD(A.TGL_PINJ, INTERVAL (A.LAMA_ANGSURAN + 3) MONTH)) < DATE(NOW())
		%s
		ORDER BY 
		DATE_ADD(A.TGL_PINJ, INTERVAL A.LAMA_ANGSURAN MONTH) ", 
		$con_keyword,
		$_having );
		 
		$query = $this->dbasemodel->loadSql($sql);
		
		if($query->num_rows() > 0) {
			$row = $query->row();
			return $row->TOTAL;
		}
		return 0;
	}
	
	protected function kasReset($post){
		// $_where = $this->session->userdata('wad_cabang') != "" ? " A.KODECABANG = '". $this->session->userdata('wad_cabang') ."' " : " 1=1 ";
		
		if($this->session->userdata("wad_level") == "admin")
		{
			$_where = " 1=1 ";
		}
		else
		{
			$_where = " A.KODECABANG = '". $this->session->userdata('wad_kodecabang') ."' ";
		}
		$sql = sprintf("SELECT
		IF(A.JENIS = 1, SUM(A.JUMLAH), 0) DEBET,      
		IF(A.JENIS = 0, SUM(A.JUMLAH), 0) KREDIT,
		A.KODECABANG,      
		B.IDAKUN
		FROM
		tbl_reset A        
		LEFT JOIN
		jenis_kas B ON (A.KODECABANG = B.KODECABANG AND B.NAMA_KAS LIKE 'kas reset')
		WHERE %s
		AND A.LUNAS = 0     
		AND A.JENIS = 0
		GROUP BY
		A.KODECABANG",
		$_where
		);
		
		$query = $this->dbasemodel->loadSql($sql);
		
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $key=>$res) {
				$result[$res['IDAKUN']]	=	['DEBET' => $res['DEBET'], 'KREDIT' => $res['KREDIT']];
			}
			return $result;
		}
		return array();
	}
	
	public function dataLama(){
		// if(!is_logged_in()){
			// redirect('/auth_login');	
		// }
		$this->load->model('ModelLaporan');
		$keyword     = null !== $this->input->post('keyword') ? $this->input->post('keyword') : "";
		$dataPerPage = $this->input->post('dataperpage');
		$page        = $this->input->post('page');
		$dataTable   = $this->ModelLaporan->getNeracaLama($keyword, 1000, $page, $this->input->post());
		
		$jenis_kas = $this->get_data_jenis_kas($this->input->post());
		if(count($jenis_kas) > 0) {
			foreach($jenis_kas as $key=>$res) {
				array_unshift($dataTable['data'], $res);
			}
		}
		
		//print_r($dataTable); die();
		header('Content-Type: application/json');
		echo json_encode($dataTable);
		die();	
    }
	
	protected function get_data_jenis_kas($post) {
		
		if($this->session->userdata("wad_level") == "admin")
		{
			// $_where = " 1=1 ";
			$sql = sprintf("SELECT ID_JNS_KAS, NAMA_KAS FROM jenis_kas WHERE AKTIF = 'Y' ORDER BY ID_JNS_KAS DESC");
		}
		else
		{
			// $_where = " A.KODECABANG = '". $this->session->userdata('wad_kodecabang') ."' ";
			$sql = sprintf("SELECT ID_JNS_KAS, NAMA_KAS FROM jenis_kas WHERE KODECABANG = '". $this->session->userdata('wad_kodecabang') ."' AND AKTIF = 'Y' ORDER BY ID_JNS_KAS DESC");
		}
		
		$query = $this->dbasemodel->loadsql($sql);
		
		if($query->num_rows() > 0){
			$n	=	$query->num_rows();
			foreach($query->result() as $res) {
				$result[]	=	array('IDAKUN'		=>	0,
									  'KODE_AKTIVA'	=>	'A.' . $n--,
									  'JENIS_TRANSAKSI'	=>	$res->NAMA_KAS,
									  'AKUN'	=>	'Aktiva',
									  'DEBET'	=>	$this->_get_saldo($res->ID_JNS_KAS, $post),
									  'KREDIT'	=>	0
								);
			}
			
			return $result;
		}
		return array();
	}
	
	protected function _get_saldo($jenis, $post) {
		
		// $con_keyword	=	$this->session->userdata('wad_cabang') != "" ? " KODECABANG = '". $this->session->userdata('wad_cabang') ."' " : " 1=1 ";
		
		if($this->session->userdata("wad_level") == "admin")
		{
			$con_keyword = " 1=1 ";
		}
		else
		{
			$con_keyword = " KODECABANG = '". $this->session->userdata('wad_cabang') ."' ";
		}
		$tgl			=	!empty($post['tgl']) ? $post['tgl'] : date('Y') . '-01-01 - ' . date('Y') . '-12-31';
		$tgl			=	explode('-', $post['tgl']);
		$con_keyword	.=	!empty($post['tgl']) ? " AND DATE(TGL) BETWEEN '". date('Y-m-d', strtotime(trim($tgl[0]))) ."' AND '". date('Y-m-d', strtotime(trim($tgl[1]))) ."'" : "";
		
		$sql	=	sprintf("SELECT SUM(DEBET) DEBET, SUM(KREDIT) KREDIT, (SUM(DEBET) - SUM(KREDIT)) JUMLAH FROM v_transaksi 
							 WHERE (UNTUK_KAS = %s OR DARI_KAS = %s) AND %s ", $jenis, $jenis, $con_keyword);
		$query 	=	$this->dbasemodel->loadsql($sql);
		
		if($query->num_rows() > 0){
			$row	=	$query->row();
			//$result	=	($row->DEBET - $row->KREDIT);
			$result	=	($row->JUMLAH);
			return $result;
		}
		return 0;
	}	
}