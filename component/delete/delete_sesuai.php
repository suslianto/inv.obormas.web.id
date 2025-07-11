<?php
include "../../configuration/config_connect.php";
include "../../configuration/config_session.php";
include "../../configuration/config_chmod.php"; // Diperlukan untuk hak akses

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Ambil data dari URL dengan aman
$no          = isset($_GET['no']) ? (int)$_GET['no'] : 0;
$nota        = isset($_GET['nota']) ? mysqli_real_escape_string($conn, $_GET['nota']) : '';
$kode_barang = isset($_GET['kode']) ? mysqli_real_escape_string($conn, $_GET['kode']) : '';
$stok_sebelum = isset($_GET['sebelum']) ? (int)$_GET['sebelum'] : 0;
$halaman_kembali = "stok_sesuaikan.php";

// Validasi dasar, jika ada parameter yang hilang, hentikan proses
if (empty($no) || empty($nota) || empty($kode_barang)) {
    header("Location: ../../" . $halaman_kembali . "?nota=" . $nota);
    exit();
}

// Cek hak akses (asumsi $chmenu5 adalah hak akses untuk menu ini)
if ($chmenu5 >= 4 || $_SESSION['jabatan'] == 'admin') {

    // 1. Kembalikan stok di tabel 'barang' ke jumlah semula
    $sql_update_stok = "UPDATE barang SET sisa = '$stok_sebelum' WHERE kode = '$kode_barang'";
    
    if (mysqli_query($conn, $sql_update_stok)) {
        // 2. Hapus item dari daftar penyesuaian sementara
        $sql_delete_item = "DELETE FROM stok_sesuai_daftar WHERE no = $no";
        mysqli_query($conn, $sql_delete_item);

        // 3. Hapus catatan mutasi yang terkait
        $sql_delete_mutasi = "DELETE FROM mutasi WHERE keterangan = '$nota' AND kodebarang = '$kode_barang' AND kegiatan = 'Penyesuaian STOK'";
        mysqli_query($conn, $sql_delete_mutasi);

        // Set pesan sukses jika diperlukan
        $_SESSION['flash_message'] = ['type' => 'info', 'message' => 'Penyesuaian untuk barang telah dibatalkan.'];

    } else {
        // Jika gagal mengembalikan stok, beri pesan error
        $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Gagal mengembalikan stok barang!'];
    }

} else {
    // Jika tidak punya hak akses, beri pesan error
    $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Anda tidak memiliki izin untuk membatalkan penyesuaian!'];
}

// Arahkan kembali ke halaman penyesuaian dengan nota yang sedang aktif
header("Location: ../../" . $halaman_kembali . "?nota=" . $nota);
exit();
?>
