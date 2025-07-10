-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 10 Jul 2025 pada 20.28
-- Versi server: 10.5.27-MariaDB-cll-lve-log
-- Versi PHP: 8.1.32

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `omecoid_inventory`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `backset`
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `backset`
--

INSERT INTO `backset` (`url`, `sessiontime`, `footer`, `themesback`, `responsive`, `namabisnis1`, `mode`, `prefikbarcode`, `loginbg`) VALUES
('https://dev.ome.co.id', '100', 'PT Obormas Mitra Elektirndo', '1', '1', 'Indotory W.E', '0', 'ID', 'dist/upload/images.jpg');

-- --------------------------------------------------------

--
-- Struktur dari tabel `barang`
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `barang`
--

INSERT INTO `barang` (`kode`, `sku`, `nama`, `hargabeli`, `hargajual`, `keterangan`, `kategori`, `satuan`, `terjual`, `terbeli`, `sisa`, `stokmin`, `barcode`, `brand`, `lokasi`, `expired`, `warna`, `ukuran`, `avatar`, `no`) VALUES
('000018', 'SKU000018', 'TES', 0, 0, '', 'Laptop', 'Pcs', 0, 101, 101, 1, 'BRG000018', 'Lap', '', '0000-00-00', '', '', 'dist/upload/', 18),
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
-- Struktur dari tabel `brand`
--

