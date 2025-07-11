<?php
// --- Blok untuk mengambil data pengguna & halaman aktif ---

// 1. Ambil data pengguna (versi aman dengan prepared statements)
include "configuration/config_connect.php";
include "configuration/config_chmod.php";

$nouser = $_SESSION['nouser'];
$user_sql = "SELECT nama, jabatan, avatar FROM user WHERE no = ?";
$stmt = mysqli_prepare($conn, $user_sql);
mysqli_stmt_bind_param($stmt, "s", $nouser);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);

$nama = $row['nama'] ?? 'Pengguna';
$jabatan = $row['jabatan'] ?? 'Tamu';
$avatar = $row['avatar'] ?? 'dist/img/avatar.png';

// 2. Deteksi halaman yang sedang aktif
$currentPage = basename($_SERVER['PHP_SELF']);

// 3. Definisikan grup halaman untuk setiap menu treeview
$barangPages = ['add_barang.php', 'barang.php', 'cetak_barcode.php'];
$atributPages = ['kategori.php', 'merek.php', 'satuan.php'];
$aktivitasPages = ['stok_masuk.php', 'stok_keluar.php', 'surat_kelola.php', 'stok_sesuaikan.php'];
$stokPages = ['stok_barang.php', 'stok_menipis.php', 'mutasi.php'];
$laporanPages = ['laporan_stok.php', 'laporan_penyesuaian.php', 'laporan_arus.php'];
$supplierPelangganPages = ['supplier.php', 'add_supplier.php', 'customer.php', 'add_customer.php'];
$manajemenUserPages = ['admin.php', 'add_jabatan.php'];
$pengaturanPages = ['set_general.php', 'set_themes.php', 'backup.php'];
?>
<aside class="main-sidebar">
    <section class="sidebar">
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= htmlspecialchars($avatar); ?>" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p><?= htmlspecialchars($nama); ?></p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>
        
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">MENU UTAMA</li>
            <li class="<?php if ($currentPage == 'index.php') { echo 'active'; } ?>">
                <a href="index.php"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a>
            </li>

            <?php if ($chmenu4 >= 1 || $_SESSION['jabatan'] == 'admin'): ?>
            <li class="treeview <?php if (in_array($currentPage, $barangPages)) echo 'active menu-open'; ?>">
                <a href="#"><i class="fa fa-th-list"></i> <span>Barang</span> <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>
                <ul class="treeview-menu">
                    <li class="<?php if ($currentPage == 'add_barang.php') echo 'active'; ?>"><a href="add_barang.php"><i class="fa fa-circle-o"></i> Tambah Barang</a></li>
                    <li class="<?php if ($currentPage == 'barang.php') echo 'active'; ?>"><a href="barang.php"><i class="fa fa-circle-o"></i> Data Barang</a></li>
                    <li class="<?php if ($currentPage == 'cetak_barcode.php') echo 'active'; ?>"><a href="cetak_barcode.php"><i class="fa fa-circle-o"></i> Cetak Barcode</a></li>
                </ul>
            </li>
            <?php endif; ?>

            <?php if ($chmenu3 >= 1 || $_SESSION['jabatan'] == 'admin'): ?>
            <li class="treeview <?php if (in_array($currentPage, $atributPages)) echo 'active menu-open'; ?>">
                <a href="#"><i class="fa fa-tags"></i> <span>Atribut Barang</span> <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>
                <ul class="treeview-menu">
                    <li class="<?php if ($currentPage == 'kategori.php') echo 'active'; ?>"><a href="kategori.php"><i class="fa fa-circle-o"></i> Kategori</a></li>
                    <li class="<?php if ($currentPage == 'merek.php') echo 'active'; ?>"><a href="merek.php"><i class="fa fa-circle-o"></i> Brand</a></li>
                    <li class="<?php if ($currentPage == 'satuan.php') echo 'active'; ?>"><a href="satuan.php"><i class="fa fa-circle-o"></i> Satuan</a></li>
                </ul>
            </li>
            <?php endif; ?>

            <?php if ($chmenu5 >= 1 || $_SESSION['jabatan'] == 'admin'): ?>
            <li class="treeview <?php if (in_array($currentPage, $aktivitasPages)) echo 'active menu-open'; ?>">
                <a href="#"><i class="fa fa-retweet"></i> <span>Aktivitas</span> <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>
                <ul class="treeview-menu">
                    <li class="<?php if ($currentPage == 'stok_masuk.php') echo 'active'; ?>"><a href="stok_masuk.php"><i class="fa fa-circle-o"></i> Barang Masuk</a></li>
                    <li class="<?php if ($currentPage == 'stok_keluar.php') echo 'active'; ?>"><a href="stok_keluar.php"><i class="fa fa-circle-o"></i> Barang Keluar</a></li>
                    <li class="<?php if ($currentPage == 'surat_kelola.php') echo 'active'; ?>"><a href="surat_kelola.php"><i class="fa fa-circle-o"></i> Surat Jalan</a></li>
                    <li class="<?php if ($currentPage == 'stok_sesuaikan.php') echo 'active'; ?>"><a href="stok_sesuaikan.php"><i class="fa fa-circle-o"></i> Penyesuaian</a></li>
                </ul>
            </li>
            <?php endif; ?>

            <?php if ($chmenu8 >= 1 || $_SESSION['jabatan'] == 'admin'): ?>
            <li class="treeview <?php if (in_array($currentPage, $stokPages)) echo 'active menu-open'; ?>">
                <a href="#"><i class="fa fa-inbox"></i> <span>Stok</span> <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>
                <ul class="treeview-menu">
                    <li class="<?php if ($currentPage == 'stok_barang.php') echo 'active'; ?>"><a href="stok_barang.php"><i class="fa fa-circle-o"></i> Data Stok</a></li>
                    <li class="<?php if ($currentPage == 'stok_menipis.php') echo 'active'; ?>"><a href="stok_menipis.php"><i class="fa fa-circle-o"></i> Stok Menipis</a></li>
                    <li class="<?php if ($currentPage == 'mutasi.php') echo 'active'; ?>"><a href="mutasi.php"><i class="fa fa-circle-o"></i> Mutasi</a></li>
                </ul>
            </li>
            <?php endif; ?>
            
            <li class="header">LAPORAN & MASTER</li>
            
            <?php if ($chmenu9 >= 1 || $_SESSION['jabatan'] == 'admin'): ?>
            <li class="treeview <?php if (in_array($currentPage, $laporanPages)) echo 'active menu-open'; ?>">
                <a href="#"><i class="fa fa-folder"></i> <span>Laporan</span> <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>
                <ul class="treeview-menu">
                    <li class="<?php if ($currentPage == 'laporan_stok.php') echo 'active'; ?>"><a href="laporan_stok.php"><i class="fa fa-circle-o"></i> Stok</a></li>
                    <li class="<?php if ($currentPage == 'laporan_penyesuaian.php') echo 'active'; ?>"><a href="laporan_penyesuaian.php"><i class="fa fa-circle-o"></i> Daftar Penyesuaian</a></li>
                    <li class="<?php if ($currentPage == 'laporan_arus.php') echo 'active'; ?>"><a href="laporan_arus.php"><i class="fa fa-circle-o"></i> Keluar Masuk</a></li>
                </ul>
            </li>
            <?php endif; ?>

            <?php if ($chmenu2 >= 1 || $_SESSION['jabatan'] == 'admin'): ?>
            <li class="treeview <?php if (in_array($currentPage, $supplierPelangganPages)) echo 'active menu-open'; ?>">
                <a href="#"><i class="fa fa-users"></i> <span>Supplier & Pelanggan</span> <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>
                <ul class="treeview-menu">
                    <li class="<?php if ($currentPage == 'supplier.php') echo 'active'; ?>"><a href="supplier.php"><i class="fa fa-circle-o"></i> Data Supplier</a></li>
                    <li class="<?php if ($currentPage == 'add_supplier.php') echo 'active'; ?>"><a href="add_supplier.php"><i class="fa fa-circle-o"></i> Tambah Supplier</a></li>
                    <li class="<?php if ($currentPage == 'customer.php') echo 'active'; ?>"><a href="customer.php"><i class="fa fa-circle-o"></i> Data Pelanggan</a></li>
                    <li class="<?php if ($currentPage == 'add_customer.php') echo 'active'; ?>"><a href="add_customer.php"><i class="fa fa-circle-o"></i> Tambah Pelanggan</a></li>
                </ul>
            </li>
            <?php endif; ?>
            
            <li class="header">PENGATURAN SISTEM</li>

            <?php if ($chmenu1 >= 1 || $_SESSION['jabatan'] == 'admin'): ?>
            <li class="treeview <?php if (in_array($currentPage, $manajemenUserPages)) echo 'active menu-open'; ?>">
                <a href="#"><i class="fa fa-user-secret"></i> <span>Manajemen User</span> <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>
                <ul class="treeview-menu">
                    <li class="<?php if ($currentPage == 'admin.php') echo 'active'; ?>"><a href="admin.php"><i class="fa fa-circle-o"></i> Kelola User</a></li>
                    <li class="<?php if ($currentPage == 'add_jabatan.php') echo 'active'; ?>"><a href="add_jabatan.php"><i class="fa fa-circle-o"></i> Jabatan User</a></li>
                </ul>
            </li>
            <?php endif; ?>

            <?php if ($chmenu10 >= 1 || $_SESSION['jabatan'] == 'admin'): ?>
            <li class="treeview <?php if (in_array($currentPage, $pengaturanPages)) echo 'active menu-open'; ?>">
                <a href="#"><i class="fa fa-cogs"></i> <span>Pengaturan</span> <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>
                <ul class="treeview-menu">
                    <li class="<?php if ($currentPage == 'set_general.php') echo 'active'; ?>"><a href="set_general.php"><i class="fa fa-circle-o"></i> General Setting</a></li>
                    <li class="<?php if ($currentPage == 'set_themes.php') echo 'active'; ?>"><a href="set_themes.php"><i class="fa fa-circle-o"></i> Theme Setting</a></li>
                    <li class="<?php if ($currentPage == 'backup.php') echo 'active'; ?>"><a href="backup.php"><i class="fa fa-circle-o"></i> Backup & Restore</a></li>
                </ul>
            </li>
            <?php endif; ?>
        </ul>
    </section>
</aside>