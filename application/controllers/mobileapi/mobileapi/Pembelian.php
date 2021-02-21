<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pembelian extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
		header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
		$this->load->helper(array('form', 'url', 'xml', 'text_helper', 'date', 'inflector', 'app', 'tri'));
		$this->load->database();
		$this->load->library(array('Pagination', 'user_agent', 'session', 'form_validation', 'session'));
		$this->load->model('mobileapi/dbasemodel');
		//@session_start();
	}
	public function index()
	{
		$noangggota 	= $this->input->post('user');
		$hp 			= $this->input->post('hp');
		$nomertrx		= date("ymdHis");
		$tgl			= date("Y-m-d H:i:s");
		$idprd 			= $this->input->post('idprd');
		$via 			= $this->input->post('via');

		//var_dump($_POST);

		$cek 		= $this->dbasemodel->loadsql("SELECT * FROM m_product WHERE IDPRODUK='$idprd'");
		if ($cek->num_rows() > 0) {
			$prod 		= $cek->row();
			$kode 		= $prod->KODE;
			$hbeli 		= $prod->HARGA_BELI;
			$hjual 		= $prod->HARGA_JUAL;

			$sqls 		= $this->dbasemodel->loadsql("SELECT * FROM m_trx WHERE DATE(TGL) = CURDATE() AND NOHP='$hp' AND PRODUK='$kode'");
			if ($sqls->num_rows() > 0) {

				$array = array(
					"code" => "404",
					"msg" => "Transaksi dengan nominal dan nomer yang sama sudah pernah dilakukan, silahkan coba dengan nominal lain",
					"data" => ""
				);
				echo json_encode($array);
			} else {

				$tbtrx	= $sqls->row();

				$datainsert = array(
					'KODE_ANGGOTA' => $noangggota,
					'TGL' => $tgl,
					'NOTRX' => $nomertrx,
					'NOHP' => $hp,
					'IDPRODUK' => $idprd,
					'PRODUK' => $kode,
					'HARGA_BELI' => $hbeli,
					'HARGA_JUAL' => $hjual,
					'PAYMENT_VIA' => $via
				);
				$idtrx = $this->dbasemodel->insertTrx("m_trx", $datainsert);

				$array = array(
					"code" => "200",
					"msg" => "Transaksi Berhasil Di Proses",
					"data" => ""
				);
				echo json_encode($array);
				//$this->pembelian($idtrx);
				trxpembelian($idtrx, $noangggota);
			}
		} else {
			$array = array(
				"code" => "404",
				"msg" => "Produk tidak ditemukan",
				"data" => ""
			);
			echo json_encode($array);
		}
	}

	function pembelianpln()
	{

		$noangggota 	= $this->input->post('user');
		$hp 			= $this->input->post('hp');
		$idpel 			= $this->input->post('idpel');
		$nomertrx		= date("ymdHis");
		$tgl			= date("Y-m-d H:i:s");
		$idprd 			= $this->input->post('idprd');
		$via 			= $this->input->post('via');

		//var_dump($_POST);

		$cek 		= $this->dbasemodel->loadsql("SELECT * FROM m_product WHERE IDPRODUK='$idprd'");
		if ($cek->num_rows() > 0) {
			$prod 		= $cek->row();
			$kode 		= $prod->KODE;
			$hbeli 		= $prod->HARGA_BELI;
			$hjual 		= $prod->HARGA_JUAL;

			$sqls 		= $this->dbasemodel->loadsql("SELECT * FROM m_trx WHERE DATE(TGL) = CURDATE() AND NOHP='$hp' AND PRODUK='$kode'");
			if ($sqls->num_rows() > 0) {

				$array = array(
					"code" => "404",
					"msg" => "Transaksi dengan nominal dan nomer yang sama sudah pernah dilakukan, silahkan coba dengan nominal lain",
					"data" => ""
				);
				echo json_encode($array);
			} else {

				$tbtrx	= $sqls->row();

				$datainsert = array(
					'KODE_ANGGOTA' => $noangggota,
					'TGL' => $tgl,
					'NOTRX' => $nomertrx,
					'NOHP' => $hp,
					'IDPRODUK' => $idprd,
					'IDPEL' => $idpel,
					'PRODUK' => $kode,
					'HARGA_BELI' => $hbeli,
					'HARGA_JUAL' => $hjual,
					'PAYMENT_VIA' => $via
				);
				$idtrx = $this->dbasemodel->insertTrx("m_trx", $datainsert);

				$array = array(
					"code" => "200",
					"msg" => "Transaksi Berhasil Di Proses",
					"data" => ""
				);
				echo json_encode($array);
				//$this->pembelian($idtrx);
				trxpln($idtrx);
			}
		} else {
			$array = array(
				"code" => "404",
				"msg" => "Produk tidak ditemukan",
				"data" => ""
			);
			echo json_encode($array);
		}
	}
}
