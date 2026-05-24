-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Maj 24, 2026 at 04:42 PM
-- Wersja serwera: 10.4.32-MariaDB
-- Wersja PHP: 8.2.12

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
-- Struktura tabeli dla tabeli `admin_logs`
--

CREATE TABLE `admin_logs` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `action` varchar(255) NOT NULL,
  `details` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_logs`
--

INSERT INTO `admin_logs` (`id`, `admin_id`, `action`, `details`, `created_at`) VALUES
(1, 1, 'Ban użytkownika', 'Użytkownik ID: 2 zbanowany na 1 dni. Powód: bo jest chujowa', '2026-05-24 11:47:02'),
(2, 1, 'Odbanowanie użytkownika', 'Użytkownik ID: 2 został ręcznie odbanowany.', '2026-05-24 11:59:36'),
(3, 1, 'Ban użytkownika', 'Użytkownik ID: 2 zbanowany na 1 dni. Powód: chuj', '2026-05-24 12:23:30'),
(4, 1, 'Odbanowanie użytkownika', 'Użytkownik ID: 2 został ręcznie odbanowany.', '2026-05-24 12:23:33'),
(5, 1, 'Rozpatrzenie zgłoszenia - BAN', 'Zbanowano ofertę ID: 11 na podstawie zgłoszenia ID: 1', '2026-05-24 12:27:32');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `email_change_requests`
--

CREATE TABLE `email_change_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `old_email` varchar(255) NOT NULL,
  `new_email` varchar(255) DEFAULT NULL,
  `old_email_token` varchar(255) DEFAULT NULL,
  `old_email_verified_at` timestamp NULL DEFAULT NULL,
  `new_email_token` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `email_verifications`
--

