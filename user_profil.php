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
$halaman       = "user_profil";
$dataapa       = "Profil User";
$tabeldatabase = "user";
$chmod         = $chmenu1; // Disesuaikan dengan menu admin/user
$forwardpage   = "admin"; // Halaman kembali setelah simpan
$no            = isset($_GET['no']) ? mysqli_real_escape_string($conn, $_GET['no']) : $_SESSION['nouser'];
$alert_script  = ""; // Variabel untuk menyimpan skrip SweetAlert

// ===================================================================
// PROSES FORM (UPDATE PROFIL / PASSWORD)
// ===================================================================
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Proses Simpan Data Profil
    if (isset($_POST['simpan'])) {
        if ($chmod >= 3 || $_SESSION['jabatan'] == 'admin') {
            $nama       = mysqli_real_escape_string($conn, $_POST["nama"]);
            $jabatan    = isset($_POST["jabatan"]) ? mysqli_real_escape_string($conn, $_POST["jabatan"]) : '';
            $nohp       = mysqli_real_escape_string($conn, $_POST["nohp"]);
            $alamat     = mysqli_real_escape_string($conn, $_POST["alamat"]);
            $tgllahir   = mysqli_real_escape_string($conn, $_POST["tgllahir"]);
            $tglaktif   = mysqli_real_escape_string($conn, $_POST["tglaktif"]);
            
            // Proses Upload Gambar
            $avatar_path = "";
            if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == 0 && !empty($_FILES['avatar']['name'])) {
                $namaavatar  = "avatar_".$no."_".basename($_FILES['avatar']['name']);
                $tmp         = $_FILES['avatar']['tmp_name'];
                $target_file = "dist/upload/" . $namaavatar;
                if (move_uploaded_file($tmp, $target_file)) {
                    $avatar_path = $target_file;
                }
            }

            $sql_update = "UPDATE $tabeldatabase SET nama='$nama', nohp='$nohp', alamat='$alamat', tgllahir='$tgllahir', tglaktif='$tglaktif'";
            if (!empty($jabatan)) {
                $sql_update .= ", jabatan='$jabatan'";
            }
            if ($avatar_path != "") {
                $sql_update .= ", avatar='$avatar_path'";
            }
            $sql_update .= " WHERE no='$no'";

            if (mysqli_query($conn, $sql_update)) {
                $alert_script = "swal('Berhasil!', 'Profil telah diupdate!', 'success').then(function(){ window.location.href = 'user_profil?no=$no'; });";
            } else {
                $alert_script = "swal('Gagal!', 'Gagal mengupdate profil. Error: " . addslashes(mysqli_error($conn)) . "', 'error');";
            }
        } else {
            $alert_script = "swal('Akses Ditolak!', 'Anda tidak memiliki izin untuk mengupdate data!', 'error');";
        }
    }

    // Proses Edit Password
    if (isset($_POST['edit_password'])) {
        $password  = $_POST["password"];
        $password2 = $_POST["password2"];
        $username  = mysqli_real_escape_string($conn, $_POST["username"]);

        if ($password == $password2 && !empty($password)) {
            $ipassword = sha1(md5($password));
            $sql_pass = "UPDATE $tabeldatabase SET pa_ssword='$ipassword' WHERE userna_me='$username'";
            if (mysqli_query($conn, $sql_pass)) {
                $alert_script = "swal('Berhasil!', 'Password telah diubah!', 'success');";
            } else {
                $alert_script = "swal('Gagal!', 'Gagal mengubah password. " . addslashes(mysqli_error($conn)) . "', 'error');";
            }
        } else {
            $alert_script = "swal('Gagal!', 'Password tidak cocok atau kosong!', 'error');";
        }
    }
}

