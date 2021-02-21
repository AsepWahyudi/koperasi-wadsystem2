<div class="col-sm-12">
    <div class="panel">
        <div class="panel-header b-primary bt-sm">
            <div class="row">
                <div class="col-sm-9 col-lg-10">
                    <h4 class="color-primary">Tambah Transfer Antar Bank</h4>
                </div>
                <div class="col-sm-3  col-lg-2 ">
                    <a href="<?php  echo base_url();?>kas-transfer-bank" class="btn btn-primary btn-block" >
                        <i class="fa fa-angle-double-left"></i> 
                        Data Transfer Antar Bank
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
            <form method="post" action="<?php  echo base_url();?>transaksi_kas/transfer/<?php  echo ( isset($row->IDTRAN_KAS) ? 'updatetrasferbank?id=' . $row->IDTRAN_KAS : 'savetransferbank')?>">
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
                    <label for="">Dari Bank :</label>
                    <select class="form-control" name="dari_bank">
                    <?php 
                        if($jns_akun->num_rows() > 0) {
                            foreach($jns_akun->result() as $res) {
                                echo '<option value="'. $res->IDAKUN .'" '. (isset($row->IDTRAN_KAS) ? ($row->DARI_BANK == $res->DARI_BANK ? 'selected' : ''): '') .'>'. $res->JENIS_TRANSAKSI .'</option>';
                            }
                        }
                    ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="">Transfer Ke Bank :</label>
                    <select class="form-control" name="untuk_bank">
                   <?php 
                        if($jns_akun->num_rows() > 0) {
                            foreach($jns_akun->result() as $res) {
                                echo '<option value="'. $res->IDAKUN .'" '. (isset($row->IDTRAN_KAS) ? ($row->UNTUK_BANK == $res->UNTUK_BANK ? 'selected' : ''): '') .'>'. $res->JENIS_TRANSAKSI .'</option>';
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
