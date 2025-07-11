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
    <!-- Pustaka SweetAlert -->
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
                    $halaman = "surat_kelola.php";
                    $dataapa = "Surat Jalan";
                    $tabeldatabase = "surat";
                    $chmod = $chmenu5;
                    $search = isset($_POST['search']) ? mysqli_real_escape_string($conn, $_POST['search']) : '';
                    ?>
                    <!-- SETTING STOP -->

                    <!-- BREADCRUMB -->
                    <ol class="breadcrumb">
                        <li><a href="<?php echo $_SESSION['baseurl']; ?>">Dashboard </a></li>
                        <li><a href="<?php echo $halaman;?>"><?php echo $dataapa ?></a></li>
                        <li class="active"><?php echo !empty($search) ? "Hasil untuk: " . htmlspecialchars($search) : "Data " . $dataapa; ?></li>
                    </ol>

                    <?php if ($chmod >= 2 || $_SESSION['jabatan'] == 'admin'): ?>
                    <div class="box">
                        <!-- PERUBAHAN UI: Header Box dirapikan -->
                        <div class="box-header with-border">
                            <h3 class="box-title" style="vertical-align: middle; margin-right: 10px;"><?php echo $dataapa; ?></h3>
                            <a href="stok_out" class="btn bg-blue btn-sm" style="vertical-align: middle;"><i class="fa fa-plus"></i> Tambah</a>
                            <div class="box-tools pull-right">
                                <form method="post" action="">
                                    <div class="input-group input-group-sm" style="width: 250px;">
                                        <input type="text" name="search" class="form-control pull-right" placeholder="Cari..." value="<?php echo htmlspecialchars($search); ?>">
                                        <div class="input-group-btn">
                                            <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="box-body table-responsive">
                            <?php
                            // LOGIKA ASLI UNTUK PAGINASI DAN PENCARIAN
                            error_reporting(E_ALL ^ E_DEPRECATED);
                            $sql_select = "select * from surat order by no desc";
                            $result = mysqli_query($conn, $sql_select);
                            $rpp = 15;
                            $reload = "$halaman"."?pagination=true";
                            $page = intval(isset($_GET["page"]) ? $_GET["page"] : 0);
                            if ($page <= 0) $page = 1;
                            $tcount = mysqli_num_rows($result);
                            $tpages = ($tcount) ? ceil($tcount / $rpp) : 1;
                            $count = 0;
                            $i = ($page - 1) * $rpp;
                            $no_urut = ($page - 1) * $rpp;
                            ?>
                            <!-- PERUBAHAN UI: Menambahkan class table-bordered -->
                            <table class="table table-hover table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nomor Surat</th>
                                        <th>Tanggal</th>
                                        <th>Tujuan</th>
                                        <th>Driver</th>
                                        <?php if ($chmod >= 3 || $_SESSION['jabatan'] == 'admin') { ?>
                                            <th>Opsi</th>
                                        <?php } ?>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                if (!empty($search)) {
                                    $query1 = "
                                        SELECT s.* FROM surat s
                                        LEFT JOIN stok_keluar_daftar skd ON s.nota = skd.nota 
                                        WHERE s.nota LIKE '%$search%'
                                        OR s.nosurat LIKE '%$search%'
                                        OR s.tujuan LIKE '%$search%'
                                        OR s.driver LIKE '%$search%' 
                                        OR skd.nama LIKE '%$search%' 
                                        OR skd.kode_barang LIKE '%$search%' 
                                        GROUP BY s.nota
                                        ORDER BY s.no DESC
                                        LIMIT $i, $rpp";
                                    $hasil = mysqli_query($conn, $query1);
                                    while ($fill = mysqli_fetch_assoc($hasil)) {
                                ?>
                                    <tr>
                                        <td><?php echo ++$no_urut;?></td>
                                        <td><?php echo htmlspecialchars($fill['nosurat']); ?></td>
                                        <td><?php echo htmlspecialchars(date("d-m-Y", strtotime($fill['tanggal']))); ?></td>
                                        <td><?php echo htmlspecialchars($fill['tujuan']); ?></td>
                                        <td><?php echo htmlspecialchars($fill['driver']); ?></td>
                                        <td>
                                            <?php if ($chmod >= 3 || $_SESSION['jabatan'] == 'admin') { ?>
                                                <a class="btn btn-success btn-xs" href="surat_print.php?nota=<?php echo $fill['nota']; ?>" target="_blank"><i class="fa fa-print"></i> Print</a>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                <?php
                                    }
                                } else {
                                    while (($count < $rpp) && ($i < $tcount)) {
                                        mysqli_data_seek($result, $i);
                                        $fill = mysqli_fetch_array($result);
                                ?>
                                    <tr>
                                        <td><?php echo ++$no_urut;?></td>
                                        <td><?php echo htmlspecialchars($fill['nosurat']); ?></td>
                                        <td><?php echo htmlspecialchars(date("d-m-Y", strtotime($fill['tanggal']))); ?></td>
                                        <td><?php echo htmlspecialchars($fill['tujuan']); ?></td>
                                        <td><?php echo htmlspecialchars($fill['driver']); ?></td>
                                        <td>
                                            <?php if ($chmod >= 3 || $_SESSION['jabatan'] == 'admin') { ?>
                                                 <a class="btn btn-success btn-xs" href="surat_print.php?nota=<?php echo $fill['nota']; ?>" target="_blank"><i class="fa fa-print"></i> Print</a>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                <?php
                                        $i++;
                                        $count++;
                                    }
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="box-footer">
                            <div class="pull-right">
                                <?php if($tcount >= $rpp){ echo paginate_one($reload, $page, $tpages); } ?>
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

<!-- SCRIPT UNTUK MENAMPILKAN SWEETALERT -->
<script>
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
