<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Datauser extends CI_Controller {

	function __construct(){ 
        parent::__construct();
		header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
		$this->load->helper(array('form','url', 'xml','text_helper','date','inflector','app'));
		$this->load->database();
		$this->load->library(array('Pagination','user_agent','session','form_validation','session'));
		$this->load->model('mobileapi/dbasemodel');
		//@session_start();
    }
	public function index()
	{
		if(!is_logged_in()){
			redirect('/auth/sign-in');	
		}
		$data['response']	= 	"";
		if($this->input->post())
		{
				$datainsert = array('NAMA'=>$this->input->post('nama'),
									'USERNAME'=>$this->input->post('username'),
									'PASSWORD'=>md5($this->input->post('password')));
				$this->dbasemodel->insertData("m_user",$datainsert);
			
			$data['response']	= '<div class="alert alert-success" role="alert">
          <strong class="d-block d-sm-inline-block-force">Berhasil</strong> Tambah user berhasil dilakukan.
          </div>';
		}
		$data['page']		= "datauser";
		$data['result'] 	= $this->dbasemodel->loadsql("SELECT * FROM m_user");														  
		$this->load->view('mobileapi/dashboard',$data);
	}
    
    function build_edit($id){
        
        if($id){
            
            $data['response']	= "";
            
            if($this->input->post('submitedit'))
    		{
				
                $dataupdate 	= array('NAMA'=>$this->input->post('nama'),
								        'USERNAME'=>$this->input->post('username'),
                                        'LEVEL'=>$this->input->post('level'),
								  );
                                  
				$whereupdate	= "IDUSER='".$id."' limit 1";
				$this->dbasemodel->updateData("m_user",$dataupdate,$whereupdate);
                
                if($this->input->post('gantipassword')){
                    $datapass       = md5($this->input->post('gantipassword'));
                    $dataupdate 	= array('PASSWORD'=>$datapass);
    				$whereupdate	= "IDUSER='".$id."' limit 1";
                    $this->dbasemodel->updateData("m_user",$dataupdate,$whereupdate);
                }
                
                
                $this->session->set_flashdata('messagebox', '<div class="alert alert-success"><strong>Success!</strong> Update data berhasil.</div>');
                redirect('/datauser');
    		}
            
            $data['headtitle']	= "Edit User";
    		$data['page']		= "edituser";
            $data['detail'] 	= $this->dbasemodel->loadsql("SELECT * FROM m_user WHERE IDUSER ='$id'");            
            return $this->load->view('mobileapi/dashboard',$data);
            exit;
        }
        
        redirect('/datauser');	
    }
    
    function hapuskat()
	{
		if($this->uri->segment('2')=="delete")
		{
			$id =  $this->uri->segment(3);
            if($id){
			 $this->dbasemodel->hapus("m_user where IDUSER = '".$id."' limit 1");
            }
			
			$this->session->set_flashdata('messagebox', '<div class="alert alert-success"><strong>Success!</strong> Hapus data berhasil.</div>');
			redirect('/datauser');	
		}
	}
}