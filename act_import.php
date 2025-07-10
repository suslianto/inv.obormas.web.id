<?php
/*
-- Source Code from My Notes Code (www.mynotescode.com)
--
-- Follow Us on Social Media
-- Facebook : http://facebook.com/mynotescode
-- Twitter  : http://twitter.com/mynotescode
-- Google+  : http://plus.google.com/118319575543333993544
--
-- Terimakasih telah mengunjungi blog kami.
-- Jangan lupa untuk Like dan Share catatan-catatan yang ada di blog kami.
*/
session_start();

           $usr=$_SESSION['nama'];
            $now=date('Y-m-d');
            $jam=date('H:i');

// Load file koneksi.php
include "configuration/config_connect.php";

if(isset($_POST['import'])){ // Jika user mengklik tombol Import
	// Load librari PHPExcel nya
	require_once 'PHPExcel/PHPExcel.php';

	$inputFileType = 'CSV';
	$inputFileName = 'tmp/data.csv';

	$reader = PHPExcel_IOFactory::createReader($inputFileType);
	$excel = $reader->load($inputFileName);

	$numrow = 1;
	$worksheet = $excel->getActiveSheet();
	foreach ($worksheet->getRowIterator() as $row) {
		// Cek $numrow apakah lebih dari 1
		// Artinya karena baris pertama adalah nama-nama kolom
		// Jadi dilewat saja, tidak usah diimport
		if($numrow > 1){
			// START -->
			// Skrip untuk mengambil value nya
			$cellIterator = $row->getCellIterator();
			$cellIterator->setIterateOnlyExistingCells(false); // Loop all cells, even if it is not set

			$get = array(); // Valuenya akan di simpan kedalam array,dimulai dari index ke 0
			foreach ($cellIterator as $cell) {
				array_push($get, $cell->getValue()); // Menambahkan value ke variabel array $get
			}
			// <-- END

			// Ambil data value yang telah di ambil dan dimasukkan ke variabel $get
							$no = $get[0]; // Ambil data kode
                             $kode = sprintf("%04s", $no);
                            $sku = $get[1]; // Ambil data nama
                            $nama = $get[2]; // Ambil data hbeli
                          
                            $kategori = $get[3]; // Ambil data NIS
                             $satuan = $get[4]; // Ambil data NIS
                             $brand = $get[5]; // Ambil data NIS
                              $hbeli=0;
                            $hjual=0;
                            $terjual = $get[6]; // Ambil data nama
                            $terbeli = $get[7]; // Ambil data jenis kelamin
                            $sisa = $get[8]; // Ambil data telepon
                           $stokmin=$get[9];
                           
                            $barcode= $get[10]; // Ambil data nama
                            
                              $rak = $get[11]; // Ambil data NIS
                                $exp = $get[12]; // Ambil data NIS
                                  $warna = $get[13]; // Ambil data NIS
                                    $ukuran = $get[14]; // Ambil data NIS
                            $keterangan = $get[15]; // Ambil data jenis kelamin
                            $avatar = "dist/upload/index.jpg"; // Ambil data jenis kelamin



			// Cek jika semua data tidak diisi
							if($sku == "" && $nama == "" && $satuan == "" && $hbeli == "" && $hjual == "" && $stokmin == "" && $kategori == "" && $sisa == "" && $no == "" && $avatar == "")
								continue; // Lewat data pada baris ini (masuk ke looping selanjutnya / baris selanjutnya)

			// Tambahkan value yang akan di insert ke variabel $query
			// Buat query Insert
			$query = "INSERT INTO barang VALUES('".$kode."','".$sku."','".$nama."','".$hbeli."','".$hjual."','".$keterangan."','".$kategori."','".$satuan."','".$terjual."','".$terbeli."','".$sisa."','".$stokmin."','".$barcode."','".$brand."','".$rak."','".$exp."','".$warna."','".$ukuran."','".$avatar."','".$no."')";
			mysqli_query($conn, $query);
		}

 $sql4=mysqli_query($conn,"INSERT INTO mutasi VALUES('$usr','$now','$jam','$kode','$sisa','$sisa','menambah Produk via impor','$kode','','','berhasil')");

		$numrow++; // Tambah 1 setiap kali looping
	}
}

header('location: impor.php'); // Redirect ke halaman awal
?>
