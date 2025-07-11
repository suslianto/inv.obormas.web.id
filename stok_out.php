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
$halaman = "stok_out.php";
$dataapa = "Stok Keluar";
$tabeldatabase = "stok_keluar";
$tabel = "stok_keluar_daftar";
$chmod = $chmenu5;
$kegiatan = "Stok Keluar";

function autoNumber(){
    include "configuration/config_connect.php";
    $query = "SELECT MAX(no) as max_id FROM stok_keluar ORDER BY no";
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
if(isset($_POST["keluar"])){
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $nota = mysqli_real_escape_string($conn, $_POST["nota"]);
        $kode = mysqli_real_escape_string($conn, $_POST["kode"]);
        $nama = mysqli_real_escape_string($conn, $_POST["nama"]);
        $jumlah = (int)mysqli_real_escape_string($conn, $_POST["jumlah"]);
        $stok = (int)mysqli_real_escape_string($conn, $_POST["stok"]);
        $hbeli = (int)mysqli_real_escape_string($conn, $_POST["hargabeli"]);
        $hjual = (int)mysqli_real_escape_string($conn, $_POST["hargajual"]);
        
        if(!empty($kode) && $jumlah > 0 && $jumlah <= $stok) {
            $brg = mysqli_query($conn,"SELECT * FROM barang WHERE kode='$kode'");
            $ass = mysqli_fetch_assoc($brg);
            $oldstok = (int)$ass['sisa'];
            $oldout = (int)$ass['terjual'];
            $newstok = $oldstok - $jumlah;
            $newout = $oldout + $jumlah;

            $sqlx = "UPDATE barang SET sisa='$newstok', terjual='$newout' WHERE kode='$kode'";
            if(mysqli_query($conn, $sqlx)){
                $sql_cek = "SELECT * FROM stok_keluar_daftar WHERE nota='$nota' AND kode_barang='$kode'";
                $resulte = mysqli_query($conn, $sql_cek);

                if(mysqli_num_rows($resulte) > 0){
                    $q = mysqli_fetch_assoc($resulte);
                    $cart = (int)$q['jumlah'];
                    $newcart = $cart + $jumlah;
                    $total = $newcart * $hjual;
                    $modal = $newcart * $hbeli;
                    $sqlu = "UPDATE stok_keluar_daftar SET jumlah='$newcart', subbeli='$modal', subtotal='$total' WHERE nota='$nota' AND kode_barang='$kode'";
                    mysqli_query($conn, $sqlu);
                } else {
                    $total = $jumlah * $hjual;
                    $modal = $jumlah * $hbeli;
                    $sql2 = "INSERT INTO stok_keluar_daftar (nota, kode_barang, nama, jumlah, subbeli, subtotal) VALUES ('$nota', '$kode', '$nama', '$jumlah', '$modal', '$total')";
                    mysqli_query($conn, $sql2);
                }
            }
        } else {
             if($jumlah > $stok) {
                $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Jumlah keluar tidak boleh lebih besar dari stok tersedia!'];
             }
        }
        header("Location: $halaman?nota=$nota");
        exit();
    }
}

