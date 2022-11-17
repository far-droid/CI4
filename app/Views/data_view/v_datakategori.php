<p>
    <button type="button" class="btn btn-danger my-1" id="btn_deletedata"><i class="fas fa-trash-alt"></i> Hapus</button>
    <button type="button" class="btn btn-primary my-1" id="btn_editdata"><i class="fas fa-edit"></i> Ubah</button>
    <button type="button" class="btn btn-info my-1" id="btn_refreshdata"><i class="fas fa-sync"></i> Refresh</button>
</p>

<div class="table-responsive">
    <table class="table table-bordered" id="tabel_kategori">
        <thead class="thead-dark">
            <tr>
                <th>
                    <input type="checkbox" id="checkbox_all">
                </th>
                <th>No</th>
                <th>Nama Kategori</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>

        </tbody>
    </table>
</div>

<script>
    // Fungsi integrasi datatable serverside
    function dataKategoriServerSide() {
        let table = $('#tabel_kategori').DataTable({
            'processing': true,
            'serverSide': true,
            'order': [],
            'ajax': {
                'url': '<?= base_url('KategoriDC/kategoriServerside') ?>',
                'type': 'POST',
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
                    $('.view-data').html(
                        `<div style="color:black;" class="card bg-light">
                        <div class="card-body">
                            <a href="#" id="btn_refreshdata"><i class="fas fa-sync"></i> Refresh</a>
                            <hr>
                            Terjadi Kesalahan (<strong>${xhr.status + ' ' + thrownError}</strong>)
                        </div>
                    </div>`
                    );
                },
            },
            //optional
            'columnDefs': [{
                'targets': 0,
                'orderable': false,
            }, ]
        })
    }

    // Ketika document sudah ready
    $(document).ready(function() {
        dataKategoriServerSide();

        // Jika checkbox_all dicentang
        $('#checkbox_all').change(function(e) {
            if ($(this).is(':checked')) {
                $('.checkbox_kategori').prop('checked', true);
            } else {
                $('.checkbox_kategori').prop('checked', false);
            }
        })

        // Jika tombol hapus multiple ditekan
        $('#btn_deletedata').click(function(e) {
            // Mengambil data-data yang dipilih
            let data_delete = document.querySelectorAll('.checkbox_kategori:checked');

            deleteData(data_delete);
        })

        // Jika tombol edit multiple ditekan
        $('#btn_editdata').click(function(e) {
            // Mengambil data-data yang dipilih
            let data_edit = document.querySelectorAll('.checkbox_kategori:checked');

            editData(data_edit);
        })
    })

    // Fungsi untuk menghapus data
    function deleteData(id_kategori) {
        // Jika tidak ada data yang dipilih
        if (id_kategori.length === 0) {
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: 'Pilihlah data yang akan dihapus'
            })
        } else {
            Swal.fire({
                icon: 'warning',
                title: 'Perhatian!',
                text: `${typeof id_kategori === 'object' ? id_kategori.length : 1} data tersebut akan dihapus secara permanen, anda yakin?`,
                showCancelButton: true,
                cancelButtonColor: '#d33',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Delete!'
            }).then((result) => {
                let id_kategori_array = [];

                // Jika data yang dihapus data tunggal
                if (Number.isInteger(id_kategori)) {
                    id_kategori_array[0] = id_kategori;
                } else {
                    for (let i = 0; i < id_kategori.length; i++) {
                        id_kategori_array[i] = id_kategori[i].value;
                    }
                }

                if (result.value) {
                    $.ajax({
                        type: 'post',
                        url: "<?= base_url('KategoriDC/deleteKategori') ?>",
                        dataType: "json",
                        data: {
                            id_kategori: id_kategori_array,
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: response.success,
                                }).then((result) => {
                                    dataKategori();
                                })
                            } else if (response.error) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!!',
                                    text: response.error,
                                }).then((result) => {
                                    dataKategori();
                                })
                            } else if (response.warning) {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Peringatan!!',
                                    text: response.warning,
                                }).then((result) => {
                                    dataKategori();
                                })
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
                }
            })
        }
    }

    // Fungsi untuk mengubah data
    function editData(id_kategori) {
        // Jika tidak ada data yang dipilih
        if (id_kategori.length === 0) {
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: 'Pilihlah data yang akan diubah'
            })
        } else {
            let id_kategori_array = [];

            // Jika data yang dihapus data tunggal
            if (Number.isInteger(id_kategori)) {
                id_kategori_array[0] = id_kategori;
            } else {
                for (let i = 0; i < id_kategori.length; i++) {
                    id_kategori_array[i] = id_kategori[i].value;
                }
            }

            $.ajax({
                type: 'post',
                url: "<?= base_url('KategoriDC/editKategori') ?>",
                dataType: "json",
                data: {
                    id_kategori: id_kategori_array,
                },
                success: function(response) {
                    if (response.error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: response.error,
                        }).then((result) => {
                            dataKategori();
                        })
                    } else if (response.data) {
                        $('.view-data').html(response.data);
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
        }
    }
</script>