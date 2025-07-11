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
    <style>
        @media print {
            .no-print, .no-print * { display: none !important; }
            .box { border: none !important; box-shadow: none !important; }
            .box-header { display: block !important; text-align: center; }
            .table { width: 100%; }
        }
    </style>
</head>
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
                    <!-- SETTING START-->
                    <?php
                    error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
                    include "configuration/config_chmod.php";
                    $halaman = "report_operasi";
                    $dataapa = "Laporan Barang";
                    $chmod = $chmenu9;
                    $m = mysqli_fetch_assoc(mysqli_query($conn, "SELECT mode FROM backset"));
                    $mode = $m['mode'];
                    
                    // Ambil tanggal dari filter
                    $dr = isset($_GET['dari']) ? mysqli_real_escape_string($conn, $_GET['dari']) : date("Y-m-d");
                    $sam = isset($_GET['sampai']) ? mysqli_real_escape_string($conn, $_GET['sampai']) : date("Y-m-d");
                    ?>
                    <!-- SETTING STOP -->

                    <!-- BREADCRUMB -->
                    <ol class="breadcrumb no-print">
                        <li><a href="<?php echo $_SESSION['baseurl']; ?>">Dashboard </a></li>
                        <li class="active"><?php echo $dataapa ?></li>
                    </ol>

                    <?php if ($chmod >= 1 || $_SESSION['jabatan'] == 'admin'): ?>

                    <!-- FORM FILTER TANGGAL -->
                    <div class="box box-info no-print">
                        <div class="box-header with-border">
                            <h3 class="box-title">Filter Laporan</h3>
                        </div>
                        <form method="get" action="">
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Dari Tanggal</label>
                                            <input type="text" class="form-control" id="datepicker" name="dari" value="<?php echo $dr; ?>" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Sampai Tanggal</label>
                                            <input type="text" class="form-control" id="datepicker2" name="sampai" value="<?php echo $sam; ?>" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <label>&nbsp;</label>
                                            <button type="submit" name="find" class="btn bg-blue btn-block"><i class="fa fa-search"></i> Cari</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <?php if(isset($_GET["find"])): ?>
                        <?php
                        // Query untuk ringkasan laba
                        $qw = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total) as total, SUM(modal) as modal, SUM(total-modal) as profit FROM stok_keluar WHERE tgl BETWEEN '$dr' AND '$sam'"));
                        ?>
                        <!-- RINGKASAN LABA
                        <div class="row">
                            <div class="col-lg-4 col-xs-6">
                                <div class="small-box bg-aqua">
                                    <div class="inner">
                                        <h3>Rp <?php echo number_format($qw['total']);?></h3>
                                        <p>Total Penjualan</p>
                                    </div>
                                    <div class="icon"><i class="ion ion-bag"></i></div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-xs-6">
                                <div class="small-box bg-red">
                                    <div class="inner">
                                        <h3>Rp <?php echo number_format($qw['modal']);?></h3>
                                        <p>Harga Pokok Penjualan (Modal)</p>
                                    </div>
                                    <div class="icon"><i class="ion ion-pie-graph"></i></div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-xs-6">
                                <div class="small-box bg-green">
                                    <div class="inner">
                                        <h3>Rp <?php echo number_format($qw['profit']);?></h3>
                                        <p>Perkiraan Laba</p>
                                    </div>
                                    <div class="icon"><i class="ion ion-stats-bars"></i></div>
                                </div>
                            </div>
                        </div> -->

                        <!-- TABEL LAPORAN STOK -->
                        <div class="box" id="tabel1">
                            <div class="box-header with-border">
                                <h3 class="box-title">Data Stok Keluar dan Masuk Periode: <?php echo date("d/m/Y", strtotime($dr));?> - <?php echo date("d/m/Y", strtotime($sam));?></h3>
                                <div class="box-tools pull-right no-print">
                                    <button type="button" class="btn btn-success btn-xs" onclick="window.print()"><i class="fa fa-print"></i> Print</button>
                                </div>
                            </div>
                            <div class="box-body table-responsive">
                                <table class="table table-hover table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Barang</th>
                                            <th style="width: 10%">Masuk</th>
                                            <th style="width: 10%">Keluar</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    // Query efisien untuk mengambil data stok
                                    $sql_data = "
                                        SELECT 
                                            b.nama,
                                            (SELECT SUM(smd.jumlah) FROM stok_masuk_daftar smd JOIN stok_masuk sm ON smd.nota = sm.nota WHERE smd.kode_barang = b.kode AND sm.tgl BETWEEN '$dr' AND '$sam') as total_masuk,
                                            (SELECT SUM(skd.jumlah) FROM stok_keluar_daftar skd JOIN stok_keluar sk ON skd.nota = sk.nota WHERE skd.kode_barang = b.kode AND sk.tgl BETWEEN '$dr' AND '$sam') as total_keluar
                                        FROM 
                                            barang b
                                        ORDER BY b.no DESC";
                                    $result_data = mysqli_query($conn, $sql_data);
                                    $no_urut = 0;
                                    while ($fill = mysqli_fetch_assoc($result_data)):
                                        $no_urut++;
                                    ?>
                                        <tr>
                                            <td><?php echo $no_urut; ?></td>
                                            <td><?php echo htmlspecialchars($fill['nama']); ?></td>
                                            <td><?php echo number_format($fill['total_masuk'] ?? 0); ?></td>
                                            <td><?php echo number_format($fill['total_keluar'] ?? 0); ?></td>
                                        </tr>
                                    <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php endif; ?>

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
<script src="dist/plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="dist/plugins/slimScroll/jquery.slimscroll.min.js"></script>
<script src="dist/plugins/fastclick/fastclick.js"></script>
<script src="dist/js/app.min.js"></script>
<script>
$(function () {
    $('#datepicker, #datepicker2').datepicker({
        autoclose: true,
        format: 'yyyy-mm-dd'
    });
});
</script>
</body>
</html>