// ===================================================================
// PROSES SIMPAN TRANSAKSI DAN BUAT SURAT JALAN
// ===================================================================
if(isset($_POST["simpan"])){
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $nota = mysqli_real_escape_string($conn, $_POST["notae"]);
        $pelanggan_kode = mysqli_real_escape_string($conn, $_POST["pelanggan"]);
        $driver = mysqli_real_escape_string($conn, $_POST["driver"]);
        $nohp_driver = mysqli_real_escape_string($conn, $_POST["nohp"]);
        $nopol = mysqli_real_escape_string($conn, $_POST["nopol"]);
        $ket = mysqli_real_escape_string($conn, $_POST["ket"]);
        $modal = mysqli_real_escape_string($conn, $_POST["modal"]);
        $total = mysqli_real_escape_string($conn, $_POST["total"]);
        $tgl = date('Y-m-d');
        $usr = $_SESSION['nouser'];
        $cab = '01'; 

        $cek_item = mysqli_query($conn, "SELECT * FROM stok_keluar_daftar WHERE nota='$nota'");
        if(mysqli_num_rows($cek_item) > 0) {
            
            $pelanggan_nama = '';
            $pelanggan_alamat = '';
            $pelanggan_notelp = '';
            $sql_get_pelanggan = "SELECT nama, alamat, notelp FROM pelanggan WHERE kode = '$pelanggan_kode' LIMIT 1";
            $res_pelanggan = mysqli_query($conn, $sql_get_pelanggan);
            if($row_pelanggan = mysqli_fetch_assoc($res_pelanggan)){
                $pelanggan_nama = $row_pelanggan['nama'];
                $pelanggan_alamat = $row_pelanggan['alamat'];
                $pelanggan_notelp = $row_pelanggan['notelp'];
            }

            $sql_stok_keluar = "INSERT INTO stok_keluar VALUES ('$nota', '$cab', '$tgl', '$pelanggan_nama', '$usr', '$ket', '$modal', '$total', '')";
            if(mysqli_query($conn, $sql_stok_keluar)){
                
                $nosurat = "SJ/" . date("Ymd") . "/" . $nota;
                
                $sql_surat = "INSERT INTO surat VALUES ('$nota', '$nosurat', '$tgl', '$pelanggan_kode', '$pelanggan_nama', '$pelanggan_notelp', '$pelanggan_alamat', '$driver', '$nohp_driver', '$nopol', '$usr', '')";
                
                if(mysqli_query($conn, $sql_surat)){
                    $mut = "UPDATE mutasi SET status='berhasil', tujuan='$pelanggan_nama' WHERE keterangan='$nota' AND kegiatan='$kegiatan'";
                    mysqli_query($conn, $mut);

                    $_SESSION['flash_message'] = ['type' => 'success', 'message' => 'Surat Jalan berhasil dibuat!'];
                    header("Location: surat_kelola.php");
                    exit();
                }
            }
        }
        
        $error_msg = mysqli_error($conn);
        $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Gagal membuat Surat Jalan! Error: ' . addslashes($error_msg)];
        header("Location: $halaman");
        exit();
    }
}

// Menangkap barcode
$barcode_val = ''; $nama_val = ''; $kode_val = ''; $stok_val = ''; $jumlah_val = '1'; $hbeli_val = ''; $hjual_val = '';
if(isset($_GET['barcode'])) {
    $barcode_val = mysqli_real_escape_string($conn, $_GET["barcode"]);
    if(!empty($barcode_val)){
        $sql1 = "SELECT * FROM barang WHERE barcode='$barcode_val'";
        $query = mysqli_query($conn, $sql1);
        if($data = mysqli_fetch_assoc($query)){
            $nama_val = $data['nama'];
            $kode_val = $data['kode'];
            $stok_val = $data['sisa'];
            $hbeli_val = $data['hargabeli'];
            $hjual_val = $data['hargajual'];
        }
    }
}

