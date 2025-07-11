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
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Manajemen Merek</title>
    <!-- Tambahkan pustaka SweetAlert -->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <style>
        /* CSS untuk membuat header box tetap (sticky) */
        .sticky-header {
            position: -webkit-sticky; /* Untuk Safari */
            position: sticky;
            top: 0; /* Tetap di bagian atas */
            z-index: 1020; /* Pastikan di atas elemen lain saat scroll */
            background-color: #ffffff; /* Warna latar belakang agar konten di bawahnya tidak tembus */
        }
    </style>
</head>
<body class="hold-transition skin-purple sidebar-mini">

<?php
// Tampilkan notifikasi SweetAlert berdasarkan status dari URL
if (isset($_GET['status'])) {
    $status = $_GET['status'];
    $dataapa_uc = "Merek";
    echo '<script>';
    // Pastikan DOM sudah siap sebelum menampilkan swal
    echo 'document.addEventListener("DOMContentLoaded", function() {';
    switch ($status) {
        case 'delete_success':
            echo "swal('Berhasil!', '$dataapa_uc telah berhasil dihapus.', 'success');";
            break;
        case 'delete_fail_permission':
            echo "swal('Gagal!', 'Hanya user tertentu yang dapat menghapus data $dataapa_uc.', 'error');";
            break;
        case 'delete_fail_transaction':
            echo "swal('Gagal!', '$dataapa_uc tidak bisa dihapus karena sudah digunakan dalam transaksi.', 'error');";
            break;
    }
    // Hapus parameter status dari URL agar notifikasi tidak muncul lagi saat refresh
    echo "if (typeof window.history.pushState == 'function') {";
    echo "var newUrl = window.location.protocol + '//' + window.location.host + window.location.pathname;";
    echo "window.history.pushState({ path: newUrl }, '', newUrl);";
    echo "}";
    echo '});';
    echo '</script>';
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
                    <!-- SETTING START-->
                    <?php
                    error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
                    include "configuration/config_chmod.php";
                    $halaman = "merek";
                    $dataapa = "Merek";
                    $tabeldatabase = "brand";
                    $chmod = $chmenu3;
                    $forward = mysqli_real_escape_string($conn, $tabeldatabase);
                    $forwardpage = mysqli_real_escape_string($conn, $halaman);
                    $search = isset($_POST['search']) ? $_POST['search'] : '';
                    ?>
                    <!-- SETTING STOP -->
                    
                    <!-- Tambahkan class "sticky-header" untuk membuat elemen ini tetap -->
                    <div class="sticky-header">
                        <!-- BREADCRUMB -->
                        <ol class="breadcrumb" style="margin-bottom: 5px;">
                            <li><a href="<?php echo $_SESSION['baseurl']; ?>">Dashboard </a></li>
                            <li><a href="<?php echo $halaman; ?>"><?php echo $dataapa ?></a></li>
                            <li class="active"><?php echo !empty($search) ? "Hasil untuk: " . $search : "Data " . $dataapa; ?></li>
                        </ol>
                    </div>


                    <?php if ($chmod >= 1 || $_SESSION['jabatan'] == 'admin') : ?>
                        <?php
                        $sqla = "SELECT COUNT(*) AS totaldata FROM $forward";
                        $hasila = mysqli_query($conn, $sqla);
                        $rowa = mysqli_fetch_assoc($hasila);
                        $totaldata = $rowa['totaldata'];
                        ?>
                        <div class="box">
                            <!-- Tambahkan class "sticky-header" untuk membuat elemen ini tetap -->
                            <div class="box-header with-border sticky-header">
                                <h3 class="box-title" style="vertical-align: middle; display: inline-block; margin-right: 10px;">Data <?php echo $dataapa; ?> <span class="label label-default"><?php echo $totaldata; ?></span></h3>
                                <a href="add_merek" class="btn btn-info btn-sm" style="vertical-align: middle;">Tambah</a>
                                <div class="box-tools pull-right">
                                    <form method="post" action="">
                                        <div class="input-group input-group-sm" style="width: 250px;">
                                            <input type="text" name="search" class="form-control" placeholder="Cari...">
                                            <div class="input-group-btn">
                                                <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="box-body table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Kode</th>
                                            <th>Nama Merek</th>
                                            <?php if ($chmod >= 3 || $_SESSION['jabatan'] == 'admin') { ?>
                                                <th style="width:100px;">Opsi</th>
                                            <?php } ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $rpp = 15;
                                        $reload = "$halaman?pagination=true";
                                        $page = intval(isset($_GET["page"]) ? $_GET["page"] : 0);
                                        if ($page <= 0) $page = 1;

                                        $sql_select = "SELECT * FROM $forward";
                                        if (!empty($search)) {
                                            $sql_select .= " WHERE kode LIKE '%$search%' OR nama LIKE '%$search%'";
                                        }
                                        $sql_select .= " ORDER BY no";

                                        $result = mysqli_query($conn, $sql_select);
                                        $tcount = mysqli_num_rows($result);
                                        $tpages = ($tcount) ? ceil($tcount / $rpp) : 1;
                                        $count = 0;
                                        $i = ($page - 1) * $rpp;
                                        $no_urut = ($page - 1) * $rpp;

                                        while (($count < $rpp) && ($i < $tcount)) {
                                            mysqli_data_seek($result, $i);
                                            $fill = mysqli_fetch_array($result);
                                        ?>
                                            <tr>
                                                <td><?php echo ++$no_urut; ?></td>
                                                <td><?php echo htmlspecialchars($fill['kode']); ?></td>
                                                <td><?php echo htmlspecialchars($fill['nama']); ?></td>
                                                <?php if ($chmod >= 3 || $_SESSION['jabatan'] == 'admin') { ?>
                                                    <td>
                                                        <button type="button" class="btn btn-success btn-xs" onclick="window.location.href='add_<?php echo $halaman; ?>?no=<?php echo $fill['no']; ?>'">Edit</button>
                                                        <?php if ($chmod >= 4 || $_SESSION['jabatan'] == 'admin') {
                                                            $delete_url = "component/delete/delete_master.php?no=" . $fill['no'] . "&forward=" . $forward . "&forwardpage=" . $forwardpage . "&chmod=" . $chmod;
                                                        ?>
                                                            <button type="button" class="btn btn-danger btn-xs" onclick="confirmDelete('<?php echo $delete_url; ?>')">Hapus</button>
                                                        <?php } ?>
                                                    </td>
                                                <?php } ?>
                                            </tr>
                                        <?php
                                            $i++;
                                            $count++;
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
                    <?php else : ?>
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

<!-- Scripts -->
<script src="dist/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="dist/bootstrap/js/bootstrap.min.js"></script>
<script src="dist/plugins/slimScroll/jquery.slimscroll.min.js"></script>
<script src="dist/plugins/fastclick/fastclick.js"></script>
<script src="dist/js/app.min.js"></script>
<script>
    // Fungsi untuk konfirmasi hapus
    function confirmDelete(url) {
        swal({
            title: "Anda Yakin?",
            text: "Setelah dihapus, data ini tidak dapat dikembalikan lagi!",
            icon: "warning",
            buttons: ["Batal", "Ya, Hapus!"],
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
                window.location.href = url;
            }
        });
    }
</script>
</body>
</html>
