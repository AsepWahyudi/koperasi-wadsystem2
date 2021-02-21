<?php
require APPPATH.'third_party/vendor/autoload.php';    
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

defined('BASEPATH') OR exit('No direct script access allowed');

class Ceklist_kolektor extends CI_Controller {

	function __construct(){ 
        parent::__construct();
		$this->load->helper(array('form','url', 'xml','text_helper','date','inflector','app'));
		$this->load->database();
		$this->load->library(array('Pagination','user_agent','session','form_validation','session'));
		$this->load->model(array('dbasemodel','ModelVTransaksi'));
		//@session_start();
		if(!is_logged_in()){
			redirect('/auth_login');	
		}
    }
	
	public function index()
	{
		
        $data['PAGE_TITLE'] = "Checklist Kolektor";
        $data['page']       = "checklist/checklist_kolektor";
        $data['response']   = '';
		
		if(isset($_GET['tgl']))
		{
			$tgl = date("Y-m-d", strtotime($_GET['tgl']));
			$wheretrgl = "AND DATE(A.TGL_AWAL)='".$tgl."'";
		}else{
			$wheretrgl = "AND DATE(A.TGL_AWAL)='".date("Y-m-d")."'";
		}
		
		// $koncabang = ($this->session->userdata('wad_cabang')!="")? " AND A.KODECABANG='".$this->session->userdata('wad_cabang')."'":"";
		
		if($this->session->userdata("wad_level") == "admin")
		{
			$koncabang = "";
		}
		else
		{
			$koncabang = " AND A.KODECABANG='".$this->session->userdata('wad_kodecabang')."'";
		}
		$data['query'] = $this->dbasemodel->loadsql("SELECT A.IDCEKKOLEKTOR,
													DATE_FORMAT(A.TGL_AWAL,'%d/%m/%Y') as TGL_AWAL,
													A.NOMINAL_SIMP,
													A.NOMINAL_PINJ,
													A.BUKTI,
													B.NAMA AS CABANG,
													C.NAMA
													FROM checklist_kolektor A
													LEFT JOIN m_cabang B ON A.KODECABANG=B.KODE
													LEFT JOIN m_user C ON A.IDKOLEKTOR=C.IDUSER
													WHERE A.STATUS='0' $koncabang $wheretrgl ORDER BY TGL_AWAL ASC ");

        $this->load->view('dashboard',$data);
    }
	
	public function uploadkolektor()
	{
		 
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
			$config['allowed_types'] 	= 'gif|jpg|png';
			$config['max_size'] 		= 2000;
			$new_name 					= time()."_".$_FILES["buktikolektor".$id]['name'];
			$config['file_name'] 		= $new_name;

			$this->load->library('upload', $config);

			if (!$this->upload->do_upload("buktikolektor".$id)) {
				//$error = array('error' => $this->upload->display_errors());
				echo "99|".$this->upload->display_errors();
			} else {
				$data = array('upload_data' => $this->upload->data());
				
				$where  = "IDCEKKOLEKTOR = '". $id."' ";
				$datacheclist = array("BUKTI"=>$mydir."/".$data['upload_data']['file_name']);
				$this->dbasemodel->updateData("checklist_kolektor", $datacheclist, $where);
				
				
				echo "00|".$mydir."/".$data['upload_data']['file_name'];
			}
			
		}
		
	}
	
