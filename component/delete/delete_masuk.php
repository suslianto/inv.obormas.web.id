<?php
include "../../configuration/config_connect.php";
include "../../configuration/config_session.php";
include "../../configuration/config_chmod.php";

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Ambil data dari URL dengan aman
$no          = isset($_GET['no']) ? (int)$_GET['no'] : 0;
$nota        = isset($_GET['nota']) ? mysqli_real_escape_string($conn, $_GET['nota']) : '';
$kode_barang = isset($_GET['kode']) ? mysqli_real_escape_string($conn, $_GET['kode']) : '';
$jumlah      = isset($_GET['jumlah']) ? (int)$_GET['jumlah'] : 0;
$halaman_detail = "stok_masuk_detail.php"; // Halaman detail pembatalan
$halaman_daftar = "stok_masuk.php";     // Halaman daftar transaksi

// Validasi dasar, jika ada parameter yang hilang, hentikan proses
if (empty($no) || empty($nota) || empty($kode_barang) || $jumlah <= 0) {
    header("Location: ../../" . $halaman_daftar);
    exit();
}

// Cek hak akses
if ($chmenu5 >= 4 || $_SESSION['jabatan'] == 'admin') {

    // Ambil data stok saat ini
    $sql_get_stok = "SELECT sisa, terbeli FROM barang WHERE kode='$kode_barang'";
    $res_get_stok = mysqli_query($conn, $sql_get_stok);
    
    if ($row = mysqli_fetch_assoc($res_get_stok)) {
        
        // Kembalikan stok
        $stok_baru = (int)$row['sisa'] - $jumlah;
        $terbeli_baru = (int)$row['terbeli'] - $jumlah;
        $sql_update_stok = "UPDATE barang SET sisa='$stok_baru', terbeli='$terbeli_baru' WHERE kode='$kode_barang'";
        
        if (mysqli_query($conn, $sql_update_stok)) {
            // Hapus item dari daftar sementara
            $sql_delete_item = "DELETE FROM stok_masuk_daftar WHERE no=$no";
            mysqli_query($conn, $sql_delete_item);

            // Hapus mutasi terkait
            $sql_delete_mutasi = "DELETE FROM mutasi WHERE keterangan = '$nota' AND kodebarang = '$kode_barang'";
            mysqli_query($conn, $sql_delete_mutasi);

            // Cek apakah transaksi utama menjadi kosong
            $sql_check_empty = "SELECT COUNT(*) as total FROM stok_masuk_daftar WHERE nota='$nota'";
            $res_check_empty = mysqli_query($conn, $sql_check_empty);
            $row_check_empty = mysqli_fetch_assoc($res_check_empty);

            if($row_check_empty['total'] == 0){
                // Jika kosong, hapus transaksi utama dan arahkan ke halaman daftar
                mysqli_query($conn, "DELETE FROM stok_masuk WHERE nota='$nota'");
                $_SESSION['flash_message'] = ['type' => 'success', 'message' => 'Transaksi berhasil dibatalkan sepenuhnya.'];
                header("Location: ../../" . $halaman_daftar);
                exit();
            }

            // Jika masih ada item, kembali ke halaman detail
            $_SESSION['flash_message'] = ['type' => 'info', 'message' => 'Satu item telah berhasil dibatalkan.'];
            header("Location: ../../" . $halaman_detail . "?nota=" . $nota);
            exit();

        } else {
            $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Gagal mengembalikan stok barang!'];
        }
    } else {
        $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Barang tidak ditemukan.'];
    }
} else {
    $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Anda tidak memiliki izin untuk melakukan aksi ini.'];
}

// Arahkan kembali jika ada kegagalan
header("Location: ../../" . $halaman_detail . "?nota=" . $nota);
exit();
?>
