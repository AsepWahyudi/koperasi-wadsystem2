<?php

class Apikolektor extends CI_Model{

    
    function __construct()
    {
        parent::__construct();
    }

    function cek_token($username,$token){
        $now = date('Y-m-d H:i:s');

        $this->db->where(array(
            'username'=>$username,
            'token'=>$token,
            'DATE(expired_at) <='=>$now
        ));

        $query =$this->db->get('m_user_token');
        return $query->num_rows();
    }


    function createtoken($data){
        $this->db->insert('m_user_token',$data);
    }

    public function cek_login($username, $password){
        $query = $this->db->get_where('m_user',array(
            'USERNAME'=>$username,
            'PASSWORD'=>md5($password),
            'LEVEL'=>'kolektor',
            'APPROVAL'=>1
        ));

        return $query->num_rows();
    }


    public function ambil_user($id){
        $query = $this->db->get_where('m_user',array('USERNAME'=>$id));
        return $query->result();
    }
}