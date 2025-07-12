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
$halaman = "set_general";
$dataapa = "Pengaturan Umum";
$chmod = $chmenu10;
$alert_script = ""; // Variabel untuk menyimpan skrip SweetAlert

// ===================================================================
// PROSES FORM
// ===================================================================
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Proses Simpan Pengaturan Umum & Aplikasi
    if (isset($_POST['simpan'])) {
        if ($chmod >= 3 || $_SESSION['jabatan'] == 'admin') {
            // Data Aplikasi
            $url = mysqli_real_escape_string($conn, $_POST['url']);
            $session = mysqli_real_escape_string($conn, $_POST['session']);
            $footer = mysqli_real_escape_string($conn, $_POST['footer']);
            $pre = mysqli_real_escape_string($conn, $_POST['prefiks']);
            $checkbox = isset($_POST["checkbox"]) ? '0' : '1';

            $sql_app = "UPDATE backset SET url='$url', sessiontime='$session', footer='$footer', responsive='$checkbox', prefikbarcode='$pre'";
            $proses_app = mysqli_query($conn, $sql_app);

            // Data Perusahaan
            $nama = mysqli_real_escape_string($conn, $_POST['nama']);
            $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
            $notelp = mysqli_real_escape_string($conn, $_POST['notelp']);
            $tagline = mysqli_real_escape_string($conn, $_POST['tagline']);
            $signature = mysqli_real_escape_string($conn, $_POST['signature']);
            
            $avatar_path = "";
            if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == 0 && !empty($_FILES['avatar']['name'])) {
                $namaavatar = "logo_".basename($_FILES['avatar']['name']);
                $target_file = "dist/upload/" . $namaavatar;
                if (move_uploaded_file($_FILES['avatar']['tmp_name'], $target_file)) {
                    $avatar_path = $target_file;
                }
            }

            $sql_data = "UPDATE data SET nama='$nama', alamat='$alamat', notelp='$notelp', tagline='$tagline', signature='$signature'";
            if ($avatar_path != "") {
                $sql_data .= ", avatar='$avatar_path'";
            }
            $proses_data = mysqli_query($conn, $sql_data);

            if($proses_app && $proses_data){
                 $alert_script = "swal('Berhasil!', 'Pengaturan telah disimpan!', 'success').then(function(){ window.location = 'set_general'; });";
            } else {
                 $alert_script = "swal('Gagal!', 'Terjadi kesalahan saat menyimpan pengaturan. Error: ".addslashes(mysqli_error($conn))."', 'error');";
            }
        } else {
            $alert_script = "swal('Akses Ditolak!', 'Anda tidak memiliki izin untuk mengubah pengaturan!', 'error');";
        }
    }

    // Proses Reset Data
    if (isset($_POST["truncate"])) {
        $tables = ['barang', 'brand', 'kategori', 'mutasi', 'supplier', 'stok_keluar', 'stok_keluar_daftar', 'stok_masuk', 'stok_masuk_daftar', 'stok_sesuai', 'stok_sesuai_daftar', 'pelanggan', 'surat'];
        $success = true;
        foreach($tables as $table){
            if(!mysqli_query($conn, "TRUNCATE TABLE $table")){
                $success = false;
                $error_msg = addslashes(mysqli_error($conn));
                break;
            }
        }
        if($success){
            $alert_script = "swal('Berhasil!', 'Semua data transaksi dan master telah direset!', 'success').then(function(){ window.location = 'set_general'; });";
        } else {
            $alert_script = "swal('Gagal!', 'Terjadi kesalahan saat mereset data. Error: $error_msg', 'error');";
        }
    }
}

// ===================================================================
// AMBIL DATA PENGATURAN DARI DATABASE
// ===================================================================
$sql_app_data = "SELECT * FROM backset LIMIT 1";
$hasil_app = mysqli_query($conn, $sql_app_data);
$app = mysqli_fetch_assoc($hasil_app);

