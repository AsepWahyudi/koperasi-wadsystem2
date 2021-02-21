<div class="br-pageheader">
	<nav class="breadcrumb pd-0 mg-0 tx-12">
	  <a class="breadcrumb-item" href="#">Dashboard</a>
	  <span class="breadcrumb-item active"><?php  echo $headtitle?></span>
	</nav>
</div><!-- br-pageheader -->


<div class="br-pagebody">
	<div class="br-section-wrapper">
          
		  <div class="row">
				<div class="col-md-6">
					<h6 class="br-section-label"><?php  echo $headtitle?></h6>
				</div>
				<div class="col-md-6 text-right">
					<a href="<?php  echo base_url();?>addproduk/<?php  echo $this->uri->segment(2)?>" class="btn btn-success btn-sm">Tambah Produk</a>
				</div>
			</div>
			<?php  echo $this->session->flashdata('messagebox');?>
			<hr>
            <div class="table-wrapper">
            <table id="datatable1" class="table display responsive nowrap">
              <thead>
                <tr>
                  <th class="wd-30">No</th>
                  <th>Produk</th>
                  <th>Kode</th>
				  <th>Kategori</th>
				  <?php  if($this->uri->segment(2)=="pembelian"){?>
				  <th class="text-right">Harga Beli</th>
				  <th class="text-right">Harga Jual</th>
				  <?php  }
 else{?>
				  <th class="text-right">Admin</th>
				  <?php  } ?>
                  <th class="wd-150 text-center">Action</th>
                </tr>
              </thead>
              <tbody>
               <?php  
					$i=1;
					foreach($result->result() as $key){ 
			   ?>
			   <tr>
                  <td><?php  echo $i?></td>
                  <td><?php  echo $key->NAMA?></td>
                  <td><?php  echo $key->KODE?></td>
				  <td><?php  echo $key->KATEGORI?></td>
				  <?php  if($this->uri->segment(2)=="pembelian"){?>
				  <td class="text-right"><?php  echo toRp($key->HARGA_BELI)?></td>
				  <td class="text-right"><?php  echo toRp($key->HARGA_JUAL)?></td>
				  <?php  }
 else{?>
				  <td class="text-right"><?php  echo toRp($key->ADMIN)?></td>
				  <?php  } ?>
                  <td>
					<a href="<?php  echo base_url();?>editproduk/<?php  echo $this->uri->segment(2)?>/<?php  echo $key->IDPRODUK?>" class="editprod" alt="edit" title="edit"><i class="ion-android-settings"></i> Edit</a>
                  	&nbsp;
					<a href="javascript:void(0)" class="hapusprod" alt="edit" title="hapus" id="<?php  echo base_url();?>prod/delete/<?php  echo $key->IDPRODUK?>"><i class="ion-android-delete"></i> Hapus</a>
				  </td>

                </tr>
					<?php  $i++;} ?>
              </tbody>
            </table>
          </div><!-- table-wrapper -->
			<?php  echo $links;?>
		  
	</div>
</div>

</div><!-- br-pagebody -->