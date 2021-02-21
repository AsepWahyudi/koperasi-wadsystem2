<?php
require APPPATH.'third_party/vendor/autoload.php';    
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

defined('BASEPATH') OR exit('No direct script access allowed');

class Simpanan extends CI_Controller {

	function __construct(){ 
        parent::__construct();
		$this->load->helper(array('app', 'form','url', 'xml','text_helper','date','inflector'));
		$this->load->database();
		$this->load->library(array('Pagination','user_agent','session','form_validation','session', 'tree'));
		$this->load->model('dbasemodel');
		//@session_start();
    }
	
	public function index(){
		
		if(!is_logged_in()){
			redirect('/auth_login');	
		}
		
	
		$data['opt_data_entries']	=	$this->load->view('_elements/data_entries', NULL, TRUE);
		$data['table_footer']		=	$this->load->view('_elements/table_footer', NULL, TRUE);
        $data['PAGE_TITLE']     = "Transaksi Setoran Tunai";
        $data['page']           = "simpanan/simpanan";
        $this->load->view('dashboard',$data);
    }
	
	public function datasetoran(){
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
		
		$tgl			=	$this->input->post('tgl') != "" ? $this->input->post('tgl') : date('01/m/Y') . ' - ' . date('d/m/Y');
		$tgl			=	explode('-', $tgl);
		$tgl[0] = trim($tgl[0]);
		$tgl[1] = trim($tgl[1]);
		$tgl[0] = str_replace('/', '-', $tgl[0]);
		$tgl[1] = str_replace('/', '-', $tgl[1]);
		$tgl[0] = date("Y-m-d", strtotime($tgl[0]));
		$tgl[1] = date("Y-m-d", strtotime($tgl[1]));
		$wheretrgl		=	" AND DATE(TGL_TRX) BETWEEN '". $tgl[0] ."' AND '". $tgl[1] ."'";
		
		$koncabang = ($this->session->userdata('wad_cabang')!="")? " AND D.KODECABANG='".$this->session->userdata('wad_cabang')."'":"";
		
		$this->load->model('ModelSimpanan');
		$keyword		=	null !== $this->input->post('keyword') ? $this->input->post('keyword') : "";
		$dataPerPage	=	$this->input->post('dataperpage');
		$page			=	$this->input->post('page');
		$dataTable		=	$this->ModelSimpanan->getDataTable($keyword, $dataPerPage, $page,$koncabang,$wheretrgl);

        header('Content-Type: application/json');
		echo json_encode($dataTable);
		die();
	}
	
