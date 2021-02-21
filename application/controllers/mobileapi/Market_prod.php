<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Market_prod extends CI_Controller {
    
    var $dir_upload = "./assets/produk"; //'/var/www/html/mobileapi/assets/iklan';
    
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
		
        $data['response']	= '';
        $data['title']  	= "Data Market Produk";
        $data['page']		= "datamarket_prod";
		$data['result'] 	= $this->dbasemodel->loadsql("SELECT * FROM m_market_produk");
        
		$this->load->view('mobileapi/dashboard',$data);
	}
    
    function build_add(){
        
        if($this->input->post()){
		        $dst_dir	= $this->dir_upload;
				$imagename  = '';
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
                  
				$datainsert = array(
                                'NAMA'=>$this->input->post('nama'),
                                'KODE'=>$this->input->post('kode'),
                                'HARGA_BELI'=>$this->input->post('harga_beli'),
                                'HARGA_JUAL'=>$this->input->post('harga_jual'),
                                'KATEGORI'=>$this->input->post('kategori'),
                                'KET'=>$this->input->post('ket'),
                                'GAMBAR'=>$imagename,
                                'AKTIF'=>$this->input->post('aktif'),
                              );
                
				$this->dbasemodel->insertData("m_market_produk",$datainsert);
                $this->session->set_flashdata('messagebox', '<div class="alert alert-success"><strong>Success!</strong> data berhasil ditambahkan.</div>');
                redirect('/market_prod');                                      
		}
        
        $data['headtitle']	= "Add Kategori Produk";
		$data['page']		= "editmarket_prod";
		$data['response']	= "";
        $data['detail'] 	= '';  
        $data['katz'] 	    = $this->dbasemodel->loadsql("SELECT * FROM m_market_kat");          
        return $this->load->view('mobileapi/dashboard',$data);
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
                
                $dataupdate 	= array(
                                    'NAMA'=>$this->input->post('nama'),
                                    'KODE'=>$this->input->post('kode'),
                                    'HARGA_BELI'=>$this->input->post('harga_beli'),
                                    'HARGA_JUAL'=>$this->input->post('harga_jual'),
                                    'KATEGORI'=>$this->input->post('kategori'),
                                    'KET'=>$this->input->post('ket'),
                                    'GAMBAR'=>$imagename,
                                    'AKTIF'=>$this->input->post('aktif'),
                                  );
                                  
				$whereupdate	= "IDPRODUK	='".$id."' limit 1";
				$this->dbasemodel->updateData("m_market_produk",$dataupdate,$whereupdate);
                $this->session->set_flashdata('messagebox', '<div class="alert alert-success"><strong>Success!</strong> update data berhasil.</div>');
                redirect('/market_prod');
    		}
            
            $data['headtitle']	= "Edit Kategori Produk";
    		$data['page']		= "editmarket_prod";
    		$data['response']	= "";
            $data['detail'] 	= $this->dbasemodel->loadsql("SELECT * FROM m_market_produk WHERE IDPRODUK ='$id'");  
            $data['katz'] 	    = $this->dbasemodel->loadsql("SELECT * FROM m_market_kat");          
            return $this->load->view('mobileapi/dashboard',$data);
            exit;
        }
        
        redirect('/market_prod');	
    }
    
    function hapuskat()
	{
		if($this->uri->segment('2')=="delete")
		{
			$id =  $this->uri->segment(3);
            if($id){
			 $this->dbasemodel->hapus("m_market_produk where IDPRODUK = '".$id."' limit 1");
            }
			
			$this->session->set_flashdata('messagebox', '<div class="alert alert-success"><strong>Success!</strong> Hapus data berhasil.</div>');
			redirect('/market_prod');	
		}
	}
}