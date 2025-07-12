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
$halaman       = "admin";
$dataapa       = "Admin";
$tabeldatabase = "user";
$chmod         = $chmenu1;
$forward       = mysqli_real_escape_string($conn, $tabeldatabase);
$forwardpage   = mysqli_real_escape_string($conn, $halaman);
$no            = isset($_GET['no']) ? $_GET['no'] : '';
$alert_script  = ""; // Variabel untuk menyimpan skrip SweetAlert

// ===================================================================
// PROSES FORM (INSERT/UPDATE)
// ===================================================================
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Proses Simpan Data Utama
    if(isset($_POST['simpan'])){
        $username   = mysqli_real_escape_string($conn, $_POST["username"]);
        $nama       = mysqli_real_escape_string($conn, $_POST["nama"]);
        $jabatan    = mysqli_real_escape_string($conn, $_POST["jabatan"]);
        $nohp       = mysqli_real_escape_string($conn, $_POST["nohp"]);
        $alamat     = mysqli_real_escape_string($conn, $_POST["alamat"]);
        $tgllahir   = mysqli_real_escape_string($conn, $_POST["tgllahir"]);
        $tglaktif   = mysqli_real_escape_string($conn, $_POST["tglaktif"]);
        $insert     = mysqli_real_escape_string($conn, $_POST["insert"]);
        $no_get     = isset($_GET['no']) ? mysqli_real_escape_string($conn, $_GET['no']) : '';

        // Proses Upload Gambar
        $avatar_path = "";
        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == 0 && $_FILES['avatar']['name'] != '') {
            $namaavatar  = basename($_FILES['avatar']['name']);
            $tmp         = $_FILES['avatar']['tmp_name'];
            $target_file = "dist/upload/" . $namaavatar;
            if (move_uploaded_file($tmp, $target_file)) {
                $avatar_path = $target_file;
            }
        }

        if ($insert == '3') { // Mode Update
            if ($chmod >= 3 || $_SESSION['jabatan'] == 'admin') {
                $sql_update = "UPDATE $tabeldatabase SET nama='$nama', nohp='$nohp', alamat='$alamat', tgllahir='$tgllahir', tglaktif='$tglaktif', jabatan='$jabatan'";
                if ($avatar_path != "") {
                    $sql_update .= ", avatar='$avatar_path'";
                }
                $sql_update .= " WHERE no='$no_get'";
                if (mysqli_query($conn, $sql_update)) {
                    $alert_script = "swal('Berhasil!', 'Data telah diupdate!', 'success').then(function(){ window.location = 'user_profil?no=$no_get'; });";
                } else {
                    $alert_script = "swal('Gagal!', 'Gagal mengupdate data. Error: " . addslashes(mysqli_error($conn)) . "', 'error');";
                }
            } else {
                $alert_script = "swal('Akses Ditolak!', 'Anda tidak memiliki izin untuk mengupdate data!', 'error');";
            }
        } else { // Mode Insert
            if ($chmod >= 2 || $_SESSION['jabatan'] == 'admin') {
                $password  = md5($_POST["password"]);
                $password  = sha1($password);
                $password2 = md5($_POST["password2"]);
                $password2 = sha1($password2);

                if ($password != $password2) {
                    $alert_script = "swal('Gagal!', 'Kata sandi tidak cocok, pastikan Anda mengisinya dengan benar.', 'error');";
                } else {
                    $sql_check = "SELECT userna_me FROM $tabeldatabase WHERE userna_me='$username'";
                    if (mysqli_num_rows(mysqli_query($conn, $sql_check)) > 0) {
                        $alert_script = "swal('Gagal!', 'Username sudah digunakan, silakan gunakan username lain.', 'error');";
                    } else {
                        $avatar_final = ($avatar_path == "") ? "dist/upload/index.jpg" : $avatar_path;
                        $sql_insert = "INSERT INTO $tabeldatabase (userna_me, pa_ssword, nama, alamat, nohp, tgllahir, tglaktif, jabatan, avatar) VALUES ('$username', '$password', '$nama', '$alamat', '$nohp', '$tgllahir', '$tglaktif', '$jabatan', '$avatar_final')";
                        if (mysqli_query($conn, $sql_insert)) {
                            $alert_script = "swal('Berhasil!', 'User baru telah ditambahkan!', 'success').then(function(){ window.location = '$forwardpage'; });";
                        } else {
                            $alert_script = "swal('Gagal!', 'Gagal menyimpan data. Error: " . addslashes(mysqli_error($conn)) . "', 'error');";
                        }
                    }
                }
            } else {
                $alert_script = "swal('Akses Ditolak!', 'Anda tidak memiliki izin untuk menambah data!', 'error');";
            }
        }
    }

    // Proses Edit Password
    if(isset($_POST['edit'])){
        $password = mysqli_real_escape_string($conn, $_POST["password"]);
        $password2 = mysqli_real_escape_string($conn, $_POST["password2"]);
        $una = mysqli_real_escape_string($conn, $_POST["username"]);

        if ($password == $password2 && !empty($password)){
            $ipassword = sha1(md5($password));
            $sql_pass = "UPDATE $tabeldatabase SET pa_ssword='$ipassword' WHERE userna_me='$una'";
            if(mysqli_query($conn, $sql_pass)){
                $alert_script = "swal('Berhasil!', 'Password telah diubah!', 'success');";
            } else {
                $alert_script = "swal('Gagal!', 'Gagal mengubah password. Error: " . addslashes(mysqli_error($conn)) . "', 'error');";
            }
        } else {
            $alert_script = "swal('Gagal!', 'Password tidak cocok atau kosong!', 'error');";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Manajemen Admin</title>
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
                $username = $password = $nama = $alamat = $nohp = $tgllahir = $tglaktif = $avatar = $jabatan = "";
                $insert_mode = '1';

                if (!empty($no) && ($chmod >= 3 || $_SESSION['jabatan'] == 'admin')) {
                    $sql_edit   = "SELECT * FROM $tabeldatabase WHERE no='$no'";
                    $hasil_edit = mysqli_query($conn, $sql_edit);
                    if ($fill = mysqli_fetch_assoc($hasil_edit)) {
                        $username = $fill["userna_me"];
                        $nama = $fill["nama"];
                        $alamat = $fill["alamat"];
                        $nohp = $fill["nohp"];
                        $tgllahir = $fill["tgllahir"];
                        $tglaktif = $fill["tglaktif"];
                        $jabatan = $fill["jabatan"];
                        $avatar = $fill["avatar"];
                        $insert_mode = '3';
                    }
                }
                ?>
                <div class="row">
                    <div class="col-md-7">
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <h3 class="box-title">Formulir <?php echo $dataapa; ?></h3>
                            </div>
                            <form class="form-horizontal" method="post" action="" enctype="multipart/form-data">
                                <div class="box-body">
                                    <div class="form-group">
                                        <label for="username" class="col-sm-3 control-label">Username</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" maxlength="20" required <?php if(!empty($no)) echo 'readonly'; ?>>
                                        </div>
                                    </div>
                                    <?php if(empty($no)): ?>
                                    <div class="form-group">
                                        <label for="password" class="col-sm-3 control-label">Kata Sandi</label>
                                        <div class="col-sm-9">
                                            <input type="password" class="form-control" id="password" name="password" placeholder="Masukan Kata Sandi" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="password2" class="col-sm-3 control-label">Ulangi Kata Sandi</label>
                                        <div class="col-sm-9">
                                            <input type="password" class="form-control" id="password2" name="password2" placeholder="Ulangi Kata Sandi" required>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                    <div class="form-group">
                                        <label for="nama" class="col-sm-3 control-label">Nama Lengkap</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="nama" name="nama" value="<?php echo htmlspecialchars($nama); ?>" placeholder="Masukan Nama Lengkap" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="alamat" class="col-sm-3 control-label">Alamat</label>
                                        <div class="col-sm-9">
                                            <textarea class="form-control" id="alamat" name="alamat" placeholder="Masukan Alamat"><?php echo htmlspecialchars($alamat); ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="nohp" class="col-sm-3 control-label">No. Handphone</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="nohp" name="nohp" value="<?php echo htmlspecialchars($nohp); ?>" placeholder="Masukan Nomor Handphone">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="datepicker" class="col-sm-3 control-label">Tgl. Lahir</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="datepicker" name="tgllahir" placeholder="YYYY-MM-DD" value="<?php echo htmlspecialchars($tgllahir); ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="datepicker2" class="col-sm-3 control-label">Tgl. Aktif</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="datepicker2" name="tglaktif" placeholder="YYYY-MM-DD" value="<?php echo htmlspecialchars($tglaktif); ?>">
                                        </div>
                                    </div>
                                    <?php if($_SESSION['jabatan'] == 'admin'): ?>
                                    <div class="form-group">
                                        <label for="jabatan" class="col-sm-3 control-label">Jabatan</label>
                                        <div class="col-sm-9">
                                            <select class="form-control select2" style="width: 100%;" name="jabatan" required>
                                                <?php
                                                $sql_jabatan = mysqli_query($conn, "SELECT DISTINCT nama FROM jabatan");
                                                while ($row = mysqli_fetch_assoc($sql_jabatan)) {
                                                    $selected = ($jabatan == $row['nama']) ? "selected" : "";
                                                    echo "<option value='" . htmlspecialchars($row['nama']) . "' " . $selected . ">" . htmlspecialchars($row['nama']) . "</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                    <div class="form-group">
                                        <label for="avatar" class="col-sm-3 control-label">Avatar</label>
                                        <div class="col-sm-9">
                                            <input type="file" name="avatar">
                                            <?php if(!empty($avatar)): ?>
                                                <p class="help-block">Kosongkan jika tidak ingin mengubah avatar.</p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="box-footer">
                                    <button type="submit" class="btn btn-primary btn-flat" name="simpan"><i class="fa fa-save"></i> Simpan</button>
                                    <a href="<?php echo $halaman; ?>" class="btn btn-danger btn-flat"><i class="fa fa-close"></i> Batal</a>
                                </div>
                                <input type="hidden" name="insert" value="<?php echo $insert_mode; ?>">
                            </form>
                        </div>
                    </div>

                    <div class="col-md-5">
                        <?php if(!empty($no)): ?>
                        <div class="box box-widget widget-user">
                            <div class="widget-user-header bg-aqua-active">
                                <h3 class="widget-user-username"><?php echo htmlspecialchars($nama); ?></h3>
                                <h5 class="widget-user-desc"><?php echo htmlspecialchars($jabatan); ?></h5>
                            </div>
                            <div class="widget-user-image">
                                <img class="img-circle" src="<?php echo htmlspecialchars($avatar); ?>" alt="User Avatar">
                            </div>
                            <div class="box-footer">
                                <div class="row">
                                    <div class="col-sm-6 border-right">
                                        <div class="description-block">
                                            <?php
                                            $sql_penjualan = mysqli_query($conn, "SELECT COUNT(nota) AS data FROM bayar WHERE kasir ='$username'");
                                            $penjualan = mysqli_fetch_assoc($sql_penjualan)['data'];
                                            ?>
                                            <h5 class="description-header"><?php echo $penjualan; ?></h5>
                                            <span class="description-text">TRX PENJUALAN</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="description-block">
                                            <?php
                                            $sql_pembelian = mysqli_query($conn, "SELECT COUNT(nota) AS data FROM beli WHERE kasir ='$username'");
                                            $pembelian = mysqli_fetch_assoc($sql_pembelian)['data'];
                                            ?>
                                            <h5 class="description-header"><?php echo $pembelian; ?></h5>
                                            <span class="description-text">TRX PEMBELIAN</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="box box-danger">
                            <div class="box-header with-border">
                                <h3 class="box-title">Ubah Password</h3>
                            </div>
                            <form class="form-horizontal" method="post" action="">
                                <div class="box-body">
                                    <input type="hidden" name="username" value="<?php echo htmlspecialchars($username); ?>">
                                    <div class="form-group">
                                        <label for="password_edit" class="col-sm-4 control-label">Password Baru</label>
                                        <div class="col-sm-8">
                                            <input type="password" class="form-control" id="password_edit" name="password" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="password2_edit" class="col-sm-4 control-label">Ulangi Password</label>
                                        <div class="col-sm-8">
                                            <input type="password" class="form-control" id="password2_edit" name="password2" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="box-footer">
                                    <button type="submit" class="btn btn-danger btn-flat" name="edit"><i class="fa fa-key"></i> Simpan Password</button>
                                </div>
                            </form>
                        </div>
                        <?php endif; ?>
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
<script src="dist/plugins/select2/select2.full.min.js"></script>
<script src="dist/plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="dist/plugins/slimScroll/jquery.slimscroll.min.js"></script>
<script src="dist/plugins/fastclick/fastclick.js"></script>
<script src="dist/js/app.min.js"></script>
<script>
    $(function() {
        $('.select2').select2();
        $('#datepicker, #datepicker2').datepicker({
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
