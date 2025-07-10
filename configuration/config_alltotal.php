<?php
include 'config_connect.php';
date_default_timezone_set("Asia/Jakarta");
$harisekarang=date('d');
$bulansekarang=date('m');

$tahunsekarang=date('Y');
$now=date('Y-m-d');
$bulanlalu = date('m',strtotime("-1 month"));
$tahunlalu = date('Y',strtotime("-1 year"));
$today = date('d-m-Y : H:i');

// Total Data1

$sqlx2="SELECT COUNT(userna_me) as data FROM user";
$hasilx2=mysqli_query($conn,$sqlx2);
$row=mysqli_fetch_assoc($hasilx2);
$datax1=$row['data'];

// Total Data2

$sqlx2="SELECT COUNT(kode) as data FROM supplier";
$hasilx2=mysqli_query($conn,$sqlx2);
$row=mysqli_fetch_assoc($hasilx2);
$datax2=$row['data'];

// Total Data3

$sqlx2="SELECT COUNT(kode) as data FROM barang";
$hasilx2=mysqli_query($conn,$sqlx2);
$row=mysqli_fetch_assoc($hasilx2);
$datax3=$row['data'];

// Total Data4

 
$sql= "select COUNT(kode) as batas from barang where sisa <= stokmin order by kode";
$hasilx2=mysqli_query($conn,$sql);
$row=mysqli_fetch_assoc($hasilx2);
$alert = $row['batas'];


$sqlx2="SELECT COUNT(kode) as data FROM barang where sisa <= '$alert' ";
$hasilx2=mysqli_query($conn,$sqlx2);
$row=mysqli_fetch_assoc($hasilx2);
$datax4=$row['data'];


  
// Data Stok

$sqlx2="SELECT SUM(sisa) AS data FROM barang ";
  $hasilx2=mysqli_query($conn,$sqlx2);
  $row=mysqli_fetch_assoc($hasilx2);
  $stok1=$row['data'];

$sqlx2="SELECT SUM(terjual) AS data FROM barang ";
  $hasilx2=mysqli_query($conn,$sqlx2);
  $row=mysqli_fetch_assoc($hasilx2);
  $stok2=$row['data'];

$sqlx2="SELECT SUM(terbeli) AS data FROM barang ";
  $hasilx2=mysqli_query($conn,$sqlx2);
  $row=mysqli_fetch_assoc($hasilx2);
  $stok3=$row['data'];

$sqlx2="SELECT COUNT(kode) AS data FROM barang ";
  $hasilx2=mysqli_query($conn,$sqlx2);
  $row=mysqli_fetch_assoc($hasilx2);
  $stok4=$row['data'];





?>
