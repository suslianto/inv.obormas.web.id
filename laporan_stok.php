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
?>
<head>
    <!-- Pustaka SweetAlert (jika diperlukan) -->
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
                    <!-- SETTING START-->
                    <?php
                    error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
                    include "configuration/config_chmod.php";
                    $halaman = "laporan_stok";
                    $dataapa = "Laporan Stok";
                    $tabeldatabase = "barang";
                    $chmod = $chmenu9;
                    $search = isset($_POST['search']) ? mysqli_real_escape_string($conn, $_POST['search']) : '';
                    ?>
                    <!-- SETTING STOP -->

                    <!-- BREADCRUMB -->
                    <ol class="breadcrumb">
                        <li><a href="<?php echo $_SESSION['baseurl']; ?>">Dashboard </a></li>
                        <li class="active"><?php echo $dataapa ?></li>
                    </ol>

                    <?php if ($chmod >= 1 || $_SESSION['jabatan'] == 'admin'): ?>
                    <div class="box" id="tabel1">
                        <div class="box-header with-border">
                            <h3 class="box-title" style="vertical-align: middle; margin-right: 10px;"><?php echo $dataapa; ?></h3>
                                <div class="btn-group" role="group">
                                    <a onclick="frames['frame'].print()" class="btn btn-default btn-sm" name="cetak" value="cetak"><i class="fa fa-print"></i> Print</a>
                                    <a href="configuration/config_export.php?forward=stokall&search=<?php echo $search; ?>" class="btn btn-default btn-sm"><i class="fa fa-download"></i> Export Excel</a>
                                </div>
                            <div class="box-tools pull-right">
                                <form method="post" action="" style="display:inline-block; margin-left: 10px;">
                                    <div class="input-group input-group-sm" style="width: 250px;">
                                        <input type="text" name="search" class="form-control pull-right" placeholder="Cari SKU atau Nama..." value="<?php echo htmlspecialchars($search); ?>">
                                        <div class="input-group-btn">
                                            <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="box-body table-responsive">
                            <?php
                            // LOGIKA PAGINASI DAN PENCARIAN YANG DIPERBAIKI
                            $rpp = 15;
                            $reload = "$halaman.php?pagination=true";
                            $page = intval(isset($_GET["page"]) ? $_GET["page"] : 1);
                            if ($page <= 0) $page = 1;

                            $sql_where = "";
                            if (!empty($search)) {
                                $sql_where = " WHERE b.sku LIKE '%$search%' OR b.nama LIKE '%$search%'";
                            }
                            
                            $sql_count = "SELECT COUNT(*) AS totaldata FROM $tabeldatabase b" . $sql_where;
                            $result_count = mysqli_query($conn, $sql_count);
                            $row_count = mysqli_fetch_assoc($result_count);
                            $tcount = $row_count['totaldata'];
                            
                            $tpages = ($tcount) ? ceil($tcount / $rpp) : 1;
                            $i = ($page - 1) * $rpp;
                            $no_urut = ($page - 1) * $rpp;

                            // QUERY EFISIEN UNTUK MENGAMBIL SEMUA DATA
                            $sql_data = "
                                SELECT 
                                    b.sku, 
                                    b.nama, 
                                    b.sisa,
                                    (SELECT SUM(jumlah) FROM stok_masuk_daftar WHERE kode_barang = b.kode) as total_masuk,
                                    (SELECT SUM(jumlah) FROM stok_keluar_daftar WHERE kode_barang = b.kode) as total_keluar
                                FROM 
                                    barang b
                                " . $sql_where . "
                                ORDER BY b.no DESC 
                                LIMIT $i, $rpp";
                            
                            $result = mysqli_query($conn, $sql_data);
                            ?>
                            <table class="table table-hover table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>SKU</th>
                                        <th>Nama</th>
                                        <th>Masuk</th>
                                        <th>Keluar</th>
                                        <th>Stok Sistem</th>
                                        <th>Stok Aktual</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                if (mysqli_num_rows($result) > 0) {
                                    while ($fill = mysqli_fetch_assoc($result)) {
                                ?>
                                    <tr>
                                        <td><?php echo ++$no_urut;?></td>
                                        <td><?php echo htmlspecialchars($fill['sku']); ?></td>
                                        <td><?php echo htmlspecialchars($fill['nama']); ?></td>
                                        <td><?php echo number_format($fill['total_masuk'] ?? 0); ?></td>
                                        <td><?php echo number_format($fill['total_keluar'] ?? 0); ?></td>
                                        <td><?php echo number_format(($fill['total_masuk'] ?? 0) - ($fill['total_keluar'] ?? 0)); ?></td>
                                        <td><?php echo number_format($fill['sisa']); ?></td>
                                    </tr>
                                <?php 
                                    }
                                } else {
                                    echo "<tr><td colspan='7' class='text-center'>Tidak ada data ditemukan.</td></tr>";
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="box-footer">
                            <div class="pull-right">
                                <?php if ($tcount > $rpp) { echo paginate_one($reload, $page, $tpages); } ?>
                            </div>
                        </div>
                    </div>
                    <?php else: ?>
                    <div class="callout callout-danger">
                        <h4>Info</h4>
                        <b>Hanya user tertentu yang dapat mengakses halaman <?php echo $dataapa;?> ini.</b>
                    </div>
                    <?php endif; ?>
                    <iframe src="laporan_stok_print.php" style="display:none;" name="frame"></iframe>
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
