<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	function __construct(){ 
        parent::__construct();
		 
		$this->load->database(); 
		$this->load->model('dbasemodel');
		$this->load->model('ModelLaporan');
		
		if(!is_logged_in()){
			redirect('/auth_login');	
		}
    }
	
	public function index()
	{
		
        $data['PAGE_TITLE'] = "Dashboard";
		$data['page'] = "";
        $wheretrgl = "AND DATE(TGL)='".date("Y-m-d")."'"; 
		
		if($this->session->userdata("wad_level") == "admin")
		{
			
			$data['pinj']    = $this->dbasemodel->loadsql("SELECT COUNT(IDPINJM_H) AS TOTALPINJ FROM tbl_pinjaman_h WHERE STATUS='1'");
			$data['anggota'] = $this->dbasemodel->loadsql("SELECT COUNT(IDANGGOTA) AS TOTALANGGOTA FROM m_anggota WHERE AKTIF='Y'");
			
			$sql = sprintf("SELECT SUM(JUMLAH) AS KAS_MASUK FROM transaksi_kas WHERE AKUN = 'Pemasukan' $wheretrgl");
			$sql2 = sprintf("SELECT SUM(JUMLAH) AS KAS_KELUAR FROM transaksi_kas WHERE AKUN = 'Pengeluaran' $wheretrgl");
			
			$pendapatanQ = "SELECT IF(ISNULL(SUM(B.DEBET)), 0, SUM(B.DEBET)) AS DEBET, IF(ISNULL(SUM(B.KREDIT)), 0, SUM(B.KREDIT)) AS KREDIT FROM jns_akun A, vtransaksi_dt B JOIN vtransaksi C ON B.IDVTRANSAKSI = C.IDVTRANSAKSI WHERE A.IDAKUN = B.IDAKUN AND A.AKUN LIKE 'Pendapatan' AND C.TANGGAL LIKE '".date("Y")."%' GROUP BY A.IDAKUN";
			
			$bebanQuery = "SELECT ((IF(ISNULL(SUM(B.KREDIT)), 0, SUM(B.KREDIT))) - (IF(ISNULL(SUM(B.DEBET)), 0, SUM(B.DEBET)))) AS BIAYA FROM jns_akun A, vtransaksi_dt B JOIN vtransaksi C ON B.IDVTRANSAKSI = C.IDVTRANSAKSI WHERE A.IDAKUN = B.IDAKUN AND A.AKUN LIKE 'Beban' AND C.TANGGAL LIKE '".date("Y")."%' GROUP BY A.IDAKUN";
			
		}
		else
		{
			
			$data['pinj']    = $this->dbasemodel->loadsql("SELECT COUNT(IDPINJM_H) AS TOTALPINJ FROM tbl_pinjaman_h WHERE KODECABANG='".$this->session->userdata("wad_kodecabang")."' AND STATUS='1'");
			$data['anggota'] = $this->dbasemodel->loadsql("SELECT COUNT(IDANGGOTA) AS TOTALANGGOTA FROM m_anggota WHERE KODECABANG='".$this->session->userdata("wad_kodecabang")."' AND STATUS='1'"); 
			
			$sql = sprintf("SELECT SUM(JUMLAH) AS KAS_MASUK FROM transaksi_kas WHERE AKUN = 'Pemasukan' AND KODECABANG='".$this->session->userdata("wad_kodecabang")."' $wheretrgl");
			$sql2 = sprintf("SELECT SUM(JUMLAH) AS KAS_KELUAR FROM transaksi_kas WHERE AKUN = 'Pengeluaran' AND KODECABANG='".$this->session->userdata("wad_kodecabang")."' $wheretrgl");
			
			$pendapatanQ = "SELECT IF(ISNULL(SUM(B.DEBET)), 0, SUM(B.DEBET)) AS DEBET, IF(ISNULL(SUM(B.KREDIT)), 0, SUM(B.KREDIT)) AS KREDIT FROM jns_akun A, vtransaksi_dt B JOIN vtransaksi C ON B.IDVTRANSAKSI = C.IDVTRANSAKSI WHERE A.IDAKUN = B.IDAKUN AND A.AKUN LIKE 'Pendapatan' AND C.TANGGAL LIKE '".date("Y")."%' AND KODECABANG='".$this->session->userdata("wad_kodecabang")."' GROUP BY A.IDAKUN";
		
			$bebanQuery = "SELECT ((IF(ISNULL(SUM(B.KREDIT)), 0, SUM(B.KREDIT))) - (IF(ISNULL(SUM(B.DEBET)), 0, SUM(B.DEBET)))) AS BIAYA FROM jns_akun A, vtransaksi_dt B JOIN vtransaksi C ON B.IDVTRANSAKSI = C.IDVTRANSAKSI WHERE A.IDAKUN = B.IDAKUN AND A.AKUN LIKE 'Beban' AND C.TANGGAL LIKE '".date("Y")."%' AND KODECABANG='".$this->session->userdata("wad_kodecabang")."' GROUP BY A.IDAKUN";
			
			$data['anggota'] = $this->dbasemodel->loadsql("SELECT COUNT(IDANGGOTA) AS TOTALANGGOTA FROM m_anggota WHERE KODECABANG='".$this->session->userdata("wad_kodecabang")."' AND AKTIF='Y'");
		}
		   
		// $koncabang = ($this->session->userdata('wad_cabang')!="")? " AND KODECABANG='".$this->session->userdata('wad_cabang')."'":""; 
		// $kodcabang = ($this->session->userdata('wad_cabang')!="")? " AND C.KODECABANG='".$this->session->userdata('wad_cabang')."'":"";
		 
		//echo  $sql;
		$data['kas_masuk']	= $this->dbasemodel->loadsql($sql);
		$data['kas_keluar'] = $this->dbasemodel->loadsql($sql2); 
		$data['pendapatan']	= $this->dbasemodel->loadsql($pendapatanQ); 
		$data['beban']      = $this->dbasemodel->loadsql($bebanQuery);

        $this->load->view('dashboard',$data);
    }

    public function map()
	{
		// $kodcabang = ($this->session->userdata('wad_cabang')!="")? " AND A.KODECABANG='".$this->session->userdata('wad_cabang')."'":"";
		
		if($this->session->userdata("wad_level") == "admin")
		{
			$query = "SELECT A.lat, A.lng, A.NAMA, A.TELP, A.ALAMAT_DOMISILI, B.PINJ_RP_ANGSURAN FROM m_anggota A, tbl_pinjaman_h B WHERE B.ANGGOTA_ID = A.IDANGGOTA AND B.LUNAS = 'Belum' AND A.lat != '0.000000' AND A.lng != '0.000000'";
		}
		else
		{
			$query = "SELECT A.lat, A.lng, A.NAMA, A.TELP, A.ALAMAT_DOMISILI, B.PINJ_RP_ANGSURAN FROM m_anggota A, tbl_pinjaman_h B WHERE B.ANGGOTA_ID = A.IDANGGOTA AND B.LUNAS = 'Belum' AND A.lat != '0.000000' AND A.lng != '0.000000' AND KODECABANG='".$this->session->userdata("wad_kodecabang")."'";
			
		}
	

		$data = $this->dbasemodel->loadsql($query);

		$json = 'var markers = { "type": "FeatureCollection", ';
		$json .= '"features": [ ';
		$data = $data->result_array();

		foreach($data as $x){
		    $json .= '{ ';
		    $json .= '"type": "Feature",';
		    $json .= '"geometry": {';
		    $json .= '"type": "Point",';
		    $json .= '"coordinates": ';
		    $json .= '[ '.$x['lng'].','.$x['lat'].' ]},';
		    $json .= '"properties": {';
		    $json .= '"name":"'.htmlspecialchars($x['NAMA']).'",
				      "angsuran":"'.htmlspecialchars(toRp($x['PINJ_RP_ANGSURAN'])).'",
				      "telp":"'.htmlspecialchars($x['TELP']).'",
				      "lat":"'.htmlspecialchars($x['lat']).'",
				      "lng":"'.htmlspecialchars($x['lng']).'",
					}},';
		}
		$json = substr($json,0,strlen($json)-1);
		$json .= ']};';
		echo $json;
	}

	public function data(){
		  
		$keyword           = null !== $this->input->post('keyword') ? $this->input->post('keyword') : "";
		$dataPerPage       = 10000;
		$page              = $this->input->post('page');
		$dataTable         = $this->ModelLaporan->getLabaRugi($keyword, $dataPerPage, $page, $this->input->post());
		$dataTable         = json_decode(json_encode($dataTable), true);
		
		$array_sum         = array('DEBET', 'KREDIT');
		$result            = $this->tree->result_tree('PARENT', 'IDAKUN', $dataTable['data'], $array_sum);
		
		$dataTable['data'] = $result['return'];
        header('Content-Type: application/json');
		echo json_encode($dataTable);
		die();
		
    }
}