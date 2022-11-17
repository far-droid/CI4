<?= form_open('KategoriDC/saveAddKategori', ['id' => 'form_tambah']) ?>
<?= csrf_field() ?>


<p>
    <button type="button" class="btn btn-secondary" id="btn_kembali"><i class="fas fa-redo"></i> Kembali</button>
    <button type="submit" class="btn btn-success"><i class="fa-regular fa-floppy-disk"></i> Simpan</button>
</p>
<!-- MODAL ADD/EDIT -->
<!-- Modal start -->
<!-- <div class="modal fade" id="modalAdd" tabindex="-1" role="dialog">
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
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" onclick="save()" id="btnSave">Simpan</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div> -->
<!-- Modal End -->
<!-- END MODAL ADD/EDIT -->

<div class="table-responsive">
    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Nama Kategori</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody id="tabel_tambah">
            <tr>
                <td>
                    <input class="form-control" type="text" name="nama_kategori[]">
                    <div class="invalid-feedback error-nama-kategori">

                    </div>
                    <div class="valid-feedback">
                        Nama kategori benar
                    </div>
                    <small class="form-text">Masukkan nama kategori dengan benar</small>
                </td>
                <td>
                    <button type="button" class="btn btn-primary" id="btn_tambahform">
                        <i class="fa-solid fa-plus"></i>
                    </button>
                </td>
            </tr>
        </tbody>
    </table>
</div>
<?= form_close() ?>

<script>
    // Ketika document sudah ready
    $(document).ready(function() {
        // Jika form tersubmit
        $('#form_tambah').submit(function(e) {
            e.preventDefault();

            $.ajax({
                type: 'post',
                url: $(this).attr('action'),
                data: $(this).serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.success,
                        }).then((result) => {
                            dataKategori();
                        })
                    } else {
                        for (let i = 0; i < response.jumlah_data; i++) {
                            // Mengambil input nama kategori
                            let ipt_nama = document.getElementById('tabel_tambah').children[i].children[0].children[0];
                            // Menghilangkan element small
                            ipt_nama.nextElementSibling.nextElementSibling.nextElementSibling.style = 'display: none;';
                            // Jika pada input nama kategori tersebut terdapat error
                            if (response[i].nama_kategori) {
                                ipt_nama.classList.remove('is-valid');
                                ipt_nama.classList.add('is-invalid');
                                ipt_nama.nextElementSibling.innerHTML = response[i].nama_kategori;
                            } else {
                                ipt_nama.classList.remove('is-invalid');
                                ipt_nama.classList.add('is-valid');
                            }
                        }
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    $('#error_message').html(
                        `<strong>${xhr.status + ' ' + thrownError}</strong>
                        <br>
                        <div class="card mt-2">
                            <div class="card-body">
                                ${xhr.responseText}
                            </div>
                        </div>`
                    );
                    $('#error_modal').modal('show');
                }
            })
            return false;
        })

        // Jika tombol kembali ditekan
        $('#btn_kembali').click(function(e) {
            dataKategori();
        })

        // Jika tombol tambah form ditekan
        $('#btn_tambahform').click(function(e) {
            // Append form baru
            $('#tabel_tambah').append(
                `<tr>
                <td>
                    <input class="form-control" type="text" name="nama_kategori[]">
                    <div class="invalid-feedback error-nama-kategori">

                    </div>
                    <div class="valid-feedback">
                        Nama kategori benar
                    </div>
                    <small class="form-text">Masukkan nama kategori dengan benar</small>
                </td>
                <td>
                    <button type="button" class="btn btn-danger" id="btn_hapusform">
                        <i class="fa-solid fa-minus"></i>
                    </button>
                </td>
            </tr>`)
        })
    })

    // Pada dokumen jika ada, dan jalankan ketika btn_hapusform ditekan
    $(document).on('click', '#btn_hapusform', function(e) {
        $(this).parents('tr').remove();
    })
</script>