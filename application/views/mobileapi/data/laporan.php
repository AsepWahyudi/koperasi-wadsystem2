<div class="br-pageheader">
	<nav class="breadcrumb pd-0 mg-0 tx-12">
	  <a class="breadcrumb-item" href="#">Dashboard</a>
	  <span class="breadcrumb-item active">Laporan</span>
	</nav>
</div><!-- br-pageheader -->


<div class="br-pagebody">

	<div class="br-section-wrapper">
          <h6 class="br-section-label">Laporan Transaksi</h6>
            <form method='post' action="<?php  echo base_url();?>laporan">
			 <div class="form-group mg-b-0">
			  <label>Date Start</label>
			  <input type="text" style="width: 14%;display: inline-block;margin-left: 20px;" name='datestart' value='<?php  if(isset($pagdatestart)) { echo $pagdatestart;} else{ echo '';} ?>' class="form-control form-control-datepicker tx-14" data-language="en" placeholder="Date of Start">
              </br>
              <label>Date End</label>
			  <input type="text" style="width: 14%;display: inline-block;margin: 9px 0px 8px 27px;" name='dateend' value='<?php  if(isset($pagdateend)) { echo $pagdateend;} else{ echo '';} ?>' class="form-control form-control-datepicker tx-14" data-language="en" placeholder="Date of End">
              </br>
              <button style="margin-bottom: 40px;margin-left: 87px;" type="submit" class="btn btn-primary tx-uppercase btn-sm">Tampilkan</button>
             
			</div><!-- form-group -->
             
			
			
            <div class="table-wrapper">
            <table id="trxtable" class="table display nowrap">
              <thead>
                <tr>
				  <th>No Transaksi</th>
                  <th>Hp</th>
				  <th>Idpel</th>
				  <th class="text-center">Produk</th>
				  <th class="text-center">Harga</th>
				  <th class="text-center">Payment</th>
				  <th>Status</th>
                  
                </tr>
              </thead>
              <tbody>
               <?php  
                    if($result){       
					$i=1;
					foreach($result->result() as $key){
						if($key->STATUS=="0"){
							$status =  '<span class="square-8 bg-warning mg-r-5 rounded-circle"></span> Pending';
						}elseif($key->STATUS=="1"){
							$status =  '<span class="square-8 bg-success mg-r-5 rounded-circle"></span> Sukses';
						}else{
							$status =  '<span class="square-8 bg-danger mg-r-5 rounded-circle"></span> Gagal';
						}
			   ?> 
			   <tr>
				  <td><a href="<?php  echo base_url();?>detailtrx/<?php  echo $key->IDTRX?>" class="tx-info"><?php  echo $key->NOTRX?></a>
				  <br><span class="tx-12 tx-roboto"><?php  echo  date("y/m/d", strtotime($key->TGL))?></span></td>
                  <td class="tx-inverse"><?php  echo $key->NOHP?></td>
				  <td><?php  echo $key->IDPEL?></td>
				  <td class="text-center"><?php  echo $key->PRODUK?></td>
				  <td class="text-right"><?php  echo toRp($key->HARGA_JUAL)?></td>
				  <td class="text-center"><?php  echo $key->PAYMENT_VIA?></td>
				  <td><?php  echo $status?></td>
                 

                </tr>
					<?php  $i++;}} ?>
              </tbody>
            </table>
          </div><!-- table-wrapper -->
          <?php  echo $links?>
          </form>   
		  
	</div>
</div>

</div><!-- br-pagebody -->