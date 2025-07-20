-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 02, 2025 at 03:34 AM
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
-- Database: `simkad`
--

-- --------------------------------------------------------

--
-- Table structure for table `ajuancapil`
--

CREATE TABLE `ajuancapil` (
  `idCapil` int(10) UNSIGNED NOT NULL,
  `idOpdes` int(10) UNSIGNED NOT NULL,
  `idLayanan` int(10) UNSIGNED NOT NULL,
  `noKK` varchar(20) NOT NULL,
  `nik` varchar(20) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `noAkta` varchar(50) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `token` varchar(6) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `statAjuan` enum('dalam antrian','ditolak','sudah diproses','revisi','selesai') NOT NULL DEFAULT 'dalam antrian',
  `linkBerkas` varchar(1024) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ajuandafduk`
--

CREATE TABLE `ajuandafduk` (
  `idDafduk` int(20) UNSIGNED NOT NULL,
  `idOpdes` int(20) UNSIGNED NOT NULL,
  `idLayanan` int(11) UNSIGNED NOT NULL,
  `noKK` varchar(20) DEFAULT NULL,
  `nik` varchar(20) DEFAULT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `token` varchar(6) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `statAjuan` enum('dalam antrian','ditolak','sudah diproses','revisi','selesai') DEFAULT 'dalam antrian',
  `linkBerkas` varchar(1024) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `desa`
--

CREATE TABLE `desa` (
  `idDesa` int(11) UNSIGNED NOT NULL,
  `namaDesa` varchar(100) NOT NULL,
  `idKec` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `desa`
--

INSERT INTO `desa` (`idDesa`, `namaDesa`, `idKec`) VALUES
(1, 'Banjaran', 1),
(2, 'Bentar', 1),
(3, 'Bentarsari', 1),
(4, 'Capar', 1),
(5, 'Ciputih', 1),
(6, 'Citimbang', 1),
(7, 'Gandoang', 1),
(8, 'Ganggawang', 1),
(9, 'Gunungjaya', 1),
(10, 'Gununglarang', 1),
(11, 'Gunungsugih', 1),
(12, 'Gunungtajem', 1),
(13, 'Indrajaya', 1),
(14, 'Kadumanis', 1),
(15, 'Pabuaran', 1),
(16, 'Pasirpanjang', 1),
(17, 'Salem', 1),
(18, 'Tembongraja', 1),
(19, 'Wanoja', 1),
(20, 'Winduasri', 1),
(21, 'Windusakti', 1),
(22, 'Bangbayang', 2),
(23, 'Banjarsari', 2),
(24, 'Bantarkawung', 2),
(25, 'Bantarwaru', 2),
(26, 'Cibentang', 2),
(27, 'Cinanas', 2),
(28, 'Ciomas', 2),
(29, 'Jipang', 2),
(30, 'Karangpari', 2),
(31, 'Kebandungan', 2),
(32, 'Legok', 2),
(33, 'Pangebatan', 2),
(34, 'Pengarasan', 2),
(35, 'Sindangwangi', 2),
(36, 'Tambakserang', 2),
(37, 'Telaga', 2),
(38, 'Terlaya', 2),
(39, 'Waru', 2),
(40, 'Adisana', 3),
(41, 'Bumiayu', 3),
(42, 'Dukuhturi', 3),
(43, 'Jatisawit', 3),
(44, 'Kalierang', 3),
(45, 'Kalilangkap', 3),
(46, 'Kalinusu', 3),
(47, 'Kalisumur', 3),
(48, 'Kaliwadas', 3),
(49, 'Langkap', 3),
(50, 'Laren', 3),
(51, 'Negaradaha', 3),
(52, 'Pamijen', 3),
(53, 'Penggarutan', 3),
(54, 'Pruwatan', 3),
(55, 'Cilibur', 4),
(56, 'Cipetung', 4),
(57, 'Kedungoleng', 4),
(58, 'Kretek', 4),
(59, 'Pagojengan', 4),
(60, 'Paguyangan', 4),
(61, 'Pakujati', 4),
(62, 'Pandansari', 4),
(63, 'Ragatunjung', 4),
(64, 'Taraban', 4),
(65, 'Wanatirta', 4),
(66, 'Winduaji', 4),
(67, 'Batursari', 5),
(68, 'Benda', 5),
(69, 'Buniwah', 5),
(70, 'Dawuhan', 5),
(71, 'Igirklanceng', 5),
(72, 'Kaligiri', 5),
(73, 'Kaliloka', 5),
(74, 'Manggis', 5),
(75, 'Mendala', 5),
(76, 'Mlayang', 5),
(77, 'Plompong', 5),
(78, 'Sridadi', 5),
(79, 'Wanareja', 5),
(80, 'Galuhtimur', 6),
(81, 'Kalijurang', 6),
(82, 'Karangjongkeng', 6),
(83, 'Kutamendala', 6),
(84, 'Kutayu', 6),
(85, 'Linggapura', 6),
(86, 'Negarayu', 6),
(87, 'Pepedan', 6),
(88, 'Purbayasa', 6),
(89, 'Purwodadi', 6),
(90, 'Rajawetan', 6),
(91, 'Tanggeran', 6),
(92, 'Tonjong', 6),
(93, 'Watujaya', 6),
(94, 'Bojong', 7),
(95, 'Buaran', 7),
(96, 'Janegara', 7),
(97, 'Jatibarang Kidul', 7),
(98, 'Jatibarang Lor', 7),
(99, 'Kalialang', 7),
(100, 'Kalipucang', 7),
(101, 'Karanglo', 7),
(102, 'Kebogadung', 7),
(103, 'Kebonagung', 7),
(104, 'Kedungtukang', 7),
(105, 'Kemiriamba', 7),
(106, 'Kendawa', 7),
(107, 'Kertasinduyasa', 7),
(108, 'Klampis', 7),
(109, 'Klikiran', 7),
(110, 'Kramat', 7),
(111, 'Pamengger', 7),
(112, 'Pedeslohor', 7),
(113, 'Rengasbandung', 7),
(114, 'Tegalwulung', 7),
(115, 'Tembelang', 7),
(116, 'Dukuhwringin', 8),
(117, 'Dumeling', 8),
(118, 'Glonggong', 8),
(119, 'Jagalempeni', 8),
(120, 'Keboledan', 8),
(121, 'Kertabesuki', 8),
(122, 'Klampok', 8),
(123, 'Kupu', 8),
(124, 'Lengkong', 8),
(125, 'Pebatan', 8),
(126, 'Pesantunan', 8),
(127, 'Sawojajar', 8),
(128, 'Siasem', 8),
(129, 'Sidamulya', 8),
(130, 'Sigentong', 8),
(131, 'Sisalam', 8),
(132, 'Siwungkuk', 8),
(133, 'Tanjungsari', 8),
(134, 'Tegalgandu', 8),
(135, 'Wanasari', 8),
(136, 'Brebes', 9),
(137, 'Gandasuli', 9),
(138, 'Limbangan Kulon', 9),
(139, 'Limbangan Wetan', 9),
(140, 'Pasarbatang', 9),
(141, 'Banjaranyar', 9),
(142, 'Kaligangsa Kulon', 9),
(143, 'Kaligangsa Wetan', 9),
(144, 'Kalimati', 9),
(145, 'Kaliwlingi', 9),
(146, 'Kedunguter', 9),
(147, 'Krasak', 9),
(148, 'Lembarawa', 9),
(149, 'Padasugih', 9),
(150, 'Pagejugan', 9),
(151, 'Pemaron', 9),
(152, 'Pulosari', 9),
(153, 'Randusanga Kulon', 9),
(154, 'Randusanga Wetan', 9),
(155, 'Sigambir', 9),
(156, 'Tengki', 9),
(157, 'Terlangu', 9),
(158, 'Wangandalem', 9),
(159, 'Cenang', 10),
(160, 'Dukuhmaja', 10),
(161, 'Gegerkunci', 10),
(162, 'Jatimakmur', 10),
(163, 'Jatirokeh', 10),
(164, 'Karangsembung', 10),
(165, 'Songgom', 10),
(166, 'Songgom Lor', 10),
(167, 'Wanacala', 10),
(168, 'Wanatawang', 10),
(169, 'Ciampel', 11),
(170, 'Cigedog', 11),
(171, 'Cikandang', 11),
(172, 'Jagapura', 11),
(173, 'Kemukten', 11),
(174, 'Kersana', 11),
(175, 'Kradenan', 11),
(176, 'Kramatsampang', 11),
(177, 'Kubangpari', 11),
(178, 'Limbangan', 11),
(179, 'Pende', 11),
(180, 'Sindangjaya', 11),
(181, 'Sutamaja', 11),
(182, 'Babakan', 12),
(183, 'Blubuk', 12),
(184, 'Bojongsari', 12),
(185, 'Dukuhsalam', 12),
(186, 'Jatisawit', 12),
(187, 'Kalibuntu', 12),
(188, 'Karangdempel', 12),
(189, 'Karangjunti', 12),
(190, 'Karangsambung', 12),
(191, 'Kecipir', 12),
(192, 'Kedungneng', 12),
(193, 'Limbangan', 12),
(194, 'Losari Kidul', 12),
(195, 'Losari Lor', 12),
(196, 'Negla', 12),
(197, 'Pekauman', 12),
(198, 'Pengabean', 12),
(199, 'Prapag Kidul', 12),
(200, 'Prapag Lor', 12),
(201, 'Randegan', 12),
(202, 'Randusari', 12),
(203, 'Rungkang', 12),
(204, 'Karangreja', 13),
(205, 'Kedawung', 13),
(206, 'Kemurang Kulon', 13),
(207, 'Kemurang Wetan', 13),
(208, 'Krakahan', 13),
(209, 'Kupangputat', 13),
(210, 'Lemahabang', 13),
(211, 'Luwungbata', 13),
(212, 'Luwunggede', 13),
(213, 'Mundu', 13),
(214, 'Pejagan', 13),
(215, 'Pengaradan', 13),
(216, 'Sarireja', 13),
(217, 'Sengon', 13),
(218, 'Sidakaton', 13),
(219, 'Tanjung', 13),
(220, 'Tegongan', 13),
(221, 'Tengguli', 13),
(222, 'Bangsri', 14),
(223, 'Banjaratma', 14),
(224, 'Bulakamba', 14),
(225, 'Bulakparen', 14),
(226, 'Bulusari', 14),
(227, 'Cimohong', 14),
(228, 'Cipelem', 14),
(229, 'Dukuhlo', 14),
(230, 'Grinting', 14),
(231, 'Jubang', 14),
(232, 'Karangsari', 14),
(233, 'Kluwut', 14),
(234, 'Luwungragi', 14),
(235, 'Pakijangan', 14),
(236, 'Petunjungan', 14),
(237, 'Pulogading', 14),
(238, 'Rancawuluh', 14),
(239, 'Siwuluh', 14),
(240, 'Tegalglagah', 14),
(241, 'Kamal', 15),
(242, 'Karangbale', 15),
(243, 'Kedungbokor', 15),
(244, 'Larangan', 15),
(245, 'Luwunggede', 15),
(246, 'Pamulihan', 15),
(247, 'Rengaspendawa', 15),
(248, 'Siandong', 15),
(249, 'Sitanggal', 15),
(250, 'Slatri', 15),
(251, 'Wlahar', 15),
(252, 'Baros', 16),
(253, 'Buara', 16),
(254, 'Bulakelor', 16),
(255, 'Ciduwet', 16),
(256, 'Cikeusal Kidul', 16),
(257, 'Cikeusal Lor', 16),
(258, 'Ciseureuh', 16),
(259, 'Dukuhbadag', 16),
(260, 'Dukuhtengah', 16),
(261, 'Dukuhturi', 16),
(262, 'Jemasih', 16),
(263, 'Karangbandung', 16),
(264, 'Karangmalang', 16),
(265, 'Ketanggungan', 16),
(266, 'Kubangjati', 16),
(267, 'Kubangsari', 16),
(268, 'Kubangwungu', 16),
(269, 'Padakaton', 16),
(270, 'Pamedaran', 16),
(271, 'Sindangjaya', 16),
(272, 'Tanggungsari', 16),
(273, 'Bandungsari', 17),
(274, 'Banjarharjo', 17),
(275, 'Banjarlor', 17),
(276, 'Blandongan', 17),
(277, 'Ciawi', 17),
(278, 'Cibendung', 17),
(279, 'Cibuniwangi', 17),
(280, 'Cigadung', 17),
(281, 'Cihaur', 17),
(282, 'Cikakak', 17),
(283, 'Cikuya', 17),
(284, 'Cimunding', 17),
(285, 'Cipajang', 17),
(286, 'Dukuhjeruk', 17),
(287, 'Karangmaja', 17),
(288, 'Kertasari', 17),
(289, 'Kubangjero', 17),
(290, 'Malahayu', 17),
(291, 'Parereja', 17),
(292, 'Penanggapan', 17),
(293, 'Pende', 17),
(294, 'Sindangheula', 17),
(295, 'Sukareja', 17),
(296, 'Tegalreja', 17),
(297, 'Tiwulandu', 17);

-- --------------------------------------------------------

--
-- Table structure for table `finaldokumen`
--

CREATE TABLE `finaldokumen` (
  `idFinDok` int(10) UNSIGNED NOT NULL,
  `idAjuan` int(10) UNSIGNED NOT NULL,
  `jenis` enum('capil','dafduk') NOT NULL,
  `filename` varchar(255) DEFAULT NULL,
  `filePath` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kecamatan`
--

CREATE TABLE `kecamatan` (
  `idKec` int(11) UNSIGNED NOT NULL,
  `namaKec` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kecamatan`
--

INSERT INTO `kecamatan` (`idKec`, `namaKec`) VALUES
(1, 'Salem'),
(2, 'Bantarkawung'),
(3, 'Bumiayu'),
(4, 'Paguyangan'),
(5, 'Sirampog'),
(6, 'Tonjong'),
(7, 'Jatibarang'),
(8, 'Wanasari'),
(9, 'Brebes'),
(10, 'Songgom'),
(11, 'Kersana'),
(12, 'Losari'),
(13, 'Tanjung'),
(14, 'Bulakamba'),
(15, 'Larangan'),
(16, 'Ketanggungan'),
(17, 'Banjarharjo');

-- --------------------------------------------------------

--
-- Table structure for table `layanan`
--

CREATE TABLE `layanan` (
  `idLayanan` int(11) UNSIGNED NOT NULL,
  `namaLayanan` varchar(100) NOT NULL,
  `jenis` enum('dafduk','capil') NOT NULL,
  `aksesVer` enum('dinasDafduk','dinasCapil','kecamatan') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `layanan`
--

INSERT INTO `layanan` (`idLayanan`, `namaLayanan`, `jenis`, `aksesVer`) VALUES
(1, 'Penambahan Anggota Keluarga', 'dafduk', 'kecamatan'),
(2, 'Akta Kelahiran', 'capil', 'dinasCapil'),
(5, 'Akta Kematian', 'capil', 'dinasCapil'),
(6, 'Pembuatan KK Baru', 'dafduk', 'kecamatan'),
(7, 'Pengurangan Anggota Keluarga', 'dafduk', 'kecamatan'),
(8, 'Perubahan Data', 'dafduk', 'kecamatan'),
(9, 'Perpindahan Antardesa, Kecamatan', 'dafduk', 'kecamatan'),
(10, 'Perpindahan AntarKab/Prov', 'dafduk', 'dinasDafduk');

-- --------------------------------------------------------

--
-- Table structure for table `operatordesa`
--

CREATE TABLE `operatordesa` (
  `idOpdes` int(20) UNSIGNED NOT NULL,
  `idUser` int(20) UNSIGNED NOT NULL,
  `idDesa` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `operatorkec`
--

CREATE TABLE `operatorkec` (
  `idOpkec` int(20) UNSIGNED NOT NULL,
  `idUser` int(20) UNSIGNED NOT NULL,
  `idKec` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `idToken` int(20) UNSIGNED NOT NULL,
  `email` varchar(255) NOT NULL,
  `otp_code` varchar(6) NOT NULL,
  `otp_expires_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `respon`
--

CREATE TABLE `respon` (
  `idRespon` int(10) UNSIGNED NOT NULL,
  `idUser` int(10) UNSIGNED NOT NULL,
  `idAjuan` int(10) UNSIGNED NOT NULL,
  `jenis` enum('capil','dafduk') NOT NULL,
  `respon` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `idUser` int(20) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `roleUser` enum('superadmin','admin','opDinCapil','opDinDafduk','operatorKecamatan','operatorDesa','') NOT NULL,
  `status` enum('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`idUser`, `nama`, `email`, `password`, `roleUser`, `status`, `created_at`, `updated_at`) VALUES
(1, 'superadmin', 'superadmin@gmail.com', '$2y$12$0MzM/Z2G8Qpji642bdznRegw2KB19BtAmX8ihUPP7aaIOI7LDecge', 'superadmin', 'aktif', '2025-06-25 19:46:35', '2025-06-28 22:27:35'),
(2, 'M. Syahndra Ramadhan', 'mohammadsyahndra@gmail.com', '$2y$12$yXeQQ5BQaS07wAn.k.mPluPRk/0dlR1gbtO7qT9HoMcGMhGu/bXWm', 'admin', 'aktif', '2025-07-01 18:30:19', '2025-07-01 18:30:19');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ajuancapil`
--
ALTER TABLE `ajuancapil`
  ADD PRIMARY KEY (`idCapil`),
  ADD KEY `fk_ajuanCapil_idOpdes` (`idOpdes`),
  ADD KEY `fk_ajuanCapil_idLayanan` (`idLayanan`);

--
-- Indexes for table `ajuandafduk`
--
ALTER TABLE `ajuandafduk`
  ADD PRIMARY KEY (`idDafduk`),
  ADD KEY `idOpdes` (`idOpdes`),
  ADD KEY `idLayanan` (`idLayanan`);

--
-- Indexes for table `desa`
--
ALTER TABLE `desa`
  ADD PRIMARY KEY (`idDesa`),
  ADD KEY `id_kecamatan` (`idKec`);

--
-- Indexes for table `finaldokumen`
--
ALTER TABLE `finaldokumen`
  ADD PRIMARY KEY (`idFinDok`);

--
-- Indexes for table `kecamatan`
--
ALTER TABLE `kecamatan`
  ADD PRIMARY KEY (`idKec`);

--
-- Indexes for table `layanan`
--
ALTER TABLE `layanan`
  ADD PRIMARY KEY (`idLayanan`);

--
-- Indexes for table `operatordesa`
--
ALTER TABLE `operatordesa`
  ADD PRIMARY KEY (`idOpdes`),
  ADD KEY `idUser` (`idUser`),
  ADD KEY `idDesa` (`idDesa`);

--
-- Indexes for table `operatorkec`
--
ALTER TABLE `operatorkec`
  ADD PRIMARY KEY (`idOpkec`),
  ADD KEY `idUser` (`idUser`),
  ADD KEY `idKec` (`idKec`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`idToken`);

--
-- Indexes for table `respon`
--
ALTER TABLE `respon`
  ADD PRIMARY KEY (`idRespon`),
  ADD KEY `fk_respon_user` (`idUser`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`idUser`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ajuancapil`
--
ALTER TABLE `ajuancapil`
  MODIFY `idCapil` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ajuandafduk`
--
ALTER TABLE `ajuandafduk`
  MODIFY `idDafduk` int(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `desa`
--
ALTER TABLE `desa`
  MODIFY `idDesa` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=298;

--
-- AUTO_INCREMENT for table `finaldokumen`
--
ALTER TABLE `finaldokumen`
  MODIFY `idFinDok` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kecamatan`
--
ALTER TABLE `kecamatan`
  MODIFY `idKec` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `layanan`
--
ALTER TABLE `layanan`
  MODIFY `idLayanan` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `operatordesa`
--
ALTER TABLE `operatordesa`
  MODIFY `idOpdes` int(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `operatorkec`
--
ALTER TABLE `operatorkec`
  MODIFY `idOpkec` int(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  MODIFY `idToken` int(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `respon`
--
ALTER TABLE `respon`
  MODIFY `idRespon` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `idUser` int(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `ajuancapil`
--
ALTER TABLE `ajuancapil`
  ADD CONSTRAINT `fk_ajuanCapil_idLayanan` FOREIGN KEY (`idLayanan`) REFERENCES `layanan` (`idLayanan`),
  ADD CONSTRAINT `fk_ajuanCapil_idOpdes` FOREIGN KEY (`idOpdes`) REFERENCES `operatordesa` (`idOpdes`);

--
-- Constraints for table `ajuandafduk`
--
ALTER TABLE `ajuandafduk`
  ADD CONSTRAINT `ajuandafduk_ibfk_1` FOREIGN KEY (`idOpdes`) REFERENCES `operatordesa` (`idOpdes`),
  ADD CONSTRAINT `ajuandafduk_ibfk_2` FOREIGN KEY (`idLayanan`) REFERENCES `layanan` (`idLayanan`);

--
-- Constraints for table `desa`
--
ALTER TABLE `desa`
  ADD CONSTRAINT `desa_ibfk_1` FOREIGN KEY (`idKec`) REFERENCES `kecamatan` (`idKec`);

--
-- Constraints for table `operatordesa`
--
ALTER TABLE `operatordesa`
  ADD CONSTRAINT `operatordesa_ibfk_1` FOREIGN KEY (`idUser`) REFERENCES `users` (`idUser`) ON DELETE CASCADE,
  ADD CONSTRAINT `operatordesa_ibfk_2` FOREIGN KEY (`idDesa`) REFERENCES `desa` (`idDesa`) ON DELETE CASCADE;

--
-- Constraints for table `operatorkec`
--
ALTER TABLE `operatorkec`
  ADD CONSTRAINT `operatorkec_ibfk_1` FOREIGN KEY (`idUser`) REFERENCES `users` (`idUser`) ON DELETE CASCADE,
  ADD CONSTRAINT `operatorkec_ibfk_2` FOREIGN KEY (`idKec`) REFERENCES `kecamatan` (`idKec`) ON DELETE CASCADE;

--
-- Constraints for table `respon`
--
ALTER TABLE `respon`
  ADD CONSTRAINT `fk_respon_user` FOREIGN KEY (`idUser`) REFERENCES `users` (`idUser`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
