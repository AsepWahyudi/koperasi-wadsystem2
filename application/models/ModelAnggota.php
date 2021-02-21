<?php

class ModelAnggota extends CI_Model {

	public function __construct(){
		parent::__construct(); 
		$this->load->database();
	}
	
	public function getDataTable($keyword, $dataperpage, $page){
		
		$con_keyword  = $keyword == "" ? "1=1" : "(NAMA LIKE '%".$keyword."%')";
		$startid      = ($page * 1 - 1) * $dataperpage;
		$datastart    = $startid + 1;
		$dataend      = $datastart + $dataperpage - 1;
		// $con_keyword .= $this->cabang != "" ? " AND KODECABANG = '". $this->cabang ."' " : "";
		
		if($this->session->userdata("wad_level") == "admin")
		{
			$con_keyword .= "";
		}
		else
		{
			$con_keyword .=" AND KODECABANG = '" .$this->session->userdata('wad_kodecabang'). "' ";
		}
		
		$basequery = sprintf("SELECT FILE_PIC, IDANGGOTA, NOREK, NAMA, '' AS NAMABANK, JK,
		DATE_FORMAT(TGL_LAHIR, '%s') AS TGL_LAHIR,
		TIMESTAMPDIFF(YEAR, TGL_LAHIR, CURDATE()) AS USIA,
		ALAMAT, DATE_FORMAT(TGL_DAFTAR, '%s') AS TGL_DAFTAR, AKTIF,KODEPUSAT,KODECABANG,NO_ANGGOTA
		FROM m_anggota
		WHERE %s AND AKTIF <> ''
		ORDER BY IDANGGOTA" 
		, '%d/%m/%Y'
		, '%d/%m/%Y'
		, $con_keyword
		);
								
		return $this->prosesData($basequery, $startid, $dataperpage, $page, $datastart, $dataend, 'IDANGGOTA');
	}
	
	public function getDetailAnggota($idanggota){
		
		$basequery = sprintf("SELECT IDANGGOTA, NAMA, IDENTITAS,NO_IDENTITAS,  NOKARTU, NOREK, TMP_LAHIR,
		DATE_FORMAT(TGL_LAHIR, '%s') AS TGL_LAHIR, IBU_KANDUNG,NAMA_BANK,KODEBANK,
		ALAMAT, ALAMAT_DOMISILI, FILE_PIC, FILE_KTP, FILE_NPWP, FILE_KK, FILE_BK_NKH, 
		KOTA, TELP, NAMA_SAUDARA, HUB_SAUDARA, ALMT_SAUDARA, TELP_SAUDARA, A.AGAMA, JK,
		PEKERJAAN, NAMA_PEKERJAAN, ALAMAT_PEKERJAAN, STATUS, 
		DATE_FORMAT(TGL_DAFTAR, '%s') AS TGL_DAFTAR,
		A.IDPROVINSI, A.IDKOTA, A.IDKECAMATAN, A.IDKELURAHAN,
		B.name NAMA_PROVINSI, C.name NAMA_KOTA, D.name NAMA_KECAMATAN, E.name NAMA_KELURAHAN
		FROM m_anggota A
		LEFT JOIN
		lokasi_provinces B ON A.IDPROVINSI = B.id_provinsi
		LEFT JOIN
		lokasi_kota C ON A.IDKOTA = C.id_kota
		LEFT JOIN
		lokasi_kecamatan D ON A.IDKECAMATAN = D.id_kecamatan
		LEFT JOIN
		lokasi_kelurahan E ON A.IDKELURAHAN = E.id_kelurahan
		WHERE IDANGGOTA = %s 
		LIMIT 1"
		, '%m/%d/%Y'
		, '%m/%d/%Y'
		, $idanggota
		);		
		
		$query = $this->db->query($basequery);
		
		$row = $query->row_array();
		
		if(!$row){
			return false;
		}
		
		return $row;
		
	}
	
}