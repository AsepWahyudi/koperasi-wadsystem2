<?php
    class Otherdbmodel extends CI_Model {
        function __construct(){
            parent::__construct();
            //load our second db and put in $db2
            $this->db2 = $this->load->database('default', TRUE);
        }

        public function getsecondUsers(){
            $query = $this->db2->get('members');
            return $query->result(); 
        }

    }
?>