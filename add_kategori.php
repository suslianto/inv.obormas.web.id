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
$halaman       = "kategori";
$dataapa       = "kategori";
$tabeldatabase = "kategori";
$chmod         = $chmenu3;
$forward       = mysqli_real_escape_string($conn, $tabeldatabase);
$forwardpage   = mysqli_real_escape_string($conn, $halaman);
$search        = isset($_POST['search']) ? $_POST['search'] : '';
$no            = isset($_GET['no']) ? $_GET['no'] : '';
$alert_script  = ""; // Variabel untuk menyimpan skrip SweetAlert

// ===================================================================
// FUNGSI BANTUAN (AUTO NUMBER)
// ===================================================================
function autoNumber() {
    global $conn; // Menggunakan koneksi global
    $query    = "SELECT MAX(CAST(SUBSTRING(kode, 2) AS UNSIGNED)) as max_id FROM kategori WHERE kode LIKE 'K%'";
    $result   = mysqli_query($conn, $query);
    $data     = mysqli_fetch_array($result);
    $id_max   = $data['max_id'];
    $sort_num = ($id_max === null) ? 1 : $id_max + 1;
    return sprintf("K%03d", $sort_num);
}

// ===================================================================
// PROSES FORM (INSERT/UPDATE) SAAT TOMBOL SIMPAN DITEKAN
// ===================================================================
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['simpan'])) {
    $kode   = mysqli_real_escape_string($conn, $_POST["kode"]);
    $nama   = mysqli_real_escape_string($conn, $_POST["nama"]);
    $insert = mysqli_real_escape_string($conn, $_POST["insert"]);

    if ($insert == '3') { // Mode Update
        if ($chmod >= 3 || $_SESSION['jabatan'] == 'admin') {
            $sql_update = "UPDATE $tabeldatabase SET nama='$nama' WHERE kode='$kode'";
            if (mysqli_query($conn, $sql_update)) {
                $alert_script = "swal('Berhasil!', 'Data telah diupdate!', 'success').then(function() { window.location = '$forwardpage'; });";
            } else {
                $error_msg = addslashes(mysqli_error($conn));
                $alert_script = "swal('Gagal!', 'Gagal mengupdate data. Error: $error_msg', 'error');";
            }
        } else {
            $alert_script = "swal('Akses Ditolak!', 'Anda tidak memiliki izin untuk mengupdate data!', 'error');";
        }
    } else { // Mode Insert
        if ($chmod >= 2 || $_SESSION['jabatan'] == 'admin') {
            $sql_check    = "SELECT kode FROM $tabeldatabase WHERE kode='$kode'";
            $result_check = mysqli_query($conn, $sql_check);
            if (mysqli_num_rows($result_check) > 0) {
                $alert_script = "swal('Gagal!', 'Kode kategori sudah ada.', 'error');";
            } else {
                $sql_insert = "INSERT INTO $tabeldatabase (kode, nama) VALUES ('$kode', '$nama')";
                if (mysqli_query($conn, $sql_insert)) {
                    $alert_script = "swal('Berhasil!', 'Data telah disimpan!', 'success').then(function() { window.location = '$forwardpage'; });";
                } else {
                    $error_msg = addslashes(mysqli_error($conn));
                    $alert_script = "swal('Gagal!', 'Data gagal disimpan. Error: $error_msg', 'error');";
                }
            }
        } else {
            $alert_script = "swal('Akses Ditolak!', 'Anda tidak memiliki izin untuk menambah data!', 'error');";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Manajemen Kategori</title>
    <!-- Tambahkan pustaka SweetAlert -->
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
            <div class="row">
                <div class="col-lg-12">
                    <!-- BREADCRUMB -->
                    <ol class="breadcrumb">
                        <li><a href="<?php echo $_SESSION['baseurl']; ?>">Dashboard </a></li>
                        <li><a href="<?php echo $halaman; ?>"><?php echo ucwords($dataapa); ?></a></li>
                        <li class="active"><?php echo !empty($no) ? 'Edit' : 'Tambah'; ?> <?php echo ucwords($dataapa); ?></li>
                    </ol>

                    <?php if ($chmod >= 2 || $_SESSION['jabatan'] == 'admin') : ?>
                        <?php
                        // Ambil data untuk form edit
                        $kode_form   = autoNumber();
                        $nama_form   = "";
                        $insert_mode = '1';

                        if (!empty($no) && ($chmod >= 3 || $_SESSION['jabatan'] == 'admin')) {
                            $sql_edit   = "SELECT * FROM $tabeldatabase WHERE no='$no'";
                            $hasil_edit = mysqli_query($conn, $sql_edit);
                            if ($fill = mysqli_fetch_assoc($hasil_edit)) {
                                $kode_form   = $fill["kode"];
                                $nama_form   = $fill["nama"];
                                $insert_mode = '3';
                            }
                        }
                        ?>
                        <!-- KONTEN BODY AWAL -->
                        <div class="box box-default">
                            <div class="box-header with-border">
                                <h3 class="box-title">Data <?php echo ucwords($dataapa); ?></h3>
                            </div>
                            <div class="box-body">
                                <div class="table-responsive">
                                    <div id="main">
                                        <div class="container-fluid">
                                            <form class="form-horizontal" method="post" action="" id="Myform">
                                                <div class="box-body">
                                                    <div class="row">
                                                        <div class="form-group col-md-6 col-xs-12">
                                                            <label for="kode" class="col-sm-3 control-label">Kode:</label>
                                                            <div class="col-sm-9">
                                                                <input type="text" class="form-control" id="kode" name="kode" value="<?php echo htmlspecialchars($kode_form); ?>" maxlength="50" required readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group col-md-6 col-xs-12">
                                                            <label for="nama" class="col-sm-3 control-label">Nama:</label>
                                                            <div class="col-sm-9">
                                                                <input type="text" class="form-control" id="nama" name="nama" value="<?php echo htmlspecialchars($nama_form); ?>" placeholder="Masukan nama kategori" maxlength="50" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" name="insert" value="<?php echo $insert_mode; ?>">
                                                </div>
                                                <div class="box-footer">
                                                    <button type="submit" class="btn btn-default pull-left btn-flat" name="simpan"><span class="glyphicon glyphicon-floppy-disk"></span> Simpan</button>
                                                    <a href="<?php echo $halaman; ?>" class="btn btn-danger pull-left btn-flat" style="margin-left: 10px;"><i class="fa fa-close"></i> Batal</a>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php else : ?>
                        <div class="callout callout-danger">
                            <h4>Info</h4>
                            <b>Hanya user tertentu yang dapat mengakses halaman <?php echo $dataapa; ?> ini.</b>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </div>
    <?php footer(); ?>
    <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->
<script src="dist/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="dist/bootstrap/js/bootstrap.min.js"></script>
<script src="dist/plugins/slimScroll/jquery.slimscroll.min.js"></script>
<script src="dist/plugins/fastclick/fastclick.js"></script>
<script src="dist/js/app.min.js"></script>
<script>
    // Jalankan skrip SweetAlert jika ada
    <?php if (!empty($alert_script)) : ?>
        document.addEventListener("DOMContentLoaded", function() {
            <?php echo $alert_script; ?>
        });
    <?php endif; ?>
</script>
</body>
</html>
