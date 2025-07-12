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

// ===================================================================
// PENGATURAN HALAMAN
// ===================================================================
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
include "configuration/config_chmod.php";
$halaman       = "add_jabatan";
$halaman_edit  = "add_jabatan";
$dataapa       = "Jabatan";
$tabeldatabase = "jabatan";
$chmod         = $chmenu10;
$forward       = mysqli_real_escape_string($conn, $tabeldatabase);
$forwardpage   = mysqli_real_escape_string($conn, $halaman);
$no            = isset($_GET['no']) ? mysqli_real_escape_string($conn, $_GET['no']) : '';
$search        = isset($_POST['search']) ? mysqli_real_escape_string($conn, $_POST['search']) : '';
$alert_script  = ""; // Variabel untuk menyimpan skrip SweetAlert

// ===================================================================
// FUNGSI BANTUAN (AUTO NUMBER)
// ===================================================================
function autoNumber() {
    global $conn, $tabeldatabase;
    $query    = "SELECT MAX(CAST(SUBSTRING(kode, 2) AS UNSIGNED)) as max_id FROM $tabeldatabase WHERE kode LIKE 'J%'";
    $result   = mysqli_query($conn, $query);
    $data     = mysqli_fetch_array($result);
    $id_max   = $data['max_id'];
    $sort_num = ($id_max === null) ? 1 : $id_max + 1;
    return sprintf("J%04d", $sort_num);
}

