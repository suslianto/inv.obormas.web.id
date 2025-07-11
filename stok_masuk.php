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
                    $halaman = "stok_masuk.php"; // Halaman ini untuk daftar
                    $dataapa = "Barang Masuk";
                    $tabeldatabase = "stok_masuk";
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

                    <?php if ($chmod >= 1 || $_SESSION['jabatan'] == 'admin'): ?>
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title" style="vertical-align: middle; margin-right: 10px;"><?php echo $dataapa; ?></h3>
                            <a href="stok_in" class="btn bg-blue btn-sm" style="vertical-align: middle;"><i class="fa fa-plus"></i> Tambah</a>
                            <div class="box-tools pull-right">
                                <form method="post" action="">
                                    <div class="input-group input-group-sm" style="width: 250px;">
                                        <input type="text" name="search" class="form-control pull-right" placeholder="Cari No. Trx atau Supplier..." value="<?php echo htmlspecialchars($search); ?>">
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
                            $reload = "$halaman?pagination=true";
                            $page = intval(isset($_GET["page"]) ? $_GET["page"] : 1);
                            if ($page <= 0) $page = 1;

                            $sql_where = "";
                            if (!empty($search)) {
                                $sql_where = " WHERE nota LIKE '%$search%' OR supplier LIKE '%$search%'";
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
                                        <th>No</th>
                                        <th>No. Trx</th>
                                        <th>Tanggal</th>
                                        <th>Supplier</th>
                                        <?php if ($chmod >= 3 || $_SESSION['jabatan'] == 'admin') { ?>
                                            <th style="width:70px;">Opsi</th>
                                        <?php } ?>
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
                                        <td><?php echo htmlspecialchars($fill['supplier']); ?></td>
                                        <td>
                                            <?php if ($chmod >= 4 || $_SESSION['jabatan'] == 'admin') { ?>
                                                <a href="stok_masuk_detail.php?nota=<?php echo $fill['nota'];?>" class="btn btn-info btn-xs" title="Detail"><i class="fa fa-eye"></i></a>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                <?php 
                                    }
                                } else {
                                    echo "<tr><td colspan='5' class='text-center'>Tidak ada data ditemukan.</td></tr>";
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

<!-- Script untuk menampilkan notifikasi jika ada -->
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
