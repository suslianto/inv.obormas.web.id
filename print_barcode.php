
 
<?php
include  "configuration/config_barcode.php"; // include php barcode 128 class
include "configuration/config_connect.php";
 
$kolom = $_POST['kolom'];  // jumlah kolom
 
if ($_POST['jumlah'] ==""){
	$copy = "1";
} else {
	$copy = $_POST['jumlah'];
} // jumlah copy barcode

$barcode = $_POST['barcode'];
$kode = $_POST['kode'];
$counter = 1;
// sql query ke database
$sql_barcode = "SELECT * FROM barang WHERE kode='$kode' ";
$baca_barcode = mysqli_query($conn, $sql_barcode) or die ("Gagal Query".mysqli_error());
$data_barcode = mysqli_fetch_array($baca_barcode);
//menampilkan hasil generate barcode
echo"
<table cellpadding='10'>";
for ($ucopy=1; $ucopy<=$copy; $ucopy++) {
if (($counter-1) % $kolom == '0') { echo "
<tr>"; }
echo"
<td class='merk'>".substr($data_barcode['nama'],0,20)."";
echo bar128(stripslashes($_POST['barcode']));
echo "<?php echo $barcode;?>"; "</td>
";
if ($counter % $kolom == '0') { echo "</tr>
"; }
$counter++;
}
echo "</table>
";
?>
<script>

       //   setTimeout(function(){window.print()}, 2000);
           </script>