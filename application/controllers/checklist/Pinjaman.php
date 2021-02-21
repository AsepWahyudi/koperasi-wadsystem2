<?php
require APPPATH.'third_party/vendor/autoload.php';    
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

defined('BASEPATH') OR exit('No direct script access allowed');

class Pinjaman extends CI_Controller {

	function __construct(){ 
        parent::__construct();
		$this->load->helper(array('app', 'form','url', 'xml','text_helper','date','inflector'));
		$this->load->database();
		$this->load->library(array('Pagination','user_agent','session','form_validation','session', 'table'));
		$this->load->model(array('dbasemodel', 'ModelVTransaksi'));
		ini_set("memory_limit", "-1");
		set_time_limit(0);
			if(!is_logged_in()){
			redirect('/auth_login');	
		}
    }
	
	public function index(){
		
	 
		// $koncabang = ($this->session->userdata('wad_cabang')!="")? " AND A.KODECABANG='".$this->session->userdata('wad_cabang')."'":"";
		if($this->session->userdata("wad_level") == "admin")
		{
			$koncabang = "";
		}
		else
		{
			$koncabang = " AND A.KODECABANG='".$this->session->userdata('wad_kodecabang')."'";
		}
		$data['query'] = $this->dbasemodel->loadsql("SELECT A.IDPINJM_H,DATE_FORMAT(A.TGL_PINJ,'%d/%m/%Y')AS TGL,
		A.JUMLAH,
		A.BIAYA_ADMIN,
		A.BIAYA_ASURANSI,
		A.FILEBUKTI,
		B.NAMA,
		B.KODEBANK,
		B.NAMA_BANK,
		B.NOREK
		FROM tbl_pinjaman_h A
		LEFT JOIN m_anggota B ON A.ANGGOTA_ID=B.IDANGGOTA
		WHERE A.UPDATE_DATA='0000-00-00 00:00:00' 
		AND ISAPPROVE='0' 
		AND A.STATUS='0' $koncabang");
 
		//$data['opt_data_entries']	=	$this->load->view('_elements/data_entries', NULL, TRUE);
		//$data['table_footer']		=	$this->load->view('_elements/table_footer', NULL, TRUE);
        $data['PAGE_TITLE'] = "Transaksi Pinjaman/Pembiayaan";
        $data['page']       = "checklist/pinjaman";
        $this->load->view('dashboard',$data);
    }
	
	public function datapinjaman(){
		
	 
		$this->load->model('ModelChecklist');
		$keyword     = null !== $this->input->post('keyword') ? $this->input->post('keyword') : "";
		$dataPerPage = $this->input->post('dataperpage');
		$page        = $this->input->post('page');
		$dataTable   = $this->ModelChecklist->getDataTablePinjaman($keyword, $dataPerPage, $page);

        header('Content-Type: application/json');
		echo json_encode($dataTable);
		die();
	}
	
	public function detail(){
		 
		$this->load->model('ChecklistModel');
		$idpinjam            = $this->input->get('id');
		$data['PAGE_TITLE']  = "Permohonan Pengajuan Pembiayaan";
		$data['data_source'] = $this->ChecklistModel->getDataPinjaman($idpinjam);
		$data['page']        = "checklist/pinjaman_detail";
        $this->load->view('dashboard',$data);	
    }
	
	public function approve(){
	 
		$id  = $this->uri->segment(2);
		$sql = sprintf("UPDATE tbl_pinjaman_h SET ISAPPROVE = '1', APPROVAL = '%s' WHERE IDPINJM_H = '%s' ", $this->session->userdata('wad_user'), $id);
		$this->dbasemodel->loadsql($sql);
		$this->session->set_flashdata('ses_checklist', '11||Anda telah berhasil menyetujui pembiayaan tersebut.');
		redirect(base_url() . 'list-pengajuan-pinjaman');
    }
	
	public function tolak(){
		if(!is_logged_in()){
			redirect('/auth_login');	
		}
		$id		=	$this->uri->segment(2);
		$idagt	=	$this->uri->segment(3);
		$sql	=	sprintf("UPDATE tbl_pinjaman_h SET ISAPPROVE = '-1', APPROVAL = '%s' WHERE IDPINJM_H = '%s' ", $id, $this->session->userdata('wad_user'));
		$this->dbasemodel->loadsql($sql);
		
		$sql = sprintf("UPDATE m_anggota 
						SET PINJ_POKOK = 0,
						PINJ_TOTAL = 0,
						PINJ_DIBAYAR = 0,
						PINJ_SISA = 0,
						PINJ_RP_ANGSURAN = 0, 
						PINJ_BASIL_DASAR = 0
						WHERE IDANGGOTA = '%s' ", $idagt);
		$this->dbasemodel->loadsql($sql);
		
		$this->session->set_flashdata('ses_checklist', '11||Anda telah menolak permohonan pembiayaan.');
		redirect(base_url() . 'list-pengajuan-pinjaman');
    }
	
	public function uploadbukti(){
		 
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
			$new_name 					= "bukticair".time();
			$config['file_name'] 		= $new_name;

			$this->load->library('upload', $config);

			if (!$this->upload->do_upload("bukticair".$id)) {
				//$error = array('error' => $this->upload->display_errors());
				echo "99|".$this->upload->display_errors();
			} else {
				$data = array('upload_data' => $this->upload->data());
				
				$where  = "IDPINJM_H = '". $id."' "; 
				$datacheclist = array("FILEBUKTI"=>$mydir."/".$data['upload_data']['file_name']);
				$this->dbasemodel->updateData("tbl_pinjaman_h", $datacheclist, $where);
				
				
				echo "00|".$mydir."/".$data['upload_data']['file_name'];
			}
			
		}
	}
	public function pembulatan($uang){
		$ratusan = substr($uang, -2);
		$akhir = $uang + (100-$ratusan);
		return $akhir;
		 
	}
	public function konfirmasi(){ 
		
		$this->load->model('ChecklistModel'); 
		$proses = 1;
		if($this->input->post())
		{
			$cek = $this->dbasemodel->loadsql("SELECT * FROM tbl_pinjaman_h WHERE UPDATE_DATA = '0000-00-00 00:00:00' AND IDPINJM_H = '". $this->input->post('id') ."' ");
			
			if($cek->num_rows() > 0)
			{
				$row = $cek->row();
				//foreach($cek->result() as $row) {
					$insert = array('TGL'	      => date("Y-m-d"),
								    'JUMLAH'	  => $row->JUMLAH,
								    'AKUN'		  => "Pengeluaran",
								    'DARI_KAS_ID' => $row->KAS_ID,
								    'JENIS_TRANS' => $row->JNS_TRANS,
								    'USERNAME'    => $this->session->userdata('wad_user'),
								    'DK'          => "K",
								    'KODEPUSAT'   => $row->KODEPUSAT,
								    'KODECABANG'  => $row->KODECABANG);
					$this->dbasemodel->insertData('transaksi_kas', $insert);
					
					$trx_kas = $this->db->insert_id();
					/* Insert jumlah pinjaman ke jurnal transaksi(table vtransaksi) */
					$datatransaksi = array('tgl'        => date("Y-m-d H:i:s"),
										   'jumlah'     => $row->JUMLAH, 
										   'keterangan' => 'Pencairan pembiayaan('. $row->REKENING .'), sebesar rp '. toRp($row->JUMLAH) .'', 
										   'kodecabang' => $row->KODECABANG,
										   'idkasakun' => $row->KAS_ID);
										   
					$this->ModelVTransaksi->insertVtransaksi($trx_kas, $datatransaksi, 'JT', $row->JNS_TRANS, $row->KAS_ID, 'KAS');
					
					/*$insert2 = array('TGL'	=> date("Y-m-d H:i:s"),
								'DEBET'		=> "0",
								'KREDIT'	=> $row->JUMLAH,
								'DARI_KAS' 	=> $row->KAS_ID,
								'TRANSAKSI'	=> $row->JNS_TRANS,
								'USER'		=> $this->session->userdata('wad_user'),
								'KODEPUSAT'	=> $row->KODEPUSAT,
								'KODECABANG'=> $row->KODECABANG);
					$this->dbasemodel->insertData('v_transaksi', $insert2);*/
					 
					/* Insert biaya admin ke jurnal transaksi(table vtransaksi) */
					$datatransaksi = array('tgl' 		=> date("Y-m-d H:i:s"),
										   'jumlah'     => $row->BIAYA_ADMIN, 
										   'keterangan' => 'Pendapatan Administrasi Pembiayaan(dari '. $row->REKENING .'), sebesar rp '. toRp($row->BIAYA_ADMIN) .'', 
										   'kodecabang' => $row->KODECABANG,'idkasakun' => $row->KAS_ID);
										    
					// $this->ModelVTransaksi->insertVtransaksi($trx_kas, $datatransaksi, 'JT', $row->KAS_ID, sukubunga('admin_pembiayaan'), 'KAS');
					$this->ModelVTransaksi->insertVtransaksi($trx_kas, $datatransaksi, 'ST', $row->KAS_ID, sukubunga('admin_pembiayaan'), 'KAS');
					
					/* Insert biaya asuransi ke jurnal asuransi(table vtransaksi) */
					$datatransaksi = array('tgl'        => date("Y-m-d H:i:s"),
										   'jumlah'     => $row->BIAYA_ASURANSI, 
										   'keterangan' => 'Pendapatan Asuransi Pembiayaan(dari '. $row->REKENING .'), sebesar rp '. toRp($row->BIAYA_ASURANSI) .'', 
										   'kodecabang' => $row->KODECABANG,'idkasakun' => $row->KAS_ID);
											
					// $this->ModelVTransaksi->insertVtransaksi($trx_kas, $datatransaksi, 'JT', $row->KAS_ID, sukubunga('asuransi_pembiayaan'), 'KAS');
					$this->ModelVTransaksi->insertVtransaksi($trx_kas, $datatransaksi, 'ST', $row->KAS_ID, sukubunga('asuransi_pembiayaan'), 'KAS');
					
					/*$insert3 = array('TGL'	=> date("Y-m-d H:i:s"),
								'DEBET'		=> $row->BIAYA_ADMIN,
								'KREDIT'	=> 0,
								'UNTUK_KAS' => $row->KAS_ID,
								'TRANSAKSI'	=> sukubunga('admin_pembiayaan'),
								'USER'		=> $this->session->userdata('wad_user'),
								'KODEPUSAT'	=> $row->KODEPUSAT,
								'KODECABANG'=> $row->KODECABANG);
					$this->dbasemodel->insertData('v_transaksi', $insert3);*/
								
					//if($row->LUNAS === 'Belum') {
						
					$datapinjam = $this->ChecklistModel->getDataPinjaman($this->input->post('id'))->row(); 
					  
					$ang_dasar = ROUND((int)$datapinjam->JUMLAH /(int)$datapinjam->LAMA_ANGSURAN);
					$bas_dasar = ROUND((((int)$datapinjam->JUMLAH * $datapinjam->BUNGA) / 100) / (int)$datapinjam->LAMA_ANGSURAN);
					 
					$angdasar     = $this->pembulatan($ang_dasar); 
					$basdasar     = $this->pembulatan($bas_dasar);
					 
					$tambahangbas = (int)$angdasar+(int)$basdasar;
					
					$tot_jml_ang = $tambahangbas*(int)$datapinjam->LAMA_ANGSURAN;
					
					// $tot_jml_ang = 0;
					// for($i = 1; $i <= $row->LAMA_ANGSURAN; $i++) {
						
						// $angdasar     = $this->pembulatan($ang_dasar);
						// $basdasar     = $this->pembulatan($bas_dasar);
						// $tambahangbas = (int)$angdasar+(int)$basdasar;
						 
						// $tot_jml_ang += $tambahangbas;
					// }
							
					$update	= array('ISCREDIT'	=>	1, 
									  'PINJ_POKOK'	=> $row->JUMLAH,
									  'PINJ_TOTAL' 	=> $row->PINJ_TOTAL,
									  'PINJ_SISA'	=> $row->PINJ_TOTAL,
									  'PINJ_RP_ANGSURAN' => $tot_jml_ang,
									  'PINJ_BASIL_DASAR' => $row->PINJ_BASIL_DASAR,
									  'PINJ_BASIL_TOTAL' => $row->PINJ_BASIL_TOTAL
									);
					$this->dbasemodel->updateData('m_anggota', $update, "IDANGGOTA = '". $row->ANGGOTA_ID ."' ");
					//}
					
					$update	= array(  'ISAPPROVE' => 1, 
								      'APPROVAL' => $this->session->userdata('wad_user'),
								      'UPDATE_DATA'	=> date('Y-m-d H:i:s'),
									  // 'PINJ_SISA'	=> $row->PINJ_TOTAL,
									  'PINJ_SISA' => $tot_jml_ang,
									  'ANGSURAN_DASAR' => $angdasar,
									  'PINJ_RP_ANGSURAN' => $tot_jml_ang,
									  'PINJ_POKOK_SISA' => $row->JUMLAH
									 );
					$this->dbasemodel->updateData('tbl_pinjaman_h', $update, "IDPINJM_H = '". $row->IDPINJM_H ."' ");
				//}
				
				$this->session->set_flashdata('ses_checklist', '11||Anda telah berhasil menyetujui pembiayaan tersebut.');
				echo "ok";
			}
		}
	}

	function excel(){
		if(!is_logged_in()){
			redirect('/auth_login');	
		}
		
		$spreadsheet = new Spreadsheet();
		$spreadsheet->getActiveSheet()->setTitle('Data Pengajuan Pinjaman');
		$sheet = $spreadsheet->getActiveSheet();
		
		$sheet->setCellValue('A1', 'TANGGAL');
		$sheet->setCellValue('B1', 'NAMA NASABAH');
		$sheet->setCellValue('C1', 'ALAMAT');
		$sheet->setCellValue('D1', 'JUMLAH PINJAMAN');
		$sheet->setCellValue('E1', 'DITERIMA NASABAH');
		$sheet->setCellValue('F1', 'JENIS PINJAMAN');
		
		foreach(range('A','F') as $columnID)
		{
			$sheet->getColumnDimension($columnID)->setAutoSize(true);
		}
		
		$sheet->getStyle('A1:F1')->applyFromArray(
		   array(
			  'font'  => array(
				  'bold'  =>  true
			  )
		   )
		);

		// $koncabang = ($this->session->userdata('wad_cabang')!="")? " AND A.KODECABANG='".$this->session->userdata('wad_cabang')."'":"";
		if($this->session->userdata("wad_level") == "admin")
		{
			$koncabang = "";
		}
		else
		{
			$koncabang = " AND A.KODECABANG='".$this->session->userdata('wad_kodecabang')."'";
		}
		$cek		= $this->dbasemodel->loadsql("SELECT 
								A.IDPINJM_H, A.TGL_PINJ,
								A.LAMA_ANGSURAN,
								A.USERNAME, A.BUNGA,
								B.NAMA NAMA_ANGGOTA, B.ALAMAT,
								B.FILE_PIC, B.IDANGGOTA,
								C.JNS_PINJ, A.JUMLAH, A.BIAYA_ADMIN,
								A.BIAYA_ASURANSI, A.NAMA_SDR, A.HUB_SDR, A.TELP_SDR, A.ALAMAT_SDR,
								FORMAT((A.JUMLAH/A.LAMA_ANGSURAN),0) ANGSURAN_DASAR,
								FORMAT(B.PINJ_BASIL_DASAR,0) BASIL_DASAR
							 FROM
							 	tbl_pinjaman_h A
							 LEFT JOIN
							 	m_anggota B ON A.ANGGOTA_ID = B.IDANGGOTA
							 LEFT JOIN
								jns_pinjm C ON A.BARANG_ID = C.IDAKUN
							 WHERE ISAPPROVE = 0 $koncabang ");

		
								
		$row = 2;
		if($cek->num_rows() > 0){ $n = 1;
		
			foreach($cek->result() as $item){ 
				$sheet->setCellValue('A'.$row,$item->TGL_PINJ);
				$sheet->setCellValue('B'.$row,$item->NAMA_ANGGOTA);
				$sheet->setCellValue('C'.$row,$item->ALAMAT);
				$sheet->setCellValue('D'.$row,$item->JUMLAH);
				$sheet->getStyle('D'.$row)->getNumberFormat()->setFormatCode('#,##0');
				$sheet->setCellValue('E'.$row,$item->JUMLAH-$item->BIAYA_ADMIN-$item->BIAYA_ADMIN);
				$sheet->getStyle('E'.$row)->getNumberFormat()->setFormatCode('#,##0');
				$sheet->setCellValue('F'.$row,$item->JNS_PINJ);
				$row++;
			} 
			
		}
		
		$writer = new Xlsx($spreadsheet);
		$file = "pengajuan_pinjaman_".date("ymdHis").".xlsx";
		$writer->save('export/'.$file);
		redirect(base_url().'export/'.$file);
		
	}	
}