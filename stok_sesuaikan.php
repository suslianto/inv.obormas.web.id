<!DOCTYPE html>
<html>
<?php
include "configuration/config_etc.php";
include "configuration/config_include.php";

date_default_timezone_set("Asia/Jakarta");
$today=date('Y-m-d');

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
$halaman = "stok_sesuaikan"; // halaman
$dataapa = "Penyesuaian"; // data
$tabeldatabase = "stok_sesuai"; // tabel database
$chmod = $chmenu5; // Hak akses Menu
$forward = mysqli_real_escape_string($conn, $tabeldatabase); // tabel database
$forwardpage = mysqli_real_escape_string($conn, $halaman); // halaman
$search = $_POST['search'];
$insert = $_POST['insert'];
$tabel = "stok_sesuai_daftar";

 function autoNumber(){
  include "configuration/config_connect.php";
  global $forward;
  $query = "SELECT MAX(RIGHT(nota, 4)) as max_id FROM stok_sesuai ORDER BY nota";
  $result = mysqli_query($conn, $query);
  $data = mysqli_fetch_array($result);
  $id_max = $data['max_id'];
  $sort_num = (int) substr($id_max, 1, 4);
  $sort_num++;
  $new_code = sprintf("%04s", $sort_num);
  return $new_code;
 }

?>

<?php
$decimal ="0";
$a_decimal =",";
$thousand =".";
?>


<!-- SETTING STOP -->


<!-- BOX INSERT BERHASIL -->

         <script>
 window.setTimeout(function() {
    $("#myAlert").fadeTo(500, 0).slideUp(1000, function(){
        $(this).remove();
    });
}, 5000);
</script>
<?php
  if($insert == "10"){
    ?>
  <div id="myAlert" class="alert alert-success alert-dismissible fade in" role="alert">
   <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <strong> Berhasil!</strong> <?php echo $dataapa;?> telah berhasil <b>ditambahkan</b> ke Data <?php echo $dataapa;?>.
</div>

<?php
  }
  if($insert == "3"){
    ?>
  <div id="myAlert" class="alert alert-success alert-dismissible fade in" role="alert">
   <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <strong> Berhasil!</strong> <?php echo $dataapa;?> telah <b>terupdate</b>.
</div>

<!-- BOX UPDATE GAGAL -->
<?php
  }
  ?>

       <!-- BOX INFORMASI -->
    <?php
