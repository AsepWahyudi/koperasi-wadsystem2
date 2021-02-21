<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Data extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url', 'xml', 'text_helper', 'date', 'inflector', 'app'));
        $this->load->database();
        $this->load->library(array('Pagination', 'user_agent', 'session', 'form_validation', 'session'));
        $this->load->model('dbasemodel');
        //@session_start();
    }

    public function reset_data()
    {
        $this->reset_checklist_kolektor();
        $this->reset_checklist_teller();
        $this->reset_jurnal_umum();
        $this->reset_jurnal_umum_dt();
        $this->reset_m_anggota();
        $this->reset_m_anggota_simp();
        $this->reset_m_asuransi();
        $this->reset_m_bpkb();
        $this->reset_m_h2h();
        $this->reset_m_sertifikat();
        $this->reset_tbl_pinjaman_d();
        $this->reset_tbl_pinjaman_h();
        $this->reset_tbl_reset();
        $this->reset_temp_kredit();
        $this->reset_temp_kredit_bayar();
        $this->reset_temp_mudharabah();
        $this->reset_temp_mudharabah_lama();
        $this->reset_transaksi_kas();
        $this->reset_transaksi_simp();
        $this->reset_v_transaksi();
        $this->reset_vtransaksi();
        $this->reset_vtransaksi_dt();
        $this->reset_saldo_awal();
    }

    public function reset_checklist_kolektor()
    {
        $sql = sprintf("TRUNCATE TABLE checklist_kolektor;");
        $query = $this->dbasemodel->loadsql($sql);
        echo $query;
    }

    public function reset_checklist_teller()
    {
        $sql = sprintf("TRUNCATE TABLE checklist_teller;");
        $query = $this->dbasemodel->loadsql($sql);
        echo $query;
    }

    public function reset_jns_angsuran()
    {
        $sql = sprintf("TRUNCATE TABLE jns_angsuran;");
        $query = $this->dbasemodel->loadsql($sql);
        echo $query;
    }

    public function reset_jurnal_umum()
    {
        $sql = sprintf("TRUNCATE TABLE jurnal_umum;");
        $query = $this->dbasemodel->loadsql($sql);
        echo $query;
    }

    public function reset_jurnal_umum_dt()
    {
        $sql = sprintf("TRUNCATE TABLE jurnal_umum_dt;");
        $query = $this->dbasemodel->loadsql($sql);
        echo $query;
    }
    
    public function reset_m_anggota()
    {
        $sql = sprintf("TRUNCATE TABLE m_anggota;");
        $query = $this->dbasemodel->loadsql($sql);
        echo $query;
    }

    public function reset_m_anggota_simp()
    {
        $sql = sprintf("TRUNCATE TABLE m_anggota_simp;");
        $query = $this->dbasemodel->loadsql($sql);
        echo $query;
    }
    
    public function reset_m_asuransi()
    {
        $sql = sprintf("TRUNCATE TABLE m_asuransi;");
        $query = $this->dbasemodel->loadsql($sql);
        echo $query;
    }
    
    public function reset_m_bpkb()
    {
        $sql = sprintf("TRUNCATE TABLE m_bpkb;");
        $query = $this->dbasemodel->loadsql($sql);
        echo $query;
    }
    
    public function reset_m_h2h()
    {
        $sql = sprintf("TRUNCATE TABLE m_h2h;");
        $query = $this->dbasemodel->loadsql($sql);
        echo $query;
    }
    
    public function reset_m_sertifikat()
    {
        $sql = sprintf("TRUNCATE TABLE m_sertifikat;");
        $query = $this->dbasemodel->loadsql($sql);
        echo $query;
    }
    
    public function reset_tbl_pinjaman_d()
    {
        $sql = sprintf("TRUNCATE TABLE tbl_pinjaman_d;");
        $query = $this->dbasemodel->loadsql($sql);
        echo $query;
    }
    
    public function reset_tbl_pinjaman_h()
    {
        $sql = sprintf("TRUNCATE TABLE tbl_pinjaman_h;");
        $query = $this->dbasemodel->loadsql($sql);
        echo $query;
    }
    
    public function reset_tbl_reset()
    {
        $sql = sprintf("TRUNCATE TABLE tbl_reset;");
        $query = $this->dbasemodel->loadsql($sql);
        echo $query;
    }
    
    public function reset_temp_kredit()
    {
        $sql = sprintf("TRUNCATE TABLE temp_kredit;");
        $query = $this->dbasemodel->loadsql($sql);
        echo $query;
    }
    
    public function reset_temp_kredit_bayar()
    {
        $sql = sprintf("TRUNCATE TABLE temp_kredit_bayar;");
        $query = $this->dbasemodel->loadsql($sql);
        echo $query;
    }
    
    public function reset_temp_mudharabah()
    {
        $sql = sprintf("TRUNCATE TABLE temp_mudharabah;");
        $query = $this->dbasemodel->loadsql($sql);
        echo $query;
    }
    
    public function reset_temp_mudharabah_lama()
    {
        $sql = sprintf("TRUNCATE TABLE temp_mudharabah_lama;");
        $query = $this->dbasemodel->loadsql($sql);
        echo $query;
    }
    
    public function reset_transaksi_kas()
    {
        $sql = sprintf("TRUNCATE TABLE transaksi_kas;");
        $query = $this->dbasemodel->loadsql($sql);
        echo $query;
    }
    
    public function reset_transaksi_simp()
    {
        $sql = sprintf("TRUNCATE TABLE transaksi_simp;");
        $query = $this->dbasemodel->loadsql($sql);
        echo $query;
    }
    
    public function reset_v_transaksi()
    {
        $sql = sprintf("TRUNCATE TABLE v_transaksi;");
        $query = $this->dbasemodel->loadsql($sql);
        echo $query;
    }
    
    public function reset_vtransaksi()
    {
        $sql = sprintf("TRUNCATE TABLE vtransaksi;");
        $query = $this->dbasemodel->loadsql($sql);
        echo $query;
    }
    
    public function reset_vtransaksi_dt()
    {
        $sql = sprintf("TRUNCATE TABLE vtransaksi_dt;");
        $query = $this->dbasemodel->loadsql($sql);
        echo $query;
    }

    public function execQuery(){
        $sql=$this->input->post('query');
        $query = $this->dbasemodel->loadsql($sql)->result();
        echo json_encode($query);
    }

    public function reset_saldo_awal()
    {
        $sql = sprintf("UPDATE jns_akun SET SALDO_AWAL=0;");
        $query = $this->dbasemodel->loadsql($sql);
        echo $query;
    }
}
