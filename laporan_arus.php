<!DOCTYPE html>
<html>

 <script src="dist/plugins/chartjs/Chart.bundle.js"></script>
       
<script type="text/javascript" src="libs/chartjs/chartjs-plugin-colorschemes.js"></script>

<?php
include "configuration/config_etc.php";
include "configuration/config_include.php";
include "configuration/config_alltotal.php";
etc();encryption();session();connect();head();body();timing();
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

<?php
$decimal ="0";
$a_decimal =",";
$thousand =".";
?>
            <div class="content-wrapper">
                <section class="content-header">
</section>
                <section class="content">
                 
                    <div class="row">
            <div class="col-lg-12">

              <!-- SETTING START-->

<?php
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
include "configuration/config_chmod.php";
$halaman = "report_operasi"; // halaman
$dataapa = "Operasional"; // data
$tabeldatabase = "operasional"; // tabel database
$chmod = $chmenu9; // Hak akses Menu
$forward = mysqli_real_escape_string($conn, $tabeldatabase); // tabel database
$forwardpage = mysqli_real_escape_string($conn, $halaman); // halaman


 $m=mysqli_fetch_assoc(mysqli_query($conn,"SELECT mode FROM backset"));
 $mode=$m['mode'];
?>

<!-- SETTING STOP -->

<textarea id="printing-css" style="display:none;">.no-print{display:none}</textarea>
<iframe id="printing-frame" name="print_frame" src="about:blank" style="display:none;"></iframe>
<script type="text/javascript">
function printDiv(elementId) {
 var a = document.getElementById('printing-css').value;
 var b = document.getElementById(elementId).innerHTML;
 window.frames["print_frame"].document.title = document.title;
 window.frames["print_frame"].document.body.innerHTML = '<style>' + a + '</style>' + b;
 window.frames["print_frame"].window.focus();
 window.frames["print_frame"].window.print();
}
</script>

<!-- BREADCRUMB -->


<!-- BOX HAPUS BERHASIL -->

         <script>
 window.setTimeout(function() {
    $("#myAlert").fadeTo(500, 0).slideUp(1000, function(){
        $(this).remove();
    });
}, 5000);
</script>


       <!-- BOX INFORMASI -->
    <?php
