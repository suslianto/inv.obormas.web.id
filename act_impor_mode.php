<?php

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
                              $hbeli=$get[6];
                            $hjual=$get[7];
                            $terjual = $get[8]; // Ambil data nama
                            $terbeli = $get[9]; // Ambil data jenis kelamin
                            $sisa = $get[10]; // Ambil data telepon
                           $stokmin=$get[11];
                           
                            $barcode= $get[12]; // Ambil data nama
                            
                              $rak = $get[13]; // Ambil data NIS
                                $exp = $get[14]; // Ambil data NIS
                                  $warna = $get[15]; // Ambil data NIS
                                    $ukuran = $get[16]; // Ambil data NIS
                            $keterangan = $get[17]; // Ambil data jenis kelamin
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

header('location: impor_mode.php'); // Redirect ke halaman awal
?>
