<?php

class ModelSertifikat extends CI_Model {

	public function __construct(){
		parent::__construct(); 
		$this->load->database();
	}
	
	public function getDataTable($keyword, $dataperpage, $page, $koncabang, $wheretrgl){
					
		$ci =& get_instance();
		$ci->load->model('DBHelper');
		
		$con_keyword = $keyword == "" ? "1=1" : "(B.NAMA LIKE '%".$keyword."%') OR CONCAT_WS('', B.KODEPUSAT, '.', (SELECT CONVERT(KODECABANG, CHAR) FROM m_cabang WHERE m_cabang.KODE = B.KODECABANG) , '.', NO_ANGGOTA, '')  LIKE '%".$keyword."%'";
		$startid     = ($page * 1 - 1) * $dataperpage;
		$datastart   = $startid + 1;
		$dataend     = $datastart + $dataperpage - 1;
		
		$sql = "SELECT  
				A.IDPINJM_H,G.IDANGGUNAN, DATE_FORMAT(A.TGL_PINJ, '%d/%m/%Y') TGL_PINJ,
				CONCAT_WS('', B.KODEPUSAT, '.', (SELECT CONVERT(KODECABANG, CHAR) FROM m_cabang WHERE m_cabang.KODE = B.KODECABANG) , '.', NO_ANGGOTA, '') KODE_ANGGOTA,
				A.LUNAS, A.BUNGA, 
				FORMAT(A.PINJ_RP_ANGSURAN,0) JML_ANGSURAN,
				FORMAT(IF(ISNULL(SUM(D.DENDA_RP)), 0, SUM(D.DENDA_RP)),0) JML_DENDA,
				FORMAT(A.PINJ_TOTAL, 0) TOTAL_TAGIHAN,
				FORMAT(A.PINJ_DIBAYAR,0) SUDAHDIBAYAR, 
				(SELECT SUM(JUMLAH_BAYAR) FROM tbl_pinjaman_d WHERE IDPINJAM = A.IDPINJM_H) SUDAH_DIBAYAR, 
				FORMAT((A.LAMA_ANGSURAN - (SELECT COUNT(IDPINJ_D) FROM tbl_pinjaman_d INNER JOIN tbl_pinjaman_h ON  tbl_pinjaman_h.IDPINJM_H = tbl_pinjaman_d.IDPINJAM WHERE tbl_pinjaman_d.IDPINJAM = A.IDPINJM_H)),0) SISA_ANGSURAN,
				FORMAT(A.PINJ_SISA,0) SISA_TAGIHAN,
				A.USERNAME,
				B.NAMA NAMA_ANGGOTA, B.ALAMAT, B.lat, B.lng,
				C.JNS_PINJ AS JNS_PINJ, FORMAT(A.JUMLAH,0) JUMLAH, FORMAT(A.BIAYA_ADMIN,0) BIAYA_ADMIN,
				FORMAT(A.BIAYA_ASURANSI,0) BIAYA_ASURANSI, A.LAMA_ANGSURAN, 
				A.ANGSURAN_DASAR,
				(SELECT SUM(DENDA_RP) FROM tbl_pinjaman_d WHERE IDPINJAM = A.IDPINJM_H) BIAYARESET, 
				(SELECT SUM(BIAYA_KOLEKTOR) FROM tbl_pinjaman_d WHERE IDPINJAM = A.IDPINJM_H) BIAYAKOLEKTOR,
				A.PINJ_BASIL_DASAR BASIL_DASAR,
				A.PINJ_RP_ANGSURAN TOTALPINJAMAN,
				E.KODECABANG,F.NAMA AS NAMACABANG,
				G.STATUS AS STATUSANGGUNAN,
				A.NO_JAMINAN,
				H.NAMAJAMINAN
				FROM
				tbl_pinjaman_h A
				LEFT JOIN
				m_anggota B ON A.ANGGOTA_ID = B.IDANGGOTA
				LEFT JOIN
				jns_pinjm C ON A.BARANG_ID = C.IDAKUN
				LEFT JOIN
				tbl_pinjaman_d D ON A.IDPINJM_H = D.IDPINJAM
				LEFT JOIN
				m_user E ON A.USERNAME = E.USERNAME
				LEFT JOIN
				m_cabang F ON E.KODECABANG = F.KODE
				LEFT JOIN 
				m_anggunan G ON A.IDPINJM_H = G.IDPINJM_H 
				LEFT JOIN
				jns_jaminan H ON A.JENIS_JAMINAN = H.IDJAMINAN
				WHERE '$con_keyword' AND A.ISAPPROVE = 1  $koncabang $wheretrgl 
				GROUP BY
				A.IDPINJM_H
				ORDER BY
				DATE(A.TGL_PINJ) DESC, A.IDPINJM_H DESC LIMIT $startid, $dataperpage";
							
		$query  = $this->db->query($sql); 
		$result = $query->result();
		// $sqls = $this->db->last_query();
		if(!$result){
			return $ci->DBHelper->generateEmptyResult();
		}
		
		return $ci->DBHelper->generateResult($result, $sql, "A.IDPINJM_H", $page, $dataperpage, $datastart, $dataend);
	}
	 
}