<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Bank extends CI_Controller
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
		$cek = $this->dbasemodel->loadsql("SELECT * FROM m_rekening_transfer WHERE status = 1");
		if ($cek->num_rows() > 0) {

			foreach ($cek->result() as $key) {
				array_push($arr, array(
					"id" => $key->IDREK,
					"nama" => $key->NAMA_BANK,
					"kode" => $key->KODE_BANK,
					"no_rekening" => $key->NO_REK,
					"atas_nama" => $key->ATAS_NAMA,
					"mulai_aktif" => $key->JAM_MULAI_AKTIF,
					"akhir_aktif" => $key->JAM_AKHIR_AKTIF
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
