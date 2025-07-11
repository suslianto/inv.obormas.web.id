<?php
include "../../configuration/config_connect.php";
include "../../configuration/config_session.php";
include "../../configuration/config_chmod.php";

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Ambil data dari URL dengan aman
$no          = isset($_GET['no']) ? (int)$_GET['no'] : 0;
$forward     = isset($_GET['forward']) ? mysqli_real_escape_string($conn, $_GET['forward']) : '';
$forwardpage = isset($_GET['forwardpage']) ? $_GET['forwardpage'] . '.php' : 'index.php'; // Tambahkan .php
$chmod       = isset($_GET['chmod']) ? (int)$_GET['chmod'] : 0;

// Validasi dasar
if (empty($no) || empty($forward)) {
    $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Parameter tidak lengkap.'];
    header("Location: ../../" . $forwardpage);
    exit();
}

// Cek hak akses
if ($chmod >= 4 || $_SESSION['jabatan'] == 'admin') {
    
    // Query untuk menghapus data
    $sql = "DELETE FROM $forward WHERE no=$no";

    if (mysqli_query($conn, $sql)) {
        // Jika berhasil
        $_SESSION['flash_message'] = ['type' => 'success', 'message' => 'Data telah berhasil dihapus.'];
    } else {
        // Jika gagal, kemungkinan karena data digunakan di tabel lain (foreign key constraint)
        if(mysqli_errno($conn) == 1451){
            $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Gagal menghapus! Data ini sedang digunakan di transaksi lain.'];
        } else {
            $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Terjadi kesalahan: ' . mysqli_error($conn)];
        }
    }
} else {
    // Jika tidak punya hak akses
    $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Akses ditolak! Anda tidak memiliki izin untuk menghapus data.'];
}

// Arahkan kembali ke halaman daftar
header("Location: ../../" . $forwardpage);
exit();
?>
