<!DOCTYPE html>
<html>
<?php
include "configuration/config_etc.php";
include "configuration/config_include.php";
etc();encryption();session();connect();head();body();timing();
//alltotal();
pagination();
?>

<?php
if (!login_check()) {
?>
<meta http-equiv="refresh" content="0; url=logout" />
<?php
exit(0);
}
?>
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
                        <!-- ./col -->

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
$search = $_POST['search'];
$insert = $_POST['insert'];

 
?>


<!-- SETTING STOP -->


<!-- BREADCRUMB -->

<ol class="breadcrumb ">
<li><a href="<?php echo $_SESSION['baseurl']; ?>">Dashboard </a></li>
<li><a href="<?php echo $halaman;?>"><?php echo $dataapa ?></a></li>
<?php

if ($search != null || $search != "") {
?>
 <li> <a href="<?php echo $halaman;?>">Data <?php echo $dataapa ?></a></li>
  <li class="active"><?php
    echo $search;
?></li>
  <?php
} else {
?>
 <li class="active">Data <?php echo $dataapa ?></li>
  <?php
}
?>
</ol>

<!-- BREADCRUMB -->

<!-- BOX INSERT BERHASIL -->

         <script>
 window.setTimeout(function() {
    $("#myAlert").fadeTo(500, 0).slideUp(1000, function(){
        $(this).remove();
    });
}, 5000);
</script>


       <!-- BOX INFORMASI -->
    <?php
if ($chmod >= 2 || $_SESSION['jabatan'] == 'admin') {
  ?>


  <!-- KONTEN BODY AWAL -->
                         <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Modul Import Barang</h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                    title="Collapse">
              <i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="box-body">
          





              
        <!-- Content -->
        <div style="padding: 0 15px;">
            <?php 

            $cek=mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(kode) as cnt FROM barang"));
              
              if($cek['cnt']<=0){?>
                 <h4>Hanya Import File dengan Format CSV"</h4>
                 <br> 
             <button type="button" class="btn btn-success" onclick="window.location.href='impor_form_mode'">IMPORT DATA PRODUK</button>
          <?php } else {?>
              <h4>Anda tidak bisa import data karena data produk sudah ada, Klik "Reset Produk"</h4> 
              <br><form method="post">
                  <button type="button" class="btn bg-maroon" data-toggle="modal" data-target="#transaksi">RESET PRODUK</button>
               
             </form>
          <?php } ?>

            
            <hr>
            
            <!-- Buat sebuah div dan beri class table-responsive agar tabel jadi responsive -->
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tr>
                        <th>no</th>
                        <th>Kode</th>
                        <th>SKU</th>
                        <th>Nama</th>
                        
                        <th>kategori</th>
                        <th>Satuan</th>
                         <th>Merek</th>
                        <th>Harga Beli</th>
                        <th>Harga Jual</th>
                        <th>Stok Keluar</th>
                        <th>Stok Masuk</th>
                        <th>Stok Tersedia</th>
                        <th>Stok Minimal</th>
                        <th>kode Barcode</th>
                       <th>keterangan</th>
                       

                    </tr>
                    <?php
                    // Load file koneksi.php
                    include "configuration/config_connect.php";
                    
                    // Buat query untuk menampilkan semua data siswa
                    $sql = mysqli_query($conn, "SELECT * FROM barang order by no");
    
                    $no = 1; // Untuk penomoran tabel, di awal set dengan 1
                    while($data = mysqli_fetch_array($sql)){ // Ambil semua data dari hasil eksekusi $sql
                        echo "<tr>";
                        echo "<td>".$no."</td>";
                        echo "<td>".$data['kode']."</td>";
                         echo "<td>".$data['sku']."</td>";
                        echo "<td>".$data['nama']."</td>";
                        
                       
                        echo "<td>".$data['kategori']."</td>";
                        echo "<td>".$data['satuan']."</td>";
                        echo "<td>".$data['brand']."</td>";
                        echo "<td>".$data['hargabeli']."</td>";
                        echo "<td>".$data['hargajual']."</td>";
                        echo "<td>".$data['terjual']."</td>";
                        echo "<td>".$data['terbeli']."</td>";
                        echo "<td>".$data['sisa']."</td>";
                        echo "<td>".$data['stokmin']."</td>";
                        echo "<td>".$data['barcode']."</td>";
                        
                         echo "<td>".$data['keterangan']."</td>";
                       
                        echo "</tr>";
                        
                        $no++; // Tambah 1 setiap kali looping
                    }
                    ?>
                </table>
            </div>
        </div>
   





        </div>

                                <!-- /.box-body -->
                            </div>

                        </div>

                        
<?php
} else {
?>
   <div class="callout callout-danger">
    <h4>Info</h4>
    <b>Hanya user tertentu yang dapat mengakses halaman <?php echo $dataapa;?> ini .</b>
    </div>
    <?php
}
?>

 <?php 

 if(isset($_POST["reset"])){
       if($_SERVER["REQUEST_METHOD"] == "POST"){

$user = $_SESSION['username'];

$sql = "SELECT userna_me FROM user where userna_me = '$user' ";

$result=mysqli_query($conn,$sql);

                  if(mysqli_num_rows($result)>0){

$trun1 = mysqli_query($conn, 'TRUNCATE TABLE barang ');
$trun2 = mysqli_query($conn, 'TRUNCATE TABLE mutasi ');
$trun3 = mysqli_query($conn, 'TRUNCATE TABLE stok_keluar ');
$trun4 = mysqli_query($conn, 'TRUNCATE TABLE stok_masuk ');
$trun5 = mysqli_query($conn, 'TRUNCATE TABLE stok_keluar_daftar ');
$trun6 = mysqli_query($conn, 'TRUNCATE TABLE stok_masuk_daftar ');


if ($trun1){
   echo "<script type='text/javascript'>  alert('Berhasil, Data produk telah direset permanen!'); </script>";
              echo "<script type='text/javascript'>window.location = 'impor_mode';</script>";
   

} else {  echo "<script type='text/javascript'>  alert('GAGAL, Data produk gagal di reset seluruhnya. Terjadi kesalahan dalam proses reset. Ulangi lagi dan pastikan internet anda stabil');</script>";}

                    } else {
                        echo "<script type='text/javascript'>  alert('GAGAL, Data telah di RESET Sebelumnya dan belum ada perubahaan sejak itu!'); </script>";
              echo "<script type='text/javascript'>window.location = 'impor_mode';</script>";
                    }
} }