// ===================================================================
// PROSES FORM (INSERT/UPDATE)
// ===================================================================
if (isset($_POST['simpan'])) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $kode   = mysqli_real_escape_string($conn, $_POST["kode"]);
        $nama   = mysqli_real_escape_string($conn, $_POST["nama"]);
        $insert = mysqli_real_escape_string($conn, $_POST["insert"]);

        if ($insert == '3') { // Mode Update
            if ($chmod >= 3 || $_SESSION['jabatan'] == 'admin') {
                $sql_update = "UPDATE $tabeldatabase SET nama='$nama' WHERE kode='$kode'";
                if (mysqli_query($conn, $sql_update)) {
                    $alert_script = "swal('Berhasil!', 'Data telah diupdate!', 'success').then(function(){ window.location = 'add_jabatan'; });";
                } else {
                    $alert_script = "swal('Gagal!', 'Gagal mengupdate data. Error: " . addslashes(mysqli_error($conn)) . "', 'error');";
                }
            } else {
                $alert_script = "swal('Akses Ditolak!', 'Anda tidak memiliki izin untuk mengupdate data!', 'error');";
            }
        } else { // Mode Insert
            if ($chmod >= 2 || $_SESSION['jabatan'] == 'admin') {
                $sql_check = "SELECT kode FROM $tabeldatabase WHERE kode='$kode'";
                if (mysqli_num_rows(mysqli_query($conn, $sql_check)) > 0) {
                    $alert_script = "swal('Gagal!', 'Kode jabatan sudah ada.', 'error');";
                } else {
                    $sql_insert = "INSERT INTO $tabeldatabase (kode, nama) VALUES ('$kode', '$nama')";
                    if (mysqli_query($conn, $sql_insert)) {
                        $alert_script = "swal('Berhasil!', 'Data telah disimpan!', 'success').then(function(){ window.location = 'jabatan'; });";
                    } else {
                        $alert_script = "swal('Gagal!', 'Data gagal disimpan. Error: " . addslashes(mysqli_error($conn)) . "', 'error');";
                    }
                }
            } else {
                $alert_script = "swal('Akses Ditolak!', 'Anda tidak memiliki izin untuk menambah data!', 'error');";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Manajemen Jabatan</title>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
</head>
<body class="hold-transition skin-purple sidebar-mini">
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
                        <li><a href="<?php echo $_SESSION['baseurl']; ?>">Dashboard</a></li>
                        <li class="active"><?php echo $dataapa; ?></li>
                    </ol>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <?php if ($chmod >= 2 || $_SESSION['jabatan'] == 'admin') : ?>
                        <?php
                        // Ambil data untuk form edit
                        $kode_form = autoNumber();
                        $nama_form = "";
                        $insert_mode = '1';

                        if (!empty($no) && ($chmod >= 3 || $_SESSION['jabatan'] == 'admin')) {
                            $sql_edit = "SELECT * FROM $tabeldatabase WHERE no='$no'";
                            $hasil_edit = mysqli_query($conn, $sql_edit);
                            if ($fill = mysqli_fetch_assoc($hasil_edit)) {
                                $kode_form = $fill["kode"];
                                $nama_form = $fill["nama"];
                                $insert_mode = '3';
                            }
                        }
                        ?>
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <h3 class="box-title"><?php echo !empty($no) ? 'Edit' : 'Tambah'; ?> <?php echo $dataapa; ?></h3>
                            </div>
                            <form class="form-horizontal" method="post" action="">
                                <div class="box-body">
                                    <div class="form-group">
                                        <label for="kode" class="col-sm-3 control-label">Kode</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="kode" name="kode" value="<?php echo htmlspecialchars($kode_form); ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="nama" class="col-sm-3 control-label">Nama Jabatan</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="nama" name="nama" value="<?php echo htmlspecialchars($nama_form); ?>" placeholder="Masukan Nama Jabatan" required>
                                        </div>
                                    </div>
                                    <input type="hidden" name="insert" value="<?php echo $insert_mode; ?>">
                                </div>
                                <div class="box-footer">
                                    <button type="submit" class="btn btn-primary btn-flat" name="simpan"><i class="fa fa-save"></i> Simpan</button>
                                    <a href="<?php echo $halaman; ?>" class="btn btn-danger btn-flat"><i class="fa fa-close"></i> Batal</a>
                                </div>
                            </form>
                        </div>
                    <?php else : ?>
                        <div class="callout callout-danger">
                            <h4>Akses Ditolak!</h4>
                            <p>Anda tidak memiliki izin untuk mengakses halaman ini.</p>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="col-lg-6">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Daftar <?php echo $dataapa; ?></h3>
                            <div class="box-tools pull-right">
                                <form method="post" action="">
                                    <div class="input-group input-group-sm" style="width: 200px;">
                                        <input type="text" name="search" class="form-control" placeholder="Cari..." value="<?php echo htmlspecialchars($search); ?>">
                                        <div class="input-group-btn">
                                            <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="box-body table-responsive no-padding">
                            <table class="table table-hover table-bordered">
                                <thead>
                                    <tr>
                                        <th style="width:50px;">No</th>
                                        <th>Kode Jabatan</th>
                                        <th>Nama Jabatan</th>
                                        <?php if ($chmod >= 3 || $_SESSION['jabatan'] == 'admin') { ?>
                                            <th style="width:120px;">Opsi</th>
                                        <?php } ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $rpp = 5;
                                    $reload = "$halaman?pagination=true";
                                    $page = intval(isset($_GET["page"]) ? $_GET["page"] : 1);
                                    if ($page <= 0) $page = 1;

                                    $sql_where = "";
                                    if (!empty($search)) {
                                        $sql_where = " WHERE kode LIKE '%$search%' OR nama LIKE '%$search%'";
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

                                    if (mysqli_num_rows($result) > 0) {
                                        while ($fill = mysqli_fetch_assoc($result)) {
                                    ?>
                                        <tr>
                                            <td><?php echo ++$no_urut; ?></td>
                                            <td><?php echo htmlspecialchars($fill['kode']); ?></td>
                                            <td><?php echo htmlspecialchars($fill['nama']); ?></td>
                                            <?php if ($chmod >= 3 || $_SESSION['jabatan'] == 'admin') { ?>
                                                <td>
                                                    <?php if ($fill['nama'] != 'admin') { ?>
                                                        <button type="button" class="btn btn-success btn-xs" onclick="window.location.href='<?php echo $halaman_edit;?>?no=<?php  echo $fill['no']; ?>'">Edit</button>
                                                        <a href="set_chmod?no=<?php echo htmlspecialchars($fill['nama']); ?>" class="btn btn-primary btn-xs">Hak Akses</a>
                                                        <?php if ($chmod >= 4) {
                                                            $delete_url = "component/delete/delete_master.php?no=" . $fill['no'] . "&forward=" . $forward . "&forwardpage=" . $halaman . "&chmod=" . $chmod;
                                                        ?>
                                                            <button type="button" class="btn btn-danger btn-xs" onclick="confirmDelete('<?php echo $delete_url; ?>')"><i class="fa fa-trash"></i></button>
                                                        <?php } ?>
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
                </div>
            </div>
        </section>
    </div>
    <?php footer(); ?>
    <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->
<script src="dist/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="dist/bootstrap/js/bootstrap.min.js"></script>
<script src="dist/plugins/slimScroll/jquery.slimscroll.min.js"></script>
<script src="dist/plugins/fastclick/fastclick.js"></script>
<script src="dist/js/app.min.js"></script>
<script>
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

    <?php if (!empty($alert_script)) : ?>
        document.addEventListener("DOMContentLoaded", function() {
            <?php echo $alert_script; ?>
        });
    <?php endif; ?>
</script>
</body>
</html>
