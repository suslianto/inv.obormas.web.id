<!DOCTYPE html>
<html>
  <title>Dashboard | INV OME</title>
<?php
include "configuration/config_include.php";
include "configuration/config_alltotal.php";
include "configuration/config_connect.php"
;encryption();session();connect();head();body();timing();
//pagination();
?>
<?php
        $decimal ="0";
        $a_decimal =",";
        $thousand =".";
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
                <!-- Content Header (Page header) -->
                <section class="content-header">
</section>
                <!-- Main content -->
                <section class="content">
                    <!-- Small boxes (Stat box) -->
                    <div class="row">
                        <!-- ./col -->

<!-- SETTING START-->

<?php
//error_reporting(E_ALL ^ (E_NOTICE | E_WARNING) );
$halaman = "index"; // halaman
$dataapa = "Dashboard"; // data
$tabeldatabase = "index"; // tabel database
$forward = mysqli_real_escape_string($conn, $tabeldatabase); // tabel database
$forwardpage = mysqli_real_escape_string($conn, $halaman); // halaman
//$search = $_POST['search'];
?>

<!-- SETTING STOP -->


<!-- BREADCRUMB -->
<div class="col-lg-12">
<ol class="breadcrumb ">
<li><a href="#">Dashboard </a></li><span class="badge bg-light-blue pull-right"> <?php echo $today;?> </span>
</ol>
</div>

<!-- BREADCRUMB -->




                                <!-- /.box-body -->

                        <!-- ./col -->

                </div>

