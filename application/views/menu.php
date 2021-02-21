
<?php 
$url = base_url(uri_string());
$exp = explode("/", $url);
if(count($exp)>1){
  $datamenu['curact'] = $exp[3];
}else{
  $datamenu['curact'] = "";
}
// echo $this->session->userdata("wad_level");
// echo $this->session->userdata("wad_kodecabang");
if($this->session->userdata("wad_level") == "kepala_cabang")
{
	$this->load->view("menukepalacabang",$datamenu);
}
elseif($this->session->userdata("wad_level") == "analis" OR $this->session->userdata("wad_level") == "finance")
{
	$this->load->view("menuanalisfinance",$datamenu);
}
else
{
	$this->load->view("menuadmin",$datamenu);
}
?>  
 
