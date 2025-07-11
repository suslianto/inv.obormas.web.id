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
$halaman       = "barang";
$dataapa       = "Barang";
$tabeldatabase = "barang";
$chmod         = $chmenu4;
$forward       = mysqli_real_escape_string($conn, $tabeldatabase);
$forwardpage   = mysqli_real_escape_string($conn, $halaman);
$id            = isset($_GET['q']) ? $_GET['q'] : '';
$alert_script  = ""; // Variabel untuk menyimpan skrip SweetAlert

// ===================================================================
// FUNGSI BANTUAN
// ===================================================================
function autoNumber() {
    global $conn;
    $query    = "SELECT MAX(no) as max_id FROM barang";
    $result   = mysqli_query($conn, $query);
    $data     = mysqli_fetch_array($result);
    $id_max   = $data['max_id'];
    $sort_num = $id_max;
    $sort_num++;
    return sprintf("%04s", $sort_num);
}

$m    = mysqli_fetch_assoc(mysqli_query($conn, "SELECT mode FROM backset"));
$mode = $m['mode'];

// ===================================================================
// PROSES FORM (INSERT/UPDATE)
// ===================================================================
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['savebarang'])) {
    // Sanitasi Input
    $kode      = mysqli_real_escape_string($conn, $_POST["kode"]);
    $sku       = mysqli_real_escape_string($conn, $_POST["sku"]);
    $nama      = mysqli_real_escape_string($conn, $_POST["nama"]);
    $satuan    = mysqli_real_escape_string($conn, $_POST["satuan"]);
    $kategori  = mysqli_real_escape_string($conn, $_POST["kategori"]);
    $hargabeli = isset($_POST["harga_beli"]) ? mysqli_real_escape_string($conn, $_POST["harga_beli"]) : 0;
    $hargajual = isset($_POST["harga_jual"]) ? mysqli_real_escape_string($conn, $_POST["harga_jual"]) : 0;
    $stok      = isset($_POST["stok"]) ? mysqli_real_escape_string($conn, $_POST["stok"]) : 0;
    $stokmin   = mysqli_real_escape_string($conn, $_POST["stok_minimal"]);
    $ukuran    = mysqli_real_escape_string($conn, $_POST["ukuran"]);
    $warna     = mysqli_real_escape_string($conn, $_POST["warna"]);
    $brand     = mysqli_real_escape_string($conn, $_POST["merek"]);
    $rak       = mysqli_real_escape_string($conn, $_POST["lokasi"]);
    $exp       = mysqli_real_escape_string($conn, $_POST["expired"]);
    $ket       = mysqli_real_escape_string($conn, $_POST["keterangan"]);
    $barcode   = mysqli_real_escape_string($conn, $_POST["barcode"]);
    $usr       = $_SESSION['nama'];
    $now       = date('Y-m-d');
    $jam       = date('H:i');

    // Proses Upload Gambar
    $avatar = '';
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == 0 && $_FILES['avatar']['name'] != '') {
        $namaavatar  = basename($_FILES['avatar']['name']);
        $tmp         = $_FILES['avatar']['tmp_name'];
        $avatar_path = "dist/upload/" . $namaavatar;
        if (move_uploaded_file($tmp, $avatar_path)) {
            $avatar = $avatar_path;
        }
    }

    $sql_check    = "SELECT * FROM $tabeldatabase WHERE kode='$kode'";
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
                $alert_script = "swal('Berhasil!', 'Data barang telah diupdate!', 'success').then(function() { window.location = '$forwardpage'; });";
            } else {
                $error_msg = addslashes(mysqli_error($conn));
                $alert_script = "swal('Gagal!', 'Terjadi kesalahan saat mengupdate data. Error: $error_msg', 'error');";
            }
        } else {
            $alert_script = "swal('Akses Ditolak!', 'Anda tidak memiliki izin untuk mengupdate data!', 'error');";
        }
    } else {
        // --- PROSES INSERT ---
        if ($chmod >= 2 || $_SESSION['jabatan'] == 'admin') {
            $avatar_final = ($avatar == '') ? "dist/upload/index.jpg" : $avatar;
            $sql_insert = "INSERT INTO $tabeldatabase (kode, sku, nama, hargabeli, hargajual, keterangan, kategori, satuan, sisa, stokmin, barcode, brand, lokasi, expired, warna, ukuran, avatar) VALUES ('$kode', '$sku', '$nama', '$hargabeli', '$hargajual', '$ket', '$kategori', '$satuan', '$stok', '$stokmin', '$barcode', '$brand', '$rak', '$exp', '$warna', '$ukuran', '$avatar_final')";

            if (mysqli_query($conn, $sql_insert)) {
                $sql_mutasi = "INSERT INTO mutasi VALUES('$usr','$now','$jam','$kode','$stok','$stok','menambah Produk','$kode','sistem','','berhasil')";
                mysqli_query($conn, $sql_mutasi);
                $alert_script = "swal('Berhasil!', 'Data telah disimpan!', 'success').then(function() { window.location = '$forwardpage'; });";
            } else {
                $error_msg = addslashes(mysqli_error($conn));
                $alert_script = "swal('Gagal!', 'Data gagal disimpan. Error: $error_msg', 'error');";
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
    <title>Add Barang | INV OME</title>
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
                <li><a href="<?php echo $halaman; ?>"><?php echo $dataapa; ?></a></li>
                <li class="active"><?php echo !empty($id) ? 'Edit' : 'Tambah'; ?> <?php echo $dataapa; ?></li>
            </ol>
            <?php if ($chmod >= 2 || $_SESSION['jabatan'] == 'admin') : ?>
                <?php
                $w = [];
                if (!empty($id)) {
                    $w_query = mysqli_query($conn, "SELECT * FROM barang WHERE no='$id'");
                    $w = mysqli_fetch_assoc($w_query);
                }
                ?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <h3 class="box-title">Tambah <?php echo $dataapa; ?></h3>
                            </div>
                            <form method="post" enctype="multipart/form-data">
                                <div class="box-body">
                                    <div class="nav-tabs-custom">
                                        <ul class="nav nav-tabs">
                                            <li class="active"><a href="#tab_1" data-toggle="tab">Informasi Dasar</a></li>
                                            <li><a href="#tab_4" data-toggle="tab">Informasi Lanjutan</a></li>
                                        </ul>
                                        <div class="tab-content">
                                            <!-- Tab Informasi Dasar -->
                                            <div class="tab-pane active" id="tab_1">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>SKU Barang</label>
                                                            <?php if (!empty($id)) : ?>
                                                                <input class="form-control" type="hidden" name="kode" value="<?php echo $w['kode']; ?>">
                                                                <input class="form-control" type="text" name="sku" value="<?php echo $w['sku']; ?>" required>
                                                            <?php else : ?>
                                                                <input class="form-control" type="hidden" name="kode" value="<?php echo autoNumber(); ?>">
                                                                <input class="form-control" type="text" name="sku" value="SKU<?php echo autoNumber(); ?>" required>
                                                            <?php endif; ?>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Nama Barang</label>
                                                            <input class="form-control" name="nama" maxlength="200" autocomplete="off" value="<?php echo isset($w['nama']) ? htmlspecialchars($w['nama']) : ''; ?>" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Kategori</label>
                                                            <div class="input-group">
                                                                <select class="form-control select2" name="kategori" required>
                                                                    <?php
                                                                    $sql_kategori = mysqli_query($conn, "select * from kategori");
                                                                    while ($row = mysqli_fetch_assoc($sql_kategori)) {
                                                                        $selected = (isset($w['kategori']) && $w['kategori'] == $row['nama']) ? "selected" : "";
                                                                        echo "<option value='" . $row['nama'] . "' " . $selected . ">" . $row['nama'] . "</option>";
                                                                    }
                                                                    ?>
                                                                </select>
                                                                <span class="input-group-btn"><a class="btn btn-default" href="add_kategori"><i class="fa fa-plus"></i></a></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Satuan</label>
                                                            <div class="input-group">
                                                                <select class="form-control select2" name="satuan" required>
                                                                    <?php
                                                                    $sql_satuan = mysqli_query($conn, "select * from satuan");
                                                                    while ($row = mysqli_fetch_assoc($sql_satuan)) {
                                                                        $selected = (isset($w['satuan']) && $w['satuan'] == $row['nama']) ? "selected" : "";
                                                                        echo "<option value='" . $row['nama'] . "' " . $selected . ">" . $row['nama'] . "</option>";
                                                                    }
                                                                    ?>
                                                                </select>
                                                                <span class="input-group-btn"><a class="btn btn-default" href="add_satuan"><i class="fa fa-plus"></i></a></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Merek</label>
                                                            <div class="input-group">
                                                                <select class="form-control select2" name="merek" required>
                                                                    <?php
                                                                    $sql_brand = mysqli_query($conn, "select * from brand");
                                                                    while ($row = mysqli_fetch_assoc($sql_brand)) {
                                                                        $selected = (isset($w['brand']) && $w['brand'] == $row['nama']) ? "selected" : "";
                                                                        echo "<option value='" . $row['nama'] . "' " . $selected . ">" . $row['nama'] . "</option>";
                                                                    }
                                                                    ?>
                                                                </select>
                                                                <span class="input-group-btn"><a class="btn btn-default" href="add_merek"><i class="fa fa-plus"></i></a></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <?php if (!empty($id)) : ?>
                                                            <div class="form-group">
                                                                <label>Stok Minimal</label>
                                                                <input class="form-control" name="stok_minimal" type="number" min="1" value="<?php echo $w['stokmin']; ?>" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Barcode</label>
                                                                <input class="form-control" name="barcode" value="<?php echo $w['barcode']; ?>" required>
                                                            </div>
                                                        <?php else : ?>
                                                            <div class="form-group">
                                                                <label>Stok Awal</label>
                                                                <input class="form-control" name="stok" type="number" min="0" required value="1" >
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Stok Minimal</label>
                                                                <input class="form-control" name="stok_minimal" type="number" min="1" value="1" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Barcode</label>
                                                                <input class="form-control" name="barcode" value="OME<?php echo autoNumber(); ?>" required>
                                                            </div>
                                                        <?php endif; ?>
                                                        <?php if ($mode >= 1) : ?>
                                                            <div class="form-group">
                                                                <label>Harga Beli</label>
                                                                <input class="form-control" name="harga_beli" required autocomplete="off" value="<?php echo isset($w['hargabeli']) ? $w['hargabeli'] : ''; ?>">
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Harga Jual</label>
                                                                <input class="form-control" name="harga_jual" required autocomplete="off" value="<?php echo isset($w['hargajual']) ? $w['hargajual'] : ''; ?>">
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Tab Informasi Lanjutan -->
                                            <div class="tab-pane" id="tab_4">
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <div class="form-group">
                                                            <label>Ukuran</label>
                                                            <select class="form-control" name="ukuran">
                                                                <?php if (!empty($id) && isset($w['ukuran'])) { echo ' <option value="' . $w['ukuran'] . '" selected>' . $w['ukuran'] . '</option>'; } ?>
                                                                <option value="">--Pilih--</option>
                                                                <option>XXL</option> <option>XL</option> <option>L</option> <option>M</option> <option>S</option> <option>XS</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Warna</label>
                                                            <input class="form-control" name="warna" value="<?php echo isset($w['warna']) ? htmlspecialchars($w['warna']) : ''; ?>">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Lokasi Rak</label>
                                                            <input class="form-control" name="lokasi" value="<?php echo isset($w['lokasi']) ? htmlspecialchars($w['lokasi']) : ''; ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="form-group">
                                                            <label>Tanggal Kedaluwarsa</label>
                                                            <input id="datepicker" class="form-control" data-language="en" name="expired" autocomplete="off" value="<?php echo isset($w['expired']) ? $w['expired'] : ''; ?>">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Keterangan</label>
                                                            <input class="form-control" name="keterangan" autocomplete="off" value="<?php echo isset($w['keterangan']) ? htmlspecialchars($w['keterangan']) : ''; ?>">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Gambar</label>
                                                            <input type="file" class="form-control" name="avatar">
                                                        </div>
                                                    </div>
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

    // Jalankan skrip SweetAlert jika ada
    <?php if (!empty($alert_script)) : ?>
        document.addEventListener("DOMContentLoaded", function() {
            <?php echo $alert_script; ?>
        });
    <?php endif; ?>
</script>
</body>
</html>
