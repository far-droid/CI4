<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>
<center>
    <h1><i class="fas fa-database"></i> Test Web Programming</h1>
    <h2>Data Produk</h2>
</center>

<!-- MODAL ADD/EDIT -->
<!-- Modal start -->
<div class="modal fade" id="modalAdd" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Modal title</h3>
            </div>
            <div class="modal-body">
                <form action="#" id="form" class="form-horizontal">

                    <div class="form-group">
                        <label class="control-label col-md-2">Nama Supplier</label>
                        <div class="col-md-10">
                            <input type="hidden" name="id_supplier">
                            <input name="nama_perusahaan" id="nama_perusahaan" class="form-control" type="text" placeholder="Nama Lengkap..." required>
                            <span class="help-block"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-2">Alamat</label>
                        <div class="col-md-10">
                            <textarea name="alamat" class="form-control" id="alamat" placeholder="Ketikkan Alamat..." required="required"></textarea>
                            <span class="help-block"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-2">Nama Sales</label>
                        <div class="col-md-10">
                            <input type="text" name="nama_sales" class="form-control" id="nama_sales" placeholder="Nama Sales..." required="required">
                            <span class="help-block"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-2">No. Telp (WA)</label>
                        <div class="col-md-10">
                            <input name="telp" id="telp" class="form-control" type="text" placeholder="Ketikkan No. Telp..." required>
                            <span class="help-block"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-2">Email</label>
                        <div class="col-md-10">
                            <input name="email" id="email" class="form-control" type="email" placeholder="Ketikkan email..." required>
                            <span class="help-block"></span>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" onclick="save()" id="btnSave">Simpan</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal End -->
<!-- END MODAL ADD/EDIT -->

<?= $this->endSection(); ?>