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
$halaman       = "supplier";
$dataapa       = "Supplier";
$tabeldatabase = "supplier";
$chmod         = $chmenu2;
$forward       = mysqli_real_escape_string($conn, $tabeldatabase);
$forwardpage   = mysqli_real_escape_string($conn, $halaman);
$no            = isset($_GET['no']) ? $_GET['no'] : '';
$alert_script  = ""; // Variabel untuk menyimpan skrip SweetAlert

// ===================================================================
// FUNGSI BANTUAN (AUTO NUMBER)
// ===================================================================
function autoNumber() {
    global $conn;
    $query    = "SELECT MAX(CAST(SUBSTRING(kode, 4) AS UNSIGNED)) as max_id FROM supplier WHERE kode LIKE 'SUP%'";
    $result   = mysqli_query($conn, $query);
    $data     = mysqli_fetch_array($result);
    $id_max   = $data['max_id'];
    $sort_num = ($id_max === null) ? 1 : $id_max + 1;
    return sprintf("SUP%03d", $sort_num);
}

// ===================================================================
// PROSES FORM (INSERT/UPDATE)
// ===================================================================
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['simpan'])) {
    // Sanitasi Input
    $kode       = mysqli_real_escape_string($conn, $_POST["kode"]);
    $nama       = mysqli_real_escape_string($conn, $_POST["nama"]);
    $tgldaftar  = mysqli_real_escape_string($conn, $_POST["tgldaftar"]);
    $nohp       = mysqli_real_escape_string($conn, $_POST["nohp"]);
    $alamat     = mysqli_real_escape_string($conn, $_POST["alamat"]);
    // Kolom keterangan dihapus dari proses
    $insert     = mysqli_real_escape_string($conn, $_POST["insert"]);

    if ($insert == '3') { // Mode Update
        if ($chmod >= 3 || $_SESSION['jabatan'] == 'admin') {
            // Perbaikan: Menghapus kolom 'keterangan' dari query UPDATE
            $sql_update = "UPDATE $tabeldatabase SET nama='$nama', tgldaftar='$tgldaftar', nohp='$nohp', alamat='$alamat' WHERE kode='$kode'";
            if (mysqli_query($conn, $sql_update)) {
                $alert_script = "swal('Berhasil!', 'Data supplier telah diupdate!', 'success').then(function() { window.location = '$forwardpage'; });";
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
                $alert_script = "swal('Gagal!', 'Kode supplier sudah ada.', 'error');";
            } else {
                // Perbaikan: Menghapus kolom 'keterangan' dari query INSERT
                $sql_insert = "INSERT INTO $tabeldatabase (kode, tgldaftar, nama, alamat, nohp) VALUES ('$kode', '$tgldaftar', '$nama', '$alamat', '$nohp')";
                if (mysqli_query($conn, $sql_insert)) {
                    $alert_script = "swal('Berhasil!', 'Data supplier telah disimpan!', 'success').then(function() { window.location = '$forwardpage'; });";
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
    <title>Manajemen Supplier</title>
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
        <section class="content">
            <!-- BREADCRUMB -->
            <ol class="breadcrumb">
                <li><a href="<?php echo $_SESSION['baseurl']; ?>">Dashboard</a></li>
                <li><a href="<?php echo $halaman; ?>"><?php echo $dataapa; ?></a></li>
                <li class="active"><?php echo !empty($no) ? 'Edit' : 'Tambah'; ?> <?php echo $dataapa; ?></li>
            </ol>
            
            <?php if ($chmod >= 2 || $_SESSION['jabatan'] == 'admin') : ?>
                <?php
                // Ambil data untuk form edit
                $kode_form = autoNumber();
                $nama_form = "";
                $tgldaftar_form = date("Y-m-d");
                $alamat_form = "";
                $nohp_form = "";
                $insert_mode = '1';

                if (!empty($no) && ($chmod >= 3 || $_SESSION['jabatan'] == 'admin')) {
                    $sql_edit   = "SELECT * FROM $tabeldatabase WHERE no='$no'";
                    $hasil_edit = mysqli_query($conn, $sql_edit);
                    if ($fill = mysqli_fetch_assoc($hasil_edit)) {
                        $kode_form = $fill["kode"];
                        $nama_form = $fill["nama"];
                        $tgldaftar_form = $fill["tgldaftar"];
                        $alamat_form = $fill["alamat"];
                        $nohp_form = $fill["nohp"];
                        $insert_mode = '3';
                    }
                }
                ?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <h3 class="box-title">Formulir <?php echo $dataapa; ?></h3>
                            </div>
                            <form class="form-horizontal" method="post" action="" id="Myform">
                                <div class="box-body">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="kode" class="col-sm-3 control-label">Kode Supplier</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="kode" name="kode" value="<?php echo htmlspecialchars($kode_form); ?>" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="nama" class="col-sm-3 control-label">Nama Supplier</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="nama" name="nama" value="<?php echo htmlspecialchars($nama_form); ?>" placeholder="Masukan Nama Supplier" required>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="datepicker" class="col-sm-3 control-label">Tanggal Daftar</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="datepicker" name="tgldaftar" value="<?php echo htmlspecialchars($tgldaftar_form); ?>" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="nohp" class="col-sm-3 control-label">No Handphone</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="nohp" name="nohp" value="<?php echo htmlspecialchars($nohp_form); ?>" placeholder="Masukan Nomor Handphone">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="alamat" class="col-sm-3 control-label">Alamat</label>
                                            <div class="col-sm-9">
                                                <textarea class="form-control" rows="3" id="alamat" name="alamat" placeholder="Alamat Lengkap"><?php echo htmlspecialchars($alamat_form); ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="insert" value="<?php echo $insert_mode; ?>">
                                </div>
                                <div class="box-footer">
                                    <button type="submit" class="btn btn-primary btn-flat" name="simpan"><i class="fa fa-save"></i> Simpan</button>
                                    <a href="<?php echo $halaman; ?>" class="btn btn-danger btn-flat"><i class="fa fa-close"></i> Batal</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            <?php else : ?>
                <div class="callout callout-danger">
                    <h4>Akses Ditolak!</h4>
                    <p>Anda tidak memiliki izin untuk mengakses halaman ini.</p>
                </div>
            <?php endif; ?>
        </section>
    </div>
    <?php footer(); ?>
    <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->
<script src="dist/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="dist/bootstrap/js/bootstrap.min.js"></script>
<script src="dist/plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="dist/plugins/slimScroll/jquery.slimscroll.min.js"></script>
<script src="dist/plugins/fastclick/fastclick.js"></script>
<script src="dist/js/app.min.js"></script>
<script>
    $(function() {
        // Inisialisasi Datepicker
        $('#datepicker').datepicker({
            autoclose: true,
            format: 'yyyy-mm-dd'
        });
    });

    // Jalankan skrip SweetAlert jika ada
    <?php if (!empty($alert_script)) : ?>
        document.addEventListener("DOMContentLoaded", function() {
            <?php echo $alert_script; ?>
        });
    <?php endif; ?>
</script>
</body>
</html>
