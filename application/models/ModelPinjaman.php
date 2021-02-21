<?php

class ModelPinjaman extends CI_Model {

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
		
		$sql = sprintf("SELECT 
						A.IDPINJM_H, DATE_FORMAT(A.TGL_PINJ, '%s') TGL_PINJ,
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
						E.KODECABANG,F.NAMA AS NAMACABANG
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
						WHERE %s AND A.ISAPPROVE = 1 %s %s
						GROUP BY
						A.IDPINJM_H
						ORDER BY
						DATE(A.TGL_PINJ) DESC, A.IDPINJM_H DESC",
						'%d/%m/%Y', $con_keyword, $koncabang, $wheretrgl
					);
							
		$query  = $this->db->query($sql." LIMIT ".$startid.", ".$dataperpage);
		$result = $query->result();
		
		if(!$result){
			return $ci->DBHelper->generateEmptyResult();
		}
		
		return $ci->DBHelper->generateResult($result, $sql, "IDPINJM_H", $page, $dataperpage, $datastart, $dataend);
	}
	
	public function getDataTableAngsuran($keyword, $dataperpage, $page, $koncabang, $wheretrgl){

		$ci			=& get_instance();
		$ci->load->model('DBHelper');
		
		$con_keyword = $keyword == "" ? "1=1" : "(B.NAMA LIKE '%".$keyword."%')";
		$startid	 = ($page * 1 - 1) * $dataperpage;
		$datastart	 = $startid + 1;
		$dataend	 = $datastart + $dataperpage - 1;
		
		$sql = sprintf("SELECT 
		A.IDPINJM_H, DATE_FORMAT(A.TGL_PINJ, '%s') TGL_PINJ,
		A.LUNAS, FORMAT(A.JUMLAH, 0) AS JUMLAH, A.LAMA_ANGSURAN,
		FORMAT((A.JUMLAH / A.LAMA_ANGSURAN),0) AS ANGSURAN_DASAR,
		FORMAT(A.PINJ_BASIL_DASAR,0) AS MARGIN_DASAR,
		FORMAT(A.PINJ_RP_ANGSURAN,0) AS ANGSURAN_PERBULAN,
		B.NAMA NAMA_ANGGOTA, B.ALAMAT,D.KODECABANG,E.NAMA AS NAMACABANG 
		FROM
		tbl_pinjaman_h A
		LEFT JOIN
		m_anggota B ON A.ANGGOTA_ID = B.IDANGGOTA
		LEFT JOIN
		m_user D ON A.USERNAME = D.USERNAME
		LEFT JOIN
		m_cabang E ON D.KODECABANG = E.KODE
		WHERE %s AND A.LUNAS = 'Belum' AND A.ISAPPROVE = 1 %s %s
		ORDER BY
		DATE(A.TGL_PINJ) DESC, A.IDPINJM_H DESC",
		'%d/%m/%Y', $con_keyword, $koncabang, $wheretrgl
		);
							
		$query = $this->db->query($sql." LIMIT ".$startid.", ".$dataperpage);
		$result = $query->result();
		
		if(!$result){
			return $ci->DBHelper->generateEmptyResult();
		}
		
		return $ci->DBHelper->generateResult($result, $sql, "IDPINJM_H", $page, $dataperpage, $datastart, $dataend);
	}
	
	public function getDataTableLunas($keyword, $dataperpage, $page, $koncabang, $wheretrgl){

		$ci			=& get_instance();
		$ci->load->model('DBHelper');
		
		$con_keyword = $keyword == "" ? "1=1" : "(B.NAMA LIKE '%".$keyword."%')";
		$startid	 = ($page * 1 - 1) * $dataperpage;
		$datastart	 = $startid + 1;
		$dataend	 = $datastart + $dataperpage - 1;
		
		$sql = sprintf("SELECT 
						A.IDPINJM_H, DATE_FORMAT(A.TGL_PINJ, '%s') TGL_PINJ,
						A.LUNAS, DATE_FORMAT(DATE_ADD(A.TGL_PINJ, INTERVAL A.LAMA_ANGSURAN MONTH), '%s') JATUH_TEMPO,
						A.LAMA_ANGSURAN, FORMAT(A.PINJ_TOTAL,0) TOTAL_TAGIHAN,
						FORMAT(SUM(D.DENDA_RP),0) JML_DENDA,
						FORMAT(A.PINJ_DIBAYAR,0) SUDAH_DIBAYAR,
						B.NAMA NAMA_ANGGOTA, B.ALAMAT,E.KODECABANG,F.NAMA AS NAMACABANG
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
						WHERE A.LUNAS LIKE 'Lunas' AND %s %s %s
						GROUP BY
						A.IDPINJM_H
						ORDER BY
						DATE(A.TGL_PINJ) DESC, A.IDPINJM_H DESC",
						'%d/%m/%Y', '%d/%m/%Y', $con_keyword, $koncabang, $wheretrgl
					);
							
		$query  = $this->db->query($sql." LIMIT ".$startid.", ".$dataperpage);
		$result = $query->result();
		
		if(!$result){
			return $ci->DBHelper->generateEmptyResult();
		}
		
		return $ci->DBHelper->generateResult($result, $sql, "IDPINJM_H", $page, $dataperpage, $datastart, $dataend);
	}
	
	public function getDataTableRekapAngsuran($keyword, $dataperpage, $page, $koncabang, $wheretrgl){

		$ci			=& get_instance();
		$ci->load->model('DBHelper');
		
		$con_keyword = $keyword == "" ? "1=1" : "(B.NAMA LIKE '%".$keyword."%')";
		$startid	 = ($page * 1 - 1) * $dataperpage;
		$datastart	 = $startid + 1;
		$dataend	 = $datastart + $dataperpage - 1;
		
		$sql = sprintf("SELECT 
						A.IDPINJM_H, 
						DATE_FORMAT(A.TGL_PINJ, '%s') TGL_PINJ,
						DATE_FORMAT(D.TGL_BAYAR, '%s') TGL_BAYAR,
						B.NAMA NAMA_ANGGOTA,
						FORMAT(A.PINJ_TOTAL,0) TOTAL_TAGIHAN,
						FORMAT(A.PINJ_RP_ANGSURAN,0) JML_ANGSURANS,
						FORMAT(D.JUMLAH_BAYAR,0) JML_ANGSURAN,
						(SELECT 
						FORMAT(((A.PINJ_TOTAL) - (SUM(PD.JUMLAH_BAYAR))), 0)
						FROM tbl_pinjaman_d PD
						WHERE A.IDPINJM_H = PD.IDPINJAM AND PD.ANGSURAN_KE <= D.ANGSURAN_KE
						) AS SISA_TAGIHAN, 
						D.ANGSURAN_KE, A.LAMA_ANGSURAN, 
						(A.LAMA_ANGSURAN - D.ANGSURAN_KE) SISA_ANGSURAN,
						E.KODECABANG,F.NAMA AS NAMACABANG
						FROM
						tbl_pinjaman_d D
						LEFT JOIN
						tbl_pinjaman_h A ON A.IDPINJM_H = D.IDPINJAM
						LEFT JOIN
						m_anggota B ON A.ANGGOTA_ID = B.IDANGGOTA
						LEFT JOIN
						m_user E ON A.USERNAME = E.USERNAME
						LEFT JOIN
						m_cabang F ON E.KODECABANG = F.KODE	
						WHERE %s %s %s
						ORDER BY
						D.TGL_BAYAR DESC, A.IDPINJM_H DESC",
						'%d/%m/%Y', '%d/%m/%Y', 
						$con_keyword, $koncabang, $wheretrgl
					);
							
		$query  = $this->db->query($sql." LIMIT ".$startid.", ".$dataperpage);
		$result = $query->result();
		
		if(!$result){
			return $ci->DBHelper->generateEmptyResult();
		}
		
		return $ci->DBHelper->generateResult($result, $sql, "IDPINJM_H", $page, $dataperpage, $datastart, $dataend);
	}
}