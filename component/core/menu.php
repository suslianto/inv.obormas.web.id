<?php
// --- Blok untuk mengambil data pengguna & halaman aktif ---

// 1. Ambil data pengguna
include "configuration/config_connect.php";
include "configuration/config_chmod.php";

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$nouser = $_SESSION['nouser'] ?? '';
$nama = 'Pengguna';
$jabatan = 'Tamu';
$avatar = 'dist/img/avatar.png'; 

if ($nouser) {
    $user_sql = "SELECT nama, jabatan, avatar FROM user WHERE no = ?";
    if ($stmt = mysqli_prepare($conn, $user_sql)) {
        mysqli_stmt_bind_param($stmt, "s", $nouser);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if ($row = mysqli_fetch_assoc($result)) {
            $nama = $row['nama'];
            $jabatan = $row['jabatan'];
            $avatar = $row['avatar'];
        }
        mysqli_stmt_close($stmt);
    }
}

// 2. Deteksi halaman aktif TANPA ekstensi .php
$currentPage = pathinfo(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), PATHINFO_FILENAME);
if (empty($currentPage)) {
    $currentPage = 'index'; // Default ke index jika URL root
}

// 3. Definisikan grup halaman TANPA ekstensi .php (SUDAH DIGABUNGKAN)
$barangPages = ['add_barang', 'barang', 'edit_barang', 'cetak_barcode', 'barang_detail'];
$atributPages = ['kategori', 'add_kategori', 'edit_kategori', 'merek', 'add_merek', 'edit_merek', 'satuan', 'add_satuan', 'edit_satuan'];
$aktivitasPages = ['stok_masuk', 'stok_keluar', 'surat_kelola', 'stok_sesuaikan'];
$stokPages = ['stok_barang', 'stok_menipis', 'mutasi']; // Ditambahkan
$laporanPages = ['laporan_stok', 'laporan_penyesuaian', 'laporan_arus']; // Ditambahkan
$supplierPelangganPages = ['supplier', 'add_supplier', 'edit_supplier', 'customer', 'add_customer', 'edit_customer'];
$manajemenUserPages = ['admin', 'add_admin', 'edit_admin', 'add_jabatan', 'edit_jabatan'];
$pengaturanPages = ['set_general', 'set_themes', 'backup'];

