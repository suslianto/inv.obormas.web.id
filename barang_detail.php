<!DOCTYPE html>
<html>
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

if (!login_check()) {
    echo '<meta http-equiv="refresh" content="0; url=logout" />';
    exit(0);
}

// ===================================================================
// PENGAMBILAN DATA DARI DATABASE
// ===================================================================
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
include "configuration/config_chmod.php";

// Pengaturan Halaman
$halaman = "barang";
$dataapa = "Barang";
$chmod = $chmenu4;
$no = isset($_GET['no']) ? (int)$_GET['no'] : 0;

// 1. Ambil Data Barang Utama
$sql_barang = "SELECT * FROM barang WHERE no = '$no'";
$query_barang = mysqli_query($conn, $sql_barang);
$data_barang = mysqli_fetch_assoc($query_barang);

// Jika data barang tidak ditemukan, hentikan eksekusi
if (!$data_barang) {
    // Menampilkan pesan error di dalam layout yang sudah ada
    echo '<div class="wrapper"><div class="content-wrapper"><section class="content"><div class="callout callout-danger"><h4>Error!</h4><p>Data barang tidak ditemukan.</p></div></section></div></div>';
    include "configuration/config_footer.php";
    exit();
}

// 2. Siapkan Variabel untuk Tampilan
$avatar = ($data_barang['avatar'] == "dist/upload/") ? "dist/upload/index.jpg" : $data_barang['avatar'];
$kode_brg = $data_barang['kode'];

// 3. Ambil Riwayat Mutasi Stok
$riwayat_mutasi = [];
$sql_mutasi = "SELECT * FROM mutasi WHERE kodebarang='$kode_brg' ORDER BY tgl DESC, jam DESC";
$result_mutasi = mysqli_query($conn, $sql_mutasi);
if ($result_mutasi) {
    while ($row = mysqli_fetch_assoc($result_mutasi)) {
        $riwayat_mutasi[] = $row;
    }
}
?>

