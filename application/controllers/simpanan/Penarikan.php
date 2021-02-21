<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Penarikan extends CI_Controller {

	function __construct(){ 
        parent::__construct();
		$this->load->helper(array('app', 'form','url', 'xml','text_helper','date','inflector'));
		$this->load->database();
		$this->load->library(array('Pagination','user_agent','session','form_validation','session', 'tree'));
		$this->load->model(array('dbasemodel', 'ModelVTransaksi'));
		$this->load->library('terbilang');
		if(!is_logged_in()){
			redirect('/auth_login');	
		}
    }
	
	public function index(){
		 
		$data['opt_data_entries'] = $this->load->view('_elements/data_entries', NULL, TRUE);
		$data['table_footer']     = $this->load->view('_elements/table_footer', NULL, TRUE);
        $data['PAGE_TITLE']       = "Transaksi Penarikan Tunai";
        $data['page']             = "simpanan/penarikan";
        $this->load->view('dashboard',$data);
    }
	
	public function datapenarikan(){
		 
		if($this->input->post('tgl'))
		{
			$tgl = date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('tgl'))));
			$wheretrgl = "AND DATE(TGL_TRX)='".$tgl."'";
		}else{
			$wheretrgl = "AND DATE(TGL_TRX)='".date("Y-m-d")."'";
		}
		
		/* if($this->session->userdata("wad_level") == "admin")
		{
			$koncabang = "";
		}
		else
		{
			$koncabang =" AND D.KODECABANG='".$this->session->userdata('wad_cabang')."'";
		} */
		
		if($this->session->userdata("wad_level") == "admin")
		{
			$koncabang = "";
			
			if($this->session->userdata('wad_cabang') === true OR $this->session->userdata('wad_cabang') !="" ){
				
				$koncabang =" AND D.KODECABANG = '" .$this->session->userdata('wad_cabang'). "' ";
				
			}else{
				$koncabang = "";
			}
		}
		else
		{ 
			
			if($this->session->userdata('wad_cabang') === true OR $this->session->userdata('wad_cabang') !="" ){
				
				$koncabang =" AND D.KODECABANG = '" .$this->session->userdata('wad_cabang'). "' ";
				
			}else{
				
				$koncabang =" AND D.KODECABANG = '" .$this->session->userdata('wad_kodecabang'). "' ";
			}
		}
		$this->load->model('ModelSimpanan');
		$keyword     = null !== $this->input->post('keyword') ? $this->input->post('keyword') : "";
		$dataPerPage = $this->input->post('dataperpage');
		$page        = $this->input->post('page');
		$dataTable   = $this->ModelSimpanan->getDataTablePenarikan($keyword, $dataPerPage, $page,$koncabang,$wheretrgl);

        header('Content-Type: application/json');
		echo json_encode($dataTable);
		die();
	}
	
    public function formadd(){
		 
        $data['PAGE_TITLE'] = "Tambah Penarikan Tunai";
        $data['page'] = "simpanan/add_penarikan";
		
		$sql = sprintf("SELECT IDJENIS_SIMP, JNS_SIMP, JUMLAH, IDAKUN FROM jns_simpan WHERE TAMPIL = 'Y' AND IDJENIS_SIMP NOT IN (40, 41)");
		$data['jenis_simpanan']	= $this->dbasemodel->loadsql($sql);
		
		if($this->session->userdata("wad_level") == "admin")
		{
			$sql = "SELECT ID_JNS_KAS,IDAKUN, NAMA_KAS FROM jenis_kas WHERE TMLP_PENARIKAN = 'Y'";
		}
		else
		{
			$sql = "SELECT ID_JNS_KAS,IDAKUN, NAMA_KAS FROM jenis_kas WHERE TMLP_PENARIKAN = 'Y' AND KODECABANG = '".$this->session->userdata('wad_kodecabang')."'";
		}
		
		
		$data['jenis_kas'] = $this->dbasemodel->loadsql($sql);
		//echo $sql;print_r($data);die;	
        $data['js_to_load'] = array();

        $this->load->view('dashboard',$data);
    }
	
	public function save(){
		 
		$this->load->model('ModelSimpanan');
		
		/* echo "<pre>";
		echo print_r($_POST);
		echo "</pre>";
		
		Array
		(
			[tgl_trx] => 26/09/2020
			[id_anggota] => 1
			[namaanggota] => Ramdani
			[KODECABANG] => 11
			[id_jenis] => 180
			[jumlah] => 50000
			[keterangan] => Keterangan 
			[id_kas] => 3
		) */
		$idtrx = $this->dbasemodel->get_id('ID_TRX_SIMP', 'transaksi_simp');
		
		$_POST['tgl_trx'] =	date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('tgl_trx')))) . ' ' . date('H:i:s');
		
		$save =	$this->input->post();
		unset($save['namaanggota']);
		
		// $getIdKas = $this->dbasemodel->loadsql("SELECT * FROM jenis_kas WHERE ID_JNS_KAS = '".$this->input->post('id_kas')."'LIMIT 1")->row();
		
		$save['keterangan']  = (trim($save['keterangan']) == "" ? "Penarikan tunai (" . $this->input->post('namaanggota') ."), sebesar rp " . toRp($save['jumlah']) : $save['keterangan']);
		
		$save['ID_TRX_SIMP'] = $idtrx;
		$save['DK']          = 'K'; 
		$save['AKUN']        = 'Penarikan';
		$save['UPDATE_DATA'] = date('Y-m-d H:i:s');
		$save['USERNAME']    = $this->session->userdata('wad_user');
		$save['KOLEKTOR']    = 0;
		$save['STATUS']      = strtolower($this->input->post('keterangan')) == 'transfer'  ? 1 : 0;
		$save['KODEPUSAT']   = $this->session->userdata('wad_kodepusat');
		$save['ID_KASAKUN']  = $this->input->post('id_kas') ;
  
		if($this->dbasemodel->insertData('transaksi_simp', $save)) 
		{
			
			$this->ModelSimpanan->updateSaldoAnggota('kurangi', $this->input->post('jumlah'), $this->input->post('id_jenis'), $this->input->post('id_anggota'));
			
			
			$datatransaksi	= array('tgl' => $this->input->post('tgl_trx'), 
									'jumlah' => $this->input->post('jumlah'), 
									'kodecabang' => $this->input->post('KODECABANG'), 
									'idkasakun' => $this->input->post('id_kas'), 
									'keterangan' => $save['keterangan']);
			 
			// $datatransaksi = array( 'tgl' => $key->TGL_TRX, 'jumlah' => $key->JUMLAH, 'keterangan' => $key->KETERANGAN,
			// 'user' => $key->USERNAME, 'kodecabang' => $key->KODECABANG, 'idkasakun' => $key->ID_KASAKUN);
											
			$this->ModelVTransaksi->insertVtransaksi($idtrx, $datatransaksi, 'PT', $this->input->post('id_jenis'), $this->input->post('id_kas'), 'SIMP');
			// $this->ModelVTransaksi->insertVtransaksi($idtrx, $datatransaksi, 'PT',  $this->input->post('id_kas'), $this->input->post('id_jenis'), 'SIMP');
			 
			$this->session->set_flashdata('ses_trx_simp', '11||Transaksi Penarikan Tunai Berhasil Disimpan.');
			
		} else {
			$this->session->set_flashdata('ses_trx_simp', '00||Transaksi Penarikan Tunai Gagal Dilakukan.');
		}
		echo true;
	}
	
	public function get_total_saldo(){
		$this->load->model('ModelSimpanan');
	    $idanggota 	= 	$this->input->post('idanggota');
	    $idjenis	=	$this->input->post('idjenis');
		$saldo_simpanan	=	$this->ModelSimpanan->getSaldo($idanggota, $idjenis);
	    
		if($this->session->userdata("wad_level") == "admin")
		{
			$this->db->select('JAMINAN_TABUNGAN');
			$this->db->where('anggota_id', $idanggota);
			$this->db->where('lunas', 'Belum');
			$_res = $this->db->get('tbl_pinjaman_h')->row();
		}
		else
		{
			$this->db->select('JAMINAN_TABUNGAN');
			$this->db->where('anggota_id', $idanggota);
			$this->db->where('lunas', 'Belum');
			$this->db->where('KODECABANG', $this->session->userdata('wad_kodecabang'));
			$_res = $this->db->get('tbl_pinjaman_h')->row();
		}
	   
	    $jaminan_tabungan = ($_res) ? floor($_res->JAMINAN_TABUNGAN) : 0 ;
	    
	    $saldo_simpanan = $saldo_simpanan - $jaminan_tabungan;
	    
	    echo ($saldo_simpanan < 0 ? 0 : $saldo_simpanan);
	}
	
	public function delete(){
		if(!is_logged_in()){
			redirect('/auth_login');	
		}
		$this->load->model('ModelSimpanan');
		
		$id		=	$this->input->get('id');
		
		$this->ModelSimpanan->updateSaldo('tambah', $id);
		//$this->dbasemodel->hapus("v_transaksi WHERE ID_TRX_SIMP = ". $id ." ");
		$this->dbasemodel->hapus("transaksi_simp WHERE ID_TRX_SIMP = ". $id ." ");
		
		$this->session->set_flashdata('ses_trx_simp', '11||Transaksi penarikan tunai telah dihapus.');
		redirect(base_url() . 'penarikan-tunai');
	}
	public function struk() { 
		
		
		error_reporting(1);
 
		$this->load->model('ModelSimpanan');
		$id = $this->uri->segment(2);
		$simpanan = $this->ModelSimpanan->lap_data_simpanan($id);

		$opsi_val_arr = $this->ModelSimpanan->get_key_val();
				
		foreach ($opsi_val_arr as $key => $value){
			$out[$key] = $value;
		}
		
		$this->db->select('*');
		$this->db->from('transaksi_simp');
		$this->db->where('ID_TRX_SIMP',$id);
		$qs = $this->db->get()->row();
		
		
		// var_dump($out);
		// exit;
		$output = $this->ModelSimpanan->getCabangh($qs->KODECABANG);
		
		// var_dump($output);
		// exit;
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
				<td colspan="2" class="h_kanan"><strong>'.$output->NAMA.'</strong></td>
			</tr>
			<tr>
				<td width="20%"><strong>BUKTI TARIK TUNAI</strong>
					<hr width="100%">
				</td>
				<td class="h_kanan" width="80%">'.$output->ALAMAT.'</td>
			</tr>
		</table>', $width = '100%', $spacing = '0', $padding = '1', $border = '0', $align = 'left').'';
		$no =1;
		
		// echo $html;
		// exit;
		foreach ($simpanan as $row) {
			
			$anggota = $this->ModelSimpanan->get_data_anggota($row->ID_ANGGOTA);
			$jns_simpan = $this->ModelSimpanan->get_jenis_simpan($row->ID_JENIS);

			$tgl_bayar = explode(' ', $row->TGL_TRX);
			$txt_tanggal = jin_date_ina($tgl_bayar[0]);
			$txt_tanggal .= ' / ' . substr($tgl_bayar[1], 0, 5);

			if($row->NAMA_PENYETOR ==''){
				$penyetor = '-';
			}else{
				$penyetor = $row->NAMA_PENYETOR;
			}

			if($row->ALAMAT ==''){
				$alamat = '-';
			} else {
				$alamat = $row->ALAMAT;
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
				<td>'.'TRD'.sprintf('%05d', $row->ID_TRX_SIMP).'</td>

				<td> User Akun </td>
				<td width="2%">:</td>
				<td class="h_kiri">'.$row->USERNAME.'</td>
			</tr>
			<tr>
				<td> ID Anggota </td>
				<td>:</td>
				<td>'.$anggota->KODEPUSAT.".".$anggota->KODECABANG.".".$anggota->NO_ANGGOTA.'</td>
			
				<td> Status </td>
				<td width="2%">:</td>
				<td class="h_kiri">SUKSES</td>
			</tr>
			<tr>
				<td> Nama Anggota </td>
				<td>:</td>
				<td>'.strtoupper($anggota->NAMA).'</td>
			</tr>
			<tr>
				<td> Cabang </td>
				<td>:</td>
				<td>'.$anggota->NAMACABANG.'</td>
			</tr>			
			<tr>
				<td> Alamat </td>
				<td>:</td>
				<td>'.$anggota->ALAMAT.'</td>

				<td></td>
				<td width="2%"></td>
				<td class="h_kiri">Paraf, </td>
			</tr>
			 
			<tr>
				<td> Jenis Akun </td>
				<td>:</td>
				<td>'.$jns_simpan->JENIS_TRANSAKSI.'</td>
			</tr>
			<tr>
				<td> Jumlah Tarik </td>
				<td>:</td>
				<td>Rp. '.number_format($row->JUMLAH).'</td>

				<td></td>
				<td width="2%"></td>
				<td class="h_kiri">____________ </td>
			</tr>
			<tr>
				<td> Terbilang </td> 
				<td>:</td>
				<td colspan="3">'.$this->terbilang->eja($row->JUMLAH).' RUPIAH </td>
			</tr>';
		}
		$html .= '</table> 
		<p class="txt_content"></p>

		<p class="txt_content">Ref. '.date('Ymd_His').'<br> 
			Informasi Hubungi Call Center : '.$output->TELP.'
			<br>
			atau dapat diakses melalui : '.$output->WEB.'
		</p>';
		// var_dump($html);
		// exit;
		$pdf->nsi_html($html);
		$pdf->Output(date('Ymd_His') . '.pdf', 'I');
	} 
}