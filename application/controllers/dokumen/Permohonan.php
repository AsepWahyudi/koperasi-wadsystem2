<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Permohonan extends CI_Controller {
 
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
												A.ANGGOTA_ID,
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
			$row = $cek->row();
			$tglx = explode('-', $row->TGL);
			
			$customer_id = $id;
			$html_content = '';
			
			$cabs =  $this->dbasemodel->loadsql("SELECT * FROM m_cabang WHERE KODE='".$row->KODECABANG."'");
			$cab  = $cabs->row();
			
			$hari = $this->getHari(date('l',strtotime($row->TGL_PINJ)));
			
			$html_content .= '<html>
			<head>
				<title id="title">Surat Permohonan Pinjaman</title>
				<link rel="stylesheet" type="text/css" href="css/dokumen.css?v=1">
			</head>
			<body><main>';

			$html_content .= '<h4 align="center">Surat Permohonan Pinjaman</h4>
		    <table width="100%">
		        <tr>
		            <td colspan="3">Kepada<br/>Yth. Koperasi Simpan Pinjam <strong>'.$cab->NAMAKSP.'</strong><br/>di '.$cab->ALAMAT.',  '.$cab->KOTA.', </td>
		        </tr>
		        <tr>
		            <td colspan="3"></td>
		        </tr>
		        <tr>
		            <td width="50">Perihal</td>
		            <td width="10"> : </td>
		            <td>Permohonan Pinjaman</td>
		        </tr>
		    </table>
		    <br/>
		    
		    <p>Pada hari ini <strong>'.$hari.'</strong> Tanggal <strong>'.$tglx[0].'</strong> Bulan <strong>'.bulan_indo($row->TGL).'</strong> Tahun <strong>'.$tglx[2].'</strong></p>
		    <p>Yang bertanda tangan dibawah ini :</p>
        ';
		
		$html_content .= '
		    <table width="100%">
		        <tr>
		            <td width="95">Kode Nasabah</td>
		            <td width="10"> : </td>
		            <td><strong>'.$row->KODEPUSAT.'.'.$row->KODECABANG.'.'.$row->NO_ANGGOTA.'</strong></td>
		        </tr>
		        <tr>
		            <td>Nama Nasabah</td>
		            <td width="10"> : </td>
		            <td><strong>'.$row->NAMA.'</strong></td>
		        </tr>
		        <tr>
		            <td>Nomor KTP</td>
		            <td width="10"> : </td>
		            <td>'.$row->NO_IDENTITAS.'</td>
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
		    <table width="100%">
		        <tr>
		            <td colspan="3" width="100%">Dengan ini bermaksud  mengajukan permohonan pinjaman dana kepada koperasi simpan pinjam <strong>'.$cab->NAMAKSP.' '.$cab->KOTA.'</strong> dengan khusus sebagai berikut :</td>
		        </tr>
		        <tr>
		            <td colspan="3"></td>
		        </tr>
		        <tr>
		            <td width="95">Jenis Pinjaman</td>
		            <td width="10"> : </td>
		            <td width="300"><strong>'.$row->JNS_PINJ.'</strong></td>
		        </tr>
		        <tr>
		            <td width="95">Nominal Pengajuan</td>
		            <td width="10"> : </td>
		            <td width="300"><strong>Rp '.str_replace(',','.',number_format($row->JUMLAH)).'</strong></td>
		        </tr>
		        <tr>
		            <td>Terbilang</td>
		            <td width="10"> : </td>
		            <td><strong>'.$this->terbilang->eja($row->JUMLAH).' RUPIAH</strong></td>
		        </tr>
		        <tr>
		            <td valign="top">Sistem Keuntungan</td>
		            <td width="10" valign="top"> : </td>
		            <td valign="top"><strong>Bagi Hasil</strong></td>
		        </tr>
		        <tr>
		            <td>Jangka Waktu</td>
		            <td width="10"> : </td>
		            <td><strong>'.($row->LAMA_ANGSURAN).' BULAN</strong></td>
		        </tr>
		        <tr>
		            <td>Angsuran</td>
		            <td width="10"> : </td>
		            <td><strong>Rp '.str_replace(',','.',number_format($this->pembulatan_ratusan((int)$row->JUMLAH/(int)$row->LAMA_ANGSURAN+(int)$row->JUMLAH*(float)$row->BUNGA/100/(int)$row->LAMA_ANGSURAN))).' *(Bulanan / Tahunan)</strong></td>
		        </tr>
		        <tr>
		            <td valign="top">Tujuan Pinjaman</td>
		            <td width="10" valign="top"> : </td>
		            <td valign="top"><strong>..............................................................</strong></td>
		        </tr>
		        <tr>
		            <td valign="top">Jaminan</td>
		            <td width="10" valign="top"> : </td>
		            <td valign="top"><strong><br/>
		            A.	Sertifikat dengan no ............................................................... <br/>
		            B.	Girik dengan no ...................................................................... <br/>
		            C.	Petok dengan no .................................................................... <br/>
		            D.	Segel Kelurahan dengan no .................................................. <br/>
		            E.	Lainnya .................................................................................... <br/>
		            </strong></td>
		        </tr>
		        <tr>
		            <td colspan="3"></td>
		        </tr>
		        <tr>
		            <td colspan="3" width="100%">Demikian surat permohonan pinjaman ini ditandatangai dengan sebenar-benarnya dan bersedia dilakukan klarifikasi dan verifikasi.</td>
		        </tr>
	        </table>
	        <br/>
	        <br/>
	        <br/>
	        
		    <table width="100%">
		        <tr>
		            <td width="33%" align="center"><strong>Suami/Istri/Orang Tua</strong></td>
		            <td width="33%" align="center"></td>
		            <td width="33%" align="center"><strong>Pemohon</strong></td>
		        </tr>
		        <tr>
		            <td width="33%" align="center"></td>
		            <td width="33%" align="center"></td>
		            <td width="33%" align="center" height="80" style="vertical-align:middle; font-size: smaller;"><i>*materai 6000</i></td>
		        </tr>
		        <tr>
		            <td width="33%" align="center" ><u>Nama Lengkap</u></td>
		            <td width="33%" align="center" ></td>
		            <td width="33%" align="center" ><u><strong>'.$row->NAMA.'</strong></u></td>
		        </tr>
		    </table>
		    
		    <br/>
		    <br/>
		    <br/>
        ';
		$html_content .= ' </main></body></html>';
			
			//$html_content .= $html;
			$this->pdf->loadHtml($html_content,'UTF-8');
			$this->pdf->setPaper('A4');
			$this->pdf->render();
			$this->pdf->stream("permohonan_".$row->KODEPUSAT.$row->KODECABANG.$row->NO_ANGGOTA.".pdf", array("Attachment"=>0));
			
		}else{
			redirect('/dashboard');
		}
	}
	
	function pembulatan_ratusan($uang){
        $sisa = substr($uang, -2);
        if ($sisa!='00') {
            $ratusan = substr($uang, -2);
            $akhir = $uang + (100-$ratusan);
            return $akhir;
        } else {
            return $uang;
        }
        
    }
	
	function getHari ($day) {
	    if ($day=='Sunday') {
	        return 'Minggu';
	    } elseif ($day=='Monday') {
	        return 'Senin';
	    } elseif ($day=='Tuesday') {
	        return 'Selasa';
	    } elseif ($day=='Wednesday') {
	        return 'Rabu';
	    } elseif ($day=='Thursday') {
	        return 'Kamis';
	    } elseif ($day=='Friday') {
	        return 'Jumat';
	    } else {
	        return 'Sabtu';
	    }
	}
}