<div class="wrapper">
    <?php
    theader();
    menu();
    ?>
    <div class="content-wrapper">
        <section class="content-header">
        </section>
        <section class="content">
            <div class="row">
                <div class="col-lg-12">
                    <!-- BREADCRUMB -->
                    <ol class="breadcrumb">
                        <li><a href="<?php echo $_SESSION['baseurl']; ?>">Dashboard </a></li>
                        <li><a href="<?php echo $halaman;?>.php"><?php echo $dataapa ?></a></li>
                        <li class="active">Detail <?php echo $dataapa ?></li>
                    </ol>
                    <h4 class="box-title">Detail : <?php echo htmlspecialchars($data_barang['sku']);?></h4>
                    <?php if ($chmod >= 1 || $_SESSION['jabatan'] == 'admin'): ?>
                    <div class="row">
                        <!-- Kolom Kiri: Profil Barang -->
                        <div class="col-md-4">
                            <div class="box box-primary">
                                <div class="box-body box-profile">
                                    <img class="profile-user-img img-responsive img-circle" src="<?php echo htmlspecialchars($avatar); ?>" alt="Gambar produk">
                                    <h3 class="profile-username text-center"><?php echo htmlspecialchars($data_barang['nama']); ?></h3>
                                    <p class="text-muted text-center"><?php echo htmlspecialchars($data_barang['brand']); ?></p>
                                    <ul class="list-group list-group-unbordered">
                                        <li class="list-group-item">
                                            <b>Stok Keluar</b> <a class="pull-right"><?php echo number_format($data_barang['terjual']); ?></a>
                                        </li>
                                        <li class="list-group-item">
                                            <b>Stok Masuk</b> <a class="pull-right"><?php echo number_format($data_barang['terbeli']); ?></a>
                                        </li>
                                        <li class="list-group-item">
                                            <b>Stok Tersedia</b> <a class="pull-right"><?php echo number_format($data_barang['sisa']); ?></a>
                                        </li>
                                    </ul>
                                    <a href="stok_sesuaikan.php" class="btn btn-primary btn-block"><b>Penyesuaian Stok</b></a>
                                </div>
                            </div>
                        </div>

                        <!-- Kolom Kanan: Detail & Riwayat -->
                        <div class="col-md-8">
                            <div class="nav-tabs-custom">
                                <ul class="nav nav-tabs">
                                    <li class="active"><a href="#detail" data-toggle="tab">Detail Barang</a></li>
                                    <li><a href="#riwayat" data-toggle="tab">Riwayat Stok</a></li>
                                </ul>
                                <div class="tab-content">
                                    <!-- TAB DETAIL BARANG -->
                                    <div class="active tab-pane" id="detail">
                                        <form class="form-horizontal">
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">SKU</label>
                                                <div class="col-sm-10"><input type="text" class="form-control" value="<?php echo htmlspecialchars($data_barang['sku']);?>" readonly></div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Nama</label>
                                                <div class="col-sm-10"><input type="text" class="form-control" value="<?php echo htmlspecialchars($data_barang['nama']);?>" readonly></div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Kategori</label>
                                                <div class="col-sm-10"><input type="text" class="form-control" value="<?php echo htmlspecialchars($data_barang['kategori']);?>" readonly></div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Merek</label>
                                                <div class="col-sm-10"><input type="text" class="form-control" value="<?php echo htmlspecialchars($data_barang['brand']);?>" readonly></div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Satuan</label>
                                                <div class="col-sm-10"><input type="text" class="form-control" value="<?php echo htmlspecialchars($data_barang['satuan']);?>" readonly></div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Expired</label>
                                                <div class="col-sm-10"><input type="text" class="form-control" value="<?php echo htmlspecialchars($data_barang['expired']);?>" readonly></div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Lokasi</label>
                                                <div class="col-sm-10"><input type="text" class="form-control" value="<?php echo htmlspecialchars($data_barang['lokasi']);?>" readonly></div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Keterangan</label>
                                                <div class="col-sm-10"><textarea class="form-control" readonly><?php echo htmlspecialchars($data_barang['keterangan']);?></textarea></div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Barcode</label>
                                                <div class="col-sm-10"><input type="text" class="form-control" value="<?php echo htmlspecialchars($data_barang['barcode']);?>" readonly></div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-offset-2 col-sm-10">
                                                    <button type="button" class="btn btn-danger" onclick="window.open('cetak_barcode.php?kode=<?php echo $data_barang['kode'];?>')">Cetak Barcode</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <!-- TAB RIWAYAT STOK -->
                                    <div class="tab-pane" id="riwayat">
                                        <div class="table-responsive">
                                            <table class="table table-condensed table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 10px">#</th>
                                                        <th>Tanggal</th>
                                                        <th>User</th>
                                                        <th>Aktivitas</th>
                                                        <th>Jumlah</th>
                                                        <th>Stok Akhir</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                <?php if (!empty($riwayat_mutasi)): ?>
                                                    <?php $no_mutasi = 0; ?>
                                                    <?php foreach ($riwayat_mutasi as $fill_mutasi): ?>
                                                        <?php $no_mutasi++; ?>
                                                        <tr>
                                                            <td><?php echo $no_mutasi;?></td>
                                                            <td><?php echo htmlspecialchars(date("d-m-Y H:i", strtotime($fill_mutasi['tgl'] . ' ' . $fill_mutasi['jam']))); ?></td>
                                                            <td><?php echo htmlspecialchars($fill_mutasi['namauser']); ?></td>
                                                            <td><?php echo htmlspecialchars($fill_mutasi['kegiatan']); ?></td>
                                                            <td><?php echo htmlspecialchars($fill_mutasi['jumlah']); ?></td>
                                                            <td><?php echo htmlspecialchars($fill_mutasi['sisa']); ?></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <tr><td colspan="6" class="text-center">Tidak ada riwayat stok.</td></tr>
                                                <?php endif; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
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
    <?php footer();?>
    <div class="control-sidebar-bg"></div>
</div>

<!-- Script -->
<script src="dist/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="dist/bootstrap/js/bootstrap.min.js"></script>
<script src="dist/plugins/slimScroll/jquery.slimscroll.min.js"></script>
<script src="dist/plugins/fastclick/fastclick.js"></script>
<script src="dist/js/app.min.js"></script>
</body>
</html>
