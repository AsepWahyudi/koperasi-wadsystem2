<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Dbasemodel extends CI_Model{
        function __construct(){
        parent::__construct();
        $this->db = $this->load->database('otherdb', TRUE);
    }
	
	function loadsql($sql){
		return $this->db->query($sql);
	}
	function insertData($table,$data){
		$str = $this->db->insert_string($table, $data);
 	    //echo $str; die; 	
		$this->db->query($str);
	}
	
	function insertTrx($table,$data){
		$str = $this->db->insert_string($table, $data); 	
		$this->db->query($str);
		$insertId = $this->db->insert_id();
		return  $insertId;
	}
	
	function updateData($table, $data, $where){
		$str = $this->db->update_string($table, $data, $where); 	
		//echo $str; die;
		$this->db->query($str);
	}
	function hapus($from){
		$sql	= "DELETE FROM $from";
		//echo $sql."<br>"; die;
		return $this->db->query($sql);
	}
	

	function getDepolist(){
		$sql	= "SELECT A.*,
				   B.EMAIL
				   FROM m_depo A LEFT JOIN m_user B ON A.IDUSER=B.IDUSER
				   ORDER BY IDDEPO DESC ";
		return $this->db->query($sql);
	}
	
	function getUser($a,$b,$cari,$order,$short){
		$sql	= "SELECT IDUSER,EMAIL,HS,TOTALDEPO,IDMASTER,BLNC,GRATIS FROM m_user WHERE (EMAIL LIKE '%$cari%' OR USERNAME LIKE '%$cari%') ORDER BY $order $short LIMIT $a,$b";
		return $this->db->query($sql);
	}
	
	function countData($tbl,$id) 
	{
		$sql = "SELECT COUNT($id)AS TOTAL FROM $tbl";
		//echo $sql;
		$q	= $this->db->query($sql);
		return $q;
	}
	
	function getCategori() 
	{
		$sql = "SELECT A.*,
				  B.KATEGORI AS TIPE
				  FROM m_kat_prod A
				  LEFT JOIN m_kat_prod B ON A.PARENT=B.IDKAT
				  WHERE A.PARENT !='0' AND A.HAPUS='0' ORDER BY A.KATEGORI ASC";
		//echo $sql;
		$q	= $this->db->query($sql);
		return $q;
	}
    
    function getLaporan($limit, $start, $newDatestart, $newDateend) 
	{
		$sql = "SELECT * FROM m_trx where tgl 
                BETWEEN '$newDatestart' and '$newDateend' 
                ORDER BY IDTRX DESC
                LIMIT $start,$limit";
		//echo $sql; //die;
		$q	= $this->db->query($sql);
		return $q;
	}
	
	function getProduk($jenis,$limit, $start) 
	{
		$sql = "SELECT A.*,
				  B.KATEGORI
				  FROM m_product A LEFT JOIN
				  m_kat_prod B ON A.KATEGORI=B.IDKAT
				  WHERE A.PRDINQ='$jenis' ORDER BY A.NAMA ASC LIMIT $start,$limit";
		//echo $sql;
		$q	= $this->db->query($sql);
		return $q;
	}
	
}