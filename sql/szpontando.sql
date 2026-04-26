-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 26, 2026 at 09:31 PM
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
-- Struktura tabeli dla tabeli `email_verifications`
--

CREATE TABLE `email_verifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `email_verifications`
--

INSERT INTO `email_verifications` (`id`, `email`, `token`, `created_at`, `updated_at`) VALUES
(1, 'chuj16@email.com', '$2y$12$xiEWSFUkEYJs2Vb/q1/ucu/jvfcV666fU/IboF/GyU0EEUHgrnbRC', '2026-04-26 17:07:40', NULL),
(2, 'chuj17@email.com', '$2y$12$G319HQTCyXNUYWLx0mgDsO29iUo80N6jCU5.Z09gzt.WHXliKSmT6', '2026-04-26 17:15:31', NULL),
(3, 'chuj18@email.com', '$2y$12$Dza6196X6jioLlrY05ATY.oTuMIhB43IO0A17kNBCGQ9MMsk3UGba', '2026-04-26 17:19:52', NULL),
(4, 'chuj19@email.com', '$2y$12$OEu5kqtidHKw0Z1FO7wnceLVzWWEgPKoltHWpuwIYU9vOskJ7CujC', '2026-04-26 17:21:11', NULL),
(5, 'chuj20@email.com', '$2y$12$GZP95Yjwt2xlM8.Ru9fD1ehl4Wlu8RCnoaeN8m.Wd7xJmI8odRaaq', '2026-04-26 17:27:01', NULL),
(6, 'chuj21@email.com', '$2y$12$Eocao5.UprLBmV4CduQWburVl4pE53UTm0CfSTDW.pZXkoVZxJQHm', '2026-04-26 17:30:40', NULL);

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
(7, '2026_04_26_185303_create_email_verifications_table', 4);

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
(2, 6, 3, 4, 4, 'chujjjjj', 'gospodarz', '2026-04-25 19:05:20'),
(3, 8, 3, 4, 4, 'pracu chuj', 'gospodarz', '2026-04-25 19:05:33'),
(4, 7, 4, 3, 4, 'hgfgjsdef', 'gospodarz', '2026-04-25 19:06:13');

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
(1, 1, 'Częstochowa', 'po_imprezie', 999, '2029-02-22 22:22:00', 'obrona', 'aktywna', '2026-03-22 14:05:08', '2026-03-22 14:05:08'),
(2, 1, 'warszawa', 'cały_dom', 6767, '2100-11-22 22:12:00', 'zamek', 'anulowane', '2026-03-22 14:05:51', '2026-03-22 14:05:51'),
(3, 2, 'opole', 'kuweta_kota', 150, '2067-02-22 12:00:00', 'mam kota', 'zaakceptowana', '2026-03-22 14:09:17', '2026-03-22 14:09:17'),
(4, 2, 'opole', 'rower', 200, '2028-05-14 11:50:00', 'rower ryszarda, mocno ubłocony', 'aktywna', '2026-03-22 14:10:54', '2026-03-22 14:10:54'),
(5, 3, '2w31qe3e', 'ogrod_tarasy', 2143, '2026-04-24 21:32:00', 'chuj', 'wygaslo', '2026-04-21 21:10:09', '2026-04-21 21:14:25'),
(6, 3, 'wefrat', 'brud_ciężki_przemysłowy', 23, '2026-04-24 23:45:00', '1324etrf', 'wygaslo', '2026-04-21 21:15:13', '2026-04-21 21:15:13'),
(7, 3, 'chuj', 'mycie_okien', 2137, '2066-07-05 23:07:00', 'chuj', 'zaakceptowana', '2026-04-25 16:36:02', '2026-04-25 16:36:02'),
(8, 4, 'sdafghj', 'kuweta_kota', 245, '2028-02-23 05:06:00', '4567', 'zaakceptowana', '2026-04-25 17:59:05', '2026-04-25 17:59:05'),
(9, 3, 'pizda', 'wybrane_pomieszczenia', 2456575, '2026-05-25 00:00:00', 'pizda', 'zaakceptowana', '2026-04-25 18:59:15', '2026-04-25 18:59:15');

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
(6, 'nowe zgloszenie do twojej oferty', 'uzytkownik chuj zglosil sie do twojego zgloszenia', 0, 2),
(7, 'twoje zgłoszenie zostało zaakceptowane', 'Twoje zgłoszenie do oferty zostało zaakceptowane', 0, 3),
(8, 'nowe zgloszenie do twojej oferty', 'uzytkownik chujj zglosil sie do twojego zgloszenia', 0, 3),
(9, 'twoje zgłoszenie zostało zaakceptowane', 'Twoje zgłoszenie do oferty zostało zaakceptowane', 0, 4),
(10, 'nowe zgloszenie do twojej oferty', 'uzytkownik chuj zglosil sie do twojego zgloszenia', 0, 4),
(11, 'twoje zgłoszenie zostało zaakceptowane', 'Twoje zgłoszenie do oferty zostało zaakceptowane', 0, 3),
(12, 'Nowa propozycja terminu', 'Wykonawca chuj zaproponował nowy termin: 2026-04-25T20:04.', 0, 4),
(13, 'nowe zgloszenie do twojej oferty', 'uzytkownik chujj zglosil sie do twojego zgloszenia', 0, 3),
(14, 'twoje zgłoszenie zostało zaakceptowane', 'Twoje zgłoszenie do oferty zostało zaakceptowane', 0, 4),
(15, 'Zaproponowano termin wykonania', 'Gospodarz zaproponował termin: 3234-02-20 23:02', 0, 4),
(16, 'Nowa propozycja terminu', 'Wykonawca chujj zaproponował nowy termin: 3238-04-21 04:07.', 0, 3),
(17, 'Termin zaakceptowany', 'Wykonawca zaakceptował Twój termin: 3238-04-21 04:07', 0, 3);

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
(1, 'jan321', 'jan', 'kowalski', '2008-03-22', 'goglin', 'janpv@email.com', NULL, '1774188185_kmicic.jpg', 'men'),
(2, 'ala321', 'ala', 'berewiczówna', '2000-03-22', 'zawada', 'alapv@email.com', NULL, '1774188489_bb8f3b58a545b4436a036bc91b135dd5.jpg', 'women'),
(3, 'chuj', 'chuj', 'chuj', '1000-04-25', 'opole', 'chujjjj@email', 4, NULL, 'women'),
(4, 'chujj', 'chujj', 'chujj', '1000-04-25', 'opole', 'chujjjjjj@email', 4, NULL, 'women'),
(5, 'max', NULL, NULL, NULL, NULL, NULL, NULL, 'default.jpg', NULL),
(6, 'chuj11', NULL, NULL, NULL, NULL, NULL, NULL, 'default.jpg', NULL),
(7, 'chuj12', NULL, NULL, NULL, NULL, NULL, NULL, 'default.jpg', NULL),
(8, 'chuj13', NULL, NULL, NULL, NULL, NULL, NULL, 'default.jpg', NULL),
(9, 'chuj14', NULL, NULL, NULL, NULL, NULL, NULL, 'default.jpg', NULL),
(11, 'chuj16', NULL, NULL, NULL, NULL, NULL, NULL, 'default.jpg', NULL),
(12, 'chuj17', NULL, NULL, NULL, NULL, NULL, NULL, 'default.jpg', NULL),
(13, 'chuj18', NULL, NULL, NULL, NULL, NULL, NULL, 'default.jpg', NULL),
(14, 'chuj19', NULL, NULL, NULL, NULL, NULL, NULL, 'default.jpg', NULL),
(15, 'chuj20', NULL, NULL, NULL, NULL, NULL, NULL, 'default.jpg', NULL),
(16, 'chuj21', NULL, NULL, NULL, NULL, NULL, NULL, 'default.jpg', NULL);

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
('hmgdaLSXUhpxcUxsnAVQu24iM2agUyDYnF6xGTTz', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 OPR/130.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoib1ZnWUJ6QzN6ZW1LWk4wYjFRcFozT0FjUERnM2ZBZ2JsdzFMYUVwZyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo0OiJtYWluIjt9fQ==', 1777231842);

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
(1, 'jan321', 'jan@email.com', '$2y$12$TjKFWRPsCPgetWepQDqHSeSMCs18KnBbYcVEudhOjFNtyFiePi.HG', 0, 1, '2026-03-22 12:59:23', '2026-03-22 12:59:23', 1),
(2, 'ala321', 'ala@email.com', '$2y$12$FPpY1P0gIfZFFQkFlFq4/eP/liFz8Cinw4GP6dxBMr/w60DqKiPtW', 0, 2, '2026-03-22 12:59:47', '2026-03-22 12:59:47', 1),
(3, 'chuj', 'chuj@email.com', '$2y$12$zM9NBxuaPiQhBVtJgNmmpex5e2JeEgxcxU1nBorw3LAKMzX8pvYxa', 0, 3, '2026-04-21 19:08:19', '2026-04-21 19:08:19', 1),
(4, 'chujj', 'chujj@email.com', '$2y$12$oe6KawzT.1LcPTTm1YhMUurGmd.4oxQdx5RLq2mmi68IOra6EkAgK', 0, 4, '2026-04-25 14:34:54', '2026-04-25 14:34:54', 1),
(5, 'max', 'maxprause@wp.pl', '$2y$12$8Yxx7KnGPAWopygIk6ADj.16CRaUJjQ/c5KDt1HwDSctM3fPwNMde', 0, 5, '2026-04-26 14:47:26', '2026-04-26 16:08:09', 1),
(6, 'chuj11', 'chuj11@email.com', '$2y$12$OtsZXT9EskXUX2UTS/7vgezuB8J7tpajDYyqB4.FQ4mFFbwFJFCqG', 0, 6, '2026-04-26 16:29:28', '2026-04-26 16:29:28', 0),
(7, 'chuj12', 'chuj12@email.com', '$2y$12$9GJIgC6gGau1EgFuIxN7gu9kaT4n8PL0KToDNWT1ew16oHUWuXlCm', 0, 7, '2026-04-26 16:47:03', '2026-04-26 16:47:03', 0),
(8, 'chuj13', 'chuj13@email.com', '$2y$12$qk/Hxa/cOJwTEtU7rCaYSuP9CD3GRS7DLTWoWuervcNQEigHov1LK', 0, 8, '2026-04-26 17:02:38', '2026-04-26 17:02:38', 0),
(9, 'chuj14', 'chuj14@email.com', '$2y$12$NCKWXwaNwzheDuxYLsggFuTeNrqFGg5FklGL54KT.GJhE6HbP2Z22', 0, 9, '2026-04-26 17:03:51', '2026-04-26 17:03:51', 0),
(11, 'chuj16', 'chuj16@email.com', '$2y$12$Jf5L6aLGbR0InJxZAwyZIOCE0J3yDz52GmpYhDpS1cYMjPA1FITw6', 0, 11, '2026-04-26 17:07:40', '2026-04-26 17:07:40', 0),
(12, 'chuj17', 'chuj17@email.com', '$2y$12$uhcFsbXZBgony94lVGSac.Zhi1BW3xkR5eLoPL.P17fkWUVm4vv5K', 0, 12, '2026-04-26 17:15:31', '2026-04-26 17:15:31', 0),
(13, 'chuj18', 'chuj18@email.com', '$2y$12$5up3JlaEjl1ONQ89kuEyU.NwpninqzofwZKVgl.cW1xDxgutDVWga', 0, 13, '2026-04-26 17:19:51', '2026-04-26 17:19:51', 0),
(14, 'chuj19', 'chuj19@email.com', '$2y$12$8akwBp6sp2OpTI1eJ5p.je/bMcMVnxSMUjHorl7jAo.RgWQ/dMIW6', 0, 14, '2026-04-26 17:21:11', '2026-04-26 17:21:11', 0),
(15, 'chuj20', 'chuj20@email.com', '$2y$12$cZlfHzhILr2F8vI8AvUXtOwLv27JAOtC5Q./FhPA0r2jBYMUqv6/W', 0, 15, '2026-04-26 17:27:01', '2026-04-26 17:27:01', 0),
(16, 'chuj21', 'chuj21@email.com', '$2y$12$VqCJooKMe6JLKpHY71Mt4eTYRk5j0KyUCBkxQgqwZNyyBH.MKFeSy', 0, 16, '2026-04-26 17:30:40', '2026-04-26 17:30:40', 0);

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
(2, 1, '2', 'dobra cena, chętnie się zgłoszę', 0, 'aktywne', NULL, 0, 0, NULL),
(3, 4, '1', 'lubie czyscic rowery', 0, 'aktywne', NULL, 0, 0, NULL),
(4, 3, '1', 'kocham zwierzęta', 0, 'aktywne', NULL, 0, 0, NULL),
(5, 3, '3', 'no dawaj', 1, 'zatwierdzone', NULL, 0, 0, NULL),
(6, 7, '4', 'chujj', 1, 'zatwierdzone', '2026-07-06 12:02:00', 1, 1, '2026-07-06 12:02:00'),
(7, 8, '3', 'herdtksjgfnyhlu', 1, 'zatwierdzone', '2026-04-25 20:35:00', 1, 1, '2026-04-25 20:35:00'),
(8, 9, '4', 'pizda', 1, 'zatwierdzone', '3238-04-21 04:07:00', 1, 0, '3238-04-21 04:07:00');

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
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `email_verifications`
--
ALTER TABLE `email_verifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `oceny`
--
ALTER TABLE `oceny`
  MODIFY `id_oceny` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `oferty`
--
ALTER TABLE `oferty`
  MODIFY `id_oferty` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `powiadomienia`
--
ALTER TABLE `powiadomienia`
  MODIFY `id_powiadomienia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `profil`
--
ALTER TABLE `profil`
  MODIFY `id_profil` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `zgloszenia`
--
ALTER TABLE `zgloszenia`
  MODIFY `id_zgloszenia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