?>
<aside class="main-sidebar">
    <section class="sidebar">
        <!-- <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= htmlspecialchars($avatar); ?>" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p><?= htmlspecialchars($nama); ?></p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div> -->
        
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">MENU UTAMA</li>
            <li class="<?php if ($currentPage == 'index') echo 'active'; ?>">
                <a href="index"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a>
            </li>

            <?php if (($chmenu4 ?? 0) >= 1 || ($_SESSION['jabatan'] ?? '') == 'admin'): ?>
            <li class="treeview <?php if (in_array($currentPage, $barangPages)) echo 'active menu-open'; ?>">
                <a href="#"><i class="fa fa-th-list"></i> <span>Barang</span> <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>
                <ul class="treeview-menu">
                    <li class="<?php if ($currentPage == 'add_barang') echo 'active'; ?>"><a href="add_barang"><i class="fa fa-circle-o"></i> Tambah Barang</a></li>
                    <li class="<?php if ($currentPage == 'barang' || $currentPage == 'edit_barang' || $currentPage == 'barang_detail') echo 'active'; ?>"><a href="barang"><i class="fa fa-circle-o"></i> Data Barang</a></li>
                    <li class="<?php if ($currentPage == 'cetak_barcode') echo 'active'; ?>"><a href="cetak_barcode"><i class="fa fa-circle-o"></i> Cetak Barcode</a></li>
                </ul>
            </li>
            <?php endif; ?>

            <?php if (($chmenu5 ?? 0) >= 1 || ($_SESSION['jabatan'] ?? '') == 'admin'): ?>
            <li class="treeview <?php if (in_array($currentPage, $aktivitasPages)) echo 'active menu-open'; ?>">
                <a href="#"><i class="fa fa-retweet"></i> <span>Aktivitas</span> <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>
                <ul class="treeview-menu">
                    <li class="<?php if ($currentPage == 'stok_masuk') echo 'active'; ?>"><a href="stok_masuk"><i class="fa fa-circle-o"></i> Barang Masuk</a></li>
                    <li class="<?php if ($currentPage == 'stok_keluar') echo 'active'; ?>"><a href="stok_keluar"><i class="fa fa-circle-o"></i> Barang Keluar</a></li>
                    <li class="<?php if ($currentPage == 'surat_kelola') echo 'active'; ?>"><a href="surat_kelola"><i class="fa fa-circle-o"></i> Surat Jalan</a></li>
                    <li class="<?php if ($currentPage == 'stok_sesuaikan') echo 'active'; ?>"><a href="stok_sesuaikan"><i class="fa fa-circle-o"></i> Penyesuaian</a></li>
                </ul>
            </li>
            <?php endif; ?>
            
            <?php if (($chmenu8 ?? 0) >= 1 || ($_SESSION['jabatan'] ?? '') == 'admin'): ?>
            <li class="treeview <?php if (in_array($currentPage, $stokPages)) echo 'active menu-open'; ?>">
                <a href="#"><i class="fa fa-inbox"></i> <span>Stok</span> <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>
                <ul class="treeview-menu">
                    <li class="<?php if ($currentPage == 'stok_barang') echo 'active'; ?>"><a href="stok_barang"><i class="fa fa-circle-o"></i> Data Stok</a></li>
                    <li class="<?php if ($currentPage == 'stok_menipis') echo 'active'; ?>"><a href="stok_menipis"><i class="fa fa-circle-o"></i> Stok Menipis</a></li>
                    <li class="<?php if ($currentPage == 'mutasi') echo 'active'; ?>"><a href="mutasi"><i class="fa fa-circle-o"></i> Mutasi</a></li>
                </ul>
            </li>
            <?php endif; ?>
            
            <li class="header">LAPORAN & MASTER</li>
            
            <?php if (($chmenu9 ?? 0) >= 1 || ($_SESSION['jabatan'] ?? '') == 'admin'): ?>
            <li class="treeview <?php if (in_array($currentPage, $laporanPages)) echo 'active menu-open'; ?>">
                <a href="#"><i class="fa fa-folder"></i> <span>Laporan</span> <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>
                <ul class="treeview-menu">
                    <li class="<?php if ($currentPage == 'laporan_stok') echo 'active'; ?>"><a href="laporan_stok"><i class="fa fa-circle-o"></i> Stok</a></li>
                    <li class="<?php if ($currentPage == 'laporan_penyesuaian') echo 'active'; ?>"><a href="laporan_penyesuaian"><i class="fa fa-circle-o"></i> Daftar Penyesuaian</a></li>
                    <li class="<?php if ($currentPage == 'laporan_arus') echo 'active'; ?>"><a href="laporan_arus"><i class="fa fa-circle-o"></i> Keluar Masuk</a></li>
                </ul>
            </li>
            <?php endif; ?>

            <?php if (($chmenu2 ?? 0) >= 1 || ($_SESSION['jabatan'] ?? '') == 'admin'): ?>
            <li class="treeview <?php if (in_array($currentPage, $supplierPelangganPages)) echo 'active menu-open'; ?>">
                <a href="#"><i class="fa fa-users"></i> <span>Supplier & Pelanggan</span> <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>
                <ul class="treeview-menu">
                    <li class="<?php if ($currentPage == 'supplier' || $currentPage == 'add_supplier' || $currentPage == 'edit_supplier') echo 'active'; ?>"><a href="supplier"><i class="fa fa-circle-o"></i> Data Supplier</a></li>
                    <li class="<?php if ($currentPage == 'customer' || $currentPage == 'add_customer' || $currentPage == 'edit_customer') echo 'active'; ?>"><a href="customer"><i class="fa fa-circle-o"></i> Data Pelanggan</a></li>
                </ul>
            </li>
            <?php endif; ?>

            <?php if (($chmenu3 ?? 0) >= 1 || ($_SESSION['jabatan'] ?? '') == 'admin'): ?>
            <li class="treeview <?php if (in_array($currentPage, $atributPages)) echo 'active menu-open'; ?>">
                <a href="#"><i class="fa fa-tags"></i> <span>Atribut Barang</span> <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>
                <ul class="treeview-menu">
                    <li class="<?php if ($currentPage == 'kategori' || $currentPage == 'add_kategori' || $currentPage == 'edit_kategori' ) echo 'active'; ?>"><a href="kategori"><i class="fa fa-circle-o"></i> Kategori</a></li>
                    <li class="<?php if ($currentPage == 'merek' || $currentPage == 'add_merek' || $currentPage == 'edit_merek') echo 'active'; ?>"><a href="merek"><i class="fa fa-circle-o"></i> Merk</a></li>
                    <li class="<?php if ($currentPage == 'satuan' || $currentPage == 'add_satuan' || $currentPage == 'edit_satuan') echo 'active'; ?>"><a href="satuan"><i class="fa fa-circle-o"></i> Satuan</a></li>
                </ul>
            </li>
            <?php endif; ?>
            
            <li class="header">PENGATURAN SISTEM</li>

            <?php if (($chmenu1 ?? 0) >= 1 || ($_SESSION['jabatan'] ?? '') == 'admin'): ?>
            <li class="treeview <?php if (in_array($currentPage, $manajemenUserPages)) echo 'active menu-open'; ?>">
                <a href="#"><i class="fa fa-user-secret"></i> <span>Manajemen User</span> <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>
                <ul class="treeview-menu">
                    <li class="<?php if ($currentPage == 'admin' || $currentPage == 'add_admin' || $currentPage == 'edit_admin') echo 'active'; ?>"><a href="admin"><i class="fa fa-circle-o"></i> Kelola User</a></li>
                    <li class="<?php if ($currentPage == 'add_jabatan' || $currentPage == 'edit_jabatan') echo 'active'; ?>"><a href="add_jabatan"><i class="fa fa-circle-o"></i> Jabatan User</a></li>
                </ul>
            </li>
            <?php endif; ?>
            
             <?php if (($chmenu10 ?? 0) >= 1 || ($_SESSION['jabatan'] ?? '') == 'admin'): ?>
            <li class="treeview <?php if (in_array($currentPage, $pengaturanPages)) echo 'active menu-open'; ?>">
                <a href="#"><i class="fa fa-cogs"></i> <span>Pengaturan</span> <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>
                <ul class="treeview-menu">
                    <li class="<?php if ($currentPage == 'set_general') echo 'active'; ?>"><a href="set_general"><i class="fa fa-circle-o"></i> General Setting</a></li>
                    <li class="<?php if ($currentPage == 'set_themes') echo 'active'; ?>"><a href="set_themes"><i class="fa fa-circle-o"></i> Theme Setting</a></li>
                    <li class="<?php if ($currentPage == 'backup') echo 'active'; ?>"><a href="backup"><i class="fa fa-circle-o"></i> Backup & Restore</a></li>
                </ul>
            </li>
            <?php endif; ?>

        </ul>
    </section>
</aside>