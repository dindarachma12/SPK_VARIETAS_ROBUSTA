-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 28, 2026 at 03:13 PM
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
-- Table structure for table `checked`
--

CREATE TABLE `checked` (
  `id_checked` int(11) NOT NULL,
  `id_varietas` int(11) NOT NULL,
  `username` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `checked`
--

INSERT INTO `checked` (`id_checked`, `id_varietas`, `username`) VALUES
(1, 1, 'dinda'),
(2, 2, 'dinda'),
(3, 3, 'dinda'),
(4, 4, 'dinda'),
(5, 5, 'dinda'),
(6, 6, 'dinda'),
(7, 7, 'dinda'),
(8, 8, 'dinda'),
(9, 9, 'dinda'),
(10, 10, 'dinda'),
(11, 11, 'dinda'),
(12, 12, 'dinda');

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
(1, 'C1', 'Jenis Tanah', 'cost'),
(2, 'C2', 'Curah Hujan', 'benefit'),
(3, 'C3', 'Suhu', 'benefit'),
(4, 'C4', 'Kelembapan Lingkungan', 'benefit'),
(5, 'C5', 'Ketinggian', 'benefit');

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
(27, 0, 1, 0),
(28, 0, 2, 0),
(29, 0, 3, 0),
(30, 0, 4, 0),
(31, 0, 5, 0),
(167, 1, 1, 1),
(168, 1, 2, 6),
(169, 1, 3, 11),
(170, 1, 4, 18),
(171, 1, 5, 21),
(177, 2, 1, 4),
(178, 2, 2, 6),
(179, 2, 3, 11),
(180, 2, 4, 16),
(181, 2, 5, 22),
(192, 5, 1, 1),
(193, 5, 2, 6),
(194, 5, 3, 11),
(195, 5, 4, 17),
(196, 5, 5, 21),
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
(247, 8, 1, 4),
(248, 8, 2, 6),
(249, 8, 3, 11),
(250, 8, 4, 17),
(251, 8, 5, 22),
(252, 9, 1, 1),
(253, 9, 2, 6),
(254, 9, 3, 11),
(255, 9, 4, 17),
(256, 9, 5, 22),
(257, 10, 1, 1),
(258, 10, 2, 7),
(259, 10, 3, 11),
(260, 10, 4, 17),
(261, 10, 5, 22);

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
(10, 'dinda', 'dinddarac', '$2y$10$MPGB4.aqkDC0loc4sScpu.9nqCYz9y8yuSixR8/1HyZYugI4V5B1.', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `peringkat`
--

CREATE TABLE `peringkat` (
  `id_peringkat` int(11) NOT NULL,
  `id_varietas` int(11) NOT NULL,
  `nilai_peringkat` float NOT NULL,
  `username` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(8, 'K8', 'Bp 534'),
(9, 'K9', 'BP 436'),
(10, 'K10', 'BP 920'),
(11, 'K11', 'BP 939'),
(12, 'K12', 'BP 308');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `checked`
--
ALTER TABLE `checked`
  ADD PRIMARY KEY (`id_checked`);

--
-- Indexes for table `kriteria`
--
ALTER TABLE `kriteria`
  ADD PRIMARY KEY (`id_kriteria`);

--
-- Indexes for table `matriks`
--
ALTER TABLE `matriks`
  ADD PRIMARY KEY (`id_matriks`);

--
-- Indexes for table `pengguna`
--
ALTER TABLE `pengguna`
  ADD PRIMARY KEY (`id_pengguna`);

--
-- Indexes for table `peringkat`
--
ALTER TABLE `peringkat`
  ADD PRIMARY KEY (`id_peringkat`);

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
-- AUTO_INCREMENT for table `checked`
--
ALTER TABLE `checked`
  MODIFY `id_checked` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `kriteria`
--
ALTER TABLE `kriteria`
  MODIFY `id_kriteria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `matriks`
--
ALTER TABLE `matriks`
  MODIFY `id_matriks` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=262;

--
-- AUTO_INCREMENT for table `pengguna`
--
ALTER TABLE `pengguna`
  MODIFY `id_pengguna` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `peringkat`
--
ALTER TABLE `peringkat`
  MODIFY `id_peringkat` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subkriteria`
--
ALTER TABLE `subkriteria`
  MODIFY `id_subkriteria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `varietas`
--
ALTER TABLE `varietas`
  MODIFY `id_varietas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
