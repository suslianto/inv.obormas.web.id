<?php include 'config_connect.php';
$search = $_GET['search'];
$forward = $_GET['forward'];
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=$forward.xls");

?>
<?php if($forward == 'stokall'){ ?>
      <table class="table">
                                        <thead>
                                            <tr>
                                              <th>No</th>
                                              <th>SKU</th>
                                              <th>Nama</th>
                                              <th>Masuk</th>
                                              <th>Keluar</th>
                                              <th>Stok Sistem</th>
                                               <th>Stok Aktual</th>
                                            </tr>
                                        </thead>
										  <?php
	error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
					$query1="SELECT * FROM barang order by no desc";
				$hasil = mysqli_query($conn,$query1);
				$no = 1;
				while ($fill = mysqli_fetch_assoc($hasil)){
					?>
                     <tbody>
<tr>
  <td><?php echo ++$no_urut;?></td>
  <td><?php  $cba =$fill['kode'];
        $r=mysqli_fetch_assoc(mysqli_query($conn,"SELECT sku FROM barang WHERE kode='$cba'"));
       echo mysqli_real_escape_string($conn, $r['sku']); ?>
                        </td>
  <td><?php  echo mysqli_real_escape_string($conn, $fill['nama']); ?></td>
 

  <td><?php  
  $kd=$fill['kode'];  
  $a=mysqli_fetch_assoc(mysqli_query($conn, "SELECT stok_masuk.tgl as tgl, stok_masuk_daftar.kode_barang as brg, SUM(stok_masuk_daftar.jumlah) as masuk FROM stok_masuk INNER JOIN stok_masuk_daftar ON stok_masuk_daftar.nota=stok_masuk.nota WHERE stok_masuk_daftar.kode_barang='$kd' "));

echo $a['masuk']+0;


?>


  </td>
 <td><?php  
  $kd=$fill['kode'];  
  $b=mysqli_fetch_assoc(mysqli_query($conn, "SELECT stok_keluar.tgl as tgl, stok_keluar_daftar.kode_barang as brg, SUM(stok_keluar_daftar.jumlah) as keluar FROM stok_keluar INNER JOIN stok_keluar_daftar ON stok_keluar_daftar.nota=stok_keluar.nota WHERE stok_keluar_daftar.kode_barang='$kd'"));

echo $b['keluar']+0;


?>


  </td>
  <td><?php  echo mysqli_real_escape_string($conn, $a['masuk']-$b['keluar']); ?></td>
   <td><?php  echo mysqli_real_escape_string($conn, $fill['sisa']); ?></td>

 </tr><?php
					;
				}

				?>
                  </tbody></table>
<?php } ?>


<?php if($forward == 'barang'){ ?>
      <table class="table">
                                        <thead>
                                            <tr>
                                              <th>No</th>
                                              <th>Kode Barang</th>
                                              <th>Nama Barang</th>
                                              <th>Kategori</th>
                                              <th>Stok Terjual</th>
                                              <th>Stok Terbeli</th>
                                              <th>Stok Tersedia</th>
                                              
                                            </tr>
                                        </thead>
										  <?php
	error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));

  $query1="select * from $forward where kode like '%$search%' or nama like '%$search%' or kategori like '%$search%' order by no";
				$hasil = mysqli_query($conn,$query1);
				$no = 1;
				while ($fill = mysqli_fetch_assoc( $hasil)){
					?>
                     <tbody>
<tr>
  <td><?php echo ++$no_urut;?></td>
  <td><?php  echo mysqli_real_escape_string($conn, $fill['kode']); ?></td>
  <td><?php  echo mysqli_real_escape_string($conn, $fill['nama']); ?></td>
  <td><?php  echo mysqli_real_escape_string($conn, $fill['kategori']); ?></td>
  <td><?php  echo mysqli_real_escape_string($conn, $fill['terjual']); ?></td>
  <td><?php  echo mysqli_real_escape_string($conn, $fill['terbeli']); ?></td>
  <td><?php  echo mysqli_real_escape_string($conn, $fill['sisa']); ?></td>
  
					  </tr><?php
					;
				}

				?>
                  </tbody></table>
<?php } ?>


<?php if($forward == 'mutasi'){ ?>
      <table class="table">
                                        <thead>
                                            <tr>
                                              <th>No</th>
                                              <th>Nama User</th>
                                              <th>Tanggal</th>
                                              <th>Barang</th>
                                              <th>Kegiatan</th>
                                              <th>Jumlah</th>
                                              <th>Stok Akhir</th>
                                              <th>Status</th>
                                              <th>Keterangan</th>
                                            </tr>
                                        </thead>
										  <?php
	error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
  $query1    = "select mutasi.namauser,mutasi.tgl,mutasi.kodebarang,mutasi.status,mutasi.jumlah,mutasi.sisa,mutasi.kegiatan,mutasi.keterangan,barang.nama from mutasi inner join barang on mutasi.kodebarang=barang.kode order by mutasi.no desc";
				$hasil = mysqli_query($conn,$query1);
				$no = 1;
				while ($fill = mysqli_fetch_assoc($hasil)){
					?>
                     <tbody>
<tr>
  <td><?php echo ++$no_urut;?></td>
  <td><?php  echo mysqli_real_escape_string($conn, $fill['namauser']); ?></td>
  <td><?php  echo mysqli_real_escape_string($conn, $fill['tgl']); ?></td>
   <td><?php  echo mysqli_real_escape_string($conn, $fill['nama']); ?></td>
  <td><?php  echo mysqli_real_escape_string($conn, $fill['kegiatan']); ?></td>
  <td><?php  echo mysqli_real_escape_string($conn, $fill['jumlah']); ?></td>
  <td><?php  echo mysqli_real_escape_string($conn, $fill['sisa']); ?></td>
  <td><?php  echo mysqli_real_escape_string($conn, $fill['status']); ?></td>
  <td><?php  echo mysqli_real_escape_string($conn, $fill['keterangan']); ?></td>
					  </tr><?php
					;
				}

				?>
                  </tbody></table>
<?php } ?>


