<?php

class ModelAkuntansi extends CI_Model {
	private	$cabang;
	public function __construct(){
		parent::__construct(); 
		$this->load->database();
		$this->cabang = $this->session->userdata('wad_cabang');
	}
	
	public function getJurnalUmum($keyword, $dataperpage, $page, $post){
		$startid = ($page * 1 - 1) * $dataperpage;
		$datastart = $startid + 1;
		$dataend = $datastart + $dataperpage - 1;
		// $con_keyword = $this->cabang != "" ? " B.KODECABANG = '". $this->cabang ."' " : " 0=0 ";
		
		if($this->session->userdata("wad_level") == "admin")
		{
			$con_keyword = "";
		}
		else
		{
			$con_keyword =" B.KODECABANG = '" .$this->session->userdata('wad_kodecabang'). "' ";
		}
		
		$tgl = !empty($post['tgl']) ? explode('-', $post['tgl']) : explode('-', (date('Y/m/d') . " - " . date('Y/m/d')));
		$con_keyword .= "AND DATE(B.TANGGAL) BETWEEN '". date('Y-m-d', strtotime(str_replace('/', '-', trim($tgl[0])))) ."' AND '". date('Y-m-d', strtotime(str_replace('/', '-', trim($tgl[1])))) ."'";
		
		$basequery = sprintf("SELECT A.ID, 
		A.IDJURNAL, 
		B.KODE_JURNAL, 
		B.REFERENSI,
		DATE_FORMAT(B.TANGGAL, '%s') TANGGAL,
		B.KETERANGAN,
		A.DEBET,
		A.KREDIT,
		C.JENIS_TRANSAKSI,
		D.NAMA CABANG
		FROM jurnal_umum_dt A
		LEFT JOIN
		jurnal_umum B ON A.IDJURNAL = B.ID
		LEFT JOIN
		jns_akun C ON A.IDAKUN = C.IDAKUN
		LEFT JOIN
		m_cabang D ON B.KODECABANG = D.KODE
		WHERE %s AND B.STATUS = 1 
		ORDER BY 
		DATE(B.TANGGAL) DESC, B.ID", 
		'%d/%m/%Y', $con_keyword );
								
		return $this->prosesData($basequery, $startid, $dataperpage, $page, $datastart, $dataend, 'ID');
	}
	
	protected function prosesData($basequery, $startid, $dataperpage, $page, $datastart, $dataend, $key){
		$ci =& get_instance();
		$ci->load->model('DBHelper');
		$query = $this->db->query($basequery." LIMIT ".$startid.", ".$dataperpage);
		$result = $query->result();
		
		if(!$result){
			return $ci->DBHelper->generateEmptyResult();
		}
		
		return $ci->DBHelper->generateResult($result, $basequery, $key, $page, $dataperpage, $datastart, $dataend);
	}
	
	
}