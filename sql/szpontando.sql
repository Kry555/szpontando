-- phpMyAdmin SQL Dump
-- version 6.0.0-dev+20260417.26bcf2d71e
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 25, 2026 at 05:11 PM
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
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;




CREATE TABLE `oceny` (
  `id_oceny` int NOT NULL,
  `id_zgloszenia` int NOT NULL,
  `id_profil_autor` int NOT NULL,
  `id_profil_oceniany` int NOT NULL,
  `gwiazdki` tinyint NOT NULL CHECK (`gwiazdki` BETWEEN 0 AND 5),
  `opis` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `rola` enum('pracownik','gospodarz') COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
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
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
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
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
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
(5, '2026_02_20_084451_create_users_table', 3);

-- --------------------------------------------------------

--
-- Table structure for table `oferty`
--

CREATE TABLE `oferty` (
  `id_oferty` int NOT NULL,
  `id_profil_owner` int NOT NULL,
  `adres` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `typ` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `cena` int NOT NULL,
  `do_kiedy_wazne` datetime NOT NULL,
  `opis` text COLLATE utf8mb4_general_ci NOT NULL,
  `status` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `oferty`
--

INSERT INTO `oferty` (`id_oferty`, `id_profil_owner`, `adres`, `typ`, `cena`, `do_kiedy_wazne`, `opis`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'Częstochowa', 'po_imprezie', 999, '2029-02-22 22:22:00', 'obrona', 'aktywna', '2026-03-22 14:05:08', '2026-03-22 14:05:08'),
(2, 1, 'warszawa', 'cały_dom', 6767, '2100-11-22 22:12:00', 'zamek', 'anulowane', '2026-03-22 14:05:51', '2026-03-22 14:05:51'),
(3, 2, 'opole', 'kuweta_kota', 150, '2067-02-22 12:00:00', 'mam kota', 'zaakceptowana', '2026-03-22 14:09:17', '2026-03-22 14:09:17'),
(4, 2, 'opole', 'rower', 200, '2028-05-14 11:50:00', 'rower ryszarda, mocno ubłocony', 'aktywna', '2026-03-22 14:10:54', '2026-03-22 14:10:54'),
(5, 3, '2w31qe3e', 'ogrod_tarasy', 2143, '2026-04-24 21:32:00', 'chuj', 'wygaslo', '2026-04-21 21:10:09', '2026-04-21 21:14:25'),
(6, 3, 'wefrat', 'brud_ciężki_przemysłowy', 23, '2026-04-24 23:45:00', '1324etrf', 'wygaslo', '2026-04-21 21:15:13', '2026-04-21 21:15:13'),
(7, 3, 'chuj', 'mycie_okien', 2137, '2066-07-05 23:07:00', 'chuj', 'zaakceptowana', '2026-04-25 16:36:02', '2026-04-25 16:36:02');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `powiadomienia`
--

CREATE TABLE `powiadomienia` (
  `id_powiadomienia` int NOT NULL,
  `tytul` text COLLATE utf8mb4_general_ci NOT NULL,
  `text` text COLLATE utf8mb4_general_ci NOT NULL,
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
(6, 'nowe zgloszenie do twojej oferty', 'uzytkownik chuj zglosil sie do twojego zgloszenia', 0, 2),
(7, 'twoje zgłoszenie zostało zaakceptowane', 'Twoje zgłoszenie do oferty zostało zaakceptowane', 0, 3),
(8, 'nowe zgloszenie do twojej oferty', 'uzytkownik chujj zglosil sie do twojego zgloszenia', 0, 3),
(9, 'twoje zgłoszenie zostało zaakceptowane', 'Twoje zgłoszenie do oferty zostało zaakceptowane', 0, 4);

-- --------------------------------------------------------

--
-- Table structure for table `profil`
--

CREATE TABLE `profil` (
  `id_profil` int NOT NULL,
  `nick` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `imie` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nazwisko` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `data_ur` date DEFAULT NULL,
  `miasto` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email_kontaktowy` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ocena` int DEFAULT NULL,
  `profilowe` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `sex` varchar(15) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `profil`
--

INSERT INTO `profil` (`id_profil`, `nick`, `imie`, `nazwisko`, `data_ur`, `miasto`, `email_kontaktowy`, `ocena`, `profilowe`, `sex`) VALUES
(1, 'jan321', 'jan', 'kowalski', '2008-03-22', 'goglin', 'janpv@email.com', NULL, '1774188185_kmicic.jpg', 'men'),
(2, 'ala321', 'ala', 'berewiczówna', '2000-03-22', 'zawada', 'alapv@email.com', NULL, '1774188489_bb8f3b58a545b4436a036bc91b135dd5.jpg', 'women'),
(3, 'chuj', 'chuj', 'chuj', '1000-04-25', 'opole', 'chujjjj@email', NULL, NULL, 'women'),
(4, 'chujj', 'chujj', 'chujj', '1000-04-25', 'opole', 'chujjjjjj@email', NULL, NULL, 'women');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('AKMAWmlubUy6LQ28Ch7DuukaKWhNfuZRMoy3fSMC', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiamtqNWhBMDlIcFhDNW9mcERtQ1g5NkVUbTZYMWM2QXBmaXhMYzhRTSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9teS1vZmVydCI7czo1OiJyb3V0ZSI7czo4OiJteV9vZmVydCI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjI7fQ==', 1776808169),
('oVbMJP48VlCMmWFuSQPX7PMIIgnNdQ8i9RTS1oiR', 4, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiR0JEcEpUeGx0UFk1TnRFbXpIWG9ORjhUandjWXdFbDg1RlN1aWFhVyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo0OiJtYWluIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6NDt9', 1777137084);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `nick` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `czy_admin` tinyint(1) NOT NULL DEFAULT '0',
  `id_profil` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `aktywny` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nick`, `email`, `password`, `czy_admin`, `id_profil`, `created_at`, `updated_at`, `aktywny`) VALUES
(1, 'jan321', 'jan@email.com', '$2y$12$TjKFWRPsCPgetWepQDqHSeSMCs18KnBbYcVEudhOjFNtyFiePi.HG', 0, 1, '2026-03-22 12:59:23', '2026-03-22 12:59:23', 1),
(2, 'ala321', 'ala@email.com', '$2y$12$FPpY1P0gIfZFFQkFlFq4/eP/liFz8Cinw4GP6dxBMr/w60DqKiPtW', 0, 2, '2026-03-22 12:59:47', '2026-03-22 12:59:47', 1),
(3, 'chuj', 'chuj@email.com', '$2y$12$zM9NBxuaPiQhBVtJgNmmpex5e2JeEgxcxU1nBorw3LAKMzX8pvYxa', 0, 3, '2026-04-21 19:08:19', '2026-04-21 19:08:19', 1),
(4, 'chujj', 'chujj@email.com', '$2y$12$oe6KawzT.1LcPTTm1YhMUurGmd.4oxQdx5RLq2mmi68IOra6EkAgK', 0, 4, '2026-04-25 14:34:54', '2026-04-25 14:34:54', 1);

-- --------------------------------------------------------

--
-- Table structure for table `zgloszenia`
--

CREATE TABLE `zgloszenia` (
  `id_zgloszenia` int NOT NULL,
  `id_oferty` int NOT NULL,
  `id_profil_wykonawca` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `wiadomosc` text COLLATE utf8mb4_general_ci,
  `zatwierdzone` tinyint(1) NOT NULL,
  `status` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
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
(2, 1, '2', 'dobra cena, chętnie się zgłoszę', 0, 'aktywne', NULL, 0, 0, NULL),
(3, 4, '1', 'lubie czyscic rowery', 0, 'aktywne', NULL, 0, 0, NULL),
(4, 3, '1', 'kocham zwierzęta', 0, 'aktywne', NULL, 0, 0, NULL),
(5, 3, '3', 'no dawaj', 1, 'zatwierdzone', NULL, 0, 0, NULL),
(6, 7, '4', 'chujj', 1, 'zatwierdzone', '2026-07-06 12:02:00', 1, 1, '2026-07-06 12:02:00');

--
-- Indexes for dumped tables
--

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
-- Indexes for table `oferty`
--
ALTER TABLE `oferty`
  ADD PRIMARY KEY (`id_oferty`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

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
-- Indexes for table `oceny`
--
ALTER TABLE `oceny`
  ADD PRIMARY KEY (`id_oceny`);

--
-- AUTO_INCREMENT for dumped tables
--

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
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `oferty`
--
ALTER TABLE `oferty`
  MODIFY `id_oferty` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `powiadomienia`
--
ALTER TABLE `powiadomienia`
  MODIFY `id_powiadomienia` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `profil`
--
ALTER TABLE `profil`
  MODIFY `id_profil` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `zgloszenia`
--
ALTER TABLE `zgloszenia`
  MODIFY `id_zgloszenia` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `oceny`
--
ALTER TABLE `oceny`
  MODIFY `id_oceny` int NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
