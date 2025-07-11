<!DOCTYPE html>
<html>
<?php
include "configuration/config_etc.php";
include "configuration/config_include.php";
etc();encryption();session();connect();head();body();timing();
pagination();

if (!login_check()) {
    echo '<meta http-equiv="refresh" content="0; url=logout" />';
    exit(0);
}
?>
<head>
    <!-- Pustaka SweetAlert -->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
</head>
<div class="wrapper">
<?php
theader();
menu();
?>
    <div class="content-wrapper">
        <section class="content-header"></section>
        <section class="content">
            <div class="row">
                <div class="col-lg-12">
                    <!-- SETTING START-->
                    <?php
                    error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
                    include "configuration/config_chmod.php";
                    $halaman = "stok_masuk_batal.php";
                    $dataapa = "Barang Masuk";
                    $tabeldatabase = "stok_masuk_daftar";
                    $chmod = $chmenu5;
                    $forward = mysqli_real_escape_string($conn, $tabeldatabase);
                    $forwardpage = mysqli_real_escape_string($conn, $halaman);
                    $nota = isset($_GET['nota']) ? mysqli_real_escape_string($conn, $_GET['nota']) : '';
                    ?>
                    <!-- SETTING STOP -->

                    <!-- BREADCRUMB -->
                    <ol class="breadcrumb">
                        <li><a href="<?php echo $_SESSION['baseurl']; ?>">Dashboard </a></li>
                        <li><a href="stok_masuk.php"><?php echo $dataapa ?></a></li>
                        <li class="active">Detail Stok Masuk</li>
                    </ol>

                    <?php if ($chmod >= 1 || $_SESSION['jabatan'] == 'admin'): ?>
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Detail Stok Masuk No. Trx: <?php echo htmlspecialchars($nota); ?></h3>
                            <div class="box-tools pull-right">
                                <a href="stok_masuk.php" class="btn btn-sm btn-default">Kembali</a>
                            </div>
                        </div>
                        <div class="box-body table-responsive">
                            <table class="table table-hover table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>No. Trx</th>
                                        <th>SKU</th>
                                        <th>Nama Barang</th>
                                        <th>Jumlah</th>
                                        <?php if ($chmod >= 4 || $_SESSION['jabatan'] == 'admin') { ?>
                                            <th style="width:70px;">Opsi</th>
                                        <?php } ?>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                $sql = "SELECT * FROM $tabeldatabase WHERE nota='$nota' ORDER BY no";
                                $result = mysqli_query($conn, $sql);
                                $no_urut = 0;
                                if (mysqli_num_rows($result) > 0) {
                                    while ($fill = mysqli_fetch_assoc($result)) {
                                        $no_urut++;
                                        $kode_brg_fill = $fill['kode_barang'];
                                        $r_sku = mysqli_fetch_assoc(mysqli_query($conn, "SELECT sku FROM barang WHERE kode='$kode_brg_fill'"));
                                ?>
                                    <tr>
                                        <td><?php echo $no_urut; ?></td>
                                        <td><?php echo htmlspecialchars($fill['nota']); ?></td>
                                        <td><?php echo htmlspecialchars($r_sku['sku']); ?></td>
                                        <td><?php echo htmlspecialchars($fill['nama']); ?></td>
                                        <td><?php echo htmlspecialchars($fill['jumlah']); ?></td>
                                        <td>
                                            <?php if ($chmod >= 4 || $_SESSION['jabatan'] == 'admin') { 
                                                // URL untuk pembatalan
                                                $cancel_url = "component/delete/delete_masuk.php?no=".$fill['no']."&forward=".$forward."&forwardpage=".$forwardpage."&nota=".$fill['nota']."&jumlah=".$fill['jumlah']."&kode=".$fill['kode_barang']."&chmod=".$chmod;
                                            ?>
                                                <!-- Tombol Batal dengan SweetAlert dan Ikon Sampah -->
                                                <button type="button" class="btn btn-danger btn-xs" onclick="confirmCancel('<?php echo $cancel_url; ?>')">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                <?php 
                                    }
                                } else {
                                    echo "<tr><td colspan='6' class='text-center'>Tidak ada item untuk transaksi ini.</td></tr>";
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php else: ?>
                    <div class="callout callout-danger">
                        <h4>Info</h4>
                        <b>Hanya user tertentu yang dapat mengakses halaman <?php echo $dataapa;?> ini.</b>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </div>
    <?php footer();?>
    <div class="control-sidebar-bg"></div>
</div>

<!-- Script -->
<script src="dist/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="dist/bootstrap/js/bootstrap.min.js"></script>
<script src="dist/plugins/slimScroll/jquery.slimscroll.min.js"></script>
<script src="dist/plugins/fastclick/fastclick.js"></script>
<script src="dist/js/app.min.js"></script>

<!-- Script untuk SweetAlert Konfirmasi -->
<script>
function confirmCancel(url) {
    swal({
        title: "Anda Yakin?",
        text: "Item ini akan dibatalkan dan stok akan dikembalikan ke jumlah semula.",
        icon: "warning",
        buttons: ["Batal", "Ya, Batalkan!"],
        dangerMode: true,
    })
    .then((willCancel) => {
        if (willCancel) {
            window.location.href = url;
        }
    });
}
</script>
</body>
</html>
