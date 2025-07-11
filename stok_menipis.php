<!DOCTYPE html>
<html>
<?php
include "configuration/config_etc.php";
include "configuration/config_include.php";
include "configuration/config_alltotal.php"; // Untuk data small box
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
            .box-header.with-border { border-bottom: 1px solid #000 !important; }
            .table-bordered th, .table-bordered td { border: 1px solid #000 !important; }
            .table { width: 100%; }
            body { margin: 0; }
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
                    $halaman = "stok_menipis";
                    $dataapa = "Stok Menipis";
                    $tabeldatabase = "barang";
                    $chmod = $chmenu8;
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
                                    <a onclick="window.print()" class="btn btn-default btn-sm"><i class="fa fa-print"></i> Print</a>
                                </div>
                            <div class="box-tools pull-right no-print">
                                <form method="post" action="" style="display:inline-block; margin-left: 10px;">
                                    <div class="input-group input-group-sm" style="width: 250px;">
                                        <input type="text" name="search" class="form-control pull-right" placeholder="Cari SKU, Nama, Merek..." value="<?php echo htmlspecialchars($search); ?>">
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

                            // Kondisi WHERE utama
                            $sql_where = " WHERE sisa <= stokmin";
                            if (!empty($search)) {
                                $sql_where .= " AND (kode LIKE '%$search%' OR nama LIKE '%$search%' OR brand LIKE '%$search%')";
                            }
                            
                            $sql_count = "SELECT COUNT(*) AS totaldata FROM $tabeldatabase" . $sql_where;
                            $result_count = mysqli_query($conn, $sql_count);
                            $row_count = mysqli_fetch_assoc($result_count);
                            $tcount = $row_count['totaldata'];
                            
                            $tpages = ($tcount) ? ceil($tcount / $rpp) : 1;
                            $i = ($page - 1) * $rpp;
                            $no_urut = ($page - 1) * $rpp;

                            $sql_data = "SELECT * FROM $tabeldatabase" . $sql_where . " ORDER BY sisa ASC LIMIT $i, $rpp";
                            $result = mysqli_query($conn, $sql_data);
                            ?>
                            <table class="table table-hover table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode</th>
                                        <th>Nama Barang</th>
                                        <th>Stok</th>
                                        <th class="no-print">Opsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                if (mysqli_num_rows($result) > 0) {
                                    while ($fill = mysqli_fetch_assoc($result)) {
                                ?>
                                    <tr>
                                        <td><?php echo ++$no_urut;?></td>
                                        <td><?php echo htmlspecialchars($fill['kode']); ?></td>
                                        <td><?php echo htmlspecialchars($fill['nama']); ?></td>
                                        <td>
                                            <?php if($fill['sisa'] >= 10){ ?>
                                                <span class="badge bg-yellow"><?php echo $fill['sisa'];?></span>
                                            <?php } else { ?> 
                                                <span class="badge bg-red"><?php echo $fill['sisa'];?></span> 
                                            <?php } ?>
                                        </td>
                                        <td class="no-print">
                                            <?php if ($chmod >= 1 || $_SESSION['jabatan'] == 'admin') { ?>
                                                <a href="stok_in.php?barcode=<?php echo $fill['barcode']?>" class="btn btn-info btn-xs"><i class="fa fa-plus"></i> Tambah</a>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                <?php 
                                    }
                                } else {
                                    echo "<tr><td colspan='5' class='text-center'>Tidak ada data stok menipis.</td></tr>";
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
