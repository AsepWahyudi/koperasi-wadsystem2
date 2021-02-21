<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reset_kolektor extends CI_Controller {
	
	function __construct(){ 
        parent::__construct();
		$this->load->helper(array('form','url', 'xml','text_helper','date','inflector','app'));
		$this->load->database();
		$this->load->library(array('Pagination','user_agent','session','form_validation','session'));
		$this->load->model(array('dbasemodel', 'ModelVTransaksi'));
		ini_set("memory_limit", "-1");
		set_time_limit(0);
    }
	
	public function index(){
		$sql	=	sprintf("SELECT 
								   A.IDPINJM_H,
								   A.JUMLAH, A.REKENING,
								   IF(A.PINJ_RP_ANGSURAN > A.PINJ_SISA, A.PINJ_SISA, A.PINJ_RP_ANGSURAN) ANGSURAN,
								   A.ANGGOTA_ID,
								   A.KODECABANG, 
								   A.KODEPUSAT,  
								   (SELECT DATE(PD.TGL_BAYAR) FROM tbl_pinjaman_d PD WHERE PD.IDPINJAM = A.IDPINJM_H ORDER BY PD.TGL_BAYAR DESC, IDPINJ_D DESC LIMIT 1) ANGSURAN_TERAKHIR,      
								   DATE(DATE_ADD(IF(ISNULL((SELECT DATE(PD.TGL_BAYAR) FROM tbl_pinjaman_d PD WHERE PD.IDPINJAM = A.IDPINJM_H ORDER BY PD.TGL_BAYAR DESC, IDPINJ_D DESC LIMIT 1)), A.TGL_PINJ, (SELECT DATE(PD.TGL_BAYAR) FROM tbl_pinjaman_d PD WHERE PD.IDPINJAM = A.IDPINJM_H ORDER BY PD.TGL_BAYAR DESC, IDPINJ_D DESC LIMIT 1)), INTERVAL 3 MONTH)) TGL_RESETKOLEKTOR,
								   (SELECT ANGSURAN_KE FROM tbl_pinjaman_d PD WHERE PD.IDPINJAM = A.IDPINJM_H ORDER BY PD.TGL_BAYAR DESC, IDPINJ_D DESC LIMIT 1) ANGSURAN_TERAKHIR_KE,
								   DATE(CONCAT_WS('', DATE_FORMAT(NOW(), '%s'), DATE_FORMAT(A.TGL_PINJ, '%s'))) TGL_ANGSURAN_SELANJUTNYA,      
                                   (SELECT DATE(TR.TANGGAL) FROM tbl_reset TR WHERE TR.IDPINJAMAN = A.IDPINJM_H AND TR.JENIS = 1 ORDER BY DATE(TR.TANGGAL) DESC LIMIT 1) TGL_KOLEKTOR_TERAKHIR
								FROM
									tbl_pinjaman_h A
								WHERE 
									A.LUNAS LIKE 'Belum'      
									AND A.PINJ_SISA > 0  
								HAVING
									  DATE(NOW()) > TGL_RESETKOLEKTOR  
									  AND DATE(CONCAT_WS('', DATE_FORMAT(TGL_RESETKOLEKTOR, '%s'), '%s')) <= DATE(NOW())   
				                      AND DATE_FORMAT(DATE(IF(ISNULL(TGL_KOLEKTOR_TERAKHIR), TGL_RESETKOLEKTOR, DATE_ADD(TGL_KOLEKTOR_TERAKHIR, INTERVAL 3 MONTH))), '%s') <= DATE_FORMAT(NOW(), '%s')
                                ORDER BY 
									IDPINJM_H DESC", 
								'%Y-%m-', '%d',
								'%Y-%m-', '01',
								'%Y%m', '%Y%m'
							);
							
		$query	=	$this->dbasemodel->loadsql($sql);
		if($query->num_rows()>0)
		{
			foreach($query->result() as $res)
			{
				$jumlah	=	50000;
				$save	=	array(
								'IDPINJAMAN'	=>	$res->IDPINJM_H,
								'IDANGGOTA'		=>	$res->ANGGOTA_ID,
								'BLTH'			=>	date('Ym', strtotime($res->TGL_ANGSURAN_SELANJUTNYA)),
								'TANGGAL'		=>	date('Y-m-01', strtotime($res->TGL_ANGSURAN_SELANJUTNYA)),
								'JUMLAH'		=>	$jumlah,
								'ANGSURAN_KE'	=>	0,
								'KODEPUSAT'		=>	$res->KODEPUSAT,
								'KODECABANG'	=>	$res->KODECABANG,
								'TANGGAL_INSERT'=>	date('Y-m-d H:i:s'),
								'JENIS'			=>	1
							);
				
				if($this->dbasemodel->insertOnDuplicate("tbl_reset", $save, array('BLTH')) > 0) {
					/* Insert data transaksi reset ke jurnal transaksi(table vtransaksi) */
					$datatransaksi	=	array( 
											'tgl' 			=>	date('Y-m-01', strtotime($res->TGL_ANGSURAN_SELANJUTNYA)) . date(' H:i:s'), 
											'jumlah' 		=>	$jumlah, 
											'keterangan'	=>	'Admin kolektor (No Rek '. $res->REKENING .')', 
											'kodepusat'		=>	$res->KODEPUSAT,
											'kodecabang'	=>	$res->KODECABANG,
											'user' 			=>	'system',
											'ket_dt'		=>	'admin kolektor'
										);
					//$this->ModelVTransaksi->insertVtransaksi(0, $datatransaksi, 'AK', sukubunga('admin_kolektor'), kasteller($res->KODECABANG));
				}
			}
			echo 'oke: ' . $query->num_rows();
		}else{
			echo "nodata";
		}
		
	}
	
}
