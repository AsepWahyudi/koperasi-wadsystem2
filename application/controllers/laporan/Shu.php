<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Shu extends CI_Controller {

	function __construct(){ 
        parent::__construct();
		$this->load->helper(array('form','url', 'xml','text_helper','date','inflector', 'app'));
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
        $data['PAGE_TITLE']       = "Laporan SHU";
		$data['page']             = "laporan/shu";
		                              
		$data['arr_opsi'] = $this->get_key_val();
		$data['arr_pinj'] = $this->get_jml_angsuran($this->input->get());
		$data['arr_pend'] = $this->get_pendapatan($this->input->get());
		$data['arr_simp'] = $this->get_simp_pen($this->input->get());
		
		$this->load->view('dashboard', $data);
    }
	
	protected function get_key_val(){
		
		$sql	= sprintf("SELECT OPSI_KEY, OPSI_VAL FROM suku_bunga");
		$query	= $this->dbasemodel->loadsql($sql);
		$result	= array();
		foreach ($query->result_array() as $key => $value) {
			$result[$value['OPSI_KEY']] = $value['OPSI_VAL'];
		}
		return $result;
	}
	
	function get_jml_angsuran($post) {
		// $con_keyword	=	$this->session->userdata('wad_cabang') != "" ? " A.KODECABANG = '". $this->session->userdata('wad_cabang') ."' " : " 1=1 ";
		/* if($this->session->userdata("wad_level") == "admin")
		{
			$con_keyword = " 1=1 ";
		}
		else
		{
			$con_keyword = " A.KODECABANG = '". $this->session->userdata('wad_kodecabang') ."' " ;
		} */
		if($this->session->userdata("wad_level") == "admin")
		{
			$con_keyword = " 1=1 ";
			
			if($this->session->userdata('wad_cabang') === true OR $this->session->userdata('wad_cabang') !="" ){
				
				$con_keyword =" A.KODECABANG = '" .$this->session->userdata('wad_cabang'). "' ";
				
			}else{
				$con_keyword = " 1=1 ";
			}
		}
		else
		{
			 
			if($this->session->userdata('wad_cabang') === true OR $this->session->userdata('wad_cabang') !="" ){
				
				$con_keyword =" A.KODECABANG = '" .$this->session->userdata('wad_cabang'). "' ";
				
			}else{
				
				$con_keyword =" A.KODECABANG = '" .$this->session->userdata('wad_kodecabang'). "' ";
			}
		}
		$tgl			=	!empty($post['tgl']) ? $post['tgl'] : date('Y') . '/01/01 - ' . date('Y') . '/12/31';
		$tgl			=	explode('-', $tgl);
		$con_keyword	.=	!empty($post['tgl']) ? " AND DATE(B.TGL_PINJ) BETWEEN '". date('Y-m-d', strtotime(trim($tgl[0]))) ."' AND '". date('Y-m-d', strtotime(trim($tgl[1]))) ."'" : " AND 2=2 ";
		
		$sql	=	sprintf("SELECT SUM(A.JUMLAH_BAYAR) DIBAYAR, SUM(B.JUMLAH) PINJAMAN
							 FROM
							 	tbl_pinjaman_d A
							 LEFT JOIN
							 	tbl_pinjaman_h B ON A.IDPINJAM = B.IDPINJM_H
							 WHERE %s ",
							 $con_keyword
						);
		$query 	=	$this->dbasemodel->loadsql($sql);
		
		if($query->num_rows() > 0){
			$result	=	$query->result_array();
			return $result[0];
		}
		return array('DIBAYAR' => 0, 'PINJAMAN' => 0);
	}
	
	protected function get_pendapatan($post) {
		// $con_keyword	=	$this->session->userdata('wad_cabang') != "" ? " AND B.KODECABANG = '". $this->session->userdata('wad_cabang') ."' " : " AND 1=1 ";
		/* if($this->session->userdata("wad_level") == "admin")
		{
			$con_keyword = " 1=1 ";
		}
		else
		{
			$con_keyword = " AND B.KODECABANG = '". $this->session->userdata('wad_kodecabang') ."' " ;
		} */
		if($this->session->userdata("wad_level") == "admin")
		{
			$con_keyword = " AND 1=1 ";
			
			if($this->session->userdata('wad_cabang') === true OR $this->session->userdata('wad_cabang') !="" ){
				
				$con_keyword =" AND B.KODECABANG = '" .$this->session->userdata('wad_cabang'). "' ";
				
			}else{
				$con_keyword = " AND 1=1 ";
			}
		}
		else
		{
			 
			if($this->session->userdata('wad_cabang') === true OR $this->session->userdata('wad_cabang') !="" ){
				
				$con_keyword =" AND B.KODECABANG = '" .$this->session->userdata('wad_cabang'). "' ";
				
			}else{
				
				$con_keyword =" AND B.KODECABANG = '" .$this->session->userdata('wad_kodecabang'). "' ";
			}
		}
		$tgl = !empty($post['tgl']) ? $post['tgl'] : date('Y') . '/01/01 - ' . date('Y') . '/12/31';
		$tgl = explode('-', $tgl);
		$con_keyword .= !empty($post['tgl']) ? " AND DATE(B.TGL) BETWEEN '". date('Y-m-d', strtotime(trim($tgl[0]))) ."' AND '". date('Y-m-d', strtotime(trim($tgl[1]))) ."'" : " AND 2=2 ";
		
		$sql = sprintf("SELECT A.LABA_RUGI, (IF(ISNULL(SUM(B.DEBET)), 0, SUM(B.DEBET)) + IF(ISNULL(SUM(B.KREDIT)), 0, SUM(B.KREDIT))) TOTAL
		FROM
		jns_akun A 
		LEFT JOIN
		v_transaksi B ON (A.IDAKUN = B.TRANSAKSI %s)
		WHERE 
		A.AKTIF = 'Y' AND LENGTH(A.KODE_AKTIVA) > 1 AND A.LABA_RUGI IN ('PENDAPATAN', 'BIAYA')
		GROUP BY 
		A.LABA_RUGI ",
		$con_keyword
		);
		
		$query = $this->dbasemodel->loadsql($sql);
		
		if($query->num_rows() > 0){
			foreach($query->result() as $res) {
				$result[$res->LABA_RUGI]	=	$res->TOTAL;
			}
			return $result;
		}
		return array('PENDAPATAN' => 0, 'BIAYA' => 0);
	}
	
	protected function get_simp_pen($post) {
		// $con_keyword	=	$this->session->userdata('wad_cabang') != "" ? " AND A.KODECABANG = '". $this->session->userdata('wad_cabang') ."' " : " AND 1=1 ";
		/* if($this->session->userdata("wad_level") == "admin")
		{
			$con_keyword = " 1=1 ";
		}
		else
		{
			$con_keyword = " AND A.KODECABANG = '". $this->session->userdata('wad_kodecabang') ."' " ;
		} */
		
		if($this->session->userdata("wad_level") == "admin")
		{
			$con_keyword = " 1=1 ";
			
			if($this->session->userdata('wad_cabang') === true OR $this->session->userdata('wad_cabang') !="" ){
				
				$con_keyword =" A.KODECABANG = '" .$this->session->userdata('wad_cabang'). "' ";
				
			}else{
				$con_keyword = " 1=1 ";
			}
		}
		else
		{
			 
			if($this->session->userdata('wad_cabang') === true OR $this->session->userdata('wad_cabang') !="" ){
				
				$con_keyword =" A.KODECABANG = '" .$this->session->userdata('wad_cabang'). "' ";
				
			}else{
				
				$con_keyword =" A.KODECABANG = '" .$this->session->userdata('wad_kodecabang'). "' ";
			}
		}
		$tgl			=	!empty($post['tgl']) ? $post['tgl'] : date('Y') . '/01/01 - ' . date('Y') . '/12/31';
		$tgl			=	explode('-', $tgl);
		$con_keyword	.=	!empty($post['tgl']) ? " AND DATE(A.TGL_TRX) BETWEEN '". date('Y-m-d', strtotime(trim($tgl[0]))) ."' AND '". date('Y-m-d', strtotime(trim($tgl[1]))) ."'" : " AND 2=2 ";
		
		$sql = sprintf("SELECT 
		SUM(A.JUMLAH) TOTAL, A.DK
		FROM
		transaksi_simp A
		WHERE
		A.ID_JENIS IN (40, 41)
		GROUP BY
		A.DK ",
		$con_keyword
		);
		$query = $this->dbasemodel->loadsql($sql);
		
		$result	= array('SIMPANAN' => 0, 'PENARIKAN' => 0);
		if($query->num_rows() > 0){
			foreach($query->result() as $res) {
				if($res->DK == 'D') {
					$result['SIMPANAN']		=	$res->TOTAL;
				} elseif($res->DK == 'K') {
					$result['PENARIKAN']	=	$res->TOTAL;
				}
			}
		}
		return $result;
	}
	public function data(){
		 
		$this->load->model('ModelLaporan');
		$keyword		= null !== $this->input->post('keyword') ? $this->input->post('keyword') : "";
		$dataPerPage	= $this->input->post('dataperpage');
		$page			= $this->input->post('page');
		die();
		//$dataTable		=	$this->ModelLaporan->getSaldoKas($keyword, 1000, $page, $this->input->post());
		
		
		$jenis_kas		= $this->get_data_jenis_kas($this->input->post());
		$saldo_sblm		= $this->get_saldo_sblm($this->input->post());
		if(count($saldo_sblm) > 0) {
			array_unshift($jenis_kas, $saldo_sblm);
		}
		
		$dataTable		=	array("status" => 200, "data" => ($jenis_kas), "datastart" => 1, "dataend" => "1", "datatotal" => "1", "pagetotal" => 1, "startNumber" => 1);
		header('Content-Type: application/json');
		echo json_encode($dataTable);
		die();	
    }
	
	protected function get_data_jenis_kas($post) {
		
		/* if($this->session->userdata("wad_level") == "admin")
		{
			$kodecabang = " ";
		}
		else
		{
			$kodecabang = " KODECABANG = '". $this->session->userdata('wad_kodecabang') ."' AND " ;
		} */
		
		if($this->session->userdata("wad_level") == "admin")
		{
			$kodecabang = " ";
			
			if($this->session->userdata('wad_cabang') === true OR $this->session->userdata('wad_cabang') !="" ){
				
				$kodecabang =" KODECABANG = '" .$this->session->userdata('wad_cabang'). "' AND ";
				
			}else{
				$kodecabang = " ";
			}
		}
		else
		{
			 
			if($this->session->userdata('wad_cabang') === true OR $this->session->userdata('wad_cabang') !="" ){
				
				$kodecabang =" KODECABANG = '" .$this->session->userdata('wad_cabang'). "' AND ";
				
			}else{
				
				$kodecabang =" KODECABANG = '" .$this->session->userdata('wad_kodecabang'). "' AND ";
			}
		}
		
		$sql	= sprintf("SELECT ID_JNS_KAS, NAMA_KAS FROM jenis_kas WHERE $kodecabang AKTIF = 'Y' ORDER BY ID_JNS_KAS");
		$query 	= $this->dbasemodel->loadsql($sql);
		if($query->num_rows() > 0){
			$n	=	$query->num_rows();
			foreach($query->result() as $res) {
				$result[]	=	array('IDKAS'		=>	$res->ID_JNS_KAS,
									  'JENIS_TRANSAKSI'	=>	$res->NAMA_KAS,
									  'JUMLAH'	=>	$this->_get_saldo($res->ID_JNS_KAS, $post)
								);
			}
			
			return $result;
		}
		return array();
	}
	
	protected function _get_saldo_akun($jenis, $post) {
		// $con_keyword	=	$this->session->userdata('wad_cabang') != "" ? " KODECABANG = '". $this->session->userdata('wad_cabang') ."' " : " 1=1 ";
		/* if($this->session->userdata("wad_level") == "admin")
		{
			$con_keyword = " 1=1 ";
		}
		else
		{
			$con_keyword = " KODECABANG = '". $this->session->userdata('wad_kodecabang') ."' " ;
		} */
		if($this->session->userdata("wad_level") == "admin")
		{
			$con_keyword = " 1=1 ";
			
			if($this->session->userdata('wad_cabang') === true OR $this->session->userdata('wad_cabang') !="" ){
				
				$con_keyword =" KODECABANG = '" .$this->session->userdata('wad_cabang'). "' ";
				
			}else{
				$con_keyword = " 1=1 ";
			}
		}
		else
		{
			 
			if($this->session->userdata('wad_cabang') === true OR $this->session->userdata('wad_cabang') !="" ){
				
				$con_keyword =" KODECABANG = '" .$this->session->userdata('wad_cabang'). "' ";
				
			}else{
				
				$con_keyword =" KODECABANG = '" .$this->session->userdata('wad_kodecabang'). "' ";
			}
		}
		$tgl = !empty($post['tgl']) ? $post['tgl'] : date('Y') . '/01/01 - ' . date('Y') . '/12/31';
		$tgl = explode('-', $tgl);
		$con_keyword .= !empty($post['tgl']) ? " AND DATE(TGL) BETWEEN '". date('Y-m-d', strtotime(trim($tgl[0]))) ."' AND '". date('Y-m-d', strtotime(trim($tgl[1]))) ."'" : " AND 2=2 ";
		
		$sql = sprintf("SELECT SUM(DEBET) DEBET, SUM(KREDIT) KREDIT FROM v_transaksi WHERE (UNTUK_KAS = %s OR DARI_KAS = %s) AND %s ", $jenis, $jenis, $con_keyword);
		$query = $this->dbasemodel->loadsql($sql);
		
		if($query->num_rows() > 0){
			$row	= $query->row();
			$result	= ($row->DEBET - $row->KREDIT);
			return $result;
		}
		return 0;
	}	
	
	function get_saldo_sblm($post) {
		
		// $con_keyword	=	$this->session->userdata('wad_cabang') != "" ? " KODECABANG = '". $this->session->userdata('wad_cabang') ."' " : " 1=1 ";
		/* if($this->session->userdata("wad_level") == "admin")
		{
			$con_keyword = " 1=1 ";
		}
		else
		{
			$con_keyword = " KODECABANG = '". $this->session->userdata('wad_kodecabang') ."' " ;
		} */
		if($this->session->userdata("wad_level") == "admin")
		{
			$con_keyword = " 1=1 ";
			
			if($this->session->userdata('wad_cabang') === true OR $this->session->userdata('wad_cabang') !="" ){
				
				$con_keyword =" KODECABANG = '" .$this->session->userdata('wad_cabang'). "' ";
				
			}else{
				$con_keyword = " 1=1 ";
			}
		}
		else
		{
			 
			if($this->session->userdata('wad_cabang') === true OR $this->session->userdata('wad_cabang') !="" ){
				
				$con_keyword =" KODECABANG = '" .$this->session->userdata('wad_cabang'). "' ";
				
			}else{
				
				$con_keyword =" KODECABANG = '" .$this->session->userdata('wad_kodecabang'). "' ";
			}
		}
		$tgl = !empty($post['tgl']) ? $post['tgl'] : date('Y') . '/01/01 - ' . date('Y') . '/12/31';
		$tgl = explode('-', $post['tgl']);
		$con_keyword .= !empty($post['tgl']) ? " AND DATE(TGL) < '". date('Y-m-d', strtotime(trim($tgl[0]))) ."'" : " AND 2=2 ";
		
		$sql = sprintf("SELECT
		((IF(ISNULL(SUM(DEBET)),0, SUM(DEBET))) - (IF(ISNULL(SUM(KREDIT)),0, SUM(KREDIT)))) AS SALDO_SEBELUMNYA
		FROM
		v_transaksi 
		WHERE %s ",
		$con_keyword
		);
		
		$query = $this->dbasemodel->loadsql($sql);
		
		if($query->num_rows() > 0){
			$row	=	$query->row();
			$result	=	array('IDKAS'	=>	0,
							  'JENIS_TRANSAKSI'	=>	'Saldo Sebelumnya',
							  'JUMLAH'	=>	$row->SALDO_SEBELUMNYA
							);
			return $result;
		}
		return array();
	}
}