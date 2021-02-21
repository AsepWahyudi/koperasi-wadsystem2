<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Produk extends CI_Controller
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
		$cek = $this->dbasemodel->loadsql("SELECT * FROM m_product");
		if ($cek->num_rows() > 0) {

			foreach ($cek->result() as $key) {
				array_push($arr, array(
					"id" => $key->IDPRODUK,
					"kode" => $key->KODE,
					"nama" => $key->NAMA,
					"price" => (int) $key->HARGA_JUAL,
					"admin" => (int) $key->ADMIN,
					"operator" => (int) $key->OPERATOR
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
				"message" => "Produk tidak ditemukan",
				"data" => []
			);
			echo json_encode($array);
		}
	}

	function kategori()
	{
		//echo "pulsa";
		$parent	= $this->input->post('parent');
		$arr 	= array();
		$cek 	= $this->dbasemodel->loadsql("SELECT * FROM m_kat_prod WHERE PARENT='$parent' ORDER BY IDKAT ASC");
		if ($cek->num_rows() > 0) {

			foreach ($cek->result() as $key) {
				array_push($arr, array(
					"id" => $key->IDKAT,
					"provider" => $key->KATEGORI,
					"images" => base_url() . "assets/produk/" . $key->GAMBAR
				));
			}

			$array = array(
				"code" => "200",
				"msg" => "",
				"data" => $arr
			);
			echo json_encode($array);
		} else {
			$array = array(
				"code" => "404",
				"msg" => "Produk tidak ditemukan",
				"data" => ""
			);
			echo json_encode($array);
		}
	}

	public function operator()
	{
		$post = file_get_contents("php://input");
		$postData = json_decode($post);

		$arr = array();
		$cek = $this->dbasemodel->loadsql("SELECT * FROM m_product WHERE OPERATOR='$postData->operatorId' ORDER BY IDPRODUK ASC");
		if ($cek->num_rows() > 0) {

			foreach ($cek->result() as $key) {
				array_push($arr, array(
					"id" => $key->IDPRODUK,
					"kode" => $key->KODE,
					"nama" => $key->NAMA,
					"inq" => $key->PRDINQ,
					"price" => $key->HARGA_JUAL
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
				"message" => "Produk tidak ditemukan",
				"data" => []
			);
			echo json_encode($array);
		}
	}

	public function kategori_parent()
	{
		$post = file_get_contents("php://input");
		$postData = json_decode($post);

		$arr = array();
		$cek = $this->dbasemodel->loadsql("SELECT DISTINCT p.IDPRODUK, p.NAMA, p.KODE, p.HARGA_JUAL, p.ADMIN, p.OPERATOR FROM m_product p INNER JOIN m_kat_prod k WHERE k.PARENT='$postData->parent' ORDER BY IDPRODUK ASC");
		if ($cek->num_rows() > 0) {

			foreach ($cek->result() as $key) {
				array_push($arr, array(
					"id" => $key->IDPRODUK,
					"kode" => $key->KODE,
					"nama" => $key->NAMA,
					"price" => (int) $key->HARGA_JUAL,
					"admin" => (int) $key->ADMIN,
					"operator" => (int) $key->OPERATOR
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
				"message" => "Produk tidak ditemukan",
				"data" => []
			);
			echo json_encode($array);
		}
	}
}