CREATE TABLE `brand` (
  `kode` varchar(20) NOT NULL,
  `nama` varchar(30) DEFAULT NULL,
  `no` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `brand`
--

INSERT INTO `brand` (`kode`, `nama`, `no`) VALUES
('0001', 'Lap', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `chmenu`
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `chmenu`
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
-- Struktur dari tabel `data`
--

CREATE TABLE `data` (
  `nama` varchar(100) DEFAULT NULL,
  `tagline` varchar(100) DEFAULT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `notelp` varchar(20) DEFAULT NULL,
  `signature` varchar(255) DEFAULT NULL,
  `avatar` varchar(150) DEFAULT NULL,
  `no` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `data`
--

INSERT INTO `data` (`nama`, `tagline`, `alamat`, `notelp`, `signature`, `avatar`, `no`) VALUES
('PT OBORMAS MITRA ELEKTRINDO', '', 'Jl. Wadas 3 No.59, RT.003/RW.004, Jaticempaka, Kec. Pd. Gede, Kota Bks, Jawa Barat 17416', '081311333312', 'OME', 'dist/upload/logo-OME.png', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `info`
--

CREATE TABLE `info` (
  `nama` varchar(50) DEFAULT NULL,
  `avatar` varchar(100) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `isi` text DEFAULT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `info`
--

INSERT INTO `info` (`nama`, `avatar`, `tanggal`, `isi`, `id`) VALUES
('admin', 'dist/upload/index.jpg', '2025-07-10', '<p>INFORMASI UNTUK BAGIAN .....</p>', 1),
('admin', 'dist/upload/index.jpg', '2025-07-10', '<p>INFORMASI UNTUK BAGIAN .....</p>', 1),
('admin', 'dist/upload/index.jpg', '2025-07-10', '<p>INFORMASI UNTUK BAGIAN .....</p>', 1),
('admin', 'dist/upload/index.jpg', '2025-07-10', '<p>INFORMASI UNTUK BAGIAN .....</p>', 1),
('admin', 'dist/upload/index.jpg', '2025-07-10', '<p>INFORMASI UNTUK BAGIAN .....</p>', 1),
('admin', 'dist/upload/index.jpg', '2025-07-10', '<p>INFORMASI UNTUK BAGIAN .....</p>', 1),
('admin', 'dist/upload/index.jpg', '2025-07-10', '<p>INFORMASI UNTUK BAGIAN .....</p>', 1),
('admin', 'dist/upload/index.jpg', '2025-07-10', '<p>INFORMASI UNTUK BAGIAN .....</p>', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `jabatan`
--

CREATE TABLE `jabatan` (
  `kode` varchar(20) NOT NULL,
  `nama` varchar(20) DEFAULT NULL,
  `no` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `jabatan`
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
-- Struktur dari tabel `kategori`
--

CREATE TABLE `kategori` (
  `kode` varchar(20) NOT NULL,
  `nama` varchar(30) DEFAULT NULL,
  `no` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `kategori`
--

INSERT INTO `kategori` (`kode`, `nama`, `no`) VALUES
('0001', 'Laptop', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `mutasi`
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `mutasi`
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
('admin', '2024-06-01', '10:48', '000018', 101, 100, 'stok masuk', '0003', 'Lantana', 26, 'berhasil');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pelanggan`
--

CREATE TABLE `pelanggan` (
  `kode` varchar(10) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `notelp` varchar(20) NOT NULL,
  `alamat` varchar(255) NOT NULL,
  `no` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `satuan`
--

CREATE TABLE `satuan` (
  `kode` varchar(10) NOT NULL,
  `nama` varchar(20) NOT NULL,
  `no` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `satuan`
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
-- Struktur dari tabel `stok_keluar`
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `stok_keluar_daftar`
--

CREATE TABLE `stok_keluar_daftar` (
  `nota` varchar(10) NOT NULL,
  `kode_barang` varchar(10) NOT NULL,
  `nama` varchar(200) NOT NULL,
  `jumlah` int(10) NOT NULL,
  `subbeli` int(10) NOT NULL,
  `subtotal` int(10) NOT NULL,
  `no` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `stok_masuk`
--

CREATE TABLE `stok_masuk` (
  `nota` varchar(10) NOT NULL,
  `cabang` varchar(2) NOT NULL,
  `tgl` date NOT NULL,
  `supplier` varchar(100) NOT NULL,
  `userid` varchar(10) NOT NULL,
  `no` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `stok_masuk`
--

INSERT INTO `stok_masuk` (`nota`, `cabang`, `tgl`, `supplier`, `userid`, `no`) VALUES
('0001', '', '2024-06-01', 'Lantana', '42', 1),
('0002', '', '2024-06-01', 'Lantana', '42', 2),
('0003', '', '2024-06-01', 'Lantana', '42', 3),
('0004', '', '2025-07-10', 'Lantana', '42', 4);

-- --------------------------------------------------------

--
-- Struktur dari tabel `stok_masuk_daftar`
--

CREATE TABLE `stok_masuk_daftar` (
  `nota` varchar(10) NOT NULL,
  `kode_barang` varchar(10) NOT NULL,
  `nama` varchar(200) NOT NULL,
  `jumlah` int(10) NOT NULL,
  `no` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `stok_masuk_daftar`
--

INSERT INTO `stok_masuk_daftar` (`nota`, `kode_barang`, `nama`, `jumlah`, `no`) VALUES
('0001', '000018', 'TES', 1, 4),
('0002', '0010', 'Adaptor 2a 12v For DVR Dan CCTV', 1, 5),
('0003', '000018', 'TES', 100, 6);

-- --------------------------------------------------------

--
-- Struktur dari tabel `stok_sesuai`
--

CREATE TABLE `stok_sesuai` (
  `nota` varchar(10) NOT NULL,
  `tgl` date NOT NULL,
  `oleh` varchar(100) NOT NULL,
  `keterangan` varchar(200) NOT NULL,
  `no` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `stok_sesuai_daftar`
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `supplier`
--

CREATE TABLE `supplier` (
  `kode` varchar(20) NOT NULL,
  `tgldaftar` date DEFAULT NULL,
  `nama` varchar(25) DEFAULT NULL,
  `alamat` varchar(70) DEFAULT NULL,
  `nohp` varchar(20) DEFAULT NULL,
  `no` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `supplier`
--

INSERT INTO `supplier` (`kode`, `tgldaftar`, `nama`, `alamat`, `nohp`, `no`) VALUES
('0001', '2024-06-01', 'Lantana', 'aaa', '08128261711', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `surat`
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`userna_me`, `pa_ssword`, `nama`, `alamat`, `nohp`, `tgllahir`, `tglaktif`, `jabatan`, `avatar`, `no`) VALUES
('admin', '90b9aa7e25f80cf4f64e990b78a9fc5ebd6cecad', 'admin', '', '', '2025-01-30', '2020-03-26', 'admin', 'dist/upload/index.jpg', 42),
('admin1', '56b1229e4ea6928294db51555750e313faf87354', 'admin111', '', '', '2025-01-30', '2020-03-26', 'admin', 'dist/upload/index.jpg', 42);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `backset`
--
ALTER TABLE `backset`
  ADD PRIMARY KEY (`url`);

--
-- Indeks untuk tabel `barang`
--
ALTER TABLE `barang`
  ADD PRIMARY KEY (`kode`),
  ADD KEY `no` (`no`),
  ADD KEY `jenis` (`kategori`);

--
-- Indeks untuk tabel `brand`
--
ALTER TABLE `brand`
  ADD PRIMARY KEY (`kode`),
  ADD KEY `no4` (`no`);

--
-- Indeks untuk tabel `chmenu`
--
ALTER TABLE `chmenu`
  ADD PRIMARY KEY (`userjabatan`);

--
-- Indeks untuk tabel `data`
--
ALTER TABLE `data`
  ADD PRIMARY KEY (`no`);

--
-- Indeks untuk tabel `info`
--
ALTER TABLE `info`
  ADD KEY `id` (`id`);

--
-- Indeks untuk tabel `jabatan`
--
ALTER TABLE `jabatan`
  ADD PRIMARY KEY (`kode`),
  ADD KEY `no` (`no`);

--
-- Indeks untuk tabel `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`kode`),
  ADD KEY `no4` (`no`);

--
-- Indeks untuk tabel `mutasi`
--
ALTER TABLE `mutasi`
  ADD PRIMARY KEY (`no`);

--
-- Indeks untuk tabel `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`no`);

--
-- Indeks untuk tabel `satuan`
--
ALTER TABLE `satuan`
  ADD PRIMARY KEY (`no`);

--
-- Indeks untuk tabel `stok_keluar`
--
ALTER TABLE `stok_keluar`
  ADD PRIMARY KEY (`no`);

--
-- Indeks untuk tabel `stok_keluar_daftar`
--
ALTER TABLE `stok_keluar_daftar`
  ADD PRIMARY KEY (`no`);

--
-- Indeks untuk tabel `stok_masuk`
--
ALTER TABLE `stok_masuk`
  ADD PRIMARY KEY (`no`);

--
-- Indeks untuk tabel `stok_masuk_daftar`
--
ALTER TABLE `stok_masuk_daftar`
  ADD PRIMARY KEY (`no`);

--
-- Indeks untuk tabel `stok_sesuai`
--
ALTER TABLE `stok_sesuai`
  ADD PRIMARY KEY (`no`);

--
-- Indeks untuk tabel `stok_sesuai_daftar`
--
ALTER TABLE `stok_sesuai_daftar`
  ADD PRIMARY KEY (`no`);

--
-- Indeks untuk tabel `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`kode`),
  ADD KEY `no3` (`no`);

--
-- Indeks untuk tabel `surat`
--
ALTER TABLE `surat`
  ADD PRIMARY KEY (`no`);

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`userna_me`),
  ADD KEY `no` (`no`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `barang`
--
ALTER TABLE `barang`
  MODIFY `no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT untuk tabel `brand`
--
ALTER TABLE `brand`
  MODIFY `no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `info`
--
ALTER TABLE `info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `jabatan`
--
ALTER TABLE `jabatan`
  MODIFY `no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT untuk tabel `kategori`
--
ALTER TABLE `kategori`
  MODIFY `no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `mutasi`
--
ALTER TABLE `mutasi`
  MODIFY `no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT untuk tabel `pelanggan`
--
ALTER TABLE `pelanggan`
  MODIFY `no` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `satuan`
--
ALTER TABLE `satuan`
  MODIFY `no` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=144;

--
-- AUTO_INCREMENT untuk tabel `stok_keluar`
--
ALTER TABLE `stok_keluar`
  MODIFY `no` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `stok_keluar_daftar`
--
ALTER TABLE `stok_keluar_daftar`
  MODIFY `no` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `stok_masuk`
--
ALTER TABLE `stok_masuk`
  MODIFY `no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `stok_masuk_daftar`
--
ALTER TABLE `stok_masuk_daftar`
  MODIFY `no` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `stok_sesuai`
--
ALTER TABLE `stok_sesuai`
  MODIFY `no` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `stok_sesuai_daftar`
--
ALTER TABLE `stok_sesuai_daftar`
  MODIFY `no` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `supplier`
--
ALTER TABLE `supplier`
  MODIFY `no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `surat`
--
ALTER TABLE `surat`
  MODIFY `no` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `user`
--
ALTER TABLE `user`
  MODIFY `no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=148;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