    public function formadd(){
		
		if(!is_logged_in()){
			redirect('/auth_login');	
		}
		
        $data['PAGE_TITLE']     = "Tambah Setoran Tunai";
        $data['page']           = "simpanan/add_simpanan";
		
		$sql	=	sprintf("SELECT IDANGGOTA, NAMA, ALAMAT, NO_IDENTITAS
							 FROM
							 	m_anggota
							 ORDER BY NAMA ASC");
		$data['anggota']		=	$this->dbasemodel->loadsql($sql);
		
		$sql	=	sprintf("SELECT IDJENIS_SIMP, JNS_SIMP, JUMLAH, IDAKUN
							 FROM
							 	jns_simpan
							 WHERE TAMPIL = 'Y' ");
		$data['jenis_simpanan']	=	$this->dbasemodel->loadsql($sql);
		
		$sql	=	sprintf("SELECT IDAKUN, NAMA_KAS
							 FROM
							 	jenis_kas
							 WHERE 
							 	TMPL_SIMPAN = 'Y'
								AND KODECABANG = '%s' ",
							 $this->session->userdata('wad_kodecabang')
						);
		$data['jenis_kas']		=	$this->dbasemodel->loadsql($sql);
		
        $data['js_to_load']     = array();

        $this->load->view('dashboard',$data);
    }
	
	public function save(){
		if(!is_logged_in()){
			redirect('/auth_login');	
		}
		$this->load->model('ModelSimpanan');
		//$idtrx					=	$this->dbasemodel->get_id('ID_TRX_SIMP', 'transaksi_simp');

		$tgl = str_replace('/', '-', $this->input->post('tgl_trx'));
		$tglTrx = date("Y-m-d", strtotime($tgl));
		
		$id_jenis				=	explode("|", $this->input->post('id_jenis'));
		$_POST['id_jenis']		=	$id_jenis[0];
		$_POST['tgl_trx']		=	date('Y-m-d', strtotime($tglTrx)) . ' ' . date('H:i:s');
		$save					=	$this->input->post();
		$save['DK']				=	'D';
		$save['AKUN']			=	'Setoran';
		$save['keterangan']		=	(trim($save['keterangan']) == "" ? "Setoran tunai (" . $save['nama_penyetor'] ."), sebesar rp " . toRp($save['jumlah']) : $save['keterangan']);
		$save['USERNAME']		=	$this->session->userdata('wad_user');
		$save['KODEPUSAT']		=	$this->session->userdata('wad_kodepusat');
		$save['KODECABANG']		=	$this->session->userdata('wad_kodecabang');
		$save['nama_penyetor']	=	addslashes($save['nama_penyetor']);
		$save['KOLEKTOR']		=	0;
		$save['STATUS']			=	strtolower($this->input->post('keterangan')) == 'transfer'  ? 1 : 0;
			
		if($this->dbasemodel->insertData('transaksi_simp', $save)) {
			
			$ceklst			=	$this->dbasemodel->loadsql("Select * FROM checklist_teller WHERE TGL_AWAL='".date("Y-m-d")."' 
											AND KODEPUSAT='".$this->session->userdata('wad_kodepusat')."'
											AND KODECABANG='".$this->session->userdata('wad_kodecabang')."'");//AND Jenis='Tabungan'
			if($ceklst->num_rows()>0)
			{
				
				$rchek	= $ceklst->row();
				$nom 	= $rchek->NOMINAL_SIMP+$this->input->post('jumlah');
				$where  = "IDCEKTELLER = '". $rchek->IDCEKTELLER."' ";
				$datacheclist = array("NOMINAL_SIMP"=>$nom, "APPROVAL" => '', "STATUS" => 0);
				$this->dbasemodel->updateData("checklist_teller", $datacheclist, $where);
				
			}else{
				$datacheclist = array("TGL_AWAL"	=> date("Y-m-d"),
									"NOMINAL_SIMP"	=> $this->input->post('jumlah'),
									"KODEPUSAT"		=> $this->session->userdata('wad_kodepusat'),
									"KODECABANG"	=> $this->session->userdata('wad_kodecabang'));//"JENIS"=>"Tabungan"
				$this->dbasemodel->insertData("checklist_teller", $datacheclist);
			}
			
			$this->session->set_flashdata('ses_trx_simp', '11||Transaksi Setoran Tunai Berhasil Disimpan.');
		} else {
			$this->session->set_flashdata('ses_trx_simp', '00||Transaksi Setoran Tunai Gagal Dilakukan.');
		}
		echo true;
	}
	
	public function get_anggota() {
		if(!is_logged_in()){
			redirect('/auth_login');	
		}
		$keyw	=	$this->input->get('para1');
		$sql	=	sprintf("SELECT IDANGGOTA id, NAMA text, ALAMAT alamat, NO_IDENTITAS identitas
							 FROM
							 	m_anggota
							 WHERE 
							 	NAMA LIKE '%s' AND AKTIF = 'Y'
								AND KODECABANG = '%s'
							 ORDER BY NAMA ASC",
							 "%". $keyw ."%",
							 $this->session->userdata('wad_kodecabang'));
		$query		=	$this->dbasemodel->loadsql($sql);
		$result		=	$query->result_array();
		echo json_encode($result);
	}
	
	public function delete(){
		if(!is_logged_in()){
			redirect('/auth_login');	
		}
		$this->load->model('ModelSimpanan');
		$id		=	$this->input->get('id');
		
		$this->ModelSimpanan->updateSaldo('kurangi', $id);
		//$this->dbasemodel->hapus("v_transaksi WHERE ID_TRX_SIMP = ". $id ." ");
		$this->dbasemodel->hapus("transaksi_simp WHERE ID_TRX_SIMP = ". $id ." ");
		
		$this->session->set_flashdata('ses_trx_simp', '11||Transaksi setoran tunai telah dihapus.');
		redirect(base_url() . 'setoran-tunai');
	}
	
	function setoran_excel()
	{
		$tgl1			= str_replace("/","-",$this->input->get('tgl1'));
		$tgl2			= str_replace("/","-",$this->input->get('tgl2'));
		//echo $tgl2;
		//echo date('Y-m-d', strtotime($tgl2));
		
		$spreadsheet = new Spreadsheet();
		$spreadsheet->getActiveSheet()->setTitle('Setoran Tunai');
		$sheet = $spreadsheet->getActiveSheet();
		
		$sheet->setCellValue('A1', 'TANGGAL');
		$sheet->setCellValue('B1', 'NAMA');
		$sheet->setCellValue('C1', 'ALAMAT');
		$sheet->setCellValue('D1', 'JENIS SIMPANAN');
		$sheet->setCellValue('E1', 'JUMLAH');
		
		
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
		
		$wheretrgl		=	" AND DATE(TGL_TRX) BETWEEN '". date('Y-m-d', strtotime(str_replace('/', '-', trim($tgl1)))) ."' AND '". date('Y-m-d', strtotime(str_replace('/', '-', trim($tgl2)))) ."'";
		$koncabang = ($this->session->userdata('wad_cabang')!="")? " AND D.KODECABANG='".$this->session->userdata('wad_cabang')."'":"";
		
		$sql	=	sprintf("SELECT 
								A.ID_TRX_SIMP, DATE_FORMAT(A.TGL_TRX, '%s') TGL_TRX,
								A.NAMA_PENYETOR, A.ALAMAT,
								A.JUMLAH, A.USERNAME, A.NO_IDENTITAS,
								B.JNS_SIMP,D.KODECABANG,E.NAMA AS NAMACABANG
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
							 	AND DK = 'D' %s %s
							 ORDER BY
							 	DATE(A.TGL_TRX) DESC, A.ID_TRX_SIMP DESC",
							 '%d/%m/%Y', $koncabang, $wheretrgl);
		//echo $sql;
		$cek = $this->dbasemodel->loadsql($sql);
		
		if($cek->num_rows()>0)
		{
			$row = 2;
			foreach($cek->result() as $item)
			{
				
				$sheet->setCellValue('A'.$row,$item->TGL_TRX);
				$sheet->setCellValue('B'.$row,$item->NAMA_PENYETOR);
				$sheet->setCellValue('C'.$row,$item->ALAMAT);
				$sheet->setCellValue('D'.$row,$item->JNS_SIMP);
				$sheet->setCellValue('E'.$row,$item->JUMLAH);
				$sheet->getStyle('E'.$row)->getNumberFormat()->setFormatCode('#,##0');
				$row++;
			}
		}
		
		$writer = new Xlsx($spreadsheet);
		$file = "setorantunai_".date("ymdHis").".xlsx";
		$writer->save('export/'.$file);
		redirect(base_url().'export/'.$file);
		
		
	}

function struk() {
		$id=$this->uri->segment(2);
		$simpanan = $this->cetak_simpanan_m->lap_data_simpanan($id);

		$opsi_val_arr = $this->setting_m->get_key_val();
		foreach ($opsi_val_arr as $key => $value){
			$out[$key] = $value;
		}

		$this->load->library('Struk');
		$pdf = new Struk('P', 'mm', 'A4', true, 'UTF-8', false);
		$pdf->set_nsi_header(false);
		$resolution = array(210, 80);
		$pdf->AddPage('L', $resolution);
		$html = '
		<style>
			.h_tengah {text-align: center;}
			.h_kiri {text-align: left;}
			.h_kanan {text-align: right;}
			.txt_judul {font-size: 12pt; font-weight: bold; padding-bottom: 12px;}
			.header_kolom {background-color: #cccccc; text-align: center; font-weight: bold;}
			.txt_content {font-size: 7pt; text-align: center;}
		</style>';
		$html .= ''.$pdf->nsi_box($text =' <table width="100%">
			<tr>
				<td colspan="2" class="h_kanan"><strong>'.$out['nama_lembaga'].'</strong></td>
			</tr>
			<tr>
				<td width="20%"><strong>BUKTI SETORAN TUNAI</strong>
					<hr width="100%">
				</td>
				<td class="h_kanan" width="80%">'.$out['alamat'].'</td>
			</tr>
		</table>', $width = '100%', $spacing = '0', $padding = '1', $border = '0', $align = 'left').'';
		$no =1;
		foreach ($simpanan as $row) {
			$anggota= $this->cetak_simpanan_m->get_data_anggota($row->anggota_id);
			$jns_simpan= $this->cetak_simpanan_m->get_jenis_simpan($row->jenis_id);

			$tgl_bayar = explode(' ', $row->tgl_transaksi);
			$txt_tanggal = jin_date_ina($tgl_bayar[0]);
			$txt_tanggal .= ' / ' . substr($tgl_bayar[1], 0, 5);

			if($row->nama_penyetor ==''){
				$penyetor = '-';
			}else{
				$penyetor = $row->nama_penyetor;
			}

			if($row->alamat ==''){
				$alamat = '-';
			} else {
				$alamat = $row->alamat;
			}

        //'.'AG'.sprintf('%04d', $row->anggota_id).'
			$html .='<table width="100%">
			<tr>
				<td width="20%"> Tanggal Transaksi </td>
				<td width="2%">:</td>
				<td width="35%" class="h_kiri">'.$txt_tanggal.'</td>

				<td> Tanggal Cetak </td>
				<td width="2%">:</td>
				<td width="25%" class="h_kiri">'.jin_date_ina(date('Y-m-d')).' / '.date('H:i').'</td>
			</tr>
			<tr>
				<td> Nomor Transaksi </td>
				<td>:</td>
				<td>'.'TRD'.sprintf('%05d', $row->id).'</td>

				<td> User Akun </td>
				<td width="2%">:</td>
				<td class="h_kiri">'.$row->user_name.'</td>
			</tr>
			<tr>
				<td> ID Anggota </td>
				<td>:</td>
				<td>'.$anggota->identitas.'</td>
			
				<td> Status </td>
				<td width="2%">:</td>
				<td class="h_kiri">SUKSES</td>
			</tr>
			<tr>
				<td> Nama Anggota </td>
				<td>:</td>
				<td>'.strtoupper($anggota->nama).'</td>
			</tr>
			<tr>
				<td> Dept </td>
				<td>:</td>
				<td>'.$anggota->departement.'</td>
			</tr>			
			<tr>
				<td> Nama Penyetor </td>
				<td>:</td>
				<td>'.$penyetor.'</td>

				<td></td>
				<td width="2%"></td>
				<td class="h_kiri">Paraf, </td>
			</tr>
			<tr>
				<td> Alamat </td>
				<td>:</td>
				<td>'.$alamat.'</td>
			</tr>
			<tr>
				<td> Jenis Akun </td>
				<td>:</td>
				<td>'.$jns_simpan->jns_simpan.'</td>
			</tr>
			<tr>
				<td> Jumlah Setoran </td>
				<td>:</td>
				<td>Rp. '.number_format($row->jumlah).'</td>

				<td></td>
				<td width="2%"></td>
				<td class="h_kiri">____________ </td>
			</tr>
			<tr>
				<td> Terbilang </td> 
				<td>:</td>
				<td colspan="3">'.$this->terbilang->eja($row->jumlah).' RUPIAH </td>
			</tr>';
		}
		$html .= '</table> 
		<p class="txt_content"></p>

		<p class="txt_content">Ref. '.date('Ymd_His').'<br> 
			Informasi Hubungi Call Center : '.$out['telepon'].'
			<br>
			atau dapat diakses melalui : '.$out['web'].'
		</p>';
		$pdf->nsi_html($html);
		$pdf->Output(date('Ymd_His') . '.pdf', 'I');
	} 
}