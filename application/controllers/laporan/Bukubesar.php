<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bukubesar extends CI_Controller {

	function __construct(){ 
        parent::__construct();
		$this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . 'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-chace');
        $this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");  
		
		// $this->load->helper(array('form','url', 'xml','text_helper','date','inflector'));
		$this->load->database();
		// $this->load->library(array('Pagination','user_agent','session','form_validation','session','tree'));
		$this->load->model('dbasemodel');
		if(!is_logged_in()){
			redirect('/auth_login');	
		}
    }
	 
	public function index(){
		
		$data['opt_data_entries'] = $this->load->view('_elements/data_entries', NULL, TRUE);
		$data['table_footer']     = $this->load->view('_elements/table_footer', NULL, TRUE);
        $data['PAGE_TITLE']       = "Laporan Buku Besar";
		$data['page']             = "laporan/bukubesar";
		$data['cabs']             = $this->dbasemodel->loadsql("SELECT NAMA,KODE FROM m_cabang ORDER BY NAMA ASC"); 
		$data['query']            = $this->dbasemodel->loadsql("SELECT A.* FROM jns_akun A ORDER BY IDAKUN ASC");

        $this->load->view('dashboard',$data);
    } 
	public function data(){
		 
		$this->load->model('ModelLaporan');
		$keyword     = null !== $this->input->post('keyword') ? $this->input->post('keyword') : "";
		$dataPerPage = $this->input->post('dataperpage');
		$page        = $this->input->post('page');
		$dataTable   = $this->ModelLaporan->getBukubesar($keyword, $dataPerPage, $page, $this->input->post());
		 
		if($dataTable['status'] == '200') {
			array_unshift($dataTable['data'], $this->sumBukuBesar($this->input->post()) );
			array_unshift($dataTable['data'], $this->saldoAwalBB($this->input->post()) );
		}
		header('Content-Type: application/json');
		echo json_encode($dataTable);
		die();
    } 
	protected function sumBukuBesar($post){
		
		// $keyword		=	null !== $this->input->post('keyword') ? $this->input->post('keyword') : "";
		// $_where  = $this->session->userdata('wad_cabang') != "" ? " B.KODECABANG = '". $this->session->userdata('wad_cabang') ."' " : " 1=1 ";
		 
		$_where = "";
		$tgl = explode('-', $post['tgl']); 
		
		$_where .= !empty($post['tgl']) ? " DATE(B.TANGGAL) BETWEEN '". date('Y-m-d', strtotime(trim($tgl[0]))) ."' AND '". date('Y-m-d', strtotime(trim($tgl[1]))) ."'" : "";
				
		if($post['plhcabang'] == "" AND $post['idakun'] == "" )
		{
			
			if($this->session->userdata("wad_level") == "admin")
			{
				$_where .= ""; 
			}
			else
			{ 
				$_where .="B.KODECABANG = '" .$this->session->userdata('wad_cabang'). "' ";
			} 	 
		}
		else
		{
			if($post['plhcabang'] != "" AND $post['idakun'] == "" )
			{
				$_where .=" AND B.KODECABANG = '" .$post['plhcabang']. "'";
			}
			if($post['plhcabang'] == "" AND $post['idakun'] != "" )
			{
				$_where .=	" AND A.IDAKUN = '". $post['idakun'] ."'";
			}
			if($post['plhcabang'] != "" AND $post['idakun'] != "" )
			{
				$_where .=" AND B.KODECABANG = '" .$post['plhcabang']. "'";
				$_where .=	" AND A.IDAKUN = '". $post['idakun'] ."'";
				 
			} 
		}
		  
		$sql = "SELECT SUM(A.DEBET) DEBET, SUM(A.KREDIT) KREDIT FROM vtransaksi_dt A LEFT JOIN vtransaksi B ON A.IDVTRANSAKSI = B.IDVTRANSAKSI WHERE $_where";
		
		$query = $this->dbasemodel->loadsql($sql);
		
		if($query->num_rows() > 0) {
			$row	=	$query->row();
			return array('DEBET' => $row->DEBET, 'KREDIT' => $row->KREDIT);
		}
		return array('DEBET' => 0, 'KREDIT' => 0);
	} 
	protected function saldoAwalBB($post){
		
		// $_where  = $this->session->userdata('wad_cabang') != "" ? " B.KODECABANG = '". $this->session->userdata('wad_cabang') ."' " : " 1=1 ";
		$_where = "";
		$tgl = explode('-', $post['tgl']); 
		$_where .= !empty($post['tgl']) ? " DATE(B.TANGGAL) < '". date('Y-m-d', strtotime(trim($tgl[0]))) ."'" : "";
		
		if($post['plhcabang'] == "" AND $post['idakun'] == "" )
		{
			
			if($this->session->userdata("wad_level") == "admin")
			{
				$_where .= ""; 
			}
			else
			{ 
				$_where .="B.KODECABANG = '" .$this->session->userdata('wad_cabang'). "' ";
			} 	 
		}
		else
		{
			if($post['plhcabang'] != "" AND $post['idakun'] == "" )
			{
				$_where .=" AND B.KODECABANG = '" .$post['plhcabang']. "'";
			}
			if($post['plhcabang'] == "" AND $post['idakun'] != "" )
			{
				$_where .=	" AND A.IDAKUN = '". $post['idakun'] ."'";
			}
			if($post['plhcabang'] != "" AND $post['idakun'] != "" )
			{
				$_where .=" AND B.KODECABANG = '" .$post['plhcabang']. "'";
				$_where .=	" AND A.IDAKUN = '". $post['idakun'] ."'";
				 
			} 
		}
		 
		$sql = "SELECT IF(ISNULL(SUM(A.DEBET)), 0, SUM(A.DEBET)) DEBET, IF(ISNULL(SUM(A.KREDIT)), 0, SUM(A.KREDIT)) KREDIT,
		C.AKUN FROM vtransaksi_dt A LEFT JOIN vtransaksi B ON A.IDVTRANSAKSI = B.IDVTRANSAKSI LEFT JOIN jns_akun C ON A.IDAKUN = C.IDAKUN WHERE $_where ";
		
		$query = $this->dbasemodel->loadsql($sql);
		
		$saldo = 0;
		if($query->num_rows() > 0) 
		{
			$row = $query->row();
			if($row == "") 
			{
				$saldo = 0;
			} 
			elseif(strtolower($row->AKUN) == "aktiva" || strtolower($row->AKUN) == "tpp") 
			{
				$saldo = ($row->DEBET - $row->KREDIT);
			} 
			else
			{
			 	$saldo = ($row->KREDIT - $row->DEBET);
			}
		}
		return array('SALDO_AWAL' => $saldo);
	} 
	public function cetak(){
		
		// plhcabang: 
		// idakun: 
		// tgl: 01/11/2020 - 25/11/2020
		$cabang = $this->input->post("plhcabang");
		$idakun = $this->input->post("idakun");
		$tgl = $this->input->post("tgl");
		$this->session->set_flashdata('cabang',$cabang);
		$this->session->set_flashdata('idakun',$idakun);
		$this->session->set_flashdata('tgl',$tgl);
	}  
	public function cetaklapbukubesar(){
		 
		  
		$this->load->library('pdf'); 	
		
		$cabang = $this->session->flashdata('cabang');
		$idakun = $this->session->flashdata('idakun');
		$tgl = $this->session->flashdata('tgl'); 
		  
		$html_content = '';
		
		$data['cabang']     = $cabang;
		$data['datacabang'] = $this->dbasemodel->loadsql("SELECT * FROM m_cabang WHERE KODE ='".$cabang."'")->row(); 
		$data['idakun']     = $idakun;
		$data['tgl']        = $tgl;
		
		$html_content  = $this->load->view('laporan/cetakbukubesar',$data,true);
		
		//$html_content .= $html;
		
		$this->pdf->loadHtml($html_content,'UTF-8');
		$this->pdf->setPaper('A4');
		$this->pdf->render();
		$this->pdf->stream("Buku Besar.pdf", array("Attachment"=>0));
	}  
	public function cetaks(){
		
	 
		$this->load->library('pdf');
		$this->load->library('terbilang');		
		 
		$keyword  = null !== $this->input->post('keyword') ? "(C.JENIS_TRANSAKSI LIKE '%".$this->input->post('keyword')."%')" : "1=1";
		$keyword .= !empty($post['tgl']) ? " AND DATE(B.TANGGAL) BETWEEN '". date('Y-m-d', strtotime(trim($tgl[0]))) ."' AND '". date('Y-m-d', strtotime(trim($tgl[1]))) ."'" : "";
		
		$keyword .=	$post['idakun'] != "" ? " AND A.IDAKUN = '". $post['idakun'] ."'" : "";
		
		if($this->session->userdata("wad_level") == "admin")
		{
			$keyword .= "";
		}
		else
		{
			$keyword .= " AND B.KODECABANG='".$this->session->userdata('wad_kodecabang')."'";
		}
		
		$cek = $this->dbasemodel->loadsql("SELECT 
		A.IDDETAIL,
		A.DEBET,
		A.KREDIT,       
		B.KETERANGAN,       
		DATE_FORMAT(B.TANGGAL, '%d/%m/%Y') TANGGAL,       
		C.JENIS_TRANSAKSI,
		C.AKUN
		FROM
		vtransaksi_dt A    
		LEFT JOIN
		vtransaksi B ON A.IDVTRANSAKSI = B.IDVTRANSAKSI
		LEFT JOIN
		jns_akun C ON A.IDAKUN = C.IDAKUN
		WHERE '$keyword'
		AND (A.DEBET <> 0 OR A.KREDIT <> 0)
		ORDER BY 
		DATE(B.TANGGAL) ASC");

		if($cek->num_rows()>0)
		{
			$base = base_url();
			$row = $cek->row();
			$tglx = explode('-', $row->TGL);
			
			$cabs =  $this->dbasemodel->loadsql("SELECT * FROM m_cabang WHERE KODE='".$row->KODECABANG."'");
			$cab  = $cabs->row();
			
			$html_content = '';
			$html_content .= '<html>
							<head>
								<title class="h_tengah" id="title">Transaksi Pinjaman</title>
								<link rel="stylesheet" type="text/css" href="css/basil.css?v=1">
							</head>
							<body>
								<header>
									<table>
										<tr>
											<td><img src="img/logokop.png" width="80" height="80"/></td>
											<td valign="top" class="headtitle">
											<h2 class="ksptitle">'.$cab->NAMAKSP.' '.$cab->KOTA.'</h2>
											'.$cab->ALAMAT.' '.$cab->KOTA.'<br>
											Telp : '.$cab->TELP.' Email : '.$cab->EMAIL.'<br>
											Web : '.$cab->WEB.'
											</td>
										</tr>
									</table><hr>
								</header>

								<footer>
								   
								</footer><main>';
						
			$html_content .= '
			<table width="100%">
				<tr align="center">
			        <td><h3>Laporan Buku Besar</h3></td>
			    </tr>
			</table>
			<table width="100%">   
				<thead>
					<tr>
                        <th scope="col">No</th>
                        <th scope="col">Tanggal</th>
                        <th scope="col">Perkiraan</th>
                        <th scope="col">Uraian</th>
                        <th scope="col">Debet</th>
                        <th scope="col">Kredit</th>
                        <th scope="col">Saldo</th>
					</tr>
				</thead>
				<tbody>
			';

			$row = array();
			for($i = 1; $i <= $row.length(); $i++) {
				$saldo	=	0;
				if(i == 0) {
					$saldo	=	$row->SALDO_AWAL;
				}else{
					if($row->AKUN == 'Aktiva' || $row->AKUN == 'Tpp') {
						$saldo	=	val($saldo) + val($row->DEBET) - val($row->KREDIT);
					} else {
						$saldo	=	val($saldo) + val($row->KREDIT) - val($row->DEBET);
					}
				}

			$html_content .= '
				<tr>
					<td>'.$i++.'</td>
					<td>'.$row->TANGGAL.'</td>
					<td>'.$row->JENIS_TRANSAKSI.'</td>
					<td'.$row->KETERANGAN.'</td>
					<td>Rp. '.toRp($row->DEBET).'</td>
					<td>Rp. '.toRp($row->KREDIT).'</td>
					<td>Rp. '.toRp($saldo).'</td>
				</tr>';
				}
			$html_content .= '</tbody></table>';
			
		   $html_content .= ' </main></body></html>';
			
			//$html_content .= $html;
			
			$this->pdf->loadHtml($html_content,'UTF-8');
			$this->pdf->setPaper('A4');
			$this->pdf->render();
			$this->pdf->stream("Buku Besar.pdf", array("Attachment"=>0));
			/*$data['res']	=	$cek;
			$data['cbs']	=	$cabs;
			$this->load->view('test_dokumen',$data);*/
			
		}else{
			redirect('/dashboard');
		}
	}    
	
}