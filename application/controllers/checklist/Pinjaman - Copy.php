<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pinjaman extends CI_Controller {

	function __construct(){ 
        parent::__construct();
		$this->load->helper(array('app', 'form','url', 'xml','text_helper','date','inflector'));
		$this->load->database();
		$this->load->library(array('Pagination','user_agent','session','form_validation','session', 'table'));
		$this->load->model('dbasemodel');
		//@session_start();
    }
	
	public function index(){
		
		if(!is_logged_in()){
			redirect('/auth_login');	
		}
		
		$koncabang = ($this->session->userdata('wad_cabang')!="")? " AND A.KODECABANG='".$this->session->userdata('wad_cabang')."'":"";

		$data['query']			= $this->dbasemodel->loadsql("SELECT   A.IDPINJM_H,DATE_FORMAT(A.TGL_PINJ,'%d/%m/%Y')AS TGL,
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
        $data['PAGE_TITLE']			= 	"Transaksi Pinjaman/Pembiayaan";
        $data['page']				= 	"checklist/pinjaman";
        $this->load->view('dashboard',$data);
    }
	
	public function datapinjaman(){
		if(!is_logged_in()){
			redirect('/auth_login');	
		}
		$this->load->model('ModelChecklist');
		$keyword		=	null !== $this->input->post('keyword') ? $this->input->post('keyword') : "";
		$dataPerPage	=	$this->input->post('dataperpage');
		$page			=	$this->input->post('page');
		$dataTable		=	$this->ModelChecklist->getDataTablePinjaman($keyword, $dataPerPage, $page);

        header('Content-Type: application/json');
		echo json_encode($dataTable);
		die();
	}
	
	public function detail(){
		if(!is_logged_in()){
			redirect('/auth_login');	
		}
		$this->load->model('ModelChecklist');
		$idpinjam		=	$this->input->get('id');
		$data['PAGE_TITLE']     =	"Permohonan Pengajuan Pembiayaan";
		$data['data_source']	=	$this->ModelChecklist->getDataPinjaman($idpinjam);
		$data['page']           =	"checklist/pinjaman_detail";
        $this->load->view('dashboard',$data);	
    }
	
	public function approve(){
		if(!is_logged_in()){
			redirect('/auth_login');	
		}
		$id		=	$this->uri->segment(2);
		$sql	=	sprintf("UPDATE tbl_pinjaman_h SET ISAPPROVE = '1', APPROVAL = '%s' WHERE IDPINJM_H = '%s' ", $this->session->userdata('wad_user'), $id);
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
		
		$sql	=	sprintf("UPDATE m_anggota 
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
	
	function uploadbukti()
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
	
	public function konfirmasi()
	{
		if(!is_logged_in()){
			redirect('/auth_login');	
		}
		$proses = 1;
		if($proses = 1)
		{
			$cek = $this->dbasemodel->loadsql("SELECT * FROM tbl_pinjaman_h ");
			if($cek->num_rows()>0)
			{
				//$row = $cek->row();
				foreach($cek->result() as $row) {
					$insert = array('TGL'	=> date("Y-m-d"),
								'JUMLAH'	=> $row->JUMLAH,
								'AKUN'		=> "Pengeluaran",
								'DARI_KAS_ID' 	=> $row->KAS_ID,
								'JENIS_TRANS'	=> $row->JNS_TRANS,
								'USERNAME'		=> $this->session->userdata('wad_user'),
								'DK'			=> "K",
								'KODEPUSAT'		=> $row->KODEPUSAT,
								'KODECABANG'	=> $row->KODECABANG);
					$this->dbasemodel->insertData('transaksi_kas', $insert);
					
					$insert2 = array('TGL'	=> date("Y-m-d H:i:s"),
								'DEBET'		=> "0",
								'KREDIT'	=> $row->JUMLAH,
								'DARI_KAS' 	=> $row->KAS_ID,
								'TRANSAKSI'	=> $row->JNS_TRANS,
								'USER'		=> $this->session->userdata('wad_user'),
								'KODEPUSAT'	=> $row->KODEPUSAT,
								'KODECABANG'=> $row->KODECABANG);
					$this->dbasemodel->insertData('v_transaksi', $insert2);
					
					
					$insert3 = array('TGL'	=> date("Y-m-d H:i:s"),
								'DEBET'		=> $row->BIAYA_ADMIN,
								'KREDIT'	=> 0,
								'UNTUK_KAS' => $row->KAS_ID,
								'TRANSAKSI'	=> sukubunga('admin_pembiayaan'),
								'USER'		=> $this->session->userdata('wad_user'),
								'KODEPUSAT'	=> $row->KODEPUSAT,
								'KODECABANG'=> $row->KODECABANG);
					$this->dbasemodel->insertData('v_transaksi', $insert3);
								
					if($row->LUNAS === 'Belum') {
						$update	=	array('ISCREDIT'	=>	1,
										  'PINJ_POKOK'	=> $row->JUMLAH,
										  'PINJ_TOTAL' 	=> ($row->JUMLAH + (($row->BUNGA * $row->JUMLAH) / 100)),
										  'PINJ_SISA'	=> ($row->JUMLAH + (($row->BUNGA * $row->JUMLAH) / 100)),
										  'PINJ_RP_ANGSURAN' => (($row->JUMLAH + (($row->BUNGA * $row->JUMLAH) / 100)) / $row->LAMA_ANGSURAN),
										  'PINJ_BASIL_DASAR' => ((($row->BUNGA * $row->JUMLAH) / 100) / $row->LAMA_ANGSURAN),
										  'PINJ_BASIL_TOTAL' => (($row->BUNGA * $row->JUMLAH) / 100),
										  'PINJ_BASIL_BAYAR' => 0 );
						$this->dbasemodel->updateData('m_anggota', $update, "IDANGGOTA = '". $row->ANGGOTA_ID ."' ");
					}
					
					$update	=	array('ISAPPROVE'	=>	1,
									  'APPROVAL'	=>	$this->session->userdata('wad_user'),
									  'UPDATE_DATA'	=>	date('Y-m-d H:i:s'),
									  'PINJ_TOTAL' 	=> ($row->JUMLAH + (($row->BUNGA * $row->JUMLAH) / 100)),
									  'PINJ_SISA'	=> ($row->JUMLAH + (($row->BUNGA * $row->JUMLAH) / 100)),
									  'PINJ_RP_ANGSURAN' => (($row->JUMLAH + (($row->BUNGA * $row->JUMLAH) / 100)) / $row->LAMA_ANGSURAN),
									  'PINJ_BASIL_DASAR' => ((($row->BUNGA * $row->JUMLAH) / 100) / $row->LAMA_ANGSURAN),
									  'PINJ_BASIL_TOTAL' => (($row->BUNGA * $row->JUMLAH) / 100)
									 );
					$this->dbasemodel->updateData('tbl_pinjaman_h', $update, "IDPINJM_H = '". $row->IDPINJM_H ."' ");
				}
				
				$this->session->set_flashdata('ses_checklist', '11||Anda telah berhasil menyetujui pembiayaan tersebut.');
				echo "ok";
			}
		}
	}
	
	
}