if ($chmod == '1' || $chmod == '2' || $chmod == '3' || $chmod == '4' || $chmod == '5' || $_SESSION['jabatan'] == 'admin') {?>


<section class="col-lg-12 connectedSortable">
 <div class="box">
       
        <div class="box-body">
          

            <div class="box-body">
          <form method="get" action="">
        

             
            <div class="col-sm-1">
              
            </div>

            <div class="col-sm-1">
             <label>Dari</label>
                  
            </div>

            <div class="col-sm-2">
              
                   <input type="text" class="form-control" id="datepicker" name="dari" autocomplete="off" placeholder="Dari">
            </div>

<div class="col-sm-1">
             <label>Sampai</label>
                  
            </div>

            <div class="col-sm-2">
              
                   <input type="text" class="form-control" id="datepicker2" name="sampai">
            </div>

               <div class="col-sm-3">
              
                   <button type="submit" name="find" class="btn bg-maroon">Filter</button>
            </div>

</form>
        
        </div>





        </div>

                                <!-- /.box-body -->
                            </div>
</section>





<?php 
 if(isset($_GET["find"])){
  if($_SERVER["REQUEST_METHOD"] == "GET"){


$dr = $_GET['dari'];
$sam = $_GET['sampai'];

      

if($dr!=''){

   $dari=date("d-m-Y",strtotime($dr));
} else {
  $dari="Awal";
}


 $sampe=date("d-m-Y",strtotime($sam));



?>



<section class="col-lg-12 connectedSortable">


<?php if($mode>=1){

$qw=mysqli_fetch_assoc(mysqli_query($conn,"SELECT SUM(total) as total, SUM(modal) as modal, SUM(total-modal) as profit FROM stok_keluar WHERE tgl BETWEEN '" . $dr . "' AND  '" . $sam . "' "));
  ?>

<div class="box">

<div class="box-body">
<table class="table table-bordered">
<tr>
  <th>Total Penjualan</th>
  <th>Harga Pokok Penjualan</th>
  <th>Perkiraan Laba</th>
</tr>

<tr>
  <td><?php echo number_format($qw['total']);?></td>
    <td><?php echo number_format($qw['modal']);?></td>
    <td><b><?php echo number_format($qw['profit']);?></b></td>
  </tr>
</table>

</div>

</div>

<?php } ?>


<div class="box" id="tabel1">
            <div class="box-header with-border">
              <h3 class="box-title">Data Stok Keluar dan Masuk Periode: <?php echo $dari;?> - <?php echo $sampe;?></h3>
             
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table class="table table-bordered" >
                <tr>
                  <th style="width: 10px">#</th>
                  <th>Nama Barang</th>
                  <th style="width: 10%">Masuk</th>
                  <th style="width: 10%">Keluar</th>
                 
                  
                </tr>
  
  <?php  

$sql1 = "SELECT * FROM barang ORDER BY no desc ";

$hasil1 = mysqli_query($conn,$sql1);

$no_urut=0;
while ($fill = mysqli_fetch_assoc($hasil1)){ ?>

                <tr>
                  <td><?php echo ++$no_urut;?></td>
                  <td><?php  echo mysqli_real_escape_string($conn, $fill['nama']); ?></td>
                  <td>
                  <?php  
  $kd=$fill['kode'];  
  $a=mysqli_fetch_assoc(mysqli_query($conn, "SELECT stok_masuk.tgl as tgl, stok_masuk_daftar.kode_barang as brg, SUM(stok_masuk_daftar.jumlah) as masuk FROM stok_masuk INNER JOIN stok_masuk_daftar ON stok_masuk_daftar.nota=stok_masuk.nota WHERE stok_masuk_daftar.kode_barang='$kd' AND tgl BETWEEN '" . $dr . "' AND  '" . $sam . "' "));

echo $a['masuk']+0;


?>
                  </td>
                  <td>
                    
<?php  
  $kd=$fill['kode'];  
  $b=mysqli_fetch_assoc(mysqli_query($conn, "SELECT stok_keluar.tgl as tgl, stok_keluar_daftar.kode_barang as brg, SUM(stok_keluar_daftar.jumlah) as keluar FROM stok_keluar INNER JOIN stok_keluar_daftar ON stok_keluar_daftar.nota=stok_keluar.nota WHERE stok_keluar_daftar.kode_barang='$kd' AND tgl BETWEEN '" . $dr . "' AND  '" . $sam . "' "));

echo $b['keluar']+0;


?>

                  </td>
                  
                  
                </tr>


<?php } ?>
              </table>
            </div>
            <!-- /.box-body -->
            



          </div>


</section>



<?php } } ?>





  <div align="right"  style="padding-right:15px"  class="no-print" id="no-print" >
             <div align="left" class="no-print" id="no-print">
               <a onclick="window.location.href='configuration/config_export?forward=perubahanstok&search=&dari=<?php echo $dr; ?>&sampai=<?php echo $sam; ?>'" class="btn btn-default btn-flat" name="cetak" value="export excel"><span class="glyphicon glyphicon-save-file"></span></a></div> <br/>
             </div>

<?php } else {
?>
   <div class="callout callout-danger">
    <h4>Info</h4>
    <b>Hanya user tertentu yang dapat mengakses halaman <?php echo $dataapa;?> ini .</b>
    </div>
    <?php
}
?>

                    <!-- /.row -->
                    <!-- Main row -->
                    <div class="row">
                    </div>
                    <!-- /.row (main row) -->
                </section>
                <!-- /.content -->
            </div>
            <!-- /.content-wrapper -->
           <?php footer();?>
            <div class="control-sidebar-bg"></div>
        </div>
        <!-- ./wrapper -->
<script src="dist/plugins/jQuery/jquery-2.2.3.min.js"></script>
      <script src="1-11-4-jquery-ui.min.js"></script>
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


  <!-- ChartJS 1.0.1 -->
<script src="dist/plugins/chartjs/Chart.min.js"></script>



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
