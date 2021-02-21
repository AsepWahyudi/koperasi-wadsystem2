<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Iklan extends CI_Controller {
    
    var $dir_upload = "./assets/iklan"; //'/var/www/html/mobileapi/assets/iklan';

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
				$this->uploadimage();
		}
		$data['page']		= "iklan";
		$data['result'] 	= $this->dbasemodel->loadsql("SELECT * FROM m_iklan");														  
		$this->load->view('mobileapi/dashboard',$data);
	}

	function uploadimage(){

		$dst_dir	= $this->dir_upload;
		
		// chmod($dst_dir,0777);
		
		if(file_exists($dst_dir.DIRECTORY_SEPARATOR.$_FILES["gambar"]["name"])){
			chmod($dst_dir.DIRECTORY_SEPARATOR.$_FILES["gambar"]["name"],0755);
			unlink($dst_dir.DIRECTORY_SEPARATOR.$_FILES["gambar"]["name"]);
		}
		
		$move		=	move_uploaded_file($_FILES["gambar"]["tmp_name"], $dst_dir.DIRECTORY_SEPARATOR.$_FILES["gambar"]["name"]);
			
		if($move){

			$datainsert = array('GAMBAR'=>$_FILES["gambar"]["name"],
								'POSISI'=>$this->input->post('posisi'),
								);
			
			$this->dbasemodel->insertData("m_iklan",$datainsert);
			$this->session->set_flashdata('messagebox', '<div class="alert alert-success"><strong>Success!</strong> data berhasil ditambah.</div>');
            
			$data['response']	= '<div class="alert alert-success" role="alert">
	          <strong class="d-block d-sm-inline-block-force">Berhasil</strong> Tambah iklan berhasil dilakukan.
	          </div>';

		}

	}
    
    function build_edit($id){
        
        if($id){
            
            if($this->input->post('submitedit'))
    		{
				
                $dst_dir	= $this->dir_upload;
        		
                $imagename  = $this->input->post('imagenamepost');
        		if($_FILES["gambar"]["name"]){
        		    // chmod($dst_dir,0777);
            		if(file_exists($dst_dir.DIRECTORY_SEPARATOR.$_FILES["gambar"]["name"])){
            			chmod($dst_dir.DIRECTORY_SEPARATOR.$_FILES["gambar"]["name"],0755);
            			unlink($dst_dir.DIRECTORY_SEPARATOR.$_FILES["gambar"]["name"]);
            		}
            		$move		= move_uploaded_file($_FILES["gambar"]["tmp_name"], $dst_dir.DIRECTORY_SEPARATOR.$_FILES["gambar"]["name"]); 
                    if($move){
                        $imagename  = $_FILES["gambar"]["name"];
                    }             
                }
                
                $dataupdate 	= array('GAMBAR'=>$imagename,
								        'POSISI'=>$this->input->post('posisi'),
								  );
                                  
				$whereupdate	= "IDIKLAN='".$id."' limit 1";
				$this->dbasemodel->updateData("m_iklan",$dataupdate,$whereupdate);
                $this->session->set_flashdata('messagebox', '<div class="alert alert-success"><strong>Success!</strong> update data berhasil.</div>');
                redirect('/iklan');
    		}
            
            $data['headtitle']	= "Edit Iklan";
    		$data['page']		= "editiklan";
    		$data['response']	= "";
            $data['detail'] 	= $this->dbasemodel->loadsql("SELECT * FROM m_iklan WHERE IDIKLAN ='$id'");            
            return $this->load->view('mobileapi/dashboard',$data);
            exit;
        }
        
        redirect('/iklan');	
    }
    
    function hapuskat()
	{
		if($this->uri->segment('2')=="delete")
		{
			$id =  $this->uri->segment(3);
            if($id){
			 $this->dbasemodel->hapus("m_iklan where IDIKLAN = '".$id."' limit 1");
            }
			
			$this->session->set_flashdata('messagebox', '<div class="alert alert-success"><strong>Success!</strong> Hapus data berhasil.</div>');
			redirect('/iklan');	
		}
	}
    
}