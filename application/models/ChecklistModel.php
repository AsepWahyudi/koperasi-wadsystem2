<?php
class ChecklistModel extends CI_Model {

	public function __construct(){
		parent::__construct(); 
		$this->load->database();
	}
	
	public function getDataTable($keyword, $dataperpage, $page){

		$ci =& get_instance();
		$ci->load->model('DBHelper');
		
		$con_keyword = $keyword == "" ? "1=1" : "m_anggota.NAMA LIKE '%".$keyword."%'";
		$startid	 = ($page * 1 - 1) * $dataperpage;
		$datastart	 = $startid + 1;
		$dataend	 = $datastart + $dataperpage - 1;
		
		if($this->session->userdata("wad_level") == "admin")
		{
			$con_keyword .= "";
		}
		else
		{
			$con_keyword .= " AND m_anggota.KODECABANG = '".$this->session->userdata('wad_kodecabang')."'";
		}
		
		$basequery	= "SELECT m_cabang.NAMA AS NAMACABANG, m_anggota.FILE_PIC, m_anggota.IDANGGOTA, m_anggota.NOREK, m_anggota.NAMA, 
					   '' AS NAMABANK, m_anggota.JK, DATE_FORMAT(m_anggota.TGL_LAHIR, '%d/%m/%Y') AS TGL_LAHIR,
					   TIMESTAMPDIFF(YEAR, m_anggota.TGL_LAHIR, CURDATE()) AS USIA,
					   m_anggota.ALAMAT, DATE_FORMAT(m_anggota.TGL_DAFTAR, '%d/%m/%Y') AS TGL_DAFTAR, m_anggota.AKTIF
					   FROM m_anggota INNER JOIN m_cabang ON m_anggota.KODECABANG = m_cabang.KODE
					   WHERE $con_keyword AND m_anggota.AKTIF IS NULL OR m_anggota.AKTIF = '' AND m_anggota.JABATAN NOT IN ('3')   
					   ORDER BY m_anggota.IDANGGOTA";	
							   
		$query  = $this->db->query($basequery." LIMIT ".$startid.", ".$dataperpage);
		$result = $query->result();
		
		if(!$result){
			return $ci->DBHelper->generateEmptyResult();
		}
		
		return $ci->DBHelper->generateResult($result, $basequery, "IDANGGOTA", $page, $dataperpage, $datastart, $dataend);
		
	}
	
	public function getDetailAnggota($idanggota){
		$basequery	=	sprintf("SELECT * FROM m_anggota WHERE IDANGGOTA = %s LIMIT 1", $idanggota);			
		return $this->db->query($basequery);
	}
	
	public function getDataTablePinjaman($keyword, $dataperpage, $page){

		$ci =& get_instance();
		$ci->load->model('DBHelper');
		
		$con_keyword = $keyword == "" ? "1=1" : "(B.NAMA LIKE '%".$keyword."%')";
		$startid	 = ($page * 1 - 1) * $dataperpage;
		$datastart	 = $startid + 1;
		$dataend	 = $datastart + $dataperpage - 1;
		
		$sql = sprintf("SELECT 
						A.IDPINJM_H, DATE_FORMAT(A.TGL_PINJ, '%s') TGL_PINJ,
						A.LUNAS, A.BUNGA, 
						FORMAT(B.PINJ_RP_ANGSURAN,0) JML_ANGSURAN,
						FORMAT(SUM(D.DENDA_RP),0) JML_DENDA,
						FORMAT(B.PINJ_TOTAL,0) TOTAL_TAGIHAN,
						FORMAT(B.PINJ_DIBAYAR,0) SUDAH_DIBAYAR,
						FORMAT((A.LAMA_ANGSURAN - COUNT(D.IDPINJ_D)),0) SISA_ANGSURAN,
						FORMAT(B.PINJ_SISA,0) SISA_TAGIHAN,
						A.USERNAME,
						B.NAMA NAMA_ANGGOTA, B.ALAMAT,
						C.JNS_PINJ, FORMAT(A.JUMLAH,0) JUMLAH, FORMAT(A.BIAYA_ADMIN,0) BIAYA_ADMIN,
						FORMAT(A.BIAYA_ASURANSI,0) BIAYA_ASURANSI, A.LAMA_ANGSURAN, 
						FORMAT((A.JUMLAH/A.LAMA_ANGSURAN),0) ANGSURAN_DASAR,
						FORMAT(B.PINJ_BASIL_DASAR,0) BASIL_DASAR
						FROM
						tbl_pinjaman_h A
						LEFT JOIN
						m_anggota B ON A.ANGGOTA_ID = B.IDANGGOTA
						LEFT JOIN
						jns_pinjm C ON  C.IDJNS_PINJ = A.IDPINJM_H
						LEFT JOIN
						tbl_pinjaman_d D ON A.IDPINJM_H = D.IDPINJAM
						WHERE %s AND ISAPPROVE = 0
						GROUP BY
						A.IDPINJM_H
						ORDER BY
						DATE(A.TGL_PINJ) DESC, A.IDPINJM_H DESC",
						'%d/%m/%Y', $con_keyword
					);
							
		$query  = $this->db->query($sql." LIMIT ".$startid.", ".$dataperpage);
		$result = $query->result();
		
		if(!$result){
			return $ci->DBHelper->generateEmptyResult();
		}
		
		return $ci->DBHelper->generateResult($result, $sql, "IDPINJM_H", $page, $dataperpage, $datastart, $dataend);
	}
	