// ===================================================================
// AMBIL DATA USER DARI DATABASE
// ===================================================================
$sql   = "SELECT * FROM $tabeldatabase WHERE no = '$no'";
$query = mysqli_query($conn, $sql);
$data  = mysqli_fetch_assoc($query);
if(!$data){
    echo "User tidak ditemukan.";
    exit;
}
// Variabel untuk Profile Card (kiri) dan Form (kanan)
$username  = $data['userna_me'];
$avatar    = $data['avatar'];
$nama      = $data['nama'];
$jabatan   = $data['jabatan'];
$alamat    = $data['alamat'];
$nohp      = $data['nohp'];
$tgllahir  = $data['tgllahir'];
$tglaktif  = $data['tglaktif'];
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Profil User</title>
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
                <li><a href="admin">Daftar User</a></li>
                <li class="active"><?php echo $dataapa; ?></li>
            </ol>
            
            <div class="row">
                <div class="col-md-3">
                    <!-- Profile Image -->
                    <div class="box box-primary">
                        <div class="box-body box-profile">
                            <img class="profile-user-img img-responsive img-circle" src="<?php echo htmlspecialchars($avatar); ?>" alt="User profile picture">
                            <h3 class="profile-username text-center"><?php echo htmlspecialchars($nama); ?></h3>
                            <p class="text-muted text-center"><?php echo htmlspecialchars($jabatan); ?></p>
                            <ul class="list-group list-group-unbordered">
                                <li class="list-group-item"><b>Telepon</b> <a class="pull-right"><?php echo htmlspecialchars($nohp); ?></a></li>
                                <li class="list-group-item"><b>Tgl Lahir</b> <a class="pull-right"><?php echo htmlspecialchars($tgllahir); ?></a></li>
                                <li class="list-group-item"><b>Tgl Gabung</b> <a class="pull-right"><?php echo htmlspecialchars($tglaktif); ?></a></li>
                            </ul>
                        </div>
                    </div>
                    <!-- About Me Box -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Tentang Saya</h3>
                        </div>
                        <div class="box-body">
                            <strong><i class="fa fa-map-marker margin-r-5"></i> Alamat</strong>
                            <p class="text-muted"><?php echo htmlspecialchars($alamat); ?></p>
                        </div>
                    </div>
                </div>

                <div class="col-md-9">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#activity" data-toggle="tab">Aktivitas Terakhir</a></li>
                            <li><a href="#settings" data-toggle="tab">Pengaturan</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="active tab-pane" id="activity">
                                <div class="box-body table-responsive no-padding">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Tanggal</th>
                                                <th>Aktivitas</th>
                                                <th>Barang</th>
                                                <th>Jumlah</th>
                                                <th>Stok Akhir</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            // PERBAIKAN: Menggunakan variabel $nama (nama lengkap) untuk filter, bukan $username
                                            $sql_mutasi = "SELECT * FROM mutasi INNER JOIN barang ON mutasi.kodebarang=barang.kode WHERE mutasi.namauser='$nama' ORDER BY mutasi.tgl DESC, mutasi.jam DESC LIMIT 15";
                                            $result_mutasi = mysqli_query($conn, $sql_mutasi);
                                            $no_mutasi = 1;
                                            while ($fill = mysqli_fetch_assoc($result_mutasi)) :
                                            ?>
                                            <tr>
                                                <td><?php echo $no_mutasi++; ?></td>
                                                <td><?php echo date('d-m-Y, H:i', strtotime($fill['tgl'] . ' ' . $fill['jam'])); ?></td>
                                                <td><?php echo htmlspecialchars($fill['kegiatan']); ?></td>
                                                <td><?php echo htmlspecialchars($fill['nama']); ?></td>
                                                <td><?php echo htmlspecialchars($fill['jumlah']); ?></td>
                                                <td><?php echo htmlspecialchars($fill['sisa']); ?></td>
                                            </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="tab-pane" id="settings">
                                <form class="form-horizontal" method="post" action="" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label for="username" class="col-sm-2 control-label">Username</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="nama" class="col-sm-2 control-label">Nama Lengkap</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="nama" name="nama" value="<?php echo htmlspecialchars($nama); ?>" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="nohp" class="col-sm-2 control-label">No. HP</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="nohp" name="nohp" value="<?php echo htmlspecialchars($nohp); ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="alamat" class="col-sm-2 control-label">Alamat</label>
                                        <div class="col-sm-10">
                                            <textarea class="form-control" id="alamat" name="alamat"><?php echo htmlspecialchars($alamat); ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="datepicker" class="col-sm-2 control-label">Tgl. Lahir</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="datepicker" name="tgllahir" value="<?php echo htmlspecialchars($tgllahir); ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="datepicker2" class="col-sm-2 control-label">Tgl. Aktif</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="datepicker2" name="tglaktif" value="<?php echo htmlspecialchars($tglaktif); ?>">
                                        </div>
                                    </div>
                                    <?php if ($_SESSION['jabatan'] == 'admin') : ?>
                                    <div class="form-group">
                                        <label for="jabatan" class="col-sm-2 control-label">Jabatan</label>
                                        <div class="col-sm-10">
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
                                        <label for="avatar" class="col-sm-2 control-label">Avatar</label>
                                        <div class="col-sm-10">
                                            <input type="file" name="avatar">
                                            <p class="help-block">Kosongkan jika tidak ingin mengubah avatar.</p>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-10">
                                            <button type="submit" name="simpan" class="btn btn-primary btn-flat">Simpan Perubahan</button>
                                        </div>
                                    </div>
                                </form>
                                <hr>
                                <!-- Form Ubah Password -->
                                <form class="form-horizontal" method="post" action="">
                                    <input type="hidden" name="username" value="<?php echo htmlspecialchars($username); ?>">
                                    <div class="form-group">
                                        <label for="password_edit" class="col-sm-2 control-label">Password Baru</label>
                                        <div class="col-sm-10">
                                            <input type="password" class="form-control" id="password_edit" name="password" placeholder="Masukan password baru" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="password2_edit" class="col-sm-2 control-label">Ulangi Password</label>
                                        <div class="col-sm-10">
                                            <input type="password" class="form-control" id="password2_edit" name="password2" placeholder="Ulangi password baru" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-10">
                                            <button type="submit" name="edit_password" class="btn btn-danger btn-flat">Ubah Password</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
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
