<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Dbasemodel extends CI_Model{
        function __construct(){
        parent::__construct();
    }
	
	function loadsql($sql){
		return $this->db->query($sql);
	}
	function insertData($table,$data){
		$str = $this->db->insert_string($table, $data); 	
		
		return $this->db->query($str);
	}
	function updateData($table, $data, $where){
		$str = $this->db->update_string($table, $data, $where); 	
		//echo $str;
		return $this->db->query($str);
	}
	function hapus($from){
		$sql	= "DELETE FROM $from";
		//echo $sql."<br>";
		return $this->db->query($sql);
	}
		
	public function insertDataProc($table, $data){
		
		$this->db->insert($table, $data);
		$insert_id = $this->db->insert_id();
		return $insert_id;
		
	} 
	
	public function insertOnDuplicate($table, $data, $fieldUpdate)
	{
		$str 	= $this->db->insert_string($table, $data);
		$str	.=	' ON DUPLICATE KEY UPDATE ';
		foreach($fieldUpdate as $key) {
			$str	.=	$key . ' = VALUES('. $key .'), ';
		}
		$sql	=	trim($str, ", ");
		$this->loadsql($sql);
		return $this->db->insert_id();
	}
	
	public function get_id($id, $tabel)
    {
        $q = $this->db->query("select $id + 1 as id from $tabel order by $id desc limit 1");
        $id = "";
        if($q->num_rows()>0)
        {
            foreach($q->result() as $k)
            {
                $kd = $k->id;
            }
        }
        else
        {
            $kd = "1";
        }
        return $kd;
    }
	
}