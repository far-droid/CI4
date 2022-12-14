<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<center>
    <h1><i class="fas fa-database"></i> Test Web Programming</h1>
    <h2>Data Produk</h2>
</center>
<hr>

<div class="row">
    <div class="col">
        <p>
            <button type="button" class="btn btn-primary" id="btn_tambahdata"><i class="far fa-plus-square"></i> Tambah</button>
        </p>
    </div>
</div>

<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-body">
                <p class="view-data">
                    <!-- Data Akan Muncul Di sini -->
                </p>
            </div>
        </div>
    </div>
</div>
<script>
    // Fungsi untuk mengambil data kategori dari backend
    function dataProduk() {
        $.ajax({
            url: "<?= base_url('ProdukDC/getAllProduk') ?>",
            dataType: "json",
            beforeSend: function() {
                $('.view-data').html('<i class="spinner-border"></i>');
            },
            success: function(response) {
                $('.view-data').html(response.data);
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
                $('.view-data').html(
                    `<div style="color:black;" class="card bg-light">
                        <div class="card-body">
                            <a href="#" id="btn_refreshdata"><i class="fas fa-sync"></i> Refresh</a>
                            <hr>
                            Terjadi Kesalahan (<strong>${xhr.status + ' ' + thrownError}</strong>)
                        </div>
                    </div>`
                );
            }
        })
    }

    // Ketika document sudah ready
    $(document).ready(function() {
        // Memanggil fungsi dataProduk()
        dataProduk();

        // Ketika tombol tambah data ditekan
        $('#btn_tambahdata').click(function(e) {
            $.ajax({
                url: "<?= base_url('ProdukDC/addProduk') ?>",
                dataType: "json",
                beforeSend: function() {
                    $('.view-data').html('<i class="spinner-border"></i>');
                },
                success: function(response) {
                    $('.view-data').html(response.data);
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
                    $('.view-data').html(
                        `<div style="color:black;" class="card bg-light">
                            <div class="card-body">
                                <a href="#" id="btn_refreshdata"><i class="fas fa-sync"></i> Refresh</a>
                                <hr>
                                Terjadi Kesalahan (<strong>${xhr.status + ' ' + thrownError}</strong>)
                            </div>
                        </div>`
                    );
                }
            })
        })
    });

    // Pada dokumen jika ada, dan jalankan ketika refresh data ditekan
    $(document).on('click', '#btn_refreshdata', function(e) {
        e.preventDefault();

        dataProduk();
    })
</script>

<?= $this->endSection(); ?>