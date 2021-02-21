<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kontrakperjanjian extends CI_Controller {

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
												A.TGL_PINJ,
												A.BIAYA_ADMIN,
												A.BIAYA_ASURANSI,
												A.FILEBUKTI,
												A.LAMA_ANGSURAN,
												A.BUNGA,
												B.NAMA,
												B.NO_ANGGOTA,
												B.KODEPUSAT,
												B.KODECABANG,
												B.ALAMAT,
												B.KOTA,
												B.PEKERJAAN,
												B.NO_IDENTITAS,
												B.KODEBANK,
												B.NAMA_BANK,
												B.NOREK
											FROM tbl_pinjaman_h A
											LEFT JOIN m_anggota B ON A.ANGGOTA_ID=B.IDANGGOTA
											WHERE A.IDPINJM_H='$id'");
		if($cek->num_rows()>0)
		{
			$row = $cek->row();
			$tglx = explode('-', $row->TGL);
			
			$hari = $this->getHari(date('l',strtotime($row->TGL_PINJ)));
			
			$customer_id = $id;
			$html_content = '';
			$html_content .= '<html>
			<head>
				<title id="title">Surat Perjanjian Pinjaman</title>
				<link rel="stylesheet" type="text/css" href="css/kontrak.css?v=1">
			</head>
			<body><main>';
			
			$html_content .= '<h2 align="center">Surat Perjanjian Pinjaman</h2><br>';
			$html_content .= "<p>Pada hari ini <b>$hari</b> Tanggal <b>".$tglx[0]."</b> Bulan <b>".bulan_indo($row->TGL)."</b> Tahun <b>".$tglx[2]."</b><p>";
			$html_content .= "<p>Telah terjadi perjanjian pinjaman antara :</p>";
			
			$html_content .= '
		    <table width="100%">
		        <tr>
		            <td width="70">Nama Lembaga</td>
		            <td width="10"> : </td>
		            <td width="300">KSP Wahyu Arta Sejahtera</td>
		        </tr>
		        <tr>
		            <td>Cabang</td>
		            <td width="10"> : </td>
		            <td>............................................................</td>
		        </tr>
		        <tr>
		            <td valign="top">Alamat</td>
		            <td width="10" valign="top"> : </td>
		            <td>............................................................</td>
		        </tr>
		        <tr>
		            <td valign="top">Kepala Cabang</td>
		            <td width="10" valign="top"> : </td>
		            <td>............................................................</td>
		        </tr>
		        <tr>
		            <td colspan="3"></td>
		        </tr>
		        <tr>
		            <td colspan="3" width="100%"><p>Bertindak atas  nama <strong>PIHAK PERTAMA</strong> Selaku Pengurus Koperasi (Meminjamkan dana)</p></td>
		        </tr>
		        <tr>
		            <td colspan="3"></td>
		        </tr>
		        <tr>
		            <td width="70">Nama</td>
		            <td width="10"> : </td>
		            <td><strong>'.$row->NAMA.'</strong></td>
		        </tr>
		        <tr>
		            <td>Nomor KTP</td>
		            <td width="10"> : </td>
		            <td>'.$row->NO_IDENTITAS.'</td>
		        </tr>
		        <tr>
		            <td valign="top">Pekerjaan</td>
		            <td width="10" valign="top"> : </td>
		            <td valign="top">'.$row->PEKERJAAN.'</td>
		        </tr>
		        <tr>
		            <td valign="top">Alamat</td>
		            <td width="10" valign="top"> : </td>
		            <td valign="top">'.$row->ALAMAT.', '.$row->KOTA.'</td>
		        </tr>
		        <tr>
		            <td colspan="3"></td>
		        </tr>
		        <tr>
		            <td colspan="3" width="100%"><p>Bertindak atas nama <strong>PIHAK KEDUA</strong>  selaku Anggota/Nasabah Koperasi (selaku peminjam dana)</p></td>
		        </tr>
		        <tr>
		            <td colspan="3"></td>
		        </tr>
		        <tr>
		            <td colspan="3">Kedua belah pihak terlebih dahulu menerangkan sebagai berikut :</td>
		        </tr>
		        <tr>
		            <td colspan="3"></td>
		        </tr>
	        </table>
	        
	        <ul>
            	<li>Bahwa <strong>PIHAK KEDUA</strong> telah menjadi rekanan dan anggota koperasi memiliki kartu anggota atau telah menjalankan mekanisme Anggaran Dasar dan Anggaran Rumah Tangga Koperasi.</li>
            	<li>Bahwa <strong>PIHAK KEDUA</strong> memerlukan dana untuk dipergunakan sebagai modal usaha</li>
            	<li>Bahwa <strong>PIHAK PERTAMA</strong> telah setuju untuk memberikan Modal yang dipergunakan untuk usaha</li>
            </ul>
            
            <p>Berdasarkan hal-hal tersebut diatas kedua belah pihak sepakat mengadakan perjanjian simpan pinjam dengan ketentuan-ketentuan sebagai berikut :</p>
            
            <p style="text-align:center"><strong>Pasal 1</strong></p>
            
            <p style="text-align:center"><strong>JUMLAH &nbsp;MAKSIMUM PINJAMAN</strong></p>
            
            <p>Jumlah maksimum pinjaman menjadi objek perjanjian ini adalah uang senilai &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
            
            <p>&nbsp;Rp <strong>'.str_replace(',','.',number_format($row->JUMLAH)).'</strong> ( '.$this->terbilang->eja($row->JUMLAH).' RUPIAH )</p>
            
            <p style="text-align:center"><strong>Pasal&nbsp; 2</strong></p>
            
            <p style="text-align:center"><strong>JANGKA WAKTU PINJAMAN</strong></p>
            
            <p>Jangka waktu pinjaman dalam perjanjian ini adalah <strong>'.$row->LAMA_ANGSURAN.' Bulan</strong> yang dapat diperpanjang atas persetujuan kedua belah Pihak .</p>
            
                        
            <p style="text-align:center"><strong>Pasal&nbsp; 3</strong></p>
            
            <p style="text-align:center"><strong>CARA PEMBERIAN PINJAMAN</strong></p>
            
            <p>Pemberian Pinjaman oleh <strong>PIHAK PERTAMA</strong> dilakukan verivikasi dan klarifikasi terhadap Permohonon Pinjaman dan persyaratan-persyaratan Pinjaman terhadap <strong>PIHAK KEDUA</strong>,</p>
            
            <p><strong>PIHAK PERTAMA</strong> dan <strong>PIHAK KEDUA</strong> bersama menandatangani Surat Perjanjian Pinjaman.</p>
            
            <br>
           
            <p style="text-align:center"><strong>Pasal&nbsp; 4</strong></p>
            
            <p style="text-align:center"><strong>PELUNASAN PINJAMAN</strong></p>
            
            <p>Pinjaman dilunasi oleh PIHAK KEDUA dengan cicilan perbulan sejumlah yang di sepakati kedua belah pihak selama jangka waktu pinjaman sesuai dengan Pasal 2&nbsp; Perjanjian ini</p>
            
            <br>
            
            <p style="text-align:center"><strong>Pasal&nbsp; 5</strong></p>
            
            <p style="text-align:center"><strong>HAK DAN KEWAJIBAN</strong></p>
            
            <p>PIHAK PERTAMA wajib memberikan pinjaman kepada PIHAK KEDUA sesuai dengan jumlah yang diperjanjikan, dan berhak mendapatkan kembali pelunasannya.</p>
            
            <p>PIHAK KEDUA berhak mendapatkan pinjaman dari PIHAK PERTAMA sesuai dengan jumlah yang diperjanjikan, dan wajib melunasi pinjaman yang dipinjam beserta dangan bunganya.</p>
            
            <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
            
            <p style="text-align:center"><strong>Pasal&nbsp; 6</strong></p>
            
            <p style="text-align:center"><strong>Bagi Hasil</strong></p>
            
            <p>Bagi Hasil yang disepakati oleh kedua belah pihak adalah sebesar <strong>'.str_replace('.',',',number_format($row->BUNGA)).' %</strong> yang dihitung dari jumlah pinjman yang dilakukan oleh PIHAK KEDUA</p>
            
            <br>
            
            <p style="text-align:center"><strong>Pasal&nbsp; 7</strong></p>
            
            <p style="text-align:center"><strong>PROVISI</strong></p>
            
            <p>Besar provisi yang disepakati oleh kedua belah pihak adalah sebesar ........ % yang dihitung dari maksimum pinjaman yang diberikan PIHAK PERTAMA kepada PIHAK KEDUA</p>
            
            <br>
			
            
            <p style="text-align:center"><strong>Pasal 8</strong></p>
            
            <p style="text-align:center"><strong>JAMINAN</strong></p>
            
            <p>Objek yang dijaminkan oleh PIHAK KEDUA dalam perjanjian ini adalah Sertifikat (SHM),&nbsp; Girik, Petok, Segel Kelurahan dan Lain-lain.</p>
            
            <p>Dengan nilai objek jaminan senilai&nbsp; Rp .................................................................</p>
            
            <br>
            
            <p style="text-align:center"><strong>Pasal&nbsp; 9</strong></p>
            
            <p style="text-align:center"><strong>PENGIKAT JAMINAN</strong></p>
            
            <p>Jaminan yang disebut dalam Pasal 8 terikat pada jaminan ini sehingga tidak dapat dialihkan oleh PIHAK KEDUA tanpa sepengetahuan PIHAK PERTAMA dan Jaminan tersebut diberikan jika pinjaman telah dilunasi atau&nbsp; dibayar Oleh PIHAK KEDUA.</p>
            
            <br>
			<br>
			<br>
			<br>
            
            <p style="text-align:center"><strong>Pasal&nbsp; 10</strong></p>
            
            <p style="text-align:center"><strong>ASURANSI</strong></p>
            
            <p>Perjanjian Pinjaman ini dijamin oleh Asuransi <strong>Kematian</strong> & <strong>Cacat Permanen (tidak bisa mencari nafkah)</strong>, yang apabila PIHAK KEDUA mengalami <strong>Kematian</strong> atau <strong>Cacat Permanen Atas Kejadian Kecelakaan</strong> maka hutang akan dilunasi oleh Asuransi.</p>
            
            <br>
           
            
            <p style="text-align:center"><strong>Pasal &nbsp;11</strong></p>
            
            <p style="text-align:center"><strong>PENYELESAIN PERSELISIHAN</strong></p>
            
            <p>1) Apabila terjadi perselisihan maka harus diusahakan selesai secara musyawarah dan mediasi.</p>
            
            <p>2) <strong>Untuk Pinjaman Yang&nbsp; mengunakan jaminan dan tidak mampu mengembalikan Pinjaman setelah Surat Peringatan Kesatu dan Surat Peringatan KeDua maka Pihak Pinjaman membuat&nbsp; Surat Kuasa Jual ditujukan Ke Koperasi Simpan Pinjam Wahyu Arta Sejahtera.</strong></p>
            
            <p>3) Jika Ayat (1) dan (Ayat)2 pada Pasal 11 tidak bisa diselesaikan masalah tersebut diselesaikan Di Pengadilan Negeri Pekalongan.</p>
            
            
			<br>
            
            <p style="text-align:center"><strong>Pasal&nbsp; 12</strong></p>
            
            <p style="text-align:center"><strong>PENUTUP</strong></p>
            
            <p>Demikian perjanjian ini dibuat dan ditandatangani oleh kedua belah pihak di Koperasi Wahyu Arta Sejahtera</p>
            
            <p>Pada hari dan tanggal yang telah disebutkan diatas, dibuat rangkap dua dan bermaterai cukup berkekuatan hukum yang sama untuk masing-masing pihak</p>

	        <br/>
	        <br/>
	        <br/>
	        <br/>
	        <br/>
	        <br/>
	        
		    <table width="100%" >
		        <tr>
		            <td width="33%" align="center"><strong>PIHAK PERTAMA</strong><br/><strong>Yang memberikan Pinjaman</strong></td>
		            <td width="33%" align="center"></td>
		            <td width="33%" align="center"><strong>PIHAK KEDUA</strong><br/><strong>Peminjam Anggota Koperasi</strong></td>
		        </tr>
		        <tr>
		            <td width="33%" align="center"></td>
		            <td width="33%" align="center"></td>
		            <td width="33%" align="center" height="80" style="vertical-align:middle; font-size: smaller;"><i>*materai 6000</i></td>
		        </tr>
		        <tr>
		            <td width="33%" align="center" >___________________________</td>
		            <td width="33%" align="center"></td>
		            <td width="33%" align="center"><strong><u>  '.$row->NAMA.'  </u></strong></td>
		        </tr>
		    </table>
		    
		    <br/>
		    </td>
		    </tr>
		    </table>';
			$html_content .= ' </main></body></html>';
			
			//$html_content .= $html;
			$this->pdf->loadHtml($html_content,'UTF-8');
			$this->pdf->setPaper('A4');
			$this->pdf->render();
			$this->pdf->stream("perjanjian_kontrak_".$row->KODEPUSAT.$row->KODECABANG.$row->NO_ANGGOTA.".pdf", array("Attachment"=>0));
			
		}else{
			redirect('/dashboard');
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