	public function confirmkoletor()
	{
		 
		if($this->input->post())
		{
			//var_dump($_POST);
			$id = $this->input->post('id');
			$cek = $this->dbasemodel->loadsql("SELECT * FROM checklist_kolektor WHERE IDCEKKOLEKTOR ='$id'");
			if($cek->num_rows()>0)
			{
				$res =  $cek->row();
				$cus = $this->dbasemodel->loadsql("SELECT * FROM transaksi_simp WHERE UPDATE_DATA='0000-00-00 00:00:00' 
													AND date(TGL_TRX)='".$res->TGL_AWAL."' 
													AND STATUS='0' 
													AND IDKOLEKTOR='".$res->IDKOLEKTOR."'
													AND KODEPUSAT='".$res->KODEPUSAT."' 
													AND KODECABANG='".$res->KODECABANG."'");
				if($cus->num_rows()>0)
				{
					foreach($cus->result() as $key)
					{
						$datatransaksi	= array( 
											'tgl' 		=> $key->TGL_TRX, 
											'jumlah' 	=> $key->JUMLAH, 
											'keterangan'=> ($key->KETERANGAN != '' ? $key->KETERANGAN : 'Setoran tunai'), 
											'user' 		=> $key->USERNAME,
											'kodecabang'=> $key->KODECABANG
										);
						$this->ModelVTransaksi->insertVtransaksi($key->ID_TRX_SIMP, $datatransaksi, 'ST', $key->ID_KAS, $key->ID_JENIS, 'SIMP');
					
						$ceklst = $this->dbasemodel->loadsql("Select * FROM m_anggota_simp WHERE IDANGGOTA='".$key->ID_ANGGOTA."' AND IDJENIS_SIMP='".$key->ID_JENIS."'");
						
						if($ceklst->num_rows()>0)
						{
							$rchek	= $ceklst->row();
							$sql	= sprintf("UPDATE m_anggota_simp SET SALDO = (SALDO + %s) WHERE ID_ANG_SIMP = %s ", $key->JUMLAH, $rchek->ID_ANG_SIMP);
							$this->dbasemodel->loadSql($sql);
							
						}
						else
						{
							$datacheclist = array("IDANGGOTA"	=> $key->ID_ANGGOTA,
												"IDJENIS_SIMP"	=> $key->ID_JENIS,
												"SALDO"			=> $key->JUMLAH,
												"TGLREG"		=> date("Y-m-d", strtotime($key->TGL_TRX)) );
							$this->dbasemodel->insertData("m_anggota_simp",$datacheclist);
						}
						
						$wheresimp  = "ID_TRX_SIMP = '". $key->ID_TRX_SIMP."'";
						$updatesimp = array("UPDATE_DATA"=>date("Y-m-d H:i:s"),"STATUS"=>"1");
						$this->dbasemodel->updateData("transaksi_simp", $updatesimp, $wheresimp);
						
						$wanggota  = "IDANGGOTA = '". $key->ID_ANGGOTA."'";
						$uanggota = array("AKTIF"=>"Y");
						$this->dbasemodel->updateData("m_anggota", $uanggota, $wanggota);
						
					} 
				}
				
				/* $wherecls  = "IDCEKKOLEKTOR = '".$id."'";
				$cls = array("APPROVAL"=>$this->session->userdata('wad_id'),"STATUS"=>"1");
				$this->dbasemodel->updateData("checklist_kolektor", $cls, $wherecls); */
				
				
				# ANGSURAN
				$sql = "SELECT A.*, 
				B.ANGGOTA_ID, B.PINJ_SISA, B.LUNAS, B.REKENING
				FROM tbl_pinjaman_d A 
				LEFT JOIN
				tbl_pinjaman_h B ON A.IDPINJAM = B.IDPINJM_H
				WHERE 
				A.UPDATE_DATA = '0000-00-00 00:00:00' 
				AND DATE(A.TGL_BAYAR)='".$res->TGL_AWAL."' 
				AND A.STATUS='0' 
				AND B.KODEPUSAT='".$res->KODEPUSAT."' 
				AND B.KODECABANG='".$res->KODECABANG."'";
				
				$query = $this->dbasemodel->loadsql($sql);
				
				if($query->num_rows()>0)
				{
					foreach($query->result() as $key) 
					{
						/* Insert pembayaran/angsuran pokok ke jurnal transaksi(table vtransaksi) */
						$datatransaksi = array(
											'tgl'        => $key->TGL_BAYAR,
											'jumlah'     => $key->POKOKBAYAR, 
											'keterangan' => 'Angsuran ke '.$key->ANGSURAN_KE.' PYD Mudharabah(pokok), No Rek : '. $key->REKENING, 
											'user'       => $key->USERNAME,
											'kodecabang' => $res->KODECABANG
										);
						if($key->POKOKBAYAR != 0) 
						{
							$this->ModelVTransaksi->insertVtransaksi($key->IDPINJ_D, $datatransaksi, 'JT', $key->KAS_ID, $key->JENIS_TRANS, 'PINJ');
						}
						
						/* Insert pembayaran/angsuran pokok ke jurnal transaksi(table vtransaksi) */
						$datatransaksi = array(
											'tgl'        => $key->TGL_BAYAR,
											'jumlah'     => $key->BASILBAYAR, 
											'keterangan' => 'Pendapatan Mudharabah dari No Rek : '. $key->REKENING, 
											'user'       => $key->USERNAME,
											'kodecabang' => $res->KODECABANG
										);
										
						if($key->BASILBAYAR != 0) 
						{
							$this->ModelVTransaksi->insertVtransaksi($key->IDPINJ_D, $datatransaksi, 'JT', $key->KAS_ID, sukubunga('pendapatan_mudharabah'), 'PINJ');
						}
						
						$status_lunas = ($key->PINJ_SISA - $key->JUMLAH_BAYAR) <= 0 ? 'Lunas' : 'Belum';
						
						$sql = sprintf("UPDATE tbl_pinjaman_h 
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
					 
						$sql = sprintf("UPDATE tbl_pinjaman_d 
										SET 
										UPDATE_DATA = NOW(), 
										STATUS = 1
										WHERE IDPINJ_D = %s ", 
										$key->IDPINJ_D
									);
						$this->dbasemodel->loadsql($sql);
						 
						$sql = sprintf("UPDATE m_anggota 
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
				}
					
				$sql = sprintf("UPDATE 
								checklist_kolektor 
								SET
								NOMINAL_SIMP = 0, NOMINAL_PINJ = 0, APPROVAL = '%s', STATUS = 1
								WHERE
								IDCEKKOLEKTOR = '%s' ", 
								$this->session->userdata('wad_id'), $id
							);
				$this->dbasemodel->loadSql($sql);
			
				echo "ok";
			}
			else
			{
				echo "error";
			}
		}
	}
	
	function detailceklis()
	{
		 
		$cek = $this->dbasemodel->loadsql("SELECT * FROM checklist_kolektor WHERE IDCEKKOLEKTOR='".$this->uri->segment(3)."'");
		
		if($cek->num_rows()>0)
		{
			$res                      = $cek->row(); 
			$data['opt_data_entries'] = $this->load->view('_elements/data_entries', NULL, TRUE);
			$data['table_footer']     = $this->load->view('_elements/table_footer', NULL, TRUE);
			$data['PAGE_TITLE']       = "Checklist Setoran";
			$data['page']             = "checklist/checklist_detail_kolektor";
			$data['tgl']              = $res->TGL_AWAL;
			$this->load->view('dashboard',$data);
		}
		else
		{
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
		
		if($this->session->userdata("wad_level") == "admin")
		{
			$koncabang = "";
		}
		else
		{
			$koncabang = " AND A.KODECABANG='".$this->session->userdata('wad_kodecabang')."'";
		}
		 
		$this->load->model('ModelChecklist');
		$keyword     = null !== $this->input->post('keyword') ? $this->input->post('keyword') : "";
		$dataPerPage = $this->input->post('dataperpage');
		$page        = $this->input->post('page');
		$dataTable   = $this->ModelChecklist->getDatasimpkolektor($keyword, $dataPerPage, $page,$koncabang,$this->input->post('tgl'));

        header('Content-Type: application/json');
		echo json_encode($dataTable);
		die();
	}

	function excel()
	{
		if(!is_logged_in()){
			redirect('/auth_login');	
		}
		
		$spreadsheet = new Spreadsheet();
		$spreadsheet->getActiveSheet()->setTitle('Checklist Kolektor');
		$sheet = $spreadsheet->getActiveSheet();
		
		$sheet->setCellValue('A1', 'TANGGAL');
		$sheet->setCellValue('B1', 'NOMINAL SIMPANAN');
		$sheet->setCellValue('C1', 'NOMINAL PINJAMAN');
		$sheet->setCellValue('D1', 'CABANG');
		$sheet->setCellValue('E1', 'NAMA');
		
		foreach(range('A','E') as $columnID)
		{
			$sheet->getColumnDimension($columnID)->setAutoSize(true);
		}
		
		$sheet->getStyle('A1:E1')->applyFromArray(
		   array(
			  'font'  => array(
				  'bold'  =>  true
			  )
		   )
		);

		if(isset($_GET['tgl']))
		{
			$tgl = date("Y-m-d", strtotime($_GET['tgl']));
			$wheretrgl = "AND DATE(A.TGL_AWAL)='".$tgl."'";
		}else{
			$wheretrgl = "AND DATE(A.TGL_AWAL)='".date("Y-m-d")."'";
		}
		
		// $koncabang = ($this->session->userdata('wad_cabang')!="")? " AND A.KODECABANG='".$this->session->userdata('wad_cabang')."'":"";
		if($this->session->userdata("wad_level") == "admin")
		{
			$koncabang = "";
		}
		else
		{
			$koncabang = " AND A.KODECABANG='".$this->session->userdata('wad_kodecabang')."'";
		}
		$cek = $this->dbasemodel->loadsql("SELECT A.IDCEKKOLEKTOR,
										  DATE_FORMAT(A.TGL_AWAL,'%d/%m/%Y') as TGL_AWAL,
										  A.NOMINAL_SIMP,
										  A.NOMINAL_PINJ,
										  A.BUKTI,
										  B.NAMA AS CABANG,
										  C.NAMA
										  FROM checklist_kolektor A
										  LEFT JOIN m_cabang B ON A.KODECABANG=B.KODE
										  LEFT JOIN m_user C ON A.IDKOLEKTOR=C.IDUSER
										  WHERE A.STATUS='0' $koncabang $wheretrgl ORDER BY TGL_AWAL ASC ");
								
		$row = 2;
		if($cek->num_rows() > 0)
		{ 
			
			$n = 1;
		
			foreach($cek->result() as $item)
			{ 
				$sheet->setCellValue('A'.$row,$item->TGL_AWAL);
				$sheet->setCellValue('B'.$row,$item->NOMINAL_SIMP);
				$sheet->getStyle('B'.$row)->getNumberFormat()->setFormatCode('#,##0');
				$sheet->setCellValue('C'.$row,$item->NOMINAL_PINJ);
				$sheet->getStyle('C'.$row)->getNumberFormat()->setFormatCode('#,##0');
				$sheet->setCellValue('D'.$row,$item->CABANG);
				$sheet->setCellValue('E'.$row,$item->BUKTI);
				$row++;
			} 
			
		}
		
		$writer = new Xlsx($spreadsheet);
		$file = "checklist_kolektor".date("ymdHis").".xlsx";
		$writer->save('export/'.$file);
		redirect(base_url().'export/'.$file);
		
	}
	
}