<!DOCTYPE html>
<html>
<?php
include "configuration/config_etc.php";
include "configuration/config_include.php";
etc();encryption();session();connect();head();body();timing();
pagination();

if (!login_check()) {
    echo '<meta http-equiv="refresh" content="0; url=logout" />';
    exit(0);
}

// ===================================================================
// PENGATURAN HALAMAN
// ===================================================================
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
include "configuration/config_chmod.php";
$halaman = "stok_in.php";
$dataapa = "Stok Masuk";
$tabeldatabase = "stok_masuk";
$tabel = "stok_masuk_daftar";
$chmod = $chmenu5;
$forwardpage = $halaman;
$search = isset($_POST['search']) ? $_POST['search'] : '';
$kegiatan = "Stok Masuk";

function autoNumber(){
    include "configuration/config_connect.php";
    $query = "SELECT MAX(no) as max_id FROM stok_masuk ORDER BY no";
    $result = mysqli_query($conn, $query);
    $data = mysqli_fetch_array($result);
    $id_max = $data['max_id'];
    $sort_num = (int) $id_max;
    $sort_num++;
    $new_code = sprintf("%04s", $sort_num);
    return $new_code;
}

$nota_aktif = isset($_GET['nota']) ? $_GET['nota'] : autoNumber();

// ===================================================================
// PROSES TAMBAH ITEM (TANPA ALERT)
// ===================================================================
if(isset($_POST["masuk"])){
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $nota = mysqli_real_escape_string($conn, $_POST["nota"]);
        $kode = mysqli_real_escape_string($conn, $_POST["kode"]);
        $jumlah = (int)mysqli_real_escape_string($conn, $_POST["jumlah"]);
        
        if(!empty($kode) && $jumlah > 0) {
            $brg = mysqli_query($conn,"SELECT * FROM barang WHERE kode='$kode'");
            $ass = mysqli_fetch_assoc($brg);
            $nama = $ass['nama'];
            $oldstok = (int)$ass['sisa'];
            $oldin = (int)$ass['terbeli'];
            $newstok = $oldstok + $jumlah;
            $newin = $oldin + $jumlah;

            $sqlx = "UPDATE barang SET sisa='$newstok', terbeli='$newin' WHERE kode='$kode'";
            if(mysqli_query($conn, $sqlx)){
                $sql_cek = "SELECT * FROM stok_masuk_daftar WHERE nota='$nota' AND kode_barang='$kode'";
                $resulte = mysqli_query($conn, $sql_cek);

                if(mysqli_num_rows($resulte) > 0){
                    $q = mysqli_fetch_assoc($resulte);
                    $cart = (int)$q['jumlah'];
                    $newcart = $cart + $jumlah;
                    $sqlu = "UPDATE stok_masuk_daftar SET jumlah='$newcart' WHERE nota='$nota' AND kode_barang='$kode'";
                    mysqli_query($conn, $sqlu);
                } else {
                    $sql2 = "INSERT INTO stok_masuk_daftar (nota, kode_barang, nama, jumlah, no) VALUES ('$nota', '$kode', '$nama', '$jumlah', NULL)";
                    mysqli_query($conn, $sql2);
                }

                // START: LOGIKA MUTASI DIPERBARUI
                $usr_nama = $_SESSION['nama']; 
                $tgl_mutasi = date('Y-m-d');
                $sql_cek_mutasi = "SELECT * FROM mutasi WHERE keterangan='$nota' AND kodebarang='$kode' AND kegiatan='$kegiatan'";
                $res_mutasi = mysqli_query($conn, $sql_cek_mutasi);

                if (mysqli_num_rows($res_mutasi) > 0) {
                    $q_mutasi = mysqli_fetch_assoc($res_mutasi);
                    $jumlah_mutasi_lama = (int)$q_mutasi['jumlah'];
                    $jumlah_mutasi_baru = $jumlah_mutasi_lama + $jumlah;
                    $sqlu_mutasi = "UPDATE mutasi SET jumlah='$jumlah_mutasi_baru', sisa='$newstok' WHERE keterangan='$nota' AND kodebarang='$kode'";
                    mysqli_query($conn, $sqlu_mutasi);
                } else {
                    $sql_ins_mutasi = "INSERT INTO mutasi (kodebarang, namauser, tgl, kegiatan, jumlah, sisa, keterangan, status) VALUES ('$kode', '$usr_nama', '$tgl_mutasi', '$kegiatan', '$jumlah', '$newstok', '$nota', 'pending')";
                    mysqli_query($conn, $sql_ins_mutasi);
                }
                // END: LOGIKA MUTASI DIPERBARUI
            }
        }
        header("Location: $halaman?nota=$nota");
        exit();
    }
}