<?php if($_SESSION['jabatan'] !='admin'){}else{ ?>

 <div class="row">
<?php

error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
$alert = $_GET['alert'];

$sql1="SELECT url FROM backset";
        $hasil1=mysqli_query($conn,$sql1);
        $row=mysqli_fetch_assoc($hasil1);
        $url=$row['url'];
if ($alert == 1 && $url =='http://idwares.esy.es'){
?>


<div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-ban"></i> Peringatan!</h4>
                Url Aplikasi belum disesuaikan dengan url anda sekarang. Klik Tombol pengaturan dibawah untuk menyesuaikan dengan url dimana anda menggunakan aplikasi. <br>
                <button type="button" class="btn btn-success btn btn-xs" data-toggle="modal" data-target="#modal-default">
                Pengaturan
              </button>
              </div>

   <?php } else {?>            
                   

                         <div class="col-lg-3 col-xs-6">
                           <!-- small box -->
                           <div class="small-box bg-aqua">
                               <div class="inner">
                                   <h3><?php echo $datax1; ?></h3>
                                   <p>Karyawan</p>
                               </div>
                               <div class="icon">
                                   <i class="ion ion-person"></i>
                               </div>
                                 <a href="admin" class="small-box-footer">Info lengkap <i class="fa fa-arrow-circle-right"></i></a>
                           </div>
                       </div>

                       <div class="col-lg-3 col-xs-6">
                         <!-- small box -->
                         <div class="small-box bg-green">
                             <div class="inner">
                                 <h3><?php echo $datax2; ?></h3>
                                 <p>Supplier</p>
                             </div>
                             <div class="icon">
                                 <i class="ion ion-person"></i>
                             </div>
                               <a href="supplier" class="small-box-footer">Info lengkap <i class="fa fa-arrow-circle-right"></i></a>
                         </div>
                     </div>

                     <div class="col-lg-3 col-xs-6">
                       <!-- small box -->
                       <div class="small-box bg-yellow">
                           <div class="inner">
                               <h3><?php echo $datax3; ?></h3>
                               <p>Barang</p>
                           </div>
                           <div class="icon">
                               <i class="glyphicon glyphicon-blackboard"></i>
                           </div>
                             <a href="barang" class="small-box-footer">Info lengkap <i class="fa fa-arrow-circle-right"></i></a>
                       </div>
                   </div>

                   <div class="col-lg-3 col-xs-6">
                     <!-- small box -->
                     <div class="small-box bg-red">
                         <div class="inner">
                             <h3><?php echo $datax4; ?></h3>
                             <p>Barang dg Stok dibawah <?php echo $alert; ?></p>
                         </div>
                         <div class="icon">
                             <i class="glyphicon glyphicon-folder-close"></i>
                         </div>
                           <a href="stok_menipis" class="small-box-footer">Info lengkap <i class="fa fa-arrow-circle-right"></i></a>
                     </div>
                 </div>



                     </div>

<?php } } ?>


                <div class="row">
                <?php if($_SESSION['jabatan'] !='admin'){}else{ ?>
                <div class="col-lg-6">
                 <div class="box box-default">
            <div class="box-header with-border">
              <h3 class="box-title">Berita Informasi</h3>
            </div>
                                <!-- /.box-header -->

                                <div class="box-body">
                <div class="table-responsive">
    <!----------------KONTEN------------------->
      <?php
 //   error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));

      $nama=$avatar=$tanggal=$isi="";
      if($_SERVER["REQUEST_METHOD"] == "POST"){
                  $nama = $_SESSION['nama'];
                  $avatar = $_SESSION['avatar'];
                  $tanggal = date('Y-m-d');
                  $isi= $_POST["isi"];


    }

         $sql="select * from info";
                  $hasil2 = mysqli_query($conn,$sql);


                  while ($fill = mysqli_fetch_assoc($hasil2)){

          $nama = $fill["nama"];
                  $avatar = $fill["avatar"];
                  $tanggal = $fill["tanggal"];
                  $isi= $fill["isi"];


    }
    ?>
  <div id="main">

   <div class="container-fluid">


  <form method="post" >


    <div class="form-group">
                <textarea class="textarea" name="isi" placeholder="<?php echo $isi;?>" style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;" value="<?php echo $ketentuane;?>"></textarea>

            </div>

  </div>

    <div class="col-sm-6" >
<br/>
    </div>
    <div class="col-sm-12" align="left">
  <button type="submit" class="btn btn-default btn-flat" name="simpan"><span class="glyphicon glyphicon-floppy-disk"></span> Simpan</button>
<br/>
    </div>




  </form>
</div>
<?php
  if($_SERVER["REQUEST_METHOD"] == "POST"){
            $id = 1;
          $nama=  $_SESSION['nama'];
                  $avatar= $_SESSION['avatar'];
                  $tanggal = date('Y-m-d');
                  $isi= $_POST["isi"];

                  if(isset($_POST['simpan'])){

           $sql="select * from info";
                  $result=mysqli_query($conn,$sql);

              if(mysqli_num_rows($result)>0){

           $sql1 = "update info set nama='$nama', avatar='$avatar',tanggal='$tanggal', isi='$isi' where id='1'";
             $result = mysqli_query($conn, $sql1);

        }else{
                $sql1 = "insert into info values('$nama','$tanggal','$isi','$avatar','$id')";
              $result = mysqli_query($conn, $sql1);
        }
          }
  }


         ?>



    <!-- KONTEN BODY AKHIR -->

                                </div>
                </div>

  <!-- TIMER -->
<div id="counter" style="display: none;">3</div>
<script type="text/javascript">
function countdown() {
    var i = document.getElementById('counter');
    if (parseInt(i.innerHTML)<=0) {
        $('#loading').hide();
      clearInterval(counter);
   resetEverything();
   recognition.stop();
    }
    i.innerHTML = parseInt(i.innerHTML)-1;

}
setInterval(function(){ countdown(); },1000);
</script>
<!-- /.TIMER -->
                                <!-- /.box-body -->

                  <div class="overlay" id="loading">  <i class="fa fa-refresh fa-spin"></i></div>

                            </div>
              </div>

              <?php } ?>
              <?php if($_SESSION['jabatan'] !='admin'){?>
              <div class="col-md-12">
               <?php
//    error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));

      $nama=$avatar=$tanggal=$isi="";
      if($_SERVER["REQUEST_METHOD"] == "POST"){
                  $nama = $_SESSION['nama'];
                  $avatar = $_SESSION['avatar'];
                  $tanggal = date('Y-m-d');
                  $isi= $_POST["isi"];


    }

         $sql="select * from info";
                  $hasil2 = mysqli_query($conn,$sql);


                  while ($fill = mysqli_fetch_assoc($hasil2)){

          $nama = $fill["nama"];
                  $avatar = $fill["avatar"];
                  $tanggal = $fill["tanggal"];
                  $isi= $fill["isi"];


    }
    ?>
              <?php
              }else{ ?>
                    <div class="col-md-6">

              <?php } ?>
          <!-- Box Comment -->
          <div class="box box-widget">
            <div class="box-header with-border">
              <div class="user-block">
                <img class="img-circle" src="<?php  echo $avatar; ?>" alt="User Image">
                <span class="username"><?php  echo $nama; ?></span>
                <span class="description"><?php echo $tanggal; ?></span>
              </div>
              <!-- /.user-block -->
              <div class="box-tools">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
              <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <!-- post text -->
              <?php echo $isi; ?>

            </div>
            <!-- /.box-body -->

          </div>
          <!-- /.box -->
        </div>


                </div>

<!-- Awal Chart  -->

<div class="row">
 <!-- Left col -->
        <section class="col-lg-7 connectedSortable">
          
          <div class="row">
        <div class="col-md-12">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">5 Barang dengan Stok paling banyak <span class="badge bg-green">dari  #<?php echo $stok1;?> di gudang</span></h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table class="table table-bordered">
                 
               <thead>
