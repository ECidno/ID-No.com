/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19  Distrib 10.5.29-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: idno_combined
-- ------------------------------------------------------
-- Server version	10.5.29-MariaDB-0+deb11u1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `contact`
--

DROP TABLE IF EXISTS `contact`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `contact` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `person_id` int(11) DEFAULT NULL,
  `contactname` varchar(100) DEFAULT NULL,
  `contactname_show` tinyint(2) NOT NULL DEFAULT 1,
  `telefon` varchar(100) NOT NULL,
  `telefon_show` tinyint(2) NOT NULL DEFAULT 1,
  `beziehung` varchar(100) NOT NULL,
  `beziehung_show` tinyint(2) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23100 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `doctrine_migration_versions`
--

DROP TABLE IF EXISTS `doctrine_migration_versions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ext_log_entries`
--

DROP TABLE IF EXISTS `ext_log_entries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `ext_log_entries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `action` varchar(32) NOT NULL,
  `severity` int(11) NOT NULL DEFAULT 0,
  `object_class` varchar(255) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `details` longtext DEFAULT NULL COMMENT '(DC2Type:array)',
  `logged_at` datetime NOT NULL,
  `object_id` varchar(64) DEFAULT NULL,
  `version` int(11) NOT NULL,
  `data` longtext DEFAULT NULL COMMENT '(DC2Type:array)',
  PRIMARY KEY (`id`),
  KEY `log_class_lookup_idx` (`object_class`) USING BTREE,
  KEY `log_date_lookup_idx` (`logged_at`) USING BTREE,
  KEY `log_user_lookup_idx` (`username`) USING BTREE,
  KEY `log_version_lookup_idx` (`object_id`,`object_class`,`version`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1425 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `image_file`
--

DROP TABLE IF EXISTS `image_file`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `image_file` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created_by_id` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `tags` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT '(DC2Type:json)' CHECK (json_valid(`tags`)),
  `width` int(11) NOT NULL DEFAULT 0,
  `height` int(11) NOT NULL DEFAULT 0,
  `orientation` int(11) NOT NULL DEFAULT 1,
  `direction` int(11) NOT NULL DEFAULT 0,
  `preview_base64` mediumtext DEFAULT NULL,
  `create_date` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  `file_name` varchar(180) NOT NULL,
  `original_filename` varchar(180) NOT NULL,
  `file_extension` varchar(10) DEFAULT NULL,
  `mime_type` varchar(50) DEFAULT NULL,
  `file_size` int(11) NOT NULL DEFAULT 0,
  `content_hash` varchar(40) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_7EA5DC8ED7DF1668` (`file_name`),
  KEY `IDX_7EA5DC8EB03A8386` (`created_by_id`),
  CONSTRAINT `FK_7EA5DC8EB03A8386` FOREIGN KEY (`created_by_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `items`
--

DROP TABLE IF EXISTS `items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `items` (
  `id` int(10) DEFAULT NULL,
  `no_status` varchar(12) DEFAULT NULL,
  `nutzer_id` int(10) DEFAULT NULL,
  `person_id` int(11) DEFAULT NULL,
  `artikel` int(10) DEFAULT NULL,
  `anbringung` text NOT NULL,
  `id_no` char(20) DEFAULT NULL,
  `kampagne` int(11) DEFAULT NULL,
  `update_anzahl` int(4) DEFAULT NULL,
  `stempel` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `registriert_datum` datetime NOT NULL,
  `aktiviert_datum` datetime DEFAULT NULL,
  `last_change_datum` datetime DEFAULT NULL,
  KEY `items_last_change_datum_IDX` (`last_change_datum`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `messenger_messages`
--

DROP TABLE IF EXISTS `messenger_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `messenger_messages` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `body` longtext NOT NULL,
  `headers` longtext NOT NULL,
  `queue_name` varchar(190) NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `available_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `delivered_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`),
  KEY `IDX_75EA56E016BA31DB` (`delivered_at`) USING BTREE,
  KEY `IDX_75EA56E0E3BD61CE` (`available_at`) USING BTREE,
  KEY `IDX_75EA56E0FB7336F0` (`queue_name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `nutzer`
--

DROP TABLE IF EXISTS `nutzer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `nutzer` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `status` varchar(12) NOT NULL,
  `anrede` varchar(10) DEFAULT NULL,
  `vorname` varchar(100) DEFAULT NULL,
  `nachname` varchar(100) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `sprache` varchar(2) NOT NULL DEFAULT 'de',
  `freigabe` varchar(5) NOT NULL DEFAULT 'nein',
  `sichtbar` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Wenn dieser Wert 0 ist, sind die Daten nicht abrufbar.',
  `passwort` varchar(100) NOT NULL,
  `stempel` timestamp NOT NULL DEFAULT current_timestamp(),
  `registriert_datum` datetime NOT NULL,
  `aktiviert_datum` datetime DEFAULT NULL,
  `last_change_datum` datetime NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `gesperrt` varchar(5) NOT NULL DEFAULT 'nein',
  `login_fehler` smallint(1) NOT NULL DEFAULT 0,
  `gesperrt_anzahl` smallint(1) NOT NULL DEFAULT 0,
  `gesperrt_datum` int(11) NOT NULL DEFAULT 0,
  `logout_ok` varchar(5) NOT NULL DEFAULT 'nein',
  `source` int(11) NOT NULL DEFAULT 1,
  `profil_ok` varchar(5) NOT NULL DEFAULT 'ja',
  `inaktiv` varchar(5) NOT NULL DEFAULT 'nein',
  `no_login` tinyint(1) DEFAULT 0,
  `quick_registration` int(11) NOT NULL DEFAULT 0,
  `send_information` tinyint(1) NOT NULL DEFAULT 0,
  `information_send_datum` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `index_email` (`email`) USING BTREE,
  KEY `index_nachname` (`nachname`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=13146 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `nutzer_auth`
--

DROP TABLE IF EXISTS `nutzer_auth`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `nutzer_auth` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nutzer_id` int(11) NOT NULL,
  `auth` varchar(40) NOT NULL,
  `time` int(11) NOT NULL,
  `status` varchar(5) NOT NULL DEFAULT 'neu',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13934 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pass_entry_allergy`
--

DROP TABLE IF EXISTS `pass_entry_allergy`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `pass_entry_allergy` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sorting` int(11) NOT NULL,
  `person_id` int(11) NOT NULL,
  `category` varchar(255) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `last_change` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pass_entry_condition`
--

DROP TABLE IF EXISTS `pass_entry_condition`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `pass_entry_condition` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sorting` int(11) NOT NULL,
  `person_id` int(11) NOT NULL,
  `category` varchar(255) DEFAULT NULL,
  `title` text DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `last_change` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pass_entry_medication`
--

DROP TABLE IF EXISTS `pass_entry_medication`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `pass_entry_medication` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sorting` int(11) NOT NULL,
  `person_id` int(11) NOT NULL,
  `ingredient` varchar(255) DEFAULT NULL,
  `trade_name` varchar(255) DEFAULT NULL,
  `dosage` varchar(255) DEFAULT NULL,
  `consumption` varchar(255) DEFAULT NULL,
  `emergency_notes` text DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `last_change` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `person`
--

DROP TABLE IF EXISTS `person`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `person` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) DEFAULT 0,
  `nutzer_id` int(11) NOT NULL,
  `status` varchar(25) NOT NULL DEFAULT 'neu',
  `email` varchar(100) DEFAULT NULL,
  `anrede` varchar(5) NOT NULL,
  `vorname` varchar(100) NOT NULL,
  `nachname` varchar(100) NOT NULL,
  `strasse` varchar(100) DEFAULT NULL,
  `strasse_show` tinyint(2) NOT NULL DEFAULT 1,
  `plz` varchar(13) DEFAULT NULL,
  `ort` varchar(100) DEFAULT NULL,
  `ort_show` tinyint(2) NOT NULL DEFAULT 1,
  `zusatz` varchar(100) DEFAULT NULL,
  `zusatz_show` tinyint(2) DEFAULT 1,
  `land` varchar(100) DEFAULT 'DE',
  `geburtsdatum_tag` int(2) DEFAULT NULL,
  `geburtsdatum_monat` int(2) DEFAULT NULL,
  `geburtsdatum_jahr` int(4) DEFAULT NULL,
  `telefon_land` varchar(6) NOT NULL DEFAULT '+49',
  `telefon_vorwahl` varchar(10) DEFAULT NULL,
  `telefon` varchar(30) DEFAULT NULL,
  `telefon_show` tinyint(2) NOT NULL DEFAULT 1,
  `mobile_land` varchar(6) NOT NULL DEFAULT '+49',
  `mobile_vorwahl` varchar(10) DEFAULT NULL,
  `mobile` varchar(30) DEFAULT NULL,
  `mobile_show` tinyint(1) NOT NULL DEFAULT 1,
  `blutgruppe` varchar(30) DEFAULT NULL,
  `blutgruppe_show` tinyint(2) NOT NULL DEFAULT 1,
  `erkrankungen` mediumtext DEFAULT NULL,
  `erkrankungen_show` tinyint(1) NOT NULL DEFAULT 1,
  `medikamente` text DEFAULT NULL,
  `medikamente_show` tinyint(2) NOT NULL DEFAULT 1,
  `allergieen` text DEFAULT NULL,
  `allergieen_show` tinyint(2) NOT NULL DEFAULT 1,
  `gewicht` varchar(10) DEFAULT NULL,
  `gewicht_einheit` varchar(5) NOT NULL DEFAULT 'kg',
  `gewicht_show` tinyint(2) NOT NULL DEFAULT 1,
  `groesse` varchar(10) DEFAULT NULL,
  `groesse_einheit` varchar(5) NOT NULL DEFAULT 'cm',
  `groesse_show` tinyint(2) NOT NULL DEFAULT 1,
  `krankenversicherung` varchar(100) DEFAULT NULL,
  `krankenversicherung_show` tinyint(2) NOT NULL DEFAULT 1,
  `versicherungsnummer` varchar(100) DEFAULT NULL,
  `versicherungsnummer_show` tinyint(2) NOT NULL DEFAULT 1,
  `zusatzversicherung` text DEFAULT NULL,
  `zusatzversicherung_show` tinyint(2) NOT NULL DEFAULT 1,
  `organspender` tinyint(2) DEFAULT NULL,
  `organspender_show` tinyint(2) NOT NULL DEFAULT 1,
  `patientenverf` tinyint(2) DEFAULT NULL,
  `patientenverf_show` tinyint(2) NOT NULL DEFAULT 1,
  `weitereangaben` text DEFAULT NULL,
  `weitereangaben_show` tinyint(2) NOT NULL DEFAULT 1,
  `sprache` varchar(3) NOT NULL DEFAULT 'de',
  `registriert_datum` datetime NOT NULL,
  `last_change_datum` datetime NOT NULL,
  `operations` text DEFAULT NULL,
  `operations_show` tinyint(1) NOT NULL DEFAULT 1,
  `operations_active` tinyint(1) NOT NULL DEFAULT 1,
  `patientenverf_comment` text DEFAULT NULL,
  `important_note` text DEFAULT NULL,
  `important_note_show` tinyint(1) NOT NULL DEFAULT 1,
  `pacemaker` tinyint(1) NOT NULL DEFAULT 0,
  `pacemaker_comment` text DEFAULT NULL,
  `pacemaker_show` tinyint(1) NOT NULL DEFAULT 1,
  `pregnancy` tinyint(1) NOT NULL DEFAULT 0,
  `pregnancy_comment` text DEFAULT NULL,
  `pregnancy_show` tinyint(1) NOT NULL DEFAULT 1,
  `conditions_active` tinyint(1) NOT NULL DEFAULT 1,
  `medications_active` tinyint(1) NOT NULL DEFAULT 1,
  `allergies_active` tinyint(1) NOT NULL DEFAULT 1,
  `reanimation` varchar(32) DEFAULT NULL,
  `reanimation_comment` text DEFAULT NULL,
  `reanimation_show` tinyint(1) NOT NULL DEFAULT 1,
  `organspender_comment` text DEFAULT NULL,
  `health_care_proxy` tinyint(1) NOT NULL DEFAULT 0,
  `health_care_proxy_comment` text DEFAULT NULL,
  `health_care_proxy_show` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=13127 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `person_images`
--

DROP TABLE IF EXISTS `person_images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `person_images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `person_id` int(11) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'neu',
  `bild` varchar(50) NOT NULL,
  `bild_show` tinyint(2) NOT NULL DEFAULT 1,
  `height` int(11) NOT NULL,
  `width` int(11) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `person_id` (`person_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=4127 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pwd_vergessen`
--

DROP TABLE IF EXISTS `pwd_vergessen`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `pwd_vergessen` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL,
  `code` varchar(15) NOT NULL,
  `stempel` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1144 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `reset_password_request`
--

DROP TABLE IF EXISTS `reset_password_request`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `reset_password_request` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `selector` varchar(20) NOT NULL,
  `hashed_token` varchar(100) NOT NULL,
  `requested_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `expires_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`),
  KEY `IDX_7CE748AA76ED395` (`user_id`),
  CONSTRAINT `FK_7CE748AA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `statistics_entry`
--

DROP TABLE IF EXISTS `statistics_entry`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `statistics_entry` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `identifier` varchar(255) NOT NULL,
  `value` decimal(24,3) NOT NULL,
  `unit` varchar(12) NOT NULL,
  `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT '(DC2Type:json)' CHECK (json_valid(`data`)),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `storage`
--

DROP TABLE IF EXISTS `storage`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `storage` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT NULL,
  `identifier` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `previews` tinyint(1) NOT NULL,
  `provider_class` varchar(100) NOT NULL,
  `indexed_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_547A1B34727ACA70` (`parent_id`),
  CONSTRAINT `FK_547A1B34727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `storage` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(180) NOT NULL,
  `email` varchar(180) NOT NULL,
  `roles` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT '(DC2Type:json)' CHECK (json_valid(`roles`)),
  `password` varchar(255) NOT NULL,
  `login_error` int(11) NOT NULL DEFAULT 0,
  `last_login_at` datetime DEFAULT NULL,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `last_activity_at` datetime DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT 'created' COMMENT '(DC2Type:userstatus)',
  `end_date` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `language` varchar(3) NOT NULL,
  `start_code` varchar(12) DEFAULT NULL,
  `totp_enabled` tinyint(1) NOT NULL,
  `totp_secret` varchar(255) DEFAULT NULL,
  `trusted_version` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`),
  UNIQUE KEY `UNIQ_8D93D649F85E0677` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-10-01 15:19:52
