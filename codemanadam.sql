-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 22 Eyl 2019, 11:02:44
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
(1, 1, 2, '2019-09-13', '2023-09-30', 0, '2019-09-13 11:29:00', '2019-09-21 23:13:56');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `user_email` varchar(50) COLLATE utf8_unicode_520_ci NOT NULL,
  `user_password` varchar(100) COLLATE utf8_unicode_520_ci NOT NULL,
  `user_name` varchar(50) COLLATE utf8_unicode_520_ci NOT NULL,
  `user_surname` varchar(50) COLLATE utf8_unicode_520_ci NOT NULL,
  `user_token` varchar(100) COLLATE utf8_unicode_520_ci DEFAULT NULL,
  `user_reset_code` varchar(100) COLLATE utf8_unicode_520_ci DEFAULT NULL,
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

INSERT INTO `users` (`user_id`, `user_email`, `user_password`, `user_name`, `user_surname`, `user_token`, `user_reset_code`, `user_last_ip`, `user_last_login`, `user_ip_code`, `user_attempts`, `user_attempt_time`, `user_is_deleted`, `user_created_at`, `user_updated_at`) VALUES
(1, 'test@test.com', '6fd742a61bd034804c00c49b18045020', 'User', 'Test', NULL, NULL, '::1', '2019-09-22 09:58:51', NULL, NULL, '21:04:55', 0, '2019-09-12 16:09:00', '2019-09-22 09:58:51');

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
(1, 1, '::2', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36', '2019-09-20 21:01:58', '2019-09-21 21:51:24', 0);

--
-- Dökümü yapılmış tablolar için indeksler
--

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
-- Tablo için AUTO_INCREMENT değeri `license`
--
ALTER TABLE `license`
  MODIFY `license_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Tablo için AUTO_INCREMENT değeri `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Tablo için AUTO_INCREMENT değeri `user_devices`
--
ALTER TABLE `user_devices`
  MODIFY `user_device_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
