<?php
// ===================================================================
// INISIALISASI & KONFIGURASI
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

// Cek status login
if (!login_check()) {
    echo '<meta http-equiv="refresh" content="0; url=logout" />';
    exit(0);
}

// ===================================================================
// PENGATURAN HALAMAN & VARIABEL
// ===================================================================
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
include "configuration/config_chmod.php";

$halaman       = "stok_sesuaikan.php";
$dataapa       = "Penyesuaian Stok";
$tabeldatabase = "stok_sesuai";
$tabel_daftar  = "stok_sesuai_daftar";
$chmod         = $chmenu5;

/**
 * FUNGSI PENOMORAN OTOMATIS YANG LEBIH BAIK
 */
function autoNumber() {
    global $conn;
    $query = "SELECT MAX(nota) as max_id FROM stok_sesuai";
    $result = mysqli_query($conn, $query);
    $data = mysqli_fetch_array($result);
    $id_max = $data['max_id'];
    
    $sort_num = 0;
    if ($id_max) {
        $sort_num = (int) substr($id_max, 3);
    }
    
    $sort_num++;
    return "ADJ" . sprintf("%04d", $sort_num);
}

$nota_aktif = isset($_GET['nota']) ? mysqli_real_escape_string($conn, $_GET['nota']) : autoNumber();

// ===================================================================
// PROSES TAMBAH ITEM KE DAFTAR PENYESUAIAN
// ===================================================================
if (isset($_POST["sesuai"])) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nota     = mysqli_real_escape_string($conn, $_POST["nota"]);
        $kode     = mysqli_real_escape_string($conn, $_POST["kode"]);
        $tersedia = (int)mysqli_real_escape_string($conn, $_POST["tersedia"]);
        $ket      = mysqli_real_escape_string($conn, $_POST["ket"]);
        $usr      = $_SESSION['nama'];
        $tgl      = date('Y-m-d');

        $brg_query = mysqli_query($conn, "SELECT nama, sisa FROM barang WHERE kode='$kode'");
        $brg = mysqli_fetch_assoc($brg_query);
        $nama_brg = $brg['nama'];
        $stok_db = (int)$brg['sisa'];

        // Pengecekan sisi server tetap ada sebagai fallback
        if ($stok_db != $tersedia) {
            $sql_cek = "SELECT * FROM $tabel_daftar WHERE nota='$nota' AND kode_brg='$kode'";
            $resulte = mysqli_query($conn, $sql_cek);

            if (mysqli_num_rows($resulte) > 0) {
                $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Barang ini sudah ada di daftar penyesuaian. Batalkan dulu jika ingin mengubah.'];
            } else {
                $selisih = $tersedia - $stok_db;
                $q_daftar = "INSERT INTO $tabel_daftar (nota, kode_brg, nama, sebelum, sesudah, selisih, catatan) VALUES ('$nota', '$kode', '$nama_brg', '$stok_db', '$tersedia', '$selisih', '$ket')";
                
                if (mysqli_query($conn, $q_daftar)) {
                    mysqli_query($conn, "UPDATE barang SET sisa='$tersedia' WHERE kode='$kode'");
                    $q_mutasi = "INSERT INTO mutasi (namauser, tgl, kodebarang, sisa, jumlah, kegiatan, keterangan, status) VALUES ('$usr', '$tgl', '$kode', '$tersedia', '$selisih', 'Penyesuaian Stok', '$nota', 'pending')";
                    mysqli_query($conn, $q_mutasi);
                } else {
                    $error_sql = mysqli_error($conn);
                    $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Gagal! Error DB: ' . $error_sql];
                }
            }
        }
        header("Location: $halaman?nota=" . $nota_aktif);
        exit();
    }
}