CREATE TABLE `email_verifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
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
(7, '2026_04_26_185303_create_email_verifications_table', 4),
(8, '2026_05_23_210543_create_email_change_requests_table', 5);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `oceny`
--

CREATE TABLE `oceny` (
  `id_oceny` int(11) NOT NULL,
  `id_zgloszenia` int(11) NOT NULL,
  `id_profil_autor` int(11) NOT NULL,
  `id_profil_oceniany` int(11) NOT NULL,
  `gwiazdki` tinyint(4) NOT NULL,
  `opis` varchar(255) DEFAULT NULL,
  `rola` enum('pracownik','gospodarz') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
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
-- Struktura tabeli dla tabeli `oferty`
--

CREATE TABLE `oferty` (
  `id_oferty` int(11) NOT NULL,
  `id_profil_owner` int(11) NOT NULL,
  `adres` varchar(255) NOT NULL,
  `typ` varchar(255) NOT NULL,
  `cena` int(11) NOT NULL,
  `do_kiedy_wazne` datetime NOT NULL,
  `opis` text NOT NULL,
  `status` varchar(20) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `oferty`
--

INSERT INTO `oferty` (`id_oferty`, `id_profil_owner`, `adres`, `typ`, `cena`, `do_kiedy_wazne`, `opis`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'Częstochowa', 'po_imprezie', 999, '2029-02-22 22:22:00', 'obrona', 'zaakceptowana', '2026-03-22 14:05:08', '2026-03-22 14:05:08'),
(2, 1, 'warszawa', 'cały_dom', 6767, '2100-11-22 22:12:00', 'zamek', 'anulowane', '2026-03-22 14:05:51', '2026-03-22 14:05:51'),
(3, 2, 'opole', 'kuweta_kota', 150, '2067-02-22 12:00:00', 'mam kota', 'zaakceptowana', '2026-03-22 14:09:17', '2026-03-22 14:09:17'),
(4, 2, 'opole', 'rower', 200, '2028-05-14 11:50:00', 'rower ryszarda, mocno ubłocony', 'zaakceptowana', '2026-03-22 14:10:54', '2026-03-22 14:10:54'),
(10, 1, 'pawel', 'miejsce_zbrodni', 12344, '4567-03-21 07:09:00', '1234566', 'aktywna', '2026-05-24 11:33:20', '2026-05-24 11:33:20'),
(11, 1, 'gliwice', 'miejsce_zbrodni', 132, '5678-04-01 09:56:00', '34567', 'zbanowana', '2026-05-24 12:26:48', '2026-05-24 12:26:48'),
(12, 2, 'asdfghjkl,', 'po_imprezie', 2137, '4567-03-12 08:06:00', 'asdfghjkl', 'aktywna', '2026-05-24 13:53:59', '2026-05-24 13:53:59');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `powiadomienia`
--

CREATE TABLE `powiadomienia` (
  `id_powiadomienia` int(11) NOT NULL,
  `tytul` text NOT NULL,
  `text` text NOT NULL,
  `odzcytane` tinyint(1) NOT NULL,
  `id_user` int(11) NOT NULL
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
(22, 'nowe zgloszenie do twojej oferty', 'uzytkownik ala321 zglosil sie do twojego zgloszenia', 0, 1);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `profil`
--

CREATE TABLE `profil` (
  `id_profil` int(11) NOT NULL,
  `nick` varchar(50) NOT NULL,
  `imie` varchar(50) DEFAULT NULL,
  `nazwisko` varchar(50) DEFAULT NULL,
  `data_ur` date DEFAULT NULL,
  `miasto` varchar(100) DEFAULT NULL,
  `email_kontaktowy` varchar(100) DEFAULT NULL,
  `ocena` int(11) DEFAULT NULL,
  `profilowe` varchar(100) DEFAULT NULL,
  `sex` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `profil`
--

INSERT INTO `profil` (`id_profil`, `nick`, `imie`, `nazwisko`, `data_ur`, `miasto`, `email_kontaktowy`, `ocena`, `profilowe`, `sex`) VALUES
(1, 'jan321', 'jan', 'kowalski', '2008-03-22', 'goglin', 'janpv@email.com', 1, '1774188185_kmicic.jpg', 'men'),
(2, 'ala321', 'ala', 'berewiczówna', '2000-03-22', 'zawada', 'alapv@email.com', 1, '1774188489_bb8f3b58a545b4436a036bc91b135dd5.jpg', 'women'),
(17, 'chups1@taikhoanfb.xyz', NULL, NULL, NULL, NULL, NULL, NULL, 'default.jpg', NULL);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` text NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('CO8fSSEFKskwLGpiuf1guDtPvjM5bzlvdmnMYnVN', 17, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 OPR/131.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiUnpMdXRVbThmUEdEdzgzWUJqcVRyUkNYYlhWYjBBa1R6bmxRZGd5dCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo0OiJtYWluIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTc7fQ==', 1779633677),
('q5L87aZAdyqbbjKDqMeMVQaKZqFK3Unzag9NEvtv', 1, '127.0.0.1', 'Mozilla/5.0', 'payload', 1779631398);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nick` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `czy_admin` tinyint(1) NOT NULL DEFAULT 0,
  `id_profil` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `aktywny` tinyint(1) NOT NULL DEFAULT 0,
  `zbanowany_do` datetime DEFAULT NULL,
  `powod_bana` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nick`, `email`, `password`, `czy_admin`, `id_profil`, `created_at`, `updated_at`, `aktywny`, `zbanowany_do`, `powod_bana`) VALUES