?>



<!-- Modal -->
<div id="transaksi" class="modal fade" role="dialog">
  <div class="modal-dialog">
<form method="post" action="">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">RESET DATA PRODUK</h4>
      </div>
      <div class="modal-body">
        <p><b>Anda yakin mau reset data produk?</b></p><br>
        <p>Tindakan ini permanen dan akan menghapus semua data produk pada seluruh cabang</p>
      </div>
      <div class="modal-footer">
         <button name="reset" type="submit" class="btn bg-maroon pull-left" >YA, Hapus Semua Produk</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
      </div>
    </div>
</form>
  </div>
</div>
                        <!-- ./col -->
                    </div>

                    <!-- /.row -->
                    <!-- Main row -->
                    <div class="row">
                        <!-- Left col -->
                        <!-- /.Left col -->
                    </div>
                    <!-- /.row (main row) -->
                </section>
                <!-- /.content -->
            </div>
            <!-- /.content-wrapper -->
            <?php  footer(); ?>
            <div class="control-sidebar-bg"></div>
        </div>
          <!-- ./wrapper -->

<!-- Script -->
    <script src='jquery-3.1.1.min.js' type='text/javascript'></script>

    <!-- jQuery UI -->
    <link href='jquery-ui.min.css' rel='stylesheet' type='text/css'>
    <script src='jquery-ui.min.js' type='text/javascript'></script>

<script src="dist/plugins/jQuery/jquery-2.2.3.min.js"></script>
        <script src="libs/1.11.4-jquery-ui.min.js"></script>

        <script>
  $.widget.bridge('uibutton', $.ui.button);
</script>
        <script src="dist/bootstrap/js/bootstrap.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
        <script src="dist/plugins/morris/morris.min.js"></script>
        <script src="dist/plugins/sparkline/jquery.sparkline.min.js"></script>
        <script src="dist/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
        <script src="dist/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
        <script src="dist/plugins/knob/jquery.knob.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
        <script src="dist/plugins/daterangepicker/daterangepicker.js"></script>
        <script src="dist/plugins/datepicker/bootstrap-datepicker.js"></script>
        <script src="dist/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
        <script src="dist/plugins/slimScroll/jquery.slimscroll.min.js"></script>
        <script src="dist/plugins/fastclick/fastclick.js"></script>
        <script src="dist/js/app.min.js"></script>
        <script src="dist/js/demo.js"></script>
    <script src="dist/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="dist/plugins/datatables/dataTables.bootstrap.min.js"></script>
    <script src="dist/plugins/slimScroll/jquery.slimscroll.min.js"></script>
    <script src="dist/plugins/fastclick/fastclick.js"></script>
    <script src="dist/plugins/select2/select2.full.min.js"></script>
    <script src="dist/plugins/input-mask/jquery.inputmask.js"></script>
    <script src="dist/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
    <script src="dist/plugins/input-mask/jquery.inputmask.extensions.js"></script>
    <script src="dist/plugins/timepicker/bootstrap-timepicker.min.js"></script>
    <script src="dist/plugins/iCheck/icheck.min.js"></script>

<!--fungsi AUTO Complete-->

</body>
</html>
