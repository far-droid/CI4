<?= form_open(base_url('ProdukDC/saveEditProduk'), ['id' => 'form_edit']) ?>


<p>
    <button type="button" class="btn btn-secondary" id="btn_kembali"><i class="fas fa-redo"></i> Kembali</button>
    <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Simpan</button>
</p>

<div class="table-responsive">
    <table class="table table-bordered">
        <thead class="thead-dark">
            <tr>
                <th>ID<div class="spasi">&nbsp</div>Kategori</th>
                <th>Nama<div class="spasi">&nbsp</div>Produk</th>
                <th>Kode<div class="spasi">&nbsp</div>Produk</th>
                <th>Foto<div class="spasi">&nbsp</div>Produk</th>
                <th>Tanggal<div class="spasi">&nbsp</div>Registrasi</th>
            </tr>
        </thead>
        <tbody id="tabel_edit">
            <?php foreach ($tbl_produk as $pd) { ?>
                <tr>
                    <input type="hidden" name="id_produk[]" value="<?= $pd['id_produk'] ?>">
                    <td>
                        <select class="form-control" name="tbl_kategori[]">
                            <?php foreach ($tbl_kategori as $gr) { ?>
                                <option <?= $gr['id_kategori-p'] == $pd['id_kategori-p'] ? 'selected=selected' : null ?> value="<?= $gr['id_kategori-p'] ?>"><?= $gr['nama_kategori'] ?></option>
                            <?php } ?>
                        </select>
                        <div class="invalid-feedback error-kategori">

                        </div>
                        <div class="valid-feedback">
                            Kategori benar
                        </div>
                        <small class="form-text">Pilih kategori dengan benar</small>
                    </td>
                    <td>
                        <input class="form-control" type="text" name="nama_produk[]" value="<?= $pd['nama_produk'] ?>">
                        <div class="invalid-feedback error-nama-produk">

                        </div>
                        <div class="valid-feedback">
                            Nama produk benar
                        </div>
                        <small class="form-text">Masukkan nama produk dengan benar</small>
                    </td>
                    <td>
                        <input class="form-control" type="text" name="kode_produk[]" value="<?= $pd['kode_produk'] ?>">
                        <div class="invalid-feedback error-kode-produk">

                        </div>
                        <div class="valid-feedback">
                            Kode produk benar
                        </div>
                        <small class="form-text">Masukkan kode produk dengan benar</small>
                    </td>
                    <td>
                        <input class="form-control" type="text" name="foto_produk[]" value="<?= $pd['foto_produk'] ?>">
                        <div class="invalid-feedback error-foto-produk">

                        </div>
                        <div class="valid-feedback">
                            Foto produk benar
                        </div>
                        <small class="form-text">Masukkan foto produk dengan benar</small>
                    </td>
                    <td>
                        <input class="form-control" type="date" name="tgl_register[]" value="<?= $pd['tgl_register'] ?>">
                        <div class="invalid-feedback error-tgl-register">

                        </div>
                        <div class="valid-feedback">
                            Tanggal sudah benar
                        </div>
                        <small class="form-text">Masukkan tanggal dengan benar</small>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<?= form_close() ?>

<script>
    // Ketika document sudah ready
    $(document).ready(function() {
        // Jika form tersubmit
        $('#form_edit').submit(function(e) {
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
                            dataProduk();
                        })
                    } else {
                        for (let i = 0; i < response.jumlah_data; i++) {
                            // Mengambil input nama produk
                            let ipt_nama = document.getElementById('tabel_edit').children[i].children[1].children[0];
                            // Menghilangkan element small
                            ipt_nama.nextElementSibling.nextElementSibling.nextElementSibling.style = 'display: none;';
                            // Jika pada input nama produk tersebut terdapat error
                            if (response[i].nama_produk) {
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
            dataProduk();
        })
    })
</script>