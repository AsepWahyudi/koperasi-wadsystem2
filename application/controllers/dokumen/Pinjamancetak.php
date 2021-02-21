<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pinjamancetak extends CI_Controller {

	function __construct(){ 
        parent::__construct();
		$this->load->helper(array('app', 'form','url', 'xml','text_helper','date','inflector'));
		$this->load->database();
		$this->load->library(array('Pagination','user_agent','session','form_validation','session', 'table'));
		$this->load->model('dbasemodel');
		$this->load->model('modelPinjaman');
		if(!is_logged_in()){
			redirect('/auth_login');	
		}
    }
	public function index(){
		 
	    $this->load->library('Pdf'); 
		$this->load->library('Terbilang');
		 
		$id  = $this->uri->segment(2);
		 
		$cek = $this->dbasemodel->loadsql("SELECT A.IDPINJM_H,DATE_FORMAT(A.TGL_PINJ,'%d-%m-%Y')AS TGL,
												A.JUMLAH,
												A.BIAYA_ADMIN,
												A.BIAYA_ASURANSI,
												A.FILEBUKTI,
												A.LAMA_ANGSURAN,
												A.NAMA_SDR,
												A.HUB_SDR,
												A.TELP_SDR,
												A.ALAMAT_SDR,
												A.TGL_PINJ,
												A.BUNGA,
												A.PINJ_POKOK_DIBAYAR,
												A.PINJ_SISA,
												A.PINJ_DIBAYAR,
												A.PINJ_BASIL_DASAR,
												A.PINJ_RP_ANGSURAN,
												A.LUNAS,
												A.PINJ_TOTAL,
												A.REKENING,
												B.NAMA,
												B.NO_ANGGOTA,
												B.KODEPUSAT,
												B.KODECABANG,
												B.NAMA_SAUDARA,
												B.HUB_SAUDARA,
												B.TELP_SAUDARA,
												B.ALMT_SAUDARA,
												B.ALAMAT,
												B.KOTA,
												B.PEKERJAAN,
												B.TELP,
												B.NO_IDENTITAS,
												B.KODEBANK,
												B.NAMA_BANK,
												B.NOREK,
												C.JNS_PINJ,
												SUM(D.DENDA_RP) as DENDA
											FROM tbl_pinjaman_h A
											LEFT JOIN m_anggota B ON A.ANGGOTA_ID=B.IDANGGOTA
											LEFT JOIN jns_pinjm C ON A.BARANG_ID=C.IDAKUN
											LEFT JOIN tbl_pinjaman_d D ON D.IDPINJAM=A.IDPINJM_H
											WHERE A.IDPINJM_H='$id'");

		if($cek->num_rows()>0)
		{
			/* $base = base_url();
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
			        <td><h3>Detail Transaksi Pembayaran Pinjaman</h3></td>
			    </tr>
			</table>
			<table width="100%">   
				<tr>
					<td width="20%"> Kode Nasabah </td>
					<td width="2%">:</td>
					<td width="40%"><strong>'.$row->KODEPUSAT.'.'.$row->KODECABANG.'.'.$row->NO_ANGGOTA.'</strong></td>
					<td width="18%">Diterima Nasabah</td>
					<td width="2%">:</td>
					<td>Rp. '.toRp($row->JUMLAH-$row->BIAYA_ADMIN-$row->BIAYA_ASURANSI).'</td>
				</tr>
				<tr>
					<td>Nama Anggota</td>
					<td>:</td>
					<td>'.$row->NAMA.'</td>
					<td>Biaya Admin</td>
					<td>:</td>
					<td>Rp. '.toRp($row->BIAYA_ADMIN).'</td>
				</tr>
				<tr>
					<td valign="top">Alamat</td>
					<td valign="top">:</td>
					<td valign="top">'.$row->ALAMAT.'</td>
					<td valign="top">Biaya Asuransi</td>
					<td valign="top">:</td>
					<td valign="top">Rp. '.toRp($row->BIAYA_ASURANSI).'</td>
				</tr>
			</table>
			<br>
			<table width="100%">   
				<tr>
					<td width="20%"> Rekening Pinjaman </td>
					<td width="2%">:</td>
					<td width="40%">'.$row->REKENING.'</td>
					<td width="18%">Pokok Pinjaman</td>
					<td width="2%">:</td>
					<td >Rp. '.toRp($row->JUMLAH).'</td>
				</tr>
				<tr>
					<td>Tanggal Pinjam</td>
					<td>:</td>
					<td>'.tgl_indo($row->TGL_PINJ).'</td>
					<td>Angsuran Dasar</td>
					<td>:</td>
					<td>Rp. '.toRp($row->JUMLAH / $row->LAMA_ANGSURAN).'</td>
				</tr>
				<tr>
					<td>Tanggal Tempo</td>
					<td>:</td>
					<td>'.tgl_indo($row->TGL_PINJ).'</td>
					<td>Basil Dasar</td>
					<td>:</td>
					<td>Rp. '.toRp($row->PINJ_BASIL_DASAR).'</td>
				</tr>
				<tr>
					<td>Lama Pinjam</td>
					<td>:</td>
					<td>'.$row->LAMA_ANGSURAN.' Bulan</td>
					<td>Jumlah Angsuran</td>
					<td>:</td>
					<td>Rp. '.toRp($row->PINJ_RP_ANGSURAN).'</td>
				</tr>
				<tr>
					<td>Status Reset</td>
					<td>:</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
			</table>
			<h4><strong>Detail Pembayaran</strong></h4>
		    <table width="100%">
		        <tr>
		            <td width="22%">Total Pinjaman</td>
		            <td width="3%"> : </td>
		            <td width="30%">Rp. '.toRp($row->PINJ_TOTAL).'</td>
		            <td width="15%">Status Lunas</td>
		            <td width="3%"> : </td>
		            <td>'.$row->LUNAS.'</td>
		        </tr>
		        <tr>
		            <td>Total Denda</td>
		            <td> : </td>
		            <td>Rp. '.toRp($row->DENDA).'</td>
		        </tr>
		        <tr>
		            <td>Total Tagihan</td>
		            <td width="10"> : </td>
		            <td>Rp. '.toRp($row->PINJ_TOTAL).'</td>
		        </tr>
		        <tr>
		            <td >Sudah Dibayar</td>
		            <td > : </td>
		            <td >Rp. '.toRp($row->PINJ_DIBAYAR).'</td>
		        </tr>
		        <tr>
		            <td >Sisa Tagihan</td>
		            <td > : </td>
		            <td >Rp. '.toRp($row->PINJ_SISA).'</td>
		        </tr>
	        </table>
	        <h4><strong>Simulasi Tagihan</strong></h4>';

		    $html_content .= '<table width="100%" class"responsive">
					<tr class="header_kolom">
						<th class="header_kolom" style="width:10%;"> Bln ke</th>
						<th class="header_kolom" style="width:20%;"> Angsuran Pokok</th>
						<th class="header_kolom" style="width:20%;"> Angsuran Bagi Hasil</th>
						<th class="header_kolom" style="width:20%;"> Jumlah Angsuran</th>
						<th class="header_kolom" style="width:20%;"> Tanggal Tempo</th>
					</tr>';

				if(!empty($row->LAMA_ANGSURAN)) {
					$no = 1;
					$rows = array();
					$jml_pokok = 0;
					$jml_bunga = 0;
					$jml_ags = 0;
					for($i = 1; $i <= $row->LAMA_ANGSURAN; $i++) {

						$jml_pokok += $row->JUMLAH / $row->LAMA_ANGSURAN;
						$jml_bunga += $row->PINJ_BASIL_DASAR;
						$jml_ags += $row->PINJ_RP_ANGSURAN;

						$html_content .= '
							<tr>
								<td class="h_tengah">'.$no.'</td>
								<td class="h_kanan">Rp '.str_replace(',','.',number_format($jml_pokok)).'</td>
								<td class="h_kanan">Rp '.str_replace(',','.',number_format($jml_bunga)).'</td>
								<td class="h_kanan">Rp '.str_replace(',','.',number_format($jml_ags)).'</td>
								<td class="h_kanan">'.tgl_indo(date('Y-m-d', strtotime($row->TGL_PINJ.'+'. $i.' month'))).'</td>
							</tr>';
						$no++;
					}
					$html_content .= '<tr bgcolor="#eee">
								<td class="h_tengah"><strong>Jumlah</strong></td>
								<td class="h_kanan"><strong>Rp '.str_replace(',','.',number_format($jml_pokok)).'</strong></td>
								<td class="h_kanan"><strong>Rp '.str_replace(',','.',number_format($jml_bunga)).'</strong></td>
								<td class="h_kanan"><strong>Rp '.str_replace(',','.',number_format($jml_ags)).'</strong></td>
								<td></td>
							</tr>
						</table>';
				}
		 $html_content .= '   <h4><strong>Data Pembayaran</strong></h4>';
		 $html_content .= '<table width="100%" class"responsive">
					<tr class="header_kolom">
						<th class="header_kolom"> No</th>
						<th class="header_kolom"> Tgl Bayar</th>
						<th class="header_kolom"> Angsuran Ke</th>
						<th class="header_kolom"> Jenis</th>
						<th class="header_kolom"> Pokok</th>
						<th class="header_kolom"> Basil</th>
						<th class="header_kolom"> Total</th>
						<th class="header_kolom"> Reset</th>
					</tr>';
				$cekPembayaran 	= $this->dbasemodel->loadsql("SELECT * FROM `tbl_pinjaman_d` WHERE IDPINJAM = '$id'");
				$key = $cekPembayaran->row();

				if($cekPembayaran->num_rows()>0)
				{
					$no = 1;
					$rows = array();
					$jml_pokok = 0;
					$jml_bunga = 0;
					$jml_ags = 0;
					$jml_denda = 0;
					for($i = 1; $i <= $cekPembayaran->num_rows(); $i++) {

						$jml_pokok += $key->POKOKBAYAR;
						$jml_bunga += $key->BASILBAYAR;
						$jml_ags += $key->JUMLAH_BAYAR;
						$jml_denda += $key->DENDA_RP;

						$html_content .= '
							<tr>
								<td class="h_tengah">'.$no.'</td>
								<td>'.tgl_en($key->TGL_BAYAR).'</td>
								<td class="h_tengah">'.$key->ANGSURAN_KE.'</td>
								<td class="h_tengah">'.$key->KET_BAYAR.'</td>
								<td class="h_kanan">Rp '.str_replace(',','.',number_format($jml_pokok)).'</td>
								<td class="h_kanan">Rp '.str_replace(',','.',number_format($jml_bunga)).'</td>
								<td class="h_kanan">Rp '.str_replace(',','.',number_format($jml_ags)).'</td>
								<td class="h_kanan">Rp '.str_replace(',','.',number_format($jml_denda)).'</td>
							</tr>';
						$no++;
					}
					$html_content .= '<tr bgcolor="#eee">
								<td class="h_tengah" colspan="4"><strong>Jumlah</strong></td>
								<td class="h_kanan"><strong>Rp '.str_replace(',','.',number_format($jml_pokok)).'</strong></td>
								<td class="h_kanan"><strong>Rp '.str_replace(',','.',number_format($jml_bunga)).'</strong></td>
								<td class="h_kanan"><strong>Rp '.str_replace(',','.',number_format($jml_ags)).'</strong></td>
								<td class="h_kanan"><strong>Rp '.str_replace(',','.',number_format($jml_denda)).'</strong></td>
							</tr>
						</table>';
				}
			
		   $html_content .= ' </main></body></html>'; */
			
			//$html_content .= $html;
			   
			$data['IDPINJM_H'] = $id;
			// $data['datapinjaman'] = $row = $cek->row();
			
			$sql = "SELECT A.IDANGGOTA, A.NAMA, A.NO_ANGGOTA, A.ALAMAT,A.KODEPUSAT,A.KODECABANG,
					A.KOTA, A.TMP_LAHIR, A.TGL_LAHIR, A.FILE_PIC,
					B.IDPINJM_H, B.TGL_PINJ, B.LAMA_ANGSURAN, B.JUMLAH, 
					B.IS_RESET, B.BUNGA, B.BIAYA_ADMIN,
					B.NO_JAMINAN, B.JENIS_JAMINAN,
					B.BIAYA_ASURANSI, B.JAMINAN_TABUNGAN, B.LUNAS,
					B.PINJ_POKOK_SISA, B.PINJ_BASIL_DASAR, 
					B.PINJ_BASIL_TOTAL, B.PINJ_BASIL_BAYAR,
					B.PINJ_DIBAYAR, B.PINJ_TOTAL,B.PINJ_RP_ANGSURAN,
					C.NAMA AS NAMACABANG, C.ALAMAT AS ALAMATCABANG, C.KOTA AS KOTACABANG, 
					C.TELP AS TLPCABANG, C.EMAIL AS EMAILCABANG, C.WEB AS WEBCABANG
					FROM m_anggota A
					LEFT JOIN tbl_pinjaman_h B ON A.IDANGGOTA = B.ANGGOTA_ID 
					LEFT JOIN m_cabang C ON A.KODECABANG = C.KODE
					WHERE B.IDPINJM_H = '".$id."'";
			$data['data_source'] = $this->dbasemodel->loadSql($sql)->row();
			
			$sql = "SELECT IDPINJ_D, TGL_BAYAR, ANGSURAN_KE, BAYAR_SALDO, BASILBAYAR, POKOKBAYAR,
					JUMLAH_BAYAR, DENDA_RP, TERLAMBAT, KET_BAYAR, DK, KAS_ID, 
					JENIS_TRANS, UPDATE_DATA, USERNAME, KETERANGAN,BIAYA_KOLEKTOR
					FROM tbl_pinjaman_d A WHERE A.IDPINJAM = '".$id."'";
			$data['data_angsuran'] = $this->dbasemodel->loadSql($sql);
			
			$sql = "SELECT ID, TANGGAL, JUMLAH, ANGSURAN_KE, LUNAS, JENIS FROM tbl_reset A WHERE A.IDPINJAMAN = '".$id."' AND JENIS = 0";
			$data['data_reset'] = $this->dbasemodel->loadSql($sql);
			
			$sql = "SELECT ID, TANGGAL, JUMLAH, LUNAS, JENIS FROM tbl_reset A WHERE A.IDPINJAMAN = '".$id."' AND JENIS = 1";
			$data['data_kolektor'] = $this->dbasemodel->loadSql($sql);
			
			$html_content = $this->load->view("laporan/cetaklappinjaman.php",$data,true);
			$this->pdf->loadHtml($html_content,'UTF-8'); 
			$this->pdf->setPaper('A4');
			$this->pdf->render();
			$this->pdf->stream("Pinjaman_".$row->KODEPUSAT.$row->KODECABANG.$row->NO_ANGGOTA.".pdf", array("Attachment"=>0));
			/*$data['res']	=	$cek;
			$data['cbs']	=	$cabs;
			$this->load->view('test_dokumen',$data);*/
			
		}else{
			redirect('/dashboard');
		}
	}

	public function struk(){
		//if(!is_logged_in()){
		//	redirect('/auth_login');	
		//}
		$this->load->library('pdf');
		$this->load->library('terbilang');

		$id 	= $this->uri->segment(2);
		$cekPembayaran 	= $this->dbasemodel->loadsql("SELECT * FROM `tbl_pinjaman_d` WHERE IDPINJ_D = '$id'");
		$key = $cekPembayaran->row();

		$koncabang = ($this->session->userdata('wad_cabang')!="")? " WHERE KODE='".$this->session->userdata('wad_cabang')."'":"";
		$sql = "SELECT * FROM m_cabang $koncabang";
		$cabs = $this->dbasemodel->loadsql($sql);
		$cab  = $cabs->row();

		if($cekPembayaran->num_rows()>0)
		{
			$html_content = '';
			$html_content .= '<html>
							<head>
								<title class="h_tengah" id="title">Struk Pembayaran</title>
								<link rel="stylesheet" type="text/css" href="css/struk.css?v=1">
							</head>
							<body>
								<header>
									<table width="100%">
										<tr>
											<td><img src="img/logokop.png" width="40px" style="padding-left:8px;"/></td>
											<td valign="top" class="headtitle">
											<strong>'.$cab->NAMAKSP.' '.$cab->KOTA.'</strong><br>
											'.$cab->ALAMAT.' '.$cab->KOTA.'
											</td>
										</tr>
									</table>
								</header>

								<footer>
									<table width="100%">
										<tr align="center">
									        <td>Mohon di cek kembali. Terimakasih</td>
									    </tr>
									</table>
								   
								</footer>
								<main>';

			$html_content .= ' 

				<table width="100%">
					<tr>
				        <td><br><br>---------------------------------</td>
				    </tr>
					<tr align="center">
				        <td><strong style="font-size:10px;">Struk Pembayaran Angsuran</strong><br><br></td>
				    </tr>
				</table>
				<table width="100%">
					<tr>
				        <td width="35%">Tanggal</td>
				        <td width="65%">: '.tgl_indo($key->TGL_BAYAR).'</td>
				    </tr>
				    <tr>
				        <td>No. Slip</td>
				        <td>: '.$key->IDPINJ_D.'</td>
				    </tr>
				    <tr>
				        <td>------------</td>
				        <td>---------------------</td>
				    </tr>
				    <tr>
				        <td>Angsuran Ke</td>
				        <td>: '.$key->ANGSURAN_KE.'</td>
				    </tr>
				    <tr>
				        <td>Jenis</td>
				        <td>: '.$key->KET_BAYAR.'</td>
				    </tr>
				    <tr>
				        <td>Pokok</td>
				        <td>: Rp. '.toRp($key->POKOKBAYAR).'</td>
				    </tr>
				    <tr>
				        <td>Basil</td>
				        <td>: Rp. '.toRp($key->BASILBAYAR).'</td>
				    </tr>
				    <tr>
				        <td>Total</td>
				        <td>: Rp. '.toRp($key->JUMLAH_BAYAR).'</td>
				    </tr>
				    <tr>
				        <td>Denda</td>
				        <td>: Rp. '.toRp($key->DENDA_RP).'</td>
				    </tr>
				    <tr>
				        <td>------------</td>
				        <td>---------------------</td>
				    </tr>
				    <tr>
				        <td colspan="2">CS WA 085215378518 - Informasi Pengaduan Anggota</td>
				    </tr>
				    <tr>
				        <td>Petugas</td>
				        <td>: '.$key->USERNAME.'</td>
				    </tr>

				</table>
				';

			$html_content .= ' </main></body></html>';
			
			//$html_content .= $html;
			$this->pdf->loadHtml($html_content,'UTF-8');
			$this->pdf->setPaper('C8');
			$this->pdf->render();
			$this->pdf->stream("Pinjaman_".$key->IDPINJAM.".pdf", array("Attachment"=>0));
		}else{
			redirect('/dashboard');
		}
	}
}