if ($chmod >= 2 || $_SESSION['jabatan'] == 'admin') {
  ?>


<?php

    if(isset($_POST["sesuai"])){
       if($_SERVER["REQUEST_METHOD"] == "POST"){
               $nota = mysqli_real_escape_string($conn, $_POST["nota"]);
                $kode = mysqli_real_escape_string($conn, $_POST["kode"]);
                 $nama = mysqli_real_escape_string($conn, $_POST["nama"]);
                  $stok = mysqli_real_escape_string($conn, $_POST["stok"]);
                  $tersedia = mysqli_real_escape_string($conn, $_POST["tersedia"]);
                   $ket = mysqli_real_escape_string($conn, $_POST["ket"]);
                  $kegiatan="Penyesuaian STOK";
                  $usr=$_SESSION['nama'];
                  $tgl=date('Y-m-d');


if($stok!=$tersedia){
                      $sql="select * from stok_sesuai_daftar where nota='$nota' and kode_brg='$kode'";
            $resulte=mysqli_query($conn,$sql);

                 if(mysqli_num_rows($resulte)>0){

                    echo "<script type='text/javascript'>  alert('Barang tersebut sudah disesuaikan, Silahkan batalkan dulu yang sebelumnya!');</script>";
                      echo "<script type='text/javascript'>window.location = '$halaman';</script>";

                 } else {

                  $selisih=$tersedia-$stok;

                  $q="INSERT INTO stok_sesuai_daftar VALUES('$nota','$kode','$nama','$stok','$tersedia','$selisih','$ket','')";

                  if(mysqli_query($conn,$q)){


                    $b=mysqli_query($conn,"UPDATE barang SET sisa='$tersedia' WHERE kode='$kode'");
                    $a=mysqli_query($conn,"INSERT INTO mutasi VALUES('$usr','$tgl','$kode','$tersedia','$selisih','$kegiatan','$nota','','pending')");

                     echo "<script type='text/javascript'>  alert('Berhasil, Stok Telah Disesuaikan!');</script>";
                      echo "<script type='text/javascript'>window.location = '$halaman';</script>";

                  } else {

                     echo "<script type='text/javascript'>  alert('Gagal Query, Periksa kembali atau hubungi admin!');</script>";
                      echo "<script type='text/javascript'>window.location = '$halaman';</script>";
                  }
                 }


} else {
   echo "<script type='text/javascript'>  alert('Jumlah Stok Tercatat dan Stok Aktual sudah sama!');</script>";
                      echo "<script type='text/javascript'>window.location = '$halaman';</script>";
}


                } } ?>






<?php

    if(isset($_POST["simpan"])){
       if($_SERVER["REQUEST_METHOD"] == "POST"){
               $nota = mysqli_real_escape_string($conn, $_POST["nota"]);
                $ket = mysqli_real_escape_string($conn, $_POST["keterangan"]);
                $date=date('Y-m-d');
                $usr=$_SESSION['nama'];

                  $q="INSERT INTO stok_sesuai VALUES('$nota','$date','$usr','$ket','')";

                  if(mysqli_query($conn,$q)){

                    $c=mysqli_query($conn, "UPDATE mutasi SET status='berhasil' WHERE kegiatan LIKE 'Penyesuaian STOK' AND keterangan='$nota'");

                  echo "<script type='text/javascript'>  alert('Berhasil, Data Penyesuaian Disimpan!');</script>";
                      echo "<script type='text/javascript'>window.location = '$halaman';</script>";

                  } else {

                     echo "<script type='text/javascript'>  alert('Gagal Menyimpan, Periksa kembali atau hubungi admin!');</script>";
                      echo "<script type='text/javascript'>window.location = '$halaman';</script>";
                  }


             } } ?>



  <!-- KONTEN BODY AWAL -->
                            <div class="box box-default">
            <div class="box-header with-border">
              <h3 class="box-title">Data <?php echo $dataapa;?></h3>
            </div>
                                <!-- /.box-header -->

                                <div class="box-body">
                <div class="table-responsive">
    <!----------------KONTEN------------------->
    
   


  <div id="main">
   <div class="container-fluid">

         
              <div class="box-body">

                <div class="row">

                  <div class="col-md-6">
                    <div class="box box-info">
                      <div class="box-body">



        <div class="row" >
           <div class="form-group col-md-12 col-xs-12" >
                  <label for="barang" class="col-sm-2 control-label">Pilih Barang:</label>
                  <div class="col-sm-10">
                    <select class="form-control select2" style="width: 100%;" name="barang" id="barang">
                      <option></option>

              <?php

            
              error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
        $sql=mysqli_query($conn,"select * from barang ");
        while ($row=mysqli_fetch_assoc($sql)){
          if ($barang==$row['kode'])
          echo "<option value='".$row['kode']."'  kode='".$row['kode']."' nama='".$row['nama']."' sisa='".$row['sisa']."' selected='selected'>".$row['sku']." | ".$row['nama']."</option>";
          else
          echo "<option value='".$row['kode']."' kode='".$row['kode']."' nama='".$row['nama']."' sisa='".$row['sisa']."' >".$row['sku']." | ".$row['nama']."</option>";
        }
     
      ?>


                    </select>
                  </div>
                </div>
        </div>


            </div>
          </div>
        </div>

        <div class="col-md-6">
          <div class="box box-danger">
            <div class="box-body">

              <div class="row" >
                 <div class="form-group col-md-12 col-xs-12" >

                     <div class="form-group col-md-12 col-xs-12" >
                  <label for="tglnota" class="col-sm-3 control-label">No.Transaksi:</label>
                  <div class="col-sm-9">
            
              <input type="text" class="form-control" id="nota" name="nota" value="<?php echo autoNumber(); ?>" maxlength="10" required readonly>
          </div>
        </div>
                       
                      </div>
              </div>


</div>
</div>
</div>

              </div>

              <div class="row">
                <div class="col-md-12">
                  <div class="box box-default">
                    <div class="box-body">
  
    <form method="post">
                  <div class="row" >
                    <?php
    error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));

    ?>

                      <div class="col-sm-4">
                      <label for="usr">Nama Barang</label>
                      <input type="text" class="form-control" id="nama" name="nama" value="<?php echo $nama; ?>" readonly>
                      <input type="hidden" class="form-control" id="nota" name="nota" value="<?php echo autoNumber(); ?>" readonly>
                      <input type="hidden" class="form-control" id="kode" name="kode" value="<?php echo $kode; ?>" readonly>
                    </div>

                    <div class="col-sm-2">
                    <label for="usr">Stok Tercatat</label>
                    <input type="text" class="form-control" id="stok" name="stok" readonly>
                  </div>



                  <div class="col-sm-2">
                  <label for="usr">Stok Aktual</label>
                  <input type="text" class="form-control" id="tersedia" name="tersedia">
                </div>

                   <div class="col-sm-2">
                    <label for="usr">Keterangan</label>
                    <input type="text" class="form-control" id="ket" name="ket">
                  </div>
               

              <div class="col-sm-2">
              <label for="usr">Aksi</label>
               <button type="submit" class="btn btn-block pull-left btn-flat btn-info" name="sesuai" >Sesuaikan</button>

            </div>



                  </div>
               
                </form>


