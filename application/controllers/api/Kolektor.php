<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kolektor extends CI_Controller {

	function __construct(){ 
        parent::__construct();
		$this->load->helper(array('form','url', 'xml','text_helper','date','inflector','app'));
		$this->load->database();
		$this->load->library(array('Pagination','user_agent','session','form_validation','session'));
		$this->load->model('dbasemodel');
		//@session_start();
    }
	
	public function index()
	{
		
		if($this->input->post())
		{
			$user = $this->input->post("user");
			$pass = md5($this->input->post("pass"));
			$cek  = $this->dbasemodel->loadsql("SELECT * from m_user WHERE USERNAME='$user' AND PASSWORD='$pass' AND LEVEL='kolektor'");
			
			if($cek->num_rows()>0)
			{
				$res =  $cek->row();
				
				$array = array(
							"code" => "200",
							"msg"  => "",
							"data" => array(
										"iduser"     => $res->IDUSER,
										"username"   => $res->USERNAME,
										"kodepusat"  => $res->KODEPUSAT,
										"kodecabang" => $res->KODECABANG
									)
						);
				echo json_encode($array);
			}
			else
			{
				$array = array(
							"code" => "404",
							"msg"  => "Login Gagal",
							"data" => ""
						);
				echo json_encode($array);
			}
		}

	}
	
	function simpanan()
	{
		if($this->input->post())
		{
			$cek = $this->dbasemodel->loadsql("SELECT * from m_anggota WHERE IDANGGOTA='".$this->input->post('idanggota')."'");
			if($cek->num_rows()>0)
			{
				$row        = $cek->row();
				$jumlah     = str_replace(".","",$this->input->post('jumlah'));
				$keterangan = (trim($this->input->post('ket')) == "" ? "Setoran tunai (". $row->NAMA .")" : $this->input->post('ket') );
				
				$insert = array(
							'ID_ANGGOTA'    => $this->input->post('idanggota'),
                            'ID_JENIS'      => $this->input->post('idjenis'),
                            'JUMLAH'        => $jumlah,
                            'KETERANGAN'    => $keterangan,
							'KET_BAYAR'     => 'Tabungan',
							'TGL_TRX'       => date("Y-m-d H:i:s"),
							'AKUN'          => 'Setoran',
							'DK'            => 'D',
							'ID_KAS'        => kasteller($this->input->post('kodecabang')),
                            'USERNAME'      => $this->input->post('uname'),
							'NAMA_PENYETOR' => $row->NAMA,
							'NO_IDENTITAS'  => $row->NO_IDENTITAS,
							'ALAMAT'        => $row->ALAMAT,
							'KOLEKTOR'      => '1',
							'STATUS'        => '0',
							'IDKOLEKTOR'    => $this->input->post('idkol'),
							'KODEPUSAT'     => $this->input->post('kodepusat'),
                            'KODECABANG'    => $this->input->post('kodecabang'),
							'LAT'           => $this->input->post('lat'),
							'LONGT'         => $this->input->post('long')
						);

				//var_dump($_POST);
				if($this->dbasemodel->insertData('transaksi_simp', $insert)) 
				{
					
					$ceklst = $this->dbasemodel->loadsql("Select * FROM checklist_kolektor WHERE TGL_AWAL='".date("Y-m-d")."' AND IDKOLEKTOR='".$this->input->post('idkol')."'");//AND Jenis='Tabungan'
					
					if($ceklst->num_rows()>0)
					{
						
						$rchek	= $ceklst->row();
						$nom 	= $rchek->NOMINAL_SIMP+$this->input->post('jumlah');
						$where  = "IDCEKKOLEKTOR = '". $rchek->IDCEKKOLEKTOR."' ";
						$datacheclist = array("NOMINAL_SIMP"=>$nom);
						$this->dbasemodel->updateData("checklist_kolektor", $datacheclist, $where);
						
						$array = array(
									"code" => "200",
									"msg"  => "Tabungan Berhasil diproses",
									"data" => ""
								);
						echo json_encode($array);
						
					}
					else
					{
						$datacheclist = array(
											"TGL_AWAL"    => date("Y-m-d"),
											"NOMINAL_SIMP"=> $jumlah,
											"KODEPUSAT"   => $this->input->post('kodepusat'),
											"KODECABANG"  => $this->input->post('kodecabang'),
											"IDKOLEKTOR"  => $this->input->post('idkol')
										);//"JENIS"=>"Tabungan"
						$this->dbasemodel->insertData("checklist_kolektor", $datacheclist);
						
						$array = array(
									"code"=> "200",
									"msg" => "Tabungan Berhasil diproses",
									"data"=> ""
								);
							echo json_encode($array);
					}
					
				}
				else
				{
					$array = array(
								"code"=> "404",
								"msg" => "Proses Tabungan Gagal",
								"data"=> ""
							);
					echo json_encode($array);
				}
				
							
			}else{
				$array = array(
							"code"=> "404",
							"msg" => "Proses Tabungan Gagal",
							"data"=> ""
						);
				echo json_encode($array);
				
			} 
		}
	}
	
	function getPending()
	{
		if($this->input->post())
		{
			//var_dump($_POST);
			$iduser = $this->input->post('iduser');
			
			$ceklst = $this->dbasemodel->loadsql("SELECT * FROM (
			(SELECT SUM(NOMINAL_PINJ) AS ANGRP_PENDING FROM checklist_kolektor WHERE IDKOLEKTOR='$iduser')AS AA,
			(SELECT SUM(NOMINAL_SIMP)  AS TABRP_PENDING FROM checklist_kolektor WHERE IDKOLEKTOR='$iduser') AS BB,
			(SELECT COUNT(IDCEKKOLEKTOR) AS TOTAL_PENDING FROM checklist_kolektor WHERE IDKOLEKTOR='$iduser') AS CC)");
			
			if($ceklst->num_rows()>0)
			{
				$row = $ceklst->row();
				$array = array(
							"code" => "200",
							"msg"  => "Proses Tabungan Gagal",
							"data" => array(
										"ANGRP_PENDING" => number_format($row->ANGRP_PENDING,0,',','.'),
										"TABRP_PENDING" => number_format($row->TABRP_PENDING,0,',','.'),
										"TOTAL_PENDING" => $row->TOTAL_PENDING 
									)
						);
				echo json_encode($array);
			}
			else
			{
				
				$array = array(
							"code" => "200",
							"msg"  => "Proses Tabungan Gagal",
							"data" => array(
										"ANGRP_PENDING"=>"0",
										"TABRP_PENDING"=>"0",
										"TOTAL_PENDING"=>"0" 			
									)
						);
				echo json_encode($array);
			}
			
  
		}
	}
	

}