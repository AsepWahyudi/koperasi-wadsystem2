<div class="col-sm-12">
    <div class="panel">
        <div class="panel-header b-primary bt-sm">
            <div class="row">
                <div class="col-sm-10 col-lg-10">
                    <h4 class="color-primary">Mutasi Bank</h4>
                </div>
            </div>
        </div>
    </div>
</div>
<?php  
	$data = json_decode($list, true);
	foreach ($data['data'] as $key) {
		if ($key['status'] == 'ACTIVE') {		
?>
<div class="col-sm-12">
    <div class="panel">
        <div class="panel-header b-primary bt-sm">
            <div class="row">
                <div class="col-sm-8 col-lg-8">
                    <h4 class="color-primary"><?php  echo $key['service_name']; ?> ( <?php  echo $key['account_number']; ?> a/n <?php  echo $key['account_name']; ?> )</h4>
                </div>
                <div class="col-sm-4 col-lg-4">
                    <h4 class="color-primary flr">Balance : Rp. <?php  echo toRp($key['balance']); ?></h4>
                </div>
            </div>
        </div>    
        <div class="panel-content">
        	<table id="responsive-table" class="data-table table table-striped table-hover responsive nowrap" cellspacing="0" width="100%">
                <thead>
	                <tr>
	                    <th>No</th>
	                    <th>Tanggal</th>
	                    <th>Keterangan</th>
	                    <th>Tipe</th>
	                    <th>Jumlah</th>
	                    <th>Saldo</th>
	                </tr>
	            </thead>
	            <tbody>
	            	<?php  
	            		$this->load->library('Cekmutasi/cekmutasi');
	            		$account_number = $key['account_number'];
						$mutasi = json_encode($this->cekmutasi->bank()->mutation(['account_number' => $account_number]));
						$dataM = json_decode($mutasi, true);
						$no = 1;
						foreach ($dataM['response'] as $key) {
	            		
					?>
	                    <tr>
	                        <td><?php  echo $no++?></td>
	                        <td><?php  echo date("d/m/Y h:m", $key['unix_timestamp'])?></td>
	                        <td><?php  echo $key['description']?></td>
	                        <td><?php  echo $key['type']?></td>
	                        <td style="text-align: right;"><?php  echo toRp($key['amount'])?></td>
	                        <td style="text-align: right;"><?php  echo toRp($key['balance'])?></td>
	                    </tr>
	                <?php  }  ?>
	            </tbody>
            </table>
        </div>
    </div>
</div>
<?php  } } ?>