(1, 'jan321', 'jan@email.com', '$2y$12$TjKFWRPsCPgetWepQDqHSeSMCs18KnBbYcVEudhOjFNtyFiePi.HG', 1, 1, '2026-03-22 12:59:23', '2026-03-22 12:59:23', 1, NULL, NULL),
(2, 'ala321', 'ala@email.com', '$2y$12$FPpY1P0gIfZFFQkFlFq4/eP/liFz8Cinw4GP6dxBMr/w60DqKiPtW', 0, 2, '2026-03-22 12:59:47', '2026-03-22 12:59:47', 1, NULL, NULL),
(17, 'chups1@taikhoanfb.xyz', 'sosayef254@nuitx.com', '$2y$12$XIEnvNx2ACuW67r.Yku6rOTcYDB.6Px0f4D9mpLeBnjO8FTyqG7v6', 0, 17, '2026-05-24 11:39:35', '2026-05-24 11:50:39', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `zgloszenia`
--

CREATE TABLE `zgloszenia` (
  `id_zgloszenia` int(11) NOT NULL,
  `id_oferty` int(11) NOT NULL,
  `id_profil_wykonawca` varchar(255) NOT NULL,
  `wiadomosc` text DEFAULT NULL,
  `zatwierdzone` tinyint(1) NOT NULL,
  `status` varchar(50) DEFAULT NULL,
  `proponowany_termin` datetime DEFAULT NULL,
  `termin_zaakceptowany_wykonawca` tinyint(1) DEFAULT 0,
  `termin_zaakceptowany_wlasciciel` tinyint(1) DEFAULT 0,
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
-- Struktura tabeli dla tabeli `zgloszenia_naduzyc`
--

CREATE TABLE `zgloszenia_naduzyc` (
  `id_zgloszenia` int(11) NOT NULL,
  `id_oferty` int(11) NOT NULL,
  `id_user_zgloszajacy` int(11) NOT NULL,
  `powod` text NOT NULL,
  `status` varchar(50) DEFAULT 'nowe',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `zgloszenia_naduzyc`
--

INSERT INTO `zgloszenia_naduzyc` (`id_zgloszenia`, `id_oferty`, `id_user_zgloszajacy`, `powod`, `status`, `created_at`, `updated_at`) VALUES
(1, 11, 2, 'pisze a czyszczeniu chuja', 'rozpatrzone', '2026-05-24 10:27:11', '2026-05-24 12:27:32'),
(2, 11, 2, 'plascki', 'nowe', '2026-05-24 11:52:58', '2026-05-24 11:52:58'),
(3, 12, 1, 'cycki', 'nowe', '2026-05-24 11:54:16', '2026-05-24 11:54:16'),
(4, 10, 17, 'peins', 'nowe', '2026-05-24 12:41:17', '2026-05-24 12:41:17');

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `admin_logs`
--
ALTER TABLE `admin_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indeksy dla tabeli `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indeksy dla tabeli `email_change_requests`
--
ALTER TABLE `email_change_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `email_change_requests_user_id_foreign` (`user_id`);

--
-- Indeksy dla tabeli `email_verifications`
--
ALTER TABLE `email_verifications`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indeksy dla tabeli `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indeksy dla tabeli `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `oceny`
--
ALTER TABLE `oceny`
  ADD PRIMARY KEY (`id_oceny`);

--
-- Indeksy dla tabeli `oferty`
--
ALTER TABLE `oferty`
  ADD PRIMARY KEY (`id_oferty`);

--
-- Indeksy dla tabeli `powiadomienia`
--
ALTER TABLE `powiadomienia`
  ADD PRIMARY KEY (`id_powiadomienia`);

--
-- Indeksy dla tabeli `profil`
--
ALTER TABLE `profil`
  ADD PRIMARY KEY (`id_profil`);

--
-- Indeksy dla tabeli `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indeksy dla tabeli `zgloszenia`
--
ALTER TABLE `zgloszenia`
  ADD PRIMARY KEY (`id_zgloszenia`);

--
-- Indeksy dla tabeli `zgloszenia_naduzyc`
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `email_change_requests`
--
ALTER TABLE `email_change_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `email_verifications`
--
ALTER TABLE `email_verifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `oceny`
--
ALTER TABLE `oceny`
  MODIFY `id_oceny` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `oferty`
--
ALTER TABLE `oferty`
  MODIFY `id_oferty` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `powiadomienia`
--
ALTER TABLE `powiadomienia`
  MODIFY `id_powiadomienia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `profil`
--
ALTER TABLE `profil`
  MODIFY `id_profil` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `zgloszenia`
--
ALTER TABLE `zgloszenia`
  MODIFY `id_zgloszenia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `zgloszenia_naduzyc`
--
ALTER TABLE `zgloszenia_naduzyc`
  MODIFY `id_zgloszenia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `email_change_requests`
--
ALTER TABLE `email_change_requests`
  ADD CONSTRAINT `email_change_requests_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `zgloszenia_naduzyc`
--
ALTER TABLE `zgloszenia_naduzyc`
  ADD CONSTRAINT `fk_oferta` FOREIGN KEY (`id_oferty`) REFERENCES `oferty` (`id_oferty`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