<?php
          $mySql  = "SELECT nama,sisa FROM barang ORDER BY sisa DESC LIMIT 5";
          $myQry  = mysqli_query($conn, $mySql)  or die ("Query  salah : ".mysqli_error());
          $nomor  = 0; 
          
            
          ?>
                <tr>
                  <th style="width: 10px">#</th>
                  <th>Barang</th>
                  <th>Stok</th>
                  <th style="width: 40px">Persentase</th>
                </tr>
                </thead>
         <?php       while ($kolomData = mysqli_fetch_array($myQry)) {
            $nomor++;  ?>
                <tbody>
                      <tr>
               <td><?php echo $nomor; ?></td>
              <td><?php echo $kolomData['nama']; ?></td>
              <td><?php echo $kolomData['sisa']; ?></td>
              <td><span class="badge bg-red"><?php echo round((($kolomData['sisa']/$stok1)*100),2); ?></span></td>
              
            </tr>
           </tbody>
           <?php } ?>
  

                 
              </table>
            </div>
            <!-- /.box-body -->
            
          </div>
          <!-- /.box -->
          <!-- /.box -->

        </section>
        <!-- /.Left col -->
        <!-- right col (We are only adding the ID to make the widgets sortable)-->
        <section class="col-lg-5 connectedSortable">

         <div class="row">
        <div class="col-md-12">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">5 Barang Keluar Terbanyak <span class="badge bg-red">dari  #<?php echo $stok2;?> keluar</span></h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table class="table table-bordered">
                 
               <thead>
<?php
          $mySql  = "SELECT nama,terjual FROM barang ORDER BY terjual DESC LIMIT 5";
          $myQry  = mysqli_query($conn, $mySql)  or die ("Query  salah : ".mysqli_error());
          $nomor  = 0; 
          
            
          ?>
                <tr>
                  <th style="width: 10px">#</th>
                  <th>Barang</th>
                  <th>Keluar</th>
                  <th style="width: 40px">Persentase</th>
                </tr>
                </thead>
         <?php       while ($kolomData = mysqli_fetch_array($myQry)) {
            $nomor++;  ?>
                <tbody>
                      <tr>
               <td><?php echo $nomor; ?></td>
              <td><?php echo $kolomData['nama']; ?></td>
              <td><?php echo $kolomData['terjual']; ?></td>
              <td><span class="badge bg-light-blue"><?php echo round((($kolomData['terjual']/$stok2)*100),2); ?></span></td>
              
            </tr>
           </tbody>
           <?php } ?>
  

                 
              </table>

              

            </div>
            <!-- /.box-body -->
           
          <!-- /.box -->
          <!-- /.box -->

        </section>
        <!-- right col -->

     
</div>

<!-- akhir chart -->
                                <!-- /.box-body -->
                            </div>

            <!-- BATAS -->



                    </div>
                    <!-- /.row -->
                    <!-- Main row -->
                    <div class="row">




                    </div>
                    <!-- /.row (main row) -->
                </section>
                <!-- /.content -->
            </div>








             <div class="modal fade" id="modal-default">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Url Aplikasi</h4>
              </div>

                <form method="post" >
              <div class="modal-body">
                <p> Url Aplikasi adalah alamat domain website/subdomain atau bisa berupa folder di localhost yang anda ketika pada address bar browser anda untuk mengakses aplikasi. Saat ini Url aplikasi seperti digambar, anda perlu menggantinya dengan milik anda sendiri.  <img src="dist/img/url.png"></p>
                <p>Anda wajib ganti URL Aplikasi agar bisa berjalan dengan baik</p>

                

                 <div class="form-group">
                  <label for="inputEmail3" class="col-sm-3 control-label">Url Aplikasi Baru</label>

                  <div class="col-sm-5">
                    <input type="text" class="form-control" name="url" placeholder="idwares.esy.es">
                  </div>
                </div>

              </div>


              <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                <button type="submit" name="save" class="btn btn-primary">Save changes</button>
              </div>
            </div>

          </form>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
 <?php


if(isset($_POST['save'])){
       if($_SERVER["REQUEST_METHOD"] == "POST"){

         $url = mysqli_real_escape_string($conn, $_POST['url']);

         $sqlu = "UPDATE backset SET url='$url' ";
         $query = mysqli_query($conn, $sqlu);


         if($query){
           echo "<script type='text/javascript'>  alert('Berhasil, Url Aplikasi telah diubah!'); </script>";
             echo "<script type='text/javascript'>window.location = 'index';</script>";
         }

       } }

        ?>


            <!-- /.content-wrapper -->
                   <?php footer();?>
            <div class="control-sidebar-bg"></div>
        </div>
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
        <script src="dist/js/pages/dashboard.js"></script>
        <script src="dist/js/demo.js"></script>
    <script src="dist/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="dist/plugins/datatables/dataTables.bootstrap.min.js"></script>
    <script src="dist/plugins/slimScroll/jquery.slimscroll.min.js"></script>
    <script src="dist/plugins/fastclick/fastclick.js"></script>

    </body>
</html>
