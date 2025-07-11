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
$halaman = "stok_sesuaikan.php";
$dataapa = "Penyesuaian";
$tabeldatabase = "stok_sesuai";
$tabel = "stok_sesuai_daftar";
$chmod = $chmenu5;

function autoNumber(){
    include "configuration/config_connect.php";
    $query = "SELECT MAX(RIGHT(nota, 4)) as max_id FROM stok_sesuai ORDER BY nota";
    $result = mysqli_query($conn, $query);
    $data = mysqli_fetch_array($result);
    $id_max = $data['max_id'];
    $sort_num = (int) substr($id_max, 1, 4);
    $sort_num++;
    $new_code = "ADJ" . sprintf("%02s", $sort_num);
    return $new_code;
}

$nota_aktif = isset($_GET['nota']) ? $_GET['nota'] : autoNumber();

// ===================================================================
// PROSES PENYESUAIAN ITEM
// ===================================================================
if(isset($_POST["sesuai"])){
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $nota = mysqli_real_escape_string($conn, $_POST["nota"]);
        $kode = mysqli_real_escape_string($conn, $_POST["kode"]);
        $nama = mysqli_real_escape_string($conn, $_POST["nama"]);
        $stok = (int)mysqli_real_escape_string($conn, $_POST["stok"]);
        $tersedia = (int)mysqli_real_escape_string($conn, $_POST["tersedia"]);
        $ket = mysqli_real_escape_string($conn, $_POST["ket"]);
        $kegiatan = "Penyesuaian STOK";
        $usr = $_SESSION['nama'];
        $tgl = date('Y-m-d');
        $jam = date('H:i:s');

        if($stok != $tersedia){
            $sql_cek = "SELECT * FROM $tabel WHERE nota='$nota' AND kode_brg='$kode'";
            $resulte = mysqli_query($conn, $sql_cek);

            if(mysqli_num_rows($resulte) > 0){
                $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Barang tersebut sudah disesuaikan, Silahkan batalkan dulu yang sebelumnya!'];
            } else {
                $selisih = $tersedia - $stok;
                $q_daftar = "INSERT INTO $tabel VALUES('$nota', '$kode', '$nama', '$stok', '$tersedia', '$selisih', '$ket', '')";
                if(mysqli_query($conn, $q_daftar)){
                    mysqli_query($conn, "UPDATE barang SET sisa='$tersedia' WHERE kode='$kode'");
                    mysqli_query($conn, "INSERT INTO mutasi VALUES('$usr','$tgl','$jam','$kode','$tersedia','$selisih','$kegiatan','$nota','','pending')");
                    $_SESSION['flash_message'] = ['type' => 'success', 'message' => 'Berhasil, Stok Telah Disesuaikan!'];
                } else {
                    $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Gagal Query, Periksa kembali atau hubungi admin!'];
                }
            }
        } else {
            $_SESSION['flash_message'] = ['type' => 'warning', 'message' => 'Jumlah Stok Tercatat dan Stok Aktual sudah sama!'];
        }
        header("Location: $halaman?nota=$nota_aktif");
        exit();
    }
}

