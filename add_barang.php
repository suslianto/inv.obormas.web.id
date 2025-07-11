<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Manajemen Barang</title>
    <!-- Tambahkan pustaka SweetAlert -->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
</head>
<?php
// Konfigurasi dan Inisialisasi
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

// Cek status login
if (!login_check()) {
    echo '<meta http-equiv="refresh" content="0; url=logout" />';
    exit(0);
}

// Pengaturan Halaman
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
include "configuration/config_chmod.php";
$halaman = "barang";
$dataapa = "Barang";
$tabeldatabase = "barang";
$chmod = $chmenu4;
$forward = mysqli_real_escape_string($conn, $tabeldatabase);
$forwardpage = mysqli_real_escape_string($conn, $halaman);
$id = isset($_GET['q']) ? $_GET['q'] : '';

// Fungsi Bantuan
function autoNumber() {
    include "configuration/config_connect.php";
    global $forward;
    $query = "SELECT MAX(no) as max_id FROM $forward ORDER BY no";
    $result = mysqli_query($conn, $query);
    $data = mysqli_fetch_array($result);
    $id_max = $data['max_id'];
    $sort_num = $id_max;
    $sort_num++;
    return sprintf("%06s", $sort_num);
}

$m = mysqli_fetch_assoc(mysqli_query($conn, "SELECT mode FROM backset"));
$mode = $m['mode'];
?>

