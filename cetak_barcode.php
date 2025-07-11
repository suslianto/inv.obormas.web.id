<?php
// ===================================================================
// KONFIGURASI DAN INISIALISASI
// ===================================================================
include "configuration/config_etc.php";
include "configuration/config_include.php";
etc();
encryption();
session();
connect();
head();
body();
timing();
pagination();

// ===================================================================
// CEK STATUS LOGIN
// ===================================================================
if (!login_check()) {
    echo '<meta http-equiv="refresh" content="0; url=logout" />';
    exit(0);
}

// ===================================================================
// PENGATURAN HALAMAN
// ===================================================================
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
include "configuration/config_chmod.php";
$halaman       = "cetak_barcode";
$dataapa       = "Form Barcode";
$tabeldatabase = "barang";
$chmod         = $chmenu5;
$forward       = mysqli_real_escape_string($conn, $tabeldatabase);
$forwardpage   = mysqli_real_escape_string($conn, $halaman);
$kode          = isset($_GET['kode']) ? $_GET['kode'] : '';

// Ambil data barang jika ada kode yang dikirim
$barcode_val = '';
$nama_val    = '';
if (!empty($kode)) {
    $sql_barang = "SELECT * FROM $tabeldatabase WHERE kode = '$kode'";
    $query_barang = mysqli_query($conn, $sql_barang);
    $data_barang = mysqli_fetch_assoc($query_barang);
    if ($data_barang) {
        $barcode_val = $data_barang['barcode'];
        $nama_val    = $data_barang['nama'];
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Cetak Barcode</title>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
</head>
<body class="hold-transition skin-purple sidebar-mini">
<div class="wrapper">
    <?php
    theader();
    menu();
    ?>
    <div class="content-wrapper">
        <section class="content-header"></section>
        <!-- Main content -->
        <section class="content">
            <!-- BREADCRUMB -->
            <ol class="breadcrumb">
                <li><a href="<?php echo $_SESSION['baseurl']; ?>">Dashboard</a></li>
                <li class="active"><?php echo $dataapa; ?></li>
            </ol>
            
            <?php if ($chmod >= 2 || $_SESSION['jabatan'] == 'admin') : ?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="box box-info">
                            <div class="box-header with-border">
                                <h3 class="box-title">Cetak Barcode</h3>
                            </div>
                            <!-- form start -->
                            <form class="form-horizontal" method="post" action="print_barcode.php" target="_blank">
                                <div class="box-body">
                                    <div class="form-group">
                                        <label for="produk" class="col-sm-2 control-label">Nama Barang</label>
                                        <div class="col-sm-10">
                                            <select class="form-control select2" style="width: 100%;" name="produk" id="produk" required>
                                                <option value="">Pilih Barang</option>
                                                <?php
                                                $sql_all_barang = mysqli_query($conn, "SELECT kode, sku, nama, barcode FROM barang ORDER BY nama");
                                                while ($row = mysqli_fetch_assoc($sql_all_barang)) {
                                                    // Cek apakah barang ini yang dipilih dari halaman sebelumnya
                                                    $selected = ($kode == $row['kode']) ? "selected" : "";
                                                    echo "<option value='" . htmlspecialchars($row['kode']) . "' data-barcode='" . htmlspecialchars($row['barcode']) . "' " . $selected . ">" . htmlspecialchars($row['sku']) . " | " . htmlspecialchars($row['nama']) . "</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="barcode" class="col-sm-2 control-label">Barcode</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="barcode" name="barcode" placeholder="Barcode akan terisi otomatis" value="<?php echo htmlspecialchars($barcode_val); ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="kolom" class="col-sm-2 control-label">Jumlah Kolom</label>
                                        <div class="col-sm-10">
                                            <input type="number" class="form-control" name="kolom" value="5" min="1" max="10" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="jumlah" class="col-sm-2 control-label">Jumlah Print</label>
                                        <div class="col-sm-10">
                                            <input type="number" class="form-control" id="jumlah" name="jumlah" placeholder="Isikan jumlah barcode yang akan dicetak" min="1" required>
                                        </div>
                                    </div>
                                    <input type="hidden" class="form-control" id="kode" name="kode" value="<?php echo htmlspecialchars($kode); ?>">
                                </div>
                                <!-- /.box-body -->
                                <div class="box-footer">
                                    <button type="submit" class="btn btn-info"><i class="fa fa-print"></i> Print</button>
                                    <a href="barang" class="btn btn-danger"><i class="fa fa-close"></i> Batal</a>
                                </div>
                                <!-- /.box-footer -->
                            </form>
                        </div>
                    </div>
                </div>
            <?php else : ?>
                <div class="callout callout-danger">
                    <h4>Info</h4>
                    <b>Hanya user tertentu yang dapat mengakses halaman <?php echo $dataapa; ?> ini.</b>
                </div>
            <?php endif; ?>
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <?php footer(); ?>
    <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<!-- Scripts -->
<script src="dist/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="dist/bootstrap/js/bootstrap.min.js"></script>
<script src="dist/plugins/select2/select2.full.min.js"></script>
<script src="dist/plugins/slimScroll/jquery.slimscroll.min.js"></script>
<script src="dist/plugins/fastclick/fastclick.js"></script>
<script src="dist/js/app.min.js"></script>
<script>
    $(function () {
        // Initialize Select2 Elements
        $(".select2").select2();

        // Event listener untuk dropdown produk
        $("#produk").on("change", function(){
            // Ambil atribut 'data-barcode' dari option yang dipilih
            var barcode = $(this).find(':selected').data('barcode');
            var kode_produk = $(this).val();

            // Isi field barcode dan kode
            $("#barcode").val(barcode);
            $("#kode").val(kode_produk);
            
            // Set default jumlah print ke 1
            if($(this).val() !== ""){
                $("#jumlah").val(1);
            } else {
                $("#jumlah").val('');
            }
        });
    });
</script>
</body>
</html>
