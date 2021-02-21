<?php

class ModelLaporan extends CI_Model
{
	private	$cabang;
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->cabang = $this->session->userdata('wad_cabang');
	}

	public function getDataTable($keyword, $dataperpage, $page)
	{
		
		$con_keyword  = $keyword == "" ? "1=1" : "(NAMA LIKE '%" . $keyword . "%')";
		$startid      = ($page * 1 - 1) * $dataperpage;
		$datastart    = $startid + 1;
		$dataend      = $datastart + $dataperpage - 1;
		 
		
		if($this->session->userdata("wad_level") == "admin")
		{
			$con_keyword .= "";
			
			if($this->session->userdata('wad_cabang') === true OR $this->session->userdata('wad_cabang') !="" ){
				
				$con_keyword .=" AND KODECABANG = '" .$this->session->userdata('wad_cabang'). "' ";
				
			}else{
				$con_keyword .= "";
			}
		}
		else
		{
			 
			if($this->session->userdata('wad_cabang') === true OR $this->session->userdata('wad_cabang') !="" ){
				
				$con_keyword .=" AND KODECABANG = '" .$this->session->userdata('wad_cabang'). "' ";
				
			}else{
				
				$con_keyword .=" AND KODECABANG = '" .$this->session->userdata('wad_kodecabang'). "' ";
			}
		}
		// $basequery = sprintf("SELECT FILE_PIC, IDANGGOTA, NOREK, NAMA, '' AS NAMABANK, JK, CONCAT_WS('', KODEPUSAT, '.', KODECABANG, '.', 			NO_ANGGOTA, '') KODE_ANGGOTA, DATE_FORMAT(TGL_LAHIR, '%s') AS TGL_LAHIR, TIMESTAMPDIFF(YEAR, TGL_LAHIR, CURDATE()) AS USIA, ALAMAT, DATE_FORMAT(TGL_DAFTAR, '%s') AS TGL_DAFTAR, AKTIF, KODEPUSAT, KODECABANG, NO_ANGGOTA FROM m_anggota WHERE %s AND AKTIF <> '' AND JABATAN NOT IN ('3') ORDER BY IDANGGOTA", '%d/%m/%Y', '%d/%m/%Y', $con_keyword);
		
		$basequery = sprintf("SELECT FILE_PIC, IDANGGOTA, NOREK, NAMA, '' AS NAMABANK, JK, CONCAT_WS('', KODEPUSAT, '.', (SELECT CONVERT(KODECABANG, CHAR) FROM m_cabang WHERE m_cabang.KODE = m_anggota.KODECABANG), '.', 			NO_ANGGOTA, '') KODE_ANGGOTA, DATE_FORMAT(TGL_LAHIR, '%s') AS TGL_LAHIR, TIMESTAMPDIFF(YEAR, TGL_LAHIR, CURDATE()) AS USIA, ALAMAT, DATE_FORMAT(TGL_DAFTAR, '%s') AS TGL_DAFTAR, AKTIF, KODEPUSAT, KODECABANG, NO_ANGGOTA FROM m_anggota WHERE %s AND AKTIF <> '' AND JABATAN NOT IN ('3') ORDER BY IDANGGOTA", '%d/%m/%Y', '%d/%m/%Y', $con_keyword);

		return $this->prosesData($basequery, $startid, $dataperpage, $page, $datastart, $dataend, 'IDANGGOTA');
	}

	public function getDataTableAktif($keyword, $dataperpage, $page)
	{
		$con_keyword = $keyword == "" ? "1=1" : "(NAMA LIKE '%" . $keyword . "%')";
		$startid     = ($page * 1 - 1) * $dataperpage;
		$datastart   = $startid + 1;
		$dataend     = $datastart + $dataperpage - 1;
		  
		if($this->session->userdata("wad_level") == "admin")
		{
			$con_keyword .= "";
			
			if($this->session->userdata('wad_cabang') === true OR $this->session->userdata('wad_cabang') !="" ){
				
				$con_keyword .=" AND KODECABANG = '" .$this->session->userdata('wad_cabang'). "' ";
				
			}else{
				$con_keyword .= "";
			}
		}
		else
		{ 
			
			if($this->session->userdata('wad_cabang') === true OR $this->session->userdata('wad_cabang') !="" ){
				
				$con_keyword .=" AND KODECABANG = '" .$this->session->userdata('wad_cabang'). "' ";
				
			}else{
				
				$con_keyword .=" AND KODECABANG = '" .$this->session->userdata('wad_kodecabang'). "' ";
			}
		}

		$basequery = sprintf("SELECT FILE_PIC, IDANGGOTA, NOREK, NAMA, '' AS NAMABANK, JK,
						      CONCAT_WS('', KODEPUSAT, '.', (SELECT CONVERT(KODECABANG, CHAR) FROM m_cabang WHERE m_cabang.KODE = m_anggota.KODECABANG) , '.', NO_ANGGOTA, '') KODE_ANGGOTA,
						      DATE_FORMAT(TGL_LAHIR, '%s') AS TGL_LAHIR,
							  TIMESTAMPDIFF(YEAR, TGL_LAHIR, CURDATE()) AS USIA,
						      ALAMAT, DATE_FORMAT(TGL_DAFTAR, '%s') AS TGL_DAFTAR, AKTIF, KODEPUSAT, KODECABANG, NO_ANGGOTA
							  FROM m_anggota
							  WHERE %s AND AKTIF = 'Y' AND JABATAN NOT IN ('3')
						      ORDER BY IDANGGOTA",
						      '%d/%m/%Y',
			                  '%d/%m/%Y',
			                  $con_keyword
							);

		return $this->prosesData($basequery, $startid, $dataperpage, $page, $datastart, $dataend, 'IDANGGOTA');
	}

	public function getDataTableNonAktif($keyword, $dataperpage, $page)
	{
		$con_keyword  = $keyword == "" ? "1=1" : "(NAMA LIKE '%" . $keyword . "%')";
		$startid      = ($page * 1 - 1) * $dataperpage;
		$datastart    = $startid + 1;
		$dataend      = $datastart + $dataperpage - 1;
		 
		if($this->session->userdata("wad_level") == "admin")
		{
			$con_keyword .= "";
			
			if($this->session->userdata('wad_cabang') === true OR $this->session->userdata('wad_cabang') !="" ){
				
				$con_keyword .=" AND KODECABANG = '" .$this->session->userdata('wad_cabang'). "' ";
				
			}else{
				$con_keyword .= "";
			}
		}
		else
		{
			
			
			if($this->session->userdata('wad_cabang') === true OR $this->session->userdata('wad_cabang') !="" ){
				
				$con_keyword .=" AND KODECABANG = '" .$this->session->userdata('wad_cabang'). "' ";
				
			}else{
				
				$con_keyword .=" AND KODECABANG = '" .$this->session->userdata('wad_kodecabang'). "' ";
			}
		}
		$basequery	= sprintf("SELECT FILE_PIC, IDANGGOTA, NOREK, NAMA, '' AS NAMABANK, JK,
							   CONCAT_WS('', KODEPUSAT, '.', (SELECT CONVERT(KODECABANG, CHAR) FROM m_cabang WHERE m_cabang.KODE = m_anggota.KODECABANG), '.', NO_ANGGOTA, '') KODE_ANGGOTA,
							   DATE_FORMAT(TGL_LAHIR, '%s') AS TGL_LAHIR,
							   TIMESTAMPDIFF(YEAR, TGL_LAHIR, CURDATE()) AS USIA,
							   ALAMAT, DATE_FORMAT(TGL_DAFTAR, '%s') AS TGL_DAFTAR, AKTIF, KODEPUSAT, KODECABANG, NO_ANGGOTA
							   FROM m_anggota
							   WHERE %s AND AKTIF = 'N' AND JABATAN NOT IN ('3')
							   ORDER BY IDANGGOTA",
							   '%d/%m/%Y',
						       '%d/%m/%Y',
							   $con_keyword
							);

		return $this->prosesData($basequery, $startid, $dataperpage, $page, $datastart, $dataend, 'IDANGGOTA');
	}

	public function getDataTableNonAnggota($keyword, $dataperpage, $page)
	{
		$con_keyword  = $keyword == "" ? "1=1" : "(NAMA LIKE '%" . $keyword . "%')";
		$startid      = ($page * 1 - 1) * $dataperpage;
		$datastart    = $startid + 1;
		$dataend      = $datastart + $dataperpage - 1;
		 
		if($this->session->userdata("wad_level") == "admin")
		{
			$con_keyword .= "";
			
			if($this->session->userdata('wad_cabang') === true OR $this->session->userdata('wad_cabang') !="" ){
				
				$con_keyword .=" AND KODECABANG = '" .$this->session->userdata('wad_cabang'). "' ";
				
			}else{
				$con_keyword .= "";
			}
		}
		else
		{
			
			
			if($this->session->userdata('wad_cabang') === true OR $this->session->userdata('wad_cabang') !="" ){
				
				$con_keyword .=" AND KODECABANG = '" .$this->session->userdata('wad_cabang'). "' ";
				
			}else{
				
				$con_keyword .=" AND KODECABANG = '" .$this->session->userdata('wad_kodecabang'). "' ";
			}
		}
		
		$basequery = sprintf("SELECT FILE_PIC, IDANGGOTA, NOREK, NAMA, '' AS NAMABANK, JK,
							  CONCAT_WS('', KODEPUSAT, '.', (SELECT CONVERT(KODECABANG, CHAR) FROM m_cabang WHERE m_cabang.KODE = m_anggota.KODECABANG), '.', NO_ANGGOTA, '') KODE_ANGGOTA,
							  DATE_FORMAT(TGL_LAHIR, '%s') AS TGL_LAHIR,
							  TIMESTAMPDIFF(YEAR, TGL_LAHIR, CURDATE()) AS USIA,
							  ALAMAT, EMAIL, DATE_FORMAT(TGL_DAFTAR, '%s') AS TGL_DAFTAR, AKTIF, KODEPUSAT, KODECABANG, NO_ANGGOTA
							  FROM m_anggota
							  WHERE %s AND JABATAN = '3'
							  ORDER BY IDANGGOTA",
							  '%d/%m/%Y',
						      '%d/%m/%Y',
							  $con_keyword
							);

		return $this->prosesData($basequery, $startid, $dataperpage, $page, $datastart, $dataend, 'IDANGGOTA');
	}

	public function getDataKasAnggota($keyword, $dataperpage, $page)
	{
		$con_keyword  = $keyword == "" ? "1=1" : "(A.NAMA LIKE '%" . $keyword . "%')";
		$startid      = ($page * 1 - 1) * $dataperpage;
		$datastart    = $startid + 1;
		$dataend      = $datastart + $dataperpage - 1;
		 
		if($this->session->userdata("wad_level") == "admin")
		{
			$con_keyword .= "";
			
			if($this->session->userdata('wad_cabang') === true OR $this->session->userdata('wad_cabang') !="" ){
				
				$con_keyword .=" AND A.KODECABANG = '" .$this->session->userdata('wad_cabang'). "' ";
				
			}else{
				$con_keyword .= "";
			}
		}
		else
		{  
			if($this->session->userdata('wad_cabang') === true OR $this->session->userdata('wad_cabang') !="" ){
				
				$con_keyword .=" AND A.KODECABANG = '" .$this->session->userdata('wad_cabang'). "' ";
				
			}else{
				
				$con_keyword .=" AND A.KODECABANG = '" .$this->session->userdata('wad_kodecabang'). "' ";
			}
		}
		if($this->session->userdata("wad_level") == "admin")
		{
			$basequery = sprintf("SELECT A.IDANGGOTA, A.FILE_PIC, A.NAMA, JK, A.ALAMAT, A.TELP, A.JABATAN,
								  CONCAT_WS('', A.KODEPUSAT, '.', (SELECT CONVERT(KODECABANG, CHAR) FROM m_cabang WHERE m_cabang.KODE = A.KODECABANG), '.', A.NO_ANGGOTA, '') KODE_ANGGOTA,
								  (SELECT GROUP_CONCAT('', JS.IDAKUN, '|', JS.JNS_SIMP, '') 
								  FROM jns_simpan JS
								  WHERE JS.TAMPIL = 'Y') JENIS_SIMPANAN,
								  (SELECT GROUP_CONCAT('', JS.IDAKUN, '|', MS.SALDO, '') 
								  FROM jns_simpan JS
								  LEFT JOIN m_anggota_simp MS ON (JS.IDAKUN = MS.IDJENIS_SIMP)
								  WHERE JS.TAMPIL = 'Y'  AND MS.IDANGGOTA = A.IDANGGOTA ) SALDO_SIMPANAN,                    
								  A.ISCREDIT, A.PINJ_POKOK, A.PINJ_TOTAL, A.PINJ_DIBAYAR, B.PINJ_SISA SISATAGIHAN,    
								  COUNT(B.IDPINJM_H) JML_PINJAM, B.IS_RESET,
								  DATE_FORMAT(DATE_ADD(B.TGL_PINJ, INTERVAL B.LAMA_ANGSURAN MONTH), '%s') JATUH_TEMPO,
								  DATE_FORMAT(DATE_ADD(B.TGL_PINJ, INTERVAL B.LAMA_ANGSURAN MONTH), '%s') TGL_TEMPO,
								  DATE_FORMAT(NOW(), '%s') TGL_NOW,(B.PINJ_RP_ANGSURAN-B.PINJ_TOTAL) SELISIHPINJAMAN,
								  (SELECT SUM(DENDA_RP) FROM tbl_pinjaman_d WHERE IDPINJAM = B.IDPINJM_H) BIAYARESET,
								  (SELECT SUM(BIAYA_KOLEKTOR) FROM tbl_pinjaman_d WHERE IDPINJAM = B.IDPINJM_H) BIAYAKOLEKTOR 
								  FROM m_anggota A
								  LEFT JOIN
								  tbl_pinjaman_h B ON A.IDANGGOTA = B.ANGGOTA_ID
								  WHERE A.AKTIF = 'Y' AND A.JABATAN NOT IN ('3') AND %s 
								  GROUP BY A.IDANGGOTA
								  ORDER BY IDANGGOTA", '%d/%m/%Y', '%Y%m%d', '%Y%m%d', $con_keyword
								); 
		}
		else
		{
			$basequery = sprintf("SELECT A.IDANGGOTA, A.FILE_PIC, A.NAMA, JK, A.ALAMAT, A.TELP, A.JABATAN,
								  CONCAT_WS('', A.KODEPUSAT, '.', (SELECT CONVERT(KODECABANG, CHAR) FROM m_cabang WHERE m_cabang.KODE = A.KODECABANG), '.', A.NO_ANGGOTA, '') KODE_ANGGOTA,
								  (SELECT GROUP_CONCAT('', JS.IDAKUN, '|', JS.JNS_SIMP, '') 
								  FROM jns_simpan JS
								  WHERE JS.TAMPIL = 'Y') JENIS_SIMPANAN,
								  (SELECT GROUP_CONCAT('', JS.IDAKUN, '|', MS.SALDO, '') 
								  FROM jns_simpan JS
								  LEFT JOIN m_anggota_simp MS ON (JS.IDAKUN = MS.IDJENIS_SIMP)
								  WHERE JS.TAMPIL = 'Y'  AND MS.IDANGGOTA = A.IDANGGOTA ) SALDO_SIMPANAN,                    
								  A.ISCREDIT, A.PINJ_POKOK, A.PINJ_TOTAL, A.PINJ_DIBAYAR, B.PINJ_SISA SISATAGIHAN,    
								  COUNT(B.IDPINJM_H) JML_PINJAM, B.IS_RESET,
								  DATE_FORMAT(DATE_ADD(B.TGL_PINJ, INTERVAL B.LAMA_ANGSURAN MONTH), '%s') JATUH_TEMPO,
								  DATE_FORMAT(DATE_ADD(B.TGL_PINJ, INTERVAL B.LAMA_ANGSURAN MONTH), '%s') TGL_TEMPO,
								  DATE_FORMAT(NOW(), '%s') TGL_NOW,(B.PINJ_RP_ANGSURAN-B.PINJ_TOTAL) SELISIHPINJAMAN,
								  (SELECT SUM(DENDA_RP) FROM tbl_pinjaman_d WHERE IDPINJAM = B.IDPINJM_H) BIAYARESET,
								  (SELECT SUM(BIAYA_KOLEKTOR) FROM tbl_pinjaman_d WHERE IDPINJAM = B.IDPINJM_H) BIAYAKOLEKTOR 
								  FROM m_anggota A
								  LEFT JOIN
								  tbl_pinjaman_h B ON A.IDANGGOTA = B.ANGGOTA_ID
								  WHERE A.AKTIF = 'Y' AND A.JABATAN NOT IN ('3') AND %s AND A.KODECABANG = '".$this->session->userdata('wad_kodecabang')."'
								  GROUP BY A.IDANGGOTA
								  ORDER BY IDANGGOTA", '%d/%m/%Y', '%Y%m%d', '%Y%m%d', $con_keyword
								);
		}
		 
		return $this->prosesData($basequery, $startid, $dataperpage, $page, $datastart, $dataend, 'IDANGGOTA');
	}

	public function getDataJatuhTempo($keyword, $dataperpage, $page, $post)
	{
		$con_keyword  = $keyword == "" ? "1=1" : "(B.NAMA LIKE '%" . $keyword . "%')";
		$startid      = ($page * 1 - 1) * $dataperpage;
		$datastart    = $startid + 1;
		$dataend      = $datastart + $dataperpage - 1;
		 
		if($this->session->userdata("wad_level") == "admin")
		{
			$con_keyword .= "";
			
			if($this->session->userdata('wad_cabang') === true OR $this->session->userdata('wad_cabang') !="" ){
				
				$con_keyword .=" AND A.KODECABANG = '" .$this->session->userdata('wad_cabang'). "' ";
				
			}else{
				$con_keyword .= "";
			}
		}
		else
		{
			
			
			if($this->session->userdata('wad_cabang') === true OR $this->session->userdata('wad_cabang') !="" ){
				
				$con_keyword .=" AND A.KODECABANG = '" .$this->session->userdata('wad_cabang'). "' ";
				
			}else{
				
				$con_keyword .=" AND A.KODECABANG = '" .$this->session->userdata('wad_kodecabang'). "' ";
			}
		}
		
		$tgl          = explode('-', $post['tgl']);
		$having_date  = " AND DATE(TGL_ANGSURAN_SELANJUTNYA) BETWEEN '" . date('Y-m-d', strtotime(trim($tgl[0]))) . "' AND '" . date('Y-m-d',       strtotime(trim($tgl[1]))) . "'";

		$basequery = sprintf("SELECT 
							  A.IDPINJM_H, A.NOREK, A.LAMA_ANGSURAN,       
							  DATE_FORMAT(A.TGL_PINJ, '%s') TGL_PINJ,
							  DATE_FORMAT(DATE_ADD(A.TGL_PINJ, INTERVAL A.LAMA_ANGSURAN MONTH), '%s') JATUH_TEMPO,
							  A.PINJ_TOTAL, A.PINJ_DIBAYAR, A.PINJ_SISA,
							  IF(A.PINJ_RP_ANGSURAN > A.PINJ_SISA, A.PINJ_SISA, A.PINJ_RP_ANGSURAN) PINJ_RP_ANGSURAN,
							  B.IDANGGOTA, B.NAMA, B.TELP,     
							  CONCAT_WS('', B.KODEPUSAT, '.', (SELECT CONVERT(KODECABANG, CHAR) FROM m_cabang WHERE m_cabang.KODE = B.KODECABANG), '.', B.NO_ANGGOTA, '') KODE_ANGGOTA,       
							  A.LUNAS,   
							  (SELECT PD.TGL_BAYAR FROM tbl_pinjaman_d PD WHERE PD.IDPINJAM = A.IDPINJM_H ORDER BY PD.TGL_BAYAR DESC, IDPINJ_D DESC    LIMIT 1) ANGSURAN_TERAKHIR,   
							  DATE(CONCAT_WS('', DATE_FORMAT(NOW(), '%s'), DATE_FORMAT(A.TGL_PINJ, '%s'))) TGL_ANGSURAN_SELANJUTNYA,
							  CONCAT_WS('', DATE_FORMAT(A.TGL_PINJ, '%s'), DATE_FORMAT(NOW(), '%s')) TGL_PEMBAYARAN_ANGSURAN
							  FROM
							  tbl_pinjaman_h A
							  LEFT JOIN
							  m_anggota B ON A.ANGGOTA_ID = B.IDANGGOTA  
							  WHERE %s 
							  AND A.LUNAS LIKE 'Belum'      
							  AND A.PINJ_SISA > 0
							  HAVING
							  IF(ISNULL(ANGSURAN_TERAKHIR), '0000-00-00', DATE(DATE_ADD(ANGSURAN_TERAKHIR, INTERVAL 1 MONTH))) < TGL_ANGSURAN_SELANJUTNYA      
							  AND DATE(NOW()) > TGL_ANGSURAN_SELANJUTNYA      
							  %s
							  ORDER BY 
							  TGL_ANGSURAN_SELANJUTNYA",
							  '%d/%m/%Y',
							  '%d/%m/%Y',
							  '%Y-%m-',
							  '%d',
							  '%d',
							  '/%m/%Y',
							  $con_keyword,
							  $having_date
							);

		return $this->prosesData($basequery, $startid, $dataperpage, $page, $datastart, $dataend, 'IDPINJM_H');
	}

	public function getDataJatuhTempoHarusLunas($keyword, $dataperpage, $page, $post)
	{
		$con_keyword  = $keyword == "" ? "1=1" : "(B.NAMA LIKE '%" . $keyword . "%')";
		$startid      = ($page * 1 - 1) * $dataperpage;
		$datastart    = $startid + 1;
		$dataend      =	$datastart + $dataperpage - 1;
		 
		if($this->session->userdata("wad_level") == "admin")
		{
			$con_keyword .= "";
			
			if($this->session->userdata('wad_cabang') === true OR $this->session->userdata('wad_cabang') !="" ){
				
				$con_keyword .=" AND A.KODECABANG = '" .$this->session->userdata('wad_cabang'). "' ";
				
			}else{
				$con_keyword .= "";
			}
		}
		else
		{
			
			
			if($this->session->userdata('wad_cabang') === true OR $this->session->userdata('wad_cabang') !="" ){
				
				$con_keyword .=" AND A.KODECABANG = '" .$this->session->userdata('wad_cabang'). "' ";
				
			}else{
				
				$con_keyword .=" AND A.KODECABANG = '" .$this->session->userdata('wad_kodecabang'). "' ";
			}
		}
		
		$tgl          = explode('-', $post['tgl']);
		$con_keyword .= !empty($post['tgl']) ? " AND DATE(DATE_ADD(A.TGL_PINJ, INTERVAL A.LAMA_ANGSURAN MONTH)) BETWEEN '" . date('Y-m-d', strtotime(trim($tgl[0]))) . "' AND '" . date('Y-m-d', strtotime(trim($tgl[1]))) . "'" : "";

		$basequery = sprintf("SELECT 
							  A.IDPINJM_H, A.NOREK, A.LAMA_ANGSURAN,       
							  DATE_FORMAT(A.TGL_PINJ, '%s') TGL_PINJ,
							  DATE_FORMAT(DATE_ADD(A.TGL_PINJ, INTERVAL A.LAMA_ANGSURAN MONTH), '%s') JATUH_TEMPO,   
							  A.PINJ_TOTAL, A.PINJ_DIBAYAR, A.PINJ_SISA,
							  B.IDANGGOTA, B.NAMA,        
							  CONCAT_WS('', B.KODEPUSAT, '.', (SELECT CONVERT(KODECABANG, CHAR) FROM m_cabang WHERE m_cabang.KODE = B.KODECABANG), '.', B.NO_ANGGOTA, '') KODE_ANGGOTA,       
							  A.LUNAS
							  FROM
							  tbl_pinjaman_h A
							  LEFT JOIN
							  m_anggota B ON A.ANGGOTA_ID = B.IDANGGOTA  
							  WHERE %s 
							  AND DATE(DATE_ADD(A.TGL_PINJ, INTERVAL A.LAMA_ANGSURAN MONTH)) < DATE(NOW()) 
							  ORDER BY IDPINJM_H", '%d/%m/%Y', '%d/%m/%Y', $con_keyword
							);

		return $this->prosesData($basequery, $startid, $dataperpage, $page, $datastart, $dataend, 'IDANGGOTA');
	}

	public function getDataKreditMacet($keyword, $dataperpage, $page, $post)
	{
		$con_keyword  = $keyword == "" ? "1=1" : "(B.NAMA LIKE '%" . $keyword . "%')";
		$startid      = ($page * 1 - 1) * $dataperpage;
		$datastart    = $startid + 1;
		$dataend      = $datastart + $dataperpage - 1;
		 
		if($this->session->userdata("wad_level") == "admin")
		{
			$con_keyword .= "";
			
			if($this->session->userdata('wad_cabang') === true OR $this->session->userdata('wad_cabang') !="" ){
				
				$con_keyword .=" AND A.KODECABANG = '" .$this->session->userdata('wad_cabang'). "' ";
				
			}else{
				$con_keyword .= "";
			}
		}
		else
		{
			
			
			if($this->session->userdata('wad_cabang') === true OR $this->session->userdata('wad_cabang') !="" ){
				
				$con_keyword .=" AND A.KODECABANG = '" .$this->session->userdata('wad_cabang'). "' ";
				
			}else{
				
				$con_keyword .=" AND A.KODECABANG = '" .$this->session->userdata('wad_kodecabang'). "' ";
			}
		}
		
		$tgl          = !empty($post['tgl']) ? $post['tgl'] : date('Y-m-d');
		//$con_keyword	.=	!empty($post['tgl']) ? 	" AND DATE_FORMAT(DATE_ADD(A.TGL_PINJ, INTERVAL (A.LAMA_ANGSURAN + 3) MONTH), '%Y') < '". date('Y', strtotime(trim($tgl))) . "'" . 
		//											" AND DATE_FORMAT(DATE_ADD(A.TGL_PINJ, INTERVAL (A.LAMA_ANGSURAN + 3) MONTH), '%Y%m') < '". date('Ym', strtotime(trim($tgl))) . "'" : "";

		$basequery	= sprintf("SELECT 
							   A.IDPINJM_H, A.NOREK, A.LAMA_ANGSURAN,       
							   DATE_FORMAT(A.TGL_PINJ, '%s') TGL_PINJ,
							   DATE_FORMAT(DATE_ADD(A.TGL_PINJ, INTERVAL A.LAMA_ANGSURAN MONTH), '%s') JATUH_TEMPO,   
							   A.PINJ_TOTAL, A.PINJ_DIBAYAR, A.PINJ_SISA,
							   B.IDANGGOTA, B.NAMA,        
							   CONCAT_WS('', B.KODEPUSAT, '.', (SELECT CONVERT(KODECABANG, CHAR) FROM m_cabang WHERE m_cabang.KODE = B.KODECABANG), '.', B.NO_ANGGOTA, '') KODE_ANGGOTA,       
							   A.LUNAS,        
							   DATEDIFF(DATE(NOW()), DATE(DATE_ADD(A.TGL_PINJ, INTERVAL A.LAMA_ANGSURAN MONTH))) LAMA_MACET 
							   FROM tbl_pinjaman_h A
							   LEFT JOIN
							   m_anggota B ON B.IDANGGOTA = A.ANGGOTA_ID
							   WHERE %s 
							   AND A.LUNAS = 'Belum'
							   AND DATE(DATE_ADD(A.TGL_PINJ, INTERVAL (A.LAMA_ANGSURAN + 3) MONTH)) < DATE(NOW())
							   ORDER BY 
							   DATE_ADD(A.TGL_PINJ, INTERVAL A.LAMA_ANGSURAN MONTH) ",
							   '%d/%m/%Y',
							   '%d/%m/%Y',
							   $con_keyword
							);

		return $this->prosesData($basequery, $startid, $dataperpage, $page, $datastart, $dataend, 'IDANGGOTA');
	}

	public function getTransaksiKas($keyword, $dataperpage, $page, $post)
	{
		$con_keyword  = $keyword == "" ? "1=1" : "1=1";
		$startid      = ($page * 1 - 1) * $dataperpage;
		$datastart    = $startid + 1;
		$dataend      = $datastart + $dataperpage - 1;
		 
		if($this->session->userdata("wad_level") == "admin")
		{
			$con_keyword .= "";
			
			if($this->session->userdata('wad_cabang') === true OR $this->session->userdata('wad_cabang') !="" ){
				
				$con_keyword .=" AND A.KODECABANG = '" .$this->session->userdata('wad_cabang'). "' ";
				
			}else{
				$con_keyword .= "";
			}
		}
		else
		{
			
			
			if($this->session->userdata('wad_cabang') === true OR $this->session->userdata('wad_cabang') !="" ){
				
				$con_keyword .=" AND A.KODECABANG = '" .$this->session->userdata('wad_cabang'). "' ";
				
			}else{
				
				$con_keyword .=" AND A.KODECABANG = '" .$this->session->userdata('wad_kodecabang'). "' ";
			}
		}
		$tgl          = explode('-', $post['tgl']);
		$con_keyword .= !empty($post['tgl']) ? " AND DATE(A.TGL) BETWEEN '" . date('Y-m-d', strtotime(str_replace('/', '-', trim($tgl[0])))) . "' AND '" . date('Y-m-d', strtotime(str_replace('/', '-', trim($tgl[1])))) . "'" : "";

		$basequery = sprintf("SELECT A.IDTRAN_KAS, DATE_FORMAT(A.TGL, '%s') TANGGAL,
							  B.JENIS_TRANSAKSI, 
							  C.NAMA_KAS DARI_KAS,
							  D.NAMA_KAS UNTUK_KAS,
							  A.JUMLAH, A.DK
							  FROM 
							  transaksi_kas A
							  LEFT JOIN
							  jns_akun B ON A.JENIS_TRANS = B.IDAKUN
							  LEFT JOIN
							  jenis_kas C ON A.DARI_KAS_ID = C.IDAKUN
							  LEFT JOIN
							  jenis_kas D ON A.UNTUK_KAS_ID = D.IDAKUN
							  WHERE %s 
							  ORDER BY A.TGL ASC", '%d/%m/%Y', $con_keyword
							);

		return $this->prosesData($basequery, $startid, $dataperpage, $page, $datastart, $dataend, 'IDTRAN_KAS');
	}

	public function getJurnalTrans($keyword, $dataperpage, $page, $post)
	{
		// $con_keyword  = $keyword == "" ? "1=1" : "1=1";
		$con_keyword  = $keyword == "" ? "" : "(B.JENIS_TRANSAKSI LIKE '%" . $keyword . "%')";
		$startid      = ($page * 1 - 1) * $dataperpage;
		$datastart    = $startid + 1;
		$dataend      = $datastart + $dataperpage - 1;
		// $con_keyword .= ($this->cabang != "") ? " AND C.KODECABANG = '" . $this->cabang . "' " : "";
		$tgl          = explode('-', $post['tgl']);
		$con_keyword .= !empty($post['tgl']) ? " DATE(C.TANGGAL) BETWEEN '" . date('Y-m-d', strtotime(str_replace('/', '-', trim($tgl[0])))) . "' AND '" . date('Y-m-d', strtotime(str_replace('/', '-', trim($tgl[1])))) . "'" : "";
		 
		if($post['plhcabang'] == "" AND $post['idakun'] == "" )
		{
			
			if($this->session->userdata("wad_level") == "admin")
			{
				$con_keyword .= "";
				 
			}
			else
			{ 
				$con_keyword .=" AND C.KODECABANG = '" .$this->session->userdata('wad_cabang'). "' ";
			}
			
		}
		else
		{
			// $con_keyword .=" AND B.KODECABANG = '" .$post['plhcabang']. "' ";
			if($post['plhcabang'] != "" AND $post['idakun'] == "" )
			{
				$con_keyword .=" AND C.KODECABANG = '" .$post['plhcabang']. "'";
			}
			if($post['plhcabang'] == "" AND $post['idakun'] != "" )
			{
				$con_keyword .=	" AND A.IDAKUN = '". $post['idakun'] ."'";
			}
			if($post['plhcabang'] != "" AND $post['idakun'] != "" )
			{
				$con_keyword .=" AND C.KODECABANG = '" .$post['plhcabang']. "'";
				$con_keyword .=	" AND A.IDAKUN = '". $post['idakun'] ."'";
				 
			}  
		}
		  
		$basequery = "SELECT A.IDVTRANSAKSI,
					  DATE_FORMAT(C.TANGGAL, '%d/%m/%Y') TANGGAL, C.KODE_JURNAL, 
					  C.ID_TRX_SIMP, C.ID_TRX_KAS, C.IDPINJ_D, C.REFERENSI, 
					  B.KODE_AKTIVA, B.JENIS_TRANSAKSI, C.KETERANGAN, A.DEBET, A.KREDIT 
					  FROM vtransaksi_dt A 
					  LEFT JOIN jns_akun B ON A.IDAKUN = B.IDAKUN
					  LEFT JOIN vtransaksi C ON A.IDVTRANSAKSI = C.IDVTRANSAKSI
					  WHERE $con_keyword AND C.KODE_JURNAL !='PL'
					  ORDER BY A.IDVTRANSAKSI ASC";
							
		return $this->prosesData($basequery, $startid, $dataperpage, $page, $datastart, $dataend, 'IDVTRANSAKSI');
	}

	public function getBukubesar($keyword, $dataperpage, $page, $post)
	{
		
		// plhcabang: 
		// idakun: 
		// tgl: 01/11/2020 - 24/11/2020
		// page: 1
		// dataperpage: 10
		// urltarget: laporan/bukubesar/data
		// dataperpage: 10
		// keyword: 

		// $con_keyword  = $keyword == "" ? "1=1" : "(C.JENIS_TRANSAKSI LIKE '%" . $keyword . "%')";
		$con_keyword  = $keyword == "" ? "" : "(C.JENIS_TRANSAKSI LIKE '%" . $keyword . "%')";
		$startid      = ($page * 1 - 1) * $dataperpage;
		$datastart    = $startid + 1;
		$dataend      = $datastart + $dataperpage - 1;
		
		$tgl          = explode('-', $post['tgl']);
		$con_keyword .= !empty($post['tgl']) ? " DATE(B.TANGGAL) BETWEEN '" . date('Y-m-d', strtotime(str_replace('/', '-', trim($tgl[0])))) . "' AND '" . date('Y-m-d', strtotime(str_replace('/', '-', trim($tgl[1])))) . "'" : "";
		
		if($post['plhcabang'] == "" AND $post['idakun'] == "" )
		{
			
			if($this->session->userdata("wad_level") == "admin")
			{
				$con_keyword .= "";
				 
			}
			else
			{ 
				$con_keyword .=" AND B.KODECABANG = '" .$this->session->userdata('wad_cabang'). "' ";
			}
			
		}
		else
		{
			// $con_keyword .=" AND B.KODECABANG = '" .$post['plhcabang']. "' ";
			if($post['plhcabang'] != "" AND $post['idakun'] == "" )
			{
				$con_keyword .=" AND B.KODECABANG = '" .$post['plhcabang']. "'";
			}
			if($post['plhcabang'] == "" AND $post['idakun'] != "" )
			{
				$con_keyword .=	" AND A.IDAKUN = '". $post['idakun'] ."'";
			}
			if($post['plhcabang'] != "" AND $post['idakun'] != "" )
			{
				$con_keyword .=" AND B.KODECABANG = '" .$post['plhcabang']. "'";
				$con_keyword .=	" AND A.IDAKUN = '". $post['idakun'] ."'";
				 
			} 
			
		}
		 
		
		
		
		// $con_keyword .=	$post['idakun'] != "" ? " AND A.IDAKUN = '" . $post['idakun'] . "'" : "";

		// $basequery = "SELECT A.IDDETAIL, A.DEBET, A.KREDIT, B.KETERANGAN, 
				      // DATE_FORMAT(B.TANGGAL, '%d/%m/%Y') TANGGAL, C.JENIS_TRANSAKSI, C.AKUN, 
				      // C.KODE_AKTIVA FROM vtransaksi_dt A    
				      // LEFT JOIN vtransaksi B ON A.IDVTRANSAKSI = B.IDVTRANSAKSI
				      // LEFT JOIN jns_akun C ON A.IDAKUN = C.IDAKUN
				      // WHERE $con_keyword AND (A.DEBET <> 0 OR A.KREDIT <> 0) AND C.AKUN !='Aktiva'
				      // ORDER BY DATE(B.TANGGAL) ASC";
		
		/* $basequery = "SELECT A.IDDETAIL, A.DEBET, A.KREDIT, B.KETERANGAN, 
				      DATE_FORMAT(B.TANGGAL, '%d/%m/%Y') TANGGAL, C.JENIS_TRANSAKSI, C.AKUN, 
				      C.KODE_AKTIVA FROM vtransaksi_dt A    
				      LEFT JOIN vtransaksi B ON A.IDVTRANSAKSI = B.IDVTRANSAKSI  
				      LEFT JOIN jns_akun C ON A.IDAKUN = C.IDAKUN
				      WHERE $con_keyword AND (A.DEBET <> 0 OR A.KREDIT <> 0) AND C.AKUN !='Aktiva' AND C.KODE_AKTIVA !='40311' OR C.KODE_AKTIVA ='1030103' OR C.KODE_AKTIVA ='1030101' OR C.KODE_AKTIVA='1020106'
				      ORDER BY DATE(B.TANGGAL) ASC"; */
					  
					  
		$basequery = "SELECT A.IDDETAIL, A.DEBET, A.KREDIT, B.KETERANGAN, 
				      DATE_FORMAT(B.TANGGAL, '%d/%m/%Y') TANGGAL, C.JENIS_TRANSAKSI, C.AKUN, 
				      C.KODE_AKTIVA FROM vtransaksi_dt A    
				      LEFT JOIN vtransaksi B ON A.IDVTRANSAKSI = B.IDVTRANSAKSI  
				      LEFT JOIN jns_akun C ON A.IDAKUN = C.IDAKUN
				      WHERE $con_keyword AND (A.DEBET <> 0 OR A.KREDIT <> 0)  
				      ORDER BY DATE(B.TANGGAL) ASC";		  
					  
		return $this->prosesData($basequery, $startid, $dataperpage, $page, $datastart, $dataend, 'IDDETAIL');
	}

	public function getNeraca($keyword, $dataperpage, $page, $post)
	{
		$startid      = ($page * 1 - 1) * $dataperpage;
		$datastart    = $startid + 1;
		$dataend      = $datastart + $dataperpage - 1;
		 
		if($this->session->userdata("wad_level") == "admin")
		{
			$con_keyword = "";
			
			if($this->session->userdata('wad_cabang') === true OR $this->session->userdata('wad_cabang') !="" ){
				
				$con_keyword =" AND C.KODECABANG = '" .$this->session->userdata('wad_cabang'). "' ";
				
			}else{
				$con_keyword = "";
			}
		}
		else
		{
			
			
			if($this->session->userdata('wad_cabang') === true OR $this->session->userdata('wad_cabang') !="" ){
				
				$con_keyword =" AND C.KODECABANG = '" .$this->session->userdata('wad_cabang'). "' ";
				
			}else{
				
				$con_keyword =" AND C.KODECABANG = '" .$this->session->userdata('wad_kodecabang'). "' ";
			}
		}
		
		$tgl          = explode('-', $post['tgl']);
		$con_keyword .= !empty($post['tgl']) ? " AND DATE(C.TANGGAL) BETWEEN '" . date('Y-m-d', strtotime(str_replace('/', '-', trim($tgl[0])))) . "' AND '" . date('Y-m-d', strtotime(str_replace('/', '-', trim($tgl[1])))) . "'" : "";

		$basequery = sprintf("SELECT 
						A.IDAKUN, 
						A.PARENT,
						A.KODE_AKTIVA,
						A.JENIS_TRANSAKSI, 
						A.AKUN,
						(
						SELECT
						IF(ISNULL(SUM(B.DEBET)), 0, SUM(B.DEBET))
						FROM   
						vtransaksi_dt B
						JOIN 
						vtransaksi C ON B.IDVTRANSAKSI = C.IDVTRANSAKSI     
						WHERE        
						A.IDAKUN = B.IDAKUN %s
						) AS DEBET, 
						(
						SELECT
						IF(ISNULL(SUM(B.KREDIT)), 0, SUM(B.KREDIT))
						FROM   
						vtransaksi_dt B
						JOIN 
						vtransaksi C ON B.IDVTRANSAKSI = C.IDVTRANSAKSI     
						WHERE        
						A.IDAKUN = B.IDAKUN %s
						) AS KREDIT 
						FROM jns_akun A
						WHERE 
						A.AKUN IN ('Aktiva', 'Pasiva', 'Equity') ",
						$con_keyword,
						$con_keyword
					);

		return $this->prosesData($basequery, $startid, $dataperpage, $page, $datastart, $dataend, 'IDAKUN');
	}

	public function getNeracaLama($keyword, $dataperpage, $page, $post)
	{
		$con_keyword	=	$keyword == "" ? "1=1" : "1=1";
		$startid      = ($page * 1 - 1) * $dataperpage;
		$datastart    = $startid + 1;
		$dataend      = $datastart + $dataperpage - 1;
		 
		if($this->session->userdata("wad_level") == "admin")
		{
			$con_keyword = "";
			
			if($this->session->userdata('wad_cabang') === true OR $this->session->userdata('wad_cabang') !="" ){
				
				$con_keyword =" AND B.KODECABANG = '" .$this->session->userdata('wad_cabang'). "' ";
				
			}else{
				$con_keyword = "";
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
		$tgl          = explode('-', $post['tgl']);
		$con_keyword .= !empty($post['tgl']) ? " AND DATE(B.TGL) BETWEEN '" . date('Y-m-d', strtotime(trim($tgl[0]))) . "' AND '" . date('Y-m-d', strtotime(trim($tgl[1]))) . "'" : "";

		$basequery = sprintf("SELECT A.IDAKUN, A.KODE_AKTIVA,
							A.JENIS_TRANSAKSI, A.AKUN,
							IF(ISNULL(SUM(B.DEBET)), 0, SUM(B.DEBET)) AS DEBET,    
							IF(ISNULL(SUM(B.KREDIT)), 0, SUM(B.KREDIT)) AS KREDIT,
							LEFT(A.KODE_AKTIVA, 1) KODELEFT,
							CAST(MID(A.KODE_AKTIVA, 2, (LENGTH(A.KODE_AKTIVA) - 1)) AS INT)  KODERIGHT
							FROM jns_akun A
							LEFT JOIN 
							v_transaksi B ON (A.IDAKUN = B.TRANSAKSI %s)
							WHERE A.AKTIF = 'Y' 
							GROUP BY
							A.IDAKUN
							ORDER BY
							KODELEFT ASC, KODERIGHT ASC",
							$con_keyword
						);

		return $this->prosesData($basequery, $startid, $dataperpage, $page, $datastart, $dataend, 'IDAKUN');
	}

	public function getKasSimpanan($keyword, $dataperpage, $page, $post)
	{
		$con_keyword  = $keyword == "" ? "1=1" : "1=1";
		$startid      = ($page * 1 - 1) * $dataperpage;
		$datastart    = $startid + 1;
		$dataend      = $datastart + $dataperpage - 1;
		 
		if($this->session->userdata("wad_level") == "admin")
		{
			$con_keyword .= "";
			
			if($this->session->userdata('wad_cabang') === true OR $this->session->userdata('wad_cabang') !="" ){
				
				$con_keyword .=" AND B.KODECABANG = '" .$this->session->userdata('wad_cabang'). "' ";
				
			}else{
				$con_keyword .= "";
			}
		}
		else
		{
			
			
			if($this->session->userdata('wad_cabang') === true OR $this->session->userdata('wad_cabang') !="" ){
				
				$con_keyword .=" AND B.KODECABANG = '" .$this->session->userdata('wad_cabang'). "' ";
				
			}else{
				
				$con_keyword .=" AND B.KODECABANG = '" .$this->session->userdata('wad_kodecabang'). "' ";
			}
		}
		$tgl          = explode('-', $post['tgl']);
		$con_keyword .= !empty($post['tgl']) ? " AND DATE(B.TGL_TRX) BETWEEN '" . date('Y-m-d', strtotime(str_replace('/', '-', trim($tgl[0])))) . "' AND '" . date('Y-m-d', strtotime(str_replace('/', '-', trim($tgl[1])))) . "'" : "";

		$basequery = sprintf("SELECT A.IDJENIS_SIMP, A.IDAKUN, A.JNS_SIMP,
							SUM(IF(B.AKUN = 'Setoran', B.JUMLAH, 0)) SETORAN,
							SUM(IF(B.AKUN = 'Penarikan', B.JUMLAH, 0)) PENARIKAN
							FROM jns_simpan A 
							LEFT JOIN
							transaksi_simp B ON (A.IDAKUN = B.ID_JENIS AND %s)
							WHERE A.TAMPIL = 'Y'
							GROUP BY
							A.IDJENIS_SIMP", $con_keyword);

		return $this->prosesData($basequery, $startid, $dataperpage, $page, $datastart, $dataend, 'IDJENIS_SIMP');
	}

	public function getKasPinjaman($keyword, $dataperpage, $page, $post)
	{
		$con_keyword  = $keyword == "" ? "1=1" : "1=1";
		$startid      = ($page * 1 - 1) * $dataperpage;
		$datastart    = $startid + 1;
		$dataend      = $datastart + $dataperpage - 1;
		 
		if($this->session->userdata("wad_level") == "admin")
		{
			$con_keyword .= "";
			
			if($this->session->userdata('wad_cabang') === true OR $this->session->userdata('wad_cabang') !="" ){
				
				$con_keyword .=" AND A.KODECABANG = '" .$this->session->userdata('wad_cabang'). "' ";
				
			}else{
				$con_keyword .= "";
			}
		}
		else
		{
			
			
			if($this->session->userdata('wad_cabang') === true OR $this->session->userdata('wad_cabang') !="" ){
				
				$con_keyword .=" AND A.KODECABANG = '" .$this->session->userdata('wad_cabang'). "' ";
				
			}else{
				
				$con_keyword .=" AND A.KODECABANG = '" .$this->session->userdata('wad_kodecabang'). "' ";
			}
		}
		$tgl          = explode('-', $post['tgl']);
		$con_keyword .= !empty($post['tgl']) ? " AND DATE(A.TGL_PINJ) BETWEEN '" . date('Y-m-d', strtotime(str_replace('/', '-', trim($tgl[0])))) . "' AND '" . date('Y-m-d', strtotime(str_replace('/', '-', trim($tgl[1])))) . "'" : "";

		$basequery = sprintf("SELECT A.IDPINJM_H,
							(SELECT COUNT(PH.IDPINJM_H) FROM tbl_pinjaman_h PH WHERE %s) JML_PINJAM2,
							COUNT(A.IDPINJM_H) JML_PINJAM, 
							A.LUNAS,
							SUM(A.JUMLAH) JUMLAH, 
							SUM(A.PINJ_RP_ANGSURAN) PINJAMAN_POKOK,       
							SUM(A.PINJ_POKOK_DIBAYAR + A.PINJ_BASIL_DASAR) TAGIHAN,
							(SELECT SUM(B.DENDA_RP+B.BIAYA_KOLEKTOR) FROM tbl_pinjaman_d B WHERE B.IDPINJAM = A.IDPINJM_H) DENDA,
							(SELECT SUM(B.JUMLAH_BAYAR) FROM tbl_pinjaman_d B WHERE B.IDPINJAM = A.IDPINJM_H) DIBAYAR, 
							(SUM(A.PINJ_RP_ANGSURAN) - (SELECT SUM(B.JUMLAH_BAYAR) FROM tbl_pinjaman_d B WHERE B.IDPINJAM = A.IDPINJM_H)) SISATAGIHAN
							FROM 
							tbl_pinjaman_h A          
							WHERE 
							%s
							GROUP BY
							A.LUNAS", str_replace('A.', 'PH.', $con_keyword),  $con_keyword);
							
							
							// SELECT A.IDPINJM_H, 
							// (SELECT COUNT(PH.IDPINJM_H) FROM tbl_pinjaman_h PH WHERE 1=1 AND DATE(PH.TGL_PINJ) BETWEEN '2020-09-01' AND '2020-09-27') JML_PINJAM2, 	
							// COUNT(A.IDPINJM_H) JML_PINJAM, 
							// A.LUNAS, 
							// SUM(A.PINJ_TOTAL) PINJAMAN_POKOK, 
							// SUM(A.PINJ_TOTAL + ((A.PINJ_TOTAL * A.BUNGA) / 100))/SUM(A.LAMA_ANGSURAN) TAGIHAN, 
							// (SELECT SUM(B.DENDA_RP) FROM tbl_pinjaman_d B WHERE B.IDPINJAM = A.IDPINJM_H) DENDA, 
							// (SELECT SUM(B.JUMLAH_BAYAR) FROM tbl_pinjaman_d B WHERE B.IDPINJAM = A.IDPINJM_H) DIBAYAR,
							// (SUM(A.PINJ_TOTAL) - (SELECT SUM(B.JUMLAH_BAYAR) FROM tbl_pinjaman_d B WHERE B.IDPINJAM = A.IDPINJM_H)) SISATAGIHAN
							// FROM tbl_pinjaman_h A 
							// WHERE 1=1 AND DATE(A.TGL_PINJ) BETWEEN '2020-09-01' AND '2020-09-27' GROUP BY A.LUNAS

		return $this->prosesData($basequery, $startid, $dataperpage, $page, $datastart, $dataend, 'IDPINJM_H');
	}

	public function getSaldoKas($keyword, $dataperpage, $page, $post)
	{
		$startid   = ($page * 1 - 1) * $dataperpage;
		$datastart = $startid + 1;
		$dataend   = $datastart + $dataperpage - 1;
		 
		if($this->session->userdata("wad_level") == "admin")
		{
			$con_keyword = "";
			
			if($this->session->userdata('wad_cabang') === true OR $this->session->userdata('wad_cabang') !="" ){
				
				$con_keyword =" AND B.KODECABANG = '" .$this->session->userdata('wad_cabang'). "' ";
				
			}else{
				$con_keyword = "";
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
		$tgl          = explode('-', $post['tgl']);
		$con_keyword .= !empty($post['tgl']) ? " AND DATE(B.TGL) BETWEEN '" . date('Y-m-d', strtotime(trim($tgl[0]))) . "' AND '" . date('Y-m-d', strtotime(trim($tgl[1]))) . "'" : "";

		$basequery = sprintf("SELECT A.IDAKUN, A.KODE_AKTIVA,
							A.JENIS_TRANSAKSI, A.AKUN,
							IF(ISNULL(SUM(B.DEBET)), 0, SUM(B.DEBET)) AS DEBET,    
							IF(ISNULL(SUM(B.KREDIT)), 0, SUM(B.KREDIT)) AS KREDIT,
							LEFT(A.KODE_AKTIVA, 1) KODELEFT,
							CAST(MID(A.KODE_AKTIVA, 2, (LENGTH(A.KODE_AKTIVA) - 1)) AS INT)  KODERIGHT
							FROM jns_akun A
							LEFT JOIN 
							v_transaksi B ON (A.IDAKUN = B.TRANSAKSI %s)
							WHERE A.AKTIF = 'Y' 
							GROUP BY
							A.IDAKUN
							ORDER BY
							KODELEFT ASC, KODERIGHT ASC",
							$con_keyword
						);

		return $this->prosesData($basequery, $startid, $dataperpage, $page, $datastart, $dataend, 'IDAKUN');
	}

	public function getRekeningKoran($keyword, $dataperpage, $page, $post)
	{
		$con_keyword  = $keyword == "" ? "1=1" : "(B.NAMA LIKE '%" . $keyword . "%' OR CONCAT_WS('', B.KODEPUSAT, '.', B.KODECABANG, '.', B.NO_ANGGOTA, '') LIKE '%" . $keyword . "%' )";
		$startid      = ($page * 1 - 1) * $dataperpage;
		$datastart    = $startid + 1;
		$dataend      = $datastart + $dataperpage - 1;
		 
		if($this->session->userdata("wad_level") == "admin")
		{
			$con_keyword .= "";
			
			if($this->session->userdata('wad_cabang') === true OR $this->session->userdata('wad_cabang') !="" ){
				
				$con_keyword .=" AND B.KODECABANG = '" .$this->session->userdata('wad_cabang'). "' ";
				
			}else{
				$con_keyword .= "";
			}
		}
		else
		{
			
			
			if($this->session->userdata('wad_cabang') === true OR $this->session->userdata('wad_cabang') !="" ){
				
				$con_keyword .=" AND B.KODECABANG = '" .$this->session->userdata('wad_cabang'). "' ";
				
			}else{
				
				$con_keyword .=" AND B.KODECABANG = '" .$this->session->userdata('wad_kodecabang'). "' ";
			}
		}
		$tgl          = !empty($post['tgl']) ? explode('-', $post['tgl']) : explode('-', date('Y/m/01 H:i:s') . " - " . date('Y/m/d H:i:s'));
		$con_keyword .= " AND DATE(A.TGL_TRX) BETWEEN '" . date('Y-m-d', strtotime(trim($tgl[0]))) . "' AND '" . date('Y-m-d', strtotime(trim($tgl[1]))) . "'";

		$basequery = sprintf("SELECT 
							A.ID_TRX_SIMP,
							DATE_FORMAT(A.TGL_TRX, '%s') TANGGAL,
							A.JUMLAH,
							A.KETERANGAN,
							A.AKUN,
							B.NAMA,
							CONCAT_WS('', B.KODEPUSAT, '.', B.KODECABANG, '.', B.NO_ANGGOTA, '') KODE_ANGGOTA,
							C.JENIS_TRANSAKSI,
							D.SALDO
							FROM
							transaksi_simp A
							LEFT JOIN
							m_anggota B ON A.ID_ANGGOTA = B.IDANGGOTA
							LEFT JOIN
							jns_akun C ON A.ID_JENIS = C.IDAKUN
							LEFT JOIN 
							m_anggota_simp D ON A.ID_TRX_SIMP = D.ID_ANG_SIMP AND A.ID_ANGGOTA = D.IDANGGOTA
							WHERE
							%s
							ORDER BY
							A.TGL_TRX ",
							'%d/%m/%Y',
							$con_keyword
						);

		return $this->prosesData($basequery, $startid, $dataperpage, $page, $datastart, $dataend, 'ID_TRX_SIMP');
	}

	public function getJurnal($keyword, $dataperpage, $page, $post)
	{
		$startid      = ($page * 1 - 1) * $dataperpage;
		$datastart    = $startid + 1;
		$dataend      = $datastart + $dataperpage - 1;
		 
		if($this->session->userdata("wad_level") == "admin")
		{
			$con_keyword = "";
			
			if($this->session->userdata('wad_cabang') === true OR $this->session->userdata('wad_cabang') !="" ){
				
				$con_keyword =" AND B.KODECABANG = '" .$this->session->userdata('wad_cabang'). "' ";
				
			}else{
				$con_keyword = "";
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
		$tgl          = explode('-', $post['tgl']);
		$con_keyword .= " AND DATE(B.TANGGAL) BETWEEN '" . date('Y-m-d', strtotime(trim($tgl[0]))) . "' AND '" . date('Y-m-d', strtotime(trim($tgl[1]))) . "'";

		$basequery = sprintf("SELECT 
							A.IDDETAIL,
							A.IDVTRANSAKSI,
							A.DEBET,
							A.KREDIT,       
							B.KETERANGAN,       
							DATE_FORMAT(B.TANGGAL, '%s') TANGGAL,       
							C.JENIS_TRANSAKSI
							FROM
							vtransaksi_dt A    
							LEFT JOIN
							vtransaksi B ON A.IDVTRANSAKSI = B.IDVTRANSAKSI
							LEFT JOIN
							jns_akun C ON A.IDAKUN = C.IDAKUN
							WHERE %s
							ORDER BY 
							DATE(B.TANGGAL) ASC, A.IDVTRANSAKSI",
							'%d/%m/%Y',
							$con_keyword
						);

		return $this->prosesData($basequery, $startid, $dataperpage, $page, $datastart, $dataend, 'IDDETAIL');
	}

	public function getLabaRugi($keyword, $dataperpage, $page, $post)
	{
		$startid     = ($page * 1 - 1) * $dataperpage;
		$datastart   = $startid + 1;
		$dataend     = $datastart + $dataperpage - 1;
		 
		
		if($this->session->userdata("wad_level") == "admin")
		{
			$con_keyword = "";
			
			if($this->session->userdata('wad_cabang') === true OR $this->session->userdata('wad_cabang') !="" ){
				
				$con_keyword =" AND C.KODECABANG = '" .$this->session->userdata('wad_cabang'). "' ";
				
			}else{
				$con_keyword = "";
			}
		}
		else
		{
			
			
			if($this->session->userdata('wad_cabang') === true OR $this->session->userdata('wad_cabang') !="" ){
				
				$con_keyword =" AND C.KODECABANG = '" .$this->session->userdata('wad_cabang'). "' ";
				
			}else{
				
				$con_keyword =" AND C.KODECABANG = '" .$this->session->userdata('wad_kodecabang'). "' ";
			}
		}
		$tgl         = explode('-', $post['tgl']);
		//$con_keyword .= " AND DATE(B.TGL) BETWEEN '". date('Y-m-d', strtotime(trim($tgl[0]))) ."' AND '". date('Y-m-d', strtotime(trim($tgl[1]))) ."'";
		$con_keyword .= " AND DATE(C.TANGGAL) <= '" . date('Y-m-d', strtotime(str_replace('/', '-', trim($tgl[1])))) . "'";
		
		$basequery = sprintf("SELECT 
							A.IDAKUN,        
							A.PARENT,
							A.KODE_AKTIVA, 
							A.JENIS_TRANSAKSI,       
							A.AKUN,
							(
							SELECT
							IF(ISNULL(SUM(B.DEBET)), 0, SUM(B.DEBET))
							FROM   
							vtransaksi_dt B
							JOIN 
							vtransaksi C ON B.IDVTRANSAKSI = C.IDVTRANSAKSI     
							WHERE        
							A.IDAKUN = B.IDAKUN %s
							) AS DEBET, 
							(
							SELECT
							IF(ISNULL(SUM(B.KREDIT)), 0, SUM(B.KREDIT))
							FROM   
							vtransaksi_dt B
							JOIN 
							vtransaksi C ON B.IDVTRANSAKSI = C.IDVTRANSAKSI     
							WHERE        
							A.IDAKUN = B.IDAKUN %s
							) AS KREDIT,
							0 AS SALDO
							FROM
							jns_akun A        
							WHERE
							(A.AKUN LIKE 'pendapatan' OR A.AKUN LIKE 'beban') AND A.AKTIF = 'Y' ",
							$con_keyword,
							$con_keyword
						);

		return $this->prosesData($basequery, $startid, $dataperpage, $page, $datastart, $dataend, 'IDAKUN');
	}

	public function getKreditPinjaman($keyword, $dataperpage, $page, $post)
	{
		$con_keyword  = $keyword == "" ? "1=1" : "(B.NAMA LIKE '%" . $keyword . "%')";
		$startid      = ($page * 1 - 1) * $dataperpage;
		$datastart    = $startid + 1;
		$dataend      = $datastart + $dataperpage - 1;
		 
		if($this->session->userdata("wad_level") == "admin")
		{
			$con_keyword .= "";
			
			if($this->session->userdata('wad_cabang') === true OR $this->session->userdata('wad_cabang') !="" ){
				
				$con_keyword .=" AND A.KODECABANG = '" .$this->session->userdata('wad_cabang'). "' ";
				
			}else{
				$con_keyword .= "";
			}
		}
		else
		{
			
			
			if($this->session->userdata('wad_cabang') === true OR $this->session->userdata('wad_cabang') !="" ){
				
				$con_keyword .=" AND A.KODECABANG = '" .$this->session->userdata('wad_cabang'). "' ";
				
			}else{
				
				$con_keyword .=" AND A.KODECABANG = '" .$this->session->userdata('wad_kodecabang'). "' ";
			}
		}
		$con_keyword .= isset($post['kredit']) ? " AND A." . $this->tipe_macet($post['kredit']) . " " : "";

		$basequery = sprintf("SELECT
							A.IDPINJM_H,
							DATE_FORMAT(A.TGL_PINJ, '%s') TGL_PINJ,
							DATE_FORMAT(DATE_ADD(A.TGL_PINJ, INTERVAL A.LAMA_ANGSURAN MONTH), '%s') JATUH_TEMPO,
							A.PINJ_TOTAL, A.PINJ_DIBAYAR, A.PINJ_SISA,
							A.IS_RESET,
							B.NAMA NAMA_ANGGOTA
							FROM tbl_pinjaman_h A
							LEFT JOIN
							m_anggota B ON B.IDANGGOTA = A.ANGGOTA_ID
							WHERE %s 
							AND A.PINJ_SISA > 0
							AND A.LUNAS LIKE 'Belum'
							ORDER BY IDPINJM_H",
							'%d/%m/%Y',
							'%d/%m/%Y',
							$con_keyword
						);

		return $this->prosesData($basequery, $startid, $dataperpage, $page, $datastart, $dataend, 'IDPINJM_H');
	}

	public function getKreditLancar($keyword, $dataperpage, $page, $post)
	{
		$con_keyword  = $keyword == "" ? "1=1" : "(B.NAMA LIKE '%" . $keyword . "%')";
		$startid      = ($page * 1 - 1) * $dataperpage;
		$datastart    = $startid + 1;
		$dataend      = $datastart + $dataperpage - 1;
		 
		if($this->session->userdata("wad_level") == "admin")
		{
			$con_keyword .= "";
			
			if($this->session->userdata('wad_cabang') === true OR $this->session->userdata('wad_cabang') !="" ){
				
				$con_keyword .=" AND A.KODECABANG = '" .$this->session->userdata('wad_cabang'). "' ";
				
			}else{
				$con_keyword .= "";
			}
		}
		else
		{
			
			
			if($this->session->userdata('wad_cabang') === true OR $this->session->userdata('wad_cabang') !="" ){
				
				$con_keyword .=" AND A.KODECABANG = '" .$this->session->userdata('wad_cabang'). "' ";
				
			}else{
				
				$con_keyword .=" AND A.KODECABANG = '" .$this->session->userdata('wad_kodecabang'). "' ";
			}
		}
		// $con_keyword	.=	isset($post['kredit']) ? " AND A.". $this->tipe_macet($post['kredit']) ." " : "";

		$basequery = sprintf("SELECT
							A.IDPINJM_H,
							DATE_FORMAT(A.TGL_PINJ, '%s') TGL_PINJ,
							DATE_FORMAT(DATE_ADD(A.TGL_PINJ, INTERVAL A.LAMA_ANGSURAN MONTH), '%s') JATUH_TEMPO,
							DATE_FORMAT(DATE_ADD(A.TGL_PINJ, INTERVAL A.LAMA_ANGSURAN MONTH), '%s') TEMPO_FIRST_NUMB,
							DATE_FORMAT(DATE_ADD(DATE_ADD(A.TGL_PINJ, INTERVAL A.LAMA_ANGSURAN MONTH), INTERVAL A.LAMA_ANGSURAN MONTH), '%s') TEMPO_SECOND_NUMB,
							DATE_FORMAT(DATE_ADD(DATE_ADD(DATE_ADD(A.TGL_PINJ, INTERVAL A.LAMA_ANGSURAN MONTH), INTERVAL A.LAMA_ANGSURAN MONTH), INTERVAL A.LAMA_ANGSURAN MONTH), '%s') TEMPO_THIRD_NUMB,
							DATE_FORMAT(NOW(), '%s') NOW_NUMB, 
							A.PINJ_TOTAL, A.PINJ_DIBAYAR, A.PINJ_SISA,
							A.IS_RESET,
							A.LUNAS,
							B.NAMA NAMA_ANGGOTA
							FROM tbl_pinjaman_h A
							LEFT JOIN
							m_anggota B ON B.IDANGGOTA = A.ANGGOTA_ID
							WHERE %s 
							-- AND A.PINJ_SISA > 0
							AND A.LUNAS LIKE 'Lunas'
							OR %s 
							AND DATE_FORMAT(DATE_ADD(A.TGL_PINJ, INTERVAL A.LAMA_ANGSURAN MONTH), '%s') > DATE_FORMAT(NOW(), '%s')
							ORDER BY IDPINJM_H",
							'%d/%m/%Y',
							'%d/%m/%Y',
							'%Y%m%d',
							'%Y%m%d',
							'%Y%m%d',
							'%Y%m%d',
							$con_keyword,
							$con_keyword,
							'%Y%m%d',
							'%Y%m%d'
						);


		return $this->prosesData($basequery, $startid, $dataperpage, $page, $datastart, $dataend, 'IDPINJM_H');
	}

	public function getKreditMeragukan($keyword, $dataperpage, $page, $post)
	{
		$con_keyword  = $keyword == "" ? "1=1" : "(B.NAMA LIKE '%" . $keyword . "%')";
		$startid      = ($page * 1 - 1) * $dataperpage;
		$datastart    = $startid + 1;
		$dataend      = $datastart + $dataperpage - 1;
		 
		if($this->session->userdata("wad_level") == "admin")
		{
			$con_keyword .= "";
			
			if($this->session->userdata('wad_cabang') === true OR $this->session->userdata('wad_cabang') !="" ){
				
				$con_keyword .=" AND A.KODECABANG = '" .$this->session->userdata('wad_cabang'). "' ";
				
			}else{
				$con_keyword .= "";
			}
		}
		else
		{
			
			
			if($this->session->userdata('wad_cabang') === true OR $this->session->userdata('wad_cabang') !="" ){
				
				$con_keyword .=" AND A.KODECABANG = '" .$this->session->userdata('wad_cabang'). "' ";
				
			}else{
				
				$con_keyword .=" AND A.KODECABANG = '" .$this->session->userdata('wad_kodecabang'). "' ";
			}
		}
		// $con_keyword	.=	isset($post['kredit']) ? " AND A.". $this->tipe_macet($post['kredit']) ." " : "";

		$basequery	= sprintf("SELECT
							A.IDPINJM_H,
							DATE_FORMAT(A.TGL_PINJ, '%s') TGL_PINJ,
							DATE_FORMAT(DATE_ADD(A.TGL_PINJ, INTERVAL A.LAMA_ANGSURAN MONTH), '%s') JATUH_TEMPO,
							DATE_FORMAT(DATE_ADD(A.TGL_PINJ, INTERVAL A.LAMA_ANGSURAN MONTH), '%s') TEMPO_FIRST_NUMB,
							DATE_FORMAT(DATE_ADD(DATE_ADD(A.TGL_PINJ, INTERVAL A.LAMA_ANGSURAN MONTH), INTERVAL A.LAMA_ANGSURAN MONTH), '%s') TEMPO_SECOND_NUMB,
							DATE_FORMAT(DATE_ADD(DATE_ADD(DATE_ADD(A.TGL_PINJ, INTERVAL A.LAMA_ANGSURAN MONTH), INTERVAL A.LAMA_ANGSURAN MONTH), INTERVAL A.LAMA_ANGSURAN MONTH), '%s') TEMPO_THIRD_NUMB,
							DATE_FORMAT(NOW(), '%s') NOW_NUMB, 
							A.PINJ_TOTAL, A.PINJ_DIBAYAR, A.PINJ_SISA,
							A.IS_RESET,
							A.LUNAS,
							B.NAMA NAMA_ANGGOTA
							FROM tbl_pinjaman_h A
							LEFT JOIN
							m_anggota B ON B.IDANGGOTA = A.ANGGOTA_ID
							WHERE %s 
							AND A.LUNAS LIKE 'Belum' 
							AND A.PINJ_SISA > 0
							AND DATE_FORMAT(DATE_ADD(A.TGL_PINJ, INTERVAL A.LAMA_ANGSURAN MONTH), '%s') <= DATE_FORMAT(NOW(), '%s') 
							AND DATE_FORMAT(DATE_ADD(DATE_ADD(A.TGL_PINJ, INTERVAL A.LAMA_ANGSURAN MONTH), INTERVAL A.LAMA_ANGSURAN MONTH), '%s') > DATE_FORMAT(NOW(), '%s')
							ORDER BY IDPINJM_H",
							'%d/%m/%Y',
							'%d/%m/%Y',
							'%Y%m%d',
							'%Y%m%d',
							'%Y%m%d',
							'%Y%m%d',
							$con_keyword,
							'%Y%m%d',
							'%Y%m%d',
							'%Y%m%d',
							'%Y%m%d'
						);

		return $this->prosesData($basequery, $startid, $dataperpage, $page, $datastart, $dataend, 'IDPINJM_H');
	}

	public function getKreditBuruk($keyword, $dataperpage, $page, $post)
	{
		$con_keyword  = $keyword == "" ? "1=1" : "(B.NAMA LIKE '%" . $keyword . "%')";
		$startid      = ($page * 1 - 1) * $dataperpage;
		$datastart    = $startid + 1;
		$dataend      = $datastart + $dataperpage - 1;
		 
		
		if($this->session->userdata("wad_level") == "admin")
		{
			$con_keyword .= "";
			
			if($this->session->userdata('wad_cabang') === true OR $this->session->userdata('wad_cabang') !="" ){
				
				$con_keyword .=" AND A.KODECABANG = '" .$this->session->userdata('wad_cabang'). "' ";
				
			}else{
				$con_keyword .= "";
			}
		}
		else
		{
			
			
			if($this->session->userdata('wad_cabang') === true OR $this->session->userdata('wad_cabang') !="" ){
				
				$con_keyword .=" AND A.KODECABANG = '" .$this->session->userdata('wad_cabang'). "' ";
				
			}else{
				
				$con_keyword .=" AND A.KODECABANG = '" .$this->session->userdata('wad_kodecabang'). "' ";
			}
		}
		// $con_keyword	.=	isset($post['kredit']) ? " AND A.". $this->tipe_macet($post['kredit']) ." " : "";

		$basequery = sprintf("SELECT
							A.IDPINJM_H,
							DATE_FORMAT(A.TGL_PINJ, '%s') TGL_PINJ,
							DATE_FORMAT(DATE_ADD(A.TGL_PINJ, INTERVAL A.LAMA_ANGSURAN MONTH), '%s') JATUH_TEMPO,
							DATE_FORMAT(DATE_ADD(A.TGL_PINJ, INTERVAL A.LAMA_ANGSURAN MONTH), '%s') TEMPO_FIRST_NUMB,
							DATE_FORMAT(DATE_ADD(DATE_ADD(A.TGL_PINJ, INTERVAL A.LAMA_ANGSURAN MONTH), INTERVAL A.LAMA_ANGSURAN MONTH), '%s') TEMPO_SECOND_NUMB,
							DATE_FORMAT(DATE_ADD(DATE_ADD(DATE_ADD(A.TGL_PINJ, INTERVAL A.LAMA_ANGSURAN MONTH), INTERVAL A.LAMA_ANGSURAN MONTH), INTERVAL A.LAMA_ANGSURAN MONTH), '%s') TEMPO_THIRD_NUMB,
							DATE_FORMAT(NOW(), '%s') NOW_NUMB, 
							A.PINJ_TOTAL, A.PINJ_DIBAYAR, A.PINJ_SISA,
							A.IS_RESET,
							A.LUNAS,
							B.NAMA NAMA_ANGGOTA
							FROM tbl_pinjaman_h A
							LEFT JOIN
							m_anggota B ON B.IDANGGOTA = A.ANGGOTA_ID
							WHERE %s 
							AND A.LUNAS LIKE 'Belum' 
							AND A.PINJ_SISA > 0
							AND DATE_FORMAT(DATE_ADD(DATE_ADD(A.TGL_PINJ, INTERVAL A.LAMA_ANGSURAN MONTH), INTERVAL A.LAMA_ANGSURAN MONTH), '%s') <= DATE_FORMAT(NOW(), '%s') 
							AND DATE_FORMAT(DATE_ADD(DATE_ADD(DATE_ADD(A.TGL_PINJ, INTERVAL A.LAMA_ANGSURAN MONTH), INTERVAL A.LAMA_ANGSURAN MONTH), INTERVAL A.LAMA_ANGSURAN MONTH), '%s') > DATE_FORMAT(NOW(), '%s')
							ORDER BY IDPINJM_H",
							'%d/%m/%Y',
							'%d/%m/%Y',
							'%Y%m%d',
							'%Y%m%d',
							'%Y%m%d',
							'%Y%m%d',
							$con_keyword,
							'%Y%m%d',
							'%Y%m%d',
							'%Y%m%d',
							'%Y%m%d'
						);

		return $this->prosesData($basequery, $startid, $dataperpage, $page, $datastart, $dataend, 'IDPINJM_H');
	}

	public function getKreditMacet($keyword, $dataperpage, $page, $post)
	{
		$con_keyword = $keyword == "" ? "1=1" : "(B.NAMA LIKE '%" . $keyword . "%')";
		$startid = ($page * 1 - 1) * $dataperpage;
		$datastart = $startid + 1;
		$dataend = $datastart + $dataperpage - 1;
		 
		if($this->session->userdata("wad_level") == "admin")
		{
			$con_keyword .= "";
			
			if($this->session->userdata('wad_cabang') === true OR $this->session->userdata('wad_cabang') !="" ){
				
				$con_keyword .=" AND A.KODECABANG = '" .$this->session->userdata('wad_cabang'). "' ";
				
			}else{
				$con_keyword .= "";
			}
		}
		else
		{
			
			
			if($this->session->userdata('wad_cabang') === true OR $this->session->userdata('wad_cabang') !="" ){
				
				$con_keyword .=" AND A.KODECABANG = '" .$this->session->userdata('wad_cabang'). "' ";
				
			}else{
				
				$con_keyword .=" AND A.KODECABANG = '" .$this->session->userdata('wad_kodecabang'). "' ";
			}
		}
		// $con_keyword	.=	isset($post['kredit']) ? " AND A.". $this->tipe_macet($post['kredit']) ." " : "";

		$basequery = sprintf("SELECT
							A.IDPINJM_H,
							DATE_FORMAT(A.TGL_PINJ, '%s') TGL_PINJ,
							DATE_FORMAT(DATE_ADD(A.TGL_PINJ, INTERVAL A.LAMA_ANGSURAN MONTH), '%s') JATUH_TEMPO,
							DATE_FORMAT(DATE_ADD(A.TGL_PINJ, INTERVAL A.LAMA_ANGSURAN MONTH), '%s') TEMPO_FIRST_NUMB,
							DATE_FORMAT(DATE_ADD(DATE_ADD(A.TGL_PINJ, INTERVAL A.LAMA_ANGSURAN MONTH), INTERVAL A.LAMA_ANGSURAN MONTH), '%s') TEMPO_SECOND_NUMB,
							DATE_FORMAT(DATE_ADD(DATE_ADD(DATE_ADD(A.TGL_PINJ, INTERVAL A.LAMA_ANGSURAN MONTH), INTERVAL A.LAMA_ANGSURAN MONTH), INTERVAL A.LAMA_ANGSURAN MONTH), '%s') TEMPO_THIRD_NUMB,
							DATE_FORMAT(NOW(), '%s') NOW_NUMB, 
							A.PINJ_TOTAL, A.PINJ_DIBAYAR, A.PINJ_SISA,
							A.IS_RESET,
							A.LUNAS,
							B.NAMA NAMA_ANGGOTA
							FROM tbl_pinjaman_h A
							LEFT JOIN
							m_anggota B ON B.IDANGGOTA = A.ANGGOTA_ID
							WHERE %s 
							AND A.LUNAS LIKE 'Belum' 
							AND A.PINJ_SISA > 0
							AND DATE_FORMAT(DATE_ADD(DATE_ADD(DATE_ADD(A.TGL_PINJ, INTERVAL A.LAMA_ANGSURAN MONTH), INTERVAL A.LAMA_ANGSURAN MONTH), INTERVAL A.LAMA_ANGSURAN MONTH), '%s') <= DATE_FORMAT(NOW(), '%s')
							ORDER BY IDPINJM_H",
							'%d/%m/%Y',
							'%d/%m/%Y',
							'%Y%m%d',
							'%Y%m%d',
							'%Y%m%d',
							'%Y%m%d',
							$con_keyword,
							'%Y%m%d',
							'%Y%m%d'
						);

		return $this->prosesData($basequery, $startid, $dataperpage, $page, $datastart, $dataend, 'IDPINJM_H');
	}

	protected function tipe_macet($data)
	{
		switch (strtolower($data)) {
			case 'lancar':
				$return = "IS_RESET = 0";
				break;
			case 'meragukan':
				$return = "IS_RESET = 1";
				break;
			case 'macet':
				$return = "IS_RESET = 3";
				break;
			case 'buruk':
				$return = "IS_RESET = 2";
				break;
			default:
				$return = "IS_RESET <> ''";
				break;
		}
		return $return;
	}

	protected function prosesData($basequery, $startid, $dataperpage, $page, $datastart, $dataend, $key)
	{
		$ci = &get_instance();
		$ci->load->model('DBHelper');
		$query  = $this->db->query($basequery . " LIMIT " . $startid . ", " . $dataperpage);
		$result = $query->result();

		if (!$result) {
			return $ci->DBHelper->generateEmptyResult($basequery);
		}

		return $ci->DBHelper->generateResult($result, $basequery, $key, $page, $dataperpage, $datastart, $dataend);
	}
}