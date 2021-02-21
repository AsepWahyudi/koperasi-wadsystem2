<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Tripay extends CI_Controller
{

	private $api_url = 'https://tripay.co.id/api/v2';
	private $header = array(
		'Accept: application/json',
		'Authorization: Bearer yY4DpFzEQXAmmvvFKU9PlkTiyVKoS94r'
	);
	private $pin = '1825';
	private $callback_secret = 'wad123';

	function __construct()
	{
		parent::__construct();
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
		header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
		$this->load->helper(array('form', 'url', 'xml', 'text_helper', 'date', 'inflector', 'app'));
		$this->load->database();
		$this->load->library(array('Pagination', 'user_agent', 'session', 'form_validation', 'session'));
		$this->load->model(array('mobileapi/dbasemodel', 'ModelVTransaksi'));
		//@session_start();
	}

	public function index()
	{
		$incomingSecret = isset($_SERVER['HTTP_X_CALLBACK_SECRET']) ? $_SERVER['HTTP_X_CALLBACK_SECRET'] : '';
		if (!hash_equals($this->callback_secret, $incomingSecret)) {
			exit("Invalid secret");
		}

		$json = file_get_contents("php://input");
		$post = json_decode($json, true);

		$nomertrx = date("ymdHis");
		$tgl = date("Y-m-d H:i:s");

		for ($i = 0; $i < sizeof($post); $i++) {

			$trxId = $post[$i]['trxid'];

			$cek = $this->dbasemodel->loadsql("SELECT * FROM m_trx WHERE TRXID='$trxId'");

			if ($cek->num_rows() > 0) {
				$trx = $cek->row();

				if ($trx->PAYMENT_VIA === 'saldo') {

					$defaultdb 	= $this->load->database('default', TRUE);
					$sql = "SELECT * FROM m_anggota_simp WHERE IDJENIS_SIMP = 180 AND IDANGGOTA='$trx->KODE_ANGGOTA'";
					$cekSaldo = $defaultdb->query($sql);

					if ($cekSaldo->num_rows() > 0) {
						$simpanan = $cekSaldo->row();

						$saldo = (int) $simpanan->SALDO;
						$hargaJual = (int) $trx->HARGA_JUAL;

						if ($saldo >= $hargaJual) {

							$statusCallback = (int) $post[$i]['status'];

							if ($statusCallback === 1) {
								$sql = "SELECT * FROM m_anggota WHERE IDANGGOTA = '$trx->KODE_ANGGOTA'";
								$cekAnggota = $defaultdb->query($sql);

								if ($cekAnggota->num_rows() > 0) {

									$anggota = $cekAnggota->row();

									$keterangan = "Pembayaran PPOB ($anggota->NAMA) Rp. $hargaJual";

									$sql = "INSERT INTO transaksi_simp(TGL_TRX, ID_ANGGOTA, ID_JENIS, JUMLAH, KETERANGAN, KET_BAYAR, AKUN, DK, ID_KAS, 
									USERNAME, NAMA_PENYETOR, NO_IDENTITAS, ALAMAT, KOLEKTOR, STATUS, KODEPUSAT, KODECABANG) VALUES 
									('$tgl', '$anggota->IDANGGOTA', 180, '$hargaJual', '$keterangan', 'Tabungan', 'Penarikan', 'K', 9, 
									'mobile-app', '$anggota->NAMA', '$anggota->NO_IDENTITAS', '$anggota->ALAMAT', 0, 1, '$anggota->KODEPUSAT', '$anggota->KODECABANG')";

									$defaultdb->query($sql);

									$sql = "SELECT * FROM transaksi_simp WHERE TGL_TRX = '$tgl' AND ID_ANGGOTA = '$anggota->IDANGGOTA'";
									$cekTrxSimp = $defaultdb->query($sql);

									if ($cekTrxSimp->num_rows() > 0) {
										foreach ($cekTrxSimp->result() as $key) {
											$datatransaksi	=	array(
												'tgl' 			=> $key->TGL_TRX,
												'jumlah' 		=> $key->JUMLAH,
												'keterangan' 	=> $key->KETERANGAN == '' ? 'Pembayaran PPOB (' . $key->NAMA_PENYETOR . ')' : $key->KETERANGAN,
												'user' 			=> $key->USERNAME,
												'kodecabang' 	=> $key->KODECABANG,
												'ket_dt' 		=> 'tarik tunai'
											);
											$this->ModelVTransaksi->insertVtransaksi($key->ID_TRX_SIMP, $datatransaksi, 'PT', $key->ID_KAS, $key->ID_JENIS, 'SIMP');
										}
									}
								}


								$sisaSaldo = $saldo - $hargaJual;
								$sql = "UPDATE m_anggota_simp SET SALDO = $sisaSaldo WHERE IDJENIS_SIMP = 180 AND IDANGGOTA='$trx->KODE_ANGGOTA'";
								$defaultdb->query($sql);
							}

							$datainsert = array(
								'KODE_TRX' => 1,
								'KODE_ANGGOTA' => $trx->KODE_ANGGOTA,
								'IDPEL' => $post[$i]['mtrpln'],
								'TGL' => $tgl,
								'NOTRX' => $nomertrx,
								'NOHP' => $trx->NOHP,
								'IDPRODUK' => $trx->IDPRODUK,
								'PRODUK' => $trx->PRODUK,
								'HARGA_BELI' => $trx->HARGA_BELI,
								'HARGA_JUAL' => $trx->HARGA_JUAL,
								'PAYMENT_VIA' => $trx->PAYMENT_VIA,
								'TOTAL_BAYAR' => $trx->HARGA_JUAL,
								'INQUIRY' => $trx->INQUIRY,
								'ORDER_ID' => $trx->ORDER_ID,
								'STATUS_BAYAR' => 1,
								'PROSES' => 1,
								'STATUS' => (int) $post[$i]['status'],
								'TRXID' => $post[$i]['trxid'],
								'LOG' => $json,
								'TOKEN' => $post[$i]['token'],
								'NOTE' => $post[$i]['note']
							);
							$this->dbasemodel->insertTrx("m_trx", $datainsert);
						}
					}
				} else if ($trx->PAYMENT_VIA === 'transfer') {
					$datainsert = array(
						'KODE_TRX' => 1,
						'KODE_ANGGOTA' => $trx->KODE_ANGGOTA,
						'IDPEL' => $post[$i]['mtrpln'],
						'TGL' => $tgl,
						'NOTRX' => $nomertrx,
						'NOHP' => $trx->NOHP,
						'IDPRODUK' => $trx->IDPRODUK,
						'PRODUK' => $trx->PRODUK,
						'HARGA_BELI' => $trx->HARGA_BELI,
						'HARGA_JUAL' => $trx->HARGA_JUAL,
						'PAYMENT_VIA' => $trx->PAYMENT_VIA,
						'KODE_UNIK' => $trx->KODE_UNIK,
						'TOTAL_BAYAR' => $trx->TOTAL_BAYAR,
						'INQUIRY' => $trx->INQUIRY,
						'ORDER_ID' => $trx->ORDER_ID,
						'STATUS_BAYAR' => 1,
						'PROSES' => 1,
						'STATUS' => (int) $post[$i]['status'],
						'TRXID' => $post[$i]['trxid'],
						'LOG' => $json,
						'TOKEN' => $post[$i]['token'],
						'NOTE' => $post[$i]['note']
					);
					$this->dbasemodel->insertTrx("m_trx", $datainsert);

					$statusCode = (int) $post[$i]['status'];

					// 0 = pending
					// 1 = sukses
					// 2 = gagal
					if ($statusCode === 1) {

						$defaultdb 	= $this->load->database('default', TRUE);
						$sql = "SELECT * FROM m_anggota_simp WHERE IDJENIS_SIMP = 180 AND IDANGGOTA='$trx->KODE_ANGGOTA'";
						$cekSaldo = $defaultdb->query($sql);

						if ($cekSaldo->num_rows() > 0) {
							$sql = "SELECT * FROM m_anggota WHERE IDANGGOTA = '$trx->KODE_ANGGOTA'";
							$cekAnggota = $defaultdb->query($sql);

							if ($cekAnggota->num_rows() > 0) {

								$anggota = $cekAnggota->row();

								$keterangan = "Penambahan Saldo Sebesar Kode Unik Dari Pembayaran PPOB ($anggota->NAMA) Rp. $trx->KODE_UNIK";

								$sql = "INSERT INTO transaksi_simp(TGL_TRX, ID_ANGGOTA, ID_JENIS, JUMLAH, KETERANGAN, KET_BAYAR, AKUN, DK, ID_KAS, 
									USERNAME, NAMA_PENYETOR, NO_IDENTITAS, ALAMAT, KOLEKTOR, STATUS, KODEPUSAT, KODECABANG) VALUES 
									('$tgl', '$anggota->IDANGGOTA', 180, '$trx->KODE_UNIK', '$keterangan', 'Tabungan', 'Setoran', 'D', 9, 
									'mobile-app', '$anggota->NAMA', '$anggota->NO_IDENTITAS', '$anggota->ALAMAT', 0, 1, '$anggota->KODEPUSAT', '$anggota->KODECABANG')";

								$defaultdb->query($sql);

								$sql = "SELECT * FROM transaksi_simp WHERE TGL_TRX = '$tgl' AND ID_ANGGOTA = '$anggota->IDANGGOTA'";
								$cekTrxSimp = $defaultdb->query($sql);

								if ($cekTrxSimp->num_rows() > 0) {
									foreach ($cekTrxSimp->result() as $key) {
										$datatransaksi	=	array(
											'tgl' 			=> $key->TGL_TRX,
											'jumlah' 		=> $key->JUMLAH,
											'keterangan' 	=> $key->KETERANGAN == '' ? 'Penambahan Saldo Sebesar Kode Unik Dari Pembayaran PPOB (' . $key->NAMA_PENYETOR . ')' : $key->KETERANGAN,
											'user' 			=> $key->USERNAME,
											'kodecabang' 	=> $key->KODECABANG,
											'ket_dt' 		=> 'tarik tunai'
										);
										$this->ModelVTransaksi->insertVtransaksi($key->ID_TRX_SIMP, $datatransaksi, 'PT', $key->ID_KAS, $key->ID_JENIS, 'SIMP');
									}
								}
							}

							// jika sukses, maka nilai dari KODE_UNIK ditambahkan ke saldo member
							$defaultdb 	= $this->load->database('default', TRUE);
							$sql = "UPDATE m_anggota_simp SET SALDO = SALDO + $trx->KODE_UNIK WHERE IDJENIS_SIMP = 180 AND IDANGGOTA='$trx->KODE_ANGGOTA'";
							$updateSaldo = $defaultdb->query($sql);

							if ($updateSaldo) {
								$datainsert = array(
									'KODE_TRX' => 1,
									'KODE_ANGGOTA' => $trx->KODE_ANGGOTA,
									'IDPEL' => $post[$i]['mtrpln'],
									'TGL' => $tgl,
									'NOTRX' => $nomertrx,
									'NOHP' => $trx->NOHP,
									'IDPRODUK' => $trx->IDPRODUK,
									'PRODUK' => $trx->PRODUK,
									'HARGA_BELI' => $trx->HARGA_BELI,
									'HARGA_JUAL' => $trx->HARGA_JUAL,
									'PAYMENT_VIA' => $trx->PAYMENT_VIA,
									'KODE_UNIK' => $trx->KODE_UNIK,
									'TOTAL_BAYAR' => $trx->TOTAL_BAYAR,
									'INQUIRY' => $trx->INQUIRY,
									'ORDER_ID' => $trx->ORDER_ID,
									'STATUS_BAYAR' => 1,
									'PROSES' => 1,
									'MSG' => 'Penambahan Kode Unik Transaksi ke saldo Anggota',
									'STATUS' => (int) $post[$i]['status'],
									'TRXID' => $post[$i]['trxid'],
									'LOG' => $json,
									'TOKEN' => $post[$i]['token'],
									'NOTE' => $post[$i]['note']
								);
								$this->dbasemodel->insertTrx("m_trx", $datainsert);
							}
						} else {
							$sql = "SELECT * FROM m_anggota WHERE IDANGGOTA = '$trx->KODE_ANGGOTA'";
							$cekAnggota = $defaultdb->query($sql);

							if ($cekAnggota->num_rows() > 0) {

								$anggota = $cekAnggota->row();

								$keterangan = "Penambahan Saldo Sebesar Kode Unik Dari Pembayaran PPOB ($anggota->NAMA) Rp. $trx->KODE_UNIK";

								$sql = "INSERT INTO transaksi_simp(TGL_TRX, ID_ANGGOTA, ID_JENIS, JUMLAH, KETERANGAN, KET_BAYAR, AKUN, DK, ID_KAS, 
									USERNAME, NAMA_PENYETOR, NO_IDENTITAS, ALAMAT, KOLEKTOR, STATUS, KODEPUSAT, KODECABANG) VALUES 
									('$tgl', '$anggota->IDANGGOTA', 180, '$trx->KODE_UNIK', '$keterangan', 'Tabungan', 'Setoran', 'D', 9, 
									'mobile-app', '$anggota->NAMA', '$anggota->NO_IDENTITAS', '$anggota->ALAMAT', 0, 1, '$anggota->KODEPUSAT', '$anggota->KODECABANG')";

								$defaultdb->query($sql);

								$sql = "SELECT * FROM transaksi_simp WHERE TGL_TRX = '$tgl' AND ID_ANGGOTA = '$anggota->IDANGGOTA'";
								$cekTrxSimp = $defaultdb->query($sql);

								if ($cekTrxSimp->num_rows() > 0) {
									foreach ($cekTrxSimp->result() as $key) {
										$datatransaksi	=	array(
											'tgl' 			=> $key->TGL_TRX,
											'jumlah' 		=> $key->JUMLAH,
											'keterangan' 	=> $key->KETERANGAN == '' ? 'Penambahan Saldo Sebesar Kode Unik Dari Pembayaran PPOB (' . $key->NAMA_PENYETOR . ')' : $key->KETERANGAN,
											'user' 			=> $key->USERNAME,
											'kodecabang' 	=> $key->KODECABANG,
											'ket_dt' 		=> 'tarik tunai'
										);
										$this->ModelVTransaksi->insertVtransaksi($key->ID_TRX_SIMP, $datatransaksi, 'PT', $key->ID_KAS, $key->ID_JENIS, 'SIMP');
									}
								}
							}

							// jika sukses, maka nilai dari KODE_UNIK ditambahkan ke saldo member
							$defaultdb 	= $this->load->database('default', TRUE);
							$sql = "INSERT INTO m_anggota_simp (IDANGGOTA, IDJENIS_SIMP, SALDO) VALUES ('$trx->KODE_ANGGOTA', 180, '$trx->KODE_UNIK)";
							$insertSaldo = $defaultdb->query($sql);

							if ($insertSaldo) {
								$datainsert = array(
									'KODE_TRX' => 1,
									'KODE_ANGGOTA' => $trx->KODE_ANGGOTA,
									'IDPEL' => $post[$i]['mtrpln'],
									'TGL' => $tgl,
									'NOTRX' => $nomertrx,
									'NOHP' => $trx->NOHP,
									'IDPRODUK' => $trx->IDPRODUK,
									'PRODUK' => $trx->PRODUK,
									'HARGA_BELI' => $trx->HARGA_BELI,
									'HARGA_JUAL' => $trx->HARGA_JUAL,
									'PAYMENT_VIA' => $trx->PAYMENT_VIA,
									'KODE_UNIK' => $trx->KODE_UNIK,
									'TOTAL_BAYAR' => $trx->TOTAL_BAYAR,
									'INQUIRY' => $trx->INQUIRY,
									'ORDER_ID' => $trx->ORDER_ID,
									'STATUS_BAYAR' => 1,
									'PROSES' => 1,
									'MSG' => 'Penambahan Kode Unik Transaksi ke saldo Anggota',
									'STATUS' => (int) $post[$i]['status'],
									'TRXID' => $post[$i]['trxid'],
									'LOG' => $json,
									'TOKEN' => $post[$i]['token'],
									'NOTE' => $post[$i]['note']
								);
								$this->dbasemodel->insertTrx("m_trx", $datainsert);
							}
						}
					} else if ($statusCode === 2) {

						$defaultdb 	= $this->load->database('default', TRUE);
						$sql = "SELECT * FROM m_anggota_simp WHERE IDJENIS_SIMP = 180 AND IDANGGOTA='$trx->KODE_ANGGOTA'";
						$cekSaldo = $defaultdb->query($sql);

						if ($cekSaldo->num_rows() > 0) {
							$sql = "SELECT * FROM m_anggota WHERE IDANGGOTA = '$trx->KODE_ANGGOTA'";
							$cekAnggota = $defaultdb->query($sql);

							if ($cekAnggota->num_rows() > 0) {

								$anggota = $cekAnggota->row();

								$keterangan = "Penambahan Saldo Sebesar Total Bayar Dari Pembayaran PPOB ($anggota->NAMA) Rp. $trx->TOTAL_BAYAR";

								$sql = "INSERT INTO transaksi_simp(TGL_TRX, ID_ANGGOTA, ID_JENIS, JUMLAH, KETERANGAN, KET_BAYAR, AKUN, DK, ID_KAS, 
									USERNAME, NAMA_PENYETOR, NO_IDENTITAS, ALAMAT, KOLEKTOR, STATUS, KODEPUSAT, KODECABANG) VALUES 
									('$tgl', '$anggota->IDANGGOTA', 180, '$trx->TOTAL_BAYAR', '$keterangan', 'Tabungan', 'Setoran', 'D', 9, 
									'mobile-app', '$anggota->NAMA', '$anggota->NO_IDENTITAS', '$anggota->ALAMAT', 0, 1, '$anggota->KODEPUSAT', '$anggota->KODECABANG')";

								$defaultdb->query($sql);

								$sql = "SELECT * FROM transaksi_simp WHERE TGL_TRX = '$tgl' AND ID_ANGGOTA = '$anggota->IDANGGOTA'";
								$cekTrxSimp = $defaultdb->query($sql);

								if ($cekTrxSimp->num_rows() > 0) {
									foreach ($cekTrxSimp->result() as $key) {
										$datatransaksi	=	array(
											'tgl' 			=> $key->TGL_TRX,
											'jumlah' 		=> $key->JUMLAH,
											'keterangan' 	=> $key->KETERANGAN == '' ? 'Penambahan Saldo Sebesar Total Bayar Dari Pembayaran PPOB (' . $key->NAMA_PENYETOR . ')' : $key->KETERANGAN,
											'user' 			=> $key->USERNAME,
											'kodecabang' 	=> $key->KODECABANG,
											'ket_dt' 		=> 'tarik tunai'
										);
										$this->ModelVTransaksi->insertVtransaksi($key->ID_TRX_SIMP, $datatransaksi, 'PT', $key->ID_KAS, $key->ID_JENIS, 'SIMP');
									}
								}
							}

							// jika gagal, maka semua nilai transfer masukkan ke saldo member
							// meski gagal transaksi, uang yg sudah ditransfer jgn hilang, harus masuk ke saldo member

							$defaultdb 	= $this->load->database('default', TRUE);
							$sql = "UPDATE m_anggota_simp SET SALDO = SALDO + $trx->TOTAL_BAYAR WHERE IDJENIS_SIMP = 180 AND IDANGGOTA='$trx->KODE_ANGGOTA'";
							$updateSaldo = $defaultdb->query($sql);

							if ($updateSaldo) {
								$datainsert = array(
									'KODE_TRX' => 1,
									'KODE_ANGGOTA' => $trx->KODE_ANGGOTA,
									'IDPEL' => $post[$i]['mtrpln'],
									'TGL' => $tgl,
									'NOTRX' => $nomertrx,
									'NOHP' => $trx->NOHP,
									'IDPRODUK' => $trx->IDPRODUK,
									'PRODUK' => $trx->PRODUK,
									'HARGA_BELI' => $trx->HARGA_BELI,
									'HARGA_JUAL' => $trx->HARGA_JUAL,
									'PAYMENT_VIA' => $trx->PAYMENT_VIA,
									'KODE_UNIK' => $trx->KODE_UNIK,
									'TOTAL_BAYAR' => $trx->TOTAL_BAYAR,
									'INQUIRY' => $trx->INQUIRY,
									'ORDER_ID' => $trx->ORDER_ID,
									'STATUS_BAYAR' => 1,
									'PROSES' => 1,
									'MSG' => 'Penambahan Nilai Transfer ke dalam saldo Anggota karena transaksi gagal',
									'STATUS' => (int) $post[$i]['status'],
									'TRXID' => $post[$i]['trxid'],
									'LOG' => $json,
									'TOKEN' => $post[$i]['token'],
									'NOTE' => $post[$i]['note']
								);
								$this->dbasemodel->insertTrx("m_trx", $datainsert);
							}
						} else {

							$sql = "SELECT * FROM m_anggota WHERE IDANGGOTA = '$trx->KODE_ANGGOTA'";
							$cekAnggota = $defaultdb->query($sql);

							if ($cekAnggota->num_rows() > 0) {

								$anggota = $cekAnggota->row();

								$keterangan = "Penambahan Saldo Sebesar Total Bayar Dari Pembayaran PPOB ($anggota->NAMA) Rp. $trx->TOTAL_BAYAR";

								$sql = "INSERT INTO transaksi_simp(TGL_TRX, ID_ANGGOTA, ID_JENIS, JUMLAH, KETERANGAN, KET_BAYAR, AKUN, DK, ID_KAS, 
									USERNAME, NAMA_PENYETOR, NO_IDENTITAS, ALAMAT, KOLEKTOR, STATUS, KODEPUSAT, KODECABANG) VALUES 
									('$tgl', '$anggota->IDANGGOTA', 180, '$trx->TOTAL_BAYAR', '$keterangan', 'Tabungan', 'Setoran', 'D', 9, 
									'mobile-app', '$anggota->NAMA', '$anggota->NO_IDENTITAS', '$anggota->ALAMAT', 0, 1, '$anggota->KODEPUSAT', '$anggota->KODECABANG')";

								$defaultdb->query($sql);

								$sql = "SELECT * FROM transaksi_simp WHERE TGL_TRX = '$tgl' AND ID_ANGGOTA = '$anggota->IDANGGOTA'";
								$cekTrxSimp = $defaultdb->query($sql);

								if ($cekTrxSimp->num_rows() > 0) {
									foreach ($cekTrxSimp->result() as $key) {
										$datatransaksi	=	array(
											'tgl' 			=> $key->TGL_TRX,
											'jumlah' 		=> $key->JUMLAH,
											'keterangan' 	=> $key->KETERANGAN == '' ? 'Penambahan Saldo Sebesar Total Bayar Dari Pembayaran PPOB (' . $key->NAMA_PENYETOR . ')' : $key->KETERANGAN,
											'user' 			=> $key->USERNAME,
											'kodecabang' 	=> $key->KODECABANG,
											'ket_dt' 		=> 'tarik tunai'
										);
										$this->ModelVTransaksi->insertVtransaksi($key->ID_TRX_SIMP, $datatransaksi, 'PT', $key->ID_KAS, $key->ID_JENIS, 'SIMP');
									}
								}
							}

							$defaultdb 	= $this->load->database('default', TRUE);
							$sql = "INSERT INTO m_anggota_simp (IDANGGOTA, IDJENIS_SIMP, SALDO) VALUES ('$trx->KODE_ANGGOTA', 180, '$trx->TOTAL_BAYAR)";
							$insertSaldo = $defaultdb->query($sql);

							if ($insertSaldo) {
								$datainsert = array(
									'KODE_TRX' => 1,
									'KODE_ANGGOTA' => $trx->KODE_ANGGOTA,
									'IDPEL' => $post[$i]['mtrpln'],
									'TGL' => $tgl,
									'NOTRX' => $nomertrx,
									'NOHP' => $trx->NOHP,
									'IDPRODUK' => $trx->IDPRODUK,
									'PRODUK' => $trx->PRODUK,
									'HARGA_BELI' => $trx->HARGA_BELI,
									'HARGA_JUAL' => $trx->HARGA_JUAL,
									'PAYMENT_VIA' => $trx->PAYMENT_VIA,
									'KODE_UNIK' => $trx->KODE_UNIK,
									'TOTAL_BAYAR' => $trx->TOTAL_BAYAR,
									'INQUIRY' => $trx->INQUIRY,
									'ORDER_ID' => $trx->ORDER_ID,
									'STATUS_BAYAR' => 1,
									'PROSES' => 1,
									'MSG' => 'Penambahan Nilai Transfer ke dalam saldo Anggota karena transaksi gagal',
									'STATUS' => (int) $post[$i]['status'],
									'TRXID' => $post[$i]['trxid'],
									'LOG' => $json,
									'TOKEN' => $post[$i]['token'],
									'NOTE' => $post[$i]['note']
								);
								$this->dbasemodel->insertTrx("m_trx", $datainsert);
							}
						}
					}
				}
			}
		}
	}

	public function cek_server()
	{
		$url = $this->api_url . '/cekserver/';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $this->header);
		curl_setopt($ch, CURLOPT_POST, 1);
		$result = curl_exec($ch);

		if (curl_errno($ch)) {
			return 'Request Error:' . curl_error($ch);
		}
		echo $result;
	}

	public function cek_saldo()
	{
		$url = $this->api_url . '/ceksaldo/';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $this->header);
		curl_setopt($ch, CURLOPT_POST, 1);
		$result = curl_exec($ch);

		if (curl_errno($ch)) {
			return 'Request Error:' . curl_error($ch);
		}
		echo $result;
	}

	public function kategori_pembelian()
	{
		$url = 'https://tripay.co.id/api/v2/pembelian/category/';

        $header = array(
           'Accept: application/json',
           'Authorization: Bearer yY4DpFzEQXAmmvvFKU9PlkTiyVKoS94r', // Ganti [apikey] dengan API KEY Anda
        );
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        $result = curl_exec($ch);
        
        if(curl_errno($ch)){
           return 'Request Error:' . curl_error($ch);
        }
        echo $result;
	}

	public function operator_pembelian()
	{
		$url = $this->api_url . '/pembelian/operator/';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $this->header);
		curl_setopt($ch, CURLOPT_POST, 1);
		$result = curl_exec($ch);

		if (curl_errno($ch)) {
			return 'Request Error:' . curl_error($ch);
		}
		echo $result;
	}

	public function produk_pembelian()
	{
		$url = $this->api_url . '/pembelian/produk/';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $this->header);
		curl_setopt($ch, CURLOPT_POST, 1);
		$result = curl_exec($ch);

		if (curl_errno($ch)) {
			return 'Request Error:' . curl_error($ch);
		}
		echo $result;
	}

	public function detail_produk_pembelian()
	{
		$url = $this->api_url . '/pembelian/produk/cek';

		$post = file_get_contents("php://input");
		$postData = json_decode($post);

		$data = array(
			'code' => $postData->code, // Kode Operator
		);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $this->header);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		$result = curl_exec($ch);

		echo $result;
	}

	public function transaksi_pembelian()
	{
		$url = $this->api_url . '/transaksi/pembelian';

		$post = file_get_contents("php://input");
		$postData = json_decode($post);

		if ($postData->via === 'saldo') {
			$defaultdb 	= $this->load->database('default', TRUE);
			$sql = "SELECT * FROM m_anggota_simp WHERE IDJENIS_SIMP = 180 AND IDANGGOTA='$postData->idAnggota'";
			$cekSaldo = $defaultdb->query($sql);

			if ($cekSaldo->num_rows() > 0) {

				$simpanan = $cekSaldo->row();

				$saldo = (int) $simpanan->SALDO;

				$cek = $this->dbasemodel->loadsql("SELECT * FROM m_product WHERE KODE='$postData->code'");

				if ($cek->num_rows() > 0) {
					$prod = $cek->row();

					$hargaJual = (int) $prod->HARGA_JUAL;

					if ($hargaJual > $saldo) {
						$array = array(
							"success" => false,
							"message" => "Maaf, saldo Anda tidak mencukupi"
						);
						echo json_encode($array);
					} else {

						if ($postData->plnPrabayar === false) {

							$data = array(
								'inquiry' => 'I', // 'PLN' untuk pembelian PLN Prabayar, atau 'I' (i besar) untuk produk lainnya
								'code' => $postData->code, // kode produk
								'phone' => $postData->phone, // nohp pembeli
								'pin' => $this->pin, // pin member
							);

							$ch = curl_init();
							curl_setopt($ch, CURLOPT_URL, $url);
							curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
							curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
							curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
							curl_setopt($ch, CURLOPT_HTTPHEADER, $this->header);
							curl_setopt($ch, CURLOPT_POST, 1);
							curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
							$result = curl_exec($ch);

							if (curl_errno($ch)) {
								return 'Request Error:' . curl_error($ch);
							}

							$res = json_decode($result, true);

							$strRes = json_encode($result);

							if ($res['success'] === true) {

								$nomertrx = date("ymdHis");
								$tgl = date("Y-m-d H:i:s");

								$datainsert = array(
									'TRXID' => $res['trxid'],
									'KODE_TRX' => 1,
									'KODE_ANGGOTA' => $postData->idAnggota,
									'TGL' => $tgl,
									'NOTRX' => $nomertrx,
									'NOHP' => $postData->phone,
									'IDPRODUK' => $prod->IDPRODUK,
									'PRODUK' => $postData->code,
									'HARGA_BELI' => $prod->HARGA_BELI,
									'HARGA_JUAL' => $prod->HARGA_JUAL,
									'TOTAL_BAYAR' => $prod->HARGA_JUAL,
									'STATUS_BAYAR' => 0,
									'PAYMENT_VIA' => $postData->via,
									'PROSES' => 1,
									'STATUS' => 0,
									'MSG' => $res['message'],
									'INQUIRY' => 'I',
									'LOG' => $strRes,
									'IDH2H' => 1
								);
								$this->dbasemodel->insertTrx("m_trx", $datainsert);

								echo $result;
							} else {
								echo $result;
							}
						} else {
							$data = array(
								'inquiry' => 'PLN', // 'PLN' untuk pembelian PLN Prabayar, atau 'I' (i besar) untuk produk lainnya
								'code' => $postData->code, // kode produk
								'phone' => $postData->phone, // nohp pembeli
								'no_meter_pln' => $postData->noMeter, // khusus untuk pembelian token PLN prabayar
								'pin' => $this->pin, // pin member
							);

							$ch = curl_init();
							curl_setopt($ch, CURLOPT_URL, $url);
							curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
							curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
							curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
							curl_setopt($ch, CURLOPT_HTTPHEADER, $this->header);
							curl_setopt($ch, CURLOPT_POST, 1);
							curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
							$result = curl_exec($ch);

							if (curl_errno($ch)) {
								return 'Request Error:' . curl_error($ch);
							}

							$res = json_decode($result, true);

							$strRes = json_encode($result);

							if ($res['success'] === true) {

								$nomertrx = date("ymdHis");
								$tgl = date("Y-m-d H:i:s");

								$datainsert = array(
									'TRXID' => $res['trxid'],
									'KODE_TRX' => 1,
									'KODE_ANGGOTA' => $postData->idAnggota,
									'TGL' => $tgl,
									'NOTRX' => $nomertrx,
									'NOHP' => $postData->phone,
									'IDPRODUK' => $prod->IDPRODUK,
									'PRODUK' => $postData->code,
									'HARGA_BELI' => $prod->HARGA_BELI,
									'HARGA_JUAL' => $prod->HARGA_JUAL,
									'TOTAL_BAYAR' => $prod->HARGA_JUAL,
									'STATUS_BAYAR' => 0,
									'PAYMENT_VIA' => $postData->via,
									'PROSES' => 1,
									'STATUS' => 0,
									'MSG' => $res['message'],
									'INQUIRY' => 'PLN',
									'LOG' => $strRes,
									'IDH2H' => 1
								);
								$this->dbasemodel->insertTrx("m_trx", $datainsert);

								echo $result;
							} else {
								echo $result;
							}
						}
					}
				} else {
					$array = array(
						"success" => false,
						"message" => "Maaf, produk belum terdaftar"
					);
					echo json_encode($array);
				}
			} else {
				$array = array(
					"success" => false,
					"saldo" => 0,
					"message" => "Maaf, Saldo Anda tidak mencukupi."
				);
				echo json_encode($array);
			}
		} else if ($postData->via === 'transfer') {
			$cek = $this->dbasemodel->loadsql("SELECT * FROM m_product WHERE KODE='$postData->code'");

			if ($cek->num_rows() > 0) {
				$prod = $cek->row();
				$nomertrx = date("ymdHis");
				$tgl = date("Y-m-d H:i:s");
				$expiredDate = date("Y-m-d H:i:s", strtotime("+1 hour"));

				if ($postData->plnPrabayar === false) {
					$datainsert = array(
						'KODE_TRX' => 1,
						'KODE_ANGGOTA' => $postData->idAnggota,
						'TGL' => $tgl,
						'NOTRX' => $nomertrx,
						'NOHP' => $postData->phone,
						'IDPRODUK' => $prod->IDPRODUK,
						'PRODUK' => $postData->code,
						'HARGA_BELI' => $prod->HARGA_BELI,
						'HARGA_JUAL' => $prod->HARGA_JUAL,
						'KODE_UNIK' => $postData->kode_unik,
						'TOTAL_BAYAR' => $postData->total_bayar,
						'STATUS_BAYAR' => 0,
						'EXPIRED_DATE' => $expiredDate,
						'PAYMENT_VIA' => $postData->via,
						'INQUIRY' => 'I',
						'PROSES' => 0,
						'STATUS' => 0,
						'MSG' => "Pembelian Anda telah tersimpan. Silakan lakukan pembayaran transfer sebelum $expiredDate.",
						'IDH2H' => 1
					);
					$this->dbasemodel->insertTrx("m_trx", $datainsert);
				} else {
					$datainsert = array(
						'KODE_TRX' => 1,
						'KODE_ANGGOTA' => $postData->idAnggota,
						'TGL' => $tgl,
						'NOTRX' => $nomertrx,
						'NOHP' => $postData->phone,
						'IDPEL' => $postData->noMeter,
						'IDPRODUK' => $prod->IDPRODUK,
						'PRODUK' => $postData->code,
						'HARGA_BELI' => $prod->HARGA_BELI,
						'HARGA_JUAL' => $prod->HARGA_JUAL,
						'KODE_UNIK' => $postData->kode_unik,
						'TOTAL_BAYAR' => $postData->total_bayar,
						'STATUS_BAYAR' => 0,
						'EXPIRED_DATE' => $expiredDate,
						'PAYMENT_VIA' => $postData->via,
						'INQUIRY' => 'PLN',
						'PROSES' => 0,
						'STATUS' => 0,
						'MSG' => "Pembelian Anda telah tersimpan. Silakan lakukan pembayaran transfer sebelum $expiredDate.",
						'IDH2H' => 1
					);
					$this->dbasemodel->insertTrx("m_trx", $datainsert);
				}

				$array = array(
					"success" => true,
					"message" => "Pembelian Anda telah tersimpan. Silakan lakukan pembayaran transfer sebelum $expiredDate. Selengkapnya dapat dilihat pada Riwayat Transaksi."
				);
				echo json_encode($array);
			} else {
				$array = array(
					"success" => false,
					"message" => "Maaf, produk belum terdaftar"
				);
				echo json_encode($array);
			}
		}
	}

	public function kategori_pembayaran()
	{
		$url = $this->api_url . '/pembayaran/category/';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $this->header);
		curl_setopt($ch, CURLOPT_POST, 1);
		$result = curl_exec($ch);

		if (curl_errno($ch)) {
			return 'Request Error:' . curl_error($ch);
		}
		echo $result;
	}

	public function operator_pembayaran()
	{
		$url = $this->api_url . '/pembayaran/operator/';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $this->header);
		curl_setopt($ch, CURLOPT_POST, 1);
		$result = curl_exec($ch);

		if (curl_errno($ch)) {
			return 'Request Error:' . curl_error($ch);
		}
		echo $result;
	}

	public function produk_pembayaran()
	{
		$url = $this->api_url . '/pembayaran/produk/';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $this->header);
		curl_setopt($ch, CURLOPT_POST, 1);
		$result = curl_exec($ch);

		if (curl_errno($ch)) {
			return 'Request Error:' . curl_error($ch);
		}
		echo $result;
	}

	public function detail_produk_pembayaran()
	{
		$url = $this->api_url . '/pembayaran/produk/cek';

		$post = file_get_contents("php://input");
		$postData = json_decode($post);

		$data = array(
			'code' => $postData->code, // Kode Operator
		);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $this->header);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		$result = curl_exec($ch);

		echo $result;
	}

	public function cek_tagihan_pembayaran()
	{
		$url = $this->api_url . '/pembayaran/cek-tagihan';

		$post = file_get_contents("php://input");
		$postData = json_decode($post);

		$data = array(
			'product' => $postData->productId, // Masukkan ID Produk (exp : PLN)
			'phone' => $postData->phone, // Masukkan No.hp Anda
			'no_pelanggan' => $postData->idPelanggan, // Masukkan ID Pelanggan (exp: no.meteran/ id pembayaran)
			'pin' => $this->pin, // Masukkan PIN user (anda)
		);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $this->header);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		$result = curl_exec($ch);

		if (curl_errno($ch)) {
			return 'Request Error:' . curl_error($ch);
		}
		echo $result;
	}

	public function bayar_tagihan_pembayaran()
	{
		$url = $this->api_url . '/transaksi/pembayaran';

		$post = file_get_contents("php://input");
		$postData = json_decode($post);

		if ($postData->via === 'saldo') {
			$defaultdb 	= $this->load->database('default', TRUE);
			$sql = "SELECT * FROM m_anggota_simp WHERE IDJENIS_SIMP = 180 AND IDANGGOTA='$postData->idAnggota'";
			$cekSaldo = $defaultdb->query($sql);

			if ($cekSaldo->num_rows() > 0) {

				$simpanan = $cekSaldo->row();

				$saldo = (int) $simpanan->SALDO;

				$cek = $this->dbasemodel->loadsql("SELECT * FROM m_product WHERE KODE='$postData->code'");

				if ($cek->num_rows() > 0) {

					$prod = $cek->row();

					$hargaJual = (int) $postData->harga_jual;

					if ($hargaJual > $saldo) {
						$array = array(
							"success" => false,
							"message" => "Maaf, saldo Anda tidak mencukupi"
						);
						echo json_encode($array);
					} else {
						$data = array(
							'order_id' => $postData->orderId, // Masukkan ID yang didapat setelah melakukan pengecekan pembayaran
							'pin' => $this->pin, // Masukkan PIN user (anda)
						);

						$ch = curl_init();
						curl_setopt($ch, CURLOPT_URL, $url);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
						curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
						curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
						curl_setopt($ch, CURLOPT_HTTPHEADER, $this->header);
						curl_setopt($ch, CURLOPT_POST, 1);
						curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
						$result = curl_exec($ch);

						if (curl_errno($ch)) {
							return 'Request Error:' . curl_error($ch);
						}

						$res = json_decode($result, true);

						$strRes = json_encode($result);

						if ($res['success'] === true) {

							$nomertrx = date("ymdHis");
							$tgl = date("Y-m-d H:i:s");

							$datainsert = array(
								'TRXID' => $res['trxid'],
								'KODE_TRX' => 1,
								'KODE_ANGGOTA' => $postData->idAnggota,
								'IDPEL' => $postData->idPelanggan,
								'TGL' => $tgl,
								'NOTRX' => $nomertrx,
								'NOHP' => $postData->phone,
								'IDPRODUK' => $prod->IDPRODUK,
								'PRODUK' => $postData->code,
								'HARGA_BELI' => $postData->harga_beli,
								'HARGA_JUAL' => $prod->harga_jual,
								'TOTAL_BAYAR' => $prod->price,
								'STATUS_BAYAR' => 0,
								'PAYMENT_VIA' => $postData->via,
								'PROSES' => 1,
								'STATUS' => 0,
								'MSG' => $res['message'],
								'LOG' => $strRes,
								'ORDER_ID' => $postData->orderId,
								'IDH2H' => 1
							);
							$this->dbasemodel->insertTrx("m_trx", $datainsert);
							echo $result;
						} else {
							echo $result;
						}
					}
				} else {
					$array = array(
						"success" => false,
						"message" => "Maaf, produk belum terdaftar"
					);
					echo json_encode($array);
				}
			} else {
				$array = array(
					"success" => false,
					"saldo" => 0,
					"message" => "Maaf, Saldo Anda tidak mencukupi."
				);
				echo json_encode($array);
			}
		} else if ($postData->via === 'transfer') {
			$cek = $this->dbasemodel->loadsql("SELECT * FROM m_product WHERE KODE='$postData->code'");

			if ($cek->num_rows() > 0) {

				$prod = $cek->row();

				$nomertrx = date("ymdHis");
				$tgl = date("Y-m-d H:i:s");
				$expiredDate = date("Y-m-d H:i:s", strtotime("+1 hour"));

				$datainsert = array(
					'KODE_TRX' => 1,
					'KODE_ANGGOTA' => $postData->idAnggota,
					'IDPEL' => $postData->idPelanggan,
					'TGL' => $tgl,
					'NOTRX' => $nomertrx,
					'NOHP' => $postData->phone,
					'IDPRODUK' => $prod->IDPRODUK,
					'PRODUK' => $postData->code,
					'HARGA_BELI' => $postData->harga_beli,
					'HARGA_JUAL' => $prod->harga_jual,
					'KODE_UNIK' => $postData->kode_unik,
					'TOTAL_BAYAR' => $postData->total_price,
					'STATUS_BAYAR' => 0,
					'PAYMENT_VIA' => $postData->via,
					'PROSES' => 0,
					'STATUS' => 0,
					'MSG' => "Pembelian Anda telah tersimpan. Silakan lakukan pembayaran transfer sebelum $expiredDate.",
					'ORDER_ID' => $postData->orderId,
					'IDH2H' => 1
				);

				$this->dbasemodel->insertTrx("m_trx", $datainsert);

				$array = array(
					"success" => true,
					"message" => "Pembelian Anda telah tersimpan. Silakan lakukan pembayaran transfer sebelum $expiredDate. Selengkapnya dapat dilihat pada Riwayat Transaksi."
				);
				echo json_encode($array);
			} else {
				$array = array(
					"success" => false,
					"message" => "Maaf, produk belum terdaftar"
				);
				echo json_encode($array);
			}
		}
	}
}
