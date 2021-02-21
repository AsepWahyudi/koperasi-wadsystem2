<div class="col-sm-12">
    <div class="panel">
        <div class="panel-header b-primary bt-sm">
            <div class="row">
                <div class="col-sm-9 col-lg-9">
                    <h4 class="color-primary">Tambah Transfer Kas</h4>
                </div>
                <div class="col-sm-3 col-lg-3 ">
                    <a href="<?php  echo base_url();?>kas-transfer" class="btn btn-primary btn-block" >
                        <i class="fa fa-angle-double-left"></i> 
                        Data Transfer Antar Koprasi
                    </a>
                </div>
            </div>
        </div>
    
        <div class="panel-content">
            <?php  
                if(isset($data_source)) {
                    $row = $data_source->row();
                }
            ?>
            <form method="post" action="<?php  echo base_url();?>transaksi_kas/transfer/<?php  echo ( isset($row->IDTRAN_KAS) ? 'update?id=' . $row->IDTRAN_KAS : 'save')?>">
                <div class="form-group">
                    <label for="">Tanggal Transaksi :</label>
                    <input id="default-datepicker" class="form-control" placeholder="Tanggal Transaksi" type="text" value="<?php  echo (isset($row->IDTRAN_KAS) ? date('d/m/Y', strtotime($row->TGL)): date('d/m/Y'))?>" name="tgl">
                    
                <div class="form-group">
                    <label for="">Jumlah :</label>
                    <input class="form-control" placeholder="100000" type="number" name="jumlah" value="<?php  echo (isset($row->IDTRAN_KAS) ? $row->JUMLAH : '')?>">
                </div>
                
                <div class="form-group">
                    <label for="">Keterangan :</label>
                    <input class="form-control" placeholder="Keterangan" type="text" name="keterangan" value="<?php  echo (isset($row->IDTRAN_KAS) ? $row->KETERANGAN : '')?>">
                </div>
                <div class="form-group">
                    <label for="">Ambil Dari Kas :</label>
                    <select class="form-control" name="dari_kas_id">
                    <?php 
                        if($jenis_kas->num_rows() > 0) {
                            foreach($jenis_kas->result() as $res) {
                                echo '<option value="'. $res->IDAKUN .'" '. (isset($row->IDTRAN_KAS) ? ($row->DARI_KAS_ID == $res->ID_JNS_KAS ? 'selected' : ''): '') .'>'. $res->NAMA_KAS .'</option>';
                            }
                        }
                    ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="">Transfer Ke Kas :</label>
                    <select class="form-control" name="untuk_kas_id">
                   <?php 
                        if($jenis_kas->num_rows() > 0) {
                            foreach($jenis_kas->result() as $res) {
                                echo '<option value="'. $res->IDAKUN .'" '. (isset($row->IDTRAN_KAS) ? ($row->UNTUK_KAS_ID == $res->ID_JNS_KAS ? 'selected' : ''): '') .'>'. $res->NAMA_KAS .'</option>';
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