$query_total = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(subbeli) as beli, SUM(subtotal) as total FROM stok_keluar_daftar WHERE nota='$nota_aktif'"));

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
                        <li><a href="<?php echo $halaman;?>"><?php echo $dataapa ?></a></li>
                        <li class="active">Data <?php echo $dataapa ?></li>
                    </ol>

                    <?php if ($chmod >= 2 || $_SESSION['jabatan'] == 'admin'): ?>
                    <div class="row">
                        <div class="col-lg-5 col-xs-12">
                            <div class="box">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Form Stok Keluar</h3>
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
                                                    $sql_brg = mysqli_query($conn, "SELECT * FROM barang ORDER BY nama");
                                                    while ($row = mysqli_fetch_assoc($sql_brg)) {
                                                        $selected = ($kode_val == $row['kode']) ? "selected" : "";
                                                        echo "<option value='" . $row['kode'] . "' nama='" . htmlspecialchars($row['nama']) . "' hargabeli='" . $row['hargabeli'] . "' hargajual='" . $row['hargajual'] . "' stok='" . $row['sisa'] . "' $selected>" . htmlspecialchars($row['nama']) . "</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <form method="post" action="">
                                        <input type="hidden" id="nota" name="nota" value="<?php echo $nota_aktif; ?>">
                                        <input type="hidden" id="kode" name="kode" value="<?php echo htmlspecialchars($kode_val); ?>">
                                        <input type="hidden" id="hbeli" name="hargabeli" value="<?php echo htmlspecialchars($hbeli_val); ?>">
                                        <input type="hidden" id="hjual" name="hargajual" value="<?php echo htmlspecialchars($hjual_val); ?>">
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
                                                <label for="stok" class="col-sm-2 control-label">Stok:</label>
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
                                                    <button type="submit" name="keluar" class="btn bg-yellow btn-flat btn-block">Tambahkan</button>
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
                                        $sql_daftar = "SELECT * FROM stok_keluar_daftar WHERE nota ='$nota_aktif' ORDER BY no";
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
                                                    // PERBAIKAN: Menambahkan parameter forwardpage ke URL Hapus
                                                    $delete_url = "component/delete/delete_stok.php?keg=out&barang=".$fill['kode_barang']."&nota=".$fill['nota']."&jumlah=".$fill['jumlah']."&no=".$fill['no']."&forwardpage=".$halaman;
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
                            
                            <?php if (mysqli_num_rows(mysqli_query($conn, "SELECT * FROM stok_keluar_daftar WHERE nota ='$nota_aktif'")) > 0): ?>
                            <div class="box box-danger">
                                <div class="box-header with-border"></div>
                                <div class="box-body">
                                    <form method="post" action="" id="form-simpan">
                                        <input type="hidden" name="notae" value="<?php echo $nota_aktif; ?>">
                                        <input type="hidden" name="modal" value="<?php echo $query_total['beli']; ?>">
                                        <input type="hidden" name="total" value="<?php echo $query_total['total']; ?>">
                                        <input type="hidden" name="simpan" value="true">
                                        
                                        <div class="row">
                                            <div class="form-group col-md-12 col-xs-12">
                                                <label for="pelanggan" class="col-sm-2 control-label">Lokasi:</label>
                                                <div class="col-sm-10">
                                                    <select class="form-control select2" style="width: 100%;" name="pelanggan" required>
                                                        <option value="" disabled selected>Pilih Lokasi</option>
                                                        <?php
                                                        $sql_pelanggan = mysqli_query($conn, "SELECT * FROM pelanggan ORDER BY nama");
                                                        while ($row_pelanggan = mysqli_fetch_assoc($sql_pelanggan)) {
                                                            echo "<option value='" . htmlspecialchars($row_pelanggan['kode']) . "'>" . htmlspecialchars($row_pelanggan['kode']) . " | " . htmlspecialchars($row_pelanggan['nama']) . "</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        
                                        <div class="row">
                                            <div class="form-group col-md-12 col-xs-12">
                                                <label for="driver" class="col-sm-2 control-label">Dibawa:</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control" name="driver" required>
                                                </div>
                                            </div>
                                        </div>
                                        <br>

                                        <div class="row">
                                            <div class="form-group col-md-12 col-xs-12">
                                                <label for="nohp" class="col-sm-2 control-label">No. HP:</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control" name="nohp">
                                                </div>
                                            </div>
                                        </div>
                                        <br>

                                        <!-- <div class="row">
                                            <div class="form-group col-md-12 col-xs-12">
                                                <label for="nopol" class="col-sm-2 control-label">No. Polisi:</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control" name="nopol" placeholder="Opsional">
                                                </div>
                                            </div>
                                        </div> -->

                                        <div class="row">
                                            <div class="form-group col-md-12 col-xs-12">
                                                <label for="ket" class="col-sm-2 control-label">Keterangan:</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control" name="ket" required>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="form-group col-md-12 col-xs-12">
                                                <button type="button" id="btn-simpan" class="btn btn-flat bg-blue btn-block">SIMPAN & BUAT SURAT JALAN</button>
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

<!-- Script -->
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
        var selectedOption = $("#produk option:selected");
        var nama = selectedOption.attr("nama");
        var kode = selectedOption.val();
        var stok = selectedOption.attr("stok");
        var hbeli = selectedOption.attr("hargabeli");
        var hjual = selectedOption.attr("hargajual");
        
        $("#nama").val(nama);
        $("#kode").val(kode);
        $("#stok").val(stok);
        $("#hbeli").val(hbeli);
        $("#hjual").val(hjual);
        $("#jumlah").val(1).focus();
    });

    $('#btn-simpan').on('click', function(e) {
        e.preventDefault();
        swal({
            title: "Anda Yakin?",
            text: "Pastikan semua data sudah benar sebelum menyimpan.",
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