<?php if($forward == 'perubahanstok'){

  $forward = 'barang';
  $dr = $_GET['dari'];
  $sam = $_GET['sampai'];


      

if($dr!=''){

   $dari=date("d-m-Y",strtotime($dr));
} else {
  $dari="Awal";
}


 $sampe=date("d-m-Y",strtotime($sam));

  ?>

      <table class="table">
                                        <thead>
                                            <tr>
                                              <th>No</th>
                                              <th>Dari</th>
                                              <th>Sampai</th>
                                              <th>Nama Barang</th>
                                              <th>Masuk</th>
                                              <th>Keluar</th>
                                             
                                            </tr>
                                        </thead>
										  <?php
	error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));

  $query1="SELECT * FROM  $forward order by no desc";

				$hasil = mysqli_query($conn,$query1);
				$no = 1;
				while ($fill = mysqli_fetch_assoc($hasil)){
					?>
                     <tbody>
 <tr>
                  <td><?php echo ++$no_urut;?></td>
                   <td><?php echo $dari;?></td>
                    <td><?php echo $sampe;?></td>
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
                  
                  
                </tr><?php
					;
				}

				?>
                  </tbody></table>
<?php } ?>


<?php if($forward == 'income'){

  $forward = 'bayar';
  $bulan = $_GET['bulan'];
  $tahun = $_GET['tahun'];

  ?>

      <table class="table">
                                        <thead>
                                            <tr>
                                              <th>No</th>
                                              <th>No Nota</th>
                                              <th>Tanggal</th>
                                              <th>Jumlah Item</th>
                                              <th>Total Masuk</th>
                                              <th>Total Keluar</th>
                                              <th>Income</th>
                                              <th>Cc</th>
                                            </tr>
                                        </thead>
										  <?php
	error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
if($tahun == null || $tahun == ""){
  $query1="SELECT * FROM  $forward where nota IN (SELECT nota FROM transaksimasuk) order by no ";
}else{
  $query1="SELECT * FROM  $forward where nota IN (SELECT nota FROM transaksimasuk) and tglbayar like '$tahun-$bulan-%' order by no ";
}
				$hasil = mysqli_query($conn,$query1);
				$no = 1;
				while ($fill = mysqli_fetch_assoc($hasil)){
					?>
                     <tbody>
<tr>
  <td><?php echo ++$no_urut;?></td>
  <td><?php  echo mysqli_real_escape_string($conn, $fill['nota']); ?></td>
  <td><?php  echo mysqli_real_escape_string($conn, $fill['tglbayar']); ?></td>
  <?php
$nota = $fill['nota'];
$sqle="SELECT COUNT( nota ) AS data FROM transaksimasuk WHERE nota ='$nota'";
$hasile=mysqli_query($conn,$sqle);
$rowa=mysqli_fetch_assoc($hasile);
$jumlahbayar=$rowa['data'];
   ?>
  <td><?php  echo mysqli_real_escape_string($conn, $jumlahbayar); ?></td>
  <td><?php  echo mysqli_real_escape_string($conn, $fill['total']); ?></td>
  <td><?php  echo mysqli_real_escape_string($conn, $fill['keluar']); ?></td>
  <td><?php  echo mysqli_real_escape_string($conn, $fill['total']-$fill['keluar']); ?></td>
  <td><?php  echo mysqli_real_escape_string($conn, $fill['kasir']); ?></td>
  <td>
					  </tr><?php
					;
				}

				?>
                  </tbody></table>
<?php } ?>


<?php if($forward == 'operasional'){ ?>

      <table class="table">
                                        <thead>
                                            <tr>
                                              <th>No</th>
                                              <th>Kode</th>
                                              <th>Nama</th>
                                              <th>Tanggal</th>
                                              <th>Biaya</th>
                                              <th>Keterangan</th>
                                              <th>Cc</th>
                                            </tr>
                                        </thead>
										  <?php
	error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
if($tahun == null || $tahun == ""){
  $query1="SELECT * FROM  $forward order by no ";
}else{
  $query1="SELECT * FROM  $forward where tanggal like '$tahun-$bulan-%' order by no ";
}
				$hasil = mysqli_query($conn,$query1);
				$no = 1;
				while ($fill = mysqli_fetch_assoc($hasil)){
					?>
                     <tbody>
<tr>
  <td><?php echo ++$no_urut;?></td>
  <td><?php  echo mysqli_real_escape_string($conn, $fill['kode']); ?></td>
  <td><?php  echo mysqli_real_escape_string($conn, $fill['nama']); ?></td>
  <td><?php  echo mysqli_real_escape_string($conn, $fill['tanggal']); ?></td>
  <td><?php  echo mysqli_real_escape_string($conn, number_format($fill['biaya'])); ?></td>
  <td><?php  echo mysqli_real_escape_string($conn, $fill['keterangan']); ?></td>
  <td><?php  echo mysqli_real_escape_string($conn, $fill['kasir']); ?></td>
					  </tr><?php
					;
				}

				?>
                  </tbody></table>
<?php } ?>
