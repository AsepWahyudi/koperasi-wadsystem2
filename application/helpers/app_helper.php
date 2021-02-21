<?php
function toRp($angka)
{
    return number_format($angka,0, '.', ',');
}

function notifikasi($data)
{
	if($data == "") { return ""; }
	$ex		=	explode("||", $data);
	if($ex[0] == "11") {
		$html	=	'<div class="alert alert-success alert-dismissible fade show" role="alert">
					  <button aria-label="Close" class="close" data-dismiss="alert" type="button"><span aria-hidden="true"> ×</span></button><strong>Success! </strong>'. $ex[1].'
					</div>';
	} elseif($ex[0] == "00") {
		$html	=	'<div class="alert alert-danger alert-dismissible fade show" role="alert">
					  <button aria-label="Close" class="close" data-dismiss="alert" type="button"><span aria-hidden="true"> ×</span></button><strong>Error! </strong>'. $ex[1].'
					</div>';
	}
    return $html;
}

function tgl_en($data)
{
	if($data == "") { return ""; }
	return date('d-M-Y', strtotime($data));
}

function sukubunga($data)
{
	$CI = get_instance();
	$CI->load->model('dbasemodel');
	$sql	= "SELECT opsi_val VALUE FROM suku_bunga WHERE opsi_key = '".$data."'";
	$ks		= $CI->dbasemodel->loadSql($sql);
	if($ks->num_rows() > 0) {
		$row	= $ks->row();
		return $row->VALUE;
	}
	return '';
}

function kasteller($data)
{
	$idakun		=	'5';
	switch($data) {
		case '01' :	$idakun	=	9;
		break;
		case '02' :	$idakun	=	13;
		break;
		case '03' :	$idakun	=	17;
		break;
		case '04' :	$idakun	=	21;
		break;
		case '05' :	$idakun	=	25;
		break;
		case '06' :	$idakun	=	29;
		break;
		case '07' :	$idakun	=	33;
		break;
		case '08' :	$idakun	=	37;
		break;
		case '09' :	$idakun	=	'5';
		break;
		case '10' : $idakun	=	'5';
		break;
		case '11' : $idakun	=	41;
		break;
		default :	
	    break;
	}
	return $idakun;
}

function tgl_indo($data)
{
	$src	= array("01","02","03","04","05","06","07","08","09","10","11","12");
	$rpl	= array("Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");
	$ex		= str_replace($src,$rpl,date('m', strtotime($data)));
	return date('d', strtotime($data))." ".$ex." ".date('Y', strtotime($data));
}
function blth_indo($data)
{
	$src	= array("01","02","03","04","05","06","07","08","09","10","11","12");
	$rpl	= array("Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");
	$ex		= str_replace($src,$rpl,date('m', strtotime($data)));
	return $ex." ".date('Y', strtotime($data));
}

function bulan_indo($bln)
{
	$bulan = array (
			1 =>   'Januari',
			'Februari',
			'Maret',
			'April',
			'Mei',
			'Juni',
			'Juli',
			'Agustus',
			'September',
			'Oktober',
			'November',
			'Desember'
		);
		$pecahkan = explode('-', $bln);
		
		// variabel pecahkan 0 = tanggal
		// variabel pecahkan 1 = bulan
		// variabel pecahkan 2 = tahun
	 
		return $bulan[ (int)$pecahkan[1] ];
	
}

function getRekpinj($kopus,$kocab)
{
	$CI = get_instance();
	$CI->load->model('dbasemodel');
	$sql	= "SELECT COALESCE(MAX(REKENING), 0)+1 AS NOMER FROM tbl_pinjaman_h WHERE KODEPUSAT='".$kopus."' AND KODECABANG='".$kocab."'";
	$ks		= $CI->dbasemodel->loadSql($sql);
	$rnom		= $ks->row();
	$rekening 	= str_pad($rnom->NOMER, 6, '0', STR_PAD_LEFT);
	return $rekening;
}

function namaCabang($kodecabang)
{
	$CI = get_instance();
	$CI->load->model('dbasemodel');
	$sql	= "SELECT * FROM m_cabang WHERE KODE = '". $kodecabang ."'";
	$ks		= $CI->dbasemodel->loadSql($sql);
	if($ks->num_rows() > 0) {
		$row	=	$ks->result_array();
		return $row[0];
	}
	return array();
}

function namaAkun($idakun)
{
	$CI = get_instance();
	$CI->load->model('dbasemodel');
	$sql	= "SELECT * FROM jns_akun WHERE IDAKUN = '". $idakun ."'";
	$ks		= $CI->dbasemodel->loadSql($sql);
	if($ks->num_rows() > 0) {
		$row	=	$ks->result_array();
		return $row[0];
	}
	return array();
}

function jenisPinjam($idakun)
{
	$CI = get_instance();
	$CI->load->model('dbasemodel');
	$sql	= "SELECT * FROM jns_pinjm WHERE IDAKUN = '". $idakun ."'";
	$ks		= $CI->dbasemodel->loadSql($sql);
	if($ks->num_rows() > 0) {
		$row	=	$ks->result_array();
		return $row[0];
	}
	return array();
}

if (!function_exists('jin_date_ina')) {
	function jin_date_ina($date_sql, $tipe = 'full', $time = false) {
		$date = '';
		if($tipe == 'full') {
			$nama_bulan = array(1=>"Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
		} else {
			$nama_bulan = array(1=>"Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Ags", "Sep", "Okt", "Nov", "Des");
		}
		if($time) {
			$exp = explode(' ', $date_sql);
			$exp = explode('-', $exp[0]);
			if(count($exp) == 3) {
				$bln = $exp[1] * 1;
				$date = $exp[2].' '.$nama_bulan[$bln].' '.$exp[0];
			}		
			$exp_time = $exp = explode(' ', $date_sql);
			$date .= ' jam ' . substr($exp_time[1], 0, 5);
		} else {
			$exp = explode('-', $date_sql);
			if(count($exp) == 3) {
				$bln = $exp[1] * 1;
				if($bln > 0) {
					$date = $exp[2].' '.$nama_bulan[$bln].' '.$exp[0];
				}
			}
		}
		return $date;
	}
}

if (!function_exists('jin_nama_bulan')) {
	function jin_nama_bulan($bln, $tipe='full') {
		$bln = $bln * 1;
		if($tipe == 'full') {
			$nama_bulan = array(1=>"Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
		} else {
			$nama_bulan = array(1=>"Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Ags", "Sep", "Okt", "Nov", "Des");
		}
		return $nama_bulan[$bln];
	}
}

if (!function_exists('nsi_round')) {
	function nsi_round($x) {
		//$x = ceil($x / 100) * 100;
		return $x;
	}
}