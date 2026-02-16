-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 16, 2026 at 05:33 PM
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
-- Database: `robustaku`
--

-- --------------------------------------------------------

--
-- Table structure for table `kriteria`
--

CREATE TABLE `kriteria` (
  `id_kriteria` int(11) NOT NULL,
  `kode_kriteria` varchar(20) NOT NULL,
  `nama_kriteria` varchar(50) NOT NULL,
  `jenis_kriteria` set('benefit','cost') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kriteria`
--

INSERT INTO `kriteria` (`id_kriteria`, `kode_kriteria`, `nama_kriteria`, `jenis_kriteria`) VALUES
(1, 'C1', 'Jenis Tanah', 'benefit'),
(2, 'C2', 'Curah Hujan', 'cost'),
(3, 'C3', 'Suhu', 'cost'),
(4, 'C4', 'Kelembapan Lingkungan', 'cost'),
(5, 'C5', 'Ketinggian', 'cost');

-- --------------------------------------------------------

--
-- Table structure for table `matriks`
--

CREATE TABLE `matriks` (
  `id_matriks` int(11) NOT NULL,
  `id_varietas` int(11) NOT NULL,
  `id_kriteria` int(11) NOT NULL,
  `id_subkriteria` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `matriks`
--

INSERT INTO `matriks` (`id_matriks`, `id_varietas`, `id_kriteria`, `id_subkriteria`) VALUES
(177, 2, 1, 4),
(178, 2, 2, 6),
(179, 2, 3, 11),
(180, 2, 4, 16),
(181, 2, 5, 22),
(197, 7, 1, 4),
(198, 7, 2, 6),
(199, 7, 3, 11),
(200, 7, 4, 17),
(201, 7, 5, 22),
(212, 12, 1, 4),
(213, 12, 2, 6),
(214, 12, 3, 11),
(215, 12, 4, 18),
(216, 12, 5, 22),
(227, 11, 1, 1),
(228, 11, 2, 7),
(229, 11, 3, 11),
(230, 11, 4, 17),
(231, 11, 5, 21),
(232, 3, 1, 4),
(233, 3, 2, 6),
(234, 3, 3, 12),
(235, 3, 4, 18),
(236, 3, 5, 21),
(237, 4, 1, 5),
(238, 4, 2, 7),
(239, 4, 3, 11),
(240, 4, 4, 17),
(241, 4, 5, 23),
(242, 6, 1, 1),
(243, 6, 2, 6),
(244, 6, 3, 11),
(245, 6, 4, 16),
(246, 6, 5, 21),
(252, 9, 1, 1),
(253, 9, 2, 6),
(254, 9, 3, 11),
(255, 9, 4, 17),
(256, 9, 5, 22),
(257, 10, 1, 1),
(258, 10, 2, 7),
(259, 10, 3, 11),
(260, 10, 4, 17),
(261, 10, 5, 22),
(267, 5, 1, 2),
(268, 5, 2, 6),
(269, 5, 3, 11),
(270, 5, 4, 17),
(271, 5, 5, 22),
(272, 8, 1, 4),
(273, 8, 2, 6),
(274, 8, 3, 12),
(275, 8, 4, 17),
(276, 8, 5, 22),
(282, 1, 1, 1),
(283, 1, 2, 6),
(284, 1, 3, 11),
(285, 1, 4, 18),
(286, 1, 5, 21);

-- --------------------------------------------------------

--
-- Table structure for table `pengguna`
--

CREATE TABLE `pengguna` (
  `id_pengguna` int(11) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(200) NOT NULL,
  `level` set('admin','user') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pengguna`
--

INSERT INTO `pengguna` (`id_pengguna`, `nama`, `username`, `password`, `level`) VALUES
(17, 'dinda', 'dinda', '$2y$10$X9DOTVGYcgSnPGtxVjto3ecvK4TwtVcwD7dI7IooqFn.dgZwfFhtG', 'admin'),
(18, 'petani', 'petani', '$2y$10$n47RUj.smSFZz70uZcHJf.3buwwaXQokDuyWlhcOvBzISiw7ZIB3y', 'user'),
(19, 'admin', 'admin', '$2y$10$Rd5p.Xz8FghgPGCFp58fheHURxc7caBOL3KsJJxmHpHYIdfHmX376', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `peringkat`
--

CREATE TABLE `peringkat` (
  `id_peringkat` int(11) NOT NULL,
  `id_varietas` int(11) NOT NULL,
  `nilai_peringkat` float NOT NULL,
  `id_pengguna` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `peringkat`
--

INSERT INTO `peringkat` (`id_peringkat`, `id_varietas`, `nilai_peringkat`, `id_pengguna`) VALUES
(1, 1, 0.76814, 17),
(2, 2, 0.2778, 17),
(3, 3, 0.34468, 17),
(4, 4, 0.266832, 17),
(5, 5, 0.700467, 17),
(6, 6, 1, 17),
(7, 7, 0.299533, 17),
(8, 8, 0.318889, 17),
(9, 9, 0.821731, 17),
(10, 10, 0.78636, 17),
(11, 11, 0.813641, 17),
(12, 12, 0.342883, 17);

-- --------------------------------------------------------

--
-- Table structure for table `subkriteria`
--

CREATE TABLE `subkriteria` (
  `id_subkriteria` int(11) NOT NULL,
  `id_kriteria` int(11) NOT NULL,
  `nama_subkriteria` varchar(50) NOT NULL,
  `nilai_subkriteria` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subkriteria`
--

INSERT INTO `subkriteria` (`id_subkriteria`, `id_kriteria`, `nama_subkriteria`, `nilai_subkriteria`) VALUES
(1, 1, 'Andosol', 5),
(2, 1, 'Latosol', 4),
(3, 1, 'Kambisol', 3),
(4, 1, 'Aluvial', 2),
(5, 1, 'Regosol', 1),
(6, 2, '1.500–2.000 mm/tahun', 5),
(7, 2, '2.000-2.500 mm/tahun', 4),
(8, 2, '2.500-3.000 mm/tahun', 3),
(9, 2, '3.000-3.500 mm/tahun', 2),
(10, 2, '&gt;3.500 mm/tahun', 1),
(11, 3, '20-24', 5),
(12, 3, '24-28', 4),
(13, 3, '28-30', 3),
(14, 3, '30-33', 2),
(15, 3, '&gt;33', 1),
(16, 4, '70-72%', 5),
(17, 4, '72-75%', 4),
(18, 4, '75-78%', 3),
(19, 4, '78-80%', 2),
(20, 4, '80-85%', 1),
(21, 5, '300-500 mdpl ', 5),
(22, 5, '500-600 mdpl', 4),
(23, 5, '600-700 mdpl', 3),
(24, 5, '700-800 mdpl', 2),
(25, 5, '&gt;800 mdpl', 1);

-- --------------------------------------------------------

--
-- Table structure for table `varietas`
--

CREATE TABLE `varietas` (
  `id_varietas` int(11) NOT NULL,
  `kode_varietas` varchar(20) NOT NULL,
  `nama_varietas` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `varietas`
--

INSERT INTO `varietas` (`id_varietas`, `kode_varietas`, `nama_varietas`) VALUES
(1, 'K1', 'BP 409'),
(2, 'K2', 'SA 237'),
(3, 'K3', 'BP 228'),
(4, 'K4', 'BP 358'),
(5, 'K5', 'BP 42'),
(6, 'K6', 'SA 203'),
(7, 'K7', 'BP 936'),
(8, 'K8', 'BP 534'),
(9, 'K9', 'BP 436'),
(10, 'K10', 'BP 920'),
(11, 'K11', 'BP 939'),
(12, 'K12', 'BP 308');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `kriteria`
--
ALTER TABLE `kriteria`
  ADD PRIMARY KEY (`id_kriteria`);

--
-- Indexes for table `matriks`
--
ALTER TABLE `matriks`
  ADD PRIMARY KEY (`id_matriks`),
  ADD KEY `fk_matriks_varietas` (`id_varietas`),
  ADD KEY `fk_matriks_kriteria` (`id_kriteria`),
  ADD KEY `fk_matriks_subkriteria` (`id_subkriteria`);

--
-- Indexes for table `pengguna`
--
ALTER TABLE `pengguna`
  ADD PRIMARY KEY (`id_pengguna`);

--
-- Indexes for table `peringkat`
--
ALTER TABLE `peringkat`
  ADD PRIMARY KEY (`id_peringkat`),
  ADD KEY `fk_peringkat_pengguna` (`id_pengguna`),
  ADD KEY `fk_peringkat_varietas` (`id_varietas`);

--
-- Indexes for table `subkriteria`
--
ALTER TABLE `subkriteria`
  ADD PRIMARY KEY (`id_subkriteria`);

--
-- Indexes for table `varietas`
--
ALTER TABLE `varietas`
  ADD PRIMARY KEY (`id_varietas`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `kriteria`
--
ALTER TABLE `kriteria`
  MODIFY `id_kriteria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `matriks`
--
ALTER TABLE `matriks`
  MODIFY `id_matriks` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=287;

--
-- AUTO_INCREMENT for table `pengguna`
--
ALTER TABLE `pengguna`
  MODIFY `id_pengguna` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `peringkat`
--
ALTER TABLE `peringkat`
  MODIFY `id_peringkat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `subkriteria`
--
ALTER TABLE `subkriteria`
  MODIFY `id_subkriteria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `varietas`
--
ALTER TABLE `varietas`
  MODIFY `id_varietas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `matriks`
--
ALTER TABLE `matriks`
  ADD CONSTRAINT `fk_matriks_kriteria` FOREIGN KEY (`id_kriteria`) REFERENCES `kriteria` (`id_kriteria`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_matriks_subkriteria` FOREIGN KEY (`id_subkriteria`) REFERENCES `subkriteria` (`id_subkriteria`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_matriks_varietas` FOREIGN KEY (`id_varietas`) REFERENCES `varietas` (`id_varietas`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `peringkat`
--
ALTER TABLE `peringkat`
  ADD CONSTRAINT `fk_peringkat_pengguna` FOREIGN KEY (`id_pengguna`) REFERENCES `pengguna` (`id_pengguna`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_peringkat_varietas` FOREIGN KEY (`id_varietas`) REFERENCES `varietas` (`id_varietas`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
