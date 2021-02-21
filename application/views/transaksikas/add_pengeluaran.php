<div class="col-sm-12">
    <div class="panel">
        <div class="panel-header b-primary bt-sm">
            <div class="row">
                <div class="col-sm-9 col-lg-10">
                    <h4 class="color-primary">Tambah Pengeluaran</h4>
                </div>
                <div class="col-sm-3  col-lg-2 ">
                    <a href="<?php  echo base_url();?>kas-pengeluaran" class="btn btn-primary btn-block" >
                        <i class="fa fa-angle-double-left"></i> 
                        Data Pengeluaran
                    </a>
                </div>
            </div>
        </div>
    
        <div class="panel-content">
            <?php 
                
                if(isset($data_source)) {
                    $row    =   $data_source->row();
                }
            ?>
            <form method="post" action="<?php  echo base_url();?>transaksi_kas/pengeluaran/<?php  echo ( isset($row->IDTRAN_KAS) ? 'update?id=' . $row->IDTRAN_KAS: 'save')?>">
                <div class="form-group">
                    <label for="">Tanggal Transaksi :</label>
                    <input id="default-datepicker" class="form-control" placeholder="Tanggal Transaksi" type="text" value="<?php  echo (isset($row->IDTRAN_KAS) ? date('d/m/Y', strtotime($row->TGL)): date('d/m/Y'))?>" name="tgl">
                </div>
                <div class="form-group">
                    <label for="">Jumlah :</label>
                    <input class="form-control" placeholder="100000" type="number" name="jumlah" value="<?php  echo (isset($row->IDTRAN_KAS) ? $row->JUMLAH : '')?>">
                </div>
                
                <div class="form-group">
                    <label for="">Keterangan :</label>
                    <input class="form-control" placeholder="Keterangan" type="text" name="keterangan" value="<?php  echo (isset($row->IDTRAN_KAS) ? $row->KETERANGAN : '')?>">
                </div>
                <div class="form-group">
                    <label for="">Dari Kas :</label>
                    <select class="form-control" name="dari_kas_id">
                    <?php 
                        if($dari_kas->num_rows() > 0) {
                            $result =   $dari_kas->result_array();
                            
                            foreach($result as $key=>$res) {
                                echo '<option value="'. $res['IDAKUN'].'">'. $res['NAMA_KAS'] .'</option>';
                            }
                        }
                    ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="">Untuk Akun :</label>
                    <select class="form-control" name="jenis_trans">
                    <?php 
                        if($untuk_akun->num_rows() > 0) {
                            foreach($untuk_akun->result() as $res) {
                                echo '<option value="'. $res->IDAKUN .'" '. (isset($row->IDTRAN_KAS) ? ($row->JENIS_TRANS == $res->IDAKUN ? 'selected' : ''): '') .'>'. $res->JENIS_TRANSAKSI .'</option>';
                            }
                        }
                    ?>
                    </select>
                </div>
                <div class="form-buttons-w">
                    <button class="btn btn-primary" type="submit"> Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6">
        
        <div class="element-box">
            
        </div>
    </div>
</div>