</div>
</div>
                </div>
              </div>



              <div class="row">
                <div class="col-md-12">
                  <div class="box box-success">
                    <div class="box-header with-border">
             <b>Daftar Barang</b>
           </div>

           <?php
           error_reporting(E_ALL ^ E_DEPRECATED);

           $sql    = "select * from stok_sesuai_daftar where nota =".autoNumber()." order by no";
           $result = mysqli_query($conn, $sql);
           $rown=mysqli_num_rows($result);
           $rpp    = 15;
           $reload = "$halaman"."?pagination=true";
           $page   = intval(isset($_GET["page"]) ? $_GET["page"] : 0);



           if ($page <= 0)
           $page = 1;
           $tcount  = mysqli_num_rows($result);
           $tpages  = ($tcount) ? ceil($tcount / $rpp) : 1;
           $count   = 0;
           $i       = ($page - 1) * $rpp;
           $no_urut = ($page - 1) * $rpp;
           ?>
           <div class="box-body table-responsive">
              <table class="data table table-hover table-bordered">
                  <thead>
                      <tr>
                          <th>No</th>
                          <th>SKU</th>
                         
                          <th>Nama Barang</th>
                          <th>Stok Sebelumnya</th>
                          <th>Stok Penyesuaian</th>
                          <th>Selisih</th>
           <?php  if ($chmod >= 3 || $_SESSION['jabatan'] == 'admin') { ?>
                          <th>Opsi</th>
           <?php }else{} ?>
                      </tr>
                  </thead>
                    <?php
           error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
           while(($count<$rpp) && ($i<$tcount)) {
           mysqli_data_seek($result,$i);
           $fill = mysqli_fetch_array($result);
           ?>
           <tbody>
           <tr>
           <td><?php echo ++$no_urut;?></td>
           <td><?php  $cba =$fill['kode_brg'];
        $r=mysqli_fetch_assoc(mysqli_query($conn,"SELECT sku FROM barang WHERE kode='$cba'"));
       echo mysqli_real_escape_string($conn, $r['sku']); ?>
                        </td>

                         

           <td><?php  echo mysqli_real_escape_string($conn, $fill['nama']); ?></td>
          
           <td><?php  echo mysqli_real_escape_string($conn, number_format($fill['sebelum'])); ?></td>
           <td><?php  echo mysqli_real_escape_string($conn, number_format($fill['sesudah']) ); ?></td>
            <td><?php  echo mysqli_real_escape_string($conn, number_format($fill['selisih']) ); ?></td>
           <td>
           <?php  if ($chmod >= 4 || $_SESSION['jabatan'] == 'admin') { ?>
           <button type="button" class="btn btn-danger btn-xs" onclick="window.location.href='component/delete/delete_sesuai?kode=<?php echo $fill['kode_brg'].'&'; ?>&nota=<?php echo $fill['nota'].'&'; ?>no=<?php echo $fill['no'].'&'; ?>forward=<?php echo $tabel.'&';?>forwardpage=<?php echo "".$forwardpage.'&'; ?>sebelum=<?php echo $fill['sebelum'].'&';?>chmod=<?php echo $chmod; ?>'">BATAL</button>
           <?php } else {}?>
           </td></tr>
           <?php
           $i++;
           $count++;
           }

           ?>
           </tbody></table>
           <div align="right"><?php if($tcount>=$rpp){ echo paginate_one($reload, $page, $tpages);}else{} ?></div>


           </div>

           </div>


         </div>
                  </div>
                </div>

