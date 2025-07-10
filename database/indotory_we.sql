-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 06, 2024 at 04:35 AM
-- Server version: 10.3.16-MariaDB
-- PHP Version: 7.1.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `weplus`
--

-- --------------------------------------------------------

--
-- Table structure for table `backset`
--

CREATE TABLE `backset` (
  `url` varchar(100) NOT NULL,
  `sessiontime` varchar(4) DEFAULT NULL,
  `footer` varchar(50) DEFAULT NULL,
  `themesback` varchar(2) DEFAULT NULL,
  `responsive` varchar(2) DEFAULT NULL,
  `namabisnis1` tinytext NOT NULL,
  `mode` varchar(1) NOT NULL,
  `prefikbarcode` varchar(10) NOT NULL,
  `loginbg` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `backset`
--

INSERT INTO `backset` (`url`, `sessiontime`, `footer`, `themesback`, `responsive`, `namabisnis1`, `mode`, `prefikbarcode`, `loginbg`) VALUES
('http://localhost/warehouse', '100', 'Idwares', '2', '0', 'Indotory W.E', '0', 'ID', 'dist/upload/images.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `barang`
--

CREATE TABLE `barang` (
  `kode` varchar(20) NOT NULL,
  `sku` varchar(20) NOT NULL,
  `nama` varchar(200) DEFAULT NULL,
  `hargabeli` int(11) DEFAULT NULL,
  `hargajual` int(11) DEFAULT NULL,
  `keterangan` varchar(200) DEFAULT NULL,
  `kategori` varchar(20) DEFAULT NULL,
  `satuan` varchar(20) NOT NULL,
  `terjual` int(10) DEFAULT NULL,
  `terbeli` int(11) DEFAULT NULL,
  `sisa` int(11) DEFAULT NULL,
  `stokmin` int(10) NOT NULL,
  `barcode` varchar(50) NOT NULL,
  `brand` text NOT NULL,
  `lokasi` varchar(50) NOT NULL,
  `expired` date NOT NULL,
  `warna` varchar(20) NOT NULL,
  `ukuran` varchar(10) NOT NULL,
  `avatar` varchar(300) NOT NULL,
  `no` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `barang`
--

INSERT INTO `barang` (`kode`, `sku`, `nama`, `hargabeli`, `hargajual`, `keterangan`, `kategori`, `satuan`, `terjual`, `terbeli`, `sisa`, `stokmin`, `barcode`, `brand`, `lokasi`, `expired`, `warna`, `ukuran`, `avatar`, `no`) VALUES
('000018', 'SKU000018', 'TES', 0, 0, '', 'Laptop', 'Pcs', 1, 101, 100, 1, 'BRG000018', 'Lap', '', '0000-00-00', '', '', 'dist/upload/', 18),
('0001', 'SKU01', 'Dell 6510 Core i7 / NVIDIA/hdd 320/4gb', 0, 0, 'new item', 'LAPTOP', 'Kg', 2, 0, 3, 2, 'IDWARE01', 'DELL', 'Laci AA', '0000-00-00', 'merah', '', 'dist/upload/index.jpg', 1),
('0002', 'SKU02', 'HP8440p corei5 m520/hdd320/ram4', 0, 0, '', 'LAPTOP', 'Pcs', 3, 0, 4, 3, 'IDWARE02', 'HP', 'Laci AA', '0000-00-00', 'putih', '', 'dist/upload/index.jpg', 2),
('0003', 'SKU03', 'HP core i5 m520/4gb/320gb (dikirim HP8440 JUGA)', 0, 0, '', 'LAPTOP', 'Kg', 4, 0, 5, 5, 'IDWARE03', 'HP', 'Laci AA', '0000-00-00', 'silver', '', 'dist/upload/index.jpg', 3),
('0004', 'SKU04', 'HARDDISK EXTERNAL 500Gb SEAGATE', 0, 0, '', 'DISK', 'Gr', 5, 0, 6, 6, 'IDWARE04', 'SEAGATE', 'Laci AA', '0000-00-00', '', '', 'dist/upload/index.jpg', 4),
('0005', 'SKU05', 'HARDDISK EXTERNAL 1TB SEAGATE', 0, 0, '', 'DISK', 'Pcs', 6, 0, 7, 8, 'IDWARE05', 'SEAGATE', 'Laci AA', '0000-00-00', '', '', 'dist/upload/index.jpg', 5),
('0006', 'SKU06', 'MOUSE LOGITECH B100', 0, 0, '', 'AKSESORIS', 'Kg', 7, 0, 8, 9, 'IDWARE06', 'LOGITECH', 'Laci AA', '0000-00-00', '', '', 'dist/upload/index.jpg', 6),
('0007', 'SKU07', 'KABEL HDMI 3 METER', 0, 0, '', 'KABEL KONEKTOR', 'Pcs', 8, 2, 9, 10, 'IDWARE07', 'UNIVERSAL', 'Laci AA', '0000-00-00', '', 'X', 'dist/upload/index.jpg', 7),
('0008', 'SKU08', 'Monitor Varro 19 Inch', 0, 0, '', 'MONITOR', 'Kg', 9, 3, 10, 11, 'IDWARE08', 'VARRO', 'Laci AA', '0000-00-00', '', 'L', 'dist/upload/index.jpg', 8),
('0009', 'SKU09', 'Kabel Hdmi 1.5m', 0, 0, '', 'KABEL KONEKTOR', 'Gr', 10, 4, 11, 12, 'IDWARE09', 'UNIVERSAL', 'Laci AA', '0000-00-00', '', '', 'dist/upload/index.jpg', 9),
('0010', 'SKU10', 'Adaptor 2a 12v For DVR Dan CCTV', 0, 0, '', 'LISTRIK', 'Pcs', 11, 1, 13, 13, 'IDWARE10', 'UNIVERSAL', 'Laci AA', '0000-00-00', '', '', 'dist/upload/index.jpg', 10),
('0011', 'SKU11', 'Power Supply Jaring CCTV 12v/10a', 0, 0, '', 'LISTRIK', 'Kg', 12, 0, 13, 14, 'IDWARE11', 'UNIVERSAL', 'Laci AA', '0000-00-00', '', '', 'dist/upload/index.jpg', 11),
('0012', 'SKU12', 'Cctv Cabang 4', 0, 0, '', 'KABEL KONEKTOR', 'Pcs', 13, 0, 14, 15, 'IDWARE12', 'UNIVERSAL', 'Laci AA', '0000-00-00', '', '', 'dist/upload/index.jpg', 12),
('0013', 'SKU13', 'Poe Kabel Splitter Injector SET', 0, 0, '', 'KABEL KONEKTOR', 'Kg', 14, 0, 15, 16, 'IDWARE13', 'UNIVERSAL', 'Laci AA', '0000-00-00', '', '', 'dist/upload/index.jpg', 13),
('0014', 'SKU14', 'Jack Bnc Cctv', 0, 0, '', 'KABEL KONEKTOR', 'Gr', 15, 0, 16, 17, 'IDWARE14', 'UNIVERSAL', 'Laci AA', '0000-00-00', '', '', 'dist/upload/index.jpg', 14),
('0015', 'SKU15', 'Jack Bnc Cctv TAIWAN', 0, 0, '', 'KABEL KONEKTOR', 'Pcs', 16, 0, 17, 18, 'IDWARE15', 'UNIVERSAL', 'Laci AA', '0000-00-00', '', '', 'dist/upload/index.jpg', 15),
('0016', 'SKU16', 'Conector Sambungan Untuk Cctv BNC', 0, 0, '', 'KABEL KONEKTOR', 'Kg', 17, 0, 18, 19, 'IDWARE16', 'UNIVERSAL', 'Laci AA', '0000-00-00', '', '', 'dist/upload/index.jpg', 16),
('0017', 'SKU17', 'Lampu Bardi 9W', 0, 0, '', 'SMARTHOME', 'Pcs', 18, 0, 19, 20, 'IDWARE17', 'BARDI', 'Laci AA', '0000-00-00', '', '', 'dist/upload/index.jpg', 17);

-- --------------------------------------------------------

--
-- Table structure for table `brand`
--

CREATE TABLE `brand` (
  `kode` varchar(20) NOT NULL,
  `nama` varchar(30) DEFAULT NULL,
  `no` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `brand`
--

INSERT INTO `brand` (`kode`, `nama`, `no`) VALUES
('0001', 'Lap', 1);

-- --------------------------------------------------------

--
-- Table structure for table `chmenu`
--

CREATE TABLE `chmenu` (
  `userjabatan` varchar(20) NOT NULL,
  `menu1` varchar(1) DEFAULT '0',
  `menu2` varchar(1) DEFAULT '0',
  `menu3` varchar(1) DEFAULT '0',
  `menu4` varchar(1) DEFAULT '0',
  `menu5` varchar(1) DEFAULT '0',
  `menu6` varchar(1) DEFAULT '0',
  `menu7` varchar(1) DEFAULT '0',
  `menu8` varchar(1) DEFAULT '0',
  `menu9` varchar(1) DEFAULT '0',
  `menu10` varchar(1) DEFAULT '0',
  `menu11` varchar(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `chmenu`
--

INSERT INTO `chmenu` (`userjabatan`, `menu1`, `menu2`, `menu3`, `menu4`, `menu5`, `menu6`, `menu7`, `menu8`, `menu9`, `menu10`, `menu11`) VALUES
('ad', '1', '1', '1', '1', '1', '', '', '1', '1', '1', ''),
('admin', '5', '5', '5', '5', '5', '5', '5', '5', '5', '5', '5'),
('Admin Gudang', '0', '0', '0', '2', '2', '', '', '2', '2', '0', ''),
('Andika', '5', '5', '1', '2', '3', '', '', '4', '0', '5', ''),
('Beta', '0', '1', '3', '3', '3', '', '', '1', '0', '0', ''),
('COBA', '0', '0', '0', '0', '0', '', '', '0', '0', '0', ''),
('Dody', '0', '0', '5', '5', '5', '', '', '5', '5', '0', ''),
('Eng Staff', '0', '0', '0', '0', '3', '', '', '0', '0', '0', ''),
('erika soedjono', '0', '0', '2', '2', '', '', '', '2', '', '', ''),
('ganteng', '5', '5', '5', '5', '5', '', '', '5', '5', '5', ''),
('Geri Dizako', '', '', '', '', '', '', '', '', '', '', ''),
('GTN', '1', '2', '2', '2', '2', '', '', '2', '2', '2', ''),
('Gudang', '0', '1', '1', '4', '2', '', '', '3', '3', '3', ''),
('Helper', '2', '2', '2', '2', '2', '', '', '2', '2', '2', ''),
('IT Specialist', '5', '5', '5', '5', '5', '', '', '5', '5', '5', ''),
('kasir', '0', '5', '5', '5', '3', '', '', '3', '5', '0', ''),
('Kepala Bagian', '3', '3', '3', '3', '3', '', '', '3', '3', '3', ''),
('Kepala Gudang', '0', '3', '3', '3', '3', '', '', '3', '3', '0', ''),
('Manager', '5', '5', '5', '5', '5', '', '', '5', '5', '5', ''),
('MANDOR', '4', '4', '4', '4', '4', '', '', '4', '4', '4', ''),
('MASTER', '5', '', '', '', '', '', '', '', '', '', ''),
('Op', '', '', '0', '', '', '', '', '', '', '', ''),
('Operasional', '1', '2', '2', '2', '2', '', '', '2', '2', '2', ''),
('Operator', '1', '5', '5', '5', '5', '', '', '5', '5', '5', ''),
('pengguna', '0', '0', '0', '1', '2', '', '', '0', '0', '0', ''),
('SAFA', '0', '0', '0', '0', '5', '', '', '1', '0', '0', ''),
('SPV TEKNIS', '1', '', '', '', '', '', '', '', '', '', ''),
('Staf Umum', '0', '2', '3', '2', '3', '', '', '1', '0', '0', ''),
('Staff', '5', '5', '2', '2', '0', '5', '5', '1', '5', '0', ''),
('Staff Gudang', '0', '0', '5', '5', '5', '', '', '5', '5', '0', ''),
('super admin', '5', '5', '5', '5', '5', '', '', '5', '5', '5', ''),
('test dulu', '1', '1', '3', '5', '5', '', '', '5', '0', '0', ''),
('testest', '1', '0', '0', '1', '5', '', '', '5', '4', '0', ''),
('Tukang catet', '1', '1', '1', '1', '1', '', '', '1', '1', '1', ''),
('user', '', '', '', '0', '5', '', '', '5', '', '', ''),
('viewer', '0', '0', '0', '1', '0', '', '', '1', '1', '0', '');

-- --------------------------------------------------------

--
-- Table structure for table `data`
--

CREATE TABLE `data` (
  `nama` varchar(100) DEFAULT NULL,
  `tagline` varchar(100) DEFAULT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `notelp` varchar(20) DEFAULT NULL,
  `signature` varchar(255) DEFAULT NULL,
  `avatar` varchar(150) DEFAULT NULL,
  `no` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `data`
--

INSERT INTO `data` (`nama`, `tagline`, `alamat`, `notelp`, `signature`, `avatar`, `no`) VALUES
('NAMA TOKO', 'Kemasan', 'Jl. Puradinata No.89, RT 5 / RW 12 Baleendah Bandung, Jawa Barat 40375', '087830934394', 'Hello', 'dist/upload/index.jpg', 0);

-- --------------------------------------------------------

--
-- Table structure for table `info`
--

CREATE TABLE `info` (
  `nama` varchar(50) DEFAULT NULL,
  `avatar` varchar(100) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `isi` text DEFAULT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `info`
--

INSERT INTO `info` (`nama`, `avatar`, `tanggal`, `isi`, `id`) VALUES
('admin', 'dist/upload/index.jpg', '2021-01-17', '<h3><b><u>Bagi yang baru selesai Instal, silahkan lakukan hal berikut:</u></b></h3><h3><b><u><br></u></b></h3><h3><b><u><br><br>ke Menu Pengaturan-&gt;general Settings, pastikan url sama dengan url anda</u></b></h3><h3><b><u><br></u></b></h3><h3><b><u><br><br>Lalu untuk mengosongkan data bawaan silahkan:</u></b><b></b></h3><h3><b><u><br></u></b></h3><h3><b><u><br></u></b></h3><h3>menu pengaturan-&gt;general settings-&gt;scroll kebawah dan klik tombol RESET</h3><p><br></p><p>Jika ada kesulitan langsung chat di tokopedia/shopee ya, kami layani jam 9 pagi-5 sore</p>', 1),
('admin', 'dist/upload/index.jpg', '2021-01-17', '<h3><b><u>Bagi yang baru selesai Instal, silahkan lakukan hal berikut:</u></b></h3><h3><b><u><br></u></b></h3><h3><b><u><br><br>ke Menu Pengaturan-&gt;general Settings, pastikan url sama dengan url anda</u></b></h3><h3><b><u><br></u></b></h3><h3><b><u><br><br>Lalu untuk mengosongkan data bawaan silahkan:</u></b><b></b></h3><h3><b><u><br></u></b></h3><h3><b><u><br></u></b></h3><h3>menu pengaturan-&gt;general settings-&gt;scroll kebawah dan klik tombol RESET</h3><p><br></p><p>Jika ada kesulitan langsung chat di tokopedia/shopee ya, kami layani jam 9 pagi-5 sore</p>', 1),
('admin', 'dist/upload/index.jpg', '2021-01-17', '<h3><b><u>Bagi yang baru selesai Instal, silahkan lakukan hal berikut:</u></b></h3><h3><b><u><br></u></b></h3><h3><b><u><br><br>ke Menu Pengaturan-&gt;general Settings, pastikan url sama dengan url anda</u></b></h3><h3><b><u><br></u></b></h3><h3><b><u><br><br>Lalu untuk mengosongkan data bawaan silahkan:</u></b><b></b></h3><h3><b><u><br></u></b></h3><h3><b><u><br></u></b></h3><h3>menu pengaturan-&gt;general settings-&gt;scroll kebawah dan klik tombol RESET</h3><p><br></p><p>Jika ada kesulitan langsung chat di tokopedia/shopee ya, kami layani jam 9 pagi-5 sore</p>', 1),
('admin', 'dist/upload/index.jpg', '2021-01-17', '<h3><b><u>Bagi yang baru selesai Instal, silahkan lakukan hal berikut:</u></b></h3><h3><b><u><br></u></b></h3><h3><b><u><br><br>ke Menu Pengaturan-&gt;general Settings, pastikan url sama dengan url anda</u></b></h3><h3><b><u><br></u></b></h3><h3><b><u><br><br>Lalu untuk mengosongkan data bawaan silahkan:</u></b><b></b></h3><h3><b><u><br></u></b></h3><h3><b><u><br></u></b></h3><h3>menu pengaturan-&gt;general settings-&gt;scroll kebawah dan klik tombol RESET</h3><p><br></p><p>Jika ada kesulitan langsung chat di tokopedia/shopee ya, kami layani jam 9 pagi-5 sore</p>', 1),
('admin', 'dist/upload/test.jpg', '2024-05-12', '<p>INFORMASI UNTUK BAGIAN .....</p>', 1),
('admin', 'dist/upload/test.jpg', '2024-05-12', '<p>INFORMASI UNTUK BAGIAN .....</p>', 1),
('admin', 'dist/upload/test.jpg', '2024-05-12', '<p>INFORMASI UNTUK BAGIAN .....</p>', 1),
('admin', 'dist/upload/test.jpg', '2024-05-12', '<p>INFORMASI UNTUK BAGIAN .....</p>', 1);

-- --------------------------------------------------------

--
-- Table structure for table `jabatan`
--

CREATE TABLE `jabatan` (
  `kode` varchar(20) NOT NULL,
  `nama` varchar(20) DEFAULT NULL,
  `no` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `jabatan`
--

INSERT INTO `jabatan` (`kode`, `nama`, `no`) VALUES
('0001', 'admin', 35),
('0002', 'Staff', 33),
('0003', 'Staff Gudang', 67),
('0005', 'IT Specialist', 69),
('0006', 'user', 70),
('0007', 'ganteng', 72),
('0008', 'Paket kecil', 73),
('0009', 'Operasional', 74);

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `kode` varchar(20) NOT NULL,
  `nama` varchar(30) DEFAULT NULL,
  `no` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`kode`, `nama`, `no`) VALUES
('0001', 'Laptop', 1);

-- --------------------------------------------------------

--
-- Table structure for table `mutasi`
--

CREATE TABLE `mutasi` (
  `namauser` varchar(50) NOT NULL,
  `tgl` date NOT NULL,
  `jam` varchar(10) NOT NULL,
  `kodebarang` varchar(10) NOT NULL,
  `sisa` int(10) NOT NULL,
  `jumlah` int(10) NOT NULL,
  `kegiatan` varchar(100) NOT NULL,
  `keterangan` varchar(100) NOT NULL,
  `tujuan` varchar(100) NOT NULL,
  `no` int(11) NOT NULL,
  `status` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mutasi`
--

INSERT INTO `mutasi` (`namauser`, `tgl`, `jam`, `kodebarang`, `sisa`, `jumlah`, `kegiatan`, `keterangan`, `tujuan`, `no`, `status`) VALUES
('admin', '2024-06-01', '04:49', '', 0, 0, 'menambah Produk via impor', '', 'sistem', 1, 'berhasil'),
('admin', '2024-06-01', '04:49', '0001', 3, 3, 'menambah Produk via impor', '0001', 'sistem', 2, 'berhasil'),
('admin', '2024-06-01', '04:49', '0002', 4, 4, 'menambah Produk via impor', '0002', 'sistem', 3, 'berhasil'),
('admin', '2024-06-01', '04:49', '0003', 5, 5, 'menambah Produk via impor', '0003', 'sistem', 4, 'berhasil'),
('admin', '2024-06-01', '04:49', '0004', 6, 6, 'menambah Produk via impor', '0004', 'sistem', 5, 'berhasil'),
('admin', '2024-06-01', '04:49', '0005', 7, 7, 'menambah Produk via impor', '0005', 'sistem', 6, 'berhasil'),
('admin', '2024-06-01', '04:49', '0006', 8, 8, 'menambah Produk via impor', '0006', 'sistem', 7, 'berhasil'),
('admin', '2024-06-01', '04:49', '0007', 9, 9, 'menambah Produk via impor', '0007', 'sistem', 8, 'berhasil'),
('admin', '2024-06-01', '04:49', '0008', 10, 10, 'menambah Produk via impor', '0008', 'sistem', 9, 'berhasil'),
('admin', '2024-06-01', '04:49', '0009', 11, 11, 'menambah Produk via impor', '0009', 'sistem', 10, 'berhasil'),
('admin', '2024-06-01', '04:49', '0010', 12, 12, 'menambah Produk via impor', '0010', 'sistem', 11, 'berhasil'),
('admin', '2024-06-01', '04:49', '0011', 13, 13, 'menambah Produk via impor', '0011', 'sistem', 12, 'berhasil'),
('admin', '2024-06-01', '04:49', '0012', 14, 14, 'menambah Produk via impor', '0012', 'sistem', 13, 'berhasil'),
('admin', '2024-06-01', '04:49', '0013', 15, 15, 'menambah Produk via impor', '0013', 'sistem', 14, 'berhasil'),
('admin', '2024-06-01', '04:49', '0014', 16, 16, 'menambah Produk via impor', '0014', 'sistem', 15, 'berhasil'),
('admin', '2024-06-01', '04:49', '0015', 17, 17, 'menambah Produk via impor', '0015', 'sistem', 16, 'berhasil'),
('admin', '2024-06-01', '04:49', '0016', 18, 18, 'menambah Produk via impor', '0016', 'sistem', 17, 'berhasil'),
('admin', '2024-06-01', '04:49', '0017', 19, 19, 'menambah Produk via impor', '0017', 'sistem', 18, 'berhasil'),
('admin', '2024-06-01', '10:01', '000018', 1, 1, 'menambah Produk', '000018', 'sistem', 19, 'berhasil'),
('admin', '2024-06-01', '10:33', '000018', 1, 1, 'stok masuk', '0001', '', 23, 'berhasil'),
('admin', '2024-06-01', '10:35', '0010', 13, 1, 'stok masuk', '0002', 'Lantana', 24, 'berhasil'),
('admin', '2024-06-01', '10:48', '000018', 101, 100, 'stok masuk', '0003', 'Lantana', 26, 'berhasil'),
('admin', '2024-06-01', '10:49', '000018', 100, 1, 'stok keluar', '0001', '', 27, 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `pelanggan`
--

CREATE TABLE `pelanggan` (
  `kode` varchar(10) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `notelp` varchar(20) NOT NULL,
  `alamat` varchar(255) NOT NULL,
  `no` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `satuan`
--

CREATE TABLE `satuan` (
  `kode` varchar(10) NOT NULL,
  `nama` varchar(20) NOT NULL,
  `no` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `satuan`
--

INSERT INTO `satuan` (`kode`, `nama`, `no`) VALUES
('0001', 'Pcs', 1),
('0002', 'kategoria', 3),
('0014', 'Pcs', 129),
('0015', 'sachet', 137),
('0017', 'BUAH', 139),
('0018', 'rol', 140),
('0019', 'Tablet', 141),
('0020', 'kg', 142),
('0021', 'krg', 143);

-- --------------------------------------------------------

--
-- Table structure for table `stok_keluar`
--

CREATE TABLE `stok_keluar` (
  `nota` varchar(10) NOT NULL,
  `cabang` varchar(2) NOT NULL,
  `tgl` date NOT NULL,
  `pelanggan` varchar(100) NOT NULL,
  `userid` varchar(10) NOT NULL,
  `keterangan` varchar(100) NOT NULL,
  `modal` int(10) NOT NULL,
  `total` int(10) NOT NULL,
  `no` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `stok_keluar_daftar`
--

CREATE TABLE `stok_keluar_daftar` (
  `nota` varchar(10) NOT NULL,
  `kode_barang` varchar(10) NOT NULL,
  `nama` varchar(200) NOT NULL,
  `jumlah` int(10) NOT NULL,
  `subbeli` int(10) NOT NULL,
  `subtotal` int(10) NOT NULL,
  `no` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `stok_keluar_daftar`
--

INSERT INTO `stok_keluar_daftar` (`nota`, `kode_barang`, `nama`, `jumlah`, `subbeli`, `subtotal`, `no`) VALUES
('0001', '000018', 'TES', 1, 0, 0, 2);

-- --------------------------------------------------------

--
-- Table structure for table `stok_masuk`
--

CREATE TABLE `stok_masuk` (
  `nota` varchar(10) NOT NULL,
  `cabang` varchar(2) NOT NULL,
  `tgl` date NOT NULL,
  `supplier` varchar(100) NOT NULL,
  `userid` varchar(10) NOT NULL,
  `no` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `stok_masuk`
--

INSERT INTO `stok_masuk` (`nota`, `cabang`, `tgl`, `supplier`, `userid`, `no`) VALUES
('0001', '', '2024-06-01', 'Lantana', '42', 1),
('0002', '', '2024-06-01', 'Lantana', '42', 2),
('0003', '', '2024-06-01', 'Lantana', '42', 3);

-- --------------------------------------------------------

--
-- Table structure for table `stok_masuk_daftar`
--

CREATE TABLE `stok_masuk_daftar` (
  `nota` varchar(10) NOT NULL,
  `kode_barang` varchar(10) NOT NULL,
  `nama` varchar(200) NOT NULL,
  `jumlah` int(10) NOT NULL,
  `no` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `stok_masuk_daftar`
--

INSERT INTO `stok_masuk_daftar` (`nota`, `kode_barang`, `nama`, `jumlah`, `no`) VALUES
('0001', '000018', 'TES', 1, 4),
('0002', '0010', 'Adaptor 2a 12v For DVR Dan CCTV', 1, 5),
('0003', '000018', 'TES', 100, 6);

-- --------------------------------------------------------

--
-- Table structure for table `stok_sesuai`
--

CREATE TABLE `stok_sesuai` (
  `nota` varchar(10) NOT NULL,
  `tgl` date NOT NULL,
  `oleh` varchar(100) NOT NULL,
  `keterangan` varchar(200) NOT NULL,
  `no` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `stok_sesuai_daftar`
--

CREATE TABLE `stok_sesuai_daftar` (
  `nota` varchar(10) NOT NULL,
  `kode_brg` varchar(10) NOT NULL,
  `nama` varchar(200) NOT NULL,
  `sebelum` int(10) NOT NULL,
  `sesudah` int(10) NOT NULL,
  `selisih` int(10) NOT NULL,
  `catatan` varchar(100) NOT NULL,
  `no` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `kode` varchar(20) NOT NULL,
  `tgldaftar` date DEFAULT NULL,
  `nama` varchar(25) DEFAULT NULL,
  `alamat` varchar(70) DEFAULT NULL,
  `nohp` varchar(20) DEFAULT NULL,
  `no` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`kode`, `tgldaftar`, `nama`, `alamat`, `nohp`, `no`) VALUES
('0001', '2024-06-01', 'Lantana', 'aaa', '08128261711', 1);

-- --------------------------------------------------------

--
-- Table structure for table `surat`
--

CREATE TABLE `surat` (
  `nota` varchar(10) NOT NULL,
  `nosurat` varchar(20) NOT NULL,
  `tanggal` date NOT NULL,
  `kode_pelanggan` varchar(10) NOT NULL,
  `tujuan` varchar(30) NOT NULL,
  `notelp` varchar(20) NOT NULL,
  `alamat` varchar(250) NOT NULL,
  `driver` varchar(20) NOT NULL,
  `nohp` varchar(20) NOT NULL,
  `nopol` varchar(10) NOT NULL,
  `oleh` varchar(50) NOT NULL,
  `no` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `userna_me` varchar(20) NOT NULL,
  `pa_ssword` varchar(70) DEFAULT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `nohp` varchar(20) DEFAULT NULL,
  `tgllahir` date DEFAULT NULL,
  `tglaktif` date DEFAULT NULL,
  `jabatan` varchar(20) DEFAULT NULL,
  `avatar` varchar(100) DEFAULT NULL,
  `no` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`userna_me`, `pa_ssword`, `nama`, `alamat`, `nohp`, `tgllahir`, `tglaktif`, `jabatan`, `avatar`, `no`) VALUES
('admin', '90b9aa7e25f80cf4f64e990b78a9fc5ebd6cecad', 'admin', ' admin', '11111', '2020-05-25', '2020-03-26', 'admin', 'dist/upload/index.jpg', 42),
('Administrasi', '10470c3b4b1fed12c3baac014be15fac67c6e815', 'Mkjk', ' Holouh', 'Uilhip', '2020-06-04', '2020-06-02', 'Staff', 'dist/upload/index.jpg', 45),
('agung', 'adcd7048512e64b48da55b027577886ee5a36350', 'aepp', 'mabn', '00000', '2024-04-30', '2024-04-27', 'user', 'dist/upload/index.jpg', 146),
('gudangA', '9582215474dd2a901367f7e5fbaf89d1b3f58bc0', 'gudangA', 'gudangA', '123', '1970-01-28', '2024-03-22', 'user', 'dist/upload/index.jpg', 144),
('gudangB', '16607ddb2eada8c2d8456cc17f0a3fc9eabfa8f8', 'gudangB', 'gudangB', 'gudangB', '2010-02-02', '2024-03-22', 'user', 'dist/upload/index.jpg', 145),
('iyan', '10470c3b4b1fed12c3baac014be15fac67c6e815', 'Andriyan Maulana1', 'c', '089', '2024-05-01', '2024-05-12', 'Operasional', 'dist/upload/index.jpg', 147),
('kacung', 'fe703d258c7ef5f50b71e06565a65aa07194907f', 'kacung', ' ', '', '2024-05-25', '2024-03-05', 'Staff Gudang', 'dist/upload/index.jpg', 142);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `backset`
--
ALTER TABLE `backset`
  ADD PRIMARY KEY (`url`);

--
-- Indexes for table `barang`
--
ALTER TABLE `barang`
  ADD PRIMARY KEY (`kode`),
  ADD KEY `no` (`no`),
  ADD KEY `jenis` (`kategori`);

--
-- Indexes for table `brand`
--
ALTER TABLE `brand`
  ADD PRIMARY KEY (`kode`),
  ADD KEY `no4` (`no`);

--
-- Indexes for table `chmenu`
--
ALTER TABLE `chmenu`
  ADD PRIMARY KEY (`userjabatan`);

--
-- Indexes for table `data`
--
ALTER TABLE `data`
  ADD PRIMARY KEY (`no`);

--
-- Indexes for table `info`
--
ALTER TABLE `info`
  ADD KEY `id` (`id`);

--
-- Indexes for table `jabatan`
--
ALTER TABLE `jabatan`
  ADD PRIMARY KEY (`kode`),
  ADD KEY `no` (`no`);

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`kode`),
  ADD KEY `no4` (`no`);

--
-- Indexes for table `mutasi`
--
ALTER TABLE `mutasi`
  ADD PRIMARY KEY (`no`);

--
-- Indexes for table `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`no`);

--
-- Indexes for table `satuan`
--
ALTER TABLE `satuan`
  ADD PRIMARY KEY (`no`);

--
-- Indexes for table `stok_keluar`
--
ALTER TABLE `stok_keluar`
  ADD PRIMARY KEY (`no`);

--
-- Indexes for table `stok_keluar_daftar`
--
ALTER TABLE `stok_keluar_daftar`
  ADD PRIMARY KEY (`no`);

--
-- Indexes for table `stok_masuk`
--
ALTER TABLE `stok_masuk`
  ADD PRIMARY KEY (`no`);

--
-- Indexes for table `stok_masuk_daftar`
--
ALTER TABLE `stok_masuk_daftar`
  ADD PRIMARY KEY (`no`);

--
-- Indexes for table `stok_sesuai`
--
ALTER TABLE `stok_sesuai`
  ADD PRIMARY KEY (`no`);

--
-- Indexes for table `stok_sesuai_daftar`
--
ALTER TABLE `stok_sesuai_daftar`
  ADD PRIMARY KEY (`no`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`kode`),
  ADD KEY `no3` (`no`);

--
-- Indexes for table `surat`
--
ALTER TABLE `surat`
  ADD PRIMARY KEY (`no`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`userna_me`),
  ADD KEY `no` (`no`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `barang`
--
ALTER TABLE `barang`
  MODIFY `no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `brand`
--
ALTER TABLE `brand`
  MODIFY `no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `info`
--
ALTER TABLE `info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `jabatan`
--
ALTER TABLE `jabatan`
  MODIFY `no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `mutasi`
--
ALTER TABLE `mutasi`
  MODIFY `no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `pelanggan`
--
ALTER TABLE `pelanggan`
  MODIFY `no` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `satuan`
--
ALTER TABLE `satuan`
  MODIFY `no` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=144;

--
-- AUTO_INCREMENT for table `stok_keluar`
--
ALTER TABLE `stok_keluar`
  MODIFY `no` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stok_keluar_daftar`
--
ALTER TABLE `stok_keluar_daftar`
  MODIFY `no` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `stok_masuk`
--
ALTER TABLE `stok_masuk`
  MODIFY `no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `stok_masuk_daftar`
--
ALTER TABLE `stok_masuk_daftar`
  MODIFY `no` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `stok_sesuai`
--
ALTER TABLE `stok_sesuai`
  MODIFY `no` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stok_sesuai_daftar`
--
ALTER TABLE `stok_sesuai_daftar`
  MODIFY `no` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `surat`
--
ALTER TABLE `surat`
  MODIFY `no` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=148;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