// ===================================================================
// PROSES SIMPAN TRANSAKSI (DENGAN SWEETALERT)
// ===================================================================
if(isset($_POST["simpan"])){
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $nota = mysqli_real_escape_string($conn, $_POST["notae"]);
        $sup = mysqli_real_escape_string($conn, $_POST["supplier"]);
        $tgl = date('Y-m-d');
        $usr = $_SESSION['nouser'];
        $cab = $_SESSION['cab'];

        $cek_item = mysqli_query($conn, "SELECT * FROM stok_masuk_daftar WHERE nota='$nota'");
        if(mysqli_num_rows($cek_item) > 0) {
            $sql2 = "INSERT INTO stok_masuk VALUES ('$nota', '$cab', '$tgl', '$sup', '$usr', '')";
            if(mysqli_query($conn, $sql2)){
                $mut = "UPDATE mutasi SET status='berhasil', tujuan='$sup' WHERE keterangan='$nota' AND kegiatan='Stok Masuk'";
                mysqli_query($conn, $mut);
                $_SESSION['flash_message'] = ['type' => 'success', 'message' => 'Transaksi stok masuk berhasil disimpan!'];
            } else {
                $error_msg = mysqli_error($conn);
                $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Gagal menyimpan! Error: ' . addslashes($error_msg)];
            }
        } else {
            $_SESSION['flash_message'] = ['type' => 'warning', 'message' => 'Tidak ada item di dalam daftar!'];
        }
        
        header("Location: stok_masuk.php");
        exit();
    }
}

