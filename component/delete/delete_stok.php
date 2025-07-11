<?php
include "../../configuration/config_connect.php";
include "../../configuration/config_session.php";
include "../../configuration/config_chmod.php"; // Untuk mendapatkan variabel hak akses

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Ambil data dari URL dengan aman
$kegiatan    = isset($_GET['keg']) ? $_GET['keg'] : '';
$no          = isset($_GET['no']) ? (int)$_GET['no'] : 0;
$nota        = isset($_GET['nota']) ? mysqli_real_escape_string($conn, $_GET['nota']) : '';
$kode_barang = isset($_GET['barang']) ? mysqli_real_escape_string($conn, $_GET['barang']) : '';
$jumlah      = isset($_GET['jumlah']) ? (int)$_GET['jumlah'] : 0;
$forwardpage = isset($_GET['forwardpage']) ? $_GET['forwardpage'] : 'index.php';

// Validasi dasar, jika ada parameter yang hilang, hentikan proses
if (empty($no) || empty($nota) || empty($kode_barang) || $jumlah <= 0 || empty($kegiatan)) {
    header("Location: ../../" . $forwardpage);
    exit();
}

// Cek hak akses menggunakan variabel dari session dan config_chmod.php
// Asumsi $chmenu5 adalah hak akses untuk menu stok
if ($chmenu5 >= 4 || $_SESSION['jabatan'] == 'admin') {

    // Ambil data stok saat ini dari tabel barang
    $sql_get_stok = "SELECT sisa, terbeli, terjual FROM barang WHERE kode='$kode_barang'";
    $res_get_stok = mysqli_query($conn, $sql_get_stok);
    
    if ($row = mysqli_fetch_assoc($res_get_stok)) {
        
        $tabel_daftar = '';
        $sql_update_stok = '';

        // Tentukan logika berdasarkan jenis kegiatan (stok masuk atau keluar)
        if($kegiatan == 'in'){
            // Logika untuk membatalkan STOK MASUK (mengurangi stok)
            $stok_baru = (int)$row['sisa'] - $jumlah;
            $terbeli_baru = (int)$row['terbeli'] - $jumlah;
            $sql_update_stok = "UPDATE barang SET sisa='$stok_baru', terbeli='$terbeli_baru' WHERE kode='$kode_barang'";
            $tabel_daftar = "stok_masuk_daftar";

        } elseif($kegiatan == 'out'){
            // Logika untuk membatalkan STOK KELUAR (menambah stok kembali)
            $stok_baru = (int)$row['sisa'] + $jumlah;
            $terjual_baru = (int)$row['terjual'] - $jumlah;
            $sql_update_stok = "UPDATE barang SET sisa='$stok_baru', terjual='$terjual_baru' WHERE kode='$kode_barang'";
            $tabel_daftar = "stok_keluar_daftar";
        }

        // Lanjutkan hanya jika logika kegiatan cocok
        if (!empty($sql_update_stok) && !empty($tabel_daftar)) {
            // 1. Kembalikan stok di tabel 'barang'
            if (mysqli_query($conn, $sql_update_stok)) {
                // 2. Hapus item dari daftar sementara (stok_keluar_daftar)
                $sql_delete_item = "DELETE FROM $tabel_daftar WHERE no=$no AND nota='$nota'";
                mysqli_query($conn, $sql_delete_item);

                // 3. Hapus catatan mutasi yang sesuai (seperti di kode asli Anda)
                $sql_delete_mutasi = "DELETE FROM mutasi WHERE kodebarang='$kode_barang' AND keterangan='$nota' AND kegiatan='$kegiatan'";
                mysqli_query($conn, $sql_delete_mutasi);
            }
        }
    }
}

// Arahkan kembali ke halaman stok keluar dengan nota yang sedang aktif
header("Location: ../../" . $forwardpage . "?nota=" . $nota);
exit();
?>
