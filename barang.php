<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Barang | INV OME</title>
    <!-- Tambahkan pustaka SweetAlert -->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
</head>
<?php
include "configuration/config_etc.php";
include "configuration/config_include.php";
etc();encryption();session();connect();head();body();timing();
pagination();

if (!login_check()) {
?>
    <meta http-equiv="refresh" content="0; url=logout" />
<?php
    exit(0);
}
?>
<body class="hold-transition skin-purple sidebar-mini">
<div class="wrapper">
    <?php
    theader();
    menu();
    ?>
    <div class="content-wrapper">
        <section class="content-header">
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-lg-12">
                    <!-- SETTING START-->
                    <?php
                    error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
                    include "configuration/config_chmod.php";
                    $halaman = "barang"; // halaman
                    $dataapa = "Barang"; // data
                    $tabeldatabase = "barang"; // tabel database
                    $chmod = $chmenu4; // Hak akses Menu
                    $forward = mysqli_real_escape_string($conn, $tabeldatabase); // tabel database
                    $forwardpage = mysqli_real_escape_string($conn, $halaman); // halaman
                    $search = isset($_POST['search']) ? $_POST['search'] : '';
                    
                    $m=mysqli_fetch_assoc(mysqli_query($conn,"SELECT mode FROM backset"));
                    $mode=$m['mode'];
                    ?>
                    <!-- SETTING STOP -->

                    <!-- BREADCRUMB -->
                    <ol class="breadcrumb">
                        <li><a href="<?php echo $_SESSION['baseurl']; ?>">Dashboard </a></li>
                        <li><a href="<?php echo $halaman;?>"><?php echo $dataapa ?></a></li>
                        <li class="active"><?php echo !empty($search) ? "Hasil untuk: " . $search : "Data " . $dataapa; ?></li>
                    </ol>
                    <!-- BREADCRUMB -->

                    <?php if ($chmod >= 2 || $_SESSION['jabatan'] == 'admin') { ?>
                    <!-- KONTEN BODY AWAL -->
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">Daftar</i> <?php echo $dataapa; ?></h3>
                        </div>
                        <div class="box-body">
                            <p>
                                <a href="add_barang" class="btn bg-blue btn-sm" style="vertical-align: middle;"><i class="fa fa-plus"></i> Tambah</a>
                                <!-- <a href="<?php echo ($mode >= 1) ? 'impor_mode' : 'impor'; ?>" class="btn btn-primary btn-sm"><i class='fa fa-upload'></i> Import Data</a> -->
                                <a href="barang" class="btn btn-default btn-sm"><i class='fa fa-refresh'></i> Refresh</a>
                                <a href="barang?limit=true" class="btn btn-warning btn-sm"><i class='fa fa-line-chart'></i> Stok Limit</a>
                                <!-- <a href="barang?exp=true" class="btn btn-danger btn-sm"><i class='fa fa-calendar-times-o'></i> Expired</a> -->
                            </p>
                            <table class="table table-bordered table-hover" id="example2">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>SKU</th>
                                        <th style="width:200px">Nama Barang</th>
                                        <?php if($mode>=1){?>
                                        <th>Harga Beli</th>
                                        <th>Harga Jual</th>
                                        <?php } ?>
                                        <?php if(isset($_GET['limit'])){?>
                                        <th>Minimal</th>
                                        <?php } ?>
                                        <th>Kategori</th>
                                        <th>Lokasi</th>
                                        <th>Merek</th>
                                        <th>Satuan</th>
                                        <th>Warna</th>
                                        <!-- <th>Ukuran</th> -->
                                        <!-- <th>Expired</th> -->
                                        <th style="width:70px">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                $sql_query = "SELECT * FROM barang ORDER BY no";
                                if(isset($_GET['exp'])){
                                    $today = date('Y-m-d');
                                    $sql_query = "SELECT * FROM barang WHERE expired != '0000-00-00' AND expired <= '$today'";
                                } else if(isset($_GET['limit'])) {
                                    $sql_query = "SELECT * FROM barang WHERE sisa <= stokmin";
                                }
                                $sql = mysqli_query($conn, $sql_query);
                                while($fill = mysqli_fetch_assoc($sql)) {
                                ?>
                                    <tr>
                                        <td><?php echo $fill['kode']; ?></td>
                                        <td><?php echo $fill['sku']; ?></td>
                                        <td><?php echo $fill['nama']; ?></td>
                                        <?php if($mode>=1){ ?>
                                        <td><?php echo number_format($fill['hargabeli']); ?></td>
                                        <td><?php echo number_format($fill['hargajual']); ?></td>
                                        <?php } ?>
                                        <?php if(isset($_GET['limit'])){ ?>
                                        <td><?php echo number_format($fill['stokmin']); ?></td>
                                        <?php } ?>
                                        <td><?php echo $fill['kategori']; ?></td>
                                        <td><?php echo $fill['lokasi']; ?></td>
                                        <td><?php echo $fill['brand']; ?></td>
                                        <td><?php echo $fill['satuan']; ?></td>
                                        <td><?php echo $fill['warna']; ?></td>
                                        <!-- <td><?php echo $fill['ukuran']; ?></td> -->
                                        <!-- <td><?php echo ($fill['expired'] != '0000-00-00') ? $fill['expired'] : ''; ?></td> -->
                                        <td>
                                            <?php if ($chmod >= 3 || $_SESSION['jabatan'] == 'admin') { ?>
                                                <button type="button" class="btn btn-success btn-xs" onclick="window.location.href='add_<?php echo $halaman;?>?q=<?php echo $fill['no']; ?>'"><i class='fa fa-edit'></i></button>
                                            <?php } ?>
                                            <?php if ($chmod >= 4 || $_SESSION['jabatan'] == 'admin') { 
                                                $delete_url = "component/delete/delete_master.php?no=" . $fill['no'] . "&forward=" . $forward . "&forwardpage=" . $forwardpage . "&chmod=" . $chmod;
                                            ?>
                                                <button type="button" class="btn btn-danger btn-xs" onclick="confirmDelete('<?php echo $delete_url; ?>')"><i class='fa fa-trash'></i></button>
                                            <?php } ?>
                                            <?php if ($chmod >= 4 || $_SESSION['jabatan'] == 'admin') { ?>
                                                <button type="button" class="btn btn-info btn-xs" onclick="window.location.href='barang_detail?no=<?php echo $fill['no']?>'"><i class='fa fa-eye'></i></button>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php } else { ?>
                    <div class="callout callout-danger">
                        <h4>Info</h4>
                        <b>Hanya user tertentu yang dapat mengakses halaman <?php echo $dataapa;?> ini.</b>
                    </div>
                    <?php } ?>
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
<script src="dist/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="dist/plugins/datatables/dataTables.bootstrap.min.js"></script>
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
            buttons: true,
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
                window.location.href = url;
            }
        });
    }

    // Inisialisasi DataTable
    $(function () {
        $('#example2').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": true
        });
    });
</script>
</body>
</html>
