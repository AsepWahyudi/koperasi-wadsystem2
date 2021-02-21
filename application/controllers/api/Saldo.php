<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Saldo extends CI_Controller
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
		$otherdb 	= $this->load->database('otherdb', TRUE);
		$sql = "SELECT * FROM m_saldo WHERE STATUS = 1 ORDER BY STATUS ASC";
		$cek = $otherdb->query($sql);
		$arr = array();

		// $sql = "SELECT * FROM m_saldo WHERE STATUS = 1";
		// $cek = $this->dbasemodel->loadsql($sql);
		if ($cek->num_rows() > 0) {
			foreach ($cek->result() as $key) {
				array_push($arr, array(
					"id" => $key->ID_SALDO,
					"nilai" => (int) $key->NILAI
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
				"message" => "Data saldo tidak ditemukan"
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

		$msg = "Topup saldo Anda telah tersimpan. Silakan lakukan pembayaran transfer sebelum $expiredDate.";

		$sql = "INSERT INTO m_trx(KODE_TRX, KODE_ANGGOTA, TGL, NOTRX, PAYMENT_VIA, KODE_UNIK, TOTAL_BAYAR, STATUS_BAYAR, EXPIRED_DATE, PROSES, STATUS, MSG, IDH2H, ID_JENIS_SIMPANAN) 
				VALUES (2, '$postData->idAnggota', '$tgl', '$nomertrx', 'transfer', '$postData->kode_unik', '$postData->total_bayar', 0, '$expiredDate', 
				0, 0, '$msg', 0, '180')";
		$otherdb = $this->load->database('otherdb', TRUE);
		$otherdb->query($sql);

		$array = array(
			"success" => true,
			"message" => "Topup saldo Anda telah tersimpan. Silakan lakukan pembayaran transfer sebelum $expiredDate. Selengkapnya dapat dilihat pada Riwayat Transaksi."
		);
		echo json_encode($array);
	}

	public function simpanan()
	{
		if ($this->input->post("idAnggota") !== null) {
			$idanggota  = $this->input->post("idAnggota");
		} else {
			$post = json_decode(file_get_contents("php://input"), true);
			$idanggota	= $post['idAnggota'];
		}
		$arr = array();
		$sql = "SELECT A.IDJENIS_SIMP KODE, B.JNS_SIMP NAMA, A.SALDO 
				FROM m_anggota_simp A
				JOIN jns_simpan B ON A.IDJENIS_SIMP = B.IDAKUN
				WHERE A.IDANGGOTA = $idanggota AND B.IDAKUN = 180";
		$cek = $this->dbasemodel->loadsql($sql);
		if ($cek->num_rows() > 0) {
			foreach ($cek->result() as $key) {
				array_push($arr, array(
					"saldo" => (int) $key->SALDO,
				));
			}
			$array = array(
				"code" => 200,
				"data" => $arr[0]
			);
			echo json_encode($array);
		} else {
			$array = array(
				"code" => 200,
				"data" => array(
					"saldo" => 0
				)
			);
			echo json_encode($array);
		}
	}

	public function pinjaman()
	{
		if ($this->input->post("idAnggota") !== null) {
			$idanggota  = $this->input->post("idAnggota");
		} else {
			$post = json_decode(file_get_contents("php://input"), true);
			$idanggota	= $post['idAnggota'];
		}
		$sql 	= sprintf(
			"
						SELECT A.*, C.JNS_PINJ 
						FROM 
							tbl_pinjaman_h A
						LEFT JOIN jns_pinjm C ON A.BARANG_ID = C.IDJNS_PINJ
						WHERE 
							A.ANGGOTA_ID = %s AND A.LUNAS = 'Belum' AND A.PINJ_SISA > 0 ",
			$idanggota
		);
		$query	= $this->dbasemodel->loadsql($sql);

		$arr = array();
		if ($query->num_rows() > 0) {

			foreach ($query->result() as $key) {
				array_push($arr, array(
					"IDPINJM_H" => $key->IDPINJM_H,
					"TGL_PINJ" => $key->TGL_PINJ,
					"ANGGOTA_ID" => $key->ANGGOTA_ID,
					"JNS_PINJ" => $key->JNS_PINJ,
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
					"JENIS_JAMINAN" => (int) $key->JENIS_JAMINAN
				));
			}
			$array = array(
				"success" => true,
				"data" => $arr
			);
			// $key = $query->row();

			// $array = array(
			// 	"success" => true,
			// 	"data" => $key
			// );
			echo json_encode($array);
		} else {
			$array = array(
				"success" => false,
				"message" => "Tidak ada pinjaman"
			);
			echo json_encode($array);
		}
		return 'null';
	}
}
