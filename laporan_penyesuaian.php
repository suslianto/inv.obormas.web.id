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
    <style>
        @media print {
            .no-print, .no-print * {
                display: none !important;
            }
            .box {
                border: none !important;
                box-shadow: none !important;
            }
            .box-header {
                display: block !important;
                text-align: center;
            }
        }
    </style>
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
                    $halaman = "laporan_penyesuaian";
                    $dataapa = "Penyesuaian Stok";
                    $tabeldatabase = "stok_sesuai";
                    $chmod = $chmenu9;
                    $search = isset($_POST['search']) ? mysqli_real_escape_string($conn, $_POST['search']) : '';
                    ?>
                    <!-- SETTING STOP -->

                    <!-- BREADCRUMB -->
                    <ol class="breadcrumb no-print">
                        <li><a href="<?php echo $_SESSION['baseurl']; ?>">Dashboard </a></li>
                        <li class="active"><?php echo $dataapa ?></li>
                    </ol>

                    <?php if ($chmod >= 1 || $_SESSION['jabatan'] == 'admin'): ?>
                    <div class="box" id="tabel1">
                        <div class="box-header with-border">
                            <h3 class="box-title" style="vertical-align: middle; margin-right: 10px;"><?php echo $dataapa; ?></h3>
                                <div class="btn-group" role="group">
                                    <a href="configuration/config_export.php?forward=penyesuaian&search=<?php echo $search; ?>" class="btn btn-default btn-sm"><i class="fa fa-download"></i> Export Excel</a>
                                </div>
                            <div class="box-tools pull-right no-print">
                                <form method="post" action="" style="display:inline-block; margin-left: 10px;">
                                    <div class="input-group input-group-sm" style="width: 250px;">
                                        <input type="text" name="search" class="form-control pull-right" placeholder="Cari Nota atau User..." value="<?php echo htmlspecialchars($search); ?>">
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
                                $sql_where = " WHERE nota LIKE '%$search%' OR oleh LIKE '%$search%' OR keterangan LIKE '%$search%'";
                            }
                            
                            $sql_count = "SELECT COUNT(*) AS totaldata FROM $tabeldatabase" . $sql_where;
                            $result_count = mysqli_query($conn, $sql_count);
                            $row_count = mysqli_fetch_assoc($result_count);
                            $tcount = $row_count['totaldata'];
                            
                            $tpages = ($tcount) ? ceil($tcount / $rpp) : 1;
                            $i = ($page - 1) * $rpp;
                            $no_urut = ($page - 1) * $rpp;

                            $sql_data = "SELECT * FROM $tabeldatabase" . $sql_where . " ORDER BY no DESC LIMIT $i, $rpp";
                            $result = mysqli_query($conn, $sql_data);
                            ?>
                            <table class="table table-hover table-bordered">
                                <thead>
                                    <tr>
                                        <th style="width:10px">No</th>
                                        <th style="width:10%">ID Sesuaian</th>
                                        <th style="width:10%">Tanggal</th>
                                        <th>Oleh</th>
                                        <th>Keterangan</th>
                                        <th style="width:70px;" class="no-print">Opsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                if (mysqli_num_rows($result) > 0) {
                                    while ($fill = mysqli_fetch_assoc($result)) {
                                ?>
                                    <tr>
                                        <td><?php echo ++$no_urut;?></td>
                                        <td><?php echo htmlspecialchars($fill['nota']); ?></td>
                                        <td><?php echo htmlspecialchars(date("d-m-Y", strtotime($fill['tgl']))); ?></td>
                                        <td><?php echo htmlspecialchars($fill['oleh']); ?></td>
                                        <td><?php echo htmlspecialchars($fill['keterangan']); ?></td>
                                        <td class="no-print">
                                            <?php if ($chmod >= 3 || $_SESSION['jabatan'] == 'admin'): ?>
                                                <a class="btn btn-success btn-xs" href="laporan_penyesuaian_print.php?nota=<?php echo $fill['nota']; ?>" target="_blank" title="Cetak Detail"><i class="fa fa-print"></i></a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php 
                                    }
                                } else {
                                    echo "<tr><td colspan='6' class='text-center'>Tidak ada data ditemukan.</td></tr>";
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="box-footer no-print">
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
