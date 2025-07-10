<?php

// --- KONFIGURASI & INISIALISASI ---

// Menampilkan semua error untuk mempermudah debugging (nonaktifkan di production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// Memuat file konfigurasi
include "configuration/config_etc.php";
include "configuration/config_include.php";
include "configuration/config_connect.php";

// Membuat koneksi database
connect();
timing();

// --- PENGAMBILAN DATA ---

// Ambil nama footer dari tabel 'data', lebih efisien dengan memilih kolom spesifik dan LIMIT 1
$query_footer = "SELECT nama FROM data LIMIT 1";
$result_footer = mysqli_query($conn, $query_footer);
$row_footer = mysqli_fetch_assoc($result_footer);
$footer_title = $row_footer['nama'] ?? 'Judul Default'; // Default title jika query gagal

// Ambil background login dari tabel 'backset', lebih efisien
$query_bg = "SELECT loginbg FROM backset LIMIT 1";
$result_bg = mysqli_query($conn, $query_bg);
$row_bg = mysqli_fetch_assoc($result_bg);
$background_image = $row_bg['loginbg'] ?? 'path/to/default/image.jpg'; // Default image jika query gagal

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo htmlspecialchars($footer_title); ?></title>

    <link rel="icon" type="image/png" href="page/images/icons/favicon.ico"/>
    <link rel="stylesheet" type="text/css" href="page/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="page/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="page/fonts/iconic/css/material-design-iconic-font.min.css">
    <link rel="stylesheet" type="text/css" href="page/vendor/animate/animate.css">
    <link rel="stylesheet" type="text/css" href="page/vendor/css-hamburgers/hamburgers.min.css">
    <link rel="stylesheet" type="text/css" href="page/vendor/animsition/css/animsition.min.css">
    <link rel="stylesheet" type="text/css" href="page/vendor/select2/select2.min.css">
    <link rel="stylesheet" type="text/css" href="page/vendor/daterangepicker/daterangepicker.css">
    <link rel="stylesheet" type="text/css" href="page/css/util.css">
    <link rel="stylesheet" type="text/css" href="page/css/main.css">
</head>
<body>

    <div class="container-login100" style="background-image: url('https://www.sufalamtech.com/wp-content/uploads/2023/06/inward-outward-bg-1.webp');">
        <div class="wrap-login100 p-l-55 p-r-55 p-t-80 p-b-30">
            <form action="op.php" method="post" class="login100-form validate-form">
                <span class="login100-form-title p-b-37">
                    <b>INV OME</b>
                </span>

                <div class="wrap-input100 validate-input m-b-20" data-validate="Masukan username">
                    <input class="input100" type="text" name="txtuser" placeholder="username">
                    <span class="focus-input100"></span>
                </div>

                <div class="wrap-input100 validate-input m-b-25" data-validate="Masukan password">
                    <input class="input100" type="password" name="txtpass" placeholder="password">
                    <span class="focus-input100"></span>
                </div>

                <div class="container-login100-form-btn">
                    <button type="submit" class="login100-form-btn">
                        Sign In
                    </button>
                </div>

                <div class="text-center p-t-50">
                     <p class="login-box-msg">Copyright © 2025 OME</p>
                </div>
            </form>
        </div>
    </div>

    <div id="dropDownSelect1"></div>

    <script src="page/vendor/jquery/jquery-3.2.1.min.js"></script>
    <script src="page/vendor/animsition/js/animsition.min.js"></script>
    <script src="page/vendor/bootstrap/js/popper.js"></script>
    <script src="page/vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="page/vendor/select2/select2.min.js"></script>
    <script src="page/vendor/daterangepicker/moment.min.js"></script>
    <script src="page/vendor/daterangepicker/daterangepicker.js"></script>
    <script src="page/vendor/countdowntime/countdowntime.js"></script>
    <script src="page/js/main.js"></script>

</body>
</html>