	public function getDataPinjaman($idpinjam){ 
		
		$sql = sprintf("SELECT 
						A.IDPINJM_H, A.TGL_PINJ,
						A.LAMA_ANGSURAN,
						A.USERNAME, A.BUNGA,
						B.NAMA NAMA_ANGGOTA, B.ALAMAT,
						B.FILE_PIC, B.IDANGGOTA,
						C.JNS_PINJ, A.JUMLAH, A.BIAYA_ADMIN,
						A.BIAYA_ASURANSI, A.NAMA_SDR, A.HUB_SDR, A.TELP_SDR, A.ALAMAT_SDR,
						FORMAT((A.JUMLAH/A.LAMA_ANGSURAN),0) ANGSURAN_DASAR,
						A.PINJ_BASIL_DASAR BASIL_DASAR
						FROM
						tbl_pinjaman_h A
						LEFT JOIN
						m_anggota B ON A.ANGGOTA_ID = B.IDANGGOTA
						LEFT JOIN
						jns_pinjm C ON A.BARANG_ID = C.IDJNS_PINJ
						WHERE A.IDPINJM_H = %s AND ISAPPROVE = 0 ",
						$idpinjam
					);
		return $this->db->query($sql);
	}
	
	public function getDatasimpanan($keyword, $dataperpage, $page,$koncabang,$tgl){

		$ci =& get_instance();
		$ci->load->model('DBHelper');
		
		$con_keyword = $keyword == "" ? "1=1" : "(A.KODECABANG = '".$keyword."')";
		$startid	 = ($page * 1 - 1) * $dataperpage;
		$datastart	 = $startid + 1;
		$dataend	 = $datastart + $dataperpage - 1;

		$basequery = sprintf("SELECT A.ID_TRX_SIMP,DATE_FORMAT(A.TGL_TRX, '%s')AS TGL,
							  A.JUMLAH,
						      A.NAMA_PENYETOR,
							  B.JENIS_TRANSAKSI,
							  C.NAMA AS NAMACABANG,
							  A.KETERANGAN   
							  FROM transaksi_simp A
							  LEFT JOIN jns_akun B ON A.ID_JENIS=B.IDAKUN
							  LEFT JOIN m_cabang C ON A.KODECABANG=C.KODE
							  WHERE A.STATUS='0' AND A.KOLEKTOR='0' AND A.DK='D' AND DATE(A.TGL_TRX)='%s' AND %s 
							  ORDER BY A.ID_TRX_SIMP ASC"
							  , '%d/%m/%Y'
							  ,$tgl, $con_keyword
							);	
		//echo $basequery;							
		$query  = $this->db->query($basequery." LIMIT ".$startid.", ".$dataperpage);
		$result = $query->result();
		
		if(!$result){
			return $ci->DBHelper->generateEmptyResult();
		}
		
		return $ci->DBHelper->generateResult($result, $basequery, "ID_TRX_SIMP", $page, $dataperpage, $datastart, $dataend);
		
	}

	public function getDatasimpkolektor($keyword, $dataperpage, $page,$koncabang,$tgl){

		$ci =& get_instance(); 
		$ci->load->model('DBHelper');
		
		$con_keyword = $keyword == "" ? "1=1" : "(NAMA_PENYETOR LIKE '%".$keyword."%')";
		$startid	 = ($page * 1 - 1) * $dataperpage;
		$datastart	 = $startid + 1;
		$dataend	 = $datastart + $dataperpage - 1;

		$basequery = sprintf("SELECT A.ID_TRX_SIMP,DATE_FORMAT(A.TGL_TRX, '%s')AS TGL,
							  FORMAT(A.JUMLAH,0)AS JUMLAH,
							  A.NAMA_PENYETOR,
							  B.JENIS_TRANSAKSI,
							  C.NAMA AS NAMACABANG,
							  A.KETERANGAN
							  FROM transaksi_simp A
							  LEFT JOIN jns_akun B ON A.ID_JENIS=B.IDAKUN
							  LEFT JOIN m_cabang C ON A.KODECABANG=C.KODE
							  WHERE A.STATUS='0' AND A.KOLEKTOR='1' AND A.DK='D' AND DATE(TGL_TRX)='%s' AND %s %s 
							  ORDER BY A.ID_TRX_SIMP ASC"
							  , '%d/%m/%Y'
							  , $tgl,$con_keyword,$koncabang
							);	
		//echo $basequery;							
		$query  = $this->db->query($basequery." LIMIT ".$startid.", ".$dataperpage);
		$result = $query->result();
		
		if(!$result){
			return $ci->DBHelper->generateEmptyResult();
		}
		
		return $ci->DBHelper->generateResult($result, $basequery, "ID_TRX_SIMP", $page, $dataperpage, $datastart, $dataend);
		
	}
}