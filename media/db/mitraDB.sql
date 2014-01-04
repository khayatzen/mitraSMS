-- phpMyAdmin SQL Dump
-- version 3.5.8.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 05, 2014 at 12:29 AM
-- Server version: 5.5.31-0ubuntu0.13.04.1
-- PHP Version: 5.4.9-4ubuntu2.3

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT=0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `borneoclimate_fresh`
--

-- --------------------------------------------------------

--
-- Table structure for table `auth_ci_sessions`
--

CREATE TABLE IF NOT EXISTS `auth_ci_sessions` (
  `session_id` varchar(40) CHARACTER SET utf8 NOT NULL DEFAULT '0',
  `ip_address` varchar(16) CHARACTER SET utf8 NOT NULL DEFAULT '0',
  `user_agent` varchar(150) CHARACTER SET utf8 NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `auth_ci_sessions`
--

INSERT INTO `auth_ci_sessions` (`session_id`, `ip_address`, `user_agent`, `last_activity`, `user_data`) VALUES
('4f7cc158b16c1468b094f231be3eb90e', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.22', 1388854678, ''),
('7ad6be7abbf44d0023cac9f2caf3434d', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.22', 1388853871, 'a:5:{s:7:"user_id";s:1:"1";s:7:"id_user";s:1:"1";s:8:"username";s:5:"admin";s:6:"status";s:1:"1";s:5:"level";s:13:"administrator";}'),
('a5844accee3f5809d155aba1511c97aa', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.22', 1388854678, 'a:5:{s:7:"user_id";s:1:"1";s:7:"id_user";s:1:"1";s:8:"username";s:5:"admin";s:6:"status";s:1:"1";s:5:"level";s:13:"administrator";}'),
('bf51ecc12875720dd4f48c7ee766abc0', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.22', 1388853871, '');

-- --------------------------------------------------------

--
-- Table structure for table `auth_login_attempts`
--

CREATE TABLE IF NOT EXISTS `auth_login_attempts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(40) NOT NULL,
  `login` varchar(50) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `auth_users`
--

CREATE TABLE IF NOT EXISTS `auth_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) CHARACTER SET utf8 NOT NULL,
  `password` varchar(255) CHARACTER SET utf8 NOT NULL,
  `email` varchar(100) CHARACTER SET utf8 NOT NULL,
  `activated` tinyint(1) NOT NULL DEFAULT '1',
  `banned` tinyint(1) NOT NULL DEFAULT '0',
  `ban_reason` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `new_password_key` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `new_password_requested` datetime DEFAULT NULL,
  `new_email` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `new_email_key` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `last_ip` varchar(40) CHARACTER SET utf8 NOT NULL,
  `last_login` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=56 ;

--
-- Dumping data for table `auth_users`
--

INSERT INTO `auth_users` (`id`, `username`, `password`, `email`, `activated`, `banned`, `ban_reason`, `new_password_key`, `new_password_requested`, `new_email`, `new_email_key`, `last_ip`, `last_login`, `created`, `modified`) VALUES
(1, 'admin', '$2a$08$356IHYlKr45bOYWv9VO4IOqm3yc03O4JK3iI4tLz2L8zkaTVwvqGy', 'pengelola@domain.com', 1, 0, NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2014-01-04 23:58:19', '2011-10-07 23:20:35', '2014-01-04 16:58:19');

-- --------------------------------------------------------

--
-- Table structure for table `auth_user_autologin`
--

CREATE TABLE IF NOT EXISTS `auth_user_autologin` (
  `key_id` char(32) COLLATE utf8_bin NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `user_agent` varchar(150) COLLATE utf8_bin NOT NULL,
  `last_ip` varchar(40) COLLATE utf8_bin NOT NULL,
  `last_login` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`key_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `auth_user_profiles`
--

CREATE TABLE IF NOT EXISTS `auth_user_profiles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `level` enum('subscriber','contributor','author','administrator') NOT NULL DEFAULT 'contributor',
  `real_name` varchar(100) NOT NULL,
  `phone_number` varchar(25) NOT NULL,
  `village_id` varchar(20) NOT NULL,
  `address` varchar(200) NOT NULL,
  `address_location` varchar(50) NOT NULL,
  `country` varchar(20) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=52 ;

--
-- Dumping data for table `auth_user_profiles`
--

INSERT INTO `auth_user_profiles` (`id`, `user_id`, `level`, `real_name`, `phone_number`, `village_id`, `address`, `address_location`, `country`, `website`) VALUES
(1, 1, 'administrator', 'Administrator', '085654098688', '', '', '', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `blacklist_numbers`
--

CREATE TABLE IF NOT EXISTS `blacklist_numbers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `phone_number` varchar(15) NOT NULL,
  `reason` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=30 ;

--
-- Dumping data for table `blacklist_numbers`
--

INSERT INTO `blacklist_numbers` (`id`, `phone_number`, `reason`) VALUES
(1, 'INDOSAT', 'Black Message'),
(2, 'TELKOMSEL', 'Black Message'),
(3, '+6281286279217', 'Black Message'),
(4, '+6282191782910', 'Black Message'),
(5, '+6282190614317', 'Black Message'),
(6, '+6282190614317', 'Black Message'),
(7, '+6287881032317', 'Black Message'),
(8, '+6287881032317', 'Black Message'),
(9, '+6281215303146', 'Black Message'),
(10, '+6281215303146', 'Black Message'),
(11, '+6281215303146', 'Black Message'),
(12, '+6281352977007', 'Black Message'),
(13, '+6287788342380', 'Black Message'),
(14, '+6282191314458', 'Black Message'),
(15, '946701', 'Black Message'),
(16, '946700', 'Black Message'),
(17, '+6281908257658', 'Black Message'),
(18, '+6282192115989', 'Black Message'),
(19, '+6282190612926', 'Black Message'),
(20, '+6289630889894', 'Black Message'),
(21, '+6282126211205', 'Black Message'),
(22, '+6281807577632', 'Black Message'),
(23, '+6281807577632', 'Black Message'),
(24, '+6281806762386', 'Black Message'),
(25, '+6285249685483', 'Black Message'),
(26, '+6285249696658', 'Black Message'),
(27, '6232665', 'Black Message'),
(28, '+6285334104218', 'Black Message'),
(29, '+628119700455', 'Black Message');

-- --------------------------------------------------------

--
-- Table structure for table `daemons`
--

CREATE TABLE IF NOT EXISTS `daemons` (
  `Start` text NOT NULL,
  `Info` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `folder_message_receive`
--

CREATE TABLE IF NOT EXISTS `folder_message_receive` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `id_inbox` int(11) NOT NULL,
  `folder_message_sent_id` int(11) NOT NULL DEFAULT '-1',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `folder_message_sent`
--

CREATE TABLE IF NOT EXISTS `folder_message_sent` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `OutboxInsertIntoDB` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `DestinationNumber` varchar(20) NOT NULL DEFAULT '',
  `id_sentitems` int(11) NOT NULL DEFAULT '-1',
  `replayed_inbox_id` int(11) NOT NULL,
  `id_folder` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `gammu`
--

CREATE TABLE IF NOT EXISTS `gammu` (
  `Version` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `gammu`
--

INSERT INTO `gammu` (`Version`) VALUES
(11);

-- --------------------------------------------------------

--
-- Table structure for table `inbox`
--

CREATE TABLE IF NOT EXISTS `inbox` (
  `UpdatedInDB` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `ReceivingDateTime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `Text` text NOT NULL,
  `SenderNumber` varchar(20) NOT NULL DEFAULT '',
  `Coding` enum('Default_No_Compression','Unicode_No_Compression','8bit','Default_Compression','Unicode_Compression') NOT NULL DEFAULT 'Default_No_Compression',
  `UDH` text NOT NULL,
  `SMSCNumber` varchar(20) NOT NULL DEFAULT '',
  `Class` int(11) NOT NULL DEFAULT '-1',
  `TextDecoded` varchar(160) NOT NULL DEFAULT '',
  `ID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `RecipientID` text NOT NULL,
  `Processed` enum('false','true') NOT NULL DEFAULT 'false',
  `id_folder` int(11) NOT NULL DEFAULT '1',
  `is_broadcast` tinyint(4) NOT NULL DEFAULT '0',
  `readed` enum('true','false') NOT NULL DEFAULT 'false',
  `Filtered` enum('false','true') NOT NULL DEFAULT 'false',
  `published` enum('true','false') NOT NULL DEFAULT 'false',
  UNIQUE KEY `ID` (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `kalkun`
--

CREATE TABLE IF NOT EXISTS `kalkun` (
  `version` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `member`
--

CREATE TABLE IF NOT EXISTS `member` (
  `id_member` int(11) NOT NULL AUTO_INCREMENT,
  `phone_number` text NOT NULL,
  `reg_date` datetime NOT NULL,
  PRIMARY KEY (`id_member`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `outbox`
--

CREATE TABLE IF NOT EXISTS `outbox` (
  `UpdatedInDB` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `InsertIntoDB` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `SendingDateTime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `Text` text,
  `DestinationNumber` varchar(20) NOT NULL DEFAULT '',
  `Coding` enum('Default_No_Compression','Unicode_No_Compression','8bit','Default_Compression','Unicode_Compression') DEFAULT '8bit',
  `UDH` text,
  `Class` int(11) DEFAULT '-1',
  `TextDecoded` varchar(160) NOT NULL DEFAULT '',
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `MultiPart` enum('false','true') DEFAULT 'false',
  `RelativeValidity` int(11) DEFAULT '-1',
  `SenderID` text,
  `SendingTimeOut` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  `DeliveryReport` enum('default','yes','no') DEFAULT 'default',
  `CreatorID` text NOT NULL,
  `is_broadcast` tinyint(1) NOT NULL DEFAULT '0',
  `is_forward` tinyint(1) NOT NULL DEFAULT '0',
  `sender_user_id` bigint(20) NOT NULL,
  UNIQUE KEY `ID` (`ID`),
  FULLTEXT KEY `CreatorID` (`CreatorID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `outbox_multipart`
--

CREATE TABLE IF NOT EXISTS `outbox_multipart` (
  `Text` text,
  `Coding` enum('Default_No_Compression','Unicode_No_Compression','8bit','Default_Compression','Unicode_Compression') DEFAULT '8bit',
  `UDH` text,
  `Class` int(11) DEFAULT '-1',
  `TextDecoded` varchar(160) DEFAULT NULL,
  `ID` int(11) unsigned NOT NULL DEFAULT '0',
  `SequencePosition` int(11) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pbk`
--

CREATE TABLE IF NOT EXISTS `pbk` (
  `ID` int(11) NOT NULL DEFAULT '-1',
  `Name` text NOT NULL,
  `Number` text NOT NULL,
  `id_pbk` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `village_id` varchar(20) NOT NULL,
  `alamat` varchar(75) NOT NULL,
  `latlong` varchar(100) NOT NULL,
  PRIMARY KEY (`id_pbk`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `pbk_groups`
--

CREATE TABLE IF NOT EXISTS `pbk_groups` (
  `Name` text NOT NULL,
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  UNIQUE KEY `ID` (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `phones`
--

CREATE TABLE IF NOT EXISTS `phones` (
  `ID` text NOT NULL,
  `UpdatedInDB` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `InsertIntoDB` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `TimeOut` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `Send` enum('yes','no') NOT NULL DEFAULT 'no',
  `Receive` enum('yes','no') NOT NULL DEFAULT 'no',
  `IMEI` text NOT NULL,
  `Client` text NOT NULL,
  `Battery` int(2) NOT NULL,
  `Signal` int(2) NOT NULL,
  `Received` int(11) NOT NULL DEFAULT '0',
  `Sent` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `phones`
--

INSERT INTO `phones` (`ID`, `UpdatedInDB`, `InsertIntoDB`, `TimeOut`, `Send`, `Receive`, `IMEI`, `Client`, `Battery`, `Signal`, `Received`, `Sent`) VALUES
('BC', '2011-12-01 09:36:43', '2011-11-29 01:27:18', '2011-12-01 09:36:53', 'yes', 'yes', '351047906876822', 'Gammu 1.26.1, Linux, kernel 2.6.32-21-generic-pae (#32-Ubuntu SMP Fri Apr 16 09:39:35 UTC 2010), GCC 4.4', 0, 57, 35, 17),
('', '2011-11-22 09:01:22', '2011-11-21 16:09:19', '2011-11-22 09:01:32', 'yes', 'yes', '355780003331616', 'Gammu 1.28.0, Linux, kernel 3.0.0-1-generic (#rc3~2-BlankOn SMP Sun Jun 19 07:33:08 UTC 2011), GCC 4.5', 0, 0, 32, 9),
('BC', '2011-12-14 21:23:31', '2011-12-14 21:12:06', '2011-12-14 21:23:41', 'yes', 'yes', '350960682187332', 'Gammu 1.26.1, Linux, kernel 2.6.32-21-generic-pae (#32-Ubuntu SMP Fri Apr 16 09:39:35 UTC 2010), GCC 4.4', 0, -1, 12, 0),
('BC', '2013-08-22 07:54:28', '2013-08-20 19:57:46', '2013-08-22 07:54:38', 'yes', 'yes', '355780003263454', 'Gammu 1.28.0, Linux, kernel 2.6.32-5-686 (#1 SMP Fri Feb 15 15:48:27 UTC 2013), GCC 4.4', 0, 63, 0, 0),
('BC', '2012-12-30 06:42:29', '2012-12-29 11:28:57', '2012-12-30 06:42:39', 'yes', 'yes', '350961682184451', 'Gammu 1.28.0, Linux, kernel 2.6.32-5-686 (#1 SMP Sun Sep 23 09:49:36 UTC 2012), GCC 4.4', 0, 57, 14, 8),
('BC', '2013-09-16 07:01:40', '2013-09-05 04:38:28', '2013-09-16 07:01:50', 'yes', 'yes', '351047900186269', 'Gammu 1.28.0, Linux, kernel 2.6.32-5-686 (#1 SMP Fri Feb 15 15:48:27 UTC 2013), GCC 4.4', 0, 75, 401, 255);

-- --------------------------------------------------------

--
-- Table structure for table `plugin`
--

CREATE TABLE IF NOT EXISTS `plugin` (
  `id_plugin` int(11) NOT NULL AUTO_INCREMENT,
  `plugin_name` varchar(50) NOT NULL,
  `plugin_status` enum('true','false') NOT NULL,
  PRIMARY KEY (`id_plugin`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `reg_country`
--

CREATE TABLE IF NOT EXISTS `reg_country` (
  `country_id` int(11) NOT NULL,
  `country_name` varchar(45) DEFAULT NULL,
  `country_coordinate_location` text,
  PRIMARY KEY (`country_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `reg_country`
--

INSERT INTO `reg_country` (`country_id`, `country_name`, `country_coordinate_location`) VALUES
(62, 'Indonesia', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `reg_district`
--

CREATE TABLE IF NOT EXISTS `reg_district` (
  `district_id` varchar(10) NOT NULL,
  `district_name` varchar(45) DEFAULT NULL,
  `district_coordinate_location` text,
  `province_id` varchar(5) NOT NULL,
  PRIMARY KEY (`district_id`),
  KEY `fk_reg_district_reg_province1` (`province_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `reg_district`
--

INSERT INTO `reg_district` (`district_id`, `district_name`, `district_coordinate_location`, `province_id`) VALUES
('6201', 'KOBAR', '', '62'),
('6202', 'KOTIM', '', '62'),
('6203', 'KAPUAS', '', '62'),
('6204', 'BARSEL', '', '62'),
('6205', 'BARUT', '', '62'),
('6271', 'P RAYA', '', '62');

-- --------------------------------------------------------

--
-- Table structure for table `reg_province`
--

CREATE TABLE IF NOT EXISTS `reg_province` (
  `province_id` varchar(5) NOT NULL,
  `province_name` varchar(45) DEFAULT NULL,
  `province_coordinate_location` text,
  `country_id` int(11) NOT NULL,
  PRIMARY KEY (`province_id`),
  KEY `fk_reg_province_reg_country1` (`country_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `reg_province`
--

INSERT INTO `reg_province` (`province_id`, `province_name`, `province_coordinate_location`, `country_id`) VALUES
('62', 'Kalimantan Tengah', NULL, 62);

-- --------------------------------------------------------

--
-- Table structure for table `reg_subdistrict`
--

CREATE TABLE IF NOT EXISTS `reg_subdistrict` (
  `subdistrict_id` varchar(20) NOT NULL,
  `subdistrict_name` varchar(45) DEFAULT NULL,
  `subdistrict_coordinate_location` text,
  `district_id` varchar(10) NOT NULL,
  PRIMARY KEY (`subdistrict_id`),
  KEY `fk_reg_subdistrict_reg_district1` (`district_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `reg_subdistrict`
--

INSERT INTO `reg_subdistrict` (`subdistrict_id`, `subdistrict_name`, `subdistrict_coordinate_location`, `district_id`) VALUES
('6201010', 'Jelai', '', '6201'),
('6201020', 'Sukamara', '', '6201'),
('6201030', 'Balai Riam', '', '6201'),
('6201040', 'Kotawaringin Lama', '', '6201'),
('6201050', 'Arut Selatan', '', '6201'),
('6201060', 'Kumai', '', '6201'),
('6201070', 'Arut Utara', '', '6201'),
('6201080', 'Bulik', '', '6201'),
('6201090', 'Lamandau', '', '6201'),
('6201100', 'Delang', '', '6201'),
('6202010', 'Seruyan Hilir', '', '6202'),
('6202020', 'Mentaya Hilir Selatan', '', '6202'),
('6202030', 'Pulau Hanauti', '', '6202'),
('6202040', 'Mentaya Hilir Utara', '', '6202'),
('6202050', 'Danau Sembuluh', '', '6202'),
('6202060', 'Hanau', '', '6202'),
('6202070', 'Mentawa Baru / Ketapang', '', '6202'),
('6202080', 'Baamang', '', '6202'),
('6202090', 'Kota Besi', '', '6202'),
('6202100', 'Cempaga', '', '6202'),
('6202110', 'Parenggean', '', '6202'),
('6202120', 'Mentaya Hulu', '', '6202'),
('6202121', 'Antang Kalang', '', '6202'),
('6202130', 'Seruyan Tengah', '', '6202'),
('6202140', 'Seruyan Hulu', '', '6202'),
('6202150', 'Katingan Hulu', '', '6202'),
('6202160', 'Marikit', '', '6202'),
('6202170', 'Sanaman Mantikei', '', '6202'),
('6202180', 'Katingan Tengah', '', '6202'),
('6202190', 'Pulau Malan', '', '6202'),
('6202200', 'Tewang S Garing', '', '6202'),
('6202210', 'Katingan Hilir', '', '6202'),
('6202220', 'Tasik Payawan', '', '6202'),
('6202230', 'Kamipang', '', '6202'),
('6202240', 'Katingan Kuala', '', '6202'),
('6202241', 'Mendawai', '', '6202'),
('6203010', 'Kahayan Kuala', '', '6203'),
('6203030', 'Selat', '', '6203'),
('6203040', 'Kapuas Timur', '', '6203'),
('6203050', 'Basarang', '', '6203'),
('6203060', 'Kapuas Hilir', '', '6203'),
('6203070', 'Pulau Petak', '', '6203'),
('6203080', 'Kapuas Murung', '', '6203'),
('6203090', 'Kapuas Barat', '', '6203'),
('6203100', 'Pandih Batut', '', '6203'),
('6203101', 'Maliku', '', '6203'),
('6203110', 'Kahayan Hilir', '', '6203'),
('6203120', 'Kahayan Tengah', '', '6203'),
('6203130', 'Banamatingang', '', '6203'),
('6203140', 'Mantangai', '', '6203'),
('6203150', 'Timpah', '', '6203'),
('6203160', 'Kapuas Tengah', '', '6203'),
('6203170', 'Kapuas Hulu', '', '6203'),
('6203180', 'Tewah', '', '6203'),
('6203190', 'Kurun', '', '6203'),
('6203200', 'Sepang', '', '6203'),
('6203210', 'Rungan', '', '6203'),
('6203220', 'Manuhing', '', '6203'),
('6203230', 'Kahayan Hulu Utara', '', '6203'),
('6204010', 'Jenamas', '', '6204'),
('6204020', 'Dusun Hilir', '', '6204'),
('6204030', 'Karau Kuala', '', '6204'),
('6204040', 'Dusun Selatan', '', '6204'),
('6204060', 'Gunung Bintang Awai', '', '6204'),
('6204070', 'Dusun Tengah', '', '6204'),
('6204080', 'Pematang Karau', '', '6204'),
('6204110', 'Dusun Timur', '', '6204'),
('6205010', 'Montalat', '', '6205'),
('6205020', 'Gunung Timang', '', '6205'),
('6205050', 'Teweh Tengah', '', '6205'),
('6205060', 'Lahei', '', '6205'),
('6205070', 'Laung Tuhup', '', '6205'),
('6205080', 'Murung', '', '6205'),
('6205090', 'Permata Intan', '', '6205'),
('6271010', 'Pahandut', '', '6271'),
('6271020', 'Bukit Batu', '', '6271');

-- --------------------------------------------------------

--
-- Table structure for table `reg_village`
--

CREATE TABLE IF NOT EXISTS `reg_village` (
  `village_id` varchar(20) NOT NULL,
  `village_name` varchar(45) DEFAULT NULL,
  `village_coordinate_location` text,
  `subdistrict_id` varchar(20) NOT NULL,
  PRIMARY KEY (`village_id`),
  KEY `fk_reg_village_reg_subdistrict1` (`subdistrict_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `reg_village`
--

INSERT INTO `reg_village` (`village_id`, `village_name`, `village_coordinate_location`, `subdistrict_id`) VALUES
('6201010001', 'Kuala Jelai', '110.750229,-3.004746', '6201010'),
('6201010003', 'Sungai Bundung', '110.833611,-3.012952', '6201010'),
('6201010004', 'Sungai Raja', '110.872787,-3.044451', '6201010'),
('6201010005', 'Sungai Damar', '110.905304,-3.061159', '6201010'),
('6201010006', 'Pulau Nibung', '110.857735,-2.919537', '6201010'),
('6201010007', 'Sungai Tabuk', '111.016151,-3.055823', '6201010'),
('6201010008', 'Sungai Cabang Darat', '111.169518,-2.981725', '6201010'),
('6201010009', 'Sungai Pasir', '111.252022,-2.940475', '6201010'),
('6201020001', 'Mendawai', '111.156036,-2.694956', '6201020'),
('6201020002', 'Natai Sedawak', '111.189537,-2.743787', '6201020'),
('6201020003', 'Pudu', '111.2043,-2.705176', '6201020'),
('6201020005', 'Karta Mulia', '111.233826,-2.690981', '6201020'),
('6201020006', 'Sukaraja', '111.277122,-2.493108', '6201020'),
('6201020007', 'Pangkalan Muntai', '111.255806,-2.397625', '6201020'),
('6201020008', 'Petarikan', '111.196808,-2.422268', '6201020'),
('6201030001', 'Jihing', '111.154243,-2.378956', '6201030'),
('6201030002', 'Air Dua', '111.138191,-2.351326', '6201030'),
('6201030004', 'Lupu Peruca', '111.227051,-2.309882', '6201030'),
('6201030006', 'Balai Riam', '111.138939,-2.315109', '6201030'),
('6201030008', 'Ajang', '111.220894,-2.259501', '6201030'),
('6201030012', 'Semantun', '111.166153,-2.19508', '6201030'),
('6201030013', 'Laman Baru', '111.274719,-2.237837', '6201030'),
('6201030014', 'Kebnawan', '111.32756,-2.232766', '6201030'),
('6201040001', 'Babuai Baboti', '111.31636,-2.598064', '6201040'),
('6201040002', 'Tempayung', '111.317375,-2.548133', '6201040'),
('6201040003', 'Sakabulin', '111.296997,-2.492089', '6201040'),
('6201040004', 'Kinjil', '111.375717,-2.453256', '6201040'),
('6201040005', 'Kotawaringin Hilir', '111.446846,-2.47994', '6201040'),
('6201040006', 'Riam Durian', '111.361664,-2.426555', '6201040'),
('6201040007', 'Dawak', '111.335243,-2.418685', '6201040'),
('6201040008', 'Kotawaringin Hulu', '111.465187,-2.420801', '6201040'),
('6201040010', 'Rungun', '111.455818,-2.39367', '6201040'),
('6201040011', 'Kondang', '111.445984,-2.37512', '6201040'),
('6201050001', 'Tanjung Putri', '111.372086,-2.916167', '6201050'),
('6201050002', 'Kumpai Batu Bawah', '111.51667,-2.766741', '6201050'),
('6201050003', 'Kumpai Batu Atas', '111.554375,-2.817708', '6201050'),
('6201050004', 'Pasir Panjang', '111.617485,-2.732008', '6201050'),
('6201050005', 'Mandawai', '111.628387,-2.674355', '6201050'),
('6201050006', 'Mendawai Seberang', '111.61203,-2.679029', '6201050'),
('6201050007', 'Raja', '111.633842,-2.668901', '6201050'),
('6201050008', 'Sidorejo', '111.615143,-2.696169', '6201050'),
('6201050009', 'Madurejo', '111.626831,-2.700844', '6201050'),
('6201050010', 'Baru', '111.669685,-2.657994', '6201050'),
('6201050011', 'Raja Seberang', '111.618263,-2.672796', '6201050'),
('6201050012', 'Rangda', '111.605904,-2.430427', '6201050'),
('6201050013', 'Sulung Kenambui', '111.626343,-2.345102', '6201050'),
('6201050014', 'Runtu', '111.693275,-2.346123', '6201050'),
('6201050015', 'Umpang', '111.779335,-2.229389', '6201050'),
('6201050017', 'Medang Sari', '111.666641,-2.500295', '6201050'),
('6201060002', 'Teluk Pulai', '111.780861,-2.953563', '6201060'),
('6201060003', 'Sungai Sekonyer', '111.817551,-2.814541', '6201060'),
('6201060004', 'Kubu', '111.674637,-2.879263', '6201060'),
('6201060005', 'Sungai Bakau', '111.62738,-2.96236', '6201060'),
('6201060006', 'Teluk Bogam', '111.538567,-3.00898', '6201060'),
('6201060007', 'Keraya', '111.514992,-2.988355', '6201060'),
('6201060008', 'Sebuai', '111.465637,-2.938266', '6201060'),
('6201060010', 'Kumai Hilir', '111.719749,-2.739841', '6201060'),
('6201060013', 'Candi', '111.713303,-2.73549', '6201060'),
('6201060014', 'Kumai Hulu', '111.716423,-2.738048', '6201060'),
('6201060015', 'Sungai Bedaun', '111.804031,-2.803922', '6201060'),
('6201060017', 'Amin Jaya', '111.962387,-2.443667', '6201060'),
('6201060018', 'Karangmulya', '111.91851,-2.451256', '6201060'),
('6201060019', 'Margomulyo', '111.864403,-2.369761', '6201060'),
('6201060020', 'Arga Mulya', '111.842957,-2.390877', '6201060'),
('6201060021', 'Kebon Agung', '111.902672,-2.365472', '6201060'),
('6201060022', 'Sido Mulyo', '111.908943,-2.392527', '6201060'),
('6201060023', 'Pangkalan Tiga', '111.810646,-2.487251', '6201060'),
('6201060024', 'Pandu Sanjaya', '111.759186,-2.474367', '6201060'),
('6201060026', 'Laada Mandala Jaya', '111.760559,-2.501339', '6201060'),
('6201060027', 'Pangkalan Banteng', '111.863869,-2.492468', '6201060'),
('6201060028', 'Sumber Agung', '111.746468,-2.575431', '6201060'),
('6201060029', 'Purbasari', '111.72821,-2.598912', '6201060'),
('6201060030', 'Sungai Rangit Jaya', '111.781136,-2.596734', '6201060'),
('6201060031', 'Bumi Harjo', '111.75248,-2.640055', '6201060'),
('6201070001', 'Nanga Mua', '111.873978,-2.235942', '6201070'),
('6201080001', 'Batu Kotam', '111.46048,-2.295225', '6201080'),
('6201080002', 'Palih Baru', '111.4636,-2.278467', '6201080'),
('6201080010', 'Bunut', '111.457382,-2.121259', '6201080'),
('6201080011', 'Sungai Mentawa', '111.431541,-2.073835', '6201080'),
('6201080015', 'Sumber Cahaya', '111.46476,-1.893417', '6201080'),
('6201080016', 'Pedongatan', '111.483711,-1.860072', '6201080'),
('6201080017', 'Nuangan', '111.49575,-1.933819', '6201080'),
('6201080019', 'Nanga Pelikodan', '111.513901,-1.989706', '6201080'),
('6201080020', 'Sungkup', '111.550575,-1.973308', '6201080'),
('6201080021', 'Nanga Koring', '111.540306,-1.926905', '6201080'),
('6201080022', 'Toka', '111.526588,-1.82955', '6201080'),
('6201080024', 'Merambang', '111.536079,-1.801456', '6201080'),
('6201080025', 'Batu Tunggal', '111.53418,-1.812845', '6201080'),
('6201080026', 'Nanga Kemujan', '111.514442,-1.695154', '6201080'),
('6201080027', 'Lubuk Hiju', '111.644012,-1.74787', '6201080'),
('6201080028', 'Batu Ampar', '111.629356,-1.872425', '6201080'),
('6201080029', 'Topalan', '111.693535,-2.000571', '6201080'),
('6201080030', 'Nanuah', '111.646957,-1.997321', '6201080'),
('6201080031', 'Modang Mas', '111.68306,-2.015012', '6201080'),
('6201080033', 'Melata', '111.607353,-1.984593', '6201080'),
('6201080034', 'Bukit Makmur', '111.618439,-2.09516', '6201080'),
('6201080035', 'Bukit Raya', '111.637573,-2.074582', '6201080'),
('6201080037', 'Bukit Harum', '111.640457,-2.127653', '6201080'),
('6201080038', 'Purworejo', '111.509972,-2.232871', '6201080'),
('6201080039', 'Bina Bhakti', '111.535301,-2.201304', '6201080'),
('6201090001', 'Cuhai', '111.090286,-1.842456', '6201090'),
('6201090002', 'Kawa', '111.105347,-1.877733', '6201090'),
('6201090003', 'Panopa', '111.159309,-1.935824', '6201090'),
('6201090004', 'Suja', '111.263458,-1.926454', '6201090'),
('6201090005', 'Sekoban', '111.360435,-1.91723', '6201090'),
('6201090006', 'Bakonsu', '111.373505,-1.955435', '6201090'),
('6201090007', 'Nanga Belantikan', '111.422592,-1.985506', '6201090'),
('6201090008', 'Sungai Buluh', '111.444206,-1.942379', '6201090'),
('6201090009', 'Tangga Batu', '111.400543,-1.828031', '6201090'),
('6201090010', 'Tapin Bini', '111.315208,-1.90418', '6201090'),
('6201090011', 'Karang Taba', '111.118248,-1.882035', '6201090'),
('6201090012', 'Tanjung Beringin', '111.080818,-1.814923', '6201090'),
('6201090013', 'Sungai Tuat', '111.084618,-1.787477', '6201090'),
('6201090014', 'Bayat', '111.400925,-1.781334', '6201090'),
('6201090015', 'Balibi', '111.407379,-1.791965', '6201090'),
('6201090016', 'Karang Besi', '111.437752,-1.734637', '6201090'),
('6201090017', 'Benuatan', '111.423355,-1.645929', '6201090'),
('6201090018', 'Kahingai', '111.41143,-1.582319', '6201090'),
('6201090019', 'Nanga Matu', '111.396667,-1.546538', '6201090'),
('6201090020', 'Bintang Mengalih', '111.370537,-1.514733', '6201090'),
('6201090021', 'Petarikan', '111.406891,-1.522116', '6201090'),
('6201100001', 'Batu Tambun', '111.106201,-1.780077', '6201100'),
('6201100002', 'Kanipan', '111.16729,-1.752974', '6201100'),
('6201100003', 'Ginih', '111.176216,-1.712367', '6201100'),
('6201100004', 'Riam Penahan', '111.071358,-1.735766', '6201100'),
('6201100005', 'Sepoyu', '111.075485,-1.697505', '6201100'),
('6201100006', 'Benakitan', '111.153908,-1.708131', '6201100'),
('6201100007', 'Liku', '111.161812,-1.679609', '6201100'),
('6201100008', 'Mengkalang', '111.160118,-1.645158', '6201100'),
('6201100009', 'Riam Tinggi', '111.039749,-1.700022', '6201100'),
('6201100010', 'Landau Kantu', '111.029175,-1.684921', '6201100'),
('6201100011', 'Nyalang', '111.0186,-1.669064', '6201100'),
('6201100012', 'Lopus', '111.036728,-1.63861', '6201100'),
('6201100013', 'Kubung', '110.90966,-1.634425', '6201100'),
('6201100014', 'Sekombulan', '110.916183,-1.604782', '6201100'),
('6201100015', 'Kiudangan', '111.031693,-1.603876', '6201100'),
('6201100016', 'Penyombaan', '111.058868,-1.588125', '6201100'),
('6201100017', 'Karang Mas', '111.201538,-1.537846', '6201100'),
('6201100018', 'Kina', '111.141449,-1.513865', '6201100'),
('6201100019', 'Jemuat', '111.140625,-1.48768', '6201100'),
('6202010011', 'Tanggul Harapan (D-1)', '112.427094,-3.210461', '6202010'),
('6202010012', 'Tanjung Rangas', '112.350739,-3.18068', '6202010'),
('6202010013', 'Muara Dua', '112.292755,-3.065477', '6202010'),
('6202010014', 'Jahitan', '112.27729,-2.999848', '6202010'),
('6202010015', 'Baung', '112.237625,-2.87896', '6202010'),
('6202020001', 'Ujung Pandaran', '112.991615,-3.149694', '6202020'),
('6202020002', 'Lampuyang', '112.941734,-3.063925', '6202020'),
('6202020003', 'Basawang', '112.994354,-2.933389', '6202020'),
('6202020004', 'Perebok', '113.000504,-2.919857', '6202020'),
('6202020005', 'Saramban', '112.989433,-2.900173', '6202020'),
('6202020006', 'Samuda Besar', '112.982048,-2.881105', '6202020'),
('6202020007', 'Samuda Kecil', '112.97036,-2.867573', '6202020'),
('6202020008', 'Samuda Kota', '112.961754,-2.85281', '6202020'),
('6202020009', 'Basirih Hilir', '112.94268,-2.824515', '6202020'),
('6202020010', 'Jaya Ketapa', '112.928535,-2.796835', '6202020'),
('6202020011', 'Basirih Hulu', '112.915619,-2.790069', '6202020'),
('6202020012', 'Jaya Karet', '112.90947,-2.778382', '6202020'),
('6202030001', 'Satiruk', '113.081535,-3.021229', '6202030'),
('6202030002', 'Bapinang Hilir Laut', '113.028801,-2.942616', '6202030'),
('6202030003', 'Bapinang Hilir', '113.014648,-2.9094', '6202030'),
('6202030004', 'Bapinang Hulu', '112.953758,-2.803601', '6202030'),
('6202030005', 'Makarti Jaya', '112.998459,-2.787107', '6202030'),
('6202030006', 'Rawa Sari', '112.997124,-2.835189', '6202030'),
('6202040001', 'Pondok Damar', '112.586777,-2.602307', '6202040'),
('6202040002', 'Natai Baru', '112.671448,-2.601123', '6202040'),
('6202040003', 'Bagendang Tengah', '112.717636,-2.637242', '6202040'),
('6202040004', 'Bagendang Hilir', '112.900238,-2.763004', '6202040'),
('6202040005', 'Bagendang Hulu', '112.902397,-2.731249', '6202040'),
('6202050001', 'Telaga Pulang', '112.259262,-2.852769', '6202050'),
('6202050002', 'Sembuluh II', '112.366051,-2.702022', '6202050'),
('6202050003', 'Cempaka Baru', '112.231071,-2.816613', '6202050'),
('6202050004', 'Palingkau', '112.209152,-2.773341', '6202050'),
('6202050005', 'Ulak Batu', '112.187347,-2.756071', '6202050'),
('6202050006', 'Paren', '112.210564,-2.726391', '6202050'),
('6202050007', 'Benua Usang', '112.194771,-2.703651', '6202050'),
('6202050008', 'Sembuluh I', '112.351715,-2.712702', '6202050'),
('6202050009', 'Bangkal', '112.394943,-2.604915', '6202050'),
('6202050010', 'Terawan', '112.382027,-2.530249', '6202050'),
('6202060001', 'Taanjung Hanau', '112.18998,-2.666311', '6202060'),
('6202060002', 'Parang Batang', '112.179878,-2.594539', '6202060'),
('6202060003', 'Bahaur', '112.153687,-2.533189', '6202060'),
('6202060004', 'Pembuang Hulu I', '112.167641,-2.512959', '6202060'),
('6202060005', 'Pembuang Hulu II', '112.180687,-2.534839', '6202060'),
('6202060006', 'Derangga', '112.130196,-2.445067', '6202060'),
('6202060007', 'Asam Baru', '112.162895,-2.392596', '6202060'),
('6202060008', 'Tanjung Hara', '112.160149,-2.376318', '6202060'),
('6202060009', 'Tanjung Paring', '112.157379,-2.357414', '6202060'),
('6202060010', 'Tanjung Rangas II', '112.168442,-2.319837', '6202060'),
('6202070001', 'Pelangsian', '112.955269,-2.631372', '6202070'),
('6202070002', 'Mentaya Seberang', '112.96212,-2.535705', '6202070'),
('6202070003', 'Ketapang', '112.975067,-2.574034', '6202070'),
('6202070004', 'Mentawa Baru Hulu', '112.968979,-2.549561', '6202070'),
('6202080001', 'Baamang Hilir', '112.972038,-2.49821', '6202080'),
('6202080002', 'Baamang Tengah', '113.046761,-2.475364', '6202080'),
('6202080003', 'Baamang Hulu', '112.99086,-2.481546', '6202080'),
('6202080004', 'Batuah', '113.053429,-2.416646', '6202080'),
('6202080005', 'Terantang', '113.049988,-2.405482', '6202080'),
('6202080006', 'Tinduk', '113.033089,-2.401657', '6202080'),
('6202090001', 'UPT.Padas Sebut D.II', '112.440559,-2.321627', '6202090'),
('6202090002', 'UPT.Padas Sebut D.I', '112.462059,-2.373467', '6202090'),
('6202090003', 'Sebabi', '112.551247,-2.416972', '6202090'),
('6202090004', 'Tanah Putih', '112.630592,-2.425262', '6202090'),
('6202090005', 'Palangan', '112.735229,-2.316117', '6202090'),
('6202090006', 'Hanjalipan', '112.744461,-2.225534', '6202090'),
('6202090007', 'Simpur', '112.794945,-2.298268', '6202090'),
('6202090008', 'Pamalian', '112.855713,-2.255727', '6202090'),
('6202090009', 'Camba', '112.861183,-2.344353', '6202090'),
('6202090010', 'Kandan', '112.90818,-2.387147', '6202090'),
('6202090011', 'Kota Besi Hulu', '112.94809,-2.385572', '6202090'),
('6202100001', 'Kota Besi Hilir', '112.980659,-2.359968', '6202100'),
('6202100002', 'Luwuk Bunter', '112.997971,-2.332802', '6202100'),
('6202100003', 'Sungai Paring', '112.988701,-2.282896', '6202100'),
('6202100004', 'Cempaka Mulia Barat', '112.994423,-2.290761', '6202100'),
('6202100005', 'Cempaka Mulia Timur', '112.957596,-2.279678', '6202100'),
('6202100006', 'Jemaras', '112.952591,-2.231418', '6202100'),
('6202100007', 'Lubuk Ranggan', '112.957954,-2.215688', '6202100'),
('6202100008', 'Patai', '112.967255,-2.184944', '6202100'),
('6202100009', 'Rubung Buyung', '112.987999,-2.158009', '6202100'),
('6202100010', 'Parit', '112.993813,-2.093074', '6202100'),
('6202100011', 'Keruing', '113.014648,-2.074418', '6202100'),
('6202100012', 'Pantai Harapan', '113.040878,-1.986419', '6202100'),
('6202110001', 'Tehang', '112.755722,-2.182964', '6202110'),
('6202110002', 'Kabuau', '112.740181,-2.142646', '6202110'),
('6202110003', 'Parenggean', '112.79113,-2.037235', '6202110'),
('6202110004', 'Sari Harapan', '112.785095,-2.005506', '6202110'),
('6202110005', 'Mekar Jaya', '112.796097,-1.989723', '6202110'),
('6202110006', 'Karang Tunggal', '112.838272,-2.005506', '6202110'),
('6202110007', 'Karang Sari', '112.840508,-1.988301', '6202110'),
('6202110008', 'Sumber Makmur', '112.839836,-1.960594', '6202110'),
('6202110009', 'Bandar Agung', '112.834793,-1.93501', '6202110'),
('6202110010', 'Beringin Tunggal Jaya', '112.839142,-1.904128', '6202110'),
('6202110011', 'Berunang Miri', '112.782417,-1.968861', '6202110'),
('6202110012', 'Sebungsu', '112.773781,-1.936359', '6202110'),
('6202110013', 'Tumbang Mujam', '112.760735,-1.893334', '6202110'),
('6202110014', 'Bukit Makmur (I-L)', '112.730003,-1.824496', '6202110'),
('6202110015', 'Wono Sari (UPT.II-L)', '112.736015,-1.794454', '6202110'),
('6202110016', 'Merah', '112.757072,-1.841457', '6202110'),
('6202110017', 'Mekar Sari (J-I)', '112.904243,-1.84953', '6202110'),
('6202110018', 'Damar Makmur (J-II)', '112.931282,-1.847527', '6202110'),
('6202110019', 'Luwuk Sampun', '112.759171,-1.817055', '6202110'),
('6202110020', 'Tanjung Jorong', '112.752258,-1.668587', '6202110'),
('6202120001', 'Tangar', '112.656624,-2.174451', '6202120'),
('6202120002', 'Baampah', '112.640015,-2.145734', '6202120'),
('6202120003', 'Kawan Batu', '112.588196,-2.018362', '6202120'),
('6202120004', 'Tanjung Batur', '112.549995,-1.996465', '6202120'),
('6202120005', 'Penda Durian', '112.59269,-2.11578', '6202120'),
('6202120006', 'Pahirangan', '112.600647,-2.09145', '6202120'),
('6202120007', 'Sationg', '112.595261,-2.079761', '6202120'),
('6202120008', 'Santilik', '112.583794,-2.060196', '6202120'),
('6202120009', 'Tangka Robah', '112.563354,-2.052865', '6202120'),
('6202120010', 'Pemantang', '112.57058,-2.02824', '6202120'),
('6202120011', 'Tumbang Sapiri', '112.530472,-2.008128', '6202120'),
('6202120012', 'Kuala Kuayan', '112.524872,-1.962165', '6202120'),
('6202120013', 'Tumbang Tilap', '112.470955,-1.926366', '6202120'),
('6202120014', 'Bawan', '112.54657,-1.931051', '6202120'),
('6202120015', 'Tanjung Jariangau', '112.551613,-1.903974', '6202120'),
('6202120016', 'Tumbang Kaminting', '112.454376,-1.885245', '6202120'),
('6202120017', 'Tanah Haluan', '112.446289,-1.852124', '6202120'),
('6202120018', 'Tumbang Penyahuan', '112.413734,-1.80807', '6202120'),
('6202120019', 'Tumbang Sapia', '112.379356,-1.779514', '6202120'),
('6202120020', 'Tembang Getas', '112.35202,-1.75971', '6202120'),
('6202120021', 'Tumbang Turung', '112.29882,-1.706028', '6202120'),
('6202120022', 'Tumbang Batu', '112.402344,-1.694187', '6202120'),
('6202120023', 'Lunuk Bagantung', '112.295708,-1.661826', '6202120'),
('6202120025', 'Tumbang Saluang', '112.2593,-1.586059', '6202120'),
('6202120026', 'Tewei Hara', '112.327744,-1.785676', '6202120'),
('6202120027', 'Tumbang Payang', '112.285835,-1.790822', '6202120'),
('6202120028', 'Tumbang Kania', '112.226524,-1.773422', '6202120'),
('6202121001', 'Tukung Langit', '112.542213,-1.827254', '6202121'),
('6202121002', 'Buana Mustika', '112.644249,-1.854472', '6202121'),
('6202121003', 'Tanjung Harapan', '112.639206,-1.822947', '6202121'),
('6202121004', 'Beringin Agung', '112.61525,-1.830513', '6202121'),
('6202121005', 'Tumbang Sangaai', '112.555923,-1.803215', '6202121'),
('6202121006', 'Rantau Katang', '112.562286,-1.784974', '6202121'),
('6202121007', 'Tumbang Mangkup', '112.591393,-1.776384', '6202121'),
('6202121008', 'Rantau Tampang', '112.593971,-1.745813', '6202121'),
('6202121009', 'Luwuk Kuwan', '112.591393,-1.717637', '6202121'),
('6202121010', 'Batu Agung', '112.497978,-1.729635', '6202121'),
('6202121011', 'Tumbang Bajanei', '112.631088,-1.677406', '6202121'),
('6202121012', 'Tumbang Boloi', '112.624847,-1.644454', '6202121'),
('6202121013', 'Tumbang Sepayang', '112.63533,-1.618742', '6202121'),
('6202121014', 'Gunung Makmur', '112.704773,-1.627497', '6202121'),
('6202121015', 'Sungai Hanya', '112.649811,-1.602016', '6202121'),
('6202121016', 'Bukit Indah', '112.529503,-1.619931', '6202121'),
('6202121017', 'Mulya Agung', '112.689644,-1.607321', '6202121'),
('6202121018', 'Bhakti Karya', '112.711082,-1.585885', '6202121'),
('6202121019', 'Waringin Agung', '112.708557,-1.556882', '6202121'),
('6202121020', 'Tumbang Kalang', '112.64888,-1.515631', '6202121'),
('6202121022', 'Tumbang Puan', '112.573135,-1.487227', '6202121'),
('6202121023', 'Rantau Sawang', '112.454834,-1.484733', '6202121'),
('6202121024', 'Rantau Suang', '112.512436,-1.485143', '6202121'),
('6202121025', 'Kuluk Telawang', '112.680244,-1.381876', '6202121'),
('6202121026', 'Sungai Puring', '112.667114,-1.432933', '6202121'),
('6202121027', 'Tumbang Ngahan', '112.66201,-1.39768', '6202121'),
('6202121028', 'Tumbang Ramei', '112.581276,-1.269887', '6202121'),
('6202121029', 'Tumbang Hejan', '112.574623,-1.275045', '6202121'),
('6202121030', 'Buntut Nusa', '112.540825,-1.285225', '6202121'),
('6202121031', 'Tumbang Gagu', '112.458374,-1.220545', '6202121'),
('6202130001', 'Sebabi', '112.179718,-2.30752', '6202130'),
('6202130002', 'Sandul', '112.156754,-2.282051', '6202130'),
('6202130003', 'UPT.Sukamandang G.2', '112.117302,-2.227736', '6202130'),
('6202130004', 'UPT.Sukamandang G.1', '112.019669,-2.245051', '6202130'),
('6202130005', 'UPT.Sukamandang C.3', '112.279472,-2.226625', '6202130'),
('6202130006', 'UPT.Sukamandang C.2', '112.259201,-2.25887', '6202130'),
('6202130008', 'UPT.Sukamandang B.1', '112.003082,-2.198987', '6202130'),
('6202130009', 'UPT.Sukamandang B.2', '111.998482,-2.139103', '6202130'),
('6202130010', 'Durian Kait', '112.131302,-2.194379', '6202130'),
('6202130011', 'UPT.Sukamandang B.4', '112.077713,-2.171348', '6202130'),
('6202130012', 'UPT.Sukamandang B.3', '112.066658,-2.157529', '6202130'),
('6202130013', 'Sahabu', '112.161545,-2.17964', '6202130'),
('6202130014', 'Batu Manangis', '112.150528,-2.184699', '6202130'),
('6202130015', 'UPT.Sukamandang DT.1', '112.050072,-2.069085', '6202130'),
('6202130016', 'UPT.Sukamdang DT.2', '112.052345,-2.034941', '6202130'),
('6202130017', 'Derawa', '112.158775,-2.146003', '6202130'),
('6202130018', 'Gantung Pengayuh', '112.16188,-2.133848', '6202130'),
('6202130019', 'Teluk Bayur', '112.15593,-2.114971', '6202130'),
('6202140001', 'Tumbang Magin', '111.478661,-1.304778', '6202140'),
('6202140002', 'Tumbang Setoli', '111.515953,-1.297873', '6202140'),
('6202140003', 'Tumbang Hentas', '111.623505,-1.230949', '6202140'),
('6202140004', 'Tumbang Langkai', '111.649048,-1.237882', '6202140'),
('6202140005', 'Tanjung Tukal', '111.668022,-1.240801', '6202140'),
('6202140006', 'Rangkang Munduk', '111.676781,-1.237152', '6202140'),
('6202140007', 'Tumbang Salau', '111.736259,-1.221827', '6202140'),
('6202140009', 'Tumbang Suei', '111.786613,-1.259776', '6202140'),
('6202140010', 'Tumbang Manjul', '111.861946,-1.252933', '6202140'),
('6202140012', 'Rantau Panjang', '111.986778,-1.231062', '6202140'),
('6202140013', 'Mongoh Juoi', '111.990829,-1.174817', '6202140'),
('6202140014', 'Tusuk Belawan', '111.854179,-1.188209', '6202140'),
('6202140016', 'Tumbang Bahan', '111.874374,-1.168274', '6202140'),
('6202140017', 'Tumbang Darap', '111.901375,-1.110946', '6202140'),
('6202140018', 'Tumbang Gugup', '111.742462,-1.178404', '6202140'),
('6202140019', 'Tumbang Katai', '111.89827,-1.064114', '6202140'),
('6202140020', 'Tumbang Setawai', '111.897789,-1.034824', '6202140'),
('6202140021', 'Sepundu Hantu', '111.754868,-1.149212', '6202140'),
('6202140022', 'Tumbang Kubang', '111.778938,-1.111403', '6202140'),
('6202140023', 'Tumbang Sepan', '111.899834,-1.016701', '6202140'),
('6202140024', 'Riam Batang', '111.903046,-0.967595', '6202140'),
('6202140025', 'Tumbang Laku', '111.762344,-1.069755', '6202140'),
('6202140026', 'Buntut Sapau', '111.770798,-1.011511', '6202140'),
('6202140027', 'Tumbang Taberau', '111.883171,-0.926674', '6202140'),
('6202140028', 'Tanjung Paku', '111.85807,-0.887565', '6202140'),
('6202150002', 'Tumbang Labaning', '112.57444,-1.174562', '6202150'),
('6202150007', 'Tumbang Jiga', '112.441521,-1.161161', '6202150'),
('6202150008', 'Tumbang Senamang', '112.423996,-1.161415', '6202150'),
('6202150009', 'Tumbang Kabayan', '112.409363,-1.135377', '6202150'),
('6202150010', 'Tumbang Mangketai', '112.488503,-1.171946', '6202150'),
('6202150011', 'Tumbang Manangei', '112.384499,-1.178583', '6202150'),
('6202150012', 'Tumbang Sabetung', '112.192719,-1.20364', '6202150'),
('6202150013', 'Tumbang Mahop', '112.343651,-1.165756', '6202150'),
('6202150015', 'Tumbang Kataei', '112.330055,-1.124487', '6202150'),
('6202150016', 'Rantau Bahai', '112.397812,-1.114272', '6202150'),
('6202150017', 'Tumbang Dahue', '112.321548,-1.090234', '6202150'),
('6202150018', 'Rantau Puka', '112.396698,-1.098498', '6202150'),
('6202150019', 'Rantau Pandan', '112.308678,-1.051836', '6202150'),
('6202150020', 'Telok Tampang', '112.381073,-1.072557', '6202150'),
('6202150021', 'Tumbang Gaei', '112.300819,-1.018226', '6202150'),
('6202150023', 'Tumbang Salaman', '112.393089,-1.055301', '6202150'),
('6202150024', 'Rangan Rondan', '112.282669,-0.987696', '6202150'),
('6202150025', 'Tumbang Kuai', '112.38678,-1.031143', '6202150'),
('6202150026', 'Rangan Bahekang', '112.266068,-0.970391', '6202150'),
('6202150027', 'Kuluk Sapangi', '112.386795,-1.00976', '6202150'),
('6202150028', 'Tumbang Kajamei', '112.26255,-0.950432', '6202150'),
('6202150029', 'Dehes Asem', '112.392838,-0.99988', '6202150'),
('6202150030', 'Tumbang Karue', '112.257484,-0.92144', '6202150'),
('6202150031', 'Rangan Kawit', '112.383781,-0.98204', '6202150'),
('6202150032', 'Tanjung Batik', '112.249886,-0.881471', '6202150'),
('6202150033', 'Kiham Batang', '112.360176,-0.908211', '6202150'),
('6202150034', 'Tumbang Kaburai', '112.25383,-0.852761', '6202150'),
('6202160001', 'Tumbang Mandurei', '112.834595,-1.322438', '6202160'),
('6202160002', 'Tumbang Paku', '112.797356,-1.318428', '6202160'),
('6202160003', 'Buntut Leleng', '112.742897,-1.274657', '6202160'),
('6202160004', 'Kuluk Leleng', '112.744438,-1.221054', '6202160'),
('6202160005', 'Rangan Surei', '112.729424,-1.164739', '6202160'),
('6202160006', 'Tumbang Hiran', '112.714287,-1.195447', '6202160'),
('6202160008', 'Tumbang Dakei', '112.69796,-1.197379', '6202160'),
('6202160009', 'Rangan Burih', '112.660225,-1.162734', '6202160'),
('6202160011', 'Tumbang Lambi', '112.619278,-1.145978', '6202160'),
('6202160012', 'Rangan Tangko', '112.589531,-1.1557', '6202160'),
('6202160013', 'Tumbang Taei', '112.72361,-1.106171', '6202160'),
('6202160015', 'Sebaung', '112.653969,-0.882403', '6202160'),
('6202160016', 'Tumbang Tundu', '112.521805,-1.053939', '6202160'),
('6202160017', 'Tumbang Tabulus', '112.514267,-0.944496', '6202160'),
('6202160018', 'Batu Panahan', '112.52475,-0.990043', '6202160'),
('6202170001', 'Dehes', '113.088402,-1.423264', '6202170'),
('6202170003', 'Tumbang Kaman', '113.074173,-1.393748', '6202170'),
('6202170004', 'Tumbang Manggo', '113.057159,-1.362664', '6202170'),
('6202170005', 'Kamanto', '113.042145,-1.322296', '6202170'),
('6202170006', 'Kuluk Habuhus', '113.032318,-1.297971', '6202170'),
('6202170007', 'Tumbang Kanei', '113.046288,-1.270024', '6202170'),
('6202170008', 'Tumbang Taranei', '113.05043,-1.241042', '6202170'),
('6202170009', 'Tumbang Pangka', '113.019379,-1.260708', '6202170'),
('6202170010', 'Tumbang Atei', '112.989876,-1.215164', '6202170'),
('6202170011', 'Tumbang Kawei', '113.056122,-1.212059', '6202170'),
('6202170012', 'Tumbang Mangara', '113.081482,-1.174796', '6202170'),
('6202170013', 'Tumbang Baraoi', '112.965157,-1.129075', '6202170'),
('6202170014', 'Batu Tukan', '113.00383,-1.026285', '6202170'),
('6202170015', 'Tumbang Tangoi', '113.014427,-1.002972', '6202170'),
('6202170016', 'Tumbang Jala', '112.93972,-1.080859', '6202170'),
('6202170017', 'Batu Badak', '112.843819,-1.055427', '6202170'),
('6202170018', 'Nusa Kutau', '112.814674,-1.04324', '6202170'),
('6202170019', 'Tumbang Habangei', '112.790306,-1.00827', '6202170'),
('6202180002', 'Tumbang Lahang', '113.179298,-1.533056', '6202180'),
('6202180003', 'Tewang Panjang', '113.139793,-1.528823', '6202180'),
('6202180004', 'Petak Puti', '113.111572,-1.507817', '6202180'),
('6202180005', 'Telok', '113.098557,-1.477288', '6202180'),
('6202180006', 'Samba Danum', '113.098557,-1.451614', '6202180'),
('6202180007', 'Samba Bakumpai', '113.092781,-1.443913', '6202180'),
('6202180008', 'Samba Katung', '113.071922,-1.437815', '6202180'),
('6202180010', 'Napu Sahur', '113.059677,-1.425045', '6202180'),
('6202180011', 'Batu Badinding', '113.077377,-1.445517', '6202180'),
('6202180012', 'Rantau Asem', '113.010315,-1.426726', '6202180'),
('6202180013', 'Tumbang Kalamei', '112.969872,-1.413148', '6202180'),
('6202180014', 'Tumbang Marak', '112.918373,-1.409308', '6202180'),
('6202180015', 'Tumbang Hangei', '112.884155,-1.35796', '6202180'),
('6202190001', 'Tewang Papari', '113.268852,-1.718889', '6202190'),
('6202190003', 'Buntut Bali', '113.261421,-1.69475', '6202190'),
('6202190004', 'Kuluk Bali', '113.234634,-1.678039', '6202190'),
('6202190005', 'Manduing Taheta', '113.259941,-1.660769', '6202190'),
('6202190006', 'Manduing Lama', '113.247719,-1.652785', '6202190'),
('6202190007', 'Tumbang Banjang', '113.230446,-1.64806', '6202190'),
('6202190008', 'Tumbang Lawang', '113.241035,-1.623619', '6202190'),
('6202190009', 'Dahian Tunggal', '113.229141,-1.617102', '6202190'),
('6202190010', 'Tewang Karangan', '113.22715,-1.593538', '6202190'),
('6202190011', 'Tumbang Tungku', '113.218895,-1.58567', '6202190'),
('6202190012', 'Geragu', '113.200439,-1.567893', '6202190'),
('6202190013', 'Tumbang Tanjung', '113.199677,-1.537759', '6202190'),
('6202190014', 'Tura', '113.214813,-1.58975', '6202190'),
('6202200001', 'Tewang Beringin', '113.313393,-1.842144', '6202200'),
('6202200002', 'Hapalam', '113.310402,-1.819052', '6202200'),
('6202200003', 'Tewang Rangas', '113.288589,-1.828888', '6202200'),
('6202200004', 'Bangkuang', '113.287735,-1.816486', '6202200'),
('6202200005', 'Tarusan Danum', '113.30526,-1.790908', '6202200'),
('6202200006', 'Pendahara', '113.28344,-1.777776', '6202200'),
('6202200007', 'Tumbang Terusan', '113.266998,-1.746741', '6202200'),
('6202200009', 'Tewang Rangkang', '113.292725,-1.742497', '6202200'),
('6202200010', 'Tewang Manyangen', '113.287422,-1.736131', '6202200'),
('6202210001', 'Tewang Kadamba', '113.395355,-1.978052', '6202210'),
('6202210002', 'Tumbang Liting', '113.380478,-1.963174', '6202210'),
('6202210003', 'Kasongan Baru', '113.369415,-1.933232', '6202210'),
('6202210005', 'Talian Kereng', '113.376152,-1.909606', '6202210'),
('6202210006', 'Banut Kalanaman', '113.330498,-1.898593', '6202210'),
('6202210007', 'Telangkah', '113.321091,-1.883626', '6202210'),
('6202220001', 'Talingke', '113.47245,-2.151862', '6202220'),
('6202220002', 'Hiang Bana', '113.475159,-2.101815', '6202220'),
('6202220003', 'Petak Bahandang', '113.436607,-2.079498', '6202220'),
('6202220004', 'Handiwung', '113.456894,-2.014573', '6202220'),
('6202220005', 'Tumbang Panggo', '113.402794,-2.035538', '6202220'),
('6202220006', 'Tewang Tampang', '113.425789,-1.992255', '6202220'),
('6202220007', 'Luwuk Kanan', '113.42646,-1.969937', '6202220'),
('6202220008', 'Luwuk Kiri', '113.411583,-1.979405', '6202220'),
('6202230001', 'Galinggang', '113.323364,-2.568898', '6202230'),
('6202230002', 'Tampelas', '113.302116,-2.469748', '6202230'),
('6202230003', 'Telaga', '113.327415,-2.429279', '6202230'),
('6202230004', 'Parupuk', '113.343597,-2.427256', '6202230'),
('6202230005', 'Karuing', '113.374962,-2.379705', '6202230'),
('6202230006', 'Jahanjang', '113.39621,-2.351376', '6202230'),
('6202230007', 'Tumbang Runen', '113.427574,-2.293708', '6202230'),
('6202230008', 'Baun Bango', '113.46096,-2.293708', '6202230'),
('6202230009', 'Asem Kumbang', '113.481918,-2.219491', '6202230'),
('6202240003', 'Jaya Makmur', '113.318329,-3.121384', '6202240'),
('6202240004', 'Subur Indah', '113.328125,-3.14776', '6202240'),
('6202240005', 'Kampung Keramat', '113.314209,-3.211715', '6202240'),
('6202240007', 'Bangun Jaya', '113.310043,-3.095007', '6202240'),
('6202240009', 'Kampung Baru', '113.309181,-3.193044', '6202240'),
('6202240010', 'Setia Mulya', '113.303261,-3.079935', '6202240'),
('6202241001', 'Teluk Sebulu', '113.26223,-3.054296', '6202241'),
('6202241002', 'Mendawai', '113.264832,-2.979529', '6202241'),
('6202241003', 'Kampung Melayu', '113.254852,-2.961781', '6202241'),
('6202241004', 'Tewang Kampung', '113.271492,-2.931831', '6202241'),
('6202241005', 'Mekar Tani', '113.306664,-2.947437', '6202241'),
('6202241006', 'Parigi', '113.263725,-2.838653', '6202241'),
('6203010007', 'Bahaur Hilir', '114.075798,-3.206567', '6203010'),
('6203010008', 'Bahaur Tengah', '114.083351,-3.187935', '6203010'),
('6203010009', 'Bahaur Hulu', '114.090401,-3.162441', '6203010'),
('6203010010', 'Paduran Sebangau', '113.755936,-3.024015', '6203010'),
('6203010013', 'Sebangau Permai', '113.73764,-2.940935', '6203010'),
('6203030001', 'Terusan Raya', '114.303024,-3.0495', '6203030'),
('6203030005', 'Tamban Luar', '114.342735,-3.156206', '6203030'),
('6203030006', 'Handel Jangkit', '114.326065,-3.097876', '6203030'),
('6203030007', 'Pulau Kupang', '114.335098,-3.085145', '6203030'),
('6203030008', 'Sei Lunuk', '114.35733,-3.041972', '6203030'),
('6203030009', 'Pulau Mambulau', '114.369164,-3.029067', '6203030'),
('6203030010', 'Murung Keramat', '114.357338,-3.02013', '6203030'),
('6203030011', 'Selat Hilir', '114.377968,-3.008276', '6203030'),
('6203030012', 'Selat Tengah', '114.383926,-3.002003', '6203030'),
('6203030013', 'Selat Hulu', '114.390511,-2.995103', '6203030'),
('6203030014', 'Selat Dalam', '114.396156,-2.98789', '6203030'),
('6203030015', 'Pulau Telo', '114.369499,-2.974403', '6203030'),
('6203040001', 'Anjir Mambulau Barat', '114.424805,-3.080947', '6203040'),
('6203040002', 'Anjir Serapat Tengah', '114.400658,-3.052841', '6203040'),
('6203050001', 'Pangkalan Rekan', '114.341042,-3.009404', '6203050'),
('6203050002', 'Basarang', '114.282516,-2.938531', '6203050'),
('6203050004', 'Basungkai', '114.293861,-2.957989', '6203050'),
('6203050005', 'Lunuk Ramba', '114.354118,-2.979414', '6203050'),
('6203050006', 'Batuah', '114.322304,-2.990938', '6203050'),
('6203050007', 'Taambun Raya', '114.306831,-2.951573', '6203050'),
('6203050009', 'Bungai Jaya', '114.305908,-2.918488', '6203050'),
('6203050010', 'Basarang Jaya', '114.288506,-2.944599', '6203050'),
('6203050011', 'Panarung', '114.274445,-2.946607', '6203050'),
('6203050012', 'Tarung Manuah', '114.236954,-2.892376', '6203050'),
('6203050013', 'Batu Nindan', '114.268814,-2.924163', '6203050'),
('6203060002', 'Hampatung', '114.386406,-3.016423', '6203060'),
('6203060003', 'Dahirang', '114.394325,-3.010881', '6203060'),
('6203060004', 'Barimba', '114.399467,-3.006526', '6203060'),
('6203060005', 'Sei Pasah', '114.40654,-3.000615', '6203060'),
('6203070001', 'Teluk Palinget', '114.442505,-2.931982', '6203070'),
('6203070002', 'Sakalagun', '114.441628,-2.947764', '6203070'),
('6203070003', 'Narahan', '114.493469,-2.891952', '6203070'),
('6203070004', 'Bunga Mawar', '114.464417,-2.917078', '6203070'),
('6203070006', 'Sei Tatas', '114.478828,-2.897563', '6203070'),
('6203070007', 'Handiwung', '114.49688,-2.885365', '6203070'),
('6203070008', 'Anjir Palambang', '114.487122,-2.90732', '6203070'),
('6203080001', 'Palingkaau Baru', '114.506149,-2.867558', '6203080'),
('6203080003', 'Palingkau Lama', '114.504448,-2.851458', '6203080'),
('6203080006', 'Taajepan', '114.531837,-2.830361', '6203080'),
('6203080007', 'Mampai', '114.543793,-2.821726', '6203080'),
('6203080008', 'Muara Dadahup', '114.582962,-2.817761', '6203080'),
('6203080009', 'Dadahup', '114.591248,-2.654724', '6203080'),
('6203080026', 'Belawang', '114.656052,-2.77855', '6203080'),
('6203080029', 'Palangka Lama', '114.732491,-2.725344', '6203080'),
('6203080031', 'Palangkau Baru', '114.752975,-2.691778', '6203080'),
('6203080036', 'Tambak Bajai', '114.677765,-2.586748', '6203080'),
('6203090001', 'Sai Kayu', '114.36364,-2.909795', '6203090'),
('6203090002', 'Saka Mangkahai', '114.334061,-2.834829', '6203090'),
('6203090003', 'Mandomai', '114.339706,-2.819986', '6203090'),
('6203090004', 'Anjir Kalampan', '114.287132,-2.801067', '6203090'),
('6203090005', 'Pantai', '114.400299,-2.788082', '6203090'),
('6203090006', 'Saka Tamiang', '114.445076,-2.803632', '6203090'),
('6203090007', 'Penda Ketapi', '114.454727,-2.774141', '6203090'),
('6203090008', 'Teluk Hiri', '114.427643,-2.756446', '6203090'),
('6203090009', 'Sei Dusun', '114.437027,-2.740896', '6203090'),
('6203100001', 'Dandang', '114.106766,-3.140487', '6203100'),
('6203100002', 'Talio', '114.128334,-3.124696', '6203100'),
('6203100003', 'Belanti Siam', '114.161026,-3.116165', '6203100'),
('6203100004', 'Gadabung', '114.177002,-3.144752', '6203100'),
('6203100005', 'Pangkoh Hilir', '114.147659,-3.085067', '6203100'),
('6203100006', 'Talio Muara', '114.118172,-3.134753', '6203100'),
('6203100007', 'Talio Hulu', '114.091591,-3.12146', '6203100'),
('6203100008', 'Pangkoh Sari', '114.122368,-3.113912', '6203100'),
('6203100009', 'Kantan Muara', '114.139442,-3.076266', '6203100'),
('6203100010', 'Pangkoh Hulu', '114.157791,-3.065422', '6203100'),
('6203100012', 'Pantik', '114.169189,-3.074061', '6203100'),
('6203101001', 'Gandang', '114.091682,-2.983658', '6203101'),
('6203101002', 'Garantung', '114.085075,-2.941703', '6203101'),
('6203101003', 'Maliku Baru', '114.149811,-2.939951', '6203101'),
('6203101004', 'Badirih', '114.151482,-2.971766', '6203101'),
('6203101005', 'Tahai Jaya', '114.183197,-2.981105', '6203101'),
('6203101006', 'Tahai Baru', '114.176987,-2.961692', '6203101'),
('6203101007', 'Kanamit', '114.154121,-2.913293', '6203101'),
('6203101008', 'Purwodadi', '114.135834,-2.886373', '6203101'),
('6203101009', 'Wonoagung', '114.107101,-2.877831', '6203101'),
('6203101010', 'Kanamit Barat', '114.167671,-2.887149', '6203101'),
('6203101011', 'Sei Baru Tewu', '114.18129,-2.877305', '6203101'),
('6203110001', 'Buntoi', '114.179771,-2.816935', '6203110'),
('6203110002', 'Mintin', '114.21196,-2.865396', '6203110'),
('6203110003', 'Mantaren II', '114.244949,-2.801348', '6203110'),
('6203110004', 'Mantaren I', '114.234802,-2.786887', '6203110'),
('6203110005', 'Pulang Pisau', '114.250336,-2.750761', '6203110'),
('6203110007', 'Gohong', '114.271942,-2.71396', '6203110'),
('6203110008', 'Garung', '114.266289,-2.658772', '6203110'),
('6203110009', 'Henda', '114.200081,-2.613032', '6203110'),
('6203110010', 'Simpur', '114.2145,-2.602175', '6203110'),
('6203110011', 'Saka Kajang', '114.20079,-2.601641', '6203110'),
('6203110012', 'Jabiren', '114.175804,-2.532591', '6203110'),
('6203110013', 'Pilang', '114.17775,-2.466584', '6203110'),
('6203110014', 'Tumbang Nusa', '114.125328,-2.367573', '6203110'),
('6203120001', 'Tanjung Sangalang', '113.916389,-2.151279', '6203120'),
('6203120002', 'Penda Barania', '113.912567,-2.136961', '6203120'),
('6203120003', 'Bukit Rawi', '113.932617,-2.092097', '6203120'),
('6203120004', 'Tuwung', '113.901115,-2.070143', '6203120'),
('6203120005', 'Sigi', '113.909706,-2.030053', '6203120'),
('6203120006', 'Petuk Liti', '113.932617,-2.015734', '6203120'),
('6203120007', 'Bukit Liti', '113.937386,-1.99378', '6203120'),
('6203120008', 'Bahu Palawa', '113.94593,-1.968238', '6203120'),
('6203120009', 'Pamarunan', '113.943565,-1.949978', '6203120'),
('6203120010', 'Balukun', '113.920906,-1.939834', '6203120'),
('6203120011', 'Bukit Bamba', '113.936462,-1.925294', '6203120'),
('6203120012', 'Tahawa', '113.925644,-1.905005', '6203120'),
('6203120013', 'Parahangan', '113.940178,-1.885393', '6203120'),
('6203130001', 'Manen Paduran', '113.920906,-1.839068', '6203130'),
('6203130002', 'Manen Kaleka', '113.899796,-1.83709', '6203130'),
('6203130003', 'Lawang Uru', '113.908569,-1.789585', '6203130'),
('6203130004', 'Hurung', '113.919998,-1.769994', '6203130'),
('6203130005', 'Hanua', '113.91877,-1.745098', '6203130'),
('6203130006', 'Ramang', '113.920815,-1.726732', '6203130'),
('6203130007', 'Tambak', '113.908569,-1.723059', '6203130'),
('6203130008', 'Pahawan', '113.922035,-1.676939', '6203130'),
('6203130009', 'Guha', '113.926117,-1.661022', '6203130'),
('6203130010', 'Bawan', '113.919083,-1.624981', '6203130'),
('6203130011', 'Rtumbang Tarusan', '113.920502,-1.601797', '6203130'),
('6203130012', 'Pandawei', '113.905357,-1.589969', '6203130'),
('6203130013', 'Pangi', '113.916245,-1.577667', '6203130'),
('6203130014', 'Tangkahen', '113.89164,-1.573882', '6203130'),
('6203140001', 'Manusup', '114.415855,-2.672037', '6203140'),
('6203140005', 'Sei Kapar', '114.384285,-2.678083', '6203140'),
('6203140007', 'Lamunti', '114.380249,-2.609907', '6203140'),
('6203140013', 'Pulau Kaladan', '114.393684,-2.580353', '6203140'),
('6203140019', 'Mantangi Hilir', '114.487122,-2.532605', '6203140'),
('6203140022', 'Mantangai Tengah', '114.491333,-2.51577', '6203140'),
('6203140023', 'Mantangai Hulu', '114.490807,-2.494725', '6203140'),
('6203140024', 'Kalumpang', '114.48555,-2.467894', '6203140'),
('6203140025', 'Sai Ahas', '114.381378,-2.346889', '6203140'),
('6203140026', 'Katunjung', '114.400841,-2.397921', '6203140'),
('6203140027', 'Lahei Mangkutup', '114.201355,-1.959834', '6203140'),
('6203140028', 'Tumbang Murui', '114.406242,-2.015862', '6203140'),
('6203140029', 'Danau Rawah', '114.353874,-1.967274', '6203140'),
('6203150001', 'Petak Puti', '114.497849,-1.949986', '6203150'),
('6203150002', 'Aruk', '114.471863,-1.881882', '6203150'),
('6203150003', 'Lawang Kajang', '114.497849,-1.853654', '6203150'),
('6203150004', 'Timpah', '114.500526,-1.789135', '6203150'),
('6203150005', 'Lungku Layang', '114.480476,-1.752511', '6203150'),
('6203150007', 'Lawang Kamah', '114.521439,-1.580973', '6203150'),
('6203150008', 'Tumbang Randang', '114.470848,-1.554982', '6203150'),
('6203150009', 'Batapah', '114.591522,-1.450089', '6203150'),
('6203160001', 'Masaran', '114.457092,-1.5055', '6203160'),
('6203160002', 'Kayu Bulan', '114.462166,-1.491062', '6203160'),
('6203160003', 'Kota Baru', '114.415337,-1.473892', '6203160'),
('6203160004', 'Penda Muntei', '114.421577,-1.447747', '6203160'),
('6203160005', 'Tapen', '114.385292,-1.395066', '6203160'),
('6203160006', 'Pujon', '114.380997,-1.373603', '6203160'),
('6203160007', 'Marapit', '114.389191,-1.358775', '6203160'),
('6203160008', 'Manis', '114.359032,-1.19205', '6203160'),
('6203160009', 'Bajuh', '114.274078,-1.264839', '6203160'),
('6203160010', 'Dandang', '114.257988,-1.213594', '6203160'),
('6203160011', 'Karukus', '114.366333,-1.157756', '6203160'),
('6203160012', 'Balai Banjang', '114.228737,-1.188247', '6203160'),
('6203160013', 'Jangkang', '114.216209,-1.168471', '6203160'),
('6203160014', 'Kaburan', '114.206459,-1.166522', '6203160'),
('6203160015', 'Sei Ringin', '114.115387,-1.118982', '6203160'),
('6203160016', 'Tbg Tukun', '114.105942,-1.104686', '6203160'),
('6203160017', 'Tumbang Diring', '114.238373,-1.07264', '6203160'),
('6203160018', 'Barunang', '114.33094,-1.061076', '6203160'),
('6203160019', 'UPT Trans HTI Karukus', '114.372047,-1.126112', '6203160'),
('6203170001', 'Supang', '114.059982,-1.020855', '6203170'),
('6203170002', 'Hurung Tabengan', '114.049286,-1.005245', '6203170'),
('6203170004', 'Tangirang', '113.985443,-0.978146', '6203170'),
('6203170005', 'Sei Hanyu', '114.014984,-0.930145', '6203170'),
('6203170006', 'Tbg Sirat/Bulai Nga', '114.000832,-0.905529', '6203170'),
('6203170007', 'Tumbang Puruh', '113.994064,-0.91476', '6203170'),
('6203170008', 'Katanjung', '113.975601,-0.919683', '6203170'),
('6203170009', 'Hurung Tampang', '113.919601,-0.935068', '6203170'),
('6203170010', 'Baronang II', '113.888214,-0.944914', '6203170'),
('6203170011', 'Tumbang Bokoi', '113.9356,-0.759678', '6203170'),
('6203170012', 'Karetaau Mantaa', '113.949135,-0.776909', '6203170'),
('6203170013', 'Lawang Tamang', '114.000214,-0.773832', '6203170'),
('6203170014', 'Masaha', '114.030373,-0.869835', '6203170'),
('6203170015', 'Sei Pinang', '114.051292,-0.803372', '6203170'),
('6203170016', 'Tumbang Tihis', '114.078987,-0.779371', '6203170'),
('6203170017', 'Tumbang Manyarung', '114.106682,-0.778756', '6203170'),
('6203180001', 'Sarerangan', '113.797035,-1.089436', '6203180'),
('6203180002', 'Tumbang Pajangei', '113.786575,-1.076288', '6203180'),
('6203180003', 'Sumur Mas', '113.651665,-1.081872', '6203180'),
('6203180004', 'Tewah', '113.724586,-1.061798', '6203180'),
('6203180005', 'Kasintu', '113.719223,-1.027988', '6203180'),
('6203180006', 'Upun Batu', '113.647339,-1.013313', '6203180'),
('6203180007', 'Batu Nyiwuh', '113.634476,-1.01903', '6203180'),
('6203180008', 'Tumbang Habaon', '113.581917,-1.004103', '6203180'),
('6203180009', 'Tanjung Untung', '113.546936,-0.988006', '6203180'),
('6203180011', 'Sei Riang', '113.535591,-0.972744', '6203180'),
('6203190001', 'Pilang Munduk', '113.908325,-1.30459', '6203190'),
('6203190002', 'Tumbang Hakau', '113.901955,-1.279109', '6203190'),
('6203190003', 'Hurung Bunut', '113.906197,-1.253628', '6203190'),
('6203190004', 'Tumbang Tariak', '113.886024,-1.264776', '6203190'),
('6203190005', 'Tumbang Miwan', '113.871696,-1.231863', '6203190'),
('6203190006', 'Tewang Pajangan', '113.874344,-1.212221', '6203190'),
('6203190007', 'Tumbang Lampahung', '113.874344,-1.200542', '6203190'),
('6203190008', 'Teluk Nyatu', '113.892395,-1.181432', '6203190'),
('6203190009', 'Tanjung Riu', '113.883369,-1.166568', '6203190'),
('6203190010', 'Petak Bahandang', '113.8993,-1.161259', '6203190'),
('6203190011', 'Tumbang Anjir', '113.875938,-1.142148', '6203190'),
('6203190012', 'Kuala Kurun', '113.869568,-1.121445', '6203190'),
('6203190013', 'Tumbang Tambirah', '113.837822,-1.073068', '6203190'),
('6203190014', 'Tumbang Manyangan', '113.82119,-1.075751', '6203190'),
('6203190015', 'Penda Pilang', '113.814484,-1.093193', '6203190'),
('6203200001', 'Pematang Limau', '113.901627,-1.551968', '6203200'),
('6203200002', 'Tampelas', '113.907394,-1.527937', '6203200'),
('6203200003', 'Sepang Kota', '113.902519,-1.502439', '6203200'),
('6203200004', 'Sepang Simin', '113.919006,-1.432857', '6203200'),
('6203200005', 'Tewai Baru', '113.912453,-1.474524', '6203200'),
('6203200006', 'Tanjung Karitak', '113.903465,-1.463168', '6203200'),
('6203200007', 'Luwuk Andan', '113.90451,-1.427005', '6203200'),
('6203200008', 'Tuyun', '113.902267,-1.416829', '6203200'),
('6203200009', 'Tumbang Empas', '113.8955,-1.394747', '6203200'),
('6203200011', 'Kampuri', '113.91687,-1.355569', '6203200'),
('6203200012', 'Tumbang Danau', '113.911888,-1.34061', '6203200'),
('6203200013', 'Dahian Tambuk', '113.925308,-1.295565', '6203200'),
('6203210002', 'Talangkah', '113.595993,-1.453775', '6203210'),
('6203210003', 'Luwuk Lengkuas', '113.596809,-1.4192', '6203210'),
('6203210004', 'Tumbang Kajuei', '113.564957,-1.375641', '6203210'),
('6203210005', 'Luwuk Kantor', '113.569077,-1.328152', '6203210'),
('6203210006', 'Tumbang Bunut', '113.56794,-1.319518', '6203210'),
('6203210007', 'Tumbang Jutuh', '113.56794,-1.31121', '6203210'),
('6203210008', 'Linau', '113.56794,-1.297037', '6203210'),
('6203210009', 'Hujung Pata', '113.46299,-1.324202', '6203210'),
('6203210010', 'Jalemu Masulan', '113.42572,-1.28382', '6203210'),
('6203210011', 'Rabambang', '113.459908,-1.249771', '6203210'),
('6203210012', 'Tumbang Barengei', '113.55735,-1.286774', '6203210'),
('6203210013', 'Tumbang Malahoi', '113.528839,-1.286285', '6203210'),
('6203210014', 'Jangkit', '113.557144,-1.262522', '6203210'),
('6203210015', 'Tumbang Lapan', '113.55394,-1.246856', '6203210'),
('6203210016', 'Tumbang Kuayan', '113.51339,-1.265503', '6203210'),
('6203210017', 'Batu Puter', '113.549622,-1.218738', '6203210'),
('6203210018', 'Tumbang Rahuyan', '113.544914,-1.193727', '6203210'),
('6203210019', 'Sei Antai', '113.526749,-1.158455', '6203210'),
('6203210020', 'Nataampang Mujai', '113.568001,-1.186886', '6203210'),
('6203220001', 'Takaras', '113.616028,-1.590612', '6203220'),
('6203220002', 'Bereng Jun', '113.517273,-1.537509', '6203220'),
('6203220003', 'Belaawan Mulya', '113.496895,-1.457586', '6203220'),
('6203220004', 'Nbereng Belawan', '113.470764,-1.434445', '6203220'),
('6203220005', 'Tumbang Sepan', '113.438179,-1.41331', '6203220'),
('6203220006', 'Tumbang Talaken', '113.385231,-1.360712', '6203220'),
('6203220007', 'Tangki Dahuyan', '113.323914,-1.318527', '6203220'),
('6203220008', 'Tumbang Jalemu', '113.332153,-1.359549', '6203220'),
('6203220009', 'Tumbang Samui', '113.321754,-1.294797', '6203220'),
('6203220010', 'Tumbang Oroi', '113.30809,-1.272505', '6203220'),
('6203220011', 'Luwuk Tukau', '113.304497,-1.225764', '6203220'),
('6203220012', 'Tehang', '113.302338,-1.193405', '6203220'),
('6203230001', 'Lawang Kanji', '113.308281,-0.928959', '6203230'),
('6203230002', 'Tumbang Maraya', '113.328468,-0.932908', '6203230'),
('6203230003', 'Tumbang Pusu', '113.344696,-0.929398', '6203230'),
('6203230004', 'Tumbang Marikoi', '113.365829,-0.936993', '6203230'),
('6203230005', 'Tumbang Hamputung', '113.414948,-0.925966', '6203230'),
('6203230006', 'Batu Tangkui', '113.431389,-0.933585', '6203230'),
('6203230007', 'Tumbang Pasangon', '113.46888,-0.934387', '6203230'),
('6203230008', 'Tumbang Miri', '113.457649,-0.920754', '6203230'),
('6203230009', 'Dandang', '113.44622,-0.933384', '6203230'),
('6203230010', 'Penda Rangas', '113.489532,-0.875217', '6203230'),
('6203230011', 'Tumbang Tajungan', '113.411339,-0.876135', '6203230'),
('6203230012', 'Karetau Rambangun', '113.22229,-0.900441', '6203230'),
('6203230014', 'Karetau Sarian', '113.250366,-0.815765', '6203230'),
('6203230015', 'Tumbang Kurik', '113.376907,-0.838088', '6203230'),
('6203230016', 'Tumbang Sian', '113.498764,-0.844975', '6203230'),
('6203230017', 'Tumbang Lapan', '113.515274,-0.811834', '6203230'),
('6203230018', 'Tumbang Siruk', '113.518539,-0.79761', '6203230'),
('6203230019', 'Tumbang Ponyoi', '113.36245,-0.814499', '6203230'),
('6203230020', 'Tumbang Mahuroi', '113.241592,-0.807429', '6203230'),
('6203230021', 'Tumbang Napoi', '113.520462,-0.775696', '6203230'),
('6203230022', 'Buntui/Koroi', '113.530266,-0.745517', '6203230'),
('6203230024', 'Tumbang Manyoi', '113.547577,-0.710096', '6203230'),
('6203230025', 'Tumbang Masukih', '113.546898,-0.685308', '6203230'),
('6204010005', 'Tampulang', '114.860878,-2.465286', '6204010'),
('6204010006', 'Rantau Bahuang', '114.857216,-2.484817', '6204010'),
('6204010007', 'Rantau Kujang', '114.911537,-2.443923', '6204010'),
('6204010008', 'Rangga Ilung', '114.868813,-2.363354', '6204010'),
('6204020001', 'Sungai Jaya', '114.772987,-2.381665', '6204020'),
('6204020002', 'Mahajandau', '114.834236,-2.308984', '6204020'),
('6204020003', 'Mengkatip', '114.840897,-2.235387', '6204020'),
('6204020004', 'Kalanis', '114.885666,-2.287362', '6204020'),
('6204020005', 'Lehai', '114.894112,-2.228904', '6204020'),
('6204020006', 'Damparan', '114.864571,-2.155109', '6204020'),
('6204020007', 'Teluk Timbau', '114.863541,-2.125262', '6204020'),
('6204020008', 'Batilap', '114.823303,-2.077891', '6204020'),
('6204020009', 'Batampang', '114.791489,-2.015725', '6204020'),
('6204030001', 'Selat Baru', '114.883926,-2.086058', '6204030'),
('6204030002', 'Bangkuang', '114.881813,-2.062533', '6204030'),
('6204030003', 'Teluk Betung', '114.878525,-2.027427', '6204030'),
('6204030004', 'Janggi', '114.899734,-1.979888', '6204030'),
('6204030005', 'Babai', '114.851051,-1.922086', '6204030'),
('6204030006', 'Malitin', '114.904343,-1.888612', '6204030'),
('6204030007', 'Muara Arai', '114.872635,-1.845009', '6204030'),
('6204030008', 'Talio', '114.846207,-1.87628', '6204030'),
('6204030009', 'Tampijak', '114.805244,-1.877161', '6204030'),
('6204030010', 'Bintang Kurung', '114.79496,-1.870359', '6204030'),
('6204030011', 'Teluk Sampudau', '114.776176,-1.867912', '6204030'),
('6204040001', 'Muara Talang', '114.777939,-1.800964', '6204040'),
('6204040002', 'Teluk Talaga', '114.789307,-1.792145', '6204040'),
('6204040003', 'Baru', '114.803482,-1.790834', '6204040'),
('6204040004', 'Danau Sadar', '114.824623,-1.77674', '6204040'),
('6204040005', 'Jelapat', '114.823303,-1.763087', '6204040'),
('6204040006', 'Hilir Sper', '114.828209,-1.753243', '6204040'),
('6204040007', 'Danau Ganting', '114.865143,-1.784668', '6204040'),
('6204040008', 'Mangaris', '114.989853,-1.757447', '6204040'),
('6204040009', 'Dangka', '115.064606,-1.683138', '6204040'),
('6204040010', 'Tetei Lanan', '115.003471,-1.692999', '6204040'),
('6204040011', 'Pamangka', '114.995316,-1.730132', '6204040'),
('6204040012', 'Sababilah', '114.895439,-1.714205', '6204040'),
('6204040013', 'Pamait', '114.872673,-1.726382', '6204040'),
('6204040014', 'Buntok Kota', '114.842247,-1.743983', '6204040'),
('6204040015', 'Muara Ripung', '114.818542,-1.713048', '6204040');
INSERT INTO `reg_village` (`village_id`, `village_name`, `village_coordinate_location`, `subdistrict_id`) VALUES
('6204040016', 'Mabuan', '114.817963,-1.685293', '6204040'),
('6204040017', 'Murung Paken', '114.788383,-1.697668', '6204040'),
('6204040018', 'Madara', '114.726021,-1.716228', '6204040'),
('6204040019', 'Kalahien', '114.813049,-1.66361', '6204040'),
('6204040020', 'Lembeng', '114.844879,-1.674513', '6204040'),
('6204040021', 'Sanggu', '114.896599,-1.704664', '6204040'),
('6204040022', 'Telang Andrau', '114.918053,-1.682849', '6204040'),
('6204040023', 'Penda Asam', '114.851784,-1.648287', '6204040'),
('6204040024', 'Pararapak', '114.82692,-1.638746', '6204040'),
('6204060001', 'Baruang/Ekeng', '114.990326,-1.581249', '6204060'),
('6204060003', 'Kayumban', '115.004112,-1.560534', '6204060'),
('6204060004', 'Tabak Kanilan', '115.040283,-1.562843', '6204060'),
('6204060005', 'Sarimbuah', '115.070518,-1.59571', '6204060'),
('6204060007', 'Muka Haji', '115.068695,-1.570265', '6204060'),
('6204060008', 'Sire', '115.089584,-1.576647', '6204060'),
('6204070001', 'Dayu', '115.082535,-1.999611', '6204070'),
('6204070002', 'Wuran', '115.030334,-1.921475', '6204070'),
('6204070003', 'Ipu Mea', '115.08847,-1.913974', '6204070'),
('6204080001', 'Muara Palantau', '115.004707,-1.963668', '6204080'),
('6204080002', 'Ketap', '115.028091,-1.889924', '6204080'),
('6204080003', 'Kupang Bersih', '115.049034,-1.859878', '6204080'),
('6204080004', 'Tuyau', '115.043571,-1.846676', '6204080'),
('6204080005', 'Pinang Tunggal', '115.068138,-1.849404', '6204080'),
('6204080006', 'Nagaleah', '115.081619,-1.834848', '6204080'),
('6204080007', 'Lampeong', '115.106392,-1.817995', '6204080'),
('6204080008', 'Bararawa', '115.058434,-1.829996', '6204080'),
('6204080009', 'Bambulung', '115.04129,-1.83666', '6204080'),
('6204080010', 'Lebo', '115.115707,-1.789554', '6204080'),
('6204110001', 'Telang Baru', '114.94838,-2.252538', '6204110'),
('6204110002', 'Juru Banu', '114.981384,-2.265234', '6204110'),
('6204110009', 'Murutuwu', '115.074776,-2.129826', '6204110'),
('6204110010', 'Telang Siong', '115.007431,-2.116937', '6204110'),
('6204110011', 'Tampulangit', '114.951492,-2.087364', '6204110'),
('6204110012', 'Maipe', '115.048035,-2.07537', '6204110'),
('6204110013', 'Balawa', '115.071045,-2.051018', '6204110'),
('6205010001', 'Pepas', '114.800438,-1.31839', '6205010'),
('6205010004', 'Montalat I', '114.910667,-1.354951', '6205010'),
('6205010005', 'Montalat II', '114.896843,-1.340017', '6205010'),
('6205010006', 'Sikan', '114.861755,-1.294834', '6205010'),
('6205010007', 'Rubei', '114.823997,-1.286174', '6205010'),
('6205010008', 'Ruji', '114.772209,-1.286693', '6205010'),
('6205010009', 'Paring Lahung', '114.81118,-1.257248', '6205010'),
('6205010010', 'Kamawen', '114.832596,-1.205043', '6205010'),
('6205020001', 'Malungai', '114.946434,-1.302678', '6205020'),
('6205020002', 'Rarawa', '114.970184,-1.316038', '6205020'),
('6205020003', 'Ketapang', '114.989975,-1.318018', '6205020'),
('6205020004', 'Walur', '115.006302,-1.299214', '6205020'),
('6205020005', 'Baliti', '115.025604,-1.294266', '6205020'),
('6205020006', 'Majangkan', '115.078552,-1.302678', '6205020'),
('6205050001', 'Lemo I', '114.826675,-1.035317', '6205050'),
('6205050002', 'Buntok Baru', '114.896545,-1.14681', '6205050'),
('6205050003', 'Butong', '114.886902,-1.108584', '6205050'),
('6205050004', 'Liang Naga', '115.001457,-1.002425', '6205050'),
('6205050005', 'Sabuh', '115.062561,-0.999768', '6205050'),
('6205050006', 'Hajak', '114.970711,-1.012293', '6205050'),
('6205050007', 'Bintang Ninggi II', '114.879395,-1.091079', '6205050'),
('6205050008', 'Bintang Ninggi I', '114.890472,-1.097152', '6205050'),
('6205050010', 'Lemo II', '114.833649,-1.024721', '6205050'),
('6205050011', 'Pendreh', '114.856232,-0.976203', '6205050'),
('6205050014', 'Jambu', '114.922134,-0.968647', '6205050'),
('6205060001', 'Karamuan', '114.891068,-0.783877', '6205060'),
('6205060002', 'Benao Hulu', '114.894882,-0.773277', '6205060'),
('6205060003', 'Benao Hilir', '114.903786,-0.763101', '6205060'),
('6205060004', 'Teluk Malewai', '114.911415,-0.773701', '6205060'),
('6205060006', 'Jangkang Lama', '114.91481,-0.789389', '6205060'),
('6205060007', 'Nihan Hulu', '114.904633,-0.806773', '6205060'),
('6205060008', 'Nihan Hilir', '114.894035,-0.805077', '6205060'),
('6205060009', 'Jangkang Baru', '114.908875,-0.813133', '6205060'),
('6205060010', 'Mukut', '114.90506,-0.869101', '6205060'),
('6205060011', 'Ipu', '114.911842,-0.912773', '6205060'),
('6205060013', 'Lahei I', '114.943214,-0.894541', '6205060'),
('6205060014', 'Juju Baru', '114.997917,-0.850445', '6205060'),
('6205060015', 'Muara Bakah', '114.952126,-0.835605', '6205060'),
('6205060016', 'Luwe Hilir', '114.942368,-0.828821', '6205060'),
('6205060017', 'Luwe Hulu', '114.934311,-0.798293', '6205060'),
('6205060018', 'Muara Inu', '115.036072,-0.790237', '6205060'),
('6205060019', 'Bengahon', '115.084831,-0.752501', '6205060'),
('6205070007', 'Makunjung', '114.859795,-0.705331', '6205070'),
('6205070008', 'Bumban Tuhup', '114.862411,-0.722339', '6205070'),
('6205080002', 'Masalan', '114.392166,-0.741377', '6205080'),
('6205080003', 'Batu Putih', '114.434639,-0.741767', '6205080'),
('6205080004', 'Mangkahui', '114.457832,-0.738703', '6205080'),
('6205080005', 'Panuut', '114.491966,-0.749206', '6205080'),
('6205080006', 'Muara Untu', '114.544044,-0.740891', '6205080'),
('6205080007', 'Muara Jaan', '114.569862,-0.745268', '6205080'),
('6205080008', 'Bahitom', '114.608818,-0.718572', '6205080'),
('6205080009', 'Danau Usung', '114.629387,-0.67656', '6205080'),
('6205080011', 'Beriwit', '114.375854,-0.733471', '6205080'),
('6205090002', 'Sungai Gula', '114.174248,-0.745825', '6205090'),
('6205090005', 'Muara Bakanon', '114.362022,-0.754719', '6205090'),
('6205090006', 'Purnama', '114.325455,-0.709752', '6205090'),
('6205090007', 'Tumbang Lahung', '114.289871,-0.702834', '6205090'),
('6205090008', 'Muara Babuat', '114.267639,-0.687021', '6205090'),
('6205090009', 'Juking Sopan', '114.229095,-0.677632', '6205090'),
('6205090018', 'UPT Sei Bakanon', '114.298721,-0.8045', '6205090'),
('6271010001', 'Kereng Bangkirai', '113.912567,-2.304959', '6271010'),
('6271010002', 'Kalampangan', '114.006477,-2.282552', '6271010'),
('6271010003', 'Bereng Bengkel', '114.018524,-2.262959', '6271010'),
('6271010004', 'Pahandut', '113.926888,-2.22096', '6271010'),
('6271010005', 'Panarung', '113.928078,-2.221713', '6271010'),
('6271010006', 'Langkai', '113.924934,-2.221849', '6271010'),
('6271010007', 'Menteng', '113.9263,-2.222395', '6271010'),
('6271010008', 'Palangka', '113.924805,-2.220894', '6271010'),
('6271010009', 'Tumbang Rungan', '113.912567,-2.173233', '6271010'),
('6271010011', 'Peteuk Katimpun', '113.870567,-2.139824', '6271010'),
('6271020001', 'Marang', '113.804169,-2.065383', '6271020'),
('6271020002', 'Tumbang Tahai', '113.791275,-2.041144', '6271020'),
('6271020003', 'Banturung', '113.765297,-2.001224', '6271020'),
('6271020004', 'Tangkiling', '113.758209,-1.986678', '6271020'),
('6271020005', 'Sei Gohong', '113.747765,-1.971012', '6271020'),
('6271020006', 'Kanarakan', '113.761192,-1.89716', '6271020'),
('6271020007', 'Petuk Bukit', '113.726883,-1.867321', '6271020'),
('6271020008', 'Panjehang', '113.713173,-1.76758', '6271020'),
('6271020009', 'Petuk Barunai', '113.706734,-1.740232', '6271020'),
('6271020010', 'Mungku Baru', '113.696442,-1.663013', '6271020');

-- --------------------------------------------------------

--
-- Table structure for table `sentitems`
--

CREATE TABLE IF NOT EXISTS `sentitems` (
  `UpdatedInDB` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `InsertIntoDB` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `SendingDateTime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `DeliveryDateTime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `Text` text NOT NULL,
  `DestinationNumber` varchar(20) NOT NULL DEFAULT '',
  `Coding` enum('Default_No_Compression','Unicode_No_Compression','8bit','Default_Compression','Unicode_Compression') NOT NULL DEFAULT '8bit',
  `UDH` text NOT NULL,
  `SMSCNumber` varchar(20) NOT NULL DEFAULT '',
  `Class` int(11) NOT NULL DEFAULT '-1',
  `TextDecoded` varchar(160) NOT NULL DEFAULT '',
  `ID` bigint(20) unsigned NOT NULL DEFAULT '0',
  `SenderID` text NOT NULL,
  `SequencePosition` int(11) NOT NULL DEFAULT '1',
  `Status` enum('SendingOK','SendingOKNoReport','SendingError','DeliveryOK','DeliveryFailed','DeliveryPending','DeliveryUnknown','Error') NOT NULL DEFAULT 'SendingOK',
  `StatusError` int(11) NOT NULL DEFAULT '-1',
  `TPMR` int(11) NOT NULL DEFAULT '-1',
  `RelativeValidity` int(11) NOT NULL DEFAULT '-1',
  `CreatorID` text NOT NULL,
  `id_folder` int(11) NOT NULL DEFAULT '3',
  `is_broadcast` tinyint(1) NOT NULL DEFAULT '0',
  `is_forward` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sms_used`
--

CREATE TABLE IF NOT EXISTS `sms_used` (
  `id_sms_used` int(11) NOT NULL AUTO_INCREMENT,
  `sms_date` date NOT NULL,
  `id_user` int(11) NOT NULL,
  `sms_count` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_sms_used`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id_user` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(12) NOT NULL,
  `realname` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone_number` varchar(15) NOT NULL,
  `email_id` varchar(64) NOT NULL,
  `level` enum('admin','user') NOT NULL DEFAULT 'user',
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `phone_number` (`phone_number`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `username`, `realname`, `password`, `phone_number`, `email_id`, `level`) VALUES
(1, 'admin', 'Administrator', 'ae48a2f3c808496a66e061139acdcd7c0c7c5d86', '+6285643224687', 'redaksi@buruhmigran.or.id', 'admin'),
(2, 'yossysuparyo', 'Yossy Suparyo', '90270988777fa12c6ca1becef57bb3dc0c6bb402', '+6281226993732', '', 'admin'),
(3, 'khayate', 'Muhammad Khayat', 'ae48a2f3c808496a66e061139acdcd7c0c7c5d86', '+6281914942468', '', 'admin'),
(4, 'ibad', 'M Ibad', 'ae48a2f3c808496a66e061139acdcd7c0c7c5d86', '+628562555294', '', 'user'),
(5, 'bondan', 'Bondan', 'bb77569579dabd23ba3b24aeed64c87e7fc7bb4c', '+628174853092', '', 'user');

-- --------------------------------------------------------

--
-- Table structure for table `user_folders`
--

CREATE TABLE IF NOT EXISTS `user_folders` (
  `id_folder` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `id_user` int(11) NOT NULL,
  `is_global` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_folder`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `user_folders`
--

INSERT INTO `user_folders` (`id_folder`, `name`, `id_user`, `is_global`) VALUES
(1, 'inbox', 0, 0),
(2, 'outbox', 0, 0),
(3, 'sent_items', 0, 0),
(4, 'draft', 0, 0),
(5, 'Trash', 0, 0),
(6, 'LAPOR', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_group`
--

CREATE TABLE IF NOT EXISTS `user_group` (
  `id_group` int(11) NOT NULL AUTO_INCREMENT,
  `id_pbk` int(11) NOT NULL,
  `id_pbk_groups` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  PRIMARY KEY (`id_group`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_inbox`
--

CREATE TABLE IF NOT EXISTS `user_inbox` (
  `id_inbox` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `trash` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_inbox`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_outbox`
--

CREATE TABLE IF NOT EXISTS `user_outbox` (
  `id_outbox` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_folder` int(11) DEFAULT NULL,
  `is_broadcast` tinyint(1) NOT NULL DEFAULT '0',
  `is_forward` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_outbox`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_sentitems`
--

CREATE TABLE IF NOT EXISTS `user_sentitems` (
  `id_sentitems` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `trash` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_sentitems`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_settings`
--

CREATE TABLE IF NOT EXISTS `user_settings` (
  `id_user` int(11) NOT NULL,
  `theme` varchar(10) NOT NULL DEFAULT 'blue',
  `signature` varchar(50) NOT NULL,
  `permanent_delete` enum('true','false') NOT NULL DEFAULT 'false',
  `paging` int(2) NOT NULL DEFAULT '10',
  `bg_image` varchar(50) NOT NULL,
  `delivery_report` enum('default','yes','no') NOT NULL DEFAULT 'default',
  `email_forward` enum('true','false') NOT NULL DEFAULT 'false',
  `language` varchar(20) NOT NULL DEFAULT 'english',
  `conversation_sort` enum('asc','desc') NOT NULL DEFAULT 'asc',
  PRIMARY KEY (`id_user`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_settings`
--

INSERT INTO `user_settings` (`id_user`, `theme`, `signature`, `permanent_delete`, `paging`, `bg_image`, `delivery_report`, `email_forward`, `language`, `conversation_sort`) VALUES
(1, 'green', 'false;-[www.borneoclimate.info]-', 'false', 10, 'true;background.jpg', 'default', 'false', 'bahasa', 'desc'),
(3, 'blue', 'false;', 'false', 20, 'true;background.jpg', 'default', 'false', 'english', 'asc'),
(5, 'blue', 'false;', 'false', 20, 'true;background.jpg', 'default', 'false', 'bahasa', 'asc'),
(4, 'blue', 'false;', 'false', 20, 'true;background.jpg', 'default', 'false', 'english', 'asc'),
(7, 'blue', 'false;', 'false', 20, 'true;background.jpg', 'default', 'false', 'english', 'asc'),
(6, 'blue', 'false;', 'false', 20, 'true;background.jpg', 'default', 'false', 'english', 'asc'),
(8, 'blue', 'false;', 'false', 20, 'true;background.jpg', 'default', 'false', 'english', 'asc'),
(9, 'blue', 'false;', 'false', 20, 'true;background.jpg', 'default', 'false', 'english', 'asc'),
(12, 'blue', 'false;', 'false', 20, 'true;background.jpg', 'default', 'false', 'english', 'asc'),
(13, 'blue', 'false;', 'false', 20, 'true;background.jpg', 'default', 'false', 'english', 'asc'),
(14, 'blue', 'false;', 'false', 20, 'true;background.jpg', 'default', 'false', 'english', 'asc'),
(15, 'blue', 'false;', 'false', 20, 'true;background.jpg', 'default', 'false', 'english', 'asc'),
(16, 'blue', 'false;', 'false', 20, 'true;background.jpg', 'default', 'false', 'english', 'asc'),
(18, 'blue', 'false;', 'false', 20, 'true;background.jpg', 'default', 'false', 'english', 'asc'),
(21, 'blue', 'false;', 'false', 20, 'true;background.jpg', 'default', 'false', 'english', 'asc'),
(24, 'blue', 'false;', 'false', 20, 'true;background.jpg', 'default', 'false', 'english', 'asc'),
(25, 'blue', 'false;', 'false', 20, 'true;background.jpg', 'default', 'false', 'english', 'asc'),
(26, 'blue', 'false;', 'false', 20, 'true;background.jpg', 'default', 'false', 'english', 'asc'),
(27, 'blue', 'false;', 'false', 20, 'true;background.jpg', 'default', 'false', 'english', 'asc'),
(28, 'blue', 'false;', 'false', 20, 'true;background.jpg', 'default', 'false', 'english', 'asc'),
(29, 'blue', 'false;', 'false', 20, 'true;background.jpg', 'default', 'false', 'english', 'asc'),
(30, 'blue', 'false;', 'false', 20, 'true;background.jpg', 'default', 'false', 'english', 'asc'),
(31, 'blue', 'false;', 'false', 20, 'true;background.jpg', 'default', 'false', 'english', 'asc'),
(32, 'blue', 'false;', 'false', 20, 'true;background.jpg', 'default', 'false', 'english', 'asc'),
(33, 'blue', 'false;', 'false', 20, 'true;background.jpg', 'default', 'false', 'english', 'asc'),
(34, 'blue', 'false;', 'false', 20, 'true;background.jpg', 'default', 'false', 'english', 'asc'),
(35, 'blue', 'false;', 'false', 20, 'true;background.jpg', 'default', 'false', 'english', 'asc'),
(36, 'blue', 'false;', 'false', 20, 'true;background.jpg', 'default', 'false', 'english', 'asc'),
(37, 'blue', 'false;', 'false', 20, 'true;background.jpg', 'default', 'false', 'english', 'asc'),
(38, 'blue', 'false;', 'false', 20, 'true;background.jpg', 'default', 'false', 'english', 'asc'),
(39, 'blue', 'false;', 'false', 20, 'true;background.jpg', 'default', 'false', 'english', 'asc'),
(40, 'blue', 'false;', 'false', 20, 'true;background.jpg', 'default', 'false', 'english', 'asc'),
(41, 'blue', 'false;', 'false', 20, 'true;background.jpg', 'default', 'false', 'english', 'asc'),
(42, 'blue', 'false;', 'false', 20, 'true;background.jpg', 'default', 'false', 'english', 'asc'),
(49, 'blue', 'false;', 'false', 20, 'true;background.jpg', 'default', 'false', 'english', 'asc'),
(52, 'blue', 'false;', 'false', 20, 'true;background.jpg', 'default', 'false', 'english', 'asc'),
(53, 'blue', 'false;', 'false', 20, 'true;background.jpg', 'default', 'false', 'english', 'asc');

-- --------------------------------------------------------

--
-- Table structure for table `user_templates`
--

CREATE TABLE IF NOT EXISTS `user_templates` (
  `id_template` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `Name` varchar(64) NOT NULL,
  `Message` text NOT NULL,
  PRIMARY KEY (`id_template`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `user_templates`
--

INSERT INTO `user_templates` (`id_template`, `id_user`, `Name`, `Message`) VALUES
(1, 1, 'attention', 'Selamat malam kepada rekan-rekan borneoclimate.info semua_ \nKami beritahukan bahwa layanan sms Kami berganti nomer menjadi \n085 7000 82 004');
SET FOREIGN_KEY_CHECKS=1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
