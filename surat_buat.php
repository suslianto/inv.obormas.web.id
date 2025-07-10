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
$halaman = "surat_buat"; // halaman
$dataapa = "Surat Jalan"; // data
$tabeldatabase = "surat"; // tabel database
$chmod = $chmenu5; // Hak akses Menu
$forward = mysqli_real_escape_string($conn, $tabeldatabase); // tabel database
$forwardpage = mysqli_real_escape_string($conn, $halaman); // halaman
$search = $_POST['search'];
$insert = $_POST['insert'];

$nota=$_GET['q'];
 
$sql=mysqli_query($conn, "SELECT keterangan FROM stok_keluar WHERE nota='$nota'");
$ai=mysqli_fetch_assoc($sql);

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
                         <div class="col-lg-6">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Form Surat Jalan</h3>

          
        </div>
        <div class="box-body">
         
<form action="" method="post">
                 
              <div class="row">
                
                <div class="col-xs-6">
                     <label>Nomor Surat</label>
                  <input type="text" class="form-control" value="SR<?php echo $nota;?>" name="nomor">
                   <input type="hidden" class="form-control" value="<?php echo $nota;?>" name="nota">
                </div>
                 
                <div class="col-xs-6">
                <label>Tanggal</label>
                  <input type="text" class="form-control" id="datepicker2" name="tgl">
                </div>
               
              </div>
<br>

                <div class="form-group">
                  <label>Pilih Tujuan</label>
                  <select class="form-control select2" style="width: 100%;" name="pilih" id="pilih">
                      <option selected="selected">Pilih Pelanggan</option>
              <?php
              error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
         $sql=mysqli_query($conn,"select * from pelanggan");
        while ($row=mysqli_fetch_assoc($sql)){
          if ($barcode==$row['barcode'])
          echo "<option value='".$row['kode']."' nama='".$row['nama']."' notelp='".$row['notelp']."' alamat='".$row['alamat']."' >".$row['kode']." | ".$row['nama']."</option>";
          else
          echo "<option value='".$row['kode']."' nama='".$row['nama']."' notelp='".$row['notelp']."' alamat='".$row['alamat']."' >".$row['kode']." | ".$row['nama']."</option>";
        }
      ?>
                    </select>
                </div>



                 <div class="form-group">
                  <label>Tujuan</label>
                  <input type="text" class="form-control" id="tujuan" name="tujuan">
                </div>

                 <div class="form-group">
                  <label>Nomor Telepon</label>
                  <input type="text" class="form-control" id="notelp" name="notelp">
                </div>

                 <div class="form-group">
                  <label>Alamat</label>
                  <textarea class="form-control" rows="3" id="alamat" placeholder="Alamat Lengkap" name="alamat"></textarea>
                </div>

                 <div class="form-group">
                  <label>Driver/Kurir</label>
                  <input type="text" class="form-control" name="driver">
                </div>

                 <div class="form-group">
                  <label>No.Hp Driver/Kurir</label>
                  <input type="text" class="form-control" name="nohp">
                </div>

                 <div class="form-group">
                  <label>Nomor Polisi Kendaraan</label>
                  <input type="text" class="form-control" placeholder="optional" name="nopol">
                </div>

                <div class="form-group">
                  <label>Keterangan</label>
                  <textarea class="form-control" rows="2" name="ket" ><?php echo $ai['keterangan'];?></textarea>
                </div>



                    <div class="box-footer">
               
                  <button type="submit" name="surat" class="btn btn-success pull-right col-lg-6 col-xs-6">Simpan</button>
              </div>


        </div>

                                <!-- /.box-body -->
                            </div>
                        </div>
</form>