// ===================================================================
// PROSES SIMPAN TRANSAKSI FINAL
// ===================================================================
if (isset($_POST["simpan"])) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nota = mysqli_real_escape_string($conn, $_POST["nota"]);
        $ket  = mysqli_real_escape_string($conn, $_POST["keterangan"]);
        $date = date('Y-m-d');
        $usr  = $_SESSION['nama'];

        $q_simpan = "INSERT INTO $tabeldatabase (nota, tgl, oleh, keterangan) VALUES ('$nota', '$date', '$usr', '$ket')";
        
        if (mysqli_query($conn, $q_simpan)) {
            mysqli_query($conn, "UPDATE mutasi SET status='berhasil' WHERE kegiatan='Penyesuaian Stok' AND keterangan='$nota'");
            $_SESSION['flash_message'] = ['type' => 'success', 'message' => 'Data penyesuaian berhasil disimpan permanen!'];
            header("Location: $halaman");
        } else {
            $error_sql = mysqli_error($conn);
            $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Gagal Simpan Final! Error DB: ' . $error_sql];
            header("Location: $halaman?nota=" . $nota_aktif);
        }
        exit();
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
                    <ol class="breadcrumb">
                        <li><a href="<?php echo htmlspecialchars($_SESSION['baseurl']); ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                        <li class="active"><?php echo htmlspecialchars($dataapa); ?></li>
                    </ol>

                    <?php if ($chmod >= 2 || $_SESSION['jabatan'] == 'admin'): ?>

                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Form Input Penyesuaian</h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Pilih Barang (Cari berdasarkan SKU atau Nama)</label>
                                        <select class="form-control select2" style="width: 100%;" id="barang">
                                            <option></option>
                                            <?php
                                            $sql_brg = mysqli_query($conn, "SELECT kode, nama, sisa, sku FROM barang ORDER BY nama");
                                            while ($row = mysqli_fetch_assoc($sql_brg)) {
                                                echo "<option value='".htmlspecialchars($row['kode'])."' data-nama='".htmlspecialchars($row['nama'])."' data-sisa='".htmlspecialchars($row['sisa'])."'>".htmlspecialchars($row['sku'])." | ".htmlspecialchars($row['nama'])."</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>No. Transaksi</label>
                                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($nota_aktif); ?>" readonly>
                                    </div>
                                </div>
                            </div>
                            
                            <form method="post" action="" id="form-tambah-item">
                                <input type="hidden" name="nota" value="<?php echo htmlspecialchars($nota_aktif); ?>">
                                <input type="hidden" class="form-control" id="kode" name="kode" readonly>

                                <div class="row">
                                    <div class="col-md-4 col-sm-12">
                                        <div class="form-group">
                                            <label>Nama Barang</label>
                                            <input type="text" class="form-control" id="nama" name="nama" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-6">
                                        <div class="form-group">
                                            <label>Stok Tercatat</label>
                                            <input type="number" class="form-control" id="stok" name="stok" readonly style="font-weight:bold; background:#f5f5f5;">
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-6">
                                        <div class="form-group">
                                            <label>Stok Aktual (Fisik)</label>
                                            <input type="number" class="form-control" id="tersedia" name="tersedia" required>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-6">
                                        <div class="form-group">
                                            <label>Catatan Item</label>
                                            <input type="text" class="form-control" id="ket" name="ket" placeholder="Cth: Rusak, Hilang">
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-6">
                                        <div class="form-group">
                                            <label>&nbsp;</label>
                                            <button type="submit" class="btn bg-yellow btn-flat btn-block" name="sesuai">Tambahkan ke Daftar</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="box box-success">
                        <div class="box-header with-border">
                            <h3 class="box-title">Daftar Barang yang Disesuaikan</h3>
                        </div>
                        <div class="box-body table-responsive">
                            <table class="table table-hover table-bordered">
                                <thead>
                                    <tr>
                                        <th style="width:5%;">No</th>
                                        <th style="width:15%;">SKU</th>
                                        <th>Nama Barang</th>
                                        <th style="width:10%;">Stok Awal</th>
                                        <th style="width:10%;">Stok Akhir</th>
                                        <th style="width:10%;">Selisih</th>
                                        <th style="width:15%;">Catatan</th>
                                        <th style="width:10%;">Opsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql_daftar = "SELECT * FROM $tabel_daftar WHERE nota ='$nota_aktif' ORDER BY no";
                                    $result_daftar = mysqli_query($conn, $sql_daftar);
                                    $no_urut = 0;
                                    if (mysqli_num_rows($result_daftar) > 0) {
                                        while ($fill = mysqli_fetch_assoc($result_daftar)) {
                                            $no_urut++;
                                            $kode_brg_fill = htmlspecialchars($fill['kode_brg']);
                                            $r_sku = mysqli_fetch_assoc(mysqli_query($conn, "SELECT sku FROM barang WHERE kode='$kode_brg_fill'"));
                                            $selisih = (int)$fill['selisih'];
                                            $selisih_class = $selisih > 0 ? 'text-green' : 'text-red';
                                            $selisih_prefix = $selisih > 0 ? '+' : '';
                                    ?>
                                        <tr>
                                            <td><?php echo $no_urut; ?></td>
                                            <td><?php echo htmlspecialchars($r_sku['sku'] ?? 'N/A'); ?></td>
                                            <td><?php echo htmlspecialchars($fill['nama']); ?></td>
                                            <td class="text-center"><?php echo number_format($fill['sebelum']); ?></td>
                                            <td class="text-center"><?php echo number_format($fill['sesudah']); ?></td>
                                            <td class="text-center" style="font-weight:bold;"><span class="<?php echo $selisih_class; ?>"><?php echo $selisih_prefix . number_format($selisih); ?></span></td>
                                            <td><?php echo htmlspecialchars($fill['catatan']); ?></td>
                                            <td class="text-center">
                                                <a href="component/delete/delete_sesuai.php?no=<?php echo htmlspecialchars($fill['no']); ?>&nota=<?php echo htmlspecialchars($fill['nota']); ?>&kode=<?php echo htmlspecialchars($fill['kode_brg']); ?>&sebelum=<?php echo htmlspecialchars($fill['sebelum']); ?>" class="btn btn-danger btn-xs btn-flat">
                                                    <i class="fa fa-trash"></i> Batal
                                                </a>
                                            </td>
                                        </tr>
                                    <?php
                                        }
                                    } else {
                                        echo "<tr><td colspan='8' class='text-center'>Belum ada barang yang ditambahkan ke daftar penyesuaian.</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <?php if (mysqli_num_rows($result_daftar) > 0) : ?>
                        <div class="box-footer">
                            <form method="post" id="form-simpan">
                                <input type="hidden" name="nota" value="<?php echo htmlspecialchars($nota_aktif); ?>">
                                <div class="form-group">
                                    <label for="keterangan">Catatan Keseluruhan (Opsional)</label>
                                    <textarea class="form-control" name="keterangan" id="keterangan" rows="2" placeholder="Cth: Hasil stok opname bulanan"></textarea>
                                </div>
                                <button type="button" id="btn-simpan" class="btn bg-blue  pull-right"><i class="fa fa-save"></i> Simpan Penyesuaian</button>
                            </form>
                        </div>
                        <?php endif; ?>
                    </div>

                    <?php else: ?>
                    <div class="callout callout-danger">
                        <h4>Akses Ditolak!</h4>
                        <p>Anda tidak memiliki izin untuk mengakses halaman ini.</p>
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
        $("#tersedia").val(sisa).focus();
        $("#kode").val(kode);
    });

    // PERMINTAAN: Tambah alert jika stok sama sebelum submit
    $('#form-tambah-item').on('submit', function(e) {
        var stokTercatat = parseInt($('#stok').val());
        var stokAktual = parseInt($('#tersedia').val());
        var kodeBarang = $('#kode').val();

        // Cek jika belum ada barang yang dipilih
        if (!kodeBarang) {
            e.preventDefault(); // Mencegah form dikirim
            swal({
                title: 'Belum Pilih Barang',
                text: 'Silakan pilih barang terlebih dahulu dari daftar.',
                icon: 'warning',
            });
            return;
        }

        // Cek jika stoknya sama
        if (stokTercatat === stokAktual) {
            e.preventDefault(); // Mencegah form dikirim
            swal({
                title: 'Tidak Ada Perubahan',
                text: 'Stok tercatat dan stok aktual sama. Tidak ada penyesuaian yang perlu dilakukan.',
                icon: 'info',
            });
        }
    });


    $('#btn-simpan').on('click', function(e) {
        e.preventDefault();
        swal({
            title: "Simpan Penyesuaian?",
            text: "Setelah disimpan, data penyesuaian ini tidak dapat diubah lagi. Pastikan semua data sudah benar.",
            icon: "warning",
            buttons: ["Batal", "Ya, Simpan!"],
            dangerMode: true,
        })
        .then((willSave) => {
            if (willSave) {
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
        $type = htmlspecialchars($flash['type']);
        $message = addslashes(htmlspecialchars($flash['message']));
        $title = '';
        switch ($type) {
            case 'success': $title = 'Berhasil!'; break;
            case 'warning': $title = 'Perhatian!'; break;
            case 'error': $title = 'Gagal!'; break;
        }
        echo "swal({ title: '$title', text: '$message', icon: '$type', timer: 3000, buttons: false });";
        unset($_SESSION['flash_message']);
    }
    ?>
});
</script>
</body>
</html>