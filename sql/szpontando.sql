-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 22, 2026 at 01:42 PM
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
(5, '2026_02_20_084451_create_users_table', 3);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `oferty`
--

CREATE TABLE `oferty` (
  `id_oferty` int(255) NOT NULL,
  `id_profil_owner` int(255) NOT NULL,
  `adres` varchar(255) NOT NULL,
  `typ` varchar(255) NOT NULL,
  `cena` varchar(6) NOT NULL,
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
(1, 1, 'burdel makłowicza', 'po_imprezie', '2137', '2026-03-30 13:35:00', 'impra xd', 'aktywna', '2026-03-21 12:36:13', '2026-03-21 12:36:27'),
(2, 1, 'dom maxa', 'miejsce_zbrodni', '34', '2026-12-31 23:45:00', 'fajna zabawa z prostytutka', 'zaakceptowana', '2026-03-21 12:37:31', '2026-03-21 12:37:31'),
(3, 6, 'passat sie wykrzaczył', 'brud_ciezki_przemyslowy', '1997', '2026-11-23 21:00:00', 'passior', 'zaakceptowana', '2026-03-21 12:38:52', '2026-03-21 12:38:52'),
(4, 6, 'clup chlup', 'rower', '567', '2026-03-28 12:07:00', 'najs', 'aktywna', '2026-03-21 13:11:03', '2026-03-21 13:11:03');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
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
(1, 'nowe zgloszenie do twojej oferty', 'uzytkownik chujj zglosil sie do twojego zgloszenia', 0, 1),
(2, 'nowe zgloszenie do twojej oferty', 'uzytkownik chuj zglosil sie do twojego zgloszenia', 0, 6),
(3, 'twoje zgłoszenie zostało zaakceptowane', 'Twoje zgłoszenie do oferty zostało zaakceptowane', 0, 1),
(4, 'nowe zgloszenie do twojej oferty', 'uzytkownik chujj zglosil sie do twojego zgloszenia', 0, 1),
(5, 'nowe zgloszenie do twojej oferty', 'uzytkownik chuj zglosil sie do twojego zgloszenia', 0, 6),
(6, 'twoje zgłoszenie zostało zaakceptowane', 'Twoje zgłoszenie do oferty zostało zaakceptowane', 0, 6);

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
(1, 'chuj', 'Jan', 'Kowalski', '2026-03-03', 'opole', 'chujjjjjj@email', NULL, '1773777744_sample_0ce96ff83ae57189afea88c8c883859b.jpg', 'men'),
(6, 'chujj', 'adam', 'wkladam i spadam', '2026-03-03', 'pizdowice', 'chujj@emial.com', NULL, '1772823008_sample_8bf2001baf434ccbb47d0d976c1bc1dc.jpg', 'slup');

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
('JQVpv2BqxdRGgbtuI2mb8FbXKwxnIJJRscj2PBfp', 6, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:148.0) Gecko/20100101 Firefox/148.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiR050MWprdTRvNzN4QVNMb2dTQ2ptcUFWSnhnZ05sSzlmUGJKTFhsTyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo0OiJtYWluIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Njt9', 1774182226),
('uPeWHg6P9pPWLeEHk7ST5zRCxkNsOnn7BBl6VToN', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:148.0) Gecko/20100101 Firefox/148.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiUXU0YUtoVWZQZlBaNzZpMzJvY1kydWVCakN1dmM3OVlnTXVldTFjWiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo0OiJtYWluIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9', 1774101696);

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
  `aktywny` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nick`, `email`, `password`, `czy_admin`, `id_profil`, `created_at`, `updated_at`, `aktywny`) VALUES
(1, 'chuj', 'chuj@email.com', '$2y$12$O7gCEy/M6rhZzY6QlMXurupyiZWHphm1m3KjvSpU4aGZzq7y6d3XG', 0, 1, NULL, NULL, 1),
(6, 'chujj', 'chujj@email.com', '$2y$12$WyS1etX6/gercTc2nM8az.Exg.QwDMqF7dZ0sKbpiUGQ1BuRheA5i', 0, 6, '2026-03-05 13:56:16', '2026-03-05 13:56:16', 1);

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
  `status` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `zgloszenia`
--

INSERT INTO `zgloszenia` (`id_zgloszenia`, `id_oferty`, `id_profil_wykonawca`, `wiadomosc`, `zatwierdzone`, `status`) VALUES
(1, 2, '6', 'pomoge', 1, 'zatwierdzone'),
(2, 3, '1', 'passattttt', 1, 'zatwierdzone'),
(3, 1, '6', 'maklowicz', 0, 'anulowane'),
(4, 4, '1', 'to jest nie zgloszony', 0, 'aktywne');

--
-- Indeksy dla zrzutów tabel
--

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
-- Indeksy dla tabeli `oferty`
--
ALTER TABLE `oferty`
  ADD PRIMARY KEY (`id_oferty`);

--
-- Indeksy dla tabeli `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

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
-- AUTO_INCREMENT for dumped tables
--

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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `oferty`
--
ALTER TABLE `oferty`
  MODIFY `id_oferty` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `powiadomienia`
--
ALTER TABLE `powiadomienia`
  MODIFY `id_powiadomienia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `profil`
--
ALTER TABLE `profil`
  MODIFY `id_profil` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `zgloszenia`
--
ALTER TABLE `zgloszenia`
  MODIFY `id_zgloszenia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
