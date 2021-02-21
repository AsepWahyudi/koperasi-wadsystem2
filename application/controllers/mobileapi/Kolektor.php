<?php

class Kolektor extends CI_Controller{
    
    public function __construct()
    {
        parent::__construct();
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE');
        header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization');
        header('Content-Type: application/json; charset=UTF-8');
        $this->load->helper(array('form','url', 'xml','text_helper','date','inflector','app'));
		$this->load->database();
		$this->load->library(array('Pagination','user_agent','session','form_validation','session'));
		$this->load->model(array('dbasemodel','apikolektor','ModelAnggota', 'modelPinjaman'));
		if ($method == "OPTIONS") {
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers:Origin, X-Requested-With, Content-Type, Accept, Authorization");
header("HTTP/1.1 200 OK");
die();
}
    }


    function index(){
        echo json_encode(array('code'=>'404','msg'=>'Kosong.'));

    }


    function create_token(){
        $data = json_decode(trim(file_get_contents('php://input')), true);
        $username = $data->username;
        //$username = $this->input->post('username');
        $teksrahasia = 'wadsystemkop99';
        $now = date('Y-m-d H:i:s');
        $expired = date('Y-m-d H:i:s',strtotime("+1 day",strtotime($now)));
        $signature = hash_hmac('sha256', $username, $teksrahasia, true);
        $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

        $datainsert = ['username'=>$username,'token'=>$base64UrlSignature,'created_at'=>$now,'expired_at'=>$expired];
        $this->dbasemodel->insertData('m_user_token',$datainsert);

        echo json_encode(['code'=>200,'token'=>$base64UrlSignature]);
    }


    function cek_token($username,$token){
        
        $cek = $this->apikolektor->cek_token($username,$token);
        return $cek;
    }



    function cek_login($username, $password){
       
        $pass = md5($password);
        $now = date('Y-m-d H:i:s');
            $ceklogin = $this->dbasemodel->loadsql("SELECT * FROM m_user WHERE USERNAME = '$username' AND PASSWORD='$pass' AND LEVEL = 'kolektor' AND AKTIF = 1")->num_rows();

            if($ceklogin > 0){
                $json = array('code'=>200,'msg'=>'Login Berhasil');
                $this->dbasemodel->loadsql("UPDATE m_user SET LOGIN_DATE = '$now' WHERE USERNAME = '$username'");
            }else{
                $json = array('code'=>500,'msg'=>'Login Gagal');
            }
           

        echo json_encode($json,JSON_PRETTY_PRINT);
    }


    function detailuser($username){
        $json = array();
        $query = $this->dbasemodel->loadsql("SELECT * FROM m_user WHERE USERNAME = '$username'")->result();
        $bulan = date('m');
        $thn = date('Y');
        if(!empty($query)){
            foreach($query as $row){
                $totalsimpanan = $this->totalsimpanan($row->IDUSER);
                $totalpinjaman = $this->totalpinjaman($row->IDUSER);
                $json[] = array(
                    'iduser'=>$row->IDUSER,
                    'nama'=>$row->NAMA,
                    'kodepusat'=>$row->KODEPUSAT,
                    'kodecabang'=>$row->KODECABANG,
                    'logindate'=>date('d/m/Y H:i:s',strtotime($row->LOGIN_DATE)),
                    'totalsimpanan'=>number_format($totalsimpanan),
                    'totalpinjaman'=>number_format($totalpinjaman)
                );
            }
        }else{
            $json[] = array('iduser'=>0);
        }

        echo json_encode($json);

    }


    function totalpinjaman($id){
        $bulan = date('m');
        $thn = date('Y');
        $query = $this->dbasemodel->loadsql("SELECT SUM(JUMLAH_BAYAR) as total FROM tbl_pinjaman_d WHERE IDKOLEKTOR = '$id' AND MONTH(TGL_BAYAR) = '$bulan' AND YEAR(TGL_BAYAR) = '$thn'");

        if($query->num_rows() > 0){
            $data = $query->row();
            $jml = $data->total;
        }else{
            $jml = 0;
        }

        return $jml;

    }


    function totalsimpanan($id){
        $bulan = date('m');
        $thn = date('Y');
        $query = $this->dbasemodel->loadsql("SELECT SUM(JUMLAH) as total FROM transaksi_simp WHERE IDKOLEKTOR = '$id' AND MONTH(TGL_TRX) = '$bulan' AND YEAR(TGL_TRX) = '$thn'");

        if($query->num_rows() > 0){
            $data = $query->row();
            $jml = $data->total;
        }else{
            $jml = 0;
        }

        return $jml;

    }


    function carinasabahsimpanan(){
    

        $geturl = file_get_contents('php://input');
        $data = json_decode($geturl);
        if(isset($data->nama)){
            $nama = $data->nama;
        } else{
            $nama = "";
        }

        if(isset($data->alamat)){
            $alamat = $data->alamat;
        } else{
            $alamat = "";
        }

        if(isset($data->ktp)){
            $ktp = $data->ktp;
        } else{
            $ktp = "";
        }

        $this->db->select(array(
            'm_anggota.IDANGGOTA',
            'm_anggota.KODEPUSAT',
            'm_anggota.KODECABANG',
            'm_anggota.NO_ANGGOTA',
            'm_anggota.NAMA',
            'm_anggota.ALAMAT'
        ));

        if($nama == "" && $ktp == "" && $alamat == ""){
            $json = array('code'=>500);
        }else{
            $kodepusat = $data->kodepusat;
            $kodecabang = $data->kodecabang;

            if($nama <> ""){
                $this->db->like('NAMA',$nama);
            }
    
            if($ktp <> ""){
                $this->db->where('NO_IDENTITAS',$ktp);
            }
    
            if($alamat <> ""){
                $this->db->like('ALAMAT',$alamat);
            }

            
    
            $this->db->join('m_anggota','m_anggota.IDANGGOTA = m_anggota_simp.IDANGGOTA','LEFT');
            // $this->db->where([
            //     'm_anggota.KODECABANG'=>$kodecabang,
            //     'm_anggota.KODEPUSAT'=>$kodepusat
            // ]);
            $this->db->group_by('m_anggota.IDANGGOTA');
            $query = $this->db->get('m_anggota_simp')->result();
    
            $json = array();
            if(!empty($query)){
                foreach($query as $row){
                    $json[] = array(
                        'code'=>200,
                        'idanggota'=>$row->IDANGGOTA,
                        'nama'=>$row->NAMA,
                        'alamat'=>$row->ALAMAT,
                        'kodepusat'=>$row->KODEPUSAT,
                        'kodecabang'=>$row->KODECABANG,
                        'kodeanggota'=>$row->NO_ANGGOTA,
                    );
                }
            }else{
                $json[] = array('code'=>500);
            }
        }
        
        echo json_encode($json);       

    }


