<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Produk extends CI_Controller {

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
		$jenis = ($this->uri->segment(2)=="pembelian")? "0":"1";
		$xjenis = ($this->uri->segment(2)=="pembelian")? "Pembelian":"Penjualan";
		
		$data['page']		= "produk";
		
		$hit					= $this->dbasemodel->countData("m_product WHERE PRDINQ='$jenis'",'IDPRODUK');
		$trow 					= $hit->row();
		$config["per_page"] 	= 20;
		$config["total_rows"] 	= $trow->TOTAL;
		$config["base_url"] 	= base_url()."produk/".$this->uri->segment(2)."/";
		$config["uri_segment"] 	= 3;
		$config['use_page_numbers'] = TRUE;
		
		$this->pagination->initialize($config); 
		
		/*$data['result'] 	= $this->dbasemodel->loadsql("SELECT A.*,
														  B.KATEGORI
														  FROM m_product A LEFT JOIN
														  m_kat_prod B ON A.KATEGORI=B.IDKAT
														  WHERE A.PRDINQ='$jenis'");*/
														  
		$page_num 				= $this->uri->segment(3);
		$page 					= ($page_num  == NULL) ? 0 : ($page_num * $config['per_page']) - $config['per_page'];
		$data['result']			= $this->dbasemodel->getProduk($jenis,$config["per_page"], $page);
		$data["links"] 			= $this->pagination->create_links();
		
		$data['headtitle']	= "Produk ".$xjenis;
		$this->load->view('mobileapi/dashboard',$data);
	}
	
	function tambahproduk()
	{
		if(!is_logged_in()){
			redirect('/auth/sign-in');	
		}
		$pgs = ($this->uri->segment(2)=="pembelian")? "addproduk_beli":"addproduk_bayar";
		$data['headtitle']	= "Tambah Produk";
		$data['page']		= $pgs;//"addproduk";
		$data['response']	= "";
		if($this->input->post())
		{
			//var_dump($_POST);
			if($_FILES['gambar']['name'] != "")
			{
				$config['encrypt_name'] = TRUE;
				$config['upload_path'] = './assets/produk/';
				$config['allowed_types'] = 'gif|jpg|png';
				$config['max_size'] = 2000;
				$new_name = time()."_".$_FILES["gambar"]['name'];
				$config['file_name'] = $new_name;
			 
				$this->load->library('upload', $config);
			 
				if ($this->upload->do_upload('gambar')){
					if($this->uri->segment(2)=="pembelian"){
						$datainsert = array('NAMA'=>$this->input->post('nama'),
										'KODE'=>$this->input->post('kode'),
										'HARGA_BELI'=>$this->input->post('hbeli'),
										'HARGA_JUAL'=>$this->input->post('hjual'),
										'KATEGORI'=>$this->input->post('kategori'),
										'KET'=>$this->input->post('keterangan'),
										'PRDINQ'=>$this->input->post('tipe'),
										'GAMBAR'=>$new_name
										);
					}else{
						$datainsert = array('NAMA'=>$this->input->post('nama'),
										'KODE'=>$this->input->post('kode'),
										'ADMIN'=>$this->input->post('admin'),
										'KATEGORI'=>$this->input->post('kategori'),
										'KET'=>$this->input->post('keterangan'),
										'PRDINQ'=>$this->input->post('tipe'),
										'GAMBAR'=>$new_name
										);
					}
				
					$this->dbasemodel->insertData("m_product",$datainsert);

					$data['response']	= '<div class="alert alert-success" role="alert">
				  <strong class="d-block d-sm-inline-block-force">Berhasil</strong> Tambah Produk Berhasil</div>';
				  
					
				}else{
					$data['response']	= '<div class="alert alert-danger" role="alert">
				  <strong class="d-block d-sm-inline-block-force">Gagal</strong> '.$this->upload->display_errors().'</div>';
				}
			}else{
				
					if($this->uri->segment(2)=="pembelian"){
						$datainsert = array('NAMA'=>$this->input->post('nama'),
									'KODE'=>$this->input->post('kode'),
									'HARGA_BELI'=>$this->input->post('hbeli'),
									'HARGA_JUAL'=>$this->input->post('hjual'),
									'KATEGORI'=>$this->input->post('kategori'),
									'KET'=>$this->input->post('keterangan'),
									'PRDINQ'=>$this->input->post('tipe')
									);
					}else{
						$datainsert = array('NAMA'=>$this->input->post('nama'),
										'KODE'=>$this->input->post('kode'),
										'ADMIN'=>$this->input->post('admin'),
										'KATEGORI'=>$this->input->post('kategori'),
										'KET'=>$this->input->post('keterangan'),
										'PRDINQ'=>$this->input->post('tipe')
										);
					}
				
					
				
					$this->dbasemodel->insertData("m_product",$datainsert);

					$data['response']	= '<div class="alert alert-success" role="alert">
				  <strong class="d-block d-sm-inline-block-force">Berhasil</strong> Tambah Produk Berhasil</div>';
				
			}
			
			
		}
		
		$data['katz'] 	= $this->dbasemodel->loadsql("SELECT * FROM m_kat_prod WHERE PARENT='0'");	
		$this->load->view('mobileapi/dashboard',$data);
	}

	function hapusprod()
	{
		
		if($this->uri->segment('2')=="delete")
		{
			$id =  $this->uri->segment(3);
			
			$dataupdate 	= array('HAPUS'=>'1');
			$whereupdate	= "IDPRODUK='".$id."'";
			$this->dbasemodel->updateData("m_product",$dataupdate,$whereupdate);
			
			$this->session->set_flashdata('messagebox', '<div class="alert alert-success"><strong>Success!</strong> Hapus data berhasil.</div>');
			redirect('/produk/pembelian');	
		}
	}

	function editproduk()
	{
		if(!is_logged_in()){
			redirect('/auth/sign-in');	
		}

		$id = $this->uri->segment(3);
		$pgs = ($this->uri->segment(2)=="pembelian")? "editproduk_beli":"editproduk_bayar";
		$data['headtitle']	= "Edit Produk";
		$data['page']		= $pgs;
		$data['response']	= "";
      

		if($this->input->post())
		{
			//var_dump($_POST);
			if($_FILES['gambar']['name'] != "")
			{
				$config['encrypt_name'] = TRUE;
				$config['upload_path'] = './assets/produk/';
				$config['allowed_types'] = 'gif|jpg|png';
				$config['max_size'] = 2000;
				$new_name = time()."_".$_FILES["gambar"]['name'];
				$config['file_name'] = $new_name;
			 
				$this->load->library('upload', $config);
			 
				if ($this->upload->do_upload('gambar')){
					if($this->uri->segment(2)=="pembelian"){
						$dataupdate = array('NAMA'=>$this->input->post('nama'),
										'KODE'=>$this->input->post('kode'),
										'HARGA_BELI'=>$this->input->post('hbeli'),
										'HARGA_JUAL'=>$this->input->post('hjual'),
										'KATEGORI'=>$this->input->post('kategori'),
										'KET'=>$this->input->post('keterangan'),
										'PRDINQ'=>$this->input->post('tipe'),
										'GAMBAR'=>$new_name
										);
					}else{
						$dataupdate = array('NAMA'=>$this->input->post('nama'),
										'KODE'=>$this->input->post('kode'),
										'ADMIN'=>$this->input->post('admin'),
										'KATEGORI'=>$this->input->post('kategori'),
										'KET'=>$this->input->post('keterangan'),
										'PRDINQ'=>$this->input->post('tipe'),
										'GAMBAR'=>$new_name
										);
					}
									
					$whereupdate	= "IDPRODUK='".$id."'";
					$this->dbasemodel->updateData("m_product",$dataupdate,$whereupdate);
					//$this->dbasemodel->insertData("m_product",$datainsert);

					$data['response']	= '<div class="alert alert-success" role="alert">
				  <strong class="d-block d-sm-inline-block-force">Berhasil</strong> Update Produk Berhasil</div>';
				  
					
				}else{
					$data['response']	= '<div class="alert alert-danger" role="alert">
				  <strong class="d-block d-sm-inline-block-force">Gagal</strong> '.$this->upload->display_errors().'</div>';
				}
			}else{
					if($this->uri->segment(2)=="pembelian"){
						$dataupdate = array('NAMA'=>$this->input->post('nama'),
										'KODE'=>$this->input->post('kode'),
										'HARGA_BELI'=>$this->input->post('hbeli'),
										'HARGA_JUAL'=>$this->input->post('hjual'),
										'KATEGORI'=>$this->input->post('kategori'),
										'KET'=>$this->input->post('keterangan'),
										'PRDINQ'=>$this->input->post('tipe')
										);
					}else{
						$dataupdate = array('NAMA'=>$this->input->post('nama'),
										'KODE'=>$this->input->post('kode'),
										'ADMIN'=>$this->input->post('admin'),
										'KATEGORI'=>$this->input->post('kategori'),
										'KET'=>$this->input->post('keterangan'),
										'PRDINQ'=>$this->input->post('tipe')
										);
					}
				
					$whereupdate	= "IDPRODUK='".$id."'";
					$this->dbasemodel->updateData("m_product",$dataupdate,$whereupdate);
					//$this->dbasemodel->insertData("m_product",$datainsert);

					$data['response']	= '<div class="alert alert-success" role="alert">
				  <strong class="d-block d-sm-inline-block-force">Berhasil</strong> Update Produk Berhasil</div>';
				
			}
			
			
		}
		$data['detail']	= $this->dbasemodel->loadsql("SELECT * FROM m_product WHERE IDPRODUK='$id'");
		//$data['katz'] 	= $this->dbasemodel->loadsql("SELECT * FROM m_kat_prod WHERE PARENT='0'");		
        $data['katz'] 	= $this->dbasemodel->loadsql("SELECT * FROM m_kat_prod WHERE HAPUS='0'");

		
		$this->load->view('mobileapi/dashboard',$data);

	}
}