<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Saldo_kas extends CI_Controller {

	function __construct(){ 
        parent::__construct();
		$this->load->helper(array('form','url', 'xml','text_helper','date','inflector'));
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
        $data['PAGE_TITLE']       = "Laporan Saldo Kas";
		$data['page']             = "laporan/saldo_kas";
		
        $this->load->view('dashboard',$data);
    }
	
	public function data(){
		 
		$this->load->model('ModelLaporan');
		$keyword     = null !== $this->input->post('keyword') ? $this->input->post('keyword') : "";
		$dataPerPage = $this->input->post('dataperpage');
		$page        = $this->input->post('page');
		//$dataTable		=	$this->ModelLaporan->getSaldoKas($keyword, 1000, $page, $this->input->post());
		
		$jenis_kas  = $this->get_data_jenis_kas($this->input->post());
		$saldo_sblm = $this->get_saldo_sblm($this->input->post());
		
		// echo "<pre>";
		// echo print_r($jenis_kas);
		// echo "</pre>";

		if(count($saldo_sblm) > 0) {
			array_unshift($jenis_kas, $saldo_sblm);
		}
		
		$dataTable = array( "status"    => 200, "data" => ($jenis_kas), "datastart" => 1, "dataend" => "1",
		                    "datatotal" => "1", "pagetotal" => 1, "startNumber" => 1,"SQLKAS" => $jenis_kas['SQLKAS'],"SQL" => $saldo_sblm['SQL']);
		                    
		header('Content-Type: application/json'); 
		echo json_encode($dataTable);
		die();	
    }
	
	function get_data_jenis_kas($post) {
		
		
		/* if($this->session->userdata("wad_level") == "admin")
		{
			$kodecabang = "";
		}
		else
		{
			$kodecabang = " A.KODECABANG='".$this->session->userdata('wad_kodecabang')."' AND ";
		} */
		if($this->session->userdata("wad_level") == "admin")
		{
			$kodecabang = "";
			
			if($this->session->userdata('wad_cabang') === true OR $this->session->userdata('wad_cabang') !="" ){
				
				$kodecabang =" A.KODECABANG = '" .$this->session->userdata('wad_cabang'). "' AND ";
				
			}else{
				$kodecabang = "";
			}
		}
		else
		{ 
			
			if($this->session->userdata('wad_cabang') === true OR $this->session->userdata('wad_cabang') !="" ){
				
				$kodecabang =" A.KODECABANG = '" .$this->session->userdata('wad_cabang'). "' AND "; 
				
			}else{
				
				$kodecabang =" A.KODECABANG = '" .$this->session->userdata('wad_kodecabang'). "' AND "; 
			}
		}
		
		$sql = "SELECT A.IDAKUN,A.KODECABANG, A.NAMA_KAS, B.NAMA NAMA_CABANG FROM jenis_kas A
				LEFT JOIN
				m_cabang B ON A.KODECABANG = B.KODE
				WHERE $kodecabang
				A.AKTIF = 'Y' AND A.IDAKUN <> '' 
				ORDER BY 
				IDAKUN";
		$query = $this->dbasemodel->loadsql($sql); 
		
		if($query->num_rows() > 0){
			$n	=	$query->num_rows();
			foreach($query->result() as $res) {
				$result[]   = array('IDAKUN'        => $res->IDAKUN,
								  'NAMA_CABANG'	    => $res->NAMA_CABANG,
								  'JENIS_TRANSAKSI'	=> $res->NAMA_KAS,
								  'JUMLAH'	        => $this->_get_saldo($res->IDAKUN, $post),
								  'SQLKAS'          => $sql
							);
								 
								 
			}
			
			return $result;
		}
		return array();
	}
	
	function _get_saldo($jenis, $post) {
		/* $con_keyword	=	$this->session->userdata('wad_cabang') != "" ? " B.KODECABANG = '". $this->session->userdata('wad_cabang') ."' " : " 1=1 ";
		if($this->session->userdata("wad_level") == "admin")
		{
			$con_keyword = " 1=1 ";
		}
		else
		{
			$con_keyword = " B.KODECABANG = '". $this->session->userdata('wad_cabang') ."' " ;
		} */
		
		if($this->session->userdata("wad_level") == "admin")
		{
			$con_keyword = " 1=1 ";
			
			if($this->session->userdata('wad_cabang') === true OR $this->session->userdata('wad_cabang') !="" ){
				
				$con_keyword =" B.KODECABANG = '" .$this->session->userdata('wad_cabang'). "' ";
				
			}else{
				$con_keyword = " 1=1 ";
			}
		}
		else
		{
			 
			if($this->session->userdata('wad_cabang') === true OR $this->session->userdata('wad_cabang') !="" ){
				
				$con_keyword =" B.KODECABANG = '" .$this->session->userdata('wad_cabang'). "' ";
				
			}else{
				
				$con_keyword =" B.KODECABANG = '" .$this->session->userdata('wad_kodecabang'). "' ";
			}
		}
		
		$tgl         = !empty($post['tgl']) ? $post['tgl'] : date('Y') . '/01/01 - ' . date('Y') . '/12/31';
		$tgl         = explode('-', $post['tgl']);
		
		$pectgl      = explode("-",trim($post['tgl']));
		$tglawal     = str_replace("/","-",$pectgl[0]);
		$tglakhir    = str_replace("/","-",$pectgl[1]);
		
		$settglawal  = date_create($tglawal);
		$gettglawal  = date_format($settglawal,"Y-m-d");
		
		$settglakhir = date_create($tglakhir);
		$gettglakhir = date_format($settglakhir,"Y-m-d");
		
		$con_keyword =	!empty($post['tgl']) ? " DATE(B.TANGGAL) BETWEEN '". $gettglawal ."' AND '". $gettglakhir ."'" : "";
		
		$sql = "SELECT SUM(A.DEBET) DEBET, SUM(A.KREDIT) KREDIT 
				FROM vtransaksi_dt A
				LEFT JOIN
				vtransaksi B ON A.IDVTRANSAKSI = B.IDVTRANSAKSI
				WHERE B.KODE_JURNAL IN('ST','KM','AR','KR','RT') AND
				(B.IDAKUNKAS = $jenis) AND $con_keyword ";
				
		$sqlkurang = "SELECT SUM(A.DEBET) DEBET, SUM(A.KREDIT) KREDIT 
				      FROM vtransaksi_dt A
				      LEFT JOIN
				      vtransaksi B ON A.IDVTRANSAKSI = B.IDVTRANSAKSI
				      WHERE B.KODE_JURNAL IN('PT','KK','JT') AND
				     (B.IDAKUNKAS = $jenis) AND $con_keyword ";
		
		$query = $this->dbasemodel->loadsql($sql);
		$querys = $this->dbasemodel->loadsql($sqlkurang);
		
		if($query->num_rows() > 0)
		{
			
			$row	= $query->row();
			$rows	= $querys->row();
			// $result	= ($row->DEBET - $row->KREDIT);
			$result	= $row->KREDIT-$rows->KREDIT;
			
			return $result;
		}
		return 0;
	}	
	 
	function get_saldo_sblm($post) {
	    
		// $con_keyword	=	$this->session->userdata('wad_cabang') != "" ? " B.KODECABANG = '". $this->session->userdata('wad_cabang') ."' " : " 1=1 ";
		// if($this->session->userdata("wad_level") == "admin")
		// {
			// $con_keyword = " 1=1 ";
		// }
		// else
		// {
			// $con_keyword = " B.KODECABANG = '". $this->session->userdata('wad_cabang') ."' " ;
		// }
		if($this->session->userdata("wad_level") == "admin")
		{
			$con_keyword = " 1=1 ";
			
			if($this->session->userdata('wad_cabang') === true OR $this->session->userdata('wad_cabang') !="" ){
				
				$con_keyword =" B.KODECABANG = '" .$this->session->userdata('wad_cabang'). "' ";
				
			}else{
				$con_keyword = " 1=1 ";
			}
		}
		else
		{
			
			
			if($this->session->userdata('wad_cabang') === true OR $this->session->userdata('wad_cabang') !="" ){
				
				$con_keyword =" B.KODECABANG = '" .$this->session->userdata('wad_cabang'). "' ";
				
			}else{
				
				$con_keyword =" B.KODECABANG = '" .$this->session->userdata('wad_kodecabang'). "' ";
			}
		}
		$tgl = !empty($post['tgl']) ? $post['tgl'] : date('Y') . '/01/01 - ' . date('Y') . '/12/31';
		$tgl = explode('-', $post['tgl']);
		
		$settgl = date_create($post['tgl']);
		$gettgl = date_format($settgl,"Y-m-d");
		
		$pectgl = explode("-",trim($post['tgl']));
		$tglawal = str_replace("/","-",$pectgl[0]);
		$tglakhir = str_replace("/","-",$pectgl[1]);
		
		$settglawal = date_create($tglawal);
		$gettglawal = date_format($settglawal,"Y-m-d");
		
		$settglakhir = date_create($tglakhir);
		$gettglakhir = date_format($settglakhir,"Y-m-d");
		 
		$con_keyword .= !empty($post['tgl']) ?  "AND '".$gettglawal."' < '".$gettglakhir."'" : "";
		  			
		$sql = sprintf("SELECT
		((IF(ISNULL(SUM(A.DEBET)),0, SUM(A.DEBET)))) AS SALDO_SEBELUMNYA
		FROM
		vtransaksi_dt A
		LEFT JOIN
		vtransaksi B ON A.IDVTRANSAKSI = B.IDVTRANSAKSI
		WHERE B.KODE_JURNAL IN('ST','KM','AR','KR','RT') AND %s ",
		$con_keyword
		);
		
		$sqlkurang = sprintf("SELECT
		((IF(ISNULL(SUM(A.DEBET)),0, SUM(A.DEBET)))) AS SALDO_SEBELUMNYA
		FROM
		vtransaksi_dt A
		LEFT JOIN
		vtransaksi B ON A.IDVTRANSAKSI = B.IDVTRANSAKSI
		WHERE B.KODE_JURNAL IN('PT','KK','JT') AND %s ",
		$con_keyword
		);
		
		$query = $this->dbasemodel->loadsql($sql);
		$querys = $this->dbasemodel->loadsql($sqlkurang);
		if($query->num_rows() > 0){
			$row = $query->row();
			$rows = $querys->row();
			$result	=	array('IDAKUN'	=>	0,
							  'NAMA_CABANG'	=> '',
							  'JENIS_TRANSAKSI'	=>	'Saldo Sebelumnya',
							  'JUMLAH'	=>	$row->SALDO_SEBELUMNYA-$rows->SALDO_SEBELUMNYA,
							  'SQL' =>$sql
							);
			return $result;
		}
		return array();
	}
}