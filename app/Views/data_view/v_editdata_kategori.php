<?= form_open(base_url('KategoriDC/saveEditKategori'), ['id' => 'form_edit']) ?>


<p>
    <button type="button" class="btn btn-secondary" id="btn_kembali"><i class="fas fa-redo"></i> Kembali</button>
    <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Simpan</button>
</p>

<div class="table-responsive">
    <table class="table table-bordered">
        <thead class="thead-dark">
            <tr>
                <th>Nama Kategori</th>
            </tr>
        </thead>
        <tbody id="tabel_edit">
            <?php foreach ($tbl_kategori as $kat) { ?>
                <tr>
                    <input type="hidden" name="id_kategori[]" value="<?= $kat['id_kategori'] ?>">
                    <td>
                        <input class="form-control" type="text" name="nama_kategori[]" value="<?= $kat['nama_kategori'] ?>">
                        <div class="invalid-feedback error-nama-kategori">

                        </div>
                        <div class="valid-feedback">
                            Nama Kategori benar
                        </div>
                        <small class="form-text">Masukkan nama kategori dengan benar</small>
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
                            dataKategori();
                        })
                    } else {
                        for (let i = 0; i < response.jumlah_data; i++) {
                            // Mengambil input nama kategori
                            let ipt_nama = document.getElementById('tabel_edit').children[i].children[1].children[0];
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
    })
</script>