-- phpMyAdmin SQL Dump
-- version 6.0.0-dev+20260522.28778d272a
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 24, 2026 at 05:49 PM
-- Server version: 8.4.3
-- PHP Version: 8.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `szpontando`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_logs`
--

CREATE TABLE `admin_logs` (
  `id` int NOT NULL,
  `admin_id` int NOT NULL,
  `action` varchar(255) NOT NULL,
  `details` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_logs`
--

INSERT INTO `admin_logs` (`id`, `admin_id`, `action`, `details`, `created_at`) VALUES
(1, 1, 'Ban użytkownika', 'Użytkownik ID: 2 zbanowany na 1 dni. Powód: bo jest chujowa', '2026-05-24 11:47:02'),
(2, 1, 'Odbanowanie użytkownika', 'Użytkownik ID: 2 został ręcznie odbanowany.', '2026-05-24 11:59:36'),
(3, 1, 'Ban użytkownika', 'Użytkownik ID: 2 zbanowany na 1 dni. Powód: chuj', '2026-05-24 12:23:30'),
(4, 1, 'Odbanowanie użytkownika', 'Użytkownik ID: 2 został ręcznie odbanowany.', '2026-05-24 12:23:33'),
(5, 1, 'Rozpatrzenie zgłoszenia - BAN', 'Zbanowano ofertę ID: 11 na podstawie zgłoszenia ID: 1', '2026-05-24 12:27:32'),
(6, 1, 'Rozpatrzenie zgłoszenia - BAN', 'Zbanowano ofertę ID: 12 na podstawie zgłoszenia ID: 3', '2026-05-24 15:11:46');

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
-- Table structure for table `email_verifications`
--

