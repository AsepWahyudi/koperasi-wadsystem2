<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Penarikan extends CI_Controller {

	function __construct(){ 
        parent::__construct();
		$this->load->helper(array('app', 'form','url', 'xml','text_helper','date','inflector'));
		$this->load->database();
		$this->load->library(array('Pagination','user_agent','session','form_validation','session', 'tree'));
		$this->load->model(array('dbasemodel', 'ModelVTransaksi'));
		
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
		$idtrx = $this->dbasemodel->get_id('ID_TRX_SIMP', 'transaksi_simp');
		
		$_POST['tgl_trx'] =	date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('tgl_trx')))) . ' ' . date('H:i:s');
		
		$save =	$this->input->post();
		unset($save['namaanggota']);
		
		$getIdKas = $this->dbasemodel->loadsql("SELECT * FROM jenis_kas WHERE ID_JNS_KAS = '".$this->input->post('id_kas')."'LIMIT 1")->row();
		
		$save['keterangan']  = (trim($save['keterangan']) == "" ? "Penarikan tunai (" . $this->input->post('namaanggota') ."), sebesar rp " . toRp($save['jumlah']) : $save['keterangan']);
		
		$save['ID_TRX_SIMP'] = $idtrx;
		$save['DK']          = 'K'; 
		$save['AKUN']        = 'Penarikan';
		$save['UPDATE_DATA'] = date('Y-m-d H:i:s');
		$save['USERNAME']    = $this->session->userdata('wad_user');
		$save['KOLEKTOR']    = 0;
		$save['STATUS']      = strtolower($this->input->post('keterangan')) == 'transfer'  ? 1 : 0;
		$save['KODEPUSAT']   = $this->session->userdata('wad_kodepusat');
		$save['ID_KASAKUN']  = $getIdKas->IDAKUN ;
		// $save['KODECABANG']  = $this->session->userdata('wad_kodecabang');
		
		if($this->dbasemodel->insertData('transaksi_simp', $save)) 
		{
			
			$this->ModelSimpanan->updateSaldoAnggota('kurangi', $this->input->post('jumlah'), $this->input->post('id_jenis'), $this->input->post('id_anggota'));
			
			/* Insert data transaksi penarikan ke jurnal transaksi(table vtransaksi) */
			$datatransaksi	= array('tgl' => $this->input->post('tgl_trx'), 
									'jumlah' => $this->input->post('jumlah'), 
									'kodecabang' => $this->input->post('KODECABANG'), 
									'keterangan' => $save['keterangan']);
									
			$this->ModelVTransaksi->insertVtransaksi($idtrx, $datatransaksi, 'PT', $this->input->post('id_jenis'), $this->input->post('id_kas'), 'SIMP');
			
			/*$data	=	array('TGL' 		=>	$this->input->post('tgl_trx'),
							  'KREDIT'		=>	$this->input->post('jumlah'),
							  'DARI_KAS'	=>	$this->input->post('id_kas'),
							  'ID_TRX_SIMP'	=>	$idtrx,
							  'TRANSAKSI'	=>	$this->input->post('id_jenis'),
							  'KODEPUSAT'	=>	$this->session->userdata('wad_kodepusat'),
							  'KODECABANG'	=>	$this->session->userdata('wad_kodecabang'),
							  'KET'			=>	$this->input->post('keterangan'),
							  'USER'		=>	$this->session->userdata('wad_user')
						);*/
			#$this->dbasemodel->insertData('v_transaksi', $data);
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
}