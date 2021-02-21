<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Simpanan extends CI_Controller
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
		$this->load->model(array('dbasemodel'));
	}

	public function index()
	{
		$post = file_get_contents("php://input");
		$postData = json_decode($post);
		$arr = array();
		$sql = "SELECT a.IDAKUN, a.JNS_SIMP, b.SALDO FROM `jns_simpan` a 
		LEFT JOIN m_anggota_simp b 
		ON b.IDJENIS_SIMP = a.IDAKUN AND b.IDANGGOTA = $postData->idAnggota";
		$cek = $this->dbasemodel->loadsql($sql);
		if ($cek->num_rows() > 0) {
			foreach ($cek->result() as $key) {
				array_push($arr, array(
					"id_akun" => (int) $key->IDAKUN,
					"jenis_simpanan" => $key->JNS_SIMP,
					"saldo" => (int) $key->SALDO
				));
			}
			$array = array(
				"success" => true,
				"data" => $arr
			);
			echo json_encode($array);
		} else {
			$array = array(
				"success" => false,
				"data" => $arr
			);
			echo json_encode($array);
		}
	}

	public function topup()
	{
		$post = file_get_contents("php://input");
		$postData = json_decode($post);

		$nomertrx = date("ymdHis");
		$tgl = date("Y-m-d H:i:s");
		$expiredDate = date("Y-m-d H:i:s", strtotime("+1 hour"));

		$msg = "Topup simpanan $postData->namaSimpanan Anda telah tersimpan. Silakan lakukan pembayaran transfer sebelum $expiredDate.";

		$sql = "INSERT INTO m_trx(KODE_TRX, ID_JENIS_SIMPANAN, KODE_ANGGOTA, TGL, NOTRX, PAYMENT_VIA, KODE_UNIK, TOTAL_BAYAR, STATUS_BAYAR, EXPIRED_DATE, PROSES, STATUS, MSG, IDH2H) 
				VALUES (3, '$postData->idSimpanan', '$postData->idAnggota', '$tgl', '$nomertrx', 'transfer', '$postData->kode_unik', '$postData->total_bayar', 0, '$expiredDate', 
				0, 0, '$msg', 0)";
		$otherdb = $this->load->database('otherdb', TRUE);
		$otherdb->query($sql);

		$array = array(
			"success" => true,
			"message" => "Topup simpanan $postData->namaSimpanan Anda telah tersimpan. Silakan lakukan pembayaran transfer sebelum $expiredDate. Selengkapnya dapat dilihat pada Riwayat Transaksi."
		);
		echo json_encode($array);
	}
}
