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
$halaman = "restore";
$dataapa = "Restore Database";
$chmod   = $chmenu4; // Sesuaikan dengan hak akses menu yang benar
$alert_script = ""; // Variabel untuk menyimpan skrip SweetAlert

// ===================================================================
// PROSES RESTORE DATABASE
// ===================================================================
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['restore'])) {
    if ($chmod >= 2 || $_SESSION['jabatan'] == 'admin') {
        
        $password_konfirmasi = $_POST['password_konfirmasi'];
        $file_sql = $_FILES['file'];
        $no_user = $_SESSION['nouser'];

        // Validasi input
        if (empty($password_konfirmasi) || empty($file_sql['name'])) {
            $alert_script = "swal('Gagal!', 'Mohon pilih file backup dan masukan password Anda.', 'error');";
        } else {
            // Verifikasi password user
            $sql_pass_check = "SELECT pa_ssword FROM user WHERE no='$no_user' AND pa_ssword=sha1(MD5('$password_konfirmasi'))";
            $query_pass_check = mysqli_query($conn, $sql_pass_check);

            if (mysqli_num_rows($query_pass_check) > 0) {
                // Proses file SQL
                $nama_file = $file_sql['name'];
                $tmp_file  = $file_sql['tmp_name'];
                $x         = explode('.', $nama_file);
                $eks       = strtolower(end($x));

                if ($eks === 'sql') {
                    $isi_file = file_get_contents($tmp_file);
                    $queries  = explode(';', $isi_file);
                    $error_count = 0;

                    foreach ($queries as $query) {
                        if (trim($query) != '') {
                            if (!mysqli_query($conn, $query)) {
                                $error_count++;
                            }
                        }
                    }

                    if ($error_count == 0) {
                        $alert_script = "swal('Berhasil!', 'Database telah berhasil dipulihkan dari file backup.', 'success').then(function(){ window.location = 'backup'; });";
                    } else {
                        $alert_script = "swal('Gagal!', 'Terjadi kesalahan saat menjalankan query restorasi. Sebagian data mungkin tidak berhasil dipulihkan.', 'error');";
                    }
                } else {
                    $alert_script = "swal('Gagal!', 'File yang diunggah harus berekstensi .sql', 'error');";
                }
            } else {
                $alert_script = "swal('Gagal!', 'Password yang Anda masukan salah. Proses restorasi dibatalkan.', 'error');";
            }
        }
    } else {
        $alert_script = "swal('Akses Ditolak!', 'Anda tidak memiliki izin untuk melakukan restorasi.', 'error');";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Restore Database</title>
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
            <div class="row">
                <div class="col-lg-12">
                    <!-- BREADCRUMB -->
                    <ol class="breadcrumb">
                        <li><a href="<?php echo $_SESSION['baseurl']; ?>">Dashboard</a></li>
                        <li><a href="backup">Backup & Restore</a></li>
                        <li class="active"><?php echo $dataapa; ?></li>
                    </ol>
                </div>
            </div>

            <?php if ($chmod >= 2 || $_SESSION['jabatan'] == 'admin') : ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-warning">
                        <div class="box-header with-border">
                            <h3 class="box-title"><i class="fa fa-upload"></i> Form Restore Database</h3>
                        </div>
                        <form class="form-horizontal" method="post" action="" enctype="multipart/form-data">
                            <div class="box-body">
                                <div class="callout callout-danger">
                                    <h4><i class="icon fa fa-warning"></i> Peringatan Keras!</h4>
                                    <p>Proses restorasi akan **MENGGANTI** semua data yang ada saat ini dengan data dari file backup. Tindakan ini tidak dapat dibatalkan. Pastikan Anda telah memilih file backup yang benar.</p>
                                </div>

                                <div class="form-group">
                                    <label for="file" class="col-sm-2 control-label">File Backup (.sql)</label>
                                    <div class="col-sm-10">
                                        <input type="file" id="file" name="file" required>
                                        <p class="help-block">Pilih file backup database dengan format .sql yang telah Anda unduh sebelumnya.</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="password_konfirmasi" class="col-sm-2 control-label">Konfirmasi Password</label>
                                    <div class="col-sm-10">
                                        <input type="password" class="form-control" id="password_konfirmasi" name="password_konfirmasi" placeholder="Masukan password Anda untuk konfirmasi" required>
                                        <p class="help-block">Untuk keamanan, masukan password login Anda saat ini untuk melanjutkan.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="box-footer">
                                <button type="button" class="btn btn-warning btn-flat" onclick="confirmRestore()"><i class="fa fa-upload"></i> RESTORE</button>
                                <a href="backup" class="btn btn-danger btn-flat"><i class="fa fa-close"></i> Batal</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <?php else : ?>
                <div class="callout callout-danger">
                    <h4>Akses Ditolak!</h4>
                    <p>Hanya user tertentu yang dapat mengakses halaman ini.</p>
                </div>
            <?php endif; ?>
        </section>
    </div>
    <?php footer(); ?>
    <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<!-- Scripts -->
<script src="dist/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="dist/bootstrap/js/bootstrap.min.js"></script>
<script src="dist/plugins/slimScroll/jquery.slimscroll.min.js"></script>
<script src="dist/plugins/fastclick/fastclick.js"></script>
<script src="dist/js/app.min.js"></script>
<script>
    function confirmRestore() {
        swal({
            title: "Anda Yakin Akan Melanjutkan?",
            text: "Data saat ini akan ditimpa oleh data dari file backup. Pastikan Anda sudah yakin!",
            icon: "warning",
            buttons: ["Batal", "Ya, Lanjutkan!"],
            dangerMode: true,
        })
        .then((willRestore) => {
            if (willRestore) {
                // Submit form jika user setuju
                document.querySelector('form').submit();
            }
        });
    }

    // Jalankan skrip SweetAlert jika ada
    <?php if (!empty($alert_script)) : ?>
        document.addEventListener("DOMContentLoaded", function() {
            <?php echo $alert_script; ?>
        });
    <?php endif; ?>
</script>
</body>
</html>