CREATE TABLE `email_verifications` (
  `id` bigint UNSIGNED NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
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
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_02_19_215404_create_sessions_table', 2),
(5, '2026_02_20_084451_create_users_table', 3),
(6, '2026_04_25_195225_create_password_resets_table', 4),
(7, '2026_04_26_185303_create_email_verifications_table', 4);

-- --------------------------------------------------------

--
-- Table structure for table `oceny`
--

CREATE TABLE `oceny` (
  `id_oceny` int NOT NULL,
  `id_zgloszenia` int NOT NULL,
  `id_profil_autor` int NOT NULL,
  `id_profil_oceniany` int NOT NULL,
  `gwiazdki` tinyint NOT NULL,
  `opis` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `rola` enum('pracownik','gospodarz') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `oceny`
--

INSERT INTO `oceny` (`id_oceny`, `id_zgloszenia`, `id_profil_autor`, `id_profil_oceniany`, `gwiazdki`, `opis`, `rola`, `created_at`) VALUES
(1, 7, 3, 4, 3, 'git', 'pracownik', '2026-04-25 18:31:27'),
(5, 2, 2, 1, 1, 'całkiem chujowy', 'pracownik', '2026-05-24 09:36:33'),
(6, 3, 1, 2, 1, 'jebac ale', 'pracownik', '2026-05-24 09:37:12'),
(7, 2, 1, 2, 1, 'chujowa', 'gospodarz', '2026-05-24 09:37:44');

-- --------------------------------------------------------

--
-- Table structure for table `oferty`
--

CREATE TABLE `oferty` (
  `id_oferty` int NOT NULL,
  `id_profil_owner` int NOT NULL,
  `adres` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `typ` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `cena` int NOT NULL,
  `do_kiedy_wazne` datetime NOT NULL,
  `opis` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `zdjecie_1` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `zdjecie_2` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `oferty`
--

INSERT INTO `oferty` (`id_oferty`, `id_profil_owner`, `adres`, `typ`, `cena`, `do_kiedy_wazne`, `opis`, `zdjecie_1`, `zdjecie_2`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'Częstochowa', 'po_imprezie', 999, '2029-02-22 22:22:00', 'obrona', NULL, NULL, 'zaakceptowana', '2026-03-22 14:05:08', '2026-03-22 14:05:08'),
(2, 1, 'warszawa', 'cały_dom', 6767, '2100-11-22 22:12:00', 'zamek', NULL, NULL, 'anulowane', '2026-03-22 14:05:51', '2026-03-22 14:05:51'),
(3, 2, 'opole', 'kuweta_kota', 150, '2067-02-22 12:00:00', 'mam kota', NULL, NULL, 'zaakceptowana', '2026-03-22 14:09:17', '2026-03-22 14:09:17'),
(4, 2, 'opole', 'rower', 200, '2028-05-14 11:50:00', 'rower ryszarda, mocno ubłocony', NULL, NULL, 'zaakceptowana', '2026-03-22 14:10:54', '2026-03-22 14:10:54'),
(10, 1, 'pawel', 'miejsce_zbrodni', 12344, '4567-03-21 07:09:00', '1234566', '1779644956_o1_edit.jpg', '1779644956_o2_edit.jpg', 'aktywna', '2026-05-24 11:33:20', '2026-05-24 17:49:16'),
(11, 1, 'gliwice', 'miejsce_zbrodni', 132, '5678-04-01 09:56:00', '34567', NULL, NULL, 'zbanowana', '2026-05-24 12:26:48', '2026-05-24 12:26:48'),
(12, 2, 'asdfghjkl,', 'po_imprezie', 2137, '4567-03-12 08:06:00', 'asdfghjkl', NULL, NULL, 'zbanowana', '2026-05-24 13:53:59', '2026-05-24 15:07:50'),
(13, 1, 'chujj', 'rower', 12345, '4567-03-12 09:00:00', 'mabyc aktywna', NULL, NULL, 'aktywna', '2026-05-24 16:18:42', '2026-05-24 16:18:42'),
(14, 1, 'asdfghj', 'sprzątanie_po_psie', 112345, '4567-03-12 03:07:00', 'chujjjj', NULL, NULL, 'aktywna', '2026-05-24 17:17:54', '2026-05-24 17:17:54');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `powiadomienia`
--

CREATE TABLE `powiadomienia` (
  `id_powiadomienia` int NOT NULL,
  `tytul` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `odzcytane` tinyint(1) NOT NULL,
  `id_user` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `powiadomienia`
--

INSERT INTO `powiadomienia` (`id_powiadomienia`, `tytul`, `text`, `odzcytane`, `id_user`) VALUES
(1, 'nowe zgloszenie do twojej oferty', 'uzytkownik ala321 zglosil sie do twojego zgloszenia', 0, 1),
(2, 'nowe zgloszenie do twojej oferty', 'uzytkownik ala321 zglosil sie do twojego zgloszenia', 0, 1),
(3, 'nowe zgloszenie do twojej oferty', 'uzytkownik jan321 zglosil sie do twojego zgloszenia', 0, 2),
(4, 'nowe zgloszenie do twojej oferty', 'uzytkownik jan321 zglosil sie do twojego zgloszenia', 0, 2),
(5, 'twoje zgłoszenie zostało zaakceptowane', 'Twoje zgłoszenie do oferty zostało zaakceptowane', 0, 2),
(18, 'twoje zgłoszenie zostało zaakceptowane', 'Twoje zgłoszenie do oferty zostało zaakceptowane', 0, 2),
(19, 'twoje zgłoszenie zostało zaakceptowane', 'Twoje zgłoszenie do oferty zostało zaakceptowane', 0, 1),
(20, 'Termin zaakceptowany', 'Wykonawca zaakceptował Twój termin: 2026-05-25 10:10', 0, 1),
(21, 'Termin zaakceptowany', 'Wykonawca zaakceptował Twój termin: 2026-05-24 14:00', 0, 2),
(22, 'nowe zgloszenie do twojej oferty', 'uzytkownik ala321 zglosil sie do twojego zgloszenia', 0, 1),
(23, 'Twoja oferta została zbanowana', 'Powód: bo ala ma fiuta', 0, 2);

-- --------------------------------------------------------

--
-- Table structure for table `profil`
--

CREATE TABLE `profil` (
  `id_profil` int NOT NULL,
  `nick` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `imie` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nazwisko` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `data_ur` date DEFAULT NULL,
  `miasto` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email_kontaktowy` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ocena` int DEFAULT NULL,
  `profilowe` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `sex` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `profil`
--

INSERT INTO `profil` (`id_profil`, `nick`, `imie`, `nazwisko`, `data_ur`, `miasto`, `email_kontaktowy`, `ocena`, `profilowe`, `sex`) VALUES
(1, 'jan321', 'jan', 'kowalski', '2008-03-22', 'goglin', 'janpv@email.com', 1, '1774188185_kmicic.jpg', 'men'),
(2, 'ala321', 'ala', 'berewiczówna', '2000-03-22', 'zawada', 'alapv@email.com', 1, '1774188489_bb8f3b58a545b4436a036bc91b135dd5.jpg', 'women');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payload` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('5xGKJYPUeu1i6HFHiV1mdOUPuGJZClmS29DF36Lm', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20100101 Firefox/151.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoid2pEWlZKOGE4WnoybmNYdnlDVTJsekdKbzJOY1A1MzNaSFhDZDFOVyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9vZmVydGEvMTAiO3M6NToicm91dGUiO3M6MTE6Im9mZXJ0YS5zaG93Ijt9fQ==', 1779644968);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `nick` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `czy_admin` tinyint(1) NOT NULL DEFAULT '0',
  `id_profil` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `aktywny` tinyint(1) NOT NULL DEFAULT '0',
  `zbanowany_do` datetime DEFAULT NULL,
  `powod_bana` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nick`, `email`, `password`, `czy_admin`, `id_profil`, `created_at`, `updated_at`, `aktywny`, `zbanowany_do`, `powod_bana`) VALUES
(1, 'jan321', 'jan@email.com', '$2y$12$TjKFWRPsCPgetWepQDqHSeSMCs18KnBbYcVEudhOjFNtyFiePi.HG', 1, 1, '2026-03-22 12:59:23', '2026-03-22 12:59:23', 1, NULL, NULL),
(2, 'ala321', 'ala@email.com', '$2y$12$FPpY1P0gIfZFFQkFlFq4/eP/liFz8Cinw4GP6dxBMr/w60DqKiPtW', 0, 2, '2026-03-22 12:59:47', '2026-03-22 12:59:47', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `zgloszenia`
--

CREATE TABLE `zgloszenia` (
  `id_zgloszenia` int NOT NULL,
  `id_oferty` int NOT NULL,
  `id_profil_wykonawca` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `wiadomosc` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `zatwierdzone` tinyint(1) NOT NULL,
  `status` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `proponowany_termin` datetime DEFAULT NULL,
  `termin_zaakceptowany_wykonawca` tinyint(1) DEFAULT '0',
  `termin_zaakceptowany_wlasciciel` tinyint(1) DEFAULT '0',
  `ostateczny_termin` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `zgloszenia`
--

INSERT INTO `zgloszenia` (`id_zgloszenia`, `id_oferty`, `id_profil_wykonawca`, `wiadomosc`, `zatwierdzone`, `status`, `proponowany_termin`, `termin_zaakceptowany_wykonawca`, `termin_zaakceptowany_wlasciciel`, `ostateczny_termin`) VALUES
(1, 2, '2', 'chętni się podejne sprzątania zamku!', 1, 'zakończone', NULL, 0, 0, NULL),
(2, 1, '2', 'dobra cena, chętnie się zgłoszę', 1, 'zatwierdzone', '2026-05-25 10:10:00', 1, 1, '2026-05-25 10:10:00'),
(3, 4, '1', 'lubie czyscic rowery', 1, 'zatwierdzone', '2026-05-24 14:00:00', 1, 1, '2026-05-24 14:00:00'),
(4, 3, '1', 'kocham zwierzęta', 0, 'aktywne', NULL, 0, 0, NULL),
(9, 10, '2', 'che', 0, 'aktywne', NULL, 0, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `zgloszenia_naduzyc`
--

CREATE TABLE `zgloszenia_naduzyc` (
  `id_zgloszenia` int NOT NULL,
  `id_oferty` int NOT NULL,
  `id_user_zgloszajacy` int NOT NULL,
  `powod` text NOT NULL,
  `status` varchar(50) DEFAULT 'nowe',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `zgloszenia_naduzyc`
--

INSERT INTO `zgloszenia_naduzyc` (`id_zgloszenia`, `id_oferty`, `id_user_zgloszajacy`, `powod`, `status`, `created_at`, `updated_at`) VALUES
(1, 11, 2, 'pisze a czyszczeniu chuja', 'rozpatrzone', '2026-05-24 10:27:11', '2026-05-24 12:27:32'),
(2, 11, 2, 'plascki', 'nowe', '2026-05-24 11:52:58', '2026-05-24 11:52:58'),
(3, 12, 1, 'cycki', 'rozpatrzone', '2026-05-24 11:54:16', '2026-05-24 15:11:46');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_logs`
--
ALTER TABLE `admin_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `email_verifications`
--
ALTER TABLE `email_verifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

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
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `oceny`
--
ALTER TABLE `oceny`
  ADD PRIMARY KEY (`id_oceny`);

--
-- Indexes for table `oferty`
--
ALTER TABLE `oferty`
  ADD PRIMARY KEY (`id_oferty`);

--
-- Indexes for table `powiadomienia`
--
ALTER TABLE `powiadomienia`
  ADD PRIMARY KEY (`id_powiadomienia`);

--
-- Indexes for table `profil`
--
ALTER TABLE `profil`
  ADD PRIMARY KEY (`id_profil`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `zgloszenia`
--
ALTER TABLE `zgloszenia`
  ADD PRIMARY KEY (`id_zgloszenia`);

--
-- Indexes for table `zgloszenia_naduzyc`
--
ALTER TABLE `zgloszenia_naduzyc`
  ADD PRIMARY KEY (`id_zgloszenia`),
  ADD KEY `fk_oferta` (`id_oferty`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_logs`
--
ALTER TABLE `admin_logs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `email_verifications`
--
ALTER TABLE `email_verifications`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `oceny`
--
ALTER TABLE `oceny`
  MODIFY `id_oceny` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `oferty`
--
ALTER TABLE `oferty`
  MODIFY `id_oferty` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `powiadomienia`
--
ALTER TABLE `powiadomienia`
  MODIFY `id_powiadomienia` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `profil`
--
ALTER TABLE `profil`
  MODIFY `id_profil` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `zgloszenia`
--
ALTER TABLE `zgloszenia`
  MODIFY `id_zgloszenia` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `zgloszenia_naduzyc`
--
ALTER TABLE `zgloszenia_naduzyc`
  MODIFY `id_zgloszenia` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `zgloszenia_naduzyc`
--
ALTER TABLE `zgloszenia_naduzyc`
  ADD CONSTRAINT `fk_oferta` FOREIGN KEY (`id_oferty`) REFERENCES `oferty` (`id_oferty`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
