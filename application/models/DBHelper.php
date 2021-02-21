<?php

class DBHelper extends CI_Model {

	public function __construct(){
		parent::__construct(); 
		$this->load->database();
	}
	
	public function generateEmptyResult($query = null){
		return array("status"=>404, "datastart"=>0, "dataend"=>0, "datatotal"=>0, "pagetotal"=>0, "query"=>$query);
	}

	public function generateResult($result, $basequery, $keyfield, $page, $dataperpage, $datastart, $dataend){
		
		$query		= $this->db->query("SELECT IFNULL(COUNT(".$keyfield."),0) AS TOTAL FROM (".$basequery.") AS A");
		$row		= $query->row_array();
		$datatotal	= $row['TOTAL'];
		$pagetotal	= ceil($datatotal/$dataperpage);
		$startnumber= ($page-1) * $dataperpage + 1;
		$dataend	= $dataend > $datatotal ? $datatotal : $dataend;
		
		return array("status"=>200, "data"=>$result ,"datastart"=>$datastart, "dataend"=>$dataend, "datatotal"=>$datatotal, "pagetotal"=>$pagetotal, "startNumber"=>$startnumber,"query"=>trim($basequery));
	}
	
}