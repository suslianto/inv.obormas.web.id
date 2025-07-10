<?php
error_reporting(0);
include "configuration/config_etc.php";
include "configuration/config_include.php";
etc();session();connect();
?>
<html>
<head>
<title></title>


<style type="text/css">
    table { page-break-inside:auto }
    tr    { page-break-inside:avoid; page-break-after:auto }
    thead { display:table-header-group }
    tfoot { display:table-footer-group }

    #content {
  margin-left: 230px;
  padding: 20px 10px 0 0;
  margin-bottom:2px;
  border:1px solid #F95;
}
#content p {
  font-size: 85%;
  line-height: 1.8em;
  padding-left: 2em;
}

h2 {
  font:Verdana, Geneva, sans-serif;
  color:#000;
  background-color: transparent;
  border-bottom: 1px  #265180;
}
table {
  font-family:Verdana, Geneva, sans-serif; 
  font-size: 10pt;
  border-width: 1px;
  border-style: solid;
  border-color:#000;
  border-collapse: collapse;
  margin: 10px 0px;
}
th{
  color:#000;
  font-size: 7pt;
  text-transform: uppercase;
  padding: 0.5em;
  border-width: 1px;
  border-style: solid;
  border-color:#000;
  border-collapse: collapse;
  background-color:#FFF;
}



td{
  padding: 0.5em;
  vertical-align: top;
  border-width: 1px;
  border-style: solid;
  border-color: #000;
  border-collapse: collapse;
}

</style>



</head>


<?php

$nota=$_GET['nota'];

        $sql1="SELECT * FROM data";
        $hasil1=mysqli_query($conn,$sql1);
        $row=mysqli_fetch_assoc($hasil1);
        $nama=$row['nama'];
        $avatar=$row['avatar'];

 $sql1="SELECT * FROM stok_sesuai WHERE nota='$nota'";
        $hasil1=mysqli_query($conn,$sql1);
        $row=mysqli_fetch_assoc($hasil1);
        $oleh=$row['oleh'];
         $ket=$row['keterangan'];
        $tgl=date("d-m-Y", strtotime($row['tgl']));

?>
<body>




      <table width="100%">
        <tbody>

          <tr>
            <th width="596" align="center"><h3><?php echo $nama;?></h3></th>

            <th width="200" rowspan="2" scope="col"><img src="<?php echo $avatar;?>" width="180" height="80"></th>
            </tr>
      <tr>
        <th width="596" align="center"><h1>PENYESUAIAN STOK</h1></th>
      </tr>
    </tbody>
  </table>





  <table width="100%" border="0">
  <tbody>
    <tr>
      <th width="160" align="left">Petugas</th>
      <th width="2" scope="col">:</th>
    <th width="300" align="left"><?php echo $oleh;?></th>

      <th width="800" rowspan="3" scope="col" align="center"><?php echo $ket;?></th>
  </tr>
  <tr>
    <th align="left">Tanggal</th>
      <th width="2" scope="col">:</th>
<th width="300" align="left"><?php echo $tgl;?></th>
</tr>

<tr>
    <th align="left">No.Penyesuaian</th>
      <th width="2" scope="col">:</th>
<th width="300" align="left"><?php echo $nota;?></th>
</tr>

</tbody>
</table>
<br>
<table width="100%" border="0" bgcolor="#000000">
      <tbody>
        <tr bgcolor="#FFFFFF" height="40">

        <th width="1%" scope="col">No</th>
        <th width="3%" scope="col">SKU</th>
        <th width="10%" scope="col">Nama Barang</th>
        <th width="4%" scope="col">Satuan</th>
        <th width="4%" scope="col">Stok Tercatat</th>
        <th width="3%" scope="col">Stok Aktual</th>
        <th width="4%" scope="col">Koreksi</th>
        <th width="10%" scope="col">Keterangan</th>

      </tr>

  <?php

          $query1="SELECT * FROM stok_sesuai_daftar WHERE nota='$nota' order by no";
          $hasil = mysqli_query($conn,$query1);
          $no_urut=0;
          while ($fill = mysqli_fetch_assoc($hasil)){
            ?>

    <tr bgcolor="white">
              <td align="center"><?php echo ++$no_urut;?></td>
              <td align="center"><?php  $cba =$fill['kode_brg'];
        $r=mysqli_fetch_assoc(mysqli_query($conn,"SELECT sku,satuan FROM barang WHERE kode='$cba'"));
       echo mysqli_real_escape_string($conn, $r['sku']); ?>
                        </td>
             <td><?php  echo mysqli_real_escape_string($conn, $fill['nama']); ?></td>
        <td align="center"><?php echo mysqli_real_escape_string($conn, $r['satuan']); ?></td>
       <td align="center"><?php echo mysqli_real_escape_string($conn, $fill['sebelum']); ?></td>
         <td align="center"><?php echo mysqli_real_escape_string($conn, $fill['sesudah']); ?></td>
         <td align="center"><?php echo mysqli_real_escape_string($conn, $fill['selisih']); ?></td>
          <td align="center"><?php echo mysqli_real_escape_string($conn, $fill['catatan']); ?></td>
    </tr>
 <?php
            ;
          }

           ?>




          </tbody>
        </table>
   <br>


 <script>

          setTimeout(function(){window.print()}, 1000);
           </script>