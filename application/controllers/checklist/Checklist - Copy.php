<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Checklist extends CI_Controller {

	function __construct(){ 
        parent::__construct();
		$this->load->helper(array('form','url', 'xml','text_helper','date','inflector','app'));
		$this->load->database();
		$this->load->library(array('Pagination','user_agent','session','form_validation','session'));
		$this->load->model('dbasemodel');
		ini_set("memory_limit", "-1");
		set_time_limit(0);
    }
	
	public function index()
	{
		if(!is_logged_in()){
			redirect('/auth_login');	
		}
        $data['PAGE_TITLE']     = "Checklist Teller";
        $data['page']           = "checklist/checklist_teller";
        $data['response']       = '';
		
		if(isset($_GET['tgl']))
		{
			$tgl = date("Y-m-d", strtotime($_GET['tgl']));
			$wheretrgl = "AND DATE(A.TGL_AWAL)='".$tgl."'";
		}else{
			$wheretrgl = "AND DATE(A.TGL_AWAL)='".date("Y-m-d")."'";
		}
		
		$koncabang = ($this->session->userdata('wad_cabang')!="")? " AND A.KODECABANG='".$this->session->userdata('wad_cabang')."'":"";

		$data['query']			= $this->dbasemodel->loadsql("SELECT A.IDCEKTELLER,
																  DATE_FORMAT(A.TGL_AWAL,'%d/%m/%Y') as TGL_AWAL,
																  A.NOMINAL_SIMP,
																  A.NOMINAL_PINJ,
																  A.BUKTI,
																  B.NAMA AS CABANG
																  FROM checklist_teller A
																  LEFT JOIN m_cabang B ON A.KODECABANG=B.KODE
															 WHERE A.STATUS='0' $koncabang $wheretrgl ORDER BY TGL_AWAL ASC ");

        $this->load->view('dashboard',$data);
    }
	
	function uploadteller()
	{
		if(!is_logged_in()){
			redirect('/auth_login');	
		}
		if($this->input->post())
		{
			//var_dump($_POST);
			$mydir	= $this->session->userdata('wad_kodepusat')."_".$this->session->userdata('wad_kodecabang')."_".date("Ymd");
			$path 	= './uploads/bukti/'.$mydir;
			if (!file_exists($path)) {
				mkdir($path, 0755, true);
			}
			$id 						= $this->input->post("id");
			//$config['encrypt_name'] 	= TRUE;
			$config['upload_path'] 		= $path.'/';
			$config['allowed_types'] 	= 'gif|jpg|png|jpeg';
			$config['max_size'] 		= 2000;
			$new_name 					= time()."_".$_FILES["buktiteller".$id]['name'];
			$config['file_name'] 		= $new_name;

			$this->load->library('upload', $config);

			if (!$this->upload->do_upload("buktiteller".$id)) {
				//$error = array('error' => $this->upload->display_errors());
				echo "99|".$this->upload->display_errors();
			} else {
				$data = array('upload_data' => $this->upload->data());
				
				$where  = "IDCEKTELLER = '". $id."' ";
				$datacheclist = array("BUKTI"=>$mydir."/".$data['upload_data']['file_name']);
				$this->dbasemodel->updateData("checklist_teller", $datacheclist, $where);
				
				
				echo "00|".$mydir."/".$data['upload_data']['file_name'];
			}
			
		}
		
	}
	
	function confirmteller()
	{
		if(!is_logged_in()){
			redirect('/auth_login');	
		}
		$id = $this->input->post('id');
		$cek = $this->dbasemodel->loadsql("SELECT * FROM checklist_teller WHERE IDCEKTELLER='$id'");
		if($cek->num_rows()>0)
		{
			$res =  $cek->row();
			$cus = $this->dbasemodel->loadsql("SELECT * FROM transaksi_simp WHERE UPDATE_DATA='0000-00-00 00:00:00' 
													AND STATUS='0' ");
			if($cus->num_rows()>0){
				foreach($cus->result() as $key){
					//echo $key->ID_TRX_SIMP."<br>";
					
					$insertsimp4 = array('TGL'=> $key->TGL_TRX,
						'DEBET'		=> $key->JUMLAH,
						'KREDIT'	=> "0",
						'UNTUK_KAS'	=> $key->ID_KAS,
						'TRANSAKSI' => $key->ID_JENIS,
						'USER'		=> $key->USERNAME,
						'ID_TRX_SIMP'	=> $key->ID_TRX_SIMP,
						'KODEPUSAT'		=> $key->KODEPUSAT,
						'KODECABANG'	=> $key->KODECABANG);
					$this->dbasemodel->insertData('v_transaksi', $insertsimp4);
					
					$ceklst			=	$this->dbasemodel->loadsql("Select * FROM m_anggota_simp WHERE IDANGGOTA='".$key->ID_ANGGOTA."' AND IDJENIS_SIMP='".$key->ID_JENIS."'");
					if($ceklst->num_rows()>0)
					{
						
						$rchek	= $ceklst->row();
						$nom 	= $rchek->SALDO+$key->JUMLAH;
						$where  = "ID_ANG_SIMP = '". $key->ID_ANGGOTA."'";
						$datacheclist = array("SALDO"=>$nom);
						$this->dbasemodel->updateData("m_anggota_simp", $datacheclist, $where);
						
					}else{
						
						$tglreg	=	date("Y-m-d", strtotime($key->TGL_TRX));
						$datacheclist = array("IDANGGOTA"=>$key->ID_ANGGOTA,
											"IDJENIS_SIMP"=>$key->ID_JENIS,
											"SALDO"=>$key->JUMLAH,
											"TGLREG"=>$tglreg);
						$this->dbasemodel->insertData("m_anggota_simp",$datacheclist);
					}
					
					$wheresimp  = "ID_TRX_SIMP = '". $key->ID_TRX_SIMP."'";
					$updatesimp = array("UPDATE_DATA"=>date("Y-m-d H:i:s"), "STATUS"=>"1");
					$this->dbasemodel->updateData("transaksi_simp", $updatesimp, $wheresimp);
					
					$wanggota  = "IDANGGOTA = '". $key->ID_ANGGOTA."'";
					$uanggota = array("AKTIF"=>"Y");
					$this->dbasemodel->updateData("m_anggota", $uanggota, $wanggota);	
				}
			}
			
			# ANGSURAN
			/* $sql	=	"SELECT A.*, B.ANGGOTA_ID, B.PINJ_SISA, B.LUNAS
						 FROM tbl_pinjaman_d A 
						 LEFT JOIN
						 	tbl_pinjaman_h B ON A.IDPINJAM = B.IDPINJM_H
						 LEFT JOIN
						 	m_anggota C ON B.ANGGOTA_ID = C.IDANGGOTA
						 WHERE A.UPDATE_DATA = '0000-00-00 00:00:00' 
							 AND DATE(A.TGL_BAYAR)='".$res->TGL_AWAL."' 
							 AND A.STATUS='0' 
							 AND B.KODEPUSAT='".$res->KODEPUSAT."' 
							 AND B.KODECABANG='".$res->KODECABANG."'"; */
			
			$sql	=	"SELECT A.*, B.ANGGOTA_ID, B.PINJ_SISA, B.LUNAS
						 FROM tbl_pinjaman_d A 
						 LEFT JOIN
						 	tbl_pinjaman_h B ON A.IDPINJAM = B.IDPINJM_H
						 LEFT JOIN
						 	m_anggota C ON B.ANGGOTA_ID = C.IDANGGOTA
						 WHERE A.UPDATE_DATA = '0000-00-00 00:00:00' 
							 AND A.STATUS='0' ";
			$query	=	$this->dbasemodel->loadsql($sql);
			
			if($query->num_rows()>0){
				foreach($query->result() as $key) {
					$save = array('TGL'		=> $key->TGL_BAYAR,
								'DEBET'		=> $key->POKOKBAYAR,
								'KREDIT'	=> "0",
								'UNTUK_KAS'	=> $key->KAS_ID,
								'TRANSAKSI' => $key->JENIS_TRANS,
								'USER'		=> $key->USERNAME,
								'KODEPUSAT'		=> $res->KODEPUSAT,
								'KODECABANG'	=> $res->KODECABANG);
					$this->dbasemodel->insertData('v_transaksi', $save);
					
					$save = array('TGL'		=> $key->TGL_BAYAR,
								'DEBET'		=> $key->BASILBAYAR,
								'KREDIT'	=> "0",
								'UNTUK_KAS'	=> $key->KAS_ID,
								'TRANSAKSI' => sukubunga('pendapatan_mudharabah'),
								'USER'		=> $key->USERNAME,
								'KODEPUSAT'		=> $res->KODEPUSAT,
								'KODECABANG'	=> $res->KODECABANG);
					$this->dbasemodel->insertData('v_transaksi', $save);
					
					$status_lunas	=	($key->PINJ_SISA - $key->JUMLAH_BAYAR) <= 0 ? 'Lunas' : 'Belum';
					
					$sql	=	sprintf("UPDATE tbl_pinjaman_h 
										 SET 
											PINJ_DIBAYAR = (PINJ_DIBAYAR + %s),
											PINJ_SISA = (PINJ_SISA - %s),
											PINJ_POKOK_DIBAYAR = (PINJ_POKOK_DIBAYAR + %s),
											PINJ_POKOK_SISA = (PINJ_POKOK_SISA - %s),
											UPDATE_DATA = NOW(),
											LUNAS = '%s',
											PINJ_BASIL_BAYAR = (PINJ_BASIL_BAYAR + %s)
										 WHERE 
											IDPINJM_H = %s ", 
										 $key->JUMLAH_BAYAR,
										 $key->JUMLAH_BAYAR,
										 $key->POKOKBAYAR,
										 $key->POKOKBAYAR,
										 $status_lunas,
										 $key->BASILBAYAR,
										 $key->IDPINJAM
									);
					$this->dbasemodel->loadSql($sql);
					
					$sql	=	sprintf("SELECT ANGSURAN_KE 
										 FROM tbl_pinjaman_d
										 WHERE IDPINJAM = %s
										 ORDER BY ANGSURAN_KE DESC
										 LIMIT 1", $key->IDPINJAM);
					$query	=	$this->dbasemodel->loadsql($sql);
					$angsuranke	=	1;
					if($query->num_rows() > 0) {
						$row	=	$query->row();
						$angsuranke	=	($row->ANGSURAN_KE + 1);
					}
										 
					$sql	=	sprintf("UPDATE tbl_pinjaman_d 
										 SET 
											UPDATE_DATA = NOW(), 
											STATUS = 1 ,
											ANGSURAN_KE = %s
										 WHERE IDPINJ_D = %s ", 
										 $angsuranke,
										 $key->IDPINJ_D
										 );


					$this->dbasemodel->loadsql($sql);
					
					if($key->LUNAS === 'Belum') {
						$sql	=	sprintf("UPDATE m_anggota 
											 SET 
												PINJ_DIBAYAR = (PINJ_DIBAYAR + %s),
												PINJ_SISA = (PINJ_SISA - %s),
												ISCREDIT = '%s',
												PINJ_POKOK = %s,
												PINJ_TOTAL = %s,
												PINJ_RP_ANGSURAN = %s,
												PINJ_BASIL_DASAR = %s,
												PINJ_BASIL_BAYAR = (PINJ_BASIL_BAYAR + %s),
												PINJ_POKOK_DIBAYAR = (PINJ_POKOK_DIBAYAR + %s),
												PINJ_POKOK_SISA = (PINJ_POKOK_SISA - %s)
											 WHERE 
												IDANGGOTA = %s ", 
											 $key->JUMLAH_BAYAR,
											 $key->JUMLAH_BAYAR,
											 ($status_lunas == 'Lunas' ? 0 : 1), 
											 ($status_lunas == 'Lunas' ? 0 : 'PINJ_POKOK'),
											 ($status_lunas == 'Lunas' ? 0 : 'PINJ_TOTAL'),
											 ($status_lunas == 'Lunas' ? 0 : 'PINJ_RP_ANGSURAN'),
											 ($status_lunas == 'Lunas' ? 0 : 'PINJ_BASIL_DASAR'),
											 $key->BASILBAYAR,
											 $key->POKOKBAYAR,
											 $key->POKOKBAYAR,
											 $key->ANGGOTA_ID
										);
						$this->dbasemodel->loadSql($sql);
					}
					/* $sql	=	sprintf("UPDATE m_anggota SET ISCREDIT = '%s' WHERE IDANGGOTA = %s ", ($status_lunas == 'Lunas' ? 0 : 1), $key->ANGGOTA_ID);
					$this->dbasemodel->loadsql($sql); */
				}
			}
			echo "ok";
			
			$sql	=	sprintf("UPDATE 
									checklist_teller 
								 SET
									AKUMULASI_SIMP = (AKUMULASI_SIMP + NOMINAL_SIMP),
									AKUMULASI_PINJ = (AKUMULASI_PINJ + NOMINAL_PINJ),
									NOMINAL_SIMP = 0, NOMINAL_PINJ = 0, APPROVAL = '%s', STATUS = 1
								 WHERE
									IDCEKTELLER = '%s' ", $this->session->userdata('wad_id'), $id);
			$this->dbasemodel->loadSql($sql);
					
			/*$wherecls  	= "IDCEKTELLER = '".$id."'";
			$cls 		= array("NOMINAL_SIMP"	=>	0,
								"NOMINAL_PINJ"	=>	0,
								"APPROVAL"		=>	0, #$this->session->userdata('wad_id'),
								"STATUS"		=>	0);
			$this->dbasemodel->updateData("checklist_teller", $cls, $wherecls);*/
			
		}else{
			echo "error";
		}
	}
	
	function detailceklis()
	{
		if(!is_logged_in()){
			redirect('/auth_login');	
		}
		
		$cek = $this->dbasemodel->loadsql("SELECT * FROM checklist_teller WHERE IDCEKTELLER='".$this->uri->segment(3)."'");
		if($cek->num_rows()>0){
			$res =  $cek->row();
		
			$data['opt_data_entries']	=	$this->load->view('_elements/data_entries', NULL, TRUE);
			$data['table_footer']		=	$this->load->view('_elements/table_footer', NULL, TRUE);
			$data['PAGE_TITLE']     = "Checklist Setoran";
			$data['page']           = "checklist/checklist_detail";
			$data['tgl']     		= $res->TGL_AWAL;
			$this->load->view('dashboard',$data);
		}else{
			redirect('/cheklist-teller');
		}
		
	}
	
	function detaildata()
	{
		if(!is_logged_in()){
			redirect('/auth_login');	
		}
		/*if($this->input->post('tgl'))
		{
			$tgl = date("Y-m-d", strtotime($this->input->post('tgl')));
			$wheretrgl = "AND DATE(TGL_TRX)='".$tgl."'";
		}else{
			$wheretrgl = "AND DATE(TGL_TRX)='".date("Y-m-d")."'";
		}*/
		
		
		$koncabang = ($this->session->userdata('wad_cabang')!="")? " AND A.KODECABANG='".$this->session->userdata('wad_cabang')."'":"";
		
		$this->load->model('ModelChecklist');
		$keyword		=	null !== $this->input->post('keyword') ? $this->input->post('keyword') : "";
		$dataPerPage	=	$this->input->post('dataperpage');
		$page			=	$this->input->post('page');
		$dataTable		=	$this->ModelChecklist->getDatasimpanan($keyword, $dataPerPage, $page,$koncabang,$this->input->post('tgl'));

        header('Content-Type: application/json');
		echo json_encode($dataTable);
		die();
	}
	
} 