<div class="col-lg-6">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Daftar Barang</h3>

         
        </div>
         <?php
           error_reporting(E_ALL ^ E_DEPRECATED);

           $sql    = "select * from stok_keluar_daftar where nota='$nota' order by no";
           $result = mysqli_query($conn, $sql);
           $rpp    = 30;
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
                          <th style="width:10px">No</th>
                          <th>Nama Barang</th>
                          <th style="width:10%">Jumlah </th>
                          
           
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

          
           <td><?php  echo mysqli_real_escape_string($conn, $fill['nama']); ?></td>
          
           <td><?php  echo mysqli_real_escape_string($conn, $fill['jumlah']); ?></td>
        
          </tr>
           <?php
           $i++;
           $count++;
           }

           ?>
           </tbody></table>
          <br>



                                <!-- /.box-body -->
                            </div>
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

    if(isset($_POST["surat"])){
       if($_SERVER["REQUEST_METHOD"] == "POST"){
               $nota = mysqli_real_escape_string($conn, $_POST["nota"]);
                $nomor = mysqli_real_escape_string($conn, $_POST["nomor"]);
                 $tgl = mysqli_real_escape_string($conn, $_POST["tgl"]);
                 $pilih = mysqli_real_escape_string($conn, $_POST["pilih"]);
                 $tujuan = mysqli_real_escape_string($conn, $_POST["tujuan"]);
                  $alamat = mysqli_real_escape_string($conn, $_POST["alamat"]);
                   $telp = mysqli_real_escape_string($conn, $_POST["notelp"]);
                    $driver = mysqli_real_escape_string($conn, $_POST["driver"]);
                     $nohp = mysqli_real_escape_string($conn, $_POST["nohp"]);
                      $nopol = mysqli_real_escape_string($conn, $_POST["nopol"]);
                      $ket = mysqli_real_escape_string($conn, $_POST["ket"]);
                      $keg="stok keluar";

                      $by=$_SESSION['nama'];


                         $sql="select * from surat where nota='$nota' or nosurat LIKE '%$nomor%'";
        $result=mysqli_query($conn,$sql);
              if(mysqli_num_rows($result)>0){

                 echo "<script type='text/javascript'>  alert('Gagal, Telah ada surat jalan dengan nomor atau id yang sama!'); </script>";
           echo "<script type='text/javascript'>window.location = 'surat_buat?q=$nota';</script>";
              } else {

        $sql2 = "insert into surat values( '$nota','$nomor','$tgl','$pilih','$tujuan','$telp','$alamat','$driver','$nohp','$nopol','$by','')";
           if(mysqli_query($conn, $sql2)){

            $a=mysqli_query($conn, "UPDATE stok_keluar SET pelanggan='$tujuan', keterangan='$ket' WHERE nota='$nota'");
              $a=mysqli_query($conn, "UPDATE mutasi SET tujuan='$tujuan'WHERE keterangan='$nota' and kegiatan='$keg'");



           echo "<script type='text/javascript'>  alert('Berhasil, Data Surat telah disimpan!'); </script>";
           echo "<script type='text/javascript'>window.location = 'surat_kelola';</script>";
         }else{
           echo "<script type='text/javascript'>  alert('Gagal, Data Surat gagal disimpan!'); </script>";
           echo "<script type='text/javascript'>window.location = 'surat_kelola';</script>";
         }

              }

               } } ?>
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
$("#pilih").on("change", function(){

  var tujuan = $("#pilih option:selected").attr("nama");
   var notelp = $("#pilih option:selected").attr("notelp");
    var alamat = $("#pilih option:selected").attr("alamat");
 

  $("#tujuan").val(tujuan);
   $("#notelp").val(notelp);
    $("#alamat").val(alamat);
  
});
</script>




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
<!-- Script -->
    <script type='text/javascript' >
    $( function() {
  
        $( "#barcode" ).autocomplete({
            source: function( request, response ) {
                
                $.ajax({
                    url: "server.php",
                    type: 'post',
                    dataType: "json",
                    data: {
                        search: request.term
                    },
                    success: function( data ) {
                        response( data );
                    }
                });
            },
            select: function (event, ui) {
                $('#nama').val(ui.item.label);
                $('#barcode').val(ui.item.value); // display the selected text
                $('#hargajual').val(ui.item.hjual);
                $('#stok').val(ui.item.sisa); // display the selected text
                $('#hargabeli').val(ui.item.hbeli);
                $('#jumlah').val(ui.item.jumlah);
                $('#kode').val(ui.item.kode); // save selected id to input
                return false;
                
            }
        });

        // Multiple select
        $( "#multi_autocomplete" ).autocomplete({
            source: function( request, response ) {
                
                var searchText = extractLast(request.term);
                $.ajax({
                    url: "server.php",
                    type: 'post',
                    dataType: "json",
                    data: {
                        search: searchText
                    },
                    success: function( data ) {
                        response( data );
                    }
                });
            },
            select: function( event, ui ) {
                var terms = split( $('#multi_autocomplete').val() );
                
                terms.pop();
                
                terms.push( ui.item.label );
                
                terms.push( "" );
                $('#multi_autocomplete').val(terms.join( ", " ));

                // Id
                var terms = split( $('#selectuser_ids').val() );
                
                terms.pop();
                
                terms.push( ui.item.value );
                
                terms.push( "" );
                $('#selectuser_ids').val(terms.join( ", " ));

                return false;
            }
           
        });
    });

    function split( val ) {
      return val.split( /,\s*/ );
    }
    function extractLast( term ) {
      return split( term ).pop();
    }

    </script>

<!--AUTO Complete-->

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
