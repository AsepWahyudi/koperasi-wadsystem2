<div class="element-actions">
    <button class="btn btn-primary" data-target="#mymodals" data-toggle="modal" type="button">Tambah Data</button>
</div>
<h6 class="element-header">
    Data Jaminan BPKB
</h6>
<div class="element-box">
    <?=$response;?>
    <div class="table-responsive">
        <table id="dataTable1" width="100%" class="table table-striped table-lightfont">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Anggota</th>
                    <th>No BPKB</th>
                    <th>Provinsi</th>
                    <th>Kota</th>
                    <th>Kelurahan</th>
                    <th>Nama Pemilik</th>
                    <th>Taksiran Harga</th>
                    <th>No STNK</th>
                    <th>No Polisi</th>
                    <th>Masa Pajak</th>
                    <th>Cabang</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
if ($query->num_rows() > 0) {$n = 1;
    foreach ($query->result() as $key) {$no = $n++;
        $btn_edit = '<a href="javascript:;" onclick="loaddata(\'btnedit' . $no . '\')" var-url="master_data/bpkb/get_edit?id=' . $key->IDBPKB . '" class="btn btn-success btnedit' . $no . ' btn-sm"><i class="fa fa-pencil"></i></a>';
        $btn_del = '<a href="' . base_url() . 'master_data/bpkb/delete/' . $key->IDBPKB . '" class="btn btn-danger btn-sm" style="margin-left:3px;" onclick="return confirm(\'Yakin dihapus?\')"><i class="fa fa-trash"></i></a>';
        ?>
                <tr>
                    <td><?=$no?></td>
                    <td><?=$key->NAMAG?></td>
                    <td><?=$key->NOS?></td>
                    <td><?=$key->PROV?></td>
                    <td><?=$key->KOT?></td>
                    <td><?=$key->KEL?></td>
                    <td><?=$key->NAMAP?></td>
                    <td><?=$key->TAKSIR?></td>
                    <td><?=$key->STNK?></td>
                    <td><?=$key->NOPOL?></td>
                    <td><?=date('d/m/Y', strtotime(str_replace('/', '-', $key->MASA_PAJAK)))?></td>
                    <td><?=$btn_edit?><?=$btn_del?></td>
                </tr>
                <?php }}?>
            </tbody>

        </table>
    </div>
</div>



<div aria-hidden="true" aria-labelledby="mymodals" class="modal" id="mymodals" role="dialog" tabindex="-1">
    <div class="modal-dialog" role="document">
        <form method="post">
            <input type="hidden" name="idbpkb" id="idbpkb">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Data Jaminan</h5>
                    <button aria-label="Close" class="close" data-dismiss="modal" type="button"><span
                            aria-hidden="true"> &times;</span></button>
                </div>
                <div class="modal-body">

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for=""> Nama Anggota</label><input class="form-control"
                                    placeholder="Nama Anggota" type="text" name="namag" id="namag" required>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for=""> Nomor BPKB</label><input class="form-control" placeholder="No BPKB"
                                    type="text" name="nos" id="nos" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="">Provinsi</label><input class="form-control" placeholder="Provinsi"
                                    type="text" name="prov" id="prov">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="">Kota</label><input class="form-control" placeholder="Kota" type="kot"
                                    name="kot" id="kot">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="">Kecamatan</label><input class="form-control" placeholder="Kecamatan"
                                    type="kec" name="kec" id="kec">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="">Kelurahan</label><input class="form-control" placeholder="Kelurahan"
                                    type="kel" name="kel" id="kel">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="">Nama Pemilik</label><input class="form-control" placeholder="Nama Pemilik"
                                    type="namap" name="namap" id="namap" required>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="">Merk</label><input class="form-control" placeholder="Merk" type="merk"
                                    name="merk" id="merk" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="">Taksiran Harga</label><input class="form-control"
                                    placeholder="Taksiran Harga" type="taksir" name="taksir" id="taksir" required>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="">Tipe</label><input class="form-control" placeholder="Tipe" type="tipe"
                                    name="tipe" id="tipe" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="">Nomor STNK</label><input class="form-control" placeholder="Nomor STNK"
                                    type="stnk" name="stnk" id="stnk" required>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="">Masa Berlaku Pajak</label><input class="single-daterange form-control"
                                    placeholder="Masa Berlaku Pajak" type="masa_pajak" name="masa_pajak" id="masa_pajak"
                                    required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="">Nomor Polisi</label><input class="form-control" placeholder="Nomor Polisi"
                                    type="nopol" name="nopol" id="nopol" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="">Cabang</label><select class="form-control" name="kodecabang"
                                    id="kodecabang">
                                    <?
								foreach($cbg->result() as $key){
							?>
                                    <option value="<?=$key->KODE?>"><?=$key->NAMA?></option>
                                    <? } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss="modal" type="button"> Close</button><button
                        class="btn btn-primary" type="submit"> Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
var base_url = '<?=base_url()?>'
</script>
<script type="text/javascript" src="<?=base_url()?>assets/js/Master/bpkb.js"></script>