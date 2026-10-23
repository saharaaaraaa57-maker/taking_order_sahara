-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 23, 2026 at 01:17 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_cafe`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`, `nama_lengkap`, `email`, `created_at`) VALUES
(1, 'admin', '$2y$10$i4/PodSRsymFkD/LtXcVY.P/TeFRF1tJr9xX6RVTgGmi1j4U.uH6G', 'Administrator', 'admin@nonspace.com', '2026-10-23 07:09:50');

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `id` int(11) NOT NULL,
  `nama_menu` varchar(100) NOT NULL,
  `kategori` varchar(50) NOT NULL,
  `harga` decimal(10,2) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `stok` int(11) DEFAULT 0,
  `gambar` varchar(255) DEFAULT NULL,
  `status` enum('tersedia','habis') DEFAULT 'tersedia',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`id`, `nama_menu`, `kategori`, `harga`, `deskripsi`, `stok`, `gambar`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Redvelvet', 'Non-coffe', 25000.00, '', 49, 'menu_20261023170705_24cd110c.jpg', 'tersedia', '2026-10-23 10:07:05', '2026-10-23 10:45:32'),
(2, 'Dimsum mentai', 'makanan', 35000.00, '', 15, 'menu_20261023171051_1acfd85f.jpg', 'tersedia', '2026-10-23 10:10:51', '2026-10-23 10:10:51'),
(3, 'Americano', 'coffe', 20000.00, '', 30, 'menu_20261023171342_b0136bb5.jpg', 'tersedia', '2026-10-23 10:13:42', '2026-10-23 10:13:42'),
(4, 'Coffe Latte', 'coffe', 20000.00, '', 35, 'menu_20261023171609_4148ed02.jpg', 'tersedia', '2026-10-23 10:16:09', '2026-10-23 10:16:09'),
(5, 'Matcha', 'Non-coffe', 20000.00, '', 32, 'menu_20261023171809_c09fec65.jpg', 'tersedia', '2026-10-23 10:18:09', '2026-10-23 10:18:09'),
(6, 'Chocolate', 'Non-coffe', 20000.00, '', 38, 'menu_20261023172438_d2dfc5cd.jpg', 'tersedia', '2026-10-23 10:24:38', '2026-10-23 10:24:38'),
(7, 'Croissant', 'makanan', 25000.00, '', 43, 'menu_20261023172657_60d8e4e5.jpg', 'tersedia', '2026-10-23 10:26:57', '2026-10-23 10:45:32'),
(8, 'Avocado Toast', 'makanan', 30000.00, '', 14, 'menu_20261023172919_0f4458c8.jpg', 'tersedia', '2026-10-23 10:29:19', '2026-10-23 10:29:19');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `kode_order` varchar(30) NOT NULL,
  `pelanggan_id` int(11) NOT NULL,
  `no_meja` varchar(10) NOT NULL,
  `total_harga` decimal(12,2) NOT NULL,
  `status` enum('pending','diproses','selesai','dibatalkan') DEFAULT 'pending',
  `catatan` text DEFAULT NULL,
  `waktu_order` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `kode_order`, `pelanggan_id`, `no_meja`, `total_harga`, `status`, `catatan`, `waktu_order`) VALUES
(1, 'ORD-20261023174532-B864', 1, 'b02', 50000.00, 'selesai', '', '2026-10-23 17:45:32');

-- --------------------------------------------------------

--
-- Table structure for table `order_detail`
--

CREATE TABLE `order_detail` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `nama_menu` varchar(100) NOT NULL,
  `harga` decimal(10,2) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `subtotal` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_detail`
--

INSERT INTO `order_detail` (`id`, `order_id`, `menu_id`, `nama_menu`, `harga`, `jumlah`, `subtotal`) VALUES
(1, 1, 7, 'Croissant', 25000.00, 1, 25000.00),
(2, 1, 1, 'Redvelvet', 25000.00, 1, 25000.00);

-- --------------------------------------------------------

--
-- Table structure for table `pelanggan`
--

CREATE TABLE `pelanggan` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `no_meja` varchar(10) NOT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `waktu_datang` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pelanggan`
--

INSERT INTO `pelanggan` (`id`, `nama`, `no_meja`, `no_hp`, `waktu_datang`) VALUES
(1, 'sahara', 'b02', '', '2026-10-23 17:45:11');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_kategori` (`kategori`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_order` (`kode_order`),
  ADD KEY `idx_pelanggan` (`pelanggan_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_waktu` (`waktu_order`);

--
-- Indexes for table `order_detail`
--
ALTER TABLE `order_detail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_order` (`order_id`),
  ADD KEY `idx_menu` (`menu_id`);

--
-- Indexes for table `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `order_detail`
--
ALTER TABLE `order_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `pelanggan`
--
ALTER TABLE `pelanggan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_pelanggan` FOREIGN KEY (`pelanggan_id`) REFERENCES `pelanggan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `order_detail`
--
ALTER TABLE `order_detail`
  ADD CONSTRAINT `fk_detail_menu` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_detail_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
