<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Transaction extends CI_Controller
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
		$this->load->model('mobileapi/dbasemodel');
		//@session_start();
	}

	public function index()
	{
		$arr = array();
		$post = file_get_contents("php://input");
		$postData = json_decode($post);
		$kodeAnggota = $postData->kodeAnggota;
		$limit = $postData->limit;
		$offset = $postData->offset;
		$sql = "SELECT * FROM m_trx WHERE KODE_ANGGOTA = '$kodeAnggota' ORDER BY TGL DESC LIMIT $limit OFFSET $offset";
		$cek = $this->dbasemodel->loadsql($sql);
		if ($cek->num_rows() > 0) {

			foreach ($cek->result() as $key) {
				array_push($arr, array(
					"kode_trx" => $key->KODE_TRX,
					"id" => $key->IDTRX,
					"id_anggota" => $key->KODE_ANGGOTA,
					"no_hp" => $key->NOHP,
					"id_pelanggan" => $key->IDPEL,
					"tgl" => $key->TGL,
					"no_trx" => $key->NOTRX,
					"id_produk" => $key->IDPRODUK,
					"produk" => $key->PRODUK,
					"harga_beli" => $key->HARGA_BELI,
					"harga_jual" => $key->HARGA_JUAL,
					"payment_via" => $key->PAYMENT_VIA,
					"kode_unik" => $key->KODE_UNIK,
					"total_bayar" => $key->TOTAL_BAYAR,
					"status_bayar" => $key->STATUS_BAYAR,
					"tgl_bayar" => $key->TGL_BAYAR,
					"expired_date" => $key->EXPIRED_DATE,
					"proses" => $key->PROSES,
					"status" => $key->STATUS,
					"msg" => $key->MSG,
					"trxid" => $key->TRXID,
					"token" => $key->TOKEN,
					"note" => $key->NOTE,
					"callback" => $key->CALLBACK,
					"order_id" => $key->ORDER_ID,
					"inquiry" => $key->INQUIRY,
					"id_H2H" => $key->IDH2H
				));
			}
			$array = array(
				"success" => true,
				"message" => "",
				"data" => $arr
			);
			echo json_encode($array);
		} else {
			$array = array(
				"success" => false,
				"message" => "Rekening Transfer tidak ditemukan",
				"data" => []
			);
			echo json_encode($array);
		}
	}
}
