<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Perjanjianbasil extends CI_Controller {

	function __construct(){ 
        parent::__construct();
		$this->load->helper(array('app', 'form','url', 'xml','text_helper','date','inflector'));
		$this->load->database();
		$this->load->library(array('Pagination','user_agent','session','form_validation','session', 'table'));
		$this->load->model('dbasemodel');
		//@session_start();
    }
	public function index(){
		
		if(!is_logged_in()){
			redirect('/auth_login');	
		}
		$this->load->library('pdf');
		$this->load->library('terbilang');


		
		
		$id 	= $this->uri->segment(2);
		$cek 	= $this->dbasemodel->loadsql("SELECT A.IDPINJM_H,DATE_FORMAT(A.TGL_PINJ,'%d-%m-%Y')AS TGL,
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
												C.JNS_PINJ
											FROM tbl_pinjaman_h A
											LEFT JOIN m_anggota B ON A.ANGGOTA_ID=B.IDANGGOTA
											LEFT JOIN jns_pinjm C ON A.BARANG_ID=C.IDAKUN
											WHERE A.IDPINJM_H='$id'");
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
								<title id="title">Surat Perjanjian Bagi Hasil</title>
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
			<table>
				<tr>
			        <td colspan="7"><h3 align="center">Surat Perjanjian Bagi Hasil</h3></td>
			    </tr>
		        <tr>
		            <td width="60">Kode KSP </td>
		            <td width="10"> : </td>
		            <td width="250">...........................................................</td>
		            <td width="10"></td>
		            <td width="50">No. Form</td>
		            <td width="10"> : </td>
		            <td width="80">..................................</td>
		        </tr>
		        <tr>
		            <td>Nama KSP</td>
		            <td width="10"> : </td>
		            <td colspan="5"><strong>'.$cab->NAMAKSP.' '.$cab->KOTA.'</strong></td>
		        </tr>
		    </table>
		    <br/><hr/>';
			
			$html_content .= '
		    <table width="100%">
		        <tr>
		            <td colspan="3"></td>
		        </tr>
		        <tr>
		            <td width="110">Kode Nasabah</td>
		            <td width="10"> : </td>
		            <td><strong>'.$row->KODEPUSAT.'.'.$row->KODECABANG.'.'.$row->NO_ANGGOTA.'</strong></td>
		        </tr>
		        <tr>
		            <td>Nama Nasabah</td>
		            <td width="10"> : </td>
		            <td><strong>'.$row->NAMA.'</strong></td>
		        </tr>
		        <tr>
		            <td>Nomor Telepon</td>
		            <td width="10"> : </td>
		            <td>'.join(" ", str_split($row->TELP, 4)).'</td>
		        </tr>
		        <tr>
		            <td valign="top">Alamat</td>
		            <td width="10" valign="top"> : </td>
		            <td valign="top">'.$row->ALAMAT.', '.$row->KOTA.'</td>
		        </tr>
		        <tr>
		            <td colspan="3"></td>
		        </tr>
	        </table>
	        <br/>
	        <table width="100%">
		        <tr>
		            <td width="110">Saudara Nasabah (1)</td>
		            <td width="10"> : </td>
		            <td width="165"><strong>'.$row->NAMA_SAUDARA.'</strong></td>
		            
		            <td width="10"></td>
		            
		            <td width="110">Saudara Nasabah (2)</td>
		            <td width="10"> : </td>
		            <td width="165"><strong>'.$row->NAMA_SDR.'</strong></td>
		        </tr>
		        <tr>
		            <td>Hubungan Saudara (1)</td>
		            <td width="10"> : </td>
		            <td>'.$row->HUB_SAUDARA.'</td>
		            
		            <td></td>
		            
		            <td>Hubungan Nasabah (2)</td>
		            <td width="10"> : </td>
		            <td>'.$row->HUB_SDR.'</td>
		        </tr>
		        <tr>
		            <td>Nomor Telepon (1)</td>
		            <td width="10"> : </td>
		            <td>'.join(" ", str_split($row->TELP_SAUDARA, 4)).'</td>
		            
		            <td></td>
		            
		            <td>Nomor Telepon (2)</td>
		            <td width="10"> : </td>
		            <td>'.join(" ", str_split($row->TELP_SDR, 4)).'</td>
		        </tr>
		        <tr>
		            <td valign="top">Alamat Saudara (1)</td>
		            <td width="10" valign="top"> : </td>
		            <td valign="top">'.$row->ALMT_SAUDARA.'</td>
		            
		            <td></td>
		            
		            <td valign="top">Alamat Saudara (2)</td>
		            <td width="10" valign="top"> : </td>
		            <td valign="top">'.$row->ALAMAT_SDR.'</td>
		        </tr>
		        <tr>
		            <td colspan="3"></td>
		        </tr>
		    </table>
		    <br/>
		    <table width="100%">
		        <tr>
		            <td width="95">Jenis Pinjaman</td>
		            <td width="10"> : </td>
		            <td width="300"><strong>'.$row->JNS_PINJ.'</strong></td>
		        </tr>
		        <tr>
		            <td width="95">Jumlah Modal</td>
		            <td width="10"> : </td>
		            <td width="300"><strong>Rp '.str_replace(',','.',number_format($row->JUMLAH)).'</strong></td>
		        </tr>
		        <tr>
		            <td>Terbilang</td>
		            <td width="10"> : </td>
		            <td><strong>'.$this->terbilang->eja($row->JUMLAH).' RUPIAH</strong></td>
		        </tr>
		        <tr>
		            <td>Jangka Waktu</td>
		            <td width="10"> : </td>
		            <td><strong>'.($row->LAMA_ANGSURAN).' BULAN</strong></td>
		        </tr>
		        <tr>
		            <td>Bagi Hasil</td>
		            <td width="10"> : </td>
		            <td><strong>'.str_replace('.',',',number_format($row->BUNGA)).' % ( Rp '.str_replace(',','.',number_format((int)$row->JUMLAH*(float)$row->BUNGA/100)).' )</strong></td>
		        </tr>
		        <tr>
		            <td valign="top">Cara Pencairan</td>
		            <td width="10" valign="top"> : </td>
		            <td valign="top"><strong>Tunai</strong> / <strong>Transfer Bank</strong> <i>*(Coret yng tidak diperlukan)</i><br/><br/></td>
		        </tr>
		        <tr>
		            <td colspan="3"></td>
		        </tr>
		        <tr>
		            <td colspan="3" width="100%">Dengan ini menyatakan kehendak untuk memberikan modal kepada Bpk/Ibu/Sdr/i <strong>'.$row->NAMA.'</strong> dengan syarat dan ketentuan yang berlaku di Koperasi Simpan Pinjam <strong>'.$cab->NAMAKSP.' '.$cab->KOTA.'</strong></td>
		        </tr>
		        <tr>
		            <td colspan="3"></td>
		        </tr>
		        <tr>
		            <td colspan="3" width="100%">Jika dalam waktu <strong>'.($row->LAMA_ANGSURAN).' BULAN</strong> nasabah tidak dapat melunasi pinjaman, maka Koperasi Simpan Pinjam <strong>'.$cab->NAMAKSP.' '.$cab->KOTA.'</strong> berhak melakukan <strong>Reset Pinjaman</strong> (registrasi pinjaman ulang dari sisa hutang)</td>
		        </tr>
		        <tr>
		            <td colspan="3"></td>
		        </tr>
		        <tr>
		            <td colspan="3" width="100%">Bagi hasil dapat ditransfer ke rekening Koperasi Simpan Pinjam <strong>'.$cab->NAMAKSP.' '.$cab->KOTA.'</strong> atau dapat melalui fasilitas pembayaran online di Aplikasi Android/IOS <strong>Wahyu Arta Digital</strong></td>
		        </tr>
		        <tr>
		            <td colspan="3"></td>
		        </tr>
		        <tr>
		            <td colspan="3" width="100%"><br/><strong>'.$cab->KOTA.', '.tgl_indo($row->TGL_PINJ).'</strong></td>
		        </tr>
	        </table>
	        <br/>
	        
		    <table width="100%" class="tblbtm">
		        <tr>
		            <td width="33%" align="center"><strong>Pemohon</strong></td>
		            <td width="33%" align="center"><strong>Mengetahui</strong></td>
		            <td width="33%" align="center"><strong>Disetujui Oleh</strong></td>
		        </tr>
		        <tr>
		            <td width="33%" align="center" height="80" style="vertical-align:middle; font-size: 10px;"><i>*materai 6000</i></td>
		            <td width="33%" align="center"><strong></strong></td>
		            <td width="33%" align="center"><strong></strong></td>
		        </tr>
		        <tr style="vertical-align:top;">
		            <td width="33%" align="center" style="padding-bottom:10px;"><u>Nama Nasabah</u><br/><strong>'.$row->NAMA.'</strong></td>
		            <td width="33%" align="center"><u>Admin</u><br/><br/></td>
		            <td width="33%" align="center"><u>Kepala Cabang</u><br/><br/></td>
		        </tr>
		    </table>
        ';
			
			
		   $html_content .= ' </main></body></html>';
			
			//$html_content .= $html;
			$this->pdf->loadHtml($html_content,'UTF-8');
			$this->pdf->setPaper('A4');
			$this->pdf->render();
			$this->pdf->stream("Basil_".$row->KODEPUSAT.$row->KODECABANG.$row->NO_ANGGOTA.".pdf", array("Attachment"=>0));
			/*$data['res']	=	$cek;
			$data['cbs']	=	$cabs;
			$this->load->view('test_dokumen',$data);*/
			
		}else{
			redirect('/dashboard');
		}
	}
}