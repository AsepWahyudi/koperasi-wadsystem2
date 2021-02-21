<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Penarikan extends CI_Controller {

	function __construct(){ 
        parent::__construct();
		$this->load->helper(array('form','url', 'xml','text_helper','date','inflector','app'));
		$this->load->database();
		$this->load->library(array('Pagination','user_agent','session','form_validation','session'));
		$this->load->model(array('dbasemodel', 'ModelVTransaksi'));
    }
	public function index()
	{
		$idanggota	= $this->input->post("idanggota");
		$nominal	= $this->input->post("nominal");
			
		$sql 	= "SELECT IDANGGOTA, KODEPUSAT, KODECABANG, NAMA
					FROM m_anggota
					WHERE IDANGGOTA = '". $idanggota ."' ";
		$query	= $this->dbasemodel->loadsql($sql);
		
		if($query->num_rows() > 0) {
			$idtrx	=	$this->dbasemodel->get_id('ID_TRX_SIMP', 'transaksi_simp');
			$row	=	$query->row();
			$save['ID_TRX_SIMP']	=	$idtrx;
			$save['TGL_TRX']		=	date('Y-m-d H:i:s');
			$save['ID_KAS']			=	297; #Pendapatan PPOB (D)
			$save['ID_ANGGOTA']		=	$idanggota;
			$save['ID_JENIS']		=	180; #Simpanan Mudharabah (K)
			$save['JUMLAH']			=	$nominal;
			$save['KETERANGAN']		=	'Pembayaran PPOB('. $row->NAMA .')';
			$save['DK']				=	'K';
			$save['AKUN']			=	'Penarikan';
			$save['UPDATE_DATA']	=	date('Y-m-d H:i:s');
			$save['USERNAME']		=	'system';
			$save['KOLEKTOR']		=	0;
			$save['STATUS']			=	1;
				
			$this->dbasemodel->insertData('transaksi_simp', $save);
			
			$datatransaksi	=	array(
									'tgl' 		=> date('Y-m-d H:i:s'),  
									'jumlah' 	=> $nominal, 
									'keterangan'=> 'Pembayaran PPOB('. $row->NAMA .')', 
									'kodepusat'	=> $row->KODEPUSAT,
									'kodecabang'=> $row->KODECABANG,
									'user' 		=> 'system' );
			$this->ModelVTransaksi->insertVtransaksi($idtrx, $datatransaksi, 'PT', $save['ID_KAS'], $save['ID_JENIS'], 'SIMP');			
			
			return 'oke';
		}
		return 'gagal';
	}
}