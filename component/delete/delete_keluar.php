<?php
include "../../configuration/config_connect.php";
include "../../configuration/config_session.php";
include "../../configuration/config_chmod.php";
include "../../configuration/config_etc.php";
$forward =$_GET['forward'];
$no = $_GET['no'];
$nota = $_GET['nota'];
$jml = $_GET['jumlah'];
$kode = $_GET['kode'];
$chmod = $_GET['chmod'];
$forwardpage = $_GET['forwardpage'];
?>

<?php
if( $chmod == '4' || $chmod == '5' || $_SESSION['jabatan'] =='admin' || $_SESSION['jabatan'] == 'guru'){


$sqle3="SELECT * FROM barang where kode='$kode'";
  $hasile3=mysqli_query($conn,$sqle3);
  $row=mysqli_fetch_assoc($hasile3);
  $keluar=$row['terjual']-$jml;
  $stok=$row['sisa']+$jml;

  $sqla=mysqli_query($conn,"UPDATE barang SET sisa='$stok', terjual='$keluar' WHERE kode='$kode'");

  $sql = "delete from $forward where no='".$no."'";
 if (mysqli_query($conn, $sql)) {

  $sqlb=mysqli_num_rows(mysqli_query($conn,"SELECT * FROM stok_keluar_daftar WHERE nota='$nota'"));
  if($sqlb==0){

$sqlc=mysqli_query($conn,"DELETE FROM stok_keluar WHERE nota='$nota'");
$sqld=mysqli_query($conn,"DELETE FROM surat WHERE nota='$nota'");
  }

 ?>



  <body onload="setTimeout(function() { document.frm1.submit() }, 10)">
  <form action="<?php echo $baseurl; ?>/<?php echo $forwardpage;?>?nota=<?php echo $nota;?>" name="frm1" method="post">

  <input type="hidden" name="hapusberhasil" value="1" />

<?php
 } else{
 ?>   <body onload="setTimeout(function() { document.frm1.submit() }, 10)">
	  <input type="hidden" name="hapusberhasil" value="2" />
 <?php
 }
}
else{

 ?>
  <body onload="setTimeout(function() { document.frm1.submit() }, 10)">
   <form action="<?php echo $baseurl; ?>/<?php echo $forwardpage;?>?nota=<?php echo $nota;?>" name="frm1" method="post">


	  <input type="hidden" name="hapusberhasil" value="2" />
 <?php
 }
?>
<table width="100%" align="center" cellspacing="0">
  <tr>
    <td height="500px" align="center" valign="middle"><img src="../../dist/img/load.gif">
  </tr>
</table>


   </form>
<meta http-equiv="refresh" content="10;url=jump?forward=<?php echo $forward.'&';?>forwardpage=<?php echo $forwardpage.'&'; ?>chmod=<?php echo $chmod; ?>">
</body>