    function carinasabahpinjaman(){
    
        $geturl = file_get_contents('php://input');
        $data2 = json_decode($geturl);
        $data = $data2;
        $nama = $data->nama !== "" ? $data->nama : "";

        if(isset($data->alamat)){
            $alamat = $data->alamat;
        } else{
            $alamat = "";
        }

        if(isset($data->ktp)){
            $ktp = $data->ktp;
        } else{
            $ktp = "";
        }

        $kodepusat = $data->kodepusat;
        $kodecabang = $data->kodecabang;

        $this->db->select(array(
            'm_anggota.IDANGGOTA',
            'm_anggota.KODEPUSAT',
            'm_anggota.KODECABANG',
            'm_anggota.NO_ANGGOTA',
            'm_anggota.NAMA',
            'm_anggota.ALAMAT',
            'm_anggota.NOREK',
        ));

        if($nama == "" && $ktp == "" && $alamat == ""){
            $json = array('code'=>500);
        }else{

            if($nama !== ""){
                $this->db->like('NAMA',$nama);
            }
    
            if($ktp <> ""){
                $this->db->where('NO_IDENTITAS',$ktp);
            }
    
            if($alamat <> ""){
                $this->db->like('ALAMAT',$alamat);
            }
    
            // if($kodecabang <> "") {
            //     $this->db->where('m_anggota.KODECABANG', $kodecabang);
            // }

            // if($kodepusat <> "") {
            //     $this->db->where('m_anggota.KODEPUSAT', $kodepusat);
            // }
        }

        $this->db->group_by('m_anggota.IDANGGOTA');
        $query = $this->db->get('m_anggota')->result();

        $json = array();
        if(!empty($query)){
            foreach($query as $row){
                $dataPinj = $this->dbasemodel->loadsql("SELECT * FROM `tbl_pinjaman_h` WHERE ANGGOTA_ID ='".$row->IDANGGOTA."' LIMIT 1")->row();
                $total_tagihan = $dataPinj->PINJ_RP_ANGSURAN + (($dataPinj->BUNGA * $dataPinj->PINJ_RP_ANGSURAN) / 100);
                $json[] = array(
                    'code'=>200,
                    'idanggota'=>$row->IDANGGOTA,
                    'nama'=>$row->NAMA,
                    'alamat'=>$row->ALAMAT,
                    'kodepusat'=>$row->KODEPUSAT,
                    'kodecabang'=>$row->KODECABANG,
                    'kodeanggota'=>$row->NO_ANGGOTA,
                    'sisapinjaman' => $total_tagihan - $dataPinj->total_bayar
                );
            }
        }else{
            $json[] = array('code'=>500);
        }
        
        echo json_encode($json);       

    }

    function list_simpananjenis($idanggota){
        
        $sqls = "SELECT IDJENIS_SIMP, JNS_SIMP, JUMLAH, IDAKUN FROM jns_simpan WHERE TAMPIL = 'Y'";
		$query	= $this->dbasemodel->loadsql($sqls); 
		$result = $query->result_array();
        $json = array();
        if(empty($result)){
            $json[] = array('code'=>500);
        }
        echo json_encode($result);
    }


    function detailnasabah($id){
        $this->db->select(array(
            'm_anggota.KODEPUSAT',
            'm_anggota.KODECABANG',
            'm_anggota.NO_ANGGOTA',
            'm_anggota.NAMA',
            'm_anggota.ALAMAT'
        ));
       
        $query = $this->db->get_where('m_anggota',['IDANGGOTA'=>$id])->result();

        $json = array();
        if(!empty($query)){
            foreach($query as $row){
                $json[] = array(
                    'code'=>200,
                    'nama'=>$row->NAMA,
                    'alamat'=>$row->ALAMAT,
                    'kodepusat'=>$row->KODEPUSAT,
                    'kodecabang'=>$row->KODECABANG,
                    'kodeanggota'=>$row->NO_ANGGOTA,
                );
            }
        }else{
            $json[] = array('code'=>500);
        }


        echo json_encode($json);

    }