// ===================================================================
// PROSES SIMPAN TRANSAKSI PENYESUAIAN
// ===================================================================
if(isset($_POST["simpan"])){
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $nota = mysqli_real_escape_string($conn, $_POST["nota"]);
        $ket = mysqli_real_escape_string($conn, $_POST["keterangan"]);
        $date = date('Y-m-d');
        $usr = $_SESSION['nama'];

        $q_simpan = "INSERT INTO $tabeldatabase VALUES('$nota', '$date', '$usr', '$ket', '')";
        if(mysqli_query($conn, $q_simpan)){
            mysqli_query($conn, "UPDATE mutasi SET status='berhasil' WHERE kegiatan LIKE 'Penyesuaian STOK' AND keterangan='$nota'");
            $_SESSION['flash_message'] = ['type' => 'success', 'message' => 'Data Penyesuaian Berhasil Disimpan!'];
        } else {
            $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Gagal Menyimpan, Periksa kembali atau hubungi admin!'];
        }
        header("Location: $halaman");
        exit();
    }
}
?>
<head>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
</head>
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
                    <ol class="breadcrumb">
                        <li><a href="<?php echo $_SESSION['baseurl']; ?>">Dashboard </a></li>
                        <li class="active"><?php echo $dataapa; ?></li>
                    </ol>

                    <?php if ($chmod >= 2 || $_SESSION['jabatan'] == 'admin'): ?>
                    
                    <div class="box box-default">
                        <div class="box-header with-border">
                            <h3 class="box-title">Form <?php echo $dataapa;?></h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Pilih Barang</label>
                                        <select class="form-control select2" style="width: 100%;" id="barang">
                                            <option></option>
                                            <?php
                                            $sql_brg = mysqli_query($conn, "SELECT * FROM barang ORDER BY nama");
                                            while ($row = mysqli_fetch_assoc($sql_brg)) {
                                                echo "<option value='".$row['kode']."' data-nama='".$row['nama']."' data-sisa='".$row['sisa']."'>".$row['sku']." | ".$row['nama']."</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>No. Transaksi</label>
                                        <input type="text" class="form-control" value="<?php echo $nota_aktif; ?>" readonly>
                                    </div>
                                </div>
                            </div>
                            <form method="post" action="">
                                <input type="hidden" id="nota" name="nota" value="<?php echo $nota_aktif; ?>">
                                <input type="hidden" class="form-control" id="kode" name="kode" readonly>
                                
                                <div class="row">
                                    <div class="col-sm-4">
                                        <label>Nama Barang</label>
                                        <input type="text" class="form-control" id="nama" name="nama" readonly>
                                    </div>
                                    <div class="col-sm-2">
                                        <label>Stok Tercatat</label>
                                        <input type="text" class="form-control" id="stok" name="stok" readonly>
                                    </div>
                                    <div class="col-sm-2">
                                        <label>Stok Aktual</label>
                                        <input type="number" class="form-control" id="tersedia" name="tersedia">
                                    </div>
                                    <div class="col-sm-2">
                                        <label>Keterangan</label>
                                        <input type="text" class="form-control" id="ket" name="ket">
                                    </div>
                                    <div class="col-sm-2">
                                        <label>Aksi</label>
                                        <button type="submit" class="btn btn-block btn-info" name="sesuai">Sesuaikan</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="box box-success">
                        <div class="box-header with-border">
                            <h3 class="box-title">Daftar Barang Disesuaikan</h3>
                        </div>
                        <div class="box-body table-responsive">
                            <table class="table table-hover table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>SKU</th>
                                        <th>Nama Barang</th>
                                        <th>Stok Sebelumnya</th>
                                        <th>Stok Penyesuaian</th>
                                        <th>Selisih</th>
                                        <th>Opsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                $sql_daftar = "SELECT * FROM $tabel WHERE nota ='$nota_aktif' ORDER BY no";
                                $result_daftar = mysqli_query($conn, $sql_daftar);
                                $no_urut = 0;
                                if (mysqli_num_rows($result_daftar) > 0) {
                                    while ($fill = mysqli_fetch_assoc($result_daftar)) {
                                        $no_urut++;
                                        $kode_brg_fill = $fill['kode_brg'];
                                        $r_sku = mysqli_fetch_assoc(mysqli_query($conn, "SELECT sku FROM barang WHERE kode='$kode_brg_fill'"));
                                ?>
                                    <tr>
                                        <td><?php echo $no_urut; ?></td>
                                        <td><?php echo htmlspecialchars($r_sku['sku']); ?></td>
                                        <td><?php echo htmlspecialchars($fill['nama']); ?></td>
                                        <td><?php echo number_format($fill['sebelum']); ?></td>
                                        <td><?php echo number_format($fill['sesudah']); ?></td>
                                        <td><?php echo number_format($fill['selisih']); ?></td>
                                        <td>
                                            <a href="component/delete/delete_sesuai.php?no=<?php echo $fill['no']; ?>&nota=<?php echo $fill['nota']; ?>&kode=<?php echo $fill['kode_brg']; ?>&sebelum=<?php echo $fill['sebelum']; ?>" class="btn btn-danger btn-xs">BATAL</a>
                                        </td>
                                    </tr>
                                <?php 
                                    }
                                } else {
                                    echo "<tr><td colspan='7' class='text-center'>Belum ada penyesuaian stok untuk transaksi ini.</td></tr>";
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                        <?php if (mysqli_num_rows($result_daftar) > 0): ?>
                        <div class="box-footer">
                            <form method="post" id="form-simpan">
                                <input type="hidden" name="nota" value="<?php echo $nota_aktif; ?>">
                                <div class="form-group">
                                    <label>Catatan Keseluruhan</label>
                                    <textarea class="form-control" name="keterangan" rows="2"></textarea>
                                </div>
                                <button type="button" id="btn-simpan" class="btn btn-danger btn-flat pull-right">Simpan Penyesuaian</button>
                            </form>
                        </div>
                        <?php endif; ?>
                    </div>

                    <?php else: ?>
                    <div class="callout callout-danger">
                        <h4>Info</h4>
                        <b>Hanya user tertentu yang dapat mengakses halaman <?php echo $dataapa;?> ini.</b>
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

<script>
$(function () {
    $(".select2").select2({
        placeholder: "Cari atau pilih barang...",
        allowClear: true
    });

    $("#barang").on("change", function(){
        var selectedOption = $(this).find("option:selected");
        var nama = selectedOption.data("nama");
        var sisa = selectedOption.data("sisa");
        var kode = $(this).val();
        
        $("#nama").val(nama);
        $("#stok").val(sisa);
        $("#tersedia").val(sisa);
        $("#kode").val(kode);
    });

    $('#btn-simpan').on('click', function(e) {
        e.preventDefault();
        swal({
            title: "Anda Yakin?",
            text: "Setelah disimpan, data penyesuaian ini tidak dapat diubah lagi.",
            icon: "warning",
            buttons: ["Batal", "Ya, Simpan!"],
            dangerMode: true,
        })
        .then((willSave) => {
            if (willSave) {
                // Menambahkan input hidden 'simpan' sebelum submit
                $('<input>').attr({
                    type: 'hidden',
                    name: 'simpan',
                    value: 'true'
                }).appendTo('#form-simpan');
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
