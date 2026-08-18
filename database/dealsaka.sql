-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 05, 2026 at 09:00 AM
-- Server version: 8.0.30
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dealsaka`
--

-- --------------------------------------------------------

--
-- Table structure for table `aduan`
--

CREATE TABLE `aduan` (
  `id` int NOT NULL,
  `no_aduan` varchar(35) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal` date NOT NULL,
  `id_kavling` int NOT NULL,
  `id_customer` int NOT NULL,
  `no_kontrak` varchar(35) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `isi_aduan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `stt_aduan` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `aduan_proses`
--

CREATE TABLE `aduan_proses` (
  `id` int NOT NULL,
  `id_aduan` int NOT NULL,
  `tgl_update` date NOT NULL,
  `catatan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `stt_proses_aduan` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `akad`
--

CREATE TABLE `akad` (
  `id` int NOT NULL,
  `tgl_akad` date NOT NULL,
  `keterangan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `balik_nama`
--

CREATE TABLE `balik_nama` (
  `id` int NOT NULL,
  `id_kavling` int NOT NULL,
  `id_lokasi` int NOT NULL,
  `id_customer` int NOT NULL,
  `nama_pengganti` varchar(225) NOT NULL,
  `stt_balik` enum('sudah','belum') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bank`
--

CREATE TABLE `bank` (
  `id` int NOT NULL,
  `nama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_rek` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pemilik_rek` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bank`
--

INSERT INTO `bank` (`id`, `nama`, `no_rek`, `pemilik_rek`) VALUES
(1, 'BTN', '09123132123', 'PT. DNP');

-- --------------------------------------------------------

--
-- Table structure for table `bank_kpr`
--

CREATE TABLE `bank_kpr` (
  `id` int NOT NULL,
  `nama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bank_kpr`
--

INSERT INTO `bank_kpr` (`id`, `nama`) VALUES
(1, 'BRI'),
(2, 'BTN');

-- --------------------------------------------------------

--
-- Table structure for table `barang`
--

CREATE TABLE `barang` (
  `id` int NOT NULL,
  `sku` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_satuan` int NOT NULL,
  `id_supplier` int NOT NULL,
  `stok_awal` int NOT NULL,
  `stok_minimal` int NOT NULL DEFAULT '0',
  `stok` int NOT NULL,
  `harga_beli` int NOT NULL,
  `deskripsi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `harga_jual` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `barang_keluar`
--

CREATE TABLE `barang_keluar` (
  `id` int NOT NULL,
  `tanggal` date NOT NULL,
  `jenis_proyek` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_proyek_bangunan` int DEFAULT NULL,
  `id_proyek_jalan` int DEFAULT NULL,
  `id_proyek_saluran` int DEFAULT NULL,
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `lampiran` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `barang_keluar_detail`
--

CREATE TABLE `barang_keluar_detail` (
  `id` int NOT NULL,
  `id_barang_keluar` int NOT NULL,
  `id_barang` int NOT NULL,
  `jumlah` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `barang_masuk`
--

CREATE TABLE `barang_masuk` (
  `id` int NOT NULL,
  `tanggal` date NOT NULL,
  `id_po` int NOT NULL,
  `nama_penerima` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `barang_masuk_detail`
--

CREATE TABLE `barang_masuk_detail` (
  `id` int NOT NULL,
  `id_masuk` int NOT NULL,
  `id_barang` int NOT NULL,
  `jumlah` int NOT NULL,
  `harga_beli` int NOT NULL,
  `sub_total` int NOT NULL,
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bast`
--

CREATE TABLE `bast` (
  `id` int NOT NULL,
  `id_customer` int NOT NULL,
  `tanggal_bast` date NOT NULL,
  `no_bast` varchar(35) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bphtb_ssp`
--

CREATE TABLE `bphtb_ssp` (
  `id` int NOT NULL,
  `id_lokasi` int NOT NULL,
  `id_kavling` int NOT NULL,
  `status_bphtb` enum('ada','tidak ada') NOT NULL,
  `status_ssp` enum('ada','tidak ada') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `content`
--

CREATE TABLE `content` (
  `id` int NOT NULL,
  `jenis_content` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `judul` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url_item` varchar(35) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama_file` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `artikel` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `icon` varchar(155) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `id` int NOT NULL,
  `kode_customer` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal_verif` timestamp NULL DEFAULT NULL,
  `id_lokasi` int DEFAULT NULL,
  `id_kavling` int DEFAULT NULL,
  `hrg_jual` bigint DEFAULT NULL,
  `biaya_surat` bigint DEFAULT NULL,
  `peningkatan_mutu` bigint DEFAULT NULL,
  `total_harga` bigint DEFAULT NULL,
  `estimasi_plafon` bigint DEFAULT '0',
  `sbum` bigint DEFAULT '0',
  `id_status_progres` int DEFAULT NULL,
  `nama_lengkap` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nik` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jenis_kelamin` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tempat_lahir` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tgl_lahir` date DEFAULT NULL,
  `alamat_ktp` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `alamat_domisili` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status_pernikahan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama_p` varchar(225) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nik_p` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_bpjs_kes` varchar(35) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama_saudara` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_telp_saudara` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jenis_perumahan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_telp` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `npwp` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pekerjaan` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_marketing` int DEFAULT NULL,
  `id_freelance` int DEFAULT NULL,
  `jenis_pembelian` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `an_surat_cash` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `termin_x_cash_b` int DEFAULT '0',
  `stt_arsip` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`id`, `kode_customer`, `tanggal_verif`, `id_lokasi`, `id_kavling`, `hrg_jual`, `biaya_surat`, `peningkatan_mutu`, `total_harga`, `estimasi_plafon`, `sbum`, `id_status_progres`, `nama_lengkap`, `nik`, `jenis_kelamin`, `tempat_lahir`, `tgl_lahir`, `alamat_ktp`, `alamat_domisili`, `status_pernikahan`, `nama_p`, `nik_p`, `no_bpjs_kes`, `nama_saudara`, `no_telp_saudara`, `jenis_perumahan`, `no_telp`, `email`, `npwp`, `pekerjaan`, `id_marketing`, `id_freelance`, `jenis_pembelian`, `an_surat_cash`, `termin_x_cash_b`, `stt_arsip`) VALUES
(1, 'DAR-0001', '2026-07-05 05:30:26', 1, 4, 173000000, 10000000, 10000000, 10000000, 150000000, 0, 2, 'faisal damanik A', '6471052401800007', 'Laki-laki', 'Batam', '1990-07-06', 'Jl. Pegangsaan Timu No. 123', 'Jl. Pegangsaan Timu No. 123', 'Belum Menikah', '', '', '', '', '', 'Subsidi', '081250274777', '', '98234234234', '', 0, 0, 'KPR', 'faisal damanik A', 60, 0);

-- --------------------------------------------------------

--
-- Table structure for table `customer_tempo`
--

CREATE TABLE `customer_tempo` (
  `id` int NOT NULL,
  `kode_customer` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal_verif` timestamp NULL DEFAULT NULL,
  `id_lokasi` int DEFAULT NULL,
  `id_kavling` int DEFAULT NULL,
  `hrg_jual` bigint DEFAULT NULL,
  `biaya_surat` bigint DEFAULT NULL,
  `peningkatan_mutu` bigint DEFAULT NULL,
  `total_harga` bigint DEFAULT NULL,
  `id_status_progres` int DEFAULT NULL,
  `nama_lengkap` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nik` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jenis_kelamin` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tempat_lahir` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tgl_lahir` date DEFAULT NULL,
  `alamat_ktp` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `alamat_domisili` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status_pernikahan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama_p` varchar(225) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nik_p` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_bpjs_kes` varchar(35) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama_saudara` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_telp_saudara` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jenis_perumahan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_telp` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `npwp` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pekerjaan` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_marketing` int DEFAULT NULL,
  `id_freelance` int DEFAULT NULL,
  `jenis_pembelian` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `an_surat_cash` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `termin_x_cash_b` int DEFAULT '0',
  `stt_arsip` int NOT NULL DEFAULT '0',
  `id_user` int NOT NULL,
  `id_customer` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `detail_akad`
--

CREATE TABLE `detail_akad` (
  `id` bigint UNSIGNED NOT NULL,
  `id_akad` int NOT NULL,
  `id_customer` int NOT NULL,
  `id_persyaratan` int NOT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `keterangan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis_akad` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `file_aduan`
--

CREATE TABLE `file_aduan` (
  `id_file_aduan` int NOT NULL,
  `id_aduan` int NOT NULL,
  `id_customer` int NOT NULL,
  `no_kontrak` varchar(35) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_file` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `file_proses_aduan`
--

CREATE TABLE `file_proses_aduan` (
  `id` int NOT NULL,
  `id_aduan` int NOT NULL,
  `nama_file` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `foto_progres`
--

CREATE TABLE `foto_progres` (
  `id` int NOT NULL,
  `id_progres_pembangunan` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_kavling` int NOT NULL,
  `file_foto` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `foto_proyek_bangunan`
--

CREATE TABLE `foto_proyek_bangunan` (
  `id` int NOT NULL,
  `id_proyek_bangunan_detail` int NOT NULL,
  `foto` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `foto_proyek_jalan`
--

CREATE TABLE `foto_proyek_jalan` (
  `id` int NOT NULL,
  `id_proyek_jalan_detail` int NOT NULL,
  `foto` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `foto_proyek_saluran`
--

CREATE TABLE `foto_proyek_saluran` (
  `id` int NOT NULL,
  `id_proyek_saluran_detail` int NOT NULL,
  `foto` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ganti_nama`
--

CREATE TABLE `ganti_nama` (
  `id` int NOT NULL,
  `tgl_ganti` date NOT NULL,
  `id_customer_lama` int NOT NULL,
  `id_customer_baru` int NOT NULL,
  `biaya_ganti_nama` int NOT NULL,
  `lampiran_bukti` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `keterangan_ganti` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ganti_namas`
--

CREATE TABLE `ganti_namas` (
  `id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hak_akses`
--

CREATE TABLE `hak_akses` (
  `id` int NOT NULL,
  `id_user` int NOT NULL,
  `id_menu` int NOT NULL,
  `lihat` int NOT NULL,
  `beranda` int NOT NULL DEFAULT '0',
  `tambah` int NOT NULL,
  `edit` int NOT NULL,
  `hapus` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hak_akses`
--

INSERT INTO `hak_akses` (`id`, `id_user`, `id_menu`, `lihat`, `beranda`, `tambah`, `edit`, `hapus`) VALUES
(1, 16, 1, 1, 0, 0, 0, 0),
(2, 15, 1, 1, 0, 0, 0, 0),
(3, 8, 1, 1, 0, 0, 0, 0),
(4, 18, 1, 1, 0, 0, 0, 0),
(5, 17, 1, 1, 0, 0, 0, 0),
(6, 9, 1, 1, 0, 0, 0, 0),
(7, 14, 1, 1, 0, 0, 0, 0),
(8, 11, 1, 1, 0, 0, 0, 0),
(9, 10, 1, 1, 0, 0, 0, 0),
(10, 4, 1, 1, 0, 0, 0, 0),
(11, 45, 1, 1, 0, 0, 0, 0),
(12, 44, 1, 1, 0, 0, 0, 0),
(13, 43, 1, 1, 0, 0, 0, 0),
(14, 42, 1, 1, 0, 0, 0, 0),
(15, 41, 1, 1, 0, 0, 0, 0),
(16, 40, 1, 1, 0, 0, 0, 0),
(17, 39, 1, 1, 0, 0, 0, 0),
(18, 38, 1, 1, 0, 0, 0, 0),
(19, 37, 1, 1, 0, 0, 0, 0),
(20, 36, 1, 1, 0, 0, 0, 0),
(21, 35, 1, 1, 0, 0, 0, 0),
(22, 34, 1, 1, 0, 0, 0, 0),
(23, 33, 1, 1, 0, 0, 0, 0),
(24, 7, 1, 1, 0, 0, 0, 0),
(25, 13, 1, 1, 0, 0, 0, 0),
(26, 12, 1, 1, 0, 0, 0, 0),
(27, 6, 1, 1, 0, 0, 0, 0),
(28, 1, 1, 1, 0, 0, 0, 0),
(29, 16, 2, 1, 1, 0, 0, 0),
(30, 15, 2, 1, 1, 0, 0, 0),
(31, 8, 2, 1, 1, 0, 0, 0),
(32, 18, 2, 1, 0, 0, 0, 0),
(33, 17, 2, 1, 0, 0, 0, 0),
(34, 9, 2, 1, 0, 0, 0, 0),
(35, 14, 2, 1, 0, 0, 0, 0),
(36, 11, 2, 1, 0, 0, 0, 0),
(37, 10, 2, 1, 0, 0, 0, 0),
(38, 4, 2, 1, 0, 0, 0, 0),
(39, 45, 2, 1, 1, 0, 0, 0),
(40, 44, 2, 1, 1, 0, 0, 0),
(41, 43, 2, 1, 1, 0, 0, 0),
(42, 42, 2, 1, 1, 0, 0, 0),
(43, 41, 2, 1, 1, 0, 0, 0),
(44, 40, 2, 1, 1, 0, 0, 0),
(45, 39, 2, 1, 1, 0, 0, 0),
(46, 38, 2, 1, 1, 0, 0, 0),
(47, 37, 2, 1, 1, 0, 0, 0),
(48, 36, 2, 1, 1, 0, 0, 0),
(49, 35, 2, 1, 1, 0, 0, 0),
(50, 34, 2, 1, 1, 0, 0, 0),
(51, 33, 2, 1, 1, 0, 0, 0),
(52, 7, 2, 1, 1, 0, 0, 0),
(53, 13, 2, 1, 0, 0, 0, 0),
(54, 12, 2, 1, 0, 0, 0, 0),
(55, 6, 2, 1, 0, 0, 0, 0),
(56, 1, 2, 1, 0, 0, 0, 0),
(57, 16, 3, 1, 0, 0, 0, 0),
(58, 15, 3, 1, 0, 0, 0, 0),
(59, 8, 3, 1, 0, 0, 0, 0),
(60, 18, 3, 1, 0, 0, 0, 0),
(61, 17, 3, 1, 0, 0, 0, 0),
(62, 9, 3, 1, 0, 0, 0, 0),
(63, 14, 3, 1, 0, 0, 0, 0),
(64, 11, 3, 1, 0, 0, 0, 0),
(65, 10, 3, 0, 0, 0, 0, 0),
(66, 4, 3, 1, 0, 0, 0, 0),
(67, 45, 3, 1, 0, 0, 0, 0),
(68, 44, 3, 1, 0, 0, 0, 0),
(69, 43, 3, 1, 0, 0, 0, 0),
(70, 42, 3, 1, 0, 0, 0, 0),
(71, 41, 3, 1, 0, 0, 0, 0),
(72, 40, 3, 1, 0, 0, 0, 0),
(73, 39, 3, 1, 0, 0, 0, 0),
(74, 38, 3, 1, 0, 0, 0, 0),
(75, 37, 3, 1, 0, 0, 0, 0),
(76, 36, 3, 1, 0, 0, 0, 0),
(77, 35, 3, 1, 0, 0, 0, 0),
(78, 34, 3, 1, 0, 0, 0, 0),
(79, 33, 3, 1, 0, 0, 0, 0),
(80, 7, 3, 1, 0, 0, 0, 0),
(81, 13, 3, 1, 0, 0, 0, 0),
(82, 12, 3, 1, 0, 0, 0, 0),
(83, 6, 3, 1, 0, 0, 0, 0),
(84, 1, 3, 1, 0, 0, 0, 0),
(85, 16, 4, 0, 0, 0, 1, 0),
(86, 15, 4, 0, 0, 0, 1, 0),
(87, 8, 4, 0, 0, 0, 1, 0),
(88, 18, 4, 0, 0, 0, 1, 0),
(89, 17, 4, 0, 0, 0, 1, 0),
(90, 9, 4, 0, 0, 0, 1, 0),
(91, 14, 4, 1, 0, 0, 1, 0),
(92, 11, 4, 1, 0, 0, 1, 0),
(93, 10, 4, 0, 0, 0, 1, 0),
(94, 4, 4, 1, 0, 0, 1, 0),
(95, 45, 4, 0, 0, 0, 1, 0),
(96, 44, 4, 0, 0, 0, 1, 0),
(97, 43, 4, 0, 0, 0, 1, 0),
(98, 42, 4, 0, 0, 0, 1, 0),
(99, 41, 4, 0, 0, 0, 1, 0),
(100, 40, 4, 0, 0, 0, 1, 0),
(101, 39, 4, 0, 0, 0, 1, 0),
(102, 38, 4, 0, 0, 0, 1, 0),
(103, 37, 4, 0, 0, 0, 1, 0),
(104, 36, 4, 0, 0, 0, 1, 0),
(105, 35, 4, 0, 0, 0, 1, 0),
(106, 34, 4, 0, 0, 0, 1, 0),
(107, 33, 4, 0, 0, 0, 1, 0),
(108, 7, 4, 0, 0, 0, 1, 0),
(109, 13, 4, 1, 0, 0, 1, 0),
(110, 12, 4, 1, 0, 0, 1, 0),
(111, 6, 4, 1, 0, 0, 1, 0),
(112, 1, 4, 1, 0, 0, 1, 0),
(113, 16, 5, 0, 0, 0, 1, 1),
(114, 15, 5, 0, 0, 0, 1, 1),
(115, 8, 5, 0, 0, 0, 1, 1),
(116, 18, 5, 0, 0, 0, 1, 1),
(117, 17, 5, 0, 0, 0, 1, 1),
(118, 9, 5, 0, 0, 0, 1, 1),
(119, 14, 5, 1, 0, 0, 1, 1),
(120, 11, 5, 1, 0, 0, 1, 1),
(121, 10, 5, 0, 0, 0, 0, 0),
(122, 4, 5, 0, 0, 0, 0, 0),
(123, 45, 5, 0, 0, 0, 0, 0),
(124, 44, 5, 0, 0, 0, 0, 0),
(125, 43, 5, 0, 0, 0, 0, 0),
(126, 42, 5, 0, 0, 0, 0, 0),
(127, 41, 5, 0, 0, 0, 0, 0),
(128, 40, 5, 0, 0, 0, 0, 0),
(129, 39, 5, 0, 0, 0, 0, 0),
(130, 38, 5, 0, 0, 0, 0, 0),
(131, 37, 5, 0, 0, 0, 0, 0),
(132, 36, 5, 0, 0, 0, 0, 0),
(133, 35, 5, 0, 0, 0, 0, 0),
(134, 34, 5, 0, 0, 0, 0, 0),
(135, 33, 5, 0, 0, 0, 0, 0),
(136, 7, 5, 0, 0, 0, 0, 0),
(137, 13, 5, 1, 0, 0, 1, 1),
(138, 12, 5, 1, 0, 0, 1, 1),
(139, 6, 5, 1, 0, 0, 1, 1),
(140, 1, 5, 1, 0, 0, 1, 1),
(141, 16, 6, 0, 0, 1, 1, 1),
(142, 15, 6, 0, 0, 1, 1, 1),
(143, 8, 6, 0, 0, 1, 1, 1),
(144, 18, 6, 0, 0, 1, 1, 1),
(145, 17, 6, 0, 0, 1, 1, 1),
(146, 9, 6, 0, 0, 1, 1, 1),
(147, 14, 6, 1, 0, 1, 1, 1),
(148, 11, 6, 1, 0, 1, 1, 1),
(149, 10, 6, 0, 0, 0, 0, 0),
(150, 4, 6, 0, 0, 0, 0, 0),
(151, 45, 6, 0, 0, 1, 1, 1),
(152, 44, 6, 0, 0, 1, 1, 1),
(153, 43, 6, 0, 0, 1, 1, 1),
(154, 42, 6, 0, 0, 1, 1, 1),
(155, 41, 6, 0, 0, 1, 1, 1),
(156, 40, 6, 0, 0, 1, 1, 1),
(157, 39, 6, 0, 0, 1, 1, 1),
(158, 38, 6, 0, 0, 1, 1, 1),
(159, 37, 6, 0, 0, 1, 1, 1),
(160, 36, 6, 0, 0, 1, 1, 1),
(161, 35, 6, 0, 0, 1, 1, 1),
(162, 34, 6, 0, 0, 1, 1, 1),
(163, 33, 6, 0, 0, 1, 1, 1),
(164, 7, 6, 0, 0, 1, 1, 1),
(165, 13, 6, 1, 0, 1, 1, 1),
(166, 12, 6, 1, 0, 1, 1, 1),
(167, 6, 6, 1, 0, 1, 1, 1),
(168, 1, 6, 1, 0, 1, 1, 1),
(169, 16, 7, 1, 0, 0, 0, 0),
(170, 15, 7, 1, 0, 0, 0, 0),
(171, 8, 7, 1, 0, 0, 0, 0),
(172, 18, 7, 0, 0, 0, 0, 0),
(173, 17, 7, 0, 0, 0, 0, 0),
(174, 9, 7, 0, 0, 0, 0, 0),
(175, 14, 7, 1, 0, 0, 0, 0),
(176, 11, 7, 1, 0, 0, 0, 0),
(177, 10, 7, 0, 0, 0, 0, 0),
(178, 4, 7, 0, 0, 0, 0, 0),
(179, 45, 7, 0, 0, 0, 0, 0),
(180, 44, 7, 0, 0, 0, 0, 0),
(181, 43, 7, 0, 0, 0, 0, 0),
(182, 42, 7, 0, 0, 0, 0, 0),
(183, 41, 7, 0, 0, 0, 0, 0),
(184, 40, 7, 0, 0, 0, 0, 0),
(185, 39, 7, 0, 0, 0, 0, 0),
(186, 38, 7, 0, 0, 0, 0, 0),
(187, 37, 7, 0, 0, 0, 0, 0),
(188, 36, 7, 0, 0, 0, 0, 0),
(189, 35, 7, 0, 0, 0, 0, 0),
(190, 34, 7, 0, 0, 0, 0, 0),
(191, 33, 7, 0, 0, 0, 0, 0),
(192, 7, 7, 0, 0, 0, 0, 0),
(193, 13, 7, 1, 0, 0, 0, 0),
(194, 12, 7, 1, 0, 0, 0, 0),
(195, 6, 7, 1, 0, 0, 0, 0),
(196, 1, 7, 1, 0, 0, 0, 0),
(197, 16, 8, 1, 0, 1, 1, 1),
(198, 15, 8, 1, 0, 1, 1, 1),
(199, 8, 8, 1, 0, 1, 1, 1),
(200, 18, 8, 1, 0, 1, 1, 1),
(201, 17, 8, 1, 0, 1, 1, 1),
(202, 9, 8, 1, 0, 1, 1, 1),
(203, 14, 8, 1, 0, 1, 1, 1),
(204, 11, 8, 1, 0, 1, 1, 1),
(205, 10, 8, 0, 0, 0, 0, 0),
(206, 4, 8, 0, 0, 0, 0, 0),
(207, 45, 8, 1, 0, 0, 0, 0),
(208, 44, 8, 1, 0, 0, 0, 0),
(209, 43, 8, 1, 0, 0, 0, 0),
(210, 42, 8, 1, 0, 0, 0, 0),
(211, 41, 8, 1, 0, 0, 0, 0),
(212, 40, 8, 1, 0, 0, 0, 0),
(213, 39, 8, 1, 0, 0, 0, 0),
(214, 38, 8, 1, 0, 0, 0, 0),
(215, 37, 8, 1, 0, 0, 0, 0),
(216, 36, 8, 1, 0, 0, 0, 0),
(217, 35, 8, 1, 0, 0, 0, 0),
(218, 34, 8, 1, 0, 0, 0, 0),
(219, 33, 8, 1, 0, 0, 0, 0),
(220, 7, 8, 1, 0, 0, 0, 0),
(221, 13, 8, 1, 0, 1, 1, 1),
(222, 12, 8, 1, 0, 1, 1, 1),
(223, 6, 8, 1, 0, 1, 1, 1),
(224, 1, 8, 1, 0, 1, 1, 1),
(225, 16, 9, 0, 0, 1, 1, 1),
(226, 15, 9, 0, 0, 1, 1, 1),
(227, 8, 9, 0, 0, 1, 1, 1),
(228, 18, 9, 0, 0, 1, 1, 1),
(229, 17, 9, 0, 0, 1, 1, 1),
(230, 9, 9, 0, 0, 1, 1, 1),
(231, 14, 9, 1, 0, 1, 1, 1),
(232, 11, 9, 1, 0, 1, 1, 1),
(233, 10, 9, 0, 0, 0, 0, 0),
(234, 4, 9, 0, 0, 0, 0, 0),
(235, 45, 9, 0, 0, 1, 1, 1),
(236, 44, 9, 0, 0, 1, 1, 1),
(237, 43, 9, 0, 0, 1, 1, 1),
(238, 42, 9, 0, 0, 1, 1, 1),
(239, 41, 9, 0, 0, 1, 1, 1),
(240, 40, 9, 0, 0, 1, 1, 1),
(241, 39, 9, 0, 0, 1, 1, 1),
(242, 38, 9, 0, 0, 1, 1, 1),
(243, 37, 9, 0, 0, 1, 1, 1),
(244, 36, 9, 0, 0, 1, 1, 1),
(245, 35, 9, 0, 0, 1, 1, 1),
(246, 34, 9, 0, 0, 1, 1, 1),
(247, 33, 9, 0, 0, 1, 1, 1),
(248, 7, 9, 0, 0, 1, 1, 1),
(249, 13, 9, 1, 0, 1, 1, 1),
(250, 12, 9, 1, 0, 1, 1, 1),
(251, 6, 9, 1, 0, 1, 1, 1),
(252, 1, 9, 1, 0, 1, 1, 1),
(253, 16, 10, 0, 0, 0, 0, 0),
(254, 15, 10, 0, 0, 0, 0, 0),
(255, 8, 10, 0, 0, 0, 0, 0),
(256, 18, 10, 0, 0, 0, 0, 0),
(257, 17, 10, 0, 0, 0, 0, 0),
(258, 9, 10, 0, 0, 0, 0, 0),
(259, 14, 10, 1, 0, 0, 0, 0),
(260, 11, 10, 1, 0, 0, 0, 0),
(261, 10, 10, 0, 0, 0, 0, 0),
(262, 4, 10, 1, 0, 0, 0, 0),
(263, 45, 10, 0, 0, 0, 0, 0),
(264, 44, 10, 0, 0, 0, 0, 0),
(265, 43, 10, 0, 0, 0, 0, 0),
(266, 42, 10, 0, 0, 0, 0, 0),
(267, 41, 10, 0, 0, 0, 0, 0),
(268, 40, 10, 0, 0, 0, 0, 0),
(269, 39, 10, 0, 0, 0, 0, 0),
(270, 38, 10, 0, 0, 0, 0, 0),
(271, 37, 10, 0, 0, 0, 0, 0),
(272, 36, 10, 0, 0, 0, 0, 0),
(273, 35, 10, 0, 0, 0, 0, 0),
(274, 34, 10, 0, 0, 0, 0, 0),
(275, 33, 10, 0, 0, 0, 0, 0),
(276, 7, 10, 0, 0, 0, 0, 0),
(277, 13, 10, 1, 0, 0, 0, 0),
(278, 12, 10, 1, 0, 0, 0, 0),
(279, 6, 10, 1, 0, 0, 0, 0),
(280, 1, 10, 0, 0, 0, 0, 0),
(281, 16, 11, 0, 0, 0, 0, 0),
(282, 15, 11, 0, 0, 0, 0, 0),
(283, 8, 11, 0, 0, 0, 0, 0),
(284, 18, 11, 0, 0, 0, 0, 0),
(285, 17, 11, 0, 0, 0, 0, 0),
(286, 9, 11, 0, 0, 0, 0, 0),
(287, 14, 11, 1, 0, 0, 0, 0),
(288, 11, 11, 1, 0, 0, 0, 0),
(289, 10, 11, 0, 0, 0, 0, 0),
(290, 4, 11, 1, 0, 0, 0, 0),
(291, 45, 11, 0, 0, 0, 0, 0),
(292, 44, 11, 0, 0, 0, 0, 0),
(293, 43, 11, 0, 0, 0, 0, 0),
(294, 42, 11, 0, 0, 0, 0, 0),
(295, 41, 11, 0, 0, 0, 0, 0),
(296, 40, 11, 0, 0, 0, 0, 0),
(297, 39, 11, 0, 0, 0, 0, 0),
(298, 38, 11, 0, 0, 0, 0, 0),
(299, 37, 11, 0, 0, 0, 0, 0),
(300, 36, 11, 0, 0, 0, 0, 0),
(301, 35, 11, 0, 0, 0, 0, 0),
(302, 34, 11, 0, 0, 0, 0, 0),
(303, 33, 11, 0, 0, 0, 0, 0),
(304, 7, 11, 0, 0, 0, 0, 0),
(305, 13, 11, 1, 0, 0, 0, 0),
(306, 12, 11, 1, 0, 0, 0, 0),
(307, 6, 11, 1, 0, 0, 0, 0),
(308, 1, 11, 0, 0, 0, 0, 0),
(309, 16, 12, 0, 0, 0, 0, 0),
(310, 15, 12, 0, 0, 0, 0, 0),
(311, 8, 12, 0, 0, 0, 0, 0),
(312, 18, 12, 0, 0, 0, 0, 0),
(313, 17, 12, 0, 0, 0, 0, 0),
(314, 9, 12, 0, 0, 0, 0, 0),
(315, 14, 12, 1, 0, 0, 0, 0),
(316, 11, 12, 1, 0, 0, 0, 0),
(317, 10, 12, 0, 0, 0, 0, 0),
(318, 4, 12, 1, 0, 0, 0, 0),
(319, 45, 12, 0, 0, 0, 0, 0),
(320, 44, 12, 0, 0, 0, 0, 0),
(321, 43, 12, 0, 0, 0, 0, 0),
(322, 42, 12, 0, 0, 0, 0, 0),
(323, 41, 12, 0, 0, 0, 0, 0),
(324, 40, 12, 0, 0, 0, 0, 0),
(325, 39, 12, 0, 0, 0, 0, 0),
(326, 38, 12, 0, 0, 0, 0, 0),
(327, 37, 12, 0, 0, 0, 0, 0),
(328, 36, 12, 0, 0, 0, 0, 0),
(329, 35, 12, 0, 0, 0, 0, 0),
(330, 34, 12, 0, 0, 0, 0, 0),
(331, 33, 12, 0, 0, 0, 0, 0),
(332, 7, 12, 0, 0, 0, 0, 0),
(333, 13, 12, 1, 0, 0, 0, 0),
(334, 12, 12, 1, 0, 0, 0, 0),
(335, 6, 12, 1, 0, 0, 0, 0),
(336, 1, 12, 0, 0, 0, 0, 0),
(337, 16, 13, 0, 0, 0, 0, 0),
(338, 15, 13, 0, 0, 0, 0, 0),
(339, 8, 13, 0, 0, 0, 0, 0),
(340, 18, 13, 1, 0, 0, 0, 0),
(341, 17, 13, 1, 0, 0, 0, 0),
(342, 9, 13, 1, 0, 0, 0, 0),
(343, 14, 13, 1, 0, 0, 0, 0),
(344, 11, 13, 1, 0, 0, 0, 0),
(345, 10, 13, 0, 0, 0, 0, 0),
(346, 4, 13, 0, 0, 0, 0, 0),
(347, 45, 13, 0, 0, 0, 0, 0),
(348, 44, 13, 0, 0, 0, 0, 0),
(349, 43, 13, 0, 0, 0, 0, 0),
(350, 42, 13, 0, 0, 0, 0, 0),
(351, 41, 13, 0, 0, 0, 0, 0),
(352, 40, 13, 0, 0, 0, 0, 0),
(353, 39, 13, 0, 0, 0, 0, 0),
(354, 38, 13, 0, 0, 0, 0, 0),
(355, 37, 13, 0, 0, 0, 0, 0),
(356, 36, 13, 0, 0, 0, 0, 0),
(357, 35, 13, 0, 0, 0, 0, 0),
(358, 34, 13, 0, 0, 0, 0, 0),
(359, 33, 13, 0, 0, 0, 0, 0),
(360, 7, 13, 0, 0, 0, 0, 0),
(361, 13, 13, 1, 0, 0, 0, 0),
(362, 12, 13, 1, 0, 0, 0, 0),
(363, 6, 13, 1, 0, 0, 0, 0),
(364, 1, 13, 1, 0, 0, 0, 0),
(365, 16, 14, 0, 0, 0, 0, 0),
(366, 15, 14, 0, 0, 0, 0, 0),
(367, 8, 14, 0, 0, 0, 0, 0),
(368, 18, 14, 0, 0, 0, 0, 0),
(369, 17, 14, 0, 0, 0, 0, 0),
(370, 9, 14, 0, 0, 0, 0, 0),
(371, 14, 14, 1, 0, 0, 0, 0),
(372, 11, 14, 1, 0, 0, 0, 0),
(373, 10, 14, 0, 0, 0, 0, 0),
(374, 4, 14, 0, 0, 0, 0, 0),
(375, 45, 14, 0, 0, 0, 0, 0),
(376, 44, 14, 0, 0, 0, 0, 0),
(377, 43, 14, 0, 0, 0, 0, 0),
(378, 42, 14, 0, 0, 0, 0, 0),
(379, 41, 14, 0, 0, 0, 0, 0),
(380, 40, 14, 0, 0, 0, 0, 0),
(381, 39, 14, 0, 0, 0, 0, 0),
(382, 38, 14, 0, 0, 0, 0, 0),
(383, 37, 14, 0, 0, 0, 0, 0),
(384, 36, 14, 0, 0, 0, 0, 0),
(385, 35, 14, 0, 0, 0, 0, 0),
(386, 34, 14, 0, 0, 0, 0, 0),
(387, 33, 14, 0, 0, 0, 0, 0),
(388, 7, 14, 0, 0, 0, 0, 0),
(389, 13, 14, 1, 0, 0, 0, 0),
(390, 12, 14, 1, 0, 0, 0, 0),
(391, 6, 14, 1, 0, 0, 0, 0),
(392, 1, 14, 1, 0, 0, 0, 0),
(393, 16, 15, 0, 0, 0, 0, 0),
(394, 15, 15, 0, 0, 0, 0, 0),
(395, 8, 15, 0, 0, 0, 0, 0),
(396, 18, 15, 0, 0, 0, 0, 0),
(397, 17, 15, 0, 0, 0, 0, 0),
(398, 9, 15, 0, 0, 0, 0, 0),
(399, 14, 15, 1, 0, 0, 0, 0),
(400, 11, 15, 1, 0, 0, 0, 0),
(401, 10, 15, 1, 0, 0, 0, 0),
(402, 4, 15, 0, 0, 0, 0, 0),
(403, 45, 15, 0, 0, 0, 0, 0),
(404, 44, 15, 0, 0, 0, 0, 0),
(405, 43, 15, 0, 0, 0, 0, 0),
(406, 42, 15, 0, 0, 0, 0, 0),
(407, 41, 15, 0, 0, 0, 0, 0),
(408, 40, 15, 0, 0, 0, 0, 0),
(409, 39, 15, 0, 0, 0, 0, 0),
(410, 38, 15, 0, 0, 0, 0, 0),
(411, 37, 15, 0, 0, 0, 0, 0),
(412, 36, 15, 0, 0, 0, 0, 0),
(413, 35, 15, 0, 0, 0, 0, 0),
(414, 34, 15, 0, 0, 0, 0, 0),
(415, 33, 15, 0, 0, 0, 0, 0),
(416, 7, 15, 0, 0, 0, 0, 0),
(417, 13, 15, 1, 0, 0, 0, 0),
(418, 12, 15, 1, 0, 0, 0, 0),
(419, 6, 15, 1, 0, 0, 0, 0),
(420, 1, 15, 0, 0, 0, 0, 0),
(421, 16, 16, 0, 0, 1, 1, 1),
(422, 15, 16, 0, 0, 1, 1, 1),
(423, 8, 16, 0, 0, 1, 1, 1),
(424, 18, 16, 0, 0, 1, 1, 1),
(425, 17, 16, 0, 0, 1, 1, 1),
(426, 9, 16, 0, 0, 1, 1, 1),
(427, 14, 16, 1, 0, 1, 1, 1),
(428, 11, 16, 1, 0, 1, 1, 1),
(429, 10, 16, 1, 0, 1, 1, 1),
(430, 4, 16, 0, 0, 0, 0, 0),
(431, 45, 16, 0, 0, 1, 1, 1),
(432, 44, 16, 0, 0, 1, 1, 1),
(433, 43, 16, 0, 0, 1, 1, 1),
(434, 42, 16, 0, 0, 1, 1, 1),
(435, 41, 16, 0, 0, 1, 1, 1),
(436, 40, 16, 0, 0, 1, 1, 1),
(437, 39, 16, 0, 0, 1, 1, 1),
(438, 38, 16, 0, 0, 1, 1, 1),
(439, 37, 16, 0, 0, 1, 1, 1),
(440, 36, 16, 0, 0, 1, 1, 1),
(441, 35, 16, 0, 0, 1, 1, 1),
(442, 34, 16, 0, 0, 1, 1, 1),
(443, 33, 16, 0, 0, 1, 1, 1),
(444, 7, 16, 0, 0, 1, 1, 1),
(445, 13, 16, 1, 0, 1, 1, 1),
(446, 12, 16, 1, 0, 1, 1, 1),
(447, 6, 16, 1, 0, 1, 1, 1),
(448, 1, 16, 0, 0, 1, 1, 1),
(449, 16, 17, 0, 0, 0, 0, 0),
(450, 15, 17, 0, 0, 0, 0, 0),
(451, 8, 17, 0, 0, 0, 0, 0),
(452, 18, 17, 0, 0, 0, 0, 0),
(453, 17, 17, 0, 0, 0, 0, 0),
(454, 9, 17, 0, 0, 0, 0, 0),
(455, 14, 17, 1, 0, 0, 0, 0),
(456, 11, 17, 1, 0, 0, 0, 0),
(457, 10, 17, 1, 0, 0, 0, 0),
(458, 4, 17, 0, 0, 0, 0, 0),
(459, 45, 17, 0, 0, 0, 0, 0),
(460, 44, 17, 0, 0, 0, 0, 0),
(461, 43, 17, 0, 0, 0, 0, 0),
(462, 42, 17, 0, 0, 0, 0, 0),
(463, 41, 17, 0, 0, 0, 0, 0),
(464, 40, 17, 0, 0, 0, 0, 0),
(465, 39, 17, 0, 0, 0, 0, 0),
(466, 38, 17, 0, 0, 0, 0, 0),
(467, 37, 17, 0, 0, 0, 0, 0),
(468, 36, 17, 0, 0, 0, 0, 0),
(469, 35, 17, 0, 0, 0, 0, 0),
(470, 34, 17, 0, 0, 0, 0, 0),
(471, 33, 17, 0, 0, 0, 0, 0),
(472, 7, 17, 0, 0, 0, 0, 0),
(473, 13, 17, 1, 0, 0, 0, 0),
(474, 12, 17, 1, 0, 0, 0, 0),
(475, 6, 17, 1, 0, 0, 0, 0),
(476, 1, 17, 1, 0, 0, 0, 0),
(505, 16, 19, 0, 0, 0, 0, 0),
(506, 15, 19, 0, 0, 0, 0, 0),
(507, 8, 19, 0, 0, 0, 0, 0),
(508, 18, 19, 0, 0, 0, 0, 0),
(509, 17, 19, 0, 0, 0, 0, 0),
(510, 9, 19, 0, 0, 0, 0, 0),
(511, 14, 19, 1, 0, 0, 0, 0),
(512, 11, 19, 1, 0, 0, 0, 0),
(513, 10, 19, 0, 0, 0, 0, 0),
(514, 4, 19, 0, 0, 0, 0, 0),
(515, 45, 19, 0, 0, 0, 0, 0),
(516, 44, 19, 0, 0, 0, 0, 0),
(517, 43, 19, 0, 0, 0, 0, 0),
(518, 42, 19, 0, 0, 0, 0, 0),
(519, 41, 19, 0, 0, 0, 0, 0),
(520, 40, 19, 0, 0, 0, 0, 0),
(521, 39, 19, 0, 0, 0, 0, 0),
(522, 38, 19, 0, 0, 0, 0, 0),
(523, 37, 19, 0, 0, 0, 0, 0),
(524, 36, 19, 0, 0, 0, 0, 0),
(525, 35, 19, 0, 0, 0, 0, 0),
(526, 34, 19, 0, 0, 0, 0, 0),
(527, 33, 19, 0, 0, 0, 0, 0),
(528, 7, 19, 0, 0, 0, 0, 0),
(529, 13, 19, 1, 0, 0, 0, 0),
(530, 12, 19, 1, 0, 0, 0, 0),
(531, 6, 19, 1, 0, 0, 0, 0),
(532, 1, 19, 1, 0, 0, 0, 0),
(533, 16, 20, 1, 1, 0, 0, 0),
(534, 15, 20, 1, 1, 0, 0, 0),
(535, 8, 20, 1, 1, 0, 0, 0),
(536, 18, 20, 1, 0, 0, 0, 0),
(537, 17, 20, 1, 0, 0, 0, 0),
(538, 9, 20, 1, 0, 0, 0, 0),
(539, 14, 20, 1, 0, 0, 0, 0),
(540, 11, 20, 1, 0, 0, 0, 0),
(541, 10, 20, 1, 0, 0, 0, 0),
(542, 4, 20, 0, 0, 0, 0, 0),
(543, 45, 20, 1, 1, 0, 0, 0),
(544, 44, 20, 1, 1, 0, 0, 0),
(545, 43, 20, 1, 1, 0, 0, 0),
(546, 42, 20, 1, 1, 0, 0, 0),
(547, 41, 20, 1, 1, 0, 0, 0),
(548, 40, 20, 1, 1, 0, 0, 0),
(549, 39, 20, 1, 1, 0, 0, 0),
(550, 38, 20, 1, 1, 0, 0, 0),
(551, 37, 20, 1, 1, 0, 0, 0),
(552, 36, 20, 1, 1, 0, 0, 0),
(553, 35, 20, 1, 1, 0, 0, 0),
(554, 34, 20, 1, 1, 0, 0, 0),
(555, 33, 20, 1, 1, 0, 0, 0),
(556, 7, 20, 1, 1, 0, 0, 0),
(557, 13, 20, 1, 0, 0, 0, 0),
(558, 12, 20, 1, 0, 0, 0, 0),
(559, 6, 20, 1, 0, 0, 0, 0),
(560, 1, 20, 1, 0, 0, 0, 0),
(561, 16, 21, 1, 1, 0, 0, 0),
(562, 15, 21, 1, 1, 0, 0, 0),
(563, 8, 21, 1, 1, 0, 0, 0),
(564, 18, 21, 1, 0, 0, 0, 0),
(565, 17, 21, 1, 0, 0, 0, 0),
(566, 9, 21, 1, 0, 0, 0, 0),
(567, 14, 21, 1, 0, 0, 0, 0),
(568, 11, 21, 1, 0, 0, 0, 0),
(569, 10, 21, 1, 0, 0, 0, 0),
(570, 4, 21, 1, 0, 0, 0, 0),
(571, 45, 21, 1, 1, 0, 0, 0),
(572, 44, 21, 1, 1, 0, 0, 0),
(573, 43, 21, 1, 1, 0, 0, 0),
(574, 42, 21, 1, 1, 0, 0, 0),
(575, 41, 21, 1, 1, 0, 0, 0),
(576, 40, 21, 1, 1, 0, 0, 0),
(577, 39, 21, 1, 1, 0, 0, 0),
(578, 38, 21, 1, 1, 0, 0, 0),
(579, 37, 21, 1, 1, 0, 0, 0),
(580, 36, 21, 1, 1, 0, 0, 0),
(581, 35, 21, 1, 1, 0, 0, 0),
(582, 34, 21, 1, 1, 0, 0, 0),
(583, 33, 21, 1, 1, 0, 0, 0),
(584, 7, 21, 1, 1, 0, 0, 0),
(585, 13, 21, 1, 0, 0, 0, 0),
(586, 12, 21, 1, 0, 0, 0, 0),
(587, 6, 21, 1, 0, 0, 0, 0),
(588, 1, 21, 1, 0, 0, 0, 0),
(589, 16, 22, 1, 1, 0, 0, 0),
(590, 15, 22, 1, 1, 0, 0, 0),
(591, 8, 22, 1, 1, 0, 0, 0),
(592, 18, 22, 1, 0, 0, 0, 0),
(593, 17, 22, 1, 0, 0, 0, 0),
(594, 9, 22, 1, 0, 0, 0, 0),
(595, 14, 22, 1, 0, 0, 0, 0),
(596, 11, 22, 1, 0, 0, 0, 0),
(597, 10, 22, 1, 0, 0, 0, 0),
(598, 4, 22, 1, 0, 0, 0, 0),
(599, 45, 22, 1, 1, 0, 0, 0),
(600, 44, 22, 1, 1, 0, 0, 0),
(601, 43, 22, 1, 1, 0, 0, 0),
(602, 42, 22, 1, 1, 0, 0, 0),
(603, 41, 22, 1, 1, 0, 0, 0),
(604, 40, 22, 1, 1, 0, 0, 0),
(605, 39, 22, 1, 1, 0, 0, 0),
(606, 38, 22, 1, 1, 0, 0, 0),
(607, 37, 22, 1, 1, 0, 0, 0),
(608, 36, 22, 1, 1, 0, 0, 0),
(609, 35, 22, 1, 1, 0, 0, 0),
(610, 34, 22, 1, 1, 0, 0, 0),
(611, 33, 22, 1, 1, 0, 0, 0),
(612, 7, 22, 1, 1, 0, 0, 0),
(613, 13, 22, 1, 0, 0, 0, 0),
(614, 12, 22, 1, 0, 0, 0, 0),
(615, 6, 22, 1, 0, 0, 0, 0),
(616, 1, 22, 1, 0, 0, 0, 0),
(617, 16, 23, 1, 1, 1, 1, 1),
(618, 15, 23, 1, 1, 1, 1, 1),
(619, 8, 23, 1, 1, 1, 1, 1),
(620, 18, 23, 0, 0, 1, 1, 1),
(621, 17, 23, 0, 0, 1, 1, 1),
(622, 9, 23, 0, 0, 1, 1, 1),
(623, 14, 23, 1, 0, 1, 1, 1),
(624, 11, 23, 1, 0, 1, 1, 1),
(625, 10, 23, 1, 0, 1, 1, 1),
(626, 4, 23, 1, 0, 1, 1, 1),
(627, 45, 23, 0, 0, 1, 1, 1),
(628, 44, 23, 0, 0, 1, 1, 1),
(629, 43, 23, 0, 0, 1, 1, 1),
(630, 42, 23, 0, 0, 1, 1, 1),
(631, 41, 23, 0, 0, 1, 1, 1),
(632, 40, 23, 0, 0, 1, 1, 1),
(633, 39, 23, 0, 0, 1, 1, 1),
(634, 38, 23, 0, 0, 1, 1, 1),
(635, 37, 23, 0, 0, 1, 1, 1),
(636, 36, 23, 0, 0, 1, 1, 1),
(637, 35, 23, 0, 0, 1, 1, 1),
(638, 34, 23, 0, 0, 1, 1, 1),
(639, 33, 23, 0, 0, 1, 1, 1),
(640, 7, 23, 0, 0, 1, 1, 1),
(641, 13, 23, 1, 0, 1, 1, 1),
(642, 12, 23, 1, 0, 1, 1, 1),
(643, 6, 23, 1, 0, 1, 1, 1),
(644, 1, 23, 1, 0, 1, 1, 1),
(645, 16, 24, 1, 1, 1, 1, 1),
(646, 15, 24, 1, 1, 1, 1, 1),
(647, 8, 24, 1, 1, 1, 1, 1),
(648, 18, 24, 0, 0, 1, 1, 1),
(649, 17, 24, 0, 0, 1, 1, 1),
(650, 9, 24, 0, 0, 1, 1, 1),
(651, 14, 24, 1, 0, 1, 1, 1),
(652, 11, 24, 1, 0, 1, 1, 1),
(653, 10, 24, 1, 0, 1, 1, 1),
(654, 4, 24, 1, 0, 1, 1, 1),
(655, 45, 24, 0, 0, 1, 1, 1),
(656, 44, 24, 0, 0, 1, 1, 1),
(657, 43, 24, 0, 0, 1, 1, 1),
(658, 42, 24, 0, 0, 1, 1, 1),
(659, 41, 24, 0, 0, 1, 1, 1),
(660, 40, 24, 0, 0, 1, 1, 1),
(661, 39, 24, 0, 0, 1, 1, 1),
(662, 38, 24, 0, 0, 1, 1, 1),
(663, 37, 24, 0, 0, 1, 1, 1),
(664, 36, 24, 0, 0, 1, 1, 1),
(665, 35, 24, 0, 0, 1, 1, 1),
(666, 34, 24, 0, 0, 1, 1, 1),
(667, 33, 24, 0, 0, 1, 1, 1),
(668, 7, 24, 0, 0, 1, 1, 1),
(669, 13, 24, 1, 0, 1, 1, 1),
(670, 12, 24, 1, 0, 1, 1, 1),
(671, 6, 24, 1, 0, 1, 1, 1),
(672, 1, 24, 1, 0, 1, 1, 1),
(673, 16, 25, 1, 1, 1, 1, 1),
(674, 15, 25, 1, 1, 1, 1, 1),
(675, 8, 25, 1, 1, 1, 1, 1),
(676, 18, 25, 0, 0, 1, 1, 1),
(677, 17, 25, 0, 0, 1, 1, 1),
(678, 9, 25, 0, 0, 1, 1, 1),
(679, 14, 25, 1, 0, 1, 1, 1),
(680, 11, 25, 1, 0, 1, 1, 1),
(681, 10, 25, 1, 0, 1, 1, 1),
(682, 4, 25, 1, 0, 1, 1, 1),
(683, 45, 25, 0, 0, 1, 1, 1),
(684, 44, 25, 0, 0, 1, 1, 1),
(685, 43, 25, 0, 0, 1, 1, 1),
(686, 42, 25, 0, 0, 1, 1, 1),
(687, 41, 25, 0, 0, 1, 1, 1),
(688, 40, 25, 0, 0, 1, 1, 1),
(689, 39, 25, 0, 0, 1, 1, 1),
(690, 38, 25, 0, 0, 1, 1, 1),
(691, 37, 25, 0, 0, 1, 1, 1),
(692, 36, 25, 0, 0, 1, 1, 1),
(693, 35, 25, 0, 0, 1, 1, 1),
(694, 34, 25, 0, 0, 1, 1, 1),
(695, 33, 25, 0, 0, 1, 1, 1),
(696, 7, 25, 0, 0, 1, 1, 1),
(697, 13, 25, 1, 0, 1, 1, 1),
(698, 12, 25, 1, 0, 1, 1, 1),
(699, 6, 25, 1, 0, 1, 1, 1),
(700, 1, 25, 1, 0, 1, 1, 1),
(701, 16, 26, 0, 0, 0, 0, 0),
(702, 15, 26, 0, 0, 0, 0, 0),
(703, 8, 26, 0, 0, 0, 0, 0),
(704, 18, 26, 0, 0, 1, 1, 1),
(705, 17, 26, 0, 0, 1, 1, 1),
(706, 9, 26, 0, 0, 1, 1, 1),
(707, 14, 26, 1, 0, 1, 1, 1),
(708, 11, 26, 1, 0, 1, 1, 1),
(709, 10, 26, 1, 0, 1, 1, 1),
(710, 4, 26, 1, 0, 1, 1, 1),
(711, 45, 26, 0, 0, 1, 1, 1),
(712, 44, 26, 0, 0, 1, 1, 1),
(713, 43, 26, 0, 0, 1, 1, 1),
(714, 42, 26, 0, 0, 1, 1, 1),
(715, 41, 26, 0, 0, 1, 1, 1),
(716, 40, 26, 0, 0, 1, 1, 1),
(717, 39, 26, 0, 0, 1, 1, 1),
(718, 38, 26, 0, 0, 1, 1, 1),
(719, 37, 26, 0, 0, 1, 1, 1),
(720, 36, 26, 0, 0, 1, 1, 1),
(721, 35, 26, 0, 0, 1, 1, 1),
(722, 34, 26, 0, 0, 1, 1, 1),
(723, 33, 26, 0, 0, 1, 1, 1),
(724, 7, 26, 0, 0, 1, 1, 1),
(725, 13, 26, 1, 0, 1, 1, 1),
(726, 12, 26, 1, 0, 1, 1, 1),
(727, 6, 26, 1, 0, 1, 1, 1),
(728, 1, 26, 1, 0, 1, 1, 1),
(729, 16, 27, 0, 0, 1, 1, 1),
(730, 15, 27, 0, 0, 1, 1, 1),
(731, 8, 27, 0, 0, 1, 1, 1),
(732, 18, 27, 0, 0, 1, 1, 1),
(733, 17, 27, 0, 0, 1, 1, 1),
(734, 9, 27, 0, 0, 1, 1, 1),
(735, 14, 27, 1, 0, 1, 1, 1),
(736, 11, 27, 1, 0, 1, 1, 1),
(737, 10, 27, 1, 0, 1, 1, 1),
(738, 4, 27, 1, 0, 1, 1, 1),
(739, 45, 27, 0, 0, 1, 1, 1),
(740, 44, 27, 0, 0, 1, 1, 1),
(741, 43, 27, 0, 0, 1, 1, 1),
(742, 42, 27, 0, 0, 1, 1, 1),
(743, 41, 27, 0, 0, 1, 1, 1),
(744, 40, 27, 0, 0, 1, 1, 1),
(745, 39, 27, 0, 0, 1, 1, 1),
(746, 38, 27, 0, 0, 1, 1, 1),
(747, 37, 27, 0, 0, 1, 1, 1),
(748, 36, 27, 0, 0, 1, 1, 1),
(749, 35, 27, 0, 0, 1, 1, 1),
(750, 34, 27, 0, 0, 1, 1, 1),
(751, 33, 27, 0, 0, 1, 1, 1),
(752, 7, 27, 0, 0, 1, 1, 1),
(753, 13, 27, 1, 0, 1, 1, 1),
(754, 12, 27, 1, 0, 1, 1, 1),
(755, 6, 27, 1, 0, 1, 1, 1),
(756, 1, 27, 1, 0, 1, 1, 1),
(757, 16, 28, 0, 0, 1, 1, 1),
(758, 15, 28, 0, 0, 1, 1, 1),
(759, 8, 28, 0, 0, 1, 1, 1),
(760, 18, 28, 0, 0, 1, 1, 1),
(761, 17, 28, 0, 0, 1, 1, 1),
(762, 9, 28, 0, 0, 1, 1, 1),
(763, 14, 28, 1, 0, 1, 1, 1),
(764, 11, 28, 1, 0, 1, 1, 1),
(765, 10, 28, 1, 0, 1, 1, 1),
(766, 4, 28, 1, 0, 1, 1, 1),
(767, 45, 28, 0, 0, 1, 1, 1),
(768, 44, 28, 0, 0, 1, 1, 1),
(769, 43, 28, 0, 0, 1, 1, 1),
(770, 42, 28, 0, 0, 1, 1, 1),
(771, 41, 28, 0, 0, 1, 1, 1),
(772, 40, 28, 0, 0, 1, 1, 1),
(773, 39, 28, 0, 0, 1, 1, 1),
(774, 38, 28, 0, 0, 1, 1, 1),
(775, 37, 28, 0, 0, 1, 1, 1),
(776, 36, 28, 0, 0, 1, 1, 1),
(777, 35, 28, 0, 0, 1, 1, 1),
(778, 34, 28, 0, 0, 1, 1, 1),
(779, 33, 28, 0, 0, 1, 1, 1),
(780, 7, 28, 0, 0, 1, 1, 1),
(781, 13, 28, 1, 0, 1, 1, 1),
(782, 12, 28, 1, 0, 1, 1, 1),
(783, 6, 28, 1, 0, 1, 1, 1),
(784, 1, 28, 1, 0, 1, 1, 1),
(785, 16, 29, 1, 0, 0, 0, 0),
(786, 15, 29, 1, 0, 0, 0, 0),
(787, 8, 29, 1, 0, 0, 0, 0),
(788, 18, 29, 1, 1, 0, 0, 0),
(789, 17, 29, 1, 1, 0, 0, 0),
(790, 9, 29, 1, 1, 0, 0, 0),
(791, 14, 29, 1, 0, 1, 1, 1),
(792, 11, 29, 1, 0, 1, 1, 1),
(793, 10, 29, 1, 0, 1, 1, 1),
(794, 4, 29, 1, 0, 1, 1, 1),
(795, 45, 29, 1, 0, 0, 0, 0),
(796, 44, 29, 1, 0, 0, 0, 0),
(797, 43, 29, 1, 0, 0, 0, 0),
(798, 42, 29, 1, 0, 0, 0, 0),
(799, 41, 29, 1, 0, 0, 0, 0),
(800, 40, 29, 1, 0, 0, 0, 0),
(801, 39, 29, 1, 0, 0, 0, 0),
(802, 38, 29, 1, 0, 0, 0, 0),
(803, 37, 29, 1, 0, 0, 0, 0),
(804, 36, 29, 1, 0, 0, 0, 0),
(805, 35, 29, 1, 0, 0, 0, 0),
(806, 34, 29, 1, 0, 0, 0, 0),
(807, 33, 29, 1, 0, 0, 0, 0),
(808, 7, 29, 1, 0, 0, 0, 0),
(809, 13, 29, 1, 0, 1, 1, 1),
(810, 12, 29, 1, 0, 1, 1, 1),
(811, 6, 29, 1, 0, 1, 1, 1),
(812, 1, 29, 1, 0, 1, 1, 1),
(813, 16, 30, 0, 0, 1, 1, 1),
(814, 15, 30, 0, 0, 1, 1, 1),
(815, 8, 30, 0, 0, 1, 1, 1),
(816, 18, 30, 0, 0, 1, 1, 1),
(817, 17, 30, 0, 0, 1, 1, 1),
(818, 9, 30, 0, 0, 1, 1, 1),
(819, 14, 30, 1, 0, 1, 1, 1),
(820, 11, 30, 1, 0, 1, 1, 1),
(821, 10, 30, 1, 0, 1, 1, 1),
(822, 4, 30, 1, 0, 1, 1, 1),
(823, 45, 30, 0, 0, 1, 1, 1),
(824, 44, 30, 0, 0, 1, 1, 1),
(825, 43, 30, 0, 0, 1, 1, 1),
(826, 42, 30, 0, 0, 1, 1, 1),
(827, 41, 30, 0, 0, 1, 1, 1),
(828, 40, 30, 0, 0, 1, 1, 1),
(829, 39, 30, 0, 0, 1, 1, 1),
(830, 38, 30, 0, 0, 1, 1, 1),
(831, 37, 30, 0, 0, 1, 1, 1),
(832, 36, 30, 0, 0, 1, 1, 1),
(833, 35, 30, 0, 0, 1, 1, 1),
(834, 34, 30, 0, 0, 1, 1, 1),
(835, 33, 30, 0, 0, 1, 1, 1),
(836, 7, 30, 0, 0, 1, 1, 1),
(837, 13, 30, 1, 0, 1, 1, 1),
(838, 12, 30, 1, 0, 1, 1, 1),
(839, 6, 30, 1, 0, 1, 1, 1),
(840, 1, 30, 1, 0, 1, 1, 1),
(841, 16, 31, 1, 0, 1, 1, 1),
(842, 15, 31, 1, 0, 1, 1, 1),
(843, 8, 31, 1, 0, 1, 1, 1),
(844, 18, 31, 0, 0, 1, 1, 1),
(845, 17, 31, 0, 0, 1, 1, 1),
(846, 9, 31, 0, 0, 1, 1, 1),
(847, 14, 31, 1, 0, 1, 1, 1),
(848, 11, 31, 1, 0, 1, 1, 1),
(849, 10, 31, 1, 0, 1, 1, 1),
(850, 4, 31, 1, 0, 1, 1, 1),
(851, 45, 31, 0, 0, 1, 1, 1),
(852, 44, 31, 0, 0, 1, 1, 1),
(853, 43, 31, 0, 0, 1, 1, 1),
(854, 42, 31, 0, 0, 1, 1, 1),
(855, 41, 31, 0, 0, 1, 1, 1),
(856, 40, 31, 0, 0, 1, 1, 1),
(857, 39, 31, 0, 0, 1, 1, 1),
(858, 38, 31, 0, 0, 1, 1, 1),
(859, 37, 31, 0, 0, 1, 1, 1),
(860, 36, 31, 0, 0, 1, 1, 1),
(861, 35, 31, 0, 0, 1, 1, 1),
(862, 34, 31, 0, 0, 1, 1, 1),
(863, 33, 31, 0, 0, 1, 1, 1),
(864, 7, 31, 0, 0, 1, 1, 1),
(865, 13, 31, 1, 0, 1, 1, 1),
(866, 12, 31, 1, 0, 1, 1, 1),
(867, 6, 31, 1, 0, 1, 1, 1),
(868, 1, 31, 1, 0, 1, 1, 1),
(869, 16, 32, 0, 0, 0, 0, 1),
(870, 15, 32, 0, 0, 0, 0, 1),
(871, 8, 32, 0, 0, 0, 0, 1),
(872, 18, 32, 0, 0, 0, 0, 1),
(873, 17, 32, 0, 0, 0, 0, 1),
(874, 9, 32, 0, 0, 0, 0, 1),
(875, 14, 32, 1, 0, 0, 0, 1),
(876, 11, 32, 1, 0, 0, 0, 1),
(877, 10, 32, 1, 0, 0, 0, 1),
(878, 4, 32, 1, 0, 0, 0, 1),
(879, 45, 32, 0, 0, 0, 0, 1),
(880, 44, 32, 0, 0, 0, 0, 1),
(881, 43, 32, 0, 0, 0, 0, 1),
(882, 42, 32, 0, 0, 0, 0, 1),
(883, 41, 32, 0, 0, 0, 0, 1),
(884, 40, 32, 0, 0, 0, 0, 1),
(885, 39, 32, 0, 0, 0, 0, 1),
(886, 38, 32, 0, 0, 0, 0, 1),
(887, 37, 32, 0, 0, 0, 0, 1),
(888, 36, 32, 0, 0, 0, 0, 1),
(889, 35, 32, 0, 0, 0, 0, 1),
(890, 34, 32, 0, 0, 0, 0, 1),
(891, 33, 32, 0, 0, 0, 0, 1),
(892, 7, 32, 0, 0, 0, 0, 1),
(893, 13, 32, 1, 0, 0, 0, 1),
(894, 12, 32, 1, 0, 0, 0, 1),
(895, 6, 32, 1, 0, 0, 0, 1),
(896, 1, 32, 1, 0, 0, 0, 1),
(897, 16, 33, 0, 0, 1, 1, 1),
(898, 15, 33, 0, 0, 1, 1, 1),
(899, 8, 33, 0, 0, 1, 1, 1),
(900, 18, 33, 0, 0, 1, 1, 1),
(901, 17, 33, 0, 0, 1, 1, 1),
(902, 9, 33, 0, 0, 1, 1, 1),
(903, 14, 33, 1, 0, 1, 1, 1),
(904, 11, 33, 1, 0, 1, 1, 1),
(905, 10, 33, 1, 0, 1, 1, 1),
(906, 4, 33, 1, 0, 1, 1, 1),
(907, 45, 33, 0, 0, 1, 1, 1),
(908, 44, 33, 0, 0, 1, 1, 1),
(909, 43, 33, 0, 0, 1, 1, 1),
(910, 42, 33, 0, 0, 1, 1, 1),
(911, 41, 33, 0, 0, 1, 1, 1),
(912, 40, 33, 0, 0, 1, 1, 1),
(913, 39, 33, 0, 0, 1, 1, 1),
(914, 38, 33, 0, 0, 1, 1, 1),
(915, 37, 33, 0, 0, 1, 1, 1),
(916, 36, 33, 0, 0, 1, 1, 1),
(917, 35, 33, 0, 0, 1, 1, 1),
(918, 34, 33, 0, 0, 1, 1, 1),
(919, 33, 33, 1, 0, 1, 1, 1),
(920, 7, 33, 0, 0, 1, 1, 1),
(921, 13, 33, 1, 0, 1, 1, 1),
(922, 12, 33, 1, 0, 1, 1, 1),
(923, 6, 33, 1, 0, 1, 1, 1),
(924, 1, 33, 1, 0, 1, 1, 1),
(925, 16, 34, 0, 0, 1, 1, 1),
(926, 15, 34, 0, 0, 1, 1, 1),
(927, 8, 34, 0, 0, 1, 1, 1),
(928, 18, 34, 0, 0, 1, 1, 1),
(929, 17, 34, 0, 0, 1, 1, 1),
(930, 9, 34, 0, 0, 1, 1, 1),
(931, 14, 34, 1, 0, 1, 1, 1),
(932, 11, 34, 1, 0, 1, 1, 1),
(933, 10, 34, 1, 0, 1, 1, 1),
(934, 4, 34, 1, 0, 1, 1, 1),
(935, 45, 34, 0, 0, 1, 1, 1),
(936, 44, 34, 0, 0, 1, 1, 1),
(937, 43, 34, 0, 0, 1, 1, 1),
(938, 42, 34, 0, 0, 1, 1, 1),
(939, 41, 34, 0, 0, 1, 1, 1),
(940, 40, 34, 0, 0, 1, 1, 1),
(941, 39, 34, 0, 0, 1, 1, 1),
(942, 38, 34, 0, 0, 1, 1, 1),
(943, 37, 34, 0, 0, 1, 1, 1),
(944, 36, 34, 0, 0, 1, 1, 1),
(945, 35, 34, 0, 0, 1, 1, 1),
(946, 34, 34, 0, 0, 1, 1, 1),
(947, 33, 34, 0, 0, 1, 1, 1),
(948, 7, 34, 0, 0, 1, 1, 1),
(949, 13, 34, 1, 0, 1, 1, 1),
(950, 12, 34, 1, 0, 1, 1, 1),
(951, 6, 34, 1, 0, 1, 1, 1),
(952, 1, 34, 1, 0, 1, 1, 1),
(953, 16, 35, 0, 0, 1, 1, 1),
(954, 15, 35, 0, 0, 1, 1, 1),
(955, 8, 35, 0, 0, 1, 1, 1),
(956, 18, 35, 0, 0, 1, 1, 1),
(957, 17, 35, 0, 0, 1, 1, 1),
(958, 9, 35, 0, 0, 1, 1, 1),
(959, 14, 35, 1, 0, 1, 1, 1),
(960, 11, 35, 1, 0, 1, 1, 1),
(961, 10, 35, 1, 0, 1, 1, 1),
(962, 4, 35, 1, 0, 1, 1, 1),
(963, 45, 35, 0, 0, 1, 1, 1),
(964, 44, 35, 0, 0, 1, 1, 1),
(965, 43, 35, 0, 0, 1, 1, 1),
(966, 42, 35, 0, 0, 1, 1, 1),
(967, 41, 35, 0, 0, 1, 1, 1),
(968, 40, 35, 0, 0, 1, 1, 1),
(969, 39, 35, 0, 0, 1, 1, 1),
(970, 38, 35, 0, 0, 1, 1, 1),
(971, 37, 35, 0, 0, 1, 1, 1),
(972, 36, 35, 0, 0, 1, 1, 1),
(973, 35, 35, 0, 0, 1, 1, 1),
(974, 34, 35, 0, 0, 1, 1, 1),
(975, 33, 35, 0, 0, 1, 1, 1),
(976, 7, 35, 0, 0, 1, 1, 1),
(977, 13, 35, 1, 0, 1, 1, 1),
(978, 12, 35, 1, 0, 1, 1, 1),
(979, 6, 35, 1, 0, 1, 1, 1),
(980, 1, 35, 1, 0, 1, 1, 1),
(981, 16, 36, 0, 0, 1, 1, 1),
(982, 15, 36, 0, 0, 1, 1, 1),
(983, 8, 36, 0, 0, 1, 1, 1),
(984, 18, 36, 0, 0, 1, 1, 1),
(985, 17, 36, 0, 0, 1, 1, 1),
(986, 9, 36, 0, 0, 1, 1, 1),
(987, 14, 36, 1, 0, 1, 1, 1),
(988, 11, 36, 1, 0, 1, 1, 1),
(989, 10, 36, 1, 0, 1, 1, 1),
(990, 4, 36, 1, 0, 1, 1, 1),
(991, 45, 36, 0, 0, 1, 1, 1),
(992, 44, 36, 0, 0, 1, 1, 1),
(993, 43, 36, 0, 0, 1, 1, 1),
(994, 42, 36, 0, 0, 1, 1, 1),
(995, 41, 36, 0, 0, 1, 1, 1),
(996, 40, 36, 0, 0, 1, 1, 1),
(997, 39, 36, 0, 0, 1, 1, 1),
(998, 38, 36, 0, 0, 1, 1, 1),
(999, 37, 36, 0, 0, 1, 1, 1),
(1000, 36, 36, 0, 0, 1, 1, 1),
(1001, 35, 36, 0, 0, 1, 1, 1),
(1002, 34, 36, 0, 0, 1, 1, 1),
(1003, 33, 36, 0, 0, 1, 1, 1),
(1004, 7, 36, 0, 0, 1, 1, 1),
(1005, 13, 36, 1, 0, 1, 1, 1),
(1006, 12, 36, 1, 0, 1, 1, 1),
(1007, 6, 36, 1, 0, 1, 1, 1),
(1008, 1, 36, 1, 0, 1, 1, 1),
(1009, 16, 37, 0, 0, 1, 1, 1),
(1010, 15, 37, 0, 0, 1, 1, 1),
(1011, 8, 37, 0, 0, 1, 1, 1),
(1012, 18, 37, 0, 0, 1, 1, 1),
(1013, 17, 37, 0, 0, 1, 1, 1),
(1014, 9, 37, 0, 0, 1, 1, 1),
(1015, 14, 37, 1, 0, 1, 1, 1),
(1016, 11, 37, 1, 0, 1, 1, 1),
(1017, 10, 37, 1, 0, 1, 1, 1),
(1018, 4, 37, 1, 0, 1, 1, 1),
(1019, 45, 37, 0, 0, 1, 1, 1),
(1020, 44, 37, 0, 0, 1, 1, 1),
(1021, 43, 37, 0, 0, 1, 1, 1),
(1022, 42, 37, 0, 0, 1, 1, 1),
(1023, 41, 37, 0, 0, 1, 1, 1),
(1024, 40, 37, 0, 0, 1, 1, 1),
(1025, 39, 37, 0, 0, 1, 1, 1),
(1026, 38, 37, 0, 0, 1, 1, 1),
(1027, 37, 37, 0, 0, 1, 1, 1),
(1028, 36, 37, 0, 0, 1, 1, 1),
(1029, 35, 37, 0, 0, 1, 1, 1),
(1030, 34, 37, 0, 0, 1, 1, 1),
(1031, 33, 37, 0, 0, 1, 1, 1),
(1032, 7, 37, 0, 0, 1, 1, 1),
(1033, 13, 37, 1, 0, 1, 1, 1),
(1034, 12, 37, 1, 0, 1, 1, 1),
(1035, 6, 37, 1, 0, 1, 1, 1),
(1036, 1, 37, 0, 0, 1, 1, 1),
(1037, 16, 38, 0, 0, 1, 1, 1),
(1038, 15, 38, 0, 0, 1, 1, 1),
(1039, 8, 38, 0, 0, 1, 1, 1),
(1040, 18, 38, 0, 0, 1, 1, 1),
(1041, 17, 38, 0, 0, 1, 1, 1),
(1042, 9, 38, 0, 0, 1, 1, 1),
(1043, 14, 38, 1, 0, 1, 1, 1),
(1044, 11, 38, 1, 0, 1, 1, 1),
(1045, 10, 38, 1, 0, 1, 1, 1),
(1046, 4, 38, 1, 0, 1, 1, 1),
(1047, 45, 38, 0, 0, 1, 1, 1),
(1048, 44, 38, 0, 0, 1, 1, 1),
(1049, 43, 38, 0, 0, 1, 1, 1),
(1050, 42, 38, 0, 0, 1, 1, 1),
(1051, 41, 38, 0, 0, 1, 1, 1),
(1052, 40, 38, 0, 0, 1, 1, 1),
(1053, 39, 38, 0, 0, 1, 1, 1),
(1054, 38, 38, 0, 0, 1, 1, 1),
(1055, 37, 38, 0, 0, 1, 1, 1),
(1056, 36, 38, 0, 0, 1, 1, 1),
(1057, 35, 38, 0, 0, 1, 1, 1),
(1058, 34, 38, 0, 0, 1, 1, 1),
(1059, 33, 38, 0, 0, 1, 1, 1),
(1060, 7, 38, 0, 0, 1, 1, 1),
(1061, 13, 38, 1, 0, 1, 1, 1),
(1062, 12, 38, 1, 0, 1, 1, 1),
(1063, 6, 38, 1, 0, 1, 1, 1),
(1064, 1, 38, 0, 0, 1, 1, 1),
(1065, 16, 39, 0, 0, 1, 1, 1),
(1066, 15, 39, 0, 0, 1, 1, 1),
(1067, 8, 39, 0, 0, 1, 1, 1),
(1068, 18, 39, 0, 0, 1, 1, 1),
(1069, 17, 39, 0, 0, 1, 1, 1),
(1070, 9, 39, 0, 0, 1, 1, 1),
(1071, 14, 39, 1, 0, 1, 1, 1),
(1072, 11, 39, 1, 0, 1, 1, 1),
(1073, 10, 39, 1, 0, 1, 1, 1),
(1074, 4, 39, 1, 0, 1, 1, 1),
(1075, 45, 39, 0, 0, 1, 1, 1),
(1076, 44, 39, 0, 0, 1, 1, 1),
(1077, 43, 39, 0, 0, 1, 1, 1),
(1078, 42, 39, 0, 0, 1, 1, 1),
(1079, 41, 39, 0, 0, 1, 1, 1),
(1080, 40, 39, 0, 0, 1, 1, 1),
(1081, 39, 39, 0, 0, 1, 1, 1),
(1082, 38, 39, 0, 0, 1, 1, 1),
(1083, 37, 39, 0, 0, 1, 1, 1),
(1084, 36, 39, 0, 0, 1, 1, 1),
(1085, 35, 39, 0, 0, 1, 1, 1),
(1086, 34, 39, 0, 0, 1, 1, 1),
(1087, 33, 39, 0, 0, 1, 1, 1),
(1088, 7, 39, 0, 0, 1, 1, 1),
(1089, 13, 39, 1, 0, 1, 1, 1),
(1090, 12, 39, 1, 0, 1, 1, 1),
(1091, 6, 39, 1, 0, 1, 1, 1),
(1092, 1, 39, 0, 0, 1, 1, 1),
(1093, 16, 40, 0, 0, 1, 1, 1),
(1094, 15, 40, 0, 0, 1, 1, 1),
(1095, 8, 40, 0, 0, 1, 1, 1),
(1096, 18, 40, 0, 0, 1, 1, 1),
(1097, 17, 40, 0, 0, 1, 1, 1),
(1098, 9, 40, 0, 0, 1, 1, 1),
(1099, 14, 40, 1, 0, 1, 1, 1),
(1100, 11, 40, 1, 0, 1, 1, 1),
(1101, 10, 40, 1, 0, 1, 1, 1),
(1102, 4, 40, 1, 0, 1, 1, 1),
(1103, 45, 40, 0, 0, 1, 1, 1),
(1104, 44, 40, 0, 0, 1, 1, 1),
(1105, 43, 40, 0, 0, 1, 1, 1),
(1106, 42, 40, 0, 0, 1, 1, 1),
(1107, 41, 40, 0, 0, 1, 1, 1),
(1108, 40, 40, 0, 0, 1, 1, 1),
(1109, 39, 40, 0, 0, 1, 1, 1),
(1110, 38, 40, 0, 0, 1, 1, 1),
(1111, 37, 40, 0, 0, 1, 1, 1),
(1112, 36, 40, 0, 0, 1, 1, 1),
(1113, 35, 40, 0, 0, 1, 1, 1),
(1114, 34, 40, 0, 0, 1, 1, 1),
(1115, 33, 40, 0, 0, 1, 1, 1),
(1116, 7, 40, 0, 0, 1, 1, 1),
(1117, 13, 40, 1, 0, 1, 1, 1),
(1118, 12, 40, 1, 0, 1, 1, 1),
(1119, 6, 40, 1, 0, 1, 1, 1),
(1120, 1, 40, 0, 0, 1, 1, 1),
(1121, 16, 41, 0, 0, 1, 1, 1),
(1122, 15, 41, 0, 0, 1, 1, 1),
(1123, 8, 41, 0, 0, 1, 1, 1),
(1124, 18, 41, 0, 0, 1, 1, 1),
(1125, 17, 41, 0, 0, 1, 1, 1),
(1126, 9, 41, 0, 0, 1, 1, 1),
(1127, 14, 41, 1, 0, 1, 1, 1),
(1128, 11, 41, 1, 0, 1, 1, 1),
(1129, 10, 41, 1, 0, 1, 1, 1),
(1130, 4, 41, 1, 0, 1, 1, 1),
(1131, 45, 41, 0, 0, 1, 1, 1),
(1132, 44, 41, 0, 0, 1, 1, 1),
(1133, 43, 41, 0, 0, 1, 1, 1),
(1134, 42, 41, 0, 0, 1, 1, 1),
(1135, 41, 41, 0, 0, 1, 1, 1),
(1136, 40, 41, 0, 0, 1, 1, 1),
(1137, 39, 41, 0, 0, 1, 1, 1),
(1138, 38, 41, 0, 0, 1, 1, 1),
(1139, 37, 41, 0, 0, 1, 1, 1),
(1140, 36, 41, 0, 0, 1, 1, 1),
(1141, 35, 41, 0, 0, 1, 1, 1),
(1142, 34, 41, 0, 0, 1, 1, 1),
(1143, 33, 41, 0, 0, 1, 1, 1),
(1144, 7, 41, 0, 0, 1, 1, 1),
(1145, 13, 41, 1, 0, 1, 1, 1),
(1146, 12, 41, 1, 0, 1, 1, 1),
(1147, 6, 41, 1, 0, 1, 1, 1),
(1148, 1, 41, 0, 0, 1, 1, 1),
(1149, 16, 42, 0, 0, 1, 1, 1),
(1150, 15, 42, 0, 0, 1, 1, 1),
(1151, 8, 42, 0, 0, 1, 1, 1),
(1152, 18, 42, 0, 0, 1, 1, 1),
(1153, 17, 42, 0, 0, 1, 1, 1),
(1154, 9, 42, 0, 0, 1, 1, 1),
(1155, 14, 42, 1, 0, 1, 1, 1),
(1156, 11, 42, 1, 0, 1, 1, 1),
(1157, 10, 42, 1, 0, 1, 1, 1),
(1158, 4, 42, 1, 0, 1, 1, 1),
(1159, 45, 42, 0, 0, 1, 1, 1),
(1160, 44, 42, 0, 0, 1, 1, 1),
(1161, 43, 42, 0, 0, 1, 1, 1),
(1162, 42, 42, 0, 0, 1, 1, 1),
(1163, 41, 42, 0, 0, 1, 1, 1),
(1164, 40, 42, 0, 0, 1, 1, 1),
(1165, 39, 42, 0, 0, 1, 1, 1),
(1166, 38, 42, 0, 0, 1, 1, 1),
(1167, 37, 42, 0, 0, 1, 1, 1),
(1168, 36, 42, 0, 0, 1, 1, 1),
(1169, 35, 42, 0, 0, 1, 1, 1),
(1170, 34, 42, 0, 0, 1, 1, 1),
(1171, 33, 42, 0, 0, 1, 1, 1),
(1172, 7, 42, 0, 0, 1, 1, 1),
(1173, 13, 42, 1, 0, 1, 1, 1),
(1174, 12, 42, 1, 0, 1, 1, 1),
(1175, 6, 42, 1, 0, 1, 1, 1),
(1176, 1, 42, 0, 0, 1, 1, 1),
(1177, 16, 43, 0, 0, 1, 1, 1),
(1178, 15, 43, 0, 0, 1, 1, 1),
(1179, 8, 43, 0, 0, 1, 1, 1),
(1180, 18, 43, 0, 0, 1, 1, 1),
(1181, 17, 43, 0, 0, 1, 1, 1),
(1182, 9, 43, 0, 0, 1, 1, 1),
(1183, 14, 43, 1, 0, 1, 1, 1),
(1184, 11, 43, 1, 0, 1, 1, 1),
(1185, 10, 43, 1, 0, 1, 1, 1),
(1186, 4, 43, 1, 0, 1, 1, 1),
(1187, 45, 43, 0, 0, 1, 1, 1),
(1188, 44, 43, 0, 0, 1, 1, 1),
(1189, 43, 43, 0, 0, 1, 1, 1),
(1190, 42, 43, 0, 0, 1, 1, 1),
(1191, 41, 43, 0, 0, 1, 1, 1),
(1192, 40, 43, 0, 0, 1, 1, 1),
(1193, 39, 43, 0, 0, 1, 1, 1),
(1194, 38, 43, 0, 0, 1, 1, 1),
(1195, 37, 43, 0, 0, 1, 1, 1),
(1196, 36, 43, 0, 0, 1, 1, 1),
(1197, 35, 43, 0, 0, 1, 1, 1),
(1198, 34, 43, 0, 0, 1, 1, 1),
(1199, 33, 43, 0, 0, 1, 1, 1),
(1200, 7, 43, 0, 0, 1, 1, 1),
(1201, 13, 43, 1, 0, 1, 1, 1),
(1202, 12, 43, 1, 0, 1, 1, 1),
(1203, 6, 43, 1, 0, 1, 1, 1),
(1204, 1, 43, 0, 0, 1, 1, 1),
(1205, 16, 44, 0, 0, 1, 1, 1),
(1206, 15, 44, 0, 0, 1, 1, 1),
(1207, 8, 44, 0, 0, 1, 1, 1),
(1208, 18, 44, 0, 0, 1, 1, 1),
(1209, 17, 44, 0, 0, 1, 1, 1),
(1210, 9, 44, 0, 0, 1, 1, 1),
(1211, 14, 44, 1, 0, 1, 1, 1),
(1212, 11, 44, 1, 0, 1, 1, 1),
(1213, 10, 44, 1, 0, 1, 1, 1),
(1214, 4, 44, 1, 0, 1, 1, 1),
(1215, 45, 44, 0, 0, 1, 1, 1),
(1216, 44, 44, 0, 0, 1, 1, 1),
(1217, 43, 44, 0, 0, 1, 1, 1),
(1218, 42, 44, 0, 0, 1, 1, 1),
(1219, 41, 44, 0, 0, 1, 1, 1),
(1220, 40, 44, 0, 0, 1, 1, 1),
(1221, 39, 44, 0, 0, 1, 1, 1),
(1222, 38, 44, 0, 0, 1, 1, 1),
(1223, 37, 44, 0, 0, 1, 1, 1),
(1224, 36, 44, 0, 0, 1, 1, 1),
(1225, 35, 44, 0, 0, 1, 1, 1),
(1226, 34, 44, 0, 0, 1, 1, 1),
(1227, 33, 44, 0, 0, 1, 1, 1),
(1228, 7, 44, 0, 0, 1, 1, 1),
(1229, 13, 44, 1, 0, 1, 1, 1),
(1230, 12, 44, 1, 0, 1, 1, 1),
(1231, 6, 44, 1, 0, 1, 1, 1),
(1232, 1, 44, 0, 0, 1, 1, 1),
(1233, 16, 45, 0, 0, 1, 1, 1),
(1234, 15, 45, 0, 0, 1, 1, 1),
(1235, 8, 45, 0, 0, 1, 1, 1),
(1236, 18, 45, 1, 1, 1, 1, 1),
(1237, 17, 45, 1, 1, 1, 1, 1),
(1238, 9, 45, 1, 1, 1, 1, 1),
(1239, 14, 45, 1, 0, 1, 1, 1),
(1240, 11, 45, 1, 0, 1, 1, 1),
(1241, 10, 45, 1, 0, 1, 1, 1),
(1242, 4, 45, 1, 0, 1, 1, 1),
(1243, 45, 45, 0, 0, 1, 1, 1),
(1244, 44, 45, 0, 0, 1, 1, 1),
(1245, 43, 45, 0, 0, 1, 1, 1),
(1246, 42, 45, 0, 0, 1, 1, 1),
(1247, 41, 45, 0, 0, 1, 1, 1),
(1248, 40, 45, 0, 0, 1, 1, 1),
(1249, 39, 45, 0, 0, 1, 1, 1),
(1250, 38, 45, 0, 0, 1, 1, 1),
(1251, 37, 45, 0, 0, 1, 1, 1),
(1252, 36, 45, 0, 0, 1, 1, 1),
(1253, 35, 45, 0, 0, 1, 1, 1),
(1254, 34, 45, 0, 0, 1, 1, 1),
(1255, 33, 45, 0, 0, 1, 1, 1),
(1256, 7, 45, 0, 0, 1, 1, 1),
(1257, 13, 45, 1, 0, 1, 1, 1),
(1258, 12, 45, 1, 0, 1, 1, 1),
(1259, 6, 45, 1, 0, 1, 1, 1),
(1260, 1, 45, 1, 0, 1, 1, 1),
(1261, 16, 46, 0, 0, 1, 1, 1),
(1262, 15, 46, 0, 0, 1, 1, 1),
(1263, 8, 46, 0, 0, 1, 1, 1),
(1264, 18, 46, 1, 1, 1, 1, 1),
(1265, 17, 46, 1, 1, 1, 1, 1),
(1266, 9, 46, 1, 1, 1, 1, 1),
(1267, 14, 46, 1, 0, 1, 1, 1),
(1268, 11, 46, 1, 0, 1, 1, 1),
(1269, 10, 46, 1, 0, 1, 1, 1),
(1270, 4, 46, 1, 0, 1, 1, 1),
(1271, 45, 46, 0, 0, 1, 1, 1),
(1272, 44, 46, 0, 0, 1, 1, 1),
(1273, 43, 46, 0, 0, 1, 1, 1),
(1274, 42, 46, 0, 0, 1, 1, 1),
(1275, 41, 46, 0, 0, 1, 1, 1),
(1276, 40, 46, 0, 0, 1, 1, 1),
(1277, 39, 46, 0, 0, 1, 1, 1),
(1278, 38, 46, 0, 0, 1, 1, 1),
(1279, 37, 46, 0, 0, 1, 1, 1),
(1280, 36, 46, 0, 0, 1, 1, 1),
(1281, 35, 46, 0, 0, 1, 1, 1),
(1282, 34, 46, 0, 0, 1, 1, 1),
(1283, 33, 46, 0, 0, 1, 1, 1),
(1284, 7, 46, 0, 0, 1, 1, 1),
(1285, 13, 46, 1, 0, 1, 1, 1),
(1286, 12, 46, 1, 0, 1, 1, 1),
(1287, 6, 46, 1, 0, 1, 1, 1),
(1288, 1, 46, 1, 0, 1, 1, 1),
(1289, 16, 47, 0, 0, 1, 1, 1),
(1290, 15, 47, 0, 0, 1, 1, 1),
(1291, 8, 47, 0, 0, 1, 1, 1),
(1292, 18, 47, 0, 0, 1, 1, 1),
(1293, 17, 47, 0, 0, 1, 1, 1),
(1294, 9, 47, 0, 0, 1, 1, 1),
(1295, 14, 47, 1, 0, 1, 1, 1),
(1296, 11, 47, 1, 0, 1, 1, 1),
(1297, 10, 47, 1, 0, 1, 1, 1),
(1298, 4, 47, 1, 0, 1, 1, 1),
(1299, 45, 47, 0, 0, 1, 1, 1),
(1300, 44, 47, 0, 0, 1, 1, 1),
(1301, 43, 47, 0, 0, 1, 1, 1),
(1302, 42, 47, 0, 0, 1, 1, 1),
(1303, 41, 47, 0, 0, 1, 1, 1),
(1304, 40, 47, 0, 0, 1, 1, 1),
(1305, 39, 47, 0, 0, 1, 1, 1),
(1306, 38, 47, 0, 0, 1, 1, 1),
(1307, 37, 47, 0, 0, 1, 1, 1),
(1308, 36, 47, 0, 0, 1, 1, 1),
(1309, 35, 47, 0, 0, 1, 1, 1),
(1310, 34, 47, 0, 0, 1, 1, 1),
(1311, 33, 47, 0, 0, 1, 1, 1),
(1312, 7, 47, 0, 0, 1, 1, 1),
(1313, 13, 47, 1, 0, 1, 1, 1),
(1314, 12, 47, 1, 0, 1, 1, 1),
(1315, 6, 47, 1, 0, 1, 1, 1),
(1316, 1, 47, 1, 0, 1, 1, 1),
(1317, 16, 48, 0, 0, 1, 1, 1),
(1318, 15, 48, 0, 0, 1, 1, 1),
(1319, 8, 48, 0, 0, 1, 1, 1),
(1320, 18, 48, 0, 0, 1, 1, 1),
(1321, 17, 48, 0, 0, 1, 1, 1),
(1322, 9, 48, 0, 0, 1, 1, 1),
(1323, 14, 48, 1, 0, 1, 1, 1),
(1324, 11, 48, 1, 0, 1, 1, 1),
(1325, 10, 48, 1, 0, 1, 1, 1),
(1326, 4, 48, 1, 0, 1, 1, 1),
(1327, 45, 48, 0, 0, 1, 1, 1),
(1328, 44, 48, 0, 0, 1, 1, 1),
(1329, 43, 48, 0, 0, 1, 1, 1),
(1330, 42, 48, 0, 0, 1, 1, 1),
(1331, 41, 48, 0, 0, 1, 1, 1),
(1332, 40, 48, 0, 0, 1, 1, 1),
(1333, 39, 48, 0, 0, 1, 1, 1),
(1334, 38, 48, 0, 0, 1, 1, 1),
(1335, 37, 48, 0, 0, 1, 1, 1),
(1336, 36, 48, 0, 0, 1, 1, 1),
(1337, 35, 48, 0, 0, 1, 1, 1),
(1338, 34, 48, 0, 0, 1, 1, 1),
(1339, 33, 48, 0, 0, 1, 1, 1),
(1340, 7, 48, 0, 0, 1, 1, 1),
(1341, 13, 48, 1, 0, 1, 1, 1),
(1342, 12, 48, 1, 0, 1, 1, 1),
(1343, 6, 48, 1, 0, 1, 1, 1),
(1344, 1, 48, 1, 0, 1, 1, 1),
(1345, 16, 49, 0, 0, 1, 1, 1),
(1346, 15, 49, 0, 0, 1, 1, 1),
(1347, 8, 49, 0, 0, 1, 1, 1),
(1348, 18, 49, 0, 0, 1, 1, 1),
(1349, 17, 49, 0, 0, 1, 1, 1),
(1350, 9, 49, 0, 0, 1, 1, 1),
(1351, 14, 49, 1, 0, 1, 1, 1),
(1352, 11, 49, 1, 0, 1, 1, 1),
(1353, 10, 49, 1, 0, 1, 1, 1),
(1354, 4, 49, 1, 0, 1, 1, 1),
(1355, 45, 49, 0, 0, 1, 1, 1),
(1356, 44, 49, 0, 0, 1, 1, 1),
(1357, 43, 49, 0, 0, 1, 1, 1),
(1358, 42, 49, 0, 0, 1, 1, 1),
(1359, 41, 49, 0, 0, 1, 1, 1),
(1360, 40, 49, 0, 0, 1, 1, 1),
(1361, 39, 49, 0, 0, 1, 1, 1),
(1362, 38, 49, 0, 0, 1, 1, 1),
(1363, 37, 49, 0, 0, 1, 1, 1),
(1364, 36, 49, 0, 0, 1, 1, 1),
(1365, 35, 49, 0, 0, 1, 1, 1),
(1366, 34, 49, 0, 0, 1, 1, 1),
(1367, 33, 49, 0, 0, 1, 1, 1),
(1368, 7, 49, 0, 0, 1, 1, 1),
(1369, 13, 49, 1, 0, 1, 1, 1),
(1370, 12, 49, 1, 0, 1, 1, 1),
(1371, 6, 49, 1, 0, 1, 1, 1),
(1372, 1, 49, 1, 0, 1, 1, 1),
(1373, 16, 50, 0, 0, 1, 1, 1),
(1374, 15, 50, 0, 0, 1, 1, 1),
(1375, 8, 50, 0, 0, 1, 1, 1),
(1376, 18, 50, 0, 0, 1, 1, 1),
(1377, 17, 50, 0, 0, 1, 1, 1),
(1378, 9, 50, 0, 0, 1, 1, 1),
(1379, 14, 50, 1, 0, 1, 1, 1),
(1380, 11, 50, 1, 0, 1, 1, 1),
(1381, 10, 50, 1, 0, 1, 1, 1),
(1382, 4, 50, 1, 0, 1, 1, 1),
(1383, 45, 50, 0, 0, 1, 1, 1),
(1384, 44, 50, 0, 0, 1, 1, 1),
(1385, 43, 50, 0, 0, 1, 1, 1),
(1386, 42, 50, 0, 0, 1, 1, 1),
(1387, 41, 50, 0, 0, 1, 1, 1),
(1388, 40, 50, 0, 0, 1, 1, 1),
(1389, 39, 50, 0, 0, 1, 1, 1),
(1390, 38, 50, 0, 0, 1, 1, 1),
(1391, 37, 50, 0, 0, 1, 1, 1),
(1392, 36, 50, 0, 0, 1, 1, 1),
(1393, 35, 50, 0, 0, 1, 1, 1),
(1394, 34, 50, 0, 0, 1, 1, 1),
(1395, 33, 50, 0, 0, 1, 1, 1),
(1396, 7, 50, 0, 0, 1, 1, 1),
(1397, 13, 50, 1, 0, 1, 1, 1),
(1398, 12, 50, 1, 0, 1, 1, 1),
(1399, 6, 50, 1, 0, 1, 1, 1),
(1400, 1, 50, 1, 0, 1, 1, 1),
(1401, 16, 51, 0, 0, 1, 1, 1),
(1402, 15, 51, 0, 0, 1, 1, 1),
(1403, 8, 51, 0, 0, 1, 1, 1),
(1404, 18, 51, 0, 0, 1, 1, 1),
(1405, 17, 51, 0, 0, 1, 1, 1),
(1406, 9, 51, 0, 0, 1, 1, 1),
(1407, 14, 51, 1, 0, 1, 1, 1),
(1408, 11, 51, 1, 0, 1, 1, 1),
(1409, 10, 51, 1, 0, 1, 1, 1),
(1410, 4, 51, 1, 0, 1, 1, 1),
(1411, 45, 51, 0, 0, 1, 1, 1),
(1412, 44, 51, 0, 0, 1, 1, 1),
(1413, 43, 51, 0, 0, 1, 1, 1),
(1414, 42, 51, 0, 0, 1, 1, 1),
(1415, 41, 51, 0, 0, 1, 1, 1),
(1416, 40, 51, 0, 0, 1, 1, 1),
(1417, 39, 51, 0, 0, 1, 1, 1),
(1418, 38, 51, 0, 0, 1, 1, 1),
(1419, 37, 51, 0, 0, 1, 1, 1),
(1420, 36, 51, 0, 0, 1, 1, 1),
(1421, 35, 51, 0, 0, 1, 1, 1),
(1422, 34, 51, 0, 0, 1, 1, 1),
(1423, 33, 51, 0, 0, 1, 1, 1),
(1424, 7, 51, 0, 0, 1, 1, 1),
(1425, 13, 51, 1, 0, 1, 1, 1),
(1426, 12, 51, 1, 0, 1, 1, 1),
(1427, 6, 51, 1, 0, 1, 1, 1),
(1428, 1, 51, 1, 0, 1, 1, 1),
(1429, 16, 52, 0, 0, 1, 1, 1),
(1430, 15, 52, 0, 0, 1, 1, 1),
(1431, 8, 52, 0, 0, 1, 1, 1),
(1432, 18, 52, 0, 0, 1, 1, 1),
(1433, 17, 52, 0, 0, 1, 1, 1),
(1434, 9, 52, 0, 0, 1, 1, 1),
(1435, 14, 52, 1, 0, 1, 1, 1),
(1436, 11, 52, 1, 0, 1, 1, 1),
(1437, 10, 52, 1, 0, 1, 1, 1),
(1438, 4, 52, 1, 0, 1, 1, 1),
(1439, 45, 52, 0, 0, 1, 1, 1),
(1440, 44, 52, 0, 0, 1, 1, 1),
(1441, 43, 52, 0, 0, 1, 1, 1),
(1442, 42, 52, 0, 0, 1, 1, 1),
(1443, 41, 52, 0, 0, 1, 1, 1),
(1444, 40, 52, 0, 0, 1, 1, 1),
(1445, 39, 52, 0, 0, 1, 1, 1),
(1446, 38, 52, 0, 0, 1, 1, 1),
(1447, 37, 52, 0, 0, 1, 1, 1),
(1448, 36, 52, 0, 0, 1, 1, 1),
(1449, 35, 52, 0, 0, 1, 1, 1),
(1450, 34, 52, 0, 0, 1, 1, 1),
(1451, 33, 52, 0, 0, 1, 1, 1),
(1452, 7, 52, 0, 0, 1, 1, 1),
(1453, 13, 52, 1, 0, 1, 1, 1),
(1454, 12, 52, 1, 0, 1, 1, 1),
(1455, 6, 52, 1, 0, 1, 1, 1),
(1456, 1, 52, 1, 0, 1, 1, 1),
(1457, 16, 53, 0, 0, 0, 0, 0),
(1458, 15, 53, 0, 0, 0, 0, 0),
(1459, 8, 53, 0, 0, 0, 0, 0),
(1460, 18, 53, 0, 0, 0, 0, 0),
(1461, 17, 53, 0, 0, 0, 0, 0),
(1462, 9, 53, 0, 0, 0, 0, 0),
(1463, 14, 53, 1, 0, 0, 0, 0),
(1464, 11, 53, 1, 0, 0, 0, 0),
(1465, 10, 53, 1, 0, 0, 0, 0),
(1466, 4, 53, 1, 0, 0, 0, 0),
(1467, 45, 53, 0, 0, 0, 0, 0),
(1468, 44, 53, 0, 0, 0, 0, 0),
(1469, 43, 53, 0, 0, 0, 0, 0),
(1470, 42, 53, 0, 0, 0, 0, 0),
(1471, 41, 53, 0, 0, 0, 0, 0),
(1472, 40, 53, 0, 0, 0, 0, 0),
(1473, 39, 53, 0, 0, 0, 0, 0),
(1474, 38, 53, 0, 0, 0, 0, 0),
(1475, 37, 53, 0, 0, 0, 0, 0),
(1476, 36, 53, 0, 0, 0, 0, 0),
(1477, 35, 53, 0, 0, 0, 0, 0),
(1478, 34, 53, 0, 0, 0, 0, 0),
(1479, 33, 53, 0, 0, 0, 0, 0),
(1480, 7, 53, 0, 0, 0, 0, 0),
(1481, 13, 53, 1, 0, 0, 0, 0),
(1482, 12, 53, 1, 0, 0, 0, 0),
(1483, 6, 53, 1, 0, 0, 0, 0),
(1484, 1, 53, 1, 0, 0, 0, 0),
(1485, 16, 54, 0, 0, 1, 1, 1),
(1486, 15, 54, 0, 0, 1, 1, 1),
(1487, 8, 54, 0, 0, 1, 1, 1),
(1488, 18, 54, 0, 0, 1, 1, 1),
(1489, 17, 54, 0, 0, 1, 1, 1),
(1490, 9, 54, 0, 0, 1, 1, 1),
(1491, 14, 54, 1, 0, 1, 1, 1),
(1492, 11, 54, 1, 0, 1, 1, 1),
(1493, 10, 54, 1, 0, 1, 1, 1),
(1494, 4, 54, 1, 0, 1, 1, 1),
(1495, 45, 54, 0, 0, 1, 1, 1),
(1496, 44, 54, 0, 0, 1, 1, 1),
(1497, 43, 54, 0, 0, 1, 1, 1),
(1498, 42, 54, 0, 0, 1, 1, 1),
(1499, 41, 54, 0, 0, 1, 1, 1),
(1500, 40, 54, 0, 0, 1, 1, 1),
(1501, 39, 54, 0, 0, 1, 1, 1),
(1502, 38, 54, 0, 0, 1, 1, 1),
(1503, 37, 54, 0, 0, 1, 1, 1),
(1504, 36, 54, 0, 0, 1, 1, 1),
(1505, 35, 54, 0, 0, 1, 1, 1),
(1506, 34, 54, 0, 0, 1, 1, 1),
(1507, 33, 54, 0, 0, 1, 1, 1),
(1508, 7, 54, 0, 0, 1, 1, 1),
(1509, 13, 54, 1, 0, 1, 1, 1),
(1510, 12, 54, 1, 0, 1, 1, 1),
(1511, 6, 54, 1, 0, 1, 1, 1),
(1512, 1, 54, 0, 0, 1, 1, 1),
(1513, 16, 55, 0, 0, 1, 1, 1),
(1514, 15, 55, 0, 0, 1, 1, 1),
(1515, 8, 55, 0, 0, 1, 1, 1),
(1516, 18, 55, 0, 0, 1, 1, 1),
(1517, 17, 55, 0, 0, 1, 1, 1),
(1518, 9, 55, 0, 0, 1, 1, 1),
(1519, 14, 55, 1, 0, 1, 1, 1),
(1520, 11, 55, 1, 0, 1, 1, 1),
(1521, 10, 55, 1, 0, 1, 1, 1),
(1522, 4, 55, 1, 0, 1, 1, 1),
(1523, 45, 55, 0, 0, 1, 1, 1),
(1524, 44, 55, 0, 0, 1, 1, 1),
(1525, 43, 55, 0, 0, 1, 1, 1),
(1526, 42, 55, 0, 0, 1, 1, 1),
(1527, 41, 55, 0, 0, 1, 1, 1),
(1528, 40, 55, 0, 0, 1, 1, 1),
(1529, 39, 55, 0, 0, 1, 1, 1),
(1530, 38, 55, 0, 0, 1, 1, 1),
(1531, 37, 55, 0, 0, 1, 1, 1),
(1532, 36, 55, 0, 0, 1, 1, 1),
(1533, 35, 55, 0, 0, 1, 1, 1),
(1534, 34, 55, 0, 0, 1, 1, 1),
(1535, 33, 55, 0, 0, 1, 1, 1),
(1536, 7, 55, 0, 0, 1, 1, 1),
(1537, 13, 55, 1, 0, 1, 1, 1),
(1538, 12, 55, 1, 0, 1, 1, 1),
(1539, 6, 55, 1, 0, 1, 1, 1),
(1540, 1, 55, 0, 0, 1, 1, 1),
(1541, 16, 56, 0, 0, 1, 1, 1),
(1542, 15, 56, 0, 0, 1, 1, 1),
(1543, 8, 56, 0, 0, 1, 1, 1),
(1544, 18, 56, 0, 0, 1, 1, 1),
(1545, 17, 56, 0, 0, 1, 1, 1),
(1546, 9, 56, 0, 0, 1, 1, 1),
(1547, 14, 56, 1, 0, 1, 1, 1),
(1548, 11, 56, 1, 0, 1, 1, 1),
(1549, 10, 56, 0, 0, 0, 0, 0),
(1550, 4, 56, 1, 0, 1, 1, 1),
(1551, 45, 56, 0, 0, 1, 1, 1),
(1552, 44, 56, 0, 0, 1, 1, 1),
(1553, 43, 56, 0, 0, 1, 1, 1),
(1554, 42, 56, 0, 0, 1, 1, 1),
(1555, 41, 56, 0, 0, 1, 1, 1),
(1556, 40, 56, 0, 0, 1, 1, 1),
(1557, 39, 56, 0, 0, 1, 1, 1),
(1558, 38, 56, 0, 0, 1, 1, 1),
(1559, 37, 56, 0, 0, 1, 1, 1),
(1560, 36, 56, 0, 0, 1, 1, 1),
(1561, 35, 56, 0, 0, 1, 1, 1),
(1562, 34, 56, 0, 0, 1, 1, 1),
(1563, 33, 56, 0, 0, 1, 1, 1),
(1564, 7, 56, 0, 0, 1, 1, 1),
(1565, 13, 56, 1, 0, 1, 1, 1),
(1566, 12, 56, 1, 0, 1, 1, 1),
(1567, 6, 56, 1, 0, 1, 1, 1),
(1568, 1, 56, 1, 0, 1, 1, 1),
(1569, 16, 57, 0, 0, 1, 1, 1),
(1570, 15, 57, 0, 0, 1, 1, 1),
(1571, 8, 57, 0, 0, 1, 1, 1),
(1572, 18, 57, 0, 0, 1, 1, 1),
(1573, 17, 57, 0, 0, 1, 1, 1),
(1574, 9, 57, 0, 0, 1, 1, 1),
(1575, 14, 57, 1, 0, 1, 1, 1),
(1576, 11, 57, 1, 0, 1, 1, 1),
(1577, 10, 57, 0, 0, 0, 0, 0),
(1578, 4, 57, 1, 0, 1, 1, 1),
(1579, 45, 57, 0, 0, 1, 1, 1),
(1580, 44, 57, 0, 0, 1, 1, 1),
(1581, 43, 57, 0, 0, 1, 1, 1),
(1582, 42, 57, 0, 0, 1, 1, 1),
(1583, 41, 57, 0, 0, 1, 1, 1),
(1584, 40, 57, 0, 0, 1, 1, 1),
(1585, 39, 57, 0, 0, 1, 1, 1),
(1586, 38, 57, 0, 0, 1, 1, 1),
(1587, 37, 57, 0, 0, 1, 1, 1),
(1588, 36, 57, 0, 0, 1, 1, 1),
(1589, 35, 57, 0, 0, 1, 1, 1),
(1590, 34, 57, 0, 0, 1, 1, 1),
(1591, 33, 57, 0, 0, 1, 1, 1),
(1592, 7, 57, 0, 0, 1, 1, 1),
(1593, 13, 57, 1, 0, 1, 1, 1),
(1594, 12, 57, 1, 0, 1, 1, 1),
(1595, 6, 57, 1, 0, 1, 1, 1),
(1596, 1, 57, 1, 0, 1, 1, 1),
(1597, 16, 58, 0, 0, 1, 1, 1),
(1598, 15, 58, 0, 0, 1, 1, 1),
(1599, 8, 58, 0, 0, 1, 1, 1),
(1600, 18, 58, 0, 0, 1, 1, 1),
(1601, 17, 58, 0, 0, 1, 1, 1),
(1602, 9, 58, 0, 0, 1, 1, 1),
(1603, 14, 58, 1, 0, 1, 1, 1),
(1604, 11, 58, 1, 0, 1, 1, 1),
(1605, 10, 58, 1, 0, 1, 1, 1),
(1606, 4, 58, 1, 0, 1, 1, 1),
(1607, 45, 58, 0, 0, 1, 1, 1),
(1608, 44, 58, 0, 0, 1, 1, 1),
(1609, 43, 58, 0, 0, 1, 1, 1),
(1610, 42, 58, 0, 0, 1, 1, 1),
(1611, 41, 58, 0, 0, 1, 1, 1),
(1612, 40, 58, 0, 0, 1, 1, 1),
(1613, 39, 58, 0, 0, 1, 1, 1),
(1614, 38, 58, 0, 0, 1, 1, 1),
(1615, 37, 58, 0, 0, 1, 1, 1),
(1616, 36, 58, 0, 0, 1, 1, 1),
(1617, 35, 58, 0, 0, 1, 1, 1),
(1618, 34, 58, 0, 0, 1, 1, 1),
(1619, 33, 58, 0, 0, 1, 1, 1),
(1620, 7, 58, 0, 0, 1, 1, 1),
(1621, 13, 58, 1, 0, 1, 1, 1),
(1622, 12, 58, 1, 0, 1, 1, 1),
(1623, 6, 58, 1, 0, 1, 1, 1),
(1624, 1, 58, 1, 0, 1, 1, 1),
(1625, 16, 59, 0, 0, 1, 1, 1),
(1626, 15, 59, 0, 0, 1, 1, 1),
(1627, 8, 59, 0, 0, 1, 1, 1),
(1628, 18, 59, 0, 0, 1, 1, 1),
(1629, 17, 59, 0, 0, 1, 1, 1),
(1630, 9, 59, 0, 0, 1, 1, 1),
(1631, 14, 59, 1, 0, 1, 1, 1),
(1632, 11, 59, 1, 0, 1, 1, 1),
(1633, 10, 59, 1, 0, 1, 1, 1),
(1634, 4, 59, 1, 0, 1, 1, 1),
(1635, 45, 59, 0, 0, 1, 1, 1),
(1636, 44, 59, 0, 0, 1, 1, 1),
(1637, 43, 59, 0, 0, 1, 1, 1),
(1638, 42, 59, 0, 0, 1, 1, 1),
(1639, 41, 59, 0, 0, 1, 1, 1),
(1640, 40, 59, 0, 0, 1, 1, 1),
(1641, 39, 59, 0, 0, 1, 1, 1),
(1642, 38, 59, 0, 0, 1, 1, 1),
(1643, 37, 59, 0, 0, 1, 1, 1),
(1644, 36, 59, 0, 0, 1, 1, 1),
(1645, 35, 59, 0, 0, 1, 1, 1),
(1646, 34, 59, 0, 0, 1, 1, 1),
(1647, 33, 59, 0, 0, 1, 1, 1),
(1648, 7, 59, 0, 0, 1, 1, 1),
(1649, 13, 59, 1, 0, 1, 1, 1),
(1650, 12, 59, 1, 0, 1, 1, 1),
(1651, 6, 59, 1, 0, 1, 1, 1),
(1652, 1, 59, 1, 0, 1, 1, 1),
(1653, 16, 60, 0, 0, 1, 1, 1),
(1654, 15, 60, 0, 0, 1, 1, 1),
(1655, 8, 60, 0, 0, 1, 1, 1),
(1656, 18, 60, 0, 0, 1, 1, 1),
(1657, 17, 60, 0, 0, 1, 1, 1),
(1658, 9, 60, 0, 0, 1, 1, 1),
(1659, 14, 60, 1, 0, 1, 1, 1),
(1660, 11, 60, 1, 0, 1, 1, 1),
(1661, 10, 60, 1, 0, 1, 1, 1),
(1662, 4, 60, 1, 0, 1, 1, 1),
(1663, 45, 60, 0, 0, 1, 1, 1),
(1664, 44, 60, 0, 0, 1, 1, 1),
(1665, 43, 60, 0, 0, 1, 1, 1),
(1666, 42, 60, 0, 0, 1, 1, 1),
(1667, 41, 60, 0, 0, 1, 1, 1),
(1668, 40, 60, 0, 0, 1, 1, 1),
(1669, 39, 60, 0, 0, 1, 1, 1),
(1670, 38, 60, 0, 0, 1, 1, 1),
(1671, 37, 60, 0, 0, 1, 1, 1),
(1672, 36, 60, 0, 0, 1, 1, 1),
(1673, 35, 60, 0, 0, 1, 1, 1),
(1674, 34, 60, 0, 0, 1, 1, 1),
(1675, 33, 60, 0, 0, 1, 1, 1),
(1676, 7, 60, 0, 0, 1, 1, 1),
(1677, 13, 60, 1, 0, 1, 1, 1),
(1678, 12, 60, 1, 0, 1, 1, 1),
(1679, 6, 60, 1, 0, 1, 1, 1),
(1680, 1, 60, 1, 0, 1, 1, 1),
(1681, 16, 61, 0, 0, 1, 1, 1),
(1682, 15, 61, 0, 0, 1, 1, 1),
(1683, 8, 61, 0, 0, 1, 1, 1),
(1684, 18, 61, 0, 0, 1, 1, 1),
(1685, 17, 61, 0, 0, 1, 1, 1),
(1686, 9, 61, 0, 0, 1, 1, 1),
(1687, 14, 61, 1, 0, 1, 1, 1),
(1688, 11, 61, 1, 0, 1, 1, 1),
(1689, 10, 61, 0, 0, 0, 0, 0),
(1690, 4, 61, 1, 0, 1, 1, 1),
(1691, 45, 61, 0, 0, 1, 1, 1),
(1692, 44, 61, 0, 0, 1, 1, 1),
(1693, 43, 61, 0, 0, 1, 1, 1),
(1694, 42, 61, 0, 0, 1, 1, 1),
(1695, 41, 61, 0, 0, 1, 1, 1),
(1696, 40, 61, 0, 0, 1, 1, 1),
(1697, 39, 61, 0, 0, 1, 1, 1),
(1698, 38, 61, 0, 0, 1, 1, 1),
(1699, 37, 61, 0, 0, 1, 1, 1),
(1700, 36, 61, 0, 0, 1, 1, 1),
(1701, 35, 61, 0, 0, 1, 1, 1),
(1702, 34, 61, 0, 0, 1, 1, 1),
(1703, 33, 61, 0, 0, 1, 1, 1),
(1704, 7, 61, 0, 0, 1, 1, 1),
(1705, 13, 61, 1, 0, 1, 1, 1),
(1706, 12, 61, 1, 0, 1, 1, 1),
(1707, 6, 61, 1, 0, 1, 1, 1),
(1708, 1, 61, 1, 0, 1, 1, 1),
(1765, 16, 64, 0, 0, 0, 1, 0),
(1766, 15, 64, 0, 0, 0, 1, 0),
(1767, 8, 64, 0, 0, 0, 1, 0),
(1768, 18, 64, 0, 0, 0, 1, 0),
(1769, 17, 64, 0, 0, 0, 1, 0),
(1770, 9, 64, 0, 0, 0, 1, 0),
(1771, 14, 64, 1, 0, 0, 1, 0),
(1772, 11, 64, 1, 0, 0, 1, 0),
(1773, 10, 64, 1, 0, 0, 1, 0),
(1774, 4, 64, 1, 0, 0, 1, 0),
(1775, 45, 64, 0, 0, 0, 1, 0),
(1776, 44, 64, 0, 0, 0, 1, 0),
(1777, 43, 64, 0, 0, 0, 1, 0),
(1778, 42, 64, 0, 0, 0, 1, 0),
(1779, 41, 64, 0, 0, 0, 1, 0),
(1780, 40, 64, 0, 0, 0, 1, 0),
(1781, 39, 64, 0, 0, 0, 1, 0),
(1782, 38, 64, 0, 0, 0, 1, 0),
(1783, 37, 64, 0, 0, 0, 1, 0),
(1784, 36, 64, 0, 0, 0, 1, 0),
(1785, 35, 64, 0, 0, 0, 1, 0),
(1786, 34, 64, 0, 0, 0, 1, 0),
(1787, 33, 64, 0, 0, 0, 1, 0),
(1788, 7, 64, 0, 0, 0, 1, 0),
(1789, 13, 64, 1, 0, 0, 1, 0),
(1790, 12, 64, 1, 0, 0, 1, 0),
(1791, 6, 64, 1, 0, 0, 1, 0),
(1792, 1, 64, 1, 0, 0, 1, 0),
(1793, 16, 65, 0, 0, 1, 1, 1),
(1794, 15, 65, 0, 0, 1, 1, 1),
(1795, 8, 65, 0, 0, 1, 1, 1),
(1796, 18, 65, 0, 0, 1, 1, 1),
(1797, 17, 65, 0, 0, 1, 1, 1),
(1798, 9, 65, 0, 0, 1, 1, 1),
(1799, 14, 65, 1, 0, 1, 1, 1),
(1800, 11, 65, 1, 0, 1, 1, 1),
(1801, 10, 65, 1, 0, 1, 1, 1),
(1802, 4, 65, 1, 0, 1, 1, 1),
(1803, 45, 65, 0, 0, 1, 1, 1),
(1804, 44, 65, 0, 0, 1, 1, 1),
(1805, 43, 65, 0, 0, 1, 1, 1),
(1806, 42, 65, 0, 0, 1, 1, 1),
(1807, 41, 65, 0, 0, 1, 1, 1),
(1808, 40, 65, 0, 0, 1, 1, 1),
(1809, 39, 65, 0, 0, 1, 1, 1),
(1810, 38, 65, 0, 0, 1, 1, 1),
(1811, 37, 65, 0, 0, 1, 1, 1),
(1812, 36, 65, 0, 0, 1, 1, 1),
(1813, 35, 65, 0, 0, 1, 1, 1),
(1814, 34, 65, 0, 0, 1, 1, 1),
(1815, 33, 65, 0, 0, 1, 1, 1),
(1816, 7, 65, 0, 0, 1, 1, 1),
(1817, 13, 65, 1, 0, 1, 1, 1),
(1818, 12, 65, 1, 0, 1, 1, 1),
(1819, 6, 65, 1, 0, 1, 1, 1),
(1820, 1, 65, 1, 0, 1, 1, 1),
(1821, 16, 66, 0, 0, 1, 1, 1),
(1822, 15, 66, 0, 0, 1, 1, 1),
(1823, 8, 66, 0, 0, 1, 1, 1),
(1824, 18, 66, 0, 0, 1, 1, 1),
(1825, 17, 66, 0, 0, 1, 1, 1),
(1826, 9, 66, 0, 0, 1, 1, 1),
(1827, 14, 66, 1, 0, 1, 1, 1),
(1828, 11, 66, 1, 0, 1, 1, 1),
(1829, 10, 66, 1, 0, 1, 1, 1),
(1830, 4, 66, 1, 0, 1, 1, 1),
(1831, 45, 66, 0, 0, 1, 1, 1),
(1832, 44, 66, 0, 0, 1, 1, 1),
(1833, 43, 66, 0, 0, 1, 1, 1),
(1834, 42, 66, 0, 0, 1, 1, 1),
(1835, 41, 66, 0, 0, 1, 1, 1),
(1836, 40, 66, 0, 0, 1, 1, 1),
(1837, 39, 66, 0, 0, 1, 1, 1),
(1838, 38, 66, 0, 0, 1, 1, 1),
(1839, 37, 66, 0, 0, 1, 1, 1),
(1840, 36, 66, 0, 0, 1, 1, 1),
(1841, 35, 66, 0, 0, 1, 1, 1),
(1842, 34, 66, 0, 0, 1, 1, 1),
(1843, 33, 66, 0, 0, 1, 1, 1),
(1844, 7, 66, 0, 0, 1, 1, 1),
(1845, 13, 66, 1, 0, 1, 1, 1),
(1846, 12, 66, 1, 0, 1, 1, 1),
(1847, 6, 66, 1, 0, 1, 1, 1),
(1848, 1, 66, 1, 0, 1, 1, 1),
(1849, 16, 67, 0, 0, 0, 1, 0),
(1850, 15, 67, 0, 0, 0, 1, 0),
(1851, 8, 67, 0, 0, 0, 1, 0),
(1852, 18, 67, 0, 0, 0, 1, 0),
(1853, 17, 67, 0, 0, 0, 1, 0),
(1854, 9, 67, 0, 0, 0, 1, 0),
(1855, 14, 67, 1, 0, 0, 1, 0),
(1856, 11, 67, 1, 0, 0, 1, 0),
(1857, 10, 67, 1, 0, 0, 1, 0),
(1858, 4, 67, 1, 0, 0, 1, 0),
(1859, 45, 67, 0, 0, 0, 1, 0),
(1860, 44, 67, 0, 0, 0, 1, 0),
(1861, 43, 67, 0, 0, 0, 1, 0),
(1862, 42, 67, 0, 0, 0, 1, 0),
(1863, 41, 67, 0, 0, 0, 1, 0);
INSERT INTO `hak_akses` (`id`, `id_user`, `id_menu`, `lihat`, `beranda`, `tambah`, `edit`, `hapus`) VALUES
(1864, 40, 67, 0, 0, 0, 1, 0),
(1865, 39, 67, 0, 0, 0, 1, 0),
(1866, 38, 67, 0, 0, 0, 1, 0),
(1867, 37, 67, 0, 0, 0, 1, 0),
(1868, 36, 67, 0, 0, 0, 1, 0),
(1869, 35, 67, 0, 0, 0, 1, 0),
(1870, 34, 67, 0, 0, 0, 1, 0),
(1871, 33, 67, 0, 0, 0, 1, 0),
(1872, 7, 67, 0, 0, 0, 1, 0),
(1873, 13, 67, 1, 0, 0, 1, 0),
(1874, 12, 67, 1, 0, 0, 1, 0),
(1875, 6, 67, 1, 0, 0, 1, 0),
(1876, 1, 67, 1, 0, 0, 1, 0),
(1877, 16, 68, 0, 0, 1, 1, 1),
(1878, 15, 68, 0, 0, 1, 1, 1),
(1879, 8, 68, 0, 0, 1, 1, 1),
(1880, 18, 68, 0, 0, 1, 1, 1),
(1881, 17, 68, 0, 0, 1, 1, 1),
(1882, 9, 68, 0, 0, 1, 1, 1),
(1883, 14, 68, 1, 0, 1, 1, 1),
(1884, 11, 68, 1, 0, 1, 1, 1),
(1885, 10, 68, 1, 0, 1, 1, 1),
(1886, 4, 68, 1, 0, 1, 1, 1),
(1887, 45, 68, 0, 0, 1, 1, 1),
(1888, 44, 68, 0, 0, 1, 1, 1),
(1889, 43, 68, 0, 0, 1, 1, 1),
(1890, 42, 68, 0, 0, 1, 1, 1),
(1891, 41, 68, 0, 0, 1, 1, 1),
(1892, 40, 68, 0, 0, 1, 1, 1),
(1893, 39, 68, 0, 0, 1, 1, 1),
(1894, 38, 68, 0, 0, 1, 1, 1),
(1895, 37, 68, 0, 0, 1, 1, 1),
(1896, 36, 68, 0, 0, 1, 1, 1),
(1897, 35, 68, 0, 0, 1, 1, 1),
(1898, 34, 68, 0, 0, 1, 1, 1),
(1899, 33, 68, 0, 0, 1, 1, 1),
(1900, 7, 68, 0, 0, 1, 1, 1),
(1901, 13, 68, 1, 0, 1, 1, 1),
(1902, 12, 68, 1, 0, 1, 1, 1),
(1903, 6, 68, 1, 0, 1, 1, 1),
(1904, 1, 68, 1, 0, 1, 1, 1),
(1905, 16, 69, 0, 0, 1, 1, 1),
(1906, 15, 69, 0, 0, 1, 1, 1),
(1907, 8, 69, 0, 0, 1, 1, 1),
(1908, 18, 69, 0, 0, 1, 1, 1),
(1909, 17, 69, 0, 0, 1, 1, 1),
(1910, 9, 69, 0, 0, 1, 1, 1),
(1911, 14, 69, 1, 0, 1, 1, 1),
(1912, 11, 69, 1, 0, 1, 1, 1),
(1913, 10, 69, 1, 0, 1, 1, 1),
(1914, 4, 69, 1, 0, 1, 1, 1),
(1915, 45, 69, 0, 0, 1, 1, 1),
(1916, 44, 69, 0, 0, 1, 1, 1),
(1917, 43, 69, 0, 0, 1, 1, 1),
(1918, 42, 69, 0, 0, 1, 1, 1),
(1919, 41, 69, 0, 0, 1, 1, 1),
(1920, 40, 69, 0, 0, 1, 1, 1),
(1921, 39, 69, 0, 0, 1, 1, 1),
(1922, 38, 69, 0, 0, 1, 1, 1),
(1923, 37, 69, 0, 0, 1, 1, 1),
(1924, 36, 69, 0, 0, 1, 1, 1),
(1925, 35, 69, 0, 0, 1, 1, 1),
(1926, 34, 69, 0, 0, 1, 1, 1),
(1927, 33, 69, 0, 0, 1, 1, 1),
(1928, 7, 69, 0, 0, 1, 1, 1),
(1929, 13, 69, 1, 0, 1, 1, 1),
(1930, 12, 69, 1, 0, 1, 1, 1),
(1931, 6, 69, 1, 0, 1, 1, 1),
(1932, 1, 69, 1, 0, 1, 1, 1),
(1933, 16, 70, 0, 0, 0, 1, 0),
(1934, 15, 70, 0, 0, 0, 1, 0),
(1935, 8, 70, 0, 0, 0, 1, 0),
(1936, 18, 70, 0, 0, 0, 1, 0),
(1937, 17, 70, 0, 0, 0, 1, 0),
(1938, 9, 70, 0, 0, 0, 1, 0),
(1939, 14, 70, 1, 0, 0, 1, 0),
(1940, 11, 70, 1, 0, 0, 1, 0),
(1941, 10, 70, 1, 0, 0, 1, 0),
(1942, 4, 70, 1, 0, 0, 1, 0),
(1943, 45, 70, 0, 0, 0, 1, 0),
(1944, 44, 70, 0, 0, 0, 1, 0),
(1945, 43, 70, 0, 0, 0, 1, 0),
(1946, 42, 70, 0, 0, 0, 1, 0),
(1947, 41, 70, 0, 0, 0, 1, 0),
(1948, 40, 70, 0, 0, 0, 1, 0),
(1949, 39, 70, 0, 0, 0, 1, 0),
(1950, 38, 70, 0, 0, 0, 1, 0),
(1951, 37, 70, 0, 0, 0, 1, 0),
(1952, 36, 70, 0, 0, 0, 1, 0),
(1953, 35, 70, 0, 0, 0, 1, 0),
(1954, 34, 70, 0, 0, 0, 1, 0),
(1955, 33, 70, 0, 0, 0, 1, 0),
(1956, 7, 70, 0, 0, 0, 1, 0),
(1957, 13, 70, 1, 0, 0, 1, 0),
(1958, 12, 70, 1, 0, 0, 1, 0),
(1959, 6, 70, 1, 0, 0, 1, 0),
(1960, 1, 70, 1, 0, 0, 1, 0),
(1961, 16, 71, 0, 0, 0, 0, 0),
(1962, 15, 71, 0, 0, 0, 0, 0),
(1963, 8, 71, 0, 0, 0, 0, 0),
(1964, 18, 71, 0, 0, 0, 0, 0),
(1965, 17, 71, 0, 0, 0, 0, 0),
(1966, 9, 71, 0, 0, 0, 0, 0),
(1967, 14, 71, 1, 0, 1, 1, 1),
(1968, 11, 71, 1, 0, 1, 1, 1),
(1969, 10, 71, 0, 0, 0, 0, 0),
(1970, 4, 71, 0, 0, 0, 0, 0),
(1971, 45, 71, 0, 0, 0, 0, 0),
(1972, 44, 71, 0, 0, 0, 0, 0),
(1973, 43, 71, 0, 0, 0, 0, 0),
(1974, 42, 71, 0, 0, 0, 0, 0),
(1975, 41, 71, 0, 0, 0, 0, 0),
(1976, 40, 71, 0, 0, 0, 0, 0),
(1977, 39, 71, 0, 0, 0, 0, 0),
(1978, 38, 71, 0, 0, 0, 0, 0),
(1979, 37, 71, 0, 0, 0, 0, 0),
(1980, 36, 71, 0, 0, 0, 0, 0),
(1981, 35, 71, 0, 0, 0, 0, 0),
(1982, 34, 71, 0, 0, 0, 0, 0),
(1983, 33, 71, 0, 0, 0, 0, 0),
(1984, 7, 71, 0, 0, 0, 0, 0),
(1985, 13, 71, 1, 0, 1, 1, 1),
(1986, 12, 71, 1, 0, 1, 1, 1),
(1987, 6, 71, 1, 0, 1, 1, 1),
(1988, 1, 71, 1, 0, 1, 1, 1),
(1989, 16, 72, 0, 0, 0, 0, 0),
(1990, 15, 72, 0, 0, 0, 0, 0),
(1991, 8, 72, 0, 0, 0, 0, 0),
(1992, 18, 72, 0, 0, 0, 0, 0),
(1993, 17, 72, 0, 0, 0, 0, 0),
(1994, 9, 72, 0, 0, 0, 0, 0),
(1995, 14, 72, 1, 0, 1, 1, 1),
(1996, 11, 72, 1, 0, 1, 1, 1),
(1997, 10, 72, 0, 0, 0, 0, 0),
(1998, 4, 72, 0, 0, 0, 0, 0),
(1999, 45, 72, 0, 0, 0, 0, 0),
(2000, 44, 72, 0, 0, 0, 0, 0),
(2001, 43, 72, 0, 0, 0, 0, 0),
(2002, 42, 72, 0, 0, 0, 0, 0),
(2003, 41, 72, 0, 0, 0, 0, 0),
(2004, 40, 72, 0, 0, 0, 0, 0),
(2005, 39, 72, 0, 0, 0, 0, 0),
(2006, 38, 72, 0, 0, 0, 0, 0),
(2007, 37, 72, 0, 0, 0, 0, 0),
(2008, 36, 72, 0, 0, 0, 0, 0),
(2009, 35, 72, 0, 0, 0, 0, 0),
(2010, 34, 72, 0, 0, 0, 0, 0),
(2011, 33, 72, 0, 0, 0, 0, 0),
(2012, 7, 72, 0, 0, 0, 0, 0),
(2013, 13, 72, 1, 0, 1, 1, 1),
(2014, 12, 72, 1, 0, 1, 1, 1),
(2015, 6, 72, 1, 0, 1, 1, 1),
(2016, 1, 72, 1, 0, 1, 1, 1),
(2017, 13, 73, 1, 0, 1, 1, 1),
(2018, 12, 73, 1, 0, 1, 1, 1),
(2019, 6, 73, 1, 0, 1, 1, 1),
(2020, 1, 73, 1, 0, 1, 1, 1),
(2021, 45, 73, 1, 0, 1, 1, 1),
(2022, 44, 73, 1, 0, 1, 1, 1),
(2023, 43, 73, 1, 0, 1, 1, 1),
(2024, 42, 73, 1, 0, 1, 1, 1),
(2025, 41, 73, 1, 0, 1, 1, 1),
(2026, 40, 73, 1, 0, 1, 1, 1),
(2027, 39, 73, 1, 0, 1, 1, 1),
(2028, 38, 73, 1, 0, 1, 1, 1),
(2029, 37, 73, 1, 0, 1, 1, 1),
(2030, 36, 73, 1, 0, 1, 1, 1),
(2031, 35, 73, 1, 0, 1, 1, 1),
(2032, 34, 73, 1, 0, 1, 1, 1),
(2033, 33, 73, 1, 0, 1, 1, 1),
(2034, 7, 73, 1, 0, 1, 1, 1),
(2035, 4, 73, 1, 0, 1, 1, 1),
(2036, 10, 73, 1, 0, 1, 1, 1),
(2037, 14, 73, 1, 0, 1, 1, 1),
(2038, 11, 73, 1, 0, 1, 1, 1),
(2039, 18, 73, 1, 0, 1, 1, 1),
(2040, 17, 73, 1, 0, 1, 1, 1),
(2041, 9, 73, 1, 0, 1, 1, 1),
(2042, 16, 73, 1, 0, 1, 1, 1),
(2043, 15, 73, 1, 0, 1, 1, 1),
(2044, 8, 73, 1, 0, 1, 1, 1),
(2045, 13, 74, 1, 0, 1, 1, 1),
(2046, 12, 74, 1, 0, 1, 1, 1),
(2047, 6, 74, 1, 0, 1, 1, 1),
(2048, 1, 74, 1, 0, 1, 1, 1),
(2049, 45, 74, 0, 0, 0, 0, 0),
(2050, 44, 74, 0, 0, 0, 0, 0),
(2051, 43, 74, 0, 0, 0, 0, 0),
(2052, 42, 74, 0, 0, 0, 0, 0),
(2053, 41, 74, 0, 0, 0, 0, 0),
(2054, 40, 74, 0, 0, 0, 0, 0),
(2055, 39, 74, 0, 0, 0, 0, 0),
(2056, 38, 74, 0, 0, 0, 0, 0),
(2057, 37, 74, 0, 0, 0, 0, 0),
(2058, 36, 74, 0, 0, 0, 0, 0),
(2059, 35, 74, 0, 0, 0, 0, 0),
(2060, 34, 74, 0, 0, 0, 0, 0),
(2061, 33, 74, 0, 0, 0, 0, 0),
(2062, 7, 74, 0, 0, 0, 0, 0),
(2063, 4, 74, 0, 0, 0, 0, 0),
(2064, 10, 74, 0, 0, 0, 0, 0),
(2065, 14, 74, 0, 0, 0, 0, 0),
(2066, 11, 74, 0, 0, 0, 0, 0),
(2067, 18, 74, 0, 0, 0, 0, 0),
(2068, 17, 74, 0, 0, 0, 0, 0),
(2069, 9, 74, 0, 0, 0, 0, 0),
(2070, 16, 74, 0, 0, 0, 0, 0),
(2071, 15, 74, 0, 0, 0, 0, 0),
(2072, 8, 74, 0, 0, 0, 0, 0),
(2073, 1, 75, 1, 0, 1, 1, 1),
(2074, 4, 75, 1, 0, 1, 1, 1),
(2075, 6, 75, 1, 0, 1, 1, 1),
(2076, 7, 75, 1, 0, 1, 1, 1),
(2077, 8, 75, 1, 0, 1, 1, 1),
(2078, 9, 75, 1, 0, 1, 1, 1),
(2079, 10, 75, 1, 0, 1, 1, 1),
(2080, 11, 75, 1, 0, 1, 1, 1),
(2081, 12, 75, 1, 0, 1, 1, 1),
(2082, 13, 75, 1, 0, 1, 1, 1),
(2083, 14, 75, 1, 0, 1, 1, 1),
(2084, 15, 75, 1, 0, 1, 1, 1),
(2085, 16, 75, 1, 0, 1, 1, 1),
(2086, 17, 75, 1, 0, 1, 1, 1),
(2087, 18, 75, 1, 0, 1, 1, 1),
(2088, 33, 75, 1, 0, 1, 1, 1),
(2089, 34, 75, 1, 0, 1, 1, 1),
(2090, 35, 75, 1, 0, 1, 1, 1),
(2091, 36, 75, 1, 0, 1, 1, 1),
(2092, 37, 75, 1, 0, 1, 1, 1),
(2093, 38, 75, 1, 0, 1, 1, 1),
(2094, 39, 75, 1, 0, 1, 1, 1),
(2095, 40, 75, 1, 0, 1, 1, 1),
(2096, 41, 75, 1, 0, 1, 1, 1),
(2097, 42, 75, 1, 0, 1, 1, 1),
(2098, 43, 75, 1, 0, 1, 1, 1),
(2099, 44, 75, 1, 0, 1, 1, 1),
(2100, 45, 75, 1, 0, 1, 1, 1),
(2135, 1, 77, 1, 0, 0, 0, 0),
(2136, 6, 77, 1, 0, 0, 0, 0),
(2137, 46, 1, 1, 0, 0, 0, 0),
(2138, 46, 2, 1, 0, 0, 0, 0),
(2139, 46, 3, 1, 0, 0, 0, 0),
(2140, 46, 4, 1, 0, 0, 1, 0),
(2141, 46, 5, 1, 0, 0, 1, 1),
(2142, 46, 6, 1, 0, 1, 1, 1),
(2143, 46, 7, 1, 0, 0, 0, 0),
(2144, 46, 8, 1, 0, 1, 1, 1),
(2145, 46, 9, 1, 0, 1, 1, 1),
(2146, 46, 10, 1, 0, 0, 0, 0),
(2147, 46, 11, 1, 0, 0, 0, 0),
(2148, 46, 12, 1, 0, 0, 0, 0),
(2149, 46, 13, 1, 0, 0, 0, 0),
(2150, 46, 14, 1, 0, 0, 0, 0),
(2151, 46, 15, 1, 0, 0, 0, 0),
(2152, 46, 16, 1, 0, 1, 1, 1),
(2153, 46, 17, 1, 0, 0, 0, 0),
(2155, 46, 19, 1, 0, 0, 0, 0),
(2156, 46, 20, 1, 0, 0, 0, 0),
(2157, 46, 21, 1, 0, 0, 0, 0),
(2158, 46, 22, 1, 0, 0, 0, 0),
(2159, 46, 23, 1, 0, 1, 1, 1),
(2160, 46, 24, 1, 0, 1, 1, 1),
(2161, 46, 25, 1, 0, 1, 1, 1),
(2162, 46, 26, 1, 0, 1, 1, 1),
(2163, 46, 27, 1, 0, 1, 1, 1),
(2164, 46, 28, 1, 0, 1, 1, 1),
(2165, 46, 29, 1, 0, 1, 1, 1),
(2166, 46, 30, 1, 0, 1, 1, 1),
(2167, 46, 31, 1, 0, 1, 1, 1),
(2168, 46, 32, 1, 0, 0, 0, 1),
(2169, 46, 33, 1, 0, 1, 1, 1),
(2170, 46, 34, 1, 0, 1, 1, 1),
(2171, 46, 35, 1, 0, 1, 1, 1),
(2172, 46, 36, 1, 0, 1, 1, 1),
(2173, 46, 37, 1, 0, 1, 1, 1),
(2174, 46, 38, 1, 0, 1, 1, 1),
(2175, 46, 39, 1, 0, 1, 1, 1),
(2176, 46, 40, 1, 0, 1, 1, 1),
(2177, 46, 41, 1, 0, 1, 1, 1),
(2178, 46, 42, 1, 0, 1, 1, 1),
(2179, 46, 43, 1, 0, 1, 1, 1),
(2180, 46, 44, 1, 0, 1, 1, 1),
(2181, 46, 45, 1, 0, 1, 1, 1),
(2182, 46, 46, 1, 0, 1, 1, 1),
(2183, 46, 47, 1, 0, 1, 1, 1),
(2184, 46, 48, 1, 0, 1, 1, 1),
(2185, 46, 49, 1, 0, 1, 1, 1),
(2186, 46, 50, 1, 0, 1, 1, 1),
(2187, 46, 51, 1, 0, 1, 1, 1),
(2188, 46, 52, 1, 0, 1, 1, 1),
(2189, 46, 53, 1, 0, 0, 0, 0),
(2190, 46, 54, 1, 0, 1, 1, 1),
(2191, 46, 55, 1, 0, 1, 1, 1),
(2192, 46, 56, 1, 0, 1, 1, 1),
(2193, 46, 57, 1, 0, 1, 1, 1),
(2194, 46, 58, 1, 0, 1, 1, 1),
(2195, 46, 59, 1, 0, 1, 1, 1),
(2196, 46, 60, 1, 0, 1, 1, 1),
(2197, 46, 61, 1, 0, 1, 1, 1),
(2200, 46, 64, 1, 0, 0, 1, 0),
(2201, 46, 65, 1, 0, 1, 1, 1),
(2202, 46, 66, 1, 0, 1, 1, 1),
(2203, 46, 67, 1, 0, 0, 1, 0),
(2204, 46, 68, 1, 0, 1, 1, 1),
(2205, 46, 69, 1, 0, 1, 1, 1),
(2206, 46, 70, 1, 0, 0, 1, 0),
(2207, 46, 71, 1, 0, 1, 1, 1),
(2208, 46, 72, 1, 0, 1, 1, 1),
(2209, 46, 73, 1, 0, 1, 1, 1),
(2210, 46, 74, 1, 0, 1, 1, 1),
(2211, 46, 75, 1, 0, 1, 1, 1),
(2212, 7, 77, 0, 0, 0, 0, 0),
(2213, 12, 77, 0, 0, 0, 0, 0),
(2214, 9, 77, 0, 0, 0, 0, 0),
(2215, 1, 78, 1, 0, 0, 0, 0),
(2216, 4, 78, 1, 0, 0, 0, 0),
(2217, 6, 78, 1, 0, 0, 0, 0),
(2218, 7, 78, 1, 0, 0, 0, 0),
(2219, 8, 78, 1, 0, 0, 0, 0),
(2220, 9, 78, 1, 0, 0, 0, 0),
(2221, 10, 78, 1, 0, 0, 0, 0),
(2222, 11, 78, 1, 0, 0, 0, 0),
(2223, 12, 78, 1, 0, 0, 0, 0),
(2224, 13, 78, 1, 0, 0, 0, 0),
(2225, 14, 78, 1, 0, 0, 0, 0),
(2226, 15, 78, 1, 0, 0, 0, 0),
(2227, 16, 78, 1, 0, 0, 0, 0),
(2228, 17, 78, 1, 0, 0, 0, 0),
(2229, 18, 78, 1, 0, 0, 0, 0),
(2230, 19, 78, 1, 0, 0, 0, 0),
(2231, 20, 78, 1, 0, 0, 0, 0),
(2232, 21, 78, 1, 0, 0, 0, 0),
(2233, 22, 78, 1, 0, 0, 0, 0),
(2234, 23, 78, 1, 0, 0, 0, 0),
(2235, 24, 78, 1, 0, 0, 0, 0),
(2236, 25, 78, 1, 0, 0, 0, 0),
(2237, 26, 78, 1, 0, 0, 0, 0),
(2238, 27, 78, 1, 0, 0, 0, 0),
(2239, 28, 78, 1, 0, 0, 0, 0),
(2240, 29, 78, 1, 0, 0, 0, 0),
(2241, 30, 78, 1, 0, 0, 0, 0),
(2242, 31, 78, 1, 0, 0, 0, 0),
(2243, 32, 78, 1, 0, 0, 0, 0),
(2244, 33, 78, 1, 0, 0, 0, 0),
(2245, 34, 78, 1, 0, 0, 0, 0),
(2246, 35, 78, 1, 0, 0, 0, 0),
(2247, 36, 78, 1, 0, 0, 0, 0),
(2248, 37, 78, 1, 0, 0, 0, 0),
(2249, 38, 78, 1, 0, 0, 0, 0),
(2250, 39, 78, 1, 0, 0, 0, 0),
(2251, 40, 78, 1, 0, 0, 0, 0),
(2252, 41, 78, 1, 0, 0, 0, 0),
(2253, 42, 78, 1, 0, 0, 0, 0),
(2254, 43, 78, 1, 0, 0, 0, 0),
(2255, 44, 78, 1, 0, 0, 0, 0),
(2256, 45, 78, 1, 0, 0, 0, 0),
(2257, 46, 78, 1, 0, 0, 0, 0),
(2258, 1, 79, 1, 0, 0, 0, 0),
(2259, 4, 79, 1, 0, 0, 0, 0),
(2260, 6, 79, 1, 0, 0, 0, 0),
(2261, 7, 79, 1, 0, 0, 0, 0),
(2262, 8, 79, 1, 0, 0, 0, 0),
(2263, 9, 79, 1, 0, 0, 0, 0),
(2264, 10, 79, 1, 0, 0, 0, 0),
(2265, 11, 79, 1, 0, 0, 0, 0),
(2266, 12, 79, 1, 0, 0, 0, 0),
(2267, 13, 79, 1, 0, 0, 0, 0),
(2268, 14, 79, 1, 0, 0, 0, 0),
(2269, 15, 79, 1, 0, 0, 0, 0),
(2270, 16, 79, 1, 0, 0, 0, 0),
(2271, 17, 79, 1, 0, 0, 0, 0),
(2272, 18, 79, 1, 0, 0, 0, 0),
(2273, 19, 79, 1, 0, 0, 0, 0),
(2274, 20, 79, 1, 0, 0, 0, 0),
(2275, 21, 79, 1, 0, 0, 0, 0),
(2276, 22, 79, 1, 0, 0, 0, 0),
(2277, 23, 79, 1, 0, 0, 0, 0),
(2278, 24, 79, 1, 0, 0, 0, 0),
(2279, 25, 79, 1, 0, 0, 0, 0),
(2280, 26, 79, 1, 0, 0, 0, 0),
(2281, 27, 79, 1, 0, 0, 0, 0),
(2282, 28, 79, 1, 0, 0, 0, 0),
(2283, 29, 79, 1, 0, 0, 0, 0),
(2284, 30, 79, 1, 0, 0, 0, 0),
(2285, 31, 79, 1, 0, 0, 0, 0),
(2286, 32, 79, 1, 0, 0, 0, 0),
(2287, 33, 79, 1, 0, 0, 0, 0),
(2288, 34, 79, 1, 0, 0, 0, 0),
(2289, 35, 79, 1, 0, 0, 0, 0),
(2290, 36, 79, 1, 0, 0, 0, 0),
(2291, 37, 79, 1, 0, 0, 0, 0),
(2292, 38, 79, 1, 0, 0, 0, 0),
(2293, 39, 79, 1, 0, 0, 0, 0),
(2294, 40, 79, 1, 0, 0, 0, 0),
(2295, 41, 79, 1, 0, 0, 0, 0),
(2296, 42, 79, 1, 0, 0, 0, 0),
(2297, 43, 79, 1, 0, 0, 0, 0),
(2298, 44, 79, 1, 0, 0, 0, 0),
(2299, 45, 79, 1, 0, 0, 0, 0),
(2300, 46, 79, 1, 0, 0, 0, 0),
(2301, 1, 80, 1, 0, 1, 1, 1),
(2302, 4, 80, 1, 0, 1, 1, 1),
(2303, 6, 80, 1, 0, 1, 1, 1),
(2304, 7, 80, 1, 0, 1, 1, 1),
(2305, 8, 80, 1, 0, 1, 1, 1),
(2306, 9, 80, 1, 0, 1, 1, 1),
(2307, 10, 80, 1, 0, 1, 1, 1),
(2308, 11, 80, 1, 0, 1, 1, 1),
(2309, 12, 80, 1, 0, 1, 1, 1),
(2310, 13, 80, 1, 0, 1, 1, 1),
(2311, 14, 80, 1, 0, 1, 1, 1),
(2312, 15, 80, 1, 0, 1, 1, 1),
(2313, 16, 80, 1, 0, 1, 1, 1),
(2314, 17, 80, 1, 0, 1, 1, 1),
(2315, 18, 80, 1, 0, 1, 1, 1),
(2316, 19, 80, 1, 0, 1, 1, 1),
(2317, 20, 80, 1, 0, 1, 1, 1),
(2318, 21, 80, 1, 0, 1, 1, 1),
(2319, 22, 80, 1, 0, 1, 1, 1),
(2320, 23, 80, 1, 0, 1, 1, 1),
(2321, 24, 80, 1, 0, 1, 1, 1),
(2322, 25, 80, 1, 0, 1, 1, 1),
(2323, 26, 80, 1, 0, 1, 1, 1),
(2324, 27, 80, 1, 0, 1, 1, 1),
(2325, 28, 80, 1, 0, 1, 1, 1),
(2326, 29, 80, 1, 0, 1, 1, 1),
(2327, 30, 80, 1, 0, 1, 1, 1),
(2328, 31, 80, 1, 0, 1, 1, 1),
(2329, 32, 80, 1, 0, 1, 1, 1),
(2330, 33, 80, 1, 0, 1, 1, 1),
(2331, 34, 80, 1, 0, 1, 1, 1),
(2332, 35, 80, 1, 0, 1, 1, 1),
(2333, 36, 80, 1, 0, 1, 1, 1),
(2334, 37, 80, 1, 0, 1, 1, 1),
(2335, 38, 80, 1, 0, 1, 1, 1),
(2336, 39, 80, 1, 0, 1, 1, 1),
(2337, 40, 80, 1, 0, 1, 1, 1),
(2338, 41, 80, 1, 0, 1, 1, 1),
(2339, 42, 80, 1, 0, 1, 1, 1),
(2340, 43, 80, 1, 0, 1, 1, 1),
(2341, 44, 80, 1, 0, 1, 1, 1),
(2342, 45, 80, 1, 0, 1, 1, 1),
(2343, 46, 80, 1, 0, 1, 1, 1),
(2344, 1, 81, 0, 0, 0, 0, 0),
(2345, 1, 82, 1, 0, 1, 1, 1),
(2346, 4, 82, 1, 0, 1, 1, 1),
(2347, 6, 82, 1, 0, 1, 1, 1),
(2348, 7, 82, 1, 0, 1, 1, 1),
(2349, 8, 82, 1, 0, 1, 1, 1),
(2350, 9, 82, 1, 0, 1, 1, 1),
(2351, 10, 82, 1, 0, 1, 1, 1),
(2352, 11, 82, 1, 0, 1, 1, 1),
(2353, 12, 82, 1, 0, 1, 1, 1),
(2354, 13, 82, 1, 0, 1, 1, 1),
(2355, 14, 82, 1, 0, 1, 1, 1),
(2356, 15, 82, 1, 0, 1, 1, 1),
(2357, 16, 82, 1, 0, 1, 1, 1),
(2358, 17, 82, 1, 0, 1, 1, 1),
(2359, 18, 82, 1, 0, 1, 1, 1),
(2360, 19, 82, 1, 0, 1, 1, 1),
(2361, 20, 82, 1, 0, 1, 1, 1),
(2362, 21, 82, 1, 0, 1, 1, 1),
(2363, 22, 82, 1, 0, 1, 1, 1),
(2364, 23, 82, 1, 0, 1, 1, 1),
(2365, 24, 82, 1, 0, 1, 1, 1),
(2366, 25, 82, 1, 0, 1, 1, 1),
(2367, 26, 82, 1, 0, 1, 1, 1),
(2368, 27, 82, 1, 0, 1, 1, 1),
(2369, 28, 82, 1, 0, 1, 1, 1),
(2370, 29, 82, 1, 0, 1, 1, 1),
(2371, 30, 82, 1, 0, 1, 1, 1),
(2372, 31, 82, 1, 0, 1, 1, 1),
(2373, 32, 82, 1, 0, 1, 1, 1),
(2374, 33, 82, 1, 0, 1, 1, 1),
(2375, 34, 82, 1, 0, 1, 1, 1),
(2376, 35, 82, 1, 0, 1, 1, 1),
(2377, 36, 82, 1, 0, 1, 1, 1),
(2378, 37, 82, 1, 0, 1, 1, 1),
(2379, 38, 82, 1, 0, 1, 1, 1),
(2380, 39, 82, 1, 0, 1, 1, 1),
(2381, 40, 82, 1, 0, 1, 1, 1),
(2382, 41, 82, 1, 0, 1, 1, 1),
(2383, 42, 82, 1, 0, 1, 1, 1),
(2384, 43, 82, 1, 0, 1, 1, 1),
(2385, 44, 82, 1, 0, 1, 1, 1),
(2386, 45, 82, 1, 0, 1, 1, 1),
(2387, 46, 82, 1, 0, 1, 1, 1),
(2388, 1, 84, 1, 0, 0, 0, 0),
(2389, 1, 84, 0, 0, 0, 0, 0),
(2390, 4, 84, 1, 0, 0, 0, 0),
(2391, 6, 84, 1, 0, 0, 0, 0),
(2392, 7, 84, 1, 0, 0, 0, 0),
(2393, 8, 84, 1, 0, 0, 0, 0),
(2394, 9, 84, 1, 0, 0, 0, 0),
(2395, 10, 84, 1, 0, 0, 0, 0),
(2396, 11, 84, 1, 0, 0, 0, 0),
(2397, 12, 84, 1, 0, 0, 0, 0),
(2398, 13, 84, 1, 0, 0, 0, 0),
(2399, 14, 84, 1, 0, 0, 0, 0),
(2400, 15, 84, 1, 0, 0, 0, 0),
(2401, 16, 84, 1, 0, 0, 0, 0),
(2402, 17, 84, 1, 0, 0, 0, 0),
(2403, 18, 84, 1, 0, 0, 0, 0),
(2404, 19, 84, 1, 0, 0, 0, 0),
(2405, 20, 84, 1, 0, 0, 0, 0),
(2406, 21, 84, 1, 0, 0, 0, 0),
(2407, 22, 84, 1, 0, 0, 0, 0),
(2408, 23, 84, 1, 0, 0, 0, 0),
(2409, 24, 84, 1, 0, 0, 0, 0),
(2410, 25, 84, 1, 0, 0, 0, 0),
(2411, 26, 84, 1, 0, 0, 0, 0),
(2412, 27, 84, 1, 0, 0, 0, 0),
(2413, 28, 84, 1, 0, 0, 0, 0),
(2414, 29, 84, 1, 0, 0, 0, 0),
(2415, 30, 84, 1, 0, 0, 0, 0),
(2416, 31, 84, 1, 0, 0, 0, 0),
(2417, 32, 84, 1, 0, 0, 0, 0),
(2418, 33, 84, 1, 0, 0, 0, 0),
(2419, 34, 84, 1, 0, 0, 0, 0),
(2420, 35, 84, 1, 0, 0, 0, 0),
(2421, 36, 84, 1, 0, 0, 0, 0),
(2422, 37, 84, 1, 0, 0, 0, 0),
(2423, 38, 84, 1, 0, 0, 0, 0),
(2424, 39, 84, 1, 0, 0, 0, 0),
(2425, 40, 84, 1, 0, 0, 0, 0),
(2426, 41, 84, 1, 0, 0, 0, 0),
(2427, 42, 84, 1, 0, 0, 0, 0),
(2428, 1, 85, 1, 0, 0, 0, 0),
(2429, 4, 85, 1, 0, 0, 0, 0),
(2430, 6, 85, 1, 0, 0, 0, 0),
(2431, 7, 85, 1, 0, 0, 0, 0),
(2432, 8, 85, 1, 0, 0, 0, 0),
(2433, 9, 85, 1, 0, 0, 0, 0),
(2434, 10, 85, 1, 0, 0, 0, 0),
(2435, 11, 85, 1, 0, 0, 0, 0),
(2436, 12, 85, 1, 0, 0, 0, 0),
(2437, 13, 85, 1, 0, 0, 0, 0),
(2438, 14, 85, 1, 0, 0, 0, 0),
(2439, 15, 85, 1, 0, 0, 0, 0),
(2440, 16, 85, 1, 0, 0, 0, 0),
(2441, 17, 85, 1, 0, 0, 0, 0),
(2442, 18, 85, 1, 0, 0, 0, 0),
(2443, 19, 85, 1, 0, 0, 0, 0),
(2444, 20, 85, 1, 0, 0, 0, 0),
(2445, 21, 85, 1, 0, 0, 0, 0),
(2446, 22, 85, 1, 0, 0, 0, 0),
(2447, 23, 85, 1, 0, 0, 0, 0),
(2448, 24, 85, 1, 0, 0, 0, 0),
(2449, 25, 85, 1, 0, 0, 0, 0),
(2450, 26, 85, 1, 0, 0, 0, 0),
(2451, 27, 85, 1, 0, 0, 0, 0),
(2452, 28, 85, 1, 0, 0, 0, 0),
(2453, 29, 85, 1, 0, 0, 0, 0),
(2454, 30, 85, 1, 0, 0, 0, 0),
(2455, 31, 85, 1, 0, 0, 0, 0),
(2456, 32, 85, 1, 0, 0, 0, 0),
(2457, 33, 85, 1, 0, 0, 0, 0),
(2458, 34, 85, 1, 0, 0, 0, 0),
(2459, 35, 85, 1, 0, 0, 0, 0),
(2460, 36, 85, 1, 0, 0, 0, 0),
(2461, 37, 85, 1, 0, 0, 0, 0),
(2462, 38, 85, 1, 0, 0, 0, 0),
(2463, 39, 85, 1, 0, 0, 0, 0),
(2464, 40, 85, 1, 0, 0, 0, 0),
(2465, 41, 85, 1, 0, 0, 0, 0),
(2466, 42, 85, 1, 0, 0, 0, 0),
(2467, 37, 77, 0, 0, 0, 0, 0),
(2468, 8, 86, 1, 1, 1, 1, 1),
(2469, 9, 86, 1, 1, 1, 1, 1),
(2470, 10, 86, 1, 1, 1, 1, 1),
(2471, 11, 86, 1, 1, 1, 1, 1),
(2472, 14, 86, 1, 1, 1, 1, 1),
(2473, 15, 86, 1, 1, 1, 1, 1),
(2474, 16, 86, 1, 1, 1, 1, 1),
(2475, 17, 86, 1, 1, 1, 1, 1),
(2476, 18, 86, 1, 1, 1, 1, 1),
(2477, 1, 86, 1, 1, 1, 1, 1),
(2478, 6, 86, 1, 1, 1, 1, 1),
(2479, 12, 86, 1, 1, 1, 1, 1),
(2480, 13, 86, 1, 1, 1, 1, 1),
(2481, 46, 86, 1, 1, 1, 1, 1),
(2482, 1, 87, 1, 0, 1, 1, 1),
(2483, 1, 88, 1, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `hak_akses_panduan_apk`
--

CREATE TABLE `hak_akses_panduan_apk` (
  `id` int NOT NULL,
  `id_role` int NOT NULL,
  `id_menu_panduan` int NOT NULL,
  `akses` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hutang`
--

CREATE TABLE `hutang` (
  `id` int NOT NULL,
  `tanggal_hutang` date NOT NULL,
  `id_bank` int NOT NULL,
  `deskripsi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nominal` int NOT NULL,
  `lampiran` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int NOT NULL,
  `terbayar` int NOT NULL,
  `sisa_bayar` int NOT NULL,
  `tgl_pelunasan` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `input_po`
--

CREATE TABLE `input_po` (
  `id` int NOT NULL,
  `tanggal` date NOT NULL,
  `id_supplier` int NOT NULL,
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_po` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `jum_item` int NOT NULL,
  `total_harga` int NOT NULL,
  `terbayar` int NOT NULL,
  `status` int NOT NULL,
  `lampiran_po` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_bank` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `input_po_detail`
--

CREATE TABLE `input_po_detail` (
  `id` int NOT NULL,
  `id_po` int NOT NULL,
  `id_barang` int NOT NULL,
  `jumlah` int NOT NULL,
  `harga_beli` int NOT NULL,
  `sub_total` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `input_po_pembayaran`
--

CREATE TABLE `input_po_pembayaran` (
  `id` int NOT NULL,
  `id_po` int NOT NULL,
  `tanggal` date NOT NULL,
  `terbayar` int NOT NULL,
  `lampiran` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoice`
--

CREATE TABLE `invoice` (
  `id` int NOT NULL,
  `tgl_invoice` date NOT NULL,
  `no_invoice` varchar(35) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_customer` int NOT NULL,
  `id_kavling` int NOT NULL,
  `nama_customer` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_telp` varchar(35) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `perumahan` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `kode_kavling` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `jumlah` int NOT NULL,
  `ppn` int NOT NULL,
  `ppn_nominal` int NOT NULL,
  `total` int NOT NULL,
  `stt_bayar` int NOT NULL,
  `stt_delete_inv` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jalan`
--

CREATE TABLE `jalan` (
  `id` int NOT NULL,
  `id_lokasi` int NOT NULL,
  `nama` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `panjang` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `lebar` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `luas` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jenis_pekerjaan_bangunan`
--

CREATE TABLE `jenis_pekerjaan_bangunan` (
  `id` int NOT NULL,
  `id_lokasi` int NOT NULL,
  `jenis` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `presentasi` decimal(5,2) NOT NULL,
  `harga` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jenis_pekerjaan_jalan`
--

CREATE TABLE `jenis_pekerjaan_jalan` (
  `id` int NOT NULL,
  `jenis` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `presentasi` decimal(5,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jenis_pekerjaan_saluran`
--

CREATE TABLE `jenis_pekerjaan_saluran` (
  `id` int NOT NULL,
  `jenis` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `presentasi` decimal(5,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kategori_transaksi`
--

CREATE TABLE `kategori_transaksi` (
  `id` int NOT NULL,
  `kode` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `kategori` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis_kategori` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `stt_fix` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kavling_peta`
--

CREATE TABLE `kavling_peta` (
  `id` int NOT NULL,
  `id_lokasi` int NOT NULL,
  `id_projek` int DEFAULT NULL,
  `cluster` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `blok` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_perusahaan` int NOT NULL DEFAULT '0',
  `kode_kavling` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `panjang_kanan` double(11,1) DEFAULT NULL,
  `panjang_kiri` double(11,1) DEFAULT NULL,
  `lebar_depan` double(11,1) DEFAULT NULL,
  `lebar_belakang` double(11,1) DEFAULT NULL,
  `luas_tanah` double(11,1) DEFAULT NULL,
  `tipe_bangunan` double(11,1) DEFAULT NULL,
  `daya_listrik` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `luas_bangunan` double(11,1) DEFAULT NULL,
  `hrg_meter` int DEFAULT NULL,
  `hrg_jual` int DEFAULT NULL,
  `biaya_surat` int DEFAULT NULL,
  `peningkatan_mutu` int DEFAULT NULL,
  `id_rumah_sikumbang` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_sertifikat` varchar(35) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jenis_map` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `map` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `matrik` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int DEFAULT NULL,
  `keterangan` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `atas_nama_surat` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_customer` int DEFAULT NULL,
  `tgl_jatuh_tempo` int DEFAULT NULL,
  `stt_cicilan` int DEFAULT NULL,
  `foto` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_ready` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kavling_peta`
--

INSERT INTO `kavling_peta` (`id`, `id_lokasi`, `id_projek`, `cluster`, `blok`, `no`, `id_perusahaan`, `kode_kavling`, `panjang_kanan`, `panjang_kiri`, `lebar_depan`, `lebar_belakang`, `luas_tanah`, `tipe_bangunan`, `daya_listrik`, `luas_bangunan`, `hrg_meter`, `hrg_jual`, `biaya_surat`, `peningkatan_mutu`, `id_rumah_sikumbang`, `no_sertifikat`, `jenis_map`, `map`, `matrik`, `status`, `keterangan`, `atas_nama_surat`, `id_customer`, `tgl_jatuh_tempo`, `stt_cicilan`, `foto`, `status_ready`) VALUES
(1, 1, NULL, '', NULL, NULL, 0, 'B-01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '33389,18037 32270,19798 33385,20504 34498,18740 ', 'matrix(0.544639 -0.838671 0.838671 0.544639 9914.2 28467.7)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(2, 1, NULL, '', NULL, NULL, 0, 'B-02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '32232,17305 31113,19065 32226,19770 33344,18009 ', 'matrix(0.544639 -0.838671 0.838671 0.544639 8729.12 27765.1)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(3, 1, NULL, '', NULL, NULL, 0, 'B-03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '31072,16571 29959,18334 31069,19037 32187,17276 ', 'matrix(0.544639 -0.838671 0.838671 0.544639 7588.34 27028.7)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(4, 1, NULL, '', NULL, NULL, 0, 'B-04', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '29915,15838 28797,17598 29914,18306 31028,16542 ', 'matrix(0.544639 -0.838671 0.838671 0.544639 6412.1 26301.9)', 2, '', '', 1, NULL, NULL, NULL, 1),
(5, 1, NULL, '', NULL, NULL, 0, 'B-05', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '28761,15107 27643,16867 28752,17570 29870,15810 ', 'matrix(0.544639 -0.838671 0.838671 0.544639 5270 25578.1)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(6, 1, NULL, '', NULL, NULL, 0, 'B-06', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '27599,14371 26489,16136 27598,16839 28716,15079 ', 'matrix(0.544639 -0.838671 0.838671 0.544639 4117.96 24827.9)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(7, 1, NULL, '', NULL, NULL, 0, 'B-07', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '26444,13640 25326,15400 26444,16108 27554,14343 ', 'matrix(0.544639 -0.838671 0.838671 0.544639 2968.42 24111.6)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(8, 1, NULL, '', NULL, NULL, 0, 'B-08', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '25290,12909 24172,14669 25282,15372 26400,13612 ', 'matrix(0.544639 -0.838671 0.838671 0.544639 1798.22 23372.6)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(9, 1, NULL, '', NULL, NULL, 0, 'B-09', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '24128,12173 23015,13936 24128,14641 25246,12881 ', 'matrix(0.544639 -0.838671 0.838671 0.544639 658.903 22632.3)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(10, 1, NULL, '', NULL, NULL, 0, 'B-10', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '22974,11443 21861,13205 22970,13908 24083,12145 ', 'matrix(0.544639 -0.838671 0.838671 0.544639 -484.457 21858)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(11, 1, NULL, '', NULL, NULL, 0, 'C-01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '20106,13375 18991,12669 17878,14431 18992,15137 ', 'matrix(0.544639 -0.838671 0.838671 0.544639 -4493.08 23112.8)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(12, 1, NULL, '', NULL, NULL, 0, 'C-02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '21263,14108 20151,13403 19036,15165 20147,15869 ', 'matrix(0.544639 -0.838671 0.838671 0.544639 -3364.83 23876.7)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(13, 1, NULL, '', NULL, NULL, 0, 'C-03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '22418,14839 21308,14136 20192,15897 21302,16601 ', 'matrix(0.544639 -0.838671 0.838671 0.544639 -2195.04 24606.2)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(14, 1, NULL, '', NULL, NULL, 0, 'C-04', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '23580,15575 22462,14867 21347,16629 22464,17337 ', 'matrix(0.544639 -0.838671 0.838671 0.544639 -1051.86 25344.8)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(15, 1, NULL, '', NULL, NULL, 0, 'C-05', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '23615,18066 24734,16305 23625,15603 22509,17365 ', 'matrix(0.544639 -0.838671 0.838671 0.544639 119.772 26087.1)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(16, 1, NULL, '', NULL, NULL, 0, 'C-06', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '24779,16334 23660,18095 24777,18803 25891,17038 ', 'matrix(0.544639 -0.838671 0.838671 0.544639 1276.11 26803.9)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(17, 1, NULL, '', NULL, NULL, 0, 'C-08', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '23789,20592 25018,21291 26046,19670 24850,18912 ', 'matrix(0.544639 -0.838671 0.838671 0.544639 1414.93 29345.7)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(18, 1, NULL, '', NULL, NULL, 0, 'C-09', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '22548,19887 23743,20566 24806,18883 23648,18150 ', 'matrix(0.544639 -0.838671 0.838671 0.544639 190.192 28595.8)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(19, 1, NULL, '', NULL, NULL, 0, 'C-10', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '21346,19203 22502,19860 23603,18122 22481,17410 ', 'matrix(0.544639 -0.838671 0.838671 0.544639 -997.188 27830.7)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(20, 1, NULL, '', NULL, NULL, 0, 'E-01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '6492,4749 5283,3984 4214,5775 5376,6511 ', 'matrix(0.544639 -0.838671 0.838671 0.544639 -18110.9 14434.7)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(21, 1, NULL, '', NULL, NULL, 0, 'E-02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '7649,5482 6536,4778 5421,6539 6532,7243 ', 'matrix(0.544639 -0.838671 0.838671 0.544639 -16958.1 15228.3)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(22, 1, NULL, '', NULL, NULL, 0, 'E-03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '8808,6216 7694,5510 6577,7271 7693,7978 ', 'matrix(0.544639 -0.838671 0.838671 0.544639 -15785.6 15959.2)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(23, 1, NULL, '', NULL, NULL, 0, 'E-04', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '9962,6947 8853,6244 7738,8006 8847,8709 ', 'matrix(0.544639 -0.838671 0.838671 0.544639 -14643.8 16696.7)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(24, 1, NULL, '', NULL, NULL, 0, 'E-05', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '11120,7680 10007,6975 8892,8737 10004,9441 ', 'matrix(0.544639 -0.838671 0.838671 0.544639 -13474.5 17438.2)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(25, 1, NULL, '', NULL, NULL, 0, 'E-06', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '12279,8414 11164,7708 10049,9470 11161,10174 ', 'matrix(0.544639 -0.838671 0.838671 0.544639 -12314.1 18154.1)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(26, 1, NULL, '', NULL, NULL, 0, 'E-07', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '11133,10218 10021,9514 8782,11471 9891,12174 ', 'matrix(0.544639 -0.838671 0.838671 0.544639 -13508.1 20071.8)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(27, 1, NULL, '', NULL, NULL, 0, 'E-08', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '9976,9486 8863,8781 7625,10738 8737,11443 ', 'matrix(0.544639 -0.838671 0.838671 0.544639 -14681 19334.2)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(28, 1, NULL, '', NULL, NULL, 0, 'E-09', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '8819,8753 7709,8051 6471,10006 7580,10709 ', 'matrix(0.544639 -0.838671 0.838671 0.544639 -15820.1 18595.3)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(29, 1, NULL, '', NULL, NULL, 0, 'E-10', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '7665,8022 6549,7316 5309,9270 6426,9978 ', 'matrix(0.544639 -0.838671 0.838671 0.544639 -16963.3 17819.8)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(30, 1, NULL, '', NULL, NULL, 0, 'E-11', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '6504,7287 5393,6584 4155,8539 5264,9242 ', 'matrix(0.544639 -0.838671 0.838671 0.544639 -18102.8 17051.5)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(31, 1, NULL, '', NULL, NULL, 0, 'E-12', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '5348,6556 4187,5820 3001,7807 4111,8510 ', 'matrix(0.544639 -0.838671 0.838671 0.544639 -19287.2 16334.9)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(32, 1, NULL, '', NULL, NULL, 0, 'B-11', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '21816,10710 20699,12469 21816,13177 22929,11414 ', 'matrix(0.544639 -0.838671 0.838671 0.544639 -1624.78 21092.1)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(33, 1, NULL, '', NULL, NULL, 0, 'B-12', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '20662,9979 19545,11738 20654,12441 21772,10682 ', 'matrix(0.544639 -0.838671 0.838671 0.544639 -2809.86 20389.5)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(34, 1, NULL, '', NULL, NULL, 0, 'B-13', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '19500,9243 18387,11005 19500,11710 20618,9951 ', 'matrix(0.544639 -0.838671 0.838671 0.544639 -3951.29 19654.1)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(35, 1, NULL, '', NULL, NULL, 0, 'B-14', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '18346,8512 17228,10271 18343,10977 19455,9215 ', 'matrix(0.544639 -0.838671 0.838671 0.544639 -5126.87 18926.3)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(36, 1, NULL, '', NULL, NULL, 0, 'B-15', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '17188,7779 16074,9540 17184,10243 18301,8484 ', 'matrix(0.544639 -0.838671 0.838671 0.544639 -6267.43 18202.5)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(37, 1, NULL, '', NULL, NULL, 0, 'B-16', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '15902,6965 14916,8806 16030,9512 17144,7751 ', 'matrix(0.544639 -0.838671 0.838671 0.544639 -7423.3 17412.9)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(38, 1, NULL, '', NULL, NULL, 0, 'C-11', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '20148,18523 21300,19177 22436,17382 21319,16674 ', 'matrix(0.544639 -0.838671 0.838671 0.544639 -2161.59 27086.7)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(39, 1, NULL, '', NULL, NULL, 0, 'C-12', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '18957,17845 20103,18497 21274,16645 20163,15942 ', 'matrix(0.544639 -0.838671 0.838671 0.544639 -3367.8 26411.3)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(40, 1, NULL, '', NULL, NULL, 0, 'C-13', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '17768,17170 18911,17819 20119,15913 19008,15209 ', 'matrix(0.544639 -0.838671 0.838671 0.544639 -4525.48 25704.5)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(41, 1, NULL, '', NULL, NULL, 0, 'C-14', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '16576,16492 17722,17143 18963,15181 17850,14476 ', 'matrix(0.544639 -0.838671 0.838671 0.544639 -5714.01 25003.8)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(42, 1, NULL, '', NULL, NULL, 0, 'D-01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '14402,9758 13408,11604 14648,12390 15766,10622 ', 'matrix(0.544639 -0.838671 0.838671 0.544639 -8884.92 20273.8)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(43, 1, NULL, '', NULL, NULL, 0, 'D-02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '16928,11358 15811,10650 14693,12419 15808,13125 ', 'matrix(0.544639 -0.838671 0.838671 0.544639 -7690.44 21118)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(44, 1, NULL, '', NULL, NULL, 0, 'D-03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '18082,12089 16973,11386 15853,13154 16961,13857 ', 'matrix(0.544639 -0.838671 0.838671 0.544639 -6518.58 21848.6)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(45, 1, NULL, '', NULL, NULL, 0, 'D-04', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '14487,15307 15630,15956 16933,13901 15824,13198 ', 'matrix(0.544639 -0.838671 0.838671 0.544639 -7791.39 23809.5)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(46, 1, NULL, '', NULL, NULL, 0, 'D-05', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '13295,14630 14441,15281 15780,13170 14665,12463 ', 'matrix(0.544639 -0.838671 0.838671 0.544639 -8950.77 23114.2)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(47, 1, NULL, '', NULL, NULL, 0, 'D-06', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '13382,11651 12132,13970 13249,14604 14620,12435 ', 'matrix(0.544639 -0.838671 0.838671 0.544639 -10110 22352.6)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(48, 1, NULL, '', NULL, NULL, 0, 'F-01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '12569,7320 13684,5555 12575,4852 11455,6615 ', 'matrix(0.544639 -0.838671 0.838671 0.544639 -10889.2 15265.5)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(49, 1, NULL, '', NULL, NULL, 0, 'F-02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '11410,6586 12530,4824 11418,4119 10298,5882 ', 'matrix(0.544639 -0.838671 0.838671 0.544639 -12074.1 14562.7)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(50, 1, NULL, '', NULL, NULL, 0, 'F-03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '10253,5853 11373,4090 10259,3384 9144,5151 ', 'matrix(0.544639 -0.838671 0.838671 0.544639 -13214.7 13826)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(51, 1, NULL, '', NULL, NULL, 0, 'F-04', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '9099,5122 10214,3356 9102,2651 7981,4415 ', 'matrix(0.544639 -0.838671 0.838671 0.544639 -14390.8 13099)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(52, 1, NULL, '', NULL, NULL, 0, 'F-05', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '7937,4386 9057,2623 7948,1920 6827,3684 ', 'matrix(0.544639 -0.838671 0.838671 0.544639 -15532.7 12374.9)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(53, 1, NULL, '', NULL, NULL, 0, 'F-06', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '6783,3655 7903,1891 6041,711 4970,2508 ', 'matrix(0.544639 -0.838671 0.838671 0.544639 -17036 11388.4)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(54, 1, NULL, '', NULL, NULL, 0, 'A-01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '29072,20917 30835,22033 31678,20704 29913,19588 ', 'matrix(0.866645 0.498926 -0.498926 0.866645 19950.8 -2155.3)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(55, 1, NULL, '', NULL, NULL, 0, 'A-02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '30806,22078 29043,20961 28202,22289 29963,23408 ', 'matrix(0.866645 0.498926 -0.498926 0.866645 19047.3 -808.461)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(56, 1, NULL, '', NULL, NULL, 0, 'A-03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '29935,23452 28174,22334 27272,23757 29088,24789 ', 'matrix(0.866645 0.498926 -0.498926 0.866645 18149.6 582.119)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(57, 1, NULL, '', NULL, NULL, 0, 'A-04', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '27262,19771 29027,20888 29869,19560 28102,18443 ', 'matrix(0.866645 0.498926 -0.498926 0.866645 18107.5 -3327.63)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(58, 1, NULL, '', NULL, NULL, 0, 'A-05', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '28999,20933 27234,19816 26396,21141 28158,22261 ', 'matrix(0.866645 0.498926 -0.498926 0.866645 17229.1 -1942.76)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(59, 1, NULL, '', NULL, NULL, 0, 'A-06', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '27226,23731 28129,22305 26367,21186 25410,22699 ', 'matrix(0.866645 0.498926 -0.498926 0.866645 16319.1 -520.611)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(60, 1, NULL, '', NULL, NULL, 0, 'C-07', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'path', 'M25936 17066l-1114 1765 30 19c1,1 3,1 4,2 2,1 3,2 5,4l1214 769 1118 -1762 -1257 -797z', 'matrix(0.544639 -0.838671 0.838671 0.544639 2520.71 27596)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(61, 2, NULL, '', NULL, NULL, 0, 'B-01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '23759,14535 25555,15670 26433,14291 24582,13240 ', 'matrix(0.83822 0.545332 -0.545332 0.83822 15918.7 -9023.14)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(62, 2, NULL, '', NULL, NULL, 0, 'B-02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '22918,15859 24748,16935 25526,15714 23731,14579 ', 'matrix(0.83822 0.545332 -0.545332 0.83822 15013.6 -7750.03)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(63, 2, NULL, '', NULL, NULL, 0, 'B-03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '21917,13370 23715,14506 24536,13214 22684,12162 ', 'matrix(0.83822 0.545332 -0.545332 0.83822 14021 -10158)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(64, 2, NULL, '', NULL, NULL, 0, 'B-04', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '21039,14754 22873,15833 23686,14551 21889,13415 ', 'matrix(0.83822 0.545332 -0.545332 0.83822 13152 -8884.29)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(65, 2, NULL, '', NULL, NULL, 0, 'A-01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '28528,17094 30324,18230 31130,16958 29278,15907 ', 'matrix(0.848438 0.529295 -0.529295 0.848438 20223.1 -6254.51)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(66, 2, NULL, '', NULL, NULL, 0, 'A-02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '30295,18274 28499,17139 27783,18273 29577,19408 ', 'matrix(0.848438 0.529295 -0.529295 0.848438 19401.9 -5077.82)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(67, 2, NULL, '', NULL, NULL, 0, 'A-03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '27022,19477 28852,20552 29549,19453 27755,18318 ', 'matrix(0.848438 0.529295 -0.529295 0.848438 18651.4 -3901.8)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(68, 2, NULL, '', NULL, NULL, 0, 'A-04', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '26688,15931 28483,17066 29232,15881 27385,14832 ', 'matrix(0.848438 0.529295 -0.529295 0.848438 18321.3 -7403.11)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(69, 2, NULL, '', NULL, NULL, 0, 'A-05', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '28455,17111 26660,15976 25942,17110 27738,18245 ', 'matrix(0.848438 0.529295 -0.529295 0.848438 17549.9 -6227.94)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(70, 2, NULL, '', NULL, NULL, 0, 'A-06', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '25142,18374 26977,19451 27710,18290 25914,17154 ', 'matrix(0.848438 0.529295 -0.529295 0.848438 16794.2 -5033.98)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(71, 2, NULL, '', NULL, NULL, 0, 'C-01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '19116,11616 20985,12801 21733,11622 19806,10527 ', 'matrix(0.845479 0.534009 -0.534009 0.845479 10919.3 -11703)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(72, 2, NULL, '', NULL, NULL, 0, 'C-02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '20956,12846 19088,11660 18372,12789 20237,13979 ', 'matrix(0.845479 0.534009 -0.534009 0.845479 10127.5 -10576.5)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(73, 2, NULL, '', NULL, NULL, 0, 'C-05', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '19001,11698 17133,10516 16417,11651 18285,12827 ', 'matrix(0.845479 0.534009 -0.534009 0.845479 8160.89 -11711.7)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(74, 2, NULL, '', NULL, NULL, 0, 'C-06', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '18257,12872 16389,11696 15680,12820 17582,13936 ', 'matrix(0.845479 0.534009 -0.534009 0.845479 7437.07 -10565.3)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(75, 2, NULL, '', NULL, NULL, 0, 'D-01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '16190,9958 14951,9230 13570,11581 14732,12264 ', 'matrix(0.506513 -0.862232 0.862232 0.506513 -8155.92 20988.4)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(76, 2, NULL, '', NULL, NULL, 0, 'D-02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '14905,9203 13756,8527 12375,10880 13525,11555 ', 'matrix(0.506513 -0.862232 0.862232 0.506513 -9423.31 20315.6)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(77, 2, NULL, '', NULL, NULL, 0, 'D-03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '13710,8501 12556,7822 11175,10176 12329,10853 ', 'matrix(0.506513 -0.862232 0.862232 0.506513 -10606.5 19609.3)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(78, 2, NULL, '', NULL, NULL, 0, 'D-04', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '10892,6844 9584,6076 8202,8430 9510,9198 ', 'matrix(0.506513 -0.862232 0.862232 0.506513 -13517.3 17912.7)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(79, 2, NULL, '', NULL, NULL, 0, 'D-05', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '9538,6049 8238,5285 6855,7640 8156,8404 ', 'matrix(0.506513 -0.862232 0.862232 0.506513 -14854.1 17129.9)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(80, 2, NULL, '', NULL, NULL, 0, 'D-06', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '8192,5258 7036,4578 5653,6934 6810,7613 ', 'matrix(0.506513 -0.862232 0.862232 0.506513 -16126.4 16364.2)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(81, 2, NULL, '', NULL, NULL, 0, 'D-07', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '6990,4551 5836,3873 4453,6230 5607,6907 ', 'matrix(0.506513 -0.862232 0.862232 0.506513 -17315.8 15673.9)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(82, 2, NULL, '', NULL, NULL, 0, 'D-08', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '5791,3847 4634,3167 3250,5524 4407,6203 ', 'matrix(0.506513 -0.862232 0.862232 0.506513 -18531 14963.8)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(83, 2, NULL, '', NULL, NULL, 0, 'D-09', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '4589,3140 3435,2462 2051,4820 3205,5497 ', 'matrix(0.506513 -0.862232 0.862232 0.506513 -19717.4 14251.6)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(84, 2, NULL, '', NULL, NULL, 0, 'D-10', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '3389,2435 2199,1736 848,4114 2005,4793 ', 'matrix(0.506513 -0.862232 0.862232 0.506513 -20904.5 13492.8)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(85, 2, NULL, '', NULL, NULL, 0, 'C-03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'path', 'M20209 14024l-1865 -1190 -19 30c0,5 -1,11 -4,17 -4,5 -9,9 -14,11l-679 1071 1909 1121 672 -1060z', 'matrix(0.845479 0.534009 -0.534009 0.845479 9384.9 -9422.67)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(86, 2, NULL, '', NULL, NULL, 0, 'C-04', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'path', 'M19050 11621c0,-5 1,-9 4,-13 3,-5 6,-8 10,-10l696 -1097 -1927 -1094 -672 1065 1868 1181 21 -32z', 'matrix(0.845479 0.534009 -0.534009 0.845479 8922.17 -12866.5)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(87, 3, NULL, '', NULL, NULL, 0, 'B-01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '9227,18588 10604,19394 11142,18476 9766,17671 ', 'matrix(0.866025 0.5 -0.5 0.866025 545.712 -5623.28)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(88, 3, NULL, '', NULL, NULL, 0, 'B-02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '11169,18430 11675,17568 10300,16761 9792,17625 ', 'matrix(0.866025 0.5 -0.5 0.866025 1066.48 -6582.95)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(89, 3, NULL, '', NULL, NULL, 0, 'B-03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '11702,17523 12206,16664 10831,15856 10327,16715 ', 'matrix(0.866025 0.5 -0.5 0.866025 1601.85 -7477.06)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(90, 3, NULL, '', NULL, NULL, 0, 'B-04', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '12233,16618 12740,15755 11364,14948 10858,15811 ', 'matrix(0.866025 0.5 -0.5 0.866025 2130.7 -8395.87)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(91, 3, NULL, '', NULL, NULL, 0, 'B-05', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '12767,15709 13273,14847 11898,14038 11391,14903 ', 'matrix(0.866025 0.5 -0.5 0.866025 2655.04 -9294.39)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(92, 3, NULL, '', NULL, NULL, 0, 'B-06', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '13299,14802 13804,13943 12430,13133 11925,13993 ', 'matrix(0.866025 0.5 -0.5 0.866025 3202.6 -10198.8)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(93, 3, NULL, '', NULL, NULL, 0, 'B-07', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '13831,13897 14336,13036 12961,12229 12456,13088 ', 'matrix(0.866025 0.5 -0.5 0.866025 3721.32 -11093.8)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(94, 3, NULL, '', NULL, NULL, 0, 'B-08', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '14363,12991 14869,12129 13492,11324 12987,12183 ', 'matrix(0.866025 0.5 -0.5 0.866025 4257.16 -12011.3)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(95, 3, NULL, '', NULL, NULL, 0, 'B-09', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '14895,12083 15401,11221 14026,10414 13519,11278 ', 'matrix(0.866025 0.5 -0.5 0.866025 4795.06 -12907.8)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(96, 3, NULL, '', NULL, NULL, 0, 'B-12', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '16625,9138 17113,8307 16376,7605 15856,7297 15250,8328 ', 'matrix(0.866025 0.5 -0.5 0.866025 6557.1 -15936.3)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(97, 3, NULL, '', NULL, NULL, 0, 'B-13', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '17906,10198 18333,9470 17152,8344 16537,9392 ', 'matrix(0.866025 0.5 -0.5 0.866025 7812.39 -14871.3)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(98, 3, NULL, '', NULL, NULL, 0, 'B-14', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '17374,11102 17879,10243 16510,9437 16006,10296 ', 'matrix(0.866025 0.5 -0.5 0.866025 7317.01 -13884.5)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(99, 3, NULL, '', NULL, NULL, 0, 'B-15', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '16843,12007 17348,11148 15979,10342 15474,11203 ', 'matrix(0.866025 0.5 -0.5 0.866025 6776.71 -12968.9)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(100, 3, NULL, '', NULL, NULL, 0, 'B-16', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '16311,12912 16816,12052 15447,11248 14941,12110 ', 'matrix(0.866025 0.5 -0.5 0.866025 6259.48 -12061.7)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(101, 3, NULL, '', NULL, NULL, 0, 'B-17', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '15777,13821 16285,12957 14914,12156 14408,13018 ', 'matrix(0.866025 0.5 -0.5 0.866025 5714.48 -11143.4)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(102, 3, NULL, '', NULL, NULL, 0, 'B-18', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '15244,14729 15750,13867 14381,13063 13876,13924 ', 'matrix(0.866025 0.5 -0.5 0.866025 5185.06 -10247.8)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(103, 3, NULL, '', NULL, NULL, 0, 'B-19', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '14712,15634 15217,14775 13849,13969 13345,14829 ', 'matrix(0.866025 0.5 -0.5 0.866025 4658.79 -9330.1)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(104, 3, NULL, '', NULL, NULL, 0, 'B-20', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '14181,16538 14685,15679 13318,14874 12812,15736 ', 'matrix(0.866025 0.5 -0.5 0.866025 4084.99 -8459.36)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(105, 3, NULL, '', NULL, NULL, 0, 'B-21', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '13646,17448 14154,16584 12785,15781 12279,16645 ', 'matrix(0.866025 0.5 -0.5 0.866025 3583.68 -7536.9)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(106, 3, NULL, '', NULL, NULL, 0, 'B-22', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '13115,18353 13619,17494 12252,16690 11747,17550 ', 'matrix(0.866025 0.5 -0.5 0.866025 3022.5 -6653.21)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(107, 3, NULL, '', NULL, NULL, 0, 'B-23', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '12583,19257 13088,18398 11721,17595 11215,18457 ', 'matrix(0.866025 0.5 -0.5 0.866025 2492.17 -5737.08)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(108, 3, NULL, '', NULL, NULL, 0, 'B-24', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '10649,19420 12017,20220 12556,19303 11188,18502 ', 'matrix(0.866025 0.5 -0.5 0.866025 1946.46 -4810.78)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(109, 3, NULL, '', NULL, NULL, 0, 'C-01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '13282,19734 12745,20649 14114,21455 14654,20537 ', 'matrix(0.866025 0.5 -0.5 0.866025 4050.2 -3573.28)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(110, 3, NULL, '', NULL, NULL, 0, 'C-02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '13814,18826 13308,19689 14680,20491 15186,19630 ', 'matrix(0.866025 0.5 -0.5 0.866025 4569.76 -4532.36)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(111, 3, NULL, '', NULL, NULL, 0, 'C-03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '14349,17916 13841,18781 15213,19585 15719,18723 ', 'matrix(0.866025 0.5 -0.5 0.866025 5105.29 -5428.54)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(112, 3, NULL, '', NULL, NULL, 0, 'C-04', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '14879,17011 14375,17871 15746,18678 16250,17819 ', 'matrix(0.866025 0.5 -0.5 0.866025 5634.47 -6347.13)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(113, 3, NULL, '', NULL, NULL, 0, 'C-05', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '15410,16106 14906,16966 16277,17773 16783,16911 ', 'matrix(0.866025 0.5 -0.5 0.866025 6157.87 -7240.89)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(114, 3, NULL, '', NULL, NULL, 0, 'C-06', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '15941,15202 15437,16061 16810,16866 17316,16005 ', 'matrix(0.866025 0.5 -0.5 0.866025 6704.37 -8145.27)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(115, 3, NULL, '', NULL, NULL, 0, 'C-07', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '16475,14291 15968,15156 17342,15960 17849,15098 ', 'matrix(0.866025 0.5 -0.5 0.866025 7223.46 -9043.71)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(116, 3, NULL, '', NULL, NULL, 0, 'C-08', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '17006,13387 16502,14246 17875,15052 18380,14193 ', 'matrix(0.866025 0.5 -0.5 0.866025 7760.09 -9961.72)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(117, 3, NULL, '', NULL, NULL, 0, 'C-11', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '18954,10067 18230,11301 19604,12110 20141,11195 ', 'matrix(0.866025 0.5 -0.5 0.866025 9579.53 -13054.8)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(118, 3, NULL, '', NULL, NULL, 0, 'C-12', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '19519,12358 20888,13165 21363,12356 20181,11232 ', 'matrix(0.866025 0.5 -0.5 0.866025 10806.6 -11967.8)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(119, 3, NULL, '', NULL, NULL, 0, 'C-13', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '20861,13210 19492,12404 18985,13268 20357,14069 ', 'matrix(0.866025 0.5 -0.5 0.866025 10290.2 -10918.3)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(120, 3, NULL, '', NULL, NULL, 0, 'C-14', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '18958,13314 18452,14175 19822,14979 20330,14115 ', 'matrix(0.866025 0.5 -0.5 0.866025 9755.65 -10020.3)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(121, 3, NULL, '', NULL, NULL, 0, 'C-15', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '18425,14220 17921,15079 19291,15884 19795,15025 ', 'matrix(0.866025 0.5 -0.5 0.866025 9214.16 -9104)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(122, 3, NULL, '', NULL, NULL, 0, 'C-16', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '17894,15125 17388,15986 18759,16788 19264,15929 ', 'matrix(0.866025 0.5 -0.5 0.866025 8696.82 -8197.72)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(123, 3, NULL, '', NULL, NULL, 0, 'C-17', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '17361,16032 16855,16893 18226,17696 18733,16834 ', 'matrix(0.866025 0.5 -0.5 0.866025 8152.04 -7280.55)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(124, 3, NULL, '', NULL, NULL, 0, 'C-18', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '16829,16938 16322,17800 17692,18606 18199,17742 ', 'matrix(0.866025 0.5 -0.5 0.866025 7622.81 -6384.45)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(125, 3, NULL, '', NULL, NULL, 0, 'C-19', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '16296,17845 15791,18704 17160,19511 17665,18651 ', 'matrix(0.866025 0.5 -0.5 0.866025 7095.77 -5466.27)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(126, 3, NULL, '', NULL, NULL, 0, 'C-20', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '15764,18750 15258,19612 16629,20415 17133,19556 ', 'matrix(0.866025 0.5 -0.5 0.866025 6521.87 -4595.6)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(127, 3, NULL, '', NULL, NULL, 0, 'C-21', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '15231,19657 14726,20518 16097,21320 16602,20461 ', 'matrix(0.866025 0.5 -0.5 0.866025 6021.25 -3675.86)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(128, 3, NULL, '', NULL, NULL, 0, 'C-22', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '14699,20563 14159,21482 15528,22288 16071,21365 ', 'matrix(0.866025 0.5 -0.5 0.866025 5444.01 -2761.76)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(129, 3, NULL, '', NULL, NULL, 0, 'D-01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '16795,21792 16256,22710 17625,23516 18164,22598 ', 'matrix(0.866025 0.5 -0.5 0.866025 7568.82 -1502.68)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(130, 3, NULL, '', NULL, NULL, 0, 'D-02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '17326,20887 16821,21747 18190,22553 18695,21693 ', 'matrix(0.866025 0.5 -0.5 0.866025 8088.82 -2459.68)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(131, 3, NULL, '', NULL, NULL, 0, 'D-03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '17857,19983 17353,20842 18722,21648 19228,20786 ', 'matrix(0.866025 0.5 -0.5 0.866025 8623.43 -3352.45)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(132, 3, NULL, '', NULL, NULL, 0, 'D-04', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '18391,19072 17884,19937 19254,20741 19761,19877 ', 'matrix(0.866025 0.5 -0.5 0.866025 9152.32 -4273.64)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(133, 3, NULL, '', NULL, NULL, 0, 'D-05', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '18923,18168 18418,19027 19788,19832 20292,18972 ', 'matrix(0.866025 0.5 -0.5 0.866025 9676.51 -5169.52)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(134, 3, NULL, '', NULL, NULL, 0, 'D-06', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '19454,17263 18949,18122 20319,18927 20825,18065 ', 'matrix(0.866025 0.5 -0.5 0.866025 10223.3 -6072.59)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(135, 3, NULL, '', NULL, NULL, 0, 'D-07', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '19985,16358 19480,17218 20852,18020 21356,17160 ', 'matrix(0.866025 0.5 -0.5 0.866025 10741.6 -6968.93)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(136, 3, NULL, '', NULL, NULL, 0, 'D-08', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '20521,15445 20012,16313 21383,17115 21890,16251 ', 'matrix(0.866025 0.5 -0.5 0.866025 11278 -7889.78)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(137, 3, NULL, '', NULL, NULL, 0, 'D-09', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '21052,14540 20548,15400 21917,16206 22421,15347 ', 'matrix(0.866025 0.5 -0.5 0.866025 11817.6 -8784.41)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(138, 3, NULL, '', NULL, NULL, 0, 'D-10', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '21984,12953 21079,14495 22448,15301 23167,14077 ', 'matrix(0.866025 0.5 -0.5 0.866025 12493.4 -10018.4)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(139, 3, NULL, '', NULL, NULL, 0, 'D-11', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '23206,14114 22493,15328 23867,16137 24393,15242 ', 'matrix(0.866025 0.5 -0.5 0.866025 13845.1 -9006.24)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(140, 3, NULL, '', NULL, NULL, 0, 'D-12', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '22467,15373 21962,16233 23335,17041 23840,16182 ', 'matrix(0.866025 0.5 -0.5 0.866025 13274.9 -7947.68)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(141, 3, NULL, '', NULL, NULL, 0, 'D-13', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '21935,16278 21429,17141 22804,17946 23309,17087 ', 'matrix(0.866025 0.5 -0.5 0.866025 12744 -7031.62)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(142, 3, NULL, '', NULL, NULL, 0, 'D-14', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '21402,17187 20897,18046 22272,18850 22777,17991 ', 'matrix(0.866025 0.5 -0.5 0.866025 12209.9 -6136.94)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(143, 3, NULL, '', NULL, NULL, 0, 'D-15', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '20871,18092 20365,18954 21737,19760 22245,18895 ', 'matrix(0.866025 0.5 -0.5 0.866025 11669.1 -5218.74)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(144, 3, NULL, '', NULL, NULL, 0, 'D-16', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '20338,18999 19833,19858 21205,20664 21710,19805 ', 'matrix(0.866025 0.5 -0.5 0.866025 11151 -4311.13)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(145, 3, NULL, '', NULL, NULL, 0, 'D-17', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '19807,19904 19300,20767 20672,21572 21179,20710 ', 'matrix(0.866025 0.5 -0.5 0.866025 10605.4 -3395.28)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(146, 3, NULL, '', NULL, NULL, 0, 'D-18', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '19273,20813 18767,21675 20137,22481 20645,21617 ', 'matrix(0.866025 0.5 -0.5 0.866025 10076.1 -2497.89)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(147, 3, NULL, '', NULL, NULL, 0, 'D-19', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '18740,21720 18236,22579 19605,23386 20110,22527 ', 'matrix(0.866025 0.5 -0.5 0.866025 9549.02 -1579.73)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(148, 3, NULL, '', NULL, NULL, 0, 'D-20', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '18209,22625 17670,23543 19039,24349 19579,23431 ', 'matrix(0.866025 0.5 -0.5 0.866025 8958.71 -679.922)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(149, 3, NULL, '', NULL, NULL, 0, 'E-01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '20589,23374 19767,24777 20640,25290 21462,23887 ', 'matrix(0.529919 -0.848048 0.848048 0.529919 -3857.48 33113.2)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(150, 3, NULL, '', NULL, NULL, 0, 'E-02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '21507,23913 20685,25317 21528,25811 22351,24409 ', 'matrix(0.529919 -0.848048 0.848048 0.529919 -2978.14 33670.8)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(151, 3, NULL, '', NULL, NULL, 0, 'E-03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '22396,24436 21573,25838 22419,26334 23241,24933 ', 'matrix(0.529919 -0.848048 0.848048 0.529919 -2076.36 34191)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(152, 3, NULL, '', NULL, NULL, 0, 'E-04', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '23287,24960 22464,26361 23310,26857 24132,25457 ', 'matrix(0.529919 -0.848048 0.848048 0.529919 -1198.65 34718.4)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(153, 3, NULL, '', NULL, NULL, 0, 'E-05', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '24177,25483 23355,26884 24195,27377 25018,25978 ', 'matrix(0.529919 -0.848048 0.848048 0.529919 -298.544 35248.5)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(154, 3, NULL, '', NULL, NULL, 0, 'E-06', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '25064,26004 24241,27403 25083,27898 25905,26499 ', 'matrix(0.529919 -0.848048 0.848048 0.529919 589.246 35755)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(155, 3, NULL, '', NULL, NULL, 0, 'E-07', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '25950,26526 25129,27925 25974,28421 26795,27022 ', 'matrix(0.529919 -0.848048 0.848048 0.529919 1488.92 36289.7)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(156, 3, NULL, '', NULL, NULL, 0, 'E-08', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '26841,27049 26020,28447 26865,28944 27686,27546 ', 'matrix(0.529919 -0.848048 0.848048 0.529919 2366.58 36808.5)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(157, 3, NULL, '', NULL, NULL, 0, 'E-09', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '28533,26103 27688,25606 26867,27004 27712,27501 ', 'matrix(0.529919 -0.848048 0.848048 0.529919 3227.45 35359.5)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(158, 3, NULL, '', NULL, NULL, 0, 'E-10', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '27642,25579 26797,25082 25977,26480 26822,26977 ', 'matrix(0.529919 -0.848048 0.848048 0.529919 2349.28 34799.1)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(159, 3, NULL, '', NULL, NULL, 0, 'E-11', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '26752,25056 25912,24562 25090,25959 25931,26453 ', 'matrix(0.529919 -0.848048 0.848048 0.529919 1475.62 34247.1)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(160, 3, NULL, '', NULL, NULL, 0, 'E-12', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '25867,24535 25025,24040 24204,25438 25045,25932 ', 'matrix(0.529919 -0.848048 0.848048 0.529919 565.328 33752.8)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(161, 3, NULL, '', NULL, NULL, 0, 'E-13', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '24979,24013 24134,23517 23313,24914 24159,25411 ', 'matrix(0.529919 -0.848048 0.848048 0.529919 -311.702 33228.7)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(162, 3, NULL, '', NULL, NULL, 0, 'E-14', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '24089,23490 23243,22993 22423,24391 23268,24887 ', 'matrix(0.529919 -0.848048 0.848048 0.529919 -1214.82 32708.3)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(163, 3, NULL, '', NULL, NULL, 0, 'E-15', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '23198,22966 22353,22469 21534,23868 22377,24364 ', 'matrix(0.529919 -0.848048 0.848048 0.529919 -2093.19 32193.1)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(164, 3, NULL, '', NULL, NULL, 0, 'E-16', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '22307,22443 21435,21929 20616,23328 21488,23841 ', 'matrix(0.529919 -0.848048 0.848048 0.529919 -2995.8 31647.2)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(165, 3, NULL, '', NULL, NULL, 0, 'F-01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '21863,21209 22781,21748 23588,20374 22668,19832 ', 'matrix(0.529919 -0.848048 0.848048 0.529919 -1741.72 29564.5)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(166, 3, NULL, '', NULL, NULL, 0, 'F-02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '22827,21774 23686,22279 24492,20906 23634,20400 ', 'matrix(0.529919 -0.848048 0.848048 0.529919 -832.477 30141.2)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(167, 3, NULL, '', NULL, NULL, 0, 'F-03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '23731,22305 24756,22906 25665,21596 24538,20933 ', 'matrix(0.529919 -0.848048 0.848048 0.529919 219.023 30718.6)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(168, 3, NULL, '', NULL, NULL, 0, 'F-04', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '24564,20887 25695,21553 26603,20245 25368,19519 ', 'matrix(0.529919 -0.848048 0.848048 0.529919 1091.31 29339.2)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(169, 3, NULL, '', NULL, NULL, 0, 'F-05', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '23660,20355 24519,20860 25322,19492 24463,18988 ', 'matrix(0.529919 -0.848048 0.848048 0.529919 10.3628 28735.5)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(170, 3, NULL, '', NULL, NULL, 0, 'F-06', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '22695,19787 23615,20328 24418,18961 23495,18419 ', 'matrix(0.529919 -0.848048 0.848048 0.529919 -922.707 28170.4)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(171, 3, NULL, '', NULL, NULL, 0, 'F-07', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '27860,18434 26469,17469 25899,18290 27287,19259 ', 'matrix(0.866025 0.5 -0.5 0.866025 17220.6 -5783.17)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(172, 3, NULL, '', NULL, NULL, 0, 'F-08', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '28457,17573 27067,16609 26499,17426 27890,18391 ', 'matrix(0.866025 0.5 -0.5 0.866025 17823.2 -6659.7)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(173, 3, NULL, '', NULL, NULL, 0, 'F-09', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '29057,16709 27666,15745 27097,16566 28487,17530 ', 'matrix(0.866025 0.5 -0.5 0.866025 18427.7 -7509.67)', 0, '', '', NULL, NULL, NULL, NULL, 1);
INSERT INTO `kavling_peta` (`id`, `id_lokasi`, `id_projek`, `cluster`, `blok`, `no`, `id_perusahaan`, `kode_kavling`, `panjang_kanan`, `panjang_kiri`, `lebar_depan`, `lebar_belakang`, `luas_tanah`, `tipe_bangunan`, `daya_listrik`, `luas_bangunan`, `hrg_meter`, `hrg_jual`, `biaya_surat`, `peningkatan_mutu`, `id_rumah_sikumbang`, `no_sertifikat`, `jenis_map`, `map`, `matrik`, `status`, `keterangan`, `atas_nama_surat`, `id_customer`, `tgl_jatuh_tempo`, `stt_cicilan`, `foto`, `status_ready`) VALUES
(174, 3, NULL, '', NULL, NULL, 0, 'F-10', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '29654,15849 28267,14880 27697,15702 29087,16666 ', 'matrix(0.866025 0.5 -0.5 0.866025 19064 -8362.39)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(175, 3, NULL, '', NULL, NULL, 0, 'F-11', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '30257,14980 28867,14016 28297,14837 29684,15805 ', 'matrix(0.866025 0.5 -0.5 0.866025 19697.1 -9210.81)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(176, 3, NULL, '', NULL, NULL, 0, 'F-12', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '30855,14119 29465,13156 28897,13973 30287,14937 ', 'matrix(0.866025 0.5 -0.5 0.866025 20267.5 -10098.5)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(177, 3, NULL, '', NULL, NULL, 0, 'F-13', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '31454,13255 30066,12290 29495,13112 30885,14076 ', 'matrix(0.866025 0.5 -0.5 0.866025 20867.8 -10949.9)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(178, 3, NULL, '', NULL, NULL, 0, 'F-14', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '32055,12390 30666,11427 30096,12247 31484,13212 ', 'matrix(0.866025 0.5 -0.5 0.866025 21466 -11825.9)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(179, 3, NULL, '', NULL, NULL, 0, 'F-15', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '32654,11526 31264,10566 30696,11383 32085,12346 ', 'matrix(0.866025 0.5 -0.5 0.866025 22057.2 -12677.8)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(180, 3, NULL, '', NULL, NULL, 0, 'F-16', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '33252,10665 31863,9702 31294,10523 32684,11483 ', 'matrix(0.866025 0.5 -0.5 0.866025 22669.5 -13540)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(181, 3, NULL, '', NULL, NULL, 0, 'F-17', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '33851,9801 32464,8837 31894,9659 33282,10622 ', 'matrix(0.866025 0.5 -0.5 0.866025 23256.4 -14393.2)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(182, 3, NULL, '', NULL, NULL, 0, 'F-18', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '34452,8936 33064,7973 32495,8794 33881,9758 ', 'matrix(0.866025 0.5 -0.5 0.866025 23861.1 -15269.1)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(183, 3, NULL, '', NULL, NULL, 0, 'F-19', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '33672,7098 33094,7930 34482,8892 35052,8072 ', 'matrix(0.866025 0.5 -0.5 0.866025 24466.9 -16127.3)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(184, 3, NULL, '', NULL, NULL, 0, 'G-01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '18099,5891 17376,5202 16282,6352 17005,7041 ', 'matrix(0.679588 -0.733594 0.733594 0.679588 -8518.15 10180.7)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(185, 3, NULL, '', NULL, NULL, 0, 'G-02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '18859,6617 18137,5928 17043,7077 17766,7766 ', 'matrix(0.679588 -0.733594 0.733594 0.679588 -7788.81 10926.2)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(186, 3, NULL, '', NULL, NULL, 0, 'G-03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '19620,7343 18897,6654 17804,7802 18527,8491 ', 'matrix(0.679588 -0.733594 0.733594 0.679588 -7012.73 11649.5)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(187, 3, NULL, '', NULL, NULL, 0, 'G-04', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '20380,8069 19658,7379 18565,8528 19288,9217 ', 'matrix(0.679588 -0.733594 0.733594 0.679588 -6268.03 12381.9)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(188, 3, NULL, '', NULL, NULL, 0, 'G-05', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '21141,8794 20418,8105 19326,9253 20049,9942 ', 'matrix(0.679588 -0.733594 0.733594 0.679588 -5493.52 13114.7)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(189, 3, NULL, '', NULL, NULL, 0, 'G-06', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '21901,9520 21179,8831 20087,9978 20810,10667 ', 'matrix(0.679588 -0.733594 0.733594 0.679588 -4730.53 13827.6)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(190, 3, NULL, '', NULL, NULL, 0, 'G-07', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '22662,10246 21939,9557 20848,10703 21571,11392 ', 'matrix(0.679588 -0.733594 0.733594 0.679588 -3955.5 14564.1)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(191, 3, NULL, '', NULL, NULL, 0, 'G-08', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '23422,10972 22700,10282 21609,11429 22331,12118 ', 'matrix(0.679588 -0.733594 0.733594 0.679588 -3214.15 15282.1)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(192, 3, NULL, '', NULL, NULL, 0, 'G-09', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '24206,11673 23743,11278 23460,11008 22370,12154 23092,12843 ', 'matrix(0.679588 -0.733594 0.733594 0.679588 -2421.67 16002.3)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(193, 3, NULL, '', NULL, NULL, 0, 'G-10', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '25008,12357 24247,11708 23131,12879 23850,13565 ', 'matrix(0.679588 -0.733594 0.733594 0.679588 -1625.11 16681.3)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(194, 3, NULL, '', NULL, NULL, 0, 'G-11', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '25806,13038 25049,12392 23888,13601 24611,14290 ', 'matrix(0.679588 -0.733594 0.733594 0.679588 -827.516 17363.8)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(195, 3, NULL, '', NULL, NULL, 0, 'G-12', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '26609,13723 25847,13073 24649,14326 25372,15015 ', 'matrix(0.679588 -0.733594 0.733594 0.679588 -77.3761 18086.9)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(196, 3, NULL, '', NULL, NULL, 0, 'G-13', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '26346,15944 27414,14409 26649,13757 25410,15052 ', 'matrix(0.679588 -0.733594 0.733594 0.679588 720.254 18891.9)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(197, 3, NULL, '', NULL, NULL, 0, 'A-01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '6793,20128 7651,20632 8457,19263 7598,18759 ', 'matrix(0.529919 -0.848048 0.848048 0.529919 -16872.4 28470.9)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(198, 3, NULL, '', NULL, NULL, 0, 'A-02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '7696,20659 8564,21168 9362,19794 8502,19290 ', 'matrix(0.529919 -0.848048 0.848048 0.529919 -15992.9 29031.6)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(199, 3, NULL, '', NULL, NULL, 0, 'A-03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '8609,21195 9468,21700 10272,20328 9407,19821 ', 'matrix(0.529919 -0.848048 0.848048 0.529919 -15068.8 29560.3)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(200, 3, NULL, '', NULL, NULL, 0, 'A-04', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '9514,21726 10373,22231 11180,20861 10317,20355 ', 'matrix(0.529919 -0.848048 0.848048 0.529919 -14175.6 30097.1)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(201, 3, NULL, '', NULL, NULL, 0, 'A-05', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '10419,22258 11278,22762 12084,21392 11225,20888 ', 'matrix(0.529919 -0.848048 0.848048 0.529919 -13259.4 30637.5)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(202, 3, NULL, '', NULL, NULL, 0, 'A-06', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '11323,22789 12188,23297 12995,21926 12130,21419 ', 'matrix(0.529919 -0.848048 0.848048 0.529919 -12350.2 31155.7)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(203, 3, NULL, '', NULL, NULL, 0, 'A-07', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '12233,23323 13093,23828 13899,22458 13040,21953 ', 'matrix(0.529919 -0.848048 0.848048 0.529919 -11432.3 31701.1)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(204, 3, NULL, '', NULL, NULL, 0, 'A-08', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '13138,23855 13997,24359 14804,22989 13945,22484 ', 'matrix(0.529919 -0.848048 0.848048 0.529919 -10540.8 32227.8)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(205, 3, NULL, '', NULL, NULL, 0, 'A-09', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '14043,24386 14905,24892 15709,23520 14850,23015 ', 'matrix(0.529919 -0.848048 0.848048 0.529919 -9622.53 32754)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(206, 3, NULL, '', NULL, NULL, 0, 'A-10', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '14950,24919 15815,25427 16619,24054 15755,23546 ', 'matrix(0.529919 -0.848048 0.848048 0.529919 -8701.15 33250)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(207, 3, NULL, '', NULL, NULL, 0, 'A-11', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '15860,25453 16720,25958 17527,24587 16665,24081 ', 'matrix(0.529919 -0.848048 0.848048 0.529919 -7777.39 33752.8)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(208, 3, NULL, '', NULL, NULL, 0, 'A-12', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '16765,25985 17624,26489 18432,25118 17573,24613 ', 'matrix(0.529919 -0.848048 0.848048 0.529919 -6897.12 34312)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(209, 3, NULL, '', NULL, NULL, 0, 'A-13', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '17670,26516 18529,27020 19337,25649 18477,25145 ', 'matrix(0.529919 -0.848048 0.848048 0.529919 -5980.4 34841.5)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(210, 3, NULL, '', NULL, NULL, 0, 'A-14', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '18574,27047 19439,27555 20247,26183 19382,25676 ', 'matrix(0.529919 -0.848048 0.848048 0.529919 -5085.53 35377.4)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(211, 3, NULL, '', NULL, NULL, 0, 'A-15', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '19484,27582 20344,28086 21152,26714 20292,26210 ', 'matrix(0.529919 -0.848048 0.848048 0.529919 -4166.68 35918.5)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(212, 3, NULL, '', NULL, NULL, 0, 'A-16', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '20389,28113 21251,28619 22056,27245 21197,26741 ', 'matrix(0.529919 -0.848048 0.848048 0.529919 -3260.15 36436)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(213, 3, NULL, '', NULL, NULL, 0, 'A-17', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '21297,28646 22161,29154 22961,27776 22102,27272 ', 'matrix(0.529919 -0.848048 0.848048 0.529919 -2343.37 36981.4)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(214, 3, NULL, '', NULL, NULL, 0, 'A-18', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '22207,29180 23121,29717 23924,28342 23007,27803 ', 'matrix(0.529919 -0.848048 0.848048 0.529919 -1419.99 37524.3)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(215, 3, NULL, '', NULL, NULL, 0, 'A-19', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '23167,29744 24085,30283 24893,28910 23970,28368 ', 'matrix(0.529919 -0.848048 0.848048 0.529919 -442.267 38083.9)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(216, 3, NULL, '', NULL, NULL, 0, 'A-20', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '24130,30310 25048,30848 25856,29476 24938,28937 ', 'matrix(0.529919 -0.848048 0.848048 0.529919 484.733 38691.8)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(217, 3, NULL, '', NULL, NULL, 0, 'A-21', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '25093,30875 26011,31414 26816,30039 25901,29502 ', 'matrix(0.529919 -0.848048 0.848048 0.529919 1461.12 39227.4)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(218, 3, NULL, '', NULL, NULL, 0, 'A-22', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '26056,31441 27073,32038 27865,30655 26862,30066 ', 'matrix(0.529919 -0.848048 0.848048 0.529919 2442.83 39848.2)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(219, 3, NULL, '', NULL, NULL, 0, 'KS-01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '6749,20099 6988,19692 5222,18655 4984,19062 ', 'matrix(0.867948 0.496655 -0.496655 0.867948 -3784.79 -4820.83)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(220, 3, NULL, '', NULL, NULL, 0, 'KS-02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '7015,19647 7254,19240 5486,18202 5248,18609 ', 'matrix(0.867948 0.496655 -0.496655 0.867948 -3538.7 -5288.8)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(221, 3, NULL, '', NULL, NULL, 0, 'KS-03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '7280,19195 7519,18788 5750,17749 5513,18156 ', 'matrix(0.867948 0.496655 -0.496655 0.867948 -3271.88 -5733.54)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(222, 3, NULL, '', NULL, NULL, 0, 'KS-04', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '8097,17849 8367,17382 6584,16328 6310,16793 ', 'matrix(0.867948 0.496655 -0.496655 0.867948 -2451.63 -7125.12)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(223, 3, NULL, '', NULL, NULL, 0, 'KS-05', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '8393,17336 8663,16869 6883,15821 6611,16282 ', 'matrix(0.867948 0.496655 -0.496655 0.867948 -2158.77 -7627.85)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(224, 3, NULL, '', NULL, NULL, 0, 'KS-06', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '8690,16823 8960,16356 7184,15310 6910,15775 ', 'matrix(0.867948 0.496655 -0.496655 0.867948 -1851.23 -8138.24)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(225, 3, NULL, '', NULL, NULL, 0, 'KS-07', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '8986,16311 9254,15847 7484,14800 7211,15265 ', 'matrix(0.867948 0.496655 -0.496655 0.867948 -1562.21 -8643.31)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(226, 3, NULL, '', NULL, NULL, 0, 'KS-08', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '9281,15801 9520,15387 7751,14348 7511,14755 ', 'matrix(0.867948 0.496655 -0.496655 0.867948 -1276.49 -9132.05)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(227, 3, NULL, '', NULL, NULL, 0, 'KS-09', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '9546,15341 9782,14933 8017,13896 7778,14303 ', 'matrix(0.867948 0.496655 -0.496655 0.867948 -1008.1 -9579.67)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(228, 3, NULL, '', NULL, NULL, 0, 'KS-10', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'polygon', '10050,14470 8285,13441 8044,13851 9809,14887 ', 'matrix(0.867948 0.496655 -0.496655 0.867948 -716.112 -10026.3)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(229, 3, NULL, '', NULL, NULL, 0, 'B-10', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'path', 'M15428 11176l515 -877c0,-2 1,-5 3,-7 1,-3 3,-5 5,-6l49 -84 -1374 -810 -573 976 1375 808z', 'matrix(0.866025 0.5 -0.5 0.866025 5399.22 -13860.4)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(230, 3, NULL, '', NULL, NULL, 0, 'B-11', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'path', 'M16027 10156l447 -762c1,-2 2,-5 3,-7 1,-2 3,-4 5,-6l116 -198 -1374 -809 -571 973 1374 809z', 'matrix(0.866025 0.5 -0.5 0.866025 6029.26 -14865.7)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(231, 3, NULL, '', NULL, NULL, 0, 'C-09', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'path', 'M17606 12365l-573 976 1374 807 516 -880c1,-1 2,-2 2,-4 1,-1 2,-2 3,-3l51 -87 -1373 -809z', 'matrix(0.866025 0.5 -0.5 0.866025 8331.04 -10912.6)', 0, '', '', NULL, NULL, NULL, NULL, 1),
(232, 3, NULL, '', NULL, NULL, 0, 'C-10', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'path', 'M19453 12367c0,-5 1,-10 4,-15 2,-4 6,-7 10,-10l110 -187 -1374 -809 -570 973 1373 809 447 -761z', 'matrix(0.866025 0.5 -0.5 0.866025 8967.47 -11919.9)', 0, '', '', NULL, NULL, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `konfigurasi`
--

CREATE TABLE `konfigurasi` (
  `id` int NOT NULL,
  `nama_perusahaan` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `telp` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `hape` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `npwp_perusahaan` varchar(75) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `front_page` int NOT NULL,
  `folder_svg` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `konfigurasi`
--

INSERT INTO `konfigurasi` (`id`, `nama_perusahaan`, `alamat`, `email`, `telp`, `hape`, `npwp_perusahaan`, `front_page`, `folder_svg`) VALUES
(1, 'PT. DWIJAYA NUSANTARA PROPERTI', 'Jl. DIPONEGORO GG. V NO. 2D', 'admin@aplikasikavling.com', '081250274777', '081250274777', '', 0, '/home/sidd4282/public_html/property.aplikasikavling.com');

-- --------------------------------------------------------

--
-- Table structure for table `konfigurasi_media`
--

CREATE TABLE `konfigurasi_media` (
  `id` int NOT NULL,
  `jenis_data` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `keterangan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_file` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `urutan` int NOT NULL,
  `jenis_download` int NOT NULL,
  `stt_aktif` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `konfigurasi_media`
--

INSERT INTO `konfigurasi_media` (`id`, `jenis_data`, `keterangan`, `nama_file`, `urutan`, `jenis_download`, `stt_aktif`) VALUES
(1, 'Logo website', 'Logo yang ditampilkan pada halaman login', 'Ddfmm5TZaNW80zT7gUTk0GAt3.png', 1, 0, 1),
(4, 'Logo Rekap', 'Logo Cetak Rekap pada menu pembayaran', 'iyTC7P8bK8fzCbh6XpGpnLidz.png', 4, 0, 1),
(5, 'Background Rekap', 'Background Cetak Rekap pada menu pembayaran	', 'Screenshot 2025-11-25 200919.webp', 5, 0, 1),
(6, 'fav icon', 'logo web', 'CJEfVwquiuSmqpmbOiOGkwRAx.png', 2, 0, 1),
(14, 'Background booking', 'Background yang ada di tampilan booking', 'bCmpAmmjWxDS8k4nJQPhpBg3O.png', 3, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `konfigurasi_wa`
--

CREATE TABLE `konfigurasi_wa` (
  `id` int NOT NULL,
  `api_key` varchar(75) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `number_key` varchar(75) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `legal`
--

CREATE TABLE `legal` (
  `id` int NOT NULL,
  `id_kavling` int NOT NULL,
  `ukuran` int NOT NULL,
  `luas` int NOT NULL,
  `no_shm` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `atas_nama` varchar(75) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tgl_pengajuan` date NOT NULL,
  `tgl_proses` date NOT NULL,
  `tgl_release` date NOT NULL,
  `tgl_serah_terima` date NOT NULL,
  `tgl_data` date NOT NULL,
  `status_legal` int NOT NULL,
  `lampiran` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `stt_legal` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `listrik_air`
--

CREATE TABLE `listrik_air` (
  `id` int NOT NULL,
  `id_lokasi` int NOT NULL,
  `id_kavling` int NOT NULL,
  `norek_listrik` varchar(35) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `foto_listrik` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `foto_listrik_2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `norek_air` varchar(35) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `foto_air` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `foto_air_2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `log_aktivitas_pengguna`
--

CREATE TABLE `log_aktivitas_pengguna` (
  `id` int NOT NULL,
  `id_user` int NOT NULL,
  `user_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `aktivitas` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `log_aktivitas_pengguna`
--

INSERT INTO `log_aktivitas_pengguna` (`id`, `id_user`, `user_name`, `aktivitas`, `created_at`) VALUES
(1, 1, 'master', 'User master dengan id 1 melakukan login pada tanggal 04 Juli 2026 jam 23:46', '2026-07-04 23:46:25'),
(2, 1, 'master', 'User master dengan id 1 melakukan login pada tanggal 04 Juli 2026 jam 23:47', '2026-07-04 23:47:30'),
(3, 1, 'master', 'User master dengan id 1 melakukan login pada tanggal 05 Juli 2026 jam 00:00', '2026-07-05 00:00:46'),
(4, 1, 'master', 'User master dengan id 1 pada tanggal 05 Juli 2026 jam 00:01 di menu Pengaturan Media melakukan edit data dengan id 6', '2026-07-05 00:01:04'),
(5, 1, 'master', 'User master dengan id 1 pada tanggal 05 Juli 2026 jam 00:01 di menu Pengaturan Media melakukan edit data dengan id 1', '2026-07-05 00:01:46'),
(6, 1, 'master', 'User master dengan id 1 melakukan login pada tanggal 05 Juli 2026 jam 09:43', '2026-07-05 09:43:12'),
(7, 1, 'master', 'User master dengan id 1 pada tanggal 05 Juli 2026 jam 12:27 di menu Verifikasi Data Booking melakukan create data dengan id 1', '2026-07-05 12:27:38'),
(8, 1, 'master', 'User master dengan id 1 pada tanggal 05 Juli 2026 jam 12:29 di menu Bank KPR melakukan create data dengan id 1', '2026-07-05 12:29:28'),
(9, 1, 'master', 'User master dengan id 1 pada tanggal 05 Juli 2026 jam 12:29 di menu Bank KPR melakukan create data dengan id 2', '2026-07-05 12:29:33'),
(10, 1, 'master', 'User master dengan id 1 pada tanggal 05 Juli 2026 jam 12:30 di menu Bank Transaksi melakukan create data dengan id 1', '2026-07-05 12:30:12'),
(11, 1, 'master', 'User master dengan id 1 pada tanggal 05 Juli 2026 jam 12:30 di menu Verifikasi Data Booking melakukan create data dengan id 1', '2026-07-05 12:30:26'),
(12, 1, 'master', 'User master dengan id 1 pada tanggal 05 Juli 2026 jam 13:47 di menu Perusahaan melakukan create data dengan id 2', '2026-07-05 13:47:55'),
(13, 1, 'master', 'User master dengan id 1 pada tanggal 05 Juli 2026 jam 13:47 di menu Perusahaan melakukan delete data dengan id 1', '2026-07-05 13:47:58'),
(14, 1, 'master', 'User master dengan id 1 pada tanggal 05 Juli 2026 jam 13:48 di menu Lokasi Perumahan melakukan edit data dengan id 1', '2026-07-05 13:48:13'),
(15, 1, 'master', 'User master dengan id 1 pada tanggal 05 Juli 2026 jam 13:48 di menu Lokasi Perumahan melakukan edit data dengan id 2', '2026-07-05 13:48:19'),
(16, 1, 'master', 'User master dengan id 1 pada tanggal 05 Juli 2026 jam 13:48 di menu Lokasi Perumahan melakukan edit data dengan id 3', '2026-07-05 13:48:23'),
(17, 1, 'master', 'User master dengan id 1 pada tanggal 05 Juli 2026 jam 14:38 di menu Pengaturan Profil melakukan edit data dengan id 1', '2026-07-05 14:38:52'),
(18, 1, 'master', 'User master dengan id 1 pada tanggal 05 Juli 2026 jam 15:36 di menu Hak Akses melakukan edit data dengan id 1', '2026-07-05 15:36:16'),
(19, 1, 'master', 'User master dengan id 1 pada tanggal 05 Juli 2026 jam 15:36 di menu Retensi melakukan create data dengan id 1', '2026-07-05 15:36:38'),
(20, 1, 'master', 'User master dengan id 1 pada tanggal 05 Juli 2026 jam 15:36 di menu Retensi melakukan create data dengan id 2', '2026-07-05 15:36:44'),
(21, 1, 'master', 'User master dengan id 1 pada tanggal 05 Juli 2026 jam 15:36 di menu Retensi melakukan create data dengan id 3', '2026-07-05 15:36:51'),
(22, 1, 'master', 'User master dengan id 1 pada tanggal 05 Juli 2026 jam 15:42 di menu Pencairan KPR melakukan create data dengan id 2', '2026-07-05 15:42:55'),
(23, 1, 'master', 'User master dengan id 1 pada tanggal 05 Juli 2026 jam 15:44 di menu Hak Akses melakukan edit data dengan id 1', '2026-07-05 15:44:09'),
(24, 1, 'master', 'User master dengan id 1 pada tanggal 05 Juli 2026 jam 15:52 di menu Hak Akses melakukan edit data dengan id 1', '2026-07-05 15:52:19'),
(25, 1, 'master', 'User master dengan id 1 melakukan logout pada tanggal 05 Juli 2026 jam 15:56', '2026-07-05 15:56:32'),
(26, 1, 'master', 'User master dengan id 1 melakukan login pada tanggal 05 Juli 2026 jam 15:57', '2026-07-05 15:57:37');

-- --------------------------------------------------------

--
-- Table structure for table `lokasi_kavling`
--

CREATE TABLE `lokasi_kavling` (
  `id` int NOT NULL,
  `nama_kavling` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_singkat` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `urutan` int NOT NULL,
  `stt_tampil` int NOT NULL DEFAULT '0',
  `header` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_cluster` tinyint(1) NOT NULL DEFAULT '0',
  `no_kwitansi` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_bast` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_ppjb` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `reset_nomor` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lokasi_kavling`
--

INSERT INTO `lokasi_kavling` (`id`, `nama_kavling`, `nama_singkat`, `alamat`, `urutan`, `stt_tampil`, `header`, `is_cluster`, `no_kwitansi`, `no_bast`, `no_ppjb`, `reset_nomor`) VALUES
(1, 'DE ALASKA RESIDENCE', 'DAR', 'SDASDF', 1, 1, 'ASD', 0, 'ASD', 'ASD', 'ASD', 1),
(2, 'DE ALASKA RESIDENCE 2', 'DAR', 'SDASDF', 2, 1, 'ASD', 0, 'ASD', 'ASD', 'ASD', 1),
(3, 'DE ALASKA DE ALASKA SERINITY', 'DAR', 'SDASDF', 3, 1, 'ASD', 0, 'ASD', 'ASD', 'ASD', 1);

-- --------------------------------------------------------

--
-- Table structure for table `lokasi_kavling_perusahaan`
--

CREATE TABLE `lokasi_kavling_perusahaan` (
  `id` int NOT NULL,
  `id_lokasi` int NOT NULL,
  `id_perusahaan` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lokasi_kavling_perusahaan`
--

INSERT INTO `lokasi_kavling_perusahaan` (`id`, `id_lokasi`, `id_perusahaan`) VALUES
(1, 1, 2),
(2, 2, 2),
(3, 3, 2);

-- --------------------------------------------------------

--
-- Table structure for table `marketing_freelance`
--

CREATE TABLE `marketing_freelance` (
  `id` int NOT NULL,
  `kode_freelance` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_freelance` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis_kelamin` int NOT NULL,
  `alamat` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_telp` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pekerjaan` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sosmed` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int NOT NULL,
  `foto` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_bank` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_rekening` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `atas_nama` varchar(75) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `marketing_offline`
--

CREATE TABLE `marketing_offline` (
  `id` int NOT NULL,
  `kode_marketing` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_marketing` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis_kelamin` int NOT NULL,
  `alamat` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_telp` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pekerjaan` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sosmed` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama_bank` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_rekening` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `atas_nama` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int NOT NULL,
  `foto` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `master_svg`
--

CREATE TABLE `master_svg` (
  `id` int NOT NULL,
  `id_lokasi` int NOT NULL,
  `header_xml` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `header_svg` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `polygon_svg` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `path_svg` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `body_svg` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `footer_svg` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `lebar` int NOT NULL,
  `tinggi` int NOT NULL,
  `ukuran_dashboard` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `master_svg`
--

INSERT INTO `master_svg` (`id`, `id_lokasi`, `header_xml`, `header_svg`, `polygon_svg`, `path_svg`, `body_svg`, `footer_svg`, `lebar`, `tinggi`, `ukuran_dashboard`) VALUES
(1, 1, '<?xml version=\"1.0\" encoding=\"UTF-8\"?>', '<svg id=\"svg-image-1\" xmlns=\"http://www.w3.org/2000/svg\" xml:space=\"preserve\" width=\"400mm\" height=\"297mm\" version=\"1.1\" style=\"shape-rendering:geometricPrecision; text-rendering:geometricPrecision; image-rendering:optimizeQuality; fill-rule:evenodd; clip-rule:evenodd\"\r\nviewBox=\"0 0 40000 29700\"\r\n xmlns:xlink=\"http://www.w3.org/1999/xlink\">\r\n <defs>\r\n  <font id=\"FontID0\" horiz-adv-x=\"647\" font-variant=\"normal\" style=\"fill-rule:nonzero\" font-style=\"normal\" font-weight=\"400\">\r\n	<font-face \r\n		font-family=\"Bahnschrift\">\r\n		<font-face-src>\r\n			<font-face-name name=\"Bahnschrift\"/>\r\n		</font-face-src>\r\n	</font-face>\r\n   <missing-glyph><path d=\"M0 0z\"/></missing-glyph>\r\n   <glyph unicode=\"D\" horiz-adv-x=\"656\" d=\"M155.338 0l0 94.6567 160.663 0c52.3396,0 92.9964,13.667 121.667,40.8391 28.8323,27.172 43.3295,65.5004 43.3295,115.005l0 208.994c0,49.505 -14.4972,87.8333 -43.3295,115.005 -28.6704,27.172 -69.3272,40.6568 -121.667,40.6568l-160.663 0 0 94.8389 157.667 0c85.9908,0 152.321,-22.1709 199.498,-66.6545 46.9943,-44.5038 70.5015,-107.008 70.5015,-187.835l0 -201.178c0,-53.6557 -10.5084,-99.4958 -31.667,-137.318 -21.1788,-38.0044 -51.9954,-67.019 -92.3283,-87.0032 -40.3329,-20.0045 -89.1697,-30.0067 -146.51,-30.0067l-157.161 0zm-67.5049 0l0 709.996 99.6578 0 0 -709.996 -99.6578 0z\"/>\r\n   <glyph unicode=\"3\" horiz-adv-x=\"528\" d=\"M256.839 -7.32957c-40.0089,0 -75.1787,7.32957 -105.165,21.6647 -30.1687,14.4972 -54.3441,35.4937 -72.506,62.8278 -18.3442,27.334 -30.0067,60.4993 -35.1698,99.6781l0 0 101.5 0 0 0c4.49493,-30.6748 16.6636,-53.0078 36.1619,-67.181 19.4983,-14.1529 44.5038,-21.3205 75.1787,-21.3205 32.8211,0 58.4948,8.82788 76.6567,26.3217 18.1619,17.676 27.334,42.3374 27.334,74.3485l0 21.4825c0,35.514 -8.6659,62.848 -25.8357,82.1843 -17.3318,19.4983 -41.6692,29.1563 -73.3362,29.1563l-50.1529 0 0 94.677 50.1529 0c28.0022,0 49.6669,8.32169 64.8525,24.8233 14.9831,16.6636 22.6569,40.1709 22.6569,70.3395l0 21.9887c0,28.0022 -8.15971,49.6669 -24.1754,65.0145 -16.1574,15.3273 -38.9965,22.8188 -68.659,22.8188 -25.0056,0 -46.3261,-6.98536 -63.6579,-21.1586 -17.514,-14.1732 -29.5005,-36.5061 -35.9999,-67.1607l0 0 -101.014 0 0 0c10.1642,58.6568 32.6793,103.991 67.6669,135.982 35.0078,32.1732 79.3497,48.1686 133.005,48.1686 61.1675,0 108.506,-16.1574 142.177,-48.3306 33.4892,-32.3351 50.1529,-77.6691 50.1529,-136.326l0 -11.6625c0,-35.514 -9.49604,-66.3306 -28.6704,-92.5105 -19.316,-26.1597 -46.4881,-45.496 -81.6579,-57.8266 38.8346,-8.15971 68.8412,-27.172 90.1618,-56.9965 21.3408,-29.6827 32.0112,-67.5049 32.0112,-113.507l0 -11.6625c0,-62.1798 -17.838,-110.328 -53.3317,-144.506 -35.514,-34.1574 -85.5049,-51.3272 -150.337,-51.3272z\"/>\r\n   <glyph unicode=\"A\" horiz-adv-x=\"647\" d=\"M19.4983 0l261.84 709.996 84.8367 0 261.819 -709.996 -107.494 0 -196.825 572.84 -196.683 -572.84 -107.494 0zm120.675 155.824l0 94.677 373.484 0 0 -94.677 -373.484 0z\"/>\r\n   <glyph unicode=\"0\" horiz-adv-x=\"551\" d=\"M275.831 -7.32957c-67.6669,0 -118.326,18.1619 -151.997,54.5061 -33.6715,36.1619 -50.4971,85.3226 -50.4971,147.158l0 322.825c0,62.848 16.9876,112.009 51.1652,147.847 34.1574,35.8177 84.6747,53.8177 151.329,53.8177 67.1607,0 117.678,-17.8177 151.673,-53.4937 33.9954,-35.6557 51.0033,-85.1607 51.0033,-148.171l0 -322.825c0,-62.8278 -17.0078,-112.171 -51.0033,-148.009 -33.9954,-35.8177 -84.5127,-53.6557 -151.673,-53.6557zm0 94.6567c36.5061,0 62.8278,9.67827 78.8435,28.6704 16.1574,19.0123 24.1552,45.172 24.1552,78.3373l0 322.825c0,33.5095 -7.99773,59.6692 -24.1552,78.4993 -16.0157,19.0123 -42.3374,28.3464 -78.8435,28.3464 -36.3239,0 -62.6658,-9.33406 -78.8232,-28.3464 -16.1777,-18.8301 -24.1754,-44.9898 -24.1754,-78.4993l0 -322.825c0,-33.1653 7.99773,-59.325 24.1754,-78.3373 16.1574,-18.9921 42.4993,-28.6704 78.8232,-28.6704z\"/>\r\n   <glyph unicode=\"-\" horiz-adv-x=\"479\" d=\"M78.1753 301.828l328.15 0 0 -94.8187 -328.15 0 0 94.8187z\"/>\r\n   <glyph unicode=\"8\" horiz-adv-x=\"562\" d=\"M281.338 -7.32957c-43.3295,0 -81.1719,8.15971 -113.345,24.4994 -32.3351,16.1574 -57.3205,39.1585 -75.1584,68.821 -18,29.5005 -26.8278,64.1843 -26.8278,104.011l0 11.6625c0,35.4937 8.82788,68.8412 26.4836,100.164 17.838,31.181 41.5072,54.83 71.0077,70.6635 -24.6614,13.667 -44.6658,33.5095 -59.9931,59.3452 -15.3475,25.9977 -23.0011,53.1697 -23.0011,81.8198l0 17.1698c0,56.1664 18.3239,101.338 54.992,135.516 36.6681,33.9954 85.1809,50.983 145.842,50.983 60.4993,0 108.992,-16.9876 145.66,-50.983 36.6681,-34.1777 54.992,-79.3497 54.992,-135.516l0 -17.1698c0,-29.3183 -7.81551,-56.8143 -23.325,-82.488 -15.3273,-25.6737 -35.8379,-45.334 -61.1675,-58.677 30.0067,-15.8335 53.9999,-39.4825 71.9998,-70.6635 18,-31.3228 27.0101,-64.6703 27.0101,-100.164l0 -11.6625c0,-39.8267 -8.84812,-74.5105 -26.8481,-104.011 -17.8177,-29.6625 -42.8233,-52.6635 -75.1584,-68.821 -32.1732,-16.3397 -69.9953,-24.4994 -113.163,-24.4994zm0 95.6691c34.8256,0 62.8278,10.0022 84.1685,29.9864 21.3205,20.1664 31.9909,46.3464 31.9909,79.0054l0 6.84363c0,32.8211 -10.6704,59.325 -31.9909,79.3294 -21.3408,20.0045 -49.343,29.9864 -84.1685,29.9864 -34.8458,0 -63.01,-9.98198 -84.3305,-29.9864 -21.3408,-20.0045 -32.0112,-46.6703 -32.0112,-79.8356l0 -7.32957c0,-32.6793 10.6704,-58.677 32.0112,-78.3373 21.3205,-19.8425 49.4847,-29.6625 84.3305,-29.6625zm0 320.82c30.1687,0 54.668,9.67827 73.1539,28.8323 18.5061,19.1743 27.8402,44.6658 27.8402,76.1708l0 6.84363c0,30.3306 -9.33406,54.668 -27.8402,72.992 -18.4859,18.3442 -42.9853,27.496 -73.1539,27.496 -30.3306,0 -54.83,-9.15184 -73.3362,-27.496 -18.5061,-18.3239 -27.8402,-42.8233 -27.8402,-73.4981l0 -7.32957c0,-31.181 9.33406,-56.3283 27.8402,-75.3407 18.5061,-19.1541 43.0055,-28.6704 73.3362,-28.6704z\"/>\r\n   <glyph unicode=\"F\" horiz-adv-x=\"558\" d=\"M87.8333 0l0 709.996 99.6578 0 0 -709.996 -99.6578 0zm48.8368 298.832l0 94.677 344.328 0 0 -94.677 -344.328 0zm0 316.325l0 94.8389 398.004 0 0 -94.8389 -398.004 0z\"/>\r\n   <glyph unicode=\"5\" horiz-adv-x=\"544\" d=\"M271.498 -7.32957c-53.9999,0 -98.3215,15.4893 -133.005,46.6703 -34.6636,30.9988 -56.4903,74.9965 -65.1562,131.487l0 0.506186 99.4958 0 0 -0.506186c3.66478,-25.9977 14.0112,-46.1641 31.343,-60.6613 17.1496,-14.4972 39.6647,-21.8267 67.3227,-21.8267 31.8289,0 56.5106,10.6704 74.0043,31.9909 17.3318,21.3408 25.9977,51.3272 25.9977,90.1618l0 59.5072c0,38.8346 -8.6659,68.659 -25.9977,89.8378 -17.4938,21.1586 -42.1754,31.8289 -74.0043,31.8289 -17.3318,0 -33.8334,-5.00111 -49.8289,-14.6591 -15.9955,-9.84025 -30.0067,-23.5073 -41.9932,-41.001l-90.8502 0 0 373.99 361.842 0 0 -94.8389 -262.164 0 0 -159.651c12.3307,10.1642 26.1597,18 41.487,23.487 15.3475,5.5073 30.8368,8.34194 46.8525,8.34194 61.8154,0 109.66,-18.9921 143.493,-56.8345 33.9954,-38.0044 50.821,-91.4981 50.821,-160.501l0 -59.5072c0,-69.3272 -17.3318,-122.983 -52.1574,-160.987 -34.8256,-37.8424 -84.0066,-56.8345 -147.502,-56.8345z\"/>\r\n   <glyph unicode=\"C\" horiz-adv-x=\"618\" d=\"M320.334 -7.32957c-49.505,0 -92.8344,10.9944 -129.826,33.0033 -37.1743,21.9887 -66.0066,52.8255 -86.5172,92.4902 -20.4904,39.6647 -30.6546,86.173 -30.6546,139.161l0 194.841c0,53.3317 10.1642,100.002 30.6546,139.667 20.5106,39.6647 49.343,70.5015 86.5172,92.5105 36.992,21.9887 80.3215,32.9831 129.826,32.9831 41.001,0 78.4993,-8.82788 112.495,-26.4836 33.9954,-17.514 62.5038,-42.0134 85.5049,-73.6804 23.0011,-31.505 38.4904,-68.497 46.6703,-110.834l0 0 -102.168 0 0 0c-6.17546,22.8391 -16.5017,42.6816 -30.9988,59.8311 -14.4972,17.1698 -31.505,30.3509 -51.0033,39.8469 -19.4983,9.33406 -39.6647,14.1529 -60.4993,14.1529 -43.6737,0 -78.6612,-15.4893 -105.165,-46.1641 -26.6659,-30.8368 -39.8267,-71.3317 -39.8267,-121.829l0 -194.841c0,-50.4971 13.1608,-90.9919 39.8267,-121.667 26.5039,-30.4926 61.4914,-45.8199 105.165,-45.8199 30.8368,0 59.9931,9.82 87.3271,29.3385 27.334,19.4983 45.8402,47.6624 55.1742,84.4925l0 0 102.168 0 0 0c-8.17996,-42.3374 -23.8312,-79.3294 -46.9943,-110.834 -23.0011,-31.667 -51.5094,-56.1664 -85.3429,-73.8424 -33.8334,-17.4938 -71.3317,-26.3217 -112.333,-26.3217z\"/>\r\n   <glyph unicode=\"2\" horiz-adv-x=\"516\" d=\"M62.5038 0l0 86.497 247.505 330.013c14.3149,18.8301 25.4915,38.4904 33.4892,58.6568 7.99773,20.1664 12.0067,39.1585 12.0067,56.9965l0 1.01237c0,28.3261 -8.34194,50.3148 -25.0056,65.9864 -16.5017,15.6715 -40.1709,23.3453 -70.6635,23.3453 -28.6704,0 -51.9954,-8.50392 -70.1776,-25.5118 -18,-17.1698 -28.9943,-41.325 -32.9831,-72.4858l0 -0.506186 -102.999 0 0 0.506186c8.82788,61.1472 31.1608,108.486 66.9987,142.319 35.6557,33.6715 81.8198,50.4971 138.168,50.4971 63.8199,0 112.981,-16.1574 147.826,-48.3306 34.8256,-32.3351 52.3396,-77.8311 52.3396,-136.832l0 -0.506186c0,-24.9853 -5.18334,-51.3272 -15.6715,-78.8232 -10.3464,-27.496 -24.8436,-53.6759 -43.5117,-78.8435l-205.491 -279.333 268.501 0 0 -94.6567 -400.332 0z\"/>\r\n   <glyph unicode=\"7\" horiz-adv-x=\"503\" d=\"M439.997 709.996l0 -88.8254 -183.664 -621.171 -105.995 0 183.664 615.157 -180.668 0 0 -101.986 -99.6578 0 0 196.825 386.321 0z\"/>\r\n   <glyph unicode=\"E\" horiz-adv-x=\"597\" d=\"M87.8333 0l0 709.996 99.6578 0 0 -709.996 -99.6578 0zm48.3306 0l0 94.6567 407.844 0 0 -94.6567 -407.844 0zm0 305.169l0 94.6567 354.006 0 0 -94.6567 -354.006 0zm0 309.988l0 94.8389 407.844 0 0 -94.8389 -407.844 0z\"/>\r\n   <glyph unicode=\"4\" horiz-adv-x=\"568\" d=\"M63.4959 108.83l0 86.497 238.332 514.163 102.513 0 -232.501 -508.332 348.154 0 0 -92.3283 -456.498 0zm306.161 -109.336l0 418.008 97.1674 0 0 -418.008 -97.1674 0z\"/>\r\n   <glyph unicode=\"1\" horiz-adv-x=\"332\" d=\"M244.67 709.996l0 -709.996 -99.6781 0 0 601.49 -100.994 -61.9976 0 102.999 100.994 67.5049 99.6781 0z\"/>\r\n   <glyph unicode=\"B\" horiz-adv-x=\"645\" d=\"M146.49 0l0 92.3283 192.351 0c52.8255,0 90.1618,10.0022 112.15,30.1687 21.847,20.1664 32.8413,46.8323 32.8413,79.6736l0 1.49831c0,35.1698 -9.33406,63.172 -28.0022,84.1685 -18.6681,20.9966 -49.505,31.505 -92.4902,31.505l-216.85 0 0 89.8176 216.85 0c36.6681,0 64.488,8.6659 83.1562,26.1799 18.8301,17.3318 28.1642,42.8233 28.1642,76.3328l0 0c0,35.4937 -10.4882,61.9976 -31.3228,79.6534 -20.8346,17.514 -51.9954,26.3419 -93.6646,26.3419l-203.183 0 0 92.3283 221.183 0c69.9953,0 121.991,-17.4938 156.33,-52.3396 34.1574,-34.8256 51.1652,-81.4959 51.1652,-140.153l0 0c0,-35.3318 -10.3262,-67.8289 -31.1608,-97.3294 -20.8346,-29.5005 -53.6759,-48.3508 -98.1798,-56.3486 43.8357,-6.49942 78.0133,-25.9977 102.33,-58.1506 24.1754,-32.1732 36.3441,-69.1854 36.3441,-110.834l0 -1.49831c0,-57.8469 -18.6681,-104.679 -56.1664,-140.173 -37.4982,-35.4937 -88.1775,-53.1697 -152.342,-53.1697l-229.505 0zm-58.6568 0l0 709.996 99.1719 0 0 -709.996 -99.1719 0z\"/>\r\n   <glyph unicode=\"9\" horiz-adv-x=\"513\" d=\"M146.004 0l178.663 352.508 -0.506186 -17.514c-8.32169,-15.3273 -20.9966,-26.3217 -37.4982,-33.1653 -16.6636,-6.82338 -35.8379,-10.3262 -57.6647,-10.3262 -51.1652,0 -92.3283,18.9921 -123.489,56.8345 -31.343,38.0044 -46.8525,88.3395 -46.8525,151.167l0 0.506186c0,68.983 17.352,122.497 52.0156,160.319 34.6636,38.0044 83.3181,56.9965 146.166,56.9965 62.8278,0 111.503,-19.1541 146.004,-57.4824 34.4814,-38.1866 51.8334,-92.3485 51.8334,-162.344l0 -0.506186c0,-28.8323 -4.33295,-60.1551 -12.8369,-93.9885 -8.34194,-33.6715 -20.8346,-66.5128 -37.0123,-98.8479l-151.329 -304.157 -107.494 0zm110.834 382.332c31.3228,0 55.4982,10.5084 72.4858,31.667 17.1698,21.1586 25.6737,51.0033 25.6737,89.4936l0 0.344206c0,37.4982 -8.50392,66.4925 -25.6737,87.0032 -16.9876,20.4904 -41.163,30.6546 -72.4858,30.6546 -31.505,0 -55.8424,-10.1642 -73.0122,-30.6546 -17.1496,-20.5106 -25.6535,-49.6669 -25.6535,-87.3474l0 -0.485938c0,-38.5106 8.50392,-68.1731 25.6535,-89.1697 17.1698,-20.9966 41.5072,-31.505 73.0122,-31.505z\"/>\r\n   <glyph unicode=\"6\" horiz-adv-x=\"513\" d=\"M256.332 -7.32957c-62.8278,0 -111.503,18.6681 -146.004,56.1664 -34.5016,37.4982 -51.6714,90.1618 -51.6714,158.173l0 0.485938c0,30.6748 4.17097,63.172 12.6749,97.5116 8.50392,34.3194 20.6726,67.3227 36.5061,99.3339l154.326 305.655 107.494 0 -181.154 -354.006 0.506186 17.514c16.8256,32.3351 46.9943,48.3306 90.3238,48.3306 55.6602,0 98.8277,-18.5061 129.341,-55.3362 30.6546,-36.992 46.0021,-89.1697 46.0021,-156.492l0 -0.506186c0,-68.659 -17.352,-121.991 -51.8334,-159.995 -34.5016,-37.8424 -83.3384,-56.8345 -146.51,-56.8345zm0 95.6691c31.9909,0 56.3283,9.82 73.3362,29.3183 16.8256,19.5185 25.3295,47.3385 25.3295,83.5004l0 0.506186c0,39.6647 -8.98986,70.3395 -26.8278,92.0043 -18,21.6647 -43.5117,32.4971 -76.677,32.4971 -29.6625,0 -52.6635,-10.8324 -68.821,-32.6591 -16.3397,-21.847 -24.4994,-52.6635 -24.4994,-92.3485l0 -0.485938c0,-36.1821 8.50392,-63.8401 25.6535,-83.1764 17.1698,-19.4983 41.3452,-29.1563 72.506,-29.1563z\"/>\r\n  </font>\r\n  <style type=\"text/css\">\r\n   <![CDATA[\r\n    @font-face { font-family:\"Bahnschrift\";font-variant:normal;font-style:normal;font-weight:normal;src:url(\"#FontID0\") format(svg)}\r\n    .fil4 {fill:none;fill-rule:nonzero}\r\n    .fil6 {fill:#332C2B}\r\n    .fil2 {fill:#FEFEFE;fill-rule:nonzero}\r\n    .fil1 {fill:#DCDDDD;fill-rule:nonzero}\r\n    .fil0 {fill:#332C2B;fill-rule:nonzero}\r\n    .fil5 {fill:#FDE9D2;fill-rule:nonzero}\r\n    .fil3 {fill:#A2D3AB;fill-rule:nonzero}\r\n    .fnt0 {font-weight:normal;font-size:493.89px;font-family:\'Bahnschrift\'}\r\n   ]]>\r\n  </style>\r\n </defs>\r\n <g id=\"Layer_x0020_1\">\r\n  <metadata id=\"CorelCorpID_0Corel-Layer\"/>\r\n  <g id=\"_2096178038048\">\r\n   <g>\r\n    <path class=\"fil0\" d=\"M15177 6444c3,0 5,1 7,2 4,2 6,4 9,7l700 444c4,-1 9,0 14,3 5,3 10,7 12,13l1261 798c6,0 12,1 18,4 5,4 9,9 11,15l1135 718c3,1 6,2 9,4 3,2 5,4 7,7l1135 719c5,0 9,1 12,4 4,2 7,5 9,9l1143 724c4,0 7,2 10,4 4,2 6,4 8,7l1134 718c5,0 9,2 13,4 4,3 7,6 9,10l4614 2922c1,0 2,1 3,1 1,1 2,2 3,2l1144 725c3,0 6,1 8,3 3,2 5,4 7,6l1150 728c2,1 4,2 5,3 2,1 3,2 4,3l1142 723c3,1 6,2 9,4 3,1 5,4 7,6l2300 1456c3,1 6,2 10,4 3,2 5,5 7,7l2394 1516c12,8 16,23 10,35l-997 1893c-1,2 -2,5 -3,7 -1,2 -2,3 -3,4l-2717 5159c-7,13 -23,18 -36,11l-20043 -11392c-12,-7 -17,-23 -10,-35l425 -910 -251 -142 -424 905c-7,13 -22,19 -35,13l-1457 -828c-2,-1 -4,-2 -6,-3 -1,0 -2,-1 -3,-2l-7987 -4538c-13,-8 -17,-24 -10,-36l4917 -8247c7,-13 24,-17 36,-9l1 0 5372 3405c2,1 3,2 5,3 1,1 3,2 4,3l3035 1924c12,7 16,23 9,35l-905 1930 242 153 900 -1923c6,-13 21,-19 35,-13l3 2 430 273z\"/>\r\n    <path class=\"fil0\" d=\"M30406 29209c-7,13 -23,18 -36,11 -13,-7 -18,-23 -11,-36l6596 -12206c7,-13 23,-18 36,-11 13,7 17,23 11,36l-6596 12206z\"/>\r\n    <path class=\"fil1\" d=\"M12948 8775c2,1 3,1 5,2 2,1 4,3 6,5l124 79 417 -889 -2085 -1320c-4,-1 -9,-2 -12,-4 -4,-3 -7,-6 -9,-10l-4613 -2921c-2,-1 -3,-1 -5,-2 -1,-1 -2,-2 -4,-3l-1829 -1159 -3331 5587 7757 4917c13,8 16,24 9,37l-2 1 -231 358 1410 801 414 -883 -372 -211 0 -1c-13,-7 -18,-23 -11,-35l179 -333 -7814 -4953c-13,-8 -16,-24 -8,-37l0 -1 1207 -2021c1,-4 2,-7 4,-11 2,-3 4,-5 7,-7l1091 -1827 0 -1 0 0 1 0 0 0 0 -1 0 0 1 -1 0 0 0 0 0 -1 1 0 0 0 0 -1 0 0 0 0 1 0 0 0 0 -1 0 0 1 -1 1 0 0 0 0 -1 0 0 1 0 0 0 0 0 0 0 1 -1 0 0 0 0 0 0 2 -1 0 0 0 0 0 0 1 0 0 0c0,-1 1,-1 2,-1l0 0 1 -1 0 0 0 0 0 0 1 0 0 0 0 0 0 0 1 0 0 0 1 0 0 0 0 -1 0 0 1 0 0 0 1 0 0 0 0 0 0 0 1 0 0 0 0 0 0 0 2 0 0 0 0 0 1 0 0 0 1 0 0 0 0 0 0 0 1 0 1 0 0 0 0 0 1 0 0 0 2 1 0 0 0 0 0 0 1 0 0 0 1 0 0 0 0 0 0 0 1 1 0 0 0 0 1 0 0 0 1 0 0 0 0 1 0 0 1 0 0 0 0 0 0 0 2 1 1249 791c2,1 3,2 5,2 1,1 2,2 4,4l2306 1460c2,1 4,2 6,3 2,1 4,3 5,4l1140 722c4,1 7,2 10,4 3,2 6,4 8,7l2298 1455c4,1 7,2 11,4 3,2 6,5 8,8l609 386z\"/>\r\n    <path class=\"fil1\" d=\"M11351 13587c-4,0 -7,-1 -10,-3 -4,-2 -7,-4 -9,-8l-20 -11 -414 885 14466 8222 969 -1530c1,-3 2,-5 3,-7 1,-3 3,-4 5,-6l1731 -2736c8,-12 24,-16 36,-8l1798 1136c6,0 11,1 16,4 5,3 8,8 10,13l1795 1134 2 1c12,8 16,24 8,37l-1743 2748 0 0 0 1 -860 1356 1762 1002 787 -1495 -542 -331c-3,-2 -6,-4 -8,-7l-50 -71c-1,-2 -2,-5 -3,-7l-27 -83c-1,-2 -2,-4 -2,-6l-15 -93c0,-3 0,-6 1,-10l21 -85 1 -4 35 -83c1,-2 2,-4 3,-6l1098 -1728c1,-2 2,-3 3,-4l55 -69c1,-2 4,-4 6,-6l71 -49c2,-2 4,-3 7,-4l82 -27c3,-1 6,-2 9,-2l85 -7c2,-1 4,0 6,0l92 14c2,0 5,1 7,2l78 35 3 2 275 177 618 -1173 -1359 -861c-6,0 -11,-1 -16,-4 -4,-3 -8,-7 -10,-12l-1143 -724 -1 -1 -2 -1 -1145 -725c-3,-1 -6,-2 -8,-3 -3,-2 -5,-4 -7,-6l-1150 -729c-2,0 -3,-1 -5,-2 -2,-1 -3,-2 -5,-4l-1140 -722c-3,-1 -7,-2 -10,-4 -3,-2 -5,-4 -7,-7l-1135 -719c-4,0 -9,-1 -13,-4 -4,-2 -7,-6 -9,-9l-1141 -723c-4,-1 -8,-2 -12,-4 -3,-3 -6,-6 -8,-9l-1132 -717c-5,0 -10,-1 -14,-4 -5,-3 -8,-7 -10,-11l-2294 -1453c-2,-1 -4,-1 -6,-3 -2,-1 -3,-2 -5,-4l-1155 -732 -2 0 -1 -1 -1145 -726c-3,0 -6,-2 -9,-3 -2,-2 -4,-4 -6,-6l-2301 -1457c-3,-1 -6,-2 -9,-4 -3,-2 -6,-4 -8,-7l-1134 -718c-5,-1 -9,-2 -13,-4 -4,-3 -7,-6 -9,-10l-1873 -1186 -1 -1c-2,-1 -3,-2 -4,-3l-304 -192 -416 889 259 164c3,0 6,1 8,3 4,2 7,4 10,8l4440 2811 1 1c12,8 16,24 8,37l-1144 1804c0,3 -2,6 -3,9 -2,3 -5,6 -7,8l-1325 2089c-8,13 -25,16 -37,9l0 -1 -1179 -669c-4,0 -7,-1 -11,-4 -3,-1 -5,-4 -7,-6l-3077 -1747z\"/>\r\n    <path class=\"fil0\" d=\"M29556 28841c-7,13 -23,18 -36,11 -13,-7 -18,-23 -11,-36l6504 -12348c6,-13 22,-18 35,-11 13,6 18,22 11,35l-6503 12349z\"/>\r\n    <path class=\"fil0\" d=\"M24776 16270c4,0 7,2 10,3 2,2 5,5 7,7l2450 1551 1 1c12,8 16,24 8,37l-2203 3471c-8,12 -24,16 -37,8l0 0 -1264 -719c-4,0 -7,-1 -11,-3 -2,-2 -5,-4 -7,-6l-2425 -1379c-4,0 -8,-2 -12,-4 -3,-2 -6,-5 -8,-7l-1176 -669c-5,0 -9,-2 -13,-4 -4,-2 -7,-5 -9,-8l-1179 -671c-1,0 -2,-1 -3,-1l0 -1 -1176 -668c-5,0 -10,-2 -14,-4 -3,-2 -6,-5 -8,-8l-1181 -672 0 0 -1 0 0 0 0 0 -1 -1 0 0 0 0 0 0 -1 -1 0 0 0 0 0 0 -1 0 0 0 0 -1 0 0 -1 0 0 0 0 -1 0 0 -1 0 0 0 0 -1 0 0 -1 0 0 0 0 -1 0 0 0 0 0 0 -1 -1 0 0 0 0 0 0 -1 -1 0 0 0 0 0 -1 0 0 -1 0 0 0 0 -1 0 0 0 0 0 0 -1 -1 0 0 0 0 0 -1 0 0 0 0 0 -1 0 0 -1 0 0 0 0 -1 0 0 0 0 0 0 0 -1 0 0 0 -1 0 0 -1 0 0 0 0 -1 0 0 0 0 0 -1 0 0 0 0 0 -1 0 0 0 0 0 0 0 -1 0 0 0 -1 0 0 0 0 0 0 0 -1 0 0 0 -1 0 0 0 0 0 0 0 -1 0 0 0 0 0 -1 0 0 0 0 0 -1 0 0 0 0 0 0 0 -1 0 0 0 -1 0 0 0 0 0 0 0 -1 0 0 1 -1 0 0 0 0 0 0 0 -1 0 0 0 0 0 0 0 -1 0 0 1 -1 0 0 0 0 0 0 0 -1 0 0 0 0 0 -1 1 0 0 0 0 -1 0 0 0 0 0 0 1 -1 2444 -3868c8,-12 24,-16 36,-8l1147 725c5,0 10,1 15,4 5,4 9,8 11,13l1140 722c2,1 3,1 4,2 1,1 2,2 3,3l1144 724c2,0 5,1 8,3 2,2 5,4 6,6l1151 728c2,1 3,2 5,3 1,1 3,2 4,3l1141 723z\"/>\r\n    <polygon class=\"fil2\" points=\"13128,8889 13370,9042 13786,8154 13545,8001 \"/>\r\n    <polygon class=\"fil2\" points=\"11037,13349 11289,13491 13348,9091 13106,8937 \"/>\r\n    <path class=\"fil2\" d=\"M12950 8838l-2124 3950c-1,3 -2,6 -3,8 -1,1 -2,3 -3,4l-175 326 346 197 2070 -4414 -111 -71z\"/>\r\n    <polygon class=\"fil2\" points=\"11344,13523 13634,9272 13393,9119 11335,13518 \"/>\r\n    <polygon class=\"fil2\" points=\"14038,6824 13463,7886 13522,7924 \"/>\r\n    <polygon class=\"fil2\" points=\"14142,8316 15136,6480 14744,6231 13854,8134 \"/>\r\n   </g>\r\n   <path class=\"fil3\" d=\"M32949 21917l-277 -178 -72 -33 -86 -13 -79 6 -78 26 -65 45 -52 66 -1097 1727 -34 81 -19 78 13 85 26 79 44 63 535 326 1241 -2358zm-23850 -8491l220 -339 -7734 -4902 -433 726 7947 4515zm1691 -682l2115 -3934 -581 -368 -2388 3760 854 542zm2628 -4886l996 -1840 -686 -435 -1114 1766 804 509zm1763 -1350l-994 1837 684 433 986 -1842 -676 -428zm-1502 2792l-2289 4249 696 395 1261 -2339c1,-2 2,-4 3,-6 0,-1 1,-2 2,-3l1006 -1866 -679 -430zm20864 9468l-1114 1764 188 118 974 -1851 -48 -31z\"/>\r\n  </g>\r\n  <path class=\"fil4\" d=\"M5287 3924l1 1c13,7 17,23 9,36\"/>\r\n  <polyline class=\"fil4\" points=\"5252,3934 5252,3934 5252,3933 \"/>', '<g id=\"_2096178038208\">\r\n   <polygon class=\"fil5\" points=\"[[1]]\" style=\"fill:[[2]]\"/>\r\n   <g transform=\"[[3]]\">\r\n    <text x=\"20000\" y=\"14850\"  class=\"fil6 fnt0\">[[4]]</text>\r\n   </g>\r\n  </g>', '<g id=\"_2096297423056\">\r\n   <path class=\"fil5\" d=\"[[1]]\" style=\"fill:[[2]]\"/>\r\n   <g transform=\"[[3]]\">\r\n    <text x=\"20000\" y=\"14850\"  class=\"fil6 fnt0\">[[4]]</text>\r\n   </g>\r\n  </g>', '', ' </g>\r\n</svg>', 400, 100, 100),
(2, 2, '<?xml version=\"1.0\" encoding=\"UTF-8\"?>', '<svg id=\"svg-image-2\" xmlns=\"http://www.w3.org/2000/svg\" xml:space=\"preserve\" width=\"400mm\" height=\"297mm\" version=\"1.1\" style=\"shape-rendering:geometricPrecision; text-rendering:geometricPrecision; image-rendering:optimizeQuality; fill-rule:evenodd; clip-rule:evenodd\"\r\nviewBox=\"0 0 40000 29700\"\r\n xmlns:xlink=\"http://www.w3.org/1999/xlink\">\r\n <defs>\r\n  <font id=\"FontID0\" horiz-adv-x=\"647\" font-variant=\"normal\" style=\"fill-rule:nonzero\" font-style=\"normal\" font-weight=\"400\">\r\n	<font-face \r\n		font-family=\"Bahnschrift\">\r\n		<font-face-src>\r\n			<font-face-name name=\"Bahnschrift\"/>\r\n		</font-face-src>\r\n	</font-face>\r\n   <missing-glyph><path d=\"M0 0z\"/></missing-glyph>\r\n   <glyph unicode=\"D\" horiz-adv-x=\"656\" d=\"M155.338 0l0 94.6567 160.663 0c52.3396,0 92.9964,13.667 121.667,40.8391 28.8323,27.172 43.3295,65.5004 43.3295,115.005l0 208.994c0,49.505 -14.4972,87.8333 -43.3295,115.005 -28.6704,27.172 -69.3272,40.6568 -121.667,40.6568l-160.663 0 0 94.8389 157.667 0c85.9908,0 152.321,-22.1709 199.498,-66.6545 46.9943,-44.5038 70.5015,-107.008 70.5015,-187.835l0 -201.178c0,-53.6557 -10.5084,-99.4958 -31.667,-137.318 -21.1788,-38.0044 -51.9954,-67.019 -92.3283,-87.0032 -40.3329,-20.0045 -89.1697,-30.0067 -146.51,-30.0067l-157.161 0zm-67.5049 0l0 709.996 99.6578 0 0 -709.996 -99.6578 0z\"/>\r\n   <glyph unicode=\"3\" horiz-adv-x=\"528\" d=\"M256.839 -7.32957c-40.0089,0 -75.1787,7.32957 -105.165,21.6647 -30.1687,14.4972 -54.3441,35.4937 -72.506,62.8278 -18.3442,27.334 -30.0067,60.4993 -35.1698,99.6781l0 0 101.5 0 0 0c4.49493,-30.6748 16.6636,-53.0078 36.1619,-67.181 19.4983,-14.1529 44.5038,-21.3205 75.1787,-21.3205 32.8211,0 58.4948,8.82788 76.6567,26.3217 18.1619,17.676 27.334,42.3374 27.334,74.3485l0 21.4825c0,35.514 -8.6659,62.848 -25.8357,82.1843 -17.3318,19.4983 -41.6692,29.1563 -73.3362,29.1563l-50.1529 0 0 94.677 50.1529 0c28.0022,0 49.6669,8.32169 64.8525,24.8233 14.9831,16.6636 22.6569,40.1709 22.6569,70.3395l0 21.9887c0,28.0022 -8.15971,49.6669 -24.1754,65.0145 -16.1574,15.3273 -38.9965,22.8188 -68.659,22.8188 -25.0056,0 -46.3261,-6.98536 -63.6579,-21.1586 -17.514,-14.1732 -29.5005,-36.5061 -35.9999,-67.1607l0 0 -101.014 0 0 0c10.1642,58.6568 32.6793,103.991 67.6669,135.982 35.0078,32.1732 79.3497,48.1686 133.005,48.1686 61.1675,0 108.506,-16.1574 142.177,-48.3306 33.4892,-32.3351 50.1529,-77.6691 50.1529,-136.326l0 -11.6625c0,-35.514 -9.49604,-66.3306 -28.6704,-92.5105 -19.316,-26.1597 -46.4881,-45.496 -81.6579,-57.8266 38.8346,-8.15971 68.8412,-27.172 90.1618,-56.9965 21.3408,-29.6827 32.0112,-67.5049 32.0112,-113.507l0 -11.6625c0,-62.1798 -17.838,-110.328 -53.3317,-144.506 -35.514,-34.1574 -85.5049,-51.3272 -150.337,-51.3272z\"/>\r\n   <glyph unicode=\"A\" horiz-adv-x=\"647\" d=\"M19.4983 0l261.84 709.996 84.8367 0 261.819 -709.996 -107.494 0 -196.825 572.84 -196.683 -572.84 -107.494 0zm120.675 155.824l0 94.677 373.484 0 0 -94.677 -373.484 0z\"/>\r\n   <glyph unicode=\"0\" horiz-adv-x=\"551\" d=\"M275.831 -7.32957c-67.6669,0 -118.326,18.1619 -151.997,54.5061 -33.6715,36.1619 -50.4971,85.3226 -50.4971,147.158l0 322.825c0,62.848 16.9876,112.009 51.1652,147.847 34.1574,35.8177 84.6747,53.8177 151.329,53.8177 67.1607,0 117.678,-17.8177 151.673,-53.4937 33.9954,-35.6557 51.0033,-85.1607 51.0033,-148.171l0 -322.825c0,-62.8278 -17.0078,-112.171 -51.0033,-148.009 -33.9954,-35.8177 -84.5127,-53.6557 -151.673,-53.6557zm0 94.6567c36.5061,0 62.8278,9.67827 78.8435,28.6704 16.1574,19.0123 24.1552,45.172 24.1552,78.3373l0 322.825c0,33.5095 -7.99773,59.6692 -24.1552,78.4993 -16.0157,19.0123 -42.3374,28.3464 -78.8435,28.3464 -36.3239,0 -62.6658,-9.33406 -78.8232,-28.3464 -16.1777,-18.8301 -24.1754,-44.9898 -24.1754,-78.4993l0 -322.825c0,-33.1653 7.99773,-59.325 24.1754,-78.3373 16.1574,-18.9921 42.4993,-28.6704 78.8232,-28.6704z\"/>\r\n   <glyph unicode=\"-\" horiz-adv-x=\"479\" d=\"M78.1753 301.828l328.15 0 0 -94.8187 -328.15 0 0 94.8187z\"/>\r\n   <glyph unicode=\"8\" horiz-adv-x=\"562\" d=\"M281.338 -7.32957c-43.3295,0 -81.1719,8.15971 -113.345,24.4994 -32.3351,16.1574 -57.3205,39.1585 -75.1584,68.821 -18,29.5005 -26.8278,64.1843 -26.8278,104.011l0 11.6625c0,35.4937 8.82788,68.8412 26.4836,100.164 17.838,31.181 41.5072,54.83 71.0077,70.6635 -24.6614,13.667 -44.6658,33.5095 -59.9931,59.3452 -15.3475,25.9977 -23.0011,53.1697 -23.0011,81.8198l0 17.1698c0,56.1664 18.3239,101.338 54.992,135.516 36.6681,33.9954 85.1809,50.983 145.842,50.983 60.4993,0 108.992,-16.9876 145.66,-50.983 36.6681,-34.1777 54.992,-79.3497 54.992,-135.516l0 -17.1698c0,-29.3183 -7.81551,-56.8143 -23.325,-82.488 -15.3273,-25.6737 -35.8379,-45.334 -61.1675,-58.677 30.0067,-15.8335 53.9999,-39.4825 71.9998,-70.6635 18,-31.3228 27.0101,-64.6703 27.0101,-100.164l0 -11.6625c0,-39.8267 -8.84812,-74.5105 -26.8481,-104.011 -17.8177,-29.6625 -42.8233,-52.6635 -75.1584,-68.821 -32.1732,-16.3397 -69.9953,-24.4994 -113.163,-24.4994zm0 95.6691c34.8256,0 62.8278,10.0022 84.1685,29.9864 21.3205,20.1664 31.9909,46.3464 31.9909,79.0054l0 6.84363c0,32.8211 -10.6704,59.325 -31.9909,79.3294 -21.3408,20.0045 -49.343,29.9864 -84.1685,29.9864 -34.8458,0 -63.01,-9.98198 -84.3305,-29.9864 -21.3408,-20.0045 -32.0112,-46.6703 -32.0112,-79.8356l0 -7.32957c0,-32.6793 10.6704,-58.677 32.0112,-78.3373 21.3205,-19.8425 49.4847,-29.6625 84.3305,-29.6625zm0 320.82c30.1687,0 54.668,9.67827 73.1539,28.8323 18.5061,19.1743 27.8402,44.6658 27.8402,76.1708l0 6.84363c0,30.3306 -9.33406,54.668 -27.8402,72.992 -18.4859,18.3442 -42.9853,27.496 -73.1539,27.496 -30.3306,0 -54.83,-9.15184 -73.3362,-27.496 -18.5061,-18.3239 -27.8402,-42.8233 -27.8402,-73.4981l0 -7.32957c0,-31.181 9.33406,-56.3283 27.8402,-75.3407 18.5061,-19.1541 43.0055,-28.6704 73.3362,-28.6704z\"/>\r\n   <glyph unicode=\"5\" horiz-adv-x=\"544\" d=\"M271.498 -7.32957c-53.9999,0 -98.3215,15.4893 -133.005,46.6703 -34.6636,30.9988 -56.4903,74.9965 -65.1562,131.487l0 0.506186 99.4958 0 0 -0.506186c3.66478,-25.9977 14.0112,-46.1641 31.343,-60.6613 17.1496,-14.4972 39.6647,-21.8267 67.3227,-21.8267 31.8289,0 56.5106,10.6704 74.0043,31.9909 17.3318,21.3408 25.9977,51.3272 25.9977,90.1618l0 59.5072c0,38.8346 -8.6659,68.659 -25.9977,89.8378 -17.4938,21.1586 -42.1754,31.8289 -74.0043,31.8289 -17.3318,0 -33.8334,-5.00111 -49.8289,-14.6591 -15.9955,-9.84025 -30.0067,-23.5073 -41.9932,-41.001l-90.8502 0 0 373.99 361.842 0 0 -94.8389 -262.164 0 0 -159.651c12.3307,10.1642 26.1597,18 41.487,23.487 15.3475,5.5073 30.8368,8.34194 46.8525,8.34194 61.8154,0 109.66,-18.9921 143.493,-56.8345 33.9954,-38.0044 50.821,-91.4981 50.821,-160.501l0 -59.5072c0,-69.3272 -17.3318,-122.983 -52.1574,-160.987 -34.8256,-37.8424 -84.0066,-56.8345 -147.502,-56.8345z\"/>\r\n   <glyph unicode=\"C\" horiz-adv-x=\"618\" d=\"M320.334 -7.32957c-49.505,0 -92.8344,10.9944 -129.826,33.0033 -37.1743,21.9887 -66.0066,52.8255 -86.5172,92.4902 -20.4904,39.6647 -30.6546,86.173 -30.6546,139.161l0 194.841c0,53.3317 10.1642,100.002 30.6546,139.667 20.5106,39.6647 49.343,70.5015 86.5172,92.5105 36.992,21.9887 80.3215,32.9831 129.826,32.9831 41.001,0 78.4993,-8.82788 112.495,-26.4836 33.9954,-17.514 62.5038,-42.0134 85.5049,-73.6804 23.0011,-31.505 38.4904,-68.497 46.6703,-110.834l0 0 -102.168 0 0 0c-6.17546,22.8391 -16.5017,42.6816 -30.9988,59.8311 -14.4972,17.1698 -31.505,30.3509 -51.0033,39.8469 -19.4983,9.33406 -39.6647,14.1529 -60.4993,14.1529 -43.6737,0 -78.6612,-15.4893 -105.165,-46.1641 -26.6659,-30.8368 -39.8267,-71.3317 -39.8267,-121.829l0 -194.841c0,-50.4971 13.1608,-90.9919 39.8267,-121.667 26.5039,-30.4926 61.4914,-45.8199 105.165,-45.8199 30.8368,0 59.9931,9.82 87.3271,29.3385 27.334,19.4983 45.8402,47.6624 55.1742,84.4925l0 0 102.168 0 0 0c-8.17996,-42.3374 -23.8312,-79.3294 -46.9943,-110.834 -23.0011,-31.667 -51.5094,-56.1664 -85.3429,-73.8424 -33.8334,-17.4938 -71.3317,-26.3217 -112.333,-26.3217z\"/>\r\n   <glyph unicode=\"2\" horiz-adv-x=\"516\" d=\"M62.5038 0l0 86.497 247.505 330.013c14.3149,18.8301 25.4915,38.4904 33.4892,58.6568 7.99773,20.1664 12.0067,39.1585 12.0067,56.9965l0 1.01237c0,28.3261 -8.34194,50.3148 -25.0056,65.9864 -16.5017,15.6715 -40.1709,23.3453 -70.6635,23.3453 -28.6704,0 -51.9954,-8.50392 -70.1776,-25.5118 -18,-17.1698 -28.9943,-41.325 -32.9831,-72.4858l0 -0.506186 -102.999 0 0 0.506186c8.82788,61.1472 31.1608,108.486 66.9987,142.319 35.6557,33.6715 81.8198,50.4971 138.168,50.4971 63.8199,0 112.981,-16.1574 147.826,-48.3306 34.8256,-32.3351 52.3396,-77.8311 52.3396,-136.832l0 -0.506186c0,-24.9853 -5.18334,-51.3272 -15.6715,-78.8232 -10.3464,-27.496 -24.8436,-53.6759 -43.5117,-78.8435l-205.491 -279.333 268.501 0 0 -94.6567 -400.332 0z\"/>\r\n   <glyph unicode=\"7\" horiz-adv-x=\"503\" d=\"M439.997 709.996l0 -88.8254 -183.664 -621.171 -105.995 0 183.664 615.157 -180.668 0 0 -101.986 -99.6578 0 0 196.825 386.321 0z\"/>\r\n   <glyph unicode=\"4\" horiz-adv-x=\"568\" d=\"M63.4959 108.83l0 86.497 238.332 514.163 102.513 0 -232.501 -508.332 348.154 0 0 -92.3283 -456.498 0zm306.161 -109.336l0 418.008 97.1674 0 0 -418.008 -97.1674 0z\"/>\r\n   <glyph unicode=\"1\" horiz-adv-x=\"332\" d=\"M244.67 709.996l0 -709.996 -99.6781 0 0 601.49 -100.994 -61.9976 0 102.999 100.994 67.5049 99.6781 0z\"/>\r\n   <glyph unicode=\"B\" horiz-adv-x=\"645\" d=\"M146.49 0l0 92.3283 192.351 0c52.8255,0 90.1618,10.0022 112.15,30.1687 21.847,20.1664 32.8413,46.8323 32.8413,79.6736l0 1.49831c0,35.1698 -9.33406,63.172 -28.0022,84.1685 -18.6681,20.9966 -49.505,31.505 -92.4902,31.505l-216.85 0 0 89.8176 216.85 0c36.6681,0 64.488,8.6659 83.1562,26.1799 18.8301,17.3318 28.1642,42.8233 28.1642,76.3328l0 0c0,35.4937 -10.4882,61.9976 -31.3228,79.6534 -20.8346,17.514 -51.9954,26.3419 -93.6646,26.3419l-203.183 0 0 92.3283 221.183 0c69.9953,0 121.991,-17.4938 156.33,-52.3396 34.1574,-34.8256 51.1652,-81.4959 51.1652,-140.153l0 0c0,-35.3318 -10.3262,-67.8289 -31.1608,-97.3294 -20.8346,-29.5005 -53.6759,-48.3508 -98.1798,-56.3486 43.8357,-6.49942 78.0133,-25.9977 102.33,-58.1506 24.1754,-32.1732 36.3441,-69.1854 36.3441,-110.834l0 -1.49831c0,-57.8469 -18.6681,-104.679 -56.1664,-140.173 -37.4982,-35.4937 -88.1775,-53.1697 -152.342,-53.1697l-229.505 0zm-58.6568 0l0 709.996 99.1719 0 0 -709.996 -99.1719 0z\"/>\r\n   <glyph unicode=\"9\" horiz-adv-x=\"513\" d=\"M146.004 0l178.663 352.508 -0.506186 -17.514c-8.32169,-15.3273 -20.9966,-26.3217 -37.4982,-33.1653 -16.6636,-6.82338 -35.8379,-10.3262 -57.6647,-10.3262 -51.1652,0 -92.3283,18.9921 -123.489,56.8345 -31.343,38.0044 -46.8525,88.3395 -46.8525,151.167l0 0.506186c0,68.983 17.352,122.497 52.0156,160.319 34.6636,38.0044 83.3181,56.9965 146.166,56.9965 62.8278,0 111.503,-19.1541 146.004,-57.4824 34.4814,-38.1866 51.8334,-92.3485 51.8334,-162.344l0 -0.506186c0,-28.8323 -4.33295,-60.1551 -12.8369,-93.9885 -8.34194,-33.6715 -20.8346,-66.5128 -37.0123,-98.8479l-151.329 -304.157 -107.494 0zm110.834 382.332c31.3228,0 55.4982,10.5084 72.4858,31.667 17.1698,21.1586 25.6737,51.0033 25.6737,89.4936l0 0.344206c0,37.4982 -8.50392,66.4925 -25.6737,87.0032 -16.9876,20.4904 -41.163,30.6546 -72.4858,30.6546 -31.505,0 -55.8424,-10.1642 -73.0122,-30.6546 -17.1496,-20.5106 -25.6535,-49.6669 -25.6535,-87.3474l0 -0.485938c0,-38.5106 8.50392,-68.1731 25.6535,-89.1697 17.1698,-20.9966 41.5072,-31.505 73.0122,-31.505z\"/>\r\n   <glyph unicode=\"6\" horiz-adv-x=\"513\" d=\"M256.332 -7.32957c-62.8278,0 -111.503,18.6681 -146.004,56.1664 -34.5016,37.4982 -51.6714,90.1618 -51.6714,158.173l0 0.485938c0,30.6748 4.17097,63.172 12.6749,97.5116 8.50392,34.3194 20.6726,67.3227 36.5061,99.3339l154.326 305.655 107.494 0 -181.154 -354.006 0.506186 17.514c16.8256,32.3351 46.9943,48.3306 90.3238,48.3306 55.6602,0 98.8277,-18.5061 129.341,-55.3362 30.6546,-36.992 46.0021,-89.1697 46.0021,-156.492l0 -0.506186c0,-68.659 -17.352,-121.991 -51.8334,-159.995 -34.5016,-37.8424 -83.3384,-56.8345 -146.51,-56.8345zm0 95.6691c31.9909,0 56.3283,9.82 73.3362,29.3183 16.8256,19.5185 25.3295,47.3385 25.3295,83.5004l0 0.506186c0,39.6647 -8.98986,70.3395 -26.8278,92.0043 -18,21.6647 -43.5117,32.4971 -76.677,32.4971 -29.6625,0 -52.6635,-10.8324 -68.821,-32.6591 -16.3397,-21.847 -24.4994,-52.6635 -24.4994,-92.3485l0 -0.485938c0,-36.1821 8.50392,-63.8401 25.6535,-83.1764 17.1698,-19.4983 41.3452,-29.1563 72.506,-29.1563z\"/>\r\n  </font>\r\n  <style type=\"text/css\">\r\n   <![CDATA[\r\n    @font-face { font-family:\"Bahnschrift\";font-variant:normal;font-style:normal;font-weight:normal;src:url(\"#FontID0\") format(svg)}\r\n    .fnt0_2 {fill:#332C2B}\r\n    .fil4_2 {fill:#FEFEFE;fill-rule:nonzero}\r\n    .fil1_2 {fill:#EEEEEF;fill-rule:nonzero}\r\n    .fil2_2 {fill:#DCDDDD;fill-rule:nonzero}\r\n    .fil0_2 {fill:#332C2B;fill-rule:nonzero}\r\n    .fil5_2 {fill:#FDE9D2;fill-rule:nonzero}\r\n    .fil3_2 {fill:#A2D3AB;fill-rule:nonzero}\r\n    .fnt0_2 {font-weight:normal;font-size:493.89px;font-family:\'Bahnschrift\'}\r\n   ]]>\r\n  </style>\r\n </defs>\r\n <g id=\"Layer_x0020_1\">\r\n  <metadata id=\"CorelCorpID_0Corel-Layer\"/>\r\n  <g id=\"_2096296796640\">\r\n   <path class=\"fil0_2\" d=\"M28077 27788c-7,13 -23,18 -36,11 -13,-6 -18,-22 -12,-35 1684,-3329 3326,-6544 5087,-9823l2830 -5505c7,-13 23,-18 36,-11 13,7 18,23 11,35 -1615,3142 -3213,6219 -4882,9328l-3034 6000zm-17166 -17706c-2,-1 -4,-1 -5,-2 -4,-2 -6,-4 -9,-6l-1377 -809c-6,1 -12,0 -17,-3 -5,-3 -9,-8 -11,-13l-1331 -781c-3,-1 -7,-2 -10,-4 -3,-2 -6,-4 -8,-7l-2536 -1489c-2,0 -3,-1 -4,-1 -1,-1 -2,-2 -3,-3l-2397 -1406c-1,-1 -1,-1 -2,-2 -1,0 -2,-1 -2,-1l-2400 -1409c-13,-7 -17,-23 -10,-36l1896 -3335c8,-13 24,-18 36,-10l19057 10822c2,0 4,1 7,3 1,1 2,2 3,3l11228 6376c13,7 17,23 11,35l-2053 3823c-7,13 -24,17 -36,10l-2082 -1222c-4,-1 -7,-2 -10,-4 -2,-2 -5,-4 -6,-6l-1856 -1089c-5,0 -10,-1 -15,-4 -4,-3 -7,-6 -9,-10l-7415 -4353c-6,1 -11,-1 -16,-4 -4,-2 -7,-6 -9,-10l-1931 -1133c-5,0 -10,-1 -15,-4 -4,-3 -7,-7 -9,-11l-2826 -1658c-5,0 -10,-1 -14,-4 -4,-3 -7,-6 -9,-10l-1187 -696c-3,-1 -6,-2 -9,-4 -4,-2 -6,-4 -8,-7l-1175 -689c-5,0 -9,-1 -14,-4 -4,-2 -7,-6 -9,-10l-1403 -823zm25395 4756c7,-13 23,-18 36,-11 13,7 18,23 11,36l-4007 7566 -3025 5981c-6,13 -22,18 -35,12 -13,-7 -18,-23 -12,-36l3025 -5981 0 0 0 0 0 -1 4007 -7566z\"/>\r\n   <g>\r\n    <polygon class=\"fil1_2\" points=\"30941,21777 32968,18002 31176,16984 28898,20578 \"/>\r\n    <path class=\"fil0_2\" d=\"M32078 18867l77 68c2,2 4,4 6,7l48 87c1,2 2,4 3,6l28 105c1,3 2,6 2,9l0 97c0,4 -1,7 -2,10l-39 98 -1 2 -544 1019c-1,2 -2,4 -3,6l-68 88c-2,2 -4,4 -6,5l-96 67c-3,2 -6,4 -10,5l-117 29c-2,1 -5,1 -8,1l-106 -10c-3,0 -7,0 -10,-2l-107 -49 -3 -1 -263 -166 0 0c-1,-1 -2,-2 -4,-3l-76 -67c-3,-2 -4,-4 -6,-7l-58 -97c-2,-2 -3,-5 -4,-9l-19 -106c-1,-2 -1,-5 0,-8l9 -105c0,-3 1,-6 2,-9l39 -97c1,-2 2,-4 3,-6l620 -969c1,-2 2,-3 3,-4l68 -88c2,-3 6,-5 9,-7l95 -48c2,-1 4,-2 7,-3l96 -28c3,-2 7,-2 11,-2l116 10c4,0 7,1 10,3l99 49 194 117 0 0 0 0 1 0 0 0 1 1 0 0 0 0 0 0 1 1 0 0 0 0 0 0 1 0 0 1 0 0 1 0 0 0z\"/>\r\n    <path class=\"fil2_2\" d=\"M12563 6610c-135,304 -273,608 -415,912l397 232c6,-1 13,0 19,3 6,4 10,10 12,16l1177 691c2,1 5,2 7,3 3,2 5,4 6,5l1177 692c5,0 9,1 13,4 5,2 8,6 10,9l1273 749c1,0 1,0 2,1 12,7 16,24 8,36l-1471 2327 856 503 2153 -3412 -977 -555 -113 184c-8,12 -24,16 -36,9l-4098 -2409zm-769 703l423 -907 -9499 -5582 -492 866 1199 705c6,0 11,1 16,4 6,3 9,7 11,12l2373 1395c6,-1 13,0 19,3 5,3 9,9 11,14l1180 694c2,0 3,1 5,2 1,1 3,2 4,3l1182 694c6,-1 13,-1 20,3 6,4 10,10 12,16l1326 780c2,0 3,1 4,2 1,0 2,1 3,2l2203 1294zm12981 9676c0,0 0,1 -1,1l0 0 0 0 -2 2 0 0 0 0 -1 0 0 0 0 1 -1 0 0 0 0 0 -1 0 0 1 0 0 -1 0 0 0 0 0 -1 0 0 1 0 0 0 0 -1 0 0 0 -1 0 0 0 0 1 0 0 -1 0 0 0 -1 0 0 0 -1 0 0 0 0 0 -1 1 0 0 0 0 0 0 -1 0 0 0 -1 0 0 0 0 0 -1 0 0 0 -1 0 0 0 0 0 -1 0 0 0 0 0 -1 0 0 0 0 0 0 0 -1 0 0 0 -1 0 0 0 0 0 -1 0 0 0 0 0 -1 0 0 0 0 0 -1 -1 0 0 0 0 -1 0 0 0 0 0 -1 0 0 0 -1 0 0 -1 0 0 -1 0 0 0 -1 0 0 0 0 -1 -1 0 -1 0 0 -1 0 0 -1863 -1095c-5,0 -10,-1 -15,-4 -4,-3 -8,-6 -10,-10l-1865 -1097c0,-1 -1,-1 -2,-2 -12,-7 -16,-24 -8,-36l1658 -2613 -859 -488 -2197 3462 5514 3237 781 -1234c1,-3 2,-7 4,-10 3,-4 5,-7 9,-9l1449 -2289 -860 -488 -891 1397c0,3 -1,5 -2,6 -2,2 -3,4 -5,6l-802 1258 -1 1 0 0 0 0 0 0 -1 1 0 1 0 0 0 0 -1 1 0 0 0 0 0 0 -1 1z\"/>\r\n    <path class=\"fil3_2\" d=\"M12632 6454c-16,36 -31,72 -47,108l4080 2398 99 -160 -4132 -2346zm18978 13896l543 -1017 36 -91 0 -90 -27 -100 -45 -80 -73 -64 -192 -115 -91 -46 -107 -9 -90 28 -90 44 -64 83 -620 968 -37 92 -9 100 18 98 54 90 73 64 259 163 102 46 98 9 108 -27 89 -63 65 -83zm-20998 -10505l1159 -2484 -834 -490 -1382 2354 1057 620zm1514 -2275c-386,827 -789,1653 -1175,2474l179 105 1381 -2353 -385 -226zm113 -1212l47 -101 -6493 -3688 6446 3789z\"/>\r\n    <path class=\"fil4_2\" d=\"M11817 7388l-1159 2484 248 145 1164 -2480 -253 -149zm515 -1105l-47 102 254 150 47 -108 -254 -144zm-70 150l-423 907 253 149 425 -906 -255 -150z\"/>\r\n   </g>\r\n  </g>', '<g id=\"_2096296796704\">\r\n   <polygon class=\"fil5_2\" points=\"[[1]]\" style=\"fill:[[2]]\"/>\r\n   <g transform=\"[[3]]\">\r\n    <text x=\"20000\" y=\"14850\"  class=\"fil6_2 fnt0_2\">[[4]]</text>\r\n   </g>\r\n  </g>', '<g id=\"_2096296850016\">\r\n   <path class=\"fil5_2\" d=\"[[1]]\" style=\"fill:[[2]]\"/>\r\n   <g transform=\"[[3]]\">\r\n    <text x=\"20000\" y=\"14850\"  class=\"fil6_2 fnt0_2\">[[4]]</text>\r\n   </g>\r\n  </g>', '', ' </g>\r\n</svg>', 400, 100, 100);
INSERT INTO `master_svg` (`id`, `id_lokasi`, `header_xml`, `header_svg`, `polygon_svg`, `path_svg`, `body_svg`, `footer_svg`, `lebar`, `tinggi`, `ukuran_dashboard`) VALUES
(3, 3, '<?xml version=\"1.0\" encoding=\"UTF-8\"?>', '<svg id=\"svg-image-3\" xmlns=\"http://www.w3.org/2000/svg\" xml:space=\"preserve\" width=\"400mm\" height=\"325mm\" version=\"1.1\" style=\"shape-rendering:geometricPrecision; text-rendering:geometricPrecision; image-rendering:optimizeQuality; fill-rule:evenodd; clip-rule:evenodd\"\r\nviewBox=\"0 0 40000 32500\"\r\n xmlns:xlink=\"http://www.w3.org/1999/xlink\">\r\n <defs>\r\n  <font id=\"FontID0\" horiz-adv-x=\"647\" font-variant=\"normal\" style=\"fill-rule:nonzero\" font-style=\"normal\" font-weight=\"400\">\r\n	<font-face \r\n		font-family=\"Bahnschrift\">\r\n		<font-face-src>\r\n			<font-face-name name=\"Bahnschrift\"/>\r\n		</font-face-src>\r\n	</font-face>\r\n   <missing-glyph><path d=\"M0 0z\"/></missing-glyph>\r\n   <glyph unicode=\"D\" horiz-adv-x=\"656\" d=\"M155.34 0l0 94.6779 160.655 0c52.3469,0 93.0007,13.6537 121.678,40.8192 28.8191,27.1656 43.3232,65.5045 43.3232,114.993l0 209.01c0,49.4886 -14.5041,87.8275 -43.3232,114.993 -28.6774,27.1656 -69.3313,40.6775 -121.678,40.6775l-160.655 0 0 94.8196 157.655 0c86.0086,0 152.34,-22.1577 199.513,-66.6619 46.9846,-44.5043 70.4887,-106.985 70.4887,-187.82l0 -201.167c0,-53.6697 -10.4883,-99.4968 -31.6538,-137.34 -21.1655,-38.0082 -52.0162,-66.9927 -92.3393,-87.0007 -40.3468,-20.008 -89.1739,-30.0002 -146.505,-30.0002l-157.159 0zm-67.5123 0l0 709.99 99.6622 0 0 -709.99 -99.6622 0z\"/>\r\n   <glyph unicode=\"3\" horiz-adv-x=\"528\" d=\"M256.845 -7.32289c-40.0161,0 -75.1896,7.32289 -105.166,21.6616 -30.1892,14.5041 -54.3548,35.5042 -72.5203,62.8351 -18.3309,27.3309 -30.0002,60.4965 -35.1499,99.6622l0 0 101.481 0 0 0c4.51185,-30.6617 16.6773,-53.0083 36.1656,-67.158 19.512,-14.1733 44.5043,-21.3545 75.1896,-21.3545 32.8113,0 58.4886,8.83472 76.6541,26.3388 18.1655,17.6694 27.3309,42.331 27.3309,74.3392l0 21.4962c0,35.5042 -8.66936,62.8351 -25.8191,82.1581 -17.3387,19.512 -41.6696,29.1735 -73.347,29.1735l-50.1736 0 0 94.6779 50.1736 0c27.9923,0 49.6776,8.31503 64.843,24.827 15.0001,16.6773 22.6537,40.1578 22.6537,70.3234l0 22.0159c0,27.9923 -8.14967,49.6539 -24.1655,64.9848 -16.1576,15.3308 -39.0003,22.8427 -68.6698,22.8427 -24.9923,0 -46.3232,-6.99218 -63.6619,-21.1655 -17.5041,-14.1733 -29.5042,-36.4964 -36.0003,-67.158l0 0 -100.985 0 0 0c10.1576,58.654 32.6459,103.985 67.6541,135.993 35.0081,32.1735 79.3235,48.1657 132.993,48.1657 61.1816,0 108.497,-16.1812 142.182,-48.3311 33.4963,-32.3388 50.15,-77.6699 50.15,-136.348l0 -11.6694c0,-35.4806 -9.49614,-66.3312 -28.6538,-92.481 -19.3466,-26.1734 -46.5122,-45.4964 -81.6621,-57.8508 38.835,-8.14967 68.8352,-27.1656 90.1661,-57.0004 21.3309,-29.6459 31.9845,-67.4887 31.9845,-113.481l0 -11.6694c0,-62.1737 -17.8348,-110.339 -53.3154,-144.497 -35.5042,-34.1814 -85.5125,-51.3311 -150.332,-51.3311z\"/>\r\n   <glyph unicode=\"A\" horiz-adv-x=\"647\" d=\"M19.4883 0l261.852 709.99 84.8274 0 261.829 -709.99 -107.505 0 -196.82 572.839 -196.679 -572.839 -107.505 0zm120.686 155.836l0 94.6543 373.491 0 0 -94.6543 -373.491 0z\"/>\r\n   <glyph unicode=\"0\" horiz-adv-x=\"551\" d=\"M275.837 -7.32289c-67.6777,0 -118.347,18.1655 -152.009,54.4965 -33.6617,36.1656 -50.5043,85.3235 -50.5043,147.167l0 322.821c0,62.8351 17.008,112.017 51.1658,147.828 34.1814,35.8349 84.6857,53.8351 151.348,53.8351 67.158,0 117.662,-17.8348 151.655,-53.4807 34.016,-35.6696 51.0004,-85.1818 51.0004,-148.182l0 -322.821c0,-62.8351 -16.9844,-112.182 -51.0004,-148.017 -33.9924,-35.8349 -84.4967,-53.6461 -151.655,-53.6461zm0 94.6543c36.4964,0 62.8351,9.66149 78.8274,28.6774 16.1576,18.9923 24.1655,45.1657 24.1655,78.3313l0 322.821c0,33.4963 -8.00794,59.6698 -24.1655,78.4967 -15.9923,19.0159 -42.331,28.3467 -78.8274,28.3467 -36.331,0 -62.6698,-9.33078 -78.8274,-28.3467 -16.1812,-18.8269 -24.1655,-45.0004 -24.1655,-78.4967l0 -322.821c0,-33.1656 7.98431,-59.339 24.1655,-78.3313 16.1576,-19.0159 42.4964,-28.6774 78.8274,-28.6774z\"/>\r\n   <glyph unicode=\"O\" horiz-adv-x=\"645\" d=\"M322.833 -7.3347c-50.1736,0 -94.0047,10.8426 -131.493,32.4924 -37.3468,21.6852 -66.3312,52.1933 -87.0243,91.5243 -20.6576,39.4728 -30.9687,85.3235 -30.9687,137.659l0 201.155c0,52.8311 10.3111,98.8236 30.9687,138.013 20.6931,39.1539 49.6776,69.662 87.0243,91.3117 37.4885,21.6852 81.3195,32.5278 131.493,32.5278 49.9965,0 93.8275,-10.8426 131.316,-32.5278 37.3468,-21.6498 66.5084,-52.1579 87.2015,-91.3117 20.6576,-39.1893 30.9687,-85.1818 30.9687,-138.013l0 -201.155c0,-52.3351 -10.3111,-98.1858 -30.9687,-137.659 -20.6931,-39.331 -49.8547,-69.8391 -87.2015,-91.5243 -37.4885,-21.6498 -81.3195,-32.4924 -131.316,-32.4924zm0 98.1504c44.5043,0 80.3274,14.6694 107.15,44.0082 26.8585,29.1616 40.1814,67.9966 40.1814,116.186l0 207.994c0,48.5083 -13.3229,87.3432 -40.1814,116.505 -26.823,28.9845 -62.6462,43.6539 -107.15,43.6539 -44.3271,0 -80.0085,-14.6694 -107.009,-43.6539 -27.0002,-29.1616 -40.5003,-67.9966 -40.5003,-116.505l0 -207.994c0,-48.1894 13.5001,-87.0243 40.5003,-116.186 27.0002,-29.3388 62.6816,-44.0082 107.009,-44.0082z\"/>\r\n   <glyph unicode=\"-\" horiz-adv-x=\"479\" d=\"M78.166 301.845l328.16 0 0 -94.8433 -328.16 0 0 94.8433z\"/>\r\n   <glyph unicode=\"I\" horiz-adv-x=\"275\" d=\"M187.513 710.013l0 -710.013 -99.674 0 0 710.013 99.674 0z\"/>\r\n   <glyph unicode=\"8\" horiz-adv-x=\"562\" d=\"M281.341 -7.32289c-43.3468,0 -81.166,8.14967 -113.339,24.4963 -32.3388,16.1576 -57.3312,39.1657 -75.1659,68.8352 -18.0001,29.4805 -26.8349,64.158 -26.8349,103.985l0 11.6694c0,35.5042 8.83472,68.8352 26.5041,100.182 17.8348,31.1577 41.5043,54.8272 70.9848,70.6541 -24.6616,13.6773 -44.646,33.4963 -60.0005,59.339 -15.3308,25.9845 -22.9844,53.1736 -22.9844,81.8274l0 17.1734c0,56.15 18.3309,101.339 54.9926,135.497 36.6617,33.9924 85.1582,51.0004 145.844,51.0004 60.4965,0 108.993,-17.008 145.655,-51.0004 36.6617,-34.1577 55.0162,-79.3471 55.0162,-135.497l0 -17.1734c0,-29.3388 -7.84258,-56.8351 -23.3388,-82.4888 -15.3308,-25.6774 -35.8349,-45.3547 -61.1816,-58.6776 30.0002,-15.8269 54.0004,-39.4964 72.0006,-70.6541 18.0001,-31.3467 27.0002,-64.6777 27.0002,-100.182l0 -11.6694c0,-39.8271 -8.83472,-74.5045 -26.8349,-103.985 -17.8348,-29.6695 -42.8271,-52.6776 -75.1659,-68.8352 -32.1499,-16.3466 -69.9927,-24.4963 -113.15,-24.4963zm0 95.6464c34.8192,0 62.8351,10.0158 84.166,30.0002 21.3309,20.1734 31.9845,46.3468 31.9845,79.0164l0 6.82683c0,32.8349 -10.6536,59.339 -31.9845,79.3235 -21.3309,20.008 -49.3468,30.0002 -84.166,30.0002 -34.8428,0 -63.0005,-9.9922 -84.3314,-30.0002 -21.3545,-19.9844 -32.0081,-46.6539 -32.0081,-79.8195l0 -7.34651c0,-32.6459 10.6536,-58.654 32.0081,-78.3313 21.3309,-19.8191 49.4886,-29.6695 84.3314,-29.6695zm0 320.837c30.1656,0 54.6618,9.66149 73.1581,28.8427 18.4962,19.1576 27.827,44.6696 27.827,76.1581l0 6.82683c0,30.3546 -9.33078,54.6855 -27.827,73.0163 -18.4962,18.3309 -42.9925,27.4963 -73.1581,27.4963 -30.3309,0 -54.8508,-9.16543 -73.347,-27.4963 -18.4962,-18.3309 -27.827,-42.8271 -27.827,-73.5124l0 -7.32289c0,-31.1577 9.33078,-56.339 27.827,-75.3313 18.4962,-19.1813 43.0161,-28.6774 73.347,-28.6774z\"/>\r\n   <glyph unicode=\"F\" horiz-adv-x=\"558\" d=\"M87.8275 0l0 709.99 99.6622 0 0 -709.99 -99.6622 0zm48.8508 298.845l0 94.6543 344.318 0 0 -94.6543 -344.318 0zm0 316.325l0 94.8196 397.987 0 0 -94.8196 -397.987 0z\"/>\r\n   <glyph unicode=\"5\" horiz-adv-x=\"544\" d=\"M271.49 -7.32289c-54.0004,0 -98.3157,15.4962 -132.993,46.6539 -34.6538,30.9924 -56.5044,75.0006 -65.1737,131.505l0 0.496067 99.5205 0 0 -0.496067c3.66145,-26.0081 13.9844,-46.1578 31.3231,-60.6619 17.1734,-14.5041 39.6617,-21.8506 67.3234,-21.8506 31.8428,0 56.5044,10.6772 74.0085,32.0081 17.3387,21.3309 26.0081,51.3311 26.0081,90.1661l0 59.5044c0,38.835 -8.66936,68.6698 -26.0081,89.8354 -17.5041,21.1655 -42.1657,31.8191 -74.0085,31.8191 -17.3151,0 -33.827,-4.98429 -49.8193,-14.6458 -16.0159,-9.85047 -30.0002,-23.5041 -42.0003,-41.0082l-90.8275 0 0 373.987 361.822 0 0 -94.8196 -262.16 0 0 -159.663c12.3308,10.1576 26.1498,18.0001 41.5043,23.5041 15.3308,5.48036 30.827,8.31503 46.8193,8.31503 61.843,0 109.678,-18.9923 143.505,-56.8351 33.9924,-37.9846 50.835,-91.4889 50.835,-160.489l0 -59.5044c0,-69.3313 -17.3387,-123.001 -52.1579,-161.009 -34.8428,-37.8192 -84.0007,-56.8115 -147.521,-56.8115z\"/>\r\n   <glyph unicode=\"C\" horiz-adv-x=\"618\" d=\"M320.341 -7.32289c-49.5122,0 -92.8354,10.9843 -129.851,33.0003 -37.1578,21.9923 -66.0005,52.8193 -86.481,92.481 -20.5041,39.6854 -30.6853,86.1739 -30.6853,139.182l0 194.836c0,53.3154 10.1812,99.9929 30.6853,139.655 20.4805,39.6617 49.3232,70.5124 86.481,92.5047 37.016,21.9923 80.3392,33.0003 129.851,33.0003 40.9846,0 78.4967,-8.83472 112.489,-26.5041 33.9924,-17.5041 62.5044,-42.0003 85.5125,-73.6541 22.9844,-31.5121 38.4806,-68.5045 46.6539,-110.836l0 0 -102.166 0 0 0c-6.1654,22.8191 -16.4883,42.6618 -30.9924,59.8351 -14.5041,17.1497 -31.5121,30.3309 -51.0004,39.8271 -19.512,9.33078 -39.6617,14.1733 -60.4965,14.1733 -43.6775,0 -78.6857,-15.5198 -105.166,-46.1815 -26.6695,-30.827 -39.8507,-71.3391 -39.8507,-121.82l0 -194.836c0,-50.5043 13.1812,-91.0165 39.8507,-121.678 26.4805,-30.4963 61.4887,-45.8271 105.166,-45.8271 30.827,0 60.0005,9.82685 87.3314,29.3388 27.3309,19.4883 45.8271,47.6697 55.1579,84.4967l0 0 102.166 0 0 0c-8.17329,-42.331 -23.8348,-79.3471 -47.0082,-110.836 -22.9844,-31.6774 -51.4965,-56.1737 -85.3235,-73.8431 -33.827,-17.5041 -71.3391,-26.3152 -112.324,-26.3152z\"/>\r\n   <glyph unicode=\"2\" horiz-adv-x=\"516\" d=\"M62.5044 0l0 86.5046 247.49 330.003c14.3387,18.8269 25.512,38.5042 33.4963,58.654 8.00794,20.1734 12.0001,39.1657 12.0001,57.0004l0 1.01576c0,28.3231 -8.31503,50.3154 -24.9923,66.0005 -16.4883,15.6615 -40.1578,23.3151 -70.6541,23.3151 -28.6774,0 -52.0162,-8.504 -70.1817,-25.4884 -18.0001,-17.1734 -28.9845,-41.3389 -33.0003,-72.4966l0 -0.519689 -102.993 0 0 0.519689c8.83472,61.158 31.1577,108.497 66.9927,142.324 35.6696,33.6617 81.8274,50.5043 138.166,50.5043 63.8273,0 113.009,-16.1812 147.828,-48.3311 34.8428,-32.3388 52.3469,-77.8353 52.3469,-136.844l0 -0.496067c0,-24.9923 -5.17327,-51.3311 -15.6615,-78.8274 -10.3465,-27.4963 -24.8506,-53.6697 -43.5122,-78.8274l-205.49 -279.333 268.49 0 0 -94.6779 -400.326 0z\"/>\r\n   <glyph unicode=\"K\" horiz-adv-x=\"641\" d=\"M151.336 162.178l14.6694 149.316 313.479 398.519 122.529 0 -450.677 -547.835zm-63.4966 -162.178l0 710.013 99.674 0 0 -710.013 -99.674 0zm422.011 0l-225.675 378.818 78.662 72.851 269.01 -451.669 -121.997 0z\"/>\r\n   <glyph unicode=\"7\" horiz-adv-x=\"503\" d=\"M440.011 709.99l0 -88.8196 -183.686 -621.17 -105.993 0 183.663 615.17 -180.663 0 0 -102.001 -99.6622 0 0 196.82 386.342 0z\"/>\r\n   <glyph unicode=\"E\" horiz-adv-x=\"597\" d=\"M87.8275 0l0 709.99 99.6622 0 0 -709.99 -99.6622 0zm48.3311 0l0 94.6779 407.838 0 0 -94.6779 -407.838 0zm0 305.176l0 94.6543 354.003 0 0 -94.6543 -354.003 0zm0 309.995l0 94.8196 407.838 0 0 -94.8196 -407.838 0z\"/>\r\n   <glyph unicode=\"4\" horiz-adv-x=\"568\" d=\"M63.4966 108.828l0 86.5046 238.348 514.162 102.497 0 -232.514 -508.327 348.168 0 0 -92.3393 -456.5 0zm306.168 -109.324l0 417.995 97.1582 0 0 -417.995 -97.1582 0z\"/>\r\n   <glyph unicode=\"S\" horiz-adv-x=\"615\" d=\"M301.325 -7.3347c-34.4766,0 -67.0045,3.33073 -97.4771,9.85047 -30.3309,6.4843 -58.8548,16.1576 -85.5007,29.1616 -26.5041,13.1458 -51.3429,29.3388 -74.3392,48.8272l0 0 62.0084 76.1463 0 0c27.0002,-23.3151 56.7997,-40.9964 89.3275,-52.654 32.4924,-11.6576 67.8194,-17.5041 105.981,-17.5041 51.3429,0 91.17,9.49614 119.517,28.6656 28.3112,19.3466 42.4846,46.1697 42.4846,80.6817l0 0.496067c0,27.0002 -7.15754,47.6579 -21.508,62.0084 -14.3151,14.3151 -32.9884,24.9805 -56.3036,31.9963 -23.3506,6.98037 -48.6854,12.6497 -76.5006,17.3269 -26.6813,4.50004 -53.5044,9.81504 -80.6817,16.0159 -27.1774,6.30714 -52.1579,15.9804 -75.0124,29.1616 -22.8191,13.1458 -41.1381,32.138 -54.9926,56.8351 -13.819,24.6616 -20.6576,57.8272 -20.6576,99.1425l0 0.496067c0,66.0123 22.0041,117.178 65.8352,153.497 44.0082,36.3546 105.981,54.5319 186.167,54.5319 37.9846,0 75.3313,-6.1654 111.65,-18.3545 36.4964,-12.1536 72.0006,-30.827 106.513,-55.9847l0 0 -56.6579 -79.0164 0 0c-27.0002,20.1616 -53.8587,35.1853 -80.8589,44.8232 -26.823,9.85047 -53.6461,14.6694 -80.6463,14.6694 -48.5083,0 -86.3511,-9.63787 -113.174,-28.9845 -26.823,-19.3466 -40.1814,-46.8429 -40.1814,-82.3471l0 -0.496067c0,-26.6459 7.68904,-46.9846 23.3506,-60.9808 15.6615,-13.9962 36.1775,-24.3427 61.3351,-31.0042 25.1577,-6.66147 52.3351,-13.004 81.3195,-18.8505 26.0081,-5.13784 52.0162,-11.4804 78.0242,-18.8151 26.1498,-7.15754 49.8193,-17.823 71.1502,-31.6774 21.3309,-13.819 38.3389,-32.8467 51.1658,-56.8351 13.004,-24.1655 19.3466,-55.4886 19.3466,-94.3236l0 -0.8504c0,-65.4808 -22.8545,-116.151 -68.3509,-151.974 -45.4964,-35.8586 -109.666,-53.6815 -192.332,-53.6815z\"/>\r\n   <glyph unicode=\"1\" horiz-adv-x=\"332\" d=\"M244.655 709.99l0 -709.99 -99.6622 0 0 601.493 -100.985 -61.9847 0 102.993 100.985 67.4887 99.6622 0z\"/>\r\n   <glyph unicode=\"B\" horiz-adv-x=\"645\" d=\"M146.505 0l0 92.3393 192.332 0c52.8193,0 90.1661,9.9922 112.158,30.1656 21.8269,20.1734 32.8349,46.8193 32.8349,79.6542l0 1.51182c0,35.1735 -9.33078,63.1659 -27.9923,84.166 -18.6616,21.0002 -49.5122,31.4884 -92.5047,31.4884l-216.828 0 0 89.8354 216.828 0c36.6617,0 64.4887,8.66936 83.1739,26.1734 18.8269,17.3387 28.1577,42.8271 28.1577,76.3234l0 0c0,35.5042 -10.4883,62.0084 -31.3231,79.6778 -20.8348,17.5041 -52.0162,26.3388 -93.6858,26.3388l-203.151 0 0 92.3157 221.151 0c70.0163,0 122.009,-17.4805 156.332,-52.3232 34.1814,-34.8428 51.1894,-81.4967 51.1894,-140.174l0 0c0,-35.3152 -10.3465,-67.8194 -31.1813,-97.3236 -20.8348,-29.5042 -53.6697,-48.3311 -98.174,-56.339 43.8429,-6.49611 78.0006,-26.0081 102.355,-58.1579 24.1655,-32.1735 36.331,-69.1659 36.331,-110.836l0 -1.51182c0,-57.8272 -18.6852,-104.646 -56.1737,-140.151 -37.5121,-35.5042 -88.1582,-53.1736 -152.34,-53.1736l-229.49 0zm-58.6776 0l0 709.99 99.1661 0 0 -709.99 -99.1661 0z\"/>\r\n   <glyph unicode=\"9\" horiz-adv-x=\"513\" d=\"M146.009 0l178.655 352.491 -0.496067 -17.4805c-8.33865,-15.3545 -21.0002,-26.3388 -37.5121,-33.1656 -16.6537,-6.85045 -35.8113,-10.3465 -57.6619,-10.3465 -51.1658,0 -92.3157,18.9923 -123.497,56.8351 -31.3231,38.0082 -46.8193,88.3235 -46.8193,151.159l0 0.519689c0,68.9769 17.3151,122.481 51.9925,160.324 34.6538,38.0082 83.3392,57.0004 146.174,57.0004 62.8115,0 111.497,-19.1813 145.985,-57.4965 34.5121,-38.1735 51.8272,-92.3393 51.8272,-162.332l0 -0.496067c0,-28.8427 -4.32287,-60.1895 -12.8269,-94.0165 -8.33865,-33.6617 -20.8348,-66.4966 -36.9924,-98.8354l-151.348 -304.16 -107.481 0zm110.836 382.326c31.3231,0 55.4886,10.5119 72.4966,31.6774 17.1497,21.1655 25.6537,51.0004 25.6537,89.5046l0 0.330711c0,37.4885 -8.504,66.4966 -25.6537,87.0007 -17.008,20.5041 -41.1736,30.6617 -72.4966,30.6617 -31.5121,0 -55.843,-10.1576 -73.0163,-30.6617 -17.1497,-20.5041 -25.6537,-49.6776 -25.6537,-87.3314l0 -0.519689c0,-38.4806 8.504,-68.1501 25.6537,-89.1503 17.1734,-21.0002 41.5043,-31.5121 73.0163,-31.5121z\"/>\r\n   <glyph unicode=\"G\" horiz-adv-x=\"646\" d=\"M327.168 382.326l246.167 0 0 -123.993c0,-53.5044 -10.5119,-99.9929 -31.1577,-140.009 -20.6695,-39.8271 -49.5122,-70.6541 -86.67,-92.6464 -37.016,-22.0159 -80.6699,-33.0003 -130.844,-33.0003 -50.339,0 -94.4889,10.3229 -132.332,30.9924 -37.6538,20.6695 -66.9927,49.8193 -87.8275,87.4968 -20.8348,37.5121 -31.1813,81.3313 -31.1813,131.505l0 209.506c0,53.3154 10.3465,99.9929 31.016,139.655 20.6695,39.6617 49.4886,70.5124 86.5046,92.5047 37.1578,21.9923 80.8117,33.0003 130.985,33.0003 41.3389,0 78.9927,-8.83472 113.009,-26.5041 33.9924,-17.5041 62.5044,-42.0003 85.6542,-73.6541 23.1734,-31.5121 38.6696,-68.5045 46.8429,-110.836l0 0 -107.835 0 0 0c-9.16543,36.8271 -26.8349,64.9848 -53.1736,84.4967 -26.5041,19.4883 -54.6618,29.3388 -84.4967,29.3388 -44.3389,0 -79.8195,-15.5198 -106.489,-46.1815 -26.6695,-30.827 -40.0161,-71.3391 -40.0161,-121.82l0 -209.506c0,-46.1815 13.6773,-83.1739 41.0082,-111.001 27.3309,-27.827 63.4966,-41.835 108.332,-41.835 44.3389,0 79.8431,14.6694 106.513,44.0082 26.6459,29.3152 39.9924,70.158 39.9924,122.481l0 30.8506 -144.001 0 0 95.1504z\"/>\r\n   <glyph unicode=\"6\" horiz-adv-x=\"513\" d=\"M256.325 -7.32289c-62.8351,0 -111.497,18.6616 -145.985,56.15 -34.5121,37.5121 -51.6618,90.1661 -51.6618,158.174l0 0.496067c0,30.6617 4.15751,63.1659 12.6615,97.5126 8.504,34.3231 20.6695,67.3234 36.4964,99.3315l154.324 305.648 107.505 0 -181.159 -353.979 0.496067 17.4805c16.8427,32.3388 47.0082,48.3311 90.3314,48.3311 55.6776,0 98.8354,-18.4962 129.332,-55.3233 30.6617,-36.9924 45.9925,-89.1739 45.9925,-156.497l0 -0.496067c0,-68.6698 -17.3151,-122.009 -51.8272,-160.017 -34.4885,-37.8192 -83.3392,-56.8115 -146.505,-56.8115zm0 95.6464c32.0081,0 56.339,9.85047 73.347,29.3388 16.819,19.512 25.323,47.339 25.323,83.5046l0 0.496067c0,39.6617 -9.00007,70.347 -26.8349,92.0086 -18.0001,21.6616 -43.4885,32.5042 -76.6541,32.5042 -29.6695,0 -52.6776,-10.8426 -68.8352,-32.6695 -16.3466,-21.8506 -24.4963,-52.6776 -24.4963,-92.3393l0 -0.496067c0,-36.1656 8.504,-63.8273 25.6537,-83.1739 17.1734,-19.4883 41.3389,-29.1735 72.4966,-29.1735z\"/>\r\n  </font>\r\n  <style type=\"text/css\">\r\n   <![CDATA[\r\n    @font-face { font-family:\"Bahnschrift\";font-variant:normal;font-style:normal;font-weight:normal;src:url(\"#FontID0\") format(svg)}\r\n    .fil6_3 {fill:#332C2B}\r\n    .fil5_3 {fill:#EEEEEF;fill-rule:nonzero}\r\n    .fil1_3 {fill:#EBECEC;fill-rule:nonzero}\r\n    .fil3_3 {fill:#FFF000;fill-rule:nonzero}\r\n    .fil2_3 {fill:#B3DAB9;fill-rule:nonzero}\r\n    .fil0_3 {fill:black;fill-rule:nonzero}\r\n    .fil4_3 {fill:#FF3F00;fill-rule:nonzero}\r\n    .fnt1_3 {font-weight:normal;font-size:282.22px;font-family:\'Bahnschrift\'}\r\n    .fnt0_3 {font-weight:normal;font-size:423.33px;font-family:\'Bahnschrift\'}\r\n   ]]>\r\n  </style>\r\n </defs>\r\n <g id=\"Layer_x0020_1\">\r\n  <metadata id=\"CorelCorpID_0Corel-Layer\"/>\r\n  <path class=\"fil0_3\" d=\"M36013 3143c3,0 6,2 9,3 3,2 5,5 7,7l208 137c12,8 16,24 8,37l-2585 3698 14 10c3,0 5,2 8,3 2,2 4,4 6,6l1416 999c11,9 14,25 6,37l-1195 1722c-1,3 -2,6 -4,9 -2,2 -4,4 -6,6l-2389 3442c-1,2 -2,4 -3,5 -1,1 -2,3 -3,4l-6708 9665 5022 2908c13,7 17,23 10,36l-1866 2856 786 425c13,6 18,22 11,35l-841 1466 -818 1428c-7,13 -24,17 -36,10l-5815 -3414c-3,-1 -6,-2 -8,-4 -3,-1 -5,-3 -7,-5l-6329 -3717c-4,-1 -8,-2 -11,-4 -4,-2 -6,-4 -8,-8l-12385 -7272c-12,-7 -16,-22 -10,-35l1458 -2932 1666 -2849c8,-13 24,-17 36,-9l2617 1526c6,-1 13,0 18,3l1786 1049 1294 -2374 1 -3 451 -651 372 -959c1,-1 1,-3 2,-4l1321 -2190c8,-12 24,-16 36,-9l410 266 2613 -4091c8,-12 24,-16 37,-8 1,1 2,2 4,3l7164 6827 3661 3132 7773 -11714c8,-12 24,-15 37,-7l759 499z\"/>\r\n  <path class=\"fil1_3\" d=\"M26372 15999c-8,12 -25,15 -37,7 -1,-1 -3,-2 -4,-4l-3246 -3094c-5,-1 -9,-3 -12,-6 -4,-3 -6,-8 -7,-12l-742 -707c-5,-1 -9,-3 -12,-6 -4,-4 -6,-8 -8,-12l-741 -707c-4,-1 -9,-3 -12,-6 -4,-4 -6,-8 -8,-13l-741 -706c-4,-1 -9,-3 -12,-7 -4,-3 -7,-7 -8,-12l-740 -706c-5,-1 -10,-3 -14,-7 -3,-3 -6,-8 -7,-12l-740 -706c-5,-1 -10,-3 -14,-7 -3,-4 -6,-8 -7,-13l-740 -705c-5,-1 -10,-3 -14,-7 -4,-4 -6,-8 -7,-13l-740 -705c-5,-1 -10,-4 -14,-7 -4,-4 -6,-9 -7,-14l-740 -705c-5,0 -10,-3 -14,-6 -4,-4 -6,-9 -7,-14l-740 -705c-5,0 -10,-3 -14,-7 -4,-3 -6,-8 -7,-13l-419 -399 -174 180 -7084 12065 0 1c-8,13 -24,17 -36,10l-460 -266 -1816 -1070c-13,-7 -17,-23 -10,-36l629 -1066c0,-4 1,-8 3,-11 2,-3 5,-6 8,-8l291 -494c0,-3 1,-5 3,-8 1,-2 3,-4 5,-6l294 -500c1,-1 1,-2 2,-4 1,-1 2,-2 3,-3l260 -442c1,-2 1,-5 3,-7 1,-2 3,-4 4,-6l258 -437c1,-3 2,-6 3,-9 2,-3 4,-6 7,-8l250 -423 -2586 -1509 -1652 2827 -1447 2907 2382 1398 813 -1394 0 0 1 0c7,-13 23,-17 36,-9l2709 1590c4,0 8,1 12,3 3,3 6,6 9,9l884 519c4,1 8,2 11,4 4,2 7,5 9,8l886 520c6,-1 12,0 17,3 6,3 10,8 12,14l2698 1584c4,0 8,1 11,3 4,2 7,5 9,8l886 520c3,0 7,2 10,3 3,2 6,5 8,8l887 520c3,1 7,2 10,4 3,2 5,4 7,7l888 521c3,0 6,1 9,3 3,2 6,4 8,6l888 522c6,-1 11,0 16,3 5,3 9,8 11,13l3607 2117c3,0 5,1 8,3 3,1 5,3 7,5l890 523c3,1 5,2 8,3 2,1 4,3 6,5l892 524c2,0 4,1 6,2 2,2 4,3 6,5l893 524c2,1 4,2 6,3 2,1 3,2 5,3l3841 2255c3,0 6,2 9,3 3,2 6,4 8,7l1018 597 813 -1418 -789 -426c-13,-8 -16,-24 -8,-37l1866 -2855 -5022 -2908 -2 -1 -1058 -621c-4,0 -8,-1 -11,-3 -4,-2 -7,-5 -9,-8l-886 -520c-3,-1 -7,-2 -10,-4 -3,-2 -6,-4 -8,-7l-954 -560c-13,-7 -17,-23 -9,-36l1658 -2836c7,-12 24,-17 36,-9l959 563c4,1 7,2 10,4 4,2 6,4 8,7l886 521c4,0 8,1 11,3 3,2 6,5 8,8l1252 736 624 -899 -1410 -985c-12,-8 -15,-25 -6,-37l2395 -3449c1,-2 2,-3 3,-4 1,-2 2,-3 3,-4l1787 -2573c0,-5 1,-11 5,-15 3,-5 7,-8 12,-10l587 -845c1,-3 2,-5 4,-7 1,-2 3,-3 5,-5l586 -844c1,-5 2,-9 5,-12 2,-4 5,-7 9,-9l1185 -1705c0,-6 1,-12 5,-17 3,-5 8,-8 13,-10l586 -843c1,-3 2,-6 4,-8 1,-3 4,-5 6,-7l588 -847 -21 -14 0 0 -120 -78 -1 -1c-12,-8 -15,-24 -7,-36l2490 -3761 -723 -475 -7775 11716 -1 2 -1099 1579z\"/>\r\n  <path class=\"fil0_3\" d=\"M9178 18621c-13,-8 -17,-24 -10,-36l1096 -1866c0,-3 1,-6 3,-8 1,-3 3,-5 5,-7l523 -891c1,-2 2,-5 3,-7 1,-2 3,-5 5,-6l3187 -5429c0,-2 1,-5 3,-7 1,-3 3,-5 5,-7l1825 -3108 1 -1c7,-13 23,-17 36,-10l546 323c2,2 4,3 5,5l751 715c2,1 3,3 4,4l1219 1161c9,9 11,23 4,34l-2574 4380c0,4 -1,8 -3,13 -3,4 -6,7 -10,9l-2120 3608c0,3 -1,6 -3,8 -1,3 -3,5 -5,7l-523 889c0,3 -1,6 -3,9 -1,3 -3,5 -6,7l-522 889c0,3 -2,6 -3,9 -2,2 -4,5 -6,7l-561 955c-8,12 -24,16 -36,9l-1407 -823c-3,0 -5,-1 -8,-3 -2,-1 -4,-3 -6,-5l-1415 -827zm15768 -570l-54 54c-2,2 -4,4 -6,5l-64 43 -1 0c-2,2 -6,3 -9,4l-79 14c-2,1 -5,1 -7,1l-78 -7c-3,0 -6,-1 -9,-2l-71 -28c-1,-1 -3,-2 -4,-3l-459 -272c-1,0 -2,-1 -4,-2l-64 -51c-3,-2 -5,-4 -7,-7l-43 -72c-1,-2 -2,-4 -3,-6l-20 -77c-2,-3 -2,-6 -2,-9l0 -79c0,-4 1,-8 2,-11l36 -78c0,-1 1,-2 1,-4l819 -1400c1,-2 3,-4 4,-5l48 -54c1,-3 4,-5 6,-7l65 -43c2,-1 5,-2 7,-3l70 -21c3,-1 6,-2 9,-2l79 0c3,0 5,1 7,1l77 21c4,1 7,2 10,4l65 43c2,2 3,3 5,5l479 465c2,1 3,3 4,4l42 57c1,1 2,3 3,5l28 63c2,3 3,6 3,10l7 76c1,4 1,7 0,10l-14 72 0 0c-1,2 -1,4 -2,5l-28 63c-1,2 -2,4 -3,7l-840 1206c-2,2 -3,3 -5,5zm-3544 3829c8,-13 24,-17 37,-9l910 535c3,0 6,1 9,3 2,2 5,4 7,6l876 515c2,1 5,2 7,3 2,1 4,3 6,5l880 517c1,1 3,2 4,2 1,1 3,2 4,3l1760 1035c6,-1 12,0 17,3 5,3 9,8 11,13l865 509c2,0 4,1 7,3 2,1 4,3 6,5l879 516c2,1 3,2 5,3 2,1 3,2 5,3l885 521c13,7 17,23 10,36l-1695 2886c-7,13 -23,17 -36,10l-2661 -1562c-3,-1 -6,-2 -10,-4 -3,-2 -5,-4 -7,-7l-4466 -2620c-12,-8 -16,-24 -9,-37l844 -1441c0,-3 2,-6 3,-9 2,-3 4,-5 7,-7l840 -1436zm-5205 826l2158 -3676c1,-3 2,-5 3,-8 2,-2 3,-4 5,-6l524 -891c0,-3 1,-5 3,-7 1,-2 2,-4 4,-6l524 -893c1,-2 2,-4 3,-6 1,-2 2,-4 4,-5l525 -894c0,-2 1,-4 2,-5 1,-2 3,-4 4,-5l1999 -3405c1,-2 2,-4 4,-6 10,-10 27,-11 37,-1l2449 2327c9,9 10,23 3,33l-2674 4548c0,4 -1,7 -3,10 -2,3 -4,6 -7,8l-521 886c0,4 -1,7 -3,11 -2,3 -5,6 -8,8l-1059 1802c-1,2 -2,4 -3,6 -1,2 -3,4 -4,6l-524 891c-1,3 -2,5 -3,8 -2,3 -4,5 -6,7l-562 955c-7,13 -23,17 -36,9l-1413 -832 -1 0 -1 -1 -1413 -832c-13,-8 -17,-24 -10,-36zm-3511 -2061l557 -948c0,-5 1,-10 3,-14 3,-4 6,-8 11,-10l1057 -1802c0,-1 1,-3 2,-4 1,-2 2,-3 3,-5l527 -899 1 -1 1 -2 1590 -2708c0,-4 1,-8 3,-11 2,-4 5,-6 8,-9l520 -886c1,-3 2,-7 4,-10 1,-3 4,-6 6,-8l1946 -3315c1,-2 2,-4 4,-6 10,-11 27,-11 37,-1l1218 1157c2,1 4,2 5,3 6,3 9,7 11,13l1215 1153c9,9 10,23 4,34l-5858 9971c-8,13 -24,17 -37,10l-2828 -1666c-13,-7 -17,-23 -10,-36z\"/>\r\n  <path class=\"fil0_3\" d=\"M3149 16500c2,-7 7,-11 12,-14l1871 -3451c1,-1 2,-3 3,-4l56 -77c1,-2 3,-4 5,-6l79 -64c2,-2 4,-3 6,-4l92 -42c3,-2 5,-3 8,-3l100 -14c3,-1 6,-1 8,0l101 14c2,0 4,1 5,2l92 35c2,0 4,1 6,2l1113 646c3,3 6,5 8,9l64 42c3,2 6,5 8,8l57 85c1,1 2,3 2,5l43 92c1,3 2,5 3,8l14 100c0,3 0,6 0,8l-14 101c-1,2 -1,4 -2,6l-34 80c-1,3 -2,7 -4,10l-2 4 -3 7c-2,4 -4,7 -7,9l-1998 3390 0 0 0 0 0 1 0 0 -1 0 0 0 0 1 0 0 0 0 -1 0 0 1 0 0 -63 84c-2,3 -4,5 -7,7l-79 58c-1,1 -3,2 -5,2l-99 43c-3,1 -6,2 -10,3l-100 7c-2,0 -4,0 -6,0l-101 -15c-1,0 -3,-1 -5,-1l-93 -36c-1,0 -3,-1 -4,-2l-970 -567c-6,-4 -10,-9 -12,-15l-54 -36c-2,-2 -4,-4 -6,-6l-64 -85c-1,-2 -3,-4 -4,-6l-43 -94c-1,-2 -2,-5 -2,-7l-14 -106c-1,-3 -1,-6 0,-9l14 -99c0,-3 1,-5 1,-7l36 -100z\"/>\r\n  <path class=\"fil2_3\" d=\"M36014 3206l-2476 3739 79 50 2569 -3676 -172 -113zm-11425 14880l66 26 72 7 70 -13 58 -39 53 -53 838 -1203 26 -59 13 -66 -6 -70 -26 -58 -40 -53 -477 -464 -60 -40 -71 -19 -72 0 -64 19 -59 40 -45 52 -817 1397 -33 72 0 71 19 71 39 65 60 46 456 271zm-21399 -1542l-26 72 -13 94 13 100 40 87 60 80 83 55c4,3 7,7 9,11l935 547 89 34 95 14 93 -7 94 -40 73 -53 61 -82 2005 -3401 38 -88 13 -93 -13 -93 -41 -89 -53 -79 -82 -55c-1,-1 -3,-2 -4,-4l-1091 -633 -88 -34 -94 -13 -93 13 -87 40 -74 60 -54 75 -1888 3481 0 1zm14148 -11378l-733 -700 -2610 4087c-8,13 -25,16 -37,8l-408 -265 -1306 2165 -372 959c-1,2 -2,4 -3,6l-452 652 -1306 2398c-2,3 -5,6 -8,8l-1992 3446c132,77 263,153 395,230l7073 -12046c1,-2 3,-4 4,-6l194 -201 0 0 0 0 1 0 0 0 0 -1 1 0 0 0 0 -1 0 0 1 0 0 0 0 0 0 0 1 -1 0 0 0 0 0 0 1 -1 0 0 0 0 0 0 1 0 0 0 0 -1 1 0 0 0 0 0 1 0 0 0 0 0 0 0 1 -1 0 0 0 0 0 0 1 0 0 0 0 0 1 0 0 -1 0 0 1 0 0 0 0 0 0 0 1 0 0 0 1 0 0 0 0 0 0 0 1 0 0 -1 0 0 1 0 0 0 0 0 1 0 0 0 0 0 0 0 1 0 0 0 1 0 0 0 0 0 0 0 1 0 0 0 0 0 1 0 0 0 0 0 1 0 0 0 0 0 1 0 0 0 0 0 1 1 0 0 0 0 0 0 1 0 0 0 1 0 0 0 0 0 0 0 1 0 0 0 0 0 0 0 1 1 0 0 1 0 0 0 0 0 0 0 1 0 0 0 0 1 0 0 1 0 0 0 1 0 0 0 0 0 0 1 1 0 0 0 0 0 0 0 1 0 0 1 0 0 0 0 1 0 0 0 0 1 0 0 1 0 0 0 0 0 1 1 0 0 0 0 0 0 430 410 1094 -1150z\"/>\r\n  <path class=\"fil3_3\" d=\"M8065 17903l5 -8 -1786 -1057 -8 13 13 8 35 20 1426 840c105,62 210,123 315,184zm-519 839l6 -10 -1769 -1039 -6 10 1769 1039z\"/>\r\n  <path class=\"fil4_3\" d=\"M649 18825c-7,13 -23,18 -36,12 -13,-7 -18,-23 -11,-36l1529 -2972 1 -2 4867 -8751c8,-13 24,-17 36,-10 13,7 18,23 11,35l-4868 8752 0 0 -1529 2972zm32228 -17716c-12,-8 -16,-24 -8,-37 8,-12 24,-16 36,-8l5988 3777c13,7 16,24 9,36 -8,12 -24,16 -37,8l-5988 -3776zm488 -776c-12,-8 -16,-24 -8,-36 8,-12 24,-16 37,-8l5995 3769c12,8 16,24 8,36 -8,13 -24,16 -36,9l-5996 -3770zm-25468 7224c7,-13 23,-17 36,-10 13,7 17,23 10,36 -365,649 -731,1297 -1098,1945 -968,1793 -1906,3442 -2937,5205 -764,1537 -1511,3031 -2312,4545 -6,13 -22,18 -35,11 -13,-7 -18,-23 -11,-36 798,-1510 1522,-3042 2312,-4546l1666 -2850c783,-1452 1562,-2866 2369,-4300z\"/>', '<g id=\"_2096257672224\">\r\n   <polygon class=\"fil5_3\" points=\"[[1]]\" style=\"fill:[[2]]\"/>\r\n   <g transform=\"[[3]]\">\r\n    <text x=\"20000\" y=\"16250\"  class=\"fil6_3 fnt0_3\">[[4]]</text>\r\n   </g>\r\n  </g>', '<g id=\"_2096257673024\">\r\n   <path class=\"fil5_3\" d=\"[[1]]\" style=\"fill:[[2]]\"/>\r\n   <g transform=\"[[3]]\">\r\n    <text x=\"20000\" y=\"16250\"  class=\"fil6_3 fnt0_3\">[[4]]</text>\r\n   </g>\r\n  </g>', '', ' </g>\r\n</svg>', 400, 100, 100);

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `id` int NOT NULL,
  `id_parent` int NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `route_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `urutan` int NOT NULL,
  `lihat` int NOT NULL,
  `tambah` int NOT NULL,
  `edit` int NOT NULL,
  `hapus` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`id`, `id_parent`, `title`, `route_name`, `icon`, `urutan`, `lihat`, `tambah`, `edit`, `hapus`) VALUES
(1, 0, 'Beranda', 'beranda.index', 'fas fa-home', 0, 1, 0, 0, 0),
(2, 0, 'Dashboard', 'dashboard.index', 'fas fa-tachometer-alt', 1, 1, 0, 0, 0),
(3, 0, 'Siteplan', '#', 'fas fa-map-marked-alt', 2, 1, 0, 0, 0),
(4, 0, 'Unit Ready', 'unit-ready.index', 'fas fa-house-user', 3, 1, 0, 1, 0),
(5, 0, 'Pengajuan Hold', 'pengajuan-hold.index', 'fas fa-bookmark', 4, 1, 0, 1, 1),
(6, 0, 'Pembayaran', 'pembayaran.index', 'fas fa-money-bill', 5, 1, 1, 1, 1),
(7, 0, 'Transaksi', '#', 'fas fa-tag', 6, 1, 0, 0, 0),
(8, 0, 'Customer', '#', 'fas fa-users', 7, 1, 1, 1, 1),
(9, 0, 'Marketing', '#', 'fas fa-user', 8, 1, 1, 1, 1),
(10, 0, 'OP Bangunan', '#', 'fas fa-home', 9, 1, 0, 0, 0),
(11, 0, 'OP Jalan', '#', 'fas fa-road', 10, 1, 0, 0, 0),
(12, 0, 'OP Saluran', '#', 'fas fa-tint', 11, 1, 0, 0, 0),
(13, 0, 'Legal', '#', 'fas fa-arrow-left', 12, 1, 0, 0, 0),
(14, 0, 'Keuangan', '#', 'fas fa-money-bill', 13, 1, 0, 0, 0),
(15, 0, 'Pembelian', '#', 'fas fa-shopping-cart', 14, 1, 0, 0, 0),
(16, 0, 'Barang Keluar', 'barang-keluar.index', 'fas fa-box-open', 15, 1, 1, 1, 1),
(17, 0, 'Master Data', '#', 'fas fa-database', 16, 1, 0, 0, 0),
(19, 0, 'Pengaturan', '#', 'fas fa-cogs', 18, 1, 0, 0, 0),
(20, 3, 'Siteplan Penjualan', 'siteplan-penjualan.index', 'far fa-circle', 1, 1, 0, 0, 0),
(21, 3, 'Siteplan Proyek', 'siteplan-proyek.index', 'far fa-circle', 2, 1, 0, 0, 0),
(22, 3, 'Siteplan Unit Ready', 'siteplan-unit-ready.index', 'far fa-circle', 3, 1, 0, 0, 0),
(23, 7, 'Wawancara', 'wawancara.index', 'far fa-circle', 2, 1, 1, 1, 1),
(24, 7, 'ACC Bank', 'acc-bank.index', 'far fa-circle', 3, 1, 1, 1, 1),
(25, 7, 'Akad', 'akad.index', 'far fa-circle', 4, 1, 1, 1, 1),
(26, 7, 'Pindah Unit', 'pindah-unit.index', 'far fa-circle', 5, 1, 1, 1, 1),
(27, 7, 'Pembelian Cancel', 'pembelian-cancel.index', 'far fa-circle', 6, 1, 1, 1, 1),
(28, 7, 'Ganti Nama', 'ganti-nama.index', 'far fa-circle', 7, 1, 1, 1, 1),
(29, 8, 'Customer', 'customer.index', 'far fa-circle', 1, 1, 1, 1, 1),
(30, 8, 'Prospek', 'prospek.index', 'far fa-circle', 2, 1, 1, 1, 1),
(31, 8, 'Upload File', 'upload-file.index', 'far fa-circle', 3, 1, 1, 1, 1),
(32, 8, 'Arsip Customer', 'arsip-customer.index', 'far fa-circle', 4, 1, 0, 0, 1),
(33, 8, 'Aduan Customer', 'aduan-customer.index', 'far fa-circle', 5, 1, 1, 1, 1),
(34, 8, 'Serah Terima Kunci', 'serah-terima-kunci.index', 'far fa-circle', 6, 1, 1, 1, 1),
(35, 9, 'Marketing Offline', 'marketing-offline.index', 'far fa-circle', 1, 1, 1, 1, 1),
(36, 9, 'Marketing Freelance', 'marketing-freelance.index', 'far fa-circle', 2, 1, 1, 1, 1),
(37, 10, 'Proyek Bangunan', 'proyek-bangunan.index', 'far fa-circle', 1, 1, 1, 1, 1),
(38, 10, 'Jenis Pekerjaan', 'jenis-pekerjaan-bangunan.index', 'far fa-circle', 2, 1, 1, 1, 1),
(39, 11, 'Proyek Jalan', 'proyek-jalan.index', 'far fa-circle', 1, 1, 1, 1, 1),
(40, 11, 'Jalan', 'jalan.index', 'far fa-circle', 2, 1, 1, 1, 1),
(41, 11, 'Jenis Pekerjaan', 'jenis-pekerjaan-jalan.index', 'far fa-circle', 3, 1, 1, 1, 1),
(42, 12, 'Proyek Saluran', 'proyek-saluran.index', 'far fa-circle', 1, 1, 1, 1, 1),
(43, 12, 'Saluran', 'saluran.index', 'far fa-circle', 2, 1, 1, 1, 1),
(44, 12, 'Jenis Pekerjaan', 'jenis-pekerjaan-saluran.index', 'far fa-circle', 3, 1, 1, 1, 1),
(45, 13, 'Listrik & Air', 'listrik-air.index', 'far fa-circle', 1, 1, 1, 1, 1),
(46, 13, 'Pengajuan Berkas', 'pengajuan-berkas.index', 'far fa-circle', 2, 1, 1, 1, 1),
(47, 14, 'Pemasukan', 'pemasukan.index', 'far fa-circle', 1, 1, 1, 1, 1),
(48, 14, 'Pengeluaran', 'pengeluaran.index', 'far fa-circle', 2, 1, 1, 1, 1),
(49, 14, 'Hutang', 'hutang.index', 'far fa-circle', 3, 1, 1, 1, 1),
(50, 14, 'Piutang', 'piutang.index', 'far fa-circle', 4, 1, 1, 1, 1),
(51, 14, 'Kategori Transaksi', 'kategori-transaksi.index', 'far fa-circle', 5, 1, 1, 1, 1),
(52, 14, 'Mutasi Saldo', 'mutasi-saldo.index', 'far fa-circle', 6, 1, 1, 1, 1),
(53, 14, 'Laporan Arus Kas', 'laporan-arus-kas.index', 'far fa-circle', 7, 1, 0, 0, 0),
(54, 15, 'Input PO', 'input-po.index', 'far fa-circle', 1, 1, 1, 1, 1),
(55, 15, 'Barang Masuk', 'barang-masuk.index', 'far fa-circle', 2, 1, 1, 1, 1),
(56, 17, 'Lokasi Perumahan', 'lokasi-kavling.index', 'far fa-circle', 1, 1, 1, 1, 1),
(57, 17, 'Kavling', 'kavling.index', 'far fa-circle', 2, 1, 1, 1, 1),
(58, 17, 'Barang', 'barang.index', 'far fa-circle', 3, 1, 1, 1, 1),
(59, 17, 'Supplier', 'supplier.index', 'far fa-circle', 4, 1, 1, 1, 1),
(60, 17, 'Satuan', 'satuan.index', 'far fa-circle', 5, 1, 1, 1, 1),
(61, 17, 'Bank Transaksi', 'bank-transaksi.index', 'far fa-circle', 6, 1, 1, 1, 1),
(64, 19, 'Pengaturan Profil', 'pengaturan-profil.index', 'far fa-circle', 1, 1, 0, 1, 0),
(65, 19, 'Pengaturan Media', 'pengaturan-media.index', 'far fa-circle', 2, 1, 1, 1, 1),
(66, 19, 'Pengaturan Pengguna', 'pengaturan-pengguna.index', 'far fa-circle', 3, 1, 1, 1, 1),
(67, 19, 'Hak Akses', 'hak-akses.index', 'far fa-circle', 4, 1, 0, 1, 0),
(68, 19, 'Konten', 'konten.index', 'far fa-circle', 5, 1, 1, 1, 1),
(69, 19, 'List Penjualan', 'list-penjualan.index', 'far fa-circle', 6, 1, 1, 1, 1),
(70, 19, 'Role User', 'role-user.index', 'far fa-circle', 4, 1, 0, 1, 0),
(72, 17, 'Perusahaan', 'perusahaan.index', 'far fa-circle', 0, 1, 1, 1, 1),
(73, 17, 'Bank KPR', 'bank-kpr.index', 'far fa-circle', 7, 1, 1, 1, 1),
(74, 7, 'PPJB', 'ppjb.index', '', 9, 1, 1, 1, 1),
(75, 17, 'Notaris', 'notaris.index', 'far fa-circle', 9, 1, 1, 1, 1),
(77, 19, 'Log Aktivitas', 'log-aktivitas.index', 'fas fa-history', 7, 1, 0, 0, 0),
(78, 3, 'Siteplan Listrik', 'siteplan-listrik.index', 'fas fa-circle', 4, 1, 1, 1, 1),
(79, 3, 'Siteplan Air', 'siteplan-air.index', 'fas fa-circle', 5, 1, 0, 0, 0),
(80, 13, 'Balik nama', 'balik-nama.index', 'fas fa-circle', 6, 1, 1, 1, 1),
(82, 13, 'BPHTB & SSP', 'bphtb-ssp.index', 'fas fa-circle', 3, 1, 1, 1, 1),
(84, 3, 'Siteplan BPHTB & SSP', 'st-bphtb-ssp.index', 'fas fa-circle', 5, 1, 0, 0, 0),
(85, 3, 'Siteplan Balik Nama', 'st-balik-nama.index', 'fas fa-circle', 7, 1, 0, 0, 0),
(86, 7, 'SPPR', 'sppr.index', 'far fa-circle', 1, 1, 1, 1, 1),
(87, 17, 'Retensi', 'retensi.index', 'far fa-circle', 10, 1, 1, 1, 1),
(88, 14, 'Retensi', 'keuangan-retensi.index', 'far fa-circle', 8, 1, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `menu_panduan_aplikasi`
--

CREATE TABLE `menu_panduan_aplikasi` (
  `id` int NOT NULL,
  `judul` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `link_yt` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `metode_bayar`
--

CREATE TABLE `metode_bayar` (
  `id` int NOT NULL,
  `jenis_bayar` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `metode_bayar`
--

INSERT INTO `metode_bayar` (`id`, `jenis_bayar`) VALUES
(1, 'CASH'),
(2, 'TRANSFER'),
(3, 'QRIS');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2026_07_05_130000_create_pemasukan_retensi_table', 1),
(2, '2026_07_05_120000_create_retensi_table_and_menu', 2),
(3, '2026_06_12_102911_add_estimasi_plafon_to_customer_table', 3),
(4, '2026_06_12_103000_add_sbum_to_customer_table', 4),
(5, '2026_07_05_140000_add_keuangan_retensi_menu', 5);

-- --------------------------------------------------------

--
-- Table structure for table `mutasi_saldo`
--

CREATE TABLE `mutasi_saldo` (
  `id` int NOT NULL,
  `tanggal` date NOT NULL,
  `rekening_asal` int NOT NULL,
  `rekening_tujuan` int NOT NULL,
  `nominal` int NOT NULL,
  `lampiran` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notaris`
--

CREATE TABLE `notaris` (
  `id` int NOT NULL,
  `nama_notaris` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat_notaris` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `telp_notaris` varchar(35) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `keterangan_notaris` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pemasukan`
--

CREATE TABLE `pemasukan` (
  `id` int NOT NULL,
  `id_hutang` int NOT NULL DEFAULT '0',
  `id_bank` int NOT NULL DEFAULT '0',
  `id_metode_bayar` int DEFAULT '1',
  `id_piutang` int NOT NULL DEFAULT '0',
  `id_mutasi` int NOT NULL DEFAULT '0',
  `id_customer` int NOT NULL DEFAULT '0',
  `id_lokasi` int NOT NULL DEFAULT '0',
  `id_pindah_unit` int NOT NULL DEFAULT '0',
  `id_ganti_nama` int NOT NULL DEFAULT '0',
  `no_kwitansi` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal` date NOT NULL,
  `nominal` int NOT NULL,
  `lampiran` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_kategori_transaksi` int NOT NULL,
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `keterangan_kategori` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pemasukan`
--

INSERT INTO `pemasukan` (`id`, `id_hutang`, `id_bank`, `id_metode_bayar`, `id_piutang`, `id_mutasi`, `id_customer`, `id_lokasi`, `id_pindah_unit`, `id_ganti_nama`, `no_kwitansi`, `tanggal`, `nominal`, `lampiran`, `id_kategori_transaksi`, `keterangan`, `keterangan_kategori`) VALUES
(1, 0, 1, 2, 0, 0, 1, 0, 0, 0, 'ASD', '2026-07-05', 1000000, '', 1, 'Booking Fee Rumah tipe  DE ALASKA RESIDENCE Blok B-04', NULL),
(2, 0, 0, 2, 0, 0, 1, 0, 0, 0, '', '2026-07-05', 120000000, '', 4, 'Pencairan KPR', 'Pencairan KPR');

-- --------------------------------------------------------

--
-- Table structure for table `pemasukan_retensi`
--

CREATE TABLE `pemasukan_retensi` (
  `id` bigint UNSIGNED NOT NULL,
  `id_pemasukan` bigint UNSIGNED NOT NULL,
  `id_retensi` bigint UNSIGNED NOT NULL,
  `nominal` bigint NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pemasukan_retensi`
--

INSERT INTO `pemasukan_retensi` (`id`, `id_pemasukan`, `id_retensi`, `nominal`) VALUES
(1, 2, 1, 5000000),
(2, 2, 2, 5000000),
(3, 2, 3, 20000000);

-- --------------------------------------------------------

--
-- Table structure for table `pembelian_cancel`
--

CREATE TABLE `pembelian_cancel` (
  `id` int NOT NULL,
  `tgl_batal` date NOT NULL,
  `id_customer` int NOT NULL,
  `keterangan_batal` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `biaya_admin` bigint NOT NULL,
  `jumlah_bayar` bigint NOT NULL,
  `id_bank` int NOT NULL,
  `id_bank_tujuan` int NOT NULL,
  `no_rekening` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `atas_nama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lampiran_bukti` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pengajuan_hold`
--

CREATE TABLE `pengajuan_hold` (
  `id` int NOT NULL,
  `no_registrasi` varchar(35) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tgl_booking` date DEFAULT NULL,
  `nama_lengkap` varchar(75) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nik` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_telp` varchar(35) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat_ktp` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat_domisili` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `jenis_kelamin` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tempat_lahir` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tgl_lahir` date DEFAULT NULL,
  `npwp` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pekerjaan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_pernikahan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama_p` varchar(225) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nik_p` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_bpjs_kes` varchar(35) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama_saudara` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_telp_saudara` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `foto_ktp` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `foto_npwp` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `foto_kk` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `foto_bpjs` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `foto_pemohon` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `foto_ktp_p` varchar(225) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `booking_fee` bigint DEFAULT NULL,
  `file_bukti` varchar(74) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_sppr` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_marketing` int NOT NULL,
  `id_freelance` bigint UNSIGNED DEFAULT NULL,
  `id_lokasi` int NOT NULL,
  `id_kavling` int NOT NULL,
  `hrg_jual` bigint NOT NULL,
  `biaya_surat` bigint NOT NULL,
  `peningkatan_mutu` bigint NOT NULL,
  `total_harga` bigint NOT NULL,
  `jenis_perumahan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jenis_pembelian` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `an_surat_cash` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `termin_x_cash_b` bigint NOT NULL DEFAULT '0',
  `stt_reg` int NOT NULL COMMENT '1=pending\r\n2=disetujui\r\n3=ditolak'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pengajuan_hold`
--

INSERT INTO `pengajuan_hold` (`id`, `no_registrasi`, `tgl_booking`, `nama_lengkap`, `nik`, `no_telp`, `email`, `alamat_ktp`, `alamat_domisili`, `jenis_kelamin`, `tempat_lahir`, `tgl_lahir`, `npwp`, `pekerjaan`, `status_pernikahan`, `nama_p`, `nik_p`, `no_bpjs_kes`, `nama_saudara`, `no_telp_saudara`, `foto_ktp`, `foto_npwp`, `foto_kk`, `foto_bpjs`, `foto_pemohon`, `foto_ktp_p`, `booking_fee`, `file_bukti`, `file_sppr`, `id_marketing`, `id_freelance`, `id_lokasi`, `id_kavling`, `hrg_jual`, `biaya_surat`, `peningkatan_mutu`, `total_harga`, `jenis_perumahan`, `jenis_pembelian`, `an_surat_cash`, `termin_x_cash_b`, `stt_reg`) VALUES
(1, '001', '2026-07-05', 'faisal damanik A', '6471052401800007', '081250274777', '', 'Jl. Pegangsaan Timu No. 123', 'Jl. Pegangsaan Timu No. 123', 'Laki-laki', 'Batam', '1990-07-06', '98234234234', '', 'Belum Menikah', '', '', '', '', '', 'ktp_1783229093.webp', NULL, NULL, NULL, NULL, NULL, 1000000, NULL, NULL, 0, 0, 1, 4, 173000000, 10000000, 10000000, 10000000, 'Subsidi', 'KPR', NULL, 0, 2);

-- --------------------------------------------------------

--
-- Table structure for table `pengajuan_hold_tempo`
--

CREATE TABLE `pengajuan_hold_tempo` (
  `id` int NOT NULL,
  `no_registrasi` varchar(35) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tgl_booking` date DEFAULT NULL,
  `nama_lengkap` varchar(75) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nik` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_telp` varchar(35) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat_ktp` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat_domisili` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `jenis_kelamin` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tempat_lahir` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tgl_lahir` date DEFAULT NULL,
  `npwp` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pekerjaan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_pernikahan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama_p` varchar(225) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nik_p` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_bpjs_kes` varchar(35) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama_saudara` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_telp_saudara` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `foto_ktp` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `foto_npwp` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `foto_kk` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `foto_bpjs` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `foto_pemohon` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `foto_ktp_p` varchar(225) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `booking_fee` bigint DEFAULT NULL,
  `file_bukti` varchar(74) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_marketing` int NOT NULL,
  `id_freelance` bigint UNSIGNED DEFAULT NULL,
  `id_lokasi` int NOT NULL,
  `id_kavling` int NOT NULL,
  `hrg_jual` bigint NOT NULL,
  `biaya_surat` bigint NOT NULL,
  `peningkatan_mutu` bigint NOT NULL,
  `total_harga` bigint NOT NULL,
  `jenis_perumahan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jenis_pembelian` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `an_surat_cash` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `termin_x_cash_b` bigint NOT NULL DEFAULT '0',
  `stt_reg` int NOT NULL COMMENT '1=pending\r\n2=disetujui\r\n3=ditolak',
  `id_pengajuan_hold` int NOT NULL,
  `id_user` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pengeluaran`
--

CREATE TABLE `pengeluaran` (
  `id` int NOT NULL,
  `id_hutang` int NOT NULL DEFAULT '0',
  `id_piutang` int NOT NULL DEFAULT '0',
  `id_po` int NOT NULL DEFAULT '0',
  `id_mutasi` int NOT NULL DEFAULT '0',
  `id_proyek_bangunan_detail` int NOT NULL DEFAULT '0',
  `id_proyek_jalan_detail` int NOT NULL DEFAULT '0',
  `id_proyek_saluran_detail` int NOT NULL DEFAULT '0',
  `id_pembelian_cancel` int NOT NULL DEFAULT '0',
  `tanggal` date NOT NULL,
  `id_bank` int NOT NULL DEFAULT '0',
  `nominal` int NOT NULL,
  `lampiran` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_kategori_transaksi` int NOT NULL,
  `id_metode_bayar` int NOT NULL DEFAULT '1',
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `persyaratan_legal`
--

CREATE TABLE `persyaratan_legal` (
  `id` int NOT NULL,
  `id_customer` int NOT NULL,
  `IPH` int DEFAULT '0',
  `SHGB` int DEFAULT '0',
  `SSP` int DEFAULT '0',
  `BPHTB` int DEFAULT '0',
  `SIKUMBANG` int DEFAULT '0',
  `DAFTAR_SIKASEP` int DEFAULT '0',
  `FOTO_SIKASEP` int DEFAULT '0',
  `TRILOGI` int DEFAULT '0',
  `catatan_kekurangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `percakapan_wa` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `persyaratan_legal`
--

INSERT INTO `persyaratan_legal` (`id`, `id_customer`, `IPH`, `SHGB`, `SSP`, `BPHTB`, `SIKUMBANG`, `DAFTAR_SIKASEP`, `FOTO_SIKASEP`, `TRILOGI`, `catatan_kekurangan`, `percakapan_wa`) VALUES
(1, 1, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `perusahaan`
--

CREATE TABLE `perusahaan` (
  `id` int NOT NULL,
  `nama_perusahaan` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat_perusahaan` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `telp_perusahaan` varchar(35) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `bg_kwitansi` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `kop_surat` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `kota_penandatangan` varchar(35) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_penandatangan` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `jabatan_penandatangan` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_mengetahui` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `perusahaan`
--

INSERT INTO `perusahaan` (`id`, `nama_perusahaan`, `alamat_perusahaan`, `telp_perusahaan`, `bg_kwitansi`, `kop_surat`, `kota_penandatangan`, `nama_penandatangan`, `jabatan_penandatangan`, `nama_mengetahui`) VALUES
(2, 'PT. DWIJAYA NUSANTARA PROPERTI', 'JL. MARSDA SURYA DARMA NO.78 RT.19 KEL. KENALI ASAM KEC. KOTA BARU', '0821 7360 373', '', '', 'JAMBI', 'Ayu Lestari', 'Admin', 'Zulfa Dewita');

-- --------------------------------------------------------

--
-- Table structure for table `pindah_unit`
--

CREATE TABLE `pindah_unit` (
  `id` int NOT NULL,
  `tgl_pindah` date NOT NULL,
  `id_customer` int NOT NULL,
  `id_kavling_lama` int NOT NULL,
  `id_kavling_baru` int NOT NULL,
  `nominal_utj` int NOT NULL,
  `biaya_admin` int NOT NULL,
  `keterangan_pindah` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_bank` int NOT NULL,
  `id_metode_bayar` int NOT NULL,
  `lampiran_bukti` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `piutang`
--

CREATE TABLE `piutang` (
  `id` int NOT NULL,
  `id_bank` int NOT NULL DEFAULT '0',
  `id_customer` int NOT NULL DEFAULT '0',
  `tanggal_piutang` date NOT NULL,
  `deskripsi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nominal` int NOT NULL,
  `lampiran` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int NOT NULL,
  `terbayar` int NOT NULL,
  `sisa_bayar` int NOT NULL,
  `tgl_pelunasan` date DEFAULT NULL,
  `id_kategori_transaksi` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `piutang`
--

INSERT INTO `piutang` (`id`, `id_bank`, `id_customer`, `tanggal_piutang`, `deskripsi`, `nominal`, `lampiran`, `status`, `terbayar`, `sisa_bayar`, `tgl_pelunasan`, `id_kategori_transaksi`) VALUES
(1, 1, 1, '2026-07-05', 'Harga Rumah tipe  DE ALASKA RESIDENCE Blok B-04', 173000000, '', 1, 1000000, 172000000, NULL, 0),
(2, 1, 1, '2026-07-05', 'Biaya Surat Rumah tipe  DE ALASKA RESIDENCE Blok B-04', 10000000, '', 1, 0, 10000000, NULL, 0),
(3, 1, 1, '2026-07-05', 'Biaya Peningkatan Mutu Rumah tipe  DE ALASKA RESIDENCE Blok B-04', 10000000, '', 1, 0, 10000000, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `ppjb`
--

CREATE TABLE `ppjb` (
  `id` int NOT NULL,
  `id_customer` int NOT NULL,
  `tanggal_ppjb` date NOT NULL,
  `no_ppjb` varchar(35) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `termin` int NOT NULL DEFAULT '0',
  `sisa_bayar` bigint NOT NULL DEFAULT '0',
  `acc_plafon` bigint NOT NULL DEFAULT '0',
  `bayar_per_bulan` bigint NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `progres_list_pembangunan`
--

CREATE TABLE `progres_list_pembangunan` (
  `id` int NOT NULL,
  `keterangan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `warna` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `progres_list_penjualan`
--

CREATE TABLE `progres_list_penjualan` (
  `id` int NOT NULL,
  `status_progres` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `urutan` int NOT NULL,
  `keterangan` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `warna` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `short_name` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `stt_tampil` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `progres_unit_ready`
--

CREATE TABLE `progres_unit_ready` (
  `id` int NOT NULL,
  `keterangan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `warna` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `prospek_customer`
--

CREATE TABLE `prospek_customer` (
  `id` int NOT NULL,
  `tgl_terima` date NOT NULL,
  `nama_lengkap` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `usia` int NOT NULL,
  `no_telp` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(75) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `pekerjaan` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `penghasilan` bigint NOT NULL,
  `sumber_informasi` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `rangking` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_marketing` int NOT NULL,
  `id_freelance` int NOT NULL,
  `keterangan_belum` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `proyek_bangunan`
--

CREATE TABLE `proyek_bangunan` (
  `id` int NOT NULL,
  `tanggal` date NOT NULL,
  `no_kontrak` varchar(35) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_proyek` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_lokasi` int NOT NULL,
  `nama_pemborong` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipe_rumah` int NOT NULL,
  `harga_satuan` int NOT NULL,
  `nilai_pekerjaan` bigint NOT NULL,
  `id_bank` int NOT NULL,
  `jumlah_unit` int NOT NULL,
  `volume_pekerjaan` int NOT NULL,
  `id_bayar` int NOT NULL,
  `jumlah_termin` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `proyek_bangunan_blok`
--

CREATE TABLE `proyek_bangunan_blok` (
  `id` int NOT NULL,
  `id_proyek_bangunan` int NOT NULL,
  `blok` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `proyek_bangunan_detail`
--

CREATE TABLE `proyek_bangunan_detail` (
  `id` int NOT NULL,
  `id_proyek_bangunan` int NOT NULL,
  `op_ke` int NOT NULL,
  `tanggal` date DEFAULT NULL,
  `persen` decimal(5,2) NOT NULL,
  `nilai_pekerjaan` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `proyek_bangunan_detail_kerja`
--

CREATE TABLE `proyek_bangunan_detail_kerja` (
  `id` int NOT NULL,
  `id_proyek_bangunan` int NOT NULL,
  `id_proyek_bangunan_detail` int NOT NULL,
  `id_jenis_pekerjaan` int NOT NULL,
  `op_lalu` int NOT NULL,
  `op_sekarang` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `proyek_bangunan_unit`
--

CREATE TABLE `proyek_bangunan_unit` (
  `id` int NOT NULL,
  `id_proyek_bangunan` int NOT NULL,
  `kode_kavling` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `proyek_jalan`
--

CREATE TABLE `proyek_jalan` (
  `id` int NOT NULL,
  `tanggal` date NOT NULL,
  `no_kontrak` varchar(35) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_proyek` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_lokasi` int NOT NULL,
  `nama_pemborong` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_jalan` int NOT NULL,
  `harga_satuan` int NOT NULL,
  `nilai_pekerjaan` bigint DEFAULT NULL,
  `id_bank` int NOT NULL,
  `id_bayar` int NOT NULL,
  `jumlah_termin` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `proyek_jalan_detail`
--

CREATE TABLE `proyek_jalan_detail` (
  `id` int NOT NULL,
  `id_proyek_jalan` int NOT NULL,
  `op_ke` int NOT NULL,
  `tanggal` date DEFAULT NULL,
  `persen` decimal(5,2) NOT NULL,
  `nilai_pekerjaan` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `proyek_jalan_detail_kerja`
--

CREATE TABLE `proyek_jalan_detail_kerja` (
  `id` int NOT NULL,
  `id_proyek_jalan` int NOT NULL,
  `id_proyek_jalan_detail` int NOT NULL,
  `id_jenis_pekerjaan` int NOT NULL,
  `op_lalu` int NOT NULL,
  `op_sekarang` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `proyek_saluran`
--

CREATE TABLE `proyek_saluran` (
  `id` int NOT NULL,
  `tanggal` date NOT NULL,
  `no_kontrak` varchar(35) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_proyek` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_lokasi` int NOT NULL,
  `nama_pemborong` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_saluran` int NOT NULL,
  `harga_satuan` int NOT NULL,
  `nilai_pekerjaan` bigint NOT NULL,
  `id_bank` int NOT NULL,
  `id_bayar` int NOT NULL,
  `jumlah_termin` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `proyek_saluran_detail`
--

CREATE TABLE `proyek_saluran_detail` (
  `id` int NOT NULL,
  `id_proyek_saluran` int NOT NULL,
  `op_ke` int NOT NULL,
  `tanggal` date DEFAULT NULL,
  `persen` decimal(5,2) NOT NULL,
  `nilai_pekerjaan` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `proyek_saluran_detail_kerja`
--

CREATE TABLE `proyek_saluran_detail_kerja` (
  `id` int NOT NULL,
  `id_proyek_saluran` int NOT NULL,
  `id_proyek_saluran_detail` int NOT NULL,
  `id_jenis_pekerjaan` int NOT NULL,
  `op_lalu` int NOT NULL,
  `op_sekarang` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rekap_akad`
--

CREATE TABLE `rekap_akad` (
  `id` int NOT NULL,
  `tgl_akad` date NOT NULL,
  `id_customer` int NOT NULL,
  `id_bank` int NOT NULL,
  `keterangan` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `retensi`
--

CREATE TABLE `retensi` (
  `id` bigint UNSIGNED NOT NULL,
  `nama_retensi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `keterangan` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `retensi`
--

INSERT INTO `retensi` (`id`, `nama_retensi`, `keterangan`) VALUES
(1, 'Listrik', 'Listrik'),
(2, 'Air', 'Air'),
(3, 'Bangunan', 'Bangunan');

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `id` int NOT NULL,
  `role` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`id`, `role`) VALUES
(1, 'Admin'),
(2, 'Marketing'),
(3, 'Proyek'),
(4, 'Gudang'),
(5, 'Keuangan'),
(6, 'Legal'),
(7, 'KPR'),
(8, 'Penjualan');

-- --------------------------------------------------------

--
-- Table structure for table `role_user`
--

CREATE TABLE `role_user` (
  `id` int NOT NULL,
  `id_role` int NOT NULL,
  `id_menu` int NOT NULL,
  `lihat` int NOT NULL,
  `beranda` int NOT NULL DEFAULT '0',
  `tambah` int NOT NULL,
  `edit` int NOT NULL,
  `hapus` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_user`
--

INSERT INTO `role_user` (`id`, `id_role`, `id_menu`, `lihat`, `beranda`, `tambah`, `edit`, `hapus`) VALUES
(1, 7, 1, 1, 0, 0, 0, 0),
(2, 6, 1, 1, 0, 0, 0, 0),
(3, 5, 1, 1, 0, 0, 0, 0),
(4, 4, 1, 1, 0, 0, 0, 0),
(5, 3, 1, 1, 0, 0, 0, 0),
(6, 2, 1, 1, 0, 0, 0, 0),
(7, 1, 1, 1, 0, 0, 0, 0),
(8, 7, 2, 1, 1, 0, 0, 0),
(9, 6, 2, 1, 0, 0, 0, 0),
(10, 5, 2, 1, 0, 0, 0, 0),
(11, 4, 2, 1, 0, 0, 0, 0),
(12, 3, 2, 1, 0, 0, 0, 0),
(13, 2, 2, 1, 1, 0, 0, 0),
(14, 1, 2, 1, 0, 0, 0, 0),
(15, 7, 3, 1, 0, 0, 0, 0),
(16, 6, 3, 1, 0, 0, 0, 0),
(17, 5, 3, 1, 0, 0, 0, 0),
(18, 4, 3, 0, 0, 0, 0, 0),
(19, 3, 3, 1, 0, 0, 0, 0),
(20, 2, 3, 1, 0, 0, 0, 0),
(21, 1, 3, 1, 0, 0, 0, 0),
(22, 7, 4, 0, 0, 0, 1, 0),
(23, 6, 4, 0, 0, 0, 1, 0),
(24, 5, 4, 1, 0, 0, 1, 0),
(25, 4, 4, 0, 0, 0, 1, 0),
(26, 3, 4, 1, 0, 0, 1, 0),
(27, 2, 4, 0, 0, 0, 1, 0),
(28, 1, 4, 1, 0, 0, 1, 0),
(29, 7, 5, 0, 0, 0, 1, 1),
(30, 6, 5, 0, 0, 0, 1, 1),
(31, 5, 5, 1, 0, 0, 1, 1),
(32, 4, 5, 0, 0, 0, 0, 0),
(33, 3, 5, 0, 0, 0, 0, 0),
(34, 2, 5, 0, 0, 0, 0, 0),
(35, 1, 5, 1, 0, 0, 1, 1),
(36, 7, 6, 0, 0, 1, 1, 1),
(37, 6, 6, 0, 0, 1, 1, 1),
(38, 5, 6, 1, 0, 1, 1, 1),
(39, 4, 6, 0, 0, 0, 0, 0),
(40, 3, 6, 0, 0, 0, 0, 0),
(41, 2, 6, 0, 0, 1, 1, 1),
(42, 1, 6, 1, 0, 1, 1, 1),
(43, 7, 7, 1, 0, 0, 0, 0),
(44, 6, 7, 0, 0, 0, 0, 0),
(45, 5, 7, 1, 0, 0, 0, 0),
(46, 4, 7, 0, 0, 0, 0, 0),
(47, 3, 7, 0, 0, 0, 0, 0),
(48, 2, 7, 0, 0, 0, 0, 0),
(49, 1, 7, 1, 0, 0, 0, 0),
(50, 7, 8, 1, 0, 1, 1, 1),
(51, 6, 8, 1, 0, 1, 1, 1),
(52, 5, 8, 1, 0, 1, 1, 1),
(53, 4, 8, 0, 0, 0, 0, 0),
(54, 3, 8, 0, 0, 0, 0, 0),
(55, 2, 8, 1, 0, 0, 0, 0),
(56, 1, 8, 1, 0, 1, 1, 1),
(57, 7, 9, 0, 0, 1, 1, 1),
(58, 6, 9, 0, 0, 1, 1, 1),
(59, 5, 9, 1, 0, 1, 1, 1),
(60, 4, 9, 0, 0, 0, 0, 0),
(61, 3, 9, 0, 0, 0, 0, 0),
(62, 2, 9, 0, 0, 1, 1, 1),
(63, 1, 9, 1, 0, 1, 1, 1),
(64, 7, 10, 0, 0, 0, 0, 0),
(65, 6, 10, 0, 0, 0, 0, 0),
(66, 5, 10, 1, 0, 0, 0, 0),
(67, 4, 10, 0, 0, 0, 0, 0),
(68, 3, 10, 1, 0, 0, 0, 0),
(69, 2, 10, 0, 0, 0, 0, 0),
(70, 1, 10, 1, 0, 0, 0, 0),
(71, 7, 11, 0, 0, 0, 0, 0),
(72, 6, 11, 0, 0, 0, 0, 0),
(73, 5, 11, 1, 0, 0, 0, 0),
(74, 4, 11, 0, 0, 0, 0, 0),
(75, 3, 11, 1, 0, 0, 0, 0),
(76, 2, 11, 0, 0, 0, 0, 0),
(77, 1, 11, 1, 0, 0, 0, 0),
(78, 7, 12, 0, 0, 0, 0, 0),
(79, 6, 12, 0, 0, 0, 0, 0),
(80, 5, 12, 1, 0, 0, 0, 0),
(81, 4, 12, 0, 0, 0, 0, 0),
(82, 3, 12, 1, 0, 0, 0, 0),
(83, 2, 12, 0, 0, 0, 0, 0),
(84, 1, 12, 1, 0, 0, 0, 0),
(85, 7, 13, 0, 0, 0, 0, 0),
(86, 6, 13, 1, 0, 0, 0, 0),
(87, 5, 13, 1, 0, 0, 0, 0),
(88, 4, 13, 0, 0, 0, 0, 0),
(89, 3, 13, 0, 0, 0, 0, 0),
(90, 2, 13, 0, 0, 0, 0, 0),
(91, 1, 13, 1, 0, 0, 0, 0),
(92, 7, 14, 0, 0, 0, 0, 0),
(93, 6, 14, 0, 0, 0, 0, 0),
(94, 5, 14, 1, 0, 0, 0, 0),
(95, 4, 14, 0, 0, 0, 0, 0),
(96, 3, 14, 0, 0, 0, 0, 0),
(97, 2, 14, 0, 0, 0, 0, 0),
(98, 1, 14, 1, 0, 0, 0, 0),
(99, 7, 15, 0, 0, 0, 0, 0),
(100, 6, 15, 0, 0, 0, 0, 0),
(101, 5, 15, 1, 0, 0, 0, 0),
(102, 4, 15, 1, 0, 0, 0, 0),
(103, 3, 15, 0, 0, 0, 0, 0),
(104, 2, 15, 0, 0, 0, 0, 0),
(105, 1, 15, 1, 0, 0, 0, 0),
(106, 7, 16, 0, 0, 1, 1, 1),
(107, 6, 16, 0, 0, 1, 1, 1),
(108, 5, 16, 1, 0, 1, 1, 1),
(109, 4, 16, 1, 0, 1, 1, 1),
(110, 3, 16, 0, 0, 0, 0, 0),
(111, 2, 16, 0, 0, 1, 1, 1),
(112, 1, 16, 1, 0, 1, 1, 1),
(113, 7, 17, 0, 0, 0, 0, 0),
(114, 6, 17, 0, 0, 0, 0, 0),
(115, 5, 17, 1, 0, 0, 0, 0),
(116, 4, 17, 1, 0, 0, 0, 0),
(117, 3, 17, 0, 0, 0, 0, 0),
(118, 2, 17, 0, 0, 0, 0, 0),
(119, 1, 17, 1, 0, 0, 0, 0),
(127, 7, 19, 0, 0, 0, 0, 0),
(128, 6, 19, 0, 0, 0, 0, 0),
(129, 5, 19, 1, 0, 0, 0, 0),
(130, 4, 19, 0, 0, 0, 0, 0),
(131, 3, 19, 0, 0, 0, 0, 0),
(132, 2, 19, 0, 0, 0, 0, 0),
(133, 1, 19, 1, 0, 0, 0, 0),
(134, 7, 20, 1, 1, 0, 0, 0),
(135, 6, 20, 1, 0, 0, 0, 0),
(136, 5, 20, 1, 0, 0, 0, 0),
(137, 4, 20, 1, 0, 0, 0, 0),
(138, 3, 20, 0, 0, 0, 0, 0),
(139, 2, 20, 1, 1, 0, 0, 0),
(140, 1, 20, 1, 0, 0, 0, 0),
(141, 7, 21, 1, 1, 0, 0, 0),
(142, 6, 21, 1, 0, 0, 0, 0),
(143, 5, 21, 1, 0, 0, 0, 0),
(144, 4, 21, 1, 0, 0, 0, 0),
(145, 3, 21, 1, 0, 0, 0, 0),
(146, 2, 21, 1, 1, 0, 0, 0),
(147, 1, 21, 1, 0, 0, 0, 0),
(148, 7, 22, 1, 1, 0, 0, 0),
(149, 6, 22, 1, 0, 0, 0, 0),
(150, 5, 22, 1, 0, 0, 0, 0),
(151, 4, 22, 1, 0, 0, 0, 0),
(152, 3, 22, 1, 0, 0, 0, 0),
(153, 2, 22, 1, 1, 0, 0, 0),
(154, 1, 22, 1, 0, 0, 0, 0),
(155, 7, 23, 1, 1, 1, 1, 1),
(156, 6, 23, 0, 0, 1, 1, 1),
(157, 5, 23, 1, 0, 1, 1, 1),
(158, 4, 23, 1, 0, 1, 1, 1),
(159, 3, 23, 1, 0, 1, 1, 1),
(160, 2, 23, 0, 0, 1, 1, 1),
(161, 1, 23, 1, 0, 1, 1, 1),
(162, 7, 24, 1, 1, 1, 1, 1),
(163, 6, 24, 0, 0, 1, 1, 1),
(164, 5, 24, 1, 0, 1, 1, 1),
(165, 4, 24, 1, 0, 1, 1, 1),
(166, 3, 24, 1, 0, 1, 1, 1),
(167, 2, 24, 0, 0, 1, 1, 1),
(168, 1, 24, 1, 0, 1, 1, 1),
(169, 7, 25, 1, 1, 1, 1, 1),
(170, 6, 25, 0, 0, 1, 1, 1),
(171, 5, 25, 1, 0, 1, 1, 1),
(172, 4, 25, 1, 0, 1, 1, 1),
(173, 3, 25, 1, 0, 1, 1, 1),
(174, 2, 25, 0, 0, 1, 1, 1),
(175, 1, 25, 1, 0, 1, 1, 1),
(176, 7, 26, 0, 0, 0, 0, 0),
(177, 6, 26, 0, 0, 1, 1, 1),
(178, 5, 26, 1, 0, 1, 1, 1),
(179, 4, 26, 1, 0, 1, 1, 1),
(180, 3, 26, 1, 0, 1, 1, 1),
(181, 2, 26, 0, 0, 1, 1, 1),
(182, 1, 26, 1, 0, 1, 1, 1),
(183, 7, 27, 0, 0, 1, 1, 1),
(184, 6, 27, 0, 0, 1, 1, 1),
(185, 5, 27, 1, 0, 1, 1, 1),
(186, 4, 27, 1, 0, 1, 1, 1),
(187, 3, 27, 1, 0, 1, 1, 1),
(188, 2, 27, 0, 0, 1, 1, 1),
(189, 1, 27, 1, 0, 1, 1, 1),
(190, 7, 28, 0, 0, 1, 1, 1),
(191, 6, 28, 0, 0, 1, 1, 1),
(192, 5, 28, 1, 0, 1, 1, 1),
(193, 4, 28, 1, 0, 1, 1, 1),
(194, 3, 28, 1, 0, 1, 1, 1),
(195, 2, 28, 0, 0, 1, 1, 1),
(196, 1, 28, 1, 0, 1, 1, 1),
(197, 7, 29, 1, 0, 0, 0, 0),
(198, 6, 29, 1, 1, 0, 0, 0),
(199, 5, 29, 1, 0, 1, 1, 1),
(200, 4, 29, 1, 0, 1, 1, 1),
(201, 3, 29, 1, 0, 1, 1, 1),
(202, 2, 29, 1, 0, 0, 0, 0),
(203, 1, 29, 1, 0, 1, 1, 1),
(204, 7, 30, 0, 0, 1, 1, 1),
(205, 6, 30, 0, 0, 1, 1, 1),
(206, 5, 30, 1, 0, 1, 1, 1),
(207, 4, 30, 1, 0, 1, 1, 1),
(208, 3, 30, 1, 0, 1, 1, 1),
(209, 2, 30, 0, 0, 1, 1, 1),
(210, 1, 30, 1, 0, 1, 1, 1),
(211, 7, 31, 1, 0, 1, 1, 1),
(212, 6, 31, 0, 0, 1, 1, 1),
(213, 5, 31, 1, 0, 1, 1, 1),
(214, 4, 31, 1, 0, 1, 1, 1),
(215, 3, 31, 1, 0, 1, 1, 1),
(216, 2, 31, 0, 0, 1, 1, 1),
(217, 1, 31, 1, 0, 1, 1, 1),
(218, 7, 32, 0, 0, 0, 0, 1),
(219, 6, 32, 0, 0, 0, 0, 1),
(220, 5, 32, 1, 0, 0, 0, 1),
(221, 4, 32, 1, 0, 0, 0, 1),
(222, 3, 32, 1, 0, 0, 0, 1),
(223, 2, 32, 0, 0, 0, 0, 1),
(224, 1, 32, 1, 0, 0, 0, 1),
(225, 7, 33, 0, 0, 1, 1, 1),
(226, 6, 33, 0, 0, 1, 1, 1),
(227, 5, 33, 1, 0, 1, 1, 1),
(228, 4, 33, 1, 0, 1, 1, 1),
(229, 3, 33, 1, 0, 1, 1, 1),
(230, 2, 33, 0, 0, 1, 1, 1),
(231, 1, 33, 1, 0, 1, 1, 1),
(232, 7, 34, 0, 0, 1, 1, 1),
(233, 6, 34, 0, 0, 1, 1, 1),
(234, 5, 34, 1, 0, 1, 1, 1),
(235, 4, 34, 1, 0, 1, 1, 1),
(236, 3, 34, 1, 0, 1, 1, 1),
(237, 2, 34, 0, 0, 1, 1, 1),
(238, 1, 34, 1, 0, 1, 1, 1),
(239, 7, 35, 0, 0, 1, 1, 1),
(240, 6, 35, 0, 0, 1, 1, 1),
(241, 5, 35, 1, 0, 1, 1, 1),
(242, 4, 35, 1, 0, 1, 1, 1),
(243, 3, 35, 1, 0, 1, 1, 1),
(244, 2, 35, 0, 0, 1, 1, 1),
(245, 1, 35, 1, 0, 1, 1, 1),
(246, 7, 36, 0, 0, 1, 1, 1),
(247, 6, 36, 0, 0, 1, 1, 1),
(248, 5, 36, 1, 0, 1, 1, 1),
(249, 4, 36, 1, 0, 1, 1, 1),
(250, 3, 36, 1, 0, 1, 1, 1),
(251, 2, 36, 0, 0, 1, 1, 1),
(252, 1, 36, 1, 0, 1, 1, 1),
(253, 7, 37, 0, 0, 1, 1, 1),
(254, 6, 37, 0, 0, 1, 1, 1),
(255, 5, 37, 1, 0, 1, 1, 1),
(256, 4, 37, 1, 0, 1, 1, 1),
(257, 3, 37, 1, 0, 1, 1, 1),
(258, 2, 37, 0, 0, 1, 1, 1),
(259, 1, 37, 1, 0, 1, 1, 1),
(260, 7, 38, 0, 0, 1, 1, 1),
(261, 6, 38, 0, 0, 1, 1, 1),
(262, 5, 38, 1, 0, 1, 1, 1),
(263, 4, 38, 1, 0, 1, 1, 1),
(264, 3, 38, 1, 0, 1, 1, 1),
(265, 2, 38, 0, 0, 1, 1, 1),
(266, 1, 38, 1, 0, 1, 1, 1),
(267, 7, 39, 0, 0, 1, 1, 1),
(268, 6, 39, 0, 0, 1, 1, 1),
(269, 5, 39, 1, 0, 1, 1, 1),
(270, 4, 39, 1, 0, 1, 1, 1),
(271, 3, 39, 1, 0, 1, 1, 1),
(272, 2, 39, 0, 0, 1, 1, 1),
(273, 1, 39, 1, 0, 1, 1, 1),
(274, 7, 40, 0, 0, 1, 1, 1),
(275, 6, 40, 0, 0, 1, 1, 1),
(276, 5, 40, 1, 0, 1, 1, 1),
(277, 4, 40, 1, 0, 1, 1, 1),
(278, 3, 40, 1, 0, 1, 1, 1),
(279, 2, 40, 0, 0, 1, 1, 1),
(280, 1, 40, 1, 0, 1, 1, 1),
(281, 7, 41, 0, 0, 1, 1, 1),
(282, 6, 41, 0, 0, 1, 1, 1),
(283, 5, 41, 1, 0, 1, 1, 1),
(284, 4, 41, 1, 0, 1, 1, 1),
(285, 3, 41, 1, 0, 1, 1, 1),
(286, 2, 41, 0, 0, 1, 1, 1),
(287, 1, 41, 1, 0, 1, 1, 1),
(288, 7, 42, 0, 0, 1, 1, 1),
(289, 6, 42, 0, 0, 1, 1, 1),
(290, 5, 42, 1, 0, 1, 1, 1),
(291, 4, 42, 1, 0, 1, 1, 1),
(292, 3, 42, 1, 0, 1, 1, 1),
(293, 2, 42, 0, 0, 1, 1, 1),
(294, 1, 42, 1, 0, 1, 1, 1),
(295, 7, 43, 0, 0, 1, 1, 1),
(296, 6, 43, 0, 0, 1, 1, 1),
(297, 5, 43, 1, 0, 1, 1, 1),
(298, 4, 43, 1, 0, 1, 1, 1),
(299, 3, 43, 1, 0, 1, 1, 1),
(300, 2, 43, 0, 0, 1, 1, 1),
(301, 1, 43, 1, 0, 1, 1, 1),
(302, 7, 44, 0, 0, 1, 1, 1),
(303, 6, 44, 0, 0, 1, 1, 1),
(304, 5, 44, 1, 0, 1, 1, 1),
(305, 4, 44, 1, 0, 1, 1, 1),
(306, 3, 44, 1, 0, 1, 1, 1),
(307, 2, 44, 0, 0, 1, 1, 1),
(308, 1, 44, 1, 0, 1, 1, 1),
(309, 7, 45, 0, 0, 1, 1, 1),
(310, 6, 45, 1, 1, 1, 1, 1),
(311, 5, 45, 1, 0, 1, 1, 1),
(312, 4, 45, 1, 0, 1, 1, 1),
(313, 3, 45, 1, 0, 1, 1, 1),
(314, 2, 45, 0, 0, 1, 1, 1),
(315, 1, 45, 1, 0, 1, 1, 1),
(316, 7, 46, 0, 0, 1, 1, 1),
(317, 6, 46, 1, 1, 1, 1, 1),
(318, 5, 46, 1, 0, 1, 1, 1),
(319, 4, 46, 1, 0, 1, 1, 1),
(320, 3, 46, 1, 0, 1, 1, 1),
(321, 2, 46, 0, 0, 1, 1, 1),
(322, 1, 46, 1, 0, 1, 1, 1),
(323, 7, 47, 0, 0, 1, 1, 1),
(324, 6, 47, 0, 0, 1, 1, 1),
(325, 5, 47, 1, 0, 1, 1, 1),
(326, 4, 47, 1, 0, 1, 1, 1),
(327, 3, 47, 1, 0, 1, 1, 1),
(328, 2, 47, 0, 0, 1, 1, 1),
(329, 1, 47, 1, 0, 1, 1, 1),
(330, 7, 48, 0, 0, 1, 1, 1),
(331, 6, 48, 0, 0, 1, 1, 1),
(332, 5, 48, 1, 0, 1, 1, 1),
(333, 4, 48, 1, 0, 1, 1, 1),
(334, 3, 48, 1, 0, 1, 1, 1),
(335, 2, 48, 0, 0, 1, 1, 1),
(336, 1, 48, 1, 0, 1, 1, 1),
(337, 7, 49, 0, 0, 1, 1, 1),
(338, 6, 49, 0, 0, 1, 1, 1),
(339, 5, 49, 1, 0, 1, 1, 1),
(340, 4, 49, 1, 0, 1, 1, 1),
(341, 3, 49, 1, 0, 1, 1, 1),
(342, 2, 49, 0, 0, 1, 1, 1),
(343, 1, 49, 1, 0, 1, 1, 1),
(344, 7, 50, 0, 0, 1, 1, 1),
(345, 6, 50, 0, 0, 1, 1, 1),
(346, 5, 50, 1, 0, 1, 1, 1),
(347, 4, 50, 1, 0, 1, 1, 1),
(348, 3, 50, 1, 0, 1, 1, 1),
(349, 2, 50, 0, 0, 1, 1, 1),
(350, 1, 50, 1, 0, 1, 1, 1),
(351, 7, 51, 0, 0, 1, 1, 1),
(352, 6, 51, 0, 0, 1, 1, 1),
(353, 5, 51, 1, 0, 1, 1, 1),
(354, 4, 51, 1, 0, 1, 1, 1),
(355, 3, 51, 1, 0, 1, 1, 1),
(356, 2, 51, 0, 0, 1, 1, 1),
(357, 1, 51, 1, 0, 1, 1, 1),
(358, 7, 52, 0, 0, 1, 1, 1),
(359, 6, 52, 0, 0, 1, 1, 1),
(360, 5, 52, 1, 0, 1, 1, 1),
(361, 4, 52, 1, 0, 1, 1, 1),
(362, 3, 52, 1, 0, 1, 1, 1),
(363, 2, 52, 0, 0, 1, 1, 1),
(364, 1, 52, 1, 0, 1, 1, 1),
(365, 7, 53, 0, 0, 0, 0, 0),
(366, 6, 53, 0, 0, 0, 0, 0),
(367, 5, 53, 1, 0, 0, 0, 0),
(368, 4, 53, 1, 0, 0, 0, 0),
(369, 3, 53, 1, 0, 0, 0, 0),
(370, 2, 53, 0, 0, 0, 0, 0),
(371, 1, 53, 1, 0, 0, 0, 0),
(372, 7, 54, 0, 0, 1, 1, 1),
(373, 6, 54, 0, 0, 1, 1, 1),
(374, 5, 54, 1, 0, 1, 1, 1),
(375, 4, 54, 1, 0, 1, 1, 1),
(376, 3, 54, 1, 0, 1, 1, 1),
(377, 2, 54, 0, 0, 1, 1, 1),
(378, 1, 54, 1, 0, 1, 1, 1),
(379, 7, 55, 0, 0, 1, 1, 1),
(380, 6, 55, 0, 0, 1, 1, 1),
(381, 5, 55, 1, 0, 1, 1, 1),
(382, 4, 55, 1, 0, 1, 1, 1),
(383, 3, 55, 1, 0, 1, 1, 1),
(384, 2, 55, 0, 0, 1, 1, 1),
(385, 1, 55, 1, 0, 1, 1, 1),
(386, 7, 56, 0, 0, 1, 1, 1),
(387, 6, 56, 0, 0, 1, 1, 1),
(388, 5, 56, 1, 0, 1, 1, 1),
(389, 4, 56, 0, 0, 0, 0, 0),
(390, 3, 56, 1, 0, 1, 1, 1),
(391, 2, 56, 0, 0, 1, 1, 1),
(392, 1, 56, 1, 0, 1, 1, 1),
(393, 7, 57, 0, 0, 1, 1, 1),
(394, 6, 57, 0, 0, 1, 1, 1),
(395, 5, 57, 1, 0, 1, 1, 1),
(396, 4, 57, 0, 0, 0, 0, 0),
(397, 3, 57, 1, 0, 1, 1, 1),
(398, 2, 57, 0, 0, 1, 1, 1),
(399, 1, 57, 1, 0, 1, 1, 1),
(400, 7, 58, 0, 0, 1, 1, 1),
(401, 6, 58, 0, 0, 1, 1, 1),
(402, 5, 58, 1, 0, 1, 1, 1),
(403, 4, 58, 1, 0, 1, 1, 1),
(404, 3, 58, 1, 0, 1, 1, 1),
(405, 2, 58, 0, 0, 1, 1, 1),
(406, 1, 58, 1, 0, 1, 1, 1),
(407, 7, 59, 0, 0, 1, 1, 1),
(408, 6, 59, 0, 0, 1, 1, 1),
(409, 5, 59, 1, 0, 1, 1, 1),
(410, 4, 59, 1, 0, 1, 1, 1),
(411, 3, 59, 1, 0, 1, 1, 1),
(412, 2, 59, 0, 0, 1, 1, 1),
(413, 1, 59, 1, 0, 1, 1, 1),
(414, 7, 60, 0, 0, 1, 1, 1),
(415, 6, 60, 0, 0, 1, 1, 1),
(416, 5, 60, 1, 0, 1, 1, 1),
(417, 4, 60, 1, 0, 1, 1, 1),
(418, 3, 60, 1, 0, 1, 1, 1),
(419, 2, 60, 0, 0, 1, 1, 1),
(420, 1, 60, 1, 0, 1, 1, 1),
(421, 7, 61, 0, 0, 1, 1, 1),
(422, 6, 61, 0, 0, 1, 1, 1),
(423, 5, 61, 1, 0, 1, 1, 1),
(424, 4, 61, 0, 0, 0, 0, 0),
(425, 3, 61, 1, 0, 1, 1, 1),
(426, 2, 61, 0, 0, 1, 1, 1),
(427, 1, 61, 1, 0, 1, 1, 1),
(442, 7, 64, 0, 0, 0, 1, 0),
(443, 6, 64, 0, 0, 0, 1, 0),
(444, 5, 64, 1, 0, 0, 1, 0),
(445, 4, 64, 1, 0, 0, 1, 0),
(446, 3, 64, 1, 0, 0, 1, 0),
(447, 2, 64, 0, 0, 0, 1, 0),
(448, 1, 64, 1, 0, 0, 1, 0),
(449, 7, 65, 0, 0, 1, 1, 1),
(450, 6, 65, 0, 0, 1, 1, 1),
(451, 5, 65, 1, 0, 1, 1, 1),
(452, 4, 65, 1, 0, 1, 1, 1),
(453, 3, 65, 1, 0, 1, 1, 1),
(454, 2, 65, 0, 0, 1, 1, 1),
(455, 1, 65, 1, 0, 1, 1, 1),
(456, 7, 66, 0, 0, 1, 1, 1),
(457, 6, 66, 0, 0, 1, 1, 1),
(458, 5, 66, 1, 0, 1, 1, 1),
(459, 4, 66, 1, 0, 1, 1, 1),
(460, 3, 66, 1, 0, 1, 1, 1),
(461, 2, 66, 0, 0, 1, 1, 1),
(462, 1, 66, 1, 0, 1, 1, 1),
(463, 7, 67, 0, 0, 0, 1, 0),
(464, 6, 67, 0, 0, 0, 1, 0),
(465, 5, 67, 1, 0, 0, 1, 0),
(466, 4, 67, 1, 0, 0, 1, 0),
(467, 3, 67, 1, 0, 0, 1, 0),
(468, 2, 67, 0, 0, 0, 1, 0),
(469, 1, 67, 1, 0, 0, 1, 0),
(470, 7, 68, 0, 0, 1, 1, 1),
(471, 6, 68, 0, 0, 1, 1, 1),
(472, 5, 68, 1, 0, 1, 1, 1),
(473, 4, 68, 1, 0, 1, 1, 1),
(474, 3, 68, 1, 0, 1, 1, 1),
(475, 2, 68, 0, 0, 1, 1, 1),
(476, 1, 68, 1, 0, 1, 1, 1),
(477, 7, 69, 0, 0, 1, 1, 1),
(478, 6, 69, 0, 0, 1, 1, 1),
(479, 5, 69, 1, 0, 1, 1, 1),
(480, 4, 69, 1, 0, 1, 1, 1),
(481, 3, 69, 1, 0, 1, 1, 1),
(482, 2, 69, 0, 0, 1, 1, 1),
(483, 1, 69, 1, 0, 1, 1, 1),
(484, 7, 70, 0, 0, 0, 1, 0),
(485, 6, 70, 0, 0, 0, 1, 0),
(486, 5, 70, 1, 0, 0, 1, 0),
(487, 4, 70, 1, 0, 0, 1, 0),
(488, 3, 70, 1, 0, 0, 1, 0),
(489, 2, 70, 0, 0, 0, 1, 0),
(490, 1, 70, 1, 0, 0, 1, 0),
(491, 7, 71, 0, 0, 0, 0, 0),
(492, 6, 71, 0, 0, 0, 0, 0),
(493, 5, 71, 1, 0, 1, 1, 1),
(494, 4, 71, 0, 0, 0, 0, 0),
(495, 3, 71, 0, 0, 0, 0, 0),
(496, 2, 71, 0, 0, 0, 0, 0),
(497, 1, 71, 1, 0, 1, 1, 1),
(498, 7, 72, 0, 0, 0, 0, 0),
(499, 6, 72, 0, 0, 0, 0, 0),
(500, 5, 72, 1, 0, 1, 1, 1),
(501, 4, 72, 0, 0, 0, 0, 0),
(502, 3, 72, 0, 0, 0, 0, 0),
(503, 2, 72, 0, 0, 0, 0, 0),
(504, 1, 72, 1, 0, 1, 1, 1),
(512, 1, 73, 1, 0, 1, 1, 1),
(513, 2, 73, 1, 0, 1, 1, 1),
(514, 3, 73, 1, 0, 1, 1, 1),
(515, 4, 73, 1, 0, 1, 1, 1),
(516, 5, 73, 1, 0, 1, 1, 1),
(517, 6, 73, 1, 0, 1, 1, 1),
(518, 7, 73, 1, 0, 1, 1, 1),
(519, 1, 74, 1, 0, 1, 1, 1),
(520, 2, 74, 0, 0, 0, 0, 0),
(521, 3, 74, 0, 0, 0, 0, 0),
(522, 4, 74, 0, 0, 0, 0, 0),
(523, 5, 74, 0, 0, 0, 0, 0),
(524, 6, 74, 0, 0, 0, 0, 0),
(525, 7, 74, 0, 0, 0, 0, 0),
(526, 1, 75, 1, 0, 1, 1, 1),
(527, 2, 75, 1, 0, 1, 1, 1),
(528, 3, 75, 1, 0, 1, 1, 1),
(529, 4, 75, 1, 0, 1, 1, 1),
(530, 5, 75, 1, 0, 1, 1, 1),
(531, 6, 75, 1, 0, 1, 1, 1),
(532, 7, 75, 1, 0, 1, 1, 1),
(540, 1, 78, 1, 0, 0, 0, 0),
(541, 2, 78, 1, 0, 0, 0, 0),
(542, 3, 78, 1, 0, 0, 0, 0),
(543, 4, 78, 1, 0, 0, 0, 0),
(544, 5, 78, 1, 0, 0, 0, 0),
(545, 6, 78, 1, 0, 0, 0, 0),
(546, 7, 78, 1, 0, 0, 0, 0),
(547, 8, 78, 1, 0, 0, 0, 0),
(548, 1, 79, 1, 0, 0, 0, 0),
(549, 2, 79, 1, 0, 0, 0, 0),
(550, 3, 79, 1, 0, 0, 0, 0),
(551, 4, 79, 1, 0, 0, 0, 0),
(552, 5, 79, 1, 0, 0, 0, 0),
(553, 6, 79, 1, 0, 0, 0, 0),
(554, 7, 79, 1, 0, 0, 0, 0),
(555, 8, 79, 1, 0, 0, 0, 0),
(556, 1, 80, 1, 0, 1, 1, 1),
(557, 2, 80, 1, 0, 1, 1, 1),
(558, 3, 80, 1, 0, 1, 1, 1),
(559, 4, 80, 1, 0, 1, 1, 1),
(560, 5, 80, 1, 0, 1, 1, 1),
(561, 6, 80, 1, 0, 1, 1, 1),
(562, 7, 80, 1, 0, 1, 1, 1),
(563, 8, 80, 1, 0, 1, 1, 1),
(564, 1, 82, 1, 0, 1, 1, 1),
(565, 2, 82, 1, 0, 1, 1, 1),
(566, 3, 82, 1, 0, 1, 1, 1),
(567, 4, 82, 1, 0, 1, 1, 1),
(568, 5, 82, 1, 0, 1, 1, 1),
(569, 6, 82, 1, 0, 1, 1, 1),
(570, 7, 82, 1, 0, 1, 1, 1),
(571, 8, 82, 1, 0, 1, 1, 1),
(572, 1, 84, 1, 0, 0, 0, 0),
(573, 2, 84, 1, 0, 0, 0, 0),
(574, 3, 84, 1, 0, 0, 0, 0),
(575, 4, 84, 1, 0, 0, 0, 0),
(576, 5, 84, 1, 0, 0, 0, 0),
(577, 6, 84, 1, 0, 0, 0, 0),
(578, 7, 84, 1, 0, 0, 0, 0),
(579, 8, 84, 1, 0, 0, 0, 0),
(580, 1, 85, 1, 0, 0, 0, 0),
(581, 2, 85, 1, 0, 0, 0, 0),
(582, 3, 85, 1, 0, 0, 0, 0),
(583, 4, 85, 1, 0, 0, 0, 0),
(584, 5, 85, 1, 0, 0, 0, 0),
(585, 6, 85, 1, 0, 0, 0, 0),
(586, 7, 85, 1, 0, 0, 0, 0),
(587, 8, 85, 1, 0, 0, 0, 0),
(588, 1, 86, 1, 0, 1, 1, 1),
(589, 2, 86, 1, 0, 1, 1, 1),
(590, 3, 86, 1, 0, 1, 1, 1),
(591, 4, 86, 1, 0, 1, 1, 1),
(592, 5, 86, 1, 0, 1, 1, 1),
(593, 6, 86, 1, 0, 1, 1, 1),
(594, 7, 86, 1, 0, 1, 1, 1),
(595, 8, 86, 1, 0, 1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `saluran`
--

CREATE TABLE `saluran` (
  `id` int NOT NULL,
  `nama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_lokasi` int NOT NULL,
  `id_jalan` int NOT NULL,
  `panjang` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `satuan`
--

CREATE TABLE `satuan` (
  `id` int NOT NULL,
  `nama_satuan` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `serah_terima_kunci`
--

CREATE TABLE `serah_terima_kunci` (
  `id` int NOT NULL,
  `id_customer` int NOT NULL,
  `tgl_serah_terima` date NOT NULL,
  `tgl_expired` date NOT NULL,
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('ksZWN1s6BlxKg0wHqcERGXtC0BBH8vyxBGFRHYwn', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:152.0) Gecko/20100101 Firefox/152.0', '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IgAqAGNoYW5nZXMiO2E6MDp7fXM6ODoiACoAY2FzdHMiO2E6MDp7fXM6MTc6IgAqAGNsYXNzQ2FzdENhY2hlIjthOjA6e31zOjIxOiIAKgBhdHRyaWJ1dGVDYXN0Q2FjaGUiO2E6MDp7fXM6MTM6IgAqAGRhdGVGb3JtYXQiO047czoxMDoiACoAYXBwZW5kcyI7YTowOnt9czoxOToiACoAZGlzcGF0Y2hlc0V2ZW50cyI7YTowOnt9czoxNDoiACoAb2JzZXJ2YWJsZXMiO2E6MDp7fXM6MTI6IgAqAHJlbGF0aW9ucyI7YTowOnt9czoxMDoiACoAdG91Y2hlcyI7YTowOnt9czoxMDoidGltZXN0YW1wcyI7YjoxO3M6MTM6InVzZXNVbmlxdWVJZHMiO2I6MDtzOjk6IgAqAGhpZGRlbiI7YTowOnt9czoxMDoiACoAdmlzaWJsZSI7YTowOnt9czoxMToiACoAZmlsbGFibGUiO2E6OTp7aTowO3M6OToiaWRfcGFyZW50IjtpOjE7czo1OiJ0aXRsZSI7aToyO3M6MTA6InJvdXRlX25hbWUiO2k6MztzOjQ6Imljb24iO2k6NDtzOjY6InVydXRhbiI7aTo1O3M6NToibGloYXQiO2k6NjtzOjY6InRhbWJhaCI7aTo3O3M6NDoiZWRpdCI7aTo4O3M6NToiaGFwdXMiO31zOjEwOiIAKgBndWFyZGVkIjthOjE6e2k6MDtzOjE6IioiO319fXM6Mjg6IgAqAGVzY2FwZVdoZW5DYXN0aW5nVG9TdHJpbmciO2I6MDt9fXM6MTA6IgAqAHRvdWNoZXMiO2E6MDp7fXM6MTA6InRpbWVzdGFtcHMiO2I6MTtzOjEzOiJ1c2VzVW5pcXVlSWRzIjtiOjA7czo5OiIAKgBoaWRkZW4iO2E6MDp7fXM6MTA6IgAqAHZpc2libGUiO2E6MDp7fXM6MTE6IgAqAGZpbGxhYmxlIjthOjk6e2k6MDtzOjk6ImlkX3BhcmVudCI7aToxO3M6NToidGl0bGUiO2k6MjtzOjEwOiJyb3V0ZV9uYW1lIjtpOjM7czo0OiJpY29uIjtpOjQ7czo2OiJ1cnV0YW4iO2k6NTtzOjU6ImxpaGF0IjtpOjY7czo2OiJ0YW1iYWgiO2k6NztzOjQ6ImVkaXQiO2k6ODtzOjU6ImhhcHVzIjt9czoxMDoiACoAZ3VhcmRlZCI7YToxOntpOjA7czoxOiIqIjt9fX1zOjI4OiIAKgBlc2NhcGVXaGVuQ2FzdGluZ1RvU3RyaW5nIjtiOjA7fXM6NDoibmFtYSI7czoxNjoiZmFpc2FsIGRhbWFuaWsgQSI7czo2OiJsb2thc2kiO3M6MTk6IkRFIEFMQVNLQSBSRVNJREVOQ0UiO3M6NDoiYmxvayI7czo0OiJCLTA0IjtzOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1783241947);

-- --------------------------------------------------------

--
-- Table structure for table `sppr`
--

CREATE TABLE `sppr` (
  `id` bigint UNSIGNED NOT NULL,
  `id_customer` int NOT NULL,
  `no_sppr` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nik` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_telp` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `luas_bangunan` bigint NOT NULL,
  `luas_tanah` bigint NOT NULL,
  `blok` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `no` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `harga_jual` bigint NOT NULL,
  `asumsi_plafon_kpr` bigint NOT NULL,
  `biaya_surat_surat` bigint NOT NULL,
  `peningkatan_mutu` bigint NOT NULL,
  `biaya_kelebihan_tanah` bigint DEFAULT NULL,
  `biaya_sudut` bigint DEFAULT NULL,
  `biaya_lain_lain` bigint DEFAULT NULL,
  `total_yang_harus_dibayar` bigint NOT NULL,
  `jumlah_booking_fee` bigint NOT NULL,
  `cicilan_per_bulan` bigint NOT NULL,
  `id_marketing` int DEFAULT NULL,
  `penandatangan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `spr`
--

CREATE TABLE `spr` (
  `id` int NOT NULL,
  `id_customer` int NOT NULL,
  `id_kavling` int NOT NULL,
  `kode_kavling` varchar(35) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipe_rumah` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `luas_tanah` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_lengkap` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nik` varchar(35) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat_rumah` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_telp` varchar(35) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_keluarga` varchar(75) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `hubungan_keluarga` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_telp_keluarga` varchar(35) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_hp_keluarga` varchar(35) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_perusahaan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat_kantor` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `telp_kantor` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nomor_spr` varchar(35) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_spr` date NOT NULL,
  `jam_spr` time NOT NULL,
  `harga_rumah` bigint NOT NULL,
  `harga_diskon` int NOT NULL,
  `booking_fee_spr` bigint NOT NULL,
  `nomor_va` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `catatan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_akad` date NOT NULL,
  `nomor_akad` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_akad` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `catatan_akad` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_ppjb` date NOT NULL,
  `nomor_ppjb` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_ppjb` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `catatan_ppjb` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_spr` int NOT NULL,
  `stt_akad` int NOT NULL,
  `id_marketing` int NOT NULL,
  `nama_marketing` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `stt_keterangan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `id` int NOT NULL,
  `kode` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_telp` varchar(35) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `keterangan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `template_pesan`
--

CREATE TABLE `template_pesan` (
  `id` int NOT NULL,
  `nama_template` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `isi_template` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis_pesan` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `throttle`
--

CREATE TABLE `throttle` (
  `id` int UNSIGNED NOT NULL,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ip` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `upload_file`
--

CREATE TABLE `upload_file` (
  `id` int NOT NULL,
  `tanggal` date NOT NULL,
  `id_customer` int NOT NULL,
  `nama_file` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `keterangan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `lampiran` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `upload_file`
--

INSERT INTO `upload_file` (`id`, `tanggal`, `id_customer`, `nama_file`, `keterangan`, `lampiran`) VALUES
(1, '2026-07-05', 1, 'Foto KTP', '', 'ktp_1783229093.webp');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `surname` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('AKTIF','BLOKIR') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'AKTIF',
  `id_role` int NOT NULL DEFAULT '0',
  `id_marketing` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `surname`, `username`, `password`, `email`, `status`, `id_role`, `id_marketing`) VALUES
(1, 'SUPERADMIN', 'master', '$2y$12$ghmj5MrDbme09n9ZO4Q2fOQMqFQRIoeCHiGsjKBZqAiGemM7JeCUq', 'demo@example.com', 'AKTIF', 1, 0),
(4, 'proyek', 'proyek', '$2y$12$jfu/OhaeHx7nMyxoOMMGduDAVZcFeor2hqR9q0Tk8Ri8DeWElXLha', 'proyek@mail.com', 'AKTIF', 3, 0),
(6, 'dev', 'dev', '$2y$12$ghmj5MrDbme09n9ZO4Q2fOQMqFQRIoeCHiGsjKBZqAiGemM7JeCUq', 'dev@gia.com', 'AKTIF', 1, 0),
(7, 'Budi Sudarsono', 'marketing_1', '$2y$12$CRCw8RMPztfJLyRu59bfbeceYh156Zpqiqjven5jx8vJWUYg0hp1a', 'budi@gmail.com', 'AKTIF', 2, 1),
(8, 'kpr', 'admin_kpr', '$2y$12$LynrYK/mtdvoufPRSwWyeO7NJno.0ILuQBN5Drk4mcj9kR86Bn2ra', 'kpr@gmail.com', 'AKTIF', 7, 0),
(9, 'legal', 'admin_legal', '$2y$12$a8BPlfN4XPpBPTH7bwTZE.Swuk9z/pN4VK5aDWmFgNSQzenLnhKYm', 'legal@gmail.com', 'AKTIF', 6, 0),
(10, 'Admin Gudang', 'gudang', '$2y$12$6GbQ.XmjBg/2tJNmuU/QRefVXwzqW8RBJd5i58oJa27832ovRjbMy', 'gudang@gmail.com', 'AKTIF', 4, 0),
(11, 'Keuangan', 'keuangan', '$2y$12$jnwZOfZo4rXTn/ZAqNmvZujlfzRlEj.ZFf/VHm7vesG3fCkJiSe5S', 'keuangan@gmail.com', 'AKTIF', 5, 0),
(12, 'Tri Hartati', 'tri', '$2y$12$9QMm1P.Rqmj9UDeycIYo7Os6XsaVeeXLhDOuuu/RkkH.PuOBSoN4q', 'ri@gmail.com', 'AKTIF', 1, 0),
(13, 'Intan', 'intan', '$2y$12$ZW0UJlcL06VmO5h6xPcncO1N4vcnFmoQbwfsPqgZKOiYghniyOHIC', 'intan@gmail.com', 'AKTIF', 1, 0),
(14, 'Desra Sinaga', 'desra', '$2y$12$3VTgtSKDtCrV5NPCYvof8OxVEL.B9ziAwyxfsyHjbv3g47Byy0Hj.', 'desra@gmail.com', 'AKTIF', 5, 0),
(15, 'Fandy', 'fandy', '$2y$12$vr10QlgtFfQpfMq2CTL4POydrV4PdHNGeBEu62cc8ejP/9si4BZy6', 'fandy@gmail.com', 'AKTIF', 7, 0),
(16, 'Fadila', 'fadila', '$2y$12$GvyxXrJdB7S/RvEdQPuNyO0xbfaXeVA3YG0r4dYLzcneuTLumOHy6', 'fadila@gmail.com', 'AKTIF', 7, 0),
(17, 'Samuel', 'samuel', '$2y$12$JQ/46kJEbP0azKMGnmrePeIBj2ATEhmPBuqGV9wxFdwFgI4FvxK7S', 'samuel@gmail.com', 'AKTIF', 6, 0),
(18, 'Satria', 'satria', '$2y$12$eQKwvwpL.0Lfr6cuI2fbF.5oOwQ9cQw0QrrN406XVRswqj/ZBqDL6', 'satria@gmail.com', 'AKTIF', 6, 0),
(33, 'Golden Marketing', 'golden_marketing', '$2y$12$4T6Y/KfCv8Wj92mQy9pSE.HP.5i7aG8PK4ED6kne5RWUtwhPTfcjW', 'golden_mkt@email.com', 'AKTIF', 2, 2),
(34, 'Johanes Marketing', 'johanes_marketing', '$2y$12$gkQB126lF1LtYeIg6IX.EegzO.uFKvk6uA8QyVO3u5pAMDZwgH592', 'johanes_mkt@email.com', 'AKTIF', 2, 3),
(35, 'Ilham Marketing', 'ilham_marketing', '$2y$12$IbVKgAeHyuSd/sKpinVzde3CUs7Dk5FMYBx4ghy4BdOY/sdwb4PBC', 'ilham_mkt@email.com', 'AKTIF', 2, 4),
(36, 'Jack Marketing', 'jack_marketing', '$2y$12$WfN4yiM1ICtso4aZKkge4uFnMZ3ZOr/VUTt3E.cmQc7YoH/YnQNK2', 'jack_mkt@email.com', 'AKTIF', 2, 5),
(37, 'Candra Marketing', 'candra_marketing', '$2y$12$X8UWfh.Xi.NL9GpLiAMtaOOgwlXfHwbD1TY2c0.IKStUAIrwQi47W', 'candra_mkt@email.com', 'AKTIF', 2, 6),
(38, 'Lampos Marketing', 'lampos_marketing', '$2y$12$7Z3wTbpyaVuJpgTOK2E7PubUJb7TH6FpFu4RtnpvrsNoWDr9aMFqq', 'lampos_mkt@email.com', 'AKTIF', 2, 7),
(39, 'Angki Aji Marketing', 'angki_aji_marketing', '$2y$12$Ft58flKBvuAddBRvq05TY.HeYwEK.OtG6lRu8UG.UOV/PPIzxDmJe', 'angki_aji_mkt@email.com', 'AKTIF', 2, 8),
(40, 'Jandri Marketing', 'jandri_marketing', '$2y$12$tKcstD.C0cNiMn1.nIUreuTyVr9eXIkLCWAnXW1aExVtjhzIsExh2', 'jandri_mkt@email.com', 'AKTIF', 2, 9),
(41, 'Rahma Marketing', 'rahma_marketing', '$2y$12$cRpGxm.wamXVzkJX.jTBw.753mjte1BqjKrJSQWzT7YyHM4KvdNoe', 'rahma_mkt@email.com', 'AKTIF', 2, 10),
(42, 'Anna Marketing', 'anna_marketing', '$2y$12$pQ41S/hxghPVjjhq7BU5AukURM20ioP1AvQW4vERCvVtLXrSwSyMu', 'anna_mkt@email.com', 'AKTIF', 2, 11),
(43, 'Fika Marketing', 'fika_marketing', '$2y$12$pFwXwPeN6kgdPt/KmgDeVemfp6se0mTE22QULDtizAsgpX0EcfyrS', 'fika_mkt@email.com', 'AKTIF', 2, 12),
(44, 'Vina Marketing', 'vina_marketing', '$2y$12$hXMb4P9hKzZOWduADTaI3eaWwEJOcZOsSY85rE5Fw9BjQj6694P72', 'vina_mkt@email.com', 'AKTIF', 2, 13),
(45, 'Agent Marketing', 'agent_marketing', '$2y$12$pnuoSzSgtqxJJtnPPJpo6eEVv0KWpXRT3oxN8kT0jhgqaO75.f8UO', 'agent_mkt@email.com', 'AKTIF', 2, 14),
(46, 'demo aplikasi', 'demo', '$2y$12$gsgWRWSGnq0s55Krpg6LveNJ3wzpzw1FQ0FsgX/JnY3qFldqkztzW', 'cek@gmail.com', 'AKTIF', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `variable_akad`
--

CREATE TABLE `variable_akad` (
  `id` int NOT NULL,
  `id_jenis` int NOT NULL,
  `jenis` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `kode_var` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `urutan` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wawancara`
--

CREATE TABLE `wawancara` (
  `id` int NOT NULL,
  `tgl_wawancara` date DEFAULT NULL,
  `id_customer` int NOT NULL,
  `catatan_wawancara` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_bank_kpr` int NOT NULL,
  `status` int NOT NULL COMMENT '1. Wawancara, 2.SP3K'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wawancara_sp3k`
--

CREATE TABLE `wawancara_sp3k` (
  `id` int NOT NULL,
  `id_wawancara` int NOT NULL,
  `id_bank_kpr` int NOT NULL,
  `acc_plafon` int NOT NULL,
  `tenor` int NOT NULL,
  `id_notaris` int NOT NULL,
  `tgl_terbit_sp3k` date NOT NULL,
  `no_sp3k` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tgl_expired` date DEFAULT NULL,
  `catatan_acc` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int NOT NULL COMMENT '1. Ready, 2. Expired',
  `lampiran` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `aduan`
--
ALTER TABLE `aduan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `aduan_proses`
--
ALTER TABLE `aduan_proses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `akad`
--
ALTER TABLE `akad`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `balik_nama`
--
ALTER TABLE `balik_nama`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bank`
--
ALTER TABLE `bank`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bank_kpr`
--
ALTER TABLE `bank_kpr`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `barang`
--
ALTER TABLE `barang`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `barang_keluar`
--
ALTER TABLE `barang_keluar`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `barang_keluar_detail`
--
ALTER TABLE `barang_keluar_detail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `barang_masuk`
--
ALTER TABLE `barang_masuk`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `barang_masuk_detail`
--
ALTER TABLE `barang_masuk_detail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bast`
--
ALTER TABLE `bast`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bphtb_ssp`
--
ALTER TABLE `bphtb_ssp`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `content`
--
ALTER TABLE `content`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer_tempo`
--
ALTER TABLE `customer_tempo`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `detail_akad`
--
ALTER TABLE `detail_akad`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `file_aduan`
--
ALTER TABLE `file_aduan`
  ADD PRIMARY KEY (`id_file_aduan`);

--
-- Indexes for table `file_proses_aduan`
--
ALTER TABLE `file_proses_aduan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_aduan` (`id_aduan`);

--
-- Indexes for table `foto_progres`
--
ALTER TABLE `foto_progres`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `foto_proyek_bangunan`
--
ALTER TABLE `foto_proyek_bangunan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `foto_proyek_jalan`
--
ALTER TABLE `foto_proyek_jalan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `foto_proyek_saluran`
--
ALTER TABLE `foto_proyek_saluran`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ganti_nama`
--
ALTER TABLE `ganti_nama`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ganti_namas`
--
ALTER TABLE `ganti_namas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hak_akses`
--
ALTER TABLE `hak_akses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hak_akses_panduan_apk`
--
ALTER TABLE `hak_akses_panduan_apk`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hutang`
--
ALTER TABLE `hutang`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `input_po`
--
ALTER TABLE `input_po`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `input_po_detail`
--
ALTER TABLE `input_po_detail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `input_po_pembayaran`
--
ALTER TABLE `input_po_pembayaran`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoice`
--
ALTER TABLE `invoice`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jalan`
--
ALTER TABLE `jalan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jenis_pekerjaan_bangunan`
--
ALTER TABLE `jenis_pekerjaan_bangunan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jenis_pekerjaan_jalan`
--
ALTER TABLE `jenis_pekerjaan_jalan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jenis_pekerjaan_saluran`
--
ALTER TABLE `jenis_pekerjaan_saluran`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kategori_transaksi`
--
ALTER TABLE `kategori_transaksi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kavling_peta`
--
ALTER TABLE `kavling_peta`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `konfigurasi`
--
ALTER TABLE `konfigurasi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `konfigurasi_media`
--
ALTER TABLE `konfigurasi_media`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `konfigurasi_wa`
--
ALTER TABLE `konfigurasi_wa`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `legal`
--
ALTER TABLE `legal`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `listrik_air`
--
ALTER TABLE `listrik_air`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `log_aktivitas_pengguna`
--
ALTER TABLE `log_aktivitas_pengguna`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lokasi_kavling`
--
ALTER TABLE `lokasi_kavling`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lokasi_kavling_perusahaan`
--
ALTER TABLE `lokasi_kavling_perusahaan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `marketing_freelance`
--
ALTER TABLE `marketing_freelance`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `marketing_offline`
--
ALTER TABLE `marketing_offline`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `master_svg`
--
ALTER TABLE `master_svg`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menu_panduan_aplikasi`
--
ALTER TABLE `menu_panduan_aplikasi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `metode_bayar`
--
ALTER TABLE `metode_bayar`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mutasi_saldo`
--
ALTER TABLE `mutasi_saldo`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notaris`
--
ALTER TABLE `notaris`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pemasukan`
--
ALTER TABLE `pemasukan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pemasukan_retensi`
--
ALTER TABLE `pemasukan_retensi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pembelian_cancel`
--
ALTER TABLE `pembelian_cancel`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pengajuan_hold`
--
ALTER TABLE `pengajuan_hold`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pengajuan_hold_tempo`
--
ALTER TABLE `pengajuan_hold_tempo`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pengeluaran`
--
ALTER TABLE `pengeluaran`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `persyaratan_legal`
--
ALTER TABLE `persyaratan_legal`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `perusahaan`
--
ALTER TABLE `perusahaan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pindah_unit`
--
ALTER TABLE `pindah_unit`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `piutang`
--
ALTER TABLE `piutang`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ppjb`
--
ALTER TABLE `ppjb`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `progres_list_pembangunan`
--
ALTER TABLE `progres_list_pembangunan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `progres_list_penjualan`
--
ALTER TABLE `progres_list_penjualan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `progres_unit_ready`
--
ALTER TABLE `progres_unit_ready`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `prospek_customer`
--
ALTER TABLE `prospek_customer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `proyek_bangunan`
--
ALTER TABLE `proyek_bangunan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `proyek_bangunan_blok`
--
ALTER TABLE `proyek_bangunan_blok`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `proyek_bangunan_detail`
--
ALTER TABLE `proyek_bangunan_detail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `proyek_bangunan_detail_kerja`
--
ALTER TABLE `proyek_bangunan_detail_kerja`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `proyek_bangunan_unit`
--
ALTER TABLE `proyek_bangunan_unit`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `proyek_jalan`
--
ALTER TABLE `proyek_jalan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `proyek_jalan_detail`
--
ALTER TABLE `proyek_jalan_detail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `proyek_jalan_detail_kerja`
--
ALTER TABLE `proyek_jalan_detail_kerja`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `proyek_saluran`
--
ALTER TABLE `proyek_saluran`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `proyek_saluran_detail`
--
ALTER TABLE `proyek_saluran_detail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `proyek_saluran_detail_kerja`
--
ALTER TABLE `proyek_saluran_detail_kerja`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rekap_akad`
--
ALTER TABLE `rekap_akad`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `retensi`
--
ALTER TABLE `retensi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `retensi_nama_retensi_unique` (`nama_retensi`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `role_user`
--
ALTER TABLE `role_user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `saluran`
--
ALTER TABLE `saluran`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `satuan`
--
ALTER TABLE `satuan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `serah_terima_kunci`
--
ALTER TABLE `serah_terima_kunci`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `sppr`
--
ALTER TABLE `sppr`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `spr`
--
ALTER TABLE `spr`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `template_pesan`
--
ALTER TABLE `template_pesan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `throttle`
--
ALTER TABLE `throttle`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `upload_file`
--
ALTER TABLE `upload_file`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `variable_akad`
--
ALTER TABLE `variable_akad`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wawancara`
--
ALTER TABLE `wawancara`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wawancara_sp3k`
--
ALTER TABLE `wawancara_sp3k`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `aduan`
--
ALTER TABLE `aduan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `aduan_proses`
--
ALTER TABLE `aduan_proses`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `akad`
--
ALTER TABLE `akad`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `balik_nama`
--
ALTER TABLE `balik_nama`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bank`
--
ALTER TABLE `bank`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `bank_kpr`
--
ALTER TABLE `bank_kpr`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `barang`
--
ALTER TABLE `barang`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `barang_keluar`
--
ALTER TABLE `barang_keluar`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `barang_keluar_detail`
--
ALTER TABLE `barang_keluar_detail`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `barang_masuk`
--
ALTER TABLE `barang_masuk`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `barang_masuk_detail`
--
ALTER TABLE `barang_masuk_detail`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bast`
--
ALTER TABLE `bast`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bphtb_ssp`
--
ALTER TABLE `bphtb_ssp`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `content`
--
ALTER TABLE `content`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `customer_tempo`
--
ALTER TABLE `customer_tempo`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `detail_akad`
--
ALTER TABLE `detail_akad`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `file_aduan`
--
ALTER TABLE `file_aduan`
  MODIFY `id_file_aduan` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `file_proses_aduan`
--
ALTER TABLE `file_proses_aduan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `foto_progres`
--
ALTER TABLE `foto_progres`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `foto_proyek_bangunan`
--
ALTER TABLE `foto_proyek_bangunan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `foto_proyek_jalan`
--
ALTER TABLE `foto_proyek_jalan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `foto_proyek_saluran`
--
ALTER TABLE `foto_proyek_saluran`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ganti_nama`
--
ALTER TABLE `ganti_nama`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ganti_namas`
--
ALTER TABLE `ganti_namas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hak_akses`
--
ALTER TABLE `hak_akses`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2484;

--
-- AUTO_INCREMENT for table `hak_akses_panduan_apk`
--
ALTER TABLE `hak_akses_panduan_apk`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hutang`
--
ALTER TABLE `hutang`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `input_po`
--
ALTER TABLE `input_po`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `input_po_detail`
--
ALTER TABLE `input_po_detail`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `input_po_pembayaran`
--
ALTER TABLE `input_po_pembayaran`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoice`
--
ALTER TABLE `invoice`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jalan`
--
ALTER TABLE `jalan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jenis_pekerjaan_bangunan`
--
ALTER TABLE `jenis_pekerjaan_bangunan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jenis_pekerjaan_jalan`
--
ALTER TABLE `jenis_pekerjaan_jalan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jenis_pekerjaan_saluran`
--
ALTER TABLE `jenis_pekerjaan_saluran`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kategori_transaksi`
--
ALTER TABLE `kategori_transaksi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kavling_peta`
--
ALTER TABLE `kavling_peta`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=233;

--
-- AUTO_INCREMENT for table `konfigurasi`
--
ALTER TABLE `konfigurasi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `konfigurasi_media`
--
ALTER TABLE `konfigurasi_media`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `konfigurasi_wa`
--
ALTER TABLE `konfigurasi_wa`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `legal`
--
ALTER TABLE `legal`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `listrik_air`
--
ALTER TABLE `listrik_air`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `log_aktivitas_pengguna`
--
ALTER TABLE `log_aktivitas_pengguna`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `lokasi_kavling`
--
ALTER TABLE `lokasi_kavling`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `lokasi_kavling_perusahaan`
--
ALTER TABLE `lokasi_kavling_perusahaan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `marketing_freelance`
--
ALTER TABLE `marketing_freelance`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `marketing_offline`
--
ALTER TABLE `marketing_offline`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `master_svg`
--
ALTER TABLE `master_svg`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT for table `menu_panduan_aplikasi`
--
ALTER TABLE `menu_panduan_aplikasi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `metode_bayar`
--
ALTER TABLE `metode_bayar`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `mutasi_saldo`
--
ALTER TABLE `mutasi_saldo`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notaris`
--
ALTER TABLE `notaris`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pemasukan`
--
ALTER TABLE `pemasukan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `pemasukan_retensi`
--
ALTER TABLE `pemasukan_retensi`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pembelian_cancel`
--
ALTER TABLE `pembelian_cancel`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pengajuan_hold`
--
ALTER TABLE `pengajuan_hold`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pengajuan_hold_tempo`
--
ALTER TABLE `pengajuan_hold_tempo`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pengeluaran`
--
ALTER TABLE `pengeluaran`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `persyaratan_legal`
--
ALTER TABLE `persyaratan_legal`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `perusahaan`
--
ALTER TABLE `perusahaan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `pindah_unit`
--
ALTER TABLE `pindah_unit`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `piutang`
--
ALTER TABLE `piutang`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `ppjb`
--
ALTER TABLE `ppjb`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `progres_list_pembangunan`
--
ALTER TABLE `progres_list_pembangunan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `progres_list_penjualan`
--
ALTER TABLE `progres_list_penjualan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `progres_unit_ready`
--
ALTER TABLE `progres_unit_ready`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `prospek_customer`
--
ALTER TABLE `prospek_customer`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `proyek_bangunan`
--
ALTER TABLE `proyek_bangunan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `proyek_bangunan_blok`
--
ALTER TABLE `proyek_bangunan_blok`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `proyek_bangunan_detail`
--
ALTER TABLE `proyek_bangunan_detail`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `proyek_bangunan_detail_kerja`
--
ALTER TABLE `proyek_bangunan_detail_kerja`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `proyek_bangunan_unit`
--
ALTER TABLE `proyek_bangunan_unit`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `proyek_jalan`
--
ALTER TABLE `proyek_jalan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `proyek_jalan_detail`
--
ALTER TABLE `proyek_jalan_detail`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `proyek_jalan_detail_kerja`
--
ALTER TABLE `proyek_jalan_detail_kerja`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `proyek_saluran`
--
ALTER TABLE `proyek_saluran`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `proyek_saluran_detail`
--
ALTER TABLE `proyek_saluran_detail`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `proyek_saluran_detail_kerja`
--
ALTER TABLE `proyek_saluran_detail_kerja`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rekap_akad`
--
ALTER TABLE `rekap_akad`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `retensi`
--
ALTER TABLE `retensi`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `role_user`
--
ALTER TABLE `role_user`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=596;

--
-- AUTO_INCREMENT for table `saluran`
--
ALTER TABLE `saluran`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `satuan`
--
ALTER TABLE `satuan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `serah_terima_kunci`
--
ALTER TABLE `serah_terima_kunci`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sppr`
--
ALTER TABLE `sppr`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `spr`
--
ALTER TABLE `spr`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `template_pesan`
--
ALTER TABLE `template_pesan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `throttle`
--
ALTER TABLE `throttle`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `upload_file`
--
ALTER TABLE `upload_file`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `variable_akad`
--
ALTER TABLE `variable_akad`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wawancara`
--
ALTER TABLE `wawancara`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wawancara_sp3k`
--
ALTER TABLE `wawancara_sp3k`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