    function simpan_setor_simpanan($idangg,$idkolektor, $jenis,$jumlah,$keterangan, $namakolektor,$kodepusat, $kodecabang,$usernamekol){
        $input = file_get_contents('php://input'); 
        $data = json_decode($input, true); 

        $angg = $this->ModelAnggota->getDetailAnggota($idangg);

        /*$idanggota = $data->idangg;
        $idkolektor = $data->idkolektor;
        $simpanan = $data->jenis;
        $jumlah = $data->jumlah;
        $keterangan = $data->keterangan;
        $tanggal = $data->tanggal;
        $namakolektor = $data->namakolektor;
        $kodepusat = $data->kodepusat;
        $kodecabang = $data->kodecabang;
        $usernamekol = $data['usernamekol'];*/
        $amount = str_replace('.','', $jumlah);
        $this->load->model('ModelSimpanan');

        $ket =  (trim($keterangan) == "" ? "Setoran tunai (" . $angg['NAMA'] ."), sebesar rp " . $jumlah : $keterangan);
        
        $getIdKas = $this->dbasemodel->loadsql("SELECT * FROM jenis_kas WHERE KODECABANG ='".$kodecabang."' LIMIT 1")->row();
		
        $save['id_jenis']		= $jenis;
        $save['ID_ANGGOTA'] = $idangg;
        $save['TGL_TRX']		= date('Y-m-d H:i:s');
       // $save['UPDATE_DATA']		= date('Y-m-d H:i:s');
		$save['JUMLAH'] = $amount;
		$save['DK']				= 'D';
		$save['AKUN']			= 'Setoran';
		$save['KETERANGAN']		= str_replace('%20', ' ', $ket);
		$save['USERNAME']		= $usernamekol;
		$save['KODEPUSAT']		= $kodepusat;
		$save['KODECABANG']		= $kodecabang;
        $save['NAMA_PENYETOR']	= addslashes($angg['NAMA']);
		$save['KOLEKTOR']		= 0;
        $save['STATUS']			=  strtolower($keterangan) == 'transfer'  ? 1 : 0;
        $save['ID_KASAKUN'] = $getIdKas ->IDAKUN;
        $save['ID_KAS'] = $getIdKas ->ID_JNS_KAS;
        $save['NO_IDENTITAS'] = $angg['NO_IDENTITAS'];
        $save['IDKOLEKTOR'] = $idkolektor;
        $save['ALAMAT'] = $angg['ALAMAT'];
			
		if($this->dbasemodel->insertData('transaksi_simp', $save)) {
		
			$ceklst	=	$this->dbasemodel->loadsql("Select * FROM checklist_teller WHERE TGL_AWAL='".date("Y-m-d")."' 
											AND KODEPUSAT='".$kodepusat."'
											AND KODECABANG='".$kodecabang."'");//AND Jenis='Tabungan'
			if($ceklst->num_rows()>0)
			{
				$rchek	= $ceklst->row();
				$nom 	= $rchek->NOMINAL_SIMP+$amount;
				$where  = "IDCEKTELLER = '".$idkolektor."' ";
				$datacheclist = array("NOMINAL_SIMP"=>$nom, "APPROVAL" => '', "STATUS" => 0);
				$this->dbasemodel->updateData("checklist_teller", $datacheclist, $where);
				
			}else{
				$datacheclist = array("TGL_AWAL"	=> date("Y-m-d"),
									"NOMINAL_SIMP"	=> $amount,
									"KODEPUSAT"		=> $kodepusat,
									"KODECABANG"	=> $kodecabang);//"JENIS"=>"Tabungan"
				$this->dbasemodel->insertData("checklist_teller", $datacheclist);
			}
			
			$json = array('code'=>200,'msg'=>'11||Transaksi Setoran Tunai Berhasil Disimpan.');
			
			//$json = array('code'=>200,'msg'=>$ceklst->num_rows(), 'date' =>date("Y-m-d"), 'rcheck' => $datacheclist);
		} else {
			$json = array('code'=>500,'msg'=>'00||Transaksi Setoran Tunai Gagal Dilakukan.');
        }
        echo json_encode($json);
    }
    

function simpan_setor_simpanan2($idangg,$idkolektor, $jenis,$jumlah,$keterangan, $namakolektor,$kodepusat, $kodecabang,$usernamekol){
        $input = file_get_contents('php://input'); 
        $data = json_decode($input, true); 

        $angg = $this->ModelAnggota->getDetailAnggota($idangg);

        /*$idanggota = $data->idangg;
        $idkolektor = $data->idkolektor;
        $simpanan = $data->jenis;
        $jumlah = $data->jumlah;
        $keterangan = $data->keterangan;
        $tanggal = $data->tanggal;
        $namakolektor = $data->namakolektor;
        $kodepusat = $data->kodepusat;
        $kodecabang = $data->kodecabang;
        $usernamekol = $data['usernamekol'];*/

        $this->load->model('ModelSimpanan');

        $ket =  (trim($keterangan) == "" ? "Setoran tunai (" . $angg['NAMA'] ."), sebesar rp " . toRp($jumlah) : $keterangan);
        
        $getIdKas = $this->dbasemodel->loadsql("SELECT * FROM jenis_kas WHERE KODECABANG ='".$kodecabang."' LIMIT 1")->row();
        
       $amount = str_replace('.','', $jumlah);
		
        $save['id_jenis']		= $jenis;
        $save['ID_ANGGOTA'] = $idangg;
        $save['TGL_TRX']		= date('Y-m-d H:i:s');
        // $save['UPDATE_DATA']		= date('Y-m-d H:i:s');
		$save['JUMLAH'] = $amount;
		$save['DK']				= 'D';
		$save['AKUN']			= 'Setoran';
		$save['KETERANGAN']		= str_replace('%20', ' ', $ket);
		$save['USERNAME']		= $usernamekol;
		$save['KODEPUSAT']		= $kodepusat;
		$save['KODECABANG']		= $kodecabang;
        $save['NAMA_PENYETOR']	= addslashes($angg['NAMA']);
		$save['KOLEKTOR']		= 0;
        $save['STATUS']			=  strtolower($keterangan) == 'transfer'  ? 1 : 0;
        $save['ID_KASAKUN'] = $getIdKas ->IDAKUN;
        $save['ID_KAS'] = $getIdKas ->ID_JNS_KAS;
        $save['NO_IDENTITAS'] = $angg['NO_IDENTITAS'];
        $save['ALAMAT'] = $angg['ALAMAT'];
			
			$ceklst	=	$this->dbasemodel->loadsql("Select * FROM checklist_teller WHERE TGL_AWAL='".date("Y-m-d")."' 
											AND KODEPUSAT='".$kodepusat."'
											AND KODECABANG='".$kodecabang."'");
			$rchek	= $ceklst->row();
				$nom 	= $rchek->NOMINAL_SIMP+$amount;
			
			$json = array('code'=>200, 'date' =>date("Y-m-d"), 'rcheck' => $nom);
		
        echo json_encode($json);
    }


    function list_setor_simpanan($idkolektor){
        $this->db->select(array(
            'transaksi_simp.ID_TRX_SIMP',
            'transaksi_simp.TGL_TRX',
            'm_anggota.NAMA',
            'transaksi_simp.JUMLAH',
            'jns_akun.JENIS_TRANSAKSI'
        ));
        $this->db->join('jns_akun','jns_akun.IDAKUN = transaksi_simp.ID_JENIS','LEFT');
        $this->db->join('m_anggota','m_anggota.IDANGGOTA = transaksi_simp.ID_ANGGOTA','LEFT');
        
        $this->db->where('m_anggota.KODECABANG',$idkolektor);
        $this->db->order_by('ID_TRX_SIMP','DESC');
        $query = $this->db->get('transaksi_simp')->result();
        
        $json = array();
        if(!empty($query)){
            foreach($query as $row){
                $json[] = array(
                    'code'=>200,
                    'idsimpanan'=>$row->ID_TRX_SIMP,
                    'tglbayar'=>date('d M Y', strtotime($row->TGL_TRX)),
                    'nama'=>$row->NAMA,
                    'jumlah'=>number_format($row->JUMLAH,0,",","."),
                    'namatrans'=>$row->JENIS_TRANSAKSI
                );
            }
        }else{
            $json[] = array('code'=>500);
        }

        echo json_encode($json);
        
    }


    function detail_setor_simpanan($id){
        $this->db->select(array(
            'tbl_pinjaman.IDPINJAM',
            'tbl_pinjaman_d.TGL_BAYAR',
            'm_anggota.NAMA',
            'tbl_pinjaman.JUMLAH_BAYAR'
        ));
        $this->db->join('tbl_pinjaman_h','tbl_pinjaman_h.IDPINJM_H = tbl_pinjaman_d.IDPINJAM','LEFT');
        $this->db->join('m_anggota','m_anggota.IDANGGOTA = tbl_pinjaman_h.ANGGOTA_ID','LEFT');
        $this->db->where('tbl_pinjaman_d.IDPINJAM',$id);
    }


    function detailnasabahpinjaman($id){
        $this->db->select(array(
            'm_anggota.KODEPUSAT',
            'm_anggota.KODECABANG',
            'm_anggota.NO_ANGGOTA',
            'm_anggota.NAMA',
            'm_anggota.ALAMAT'
        ));
       
        $query = $this->db->get_where('m_anggota',['IDANGGOTA'=>$id])->result();
        $json = array();
        if(!empty($query)){
            foreach($query as $row){
                $pinj = $this->detailpinjaman($id);
                $json[] = array(
                    'code'=>200,
                    'nama'=>$row->NAMA,
                    'alamat'=>$row->ALAMAT,
                    'kodepusat'=>$row->KODEPUSAT,
                    'kodecabang'=>$row->KODECABANG,
                    'kodeanggota'=>$row->NO_ANGGOTA,
                    'sisa'=>number_format($pinj->PINJ_SISA,0,",","."), 
                    'angsuran'=>number_format($pinj->ANGSURAN_DASAR,0,",",".")
                );
            }
        }else{
            $json[] = array('code'=>500);
        }


        echo json_encode($json);

    }

    function list_angsuran($id){
        
        $pinj = $this->detailpinjaman($id);
        $status = $this->__statusanggota($pinj->IDPINJM_H);
        $jmlans = $this->detailangsuranbayar($pinj->IDPINJM_H);
        $lama = $pinj->LAMA_ANGSURAN;
        $sisa = $pinj->PINJ_SISA;
        $pinj_basil_total = $pinj->PINJ_BASIL_TOTAL;
        $pinj_pokok_sisa = $pinj->PINJ_POKOK_SISA;
        $sisa_tagihan = $pinj->PINJ_SISA;
        $tglpinj = $pinj->TGL_PINJ;
        $angsuran = $pinj->PINJ_RP_ANGSURAN;
        $basil = $pinj->PINJ_BASIL_DASAR;
        $angsdasar = $pinj->ANGSURAN_DASAR;
        $angsperbln = $angsdasar + $basil;
        $json = array();
        $bln=0;
        

      //  $getbunga = ($pinj->PINJ_SISA/100)*100;
        $hitreset = ($pinj->PINJ_SISA/100)*$pinj->BUNGA;
        if($status != "Lancar"){
            $byreset = $hitreset;
            $bykolektor = 30000;
        }else{
            $byreset = 0;
            $bykolektor = 0;
        }

         $jumlahBayar = $pinj->PINJ_SISA + $byreset + $bykolektor;

        if($jmlans == $lama){
            $tempo = date('Y-m-d',strtotime("+$lama month", strtotime($tglpinj)));
            $json[] = array(
                'idpinjaman'=>$pinj->IDPINJM_H,
                'angske'=>$jmlans,
                'status'=>$status,
                'tempo'=>$tempo,
                'biayareset'=>number_format($byreset,0,",","."),
                'biayakolektor'=>number_format($bykolektor,0,",","."),
                'jumlah'=>number_format($angsuran,0,",","."),
                'basil'=>$basil,
                'lama' => number_format($lama,0,",","."),
                'angsbln' => number_format($angsperbln,0,",","."),
                'sisatagihan' => number_format($pinj->PINJ_SISA,0,",","."),
                'bayar_pokok' => number_format($angsdasar,0,",","."),
                'jumlah_bayar' => number_format($jumlahBayar,0,",",".")
            );
        }elseif($jmlans < $lama){
            for($i=$jmlans;$i<=$lama;$i++){
                $bln++;
                $tempo = date('Y-m-d',strtotime("+$bln month", strtotime($tglpinj)));
                $json[] = array(
                    'idpinjaman'=>$pinj->IDPINJM_H,
                    'angske'=>$i,
                    'status'=>$status,
                    'tempo'=>$tempo,
                    'biayareset'=>number_format($byreset,0,",","."),
                'biayakolektor'=>number_format($bykolektor,0,",","."),
                    'jumlah'=>number_format($angsuran,0,",","."),
                    'basil'=>$basil,
                'lama' => number_format($lama,0,",","."),
                'angsbln' => number_format($angsperbln,0,",","."),
                'sisatagihan' => number_format($pinj->PINJ_SISA,0,",","."),
                'bayar_pokok' => number_format($angsdasar,0,",","."),
                'jumlah_bayar' => number_format($jumlahBayar,0,",",".")
                );
            }
        }else{
            $json[] = array('code'=>500);
        }
        

        echo json_encode($json);
    }



    function __statusanggota($idpinjaman){
       
        $ceklancar = $this->db->query("SELECT A.IDPINJM_H, DATE_FORMAT(A.TGL_PINJ, '%d/%m/%Y') TGL_PINJ, DATE_FORMAT(DATE_ADD(A.TGL_PINJ, INTERVAL A.LAMA_ANGSURAN MONTH), '%d/%m/%Y') JATUH_TEMPO, DATE_FORMAT(DATE_ADD(A.TGL_PINJ, INTERVAL A.LAMA_ANGSURAN MONTH), '%Y%m%d') TEMPO_FIRST_NUMB, DATE_FORMAT(DATE_ADD(DATE_ADD(A.TGL_PINJ, INTERVAL A.LAMA_ANGSURAN MONTH), INTERVAL A.LAMA_ANGSURAN MONTH), '%Y%m%d') TEMPO_SECOND_NUMB, DATE_FORMAT(DATE_ADD(DATE_ADD(DATE_ADD(A.TGL_PINJ, INTERVAL A.LAMA_ANGSURAN MONTH), INTERVAL A.LAMA_ANGSURAN MONTH), INTERVAL A.LAMA_ANGSURAN MONTH), '%Y%m%d') TEMPO_THIRD_NUMB, DATE_FORMAT(NOW(), '%Y%m%d') NOW_NUMB, A.PINJ_TOTAL, A.PINJ_DIBAYAR, A.PINJ_SISA, A.IS_RESET, A.LUNAS, B.NAMA NAMA_ANGGOTA 
        FROM tbl_pinjaman_h A LEFT JOIN m_anggota B ON B.IDANGGOTA = A.ANGGOTA_ID 
        WHERE A.IDPINJM_H ='".$idpinjaman."' AND 1=1 
        -- AND A.PINJ_SISA > 0 
        AND A.LUNAS LIKE 'Lunas' OR 1=1 
        AND DATE_FORMAT(DATE_ADD(A.TGL_PINJ, INTERVAL A.LAMA_ANGSURAN MONTH), '%Y%m%d') > DATE_FORMAT(NOW(), '%Y%m%d')")->num_rows();


        if($ceklancar > 0){ 
            $keterangan = 'Lancar';
        }

        $cekragu = $this->db->query("SELECT A.IDPINJM_H, DATE_FORMAT(A.TGL_PINJ, '%d/%m/%Y') TGL_PINJ, DATE_FORMAT(DATE_ADD(A.TGL_PINJ, INTERVAL A.LAMA_ANGSURAN MONTH), '%d/%m/%Y') JATUH_TEMPO, DATE_FORMAT(DATE_ADD(A.TGL_PINJ, INTERVAL A.LAMA_ANGSURAN MONTH), '%Y%m%d') TEMPO_FIRST_NUMB, DATE_FORMAT(DATE_ADD(DATE_ADD(A.TGL_PINJ, INTERVAL A.LAMA_ANGSURAN MONTH), INTERVAL A.LAMA_ANGSURAN MONTH), '%Y%m%d') TEMPO_SECOND_NUMB, DATE_FORMAT(DATE_ADD(DATE_ADD(DATE_ADD(A.TGL_PINJ, INTERVAL A.LAMA_ANGSURAN MONTH), INTERVAL A.LAMA_ANGSURAN MONTH), INTERVAL A.LAMA_ANGSURAN MONTH), '%Y%m%d') TEMPO_THIRD_NUMB, DATE_FORMAT(NOW(), '%Y%m%d') NOW_NUMB, A.PINJ_TOTAL, A.PINJ_DIBAYAR, A.PINJ_SISA, A.IS_RESET, A.LUNAS, B.NAMA NAMA_ANGGOTA 
        FROM tbl_pinjaman_h A LEFT JOIN m_anggota B ON B.IDANGGOTA = A.ANGGOTA_ID 
        WHERE A.IDPINJM_H ='".$idpinjaman."' AND 1=1 AND A.LUNAS LIKE 'Belum' 
        AND A.PINJ_SISA > 0 
        AND DATE_FORMAT(DATE_ADD(A.TGL_PINJ, INTERVAL A.LAMA_ANGSURAN MONTH), '%Y%m%d') <= DATE_FORMAT(NOW(), '%Y%m%d') 
        AND DATE_FORMAT(DATE_ADD(DATE_ADD(A.TGL_PINJ, INTERVAL A.LAMA_ANGSURAN MONTH), INTERVAL A.LAMA_ANGSURAN MONTH), '%Y%m%d') > DATE_FORMAT(NOW(), '%Y%m%d')")->num_rows();
        
        if($cekragu > 0){ 
            $keterangan = 'Meragukan';
        }                            
        

        $cekburuk = $this->db->query("SELECT A.IDPINJM_H, DATE_FORMAT(A.TGL_PINJ, '%d/%m/%Y') TGL_PINJ, DATE_FORMAT(DATE_ADD(A.TGL_PINJ, INTERVAL A.LAMA_ANGSURAN MONTH), '%d/%m/%Y') JATUH_TEMPO, DATE_FORMAT(DATE_ADD(A.TGL_PINJ, INTERVAL A.LAMA_ANGSURAN MONTH), '%Y%m%d') TEMPO_FIRST_NUMB, DATE_FORMAT(DATE_ADD(DATE_ADD(A.TGL_PINJ, INTERVAL A.LAMA_ANGSURAN MONTH), INTERVAL A.LAMA_ANGSURAN MONTH), '%Y%m%d') TEMPO_SECOND_NUMB, DATE_FORMAT(DATE_ADD(DATE_ADD(DATE_ADD(A.TGL_PINJ, INTERVAL A.LAMA_ANGSURAN MONTH), INTERVAL A.LAMA_ANGSURAN MONTH), INTERVAL A.LAMA_ANGSURAN MONTH), '%Y%m%d') TEMPO_THIRD_NUMB, DATE_FORMAT(NOW(), '%Y%m%d') NOW_NUMB, A.PINJ_TOTAL, A.PINJ_DIBAYAR, A.PINJ_SISA, A.IS_RESET, A.LUNAS, B.NAMA NAMA_ANGGOTA 
        FROM tbl_pinjaman_h A LEFT JOIN m_anggota B ON B.IDANGGOTA = A.ANGGOTA_ID 
        WHERE A.IDPINJM_H ='".$idpinjaman."' AND 1=1 
        AND A.LUNAS LIKE 'Belum' 
        AND A.PINJ_SISA > 0 
        AND DATE_FORMAT(DATE_ADD(DATE_ADD(A.TGL_PINJ, INTERVAL A.LAMA_ANGSURAN MONTH), INTERVAL A.LAMA_ANGSURAN MONTH), '%Y%m%d') <= DATE_FORMAT(NOW(), '%Y%m%d') 
        AND DATE_FORMAT(DATE_ADD(DATE_ADD(DATE_ADD(A.TGL_PINJ, INTERVAL A.LAMA_ANGSURAN MONTH), INTERVAL A.LAMA_ANGSURAN MONTH), INTERVAL A.LAMA_ANGSURAN MONTH), '%Y%m%d') > DATE_FORMAT(NOW(), '%Y%m%d')")->num_rows();
        
        if($cekburuk > 0){ 
            $keterangan = 'Buruk';
        }     

        $cekmacet = $this->db->query("SELECT A.IDPINJM_H, DATE_FORMAT(A.TGL_PINJ, '%d/%m/%Y') TGL_PINJ, DATE_FORMAT(DATE_ADD(A.TGL_PINJ, INTERVAL A.LAMA_ANGSURAN MONTH), '%d/%m/%Y') JATUH_TEMPO, DATE_FORMAT(DATE_ADD(A.TGL_PINJ, INTERVAL A.LAMA_ANGSURAN MONTH), '%Y%m%d') TEMPO_FIRST_NUMB, DATE_FORMAT(DATE_ADD(DATE_ADD(A.TGL_PINJ, INTERVAL A.LAMA_ANGSURAN MONTH), INTERVAL A.LAMA_ANGSURAN MONTH), '%Y%m%d') TEMPO_SECOND_NUMB,			DATE_FORMAT(DATE_ADD(DATE_ADD(DATE_ADD(A.TGL_PINJ, INTERVAL A.LAMA_ANGSURAN MONTH), INTERVAL A.LAMA_ANGSURAN MONTH), INTERVAL A.LAMA_ANGSURAN MONTH), '%Y%m%d') TEMPO_THIRD_NUMB, DATE_FORMAT(NOW(), '%Y%m%d') NOW_NUMB, A.PINJ_TOTAL, A.PINJ_DIBAYAR, A.PINJ_SISA, A.IS_RESET, A.LUNAS, B.NAMA NAMA_ANGGOTA 
        FROM tbl_pinjaman_h A LEFT JOIN m_anggota B ON B.IDANGGOTA = A.ANGGOTA_ID 
        WHERE A.IDPINJM_H ='".$idpinjaman."' AND 1=1 
        AND A.LUNAS LIKE 'Belum' 
        AND A.PINJ_SISA > 0 
        AND DATE_FORMAT(DATE_ADD(DATE_ADD(DATE_ADD(A.TGL_PINJ, INTERVAL A.LAMA_ANGSURAN MONTH), INTERVAL A.LAMA_ANGSURAN MONTH), INTERVAL A.LAMA_ANGSURAN MONTH), '%Y%m%d') <= DATE_FORMAT(NOW(), '%Y%m%d')")->num_rows();
        
        if($cekmacet > 0){ 
            $keterangan = 'Macet';
        }  


        return $keterangan;
    }



    function simpan_setor_pinjaman($tanggal, $idpinjaman, $angsuranke, $bayar, $bayar_basil, $bayar_pokok, $biaya_reset, $biaya_kolektor, $jns_kas, $kode_cabang, $keterangan, $username, $kode_pusat, $idkolektor)
    {
      
      /*  
        $amount = str_replace('.','', $jumlah);
        $iduser = $idkolektor;
        $angsuran =	$angsuranke;
        $tanggal = $tanggal;
        $idpinjaman = $idpinjaman;
        $jumlahangsuranx = $amount;
        $bayar_pokok = str_replace(".","",$bayar_pokok);
        $biaya_reset = str_replace(".","",$biayareset);
        $biaya_kolektor = str_replace(".","",$biayakolektor);
        $jumlahangsuran = intval($jumlahangsuranx);
        $usernamekol = $usernamekol;
        $keterangan = $biaya_keterangan;
        $kodepusat = $kodepusat;
        $kodecabang = $kodecabang;
        $basil = $basil;
        $kas = $kas;
*/
		$bayar_saldo	=	0;
		$denda_rp	=	0;
		$tgl_trx		=	date('Y-m-d', strtotime($tanggal)) . date(' H:i:s');

$angsuran = str_replace(".","", $bayar);

$save = array('TGL_BAYAR'		=> $tgl_trx,
						'IDPINJAM'		=> $idpinjaman,
						'ANGSURAN_KE'	=> $angsuranke,
						'BAYAR_SALDO'	=> 0,
						'JUMLAH_BAYAR'	=> str_replace(".","", $bayar),
						'BASILBAYAR'	=> str_replace(".","", $bayar_basil),
						'POKOKBAYAR'	=> str_replace(".","", $bayar_pokok), 
						'DENDA_RP'	=> str_replace(".","", $biaya_reset),
						'BIAYA_KOLEKTOR'=> str_replace(".","", $biaya_kolektor),  
						'KET_BAYAR'	=> 'Angsuran',
						'KAS_ID'	=> $jns_kas,
						'JENIS_TRANS'	=> sukubunga('angsuran_pembiayaan'),
						'IDKOLEKTOR'	=> $idkolektor,
						'KODECABANG'	=> $kode_cabang,
						'KETERANGAN'	=> str_replace('%20', ' ', $keterangan),
						'USERNAME'	=> $username);
		$this->dbasemodel->insertData('tbl_pinjaman_d', $save);
		
		
		$ceklst = $this->dbasemodel->loadsql("Select * FROM checklist_teller WHERE TGL_AWAL='".date("Y-m-d")."' AND KODECABANG='".$kode_cabang."'");
		
		if($ceklst->num_rows()>0)
		{
			
			$rchek	= $ceklst->row();
			$nom 	= $rchek->NOMINAL_PINJ + $angsuran;
			$where  = "IDCEKTELLER = '". $rchek->IDCEKTELLER."' ";
			$datacheclist = array("NOMINAL_PINJ"=>$nom, "APPROVAL" => '', "STATUS" => 0);
			$this->dbasemodel->updateData("checklist_teller", $datacheclist, $where);
			
		}
		else
		{
			$datacheclist = array("TGL_AWAL"   => date("Y-m-d", strtotime($tgl_trx)),
								"NOMINAL_PINJ" => $angsuran,
								"KODEPUSAT"    => $kode_pusat,
								"KODECABANG"   => $kode_cabang);//"JENIS"=>"Angsuran"
								
			$this->dbasemodel->insertData("checklist_teller", $datacheclist);
		}
            
        $json = array('code'=>200, 'msg'=>'Transaksi pembayaran angsuran telah disimpan.');
		echo json_encode($json);

    }
    public function jenis_kas($kodecabang) {
		
		$query	=	$this->dbasemodel->loadsql("SELECT IDAKUN, NAMA_KAS
        FROM
            jenis_kas
        WHERE 
            TMPL_BAYAR = 'Y'
           AND KODECABANG = '$kodecabang'");
		
		//$result	=	$this->tree->result_tree('PARENT', 'IDAKUN', $query->result_array());
							
        $json= array();
        foreach($query->result() as $row){
            $json[] = array('idakun'=>$row->IDAKUN, 'namakas'=>$row->NAMA_KAS);
        }

        echo json_encode($json);
	}

    


    function list_setor_pinjaman($idkolektor){
        $this->db->select(array(
            'tbl_pinjaman_d.IDPINJAM',
            'tbl_pinjaman_d.TGL_BAYAR',
            'm_anggota.NAMA',
            'tbl_pinjaman_d.JUMLAH_BAYAR'
            //, 'tbl_pinjaman_d.ANGSURAN_KE','tbl_pinjaman_d.KET_BAYAR', 'tbl_pinjaman_d.POKOKBAYAR', 'tbl_pinjaman_d.BASILBAYAR', 'tbl_pinjaman_d.DENDA_RP'
        ));
        $this->db->join('tbl_pinjaman_h','tbl_pinjaman_h.IDPINJM_H = tbl_pinjaman_d.IDPINJAM','LEFT');
        $this->db->join('m_anggota','m_anggota.IDANGGOTA = tbl_pinjaman_h.ANGGOTA_ID','LEFT');
        $this->db->where('m_anggota.KODECABANG',$idkolektor);
        $this->db->order_by('IDPINJ_D','DESC');
        $query = $this->db->get('tbl_pinjaman_d')->result();
        $json = array();
        if(!empty($query)){
            foreach($query as $row){
                $json[] = array(
                    'code'=>200,
                    'angsuranke' => number_format($row->ANGSURAN_KE,0,",","."),
                    'pokok' => number_format($row->POKOKBAYAR,0,",","."),
                    'basil' => number_format($row->BASILBAYAR,0,",","."),
                    'denda' => number_format($row->DENDA_RP,0,",","."),
                    'idpinjaman'=>$row->IDPINJAM,
                    'ket'=>$row->KET_BAYAR,
                    'tglbayar'=>date('d M Y',strtotime($row->TGL_BAYAR)),
                    'nama'=>$row->NAMA,
                    'jumlah'=>number_format($row->JUMLAH_BAYAR,0,",",".")
                );
            }
        }else{
            $json[] = array('code'=>500);
        }

        echo json_encode($json);

    }


    function detail_setor_pinjaman(){
        $data = json_decode(trim(file_get_contents('php://input')), true);
        $iduser = $data->iduser;

    }

    function detailpinjaman($id){
        $this->db->where(array(
            'tbl_pinjaman_h.ANGGOTA_ID'=>$id,
            'LUNAS'=>'Belum'
        ));

        $query = $this->db->get('tbl_pinjaman_h');

        return $query->row();
    }


    function detailangsuranbayar($idpinjaman){
        $this->db->select('MAX(ANGSURAN_KE) as angske');
        $query = $this->db->get_where('tbl_pinjaman_d',array('IDPINJAM'=>$idpinjaman));
        
        if($query->num_rows() > 0){
            $data = $query->row();
            $angs = intval($data->angske) + 1;
        }else{
            $angs = 1;
        }

        return $angs;

    }    

    function datanasabah($kodepusat, $kodecabang,$num=null,$offset=null){
       
        $this->db->where(array(
            'KODEPUSAT'=>$kodepusat,
            'KODECABANG'=>$kodecabang
        ));
        $this->db->order_by('NAMA','ASC');
        $query = $this->db->get('m_anggota',$num,$offset)->result();
        $json  = array();
        if(!empty($query)){
            foreach($query as $row){
                $json[] = array(
                    'code'=>200,
                    'idanggota'=>$row->IDANGGOTA,
                    'nama'=>$row->NAMA,
                    'alamat'=>$row->ALAMAT,
                    'kodepusat'=>$row->KODEPUSAT,
                    'kodecabang'=>$row->KODECABANG,
                    'kodeanggota'=>$row->NO_ANGGOTA,
                    'ktp'=>$row->NO_IDENTITAS,
                    'lng'=>$row->lng,
                    'lat'=>$row->lat
                );
            }
        }else{
            $json[] = array('code'=>500);
        }

        echo json_encode($json);
        
    }


    function kas_simpanan($idkolektor,$num=null,$offset=null){
        $this->db->select(array(
            'm_anggota.NAMA',
            'm_anggota.KODEPUSAT',
            'm_anggota.KODECABANG',
            'm_anggota.NO_ANGGOTA',
            'm_anggota.ALAMAT',
            'SUM(transaksi_simp.JUMLAH) as total'
        ));

        $this->db->join('m_anggota','m_anggota.IDANGGOTA = transaksi_simp.ID_ANGGOTA','LEFT');
        $this->db->where('transaksi_simp.IDKOLEKTOR',$idkolektor);
        $this->db->group_by('transaksi_simp.ID_ANGGOTA');
        $query = $this->db->get('transaksi_simp',$num,$offset)->result();
        $json  = array();
        if(!empty($query)){
            foreach($query as $row){
                $json[] = array(
                    'code'=>200,
                    'nama'=>$row->NAMA,
                    'alamat'=>$row->ALAMAT,
                    'kodepusat'=>$row->KODEPUSAT,
                    'kodecabang'=>$row->KODECABANG,
                    'kodeanggota'=>$row->NO_ANGGOTA,
                    'jumlah'=>number_format($row->total)
                );
            }
        }else{
            $json[] = array('code'=>500);
        }

        echo json_encode($json);
    }


    function kas_pinjaman($idkolektor,$num=null,$offset=null){
        $this->db->select(array(
            'm_anggota.NAMA',
            'm_anggota.KODEPUSAT',
            'm_anggota.KODECABANG',
            'm_anggota.NO_ANGGOTA',
            'm_anggota.ALAMAT',
            'SUM(tbl_pinjaman_d.JUMLAH_BAYAR) as total'
        ));

        $this->db->join('tbl_pinjaman_h','tbl_pinjaman_h.IDPINJM_H = tbl_pinjaman_d.IDPINJAM','LEFT');
        $this->db->join('m_anggota','m_anggota.IDANGGOTA = tbl_pinjaman_h.ANGGOTA_ID','LEFT');
        $this->db->where('tbl_pinjaman_d.IDKOLEKTOR',$idkolektor);
        $this->db->group_by('tbl_pinjaman_h.ANGGOTA_ID');
        $query = $this->db->get('tbl_pinjaman_d',$num,$offset)->result();
        $json  = array();
        if(!empty($query)){
            foreach($query as $row){
                $json[] = array(
                    'code'=>200,
                    'nama'=>$row->NAMA,
                    'alamat'=>$row->ALAMAT,
                    'kodepusat'=>$row->KODEPUSAT,
                    'kodecabang'=>$row->KODECABANG,
                    'kodeanggota'=>$row->NO_ANGGOTA,
                    'jumlah'=>number_format($row->total,0,",",".")
                );
            }
        }else{
            $json[] = array('code'=>500);
        }

        echo json_encode($json);
    }

    function detailnasabahfull($id){
        $query = $this->db->get_where('m_anggota',['IDANGGOTA'=>$id])->result();
        $json  = array();
        if(!empty($query)){
            foreach($query as $row){
                $json[] = array(
                    'code'=>200,
                    'nama'=>$row->NAMA,
                    'alamat'=>$row->ALAMAT,
                    'kodepusat'=>$row->KODEPUSAT,
                    'kodecabang'=>$row->KODECABANG,
                    'kodeanggota'=>$row->NO_ANGGOTA,
                    'ktp'=>$row->NO_IDENTITAS,
                    'lng'=>$row->lng,
                    'lat'=>$row->lat,
                    'tempatlahir'=>$row->TMP_LAHIR,
                    'tanggallahir'=>$row->TGL_LAHIR,
                    'pekerjaan'=>$row->PEKERJAAN,
                    'email'=>$row->EMAIL,
                    'statuskawin'=>$row->STATUS,
                    'norek'=>$row->NOREK,
                    'kodebank'=>$row->KODEBANK,
                    'namabank'=>$row->NAMA_BANK,
                    'namaibu'=>$row->IBU_KANDUNG,
                    'namasaudara'=>$row->NAMA_SAUDARA,
                    'alamatsaudara'=>$row->ALMT_SAUDARA,
                    'hubsaudara'=>$row->HUB_SAUDARA,
                    'telpsaudara'=>$row->TELP_SAUDARA
                );
            }
        }else{
            $json[] = array('code'=>500);
        }

        echo json_encode($json);
    }

    function simpanprofil(){
        
        $geturl = file_get_contents('php://input');
        $data = json_decode($geturl);
        $nama = $data->nama;
        
        $iduser = $data->iduser;

        if(isset($password)){
            $password =$data->password;
            if($password <> ""){
                $data_user = array(
                    'NAMA'=>$nama,
                    'PASSWORD'=>md5($password)
        
                );
            }else{
                $data_user = array(
                    'NAMA'=>$nama
                );
            }
            
        }else{
            $data_user = array(
                'NAMA'=>$nama
            );
        }
        
        $this->db->where('IDUSER',$iduser);
        $this->db->update('m_user',$data_user);

        echo json_encode(array('code'=>200));

    }


    public function simpanposisi(){
        $arrInsert = array('lat'=>$this->input->post('lat'),'lng'=>$this->input->post('lng'));
        $where 				= 	"IDANGGOTA=".$this->input->post('idanggota');
		//var_dump($_POST);
		$insertProc			=	$this->dbasemodel->updateData('m_anggota', $arrInsert,$where);
		if($insertProc){
			$responseArr	=	array("status"=>200, "msg"=>"");
		} else {
			$responseArr	=	array("status"=>103, "msg"=>"Gagal, data sudah ada sebelumnya. Harap masukkan data lain");
        }
        
        echo json_encode($responseArr);
    }



    public function carinasabah(){
        $geturl = file_get_contents('php://input');
        $data = json_decode($geturl);
        if(isset($data->nama)){
            $nama = $data->nama;
        } else{
            $nama = "";
        }

        if(isset($data->alamat)){
            $alamat = $data->alamat;
        } else{
            $alamat = "";
        }

        if(isset($data->ktp)){
            $ktp = $data->ktp;
        } else{
            $ktp = "";
        }


        if($nama == "" && $ktp == "" && $alamat == ""){
            $json = array('code'=>500);
        }else{
            $kodepusat = $data->kodepusat;
            $kodecabang = $data->kodecabang;

            if($nama <> ""){
                $this->db->like('NAMA',$nama);
            }
    
            if($ktp <> ""){
                $this->db->where('NO_IDENTITAS',$ktp);
            }
    
            if($alamat <> ""){
                $this->db->like('ALAMAT',$alamat);
            }

            $this->db->where([
                'm_anggota.KODECABANG'=>$kodecabang,
                'm_anggota.KODEPUSAT'=>$kodepusat
            ]);
            $this->db->group_by('m_anggota.IDANGGOTA');
            $query = $this->db->get('m_anggota')->result();
    
            $json = array();
            if(!empty($query)){
                foreach($query as $row){
                    $json[] = array(
                        'code'=>200,
                        'nama'=>$row->NAMA,
                        'alamat'=>$row->ALAMAT,
                        'kodepusat'=>$row->KODEPUSAT,
                        'kodecabang'=>$row->KODECABANG,
                        'kodeanggota'=>$row->NO_ANGGOTA,
                        'ktp'=>$row->NO_IDENTITAS,
                        'lng'=>$row->lng,
                        'lat'=>$row->lat,
                        'tempatlahir'=>$row->TMP_LAHIR,
                        'tanggallahir'=>$row->TGL_LAHIR,
                        'pekerjaan'=>$row->PEKERJAAN,
                        'email'=>$row->EMAIL,
                        'statuskawin'=>$row->STATUS,
                        'norek'=>$row->NOREK,
                        'kodebank'=>$row->KODEBANK,
                        'namabank'=>$row->NAMA_BANK,
                        'namaibu'=>$row->IBU_KANDUNG,
                        'namasaudara'=>$row->NAMA_SAUDARA,
                        'alamatsaudara'=>$row->ALMT_SAUDARA,
                        'hubsaudara'=>$row->HUB_SAUDARA,
                        'telpsaudara'=>$row->TELP_SAUDARA
                    );
                }
            }else{
                $json[] = array('code'=>500);
            }
        }
        
        echo json_encode($json);    
    }

    public function updatenasabah(){
        $geturl = file_get_contents('php://input');
        $data = json_decode($geturl);

        $idanggota = $data->idanggota;
        $lat = $data->lat;
        $lng = $data->lng;

        $datacheclist = array(
            "lat"=>$lat, 
            "lng" => $lng
        );
        
        $where = array('IDANGGOTA'=>$idanggota);
        $update = $this->dbasemodel->updateData("m_anggota", $datacheclist, $where);

        if($update){
            $json = array('code'=>200,'msg'=>'nasabah berhasil diupdate');

        }else{
            $json = array('code'=>500);
        }

        echo json_encode($json);
            
    }
    
    public function struk_pinjaman($id){
		//if(!is_logged_in()){
		//	redirect('/auth_login');	
		//}
		$this->load->library('pdf');
		$this->load->library('terbilang');

		$cekPembayaran 	= $this->dbasemodel->loadsql("SELECT * FROM `tbl_pinjaman_d` WHERE IDPINJ_D = '$id'");
		$key = $cekPembayaran->row();
		
		if($cekPembayaran->num_rows()>0){
            $json = array(
                'code'=>200,'tglbayar'=>tgl_indo($key->TGL_BAYAR),
                'idpinjaman'=>$id,'angsuranke'=>$key->ANGSURAN_KE,
                'ket'=>$key->KET_BAYAR,'pokok'=>toRp($key->POKOKBAYAR),
                'basil'=>toRp($key->BASILBAYAR), 'jumlah'=>toRp($key->JUMLAH_BAYAR), 'denda'=>toRp($key->DENDA_RP)
            );

        }else{
            $json = array('code'=>500);
        }
		
        echo json_encode($json);
	}

public function struk_simpan($id){
		//if(!is_logged_in()){
		//	redirect('/auth_login');	
		//}
		$this->load->model('ModelSimpanan'); 
		$this->load->library('terbilang');

        $this->db->select('*');
		$this->db->from('transaksi_simp');
		$this->db->where('ID_TRX_SIMP',$id);
		$qs = $this->db->get()->row();
		
		if(!empty($qs)){
		    $output = $this->ModelSimpanan->getCabangh($qs->KODECABANG);
		    $anggota = $this->ModelSimpanan->get_data_anggota($qs->ID_ANGGOTA);
		    $jns_simpan = $this->ModelSimpanan->get_jenis_simpan($qs->ID_JENIS);
            $json = array(
                'code'=>200,'tgl'=>tgl_indo($qs->TGL_TRX),
                'no'=>'TRD'.sprintf('%05d', $qs->ID_TRX_SIMP),'jumlah'=>toRp($qs->JUMLAH),
                'no_anggota' => $anggota->KODEPUSAT.".".$anggota->KODECABANG.".".$anggota->NO_ANGGOTA,
                'nama' => $anggota->NAMA, 'jenis' => $jns_simpan->JENIS_TRANSAKSI, 'terbilang' => $this->terbilang->eja($qs->JUMLAH) .' RUPIAH'
            );

        }else{
            $json = array('code'=>500);
        }
		
        echo json_encode($json);
	}


}