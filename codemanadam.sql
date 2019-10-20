-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 20 Eki 2019, 12:14:08
-- Sunucu sürümü: 10.1.40-MariaDB
-- PHP Sürümü: 7.3.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `codemanadam`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `groups`
--

CREATE TABLE `groups` (
  `group_id` int(11) NOT NULL,
  `group_name` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `group_child` int(11) NOT NULL,
  `group_permission` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `group_created_at` datetime NOT NULL,
  `group_updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `group_is_deleted` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `license`
--

CREATE TABLE `license` (
  `license_id` int(11) NOT NULL,
  `license_user_id` int(11) NOT NULL,
  `license_type` tinyint(4) NOT NULL,
  `license_start_date` date NOT NULL,
  `license_end_date` date NOT NULL,
  `license_is_deleted` int(11) NOT NULL DEFAULT '0',
  `license_created_at` datetime NOT NULL,
  `license_update_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_520_ci;

--
-- Tablo döküm verisi `license`
--

INSERT INTO `license` (`license_id`, `license_user_id`, `license_type`, `license_start_date`, `license_end_date`, `license_is_deleted`, `license_created_at`, `license_update_at`) VALUES
(1, 2, 1, '2019-10-12', '2020-10-30', 0, '2019-10-12 00:00:00', '2019-10-20 13:12:45');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `user_level` tinyint(4) NOT NULL,
  `user_group` int(11) NOT NULL,
  `user_email` varchar(50) COLLATE utf8_unicode_520_ci NOT NULL,
  `user_password` varchar(100) COLLATE utf8_unicode_520_ci NOT NULL,
  `user_name` varchar(50) COLLATE utf8_unicode_520_ci NOT NULL,
  `user_surname` varchar(50) COLLATE utf8_unicode_520_ci NOT NULL,
  `user_phone` varchar(10) COLLATE utf8_unicode_520_ci NOT NULL,
  `user_token` varchar(100) COLLATE utf8_unicode_520_ci DEFAULT NULL,
  `user_last_ip` varchar(50) COLLATE utf8_unicode_520_ci NOT NULL,
  `user_last_login` datetime NOT NULL,
  `user_ip_code` varchar(10) COLLATE utf8_unicode_520_ci DEFAULT NULL,
  `user_attempts` tinyint(4) DEFAULT NULL,
  `user_attempt_time` time NOT NULL,
  `user_is_deleted` tinyint(4) NOT NULL DEFAULT '0',
  `user_created_at` datetime NOT NULL,
  `user_updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_520_ci;

--
-- Tablo döküm verisi `users`
--

INSERT INTO `users` (`user_id`, `user_level`, `user_group`, `user_email`, `user_password`, `user_name`, `user_surname`, `user_phone`, `user_token`, `user_last_ip`, `user_last_login`, `user_ip_code`, `user_attempts`, `user_attempt_time`, `user_is_deleted`, `user_created_at`, `user_updated_at`) VALUES
(1, 1, 3, 'admin@test.com', '6fd742a61bd034804c00c49b18045020', 'Admin', 'Test', '1111111111', NULL, '::1', '2019-10-20 13:05:05', NULL, NULL, '12:34:03', 0, '0000-00-00 00:00:00', '2019-10-20 13:05:05'),
(2, 2, 4, 'user@test.com', '6fd742a61bd034804c00c49b18045020', 'User', 'Test', '', NULL, '::1', '2019-10-20 13:12:47', NULL, NULL, '00:00:00', 0, '0000-00-00 00:00:00', '2019-10-20 13:12:47');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `user_devices`
--

CREATE TABLE `user_devices` (
  `user_device_id` int(11) NOT NULL,
  `user_device_user_id` int(11) NOT NULL,
  `user_device_ip_address` varchar(20) COLLATE utf8_unicode_520_ci NOT NULL,
  `user_device_log` text COLLATE utf8_unicode_520_ci NOT NULL,
  `user_device_created_at` datetime NOT NULL,
  `user_device_updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `user_device_is_deleted` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_520_ci;

--
-- Tablo döküm verisi `user_devices`
--

INSERT INTO `user_devices` (`user_device_id`, `user_device_user_id`, `user_device_ip_address`, `user_device_log`, `user_device_created_at`, `user_device_updated_at`, `user_device_is_deleted`) VALUES
(1, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36', '2019-10-13 14:01:30', '2019-10-13 14:01:30', 0),
(2, 2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36', '2019-10-13 14:01:41', '2019-10-13 14:01:41', 0);

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`group_id`);

--
-- Tablo için indeksler `license`
--
ALTER TABLE `license`
  ADD PRIMARY KEY (`license_id`);

--
-- Tablo için indeksler `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- Tablo için indeksler `user_devices`
--
ALTER TABLE `user_devices`
  ADD PRIMARY KEY (`user_device_id`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `groups`
--
ALTER TABLE `groups`
  MODIFY `group_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `license`
--
ALTER TABLE `license`
  MODIFY `license_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Tablo için AUTO_INCREMENT değeri `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Tablo için AUTO_INCREMENT değeri `user_devices`
--
ALTER TABLE `user_devices`
  MODIFY `user_device_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
