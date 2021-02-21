<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class General extends CI_Controller {

	function __construct(){ 
        parent::__construct();
		$this->load->helper(array('url', 'xml','text_helper','date','inflector'));
		$this->load->database();
		$this->load->library(array('session','form_validation'));
		$this->load->model('dbasemodel');
    }
	
	public function get_total_saldo()
	{
		if(!is_logged_in()){
			redirect('/auth_login');	
		}
		$this->load->model('ModelSimpanan');
	    $idanggota 	= 	$this->input->post('idanggota');
	   	// $idjenis	=	$this->input->post('idjenis');
	    //$saldo_simpanan	=	$this->ModelSimpanan->getSaldo($idanggota, '32');
	   
		$sql	=	sprintf("SELECT SUM(SALDO) SALDO FROM m_anggota_simp WHERE IDJENIS_SIMP <> 260 AND IDANGGOTA = %s ", $idanggota);
		$query	=	$this->dbasemodel->loadSql($sql)->row();
		$saldo_simpanan	=	$query->SALDO;
	    
	    $this->db->select('JAMINAN_TABUNGAN');
	    $this->db->where('anggota_id',$idanggota);
	    $this->db->where('lunas','Belum');
	    $_res = $this->db->get('tbl_pinjaman_h')->row();
	    $jaminan_tabungan = ($_res) ? floor($_res->JAMINAN_TABUNGAN) : 0 ;
	    
	    $saldo_simpanan = $saldo_simpanan - $jaminan_tabungan;
	    
	    echo $saldo_simpanan;
    }
	
	public function get_anggota() {
		if(!is_logged_in()){
			redirect('/auth_login');	
		}
		if($this->session->userdata('wad_level') == "admin") {
			$kocab	=	($this->input->get('kocab') != "" ? " AND KODECABANG = '". $this->input->get('kocab') ."' " : "");
		} else {
			$kocab	=	" AND KODECABANG = '". $this->session->userdata('wad_kodecabang') ."'";
		}
		 
		$aktif	= " AKTIF = 'Y' ";
		$keyw	= $this->input->get('para1');
		
		$sql	= sprintf("SELECT IDANGGOTA id, NAMA text, ALAMAT alamat, NO_IDENTITAS identitas,KODECABANG
							 FROM
							 	m_anggota
							 WHERE 
							 	NAMA LIKE '%s' AND %s
								%s
							 ORDER BY NAMA ASC",
							 "%". $keyw ."%", $aktif, 
							 $kocab);
		$query		=	$this->dbasemodel->loadsql($sql);
		$result		=	$query->result_array();
		echo json_encode($result);
	}
}