<body class="hold-transition skin-purple sidebar-mini">
<div class="wrapper">
    <?php
    theader();
    menu();
    ?>
    <div class="content-wrapper">
        <section class="content-header">
            <!-- BREADCRUMB -->
            <ol class="breadcrumb">
                <li><a href="<?php echo $_SESSION['baseurl']; ?>">Dashboard</a></li>
                <li><a href="<?php echo $halaman; ?>"><?php echo $dataapa; ?></a></li>
                <li class="active"><?php echo isset($_POST['search']) && $_POST['search'] != '' ? 'Hasil untuk "' . $_POST['search'] . '"' : 'Data ' . $dataapa; ?></li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <?php if ($chmod >= 2 || $_SESSION['jabatan'] == 'admin') : ?>
                <?php
                $w = []; // Inisialisasi array $w untuk menghindari error undefined
                if (!empty($id)) {
                    $w_query = mysqli_query($conn, "SELECT * FROM barang WHERE no='$id'");
                    $w = mysqli_fetch_assoc($w_query);
                }
                ?>
                <div class="row">
                    <div class="col-md-8 col-lg-8 col-xs-12">
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <h3 class="box-title">Formulir <?php echo $dataapa; ?></h3>
                                <div class="box-tools pull-right">
                                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                                </div>
                            </div>
                            <div class="box-body">
                                <form method="post" enctype="multipart/form-data">
                                    <div class="nav-tabs-custom">
                                        <ul class="nav nav-tabs">
                                            <li class="active"><a href="#tab_1" data-toggle="tab">Informasi Dasar</a></li>
                                            <li><a href="#tab_4" data-toggle="tab">Informasi Lanjutan</a></li>
                                        </ul>
                                        <div class="tab-content">
                                            <!-- Tab Informasi Dasar -->
                                            <div class="tab-pane active" id="tab_1">
                                                <div class="row">
                                                    <div class="col-md-7">
                                                        <table class="table table-borderless">
                                                            <tr>
                                                                <td style="width:150px">SKU Barang</td>
                                                                <td>
                                                                    <?php if (!empty($id)) : ?>
                                                                        <input class="form-control" type="hidden" name="kode" value="<?php echo $w['kode']; ?>">
                                                                        <input class="form-control" type="text" name="sku" value="<?php echo $w['sku']; ?>">
                                                                    <?php else : ?>
                                                                        <input class="form-control" type="hidden" name="kode" value="<?php echo autoNumber(); ?>">
                                                                        <input class="form-control" type="text" name="sku" value="SKU<?php echo autoNumber(); ?>">
                                                                    <?php endif; ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Nama Barang</td>
                                                                <td><input class="form-control" name="nama" maxlength="200" autocomplete="off" value="<?php echo isset($w['nama']) ? $w['nama'] : ''; ?>" required></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Kategori</td>
                                                                <td>
                                                                    <div class="input-group">
                                                                        <select class="form-control select2" name="kategori" required>
                                                                            <?php
                                                                            $sql = mysqli_query($conn, "select * from kategori");
                                                                            while ($row = mysqli_fetch_assoc($sql)) {
                                                                                $selected = (isset($w['kategori']) && $w['kategori'] == $row['nama']) ? "selected" : "";
                                                                                echo "<option value='" . $row['nama'] . "' " . $selected . ">" . $row['nama'] . "</option>";
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                        <span class="input-group-btn"><a class="btn btn-default" href="add_kategori"><i class="fa fa-plus"></i></a></span>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Satuan</td>
                                                                <td>
                                                                    <div class="input-group">
                                                                        <select class="form-control select2" name="satuan" required>
                                                                            <?php
                                                                            $sql = mysqli_query($conn, "select * from satuan");
                                                                            while ($row = mysqli_fetch_assoc($sql)) {
                                                                                $selected = (isset($w['satuan']) && $w['satuan'] == $row['nama']) ? "selected" : "";
                                                                                echo "<option value='" . $row['nama'] . "' " . $selected . ">" . $row['nama'] . "</option>";
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                        <span class="input-group-btn"><a class="btn btn-default" href="add_satuan"><i class="fa fa-plus"></i></a></span>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Merek</td>
                                                                <td>
                                                                    <div class="input-group">
                                                                        <select class="form-control select2" name="merek" required>
                                                                            <?php
                                                                            $sql = mysqli_query($conn, "select * from brand");
                                                                            while ($row = mysqli_fetch_assoc($sql)) {
                                                                                $selected = (isset($w['brand']) && $w['brand'] == $row['nama']) ? "selected" : "";
                                                                                echo "<option value='" . $row['nama'] . "' " . $selected . ">" . $row['nama'] . "</option>";
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                        <span class="input-group-btn"><a class="btn btn-default" href="add_merek"><i class="fa fa-plus"></i></a></span>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <table class="table table-borderless">
                                                            <?php if (!empty($id)) : ?>
                                                                <tr>
                                                                    <td>Stok Minimal</td>
                                                                    <td><input class="form-control" name="stok_minimal" type="number" min="1" value="<?php echo $w['stokmin']; ?>" required></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Barcode</td>
                                                                    <td><input class="form-control" name="barcode" value="<?php echo $w['barcode']; ?>" required></td>
                                                                </tr>
                                                            <?php else : ?>
                                                                <tr>
                                                                    <td>Stok Awal</td>
                                                                    <td><input class="form-control" name="stok" type="number" min="0" required></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Stok Minimal</td>
                                                                    <td><input class="form-control" name="stok_minimal" type="number" min="1" value="1" required></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Barcode</td>
                                                                    <td><input class="form-control" name="barcode" value="BRG<?php echo autoNumber(); ?>" required></td>
                                                                </tr>
                                                            <?php endif; ?>
                                                            <?php if ($mode >= 1) : ?>
                                                                <tr>
                                                                    <td>Harga Beli</td>
                                                                    <td><input class="form-control" name="harga_beli" required autocomplete="off" value="<?php echo isset($w['hargabeli']) ? $w['hargabeli'] : ''; ?>"></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Harga Jual</td>
                                                                    <td><input class="form-control" name="harga_jual" required autocomplete="off" value="<?php echo isset($w['hargajual']) ? $w['hargajual'] : ''; ?>"></td>
                                                                </tr>
                                                            <?php endif; ?>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Tab Informasi Lanjutan -->
                                            <div class="tab-pane" id="tab_4">
                                                <div class="row">
                                                    <div class="col-lg-8">
                                                        <table class="table table-borderless">
                                                            <tr>
                                                                <td>Ukuran</td>
                                                                <td>
                                                                    <select class="form-control" name="ukuran">
                                                                        <?php if (!empty($id) && isset($w['ukuran'])) { echo ' <option value="' . $w['ukuran'] . '" selected>' . $w['ukuran'] . '</option>'; } ?>
                                                                        <option value="">--Pilih--</option>
                                                                        <option>XXL</option> <option>XL</option> <option>L</option> <option>M</option> <option>S</option> <option>XS</option>
                                                                    </select>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Warna</td>
                                                                <td><input class="form-control" name="warna" value="<?php echo isset($w['warna']) ? $w['warna'] : ''; ?>"></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Lokasi Rak</td>
                                                                <td><input class="form-control" name="lokasi" value="<?php echo isset($w['lokasi']) ? $w['lokasi'] : ''; ?>"></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Tanggal Kedaluwarsa</td>
                                                                <td><input id="datepicker" class="form-control" data-language="en" name="expired" autocomplete="off" value="<?php echo isset($w['expired']) ? $w['expired'] : ''; ?>"></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Keterangan</td>
                                                                <td><input class="form-control" name="keterangan" autocomplete="off" value="<?php echo isset($w['keterangan']) ? $w['keterangan'] : ''; ?>"></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Gambar</td>
                                                                <td><input type="file" class="form-control" name="avatar"></td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="box-footer">
                                        <button class="btn btn-primary" type="submit" name="savebarang"><i class="fa fa-check-square-o"></i> Simpan</button>
                                        <a class="btn btn-warning" href="add_barang"><i class="fa fa-retweet"></i> Reset</a>
                                        <a class="btn btn-danger" href="barang"><i class="fa fa-window-close"></i> Batal</a>
                                    </div>
                                </form>
                            </div>
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

<?php
// Proses Form
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['savebarang'])) {
    // Sanitasi Input
    $kode = mysqli_real_escape_string($conn, $_POST["kode"]);
    $sku = mysqli_real_escape_string($conn, $_POST["sku"]);
    $nama = mysqli_real_escape_string($conn, $_POST["nama"]);
    $satuan = mysqli_real_escape_string($conn, $_POST["satuan"]);
    $kategori = mysqli_real_escape_string($conn, $_POST["kategori"]);
    $hargabeli = isset($_POST["harga_beli"]) ? mysqli_real_escape_string($conn, $_POST["harga_beli"]) : 0;
    $hargajual = isset($_POST["harga_jual"]) ? mysqli_real_escape_string($conn, $_POST["harga_jual"]) : 0;
    $stok = isset($_POST["stok"]) ? mysqli_real_escape_string($conn, $_POST["stok"]) : 0;
    $stokmin = mysqli_real_escape_string($conn, $_POST["stok_minimal"]);
    $ukuran = mysqli_real_escape_string($conn, $_POST["ukuran"]);
    $warna = mysqli_real_escape_string($conn, $_POST["warna"]);
    $brand = mysqli_real_escape_string($conn, $_POST["merek"]);
    $rak = mysqli_real_escape_string($conn, $_POST["lokasi"]);
    $exp = mysqli_real_escape_string($conn, $_POST["expired"]);
    $ket = mysqli_real_escape_string($conn, $_POST["keterangan"]);
    $barcode = mysqli_real_escape_string($conn, $_POST["barcode"]);

    // Proses Upload Gambar
    $avatar = '';
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == 0 && $_FILES['avatar']['name'] != '') {
        $namaavatar = basename($_FILES['avatar']['name']);
        $tmp = $_FILES['avatar']['tmp_name'];
        $avatar_path = "dist/upload/" . $namaavatar;
        if (move_uploaded_file($tmp, $avatar_path)) {
            $avatar = $avatar_path;
        }
    }

    $usr = $_SESSION['nama'];
    $now = date('Y-m-d');
    $jam = date('H:i');

    // Cek apakah data sudah ada (untuk update) atau belum (untuk insert)
    $sql_check = "SELECT * FROM $tabeldatabase WHERE kode='$kode'";
    $result_check = mysqli_query($conn, $sql_check);

    if (mysqli_num_rows($result_check) > 0) {
        // --- PROSES UPDATE ---
        if ($chmod >= 3 || $_SESSION['jabatan'] == 'admin') {
            $sql_update = "UPDATE barang SET sku='$sku', nama='$nama', kategori='$kategori', hargabeli='$hargabeli', hargajual='$hargajual', keterangan='$ket', satuan='$satuan', stokmin='$stokmin', barcode='$barcode', brand='$brand', lokasi='$rak', expired='$exp', warna='$warna', ukuran='$ukuran'";
            if ($avatar != '') {
                $sql_update .= ", avatar='$avatar'";
            }
            $sql_update .= " WHERE kode='$kode'";

            if (mysqli_query($conn, $sql_update)) {
                echo "<script>swal('Berhasil!', 'Data barang telah diupdate!', 'success').then(function() { window.location = '$forwardpage'; });</script>";
            } else {
                $error_msg = mysqli_error($conn);
                echo "<script>swal('Gagal!', 'Terjadi kesalahan saat mengupdate data. Error: " . addslashes($error_msg) . "', 'error');</script>";
            }
        } else {
            echo "<script>swal('Akses Ditolak!', 'Anda tidak memiliki izin untuk mengupdate data!', 'error');</script>";
        }
    } else {
        // --- PROSES INSERT ---
        if ($chmod >= 2 || $_SESSION['jabatan'] == 'admin') {
            $avatar_final = ($avatar == '') ? "dist/upload/index.jpg" : $avatar;
            // Menggunakan query INSERT dari kode original yang tampaknya memiliki 20 kolom
            $sql_insert = "INSERT INTO $tabeldatabase VALUES ('$kode','$sku','$nama','$hargabeli','$hargajual','$ket','$kategori','$satuan','','','$stok','$stokmin','$barcode','$brand','$rak','$exp','$warna','$ukuran','$avatar_final','')";

            if (mysqli_query($conn, $sql_insert)) {
                $sql_mutasi = "INSERT INTO mutasi VALUES('$usr','$now','$jam','$kode','$stok','$stok','menambah Produk','$kode','sistem','','berhasil')";
                mysqli_query($conn, $sql_mutasi);
                echo "<script>swal('Berhasil!', 'Data telah disimpan!', 'success').then(function() { window.location = '$forwardpage'; });</script>";
            } else {
                $error_msg = mysqli_error($conn);
                echo "<script>swal('Gagal!', 'Data gagal disimpan. Error: " . addslashes($error_msg) . "', 'error');</script>";
            }
        } else {
            echo "<script>swal('Akses Ditolak!', 'Anda tidak memiliki izin untuk menambah data!', 'error');</script>";
        }
    }
}
?>

<!-- Script Tambahan -->
<script src="dist/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="dist/bootstrap/js/bootstrap.min.js"></script>
<script src="dist/plugins/select2/select2.full.min.js"></script>
<script src="dist/plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="dist/plugins/slimScroll/jquery.slimscroll.min.js"></script>
<script src="dist/plugins/fastclick/fastclick.js"></script>
<script src="dist/js/app.min.js"></script>

<script>
    $(function() {
        // Inisialisasi Select2
        $(".select2").select2();

        // Inisialisasi Datepicker
        $('#datepicker').datepicker({
            autoclose: true,
            format: 'yyyy-mm-dd'
        });
    });
</script>
</body>
</html>
