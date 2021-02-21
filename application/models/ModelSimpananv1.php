<?php



class ModelSimpanan extends CI_Model
{



	public function __construct()
	{

		parent::__construct();

		$this->load->database();
	}



	public function getDataTable($keyword, $dataperpage, $page, $koncabang, $wheretrgl)
	{

		$ci	= &get_instance();
		$ci->load->model('DBHelper');

		$con_keyword =	$keyword == "" ? "1=1" : "(A.NAMA_PENYETOR LIKE '%" . $keyword . "%')";

		$startid	=	($page * 1 - 1) * $dataperpage;

		$datastart	=	$startid + 1;

		$dataend	=	$datastart + $dataperpage - 1;


		$sql	=	sprintf(
			"SELECT 

								A.ID_TRX_SIMP, DATE_FORMAT(A.TGL_TRX, '%s') TGL_TRX,

								A.NAMA_PENYETOR, A.ALAMAT,

								FORMAT(A.JUMLAH, 0) JUMLAH, A.USERNAME, A.NO_IDENTITAS,

								B.JNS_SIMP,D.KODECABANG,E.NAMA AS NAMACABANG,

								A.KETERANGAN

							 FROM

							 	transaksi_simp A

							 LEFT JOIN

							 	jns_simpan B ON A.ID_JENIS = B.IDAKUN

							LEFT JOIN

							 	m_user D ON A.USERNAME = D.USERNAME

							 LEFT JOIN

							 	m_cabang E ON D.KODECABANG = E.KODE

							 WHERE 

							 	STATUS = 1 AND UPDATE_DATA <> '0000-00-00 00:00:00' 

							 	AND DK = 'D' AND %s %s %s

							 ORDER BY

							 	DATE(A.TGL_TRX) DESC, A.ID_TRX_SIMP DESC",

			'%d/%m/%Y',
			$con_keyword,
			$koncabang,
			$wheretrgl

		);

		//echo $sql;die;				

		$query		=	$this->db->query($sql . " LIMIT " . $startid . ", " . $dataperpage);

		$result		=	$query->result();



		if (!$result) {

			return $ci->DBHelper->generateEmptyResult();
		}



		return $ci->DBHelper->generateResult($result, $sql, "ID_TRX_SIMP", $page, $dataperpage, $datastart, $dataend);
	}



	public function getDataTablePenarikan($keyword, $dataperpage, $page, $koncabang, $wheretrgl)
	{



		$ci			= &get_instance();

		$ci->load->model('DBHelper');



		$con_keyword =	$keyword == "" ? "1=1" : "(C.NAMA LIKE '%" . $keyword . "%')";

		$startid	=	($page * 1 - 1) * $dataperpage;

		$datastart	=	$startid + 1;

		$dataend	=	$datastart + $dataperpage - 1;



		$sql	=	sprintf(
			"SELECT 

								A.ID_TRX_SIMP, DATE_FORMAT(A.TGL_TRX, '%s') TGL_TRX,

								FORMAT(A.JUMLAH,0) JUMLAH, A.USERNAME,

								B.JNS_SIMP,

								C.NAMA NAMA_ANGGOTA,D.KODECABANG,E.NAMA AS NAMACABANG,

								A.KETERANGAN

							 FROM

							 	transaksi_simp A

							 LEFT JOIN

							 	jns_simpan B ON A.ID_JENIS = B.IDAKUN

							 LEFT JOIN

							 	m_anggota C ON A.ID_ANGGOTA = C.IDANGGOTA

							LEFT JOIN

							 	m_user D ON A.USERNAME = D.USERNAME

							 LEFT JOIN

							 	m_cabang E ON D.KODECABANG = E.KODE

							 WHERE DK = 'K' AND %s %s %s

							 ORDER BY

							 	DATE(A.TGL_TRX) DESC, A.ID_TRX_SIMP DESC",

			'%d/%m/%Y',
			$con_keyword,
			$koncabang,
			$wheretrgl

		);



		$query		=	$this->db->query($sql . " LIMIT " . $startid . ", " . $dataperpage);

		$result		=	$query->result();



		if (!$result) {

			return $ci->DBHelper->generateEmptyResult();
		}



		return $ci->DBHelper->generateResult($result, $sql, "ID_TRX_SIMP", $page, $dataperpage, $datastart, $dataend);
	}



	public function updateSaldoAnggota($tipe = 'tambah', $nominal = 0, $jenis = '32', $idanggota)
	{

		switch ($jenis) {

			case	'32':
				$_update	=	" SLD_MUDHARABAH = (SLD_MUDHARABAH " . ($tipe == "tambah" ? "+" : "-") . " " . $nominal . ") "; //mudharabah

				break;

			case	'40':
				$_update	=	" SLD_POKOK = (SLD_POKOK " . ($tipe == "tambah" ? "+" : "-") . " " . $nominal . ") "; //pokok

				break;

			case	'41':
				$_update	=	" SLD_WAJIB = (SLD_WAJIB " . ($tipe == "tambah" ? "+" : "-") . " " . $nominal . ") "; //wajib

				break;

			case	'42':
				$_update	=	" SLD_PENYERTAAN = (SLD_PENYERTAAN " . ($tipe == "tambah" ? "+" : "-") . " " . $nominal . ") "; //penyertaan

				break;

			default:

				break;
		}



		$sql	=	sprintf(
			"INSERT INTO m_anggota_simp(IDANGGOTA, IDJENIS_SIMP, SALDO, TGLREG)

								VALUES(%s, %s, %s, date(now()))

								ON DUPLICATE KEY UPDATE

										 SALDO =  (SALDO %s %s) ",

			$idanggota,
			$jenis,
			$nominal,

			($tipe == "tambah" ? "+" : "-"),

			$nominal

		);





		//$sql	=	sprintf("UPDATE m_anggota SET %s WHERE IDANGGOTA = '%s' ", $_update, $idanggota);

		return $this->db->query($sql);
	}



	public function updateSaldo($tipe = 'tambah', $idtrx)
	{

		$sql	=	sprintf(
			"SELECT 

								A.ID_ANGGOTA, A.JUMLAH, A.ID_JENIS

							 FROM

							 	transaksi_simp A

							 WHERE ID_TRX_SIMP = %s",

			$idtrx

		);



		$query		=	$this->db->query($sql);

		$row		=	$query->row();

		return $this->updateSaldoAnggota($tipe, $row->JUMLAH, $row->ID_JENIS, $row->ID_ANGGOTA);
	}



	public function getSaldo($idanggota, $idjenis)
	{

		$sql	=	sprintf("SELECT SALDO

							 FROM m_anggota_simp

							 WHERE IDANGGOTA = %s AND IDJENIS_SIMP IN (%s) ", $idanggota, $idjenis);



		$query		=	$this->db->query($sql);

		if ($query->num_rows() > 0) {

			$row		=	$query->row();

			return $row->SALDO;
		}

		return 0;
	}



	public function getSaldo2222($idanggota, $idjenis)
	{

		$field	=	$idjenis == '32' ? 'SLD_MUDHARABAH' : 'SLD_PENYERTAAN';

		$sql	=	sprintf("SELECT %s AS SALDO

							 FROM m_anggota

							 WHERE IDANGGOTA = %s", $field, $idanggota);



		$query		=	$this->db->query($sql);

		$row		=	$query->row();

		return $row->SALDO;
	}
}