$sql_gen_data = "SELECT * FROM data LIMIT 1";
$hasil_gen = mysqli_query($conn, $sql_gen_data);
$gen = mysqli_fetch_assoc($hasil_gen);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Pengaturan Umum</title>
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
            <ol class="breadcrumb">
                <li><a href="<?php echo $_SESSION['baseurl']; ?>">Dashboard</a></li>
                <li class="active">Pengaturan</li>
            </ol>
            
            <?php if ($chmod >= 2 || $_SESSION['jabatan'] == 'admin') : ?>
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#tab_1" data-toggle="tab">Pengaturan Umum</a></li>
                    <li><a href="#tab_2" data-toggle="tab">Pengaturan Aplikasi</a></li>
                    <li><a href="#tab_3" data-toggle="tab">Opsi Lanjutan</a></li>
                </ul>
                <form class="form-horizontal" method="post" enctype="multipart/form-data">
                    <div class="tab-content">
                        <!-- Tab Pengaturan Umum -->
                        <div class="tab-pane active" id="tab_1">
                            <div class="box-body">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nama" class="col-sm-3 control-label">Nama Usaha</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="nama" name="nama" value="<?php echo htmlspecialchars($gen['nama']); ?>" placeholder="Nama Toko/Perusahaan">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="alamat" class="col-sm-3 control-label">Alamat</label>
                                        <div class="col-sm-9">
                                            <textarea class="form-control" id="alamat" name="alamat" rows="3" placeholder="Alamat Lengkap Usaha"><?php echo htmlspecialchars($gen['alamat']); ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="notelp" class="col-sm-3 control-label">No. Telepon</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="notelp" name="notelp" value="<?php echo htmlspecialchars($gen['notelp']); ?>" placeholder="Nomor Telepon">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tagline" class="col-sm-3 control-label">Tagline</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="tagline" name="tagline" value="<?php echo htmlspecialchars($gen['tagline']); ?>" placeholder="Tagline atau Slogan Usaha">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="signature" class="col-sm-3 control-label">Teks Footer Struk</label>
                                        <div class="col-sm-9">
                                            <textarea class="form-control" id="signature" name="signature" rows="3" placeholder="Teks di bagian bawah struk. Contoh: Terima Kasih"><?php echo htmlspecialchars($gen['signature']); ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="avatar" class="col-sm-3 control-label">Logo</label>
                                        <div class="col-sm-9">
                                            <?php if(!empty($gen['avatar'])): ?>
                                                <img src="<?php echo $gen['avatar']; ?>" class="img-responsive" style="max-height: 80px; margin-bottom: 10px;">
                                            <?php endif; ?>
                                            <input type="file" name="avatar">
                                            <p class="help-block">Kosongkan jika tidak ingin mengubah logo.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Tab Pengaturan Aplikasi -->
                        <div class="tab-pane" id="tab_2">
                            <div class="box-body">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="url" class="col-sm-3 control-label">URL Aplikasi</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="url" name="url" value="<?php echo htmlspecialchars($app['url']); ?>" placeholder="Contoh: http://localhost/nama_folder">
                                            <p class="help-block">URL lengkap instalasi aplikasi Anda.</p>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="session" class="col-sm-3 control-label">Waktu Sesi (Menit)</label>
                                        <div class="col-sm-9">
                                            <input type="number" class="form-control" id="session" name="session" value="<?php echo htmlspecialchars($app['sessiontime']); ?>" placeholder="Waktu logout otomatis">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="footer" class="col-sm-3 control-label">Teks Footer Aplikasi</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="footer" name="footer" value="<?php echo htmlspecialchars($app['footer']); ?>" placeholder="Teks di bagian bawah halaman">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="prefiks" class="col-sm-3 control-label">Prefiks Barcode & SKU</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="prefiks" name="prefiks" value="<?php echo htmlspecialchars($app['prefikbarcode']); ?>" placeholder="Contoh: OME">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-offset-3 col-sm-9">
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="checkbox" value="0" <?php if($app['responsive'] == '0') echo 'checked'; ?>> Tampilan Responsif
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Tab Opsi Lanjutan -->
                        <div class="tab-pane" id="tab_3">
                            <div class="box-body">
                                <div class="callout callout-danger">
                                    <h4><i class="icon fa fa-warning"></i> Peringatan: Zona Berbahaya</h4>
                                    <p>Tindakan di bawah ini akan menghapus data secara permanen dan tidak dapat dikembalikan. Lakukan dengan sangat hati-hati.</p>
                                </div>
                                <h4>Reset Data Aplikasi</h4>
                                <p>Menghapus semua data transaksi (penjualan, pembelian, stok) dan data master (barang, pelanggan, supplier, dll).</p>
                                <button type="button" class="btn btn-danger" onclick="confirmReset()"><i class="fa fa-trash"></i> RESET DATA APLIKASI</button>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary btn-flat" name="simpan"><i class="fa fa-save"></i> Simpan Semua Pengaturan</button>
                    </div>
                </form>
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

<!-- Form tersembunyi untuk proses reset -->
<form id="reset-form" method="post" action="">
    <input type="hidden" name="truncate" value="1">
</form>

<!-- Scripts -->
<script src="dist/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="dist/bootstrap/js/bootstrap.min.js"></script>
<script src="dist/plugins/select2/select2.full.min.js"></script>
<script src="dist/plugins/slimScroll/jquery.slimscroll.min.js"></script>
<script src="dist/plugins/fastclick/fastclick.js"></script>
<script src="dist/js/app.min.js"></script>
<script>
    function confirmReset() {
        swal({
            title: "Anda Yakin?",
            text: "Tindakan ini akan menghapus SEMUA data transaksi dan master. Data tidak dapat dikembalikan!",
            icon: "warning",
            buttons: {
                cancel: "Batal",
                confirm: {
                    text: "Ya, Saya Mengerti & Lanjutkan!",
                    value: true,
                    visible: true,
                    className: "btn-danger",
                }
            },
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
                swal({
                  text: 'Untuk konfirmasi, ketik "RESET" di bawah ini.',
                  content: "input",
                  button: {
                    text: "RESET SEKARANG",
                    closeModal: false,
                  },
                })
                .then(name => {
                  if (name.toUpperCase() !== "RESET") {
                    swal.stopLoading();
                    swal.close();
                    swal("Dibatalkan", "Teks konfirmasi tidak sesuai.", "error");
                  } else {
                    document.getElementById('reset-form').submit();
                  }
                });
            }
        });
    }

    $(function() {
        $('.select2').select2();
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
