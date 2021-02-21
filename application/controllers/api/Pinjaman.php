<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pinjaman extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
		header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
		$this->load->helper(array('form', 'url', 'xml', 'text_helper', 'date', 'inflector', 'app'));
		$this->load->database();
		$this->load->library(array('Pagination', 'user_agent', 'session', 'form_validation', 'session'));
		$this->load->model(array('dbasemodel', 'ModelVTransaksi'));
		//@session_start();
	}

	public function index()
	{
		if ($this->input->post("idpinj") !== null) {
			$idpinj  = $this->input->post("idpinj");
		} else {
			$post = json_decode(file_get_contents("php://input"));
			$idpinj	= $post->idpinj;
		}

		$sql = "SELECT A.*, C.JNS_PINJ, C.IDAKUN AS JENIS_TRANS
						FROM tbl_pinjaman_h A
						LEFT JOIN jns_pinjm C ON A.BARANG_ID=C.IDJNS_PINJ
						WHERE A.IDPINJM_H='$idpinj' AND A.LUNAS='Belum'"; //AND NAMA LIKE '%$cari%' OR NO_ANGGOTA LIKE'%$cari%'
		$cek  		= $this->dbasemodel->loadsql($sql);
		$arr = array();
		//var_dump($_POST);
		if ($cek->num_rows() > 0) {
			$kes = $this->dbasemodel->loadsql("SELECT MAX(ANGSURAN_KE) AS AGSURANKE FROM tbl_pinjaman_d WHERE IDPINJAM='$idpinj'")->row();
			foreach ($cek->result() as $key) {
			    $ceklancar = $this->db->query("SELECT A.IDPINJM_H, DATE_FORMAT(A.TGL_PINJ, '%d/%m/%Y') TGL_PINJ, DATE_FORMAT(DATE_ADD(A.TGL_PINJ, INTERVAL A.LAMA_ANGSURAN MONTH), '%d/%m/%Y') JATUH_TEMPO, DATE_FORMAT(DATE_ADD(A.TGL_PINJ, INTERVAL A.LAMA_ANGSURAN MONTH), '%Y%m%d') TEMPO_FIRST_NUMB, DATE_FORMAT(DATE_ADD(DATE_ADD(A.TGL_PINJ, INTERVAL A.LAMA_ANGSURAN MONTH), INTERVAL A.LAMA_ANGSURAN MONTH), '%Y%m%d') TEMPO_SECOND_NUMB, DATE_FORMAT(DATE_ADD(DATE_ADD(DATE_ADD(A.TGL_PINJ, INTERVAL A.LAMA_ANGSURAN MONTH), INTERVAL A.LAMA_ANGSURAN MONTH), INTERVAL A.LAMA_ANGSURAN MONTH), '%Y%m%d') TEMPO_THIRD_NUMB, DATE_FORMAT(NOW(), '%Y%m%d') NOW_NUMB, A.PINJ_TOTAL, A.PINJ_DIBAYAR, A.PINJ_SISA, A.IS_RESET, A.LUNAS, B.NAMA NAMA_ANGGOTA 
                FROM tbl_pinjaman_h A LEFT JOIN m_anggota B ON B.IDANGGOTA = A.ANGGOTA_ID 
                WHERE A.IDPINJM_H ='".$key->IDPINJM_H."' AND 1=1 
                -- AND A.PINJ_SISA > 0 
                AND A.LUNAS LIKE 'Lunas' OR 1=1 
                AND DATE_FORMAT(DATE_ADD(A.TGL_PINJ, INTERVAL A.LAMA_ANGSURAN MONTH), '%Y%m%d') > DATE_FORMAT(NOW(), '%Y%m%d')")->num_rows();
        
        
                if($ceklancar > 0){ 
                    $keterangan = 'Lancar';
                }
                
                $cekragu = $this->db->query("SELECT A.IDPINJM_H, DATE_FORMAT(A.TGL_PINJ, '%d/%m/%Y') TGL_PINJ, DATE_FORMAT(DATE_ADD(A.TGL_PINJ, INTERVAL A.LAMA_ANGSURAN MONTH), '%d/%m/%Y') JATUH_TEMPO, DATE_FORMAT(DATE_ADD(A.TGL_PINJ, INTERVAL A.LAMA_ANGSURAN MONTH), '%Y%m%d') TEMPO_FIRST_NUMB, DATE_FORMAT(DATE_ADD(DATE_ADD(A.TGL_PINJ, INTERVAL A.LAMA_ANGSURAN MONTH), INTERVAL A.LAMA_ANGSURAN MONTH), '%Y%m%d') TEMPO_SECOND_NUMB, DATE_FORMAT(DATE_ADD(DATE_ADD(DATE_ADD(A.TGL_PINJ, INTERVAL A.LAMA_ANGSURAN MONTH), INTERVAL A.LAMA_ANGSURAN MONTH), INTERVAL A.LAMA_ANGSURAN MONTH), '%Y%m%d') TEMPO_THIRD_NUMB, DATE_FORMAT(NOW(), '%Y%m%d') NOW_NUMB, A.PINJ_TOTAL, A.PINJ_DIBAYAR, A.PINJ_SISA, A.IS_RESET, A.LUNAS, B.NAMA NAMA_ANGGOTA 
                FROM tbl_pinjaman_h A LEFT JOIN m_anggota B ON B.IDANGGOTA = A.ANGGOTA_ID 
                WHERE A.IDPINJM_H ='".$key->IDPINJM_H."' AND 1=1 AND A.LUNAS LIKE 'Belum' 
                AND A.PINJ_SISA > 0 
                AND DATE_FORMAT(DATE_ADD(A.TGL_PINJ, INTERVAL A.LAMA_ANGSURAN MONTH), '%Y%m%d') <= DATE_FORMAT(NOW(), '%Y%m%d') 
                AND DATE_FORMAT(DATE_ADD(DATE_ADD(A.TGL_PINJ, INTERVAL A.LAMA_ANGSURAN MONTH), INTERVAL A.LAMA_ANGSURAN MONTH), '%Y%m%d') > DATE_FORMAT(NOW(), '%Y%m%d')")->num_rows();
                
                if($cekragu > 0){ 
                    $keterangan = 'Meragukan';
                } 
                
                $cekburuk = $this->db->query("SELECT A.IDPINJM_H, DATE_FORMAT(A.TGL_PINJ, '%d/%m/%Y') TGL_PINJ, DATE_FORMAT(DATE_ADD(A.TGL_PINJ, INTERVAL A.LAMA_ANGSURAN MONTH), '%d/%m/%Y') JATUH_TEMPO, DATE_FORMAT(DATE_ADD(A.TGL_PINJ, INTERVAL A.LAMA_ANGSURAN MONTH), '%Y%m%d') TEMPO_FIRST_NUMB, DATE_FORMAT(DATE_ADD(DATE_ADD(A.TGL_PINJ, INTERVAL A.LAMA_ANGSURAN MONTH), INTERVAL A.LAMA_ANGSURAN MONTH), '%Y%m%d') TEMPO_SECOND_NUMB, DATE_FORMAT(DATE_ADD(DATE_ADD(DATE_ADD(A.TGL_PINJ, INTERVAL A.LAMA_ANGSURAN MONTH), INTERVAL A.LAMA_ANGSURAN MONTH), INTERVAL A.LAMA_ANGSURAN MONTH), '%Y%m%d') TEMPO_THIRD_NUMB, DATE_FORMAT(NOW(), '%Y%m%d') NOW_NUMB, A.PINJ_TOTAL, A.PINJ_DIBAYAR, A.PINJ_SISA, A.IS_RESET, A.LUNAS, B.NAMA NAMA_ANGGOTA 
                FROM tbl_pinjaman_h A LEFT JOIN m_anggota B ON B.IDANGGOTA = A.ANGGOTA_ID 
                WHERE A.IDPINJM_H ='".$key->IDPINJM_H."' AND 1=1 
                AND A.LUNAS LIKE 'Belum' 
                AND A.PINJ_SISA > 0 
                AND DATE_FORMAT(DATE_ADD(DATE_ADD(A.TGL_PINJ, INTERVAL A.LAMA_ANGSURAN MONTH), INTERVAL A.LAMA_ANGSURAN MONTH), '%Y%m%d') <= DATE_FORMAT(NOW(), '%Y%m%d') 
                AND DATE_FORMAT(DATE_ADD(DATE_ADD(DATE_ADD(A.TGL_PINJ, INTERVAL A.LAMA_ANGSURAN MONTH), INTERVAL A.LAMA_ANGSURAN MONTH), INTERVAL A.LAMA_ANGSURAN MONTH), '%Y%m%d') > DATE_FORMAT(NOW(), '%Y%m%d')")->num_rows();
                
                if($cekburuk > 0){ 
                    $keterangan = 'Buruk';
                }     
        
                $cekmacet = $this->db->query("SELECT A.IDPINJM_H, DATE_FORMAT(A.TGL_PINJ, '%d/%m/%Y') TGL_PINJ, DATE_FORMAT(DATE_ADD(A.TGL_PINJ, INTERVAL A.LAMA_ANGSURAN MONTH), '%d/%m/%Y') JATUH_TEMPO, DATE_FORMAT(DATE_ADD(A.TGL_PINJ, INTERVAL A.LAMA_ANGSURAN MONTH), '%Y%m%d') TEMPO_FIRST_NUMB, DATE_FORMAT(DATE_ADD(DATE_ADD(A.TGL_PINJ, INTERVAL A.LAMA_ANGSURAN MONTH), INTERVAL A.LAMA_ANGSURAN MONTH), '%Y%m%d') TEMPO_SECOND_NUMB,			DATE_FORMAT(DATE_ADD(DATE_ADD(DATE_ADD(A.TGL_PINJ, INTERVAL A.LAMA_ANGSURAN MONTH), INTERVAL A.LAMA_ANGSURAN MONTH), INTERVAL A.LAMA_ANGSURAN MONTH), '%Y%m%d') TEMPO_THIRD_NUMB, DATE_FORMAT(NOW(), '%Y%m%d') NOW_NUMB, A.PINJ_TOTAL, A.PINJ_DIBAYAR, A.PINJ_SISA, A.IS_RESET, A.LUNAS, B.NAMA NAMA_ANGGOTA 
                FROM tbl_pinjaman_h A LEFT JOIN m_anggota B ON B.IDANGGOTA = A.ANGGOTA_ID 
                WHERE A.IDPINJM_H ='".$key->IDPINJM_H."' AND 1=1 
                AND A.LUNAS LIKE 'Belum' 
                AND A.PINJ_SISA > 0 
                AND DATE_FORMAT(DATE_ADD(DATE_ADD(DATE_ADD(A.TGL_PINJ, INTERVAL A.LAMA_ANGSURAN MONTH), INTERVAL A.LAMA_ANGSURAN MONTH), INTERVAL A.LAMA_ANGSURAN MONTH), '%Y%m%d') <= DATE_FORMAT(NOW(), '%Y%m%d')")->num_rows();
                
                if($cekmacet > 0){ 
                    $keterangan = 'Macet';
                }
                
                $hitreset = ((int) $key->PINJ_SISA/100)* (int)$key->BUNGA;
			    
			    $angsbln = (int) $key->ANGSURAN_DASAR + (int) $key->PINJ_BASIL_DASAR;
				array_push($arr, array(
					"IDPINJM_H" => $key->IDPINJM_H,
					"TGL_PINJ" => $key->TGL_PINJ,
					"ANGGOTA_ID" => $key->ANGGOTA_ID,
					"ANGSURAN_PER_BULAN" => $angsbln,
					"JNS_PINJ" => $key->JNS_PINJ,
					"JENIS_TRANS" => (int) $key->JNS_TRANS,
					"KAS_ID" => (int)$key->KAS_ID,
					"SUDAH_ANGSUR" => ($kes->AGSURANKE == null) ? 0 : (int) $kes->AGSURANKE,
					"LAMA_ANGSURAN" => (int) $key->LAMA_ANGSURAN,
					"JUMLAH" => (int) $key->JUMLAH,
					"BUNGA" => $key->BUNGA,
					"BIAYA_ADMIN" => (int) $key->BIAYA_ADMIN,
					"BIAYA_ASURANSI" => (int) $key->BIAYA_ASURANSI,
					"PINJ_TOTAL" => (int) $key->PINJ_TOTAL,
					"PINJ_DIBAYAR" => (int) $key->PINJ_DIBAYAR,
					"PINJ_SISA" => (int) $key->PINJ_SISA,
					"PINJ_RP_ANGSURAN" => (int) $key->PINJ_RP_ANGSURAN,
					"PINJ_POKOK_DIBAYAR" => (int) $key->PINJ_POKOK_DIBAYAR,
					"PINJ_POKOK_SISA" => (int) $key->PINJ_POKOK_SISA,
					"PINJ_BASIL_DASAR" => (int) $key->PINJ_BASIL_DASAR,
					"PINJ_BASIL_TOTAL" => (int) $key->PINJ_BASIL_TOTAL,
					"PINJ_BASIL_BAYAR" => (int) $key->PINJ_BASIL_BAYAR,
					"JENIS_JAMINAN" => (int) $key->JENIS_JAMINAN,
					"STATUS_BAYAR" => $keterangan,
					"BIAYA_RESET" => $hitreset
				));
			}
			$array = array(
				"success" => true,
				"data" => $arr[0]
			);
			echo json_encode($array);
		} else {
			$array = array(
				"success" => false,
				"msg" => "Data tidak ditemukan",
			);
			echo json_encode($array);
		}
	}

	function bayar_angsuran()
	{
		if ($this->input->post("id_anggota") !== null) {
			$id_anggota  = $this->input->post("id_anggota");
			$id_pinjam  = $this->input->post("id_pinjam");
			$angsuran_ke  = $this->input->post("angsuran_ke");
			$bayar_basil  = $this->input->post("bayar_basil");
			$bayar_pokok  = $this->input->post("bayar_pokok");
			$bayar_transfer  = $this->input->post("bayar_transfer");
			$bayar_saldo  = $this->input->post("bayar_saldo");
			$biaya_kolektor  = $this->input->post("biaya_kolektor");
			$biaya_reset  = $this->input->post("biaya_reset");
			$jenis_trans  = $this->input->post("jenis_trans");
			$id_kas  = $this->input->post("id_kas");
			$jumlah_bayar  = $this->input->post("jumlah_bayar");
			$ket_bayar  = $this->input->post("ket_bayar");
			$kode_unik  = $this->input->post("kode_unik");
			$total_bayar  = $this->input->post("total_bayar");
			$pinjaman_sisa = $this->input->post("pinjaman_sisa");
			$pinjaman_pokok_sisa = $this->input->post("pinjaman_pokok_sisa");
			$pinjaman_pokok_sisa_baru = $pinjaman_pokok_sisa - $bayar_pokok;
		} else {
			$post = json_decode(file_get_contents("php://input"));
			$id_anggota	= $post->id_anggota;
			$id_pinjam	= $post->id_pinjam;
			$angsuran_ke	= $post->angsuran_ke;
			$bayar_basil	= $post->bayar_basil;
			$bayar_pokok	= $post->bayar_pokok;
			$bayar_transfer	= $post->bayar_transfer;
			$bayar_saldo	= $post->bayar_saldo;
			$biaya_kolektor	= $post->biaya_kolektor;
			$biaya_reset	= $post->biaya_reset;
			$jenis_trans	= $post->jenis_trans;
			$jumlah_bayar	= $post->jumlah_bayar;
			$id_kas  = $post->id_kas;
			$ket_bayar	= $post->ket_bayar;
			$kode_unik	= $post->kode_unik;
			$total_bayar	= $post->total_bayar;
			$pinjaman_sisa	= $post->pinjaman_sisa;
			$pinjaman_pokok_sisa	= $post->pinjaman_pokok_sisa;
			$pinjaman_pokok_sisa_baru = $pinjaman_pokok_sisa - $bayar_pokok;
		}

		$otherdb 	= $this->load->database('otherdb', TRUE);
		$kode_trx = 4;
		$expiredDate = date("Y-m-d H:i:s", strtotime("+1 hour"));
		$nomertrx = date("ymdHis");
		$tgl = date("Y-m-d H:i:s");

		$saldo = 0;

		$defaultdb 	= $this->load->database('default', TRUE);
		$sql = "SELECT * FROM m_anggota_simp WHERE IDJENIS_SIMP = 180 AND IDANGGOTA='$id_anggota'";
		$cekSaldo = $defaultdb->query($sql);

		if ($cekSaldo->num_rows() > 0) {

			$simpanan = $cekSaldo->row();

			$saldo = (int) $simpanan->SALDO;
		} else {
			$saldo = 0;
		}

		if ($bayar_transfer > 0) {
			if ($saldo < $bayar_saldo) {
				$array = array(
					"success" => false,
					"saldo" => 0,
					"message" => "Maaf, Saldo Anda tidak mencukupi."
				);
				echo json_encode($array);
				return;
			}
			$payment_via = 'transfer';
			$msg = "Pembayaran angsuran Anda telah tersimpan. Silakan lakukan pembayaran transfer sebelum $expiredDate.";

			$sql = "INSERT INTO m_trx(KODE_TRX, KODE_ANGGOTA, TGL, NOTRX, KODE_UNIK, TOTAL_BAYAR, STATUS_BAYAR, EXPIRED_DATE, 
					PAYMENT_VIA, PROSES, STATUS, MSG, ID_PINJAM, ANGSURAN_KE_PINJAM, BAYAR_SALDO_PINJAM, BASIL_BAYAR_PINJAM, POKOK_BAYAR_PINJAM, 
					JUMLAH_BAYAR_PINJAM, BIAYA_RESET_PINJAM, BIAYA_KOLEKTOR_PINJAM, JENIS_TRANS_PINJAM, KETERANGAN_BAYAR_PINJAM, IDH2H) values 
					('$kode_trx', '$id_anggota', '$tgl', '$nomertrx', '$kode_unik', '$total_bayar', 0, '$expiredDate', 
					'$payment_via', 0, 0, '$msg', '$id_pinjam', '$angsuran_ke', '$bayar_saldo', '$bayar_basil', '$bayar_pokok', 
					'$jumlah_bayar', '$biaya_reset', '$biaya_kolektor', '$jenis_trans', '$ket_bayar', 0)";

			$add = $otherdb->query($sql);

			if ($add) {
				$array = array(
					"success" => true,
					"message" => "Pembayaran angsuran Anda telah tersimpan. Silakan lakukan pembayaran transfer sebelum $expiredDate. Selengkapnya dapat dilihat pada Riwayat Transaksi."
				);
				echo json_encode($array);
			}
		} else {
			if ($saldo < $bayar_saldo) {
				$array = array(
					"success" => false,
					"saldo" => 0,
					"message" => "Maaf, Saldo Anda tidak mencukupi."
				);
				echo json_encode($array);
			} else {

				$sisaSaldo = $saldo - $bayar_saldo;

				$sql = "UPDATE m_anggota_simp SET SALDO = $sisaSaldo WHERE IDJENIS_SIMP = 180 AND IDANGGOTA='$id_anggota'";
				$defaultdb->query($sql);

				$sql = "SELECT * FROM m_anggota WHERE IDANGGOTA = '$id_anggota'";
				$cekAnggota = $defaultdb->query($sql);

				if ($cekAnggota->num_rows() > 0) {

					$anggota = $cekAnggota->row();

					$keterangan = "Pembayaran Angsuran Menggunakan Saldo Mudharabah Melalui App ($anggota->NAMA), sebesar Rp. $bayar_saldo";

					$sql = "INSERT INTO transaksi_simp(TGL_TRX, ID_ANGGOTA, ID_JENIS, JUMLAH, KETERANGAN, KET_BAYAR, AKUN, DK, ID_KAS, 
							USERNAME, NAMA_PENYETOR, NO_IDENTITAS, ALAMAT, KOLEKTOR, STATUS, KODEPUSAT, KODECABANG) VALUES 
							('$tgl', '$anggota->IDANGGOTA', 180, '$bayar_saldo', '$keterangan', 'Tabungan', 'Penarikan', 'K', 9, 
							'mobile-app', '$anggota->NAMA', '$anggota->NO_IDENTITAS', '$anggota->ALAMAT', 0, 1, '$anggota->KODEPUSAT', '$anggota->KODECABANG')";

					$defaultdb->query($sql);

					$sql = "SELECT * FROM transaksi_simp WHERE TGL_TRX = '$tgl' AND ID_ANGGOTA = '$anggota->IDANGGOTA'";
					$cekTrxSimp = $defaultdb->query($sql);

					if ($cekTrxSimp->num_rows() > 0) {
						foreach ($cekTrxSimp->result() as $key) {
							$datatransaksi	=	array(
								'tgl' 			=> $key->TGL_TRX,
								'jumlah' 		=> $key->JUMLAH,
								'keterangan' 	=> $key->KETERANGAN == '' ? 'Pembayaran Angsuran (' . $key->NAMA_PENYETOR . ')' : $key->KETERANGAN,
								'user' 			=> $key->USERNAME,
								'kodecabang' 	=> $key->KODECABANG,
								'ket_dt' 		=> 'tarik tunai'
							);
							$this->ModelVTransaksi->insertVtransaksi($key->ID_TRX_SIMP, $datatransaksi, 'PT', $key->ID_KAS, $key->ID_JENIS, 'SIMP');
						}
					}

					$payment_via = 'saldo';
					$msg = "Pembayaran angsuran Anda telah tersimpan.";

					$sql = "INSERT INTO m_trx(KODE_TRX, KODE_ANGGOTA, TGL, NOTRX, TOTAL_BAYAR, STATUS_BAYAR, 
						PAYMENT_VIA, PROSES, STATUS, MSG, ID_PINJAM, ANGSURAN_KE_PINJAM, BAYAR_SALDO_PINJAM, BASIL_BAYAR_PINJAM, POKOK_BAYAR_PINJAM, 
						JUMLAH_BAYAR_PINJAM, BIAYA_RESET_PINJAM, BIAYA_KOLEKTOR_PINJAM, JENIS_TRANS_PINJAM, KETERANGAN_BAYAR_PINJAM, IDH2H) values 
						('$kode_trx', '$id_anggota', '$tgl', '$nomertrx', '$total_bayar', 1, 
						'$payment_via', 1, 1, '$msg', '$id_pinjam', '$angsuran_ke', '$bayar_saldo', '$bayar_basil', '$bayar_pokok', 
						'$jumlah_bayar', '$biaya_reset', '$biaya_kolektor', '$jenis_trans', '$ket_bayar', 0)";

					$addTrx = $otherdb->query($sql);

					if ($addTrx) {

						$sql = "INSERT INTO tbl_pinjaman_d(TGL_BAYAR, IDPINJAM, ANGSURAN_KE, BAYAR_SALDO, BASILBAYAR, JUMLAH_BAYAR, BIAYA_RESET, 
						BIAYA_KOLEKTOR, KET_BAYAR, DK, KAS_ID, JENIS_TRANS, USERNAME, STATUS) VALUES ('$tgl', '$id_pinjam', '$angsuran_ke', '$bayar_saldo', 
						'$bayar_basil', '$jumlah_bayar', '$biaya_reset', '$biaya_kolektor','$ket_bayar', 'D', 9, '$jenis_trans', 'mobile-app', 1)";
						$defaultdb->query($sql);

						if ($ket_bayar === 'Angsuran') {
							$lunas = 'Belum';
						} else {
							$lunas = 'Lunas';
						}

						$sisa_pinjaman = $pinjaman_sisa - $bayar_saldo;

						$sql = "UPDATE tbl_pinjaman_h SET LUNAS = '$lunas', PINJ_DIBAYAR = PINJ_DIBAYAR + '$bayar_saldo', 
								PINJ_SISA = '$sisa_pinjaman', PINJ_POKOK_DIBAYAR = PINJ_POKOK_DIBAYAR + '$bayar_pokok', PINJ_POKOK_SISA = PINJ_POKOK_SISA - '$bayar_pokok', 
								PINJ_BASIL_BAYAR = PINJ_BASIL_BAYAR + '$bayar_basil' WHERE IDPINJM_H = '$id_pinjam'";

						$defaultdb->query($sql);

						if ($lunas === 'Belum') {
							$isCredit = 1;
						} else {
							$isCredit = 0;
						}

						if ($isCredit === 0) {
							$sql = "UPDATE m_anggota SET ISCREDIT = 0, PINJ_POKOK = 0, PINJ_TOTAL = 0, PINJ_DIBAYAR = 0, 
						PINJ_SISA = 0, PINJ_POKOK_DIBAYAR = 0, PINJ_POKOK_SISA = 0, PINJ_RP_ANGSURAN = 0, PINJ_BASIL_DASAR = 0, 
						PINJ_BASIL_TOTAL = 0, PINJ_BASIL_BAYAR = 0 WHERE IDANGGOTA = '$id_anggota'";
						} else {
							$sql = "UPDATE m_anggota SET PINJ_DIBAYAR = PINJ_DIBAYAR + '$bayar_saldo', 
						PINJ_SISA = $sisa_pinjaman, PINJ_POKOK_DIBAYAR = PINJ_POKOK_DIBAYAR + '$bayar_pokok', PINJ_POKOK_SISA = '$pinjaman_pokok_sisa_baru', 
						PINJ_BASIL_BAYAR = PINJ_BASIL_BAYAR + '$bayar_basil' WHERE IDANGGOTA = '$id_anggota'";
						}

						$defaultdb->query($sql);

						$sql	=	"SELECT A.*, 
							B.ANGGOTA_ID, B.PINJ_SISA, B.LUNAS, B.REKENING, B.KODECABANG, 
							C.NAMA NAMA_AGT,
							D.JENIS_TRANSAKSI
						 FROM tbl_pinjaman_d A 
						 LEFT JOIN
						 	tbl_pinjaman_h B ON A.IDPINJAM = B.IDPINJM_H
						 LEFT JOIN
						 	m_anggota C ON B.ANGGOTA_ID = C.IDANGGOTA
						 LEFT JOIN
						 	jns_akun D ON B.JNS_TRANS = D.IDAKUN
						 WHERE A.IDPINJAM = '$id_pinjam' AND A.ANGSURAN_KE = '$angsuran_ke'";

						$cekPinj = $defaultdb->query($sql);

						if ($cekPinj->num_rows() > 0) {
							foreach ($cekPinj->result() as $key) {
								$datatransaksi	=	array(
									'tgl' 		=> $key->TGL_BAYAR,
									'jumlah' 		=> $key->POKOKBAYAR,
									'keterangan' 	=> 'Angsuran ke ' . $key->ANGSURAN_KE . ' ' . $key->JENIS_TRANSAKSI . ', No Rek : ' . $key->REKENING . '(' . $key->NAMA_AGT . ')',
									'user' 			=> $key->USERNAME,
									'kodecabang' 	=> $key->KODECABANG,
									'ket_dt'		=> 'angsuran ' . $key->JENIS_TRANSAKSI
								);
								$this->ModelVTransaksi->insertVtransaksi($key->IDPINJ_D, $datatransaksi, 'JT', $key->KAS_ID, $key->JENIS_TRANS, 'PINJ');

								$jenisAkun		=	namaAkun(sukubunga('pendapatan_mudharabah'));
								$datatransaksi	=	array(
									'tgl' 		=> $key->TGL_BAYAR,
									'jumlah' 		=> $key->BASILBAYAR,
									'keterangan' 	=> $jenisAkun['JENIS_TRANSAKSI'] . ', No Rek : ' . $key->REKENING . '(' . $key->NAMA_AGT . ')',
									'user' 			=> $key->USERNAME,
									'kodecabang' 	=> $key->KODECABANG,
									'ket_dt'		=> $jenisAkun['JENIS_TRANSAKSI']
								);

								$this->ModelVTransaksi->insertVtransaksi($key->IDPINJ_D, $datatransaksi, 'JT', $key->KAS_ID, $jenisAkun['IDAKUN'], 'PINJ');

								$idAkunReset	=	$this->akunKasReset($key->KODECABANG);
								$datatransaksi	=	array(
									'tgl' 		=> $key->TGL_BAYAR,
									'jumlah' 		=> $key->DENDA_RP,
									'keterangan' 	=> 'Pendapatan Reset ' . $key->JENIS_TRANSAKSI . ', No Rek: ' . $key->REKENING . '(' . $key->NAMA_AGT . ')',
									'user' 			=> $key->USERNAME,
									'kodecabang' 	=> $key->KODECABANG
								);
								if ($key->DENDA_RP != 0) {
									$this->ModelVTransaksi->insertVtransaksi($key->IDPINJ_D, $datatransaksi, 'KR', $idAkunReset, sukubunga('admin_pembiayaan'), 'PINJ');
									$this->updateTabelReset($key->IDPINJAM, $key->DENDA_RP, 0);
								}

								/* Insert Biaya admin kolektor ke jurnal transaksi(table vtransaksi) */
								$datatransaksi	=	array(
									'tgl' 		=> $key->TGL_BAYAR,
									'jumlah' 		=> $key->BIAYA_KOLEKTOR,
									'keterangan' 	=> 'Pendapatan Admin Kolektor No Rek: ' . $key->REKENING . '(' . $key->NAMA_AGT . ')',
									'user' 			=> $key->USERNAME,
									'kodecabang' 	=> $key->KODECABANG
								);
								if ($key->BIAYA_KOLEKTOR != 0) {
									$this->ModelVTransaksi->insertVtransaksi($key->IDPINJ_D, $datatransaksi, 'RT', kasteller($key->KODECABANG), sukubunga('admin_kolektor'), 'PINJ');
									$this->updateTabelReset($key->IDPINJAM, $key->BIAYA_KOLEKTOR, 1);
								}
							}
						}

						$array = array(
							"success" => true,
							"message" => "Pembayaran angsuran Anda telah tersimpan"
						);
						echo json_encode($array);
					}
				}
			}
		}
	}

	protected function updateTabelReset($idpinjam, $jumlah, $jenis = 0)
	{
		$sql	=	sprintf("SELECT * FROM tbl_reset WHERE IDPINJAMAN = %s AND JENIS = %s AND LUNAS = 0 ORDER BY TANGGAL", $idpinjam, $jenis);
		$query	=	$this->dbasemodel->loadsql($sql);
		if ($query->num_rows() > 0) {
			foreach ($query->result() as $res) {
				if ($jumlah > 0) {
					$sisa	=	($res->JUMLAH - $res->DIBAYAR);
					$bayar	=	$jumlah >= $sisa ? $sisa : $jumlah;
					$lunas	=	($sisa == $bayar ? '1' : '0');
					$sql	=	sprintf("UPDATE tbl_reset SET DIBAYAR = (DIBAYAR + %s), LUNAS = %s WHERE ID = %s ", $bayar, $lunas, $res->ID);
					$this->dbasemodel->loadsql($sql);
					$jumlah	=	$jumlah - $bayar;
				}
			}
		}
	}
}
