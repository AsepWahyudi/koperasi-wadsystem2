<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Cekmutasi extends CI_Controller

{



	private $api_url = 'https://api.cekmutasi.co.id/v1';

	private $api_key = '03583bca1736edd8c64defc84fb128255f887dc2e544e';

	private $header = array(

		'Accept: application/json',

		'Api-Key: 03583bca1736edd8c64defc84fb128255f887dc2e544e'

	);

	private $api_signature = 'S8xYz1bejZGq3gjfy49kTE5LDe2JdP64';



	private $tripay_url = 'https://tripay.co.id/api/v2';

	private $tripay_header = array(

		'Accept: application/json',

		'Authorization: Bearer yY4DpFzEQXAmmvvFKU9PlkTiyVKoS94r'

	);

	private $tripay_pin = '1825';



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

		$cekmutasi = array(

			"api_signature" => $this->api_signature

		);



		$incomingApiSignature = isset($_SERVER['HTTP_API_SIGNATURE']) ? $_SERVER['HTTP_API_SIGNATURE'] : '';



		// validasi API Signature

		if (!hash_equals($cekmutasi['api_signature'], $incomingApiSignature)) {

			exit("Invalid Signature");

		}



		$post = file_get_contents("php://input");

		$json = json_decode($post);



		if (json_last_error() !== JSON_ERROR_NONE) {

			exit("Invalid JSON");

		}



		if ($json->action == "payment_report") {

			foreach ($json->content->data as $data) 
			{
				$time = $data->unix_timestamp;
				$strTime = date("Y-m-d H:i:s", $time);
				$type = $data->type;
				$amount = (int) $data->amount;

				if ($type === 'credit') 
				{
					$cek = $this->dbasemodel->loadsql("SELECT * FROM m_trx WHERE PAYMENT_VIA = 'transfer' AND STATUS_BAYAR = 0 AND TOTAL_BAYAR = $amount AND EXPIRED_DATE >= '$strTime'");

					if ($cek->num_rows() > 0) 
					{
						$trx = $cek->row();
						$otherdb 	= $this->load->database('otherdb', TRUE);
						$sql = "UPDATE m_trx SET STATUS_BAYAR = 1, TGL_BAYAR = '$strTime' WHERE IDTRX = $trx->IDTRX";
						$updateTrx = $otherdb->query($sql);



						if ($updateTrx) {

							$trx->KODE_TRX = (int) $trx->KODE_TRX;

							if ($trx->KODE_TRX === 1 && $trx->INQUIRY !== NULL) { // ini artinya transaksi pembelian



								if ($trx->INQUIRY === 'I') {

									$data = array(

										'inquiry' => 'I', // 'PLN' untuk pembelian PLN Prabayar, atau 'I' (i besar) untuk produk lainnya

										'code' => $trx->PRODUK, // kode produk

										'phone' => $trx->NOHP, // nohp pembeli

										'pin' => $this->tripay_pin, // pin member

									);



									$url = $this->tripay_url . '/transaksi/pembelian';



									$ch = curl_init();

									curl_setopt($ch, CURLOPT_URL, $url);

									curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

									curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

									curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);

									curl_setopt($ch, CURLOPT_HTTPHEADER, $this->tripay_header);

									curl_setopt($ch, CURLOPT_POST, 1);

									curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

									$result = curl_exec($ch);



									if (curl_errno($ch)) {

										return 'Request Error:' . curl_error($ch);

									}



									$res = json_decode($result, true);



									$strRes = json_encode($result);



									if ($res['success'] === false) {



										$nomertrx = date("ymdHis");

										$tgl = date("Y-m-d H:i:s");



										// jika gagal, maka semua nilai transfer masukkan ke saldo member

										// meski gagal transaksi, uang yg sudah ditransfer jgn hilang, harus masuk ke saldo member



										$defaultdb 	= $this->load->database('default', TRUE);

										$sql = "SELECT * FROM m_anggota_simp WHERE IDJENIS_SIMP = '$trx->ID_JENIS_SIMPANAN' AND IDANGGOTA='$trx->KODE_ANGGOTA'";

										$cekSaldo = $defaultdb->query($sql);



										if ($cekSaldo->num_rows() > 0) {

											$sql = "SELECT * FROM m_anggota WHERE IDANGGOTA = '$trx->KODE_ANGGOTA'";

											$cekAnggota = $defaultdb->query($sql);



											if ($cekAnggota->num_rows() > 0) {



												$anggota = $cekAnggota->row();



												$sql = "SELECT * FROM jenis_kas WHERE KODECABANG = '$anggota->KODECABANG'";

												$cekJenisKas = $defaultdb->query($sql);

												$jnsKas = $cekJenisKas->row();



												$keterangan = "Penambahan Saldo Sebesar Total Bayar Dari Pembayaran PPOB ($anggota->NAMA) Rp. $trx->TOTAL_BAYAR";
                                                $now = date('Y-m-d H:i:s');


												$sql = "INSERT INTO transaksi_simp(TGL_TRX, ID_ANGGOTA, ID_JENIS, JUMLAH, KETERANGAN, KET_BAYAR, AKUN, DK, ID_KAS, ID_KASAKUN,ID_KASAKUN,

														USERNAME, NAMA_PENYETOR, NO_IDENTITAS, ALAMAT, KOLEKTOR, STATUS, KODEPUSAT, KODECABANG, UPDATE_DATA) VALUES 

														('$tgl', '$anggota->IDANGGOTA', '$trx->ID_JENIS_SIMPANAN', '$trx->TOTAL_BAYAR', '$keterangan', 'Tabungan', 'Setoran', 'D', '$jnsKas->ID_JNS_KAS', '$jnsKas->IDAKUN', 

														'mobile-app', '$anggota->NAMA', '$anggota->NO_IDENTITAS', '$anggota->ALAMAT', 0, 1, '$anggota->KODEPUSAT', '$anggota->KODECABANG', '$now')";



												$defaultdb->query($sql);



												$sql = "SELECT * FROM transaksi_simp WHERE TGL_TRX = '$tgl' AND ID_ANGGOTA = '$anggota->IDANGGOTA'";

												$cekTrxSimp = $defaultdb->query($sql);



												if ($cekTrxSimp->num_rows() > 0) {

													foreach ($cekTrxSimp->result() as $key) {

														$datatransaksi	=	array(

															'tgl' 			=> $key->TGL_TRX,

															'jumlah' 		=> $key->JUMLAH,

															'keterangan' 	=> $key->KETERANGAN == '' ? 'Penambahan Saldo (' . $key->NAMA_PENYETOR . ')' : $key->KETERANGAN,

															'user' 			=> $key->USERNAME,

															'kodecabang' 	=> $key->KODECABANG,

															'ket_dt' 		=> 'setor tunai'

														);

														$this->ModelVTransaksi->insertVtransaksi($key->ID_TRX_SIMP, $datatransaksi, 'ST', $key->ID_KAS, $key->ID_JENIS, 'SIMP');

													}

												}

											}



											$defaultdb 	= $this->load->database('default', TRUE);

											$sql = "UPDATE m_anggota_simp SET SALDO = SALDO + $trx->TOTAL_BAYAR WHERE IDJENIS_SIMP = '$trx->ID_JENIS_SIMPANAN' AND IDANGGOTA='$trx->KODE_ANGGOTA'";

											$updateSaldo = $defaultdb->query($sql);



											if ($updateSaldo) {

												$datainsert = array(

													'KODE_TRX' => $trx->KODE_TRX,

													'KODE_ANGGOTA' => $trx->KODE_ANGGOTA,

													'IDPEL' => $trx->IDPEL,

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

													'STATUS' => 2,

													'TRXID' => $trx->TRXID,

													'TOKEN' => $trx->TOKEN,

													'LOG' => $strRes,

													'NOTE' => $res['message']

												);

												$this->dbasemodel->insertTrx("m_trx", $datainsert);

											}

										} else {

											$sql = "SELECT * FROM m_anggota WHERE IDANGGOTA = '$trx->KODE_ANGGOTA'";

											$cekAnggota = $defaultdb->query($sql);



											if ($cekAnggota->num_rows() > 0) {



												$anggota = $cekAnggota->row();



												$sql = "SELECT * FROM jenis_kas WHERE KODECABANG = '$anggota->KODECABANG'";

												$cekJenisKas = $defaultdb->query($sql);

												$jnsKas = $cekJenisKas->row();
												
												$today = date('Y-m-d H:i:s');



												$keterangan = "Penambahan Saldo Sebesar Total Bayar Dari Pembayaran PPOB ($anggota->NAMA) Rp. $trx->TOTAL_BAYAR";



												$sql = "INSERT INTO transaksi_simp(TGL_TRX, ID_ANGGOTA, ID_JENIS, JUMLAH, KETERANGAN, KET_BAYAR, AKUN, DK, ID_KAS, ID_KASAKUN,ID_KASAKUN, 

														USERNAME, NAMA_PENYETOR, NO_IDENTITAS, ALAMAT, KOLEKTOR, STATUS, KODEPUSAT, KODECABANG, UPDATE_DATA) VALUES 

														('$tgl', '$anggota->IDANGGOTA', '$trx->ID_JENIS_SIMPANAN', '$trx->TOTAL_BAYAR', '$keterangan', 'Tabungan', 'Setoran', 'D', '$jnsKas->ID_JNS_KAS', '$jnsKas->IDAKUN',

														'mobile-app', '$anggota->NAMA', '$anggota->NO_IDENTITAS', '$anggota->ALAMAT', 0, 1, '$anggota->KODEPUSAT', '$anggota->KODECABANG', '$today')";



												$defaultdb->query($sql);



												$sql = "SELECT * FROM transaksi_simp WHERE TGL_TRX = '$tgl' AND ID_ANGGOTA = '$anggota->IDANGGOTA'";

												$cekTrxSimp = $defaultdb->query($sql);



												if ($cekTrxSimp->num_rows() > 0) {

													foreach ($cekTrxSimp->result() as $key) {

														$datatransaksi	=	array(

															'tgl' 			=> $key->TGL_TRX,

															'jumlah' 		=> $key->JUMLAH,

															'keterangan' 	=> $key->KETERANGAN == '' ? 'Penambahan Saldo (' . $key->NAMA_PENYETOR . ')' : $key->KETERANGAN,

															'user' 			=> $key->USERNAME,

															'kodecabang' 	=> $key->KODECABANG,

															'ket_dt' 		=> 'setor tunai'

														);

														$this->ModelVTransaksi->insertVtransaksi($key->ID_TRX_SIMP, $datatransaksi, 'ST', $key->ID_KAS, $key->ID_JENIS, 'SIMP');

													}

												}

											}



											$defaultdb 	= $this->load->database('default', TRUE);

											$sql = "INSERT INTO m_anggota_simp (IDANGGOTA, IDJENIS_SIMP, SALDO) VALUES ('$trx->KODE_ANGGOTA', '$trx->ID_JENIS_SIMPANAN', '$trx->TOTAL_BAYAR')";

											$insertSaldo = $defaultdb->query($sql);



											if ($insertSaldo) {

												$datainsert = array(

													'KODE_TRX' => $trx->KODE_TRX,

													'KODE_ANGGOTA' => $trx->KODE_ANGGOTA,

													'IDPEL' => $trx->IDPEL,

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

													'STATUS' => 2,

													'TRXID' => $trx->TRXID,

													'TOKEN' => $trx->TOKEN,

													'LOG' => $strRes,

													'NOTE' => $res['message']

												);

												$this->dbasemodel->insertTrx("m_trx", $datainsert);

											}

										}

									}

								} else if ($trx->INQUIRY === 'PLN') {

									$data = array(

										'inquiry' => 'PLN', // 'PLN' untuk pembelian PLN Prabayar, atau 'I' (i besar) untuk produk lainnya

										'code' => $trx->PRODUK, // kode produk

										'phone' => $trx->NOHP, // nohp pembeli

										'no_meter_pln' => $trx->IDPEL, // khusus untuk pembelian token PLN prabayar

										'pin' => $this->tripay_pin, // pin member

									);



									$url = $this->tripay_url . '/transaksi/pembelian';



									$ch = curl_init();

									curl_setopt($ch, CURLOPT_URL, $url);

									curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

									curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

									curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);

									curl_setopt($ch, CURLOPT_HTTPHEADER, $this->tripay_header);

									curl_setopt($ch, CURLOPT_POST, 1);

									curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

									$result = curl_exec($ch);



									if (curl_errno($ch)) {

										return 'Request Error:' . curl_error($ch);

									}



									$res = json_decode($result, true);



									$strRes = json_encode($result);



									if ($res['success'] === false) {



										$nomertrx = date("ymdHis");

										$tgl = date("Y-m-d H:i:s");



										// jika gagal, maka semua nilai transfer masukkan ke saldo member

										// meski gagal transaksi, uang yg sudah ditransfer jgn hilang, harus masuk ke saldo member



										$defaultdb 	= $this->load->database('default', TRUE);

										$sql = "SELECT * FROM m_anggota_simp WHERE IDJENIS_SIMP = '$trx->ID_JENIS_SIMPANAN' AND IDANGGOTA='$trx->KODE_ANGGOTA'";

										$cekSaldo = $defaultdb->query($sql);



										if ($cekSaldo->num_rows() > 0) {

											$sql = "SELECT * FROM m_anggota WHERE IDANGGOTA = '$trx->KODE_ANGGOTA'";

											$cekAnggota = $defaultdb->query($sql);



											if ($cekAnggota->num_rows() > 0) {



												$anggota = $cekAnggota->row();



												$sql = "SELECT * FROM jenis_kas WHERE KODECABANG = '$anggota->KODECABANG'";

												$cekJenisKas = $defaultdb->query($sql);

												$jnsKas = $cekJenisKas->row();



												$keterangan = "Penambahan Saldo Sebesar Total Bayar Dari Pembayaran PPOB ($anggota->NAMA) Rp. $trx->TOTAL_BAYAR";
												
												$now = date('Y-m-d H:i:s');



												$sql = "INSERT INTO transaksi_simp(TGL_TRX, ID_ANGGOTA, ID_JENIS, JUMLAH, KETERANGAN, KET_BAYAR, AKUN, DK, ID_KAS, ID_KASAKUN,

														USERNAME, NAMA_PENYETOR, NO_IDENTITAS, ALAMAT, KOLEKTOR, STATUS, KODEPUSAT, KODECABANG, UPDATE_DATA) VALUES 

														('$tgl', '$anggota->IDANGGOTA', '$trx->ID_JENIS_SIMPANAN', '$trx->TOTAL_BAYAR', '$keterangan', 'Tabungan', 'Setoran', 'D', '$jnsKas->ID_JNS_KAS', '$jnsKas->IDAKUN',

														'mobile-app', '$anggota->NAMA', '$anggota->NO_IDENTITAS', '$anggota->ALAMAT', 0, 1, '$anggota->KODEPUSAT', '$anggota->KODECABANG', '$now')";



												$defaultdb->query($sql);



												$sql = "SELECT * FROM transaksi_simp WHERE TGL_TRX = '$tgl' AND ID_ANGGOTA = '$anggota->IDANGGOTA'";

												$cekTrxSimp = $defaultdb->query($sql);



												if ($cekTrxSimp->num_rows() > 0) {

													foreach ($cekTrxSimp->result() as $key) {

														$datatransaksi	=	array(

															'tgl' 			=> $key->TGL_TRX,

															'jumlah' 		=> $key->JUMLAH,

															'keterangan' 	=> $key->KETERANGAN == '' ? 'Penambahan Saldo (' . $key->NAMA_PENYETOR . ')' : $key->KETERANGAN,

															'user' 			=> $key->USERNAME,

															'kodecabang' 	=> $key->KODECABANG,

															'ket_dt' 		=> 'setor tunai'

														);

														$this->ModelVTransaksi->insertVtransaksi($key->ID_TRX_SIMP, $datatransaksi, 'ST', $key->ID_KAS, $key->ID_JENIS, 'SIMP');

													}

												}

											}



											$defaultdb 	= $this->load->database('default', TRUE);

											$sql = "UPDATE m_anggota_simp SET SALDO = SALDO + $trx->TOTAL_BAYAR WHERE IDJENIS_SIMP = '$trx->ID_JENIS_SIMPANAN' AND IDANGGOTA='$trx->KODE_ANGGOTA'";

											$updateSaldo = $defaultdb->query($sql);



											if ($updateSaldo) {

												$datainsert = array(

													'KODE_TRX' => $trx->KODE_TRX,

													'KODE_ANGGOTA' => $trx->KODE_ANGGOTA,

													'IDPEL' => $trx->IDPEL,

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

													'STATUS' => 2,

													'TRXID' => $trx->TRXID,

													'TOKEN' => $trx->TOKEN,

													'LOG' => $strRes,

													'NOTE' => $res['message']

												);

												$this->dbasemodel->insertTrx("m_trx", $datainsert);

											}

										} else {

											$sql = "SELECT * FROM m_anggota WHERE IDANGGOTA = '$trx->KODE_ANGGOTA'";

											$cekAnggota = $defaultdb->query($sql);



											if ($cekAnggota->num_rows() > 0) {



												$anggota = $cekAnggota->row();

												$sql = "SELECT * FROM jenis_kas WHERE KODECABANG = '$anggota->KODECABANG'";

												$cekJenisKas = $defaultdb->query($sql);

												$jnsKas = $cekJenisKas->row();



												$keterangan = "Penambahan Saldo Sebesar Total Bayar Dari Pembayaran PPOB ($anggota->NAMA) Rp. $trx->TOTAL_BAYAR";
												
												$now = date('Y-m-d H:i:s');



												$sql = "INSERT INTO transaksi_simp(TGL_TRX, ID_ANGGOTA, ID_JENIS, JUMLAH, KETERANGAN, KET_BAYAR, AKUN, DK, ID_KAS, ID_KASAKUN,ID_KASAKUN, 

														USERNAME, NAMA_PENYETOR, NO_IDENTITAS, ALAMAT, KOLEKTOR, STATUS, KODEPUSAT, KODECABANG, UPDATE_DATA) VALUES 

														('$tgl', '$anggota->IDANGGOTA', '$trx->ID_JENIS_SIMPANAN', '$trx->TOTAL_BAYAR', '$keterangan', 'Tabungan', 'Setoran', 'D', '$jnsKas->ID_JNS_KAS', '$jnsKas->IDAKUN',

														'mobile-app', '$anggota->NAMA', '$anggota->NO_IDENTITAS', '$anggota->ALAMAT', 0, 1, '$anggota->KODEPUSAT', '$anggota->KODECABANG', '$now')";



												$defaultdb->query($sql);



												$sql = "SELECT * FROM transaksi_simp WHERE TGL_TRX = '$tgl' AND ID_ANGGOTA = '$anggota->IDANGGOTA'";

												$cekTrxSimp = $defaultdb->query($sql);



												if ($cekTrxSimp->num_rows() > 0) {

													foreach ($cekTrxSimp->result() as $key) {

														$datatransaksi	=	array(

															'tgl' 			=> $key->TGL_TRX,

															'jumlah' 		=> $key->JUMLAH,

															'keterangan' 	=> $key->KETERANGAN == '' ? 'Penambahan Saldo (' . $key->NAMA_PENYETOR . ')' : $key->KETERANGAN,

															'user' 			=> $key->USERNAME,

															'kodecabang' 	=> $key->KODECABANG,

															'ket_dt' 		=> 'setor tunai'

														);

														$this->ModelVTransaksi->insertVtransaksi($key->ID_TRX_SIMP, $datatransaksi, 'ST', $key->ID_KAS, $key->ID_JENIS, 'SIMP');

													}

												}

											}



											$defaultdb 	= $this->load->database('default', TRUE);

											$sql = "INSERT INTO m_anggota_simp (IDANGGOTA, IDJENIS_SIMP, SALDO) VALUES ('$trx->KODE_ANGGOTA', '$trx->ID_JENIS_SIMPANAN', '$trx->TOTAL_BAYAR')";

											$insertSaldo = $defaultdb->query($sql);



											if ($insertSaldo) {

												$datainsert = array(

													'KODE_TRX' => $trx->KODE_TRX,

													'KODE_ANGGOTA' => $trx->KODE_ANGGOTA,

													'IDPEL' => $trx->IDPEL,

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

													'STATUS' => 2,

													'TRXID' => $trx->TRXID,

													'TOKEN' => $trx->TOKEN,

													'LOG' => $strRes,

													'NOTE' => $res['message']

												);

												$this->dbasemodel->insertTrx("m_trx", $datainsert);

											}

										}

									}

								}

							} else if ($trx->KODE_TRX === 1 && $trx->ORDER_ID !== NULL) { // tripay pembayaran

								$url = $this->tripay_url . '/transaksi/pembayaran';



								$data = array(

									'order_id' => $trx->ORDER_ID, // Masukkan ID yang didapat setelah melakukan pengecekan pembayaran

									'pin' => $this->tripay_pin, // Masukkan PIN user (anda)

								);



								$ch = curl_init();

								curl_setopt($ch, CURLOPT_URL, $url);

								curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

								curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

								curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);

								curl_setopt($ch, CURLOPT_HTTPHEADER, $this->tripay_header);

								curl_setopt($ch, CURLOPT_POST, 1);

								curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

								$result = curl_exec($ch);



								if (curl_errno($ch)) {

									return 'Request Error:' . curl_error($ch);

								}



								$res = json_decode($result, true);



								$strRes = json_encode($result);



								if ($res['success'] === false) {



									$nomertrx = date("ymdHis");

									$tgl = date("Y-m-d H:i:s");



									// jika gagal, maka semua nilai transfer masukkan ke saldo member

									// meski gagal transaksi, uang yg sudah ditransfer jgn hilang, harus masuk ke saldo member



									$defaultdb 	= $this->load->database('default', TRUE);

									$sql = "SELECT * FROM m_anggota_simp WHERE IDJENIS_SIMP = '$trx->ID_JENIS_SIMPANAN' AND IDANGGOTA='$trx->KODE_ANGGOTA'";

									$cekSaldo = $defaultdb->query($sql);



									if ($cekSaldo->num_rows() > 0) {



										$sql = "SELECT * FROM m_anggota WHERE IDANGGOTA = '$trx->KODE_ANGGOTA'";

										$cekAnggota = $defaultdb->query($sql);



										if ($cekAnggota->num_rows() > 0) {



											$anggota = $cekAnggota->row();



											$sql = "SELECT * FROM jenis_kas WHERE KODECABANG = '$anggota->KODECABANG'";

											$cekJenisKas = $defaultdb->query($sql);

											$jnsKas = $cekJenisKas->row();



											$keterangan = "Penambahan Saldo Sebesar Total Bayar Dari Pembayaran PPOB ($anggota->NAMA) Rp. $trx->TOTAL_BAYAR";
											
											$now = date('Y-m-d H:i:s');



											$sql = "INSERT INTO transaksi_simp(TGL_TRX, ID_ANGGOTA, ID_JENIS, JUMLAH, KETERANGAN, KET_BAYAR, AKUN, DK, ID_KAS, ID_KASAKUN,ID_KASAKUN, 

													USERNAME, NAMA_PENYETOR, NO_IDENTITAS, ALAMAT, KOLEKTOR, STATUS, KODEPUSAT, KODECABANG, UPDATE_DATA) VALUES 

													('$tgl', '$anggota->IDANGGOTA', '$trx->ID_JENIS_SIMPANAN', '$trx->TOTAL_BAYAR', '$keterangan', 'Tabungan', 'Setoran', 'D', '$jnsKas->ID_JNS_KAS', '$jnsKas->IDAKUN',

													'mobile-app', '$anggota->NAMA', '$anggota->NO_IDENTITAS', '$anggota->ALAMAT', 0, 1, '$anggota->KODEPUSAT', '$anggota->KODECABANG', '$now')";



											$defaultdb->query($sql);



											$sql = "SELECT * FROM transaksi_simp WHERE TGL_TRX = '$tgl' AND ID_ANGGOTA = '$anggota->IDANGGOTA'";

											$cekTrxSimp = $defaultdb->query($sql);



											if ($cekTrxSimp->num_rows() > 0) {

												foreach ($cekTrxSimp->result() as $key) {

													$datatransaksi	=	array(

														'tgl' 			=> $key->TGL_TRX,

														'jumlah' 		=> $key->JUMLAH,

														'keterangan' 	=> $key->KETERANGAN == '' ? 'Penambahan Saldo (' . $key->NAMA_PENYETOR . ')' : $key->KETERANGAN,

														'user' 			=> $key->USERNAME,

														'kodecabang' 	=> $key->KODECABANG,

														'ket_dt' 		=> 'setor tunai'

													);

													$this->ModelVTransaksi->insertVtransaksi($key->ID_TRX_SIMP, $datatransaksi, 'ST', $key->ID_KAS, $key->ID_JENIS, 'SIMP');

												}

											}

										}



										$defaultdb 	= $this->load->database('default', TRUE);

										$sql = "UPDATE m_anggota_simp SET SALDO = SALDO + $trx->TOTAL_BAYAR WHERE IDJENIS_SIMP = '$trx->ID_JENIS_SIMPANAN' AND IDANGGOTA='$trx->KODE_ANGGOTA'";

										$updateSaldo = $defaultdb->query($sql);



										if ($updateSaldo) {

											$datainsert = array(

												'KODE_TRX' => $trx->KODE_TRX,

												'KODE_ANGGOTA' => $trx->KODE_ANGGOTA,

												'IDPEL' => $trx->IDPEL,

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

												'STATUS' => 2,

												'TRXID' => $trx->TRXID,

												'TOKEN' => $trx->TOKEN,

												'LOG' => $strRes,

												'NOTE' => $res['message']

											);

											$this->dbasemodel->insertTrx("m_trx", $datainsert);

										}

									} else {



										$sql = "SELECT * FROM m_anggota WHERE IDANGGOTA = '$trx->KODE_ANGGOTA'";

										$cekAnggota = $defaultdb->query($sql);



										if ($cekAnggota->num_rows() > 0) {



											$anggota = $cekAnggota->row();



											$sql = "SELECT * FROM jenis_kas WHERE KODECABANG = '$anggota->KODECABANG'";

											$cekJenisKas = $defaultdb->query($sql);

											$jnsKas = $cekJenisKas->row();



											$keterangan = "Penambahan Saldo Sebesar Total Bayar Dari Pembayaran PPOB ($anggota->NAMA) Rp. $trx->TOTAL_BAYAR";
											$now = date('Y-m-d H:i:s');



											$sql = "INSERT INTO transaksi_simp(TGL_TRX, ID_ANGGOTA, ID_JENIS, JUMLAH, KETERANGAN, KET_BAYAR, AKUN, DK, ID_KAS, ID_KASAKUN,

													USERNAME, NAMA_PENYETOR, NO_IDENTITAS, ALAMAT, KOLEKTOR, STATUS, KODEPUSAT, KODECABANG, UPDATE_DATA) VALUES 

													('$tgl', '$anggota->IDANGGOTA', '$trx->ID_JENIS_SIMPANAN', '$trx->TOTAL_BAYAR', '$keterangan', 'Tabungan', 'Setoran', 'D', '$jnsKas->ID_JNS_KAS', '$jnsKas->IDAKUN',

													'mobile-app', '$anggota->NAMA', '$anggota->NO_IDENTITAS', '$anggota->ALAMAT', 0, 1, '$anggota->KODEPUSAT', '$anggota->KODECABANG', '$now')";



											$defaultdb->query($sql);



											$sql = "SELECT * FROM transaksi_simp WHERE TGL_TRX = '$tgl' AND ID_ANGGOTA = '$anggota->IDANGGOTA'";

											$cekTrxSimp = $defaultdb->query($sql);



											if ($cekTrxSimp->num_rows() > 0) {

												foreach ($cekTrxSimp->result() as $key) {

													$datatransaksi	=	array(

														'tgl' 			=> $key->TGL_TRX,

														'jumlah' 		=> $key->JUMLAH,

														'keterangan' 	=> $key->KETERANGAN == '' ? 'Penambahan Saldo (' . $key->NAMA_PENYETOR . ')' : $key->KETERANGAN,

														'user' 			=> $key->USERNAME,

														'kodecabang' 	=> $key->KODECABANG,

														'ket_dt' 		=> 'setor tunai'

													);

													$this->ModelVTransaksi->insertVtransaksi($key->ID_TRX_SIMP, $datatransaksi, 'ST', $key->ID_KAS, $key->ID_JENIS, 'SIMP');

												}

											}

										}

										$defaultdb 	= $this->load->database('default', TRUE);

										$sql = "INSERT INTO m_anggota_simp (IDANGGOTA, IDJENIS_SIMP, SALDO) VALUES ('$trx->KODE_ANGGOTA', '$trx->ID_JENIS_SIMPANAN', '$trx->TOTAL_BAYAR')";

										$insertSaldo = $defaultdb->query($sql);



										if ($insertSaldo) {

											$datainsert = array(

												'KODE_TRX' => $trx->KODE_TRX,

												'KODE_ANGGOTA' => $trx->KODE_ANGGOTA,

												'IDPEL' => $trx->IDPEL,

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

												'STATUS' => 2,

												'TRXID' => $trx->TRXID,

												'TOKEN' => $trx->TOKEN,

												'LOG' => $strRes,

												'NOTE' => $res['message']

											);

											$this->dbasemodel->insertTrx("m_trx", $datainsert);

										}

									}

								}

							} else if ($trx->KODE_TRX === 2) { // isi saldo

								$defaultdb 	= $this->load->database('default', TRUE);

								$sql = "SELECT * FROM m_anggota_simp WHERE IDJENIS_SIMP = '$trx->ID_JENIS_SIMPANAN' AND IDANGGOTA='$trx->KODE_ANGGOTA'";

								$cekSaldo = $defaultdb->query($sql);



								if ($cekSaldo->num_rows() > 0) {

									$sql = "SELECT * FROM m_anggota WHERE IDANGGOTA = '$trx->KODE_ANGGOTA'";

									$cekAnggota = $defaultdb->query($sql);



									if ($cekAnggota->num_rows() > 0) {



										$anggota = $cekAnggota->row();



										$sql = "SELECT * FROM jenis_kas WHERE KODECABANG = '$anggota->KODECABANG'";

										$cekJenisKas = $defaultdb->query($sql);

										$jnsKas = $cekJenisKas->row();



										$tgl = date("Y-m-d H:i:s");



										// $keterangan = "Penambahan Saldo Sebesar Total Bayar Dari Pembayaran PPOB ($anggota->NAMA) Rp. $trx->TOTAL_BAYAR";

										$keterangan = "Setoran Melalui App ($anggota->NAMA), sebesar Rp. $trx->TOTAL_BAYAR";
										
										$now = date('Y-m-d H:i:s');



										$sql = "INSERT INTO transaksi_simp(TGL_TRX, ID_ANGGOTA, ID_JENIS, JUMLAH, KETERANGAN, KET_BAYAR, AKUN, DK, ID_KAS, ID_KASAKUN,

												USERNAME, NAMA_PENYETOR, NO_IDENTITAS, ALAMAT, KOLEKTOR, STATUS, KODEPUSAT, KODECABANG, UPDATE_DATA) VALUES 

												('$tgl', '$anggota->IDANGGOTA', '$trx->ID_JENIS_SIMPANAN', '$trx->TOTAL_BAYAR', '$keterangan', 'Tabungan', 'Setoran', 'D', '$jnsKas->ID_JNS_KAS', '$jnsKas->IDAKUN',

												'mobile-app', '$anggota->NAMA', '$anggota->NO_IDENTITAS', '$anggota->ALAMAT', 0, 1, '$anggota->KODEPUSAT', '$anggota->KODECABANG', '$now')";



										$defaultdb->query($sql);



										$sql = "SELECT * FROM transaksi_simp WHERE TGL_TRX = '$tgl' AND ID_ANGGOTA = '$anggota->IDANGGOTA'";

										$cekTrxSimp = $defaultdb->query($sql);



										if ($cekTrxSimp->num_rows() > 0) {

											foreach ($cekTrxSimp->result() as $key) {

												$datatransaksi	=	array(

													'tgl' 			=> $key->TGL_TRX,

													'jumlah' 		=> $key->JUMLAH,

													'keterangan' 	=> $key->KETERANGAN == '' ? 'Setoran Melalui App (' . $key->NAMA_PENYETOR . ')' : $key->KETERANGAN,

													'user' 			=> $key->USERNAME,

													'kodecabang' 	=> $key->KODECABANG,

													'ket_dt' 		=> 'setor tunai'

												);

												$this->ModelVTransaksi->insertVtransaksi($key->ID_TRX_SIMP, $datatransaksi, 'ST', $key->ID_KAS, $key->ID_JENIS, 'SIMP');

											}

										}

									}



									$defaultdb 	= $this->load->database('default', TRUE);

									$sql = "UPDATE m_anggota_simp SET SALDO = SALDO + $trx->TOTAL_BAYAR WHERE IDJENIS_SIMP = '$trx->ID_JENIS_SIMPANAN' AND IDANGGOTA='$trx->KODE_ANGGOTA'";

									$updateSaldo = $defaultdb->query($sql);



									if ($updateSaldo) {

										if ($updateSaldo) {

											$nomertrx = date("ymdHis");

											$tgl = date("Y-m-d H:i:s");



											$datainsert = array(

												'KODE_TRX' => $trx->KODE_TRX,

												'KODE_ANGGOTA' => $trx->KODE_ANGGOTA,

												'IDPEL' => $trx->IDPEL,

												'TGL' => $tgl,

												'NOTRX' => $nomertrx,

												'PAYMENT_VIA' => $trx->PAYMENT_VIA,

												'KODE_UNIK' => $trx->KODE_UNIK,

												'TOTAL_BAYAR' => $trx->TOTAL_BAYAR,

												'STATUS_BAYAR' => 1,

												'PROSES' => 1,

												'MSG' => "Saldo telah bertambah senilai $trx->TOTAL_BAYAR",

												"IDH2H" => 0,

												'STATUS' => 1,

											);

											$this->dbasemodel->insertTrx("m_trx", $datainsert);

										}

									}

								} else {

									$sql = "SELECT * FROM m_anggota WHERE IDANGGOTA = '$trx->KODE_ANGGOTA'";

									$cekAnggota = $defaultdb->query($sql);



									if ($cekAnggota->num_rows() > 0) {



										$anggota = $cekAnggota->row();



										$sql = "SELECT * FROM jenis_kas WHERE KODECABANG = '$anggota->KODECABANG'";

										$cekJenisKas = $defaultdb->query($sql);

										$jnsKas = $cekJenisKas->row();



										$tgl = date("Y-m-d H:i:s");



										// $keterangan = "Penambahan Saldo Sebesar Total Bayar Dari Pembayaran PPOB ($anggota->NAMA) Rp. $trx->TOTAL_BAYAR";

										$keterangan = "Setoran Melalui App ($anggota->NAMA), sebesar Rp. $trx->TOTAL_BAYAR";
										
										$now = date('Y-m-d H:i:s');



										$sql = "INSERT INTO transaksi_simp(TGL_TRX, ID_ANGGOTA, ID_JENIS, JUMLAH, KETERANGAN, KET_BAYAR, AKUN, DK, ID_KAS, ID_KASAKUN,

												USERNAME, NAMA_PENYETOR, NO_IDENTITAS, ALAMAT, KOLEKTOR, STATUS, KODEPUSAT, KODECABANG, UPDATE_DATA) VALUES 

												('$tgl', '$anggota->IDANGGOTA', '$trx->ID_JENIS_SIMPANAN', '$trx->TOTAL_BAYAR', '$keterangan', 'Tabungan', 'Setoran', 'D', '$jnsKas->ID_JNS_KAS', '$jnsKas->IDAKUN',

												'mobile-app', '$anggota->NAMA', '$anggota->NO_IDENTITAS', '$anggota->ALAMAT', 0, 1, '$anggota->KODEPUSAT', '$anggota->KODECABANG', '$now')";



										$defaultdb->query($sql);



										$sql = "SELECT * FROM transaksi_simp WHERE TGL_TRX = '$tgl' AND ID_ANGGOTA = '$anggota->IDANGGOTA'";

										$cekTrxSimp = $defaultdb->query($sql);



										if ($cekTrxSimp->num_rows() > 0) {

											foreach ($cekTrxSimp->result() as $key) {

												$datatransaksi	=	array(

													'tgl' 			=> $key->TGL_TRX,

													'jumlah' 		=> $key->JUMLAH,

													'keterangan' 	=> $key->KETERANGAN == '' ? 'Setoran Melalui App (' . $key->NAMA_PENYETOR . ')' : $key->KETERANGAN,

													'user' 			=> $key->USERNAME,

													'kodecabang' 	=> $key->KODECABANG,

													'ket_dt' 		=> 'setor tunai'

												);

												$this->ModelVTransaksi->insertVtransaksi($key->ID_TRX_SIMP, $datatransaksi, 'ST', $key->ID_KAS, $key->ID_JENIS, 'SIMP');

											}

										}

									}



									$defaultdb 	= $this->load->database('default', TRUE);

									$sql = "INSERT INTO m_anggota_simp (IDANGGOTA, IDJENIS_SIMP, SALDO) VALUES ('$trx->KODE_ANGGOTA', '$trx->ID_JENIS_SIMPANAN', '$trx->TOTAL_BAYAR')";

									$insertSaldo = $defaultdb->query($sql);



									if ($insertSaldo) {

										$nomertrx = date("ymdHis");

										$tgl = date("Y-m-d H:i:s");



										$datainsert = array(

											'KODE_TRX' => $trx->KODE_TRX,

											'KODE_ANGGOTA' => $trx->KODE_ANGGOTA,

											'IDPEL' => $trx->IDPEL,

											'TGL' => $tgl,

											'NOTRX' => $nomertrx,

											'PAYMENT_VIA' => $trx->PAYMENT_VIA,

											'KODE_UNIK' => $trx->KODE_UNIK,

											'TOTAL_BAYAR' => $trx->TOTAL_BAYAR,

											'STATUS_BAYAR' => 1,

											'PROSES' => 1,

											'MSG' => "Saldo telah bertambah senilai $trx->TOTAL_BAYAR",

											"IDH2H" => 0,

											'STATUS' => 1,

										);

										$this->dbasemodel->insertTrx("m_trx", $datainsert);

									}

								}

							} else if ($trx->KODE_TRX === 3) { // isi simpanan

								$defaultdb 	= $this->load->database('default', TRUE);

								$sql = "SELECT * FROM m_anggota_simp WHERE IDJENIS_SIMP = '$trx->ID_JENIS_SIMPANAN' AND IDANGGOTA='$trx->KODE_ANGGOTA'";

								$cekSaldo = $defaultdb->query($sql);



								if ($cekSaldo->num_rows() > 0) {

									$sql = "SELECT * FROM m_anggota WHERE IDANGGOTA = '$trx->KODE_ANGGOTA'";

									$cekAnggota = $defaultdb->query($sql);



									if ($cekAnggota->num_rows() > 0) {



										$anggota = $cekAnggota->row();

										

										$tgl = date("Y-m-d H:i:s");



										$sql = "SELECT * FROM jns_simpan WHERE IDAKUN = '$trx->ID_JENIS_SIMPANAN'";

										$cekSimpanan = $defaultdb->query($sql);



										if ($cekSimpanan->num_rows() > 0) {



											$sql = "SELECT * FROM jenis_kas WHERE KODECABANG = '$anggota->KODECABANG'";

											$cekJenisKas = $defaultdb->query($sql);

											$jnsKas = $cekJenisKas->row();





											$simpanan = $cekSimpanan->row();

											// $keterangan = "Penambahan Saldo Sebesar Total Bayar Dari Pembayaran PPOB ($anggota->NAMA) Rp. $trx->TOTAL_BAYAR";

											$keterangan = "Setoran Simpanan $simpanan->JNS_SIMP Melalui App ($anggota->NAMA), sebesar Rp. $trx->TOTAL_BAYAR";
											
											$now = date('Y-m-d H:i:s');



											$sql = "INSERT INTO transaksi_simp(TGL_TRX, ID_ANGGOTA, ID_JENIS, JUMLAH, KETERANGAN, KET_BAYAR, AKUN, DK, ID_KAS, ID_KASAKUN,

													USERNAME, NAMA_PENYETOR, NO_IDENTITAS, ALAMAT, KOLEKTOR, STATUS, KODEPUSAT, KODECABANG, UPDATE_DATA) VALUES 

													('$tgl', '$anggota->IDANGGOTA', '$trx->ID_JENIS_SIMPANAN', '$trx->TOTAL_BAYAR', '$keterangan', 'Tabungan', 'Setoran', 'D', $jnsKas->ID_JNS_KAS, '$jnsKas->IDAKUN',

													'mobile-app', '$anggota->NAMA', '$anggota->NO_IDENTITAS', '$anggota->ALAMAT', 0, 1, '$anggota->KODEPUSAT', '$anggota->KODECABANG', '$now')";



											$defaultdb->query($sql);



											$defaultdb 	= $this->load->database('default', TRUE);



											$saldo = $cekSaldo->row();



											$sql = "UPDATE m_anggota_simp SET SALDO = SALDO + $trx->TOTAL_BAYAR WHERE IDJENIS_SIMP = '$trx->ID_JENIS_SIMPANAN' AND IDANGGOTA='$trx->KODE_ANGGOTA'";

											$updateSaldo = $defaultdb->query($sql);



											if ($updateSaldo) {

												$nomertrx = date("ymdHis");

												$tgl = date("Y-m-d H:i:s");



												$datainsert = array(

													'KODE_TRX' => $trx->KODE_TRX,

													'KODE_ANGGOTA' => $trx->KODE_ANGGOTA,

													'IDPEL' => $trx->IDPEL,

													'TGL' => $tgl,

													'NOTRX' => $nomertrx,

													'PAYMENT_VIA' => $trx->PAYMENT_VIA,

													'KODE_UNIK' => $trx->KODE_UNIK,

													'TOTAL_BAYAR' => $trx->TOTAL_BAYAR,

													'STATUS_BAYAR' => 1,

													'PROSES' => 1,

													'MSG' => "Simpanan  $simpanan->JNS_SIMP telah bertambah senilai $trx->TOTAL_BAYAR",

													"IDH2H" => 0,

													'STATUS' => 1,

												);

												$this->dbasemodel->insertTrx("m_trx", $datainsert);



												$sql = "SELECT * FROM transaksi_simp WHERE TGL_TRX = '$tgl' AND ID_ANGGOTA = '$anggota->IDANGGOTA'";

												$cekTrxSimp = $defaultdb->query($sql);



												if ($cekTrxSimp->num_rows() > 0) {

													foreach ($cekTrxSimp->result() as $key) {

														$datatransaksi	=	array(

															'tgl' 			=> $key->TGL_TRX,

															'jumlah' 		=> $key->JUMLAH,

															'keterangan' 	=> $key->KETERANGAN == '' ? 'Setoran tunai (' . $key->NAMA_PENYETOR . ')' : $key->KETERANGAN,

															'user' 			=> $key->USERNAME,

															'kodecabang' 	=> $key->KODECABANG,

															'ket_dt' 		=> 'setoran tunai'

														);

														$this->ModelVTransaksi->insertVtransaksi($key->ID_TRX_SIMP, $datatransaksi, 'ST', $key->ID_KAS, $key->ID_JENIS, 'SIMP');

													}

												}

											}

										}

									}

								} else {

									$sql = "SELECT * FROM m_anggota WHERE IDANGGOTA = '$trx->KODE_ANGGOTA'";

									$cekAnggota = $defaultdb->query($sql);



									if ($cekAnggota->num_rows() > 0) {



										$anggota = $cekAnggota->row();										



										$tgl = date("Y-m-d H:i:s");



										$sql = "SELECT * FROM jns_simpan WHERE IDAKUN = '$trx->ID_JENIS_SIMPANAN'";

										$cekSimpanan = $defaultdb->query($sql);



										if ($cekSimpanan->num_rows() > 0) {



											$sql = "SELECT * FROM jenis_kas WHERE KODECABANG = '$anggota->KODECABANG'";

											$cekJenisKas = $defaultdb->query($sql);

											$jnsKas = $cekJenisKas->row();



											$simpanan = $cekSimpanan->row();

											// $keterangan = "Penambahan Saldo Sebesar Total Bayar Dari Pembayaran PPOB ($anggota->NAMA) Rp. $trx->TOTAL_BAYAR";

											$keterangan = "Setoran Simpanan $simpanan->JNS_SIMP Melalui App ($anggota->NAMA), sebesar Rp. $trx->TOTAL_BAYAR";
											
											$now = date('Y-m-d H:i:s');



											$sql = "INSERT INTO transaksi_simp(TGL_TRX, ID_ANGGOTA, ID_JENIS, JUMLAH, KETERANGAN, KET_BAYAR, AKUN, DK, ID_KAS, ID_KASAKUN,

													USERNAME, NAMA_PENYETOR, NO_IDENTITAS, ALAMAT, KOLEKTOR, STATUS, KODEPUSAT, KODECABANG, UPDATE_DATA) VALUES 

													('$tgl', '$anggota->IDANGGOTA', '$trx->ID_JENIS_SIMPANAN', '$trx->TOTAL_BAYAR', '$keterangan', 'Tabungan', 'Setoran', 'D', '$jnsKas->ID_JNS_KAS', '$jnsKas->IDAKUN',

													'mobile-app', '$anggota->NAMA', '$anggota->NO_IDENTITAS', '$anggota->ALAMAT', 0, 1, '$anggota->KODEPUSAT', '$anggota->KODECABANG', '$now')";



											$defaultdb->query($sql);



											$sql = "SELECT * FROM transaksi_simp WHERE TGL_TRX = '$tgl' AND ID_ANGGOTA = '$anggota->IDANGGOTA'";

											$cekTrxSimp = $defaultdb->query($sql);



											if ($cekTrxSimp->num_rows() > 0) {

												foreach ($cekTrxSimp->result() as $key) {

													$datatransaksi	=	array(

														'tgl' 			=> $key->TGL_TRX,

														'jumlah' 		=> $key->JUMLAH,

														'keterangan' 	=> $key->KETERANGAN == '' ? 'Penambahan Saldo (' . $key->NAMA_PENYETOR . ')' : $key->KETERANGAN,

														'user' 			=> $key->USERNAME,

														'kodecabang' 	=> $key->KODECABANG,

														'ket_dt' 		=> 'setor tunai'

													);

													$this->ModelVTransaksi->insertVtransaksi($key->ID_TRX_SIMP, $datatransaksi, 'ST', $key->ID_KAS, $key->ID_JENIS, 'SIMP');

												}

											}



											$defaultdb 	= $this->load->database('default', TRUE);

											$sql = "INSERT INTO m_anggota_simp (IDANGGOTA, IDJENIS_SIMP, SALDO) VALUES ('$trx->KODE_ANGGOTA', '$trx->ID_JENIS_SIMPANAN', '$trx->TOTAL_BAYAR')";

											$insertSaldo = $defaultdb->query($sql);



											if ($insertSaldo) {

												$nomertrx = date("ymdHis");

												$tgl = date("Y-m-d H:i:s");



												$datainsert = array(

													'KODE_TRX' => $trx->KODE_TRX,

													'KODE_ANGGOTA' => $trx->KODE_ANGGOTA,

													'IDPEL' => $trx->IDPEL,

													'TGL' => $tgl,

													'NOTRX' => $nomertrx,

													'PAYMENT_VIA' => $trx->PAYMENT_VIA,

													'KODE_UNIK' => $trx->KODE_UNIK,

													'TOTAL_BAYAR' => $trx->TOTAL_BAYAR,

													'STATUS_BAYAR' => 1,

													'PROSES' => 1,

													'MSG' => "Simpanan  $simpanan->JNS_SIMP telah bertambah senilai $trx->TOTAL_BAYAR",

													"IDH2H" => 0,

													'STATUS' => 1,

												);

												$this->dbasemodel->insertTrx("m_trx", $datainsert);

											}

										}

									}

								}

							} else if ($trx->KODE_TRX === 4) { // bayar angsuran

								// $sql = "SELECT * FROM m_trx WHERE ID_PINJAM = '$trx->ID_PINJAM' AND ANGSURAN_KE_PINJAM = '$trx->ANGSURAN_KE_PINJAM' AND PAYMENT_VIA = 'saldo'";

								// $checkTrx = $otherdb->query($sql);

								$defaultdb 	= $this->load->database('default', TRUE);



								$sql = "SELECT * FROM m_anggota WHERE IDANGGOTA = '$trx->KODE_ANGGOTA'";

								$cekAnggota = $defaultdb->query($sql);



								if ($cekAnggota->num_rows() > 0) {



									$anggota = $cekAnggota->row();



									$tgl = date("Y-m-d H:i:s");

									$nomertrx = date("ymdHis");



									$sql = "SELECT * FROM tbl_pinjaman_h WHERE IDPINJM_H = '$trx->ID_PINJAM'";

									$cekPinjaman = $defaultdb->query($sql);



									if ($trx->BAYAR_SALDO_PINJAM) {

										$sql = "SELECT * FROM m_anggota_simp WHERE IDJENIS_SIMP = '$trx->ID_JENIS_SIMPANAN' AND IDANGGOTA='$trx->KODE_ANGGOTA'";

										$cekSaldo = $defaultdb->query($sql);



										if ($cekSaldo->num_rows() > 0) {

											$dataSaldo = $cekSaldo->row();



											if ($dataSaldo->SALDO >= $trx->BAYAR_SALDO_PINJAM) {

												$sql = "UPDATE m_anggota_simp SET SALDO = SALDO - $trx->BAYAR_SALDO_PINJAM WHERE IDJENIS_SIMP = '$trx->ID_JENIS_SIMPANAN' AND IDANGGOTA='$trx->KODE_ANGGOTA'";

												$defaultdb->query($sql);



												$datainsert = array(

													'KODE_TRX' => $trx->KODE_TRX,

													'KODE_ANGGOTA' => $trx->KODE_ANGGOTA,

													'TGL' => $tgl,

													'NOTRX' => $nomertrx,

													'PAYMENT_VIA' => $trx->PAYMENT_VIA,

													'KODE_UNIK' => $trx->KODE_UNIK,

													'TOTAL_BAYAR' => $trx->TOTAL_BAYAR,

													'INQUIRY' => $trx->INQUIRY,

													'ORDER_ID' => $trx->ORDER_ID,

													'STATUS_BAYAR' => 1,

													'PROSES' => 1,

													'MSG' => "Saldo terpotong sebesar $trx->BAYAR_SALDO_PINJAM untuk pembayaran angsuran",

													'STATUS' => 1,

													'ID_PINJAM' => $trx->ID_PINJAM,

													'ANGSURAN_KE_PINJAM' => $trx->ANGSURAN_KE_PINJAM,

													'BAYAR_SALDO_PINJAM' => $trx->BAYAR_SALDO_PINJAM,

													'BASIL_BAYAR_PINJAM' => $trx->BASIL_BAYAR_PINJAM,

													'POKOK_BAYAR_PINJAM' => $trx->POKOK_BAYAR_PINJAM,

													'JUMLAH_BAYAR_PINJAM' => $trx->JUMLAH_BAYAR_PINJAM,

													'BIAYA_RESET_PINJAM' => $trx->BIAYA_RESET_PINJAM,

													'BIAYA_KOLEKTOR_PINJAM' => $trx->BIAYA_KOLEKTOR_PINJAM,

													'JENIS_TRANS_PINJAM' => $trx->JENIS_TRANS_PINJAM,

													'KETERANGAN_BAYAR_PINJAM' => $trx->KETERANGAN_BAYAR_PINJAM,

													'IDH2H' => $trx->IDH2H

												);

												$this->dbasemodel->insertTrx("m_trx", $datainsert);



												$keterangan = "Pembayaran Angsuran Menggunakan Saldo Mudharabah Melalui App ($anggota->NAMA), sebesar Rp. $trx->BAYAR_SALDO_PINJAM";



												$sql = "SELECT * FROM jenis_kas WHERE KODECABANG = '$anggota->KODECABANG'";

												$cekJenisKas = $defaultdb->query($sql);

												$jnsKas = $cekJenisKas->row();
												
												$now = date('Y-m-d H:i:s');





												$sql = "INSERT INTO transaksi_simp(TGL_TRX, ID_ANGGOTA, ID_JENIS, JUMLAH, KETERANGAN, KET_BAYAR, AKUN, DK, ID_KAS, ID_KASAKUN,

														USERNAME, NAMA_PENYETOR, NO_IDENTITAS, ALAMAT, KOLEKTOR, STATUS, KODEPUSAT, KODECABANG, UPDATE_DATA) VALUES 

														('$tgl', '$anggota->IDANGGOTA', '$trx->ID_JENIS_SIMPANAN', '$trx->BAYAR_SALDO_PINJAM', '$keterangan', 'Tabungan', 'Penarikan', 'K', '$jnsKas->ID_JNS_KAS','$jnsKas->IDAKUN',

														'mobile-app', '$anggota->NAMA', '$anggota->NO_IDENTITAS', '$anggota->ALAMAT', 0, 1, '$anggota->KODEPUSAT', '$anggota->KODECABANG', '$now')";



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

											} else {

												$sql = "UPDATE m_anggota_simp SET SALDO = SALDO + $trx->TOTAL_BAYAR WHERE IDJENIS_SIMP = '$trx->ID_JENIS_SIMPANAN' AND IDANGGOTA='$trx->KODE_ANGGOTA'";

												$defaultdb->query($sql);



												$datainsert = array(

													'KODE_TRX' => $trx->KODE_TRX,

													'KODE_ANGGOTA' => $trx->KODE_ANGGOTA,

													'TGL' => $tgl,

													'NOTRX' => $nomertrx,

													'PAYMENT_VIA' => $trx->PAYMENT_VIA,

													'KODE_UNIK' => $trx->KODE_UNIK,

													'TOTAL_BAYAR' => $trx->TOTAL_BAYAR,

													'INQUIRY' => $trx->INQUIRY,

													'ORDER_ID' => $trx->ORDER_ID,

													'STATUS_BAYAR' => 1,

													'PROSES' => 1,

													'MSG' => 'Penambahan Nilai Transfer ke dalam saldo Anggota karena saldo Anda tidak mencukupi untuk bayar angsuran',

													'STATUS' => 2,

													'ID_PINJAM' => $trx->ID_PINJAM,

													'ANGSURAN_KE_PINJAM' => $trx->ANGSURAN_KE_PINJAM,

													'BAYAR_SALDO_PINJAM' => $trx->BAYAR_SALDO_PINJAM,

													'BASIL_BAYAR_PINJAM' => $trx->BASIL_BAYAR_PINJAM,

													'POKOK_BAYAR_PINJAM' => $trx->POKOK_BAYAR_PINJAM,

													'JUMLAH_BAYAR_PINJAM' => $trx->JUMLAH_BAYAR_PINJAM,

													'BIAYA_RESET_PINJAM' => $trx->BIAYA_RESET_PINJAM,

													'BIAYA_KOLEKTOR_PINJAM' => $trx->BIAYA_KOLEKTOR_PINJAM,

													'JENIS_TRANS_PINJAM' => $trx->JENIS_TRANS_PINJAM,

													'KETERANGAN_BAYAR_PINJAM' => $trx->KETERANGAN_BAYAR_PINJAM,

													'IDH2H' => $trx->IDH2H

												);

												$this->dbasemodel->insertTrx("m_trx", $datainsert);



												$sql = "SELECT * FROM jenis_kas WHERE KODECABANG = '$anggota->KODECABANG'";

												$cekJenisKas = $defaultdb->query($sql);

												$jnsKas = $cekJenisKas->row();



												$keterangan = "Penambahan Saldo Dari Transfer Angsuran Karena Saldo Tidak Mencukupi ($anggota->NAMA) Rp. $trx->TOTAL_BAYAR";
												
												$now = date('Y-m-d H:i:s');



												$sql = "INSERT INTO transaksi_simp(TGL_TRX, ID_ANGGOTA, ID_JENIS, JUMLAH, KETERANGAN, KET_BAYAR, AKUN, DK, ID_KAS, ID_KASAKUN,

													USERNAME, NAMA_PENYETOR, NO_IDENTITAS, ALAMAT, KOLEKTOR, STATUS, KODEPUSAT, KODECABANG, UPDATE_DATA) VALUES 

													('$tgl', '$anggota->IDANGGOTA', '$trx->ID_JENIS_SIMPANAN', '$trx->TOTAL_BAYAR', '$keterangan', 'Tabungan', 'Setoran', 'D', '$jnsKas->ID_JNS_KAS', '$jnsKas->IDAKUN',

													'mobile-app', '$anggota->NAMA', '$anggota->NO_IDENTITAS', '$anggota->ALAMAT', 0, 1, '$anggota->KODEPUSAT', '$anggota->KODECABANG', '$now')";



												$defaultdb->query($sql);



												$sql = "SELECT * FROM transaksi_simp WHERE TGL_TRX = '$tgl' AND ID_ANGGOTA = '$anggota->IDANGGOTA'";

												$cekTrxSimp = $defaultdb->query($sql);



												if ($cekTrxSimp->num_rows() > 0) {

													foreach ($cekTrxSimp->result() as $key) {

														$datatransaksi	=	array(

															'tgl' 			=> $key->TGL_TRX,

															'jumlah' 		=> $key->JUMLAH,

															'keterangan' 	=> $key->KETERANGAN == '' ? 'Penambahan Saldo (' . $key->NAMA_PENYETOR . ')' : $key->KETERANGAN,

															'user' 			=> $key->USERNAME,

															'kodecabang' 	=> $key->KODECABANG,

															'ket_dt' 		=> 'setor tunai'

														);

														$this->ModelVTransaksi->insertVtransaksi($key->ID_TRX_SIMP, $datatransaksi, 'ST', $key->ID_KAS, $key->ID_JENIS, 'SIMP');

													}

												}



												return;

											}

										} else {

											$sql = "UPDATE m_anggota_simp SET SALDO = SALDO + $trx->TOTAL_BAYAR WHERE IDJENIS_SIMP = '$trx->ID_JENIS_SIMPANAN' AND IDANGGOTA='$trx->KODE_ANGGOTA'";

											$defaultdb->query($sql);



											$datainsert = array(

												'KODE_TRX' => $trx->KODE_TRX,

												'KODE_ANGGOTA' => $trx->KODE_ANGGOTA,

												'TGL' => $tgl,

												'NOTRX' => $nomertrx,

												'PAYMENT_VIA' => $trx->PAYMENT_VIA,

												'KODE_UNIK' => $trx->KODE_UNIK,

												'TOTAL_BAYAR' => $trx->TOTAL_BAYAR,

												'INQUIRY' => $trx->INQUIRY,

												'ORDER_ID' => $trx->ORDER_ID,

												'STATUS_BAYAR' => 1,

												'PROSES' => 1,

												'MSG' => 'Penambahan Nilai Transfer ke dalam saldo Anggota karena saldo Anda tidak mencukupi untuk bayar angsuran',

												'STATUS' => 2,

												'ID_PINJAM' => $trx->ID_PINJAM,

												'ANGSURAN_KE_PINJAM' => $trx->ANGSURAN_KE_PINJAM,

												'BAYAR_SALDO_PINJAM' => $trx->BAYAR_SALDO_PINJAM,

												'BASIL_BAYAR_PINJAM' => $trx->BASIL_BAYAR_PINJAM,

												'POKOK_BAYAR_PINJAM' => $trx->POKOK_BAYAR_PINJAM,

												'JUMLAH_BAYAR_PINJAM' => $trx->JUMLAH_BAYAR_PINJAM,

												'BIAYA_RESET_PINJAM' => $trx->BIAYA_RESET_PINJAM,

												'BIAYA_KOLEKTOR_PINJAM' => $trx->BIAYA_KOLEKTOR_PINJAM,

												'JENIS_TRANS_PINJAM' => $trx->JENIS_TRANS_PINJAM,

												'KETERANGAN_BAYAR_PINJAM' => $trx->KETERANGAN_BAYAR_PINJAM,

												'IDH2H' => $trx->IDH2H

											);

											$this->dbasemodel->insertTrx("m_trx", $datainsert);



											$keterangan = "Penambahan Saldo Dari Transfer Angsuran Karena Saldo Tidak Mencukupi ($anggota->NAMA) Rp. $trx->TOTAL_BAYAR";



											$sql = "SELECT * FROM jenis_kas WHERE KODECABANG = '$anggota->KODECABANG'";

											$cekJenisKas = $defaultdb->query($sql);

											$jnsKas = $cekJenisKas->row();
											$now = date('Y-m-d H:i:s');



											$sql = "INSERT INTO transaksi_simp(TGL_TRX, ID_ANGGOTA, ID_JENIS, JUMLAH, KETERANGAN, KET_BAYAR, AKUN, DK, ID_KAS, ID_KASAKUN,

													USERNAME, NAMA_PENYETOR, NO_IDENTITAS, ALAMAT, KOLEKTOR, STATUS, KODEPUSAT, KODECABANG, UPDATE_DATA) VALUES 

													('$tgl', '$anggota->IDANGGOTA', '$trx->ID_JENIS_SIMPANAN', '$trx->TOTAL_BAYAR', '$keterangan', 'Tabungan', 'Setoran', 'D', '$jnsKas->ID_JNS_KAS', '$jnsKas->IDAKUN',

													'mobile-app', '$anggota->NAMA', '$anggota->NO_IDENTITAS', '$anggota->ALAMAT', 0, 1, '$anggota->KODEPUSAT', '$anggota->KODECABANG', '$now')";



											$defaultdb->query($sql);



											$sql = "SELECT * FROM transaksi_simp WHERE TGL_TRX = '$tgl' AND ID_ANGGOTA = '$anggota->IDANGGOTA'";

											$cekTrxSimp = $defaultdb->query($sql);



											if ($cekTrxSimp->num_rows() > 0) {

												foreach ($cekTrxSimp->result() as $key) {

													$datatransaksi	=	array(

														'tgl' 			=> $key->TGL_TRX,

														'jumlah' 		=> $key->JUMLAH,

														'keterangan' 	=> $key->KETERANGAN == '' ? 'Penambahan Saldo (' . $key->NAMA_PENYETOR . ')' : $key->KETERANGAN,

														'user' 			=> $key->USERNAME,

														'kodecabang' 	=> $key->KODECABANG,

														'ket_dt' 		=> 'setor tunai'

													);

													$this->ModelVTransaksi->insertVtransaksi($key->ID_TRX_SIMP, $datatransaksi, 'ST', $key->ID_KAS, $key->ID_JENIS, 'SIMP');

												}

											}



											return;

										}

									}



									if ($cekPinjaman->num_rows() > 0) {

										$pinjaman = $cekPinjaman->row();



										$sisa_pinjaman = $pinjaman->PINJ_SISA - $trx->JUMLAH_BAYAR_PINJAM;



										$sql = "SELECT * FROM jenis_kas WHERE KODECABANG = '$anggota->KODECABANG'";

										$cekJenisKas = $defaultdb->query($sql);

										$jnsKas = $cekJenisKas->row();



										$sql = "INSERT INTO tbl_pinjaman_d(TGL_BAYAR, IDPINJAM, ANGSURAN_KE, BAYAR_SALDO, BASILBAYAR, JUMLAH_BAYAR, BIAYA_RESET, 

												BIAYA_KOLEKTOR, KET_BAYAR, DK, KAS_ID, JENIS_TRANS, USERNAME, STATUS) VALUES ('$tgl', '$trx->ID_PINJAM', '$trx->ANGSURAN_KE_PINJAM', '$trx->BAYAR_SALDO_PINJAM', 

												'$trx->BASIL_BAYAR_PINJAM', '$trx->JUMLAH_BAYAR_PINJAM', '$trx->BIAYA_RESET_PINJAM', '$trx->BIAYA_KOLEKTOR_PINJAM',

												'$trx->KETERANGAN_BAYAR_PINJAM', 'D', '$jnsKas->ID_JNS_KAS', '$trx->JENIS_TRANS_PINJAM', 'mobile-app', 1)";

										$defaultdb->query($sql);



										if ($trx->KETERANGAN_BAYAR_PINJAM === 'Angsuran') {

											$lunas = 'Belum';

										} else {

											$lunas = 'Lunas';

										}



										$sql = "UPDATE tbl_pinjaman_h SET LUNAS = '$lunas', PINJ_DIBAYAR = PINJ_DIBAYAR + '$trx->JUMLAH_BAYAR_PINJAM', 

												PINJ_SISA = '$sisa_pinjaman', PINJ_POKOK_DIBAYAR = PINJ_POKOK_DIBAYAR + '$trx->POKOK_BAYAR_PINJAM', PINJ_POKOK_SISA = PINJ_POKOK_SISA - '$trx->POKOK_BAYAR_PINJAM', 

												PINJ_BASIL_BAYAR = PINJ_BASIL_BAYAR + '$trx->BASIL_BAYAR_PINJAM' WHERE IDPINJM_H = '$trx->ID_PINJAM'";



										$defaultdb->query($sql);



										if ($lunas === 'Belum') {

											$isCredit = 1;

										} else {

											$isCredit = 0;

										}



										if ($isCredit === 0) {

											$sql = "UPDATE m_anggota SET ISCREDIT = 0, PINJ_POKOK = 0, PINJ_TOTAL = 0, PINJ_DIBAYAR = 0, 

											PINJ_SISA = 0, PINJ_POKOK_DIBAYAR = 0, PINJ_POKOK_SISA = 0, PINJ_RP_ANGSURAN = 0, PINJ_BASIL_DASAR = 0, 

											PINJ_BASIL_TOTAL = 0, PINJ_BASIL_BAYAR = 0 WHERE IDANGGOTA = '$trx->KODE_ANGGOTA'";

										} else {

											$pinjaman_pokok_sisa_baru = $pinjaman->PINJ_POKOK_SISA - $trx->POKOK_BAYAR_PINJAM;

											$sql = "UPDATE m_anggota SET PINJ_DIBAYAR = PINJ_DIBAYAR + '$trx->JUMLAH_BAYAR_PINJAM', 

											PINJ_SISA = $sisa_pinjaman, PINJ_POKOK_DIBAYAR = PINJ_POKOK_DIBAYAR + '$trx->POKOK_BAYAR_PINJAM', PINJ_POKOK_SISA = '$pinjaman_pokok_sisa_baru', 

											PINJ_BASIL_BAYAR = PINJ_BASIL_BAYAR + '$trx->BASIL_BAYAR_PINJAM' WHERE IDANGGOTA = '$trx->KODE_ANGGOTA'";

										}



										$defaultdb->query($sql);



										$sql = "UPDATE m_anggota_simp SET SALDO = SALDO + $trx->KODE_UNIK WHERE IDJENIS_SIMP = '$trx->ID_JENIS_SIMPANAN' AND IDANGGOTA='$trx->KODE_ANGGOTA'";

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

												WHERE A.IDPINJAM = '$trx->ID_PINJAM' AND A.ANGSURAN_KE = '$trx->ANGSURAN_KE_PINJAM'";



										$cekPinj = $defaultdb->query($sql);



										if ($cekPinj->num_rows() > 0) {

											foreach ($cekPinj->result() as $key) {

												$datatransaksi	=	array(

													'tgl' 		=> $key->TGL_BAYAR,

													'jumlah' 		=> $key->JUMLAH_BAYAR,

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

									}

								}

							}
							else if ($trx->KODE_TRX === 5) { // aktivasi anggota

								$defaultdb 	= $this->load->database('default', TRUE);								
								$sql = "SELECT * FROM m_anggota WHERE IDANGGOTA = '$trx->KODE_ANGGOTA'";
								$cekAnggota = $defaultdb->query($sql);

								if ($cekAnggota->num_rows() > 0) 
								{
								    $anggota = $cekAnggota->row();
								    
									$noAgt = $anggota->KODEPUSAT.$anggota->KODECABANG."-".$anggota->NO_ANGGOTA;
									$sql = "SELECT * FROM jenis_kas WHERE TMPL_SIMPAN = 'Y' AND KODECABANG='".$anggota->KODECABANG."' LIMIT 1";
									$cekJenisKas = $defaultdb->query($sql);
																	
									if ($cekJenisKas->num_rows() > 0) 
									{
										$jnsKas = $cekJenisKas->row();
										$now = date('Y-m-d H:i:s');

										$sqlsimpan1 = "INSERT INTO transaksi_simp(TGL_TRX, ID_ANGGOTA, ID_JENIS, JUMLAH, KETERANGAN, KET_BAYAR, AKUN, DK, ID_KAS, ID_KASAKUN,
												ID_KASAKUN, USERNAME, NAMA_PENYETOR, NO_IDENTITAS, ALAMAT, KOLEKTOR, STATUS, KODEPUSAT, KODECABANG, UPDATE_DATA) VALUES 
												('$tgl', '$anggota->IDANGGOTA', '258', '30000', 'Setoran awal simpanan pokok '.$anggota->NAMA.' '.$noAgt', 'Tabungan', 'Setoran', 'D', '$jnsKas->ID_JNS_KAS', 
												'$jnsKas->IDAKUN', 'mobile-app', '$anggota->NAMA', '$anggota->NO_IDENTITAS', '$anggota->ALAMAT', 0, 1, '$anggota->KODEPUSAT', '$anggota->KODECABANG', '$now')";

										$defaultdb->query($sqlsimpan1);
										
										$sqlsimpan2 = "INSERT INTO transaksi_simp(TGL_TRX, ID_ANGGOTA, ID_JENIS, JUMLAH, KETERANGAN, KET_BAYAR, AKUN, DK, ID_KAS, ID_KASAKUN,
												ID_KASAKUN, USERNAME, NAMA_PENYETOR, NO_IDENTITAS, ALAMAT, KOLEKTOR, STATUS, KODEPUSAT, KODECABANG, UPDATE_DATA) VALUES 
												('$tgl', '$anggota->IDANGGOTA', '180', '50000', 'Setoran awal simpanan mudharabah'.$anggota->NAMA.' '.$noAgt', 'Tabungan', 'Setoran', 'D', '$jnsKas->ID_JNS_KAS', 
												'$jnsKas->IDAKUN', 'mobile-app', '$anggota->NAMA', '$anggota->NO_IDENTITAS', '$anggota->ALAMAT', 0, 1, '$anggota->KODEPUSAT', '$anggota->KODECABANG', '$now')";

										$defaultdb->query($sqlsimpan2);

										$sqlsimpan3 = "INSERT INTO transaksi_simp(TGL_TRX, ID_ANGGOTA, ID_JENIS, JUMLAH, KETERANGAN, KET_BAYAR, AKUN, DK, ID_KAS, ID_KASAKUN,
												ID_KASAKUN, USERNAME, NAMA_PENYETOR, NO_IDENTITAS, ALAMAT, KOLEKTOR, STATUS, KODEPUSAT, KODECABANG, UPDATE_DATA) VALUES 
												('$tgl', '$anggota->IDANGGOTA', '259', '20000', 'Setoran awal simpanan wajib'.$anggota->NAMA.' '.$noAgt', 'Tabungan', 'Setoran', 'D', '$jnsKas->ID_JNS_KAS', 
												'$jnsKas->IDAKUN', 'mobile-app', '$anggota->NAMA', '$anggota->NO_IDENTITAS', '$anggota->ALAMAT', 0, 1, '$anggota->KODEPUSAT', '$anggota->KODECABANG', '$now')";

										$defaultdb->query($sqlsimpan3);

										$tgl = date("Y-m-d H:i:s");

										$sql = "SELECT * FROM transaksi_simp WHERE TGL_TRX = '$tgl' AND ID_ANGGOTA = '$anggota->IDANGGOTA'";
										$cekTrxSimp = $defaultdb->query($sql);

										if ($cekTrxSimp->num_rows() > 0) 
										{

											foreach($cekTrxSimp->result() as $key)
											{ 
												/* Insert data transaksi simpanan ke jurnal transaksi(table vtransaksi) */
												$datatransaksi = array( 'tgl' => $key->TGL_TRX, 'jumlah' => $key->JUMLAH, 'keterangan' => $key->KETERANGAN,
																		'user' => $key->USERNAME, 'kodecabang' => $key->KODECABANG, 'idkasakun' => $key->ID_KASAKUN);
																		
												$this->ModelVTransaksi->insertVtransaksi($key->ID_TRX_SIMP, $datatransaksi, 'ST', $key->ID_KAS, $key->ID_JENIS, 'SIMP');
																								
												$wanggota = "IDANGGOTA = '". $key->ID_ANGGOTA."'";
												$uanggota = array("AKTIF"=>"Y");
												
												$this->dbasemodel->updateData("m_anggota", $uanggota, $wanggota);	

												$ceklst = $this->dbasemodel->loadsql("SELECT * FROM m_anggota_simp WHERE IDANGGOTA='".$anggota->IDANGGOTA."' AND IDJENIS_SIMP='".$key->ID_JENIS."'");
				
												if($ceklst->num_rows()>0)
												{
													$rchek = $ceklst->row();

													$sqlUpdateSimp = "UPDATE m_anggota_simp SET SALDO = SALDO + $key->JUMLAH WHERE ID_ANG_SIMP = $rchek->ID_ANG_SIMP";
													$defaultdb->query($sqlUpdateSimp);
												}
												else
												{

                                                    $tgTRX = date("Y-m-d", strtotime($key->TGL_TRX));
													$sqlInsertSimp = "INSERT INTO m_anggota_simp (IDANGGOTA, IDJENIS_SIMP, SALDO, TGLREG) VALUES ('$key->ID_ANGGOTA', '$key->ID_JENIS', '$key->JUMLAH', '$tgTRX'";
													$defaultdb->query($sqlInsertSimp);
												}
											}
										}

										$sqlUpdateAnggota = "UPDATE m_anggota SET AKTIF = 'Y' WHERE IDANGGOTA = $anggota->IDANGGOTA";
										$defaultdb->query($sqlUpdateAnggota);

									}
								}

								
								
								

							}

						} else {

							$array = array(

								"success" => false,

								"message" => "Maaf, Gagal update transaksi"

							);

							echo json_encode($array);

						}

					} else {

						$array = array(

							"success" => false,

							"message" => "Maaf, transaksi tidak ditemukan"

						);

						echo json_encode($array);

					}

				}

			}

		}

		echo json_encode($json);

	}



	protected function akunKasReset($kodecabang)

	{

		$sql			=	sprintf(

			"SELECT

										A.IDAKUN

									FROM

										jenis_kas A

									WHERE

										A.KODECABANG = '%s'

										AND A.NAMA_KAS LIKE 'kas reset'

									LIMIT 1",

			$kodecabang

		);

		$query			=	$this->dbasemodel->loadSql($sql);

		if ($query->num_rows() > 0) {

			$row	=	$query->row();

			return $row->IDAKUN;

		}

		return 0;

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



	public function ovo_daftar_bank()

	{

		$post = file_get_contents("php://input");

		$postData = json_decode($post);



		$data = array(

			"source_number"  => $postData->phoneNo

		);



		$ch = curl_init();

		curl_setopt_array($ch, array(

			CURLOPT_URL             => $this->api_url . "/ovo/transfer/bank-list",

			CURLOPT_POST            => true,

			CURLOPT_POSTFIELDS      => http_build_query($data),

			CURLOPT_HTTPHEADER      => ["Api-Key: " . $this->api_key, "Accept: application/json"], // tanpa tanda kurung

			CURLOPT_RETURNTRANSFER  => true,

			CURLOPT_HEADER          => false,

			CURLOPT_IPRESOLVE		=> CURL_IPRESOLVE_V4,

		));

		$result = curl_exec($ch);

		curl_close($ch);



		echo $result;

	}

}