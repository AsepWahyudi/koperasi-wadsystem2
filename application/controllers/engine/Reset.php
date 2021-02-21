<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reset extends CI_Controller {
	
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
							   DATE(A.TGL_PINJ) TGL_PINJ,
							   A.JUMLAH, A.REKENING,
							   A.PINJ_SISA,
							   IF(A.PINJ_RP_ANGSURAN > A.PINJ_SISA, A.PINJ_SISA, A.PINJ_RP_ANGSURAN) ANGSURAN,
							   A.ANGGOTA_ID,
							   A.KODECABANG, 
							   A.KODEPUSAT,
							   TIMESTAMPDIFF(MONTH, DATE_ADD(A.TGL_PINJ, INTERVAL (A.LAMA_ANGSURAN + 3) MONTH), NOW()) AS selisih_bulan
							FROM
								tbl_pinjaman_h A
							WHERE 
								A.LUNAS LIKE 'Belum'      
								AND A.PINJ_SISA > 0
								AND DATE_ADD(A.TGL_PINJ, INTERVAL (A.LAMA_ANGSURAN + 3) MONTH) < DATE(NOW())
 ", 
								'%Y-%m-', '%d');
								
		$query	=	$this->dbasemodel->loadsql($sql);
		if($query->num_rows()>0)
		{
			foreach($query->result() as $res)
			{
				$jumlah	=	(($res->PINJ_SISA * 5) / 100);
				$save	=	array(
								'IDPINJAMAN'	=>	$res->IDPINJM_H,
								'IDANGGOTA'		=>	$res->ANGGOTA_ID,
								'BLTH'			=>	date('Ym', strtotime($res->TGL_ANGSURAN_SELANJUTNYA)),
								'TANGGAL'		=>	$res->TGL_ANGSURAN_SELANJUTNYA,
								'JUMLAH'		=>	$jumlah,
								'ANGSURAN_KE'	=>	($res->ANGSURAN_TERAKHIR_KE == "" ? 1 : ($res->ANGSURAN_TERAKHIR_KE + 1)),
								'KODEPUSAT'		=>	$res->KODEPUSAT,
								'KODECABANG'	=>	$res->KODECABANG,
								'TANGGAL_INSERT'=>	date('Y-m-d H:i:s'),
							);
				
				if($this->dbasemodel->insertOnDuplicate("tbl_reset", $save, array('BLTH')) > 0) {
					
					//$idAkunReset	=	$this->akunKasReset($res->KODECABANG);
					/* Insert data transaksi reset ke jurnal transaksi(table vtransaksi) */
					$datatransaksi	=	array( 
											'tgl' 			=>	$res->TGL_ANGSURAN_SELANJUTNYA . date(' H:i:s'), 
											'jumlah' 		=>	$jumlah, 
											'keterangan'	=>	'Reset pembiayaan (No Rek '. $res->REKENING .')', 
											'kodepusat'		=>	$res->KODEPUSAT,
											'kodecabang'	=>	$res->KODECABANG,
											'user' 			=>	'system',
											'ket_dt'		=>	'biaya reset'
										);
					//$this->ModelVTransaksi->insertVtransaksi(0, $datatransaksi, 'KR', sukubunga('admin_pembiayaan'), $idAkunReset);
					
					# UPDATE TABEL PINJAMAN_H
					$sql	=	sprintf("UPDATE tbl_pinjaman_h
										SET
											IS_RESET = 1,
											PINJ_TOTAL = (PINJ_TOTAL + %s) ,
											PINJ_SISA = (PINJ_SISA + %s) 
										 WHERE
										 	IDPINJM_H = %s",
										$jumlah, $jumlah,
										$res->IDPINJM_H
									);
					$this->dbasemodel->loadSql($sql);
					
					# UPDATE TABEL ANGGOTA
					$sql	=	sprintf("UPDATE m_anggota
										SET
											PINJ_TOTAL = (PINJ_TOTAL + %s) ,
											PINJ_SISA = (PINJ_SISA + %s) 
										 WHERE
										 	IDANGGOTA = %s",
										$jumlah, $jumlah,
										$res->ANGGOTA_ID
									);
					$this->dbasemodel->loadSql($sql);
					
				}
			}
			echo 'oke';
		}else{
			echo "nodata";
		}
		
	}
	
	protected function akunKasReset($kodecabang){
		$sql			=	sprintf("SELECT
										A.IDAKUN
									FROM
										jenis_kas A
									WHERE
										A.KODECABANG = '%s'
										AND A.NAMA_KAS LIKE 'kas reset'
									LIMIT 1",
									$kodecabang
								);
		$query			=	$this->dbasemodel->loadSql($sql);
		if($query->num_rows() > 0) {
			$row	=	$query->row();
			return $row->IDAKUN;
		}
		return 0;
	}
	
}
