<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ModelVTransaksi extends CI_Model{
        function __construct(){
        parent::__construct();
    }
	
	public function insertVtransaksi($idtrx, $data, $kode_jurnal = 'KM', $akundebet, $akunkredit, $tipe = ''){
		
		$save = array('TANGGAL'		=> $data['tgl'],
					  'KODE_JURNAL'	=> $kode_jurnal,
					  'JUMLAH'		=> $data['jumlah'],
					  'KETERANGAN' 	=> $data['keterangan'],
					  'REFERENSI' 	=> (isset($data['referensi']) ? $data['referensi'] : ''),
					  'USER'        => (isset($data['user']) ? $data['user'] : $this->session->userdata('wad_user')),
					  'KODEPUSAT'   => (isset($data['kodepusat']) ? $data['kodepusat'] : $this->session->userdata('wad_kodepusat')),
					  'KODECABANG'	=> (isset($data['kodecabang']) ? $data['kodecabang'] : $this->session->userdata('wad_kodecabang')),
					  'IDAKUNKAS'	=> (isset($data['idkasakun']) ? $data['idkasakun'] : '') 
					  ); 
		
		if($tipe == 'KAS') 
		{
			$save['ID_TRX_KAS']	= $idtrx;
		}
		elseif($tipe == 'SIMP') 
		{
			$save['ID_TRX_SIMP'] = $idtrx;
		}
		elseif($tipe == 'PINJ') 
		{
			$save['IDPINJ_D'] = $idtrx;
		}
		
		$id = $this->insertDataProc('vtransaksi', $save);
		
		$debet = array('IDVTRANSAKSI' => $id,
						'IDAKUN'      => $akundebet,
						'DEBET'       => $data['jumlah'],
						'KREDIT'      => 0,
						'KETERANGAN'  => (isset($data['ket_dt']) ? $data['ket_dt'] : ''));
						
		$this->insertData('vtransaksi_dt', $debet);
		
		$kredit	= array('IDVTRANSAKSI' => $id,
						'IDAKUN'       => $akunkredit,
						'DEBET'        => 0,
						'KREDIT'       => $data['jumlah'],
						'KETERANGAN'   => (isset($data['ket_dt']) ? $data['ket_dt'] : ''));
						
		$this->insertData('vtransaksi_dt', $kredit);
	}
	 
	protected function insertData($table,$data){
		$str = $this->db->insert_string($table, $data); 	
		return $this->db->query($str);
	}
	
	protected function insertDataProc($table, $data){
		
		$this->db->insert($table, $data);
		$insert_id = $this->db->insert_id();
		return $insert_id;
	} 
	
	
}