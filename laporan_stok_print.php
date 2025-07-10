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

        $sql1="SELECT * FROM data";
        $hasil1=mysqli_query($conn,$sql1);
        $row=mysqli_fetch_assoc($hasil1);
        $nama=$row['nama'];
        $avatar=$row['avatar'];
?>
<body>




      <table width="100%">
        <tbody>

          <tr>
            <th width="596" align="center"><h3><?php echo $nama;?></h3></th>

            <th width="200" rowspan="2" scope="col"><img src="<?php echo $avatar;?>" width="180" height="80"></th>
            </tr>
      <tr>
        <th width="596" align="center"><h1>LAPORAN STOK</h1></th>
      </tr>
    </tbody>
  </table>





  <table width="100%" border="0">
  <tbody>
    <tr>
      <th width="150" align="left">Di Cetak Oleh</th>
      <th width="2" scope="col">:</th>
    <th width="690" align="left"><?php echo $_SESSION['nama'];?></th>

     <th width="700" rowspan="2" scope="col" align="center">Gunakan fitur penyesuaian stok untuk membuat jurnal penyesuaian apabila ada selisih antara stok yang tercatat dengan stok aktual</th>

  </tr>
  <tr>
    <th align="left">Tanggal</th>
      <th width="2" scope="col">:</th>
<th width="690" align="left"><?php echo date('d-m-Y');?></th>
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
         <th width="4%" scope="col">Merk</th>
          <th width="4%" scope="col">Satuan</th>
        <th width="4%" scope="col">Jumlah Masuk</th>
        <th width="4%" scope="col">Jumlah Keluar</th>
        <th width="3%" scope="col">Stok Tercatat</th>
        <th width="4%" scope="col">Stok Aktual</th>
         <th width="4%" scope="col">Koreksi</th>

      </tr>

  <?php

          $query1="SELECT * FROM  barang order by no";
          $hasil = mysqli_query($conn,$query1);
          $no_urut=0;
          while ($fill = mysqli_fetch_assoc($hasil)){
            ?>


    <tr bgcolor="white">
              <td align="center"><?php echo ++$no_urut;?></td>

                <td align="center"><?php  $cba =$fill['kode'];
        $r=mysqli_fetch_assoc(mysqli_query($conn,"SELECT sku FROM barang WHERE kode='$cba'"));
       echo mysqli_real_escape_string($conn, $r['sku']); ?>
                        </td>

              <td><?php  echo mysqli_real_escape_string($conn, $fill['nama']); ?></td>
        <td align="center"><?php  echo mysqli_real_escape_string($conn, $fill['brand']); ?></td>
         <td align="center"><?php  echo mysqli_real_escape_string($conn, $fill['satuan']); ?></td>

        <td align="center">
          <?php  
  $kd=$fill['kode'];  
  $a=mysqli_fetch_assoc(mysqli_query($conn, "SELECT stok_masuk.tgl as tgl, stok_masuk_daftar.kode_barang as brg, SUM(stok_masuk_daftar.jumlah) as masuk FROM stok_masuk INNER JOIN stok_masuk_daftar ON stok_masuk_daftar.nota=stok_masuk.nota WHERE stok_masuk_daftar.kode_barang='$kd'"));

echo $a['masuk'];


?>         </td>


        <td align="center">
          <?php  
  $kd=$fill['kode'];  
  $b=mysqli_fetch_assoc(mysqli_query($conn, "SELECT stok_keluar.tgl as tgl, stok_keluar_daftar.kode_barang as brg, SUM(stok_keluar_daftar.jumlah) as keluar FROM stok_keluar INNER JOIN stok_keluar_daftar ON stok_keluar_daftar.nota=stok_keluar.nota WHERE stok_keluar_daftar.kode_barang='$kd' "));

echo $b['keluar'];

?>
        </td>
        <td align="center"><?php  echo mysqli_real_escape_string($conn, $a['masuk']-$b['keluar']); ?></td>
        <td align="center"><?php  echo mysqli_real_escape_string($conn, $fill['sisa']); ?></td>
       
           <td align="center"><?php  echo mysqli_real_escape_string($conn, ($fill['sisa'])-($a['masuk']-$b['keluar'])); ?></td>
     
    </tr>

     <?php
            ;
          }

           ?>


          </tbody>
        </table>
   <br>