<form method="post">

                            <div class="row">
                              <div class="col-md-12">
                                <div class="box box-solid">
                                  <div class="box-header with-border">


                                    <div class="row" >
                                      

                                          

                                       <div class="form-group col-md-12 col-xs-12" >
                                              <label for="keterangan" class="col-sm-2 control-label">Catatan:</label>
                                              <div class="col-sm-10">
                                              <textarea class="col-sm-12" id="keterangan" name="keterangan" value="<?php echo $keterangan; ?>" maxlength="250"> </textarea>
                                            </div>
                                              
                                            </div>
                                    </div>



                                  </div>
                                </div>
                              </div>
                            </div>


                           
              <!-- /.box-body -->
              <div class="box-footer" >
                <div class="col-sm-12">

                  <?php if($rown>0){?>
                       <input type="hidden" class="form-control" id="nota" name="nota" value="<?php echo autoNumber(); ?>" readonly>
                <button type="submit" class="btn btn-block pull-left btn-flat btn-danger" name="simpan" ><span class="glyphicon glyphicon-floppy-disk"></span> Simpan</button>
              <?php } ?>
</div>
              </div>
              <!-- /.box-footer -->
</form>

 </form>
</div>
<script>
function myFunction() {
    document.getElementById("Myform").submit();
}
</script>

    <!-- KONTEN BODY AKHIR -->

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
<script src="dist/plugins/jQuery/jquery-2.2.3.min.js"></script>
        <script src="1-11-4-jquery-ui.min.js"></script>
        <script>
  $.widget.bridge('uibutton', $.ui.button);
</script>
<script>
$("#barang").on("change", function(){

  var nama = $("#barang option:selected").attr("nama");
  var kode = $("#barang option:selected").attr("kode");
  var sisa = $("#barang option:selected").attr("sisa");

  $("#nama").val(nama);
  
  $("#stok").val(sisa);
   $("#tersedia").val(sisa);
    $("#kode").val(kode);
});
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
<script>
  $(function () {
    //Initialize Select2 Elements
    $(".select2").select2();

    //Datemask dd/mm/yyyy
    $("#datemask").inputmask("yyyy-mm-dd", {"placeholder": "yyyy/mm/dd"});
    //Datemask2 mm/dd/yyyy
    $("#datemask2").inputmask("yyyy-mm-dd", {"placeholder": "yyyy/mm/dd"});
    //Money Euro
    $("[data-mask]").inputmask();

    //Date range picker
    $('#reservation').daterangepicker();
    //Date range picker with time picker
    $('#reservationtime').daterangepicker({timePicker: true, timePickerIncrement: 30, format: 'YYYY/MM/DD h:mm A'});
    //Date range as a button
    $('#daterange-btn').daterangepicker(
        {
          ranges: {
            'Hari Ini': [moment(), moment()],
            'Kemarin': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Akhir 7 Hari': [moment().subtract(6, 'days'), moment()],
            'Akhir 30 Hari': [moment().subtract(29, 'days'), moment()],
            'Bulan Ini': [moment().startOf('month'), moment().endOf('month')],
            'Akhir Bulan': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
          },
          startDate: moment().subtract(29, 'days'),
          endDate: moment()
        },
        function (start, end) {
          $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        }
    );

    //Date picker
    $('#datepicker').datepicker({
      autoclose: true
    });

   $('.datepicker').datepicker({
    dateFormat: 'yyyy-mm-dd'
 });

   //Date picker 2
   $('#datepicker2').datepicker('update', new Date());

    $('#datepicker2').datepicker({
      autoclose: true
    });

   $('.datepicker2').datepicker({
    dateFormat: 'yyyy-mm-dd'
 });


    //iCheck for checkbox and radio inputs
    $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
      checkboxClass: 'icheckbox_minimal-blue',
      radioClass: 'iradio_minimal-blue'
    });
    //Red color scheme for iCheck
    $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
      checkboxClass: 'icheckbox_minimal-red',
      radioClass: 'iradio_minimal-red'
    });
    //Flat red color scheme for iCheck
    $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
      checkboxClass: 'icheckbox_flat-green',
      radioClass: 'iradio_flat-green'
    });

    //Colorpicker
    $(".my-colorpicker1").colorpicker();
    //color picker with addon
    $(".my-colorpicker2").colorpicker();

    //Timepicker
    $(".timepicker").timepicker({
      showInputs: false
    });
  });
</script>
</body>
</html>
