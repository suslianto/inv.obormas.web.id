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
$halaman = "set_themes";
$dataapa = "Themes";
$alert_script = ""; // Variabel untuk menyimpan skrip SweetAlert

// ===================================================================
// PROSES PEMILIHAN TEMA
// ===================================================================
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['pilih_tema'])) {
    if ($_SESSION['jabatan'] == 'admin') {
        $pilih = mysqli_real_escape_string($conn, $_POST['pilih_tema']);
        
        $sql_check = "SELECT * FROM backset";
        $result_check = mysqli_query($conn, $sql_check);

        if (mysqli_num_rows($result_check) > 0) {
            $sql_update = "UPDATE backset SET themesback='$pilih'";
            $proses = mysqli_query($conn, $sql_update);
        } else {
            $sql_insert = "INSERT INTO backset (themesback) VALUES ('$pilih')";
            $proses = mysqli_query($conn, $sql_insert);
        }

        if ($proses) {
            $alert_script = "swal('Berhasil!', 'Tema telah berhasil diubah.', 'success').then(function(){ window.location = '$halaman'; });";
        } else {
            $alert_script = "swal('Gagal!', 'Terjadi kesalahan saat mengubah tema.', 'error');";
        }
    } else {
        $alert_script = "swal('Akses Ditolak!', 'Hanya admin yang dapat mengubah tema.', 'error');";
    }
}

// ===================================================================
// AMBIL TEMA AKTIF
// ===================================================================
$sql_theme = "SELECT themesback FROM backset LIMIT 1";
$hasil_theme = mysqli_query($conn, $sql_theme);
$data_theme = mysqli_fetch_assoc($hasil_theme);
$tema_aktif = $data_theme ? $data_theme['themesback'] : '1'; // Default theme 1 jika belum ada

// Array tema untuk memudahkan looping
$themes = [
    ['id' => '1', 'name' => 'Default Theme', 'image' => 'dist/img/themes/default.jpg'],
    ['id' => '2', 'name' => 'Blue Theme', 'image' => 'dist/img/themes/blue.jpg'],
    ['id' => '3', 'name' => 'Green Theme', 'image' => 'dist/img/themes/green.jpg'],
    ['id' => '4', 'name' => 'Purple Theme', 'image' => 'dist/img/themes/purple.jpg'],
    ['id' => '5', 'name' => 'Red Theme', 'image' => 'dist/img/themes/red.jpg'],
    ['id' => '6', 'name' => 'Yellow Theme', 'image' => 'dist/img/themes/yellow.jpg'],
];
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Pengaturan Tema</title>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <style>
        .thumbnail {
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 10px;
            transition: box-shadow .3s;
            /* Perbaikan: Hapus height: 100% agar tinggi kartu menyesuaikan konten */
        }
        .thumbnail:hover {
            box-shadow: 0 0 11px rgba(33,33,33,.2);
        }
        .thumbnail .caption {
            text-align: center;
            margin-top: 10px;
        }
        .theme-active-badge {
            position: absolute;
            top: 15px;
            left: 15px;
            background-color: #00a65a;
            color: white;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 12px;
        }
    </style>
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
            <ol class="breadcrumb">
                <li><a href="#">Pengaturan</a></li>
                <li class="active"><?php echo $dataapa; ?></li>
            </ol>

            <?php if ($_SESSION['jabatan'] == 'admin') : ?>
            <div class="row">
                <?php foreach ($themes as $theme) : ?>
                <div class="col-sm-6 col-md-3" style="margin-bottom: 20px;">
                    <div class="thumbnail">
                        <?php if ($tema_aktif == $theme['id']) : ?>
                            <span class="theme-active-badge"><i class="fa fa-check"></i> Aktif</span>
                        <?php endif; ?>
                        <img src="<?php echo $theme['image']; ?>" alt="<?php echo $theme['name']; ?>" class="img-responsive">
                        <div class="caption">
                            <h4><?php echo $theme['name']; ?></h4>
                            <form action="" method="post">
                                <button type="submit" class="btn btn-primary btn-flat" name="pilih_tema" value="<?php echo $theme['id']; ?>" <?php if ($tema_aktif == $theme['id']) echo 'disabled'; ?>>
                                    <i class="fa fa-paint-brush"></i> Pilih Tema
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else : ?>
                <div class="callout callout-danger">
                    <h4>Akses Ditolak!</h4>
                    <p>Hanya admin yang dapat mengakses halaman ini.</p>
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
