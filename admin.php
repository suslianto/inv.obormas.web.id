<?php
// ===================================================================
// KONFIGURASI DAN INISIALISASI
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

// ===================================================================
// CEK STATUS LOGIN
// ===================================================================
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
    <title>Manajemen Admin</title>
    <!-- Tambahkan pustaka SweetAlert -->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <style>
        /* CSS untuk border dan alignment tabel */
        .table-bordered th,
        .table-bordered td {
            border: 1px solid #f4f4f4 !important; /* Border lebih tipis */
            text-align: center;
            vertical-align: middle !important; /* Rata tengah vertikal */
        }
        .table .user-block img {
            margin: auto; /* Memastikan gambar di tengah */
        }
    </style>
</head>
<body class="hold-transition skin-purple sidebar-mini">

<?php
// Tampilkan notifikasi SweetAlert berdasarkan status dari URL
if (isset($_GET['status'])) {
    $status = $_GET['status'];
    $dataapa_uc = "Admin";
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
                    $halaman = "admin";
                    $dataapa = "Admin";
                    $tabeldatabase = "user";
                    $chmod = $chmenu1;
                    $forward = mysqli_real_escape_string($conn, $tabeldatabase);
                    $forwardpage = mysqli_real_escape_string($conn, $halaman);
                    $search = isset($_POST['search']) ? $_POST['search'] : '';
                    ?>
                    <!-- SETTING STOP -->

                    <!-- BREADCRUMB -->
                    <ol class="breadcrumb">
                        <li><a href="<?php echo $_SESSION['baseurl']; ?>">Dashboard </a></li>
                        <li class="active"><?php echo $dataapa ?></li>
                    </ol>

                    <?php if ($chmod >= 1 || $_SESSION['jabatan'] == 'admin') : ?>
                        <?php
                        $sql_count = "SELECT COUNT(*) AS totaldata FROM $forward";
                        if (!empty($search)) {
                            $sql_count .= " WHERE userna_me LIKE '%$search%' OR nama LIKE '%$search%'";
                        }
                        $hasil_count = mysqli_query($conn, $sql_count);
                        $row_count = mysqli_fetch_assoc($hasil_count);
                        $totaldata = $row_count['totaldata'];
                        ?>
                        <div class="box">
                            <div class="box-header with-border">
                                <h3 class="box-title" style="vertical-align: middle; margin-right: 10px;"><?php echo $dataapa; ?> <span class="label label-default"><?php echo $totaldata; ?></span></h3>
                                <a href="add_admin" class="btn bg-blue btn-sm" style="vertical-align: middle;"><i class="fa fa-plus"></i> Tambah</a>
                                <div class="box-tools pull-right">
                                    <form method="post" action="">
                                        <div class="input-group input-group-sm" style="width: 250px;">
                                            <input type="text" name="search" class="form-control" placeholder="Cari..." value="<?php echo htmlspecialchars($search); ?>">
                                            <div class="input-group-btn">
                                                <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="box-body table-responsive">
                                <table class="table table-hover table-bordered">
                                    <thead>
                                        <tr>
                                            <th style="width:40px;">No</th>
                                            <th style="width:200px;">Nama</th>
                                            <th style="width:60px;">Foto</th>
                                            <?php if ($chmod >= 3 || $_SESSION['jabatan'] == 'admin') { ?>
                                                <th style="width:80px;">Opsi</th>
                                            <?php } ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $rpp = 15;
                                        $reload = "$halaman.php?pagination=true";
                                        $page = intval(isset($_GET["page"]) ? $_GET["page"] : 1);
                                        if ($page <= 0) $page = 1;

                                        $sql_where = "";
                                        if (!empty($search)) {
                                            $sql_where = " WHERE userna_me LIKE '%$search%' OR nama LIKE '%$search%'";
                                        }
                                        
                                        $tcount = $totaldata;
                                        $tpages = ($tcount) ? ceil($tcount / $rpp) : 1;
                                        $i = ($page - 1) * $rpp;
                                        $no_urut = ($page - 1) * $rpp;

                                        $sql_data = "SELECT * FROM $tabeldatabase" . $sql_where . " ORDER BY no DESC LIMIT $i, $rpp";
                                        $result = mysqli_query($conn, $sql_data);

                                        if (mysqli_num_rows($result) > 0) {
                                            while ($fill = mysqli_fetch_assoc($result)) {
                                        ?>
                                            <tr>
                                                <td><?php echo ++$no_urut; ?></td>
                                                <td><?php echo htmlspecialchars($fill['nama']); ?></td>
                                                <td>
                                                    <img class="img-circle" src="<?php echo htmlspecialchars($fill['avatar']); ?>" alt="User Image" style="width: 40px; height: 40px;">
                                                </td>
                                                <?php if ($chmod >= 3 || $_SESSION['jabatan'] == 'admin') { ?>
                                                    <td>
                                                        <button type="button" class="btn btn-success btn-xs" onclick="window.location.href='user_profil?no=<?php echo $fill['no']; ?>'"><i class="fa fa-pencil"></i></button>
                                                        <?php if ($chmod >= 4 || $_SESSION['jabatan'] == 'admin') {
                                                            $delete_url = "component/delete/delete_master.php?no=" . $fill['no'] . "&forward=" . $forward . "&forwardpage=" . $forwardpage . "&chmod=" . $chmod;
                                                        ?>
                                                            <button type="button" class="btn btn-danger btn-xs" onclick="confirmDelete('<?php echo $delete_url; ?>')"><i class="fa fa-trash"></i></button>
                                                        <?php } ?>
                                                    </td>
                                                <?php } ?>
                                            </tr>
                                        <?php
                                            }
                                        } else {
                                            echo "<tr><td colspan='4' class='text-center'>Tidak ada data ditemukan.</td></tr>";
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