// Menangkap barcode
$barcode_val = ''; $nama_val = ''; $kode_val = ''; $stok_val = ''; $jumlah_val = '1';
if(isset($_GET['barcode'])) {
    $barcode_val = mysqli_real_escape_string($conn, $_GET["barcode"]);
    if(!empty($barcode_val)){
        $sql1 = "SELECT * FROM barang WHERE barcode='$barcode_val'";
        $query = mysqli_query($conn, $sql1);
        if($data = mysqli_fetch_assoc($query)){
            $nama_val = $data['nama'];
            $kode_val = $data['kode'];
            $stok_val = $data['sisa'];
        }
    }
}
?>
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
                    <ol class="breadcrumb ">
                        <li><a href="<?php echo $_SESSION['baseurl']; ?>">Dashboard </a></li>
                        <li><a href="stok_masuk.php"><?php echo $dataapa ?></a></li>
                        <li class="active">Tambah <?php echo $dataapa ?></li>
                    </ol>

                    <?php if ($chmod >= 2 || $_SESSION['jabatan'] == 'admin'): ?>
                    <div class="row">
                        <div class="col-lg-5 col-xs-12">
                            <div class="box">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Form Stok Masuk</h3>
                                </div>
                                <div class="box-body">
                                    <form method="get" action="">
                                        <div class="row">
                                            <div class="form-group col-md-12 col-xs-12">
                                                <label for="barcode" class="col-sm-2 control-label">Barcode:</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" id="barcode" name="barcode" autofocus>
                                                    <input type="hidden" name="nota" value="<?php echo $nota_aktif; ?>">
                                                </div>
                                                <div class="col-sm-2"><b>atau</b></div>
                                            </div>
                                        </div>
                                    </form>
                                    <div class="row">
                                        <div class="form-group col-md-12 col-xs-12">
                                            <label for="produk" class="col-sm-2 control-label">Pilih Barang:</label>
                                            <div class="col-sm-10">
                                                <select class="form-control select2" style="width: 100%;" id="produk">
                                                    <option selected="selected">Pilih Barang</option>
                                                    <?php
                                                    $sql_brg = mysqli_query($conn, "SELECT kode, nama, sisa, barcode FROM barang ORDER BY nama");
                                                    while ($row = mysqli_fetch_assoc($sql_brg)) {
                                                        $selected = ($kode_val == $row['kode']) ? "selected" : "";
                                                        echo "<option value='" . $row['kode'] . "' nama='" . htmlspecialchars($row['nama']) . "' stok='" . $row['sisa'] . "' $selected>" . htmlspecialchars($row['nama']) . "</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <form method="post" action="">
                                        <input type="hidden" id="nota" name="nota" value="<?php echo $nota_aktif; ?>">
                                        <input type="hidden" id="kode" name="kode" value="<?php echo htmlspecialchars($kode_val); ?>">
                                        <div class="row">
                                            <div class="form-group col-md-12 col-xs-12">
                                                <label for="nama" class="col-sm-2 control-label">Nama Produk:</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control" readonly id="nama" name="nama" value="<?php echo htmlspecialchars($nama_val); ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-12 col-xs-12">
                                                <label for="stok" class="col-sm-2 control-label">Stok Tersedia:</label>
                                                <div class="col-sm-5">
                                                    <input type="text" class="form-control" id="stok" name="stok" value="<?php echo htmlspecialchars($stok_val); ?>" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-12 col-xs-12">
                                                <label for="jumlah" class="col-sm-2 control-label">Jumlah:</label>
                                                <div class="col-sm-5">
                                                    <input type="number" class="form-control" id="jumlah" name="jumlah" value="<?php echo $jumlah_val; ?>" min="1">
                                                </div>
                                                <div class="col-sm-5">
                                                    <button type="submit" name="masuk" class="btn bg-yellow btn-flat btn-block">Tambahkan</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-7 col-xs-12">
                            <div class="box">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Daftar Barang</h3>
                                </div>
                                <div class="box-body table-responsive">
                                    <table class="data table table-hover table-bordered">
                                        <thead>
                                            <tr>
                                                <th style="width:10px">No</th>
                                                <th>Nama Barang</th>
                                                <th style="width:10%">Qty</th>
                                                <?php if ($chmod >= 3 || $_SESSION['jabatan'] == 'admin') { ?>
                                                    <th style="width:10px">Opsi</th>
                                                <?php } ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $sql_daftar = "SELECT * FROM stok_masuk_daftar WHERE nota ='$nota_aktif' ORDER BY no";
                                        $result_daftar = mysqli_query($conn, $sql_daftar);
                                        $no_urut = 0;
                                        while ($fill = mysqli_fetch_array($result_daftar)) {
                                            $no_urut++;
                                        ?>
                                            <tr>
                                                <td><?php echo $no_urut; ?></td>
                                                <td><?php echo htmlspecialchars($fill['nama']); ?></td>
                                                <td><?php echo htmlspecialchars($fill['jumlah']); ?></td>
                                                <td>
                                                <?php if ($chmod >= 4 || $_SESSION['jabatan'] == 'admin') { 
                                                    $delete_url = "component/delete/delete_stok.php?keg=in&barang=".$fill['kode_barang']."&nota=".$fill['nota']."&jumlah=".$fill['jumlah']."&no=".$fill['no']."&forward=".$tabel."&forwardpage=".$forwardpage;
                                                ?>
                                                    <a href="<?php echo $delete_url; ?>" class="btn btn-danger btn-xs"><i class='fa fa-trash'></i></a>
                                                <?php } ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            <?php if (mysqli_num_rows(mysqli_query($conn, "SELECT * FROM stok_masuk_daftar WHERE nota ='$nota_aktif'")) > 0): ?>
                            <div class="box box-danger">
                                <div class="box-header with-border"></div>
                                <div class="box-body">
                                    <form method="post" action="" id="form-simpan">
                                        <input type="hidden" name="notae" value="<?php echo $nota_aktif; ?>">
                                        <input type="hidden" name="simpan" value="true">
                                        <div class="row">
                                            <div class="form-group col-md-12 col-xs-12">
                                                <label for="supplier" class="col-sm-2 control-label">Supplier:</label>
                                                <div class="col-sm-10">
                                                    <select class="form-control select2" style="width: 100%;" name="supplier" required>
                                                        <?php
                                                        $sql_sup = mysqli_query($conn, "SELECT * FROM supplier");
                                                        while ($row = mysqli_fetch_assoc($sql_sup)) {
                                                            echo "<option value='" . htmlspecialchars($row['nama']) . "'>" . htmlspecialchars($row['nama']) . "</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="form-group col-md-12 col-xs-12">
                                                <button type="button" id="btn-simpan" class="btn btn-flat bg-blue btn-block">Simpan</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php else: ?>
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

<script src="dist/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="dist/bootstrap/js/bootstrap.min.js"></script>
<script src="dist/plugins/select2/select2.full.min.js"></script>
<script src="dist/plugins/slimScroll/jquery.slimscroll.min.js"></script>
<script src="dist/plugins/fastclick/fastclick.js"></script>
<script src="dist/js/app.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script>
$(function () {
    $(".select2").select2();

    $("#produk").on("change", function(){
        var nama = $("#produk option:selected").attr("nama");
        var kode = $("#produk option:selected").val();
        var stok = $("#produk option:selected").attr("stok");
        
        $("#nama").val(nama);
        $("#stok").val(stok);
        $("#kode").val(kode);
        $("#jumlah").val(1).focus();
    });

    $('#btn-simpan').on('click', function(e) {
        e.preventDefault();
        swal({
            title: "Anda Yakin?",
            text: "Apakah Anda yakin ingin menyimpan transaksi ini?",
            icon: "warning",
            buttons: ["Batal", "Ya, Simpan!"],
            dangerMode: true,
        })
        .then((willSave) => {
            if (willSave) {
                $('#form-simpan').submit();
            }
        });
    });
});

document.addEventListener("DOMContentLoaded", function() {
    <?php
    if (isset($_SESSION['flash_message'])) {
        $flash = $_SESSION['flash_message'];
        $type = $flash['type'];
        $message = addslashes($flash['message']);
        $title = ($type == 'success') ? 'Berhasil!' : (($type == 'warning') ? 'Perhatian!' : 'Gagal!');
        
        echo "swal({ title: '$title', text: '$message', icon: '$type' });";
        
        unset($_SESSION['flash_message']);
    }
    ?>
});
</script>
</body>
</html>